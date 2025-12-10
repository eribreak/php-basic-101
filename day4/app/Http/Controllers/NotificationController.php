<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    private function getDefaultUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'ngokcuaank@gmail.com'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );
    }

    public function index(): View
    {
        $user = $this->getDefaultUser();
        $notifications = $user->notifications()->paginate(15);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(string $id): RedirectResponse
    {
        $user = $this->getDefaultUser();
        $notification = $user->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->route('notifications.index')
            ->with('success', 'Đã đánh dấu đã đọc!');
    }

    public function markAllAsRead(): RedirectResponse
    {
        $user = $this->getDefaultUser();
        $user->unreadNotifications->markAsRead();

        return redirect()->route('notifications.index')
            ->with('success', 'Đã đánh dấu tất cả đã đọc!');
    }

    public function destroy(string $id): RedirectResponse
    {
        $user = $this->getDefaultUser();
        $user->notifications()->find($id)?->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Đã xóa thông báo!');
    }
}
