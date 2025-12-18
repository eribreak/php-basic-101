@extends('layouts.auth')

@section('content')
<section class="mx-auto flex max-w-md flex-1 items-center px-4 py-10 sm:px-6">
    <div class="w-full rounded-lg border border-gray-200 bg-white px-5 py-6 shadow-sm">
        <h1 class="text-lg font-semibold text-heading">Quên mật khẩu</h1>
        <p class="mt-1 text-xs text-softText">Nhập email để nhận liên kết đặt lại mật khẩu.</p>

        @if (session('status'))
            <div class="mt-3 rounded bg-green-50 px-3 py-2 text-xs text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-4 space-y-4">
            @csrf
            <div class="space-y-1">
                <label class="text-xs font-medium text-body" for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                />
                @error('email')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full rounded bg-primary px-4 py-2 text-xs font-semibold text-primary-foreground hover:bg-primary/90"
            >
                Gửi liên kết
            </button>
        </form>
    </div>
</section>
@endsection

