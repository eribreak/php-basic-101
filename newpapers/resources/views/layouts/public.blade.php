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
                <img src="{{ asset('images/logo.jpg') }}" alt="NewPaper Logo" class="h-10 w-30 object-contain">
            </a>

            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('home') }}" class="text-body hover:text-heading">Trang chủ</a>
                <details class="group relative [&_summary::-webkit-details-marker]:hidden">
                    <summary class="cursor-pointer list-none text-body hover:text-heading">Tìm kiếm</summary>
                    <div class="absolute right-0 mt-2 w-72 rounded-lg border border-gray-200 bg-white p-3 shadow-sm">
                        <form method="GET" action="{{ route('search') }}" class="flex items-center gap-2">
                            <input
                                type="text"
                                name="q"
                                placeholder="Nhập từ khoá..."
                                class="block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                                autofocus
                            />
                            <button
                                type="submit"
                                class="inline-flex h-9 w-12 items-center justify-center rounded bg-primary text-primary-foreground hover:bg-primary/90"
                                aria-label="Tìm kiếm"
                                title="Tìm kiếm"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                    <path d="M21 21l-4.3-4.3" />
                                    <circle cx="11" cy="11" r="7" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </details>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-mutedText hover:text-heading" type="submit">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded border border-gray-300 px-3 py-1 text-xs font-medium text-body hover:bg-waterWhite">
                        Đăng nhập
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

