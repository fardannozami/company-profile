{{-- Generic bordered card with an optional title/description header and a slot for the body content. --}}
@props([
    'title' => null,
    'desc' => null,
])

<div
    {{ $attributes->merge([
        'class' => '
            admin-card overflow-hidden
        '
    ]) }}
>

    {{-- HEADER --}}
    @if ($title)
        <div class="px-6 py-5">
            <h3 class="font-display text-lg font-medium text-app-heading">
                {{ $title }}
            </h3>

            @if ($desc)
                <p class="mt-1 text-sm text-app-muted">
                    {{ $desc }}
                </p>
            @endif
        </div>
    @endif

    {{-- BODY --}}
    <div
        class="
            p-4
            sm:p-6
            {{ $title ? 'border-t border-app-border' : '' }}
        "
    >
        <div class="space-y-4">
            {{ $slot }}
        </div>
    </div>

</div>
