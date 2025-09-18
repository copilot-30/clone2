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
 
    @include('components.alert')

  </body>
</html>