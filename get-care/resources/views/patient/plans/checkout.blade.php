@extends('patient_layout')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-teal-600 p-6 text-white text-center">
            <h1 class="text-3xl font-bold mb-2">Checkout for {{ strtoupper($plan->name) }} Plan</h1>
            <p class="text-lg">Complete your subscription for enhanced care.</p>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-6 mt-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Plan Details</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                    <div class="col-span-1">
                        <dt class="font-medium text-gray-600">Plan Name:</dt>
                        <dd class="text-gray-900 font-semibold">{{ strtoupper($plan->name) }}</dd>
                    </div>
                    <div class="col-span-1">
                        <dt class="font-medium text-gray-600">Price:</dt>
                        <dd class="text-gray-900 font-semibold">₱{{ number_format($plan->price, 2) }} / month</dd>
                    </div>
                    <div class="col-span-full mt-2">
                        <dt class="font-medium text-gray-600">Description:</dt>
                        <dd class="text-gray-900">{{ $plan->description }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Payment Information</h3>
                <form action="{{ route('patient.plans.process-payment', $plan->id) }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="payment_method" class="block text-gray-700 text-sm font-bold mb-2">Payment Method:</label>
                        <select name="payment_method" id="payment_method" class="shadow-sm appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('payment_method') border-red-500 @enderror">
                            <option value="">Select a payment method</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="gcash">GCash</option>
                        </select>
                        @error('payment_method')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center justify-between mt-8">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Proceed to Payment
                        </button>
                        <a href="{{ route('patient.plans') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection