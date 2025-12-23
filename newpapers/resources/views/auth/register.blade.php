@extends('layouts.auth')

@section('content')
<section class="mx-auto flex max-w-md flex-1 items-center px-4 py-10 sm:px-6">
    <div class="w-full rounded-lg border border-gray-200 bg-white px-5 py-6 shadow-sm">
        <h1 class="text-lg font-semibold text-heading">Tạo tài khoản</h1>
        <p class="mt-1 text-xs text-softText">Nhập thông tin để bắt đầu.</p>

        <form
            method="POST"
            action="{{ route('register') }}"
            class="mt-4 space-y-4"
            novalidate
            data-js-validate="register"
            data-msg-name-required="{{ __('validation.required', ['attribute' => __('validation.attributes.name')]) }}"
            data-msg-email-required="{{ __('validation.required', ['attribute' => __('validation.attributes.email')]) }}"
            data-msg-email-invalid="{{ __('validation.email', ['attribute' => __('validation.attributes.email')]) }}"
            data-msg-password-required="{{ __('validation.required', ['attribute' => __('validation.attributes.password')]) }}"
            data-msg-password-confirmed="{{ __('validation.confirmed', ['attribute' => __('validation.attributes.password')]) }}"
        >
            @csrf

            <div class="space-y-1 relative">
                <label class="text-xs font-medium text-body" for="name">Tên</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    autofocus
                    autocomplete="name"
                    class="mb-6 block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                />
                <p class="absolute top-0 translate-y-16 text-xs text-red-600 {{ $errors->has('name') ? '' : 'hidden' }}" data-field-error="name">
                    {{ $errors->first('name') }}
                </p>
            </div>

            <div class="space-y-1 relative">
                <label class="text-xs font-medium text-body" for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    class="mb-6 block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                />
                <p class="absolute top-0 translate-y-16 text-xs text-red-600 {{ $errors->has('email') ? '' : 'hidden' }}" data-field-error="email">
                    {{ $errors->first('email') }}
                </p>
            </div>

            <div class="space-y-1 relative">
                <label class="text-xs font-medium text-body" for="password">Mật khẩu</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="new-password"
                    class="mb-6 block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                />
                <p class="absolute top-0 translate-y-16 text-xs text-red-600 {{ $errors->has('password') ? '' : 'hidden' }}" data-field-error="password">
                    {{ $errors->first('password') }}
                </p>
            </div>

            <div class="space-y-1 relative">
                <label class="text-xs font-medium text-body" for="password_confirmation">Nhập lại mật khẩu</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    autocomplete="new-password"
                    class="mb-6 block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                />
                <p class="absolute top-0 translate-y-16 text-xs text-red-600 {{ $errors->has('password_confirmation') ? '' : 'hidden' }}" data-field-error="password_confirmation">
                    {{ $errors->first('password_confirmation') }}
                </p>
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

