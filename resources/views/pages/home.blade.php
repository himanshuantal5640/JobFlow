@extends('layouts.app')

@section('content')

@include('home.hero')
@include('components.ticker')
@include('home.search')
@include('home.jobs')
@include('home.features')
@include('home.how')
@include('home.roles')
@include('home.testimonials')
@include('home.cta')

@endsection