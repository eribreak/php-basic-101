@extends('layout')

@section('content')
<h1 class="text-3xl font-bold mb-6">TodoList</h1>

<div class="mb-6 flex gap-3">
    <a href="{{ route('todos.create') }}" class="bg-blue-strong text-white px-4 py-2 rounded-md hover:bg-blue-hover transition">
        Thêm Todo Mới
    </a>
    <a href="{{ route('notifications.index') }}" class="bg-gray text-gray-strong px-4 py-2 rounded-md hover:bg-gray-border transition">
        Thông báo
    </a>
</div>

<div class="flex gap-3 mb-6">
    <a href="{{ route('todos.index') }}" class="px-4 py-2 rounded-md transition {{ $currentStatus === null ? 'bg-blue-strong text-white' : 'bg-gray text-gray-strong hover:bg-gray-border' }}">
        Tất cả
    </a>
    <a href="{{ route('todos.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-md transition {{ $currentStatus === 'pending' ? 'bg-blue-strong text-white' : 'bg-gray text-gray-strong hover:bg-gray-border' }}">
        Đang làm
    </a>
    <a href="{{ route('todos.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-md transition {{ $currentStatus === 'completed' ? 'bg-blue-strong text-white' : 'bg-gray text-gray-strong hover:bg-gray-border' }}">
        Hoàn thành
    </a>
</div>

@if($todos->isEmpty())
    <p class="text-gray-text text-center py-8">Chưa có todo nào.</p>
@else
    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-border mt-5">
            <thead>
                <tr class="bg-gray-light">
                    <th class="border border-gray-border px-4 py-3 text-left font-semibold">ID</th>
                    <th class="border border-gray-border px-4 py-3 text-left font-semibold">Tiêu đề</th>
                    <th class="border border-gray-border px-4 py-3 text-left font-semibold">Mô tả</th>
                    <th class="border border-gray-border px-4 py-3 text-left font-semibold">Danh mục</th>
                    <th class="border border-gray-border px-4 py-3 text-left font-semibold">Trạng thái</th>
                    <th class="border border-gray-border px-4 py-3 text-left font-semibold">Ngày tạo</th>
                    <th class="border border-gray-border px-4 py-3 text-left font-semibold">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($todos as $todo)
                    <tr class="hover:bg-gray-bg">
                        <td class="border border-gray-border px-4 py-3">{{ $todo->id }}</td>
                        <td class="border border-gray-border px-4 py-3"><strong>{{ $todo->title }}</strong></td>
                        <td class="border border-gray-border px-4 py-3">{{ $todo->description ?? '' }}</td>
                        <td class="border border-gray-border px-4 py-3">{{ $todo->category->name ?? 'Không có' }}</td>
                        <td class="border border-gray-border px-4 py-3">
                            <span class="px-2 py-1 rounded text-sm {{ $todo->status === 'completed' ? 'bg-green-light text-green-dark' : 'bg-yellow-light text-yellow-dark' }}">
                                {{ $todo->status === 'completed' ? 'Hoàn thành' : 'Đang làm' }}
                            </span>
                        </td>
                        <td class="border border-gray-border px-4 py-3">{{ $todo->created_at->format('d/m/Y H:i') }}</td>
                        <td class="border border-gray-border px-4 py-3">
                            <div class="flex gap-2 flex-wrap">
                                <a href="{{ route('todos.toggle-status', $todo) }}" class="bg-green-strong text-white px-3 py-1 rounded text-sm hover:bg-green-hover transition">
                                    Đổi
                                </a>
                                <a href="{{ route('todos.edit', $todo) }}" class="bg-blue-strong text-white px-3 py-1 rounded text-sm hover:bg-blue-hover transition">
                                    Sửa
                                </a>
                                <form action="{{ route('todos.destroy', $todo) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-strong text-white px-3 py-1 rounded text-sm hover:bg-red-hover transition" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                        Xóa
                                    </button>
                                </form>
                                <a href="{{ route('send-welcome-mail', $todo) }}" class="bg-yellow-strong text-white px-3 py-1 rounded text-sm hover:bg-yellow-hover transition">
                                    Gửi Email
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
