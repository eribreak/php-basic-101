<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Todo;
use App\Models\User;
use App\Notifications\TodoCreated;
use App\Notifications\TodoDeleted;
use App\Notifications\TodoStatusChanged;
use App\Notifications\TodoUpdated;
use App\Repositories\Interfaces\TodoRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TodoController extends Controller
{

    public function __construct(
        private TodoRepositoryInterface $todoRepository
    ) {
    }

    private function getCurrentUser(): User
    {
        return Auth::user();
    }

    public function index(Request $request): View
    {
        $user = $this->getCurrentUser();

        $todos = $this->todoRepository->paginateForUser(
            userId: $user->id,
            status: $request->status,
            priority: $request->priority,
            search: $request->search,
            perPage: 10
        );

        $todos->appends($request->query());

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
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf,txt,md,log,json,csv|max:5120',
        ]);

        $user = $this->getCurrentUser();

        $data = [
            ...$validated,
            'priority' => $validated['priority'] ?? 'medium',
            'user_id' => $user->id,
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('todos', $fileName, 'public');
            $data['attachment_path'] = $path;
        }

        $todo = $this->todoRepository->create($data);

        $user->notify(new TodoCreated($todo));

        return redirect()->route('todos.index')
            ->with('success', 'Tạo todo thành công!');
    }

    public function show(Todo $todo): View
    {
        $user = $this->getCurrentUser();

        $todo = $this->todoRepository->findByIdAndUser($todo->id, $user->id);

        if (!$todo) {
            abort(403, 'Bạn không có quyền xem todo này.');
        }

        $todo->load('category');
        return view('todos.show', ['todo' => $todo]);
    }

    public function edit(Todo $todo): View
    {
        $user = $this->getCurrentUser();

        $todo = $this->todoRepository->findByIdAndUser($todo->id, $user->id);

        if (!$todo) {
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

        $todo = $this->todoRepository->findByIdAndUser($todo->id, $user->id);

        if (!$todo) {
            abort(403, 'Bạn không có quyền cập nhật todo này.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,completed',
            'priority' => 'in:high,medium,low',
            'category_id' => 'nullable|exists:categories,id',
            'attachment' => 'nullable|file|max:5120',
        ]);

        $data = $validated;

        if ($request->hasFile('attachment')) {
            if (!empty($todo->attachment_path)) {
                Storage::disk('public')->delete($todo->attachment_path);
            }
            $file = $request->file('attachment');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('todos', $fileName, 'public');
            $data['attachment_path'] = $path;
        }

        $this->todoRepository->update($todo, $data);

        $todo->refresh();
        $user->notify(new TodoUpdated($todo));

        return redirect()->route('todos.index')
            ->with('success', 'Cập nhật todo thành công!');
    }

    public function destroy(Todo $todo): RedirectResponse
    {
        $user = $this->getCurrentUser();

        $todo = $this->todoRepository->findByIdAndUser($todo->id, $user->id);

        if (!$todo) {
            abort(403, 'Bạn không có quyền xóa todo này.');
        }

        $todoTitle = $todo->title;
        $todoId = $todo->id;

        $this->todoRepository->delete($todo);

        $user->notify(new TodoDeleted($todoTitle, $todoId));

        return redirect()->route('todos.index')
            ->with('success', 'Xóa todo thành công!');
    }

    public function toggleStatus(Todo $todo): RedirectResponse
    {
        $user = $this->getCurrentUser();

        $todo = $this->todoRepository->findByIdAndUser($todo->id, $user->id);

        if (!$todo) {
            abort(403, 'Bạn không có quyền thay đổi trạng thái todo này.');
        }

        $oldStatus = $todo->status;
        $newStatus = $todo->status === 'completed' ? 'pending' : 'completed';

        $this->todoRepository->update($todo, ['status' => $newStatus]);

        $todo->refresh();
        $user->notify(new TodoStatusChanged($todo, $oldStatus, $newStatus));

        return redirect()->route('todos.index')
            ->with('success', 'Đổi trạng thái thành công!');
    }
}
