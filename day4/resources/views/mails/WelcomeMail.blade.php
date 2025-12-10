<p>Welcome to our website</p>
<p>This is todo info : {{$todo->title}}</p>
<p>This is todo description : {{$todo->description}}</p>
<p>This is todo status : {{$todo->status}}</p>
<p>This is todo category : {{$todo->category ? $todo->category->name : 'Không có'}}</p>
<p>This is todo created at : {{$todo->created_at}}</p>
<p>This is todo updated at : {{$todo->updated_at}}</p>
