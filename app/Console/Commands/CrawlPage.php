<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Crawler\Crawler;
use App\Crawlers\PageContentObserver;

class CrawlPage extends Command
{
    protected $signature = 'crawl:page {url}';
    protected $description = 'Crawl HTML and CSS content of a page';

    public function handle()
    {
        $url = $this->argument('url');

        $this->info("Crawling: {$url}");

        Crawler::create()
            ->setCrawlObserver(new PageContentObserver())
            ->setMaximumDepth(0) // only the given page
            ->startCrawling($url);

        $this->info('Crawl finished.');
    }
}
