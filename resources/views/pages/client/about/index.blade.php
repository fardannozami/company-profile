{{-- About page: profil organisasi — hero, navigasi antar-seksi, seksi profil per <h2>, visi-misi, CTA. --}}
@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')

@php
    /*
     * Seksi profil dibaca dari tabel `about_sections` (terstruktur, dikelola admin).
     * Bila tabel kosong (mis. instalasi lama), fallback ke logika split-blob lama
     * dari kolom `about` — kode lama dipertahankan di bawah, bukan dihapus.
     * Bentuk $profileSections selalu: title, slug (anchor #profil-{slug}), body.
     */
    $aboutHtml = trim($companyProfile->about ?? '');
    $profileIntro = '';
    $profileSections = [];

    if (isset($aboutSections) && $aboutSections->isNotEmpty()) {
        foreach ($aboutSections as $aboutSection) {
            $profileSections[] = [
                'title' => $aboutSection->title !== '' ? $aboutSection->title : 'Bagian',
                'slug'  => $aboutSection->slug !== '' ? $aboutSection->slug : \Illuminate\Support\Str::slug($aboutSection->title),
                'body'  => $aboutSection->body,
            ];
        }
    } elseif ($aboutHtml !== '' && preg_match('/<h2[\s>]/i', $aboutHtml)) {
        $chunks = preg_split('/(<h2[\s>].*?<\/h2\s*>)/is', $aboutHtml, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $current = null;
        foreach ($chunks as $chunk) {
            if (preg_match('/<h2[^>]*>(.*?)<\/h2\s*>/is', $chunk, $m)) {
                if (is_array($current) && (trim(strip_tags($current['body'])) !== '' || preg_match('/<(img|table|audio|iframe|figure)\b/i', $current['body']))) {
                    $profileSections[] = $current;
                }
                $heading = trim(strip_tags(html_entity_decode($m[1], ENT_QUOTES, 'UTF-8')));
                $current = ['title' => $heading !== '' ? $heading : 'Bagian', 'slug' => \Illuminate\Support\Str::slug($heading !== '' ? $heading : 'Bagian'), 'body' => ''];
            } elseif (is_array($current)) {
                $current['body'] .= $chunk;
            } else {
                $profileIntro .= $chunk;
            }
        }
        if (is_array($current) && (trim(strip_tags($current['body'])) !== '' || preg_match('/<(img|table|audio|iframe|figure)\b/i', $current['body']))) {
            $profileSections[] = $current;
        }
    } elseif ($aboutHtml !== '') {
        $profileSections[] = ['title' => 'Profil', 'slug' => 'profil', 'body' => $aboutHtml];
    }
@endphp

{{-- ================= HERO ================= --}}
<section class="relative isolate overflow-hidden bg-app-bg glow-top">
    <div class="absolute inset-0 -z-10 bg-grid [mask-image:radial-gradient(ellipse_at_top,black,transparent_70%)]"></div>

    <div class="mx-auto max-w-7xl px-6 pt-20 pb-14 lg:pt-24 lg:pb-16">
        <div class="grid gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:items-center lg:gap-16">

            {{-- Copy --}}
            <div>
                <span x-data x-reveal class="eyebrow">Tentang Kami</span>
                <h1 x-data x-reveal="{ delay: 80 }"
                    class="mt-4 font-display text-4xl font-medium leading-[1.05] tracking-tight text-app-heading sm:text-5xl lg:text-6xl">
                    {{ $companyProfile->company_name ?? 'Our Company' }}
                </h1>
                <p x-data x-reveal="{ delay: 160 }" class="mt-6 max-w-xl text-lg leading-relaxed text-app-muted">
                    {!! nl2br(e(
                        \Illuminate\Support\Str::limit(strip_tags($companyProfile->about ??
                        'A digital studio building meaningful, scalable products for teams that care about doing things well.'), 200)
                    )) !!}
                </p>
                <div x-data x-reveal="{ delay: 240 }" class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('contact') }}" class="btn-primary btn-lg">Work with us</a>
                    <a href="{{ route('products') }}" class="btn-outline btn-lg">See our work</a>
                </div>
            </div>

            {{-- Foto: galeri aktif, atau logo organisasi, atau placeholder --}}
            <div x-data x-reveal="{ delay: 200 }" class="relative mx-auto w-full max-w-lg lg:ml-auto lg:mr-0">
                <div class="absolute -inset-6 -z-10 rounded-[2rem] bg-gradient-to-tr from-brand-main/20 via-transparent to-transparent blur-2xl"></div>
                <figure class="overflow-hidden rounded-2xl border border-app-border shadow-2xl ring-1 ring-gold/40">
                    @if ($aboutImage)
                        <img src="{{ asset('storage/'.$aboutImage) }}"
                             alt="{{ $companyProfile->company_name ?? 'Our team' }}"
                             class="aspect-[4/3] w-full object-cover">
                    @elseif (!empty($companyProfile->logo))
                        <img src="{{ asset('storage/'.$companyProfile->logo) }}"
                             alt="{{ $companyProfile->company_name ?? 'Our team' }}"
                             class="aspect-[4/3] w-full bg-app-surface object-contain p-10">
                    @else
                        <img src="{{ asset('images/hero.jpg') }}"
                             alt="{{ $companyProfile->company_name ?? 'Our team' }}"
                             class="aspect-[4/3] w-full object-cover">
                    @endif
                </figure>
                @if (filled($companyProfile->address))
                    <figcaption class="mt-3 text-center text-xs tracking-wide text-app-muted">
                        {{ $companyProfile->address }}
                    </figcaption>
                @endif
            </div>
        </div>

        {{-- Navigasi antar-seksi profil --}}
        @if (count($profileSections) > 1)
            <nav x-data x-reveal aria-label="Navigasi profil organisasi"
                 class="no-scrollbar mt-14 flex gap-2 overflow-x-auto border-t border-app-border pt-6">
                @foreach ($profileSections as $section)
                    <a href="#profil-{{ $section['slug'] }}"
                       class="inline-flex shrink-0 items-center gap-2.5 rounded-full border border-app-border bg-app-surface px-4 py-2 text-sm text-app-text transition hover:border-gold hover:text-app-heading">
                        <span class="section-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="font-medium">{{ $section['title'] }}</span>
                    </a>
                @endforeach
            </nav>
        @endif
    </div>
</section>


{{-- ================= SEKSI PROFIL ================= --}}
@if (count($profileSections) > 0)
    <section class="bg-app-bg py-16 lg:py-20">
        <div class="mx-auto max-w-3xl px-6">

            @if (trim(strip_tags($profileIntro)) !== '')
                <div x-data x-reveal class="wp-profile text-lg leading-relaxed">
                    {!! \App\Support\HtmlSanitizer::clean($profileIntro) !!}
                </div>
            @endif

            @foreach ($profileSections as $section)
                <article id="profil-{{ $section['slug'] }}"
                         class="scroll-mt-28 border-t border-app-border pt-12 {{ $loop->first && trim(strip_tags($profileIntro)) === '' ? 'mt-0 border-t-0 pt-0' : 'mt-14' }}">
                    <div x-data x-reveal>
                        <span class="section-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} &mdash; Profil</span>
                        <h2 class="mt-3 font-display text-3xl font-medium tracking-tight text-app-heading md:text-4xl">
                            {{ $section['title'] }}
                        </h2>
                    </div>
                    <div x-data x-reveal="{ delay: 100 }" class="wp-profile mt-6">
                        {!! \App\Support\HtmlSanitizer::clean($section['body']) !!}
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@else
    <section class="bg-app-bg py-16 lg:py-20">
        <div class="mx-auto max-w-3xl px-6">
            <p x-data x-reveal class="text-xl leading-relaxed text-app-text sm:text-2xl">
                {{ strip_tags($companyProfile->about ??
                'We are a multidisciplinary studio that partners with businesses to design, build, and scale digital products. We bring together strategy, design, and engineering to ship work that solves real problems — and keeps working long after launch.') }}
            </p>
        </div>
    </section>
@endif


{{-- ================= VISI & MISI ================= --}}
<section class="bg-app-bg pb-20 lg:pb-24">
    <div class="mx-auto max-w-7xl px-6">
        <div x-data x-reveal class="relative overflow-hidden rounded-3xl bg-ink px-6 py-14 text-center md:px-12 md:py-16">
            <div class="absolute inset-0 pattern-star opacity-60"></div>
            <div class="absolute left-1/2 top-0 h-56 w-[32rem] -translate-x-1/2 rounded-full bg-gold/20 blur-3xl"></div>

            <div class="relative">
                <span class="eyebrow justify-center">Visi &amp; Misi</span>
                <p class="mx-auto mt-6 max-w-3xl font-display text-2xl font-medium leading-snug text-[#f4f1e8] sm:text-3xl">
                    &ldquo;{{ $companyProfile->vision
                        ?? 'To be a trusted digital partner that helps businesses grow through purposeful, lasting technology.' }}&rdquo;
                </p>
                <div class="mx-auto my-8 h-px w-24 bg-gold/60"></div>
                <p class="mx-auto max-w-2xl text-base leading-relaxed text-white/75">
                    {{ $companyProfile->mission
                        ?? 'To design and build digital products that solve real problems and deliver meaningful, long-term value.' }}
                </p>
            </div>
        </div>
    </div>
</section>


{{-- ================= CTA + CONTACT ================= --}}
<x-cta eyebrow="Say hello"
       title="Let's build something together"
       subtitle="Tell us about your project — we'll get back to you within one business day.">
    <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
        <a href="{{ route('contact') }}" class="btn-primary btn-lg">Start a conversation</a>
        <a href="{{ route('products') }}" class="btn-outline btn-lg">See our work</a>
    </div>

    @if (filled($companyProfile->email) || filled($companyProfile->phone))
        <div class="mt-8 flex flex-wrap items-center justify-center gap-x-8 gap-y-2 text-sm text-app-muted">
            @if (filled($companyProfile->email))
                <a href="mailto:{{ $companyProfile->email }}" class="transition hover:text-app-heading">{{ $companyProfile->email }}</a>
            @endif
            @if (filled($companyProfile->phone))
                <a href="tel:{{ $companyProfile->phone }}" class="transition hover:text-app-heading">{{ $companyProfile->phone }}</a>
            @endif
            @if (filled($companyProfile->address))
                <span>{{ $companyProfile->address }}</span>
            @endif
        </div>
    @endif
</x-cta>

{{-- Tipografi untuk HTML bawaan WordPress: token tema agar selaras terang/gelap --}}
<style>
    .wp-profile { color: var(--color-app-text); font-size: 1.05rem; line-height: 1.8; }
    .wp-profile p { margin-bottom: 1.1rem; }
    .wp-profile h2, .wp-profile h3, .wp-profile h4 { font-family: var(--font-display); font-weight: 500; letter-spacing: -0.01em; color: var(--color-app-heading); margin: 2rem 0 1rem; line-height: 1.2; }
    .wp-profile h2 { font-size: 1.75rem; }
    .wp-profile h3 { font-size: 1.4rem; }
    .wp-profile h4 { font-size: 1.15rem; }
    .wp-profile strong, .wp-profile b { color: var(--color-app-heading); }
    .wp-profile img, .wp-profile figure img { max-width: 100%; height: auto; border-radius: 1rem; }
    .wp-profile img { display: block; margin: 1.75rem auto; }
    .wp-profile figure { margin: 1.75rem 0; text-align: center; }
    .wp-profile figcaption { margin-top: 0.75rem; font-size: 0.8rem; color: var(--color-app-muted); }
    .wp-profile ul, .wp-profile ol { margin: 0 0 1.1rem 1.5rem; }
    .wp-profile li { margin-bottom: 0.4rem; }
    .wp-profile a { color: var(--color-gold-deep); text-decoration: underline; text-underline-offset: 3px; }
    .wp-profile a:hover { color: var(--color-gold); }
    .wp-profile table { width: 100%; margin: 1.5rem 0; border-collapse: collapse; font-size: 0.9rem; line-height: 1.6; }
    .wp-profile th, .wp-profile td { padding: 0.65rem 0.85rem; border: 1px solid var(--color-app-border); text-align: left; vertical-align: top; }
    .wp-profile th, .wp-profile tr:first-child td { color: var(--color-app-heading); font-weight: 600; }
    .wp-profile audio { width: 100%; margin: 1.5rem 0; }
    .wp-profile iframe { max-width: 100%; border-radius: 1rem; }
</style>

@endsection
