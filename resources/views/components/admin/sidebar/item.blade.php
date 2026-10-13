{{-- Sidebar navigation link with active state — gold rail on ink. --}}
@props([
    'href',
    'active' => false,
    'icon' => null
])

<a
    href="{{ $href }}"
    class="relative flex items-center justify-between gap-3
           rounded-xl px-3 py-2 transition
           {{ $active
                ? 'bg-white/[0.07] text-white'
                : 'text-[#a7abc7] hover:bg-white/[0.05] hover:text-white'
           }}"
>
    @if ($active)
        <span aria-hidden="true" class="absolute left-0 top-1/2 h-6 w-[3px] -translate-y-1/2 rounded-full bg-[#c6a15b]"></span>
    @endif

    <div class="flex items-center gap-3">
        @if ($icon)
            <span class="relative shrink-0 {{ $active ? 'text-[#c6a15b]' : '' }}">
                <x-common.icon :name="$icon" class="h-5 w-5" />

                @if (isset($badge))
                    <span
                        x-show="!$store.sidebar.isExpanded && !$store.sidebar.isMobileOpen"
                        x-cloak
                        class="absolute -right-1 -top-1 h-2.5 w-2.5 rounded-full bg-[#c6a15b] ring-2 ring-[#141834]"
                    ></span>
                @endif
            </span>
        @endif

        <span
            class="whitespace-nowrap transition-opacity duration-200"
            :class="{
                'opacity-100': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen,
                'opacity-0': !($store.sidebar.isExpanded || $store.sidebar.isMobileOpen)
            }"
        >
            {{ $slot }}
        </span>
    </div>

    {{-- BADGE (expanded state: full count pill) --}}
    @if (isset($badge))
        <span
            x-show="$store.sidebar.isExpanded || $store.sidebar.isMobileOpen"
            class="ml-auto rounded-full
                   bg-[#c6a15b]/15
                   px-2 py-0.5
                   text-xs font-medium
                   text-[#c6a15b]"
        >
            {{ $badge }}
        </span>
    @endif
</a>
