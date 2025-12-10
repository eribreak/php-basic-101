@extends('layout')

@section('content')
<h1>Chi tiết Todo</h1>

<p><a href="{{ route('todos.index') }}" class="btn">Quay lại</a></p>

<table>
    <tr>
        <th>ID</th>
        <td>{{ $todo->id }}</td>
    </tr>
    <tr>
        <th>Tiêu đề</th>
        <td><strong>{{ $todo->title }}</strong></td>
    </tr>
    <tr>
        <th>Mô tả</th>
        <td>{{ $todo->description ?? 'Không có' }}</td>
    </tr>
    <tr>
        <th>Danh mục</th>
        <td>{{ $todo->category->name ?? 'Không có' }}</td>
    </tr>
    <tr>
        <th>Trạng thái</th>
        <td>{{ $todo->status === 'completed' ? 'Hoàn thành' : 'Đang làm' }}</td>
    </tr>
    <tr>
        <th>Ngày tạo</th>
        <td>{{ $todo->created_at->format('d/m/Y H:i') }}</td>
    </tr>
    <tr>
        <th>Ngày cập nhật</th>
        <td>{{ $todo->updated_at->format('d/m/Y H:i') }}</td>
    </tr>
</table>

<p>
    <a href="{{ route('todos.edit', $todo) }}" class="btn btn-primary">Sửa</a>
    <form action="{{ route('todos.destroy', $todo) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</button>
    </form>
</p>
@endsection

