@extends('patient_layout')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white text-center">
            <h1 class="text-3xl font-bold mb-2">Payment Details</h1>
            <p class="text-lg">For the {{ strtoupper($plan->name) }} Plan</p>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-6 mt-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Plan Information</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                    <div class="col-span-1">
                        <dt class="font-medium text-gray-600">Plan Name:</dt>
                        <dd class="text-gray-900 font-semibold">{{ $plan->name }}</dd>
                    </div>
                    <div class="col-span-1">
                        <dt class="font-medium text-gray-600">Price:</dt>
                        <dd class="text-gray-900 font-semibold">₱{{ number_format($plan->price, 2) }} / month</dd>
                    </div>
                    <div class="col-span-full mt-2">
                        <dt class="font-medium text-gray-600">Description:</dt>
                        <dd class="text-gray-900">
                            @php
                                $benefits = explode(',', str_replace("\n", ",", e($plan->description)));
                            @endphp
                            @foreach ($benefits as $benefit)
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    {{ trim($benefit) }}
                                </li>
                            @endforeach
                        </dd>
                    </div>
                </dl>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Payment Details</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                    <div class="col-span-1">
                        <dt class="font-medium text-gray-600">Payment Status:</dt>
                        <dd class="text-yellow-600 font-semibold uppercase">{{ $payment->status }}</dd>
                    </div>
                    <div class="col-span-1">
                        <dt class="font-medium text-gray-600">Payment By:</dt>
                        <dd class="text-gray-900">{{ $payment->user->patient->full_name }}</dd>
                    </div>
                    <div class="col-span-1">
                        <dt class="font-medium text-gray-600">Payment ID:</dt>
                        <dd class="text-gray-900">{{ $payment->id }}</dd>
                    </div>
                    <div class="col-span-1">
                        <dt class="font-medium text-gray-600">User ID:</dt>
                        <dd class="text-gray-900">{{ $payment->user_id }}</dd>
                    </div>
                    <div class="col-span-1">
                        <dt class="font-medium text-gray-600">Payment Method:</dt>
                        <dd class="text-gray-900">{{ $payment->payment_method }}</dd>
                    </div>
                    @if($payment->transaction_id)
                    <div class="col-span-1">
                        <dt class="font-medium text-gray-600">Transaction ID:</dt>
                        <dd class="text-gray-900">{{ $payment->transaction_id }}</dd>
                    </div>
                    @endif
                    @if ($payment->payment_date)
                    <div class="col-span-1">
                        <dt class="font-medium text-gray-600">Payment Date:</dt>
                        <dd class="text-gray-900">{{ $payment->payment_date->format('M d, Y H:i A') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <div class="mt-8 pt-6 border-t text-center">
                <p class="text-lg text-gray-700 mb-4 font-semibold">Your payment is currently being processed. Please wait for administrator approval.</p>
                <a href="{{ route('patient.plans') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fa fa-arrow-left mr-2"></i> Back to Plans
                </a>
            </div>
        </div>
    </div>
</div>
@endsection