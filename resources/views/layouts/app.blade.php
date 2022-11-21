<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
</head>
<body>
<div class="min-h-full">
    @include('layouts.nav')
    <div class="py-10">
        @yield('content')
    </div>
</div>

</body>
</html>
