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
}
