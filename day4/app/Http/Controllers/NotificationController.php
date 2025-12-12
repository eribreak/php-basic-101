<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{

    public function index(): View
    {
        $notifications = Auth::user()->notifications()->paginate(15);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(string $id): RedirectResponse
    {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->route('notifications.index')
            ->with('success', 'Đã đánh dấu đã đọc!');
    }

    public function markAllAsRead(): RedirectResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->route('notifications.index')
            ->with('success', 'Đã đánh dấu tất cả đã đọc!');
    }

    public function destroy(string $id): RedirectResponse
    {
        Auth::user()->notifications()->find($id)?->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Đã xóa thông báo!');
    }
}
