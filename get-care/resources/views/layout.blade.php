<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('vite.svg') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GetCare - Your Online Health Team</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <script src="{{ mix('js/app.js') }}" defer></script>
  </head>
  <body>
    <div id="root">
       @yield('content')
    </div>

 @if ($errors->any())
        <div class="fixed top-4 right-4 z-50">
            <div class="flex items-center justify-between bg-red-500 text-white px-4 py-2 rounded shadow-md mb-2">
                <div>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
                <button class="text-gray-100 hover:text-red-600" onclick="this.parentElement.remove()">
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

  </body>
</html>