@extends('patient_layout')

@section('content')
<div class="bg-gray-900 text-white py-20">
    <div class=" text-center">
        <h2 class="text-5xl font-bold mb-4">Quality care & flexible plans for your needs.</h2>
        <p class="text-xl mb-8">Get the care that you deserved.</p>
    </div>

    <div class="w-full max-w-3xl mx-auto">
        @if ($currentSubscription)
            <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3 mb-6 rounded-lg shadow-lg" role="alert">
                <p class="font-bold text-lg mb-2">Current Subscription:</p>
                <p class="text-gray-800">You are currently subscribed to the <strong class="text-indigo-600">{{ $currentSubscription->plan->name }}</strong> plan.</p>
                <p class="text-gray-700"><strong>Benefits:</strong> <pre>{{ str_replace("\n", "<br>", e($currentSubscription->plan->description)) }}</pre></p>
                <p class="text-gray-700">Ends: {{ $currentSubscription->end_date->format('M d, Y') }}</p>
                <p class="text-gray-700">Status: <span class="uppercase font-semibold text-green-700">{{ $currentSubscription->status }}</span></p>
            </div>
        @elseif ($pendingPlanPayment)
            <div class="bg-yellow-100 border-t border-b border-yellow-500 text-yellow-700 px-4 py-3 mb-6 rounded-lg shadow-lg" role="alert">
                <p class="font-bold text-lg mb-2">Pending Plan Payment</p>
                <p class="text-gray-800">You have a pending payment for a plan. <a href="{{ route('patient.payment', $pendingPlanPayment->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Click here to view details.</a></p>
            </div>
        @else
            <div class="bg-yellow-100 border-t border-b border-yellow-500 text-yellow-700 px-4 py-3 mb-6 rounded-lg shadow-lg" role="alert">
                <p class="font-bold text-lg mb-2">No Active Subscription</p>
                <p class="text-gray-800">Choose a plan below to get started!</p>
            </div>
        @endif
        </div>
    </div>
    
    <div class="container mx-auto  -mt-16">
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
    
    
    
        <div class="flex flex-wrap -mx-4 justify-center gap-10">
            @foreach ($plans as $plan)
                <div class="bg-white rounded-lg shadow-xl overflow-hidden flex flex-col justify-between md:w-1/{{ count($plans) +1}} mb-6">
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">{{strtoupper($plan->name)}}</h2>
              
                        <div class="text-4xl font-extrabold text-indigo-600 mb-6 text-center">
                            ₱{{ number_format($plan->price, 2) }} <span class="text-xl text-gray-500">/mo</span>
                        </div>
                        <ul class="text-gray-700 mb-8 space-y-3">
                            {{-- Assuming plan benefits are stored as a delimited string or can be parsed --}}
                            @php
                                $benefits = explode(',', str_replace("\n", ",", e($plan->description)));
                            @endphp
                            @foreach ($benefits as $benefit)
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    {{ trim($benefit) }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="p-8 border-t border-gray-200">
                        @if ($currentSubscription || $pendingPlanPayment)
                            <button class="block w-full text-center bg-gray-400 text-white py-3 px-4 rounded-md cursor-not-allowed text-lg font-semibold" disabled>
                                {{ $currentSubscription ? 'Current Plan' : 'Pending Approval' }}
                            </button>
                        @else
                            <a href="{{ route('patient.plans.checkout', $plan->id) }}" class="block w-full text-center bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-lg font-semibold">
                                Select Plan
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection