{{-- Homepage: assembles the landing page sections. --}}
@extends('layouts.app')

@section('title', 'Home')

@section('content')
    @if ($companyProfile->show_hero ?? true)
        @include('pages.client.home.partials.hero')
    @endif
    @if ($companyProfile->show_vision ?? true)
        @include('pages.client.home.partials.vision-mission')
    @endif
    @if ($companyProfile->show_gallery ?? true)
        @include('pages.client.home.partials.gallery-carousel')
    @endif
    @if ($companyProfile->show_products ?? true)
        @include('pages.client.home.partials.products')
    @endif
    @if ($companyProfile->show_clients ?? true)
        @include('pages.client.home.partials.clients')
    @endif
    @if ($companyProfile->show_contact ?? true)
        @include('pages.client.home.partials.contact')
    @endif
    @if ($companyProfile->show_location ?? true)
        @include('pages.client.home.partials.location')
    @endif
@endsection
