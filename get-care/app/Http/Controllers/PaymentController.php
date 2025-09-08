<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Subscription;
use Illuminate\Http\Request; // Keep only one instance of this
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; // Assuming Laravel's HTTP client

class PaymentController extends Controller
{
    /**
     * Initiate a payment for a subscription.
     * This method would typically be called by the frontend when a user selects a payment method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|uuid|exists:patients,id',
            'subscription_id' => 'required|uuid|exists:subscriptions,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:gcash,card_maya,bank_transfer',
            'bank_name' => 'required_if:payment_method,bank_transfer|string|max:255',
            'account_name' => 'required_if:payment_method,bank_transfer|string|max:255',
            'account_number' => 'required_if:payment_method,bank_transfer|string|max:255',
        ]);

        $subscription = Subscription::find($request->subscription_id);
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        // Initialize PayMongo client (dummy implementation)
        $secretKey = env('PAYMONGO_SECRET_KEY');
        $publicKey = env('PAYMONGO_PUBLIC_KEY');

        try {
            // This is a simplified representation of calling a payment gateway API
            // In a real scenario, this would involve sending requests to PayMongo API
            // and handling their specific response formats and redirects.
            $response = Http::withBasicAuth($secretKey, '')
                            ->post('https://api.paymongo.com/v1/sources', [
                                'data' => [
                                    'attributes' => [
                                        'amount' => $request->amount * 100, // Amount in cents
                                        'redirect' => [
                                            'success' => config('app.url') . '/payment/success',
                                            'failed' => config('app.url') . '/payment/failed',
                                        ],
                                        'type' => $request->payment_method, // e.g., 'gcash', 'card'
                                        'currency' => 'PHP',
                                    ]
                                ]
                            ]);

            if ($response->successful()) {
                $source = $response->json()['data']['attributes'];
                
                // Create a pending payment record
                $payment = Payment::create([
                    'user_id' => $request->patient_id,
                    'payable_type' => 'App\Subscription',
                    'payable_id' => $subscription->id,
                    'amount' => $request->amount,
                    'currency' => 'PHP',
                    'payment_method' => $request->payment_method,
                    'transaction_id' => $source['id'], // PayMongo Source ID
                    'status' => 'pending',
                    'payment_date' => now(),
                ]);

                return response()->json([
                    'message' => 'Payment initiated',
                    'payment_url' => $source['redirect']['checkout_url'] ?? null, // Redirect URL for GCash/Maya
                    'payment' => $payment
                ], 200);
            } else {
                Log::error('PayMongo Initiation Error: ' . $response->body());
                return response()->json(['message' => 'Payment initiation failed', 'details' => $response->json()], 500);
            }

        } catch (\Exception $e) {
            Log::error('Payment initiation exception: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred during payment initiation.'], 500);
        }
    }

    /**
     * Handle payment webhook from payment gateway (e.g., PayMongo).
     * This endpoint should be publicly accessible for the payment gateway to send notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleWebhook(Request $request)
    {
        // In a real scenario, you'd verify the webhook signature first
        // $signature = $request->header('X-Paymongo-Webhook-Signature');
        // if (! $this->verifyWebhookSignature($request->getContent(), $signature, env('PAYMONGO_WEBHOOK_SECRET'))) {
        //     return response()->json(['message' => 'Invalid webhook signature'], 400);
        // }

        $event = $request->json('data.attributes.type');
        $data = $request->json('data.attributes.data.attributes');

        switch ($event) {
            case 'source.chargeable':
                // Source is ready to be charged
                // You would typically create a charge here using the source ID
                // For simplicity, we'll assume the payment is considered successful at this point
                Log::info("PayMongo Webhook: Source Chargeable - " . $data['id']);
                break;

            case 'payment.paid':
                // Payment has been successfully paid
                $transactionId = $data['source']['id'] ?? $data['id']; // Source ID or Payment ID
                $status = $data['status']; // 'paid'

                $payment = Payment::where('transaction_id', $transactionId)->first();

                if ($payment) {
                    $payment->status = $status;
                    $payment->save();

                    // Activate subscription
                    if ($payment->payable_type === 'App\Subscription' && $payment->payable) {
                        $subscription = $payment->payable;
                        $subscription->status = 'active';
                        $subscription->start_date = now();
                        // Set end date based on plan (e.g., +1 month)
                        $subscription->end_date = now()->addMonth();
                        $subscription->save();
                    }
                    Log::info("Payment Paid for transaction: " . $transactionId);
                } else {
                    Log::warning("Payment Paid webhook received for unknown transaction: " . $transactionId);
                }
                break;

            case 'payment.failed':
                // Payment failed
                $transactionId = $data['source']['id'] ?? $data['id'];
                $status = $data['status']; // 'failed'
                $payment = Payment::where('transaction_id', $transactionId)->first();
                if ($payment) {
                    $payment->status = $status;
                    $payment->save();
                    Log::info("Payment Failed for transaction: " . $transactionId);
                } else {
                    Log::warning("Payment Failed webhook received for unknown transaction: " . $transactionId);
                }
                break;

            default:
                Log::info("Unhandled PayMongo Webhook Event: " . $event);
                break;
        }

        event(new AuditableEvent(null, 'payment_webhook_received', [
            'event' => $event,
            'transaction_id' => $transactionId ?? 'N/A',
            'status' => $status ?? 'N/A',
        ]));

        return response()->json(['message' => 'Webhook received'], 200);
    }

    /**
     * Track earnings for doctors based on a successful payment.
     * This method would be called internally after a payment is successfully processed.
     *
     * @param \App\Payment $payment
     * @return void
     */
    private function trackDoctorEarnings(Payment $payment)
    {
        // Example: If a payment is for a subscription that enables doctor consultations,
        // you would calculate the doctor's share here.
        // For simplicity, let's assume a fixed percentage or per-consultation fee.

        // This logic is highly dependent on the subscription model and payout structure.
        $doctor = $subscription->doctor; // Assuming subscription has a direct doctor relationship
        if ($doctor) {
            // Log or add to an earnings table for the doctor
            // This is a placeholder for actual financial logic
            Log::info("Doctor {$doctor->id} earned from subscription {$subscription->id}. Amount: {$payment->amount}");
        }

        if ($payment->payable_type === 'App\Subscription' && $payment->payable && $payment->status === 'completed') {
            $subscription = $payment->payable;
            // Assuming a simple model where doctors are paid per consultation from the subscription pool
            // or a direct share of the subscription.
            // This would likely involve more complex business logic and potentially an 'earnings' table
            // for doctors. For now, we just log it.

            Log::info("Tracking earnings for subscription ID: {$subscription->id}. Payment amount: {$payment->amount}");

            // In a real application, you'd add this to a doctor's earning balance,
            // which can then be paid out.
        }
    }

    /**
     * Initiate a payout to a doctor.
     * This would typically be called by an Admin or a scheduled job.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function initiateDoctorPayout(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|uuid|exists:doctors,id',
            'amount' => 'required|numeric|min:0',
            'payout_method' => 'required|in:bank_transfer,gcash,maya',
            'bank_name' => 'required_if:payout_method,bank_transfer|string|max:255',
            'account_name' => 'required_if:payout_method,bank_transfer|string|max:255',
            'account_number' => 'required_if:payout_method,bank_transfer|string|max:255',
            'gcash_number' => 'required_if:payout_method,gcash|string|max:255',
            'maya_number' => 'required_if:payout_method,maya|string|max:255',
        ]);

        // In a real scenario:
        // 1. Verify doctor's available balance.
        // 2. Process payout via bank transfer API or manual instruction.
        // 3. Create a Payment record with type 'payout' and link to doctor.

        Log::info("Initiating payout of {$request->amount} to Doctor ID: {$request->doctor_id} via {$request->payout_method}");

        // Create a payout record (can be in the Payments table or a separate Payouts table)
        $payout = Payment::create([
            'user_id' => $request->doctor_id, // Link to doctor's user ID
            'payable_type' => 'App\Doctor',
            'payable_id' => $request->doctor_id,
            'amount' => $request->amount,
            'currency' => 'PHP', // Or dynamic based on configuration
            'payment_method' => $request->payout_method,
            'transaction_id' => 'PAYOUT_' . uniqid(), // Generate a unique transaction ID
            'status' => 'pending', // Status will change based on actual payout process
            'payment_date' => now(),
            'metadata' => $request->except(['doctor_id', 'amount', 'payout_method']) // Store payout details
        ]);

        event(new AuditableEvent(auth()->id(), 'doctor_payout_initiated', [
            'doctor_id' => $request->doctor_id,
            'amount' => $request->amount,
            'payout_method' => $request->payout_method,
            'payout_id' => $payout->id,
        ]));

        return response()->json(['message' => 'Payout initiated successfully', 'payout_details' => $payout], 200);
    }
}
