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

    <div class="message-wrapper border-0 bg-white shadow rounded mb-4">

        <!-- Event Card -->
        @foreach($events as $event)
        @php $event = (object) $event @endphp
        <div class="card hover-state border-bottom rounded-0 py-3">
            <div class="card-body d-flex align-items-stretch flex-wrap flex-lg-nowrap py-0">

                <div class="col-10 col-lg-2 d-flex align-items-center justify-content-start me-3">
                    <img src="{{ $event->preview }}" class="rounded float-start" alt="Preview">
                </div>

                <div class="col-12 col-lg-7">
                    <h6 class="h6 fw-bold mb-0">{{ $event->title }}</h6><br>
                    <small class="text-gray-500"">{{ $event->description }}</small>
                </div>

                <div class=" col-2 col-lg-2 d-flex align-items-center justify-content-end px-0 order-lg-4">
                        <div class="text-muted small d-none d-lg-block">
                            <div>{{ $event->added_at->isoFormat('DD.MM в HH:mm') }}</div>
                            <div>
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

                            </div>
                            <div class="mb-0">{{ \CarboN\Carbon::parse($event->date_start)->isoFormat('DD MMM.') }}
                                @if($event->time_start == '00:00')
                                {{ 'время не указано' }}
                                @else()
                                в {{ $event->time_start }}
                                @endif
                            </div>
                        </div>

                        <!-- Dropdown -->
                        <div class="dropdown ms-3">
                            <button type="button" class="btn btn-sm fs-6 px-1 py-0 dropdown-toggle" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                            </button>
                            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('shrimplipiblz.admin.unPublic', [$event->id]) }}">
                                    <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                                        <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Снять с публикации
                                </a>
                                <!-- <a class="dropdown-item d-flex align-items-center" href="#">
                                    <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M2.94 6.412A2 2 0 002 8.108V16a2 2 0 002 2h12a2 2 0 002-2V8.108a2 2 0 00-.94-1.696l-6-3.75a2 2 0 00-2.12 0l-6 3.75zm2.615 2.423a1 1 0 10-1.11 1.664l5 3.333a1 1 0 001.11 0l5-3.333a1 1 0 00-1.11-1.664L10 11.798 5.555 8.835z" clip-rule="evenodd"></path>
                                    </svg>
                                    Опубликовать
                                </a> -->
                            </div>

                        </div>
                        <!-- End Dropdown -->

                </div>
            </div>
        </div>
        @endforeach
        <!-- End Event row -->

        <div class="row p-4">
            <div class="col-7 mt-1">{{ count($events) }}</div>
            <div class="col-5">
                <div class="btn-group float-end">
                    <a href="#" class="btn btn-gray-100">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <a href="#" class="btn btn-gray-800">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>