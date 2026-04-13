@php $seo = (object) $seo; @endphp
@php $event = (object) $event; @endphp

@extends('poidu::layouts.app')

@section('content')
    <!-- Head -->
    @include('poidu::includes.head')
    <!-- End Head -->

    <div class="cnt mb-4">
        <!-- todo попробовать вывести исходное описание -->
        <a href="{{ $event->link_to_post }}" class="btn btn-outline-primary w-100" type="button" target="_blank">Перейти в источник</a>
    </div>

@endsection