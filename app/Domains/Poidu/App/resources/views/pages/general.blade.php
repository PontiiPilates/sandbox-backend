@php $seo = (object) $seo; @endphp

@extends('poidu::layouts.app')

@section('title', 'Poidu | ' . $seo->title)

@section('content')
    <!-- Head -->
    @include('poidu::includes.head')
    <!-- End Head -->

    <!-- Search -->
    @include('poidu::includes.search')
    <!-- End Search -->

    <!-- Category -->
    @include('poidu::includes.categories')
    <!-- End Category -->

    <!-- Events -->
    @include('poidu::includes.events')
    <!-- End Events -->
@endsection