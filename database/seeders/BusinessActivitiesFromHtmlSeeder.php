<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Category;
use App\Models\CategorieType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BusinessActivitiesFromHtmlSeeder extends Seeder
{
    /**
     * Parse the business category menu HTML and create activities by category.
     */
    public function run(): void
    {
        $htmlPath = database_path('seeders/data/business_activities_menu.html');

        if (!is_file($htmlPath)) {
            $this->command?->error('Fichier introuvable: ' . $htmlPath);
            $this->command?->line('Ajoutez le HTML du menu business dans ce fichier puis relancez le seeder.');
            return;
        }

        $html = file_get_contents($htmlPath);
        if ($html === false || trim($html) === '') {
            $this->command?->error('Le fichier HTML est vide.');
            return;
        }

        $businessType = CategorieType::withTrashed()->firstOrCreate(
            ['slug' => Str::slug('Business')],
            ['name' => 'Business', 'is_active' => true]
        );

        if ($businessType->trashed()) {
            $businessType->restore();
        }

        $businessType->update(['name' => 'Business', 'is_active' => true]);

        [$nodesByCategory, $categoriesCount] = $this->parseHtmlByCategory($html);

        if ($categoriesCount === 0) {
            $this->command?->warn('Aucune categorie detectee. Verifiez le HTML.');
            return;
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($nodesByCategory as $categoryName => $activities) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($categoryName)],
                [
                    'name' => $categoryName,
                    'categorie_type_id' => $businessType->id,
                    'description' => null,
                    'is_active' => true,
                ]
            );

            if ((int) $category->categorie_type_id !== (int) $businessType->id) {
                $category->categorie_type_id = $businessType->id;
                $category->is_active = true;
                $category->save();
            }

            foreach ($activities as $row) {
                $name = trim($row['name'] ?? '');
                if ($name === '') {
                    $skipped++;
                    continue;
                }

                $activity = Activity::where('categorie_id', $category->id)
                    ->where('name', $name)
                    ->first();

                $tagsPayload = [
                    'source_activity_id' => $row['source_activity_id'] ?? null,
                    'source_url' => $row['source_url'] ?? null,
                    'source' => 'business_html_menu',
                ];

                if (!$activity) {
                    $activity = new Activity();
                    $activity->name = $name;
                    $activity->categorie_id = $category->id;
                    $activity->type = $businessType->id;
                    $activity->description = null;
                    $activity->image = null;
                    $activity->tags = json_encode($tagsPayload, JSON_UNESCAPED_UNICODE);
                    $activity->is_active = true;
                    $activity->slug = $this->buildUniqueSlug($name, $categoryName);
                    $activity->save();
                    $created++;
                } else {
                    $activity->type = $businessType->id;
                    $activity->tags = json_encode($tagsPayload, JSON_UNESCAPED_UNICODE);
                    $activity->is_active = true;
                    $activity->save();
                    $updated++;
                }
            }
        }

        $this->command?->info("Business activities seed termine. Categories: {$categoriesCount}, creees: {$created}, mises a jour: {$updated}, ignorees: {$skipped}");
    }

    /**
     * @return array{0: array<string, array<int, array{name:string,source_activity_id:?int,source_url:?string}>>, 1: int}
     */
    private function parseHtmlByCategory(string $html): array
    {
        $nodesByCategory = [];

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $categoryLis = $xpath->query('//ul[contains(@class,"bar")]/li[contains(@class,"btn-success")]');

        if (!$categoryLis) {
            return [[], 0];
        }

        foreach ($categoryLis as $li) {
            $titleNode = $xpath->query('.//a[contains(@class,"list_item")]', $li)?->item(0);
            if (!$titleNode) {
                continue;
            }

            $categoryName = trim(html_entity_decode($titleNode->textContent ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            if ($categoryName === '') {
                continue;
            }

            $activityAnchors = $xpath->query('.//ul[contains(@class,"sub")]//a[contains(@class,"self-select")]', $li);
            if (!$activityAnchors) {
                continue;
            }

            foreach ($activityAnchors as $anchor) {
                $activityName = trim(html_entity_decode($anchor->textContent ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                $sourceIdRaw = $anchor->attributes?->getNamedItem('data-id')?->nodeValue;
                $sourceUrl = $anchor->attributes?->getNamedItem('href')?->nodeValue;

                if ($activityName === '') {
                    continue;
                }

                $nodesByCategory[$categoryName][] = [
                    'name' => preg_replace('/\s+/', ' ', $activityName) ?? $activityName,
                    'source_activity_id' => is_numeric($sourceIdRaw) ? (int) $sourceIdRaw : null,
                    'source_url' => $sourceUrl ? trim($sourceUrl) : null,
                ];
            }
        }

        return [$nodesByCategory, count($nodesByCategory)];
    }

    private function buildUniqueSlug(string $activityName, string $categoryName): string
    {
        $base = Str::slug($activityName);
        if ($base === '') {
            $base = 'activity';
        }

        if (!Activity::where('slug', $base)->exists()) {
            return $base;
        }

        $withCategory = Str::slug($activityName . '-' . $categoryName);
        if ($withCategory !== '' && !Activity::where('slug', $withCategory)->exists()) {
            return $withCategory;
        }

        $i = 2;
        do {
            $candidate = $base . '-' . $i;
            $exists = Activity::where('slug', $candidate)->exists();
            $i++;
        } while ($exists);

        return $candidate;
    }
}

