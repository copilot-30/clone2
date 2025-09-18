@extends('patient_layout')

@section('content')
<div class="bg-gray-900  bg-opacity-50 text-white py-20" style="
    background-size: cover; 
    background-image: url('https://media.istockphoto.com/id/1437830105/photo/cropped-shot-of-a-female-nurse-hold-her-senior-patients-hand-giving-support-doctor-helping.jpg?s=612x612&w=0&k=20&c=oKR-00at4oXr4tY5IxzqsswaLaaPsPRkdw2MJbYHWgA='); 
    backdrop-filter: blur(10px) brightness(0) contrast(0) invert(1);
    background-blend-mode: soft-light;
        margin-top: -23px;
    ">
    
    <div class="absolute inset-0 bg-cover bg-black bg-center opacity-30" style="
        background-size: cover; 
        background-image: inherit;
    "></div>
    <div class="text-center relative" >
        <h2 class="text-5xl font-bold mb-4 text-shadow-lg shadow-gray-800" style="text-shadow: 2px 2px 4px #000000;">Quality care & flexible plans for your needs.</h2>
        <p class="text-xl mb-8 text-shadow-lg shadow-gray-800" style="text-shadow: 2px 2px 4px #000000;">Get the care that you deserved.</p>
    </div>

    <div class="w-full max-w-3xl mx-auto relative">
        
        
        @if ($currentSubscription)
            <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3 mb-6 rounded-lg shadow-lg" role="alert">
                <p class="font-bold text-lg mb-2">Current Subscription:</p>
                <p class="text-gray-800">You are currently subscribed to the <strong class="text-indigo-600">{{ $currentSubscription->plan->name }}</strong> plan.</p>
                <p class="text-gray-700"><strong>Benefits:</strong> 
                     @php
                        $benefits = explode(',', str_replace("\n", ",", e($currentSubscription->plan->description)));
                    @endphp
                    @foreach ($benefits as $benefit)
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ trim($benefit) }}
                        </li>
                    @endforeach
                </p>
                <p class="text-gray-700">Ends: {{ $currentSubscription->end_date->format('M d, Y') }}</p>
                <p class="text-gray-700">Status: <span class="uppercase font-semibold
                @if ($currentSubscription->status == 'ACTIVE')
                    text-emerald-600
                @else
                    text-red-600
                @endif
                
                ">{{ $currentSubscription->status }}</span></p>
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
</div>
    
    <div class="container mx-auto  -mt-26">
        <div class="flex flex-wrap -mx-4 justify-center gap-10">
            @foreach ($plans as $plan)
                <div class="bg-white rounded-lg shadow-xl overflow-hidden border border-gray-200 hover:border-indigo-600 hover:shadow-2xl flex flex-col justify-between md:w-1/{{ count($plans) +1}} mb-6" style="z-index:1">
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
                        @if ($currentSubscription && $currentSubscription->status == 'ACTIVE' || $pendingPlanPayment)
                        
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