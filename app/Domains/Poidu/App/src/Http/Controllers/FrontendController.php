<?php

namespace App\Domains\Poidu\App\src\Http\Controllers;

use App\Domains\Poidu\App\src\Repositories\CategoryRepository;
use App\Domains\Poidu\App\src\Repositories\EventMiningRepository;
use App\Http\Controllers\Controller;
use danog\MadelineProto\API;
use danog\MadelineProto\Settings\AppInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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

        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Все туристические мероприятия красноярска',
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

        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
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

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
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

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
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

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
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

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
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

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Сплавы в красноярске',
                'description' => 'В разработке'
            ],
        ]);
    }

    public function tournaments(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 9]);

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Соревнования в красноярске',
                'description' => 'В разработке'
            ],
        ]);
    }

    public function photo(Request $request)
    {
        $request->merge(['column' => 'category']);
        $request->merge(['value' => 10]);

        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories($request);

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => [
                'title' => 'Туризм с упором на фотографию в Красноярске',
                'description' => 'В разработке'
            ],
        ]);
    }

    public function auth()
    {
        $apiId = config('services.parsing.tg.madeline_proto.api_id');
        $apiHash = config('services.parsing.tg.madeline_proto.api_hash');
        $pathToSessionCheck = config('services.parsing.tg.madeline_proto.path_to_session_check');
        $pathToSession = config('services.parsing.tg.madeline_proto.path_to_session');

        // если нет пути для хранения сессии - он будет создан
        if (!Storage::directoryExists($pathToSessionCheck)) {
            Storage::makeDirectory($pathToSessionCheck);
        }

        $settings = new AppInfo();
        $settings->setApiId($apiId);
        $settings->setApiHash($apiHash);

        $madelineProto = new API($pathToSession, $settings);

        $madelineProto->start();

        dd($madelineProto->getSelf());
    }
}
