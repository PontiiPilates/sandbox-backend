<?php

namespace App\Domains\Poidu\App\src\Http\Controllers;

use App\Domains\Poidu\App\src\Repositories\CategoryRepository;
use App\Domains\Poidu\App\src\Repositories\EventMiningRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function __construct(
        private EventMiningRepository $eventMiningRepository,
        private CategoryRepository $categoryRepository,
    ) {}

    public function general(Request $request)
    {
        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories();

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Все туристические мероприятия Красноярска',
                'description' => 'Откройте для себя эпические приключения в горах, реках, лесах и за их пределами. Общайтесь с природой и единомышленниками.'
            ],
        ]);
    }

    public function event(Request $request)
    {
        $event = $this->eventMiningRepository->getEvent($request);
        $count = $this->eventMiningRepository->getCount();

        return view('poidu::pages.event', [
            'event' => $event->resolve(),
            'count' => $count,
            'seo' => [
                'title' => $event->title,
                'description' => $event->description,
            ],
        ]);
    }

    // --------------------+
    // Посадочные страницы |
    // --------------------+
    public function hiking(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 1]);

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories();

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Пешие походы в Красноярске',
                'description' => 'Походы выходного дня, однодневные и многодневные маршруты в Красноярске и окресностях по будням и выходным. Выбирайте поход под свой уровень подготовки.'
            ],
        ]);
    }
    public function excursions(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 3]);

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories();

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Групповые экскурсии в Красноярске',
                'description' => 'Обзорные, исторические и природные экскурсии по Красноярску и краю. Готовые маршруты с гидами. От центра города до заповедников.'
            ],
        ]);
    }
    public function tours(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 4]);

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories();

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Туристические туры по Красноярску и краю',
                'description' => 'От выходного дня до недельных экспедиций. Пакетные путешествия с проживанием и трансфером. Идеи для отдыха на любой сезон.'
            ],
        ]);
    }
    public function mountains(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 8]);

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories();

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Восхождения на горы Красноярска и края',
                'description' => 'От подъёма на видовки Николаевской сопки до покорения Боруса и Аргыджэка. Выбирайте сложность — от треккинга до технического подъёма.'
            ],
        ]);
    }
    public function speleo(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 7]);

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories();

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Спуски в пещеры Красноярска и края',
                'description' => 'Большая Орешная, Баджейская, Кубинская. Карточки спелеотуров для новичков и опытных групп.'
            ],
        ]);
    }

    public function water(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 2]);

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories();

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Сплавы по рекам Красноярска и края',
                'description' => 'Мана, Енисей, Катунь (выездные), рафт-туры на катамаранах и байдарках. Уровень сложности — от семейного до экстремального.'
            ],
        ]);
    }

    public function tournaments(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 9]);

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories();

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Соревнования в Красноярске',
                'description' => 'Спортивный туризм, ориентирование, техника пешеходного туризма, скалолазание, Скандинавская хотьба.'
            ],
        ]);
    }

    public function photo(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 10]);

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories();

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Туризм с упором на фотографию в Красноярске',
                'description' => 'Рассветы на Столбах, съёмка пещер и сплавов. С собой — любая камера.'
            ],
        ]);
    }

    public function child(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 5]);

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories();

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Куда сходить с детьми в Красноярске',
                'description' => 'На эти мероприятия можно брать с собой детей.'
            ],
        ]);
    }
}
