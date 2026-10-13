{{-- Admin page listing about sections in display order with edit/delete actions and pagination. --}}
@extends('layouts.admin')

@section('title', 'About Sections')

@section('content')
<x-common.component-card title="About Sections">

    {{-- HEADER --}}
    <div class="mb-4 flex items-center justify-between">
        <p class="text-sm text-text-muted">
            Manage the sections shown on the About page
        </p>

        <a href="{{ route('admin.about-sections.create') }}"
           class="btn-primary">
            + Add Section
        </a>
    </div>

    {{-- LIST --}}
    <div class="grid grid-cols-1 gap-4">

        @forelse ($aboutSections as $aboutSection)
            <div class="rounded-xl border border-app-border overflow-hidden">

                <div class="p-4 space-y-1 text-sm">
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="font-medium">
                            {{ $aboutSection->title ?? 'Untitled' }}
                        </p>

                        @if ($aboutSection->is_active)
                            <span class="rounded-full bg-green-500/10 px-2 py-0.5 text-xs text-green-500">Active</span>
                        @else
                            <span class="rounded-full bg-app-surface-2 px-2 py-0.5 text-xs text-app-muted">Inactive</span>
                        @endif
                    </div>

                    <p class="text-xs text-app-muted">
                        #profil-{{ $aboutSection->slug }} · Order: {{ $aboutSection->order }}
                    </p>

                    <div class="mt-3 flex justify-between gap-2">
                        <a href="{{ route('admin.about-sections.edit', $aboutSection) }}"
                           class="btn-admin text-xs">
                            Edit
                        </a>

                        <form method="POST"
                              action="{{ route('admin.about-sections.destroy', $aboutSection) }}"
                              onsubmit="return confirm('Delete this section?')">
                            @csrf
                            @method('DELETE')

                            <button class="btn-danger text-xs">
                                Delete
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="admin-table-empty">
                No about sections found
            </div>
        @endforelse

    </div>

    <div class="mt-6">
        {{ $aboutSections->links() }}
    </div>

</x-common.component-card>
@endsection
