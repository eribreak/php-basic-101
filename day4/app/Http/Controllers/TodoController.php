<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Todo;
use App\Models\User;
use App\Notifications\TodoCreated;
use App\Notifications\TodoDeleted;
use App\Notifications\TodoStatusChanged;
use App\Notifications\TodoUpdated;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TodoController extends Controller
{
    private function getDefaultUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'ngokcuaank@gmail.com'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );
    }

    public function index(Request $request): View
    {
        $query = Todo::with('category');

        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        $todos = $query->orderBy('created_at', 'desc')->get();

        return view('todos.index', [
            'todos' => $todos,
            'currentStatus' => $request->status,
        ]);
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('todos.create', ['categories' => $categories]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,completed',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $todo = Todo::create($validated);

        $user = $this->getDefaultUser();
        $user->notify(new TodoCreated($todo));

        return redirect()->route('todos.index')
            ->with('success', 'Tạo todo thành công!');
    }

    public function show(Todo $todo): View
    {
        $todo->load('category');
        return view('todos.show', ['todo' => $todo]);
    }

    public function edit(Todo $todo): View
    {
        $categories = Category::orderBy('name')->get();
        return view('todos.edit', [
            'todo' => $todo,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Todo $todo): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,completed',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $todo->update($validated);

        $user = $this->getDefaultUser();
        $user->notify(new TodoUpdated($todo));

        return redirect()->route('todos.index')
            ->with('success', 'Cập nhật todo thành công!');
    }

    public function destroy(Todo $todo): RedirectResponse
    {
        $todoTitle = $todo->title;
        $todoId = $todo->id;

        $todo->delete();

        $user = $this->getDefaultUser();
        $user->notify(new TodoDeleted($todoTitle, $todoId));

        return redirect()->route('todos.index')
            ->with('success', 'Xóa todo thành công!');
    }

    public function toggleStatus(Todo $todo): RedirectResponse
    {
        $oldStatus = $todo->status;
        $todo->status = $todo->status === 'completed' ? 'pending' : 'completed';
        $todo->save();

        $user = $this->getDefaultUser();
        $user->notify(new TodoStatusChanged($todo, $oldStatus, $todo->status));

        return redirect()->route('todos.index')
            ->with('success', 'Đổi trạng thái thành công!');
    }
}
