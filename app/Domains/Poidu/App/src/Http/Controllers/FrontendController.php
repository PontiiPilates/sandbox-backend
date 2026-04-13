<?php

namespace App\Domains\Poidu\App\src\Http\Controllers;

use App\Domains\Poidu\App\src\Repositories\CategoryRepository;
use App\Domains\Poidu\App\src\Repositories\EventRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FrontendController extends Controller
{
    public function __construct(
        private EventRepository $eventRepository,
        private CategoryRepository $categoryRepository,
    ) {}

    public function general(Request $request)
    {
        $events = $this->eventRepository->getEvents($request);
        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Все туристические мероприятия красноярска',
                'description' => 'Откройте для себя эпические приключения в горах, реках, лесах и за их пределами. Общайтесь с природой и единомышленниками.'
            ],
        ]);
    }

    public function event(Request $request)
    {
        $event = $this->eventRepository->getEvent($request);

        return view('poidu::pages.event', [
            'event' => $event->resolve(),
            'seo' => [
                'title' => $event->title,
                'description' => $event->description,
            ],
        ]);
    }

    // -----------------------+
    // Для посадочных страниц |
    // -----------------------+
    public function hiking(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 1]);

        $events = $this->eventRepository->getEvents($request);
        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Походы в красноярске',
                'description' => 'В разработке'
            ],
        ]);
    }
    public function excursions(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 3]);

        $events = $this->eventRepository->getEvents($request);
        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Экскурсии в Красноярске',
                'description' => 'В разработке'
            ],
        ]);
    }
    public function tours(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 4]);

        $events = $this->eventRepository->getEvents($request);
        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Туристические туры по Красноярску и краю',
                'description' => 'В разработке'
            ],
        ]);
    }
    public function mountains(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 8]);

        $events = $this->eventRepository->getEvents($request);
        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Восхождения на горы Красноярска и края',
                'description' => 'В разработке'
            ],
        ]);
    }
    public function speleo(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 7]);

        $events = $this->eventRepository->getEvents($request);
        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Спуски в пещеры Красноярска и края',
                'description' => 'В разработке'
            ],
        ]);
    }
    public function water(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 2]);

        $events = $this->eventRepository->getEvents($request);
        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Сплавы в красноярске',
                'description' => 'В разработке'
            ],
        ]);
    }
}
