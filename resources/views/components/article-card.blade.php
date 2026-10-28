@props(['article'])

{{-- Reusable article preview card — editorial: tanggal serif, garis emas hover. --}}
<a href="{{ route('articles.show', $article->slug) }}"
   class="group surface surface-hover gold-topline flex flex-col overflow-hidden rounded-2xl">

    <div class="aspect-[16/9] overflow-hidden bg-app-surface-2">
        @if ($article->thumbnail)
            <img src="{{ asset('storage/'.$article->thumbnail) }}" alt="{{ $article->title }}"
                 class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
        @else
            <div class="flex h-full items-center justify-center text-app-muted" aria-hidden="true">
                <svg class="h-10 w-10 opacity-50" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.5-4.5a2 2 0 012.8 0L16 16m-2-2l1.5-1.5a2 2 0 012.8 0L20 14M4 6h16v12H4z"/>
                </svg>
            </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-6">
        <div class="mb-2 flex items-center gap-2 text-xs text-app-muted">
            <svg class="h-4 w-4 text-[#a5813d]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
            </svg>
            {{ $article->published_at?->format('d M Y') ?? '-' }}
        </div>

        <h3 class="font-display text-xl font-medium leading-snug text-app-heading transition group-hover:text-brand-accent">
            {{ $article->title }}
        </h3>

        @if ($article->excerpt)
            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-app-muted">{{ $article->excerpt }}</p>
        @endif

        <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-medium text-brand-accent transition group-hover:gap-2.5">
            Baca artikel
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </span>
    </div>
</a>
