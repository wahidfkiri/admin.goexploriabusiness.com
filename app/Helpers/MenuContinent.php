<?php
namespace App\Helpers;

use App\Models\Menu;
use App\Models\Country;

class MenuContinent
{
    public static function renderMenuContinent($continentId = null)
    {
        $menuDestinations = Country::with(['provinces' => function($query) {
            $query->where('is_active', true)->orderBy('name');
        }])
        ->where('continent_id', $continentId)
        ->where('is_active', true)
        ->get();

        $menus = Menu::with(['activeChildren' => function($query) {
            $query->with('activeChildren')->orderBy('order');
        }])
        ->where('menu_type', 'Destination')
        ->whereNull('parent_id')
        ->where('is_active', true)
        ->orderBy('order')
        ->get();

        return self::buildMenuHtml($menus, $menuDestinations);
    }

    private static function buildMenuHtml($menus, $destinations)
    {
        $html = '<!--begin: Main Navigation--><div id="mainMenu"><div class="container"><nav><ul>';
        
        // Ajouter le menu Destination en premier
        $html .= self::renderDestinationMegaMenu($destinations);
        
        foreach ($menus as $menu) {
            $hasChildren = $menu->activeChildren->isNotEmpty();
            $isMegaMenu = self::shouldBeMegaMenu($menu);
            
            $html .= '<li class="' . ($isMegaMenu ? 'dropdown mega-menu-item' : 'dropdown') . '">';
            $html .= '<a href="' . $menu->url . '">';
            
            if ($menu->icon) {
                $html .= '<i class="' . $menu->icon . ' me-1"></i>';
            }
            
            $html .= $menu->final_title . '</a>';
            
            if ($hasChildren) {
                if ($isMegaMenu) {
                    $html .= self::renderMegaMenu($menu->activeChildren);
                } else {
                    $html .= self::renderDropdownMenu($menu->activeChildren);
                }
            }
            
            $html .= '</li>';
        }
        
        // Ajouter le formulaire de recherche à la fin du menu
        $html .= self::renderSearchForm();
        
        $html .= '</ul></nav></div></div><!--end: Main Navigation-->';
        
        return $html;
    }

    private static function renderSearchForm()
    {
        $html = '<li class="search-menu-item">';
        $html .= '<div class="search-form-container">';
        $html .= '<form action="' . route('search') . '" method="GET" class="d-flex search-form">';
        $html .= '<div class="input-group">';
        $html .= '<input type="text" 
                        name="q" 
                        class="form-control search-input" 
                        placeholder="Rechercher..." 
                        aria-label="Rechercher" 
                        aria-describedby="search-button">';
        $html .= '<button class="btn btn-outline-primary search-button" type="submit" id="search-button">';
        $html .= '<i class="fas fa-search"></i>';
        $html .= '</button>';
        $html .= '</div>';
        $html .= '</form>';
        $html .= '</div>';
        $html .= '</li>';
        
        return $html;
    }

    private static function renderDestinationMegaMenu($countries)
    {
        $html = '<li class="dropdown">';
        $html .= '<a href="#">';
        $html .= '<i class="fas fa-globe-americas me-1"></i>';
        $html .= 'Destinations</a>';
        
        $html .= '<ul class="dropdown-menu" style="width:900px;">';
        $html .= '<li class="mega-menu-content"><div class="row">';
        
        // Organiser les countries en colonnes
        $columns = self::organizeContinentsIntoColumns($countries, 4);
        
        foreach ($columns as $columnIndex => $columnContinents) {
            
            foreach ($columnContinents as $countrie) {
            $html .= '<div class="col-lg-4">';
                $html .= '<div class="continent-section mb-4">';
                
                // En-tête du continent avec image
                $html .= '<div class="continent-header align-items-center mb-2">';
                if ($countrie->image) {
                    $html .= '<div class="continent-thumb me-3" style="width: 100%; height: 150px; border-radius: 8px; overflow: hidden;">';
                    $html .= '<img src="' . asset('storage/' . $countrie->image) . '" 
                            alt="' . $countrie->name . '" 
                            style="width: 100%; height: 100%; object-fit: cover;">';
                    $html .= '</div>';
                }
                $html .= '<h6 class="continent-title mb-0">';
                $html .= '<a href="#" 
                          class="text-dark fw-bold">';
                $html .= $countrie->name . '</a>';
                $html .= '</h6>';
                $html .= '</div>';
                
                // Liste des pays du continent
                if ($countrie->provinces && $countrie->provinces->isNotEmpty()) {
                    $html .= '<ul class="list-unstyled country-list">';
                    
                    // Organiser les pays en 2 colonnes si nécessaire
                    $countryChunks = $countrie->provinces->chunk(ceil($countrie->provinces->count() / 2));
                    
                    $html .= '<div class="row">';
                    foreach ($countryChunks as $chunk) {
                        $html .= '<div class="col-6">';
                        foreach ($chunk as $province) {
                            $html .= '<li class="country-item mb-1">';
                            $html .= '<a href="#" 
                                      class="text-muted small d-flex align-items-center">';
                            
                            // Optionnel : ajouter un drapeau du pays si disponible
                            if ($province->flag_emoji) {
                                $html .= '<span class="me-1">' . $province->flag_emoji . '</span>';
                            } elseif ($province->image) {
                                $html .= '<img src="' . asset('/storage/' . $province->image) . '" 
                                        alt="' . $province->name . '"
                                        style="width: 16px; height: 12px; object-fit: cover; margin-right: 4px;">';
                            }
                            
                            $html .= '<span class="">' . $province->name . '</span>';
                            
                            // Indicateur si le pays a des sous-pages
                            if ($province->cities_count > 0 || $province->regions_count > 0) {
                                $html .= '<small class="ms-1 text-primary"><i class="fas fa-chevron-right"></i></small>';
                            }
                            
                            $html .= '</a>';
                            $html .= '</li>';
                        }
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                    
                    $html .= '</ul>';
                } else {
                    $html .= '<p class="text-muted small mb-0">Aucun pays disponible</p>';
                }
                
                $html .= '</div>'; // Fin continent-section
            $html .= '</div>'; // Fin col-lg-
            }
            
        }
        
        $html .= '</div></li></ul>';
        $html .= '</li>';
        
        return $html;
    }

    private static function organizeContinentsIntoColumns($continents, $maxColumns = 4)
    {
        $total = $continents->count();
        
        // Vérifier si la collection est vide
        if ($total === 0) {
            return []; // Retourner un tableau vide si aucun continent
        }
        
        // S'assurer qu'on a au moins 1 colonne
        $columns = min($maxColumns, max(1, ceil($total / 3)));
        
        // Éviter la division par zéro
        $itemsPerColumn = ceil($total / $columns);
        $organized = [];
        
        $index = 0;
        for ($col = 0; $col < $columns; $col++) {
            $organized[$col] = [];
            for ($i = 0; $i < $itemsPerColumn && $index < $total; $i++) {
                $organized[$col][] = $continents[$index];
                $index++;
            }
        }
        
        return $organized;
    }

    private static function renderMegaMenu($children)
    {
        $html = '<ul class="dropdown-menu mega-menu">';
        $html .= '<li class="mega-menu-content"><div class="row">';
        
        // Organiser les enfants en colonnes (max 4 colonnes)
        $columns = self::organizeIntoColumns($children, 4);
        
        foreach ($columns as $columnIndex => $columnItems) {
            $html .= '<div class="col-lg-' . (12 / count($columns)) . '">';
            
            foreach ($columnItems as $item) {
                if ($item->level === 0) {
                    $html .= '<ul>';
                    $html .= '<li class="mega-menu-title">';
                    
                    if ($item->icon) {
                        $html .= '<i class="' . $item->icon . ' me-1"></i>';
                    }
                    
                    $html .= $item->final_title . '</li>';
                    
                    // Afficher les sous-enfants de cet item
                    if ($item->activeChildren->isNotEmpty()) {
                        foreach ($item->activeChildren as $subItem) {
                            $html .= '<li>';
                            $html .= '<a href="' . $subItem->final_url . '">';
                            
                            if ($subItem->icon) {
                                $html .= '<i class="' . $subItem->icon . ' me-1"></i>';
                            }
                            
                            $html .= $subItem->final_title . '</a>';
                            $html .= '</li>';
                        }
                    }
                    
                    $html .= '</ul>';
                }
            }
            
            $html .= '</div>';
        }
        
        $html .= '</div></li></ul>';
        
        return $html;
    }

    private static function renderDropdownMenu($children)
    {
        $html = '<ul class="dropdown-menu" style="width:400px;"><div class="mega-menu-content"><div class="row">';
        
        foreach ($children as $child) {
            $hasGrandChildren = $child->activeChildren->isNotEmpty();
            
            $html .= '<div class="col-lg-4"><li class="' . ($hasGrandChildren ? 'mega-menu-title' : '') . '">';
            $html .= '<a href="' . $child->url . '">';
            
            if ($child->icon) {
                $html .= '<i class="' . $child->icon . ' me-1"></i>';
            }
            
            $html .= $child->final_title;
            
            $html .= '</a>';
            
            if ($hasGrandChildren) {
                $html .= '<ul>';
                foreach ($child->activeChildren as $grandChild) {
                    $html .= '<li>';
                    $html .= '<a href="' . $grandChild->url . '">';
                    
                    if ($grandChild->icon) {
                        $html .= '<i class="' . $grandChild->icon . ' me-1"></i>';
                    }
                    
                    $html .= $grandChild->final_title . '</a>';
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }
            
            $html .= '</li></div>';
        }
        
        $html .= '</div></div></ul>';
        
        return $html;
    }

    private static function organizeIntoColumns($items, $maxColumns = 4)
    {
        $totalItems = $items->count();
        $columns = min($maxColumns, ceil($totalItems / 3));
        
        $itemsPerColumn = ceil($totalItems / $columns);
        $organized = [];
        
        $index = 0;
        for ($col = 0; $col < $columns; $col++) {
            $organized[$col] = [];
            for ($i = 0; $i < $itemsPerColumn && $index < $totalItems; $i++) {
                $organized[$col][] = $items[$index];
                $index++;
            }
        }
        
        return $organized;
    }

    private static function shouldBeMegaMenu($menu)
    {
        // Exclure "Destinations" de cette vérification car on le gère séparément
        if ($menu->title === 'Destinations') {
            return false;
        }
        
        $megaMenuTitles = [
            'Business', 'Local', 'Affaires', 
            'Prime Time', 'Web TV', 'Marketplace', 'Plan-N-Go'
        ];
        
        return in_array($menu->title, $megaMenuTitles) || 
               $menu->activeChildren->count() > 5;
    }
}