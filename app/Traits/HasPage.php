<?php
// app/Traits/HasPage.php

namespace App\Traits;

use App\Models\Page;

trait HasPage
{
    /**
     * Relation avec les pages
     */
    public function pages()
    {
        return $this->morphMany(Page::class, 'pageable');
    }

    /**
     * Récupérer la page active
     */
    public function activePage()
    {
        return $this->pages()->active()->first();
    }

    /**
     * Créer ou mettre à jour une page
     */
    public function savePage(array $data)
    {
        return $this->pages()->updateOrCreate(
            ['pageable_id' => $this->id, 'pageable_type' => self::class],
            $data
        );
    }
}