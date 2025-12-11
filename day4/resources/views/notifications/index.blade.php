@extends('layout')

@section('content')
<h1 class="text-3xl font-bold mb-6">Thông báo</h1>

<div class="mb-6 flex gap-3">
    <a href="{{ route('todos.index') }}" class="bg-gray text-gray-strong px-4 py-2 rounded-md hover:bg-gray-border transition">
        Quay lại TodoList
    </a>
    @if($notifications->where('read_at', null)->count() > 0)
        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-blue-strong text-white px-4 py-2 rounded-md hover:bg-blue-hover transition">
                Đánh dấu tất cả đã đọc
            </button>
        </form>
    @endif
</div>

@if($notifications->isEmpty())
    <p class="text-gray-text text-center py-8">Chưa có thông báo nào.</p>
@else
    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-border">
            <thead>
                <tr class="bg-gray-light">
                    <th class="border border-gray-border px-4 py-3 text-left font-semibold">Thời gian</th>
                    <th class="border border-gray-border px-4 py-3 text-left font-semibold">Nội dung</th>
                    <th class="border border-gray-border px-4 py-3 text-left font-semibold">Trạng thái</th>
                    <th class="border border-gray-border px-4 py-3 text-left font-semibold">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notifications as $notification)
                    <tr class="hover:bg-gray-bg {{ $notification->read_at ? '' : 'bg-gray-light' }}">
                        <td class="border border-gray-border px-4 py-3">{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                        <td class="border border-gray-border px-4 py-3">
                            <strong>{{ $notification->data['message'] ?? 'Thông báo' }}</strong>
                            @if(isset($notification->data['todo_title']))
                                <br><small class="text-gray-text">Todo: {{ $notification->data['todo_title'] }}</small>
                            @endif
                            @if(isset($notification->data['type']))
                                <br><small class="text-gray-text">Loại: {{ $notification->data['type'] }}</small>
                            @endif
                        </td>
                        <td class="border border-gray-border px-4 py-3">
                            @if($notification->read_at)
                                <span class="text-green-strong font-medium">Đã đọc</span>
                            @else
                                <span class="text-yellow-strong font-bold">Chưa đọc</span>
                            @endif
                        </td>
                        <td class="border border-gray-border px-4 py-3">
                            <div class="flex gap-2">
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-strong text-white px-3 py-1 rounded text-sm hover:bg-green-hover transition">
                                            Đánh dấu đã đọc
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-strong text-white px-3 py-1 rounded text-sm hover:bg-red-hover transition" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                        Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $notifications->links() }}
    </div>
@endif
@endsection
