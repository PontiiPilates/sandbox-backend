@php $seo = (object) $seo; @endphp

@extends('poidu::layouts.app')

@section('title', 'Poidu | ' . $seo->title)

@section('content')
    <!-- Events -->
    @include('poidu::includes.published')
    <!-- End Events -->
@endsection