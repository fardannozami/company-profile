{{-- Admin panel shell: sidebar, header and always-dark admin-scope styling for every admin page. --}}
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Theme: apply saved preference before paint (light is the default) --}}
    <script>
        try {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        } catch (e) {}
    </script>

    <title>@yield('title', 'Admin')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400..700;1,9..144,400..700&family=Plus+Jakarta+Sans:wght@400..800&display=swap" rel="stylesheet">
    <link
    rel="icon"
    type="image/png"
    href="{{ asset('storage/' . $companyProfile->logo) }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:opsz,ital,wght@9..144,0,400;9..144,0,500;9..144,0,600;9..144,1,400;9..144,1,500|plus-jakarta-sans:400,500,600,700" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>[x-cloak]{display:none!important}</style>

    {{-- Global Alpine stores: sidebar + theme (Alpine itself is bundled via app.js) --}}
    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.store('theme', {
                theme: localStorage.getItem('theme') ?? 'light',
                init() { this.apply(); },
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.apply();
                },
                apply() {
                    document.documentElement.classList.toggle('dark', this.theme === 'dark');
                }
            });

            Alpine.store('sidebar', {
                isExpanded: window.innerWidth >= 1280,
                isMobileOpen: false,
                isHovered: false,

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    this.isMobileOpen = false;
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                },

                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },

                setHovered(val) {
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });
        });
    </script>
</head>

<body
    class="h-full bg-app-bg text-app-text admin-scope"
    x-data
    x-init="
        const checkScreen = () => {
            if (window.innerWidth < 1280) {
                $store.sidebar.isExpanded = false;
                $store.sidebar.isMobileOpen = false;
            } else {
                $store.sidebar.isExpanded = true;
                $store.sidebar.isMobileOpen = false;
            }
        };
        checkScreen();
        window.addEventListener('resize', checkScreen);
    "
>

    {{-- SIDEBAR --}}
    @include('pages.admin.partials.sidebar')

    {{-- MOBILE OVERLAY --}}
    <div
        x-show="$store.sidebar.isMobileOpen"
        x-transition.opacity
        @click="$store.sidebar.isMobileOpen = false"
        class="fixed inset-0 z-40 bg-black/60 xl:hidden">
    </div>

    {{-- CONTENT WRAPPER --}}
    <div
        class="min-h-screen transition-all duration-300"
        :class="{
            'xl:ml-[290px]': $store.sidebar.isExpanded,
            'xl:ml-[90px]': !$store.sidebar.isExpanded
        }"
    >

        {{-- HEADER --}}
        @include('pages.admin.partials.header')

        {{-- PAGE CONTENT --}}
        <main class="p-6">
            @yield('content')
        </main>

    </div>

</body>
</html>
