{{-- Sidebar header: company logo and name. --}}
@props(['companyProfile'])

<div
    class="flex h-16 items-center gap-3 px-6
           border-b border-white/10"
>
    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#c6a15b] overflow-hidden shrink-0">
        @if ($companyProfile?->logo)
            <img src="{{ asset('storage/'.$companyProfile->logo) }}"
                 class="h-full w-full object-cover">
        @else
            <span class="font-display text-sm italic text-[#141834]">
                {{ Str::upper(Str::substr($companyProfile->company_name ?? 'PM', 0, 2)) }}
            </span>
        @endif
    </div>

    <span
        x-show="$store.sidebar.isExpanded || $store.sidebar.isMobileOpen"
        class="leading-tight"
    >
        <span class="block truncate font-display text-[0.95rem] font-medium text-white">
            {{ $companyProfile->company_name ?? 'Admin Panel' }}
        </span>
        <span class="block text-[10px] font-semibold uppercase tracking-[0.2em] text-[#c6a15b]/80">Panel Admin</span>
    </span>
</div>
