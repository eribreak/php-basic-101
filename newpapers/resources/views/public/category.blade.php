@extends('layouts.public')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
    <header class="mb-6 border-b pb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <p class="text-base font-semibold uppercase tracking-[0.15em] text-softText">Danh mục</p>
                <nav class="text-[11px] text-softText">
                  <ol class="flex flex-wrap items-center gap-1">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-body">Trang chủ</a>
                        <span>/</span>
                    </li>
                    <li>
                        <a href="{{ route('categories.show', $category->slug) }}" class="hover:text-body">
                            {{ $category->name }}
                        </a>
                    </li>
                    </ol>
                </nav>
                <h1 class="mt-1 text-2xl font-semibold text-heading">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="mt-1 text-sm text-mutedText">{{ $category->description }}</p>
                @endif

            </div>
        </div>
    </header>

    @if($posts->count())
        <div class="space-y-4">
            @foreach($posts as $post)
                <article class="rounded-lg border border-gray-200 bg-white px-4 py-3 shadow-sm">
                    <div class="flex gap-3">
                        <a href="{{ route('posts.show', $post->public_slug) }}" class="block h-24 w-32 shrink-0 overflow-hidden rounded-md bg-chipBg">
                            @if($post->public_thumbnail)
                                    <img
                                        src="{{ Str::startsWith($post->public_thumbnail, 'http') ? $post->public_thumbnail : asset('storage/'.$post->public_thumbnail) }}"
                                        alt="{{ $post->public_title }}"
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
                                    <a href="{{ route('posts.show', $post->public_slug) }}" class="hover:text-body">
                                            {{ $post->public_title }}
                                    </a>
                                </h2>
                                <p class="text-[11px] text-softText">
                                        {{ optional($post->public_published_at)->format('d M Y') }} · {{ $post->author->name ?? 'Ẩn danh' }} ·
                                    {{ $post->views_count ?? 0 }} lượt xem
                                </p>
                            </header>

                            <p class="mt-2 text-sm text-body line-clamp-3">
                                {{ \Illuminate\Support\Str::limit($post->public_excerpt ?? strip_tags($post->public_content), 220) }}
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

