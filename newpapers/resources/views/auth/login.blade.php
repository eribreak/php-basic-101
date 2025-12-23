@extends('layouts.auth')

@section('content')
<section class="mx-auto flex max-w-md flex-1 items-center px-4 py-10 sm:px-6">
    <div class="w-full rounded-lg border border-gray-200 bg-white px-5 py-6 shadow-sm">
        <h1 class="text-lg font-semibold text-heading">Đăng nhập</h1>
        <p class="mt-1 text-xs text-softText">Nhập email và mật khẩu để tiếp tục.</p>

        @if (session('status'))
            <div class="mt-3 rounded bg-green-50 px-3 py-2 text-xs text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('login') }}"
            class="mt-4 space-y-4"
            novalidate
            data-js-validate="login"
            data-msg-email-required="{{ __('validation.required', ['attribute' => __('validation.attributes.email')]) }}"
            data-msg-email-invalid="{{ __('validation.email', ['attribute' => __('validation.attributes.email')]) }}"
            data-msg-password-required="{{ __('validation.required', ['attribute' => __('validation.attributes.password')]) }}"
        >
            @csrf

            <div class="space-y-1 relative">
                <label class="text-xs font-medium text-body" for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    autofocus
                    autocomplete="email"
                    class="mb-6 block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                />
                <p class="absolute top-0 translate-y-16 text-xs text-red-600 {{ $errors->has('email') ? '' : 'hidden' }}" data-field-error="email">
                    {{ $errors->first('email') }}
                </p>
            </div>

            <div class="space-y-1 relative">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-medium text-body" for="password">Mật khẩu</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-mutedText hover:text-body">
                            Quên mật khẩu?
                        </a>
                    @endif
                </div>
                <input
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="current-password"
                    class="mb-8 block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                />
                <p class="absolute top-0 translate-y-14 text-xs text-red-600 {{ $errors->has('password') ? '' : 'hidden' }}" data-field-error="password">
                    {{ $errors->first('password') }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <input id="remember" type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                <label for="remember" class="text-xs text-body">Ghi nhớ đăng nhập</label>
            </div>

            <button
                type="submit"
                class="w-full rounded bg-primary px-4 py-2 text-xs font-semibold text-primary-foreground hover:bg-primary/90"
            >
                Đăng nhập
            </button>

            @if (Route::has('register'))
                <p class="text-center text-xs text-softText">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}" class="text-body hover:text-heading font-medium">Đăng ký ngay</a>
                </p>
            @endif
        </form>
    </div>
@endsection

