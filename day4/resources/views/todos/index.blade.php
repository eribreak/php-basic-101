@extends('layout')

@section('content')
<h1>TodoList</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<p><a href="{{ route('todos.create') }}" class="btn btn-primary">Thêm Todo Mới</a></p>

<div class="filter">
    <a href="{{ route('todos.index') }}" class="{{ $currentStatus === null ? 'active' : '' }}">Tất cả</a>
    <a href="{{ route('todos.index', ['status' => 'pending']) }}" class="{{ $currentStatus === 'pending' ? 'active' : '' }}">Đang làm</a>
    <a href="{{ route('todos.index', ['status' => 'completed']) }}" class="{{ $currentStatus === 'completed' ? 'active' : '' }}">Hoàn thành</a>
</div>

@if($todos->isEmpty())
    <p>Chưa có todo nào.</p>
@else
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Mô tả</th>
                <th>Danh mục</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($todos as $todo)
                <tr>
                    <td>{{ $todo->id }}</td>
                    <td><strong>{{ $todo->title }}</strong></td>
                    <td>{{ $todo->description ?? '' }}</td>
                    <td>{{ $todo->category->name ?? 'Không có' }}</td>
                    <td>{{ $todo->status === 'completed' ? 'Hoàn thành' : 'Đang làm' }}</td>
                    <td>{{ $todo->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('todos.toggle-status', $todo) }}" class="btn btn-success btn-sm">Đổi</a>
                        <a href="{{ route('todos.edit', $todo) }}" class="btn btn-primary btn-sm">Sửa</a>
                        <form action="{{ route('todos.destroy', $todo) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</button>
                        </form>
                        <a href="{{ route('send-welcome-mail', $todo) }}" class="btn btn-warning btn-sm">Gửi Email</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection

