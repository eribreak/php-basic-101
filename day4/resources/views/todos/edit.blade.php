@extends('layout')

@section('content')
<h1>Chỉnh sửa Todo</h1>

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

<form method="POST" action="{{ route('todos.update', $todo) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="title">Tiêu đề *</label>
        <input type="text" id="title" name="title" required value="{{ old('title', $todo->title) }}">
    </div>

    <div class="form-group">
        <label for="description">Mô tả</label>
        <textarea id="description" name="description">{{ old('description', $todo->description) }}</textarea>
    </div>

    <div class="form-group">
        <label for="category_id">Danh mục</label>
        <select id="category_id" name="category_id">
            <option value="">-- Chọn danh mục --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $todo->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="status">Trạng thái</label>
        <select id="status" name="status">
            <option value="pending" {{ old('status', $todo->status) === 'pending' ? 'selected' : '' }}>Đang làm</option>
            <option value="completed" {{ old('status', $todo->status) === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
        </select>
    </div>

    <p>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('todos.index') }}" class="btn">Hủy</a>
    </p>
</form>
@endsection

