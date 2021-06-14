<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>STUNetWork</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script type="text/javascript" src="https://kit.fontawesome.com/d210984464.js" crossorigin="anonymous"></script>
    </head>
    <body>
       <div id="app" class="w-full h-full">
            @yield("content")
       </div>
    <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
