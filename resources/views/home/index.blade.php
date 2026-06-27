@extends('layouts.app')

@section('title', config('site.name') . ' — ' . config('site.tagline'))

@section('content')
    @include('home.sections.hero')
    @include('home.sections.stats')
    @include('home.sections.cities-showcase')
    @include('home.sections.popular-tours')
    @include('home.sections.delhi-agra-spotlight')
    @include('home.sections.golden-triangle')
    @include('home.sections.more-destinations')
    @include('home.sections.story')
    @include('home.sections.process')
    @include('home.sections.reviews')
    @include('home.sections.cta')
@endsection
