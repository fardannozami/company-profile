{{-- Landing section: closing call-to-action banner. --}}
@php
    $waLink = filled($companyProfile->whatsapp)
        ? 'https://wa.me/' . preg_replace('/\D/', '', $companyProfile->whatsapp)
        : null;
@endphp

<x-cta :eyebrow="$companyProfile->contact_eyebrow ?? 'Mari terhubung'"
       :title="$companyProfile->contact_title ?? 'Ingin berkolaborasi dengan kami?'"
       :subtitle="$companyProfile->contact_subtitle ?? 'Sampaikan pesan Anda — insyaallah kami balas dalam satu hari kerja.'">
    <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
        <a href="{{ route('contact') }}" class="btn-primary btn-lg">Kirim pesan</a>
        @if ($waLink)
            <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="btn-outline btn-lg border-white/25 text-white hover:border-[#c6a15b] hover:bg-[#c6a15b]/10">Chat WhatsApp</a>
        @endif
    </div>

    @if (filled($companyProfile->email) || filled($companyProfile->phone))
        <div class="mt-8 flex flex-wrap items-center justify-center gap-x-8 gap-y-2 text-sm text-white/60">
            @if (filled($companyProfile->email))
                <a href="mailto:{{ $companyProfile->email }}" class="transition hover:text-white">{{ $companyProfile->email }}</a>
            @endif
            @if (filled($companyProfile->phone))
                <a href="tel:{{ $companyProfile->phone }}" class="transition hover:text-white">{{ $companyProfile->phone }}</a>
            @endif
        </div>
    @endif
</x-cta>
