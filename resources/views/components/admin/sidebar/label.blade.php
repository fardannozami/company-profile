{{-- Small uppercase section label for grouping sidebar nav items; hidden when the sidebar is collapsed. --}}
<div
    x-show="$store.sidebar.isExpanded || $store.sidebar.isMobileOpen"
    class="rail-label px-3 text-[11px] font-semibold uppercase tracking-[0.2em] select-none"
>
    {{ $slot }}
</div>
