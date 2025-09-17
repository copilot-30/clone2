<header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
    <div class="flex items-center">
        <h1 class="text-2xl font-bold text-gray-800">
            <a href="{{ route('patient.dashboard') }}">
                <span style="color:#306547">Get</span><span style="color:#6A9CF1">Care</span>
            </a>
        </h1>
    </div>
    <nav class="flex space-x-6">
        @php
         if(Auth::user()->patient && Auth::user()->patient->attendingPhysician){
            $menuItems = [
                ['id' => 'dashboard', 'label' => 'Dashboard', 'url' => route('patient.dashboard')],
                ['id' => 'attending-physician', 'label' => 'My Doctor', 'url' => route('patient.attending-physician-details')],
                ['id' => 'chat', 'label' => 'Chat', 'url' => route('patient.chat')],
                ['id' => 'ai-consult', 'label' => 'AI Consult', 'url' => route('patient.ai-consult')],
                ['id' => 'Files', 'label' => 'Medical Records' , 'url' => route('patient.medical-records')],
                ['id' => 'plan', 'label' => 'Plan', 'url' => route('patient.plans')],
            ];
         }else{
            $menuItems = [
                ['id' => 'dashboard', 'label' => 'Dashboard', 'url' => route('patient.dashboard')],
                ['id' => 'doctors', 'label' => 'Doctors', 'url' => route('patient.select-doctor')],
                ['id' => 'ai-consult', 'label' => 'AI Consult', 'url' => route('patient.ai-consult')],
                ['id' => 'plan', 'label' => 'Plan', 'url' => route('patient.plans')],
            
                 
            ];
         }
        @endphp

        @foreach ($menuItems as $item)
            <a href="{{ $item['url'] ?? '#' }}"
               class="text-green-600 hover:text-green-800 
                 {{-- Add active state if needed based on current route --}}
                 @if(isset($activeItem) && $activeItem === $item['id']) border-b-2 border-green-600 @endif">
                {{ $item['label'] }}
            </a>
        @endforeach

        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="text-green-600 hover:text-green-800 ">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>
</header>