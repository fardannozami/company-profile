{{-- Admin: add an about section. --}}
@extends('layouts.admin')

@section('title', 'Add About Section')

@section('content')

<x-common.component-card title="Add About Section">

    {{-- ALERT --}}
    <x-common.alert />
    <x-common.alert type="success" />

    {{-- FORM COMPONENT --}}
    <x-admin.about-sections.form
        :action="route('admin.about-sections.store')"
    />

</x-common.component-card>

@endsection
