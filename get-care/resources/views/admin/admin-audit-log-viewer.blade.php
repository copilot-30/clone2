@extends('admin_layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Audit Log Viewer</h1>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Filter Audit Logs</h2>
        <form action="{{ route('admin.audit-logs') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="user_email" class="block text-gray-700 text-sm font-bold mb-2">User Email:</label>
                <input type="text" name="user_email" id="user_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ request('user_email') }}">
            </div>
            <div>
                <label for="action" class="block text-gray-700 text-sm font-bold mb-2">Action:</label>
                <select name="action" id="action" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">All</option>
                    @foreach ($actions as $actionType)
                        <option value="{{ $actionType }}" {{ request('action') == $actionType ? 'selected' : '' }}>{{ $actionType }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="ip_address" class="block text-gray-700 text-sm font-bold mb-2">IP Address:</label>
                <input type="text" name="ip_address" id="ip_address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ request('ip_address') }}">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Filter</button>
                <a href="{{ route('admin.audit-logs') }}" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Clear</a>
            </div>
        </form>
    </div>

    @if ($auditLogs->isEmpty())
        <p class="text-gray-600">No audit logs found.</p>
    @else
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                         <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auditable ID</th> -->
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auditable Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Old Values</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">New Values</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tags</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th> -->
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($auditLogs as $log)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
  
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->user->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->action }}</td>
                            <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->auditable_id }}</td> -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->auditable_type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <textarea class="w-full" readonly>{{ $log->old_values ? json_encode($log->old_values) : 'N/A' }}</textarea>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <textarea class="w-full" readonly>{{ $log->new_values ? json_encode($log->new_values) : 'N/A' }}</textarea>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ json_encode($log->tags) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->ip_address }}</td>
                            <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->user_agent }}</td> -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $auditLogs->links() }}
        </div>
    @endif
</div>
@endsection