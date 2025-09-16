@extends('patient_layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Available Plans</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if ($currentSubscription)
        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3 mb-6" role="alert">
            <p class="font-bold">Current Subscription:</p>
            <p>You are currently subscribed to the <strong>{{ $currentSubscription->plan->name }}</strong> plan.</p>
            <p>Ends: {{ $currentSubscription->end_date->format('M d, Y') }}</p>
            <p>Status: <span class="uppercase font-semibold">{{ $currentSubscription->status }}</span></p>
        </div>
    @else
        <div class="bg-yellow-100 border-t border-b border-yellow-500 text-yellow-700 px-4 py-3 mb-6" role="alert">
            <p class="font-bold">No Active Subscription</p>
            <p>Choose a plan below to get started!</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($plans as $plan)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h2>
                    <p class="text-gray-600 mb-4">{{ $plan->description }}</p>
                    <div class="text-3xl font-extrabold text-indigo-600 mb-4">
                        ₱{{ number_format($plan->price, 2) }}
                    </div>
                    <a href="{{ route('patient.plans.checkout', $plan->id) }}" class="block w-full text-center bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ $currentSubscription ? 'Upgrade Plan' : 'Select Plan' }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection