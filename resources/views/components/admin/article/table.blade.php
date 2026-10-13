{{-- Admin articles table: lists articles with thumbnail, status, dates and edit/delete actions. --}}
@props([
    'articles'
])

{{-- BULK ACTIONS --}}
<form id="bulk-articles-form" method="POST" action="{{ route('admin.articles.bulk-destroy') }}" class="mb-3 flex items-center gap-3">
    @csrf
    <button type="submit" class="btn-danger text-xs disabled:cursor-not-allowed disabled:opacity-50" data-bulk-submit disabled>
        Hapus yang dipilih (<span data-bulk-count>0</span>)
    </button>
</form>

<div class="overflow-x-auto">

    <table class="admin-table">

        <thead>
            <tr>
                <th class="w-10">
                    <input type="checkbox" data-bulk-select-all="bulk-articles-form" class="h-4 w-4" aria-label="Pilih semua">
                </th>
                <th class="w-20">Thumbnail</th>
                <th>Title</th>
                <th class="max-w-xs">Excerpt</th>
                <th>Author</th>
                <th>Status</th>
                <th>Published</th>
                <th>Created</th>
                <th class="text-right">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($articles as $article)
                <tr>

                    {{-- BULK SELECT --}}
                    <td>
                        <input type="checkbox" name="ids[]" value="{{ $article->id }}" form="bulk-articles-form" data-bulk-item="bulk-articles-form" class="h-4 w-4" aria-label="Pilih artikel">
                    </td>

                    {{-- THUMBNAIL --}}
                    <td>
                        @if ($article->thumbnail)
                            <img
                                src="{{ asset('storage/'.$article->thumbnail) }}"
                                alt="Thumbnail"
                                class="h-12 w-16 rounded-md object-cover
                                       border border-border-soft"
                            >
                        @else
                            <div
                                class="h-12 w-16 flex items-center justify-center
                                       rounded-md border border-border-soft
                                       text-xs text-app-muted"
                            >
                                No image
                            </div>
                        @endif
                    </td>

                    {{-- TITLE --}}
                    <td class="font-medium text-app-heading">
                        {{ $article->title }}
                    </td>

                    {{-- EXCERPT --}}
                    <td class="text-app-muted text-sm max-w-xs">
                        {{ Str::limit($article->excerpt ?? '-', 80) }}
                    </td>

                    {{-- AUTHOR --}}
                    <td class="text-app-muted">
                        {{ $article->author ?? '-' }}
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if ($article->is_published)
                            <span class="badge badge-success">
                                Published
                            </span>
                        @else
                            <span class="badge badge-muted">
                                Draft
                            </span>
                        @endif
                    </td>

                    {{-- PUBLISHED DATE --}}
                    <td class="text-app-muted">
                        {{ $article->published_at?->format('d M Y') ?? '-' }}
                    </td>

                    {{-- CREATED DATE --}}
                    <td class="text-app-muted">
                        {{ $article->created_at->format('d M Y') }}
                    </td>

                    {{-- ACTION --}}
                    <td class="text-right">
                        <div class="inline-flex gap-2">

                            <a
                                href="{{ route('admin.articles.edit', $article) }}"
                                class="btn-admin"
                            >
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="{{ route('admin.articles.destroy', $article) }}"
                                onsubmit="return confirm('Delete this article?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button class="btn-danger">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="9" class="admin-table-empty">
                        No articles found
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>

</div>

{{-- BULK SELECT BEHAVIOR --}}
<script>
(function () {
    const formId = 'bulk-articles-form';
    const form = document.getElementById(formId);
    if (!form) return;
    const itemBoxes = () => Array.from(document.querySelectorAll('input[data-bulk-item="' + formId + '"]'));
    const selectAllBox = document.querySelector('input[data-bulk-select-all="' + formId + '"]');
    const submitBtn = form.querySelector('[data-bulk-submit]');
    const countLabel = form.querySelector('[data-bulk-count]');
    const refresh = () => {
        const checked = itemBoxes().filter((b) => b.checked).length;
        if (countLabel) countLabel.textContent = checked;
        if (submitBtn) submitBtn.disabled = checked === 0;
        if (selectAllBox) selectAllBox.checked = itemBoxes().length > 0 && checked === itemBoxes().length;
    };
    if (selectAllBox) selectAllBox.addEventListener('change', () => {
        itemBoxes().forEach((b) => { b.checked = selectAllBox.checked; });
        refresh();
    });
    itemBoxes().forEach((b) => b.addEventListener('change', refresh));
    form.addEventListener('submit', (e) => {
        const checked = itemBoxes().filter((b) => b.checked).length;
        if (checked === 0 || !confirm('Hapus ' + checked + ' artikel yang dipilih?')) e.preventDefault();
    });
    refresh();
})();
</script>
