@php $seo = (object) $seo; @endphp
@php $event = (object) $event; @endphp

@extends('poidu::layouts.app')

@section('title', 'Poidu | ' . $seo->title)

@section('content')
    <!-- Head -->
    @include('poidu::includes.head')
    <!-- End Head -->

    <!-- Full content -->
    <div class="cnt mb-4 flex-grow-1">
        <!-- todo попробовать вывести исходное описание -->
        <a href="{{ $event->link_to_post }}" class="btn btn-outline-primary w-100" type="button" target="_blank">Перейти в источник</a>
    </div>
    <!-- End Full content -->

@endsection