<div class="w-64 bg-emerald-600 min-h-screen text-white">
  <div class="p-6">
    <h1 class="text-2xl font-bold">GetCare</h1>
  </div>

  <nav class="mt-8">
    @php
      $menuItems = [
        ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'LayoutDashboard'],
        ['id' => 'patients', 'label' => 'Patients', 'icon' => 'Users'],
        ['id' => 'notes', 'label' => 'Notes', 'icon' => 'FileText'],
        ['id' => 'chat', 'label' => 'Chat', 'icon' => 'MessageCircle'],
        ['id' => 'files', 'label' => 'Files', 'icon' => 'Files'],
        ['id' => 'analytics', 'label' => 'Analytics', 'icon' => 'BarChart3'],
        ['id' => 'engagement', 'label' => 'Engagement', 'icon' => 'Heart'],
      ];
    @endphp

    @foreach ($menuItems as $item)
      <a href="#" {{-- Replace with actual routes --}}
         class="w-full flex items-center px-6 py-3 text-left hover:bg-emerald-700 transition-colors
           @if(isset($activeItem) && $activeItem === $item['id']) bg-emerald-700 border-r-4 border-white @endif">
        {{-- You would typically include SVG icons directly or use a Blade component for icons --}}
        @if($item['icon'] === 'LayoutDashboard')
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"></path></svg>
        @elseif($item['icon'] === 'Users')
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2H9a2 2 0 00-2 2v10a2 2 0 002 2h2m4 0l3-3m-3 3l-3-3m-6 0v-4m0 0a2 2 0 01-2-2V7a2 2 0 012-2h2m4 0l3-3m-3 3l-3-3"></path></svg>
        @elseif($item['icon'] === 'FileText')
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        @elseif($item['icon'] === 'MessageCircle')
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8z"></path></svg>
        @elseif($item['icon'] === 'Files')
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2m7 0V5a2 2 0 00-2-2H7a2 2 0 00-2 2v6m12 0a2 2 0 012 2v5a2 2 0 01-2 2H7a2 2 0 01-2-2v-5a2 2 0 012-2h10z"></path></svg>
        @elseif($item['icon'] === 'BarChart3')
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.944 9.944 0 0112 3c5.4 0 9.772 4.372 9.772 9.772C21.772 17.143 17.4 21.516 12 21.516c-5.4 0-9.772-4.373-9.772-9.772C2.228 7.855 6.6 3.483 12 3.483zm0 0v-2.428m0 2.428h2.428m-2.428 0a.5.5 0 00-.5.5v.925"></path></svg>
        @elseif($item['icon'] === 'Heart')
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 22l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
        @endif
        <span>{{ $item['label'] }}</span>
      </a>
    @endforeach
  </nav>
</div>