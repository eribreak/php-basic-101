@extends('layout')

@section('content')
<h1>Thêm Todo Mới</h1>

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('todos.store') }}">
    @csrf

    <div class="form-group">
        <label for="title">Tiêu đề *</label>
        <input type="text" id="title" name="title" required value="{{ old('title') }}">
    </div>

    <div class="form-group">
        <label for="description">Mô tả</label>
        <textarea id="description" name="description">{{ old('description') }}</textarea>
    </div>

    <div class="form-group">
        <label for="category_id">Danh mục</label>
        <select id="category_id" name="category_id">
            <option value="">-- Chọn danh mục --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="status">Trạng thái</label>
        <select id="status" name="status">
            <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Đang làm</option>
            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
        </select>
    </div>

    <p>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('todos.index') }}" class="btn">Hủy</a>
    </p>
</form>
@endsection

