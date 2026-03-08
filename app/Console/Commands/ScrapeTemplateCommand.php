<?php
// app/Console/Commands/ScrapeTemplateCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RoachPHP\Roach;
use RoachPHP\Spider\Configuration\Overrides;
use App\Spiders\TemplateSpider;

class ScrapeTemplateCommand extends Command
{
    protected $signature = 'scrape:template {url : The URL to scrape} 
                          {--name= : Name for the template}
                          {--save : Save to database}';

    protected $description = 'Scrape HTML body and CSS from a URL';

    public function handle()
    {
        $url = $this->argument('url');
        $name = $this->option('name') ?? basename($url);
        $shouldSave = $this->option('save');

        $this->info("Starting scrape for: {$url}");

        $items = Roach::collectSpider(
            TemplateSpider::class,
            new Overrides(startUrls: [$url])
        );

        if ($shouldSave && !empty($items)) {
            $template = \App\Models\Template::create([
                'name' => $name,
                'url' => $url,
                'html_content' => $items[0]['html_body'],
                'css_content' => $items[0]['css_content'],
                'metadata' => $items[0]['metadata']
            ]);
            
            $this->info("Template saved with ID: {$template->id}");
        }

        return Command::SUCCESS;
    }
}