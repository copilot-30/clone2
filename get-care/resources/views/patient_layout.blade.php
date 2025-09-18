<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('vite.svg') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GetCare - Patient Dashboard</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <style>
    body {font-family:  'Roboto', sans-serif !important;}
       [x-cloak] { display: none !important; }
    </style>
      
    @yield('styles')
  </head>
  <body class="bg-gray-100">
    <div id="root" class="flex flex-col h-screen">
      <!-- Patient Header -->
      @include('components.patient-header')

      <div class="flex flex-col flex-1">
        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto ">
          <div class="container mx-auto px-4 py-6">
            @yield('content')
          </div>
        </main>
      </div>
    </div>



  
    @include('components.alert')
    @stack('scripts')
  </body>
</html>