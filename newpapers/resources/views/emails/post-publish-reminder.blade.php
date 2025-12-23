<x-mail::message>
# Bài viết sẽ xuất bản sau 5 phút

**Tiêu đề:** {{ $post->title }}

**Thời gian xuất bản:** {{ optional($post->public_published_at ?? $post->published_at)->format('d/m/Y H:i') }}

<x-mail::button :url="config('app.url')">
Mở website
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
