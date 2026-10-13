{{-- Admin sidebar: collapsible navigation. --}}
@php
    use App\Models\CompanyProfile;
    $companyProfile = CompanyProfile::first();
@endphp

<aside
    @mouseenter="
        if (window.innerWidth >= 1280 && !$store.sidebar.isPinned) {
            $store.sidebar.isExpanded = true
        }
    "
    @mouseleave="
        if (window.innerWidth >= 1280 && !$store.sidebar.isPinned) {
            $store.sidebar.isExpanded = false
        }
    "

    class="fixed top-0 left-0 z-50 h-screen
           admin-scope admin-rail
           transition-[width] duration-300 ease-out
           xl:translate-x-0
           overflow-hidden"

    :class="{
        'xl:w-[280px]': $store.sidebar.isExpanded,
        'xl:w-[88px]': !$store.sidebar.isExpanded,
        'w-[280px] translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full': !$store.sidebar.isMobileOpen
    }"
>

    {{-- ================= BRAND (FIXED HEIGHT) ================= --}}
    <x-admin.sidebar.brand :company-profile="$companyProfile" />

    {{-- ================= SCROLLABLE NAV AREA ================= --}}
    <div class="h-[calc(100vh-72px)] overflow-y-auto scrollbar-hide">

        {{-- ================= NAVIGATION ================= --}}
        <nav class="mt-4 px-3 pb-4 text-sm space-y-6">

            {{-- ========== MENU ========= --}}
            <div class="space-y-0.5">
                <x-admin.sidebar.label>Menu</x-admin.sidebar.label>

                <x-admin.sidebar.item
                    href="{{ route('admin.dashboard') }}"
                    :active="request()->is('admin/dashboard*')"
                    icon="dashboard"
                >
                    Dasbor
                </x-admin.sidebar.item>

                <x-admin.sidebar.item
                    href="{{ route('admin.company-profile.index') }}"
                    :active="request()->is('admin/company-profile*')"
                    icon="company"
                >
                    Profil Organisasi
                </x-admin.sidebar.item>

                <x-admin.sidebar.item
                    href="{{ route('admin.homepage-settings.index') }}"
                    :active="request()->is('admin/homepage-settings*')"
                    icon="article"
                >
                    Beranda
                </x-admin.sidebar.item>

                <x-admin.sidebar.item
                    href="{{ route('admin.orders.index') }}"
                    :active="request()->is('admin/orders*')"
                    icon="orders"
                >
                    Pesanan
                </x-admin.sidebar.item>

                <x-admin.sidebar.item
                    href="{{ route('admin.messages.index') }}"
                    :active="request()->is('admin/messages*')"
                    icon="inbox"
                >
                    Kotak Masuk

                    @if (!empty($unreadInboxCount) && $unreadInboxCount > 0)
                        <x-slot:badge>
                            {{ $unreadInboxCount }}
                        </x-slot:badge>
                    @endif
                </x-admin.sidebar.item>


                <x-admin.sidebar.item
                    href="{{ route('admin.users.index') }}"
                    :active="request()->is('admin/users*')"
                    icon="user"
                >
                    Pengguna
                </x-admin.sidebar.item>
            </div>

            {{-- ========== CONTENT ========= --}}
            <div class="space-y-0.5">
                <x-admin.sidebar.label>Konten</x-admin.sidebar.label>

                <x-admin.sidebar.item
                    href="{{ route('admin.about-sections.index') }}"
                    :active="request()->is('admin/about-sections*')"
                    icon="article"
                >
                    Tentang
                </x-admin.sidebar.item>

                <x-admin.sidebar.item
                    href="{{ route('admin.articles.index') }}"
                    :active="request()->is('admin/articles*')"
                    icon="article"
                >
                    Artikel
                </x-admin.sidebar.item>

                <x-admin.sidebar.item
                    href="{{ route('admin.products.index') }}"
                    :active="request()->is('admin/products*')"
                    icon="product"
                >
                    Produk
                </x-admin.sidebar.item>

                <x-admin.sidebar.item
                    href="{{ route('admin.events.index') }}"
                    :active="request()->is('admin/events*')"
                    icon="event"
                >
                    Kegiatan
                </x-admin.sidebar.item>

                <x-admin.sidebar.item
                    href="{{ route('admin.gallery.index') }}"
                    :active="request()->is('admin/gallery*')"
                    icon="gallery"
                >
                    Galeri
                </x-admin.sidebar.item>

                <x-admin.sidebar.item
                    href="{{ route('admin.clients.index') }}"
                    :active="request()->is('admin/clients*')"
                    icon="client"
                >
                    Mitra
                </x-admin.sidebar.item>
            </div>

        </nav>
    </div>

</aside>
