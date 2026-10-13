@props([
    'eyebrow' => null,
    'title',
    'subtitle' => null,
    'href' => null,
    'label' => 'Hubungi kami',
])

{{-- Reusable closing call-to-action band — navy tinta dengan motif bintang dan garis emas. --}}
<section class="bg-app-bg py-24 lg:py-28">
    <div class="mx-auto max-w-7xl px-6">
        <div x-data x-reveal
             class="relative isolate overflow-hidden rounded-[2rem] bg-[#141834] px-6 py-16 text-center md:px-12 md:py-20">

            <div class="absolute inset-0 pattern-star opacity-60"></div>
            <div aria-hidden="true" class="absolute inset-x-12 top-0 h-px bg-gradient-to-r from-transparent via-[#c6a15b] to-transparent"></div>
            <div class="absolute left-1/2 top-0 -z-10 h-64 w-[36rem] -translate-x-1/2 rounded-full bg-[#4f46e5]/25 blur-3xl"></div>

            @if ($eyebrow)
                <span class="inline-flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.22em] text-[#c6a15b]">
                    <span class="h-px w-8 bg-[#c6a15b]/70"></span>{{ $eyebrow }}<span class="h-px w-8 bg-[#c6a15b]/70"></span>
                </span>
            @endif

            <h2 class="mx-auto mt-4 max-w-2xl font-display text-3xl font-medium tracking-tight text-[#f4f1e8] md:text-[2.75rem] md:leading-[1.08]">{{ $title }}</h2>

            @if ($subtitle)
                <p class="mx-auto mt-4 max-w-xl text-[0.95rem] leading-relaxed text-white/60">{{ $subtitle }}</p>
            @endif

            @if (trim($slot) !== '')
                {{ $slot }}
            @elseif ($href)
                <div class="mt-10">
                    <a href="{{ $href }}" class="btn-primary btn-lg">{{ $label }}</a>
                </div>
            @endif
        </div>
    </div>
</section>
