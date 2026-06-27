@extends('layouts.app')

@section('title', $tour['title'] . ' — ' . config('site.name'))
@section('meta_description', $tour['description'])

@section('content')
    @include('tours.partials.hero')
    @include('tours.partials.monuments')
    @include('tours.partials.tour-cards')
    @include('tours.partials.related-cities')
    @include('tours.partials.cta')
@endsection
