{{-- Admin: edit an about section. --}}
@extends('layouts.admin')

@section('title', 'Edit About Section')

@section('content')

<x-common.component-card title="Edit About Section">

    {{-- ALERT --}}
    <x-common.alert />
    <x-common.alert type="success" />

    {{-- FORM COMPONENT --}}
    <x-admin.about-sections.form
        :action="route('admin.about-sections.update', $aboutSection)"
        method="PUT"
        :aboutSection="$aboutSection"
        showOrder
    />

</x-common.component-card>

@endsection
