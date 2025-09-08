<header class="bg-white border-b border-gray-200 px-6 py-4">
  <div class="flex items-center justify-between">
    {{-- Search Bar --}}
    <div class="relative flex-1 max-w-md">
      {{-- Assuming a search icon from a font library or a simple SVG --}}
      <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
      <input
        type="text"
        placeholder="Search"
        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
      />
    </div>

    {{-- Right Side Actions --}}
    <div class="flex items-center space-x-4">
      <button class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
        {{-- Calendar Icon --}}
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
      </button>
      <button class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
        {{-- Settings Icon --}}
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.522-.174 1.058-.174 1.58 0A1.982 1.982 0 0113.82 4h2.29a1.982 1.982 0 011.979 1.884l.257 2.943a1.982 1.982 0 01-.522 1.822l-1.464 1.159a1.982 1.982 0 00-.522 1.822l.257 2.943a1.982 1.982 0 01-1.98 1.884h-2.29a1.982 1.982 0 01-1.979-1.884l-.257-2.943a1.982 1.982 0 00-.522-1.822l-1.464-1.159a1.982 1.982 0 00-.522-1.822l.257-2.943A1.982 1.982 0 017.68 4h2.29zm0 0l-2.5 4m2.5-4l2.5 4m-2.5-4v16"></path><circle cx="12" cy="12" r="3"></circle></svg>
      </button>
      <button class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors relative">
        {{-- Bell Icon --}}
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a2 2 0 11-4 0m4 0h-4"></path></svg>
        <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
      </button>
      <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-full transition-colors">
          {{-- User Icon --}}
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </button>
        {{-- Dropdown content --}}
        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
            @auth
                <div class="block px-4 py-2 text-sm text-gray-700">{{ Auth::user()->email }}</div>
                @if(Auth::user()->role === 'PATIENT')
                    <a href="{{ route('patient-details') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Patient Details</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Login</a>
                <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Register</a>
            @endauth
        </div>
      </div>
    </div>
  </div>
</header>