{{-- Admin page for editing the homepage texts shown on the public landing page. --}}

@extends('layouts.admin')

@section('title', 'Homepage Settings')

@section('content')

<x-common.component-card
    title="Pengaturan Homepage"
    desc="Kelola teks yang tampil di beranda publik (kosongkan untuk memakai teks bawaan)"
    class="admin-card"
>
    <x-admin.homepage-settings.form
        :companyProfile="$companyProfile"
    />
</x-common.component-card>
@endsection
