{{-- Generic styled link button; content is passed in via the component slot. --}}

@props([
    'href',
])

<a
    href="{{ $href }}"
    class="inline-flex items-center gap-2
           rounded-full border border-app-border
           bg-app-surface-2 px-4 py-2
           text-xs font-semibold text-app-text
           transition hover:border-[#c6a15b] hover:text-app-heading"
>
    {{ $slot }}
</a>
