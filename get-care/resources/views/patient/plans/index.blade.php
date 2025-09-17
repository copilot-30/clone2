@extends('patient_layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Available Plans</h1>

    <div class="w-full max-w-3xl mx-auto mb-6">
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

    @if ($plans->isEmpty())
        <p class="text-gray-600">No plans found.</p>
    @else
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($plans as $plan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ucfirst($plan->name) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ₱{{ number_format($plan->price, 2) }} /mo
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{-- Assuming plan benefits are stored as a delimited string or can be parsed --}}
                                @php
                                    $benefits = explode(',', str_replace("\n", ",", e($plan->description)));
                                @endphp
                                <ul class="list-disc list-inside">
                                    @foreach ($benefits as $benefit)
                                        <li>{{ trim($benefit) }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if ($currentSubscription && $currentSubscription->plan_id === $plan->id)
                                    <button class="block w-full text-center bg-gray-400 text-white py-2 px-4 rounded-md cursor-not-allowed text-sm font-semibold" disabled>
                                        Current Plan
                                    </button>
                                @elseif ($pendingPlanPayment)
                                    <button class="block w-full text-center bg-gray-400 text-white py-2 px-4 rounded-md cursor-not-allowed text-sm font-semibold" disabled>
                                        Pending Approval
                                    </button>
                                @else
                                    <a href="{{ route('patient.plans.checkout', $plan->id) }}" class="block w-full text-center bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-sm font-semibold">
                                        Select Plan
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection