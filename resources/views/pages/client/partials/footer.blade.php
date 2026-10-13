{{-- Site-wide footer: brand blurb, quick links, contact info, and social links pulled from the company profile. --}}
<footer class="relative bg-[#141834] text-white/70">
    <div aria-hidden="true" class="h-[2px] bg-gradient-to-r from-transparent via-[#c6a15b] to-transparent"></div>
    <div class="absolute inset-0 pattern-star opacity-40"></div>

    <div class="relative mx-auto max-w-7xl px-6 py-16">

        <!-- ================= TOP ================= -->
        <div class="grid grid-cols-1 gap-y-14 gap-x-20 md:grid-cols-4 items-start">

            <!-- ================= BRAND ================= -->
            <div>
                <div class="flex items-center gap-3">
                    @if (!empty($companyProfile->logo))
                        <img
                            src="{{ asset('storage/' . $companyProfile->logo) }}"
                            alt="{{ $companyProfile->company_name }}"
                            class="h-10 w-10 rounded-full object-cover ring-1 ring-[#c6a15b]/50"
                        >
                    @else
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#c6a15b] font-display text-lg italic text-[#141834]">
                            {{ Str::substr($companyProfile->company_name ?? 'P', 0, 1) }}
                        </div>
                    @endif

                    <span class="leading-tight">
                        <span class="block font-display text-lg font-medium text-white">
                            {{ $companyProfile->company_name ?? 'Pemuda Muhammadiyah DIY' }}
                        </span>
                        <span class="block text-[10px] font-semibold uppercase tracking-[0.24em] text-[#c6a15b]">
                            Muda · Mencerahkan
                        </span>
                    </span>
                </div>

                <p class="mt-5 max-w-sm text-sm leading-relaxed text-white/55">
                    {{ \Illuminate\Support\Str::limit(strip_tags($companyProfile->about
                        ?? 'Gerakan kader muda Islam yang berkemajuan: belajar, berkhidmat, dan memberi manfaat bagi umat dan bangsa.'), 160) }}
                </p>
            </div>

            <!-- ================= LINKS ================= -->
            <div>
                <h4 class="text-xs font-semibold uppercase tracking-[0.2em] text-[#c6a15b]">Jelajah</h4>

                @php
                    $linkBase = 'group flex items-center gap-3 text-white/60 transition hover:text-white';
                    $underline = 'absolute left-0 -bottom-1 h-px w-0 bg-[#c6a15b] transition-all duration-300 group-hover:w-full';
                @endphp

                <ul class="mt-5 space-y-4 text-sm">
                    <li>
                        <a href="{{ route('about') }}" class="{{ $linkBase }}">
                            <span class="relative">Tentang<span class="{{ $underline }}"></span></span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('articles') }}" class="{{ $linkBase }}">
                            <span class="relative">Artikel<span class="{{ $underline }}"></span></span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products') }}" class="{{ $linkBase }}">
                            <span class="relative">Produk<span class="{{ $underline }}"></span></span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('events') }}" class="{{ $linkBase }}">
                            <span class="relative">Kegiatan<span class="{{ $underline }}"></span></span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- ================= CONTACT ================= -->
            <div class="md:justify-self-end">
                <h4 class="text-xs font-semibold uppercase tracking-[0.2em] text-[#c6a15b]">Kontak</h4>

                <ul class="mt-5 space-y-4 text-sm">
                    @if (filled($companyProfile->address))
                        <li>
                            <a
                                href="https://www.google.com/maps/search/?api=1&query={{ urlencode($companyProfile->address) }}"
                                target="_blank" rel="noopener noreferrer"
                                class="{{ $linkBase }}"
                            >
                                <span class="relative">
                                    {{ $companyProfile->address }}
                                    <span class="{{ $underline }}"></span>
                                </span>
                            </a>
                        </li>
                    @endif

                    @if (filled($companyProfile->phone))
                        <li>
                            <a href="tel:{{ preg_replace('/\s+/', '', $companyProfile->phone) }}" class="{{ $linkBase }}">
                                <span class="relative">{{ $companyProfile->phone }}<span class="{{ $underline }}"></span></span>
                            </a>
                        </li>
                    @endif

                    @if (filled($companyProfile->email))
                        <li>
                            <a href="mailto:{{ $companyProfile->email }}" class="{{ $linkBase }}">
                                <span class="relative">{{ $companyProfile->email }}<span class="{{ $underline }}"></span></span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- ================= SOCIAL ================= -->
            <div>
                <h4 class="text-xs font-semibold uppercase tracking-[0.2em] text-[#c6a15b]">Media Sosial</h4>

                <ul class="mt-5 space-y-4 text-sm">
                    <li>
                        <a
                            href="{{ filled($companyProfile->whatsapp)
                                ? 'https://wa.me/' . preg_replace('/\D/', '', $companyProfile->whatsapp)
                                : '#' }}"
                            target="_blank" rel="noopener noreferrer"
                            class="{{ $linkBase }}"
                        >
                            <span class="relative">WhatsApp<span class="{{ $underline }}"></span></span>
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ filled($companyProfile->instagram)
                                ? (str_starts_with($companyProfile->instagram, 'http')
                                    ? $companyProfile->instagram
                                    : 'https://instagram.com/' . ltrim($companyProfile->instagram, '@'))
                                : '#' }}"
                            target="_blank" rel="noopener noreferrer"
                            class="{{ $linkBase }}"
                        >
                            <span class="relative">Instagram<span class="{{ $underline }}"></span></span>
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- ================= BOTTOM ================= -->
        <div class="pt-8 mt-14 border-t border-white/10 text-center text-xs text-white/45">
            © {{ date('Y') }}
            <span class="font-display italic text-[#c6a15b]">
                {{ $companyProfile->company_name ?? 'Pemuda Muhammadiyah DIY' }}
            </span>
            · Seluruh hak cipta dilindungi.
        </div>

    </div>
</footer>
