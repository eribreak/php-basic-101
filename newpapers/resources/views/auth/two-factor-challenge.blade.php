@extends('layouts.auth')

@section('content')
<section class="mx-auto flex max-w-md flex-1 items-center px-4 py-10 sm:px-6">
    <div class="w-full rounded-lg border border-gray-200 bg-white px-5 py-6 shadow-sm">
        <h1 class="text-lg font-semibold text-heading">Xác thực hai bước</h1>
        <p class="mt-1 text-xs text-mutedText">Nhập mã xác thực hoặc khôi phục để tiếp tục.</p>

        <form method="POST" action="{{ route('two-factor.login') }}" class="mt-4 space-y-4">
            @csrf

            <div class="space-y-1">
                <label class="text-xs font-medium text-body" for="code">Mã xác thực</label>
                <input
                    id="code"
                    type="text"
                    name="code"
                    class="block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                    placeholder="6-digit code"
                    autocomplete="one-time-code"
                    autofocus
                />
                @error('code')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="text-xs font-medium text-body" for="recovery_code">Hoặc mã khôi phục</label>
                <input
                    id="recovery_code"
                    type="text"
                    name="recovery_code"
                    class="block w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                    placeholder="Recovery code"
                />
                @error('recovery_code')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full rounded bg-primary px-4 py-2 text-xs font-semibold text-primary-foreground hover:bg-primary/90"
            >
                Xác nhận
            </button>
        </form>
    </div>
</section>
@endsection

