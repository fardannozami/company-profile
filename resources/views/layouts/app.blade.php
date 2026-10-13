{{-- Main public-site layout: page shell with header, footer, theme toggle and floating support chat. --}}
<!DOCTYPE html>
<html lang="id">
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

    <title>@yield('title','Website')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400..700;1,9..144,400..700&family=Plus+Jakarta+Sans:wght@400..800&display=swap" rel="stylesheet">

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('storage/' . $companyProfile->logo) }}"
    >

    {{-- Fonts: serif editorial + tubuh karya anak bangsa --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:opsz,ital,wght@9..144,0,400;9..144,0,500;9..144,0,600;9..144,1,400;9..144,1,500|plus-jakarta-sans:400,500,600,700" rel="stylesheet">

    {{-- Vite: CSS, JS and Alpine (bundled) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>[x-cloak]{display:none!important}</style>
</head>

<body class="min-h-screen bg-app-bg text-app-text overflow-x-hidden">

    @include('pages.client.partials.header')

    <main>
        @yield('content')
    </main>

    @include('pages.client.partials.footer')

    {{-- Floating support chat (available on every public page) --}}
    <x-support-chat />

    {{-- PAGE SCRIPTS --}}
    @stack('scripts')

</body>
</html>
