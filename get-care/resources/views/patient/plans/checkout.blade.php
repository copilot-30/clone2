@extends('patient_layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Checkout for {{ $plan->name }} Plan</h1>

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
        <form action="{{ route('patient.plans.process-payment', $plan->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="payment_method" class="block text-gray-700 text-sm font-bold mb-2">Payment Method:</label>
                <select name="payment_method" id="payment_method" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('payment_method') border-red-500 @enderror">
                    <option value="">Select a payment method</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="gcash">GCash</option>
                </select>
                @error('payment_method')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Proceed to Payment
                </button>
                <a href="{{ route('patient.plans') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection