<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('vite.svg') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GetCare - Admin Dashboard</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <script src="//unpkg.com/alpinejs" defer></script>
    
        <script src="{{ mix('js/app.js') }}" defer></script> 
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <style>
    body {font-family:  'Roboto', sans-serif !important;}
    [x-cloak] { display: none !important; }
    #appointmentModal{
        z-index: 999999999999999;
    }
    </style>
  
    @yield('styles')
  </head>
  <body>
    <div id="root" class="flex h-screen">
      <!-- Sidebar -->
      @include('components.sidebar')

      <div class="flex flex-col flex-1">
        <!-- Header -->
        @include('components.header')

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
          @yield('content')
        </main>
      </div>
    </div>

    @if ($errors->any())
        <div class="fixed top-4 right-4 z-50">
            <div class="flex items-center justify-between bg-red-500 text-white px-4 py-2 rounded shadow-md mb-2">
                <div>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
                <button class="text-white hover:text-red-600" onclick="this.parentElement.remove()">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="fixed top-20 right-4 z-50">
            <div class="flex items-center justify-between bg-green-500 text-white px-4 py-2 rounded shadow-md mb-2">
                <div>
                    {{ session('success') }}
                </div>
                <button class="text-gray-100 hover:text-red-600" onclick="this.parentElement.remove()">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if (session('status'))
        <div class="fixed top-4 right-4 z-50">
            <div class="flex items-center justify-between bg-blue-500 text-white px-4 py-2 rounded shadow-md mb-2">
                <div>
                    {{ session('status') }}
                </div>
                <button class="text-gray-100 hover:text-red-600" onclick="this.parentElement.remove()">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    @endif
    

    @stack('modals')


    @stack('scripts')
  </body>
</html>