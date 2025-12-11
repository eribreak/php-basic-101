<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    private function getCurrentUser(): User
    {
        return Auth::user();
    }

    public function index(): View
    {
        $user = $this->getCurrentUser();

        $notifications = $user->notifications()->paginate(15);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(string $id): RedirectResponse
    {
        $user = $this->getCurrentUser();

        $notification = $user->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->route('notifications.index')
            ->with('success', 'Đã đánh dấu đã đọc!');
    }

    public function markAllAsRead(): RedirectResponse
    {
        $user = $this->getCurrentUser();

        $user->unreadNotifications->markAsRead();

        return redirect()->route('notifications.index')
            ->with('success', 'Đã đánh dấu tất cả đã đọc!');
    }

    public function destroy(string $id): RedirectResponse
    {
        $user = $this->getCurrentUser();

        $user->notifications()->find($id)?->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Đã xóa thông báo!');
    }
}
