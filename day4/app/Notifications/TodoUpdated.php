<?php

namespace App\Notifications;

use App\Models\Todo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TodoUpdated extends Notification
{
    use Queueable;

    public function __construct(public Todo $todo)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Todo đã được cập nhật: ' . $this->todo->title)
            ->line('Todo đã được cập nhật trong hệ thống.')
            ->line('Tiêu đề: ' . $this->todo->title)
            ->line('Mô tả: ' . ($this->todo->description ?? 'Không có'))
            ->line('Trạng thái: ' . ($this->todo->status === 'completed' ? 'Hoàn thành' : 'Đang làm'))
            ->line('Cảm ơn bạn đã sử dụng ứng dụng!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'todo_id' => $this->todo->id,
            'todo_title' => $this->todo->title,
            'message' => 'Todo đã được cập nhật: ' . $this->todo->title,
            'type' => 'todo_updated',
        ];
    }
}
