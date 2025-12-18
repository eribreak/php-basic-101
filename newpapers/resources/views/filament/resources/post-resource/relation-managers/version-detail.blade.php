<div class="space-y-4">
    <div>
        <h3 class="text-sm font-semibold text-heading mb-2">Thông tin cơ bản</h3>
        <dl class="grid grid-cols-1 gap-2 text-sm">
            <div>
                <dt class="font-medium text-softText">Tiêu đề:</dt>
                <dd class="text-heading">{{ $version->title }}</dd>
            </div>
            <div>
                <dt class="font-medium text-softText">Slug:</dt>
                <dd class="text-heading">{{ $version->slug }}</dd>
            </div>
            @if($version->excerpt)
            <div>
                <dt class="font-medium text-softText">Tóm tắt:</dt>
                <dd class="text-heading">{{ $version->excerpt }}</dd>
            </div>
            @endif
            <div>
                <dt class="font-medium text-softText">Trạng thái:</dt>
                <dd class="text-heading">
                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $version->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $version->status === 'published' ? 'Đã xuất bản' : 'Nháp' }}
                    </span>
                </dd>
            </div>
            @if($version->published_at)
            <div>
                <dt class="font-medium text-softText">Ngày xuất bản:</dt>
                <dd class="text-heading">{{ $version->published_at->format('d/m/Y H:i') }}</dd>
            </div>
            @endif
            <div>
                <dt class="font-medium text-softText">Người tạo:</dt>
                <dd class="text-heading">{{ $version->creator->name ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-softText">Ngày tạo:</dt>
                <dd class="text-heading">{{ $version->created_at->format('d/m/Y H:i') }}</dd>
            </div>
        </dl>
    </div>

    <div>
        <h3 class="text-sm font-semibold text-heading mb-2">Nội dung</h3>
        <div class="prose prose-sm max-w-none text-body border border-gray-200 rounded-lg p-4 bg-waterWhite max-h-96 overflow-y-auto">
            {!! $version->content !!}
        </div>
    </div>
</div>

