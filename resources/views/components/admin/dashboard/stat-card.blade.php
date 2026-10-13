{{-- Dashboard stat tile: icon, title/subtitle, a big value, and optional "view all" / action slots. --}}
@props([
    'title',
    'subtitle',
    'value',
])

<div class="surface gold-topline rounded-2xl p-6">

    {{-- HEADER --}}
    <div class="flex items-start justify-between">
        <div class="flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#141834] text-[#c6a15b]">
                {{ $icon }}
            </span>
            <div>
                <p class="text-sm font-semibold text-app-heading">{{ $title }}</p>
                <p class="text-xs text-app-muted">{{ $subtitle }}</p>
            </div>
        </div>

        {{ $viewAll ?? '' }}
    </div>

    {{-- VALUE + ACTION --}}
    <div class="mt-6 flex items-end justify-between">
        <p class="font-display text-4xl font-medium tracking-tight text-app-heading">{{ $value }}</p>
        {{ $action ?? '' }}
    </div>
</div>
