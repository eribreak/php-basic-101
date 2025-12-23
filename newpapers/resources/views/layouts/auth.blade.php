<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'NewPaper') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite('resources/css/app.css')
    @vite('resources/js/auth.ts')
</head>
<body class="bg-waterWhite text-heading antialiased flex items-center justify-center min-h-screen">
    <main class="w-full">
        @yield('content')
    </main>
</body>
</html>
