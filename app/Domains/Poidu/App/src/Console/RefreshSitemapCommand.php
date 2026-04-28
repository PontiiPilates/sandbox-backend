<?php

namespace App\Domains\Poidu\App\src\Console;

use App\Domains\Poidu\App\src\Repositories\CategoryRepository;
use App\Domains\Poidu\App\src\Repositories\EventMiningRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;

class RefreshSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'poidu:refresh-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(
        private CategoryRepository $categoryRepository,
        private EventMiningRepository $eventMiningRepository
    ) {
        return parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Начинается генерация нового sitemap");

        $sitemap = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');
        $pages = collect();

        $stables = collect([
            [
                'loc' => url('/'),
                'priority' => '1.0',
                'changefreq' => 'daily',
                'lastmod' => Carbon::now()->toDateString()
            ]
        ]);

        $categories = collect($this->categoryRepository->getCategories()->resolve());
        $categories = $categories->map(function ($category) {
            if ($category['count'] > 0) {
                return [
                    'loc' => route($category['alias']),
                    'priority' => '0.9',
                    'changefreq' => 'daily',
                    'lastmod' => Carbon::now()->toDateString()
                ];
            }
        })->filter();

        $events = collect($this->eventMiningRepository->getEvents()->resolve());
        $events = $events->map(function ($event) {
            return [
                'loc' => url("/event/{$event['id']}"),
                'priority' => '0.8',
                'changefreq' => 'daily',
                'lastmod' => Carbon::now()->toDateString()
            ];
        });

        $pages = $pages->concat($stables)->concat($categories)->concat($events);

        $pages->each(function ($page) use (&$sitemap) {
            $url = $sitemap->addChild('url');
            $url->addChild('loc', $page['loc']);
            $url->addChild('priority', $page['priority']);
            $url->addChild('changefreq', $page['changefreq']);
            $url->addChild('lastmod', $page['lastmod']);
        });

        $check = Storage::disk('public')->put('sitemap.xml', $sitemap->asXML());

        if ($check) {
            $this->info("Sitemap успешно сгенерирован");
        } else {
            $this->warn("Не удалось сохранить sitemap");
        }
    }
}
