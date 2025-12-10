<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TodoDeleted extends Notification
{
    use Queueable;

    public function __construct(public string $todoTitle, public int $todoId)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'vonage'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Todo đã được xóa: ' . $this->todoTitle)
            ->line('Todo đã được xóa khỏi hệ thống.')
            ->line('Tiêu đề todo đã xóa: ' . $this->todoTitle)
            ->line('Cảm ơn bạn đã sử dụng ứng dụng!');
    }

    public function toVonage(object $notifiable): \Illuminate\Notifications\Messages\VonageMessage
    {
        return (new \Illuminate\Notifications\Messages\VonageMessage)
            ->content('Todo đã xóa: ' . $this->todoTitle . ' (ID: ' . $this->todoId . ')');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'todo_id' => $this->todoId,
            'todo_title' => $this->todoTitle,
            'message' => 'Todo đã được xóa: ' . $this->todoTitle,
            'type' => 'todo_deleted',
        ];
    }
}
