@extends('patient_layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Payment Details for {{ strtoupper($plan->name) }} Plan</h1>

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Plan Details</h2>
        <p class="text-gray-600 mb-2"><strong>Name:</strong> {{ $plan->name }}</p>
        <p class="text-gray-600 mb-2"><strong>Description:</strong> {{ $plan->description }}</p>
        <p class="text-gray-600 mb-4"><strong>Price:</strong> ₱{{ number_format($plan->price, 2) }}</p>

        <h3 class="text-xl font-bold text-gray-800 mb-4">Payment Information</h3>
        <p class="text-gray-600 mb-2"><strong>Payment By:</strong> 
        <span class="uppercase font-semibold text-yellow-600">{{ $payment->user->patient->full_name }}</span></p>
        <p class="text-gray-600 mb-2"><strong>Payment ID:</strong> 
        <span class="uppercase font-semibold text-yellow-600">{{ $payment->id }}</span></p>
                <p class="text-gray-600 mb-2"><strong>User ID:</strong> 
        <span class="uppercase font-semibold text-yellow-600">{{ $payment->user_id }}</span></p>
        
        <p class="text-gray-600 mb-2"><strong>Status:</strong> <span class="uppercase font-semibold text-yellow-600">{{ $payment->status }}</span></p>
        <p class="text-gray-600 mb-2"><strong>Payment Method:</strong> {{ $payment->payment_method }}</p>
        @if($payment->transaction_id)
        <p class="text-gray-600 mb-2"><strong>Transaction ID:</strong> {{ $payment->transaction_id }}</p>
        @endif
        @if ($payment->payment_date)
        <p class="text-gray-600 mb-4"><strong>Payment Date:</strong> {{ $payment->payment_date->format('M d, Y H:i A') }}</p>
        @endif
        <div class="flex items-center justify-between">
            <p class="text-gray-800 font-bold">Your payment is currently being processed. Please wait for administrator approval.</p>
            <a href="{{ route('patient.plans') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
               <i class="fa fa-arrow-left"></i> Back to Plans
            </a>
        </div>
    </div>
</div>
@endsection