{{-- Admin page for editing the homepage texts shown on the public landing page. --}}

@extends('layouts.admin')

@section('title', 'Homepage Settings')

@section('content')

<x-common.component-card
    title="Homepage Settings"
    desc="Manage the texts shown on the public homepage (blank = default text)"
    class="admin-card"
>
    <x-admin.homepage-settings.form
        :companyProfile="$companyProfile"
    />
</x-common.component-card>
@endsection
