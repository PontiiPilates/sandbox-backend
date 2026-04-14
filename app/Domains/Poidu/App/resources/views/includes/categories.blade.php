<div class="cnt d-flex justify-content-start">
    <div>
        <!-- Default button -->
        <a href="{{ route('general') }}" class="btn btn-outline-primary position-relative me-2 mb-2 @if(request()->url() == route('general')) active @endif" type="button">
            Все
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="background-color: gray!important ;">{{ $count }}</span>
        </a>
        <!-- End Default button -->

        @foreach($categories as $category)
        @php $category = (object) $category; @endphp

        @if($category->count == 0)
        @continue
        @endif

        <a href="{{ route($category->alias) }}" class="btn btn-outline-primary position-relative me-2 mb-2 @if(request()->url() == route($category->alias)) active @endif" type="button">
            {{ $category->name }}
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="background-color: gray!important ;">{{ $category->count }}</span>
        </a>
        @endforeach
    </div>
</div>