<?php

namespace App\Domains\Poidu\App\src\Http\Controllers;

use App\Domains\Poidu\App\src\Repositories\CategoryRepository;
use App\Domains\Poidu\App\src\Repositories\EventMiningRepository;
use App\Http\Controllers\Controller;
use App\Traits\TransformTrait;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    use TransformTrait;

    private object $seo;

    public function __construct(
        private EventMiningRepository $eventMiningRepository,
        private CategoryRepository $categoryRepository,
    ) {
        require_once('../storage/app/private/poidu/seo/metatags.php');
        $this->seo = $this->arrayToObject($seo);
    }

    public function general(Request $request)
    {
        $events = $this->eventMiningRepository->getEvents($request);
        $count = $this->eventMiningRepository->getCount();

        $categories = $this->categoryRepository->getCategories();

        return view('poidu::pages.general', [
            'events' => $events->resolve(),
            'count' => $count,
            'categories' => $categories->resolve(),
            'seo' => $this->seo->general
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
            'seo' => $this->seo->hiking,
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
            'seo' => $this->seo->excursions,
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
            'seo' => $this->seo->tours,
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
            'seo' => $this->seo->mountains,
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
            'seo' => $this->seo->speleo,
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
            'seo' => $this->seo->water,
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
            'seo' => $this->seo->tournaments,
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
            'seo' => $this->seo->photo,
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
            'seo' => $this->seo->child,
        ]);
    }
}
