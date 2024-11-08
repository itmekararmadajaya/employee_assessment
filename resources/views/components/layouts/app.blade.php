<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>

        @vite(['resources/css/app.css','resources/js/app.js'])
    </head>
    <body>
        <div class="lg:grid lg:grid-cols-3 lg:gap-3">

        </div>
        {{ $slot }}
    </body>
</html>
