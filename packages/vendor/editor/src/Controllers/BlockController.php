<?php

namespace Vendor\Editor\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Section;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlockController extends Controller
{
    /**
     * Interface d'administration web (vue HTML)
     * Accès via: /admin/blocks
     */
    public function index(Request $request)
    {
        $query = Block::with('section')
            ->active()
            ->ordered();

        // Filtres
        if ($request->has('section')) {
            $query->whereHas('section', function ($q) use ($request) {
                $q->where('slug', $request->section);
            });
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('website_type')) {
            $query->where('website_type', $request->website_type);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // Récupérer les sections pour le filtre
        $sections = Section::active()->ordered()->get();
        
        // Statistiques
        $stats = [
            'total_blocks' => Block::active()->count(),
            'total_views' => Block::active()->sum('views_count'),
            'total_usage' => Block::active()->sum('usage_count'),
        ];

        // Pagination ou tout récupérer
        $blocks = $request->has('all') ? $query->get() : $query->paginate(12);

        return view('editor::blocks.index', compact('blocks', 'sections', 'stats'));
    }

    /**
     * API pour récupérer les blocs (utilisé par l'éditeur GrapeJS)
     * Accès via: /api/blocks
     * Retourne TOUJOURS du JSON
     */
    public function getBlocks(Request $request)
    {
        try {

            $template_id = $request->query('template_id');
            $template = Template::find($template_id);
            $query = Block::with('section')
                ->active()
                ->orderBy('id', 'DESC');

            // Filtres
            if ($request->has('section_id')) {
                $query->where('section_id', $request->section_id);
            } else if ($request->has('section')) {
                $query->whereHas('section', function ($q) use ($request) {
                    $q->where('slug', $request->section);
                });
            }

            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            if ($request->has('website_type')) {
                $query->where('website_type', $request->website_type);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('tags', 'like', "%{$search}%");
                });
            }

            // Récupérer tous les blocs (pas de pagination pour l'éditeur)
            $blocks = $query->get();

            // Récupérer les sections
            $sections = Section::active()
                ->ordered()
                ->withCount(['blocks' => function ($q) {
                    $q->active();
                }])
                ->get();

            // Formater la réponse
            $formattedBlocks = $blocks->map(function ($block) {
                return [
                    'id' => $block->id,
                    'name' => $block->name,
                    'slug' => $block->slug,
                    'description' => $block->description,
                    'thumbnail' => $block->thumbnail,
                    'icon' => $block->icon,
                    'html_content' => $block->html_content,
                    'css_content' => $block->css_content,
                    'js_content' => $block->js_content,
                    'section_id' => $block->section_id,
                    'section' => $block->section ? [
                        'id' => $block->section->id,
                        'name' => $block->section->name,
                        'slug' => $block->section->slug,
                        'icon' => $block->section->icon
                    ] : null,
                    'section_name' => $block->section->name ?? 'General',
                    'section_slug' => $block->section->slug ?? 'general',
                    'category' => $block->category,
                    'website_type' => $block->website_type,
                    'tags' => $block->tags ? (is_array($block->tags) ? $block->tags : json_decode($block->tags, true)) : [],
                    'is_responsive' => (bool) $block->is_responsive,
                    'is_free' => (bool) $block->is_free,
                    'width' => $block->width,
                    'height' => $block->height,
                    'usage_count' => $block->usage_count,
                    'views_count' => $block->views_count,
                    'rating' => (float) $block->rating,
                    'order' => $block->order,
                    'created_at' => $block->created_at->format('Y-m-d'),
                    'updated_at' => $block->updated_at->format('Y-m-d')
                ];
            });

            // Statistiques
            $stats = [
                'total_blocks' => $blocks->count(),
                'total_usage' => $blocks->sum('usage_count'),
                'total_views' => $blocks->sum('views_count'),
                'categories' => $blocks->groupBy('category')->map->count(),
                'website_types' => $blocks->groupBy('website_type')->map->count()
            ];

            return response()->json([
                'success' => true,
                'message' => 'Blocks retrieved successfully',
                'blocks' => $formattedBlocks,
                'sections' => $sections->map(function ($section) {
                    return [
                        'id' => $section->id,
                        'name' => $section->name,
                        'slug' => $section->slug,
                        'description' => $section->description,
                        'icon' => $section->icon,
                        'order' => $section->order,
                        'blocks_count' => $section->blocks_count,
                        'is_active' => (bool) $section->is_active
                    ];
                }),
                'stats' => $stats,
                'meta' => [
                    'count' => $blocks->count(),
                    'filters' => $request->all()
                ]
            ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        } catch (\Exception $e) {
            \Log::error('Error in getBlocks:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving blocks: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'blocks' => [],
                'sections' => [],
                'stats' => [
                    'total_blocks' => 0,
                    'total_usage' => 0,
                    'total_views' => 0
                ]
            ], 500);
        }
    }

    /**
     * API pour ajouter un bloc dans l'éditeur
     */
    public function addToEditor(Request $request)
    {
        try {
            $blockId = $request->input('block_id');
            $block = Block::findOrFail($blockId);

            // Incrémenter le compteur d'utilisation
            $block->incrementUsage();

            return response()->json([
                'success' => true,
                'block' => [
                    'html' => $block->html_content,
                    'css' => $block->css_content,
                    'js' => $block->js_content,
                ],
                'message' => 'Bloc ajouté avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du bloc'
            ], 500);
        }
    }

    /**
     * Créer un nouveau bloc depuis l'éditeur
     */
    public function storeFromEditor(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'html_content' => 'required|string',
                'css_content' => 'nullable|string',
                'section_id' => 'nullable|exists:sections,id',
            ]);

            $block = Block::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . uniqid(),
                'description' => $request->description,
                'html_content' => $request->html_content,
                'css_content' => $request->css_content,
                'js_content' => $request->js_content,
                'section_id' => $request->section_id,
                'category' => $request->category ?? 'Custom',
                'website_type' => $request->website_type ?? 'General',
                'tags' => $request->tags ? json_encode(explode(',', $request->tags)) : null,
                'is_responsive' => true,
                'is_free' => true,
            ]);

            return response()->json([
                'success' => true,
                'block' => $block,
                'message' => 'Bloc sauvegardé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la sauvegarde du bloc',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sauvegarder le contenu actuel comme nouveau bloc
     */
    public function saveAsBlock(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'html' => 'required|string',
                'css' => 'nullable|string',
            ]);

            $block = Block::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . uniqid(),
                'description' => $request->description ?? 'Sauvegardé depuis l\'éditeur',
                'html_content' => $request->html,
                'css_content' => $request->css,
                'js_content' => $request->js,
                'section_id' => $request->section_id,
                'category' => 'Custom',
                'website_type' => 'Editor',
                'tags' => $request->tags ? json_encode(['custom', 'editor-saved']) : json_encode(['custom', 'editor-saved']),
                'is_responsive' => true,
                'is_free' => true,
            ]);

            return response()->json([
                'success' => true,
                'block' => $block,
                'message' => 'Contenu sauvegardé comme nouveau bloc'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la sauvegarde',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les catégories uniques
     */
    public function getCategories()
    {
        $categories = Block::active()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    /**
     * Obtenir les types de site web uniques
     */
    public function getWebsiteTypes()
    {
        $types = Block::active()
            ->select('website_type')
            ->distinct()
            ->orderBy('website_type')
            ->pluck('website_type');

        return response()->json([
            'success' => true,
            'website_types' => $types
        ]);
    }

    /**
     * Obtenir les statistiques d'utilisation
     */
    public function getStats()
    {
        $stats = [
            'total_blocks' => Block::active()->count(),
            'total_usage' => Block::active()->sum('usage_count'),
            'popular_blocks' => Block::active()
                ->orderByDesc('usage_count')
                ->limit(5)
                ->get(['id', 'name', 'usage_count']),
            'blocks_by_category' => Block::active()
                ->select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->get(),
            'blocks_by_section' => Block::active()
                ->with('section')
                ->select('section_id', DB::raw('count(*) as count'))
                ->groupBy('section_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'section' => $item->section->name ?? 'Unknown',
                        'count' => $item->count
                    ];
                })
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Rechercher des blocs
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('q');
            
            $blocks = Block::active()
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhere('tags', 'like', "%{$query}%");
                })
                ->orderByDesc('usage_count')
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'blocks' => $blocks->map(function ($block) {
                    return [
                        'id' => $block->id,
                        'name' => $block->name,
                        'description' => $block->description,
                        'category' => $block->category,
                        'icon' => $block->icon
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche'
            ], 500);
        }
    }

    /**
     * Prévisualiser un bloc
     */
    public function preview($id)
    {
        $block = Block::findOrFail($id);
        
        // Incrémenter les vues
        $block->increment('views_count');
        
        return view('blocks.preview', compact('block'));
    }

    /**
     * Obtenir le code HTML/CSS d'un bloc
     */
    public function getBlockCode($id)
    {
        try {
            $block = Block::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'html' => $block->html_content,
                'css' => $block->css_content,
                'js' => $block->js_content
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bloc non trouvé'
            ], 404);
        }
    }

    /**
     * Mettre à jour un bloc
     */
    public function update(Request $request, $id)
    {
        try {
            $block = Block::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'html_content' => 'sometimes|required|string',
                'css_content' => 'nullable|string',
                'js_content' => 'nullable|string',
                'section_id' => 'nullable|exists:sections,id',
                'category' => 'sometimes|string',
                'website_type' => 'sometimes|string',
                'is_active' => 'sometimes|boolean',
            ]);

            if (isset($validated['name']) && $validated['name'] !== $block->name) {
                $validated['slug'] = Str::slug($validated['name']) . '-' . $block->id;
            }

            $block->update($validated);

            return response()->json([
                'success' => true,
                'block' => $block,
                'message' => 'Bloc mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Supprimer un bloc
     */
    public function destroy($id)
    {
        try {
            $block = Block::findOrFail($id);
            $block->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bloc supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }

    /**
     * Importer des blocs par lot
     */
    public function import(Request $request)
    {
        try {
            $request->validate([
                'blocks' => 'required|array',
                'blocks.*.name' => 'required|string',
                'blocks.*.html_content' => 'required|string',
            ]);

            $imported = [];
            $errors = [];

            foreach ($request->blocks as $blockData) {
                try {
                    $block = Block::create([
                        'name' => $blockData['name'],
                        'slug' => Str::slug($blockData['name']) . '-' . uniqid(),
                        'description' => $blockData['description'] ?? null,
                        'html_content' => $blockData['html_content'],
                        'css_content' => $blockData['css_content'] ?? null,
                        'js_content' => $blockData['js_content'] ?? null,
                        'section_id' => $blockData['section_id'] ?? null,
                        'category' => $blockData['category'] ?? 'Imported',
                        'website_type' => $blockData['website_type'] ?? 'General',
                        'tags' => isset($blockData['tags']) ? json_encode($blockData['tags']) : null,
                        'is_responsive' => $blockData['is_responsive'] ?? true,
                        'is_free' => $blockData['is_free'] ?? true,
                    ]);

                    $imported[] = $block;

                } catch (\Exception $e) {
                    $errors[] = [
                        'block' => $blockData['name'],
                        'error' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'imported' => count($imported),
                'errors' => $errors,
                'message' => count($imported) . ' blocs importés avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'importation'
            ], 500);
        }
    }
}