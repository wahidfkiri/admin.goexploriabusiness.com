<?php

namespace Vendor\Cms\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BlockController extends Controller
{
    /**
     * Affiche la page de gestion des blocks
     */
    public function index(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        return view('cms::admin.blocks.index', compact('etablissement'));
    }
    
    /**
     * API pour récupérer les blocks au format JSON pour GrapeJS
     */
    public function api(Request $request, $etablissementId): JsonResponse
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        $blocks = Block::active()
            ->ordered()
            ->with('categorie')
            ->get();
        
        // Formater les blocks pour GrapeJS
        $formattedBlocks = [];
        
        foreach ($blocks as $block) {
            // Déterminer la catégorie
            $category = $block->categorie ? $block->categorie->name : ($block->category ?: 'Autres');
            
            // Créer le contenu HTML complet avec CSS intégré
            $htmlContent = $this->buildBlockHtml($block);
            
            $formattedBlocks[] = [
                'id' => $block->id,
                'label' => $block->name,
                'content' => $htmlContent,
                'category' => $category,
                'media' => $block->thumbnail ? asset('storage/' . $block->thumbnail) : null,
                'attributes' => [
                    'class' => 'block-' . $block->slug,
                    'data-block-id' => $block->id
                ],
                'style' => [
                    'display' => 'block',
                    'width' => $block->width ?: '100%',
                    'height' => $block->height ?: 'auto',
                ]
            ];
        }
        
        return response()->json([
            'success' => true,
            'blocks' => $formattedBlocks,
            'categories' => $this->getCategoriesWithCount($blocks)
        ]);
    }
    
    /**
     * Construit le HTML complet du block avec CSS intégré
     */
    protected function buildBlockHtml($block): string
    {
        $html = '';
        
        // Ajouter le CSS inline si présent
        if ($block->css_content) {
            $html .= '<style>';
            $html .= $block->css_content;
            $html .= '</style>';
        }
        
        // Ajouter le HTML du block
        $html .= $block->html_content;
        
        // Ajouter le JS si présent
        if ($block->js_content) {
            $html .= '<script>';
            $html .= $block->js_content;
            $html .= '</script>';
        }
        
        return $html;
    }
    
    /**
     * Récupère les catégories avec leur nombre de blocks
     */
    protected function getCategoriesWithCount($blocks): array
    {
        $categories = [];
        
        foreach ($blocks as $block) {
            $catName = $block->categorie ? $block->categorie->name : ($block->category ?: 'Autres');
            
            if (!isset($categories[$catName])) {
                $categories[$catName] = 0;
            }
            $categories[$catName]++;
        }
        
        $result = [];
        foreach ($categories as $name => $count) {
            $result[] = [
                'id' => \Illuminate\Support\Str::slug($name),
                'label' => $name . " ($count)",
                'name' => $name,
                'count' => $count
            ];
        }
        
        return $result;
    }
    
    /**
     * Récupère les catégories disponibles
     */
    public function categories(Request $request, $etablissementId): JsonResponse
    {
        $categories = Block::active()
            ->with('categorie')
            ->get()
            ->groupBy(function($block) {
                return $block->categorie ? $block->categorie->name : ($block->category ?: 'Autres');
            })
            ->map(function($items, $category) {
                return [
                    'name' => $category,
                    'slug' => \Illuminate\Support\Str::slug($category),
                    'count' => $items->count()
                ];
            })
            ->values();
        
        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
}