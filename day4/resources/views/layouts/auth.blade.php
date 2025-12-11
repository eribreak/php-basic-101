<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Auth') - Laravel</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-bg text-gray-dark font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-2xl">
            <div class="bg-white py-10 px-16 rounded-lg shadow-sm">
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
    </div>
</body>
</html>


