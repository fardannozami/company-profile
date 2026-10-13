{{-- Admin clients table with actions. --}}
@props(['clients'])

{{-- BULK ACTIONS --}}
<form id="bulk-clients-form" method="POST" action="{{ route('admin.clients.bulk-destroy') }}" class="mb-3 flex items-center gap-3">
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
                    <input type="checkbox" data-bulk-select-all="bulk-clients-form" class="h-4 w-4" aria-label="Pilih semua">
                </th>
                <th>Logo</th>
                <th>Name</th>
                <th>Website</th>
                <th>Status</th>
                <th>Order</th>
                <th class="text-right">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($clients as $client)
                <tr>
                    <td>
                        <input type="checkbox" name="ids[]" value="{{ $client->id }}" form="bulk-clients-form" data-bulk-item="bulk-clients-form" class="h-4 w-4" aria-label="Pilih mitra">
                    </td>
                    <td>
                        @if ($client->logo)
                            <img
                                src="{{ asset('storage/'.$client->logo) }}"
                                class="h-10 w-20 object-contain"
                            >
                        @else
                            <span class="text-xs text-text-muted">No logo</span>
                        @endif
                    </td>

                    <td class="font-medium">
                        {{ $client->name }}
                    </td>

                    <td class="text-text-muted">
                        {{ $client->website ?? '-' }}
                    </td>

                    <td>
                        @if ($client->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-muted">Inactive</span>
                        @endif
                    </td>

                    <td>
                        {{ $client->order }}
                    </td>

                    <td class="text-right">
                        <div class="inline-flex gap-2">
                            <a href="{{ route('admin.clients.edit', $client) }}" class="btn-admin">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.clients.destroy', $client) }}"
                                  onsubmit="return confirm('Delete this client?')">
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
                    <td colspan="7" class="admin-table-empty">
                        No clients found
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
</div>

{{-- BULK SELECT BEHAVIOR --}}
<script>
(function () {
    const formId = 'bulk-clients-form';
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
        if (checked === 0 || !confirm('Hapus ' + checked + ' mitra yang dipilih?')) e.preventDefault();
    });
    refresh();
})();
</script>
