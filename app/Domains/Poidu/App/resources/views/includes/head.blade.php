<!-- Head -->
<div class="cnt d-block my-4">

    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="cnt p-0 d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="{{ route('general') }}">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </a>
            </li>

            @if(request()->path() == '/')
                <li class="breadcrumb-item active" aria-current="page">Вы дома</li>
            @endif

            @foreach($categories as $category)
                @php $category = (object) $category; @endphp
                    @if(request()->path() == $category->alias)
                    <li class="breadcrumb-item"><a href="{{ route('general') }}">Главная</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                @endif
            @endforeach
        </ol>
    </nav>
    <!-- End Breadcrumbs -->

    @if(isset($event->preview))
    <img src="{{ $event->preview }}" class="img-fluid mb-4" alt="...">
    @endif


    <!-- Title -->
    <h1 class="h3">{{ $seo->title }}</h1>
    <!-- End Title -->

    <!-- Description -->
    <p class="mb-0">{{ $seo->description }}</p>
    <!-- End Description -->
     
</div>
<!-- End Head -->