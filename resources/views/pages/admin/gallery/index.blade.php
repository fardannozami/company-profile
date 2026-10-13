{{-- Admin page listing gallery images in a grid with edit/delete actions and pagination. --}}
@extends('layouts.admin')

@section('title', 'Gallery')

@section('content')
<x-common.component-card title="Gallery">

    {{-- HEADER --}}
    <div class="mb-4 flex items-center justify-between">
        <p class="text-sm text-text-muted">
            Manage gallery images
        </p>

        {{-- ✅ ADD IMAGE BUTTON --}}
        <a href="{{ route('admin.gallery.create') }}"
           class="btn-primary">
            + Add Image
        </a>
    </div>

    {{-- BULK ACTIONS --}}
    <form id="bulk-gallery-form" method="POST" action="{{ route('admin.gallery.bulk-destroy') }}" class="mb-3 flex flex-wrap items-center gap-3">
        @csrf
        <label class="flex items-center gap-2 text-sm text-app-muted">
            <input type="checkbox" data-bulk-select-all="bulk-gallery-form" class="h-4 w-4">
            Pilih semua
        </label>
        <button type="submit" class="btn-danger text-xs disabled:cursor-not-allowed disabled:opacity-50" data-bulk-submit disabled>
            Hapus yang dipilih (<span data-bulk-count>0</span>)
        </button>
    </form>

    {{-- GRID --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        @forelse ($galleries as $gallery)
            <div class="relative rounded-xl border border-app-border overflow-hidden">

                <input type="checkbox" name="ids[]" value="{{ $gallery->id }}" form="bulk-gallery-form" data-bulk-item="bulk-gallery-form" class="absolute left-2 top-2 z-10 h-4 w-4" aria-label="Pilih gambar">

                <img
                    src="{{ asset('storage/'.$gallery->image) }}"
                    class="h-40 w-full object-cover"
                >

                <div class="p-3 space-y-1 text-sm">

                    <p class="font-medium">
                        {{ $gallery->title ?? 'Untitled' }}
                    </p>

                    <p class="text-xs text-app-muted">
                        Order: {{ $gallery->order }}
                    </p>

                    <div class="mt-3 flex justify-between gap-2">
                        <a href="{{ route('admin.gallery.edit', $gallery) }}"
                           class="btn-admin text-xs">
                            Edit
                        </a>

                        <form method="POST"
                              action="{{ route('admin.gallery.destroy', $gallery) }}"
                              onsubmit="return confirm('Delete this image?')">
                            @csrf
                            @method('DELETE')

                            <button class="btn-danger text-xs">
                                Delete
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-span-full admin-table-empty">
                No gallery items found
            </div>
        @endforelse

    </div>

    <div class="mt-6">
        {{ $galleries->links() }}
    </div>

    {{-- BULK SELECT BEHAVIOR --}}
    <script>
    (function () {
        const formId = 'bulk-gallery-form';
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
            if (checked === 0 || !confirm('Hapus ' + checked + ' gambar yang dipilih?')) e.preventDefault();
        });
        refresh();
    })();
    </script>

</x-common.component-card>
@endsection
