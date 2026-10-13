{{-- Landing section: vision & mission as numbered editorial panels. --}}
<section class="bg-app-bg py-24 lg:py-28">
    <div class="mx-auto max-w-6xl px-6">

        <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-end">
            <div x-data x-reveal>
                <span class="section-index">01 — Arah gerak</span>
                <h2 class="section-title mt-3">Visi &amp; Misi</h2>
            </div>
            <p x-data x-reveal="{ delay: 120 }" class="max-w-xl text-[0.95rem] leading-relaxed text-app-muted lg:justify-self-end">
                Kompas yang menuntun setiap program, kajian, dan pengabdian kader muda di Yogyakarta.
            </p>
        </div>

        <div class="mt-12 grid gap-5 md:grid-cols-2">
            {{-- Visi --}}
            <div x-data x-reveal class="surface gold-topline relative overflow-hidden rounded-2xl p-8 sm:p-10">
                <div class="flex items-baseline justify-between">
                    <span class="font-display text-5xl text-[#c6a15b]/40">01</span>
                    <span class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#a5813d]">Visi</span>
                </div>
                <p class="mt-6 font-display text-xl leading-relaxed text-app-heading sm:text-2xl">
                    {{ filled($companyProfile->vision) ? $companyProfile->vision : 'Menjadi gerakan kader muda yang berkemajuan dan mencerahkan.' }}
                </p>
            </div>

            {{-- Misi --}}
            <div x-data x-reveal="{ delay: 120 }" class="surface gold-topline relative overflow-hidden rounded-2xl border-[#141834] bg-[#141834] p-8 sm:p-10">
                <div class="absolute inset-0 pattern-star opacity-40"></div>
                <div class="relative flex items-baseline justify-between">
                    <span class="font-display text-5xl text-[#c6a15b]/60">02</span>
                    <span class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#c6a15b]">Misi</span>
                </div>
                <p class="relative mt-6 font-display text-xl leading-relaxed text-[#f4f1e8] sm:text-2xl">
                    {{ filled($companyProfile->mission) ? $companyProfile->mission : 'Menguatkan kader, menghidupkan dakwah, dan memberi manfaat nyata bagi umat.' }}
                </p>
            </div>
        </div>
    </div>
</section>
