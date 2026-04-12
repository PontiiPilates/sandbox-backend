@php $seo = (object) $seo; @endphp

@extends('poidu::layouts.app')

@section('title', $seo->title)

@section('content')
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