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

<style>
    body {
        font-family:  'Roboto', sans-serif !important;
    }

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

    @if ($errors->any())
        <div class="fixed top-4 right-4 z-50">
            @foreach ($errors->all() as $error)
                <div class="bg-red-500 text-white px-4 py-2 rounded shadow-md mb-2">
                    {{ $error }}
                </div>
            @endforeach
        </div>
    @endif

    @if (session('success'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-green-500 text-white px-4 py-2 rounded shadow-md mb-2">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('status'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-blue-500 text-white px-4 py-2 rounded shadow-md mb-2">
                {{ session('status') }}
            </div>
        </div>
    @endif
    @stack('scripts')
  </body>
</html>