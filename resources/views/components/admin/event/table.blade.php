{{-- Admin events table with actions. --}}
@props(['events'])

{{-- BULK ACTIONS --}}
<form id="bulk-events-form" method="POST" action="{{ route('admin.events.bulk-destroy') }}" class="mb-3 flex items-center gap-3">
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
                    <input type="checkbox" data-bulk-select-all="bulk-events-form" class="h-4 w-4" aria-label="Pilih semua">
                </th>
                <th>Image</th>
                <th>Title</th>
                <th>Location</th>
                <th>Start</th>
                <th>End</th>
                <th>Status</th>
                <th class="text-right">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($events as $event)
                <tr>

                    {{-- BULK SELECT --}}
                    <td>
                        <input type="checkbox" name="ids[]" value="{{ $event->id }}" form="bulk-events-form" data-bulk-item="bulk-events-form" class="h-4 w-4" aria-label="Pilih event">
                    </td>

                    {{-- IMAGE --}}
                    <td>
                        @if ($event->image)
                            <img
                                src="{{ asset('storage/'.$event->image) }}"
                                class="h-12 w-16 rounded object-cover"
                            >
                        @else
                            <span class="text-xs text-app-muted">No image</span>
                        @endif
                    </td>

                    {{-- TITLE --}}
                    <td class="font-medium">
                        {{ $event->title }}
                    </td>

                    {{-- LOCATION --}}
                    <td class="text-app-muted">
                        {{ $event->location ?? '-' }}
                    </td>

                    {{-- START --}}
                    <td class="text-app-muted">
                        {{ $event->start_date?->format('d M Y') }}
                    </td>

                    {{-- END --}}
                    <td class="text-app-muted">
                        {{ $event->end_date?->format('d M Y') ?? '-' }}
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if ($event->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-muted">Inactive</span>
                        @endif
                    </td>

                    {{-- ACTION --}}
                    <td class="text-right">
                        <div class="inline-flex gap-2">
                            <a
                                href="{{ route('admin.events.edit', $event) }}"
                                class="btn-admin"
                            >
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="{{ route('admin.events.destroy', $event) }}"
                                onsubmit="return confirm('Delete this event?')"
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
                    <td colspan="8" class="admin-table-empty">
                        No events found
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
</div>

{{-- BULK SELECT BEHAVIOR --}}
<script>
(function () {
    const formId = 'bulk-events-form';
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
        if (checked === 0 || !confirm('Hapus ' + checked + ' event yang dipilih?')) e.preventDefault();
    });
    refresh();
})();
</script>
