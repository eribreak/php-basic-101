@extends('layouts.auth')

@section('title', 'Đăng ký')

@section('content')
<div class="text-center mb-8">
    <h1 class="text-3xl font-bold mb-2">Đăng ký tài khoản</h1>
    <p class="text-gray-text">Tạo tài khoản mới để bắt đầu sử dụng TodoList</p>
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

<form method="POST" action="{{ route('register') }}" class="space-y-6">
    @csrf

    <x-input
        name="name"
        label="Họ và tên"
        type="text"
        value="{{ old('name') }}"
        required
        placeholder="Nhập họ và tên của bạn"
    />

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
        placeholder="Tối thiểu 8 ký tự"
    />

    <x-input
        name="password_confirmation"
        label="Xác nhận mật khẩu"
        type="password"
        required
        placeholder="Nhập lại mật khẩu"
    />

    <div class="flex gap-3 mt-6">
        <button type="submit" class="flex-1 bg-blue-strong text-white px-4 py-2 rounded-md hover:bg-blue-hover transition">
            Đăng ký
        </button>
        <a href="{{ route('login') }}" class="flex-1 bg-gray text-gray-strong px-4 py-2 rounded-md text-center hover:bg-gray-border transition">
            Hủy
        </a>
    </div>
</form>

<div class="text-center mt-6 text-gray-text">
    <p>Đã có tài khoản? <a href="{{ route('login') }}" class="text-blue-strong hover:underline">Đăng nhập ngay</a></p>
</div>
@endsection
