<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TodoList') - Laravel</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-bg text-gray-dark font-sans antialiased">
    <div id="loadingOverlay"
        style="
            display:none;
            position:fixed;
            inset:0;
            background:rgba(0,0,0,0.45);
            color:white;
            font-size:22px;
            justify-content:center;
            align-items:center;
            z-index:9999;
        ">
        Đang xử lý...
    </div>
    @auth
    <div class="bg-white shadow-sm mb-8">
        <div class="max-w-7xl mx-auto px-5">
            <nav class="flex justify-between items-center py-4">
                <a href="{{ route('todos.index') }}" class="text-2xl font-bold text-blue-strong no-underline hover:text-blue-hover">
                    TodoList
                </a>
                <div class="flex gap-5 items-center">
                    <a href="{{ route('todos.index') }}" class="text-gray-strong hover:bg-gray-light px-4 py-2 rounded transition">
                        Trang chủ
                    </a>
                    <a href="{{ route('notifications.index') }}" class="text-gray-strong hover:bg-gray-light px-4 py-2 rounded transition">
                        Thông báo
                    </a>
                    <span class="text-gray-text">Xin chào, {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-strong text-white px-3 py-1.5 rounded text-sm hover:bg-red-hover transition">
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </div>
    @endauth

    <div class="max-w-7xl mx-auto px-5">
        <div class="bg-white p-8 rounded-lg shadow-sm">
            @if(session('success'))
                <div class="bg-green-light text-green-dark px-4 py-3 rounded mb-5 border-l-4 border-green">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-light text-red-dark px-4 py-3 rounded mb-5 border-l-4 border-red">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>
    <script>
    const overlay = document.getElementById('loadingOverlay');

    document.addEventListener('submit', function(e) {
        overlay.style.display = 'flex';
    });

    document.addEventListener('click', function(e) {
        const el = e.target.closest('a');

        if (!el) return;

        if (el.target === "_blank") return;
        if (el.href.includes('#')) return;

        overlay.style.display = 'flex';
    });
    </script>
</body>
</html>
