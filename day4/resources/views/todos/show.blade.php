@extends('layout')

@section('content')
<h1 class="text-3xl font-bold mb-6">Chi tiết Todo</h1>

<div class="mb-6">
    <a href="{{ route('todos.index') }}" class="bg-gray text-gray-strong px-4 py-2 rounded-md hover:bg-gray-border transition">
        Quay lại
    </a>
</div>

<div class="overflow-x-auto">
    <table class="w-full border-collapse border border-gray-border">
        <tbody>
            <tr class="hover:bg-gray-bg">
                <th class="border border-gray-border px-4 py-3 text-left font-semibold bg-gray-light w-1/4">ID</th>
                <td class="border border-gray-border px-4 py-3">{{ $todo->id }}</td>
            </tr>
            <tr class="hover:bg-gray-bg">
                <th class="border border-gray-border px-4 py-3 text-left font-semibold bg-gray-light">Tiêu đề</th>
                <td class="border border-gray-border px-4 py-3"><strong>{{ $todo->title }}</strong></td>
            </tr>
            <tr class="hover:bg-gray-bg">
                <th class="border border-gray-border px-4 py-3 text-left font-semibold bg-gray-light">Mô tả</th>
                <td class="border border-gray-border px-4 py-3">{{ $todo->description ?? 'Không có' }}</td>
            </tr>
            <tr class="hover:bg-gray-bg">
                <th class="border border-gray-border px-4 py-3 text-left font-semibold bg-gray-light">Danh mục</th>
                <td class="border border-gray-border px-4 py-3">{{ $todo->category->name ?? 'Không có' }}</td>
            </tr>
            <tr class="hover:bg-gray-bg">
                <th class="border border-gray-border px-4 py-3 text-left font-semibold bg-gray-light">Ưu tiên</th>
                <td class="border border-gray-border px-4 py-3">
                    @php $priorityMap = ['high' => 'Cao', 'medium' => 'Trung bình', 'low' => 'Thấp']; @endphp
                    {{ $priorityMap[$todo->priority ?? 'medium'] ?? 'Trung bình' }}
                </td>
            </tr>
            <tr class="hover:bg-gray-bg">
                <th class="border border-gray-border px-4 py-3 text-left font-semibold bg-gray-light">Đính kèm</th>
                <td class="border border-gray-border px-4 py-3">
                    @if($todo->attachment_path)
                        @php
                            $url = Storage::disk('public')->url($todo->attachment_path);
                            $filename = basename($todo->attachment_path);
                            $extension = strtolower(pathinfo($todo->attachment_path, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']);
                            $isPdf = $extension === 'pdf';
                            $isText = in_array($extension, ['txt', 'md', 'log', 'json', 'csv']);
                        @endphp
                        <a href="{{ $url }}" target="_blank" class="text-blue-strong hover:underline">
                            Tải / Xem file: {{ $filename }}
                        </a>
                        @if($isImage)
                            <div class="mt-3">
                                <img src="{{ $url }}" alt="Preview attachment" class="max-h-64 rounded border border-gray-border">
                            </div>
                        @elseif($isPdf)
                            <div class="mt-3 h-96">
                                <iframe src="{{ $url }}" class="w-full h-full rounded border border-gray-border"></iframe>
                            </div>
                        @elseif($isText)
                            @php
                                try {
                                    $textContent = \Illuminate\Support\Str::limit(
                                        Storage::disk('public')->get($todo->attachment_path),
                                        5000,
                                        '...'
                                    );
                                } catch (\Throwable $e) {
                                    $textContent = 'Không thể đọc nội dung file.';
                                }
                            @endphp
                            <div class="mt-3">
                                <p class="text-sm text-gray-text mb-1">Xem nhanh (tối đa 5000 ký tự):</p>
                                <pre class="whitespace-pre-wrap bg-gray-100 p-3 rounded border border-gray-border text-sm">{{ $textContent }}</pre>
                            </div>
                        @endif
                    @else
                        Không có
                    @endif
                </td>
            </tr>
            <tr class="hover:bg-gray-bg">
                <th class="border border-gray-border px-4 py-3 text-left font-semibold bg-gray-light">Trạng thái</th>
                <td class="border border-gray-border px-4 py-3">
                    <span class="px-2 py-1 rounded text-sm {{ $todo->status === 'completed' ? 'bg-green-light text-green-dark' : 'bg-yellow-light text-yellow-dark' }}">
                        {{ $todo->status === 'completed' ? 'Hoàn thành' : 'Đang làm' }}
                    </span>
                </td>
            </tr>
            <tr class="hover:bg-gray-bg">
                <th class="border border-gray-border px-4 py-3 text-left font-semibold bg-gray-light">Ngày tạo</th>
                <td class="border border-gray-border px-4 py-3">{{ $todo->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr class="hover:bg-gray-bg">
                <th class="border border-gray-border px-4 py-3 text-left font-semibold bg-gray-light">Ngày cập nhật</th>
                <td class="border border-gray-border px-4 py-3">{{ $todo->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="flex gap-3 mt-6">
    <a href="{{ route('todos.edit', $todo) }}" class="bg-blue-strong text-white px-4 py-2 rounded-md hover:bg-blue-hover transition">
        Sửa
    </a>
    <form action="{{ route('todos.destroy', $todo) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-strong text-white px-4 py-2 rounded-md hover:bg-red-hover transition" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
            Xóa
        </button>
    </form>
</div>
@endsection
