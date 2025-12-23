<?php

namespace App\Console\Commands;

use App\Mail\PostPublishReminderMail;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPublishReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:send-publish-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi mail cho tác giả trước 5 phút khi bài viết được xuất bản';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = now();

$windowStart = $now->addMinutes(5)->subMinutes(1); // 4 phút
$windowEnd   = $now->addMinutes(5)->addMinutes(1); // 6 phút


        $posts = Post::query()
            ->with(['author', 'latestPublishedVersion'])
            ->whereNull('publish_reminder_sent_at')
            ->where(function ($query) use ($windowStart, $windowEnd): void {
                $query
                    ->where(function ($q) use ($windowStart, $windowEnd): void {
                        $q->where('status', 'published')
                            ->whereNotNull('published_at')
                            ->where('published_at', '>=', $windowStart)
                            ->where('published_at', '<', $windowEnd);
                    })
                    ->orWhere(function ($q) use ($windowStart, $windowEnd): void {
                        $q->where('status', 'draft')
                            ->whereHas('latestPublishedVersion', function ($v) use ($windowStart, $windowEnd): void {
                                $v->whereNotNull('published_at')
                                    ->where('published_at', '>=', $windowStart)
                                    ->where('published_at', '<', $windowEnd);
                            });
                    });
            })
            ->get();

        $sent = 0;
        foreach ($posts as $post) {
            $email = $post->author?->email;
            if (! $email) {
                continue;
            }

            try {
                Mail::to($email)->send(new PostPublishReminderMail($post));
                $post->forceFill(['publish_reminder_sent_at' => now()])->saveQuietly();
                $sent++;
            } catch (\Throwable $e) {
                Log::error('Failed to send publish reminder email', [
                    'post_id' => $post->id,
                    'email' => $email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Sent {$sent} publish reminder email(s). ");
        return self::SUCCESS;
    }
}
