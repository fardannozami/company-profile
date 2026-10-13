{{-- Admin dashboard showing content stats, quick-add actions, and recent orders. --}}
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mt-4">

    {{-- PAGE HEADER --}}
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#a5813d]">Panel Admin</p>
            <h1 class="mt-2 font-display text-3xl font-medium tracking-tight text-app-heading">
                Dasbor
            </h1>
            <p class="mt-1 text-sm text-app-muted">
                Ringkasan konten, aktivitas, dan pesanan terbaru
            </p>
        </div>
        <span class="badge badge-gold">Hari ini · {{ now()->format('d M Y') }}</span>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

        {{-- ARTICLES --}}
        <x-admin.dashboard.stat-card
            title="Artikel"
            subtitle="Terbit {{ $publishedArticles }} / {{ $totalArticles }}"
            :value="$totalArticles"
        >
            <x-slot:icon>
                <x-common.icon name="article" class="h-6 w-6 text-brand-accent" />
            </x-slot:icon>

            <x-slot:viewAll>
                <x-common.button.link :href="route('admin.articles.index')">
                    Lihat semua
                </x-common.button.link>
            </x-slot:viewAll>

            <x-slot:action>
                <x-admin.dashboard.quick-add
                    type="article"
                    label="Artikel"
                />
            </x-slot:action>
        </x-admin.dashboard.stat-card>

        {{-- PRODUCTS --}}
        <x-admin.dashboard.stat-card
            title="Produk"
            subtitle="Aktif {{ $activeProducts }} / {{ $totalProducts }}"
            :value="$totalProducts"
        >
            <x-slot:icon>
                <x-common.icon name="product" class="h-6 w-6 text-brand-accent" />
            </x-slot:icon>

            <x-slot:viewAll>
                <x-common.button.link :href="route('admin.products.index')">
                    Lihat semua
                </x-common.button.link>
            </x-slot:viewAll>

            <x-slot:action>
                <x-admin.dashboard.quick-add
                    type="product"
                    label="Produk"
                />
            </x-slot:action>
        </x-admin.dashboard.stat-card>

        {{-- EVENTS --}}
        <x-admin.dashboard.stat-card
            title="Kegiatan"
            subtitle="Aktif {{ $activeEvents }} / {{ $totalEvents }}"
            :value="$totalEvents"
        >
            <x-slot:icon>
                <x-common.icon name="event" class="h-6 w-6 text-brand-accent" />
            </x-slot:icon>

            <x-slot:viewAll>
                <x-common.button.link :href="route('admin.events.index')">
                    Lihat semua
                </x-common.button.link>
            </x-slot:viewAll>

            <x-slot:action>
                <x-admin.dashboard.quick-add
                    type="event"
                    label="Kegiatan"
                />
            </x-slot:action>
        </x-admin.dashboard.stat-card>

        {{-- GALLERIES --}}
        <x-admin.dashboard.stat-card
            title="Galeri"
            subtitle="Aktif {{ $activeGalleries }} / {{ $totalGalleries }}"
            :value="$totalGalleries"
        >
            <x-slot:icon>
                <x-common.icon name="gallery" class="h-6 w-6 text-brand-accent" />
            </x-slot:icon>

            <x-slot:viewAll>
                <x-common.button.link :href="route('admin.gallery.index')">
                    Lihat semua
                </x-common.button.link>
            </x-slot:viewAll>

            <x-slot:action>
                <x-admin.dashboard.quick-add
                    type="gallery"
                    label="Galeri"
                />
            </x-slot:action>
        </x-admin.dashboard.stat-card>

        {{-- CLIENTS --}}
        <x-admin.dashboard.stat-card
            title="Mitra"
            subtitle="Aktif {{ $activeClients }} / {{ $totalClients }}"
            :value="$totalClients"
        >
            <x-slot:icon>
                <x-common.icon name="client" class="h-6 w-6 text-brand-accent" />
            </x-slot:icon>

            <x-slot:viewAll>
                <x-common.button.link :href="route('admin.clients.index')">
                    Lihat semua
                </x-common.button.link>
            </x-slot:viewAll>

            <x-slot:action>
                <x-admin.dashboard.quick-add
                    type="client"
                    label="Mitra"
                />
            </x-slot:action>
        </x-admin.dashboard.stat-card>

    </div>

    {{-- RECENT ORDERS --}}
    <div class="mt-12">
        <x-admin.dashboard.recent-orders :orders="$recentOrders" />
    </div>

</div>
@endsection
