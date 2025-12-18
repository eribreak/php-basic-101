@extends('layouts.auth')

@section('content')
<section class="mx-auto flex max-w-md flex-1 items-center px-4 py-10 sm:px-6">
    <div class="w-full rounded-lg border border-gray-200 bg-white px-5 py-6 shadow-sm">
        <h1 class="text-lg font-semibold text-heading">Xác minh email</h1>
        <p class="mt-2 text-xs text-mutedText">
            Một liên kết xác minh đã được gửi tới email của bạn. Nếu chưa nhận được, bạn có thể yêu cầu gửi lại.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mt-3 rounded bg-green-50 px-3 py-2 text-xs text-green-700">
                Liên kết mới đã được gửi tới email của bạn.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="mt-4 space-y-3">
            @csrf
            <button
                type="submit"
                class="w-full rounded bg-primary px-4 py-2 text-xs font-semibold text-primary-foreground hover:bg-primary/90"
            >
                Gửi lại email xác minh
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-2 text-center">
            @csrf
            <button type="submit" class="text-xs text-softText hover:text-body">
                Đăng xuất
            </button>
        </form>
    </div>
</section>
@endsection

