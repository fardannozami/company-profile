{{-- Admin products table with reorder and actions. --}}
@props(['products'])

@php use Illuminate\Support\Str; @endphp

{{-- BULK ACTIONS --}}
<form id="bulk-products-form" method="POST" action="{{ route('admin.products.bulk-destroy') }}" class="mb-3 flex items-center gap-3">
    @csrf
    <button type="submit" class="btn-danger text-xs disabled:cursor-not-allowed disabled:opacity-50" data-bulk-submit disabled>
        Hapus yang dipilih (<span data-bulk-count>0</span>)
    </button>
</form>

<div class="overflow-x-auto">
    <table class="admin-table w-full">

        <thead>
            <tr>
                <th class="w-10">
                    <input type="checkbox" data-bulk-select-all="bulk-products-form" class="h-4 w-4" aria-label="Pilih semua">
                </th>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Status</th>
                <th>Order</th>
                <th class="text-right">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($products as $product)
                <tr>

                    <td>
                        <input type="checkbox" name="ids[]" value="{{ $product->id }}" form="bulk-products-form" data-bulk-item="bulk-products-form" class="h-4 w-4" aria-label="Pilih produk">
                    </td>

                    <td>
                        @if ($product->image)
                            <img
                                src="{{ asset('storage/'.$product->image) }}"
                                class="h-12 w-16 rounded object-cover border border-app-border"
                            >
                        @else
                            <span class="text-xs text-app-muted">No image</span>
                        @endif
                    </td>

                    <td class="font-medium">
                        {{ $product->name }}
                    </td>

                    <td class="text-sm text-app-muted max-w-xs">
                        {{ Str::limit($product->description, 80) ?? '-' }}
                    </td>

                    <td>
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>

                    <td>
                        @if ($product->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-muted">Inactive</span>
                        @endif
                    </td>

                    <td>{{ $product->order }}</td>

                    <td class="text-right">
                        <div class="inline-flex gap-2">
                            <a
                                href="{{ route('admin.products.edit', $product) }}"
                                class="btn-admin"
                            >
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="{{ route('admin.products.destroy', $product) }}"
                                onsubmit="return confirm('Delete this product?')"
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
                        No products found
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
</div>

{{-- BULK SELECT BEHAVIOR --}}
<script>
(function () {
    const formId = 'bulk-products-form';
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
        if (checked === 0 || !confirm('Hapus ' + checked + ' produk yang dipilih?')) e.preventDefault();
    });
    refresh();
})();
</script>
