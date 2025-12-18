<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'NewPaper') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite('resources/css/app.css')
</head>
<body class="bg-waterWhite text-heading antialiased min-h-screen flex flex-col">
    <header class="sticky top-0 z-20 border-b bg-white/95 backdrop-blur">
        <nav class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="rounded bg-primary px-2 py-1 text-xs font-semibold tracking-tight text-primary-foreground">
                    NewPaper
                </span>
                <span class="hidden text-xs text-softText sm:inline">EriBreak</span>
            </a>

            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('home') }}" class="text-body hover:text-heading">Trang chủ</a>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-mutedText hover:text-heading" type="submit">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-mutedText hover:text-heading">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="rounded border border-gray-300 px-3 py-1 text-xs font-medium text-body hover:bg-waterWhite">
                        Đăng ký
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="border-t bg-white">
        <div class="mx-auto flex max-w-5xl flex-col gap-2 px-4 py-4 text-xs text-softText sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <p>© {{ date('Y') }} NewPaper.</p>
            <p>Sản phẩm không sử dụng cho</p>
        </div>
    </footer>
</body>
</html>

