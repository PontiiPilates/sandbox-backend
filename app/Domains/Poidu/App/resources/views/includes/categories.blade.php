<div class="cnt d-flex justify-content-start">
    <div>
        @foreach($categories as $category)
        @php $category = (object) $category; @endphp

        @if($category->count == 0)
        @continue
        @endif

        <a href="{{ $category->id }}" class="btn btn-outline-primary position-relative me-2 mb-2" type="button">
            {{ $category->name }}
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="background-color: gray!important ;">{{ $category->count }}</span>
        </a>
        @endforeach
    </div>
</div>