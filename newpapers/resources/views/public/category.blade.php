@extends('layouts.public')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
    <header class="mb-6 border-b pb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-softText">Danh mục</p>
                <h1 class="mt-1 text-2xl font-semibold text-heading">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="mt-1 text-sm text-mutedText">{{ $category->description }}</p>
                @endif
            </div>
            <a href="{{ route('home') }}" class="text-xs text-softText hover:text-body">← Về trang chủ</a>
        </div>
    </header>

    @if($posts->count())
        <div class="space-y-4">
            @foreach($posts as $post)
                <article class="rounded-lg border border-gray-200 bg-white px-4 py-3 shadow-sm">
                    <div class="flex gap-3">
                        <a href="{{ route('posts.show', $post->slug) }}" class="block h-24 w-32 shrink-0 overflow-hidden rounded-md bg-chipBg">
                            @if($post->thumbnail)
                                <img
                                    src="{{ Str::startsWith($post->thumbnail, 'http') ? $post->thumbnail : asset('storage/'.$post->thumbnail) }}"
                                    alt="{{ $post->title }}"
                                    class="h-full w-full object-cover"
                                >
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gray-200 text-xs font-semibold text-mutedText">
                                    No image
                                </div>
                            @endif
                        </a>

                        <div class="flex-1 min-w-0">
                            <header class="flex flex-col gap-1 sm:flex-row sm:items-baseline sm:justify-between">
                                <h2 class="text-base font-semibold text-heading line-clamp-2">
                                    <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-body">
                                        {{ $post->title }}
                                    </a>
                                </h2>
                                <p class="text-[11px] text-softText">
                                    {{ optional($post->published_at)->format('d M Y') }} · {{ $post->author->name ?? 'Ẩn danh' }} ·
                                    {{ $post->views_count ?? 0 }} lượt xem
                                </p>
                            </header>

                            <p class="mt-2 text-sm text-body line-clamp-3">
                        {{ \Illuminate\Support\Str::limit($post->excerpt ?? strip_tags($post->content), 220) }}
                    </p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @else
        <p class="text-sm text-softText">Danh mục này chưa có bài viết nào.</p>
    @endif
</section>
@endsection

