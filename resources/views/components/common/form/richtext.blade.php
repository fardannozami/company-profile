@props([
    'label',
    'name',
    'value' => null,
])

{{-- Labeled Trix rich-text editor. Syncs into the hidden input of the same name on submit. --}}
<div class="space-y-1.5">
    <label for="{{ $name }}" class="block text-sm font-medium text-app-heading">
        {{ $label }}
        @if ($attributes->get('required')) <span class="text-danger">*</span> @endif
    </label>

    <input
        id="{{ $name }}"
        type="hidden"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
    >
    <trix-editor
        input="{{ $name }}"
        {{ $attributes->class('form-input trix-content') }}
    ></trix-editor>

    @error($name)
        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
    @enderror
</div>
