@extends('layout')

@section('title', 'Đăng nhập')

@section('content')
<div class="max-w-md mx-auto my-12">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold mb-2">Đăng nhập</h1>
        <p class="text-gray-text">Nhập thông tin để truy cập vào tài khoản của bạn</p>
    </div>

    @if($errors->any())
        <div class="bg-red-light text-red-dark px-4 py-3 rounded mb-5 border-l-4 border-red">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <x-input 
            name="email" 
            label="Email" 
            type="email" 
            value="{{ old('email') }}"
            required 
            placeholder="example@email.com"
        />

        <x-input 
            name="password" 
            label="Mật khẩu" 
            type="password" 
            required 
            placeholder="Nhập mật khẩu của bạn"
        />

        <div class="flex items-center gap-2">
            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }} class="w-4 h-4 text-blue-strong border-gray-border rounded focus:ring-blue">
            <label for="remember" class="text-gray-strong font-normal">Ghi nhớ đăng nhập</label>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="flex-1 bg-blue-strong text-white px-4 py-2 rounded-md hover:bg-blue-hover transition">
                Đăng nhập
            </button>
            <a href="{{ route('register') }}" class="flex-1 bg-gray text-gray-strong px-4 py-2 rounded-md text-center hover:bg-gray-border transition">
                Đăng ký
            </a>
        </div>
    </form>

    <div class="text-center mt-6 text-gray-text">
        <p>Chưa có tài khoản? <a href="{{ route('register') }}" class="text-blue-strong hover:underline">Đăng ký ngay</a></p>
    </div>
</div>
@endsection
