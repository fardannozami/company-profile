@props([
    'aboutSection' => null,
    'action',
    'method' => 'POST',
    'showOrder' => false,
])

{{-- About section form, shared by create and edit. Body is rendered as a section on the About page. --}}
<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="grid grid-cols-1 gap-5">
        <x-common.form.input label="Title" name="title" :value="$aboutSection?->title" required />

        <div>
            <x-common.form.richtext label="Body" name="body" :value="$aboutSection?->body" required />
            <p class="mt-1.5 text-xs text-app-muted">
                Konten tampil sebagai seksi di halaman About (HTML didukung).
            </p>
        </div>

        <x-common.form.file label="Image (optional)" name="image" />

        @if ($aboutSection?->image)
            <img src="{{ asset('storage/'.$aboutSection->image) }}" class="h-24 rounded-lg border border-app-border object-cover">
        @endif

        <x-common.form.radio-group
            label="Status"
            name="is_active"
            :value="$aboutSection?->is_active ?? 1"
            :options="[1 => 'Active', 0 => 'Inactive']"
        />

        {{-- ORDER POSITION (EDIT ONLY) --}}
        @if ($showOrder)
            <div>
                <label class="mb-1.5 block text-sm font-medium text-app-heading">Order Position</label>
                <select name="order_action" class="form-input">
                    <option value="">Keep current position</option>
                    <option value="top">Move to top</option>
                    <option value="up">Move up</option>
                    <option value="down">Move down</option>
                    <option value="bottom">Move to bottom</option>
                </select>
                <p class="mt-1 text-xs text-app-muted">Current order: {{ $aboutSection->order }}</p>
            </div>
        @endif

        <div class="pt-2">
            <button class="btn-primary">{{ $method === 'POST' ? 'Save Section' : 'Update Section' }}</button>
        </div>
    </div>
</form>
