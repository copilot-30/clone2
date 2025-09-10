<header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
    <div class="flex items-center">
        <h1 class="text-2xl font-bold text-gray-800">GetCare</h1>
    </div>
    <nav class="flex space-x-6">
        @php
            $menuItems = [
                ['id' => 'dashboard', 'label' => 'Dashboard', 'url' => route('patient.dashboard')],
                ['id' => 'doctors', 'label' => 'Doctors', 'url' => route('patient.select-doctor')],
                ['id' => 'attending-physician', 'label' => 'My Doctor', 'url' => route('patient.attending-physician-details')],
                ['id' => 'chat', 'label' => 'Chat', 'url' => route('patient.chat')],
                // Add other patient-specific menu items as needed
                // ['id' => 'notes', 'label' => 'Notes'],
                // ['id' => 'files', 'label' => 'Files'],
                // ['id' => 'analytics', 'label' => 'Analytics'],
                // ['id' => 'engagement', 'label' => 'Engagement'],
                // ['id' => 'plan', 'label' => 'Plan'],
            ];
        @endphp

        @foreach ($menuItems as $item)
            <a href="{{ $item['url'] ?? '#' }}"
               class="text-green-600 hover:text-green-800 font-semibold
                 {{-- Add active state if needed based on current route --}}
                 @if(isset($activeItem) && $activeItem === $item['id']) border-b-2 border-green-600 @endif">
                {{ $item['label'] }}
            </a>
        @endforeach

        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="text-green-600 hover:text-green-800 font-semibold">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>
</header>