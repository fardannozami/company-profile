{{-- Landing section: homepage hero — editorial serif, bingkai lengkung emas, motif bintang halus. --}}
@php
    // Use the newest gallery image as the hero visual, falling back to the static asset.
    $heroImage = optional($galleries->first())->image;
    $heroSrc = $heroImage ? asset('storage/'.$heroImage) : asset('images/hero.jpg');

    // Only clients with a logo make sense in this compact trust strip.
    $trustedLogos = $clients->filter(fn ($client) => filled($client->logo))->take(5);
@endphp

<section class="relative isolate overflow-hidden bg-app-bg glow-top">

    {{-- Motif bintang delapan yang sangat halus + grid --}}
    <div class="absolute inset-0 -z-10 pattern-star opacity-60 [mask-image:radial-gradient(ellipse_at_top,black,transparent_75%)]"></div>

    <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-6 pt-20 pb-16
                lg:min-h-[calc(100vh-4rem)] lg:grid-cols-[1.08fr_0.92fr] lg:gap-14 lg:py-10">

        {{-- ============ LEFT: copy ============ --}}
        <div>
            <a href="{{ route('events') }}" x-data x-reveal
               class="group inline-flex items-center gap-2.5 rounded-full border border-app-border
                      bg-app-surface/80 py-1 pl-1 pr-4 text-xs text-app-muted backdrop-blur transition hover:border-[#c6a15b]/70">
                <span class="rounded-full bg-[#141834] px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-[#c6a15b]">Baru</span>
                {{ $companyProfile->hero_badge ?? 'Lihat kegiatan terbaru kami' }}
                <svg class="h-3.5 w-3.5 text-[#a5813d] transition-transform group-hover:translate-x-0.5" fill="none"
                     stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <h1 x-data x-reveal="{ delay: 80 }"
                class="mt-6 font-display text-[2.6rem] font-medium leading-[1.04] tracking-tight text-app-heading sm:text-6xl lg:text-[4rem]">
                {{ $companyProfile->hero_title ?? 'Gerakan muda yang' }}
                <em class="relative whitespace-nowrap text-brand-accent">{{ $companyProfile->hero_title_highlight ?? 'mencerahkan' }}<span aria-hidden="true" class="absolute inset-x-0 -bottom-1 h-[3px] rounded-full bg-gradient-to-r from-[#c6a15b] to-transparent"></span></em>
            </h1>

            <p x-data x-reveal="{ delay: 160 }" class="mt-6 max-w-xl text-base leading-relaxed text-app-muted">
                {{ filled($companyProfile->hero_description) ? $companyProfile->hero_description : \Illuminate\Support\Str::limit(strip_tags($companyProfile->about
                    ?? 'Pemuda Muhammadiyah DIY: mewadahi kader muda untuk belajar, berkhidmat, dan memberi manfaat bagi umat dan bangsa.'), 170) }}
            </p>

            <div x-data x-reveal="{ delay: 240 }" class="mt-8 flex flex-wrap items-center gap-3">
                <a href="{{ route('contact') }}" class="btn-primary btn-lg">{{ $companyProfile->hero_cta_primary ?? 'Hubungi kami' }}</a>
                <a href="{{ route('products') }}" class="btn-outline btn-lg">{{ $companyProfile->hero_cta_secondary ?? 'Jelajahi karya' }}</a>
            </div>

            {{-- Angka ringkas — bukti gerak, bukan pajangan --}}
            <dl x-data x-reveal="{ delay: 300 }" class="mt-10 flex items-center gap-8 border-t border-app-border pt-6">
                <div>
                    <dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-app-muted">Galeri</dt>
                    <dd class="mt-1 font-display text-2xl text-app-heading">{{ number_format($galleries->count()) }}<span class="text-[#a5813d]">+</span></dd>
                </div>
                <div class="h-10 w-px bg-app-border"></div>
                <div>
                    <dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-app-muted">Produk</dt>
                    <dd class="mt-1 font-display text-2xl text-app-heading">{{ number_format($products->count()) }}<span class="text-[#a5813d]">+</span></dd>
                </div>
                <div class="h-10 w-px bg-app-border"></div>
                <div>
                    <dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-app-muted">Mitra</dt>
                    <dd class="mt-1 font-display text-2xl text-app-heading">{{ number_format($clients->count()) }}<span class="text-[#a5813d]">+</span></dd>
                </div>
            </dl>
        </div>

        {{-- ============ RIGHT: arched visual ============ --}}
        <div x-data x-reveal="{ delay: 200 }" class="relative mx-auto w-full max-w-md lg:mx-0 lg:justify-self-end">
            {{-- Offset emas di belakang bingkai --}}
            <div aria-hidden="true" class="absolute -inset-3 rounded-b-[2rem] rounded-t-[999px] border border-[#c6a15b]/50"></div>

            <figure class="relative overflow-hidden rounded-b-[1.6rem] rounded-t-[999px] border border-app-border bg-ink shadow-2xl">
                <img src="{{ $heroSrc }}" alt="{{ $companyProfile->company_name ?? 'Kegiatan kami' }}"
                     class="aspect-[3/4] max-h-[520px] w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#141834]/70 via-transparent to-transparent"></div>
                <figcaption class="absolute inset-x-0 bottom-0 p-6 text-center">
                    <p class="font-display text-lg italic text-white">{{ $companyProfile->company_name ?? 'Pemuda Muhammadiyah DIY' }}</p>
                    <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.24em] text-[#c6a15b]">Muda · Mencerahkan</p>
                </figcaption>
            </figure>

            @if ($trustedLogos->isNotEmpty())
                <div class="mt-10 flex items-center justify-center gap-6">
                    @foreach ($trustedLogos as $client)
                        <img src="{{ asset('storage/'.$client->logo) }}" alt="{{ $client->name }}"
                             class="h-6 w-auto opacity-45 grayscale transition duration-300 hover:opacity-100 hover:grayscale-0">
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
