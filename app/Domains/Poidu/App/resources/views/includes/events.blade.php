<div class="cnt py-4 flex-grow-1">

    <!-- Если запрос на поиск есть, а результата нет и категория не выбрана -->
    @if( request()->has('search') && empty($events) && request()->path() == '/')
    <p class="h4">По запросу «{{ request()->input('search') }}» ничего не нашлось</p>
    @endif
    <!-- Если запрос на поиск есть, а результата нет и категория не выбрана -->

    <!-- Если запрос на поиск есть, а результата нет, но категория выбрана -->
    @if( request()->has('search') && empty($events) && request()->path() != '/' )

    @foreach($categories as $category)
        @php $category = (object) $category; @endphp
        @if($category->alias == request()->path())
            @php $category = $category->name; @endphp
            @break
        @endif
    @endforeach

    <p class="h4">По запросу «{{ request()->input('search') }}» в категории «{{ $category }}» ничего не нашлось</p>
    @endif
    <!-- Если запрос на поиск есть, а результата нет, но категория выбрана -->

    <div class="row g-3">
        <!-- Event Card -->
        @foreach($events as $event)
        @php $event = (object) $event @endphp
        <div class="col-12 col-lg-6 col-xl-4">
            <a href="{{ route('event', ['id' => $event->id]) }}">
                <img src="{{ $event->preview }}" class="card-img-top" alt="...">
                <div class="card border-0 shadow p-4 pb-0">
                    <div class="card-header d-flex align-items-center justify-content-between border-0 p-0 mb-3">
                        <h5 class="card-title">{{ Str::limit($event->title, 200) }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <p class="card-text text-gray-500 small">{{ Str::limit($event->description, 400) }}</p>
                        <div class="d-flex align-items-center justify-content-start">
                            <svg class="icon icon-xs text-gray-600 me-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                            </svg>
                            <h4 class="mb-0">
                                @if($event->price_min > 0 && $event->price_max == 0)
                                    {{ number_format($event->price_min, 0, '.', ' ') }}
                                @elseif($event->price_min == 0 && $event->price_max > 0)
                                    {{ number_format($event->price_max, 0, '.', ' ') }}
                                @elseif($event->price_min == 0 && $event->price_max == 0)
                                    {{ 'Цена не указана' }}
                                @elseif($event->price_min > 0 && $event->price_max > 0 && $event->price_min < $event->price_max)
                                    {{ number_format($event->price_min, 0, '.', ' ') }} — {{ number_format($event->price_max, 0, '.', ' ') }}
                                @elseif($event->price_min > 0 && $event->price_max > 0 && $event->price_min == $event->price_max)
                                    {{ number_format($event->price_max, 0, '.', ' ') }}
                                @endif
                            </h4>
                        </div>
                        <div class="d-flex align-items-center justify-content-start">
                            <svg class="icon icon-xs text-gray-600 me-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            <h6 class="mb-0">{{ \CarboN\Carbon::parse($event->date_start)->isoFormat('DD MMM.') }}
                                @if($event->time_start == '00:00')
                                    {{ 'время не указано' }}
                                @else()
                                    в {{ $event->time_start }}
                                @endif
                            </h6>
                        </div>
                        <div class="d-flex align-items-center justify-content-end mt-3 mb-2">
                            <svg class="icon icon-xs text-gray-300 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                            <small class="mb-0 text-gray-300">{{ $event->views }}</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
        <!-- End Event card -->
    </div>
</div>