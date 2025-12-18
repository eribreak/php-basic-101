@extends('layouts.auth')

@section('content')
<section class="mx-auto flex max-w-md flex-1 items-center px-4 py-10 sm:px-6">
    <div class="w-full rounded-lg border border-gray-200 bg-white px-5 py-6 shadow-sm">
        <h1 class="text-lg font-semibold text-heading">Tạo tài khoản</h1>
        <p class="mt-1 text-xs text-softText">Nhập thông tin để bắt đầu.</p>

        <form method="POST" action="{{ route('register') }}" class="mt-4 space-y-4">
            @csrf

            <div class="space-y-1">
                <label class="text-xs font-medium text-body" for="name">Tên</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    class="block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                />
                @error('name')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="text-xs font-medium text-body" for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    class="block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                />
                @error('email')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="text-xs font-medium text-body" for="password">Mật khẩu</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                />
                @error('password')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="text-xs font-medium text-body" for="password_confirmation">Nhập lại mật khẩu</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                />
                @error('password_confirmation')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full rounded bg-primary px-4 py-2 text-xs font-semibold text-primary-foreground hover:bg-primary/90"
            >
                Tạo tài khoản
            </button>

            <p class="text-center text-xs text-softText">
                Đã có tài khoản?
                <a href="{{ route('login') }}" class="text-body hover:text-heading font-medium">Đăng nhập</a>
            </p>
        </form>
    </div>
</section>
@endsection

