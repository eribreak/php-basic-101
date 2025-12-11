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
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TodoController extends Controller
{
    private function getCurrentUser(): User
    {
        return Auth::user();
    }

    public function index(Request $request): View
    {
        $user = $this->getCurrentUser();

        $query = Todo::with('category')
            ->where('user_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->get('priority'));
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $todos = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        return view('todos.index', [
            'todos' => $todos,
            'currentStatus' => $request->status,
            'currentPriority' => $request->priority,
            'searchQuery' => $request->search,
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
            'priority' => 'in:high,medium,low',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $user = $this->getCurrentUser();

        $todo = Todo::create([
            ...$validated,
            'priority' => $validated['priority'] ?? 'medium',
            'user_id' => $user->id,
        ]);

        $user->notify(new TodoCreated($todo));

        return redirect()->route('todos.index')
            ->with('success', 'Tạo todo thành công!');
    }

    public function show(Todo $todo): View
    {
        $user = $this->getCurrentUser();

        if ($todo->user_id !== $user->id) {
            abort(403, 'Bạn không có quyền xem todo này.');
        }

        $todo->load('category');
        return view('todos.show', ['todo' => $todo]);
    }

    public function edit(Todo $todo): View
    {
        $user = $this->getCurrentUser();

        if ($todo->user_id !== $user->id) {
            abort(403, 'Bạn không có quyền sửa todo này.');
        }

        $categories = Category::orderBy('name')->get();
        return view('todos.edit', [
            'todo' => $todo,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Todo $todo): RedirectResponse
    {
        $user = $this->getCurrentUser();

        if ($todo->user_id !== $user->id) {
            abort(403, 'Bạn không có quyền cập nhật todo này.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,completed',
            'priority' => 'in:high,medium,low',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $todo->update($validated);

        $user->notify(new TodoUpdated($todo));

        return redirect()->route('todos.index')
            ->with('success', 'Cập nhật todo thành công!');
    }

    public function destroy(Todo $todo): RedirectResponse
    {
        $user = $this->getCurrentUser();

        if ($todo->user_id !== $user->id) {
            abort(403, 'Bạn không có quyền xóa todo này.');
        }

        $todoTitle = $todo->title;
        $todoId = $todo->id;

        $todo->delete();

        $user->notify(new TodoDeleted($todoTitle, $todoId));

        return redirect()->route('todos.index')
            ->with('success', 'Xóa todo thành công!');
    }

    public function toggleStatus(Todo $todo): RedirectResponse
    {
        $user = $this->getCurrentUser();

        if ($todo->user_id !== $user->id) {
            abort(403, 'Bạn không có quyền thay đổi trạng thái todo này.');
        }

        $oldStatus = $todo->status;
        $todo->status = $todo->status === 'completed' ? 'pending' : 'completed';
        $todo->save();

        $user->notify(new TodoStatusChanged($todo, $oldStatus, $todo->status));

        return redirect()->route('todos.index')
            ->with('success', 'Đổi trạng thái thành công!');
    }
}
