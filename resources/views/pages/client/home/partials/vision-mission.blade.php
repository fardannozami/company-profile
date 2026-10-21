{{-- Halaman bagian: Visi & Misi dalam satu kartu editorial dengan kontrol tab. --}}
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
            class="mt-12 surface gold-topline relative overflow-hidden rounded-2xl border border-[#141834]/12 p-6 sm:p-8 max-w-4xl mx-auto lg:max-w-5xl">

            <div class="flex flex-col lg:flex lg:items-start lg:gap-8">

                {{-- Kontrol tab: numeralis besar sebagai tombol (baris 2 kolom di mobile, rail kiri di desktop) --}}
                <div role="tablist" aria-label="Pilih Visi atau Misi" @keydown="move($event)"
                     class="grid grid-cols-2 border border-[#141834]/20 rounded-xl overflow-hidden
                            mb-6 sm:mb-8
                            lg:flex lg:flex-col lg:w-[260px] lg:flex-shrink-0 lg:border-0 lg:rounded-none lg:mb-0">

                    {{-- Visi (aktif secara default) --}}
                    <button type="button" role="tab" id="tab-vision" aria-controls="vision-panel"
                            :aria-selected="tab === 'visi'"
                            :tabindex="tab === 'visi' ? 0 : -1"
                            @click="select(0)"
                            class="flex w-full flex-col items-start gap-1.5 sm:flex-row sm:items-center sm:justify-between
                                   px-5 sm:px-6 py-4 sm:py-4.5 lg:py-7 lg:px-7 text-left
                                   bg-transparent
                                   border-r border-r-[#141834]/20 lg:border-r-0 lg:border-b lg:border-b-[#141834]/20
                                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#141834] focus-visible:ring-offset-2 focus-visible:ring-offset-[#faf7f0]
                                   transition-colors duration-200"
                            :class="tab === 'visi' ? 'bg-[#141834]' : 'bg-transparent'">
                        <span class="font-display text-5xl leading-none"
                              :class="tab === 'visi' ? 'text-[#c6a15b]' : 'text-[#c6a15b]/30'"
                              aria-hidden="true">01</span>
                        <span class="text-[11px] font-semibold uppercase tracking-[0.22em]"
                              :class="tab === 'visi' ? 'text-white' : 'text-[#141834]/60'">Visi</span>
                    </button>

                    {{-- Misi --}}
                    <button type="button" role="tab" id="tab-mission" aria-controls="mission-panel"
                            :aria-selected="tab === 'misi'"
                            :tabindex="tab === 'misi' ? 0 : -1"
                            @click="select(1)"
                            class="flex w-full flex-col items-start gap-1.5 sm:flex-row sm:items-center sm:justify-between
                                   px-5 sm:px-6 py-4 sm:py-4.5 lg:py-7 lg:px-7 text-left
                                   bg-transparent
                                   focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#141834] focus-visible:ring-offset-2 focus-visible:ring-offset-[#faf7f0]
                                   transition-colors duration-200"
                            :class="tab === 'misi' ? 'bg-[#141834]' : 'bg-transparent'">
                        <span class="font-display text-5xl leading-none"
                              :class="tab === 'misi' ? 'text-[#c6a15b]' : 'text-[#c6a15b]/30'"
                              aria-hidden="true">02</span>
                        <span class="text-[11px] font-semibold uppercase tracking-[0.22em]"
                              :class="tab === 'misi' ? 'text-white' : 'text-[#141834]/60'">Misi</span>
                    </button>
                </div>

                {{-- Isi kartu: satu panel kontinyu dengan tinggi stabil saat beralih tab --}}
                <div class="flex-1 lg:min-w-0">
                    <div role="tabpanel" id="vision-panel" aria-labelledby="tab-vision"
                         x-show="tab === 'visi'"
                         x-transition:enter="transition-opacity duration-150 ease-out motion-reduce:transition-none"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition-opacity duration-150 ease-in motion-reduce:transition-none"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="min-h-[240px] lg:min-h-[320px]">
                        <p class="mt-6 font-display text-xl leading-relaxed text-app-heading sm:text-2xl">
                            {{ filled($companyProfile->vision) ? $companyProfile->vision : 'Menjadi gerakan kader muda yang berkemajuan dan mencerahkan.' }}
                        </p>
                    </div>
                    <div role="tabpanel" id="mission-panel" aria-labelledby="tab-mission"
                         x-show="tab === 'misi'"
                         x-transition:enter="transition-opacity duration-150 ease-out motion-reduce:transition-none"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition-opacity duration-150 ease-in motion-reduce:transition-none"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="min-h-[240px] lg:min-h-[320px]">
                        <p class="mt-6 font-display text-xl leading-relaxed text-app-heading sm:text-2xl">
                            {{ filled($companyProfile->mission) ? $companyProfile->mission : 'Menguatkan kader, menghidupkan dakwah, dan memberi manfaat nyata bagi umat.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
