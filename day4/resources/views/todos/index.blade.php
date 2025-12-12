@extends('layout')

@section('content')
<h1 class="text-3xl font-bold mb-6">TodoList</h1>

<div class="mb-6 flex gap-3">
    <a href="{{ route('todos.create') }}" class="bg-blue-strong text-white px-4 py-2 rounded-md hover:bg-blue-hover transition">
        Thêm Todo Mới
    </a>
</div>

<form method="GET" action="{{ route('todos.index') }}" class="flex flex-col gap-4 md:flex-row md:items-end mb-6">
    <div class="flex-1">
        <label for="search" class="block mb-1 text-gray-strong">Tìm kiếm</label>
        <input
            type="text"
            id="search"
            name="search"
            value="{{ $searchQuery }}"
            placeholder="Nhập tiêu đề hoặc mô tả"
            class="w-full px-4 py-2 border border-gray-border rounded-md focus:outline-none focus:ring-2 focus:ring-blue focus:border-transparent"
        >
    </div>

    <div>
        <label for="status" class="block mb-1 text-gray-strong">Trạng thái</label>
        <select
            id="status"
            name="status"
            class="px-4 py-2 border border-gray-border rounded-md focus:outline-none focus:ring-2 focus:ring-blue focus:border-transparent"
        >
            <option value="" {{ $currentStatus === null ? 'selected' : '' }}>Tất cả</option>
            <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>Đang làm</option>
            <option value="completed" {{ $currentStatus === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
        </select>
    </div>

    <div>
        <label for="priority" class="block mb-1 text-gray-strong">Ưu tiên</label>
        <select
            id="priority"
            name="priority"
            class="px-4 py-2 border border-gray-border rounded-md focus:outline-none focus:ring-2 focus:ring-blue focus:border-transparent"
        >
            <option value="" {{ ($currentPriority ?? null) === null ? 'selected' : '' }}>Tất cả</option>
            <option value="high" {{ ($currentPriority ?? null) === 'high' ? 'selected' : '' }}>Cao</option>
            <option value="medium" {{ ($currentPriority ?? null) === 'medium' ? 'selected' : '' }}>Trung bình</option>
            <option value="low" {{ ($currentPriority ?? null) === 'low' ? 'selected' : '' }}>Thấp</option>
        </select>
    </div>

    <div class="flex gap-2">
        <button type="submit" class="bg-blue-strong text-white px-4 py-2 rounded-md hover:bg-blue-hover transition">
            Lọc
        </button>
        <a href="{{ route('todos.index') }}" class="bg-gray text-gray-strong px-4 py-2 rounded-md hover:bg-gray-border transition">
            Xóa lọc
        </a>
    </div>
</form>

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
                    <th class="border border-gray-border px-4 py-3 text-left font-semibold">Ưu tiên</th>
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
                            @php $priorityMap = ['high' => 'Cao', 'medium' => 'Trung bình', 'low' => 'Thấp']; @endphp
                            <span class="px-2 py-1 rounded text-sm
                                {{ $todo->priority === 'high' ? 'bg-red-100 text-red-700' : ($todo->priority === 'low' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700') }}">
                                {{ $priorityMap[$todo->priority ?? 'medium'] ?? 'Trung bình' }}
                            </span>
                        </td>
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
                        <a href="{{ route('todos.show', $todo) }}" class="bg-gray text-gray-strong px-3 py-1 rounded text-sm hover:bg-gray-border transition">
                            Xem
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
    <div class="mt-4">
        {{ $todos->links() }}
    </div>
@endif
@endsection
