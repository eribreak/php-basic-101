<?php

namespace App\Notifications;

use App\Models\Todo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TodoStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Todo $todo, public string $oldStatus, public string $newStatus)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusText = $this->newStatus === 'completed' ? 'Hoàn thành' : 'Đang làm';

        return (new MailMessage)
            ->subject('Trạng thái todo đã thay đổi: ' . $this->todo->title)
            ->line('Trạng thái của todo đã được thay đổi.')
            ->line('Tiêu đề: ' . $this->todo->title)
            ->line('Trạng thái cũ: ' . ($this->oldStatus === 'completed' ? 'Hoàn thành' : 'Đang làm'))
            ->line('Trạng thái mới: ' . $statusText)
            ->line('Cảm ơn bạn đã sử dụng ứng dụng!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'todo_id' => $this->todo->id,
            'todo_title' => $this->todo->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => 'Trạng thái todo đã thay đổi: ' . $this->todo->title . ' (' . $this->oldStatus . ' → ' . $this->newStatus . ')',
            'type' => 'todo_status_changed',
        ];
    }
}
