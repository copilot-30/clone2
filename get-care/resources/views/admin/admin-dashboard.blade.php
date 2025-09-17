@extends('admin_layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Users</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
            </div>
            <i class="fas fa-users text-4xl text-indigo-400"></i>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Doctors</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalDoctors }}</p>
            </div>
            <i class="fas fa-user-md text-4xl text-green-400"></i>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Patients</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalPatients }}</p>
            </div>
            <i class="fas fa-user-injured text-4xl text-yellow-400"></i>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Appointments</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalAppointments }}</p>
            </div>
            <i class="fas fa-calendar-check text-4xl text-red-400"></i>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Pending Appointments</p>
                <p class="text-3xl font-bold text-gray-900">{{ $pendingAppointments }}</p>
            </div>
            <i class="fas fa-hourglass-half text-4xl text-blue-400"></i>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Subscriptions</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalSubscriptions }}</p>
            </div>
            <i class="fas fa-credit-card text-4xl text-purple-400"></i>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Active Subscriptions</p>
                <p class="text-3xl font-bold text-gray-900">{{ $activeSubscriptions }}</p>
            </div>
            <i class="fas fa-toggle-on text-4xl text-teal-400"></i>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-900">₱{{ number_format($totalRevenue, 2) }}</p>
            </div>
            <i class="fas fa-money-bill-wave text-4xl text-emerald-400"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Recent Users</h2>
            @if ($recentUsers->isEmpty())
                <p class="text-gray-600">No recent users found.</p>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($recentUsers as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst(strtolower($user->role)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

 
    </div>
</div>
@endsection