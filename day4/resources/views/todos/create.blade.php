@extends('layout')

@section('content')
<h1 class="text-3xl font-bold mb-6">Thêm Todo Mới</h1>

@if($errors->any())
    <div class="bg-red-light text-red-dark px-4 py-3 rounded mb-5 border-l-4 border-red">
        <ul class="list-disc ml-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('todos.store') }}" class="space-y-6" enctype="multipart/form-data">
    @csrf

    <div class="mb-5">
        <label for="title" class="block mb-2 font-medium text-gray-strong">
            Tiêu đề <span class="text-red">*</span>
        </label>
        <input
            type="text"
            id="title"
            name="title"
            required
            value="{{ old('title') }}"
            class="w-full px-4 py-2 border border-gray-border rounded-md focus:outline-none focus:ring-2 focus:ring-blue focus:border-transparent @error('title') border-red @enderror"
        >
        @error('title')
            <div class="text-red-strong text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-5">
        <label for="description" class="block mb-2 font-medium text-gray-strong">Mô tả</label>
        <textarea
            id="description"
            name="description"
            rows="4"
            class="w-full px-4 py-2 border border-gray-border rounded-md focus:outline-none focus:ring-2 focus:ring-blue focus:border-transparent resize-y min-h-[100px] @error('description') border-red @enderror"
        >{{ old('description') }}</textarea>
        @error('description')
            <div class="text-red-strong text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-5">
        <label for="category_id" class="block mb-2 font-medium text-gray-strong">Danh mục</label>
        <select
            id="category_id"
            name="category_id"
            class="w-full px-4 py-2 border border-gray-border rounded-md focus:outline-none focus:ring-2 focus:ring-blue focus:border-transparent @error('category_id') border-red @enderror"
        >
            <option value="">-- Chọn danh mục --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="text-red-strong text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-5">
        <label for="priority" class="block mb-2 font-medium text-gray-strong">Ưu tiên</label>
        <select
            id="priority"
            name="priority"
            class="w-full px-4 py-2 border border-gray-border rounded-md focus:outline-none focus:ring-2 focus:ring-blue focus:border-transparent @error('priority') border-red @enderror"
        >
            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Cao</option>
            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Trung bình</option>
            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Thấp</option>
        </select>
        @error('priority')
            <div class="text-red-strong text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-5">
        <label for="attachment" class="block mb-2 font-medium text-gray-strong">Đính kèm (tùy chọn, tối đa 5MB)</label>
        <input
            type="file"
            id="attachment"
            name="attachment"
            class="w-full px-4 py-2 border border-gray-border rounded-md focus:outline-none focus:ring-2 focus:ring-blue focus:border-transparent @error('attachment') border-red @enderror"
        >
        @error('attachment')
            <div class="text-red-strong text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-5">
        <label for="status" class="block mb-2 font-medium text-gray-strong">Trạng thái</label>
        <select
            id="status"
            name="status"
            class="w-full px-4 py-2 border border-gray-border rounded-md focus:outline-none focus:ring-2 focus:ring-blue focus:border-transparent @error('status') border-red @enderror"
        >
            <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Đang làm</option>
            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
        </select>
        @error('status')
            <div class="text-red-strong text-sm mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="flex gap-3 mt-6">
        <button type="submit" class="bg-blue-strong text-white px-4 py-2 rounded-md hover:bg-blue-hover transition">
            Lưu
        </button>
        <a href="{{ route('todos.index') }}" class="bg-gray text-gray-strong px-4 py-2 rounded-md hover:bg-gray-border transition">
            Hủy
        </a>
    </div>
</form>
@endsection
