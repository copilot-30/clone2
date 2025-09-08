<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('vite.svg') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GetCare - Admin Dashboard</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <script src="{{ mix('js/app.js') }}" defer></script>
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
  </body>
</html>