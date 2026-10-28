{{-- Landing section: latest published articles. --}}
<section class="bg-app-bg py-24 lg:py-28">
    <div class="mx-auto max-w-7xl px-6">

        <div x-data x-reveal class="mb-14 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <span class="section-index">04 — Wawasan</span>
                <x-section-heading
                    eyebrow="Artikel terbaru"
                    title="Cerita, gagasan, dan langkah kami"
                    subtitle="Baca kabar terbaru, insight, dan catatan dari perjalanan kami." />
            </div>
            <a href="{{ route('articles') }}" class="btn-ghost btn-sm self-start sm:self-auto">
                Lihat semua artikel &gt;
            </a>
        </div>

        <div x-data x-reveal class="grid grid-cols-1 gap-5 md:grid-cols-3">
            @forelse ($articles as $article)
                <x-article-card :article="$article" />
            @empty
                <div class="surface col-span-full rounded-2xl py-16 text-center text-app-muted">
                    Belum ada artikel yang diterbitkan.
                </div>
            @endforelse
        </div>
    </div>
</section>
