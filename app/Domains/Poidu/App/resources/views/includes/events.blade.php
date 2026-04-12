<div class="cnt py-4">
    <div class="row g-3">
        <!-- Event Card -->
        @foreach($events as $event)
        @php $event = (object) $event @endphp
        <div class="col-12 col-lg-6 col-xl-4">
            <div class="card border-0 shadow p-4">
                <a href="{{ route('event', ['id' => $event->id]) }}">
                    <div class="card-header d-flex align-items-center justify-content-between border-0 p-0 mb-3">
                        <h3 class="h5 mb-0">{{ $event->title }}</h3>
                    </div>
                    <div class="card-body p-0">
                        <img src="{{ $event->preview }}" class="card-img-top mb-2 mb-lg-3" alt="themesberg marketplace" draggable="false">
                        <p>{{ $event->description }}</p>
                        <div>
                            <p><b><small>время начала: </small></b>{{ $event->date_start }} в {{ $event->time_start }}</p>
                            <p><b><small>стоимость: </small></b>{{ $event->price_min }} - {{ $event->price_max }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
        <!-- End Event card -->
    </div>
</div>