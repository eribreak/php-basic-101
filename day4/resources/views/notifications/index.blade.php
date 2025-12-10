@extends('layout')

@section('content')
<h1>Thông báo</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<p>
    <a href="{{ route('todos.index') }}" class="btn">Quay lại TodoList</a>
    @if($notifications->where('read_at', null)->count() > 0)
        <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary">Đánh dấu tất cả đã đọc</button>
        </form>
    @endif
</p>

@if($notifications->isEmpty())
    <p>Chưa có thông báo nào.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Thời gian</th>
                <th>Nội dung</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notifications as $notification)
                <tr style="{{ $notification->read_at ? '' : 'background: grey;' }}">
                    <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <strong>{{ $notification->data['message'] ?? 'Thông báo' }}</strong>
                        @if(isset($notification->data['todo_title']))
                            <br><small>Todo: {{ $notification->data['todo_title'] }}</small>
                        @endif
                        @if(isset($notification->data['type']))
                            <br><small>Loại: {{ $notification->data['type'] }}</small>
                        @endif
                    </td>
                    <td>
                        @if($notification->read_at)
                            <span style="color: green;">Đã đọc</span>
                        @else
                            <span style="color: orange; font-weight: bold;">Chưa đọc</span>
                        @endif
                    </td>
                    <td>
                        @if(!$notification->read_at)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Đánh dấu đã đọc</button>
                            </form>
                        @endif
                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $notifications->links() }}
    </div>
@endif
@endsection

