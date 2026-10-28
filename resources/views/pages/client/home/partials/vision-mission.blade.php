{{-- Halaman bagian: Visi & Misi dalam satu kartu editorial dengan kontrol tab. --}}
<section class="bg-app-bg py-24 lg:py-28">
    <div class="mx-auto max-w-7xl px-6">

        <div class="mb-12 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div x-data x-reveal>
                <span class="section-index">01 — Arah gerak</span>
                <x-section-heading
                    eyebrow="Visi &amp; Misi"
                    title="Arah gerak kami"
                    subtitle="Kompas yang menuntun setiap program, kajian, dan pengabdian kader muda di Yogyakarta." />
            </div>
        </div>

        {{-- Satu kartu berbasis tab: klik "Visi" atau "Misi" untuk mengubah isi kartu. --}}
        <div x-data="{
                tab: 'visi',
                tabs: ['visi', 'misi'],
                focusTab(i) {
                    const els = this.$root.querySelectorAll('[role=tab]');
                    els[i]?.focus();
                },
                select(i) { this.tab = this.tabs[i]; this.focusTab(i) },
                move(e) {
                    const k = e.key;
                    if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(k)) return;
                    e.preventDefault();
                    let i = this.tabs.indexOf(this.tab);
                    if (k === 'ArrowLeft')  i = i > 0 ? i - 1 : this.tabs.length - 1;
                    if (k === 'ArrowRight') i = i < this.tabs.length - 1 ? i + 1 : 0;
                    if (k === 'Home')       i = 0;
                    if (k === 'End')        i = this.tabs.length - 1;
                    this.tab = this.tabs[i];
                    this.focusTab(i);
                }
            }" x-cloak
            class="mt-12 relative overflow-hidden rounded-3xl border border-[#c6a15b]/25 bg-gradient-to-br from-[#141834] via-[#1b2144] to-[#10142b] p-6 shadow-[0_24px_70px_-32px_rgba(20,24,52,.75)] sm:p-10">

            <div aria-hidden="true" class="pointer-events-none absolute -right-24 -top-28 h-72 w-72 rounded-full bg-[#c6a15b]/10 blur-3xl"></div>
            <div aria-hidden="true" class="pointer-events-none absolute -bottom-36 -left-20 h-64 w-64 rounded-full bg-indigo-400/10 blur-3xl"></div>

            <div class="flex flex-col lg:flex-col lg:items-stretch lg:gap-8">

                {{-- Kontrol tab: numeralis besar sebagai tombol (baris 2 kolom di mobile, rail kiri di desktop) --}}
                <div role="tablist" aria-label="Pilih Visi atau Misi" @keydown="move($event)"
                     class="relative grid grid-cols-2 gap-3 rounded-2xl border border-white/10 bg-white/[.06] p-1.5 shadow-inner shadow-black/10
                            mb-6 sm:mb-8 lg:mb-6
                            lg:flex lg:flex-row lg:gap-3">

                    {{-- Visi (aktif secara default) --}}
                    <button type="button" role="tab" id="tab-vision" aria-controls="vision-panel"
                            :aria-selected="tab === 'visi'"
                            :tabindex="tab === 'visi' ? 0 : -1"
                            @click="select(0)"
                            class="flex w-full cursor-pointer flex-col items-center justify-center gap-2
                                   px-5 sm:px-6 py-5 lg:py-6 lg:px-8 text-center lg:w-1/2
                                   rounded-xl lg:rounded-2xl
                                   border border-transparent
                                   transition-all duration-500 ease-[cubic-bezier(.22,1,.36,1)] motion-reduce:transition-none
                                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#c6a15b] focus-visible:ring-offset-2 focus-visible:ring-offset-[#141834]"
                            :class="tab === 'visi'
                                    ? 'bg-gradient-to-br from-[#c6a15b] to-[#a77d36] shadow-lg shadow-black/25 ring-1 ring-white/30'
                                    : 'bg-transparent border border-transparent hover:bg-white/[.08]'"
                        >
                            <span class="font-display text-4xl lg:text-5xl leading-none tracking-tight
                                      :class="tab === 'visi' ? 'text-[#141834]' : 'text-[#e5d3ad]/45'"
                                      aria-hidden="true">01</span>
                            <span class="text-[11px] lg:text-sm font-semibold uppercase tracking-wider
                                      :class="tab === 'visi' ? 'text-[#141834]' : 'text-white/65'"
                                      >Visi</span>
                        </button>

                    {{-- Misi --}}
                    <button type="button" role="tab" id="tab-mission" aria-controls="mission-panel"
                            :aria-selected="tab === 'misi'"
                            :tabindex="tab === 'misi' ? 0 : -1"
                            @click="select(1)"
                            class="flex w-full cursor-pointer flex-col items-center justify-center gap-2
                                   px-5 sm:px-6 py-5 lg:py-6 lg:px-8 text-center lg:w-1/2
                                   rounded-xl lg:rounded-2xl
                                   border border-transparent
                                   transition-all duration-500 ease-[cubic-bezier(.22,1,.36,1)] motion-reduce:transition-none
                                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#c6a15b] focus-visible:ring-offset-2 focus-visible:ring-offset-[#141834]"
                            :class="tab === 'misi'
                                    ? 'bg-gradient-to-br from-[#c6a15b] to-[#a77d36] shadow-lg shadow-black/25 ring-1 ring-white/30'
                                    : 'bg-transparent border border-transparent hover:bg-white/[.08]'"
                        >
                            <span class="font-display text-4xl lg:text-5xl leading-none tracking-tight
                                      :class="tab === 'misi' ? 'text-[#141834]' : 'text-[#e5d3ad]/45'"
                                      aria-hidden="true">02</span>
                            <span class="text-[11px] lg:text-sm font-semibold uppercase tracking-wider
                                      :class="tab === 'misi' ? 'text-[#141834]' : 'text-white/65'"
                                      >Misi</span>
                        </button>
                </div>

                {{-- Isi kartu: satu panel kontinyu dengan tinggi stabil saat beralih tab --}}
                <div class="flex-1 lg:min-w-0">
                    <div role="tabpanel" id="vision-panel" aria-labelledby="tab-vision"
                         x-show="tab === 'visi'"
                         x-transition:enter="transition-all duration-500 ease-[cubic-bezier(.22,1,.36,1)] motion-reduce:transition-none"
                         x-transition:enter-start="opacity-0 translate-y-3 blur-sm"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition-all duration-300 ease-in motion-reduce:transition-none"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2 blur-sm"
                         class="min-h-[200px] lg:min-h-[220px]">
                        <p class="mt-6 max-w-4xl font-display text-xl leading-relaxed text-white/90 sm:text-2xl">
                            {{ filled($companyProfile->vision) ? $companyProfile->vision : 'Menjadi gerakan kader muda yang berkemajuan dan mencerahkan.' }}
                        </p>
                    </div>
                    <div role="tabpanel" id="mission-panel" aria-labelledby="tab-mission"
                         x-show="tab === 'misi'"
                         x-transition:enter="transition-all duration-500 ease-[cubic-bezier(.22,1,.36,1)] motion-reduce:transition-none"
                         x-transition:enter-start="opacity-0 translate-y-3 blur-sm"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition-all duration-300 ease-in motion-reduce:transition-none"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2 blur-sm"
                         class="min-h-[200px] lg:min-h-[220px]">
                        <p class="mt-6 max-w-4xl font-display text-xl leading-relaxed text-white/90 sm:text-2xl">
                            {{ filled($companyProfile->mission) ? $companyProfile->mission : 'Menguatkan kader, menghidupkan dakwah, dan memberi manfaat nyata bagi umat.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
