<?php

namespace Vendor\Ecommerce\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductFamily;
use App\Models\ProductCategory;
use App\Models\Tax;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Product::with(['category', 'family', 'variants']);
            
            // Apply filters
            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('reference', 'like', '%' . $request->search . '%')
                      ->orWhere('sku', 'like', '%' . $request->search . '%');
                });
            }
            
            if ($request->type) {
                $query->where('main_type', $request->type);
            }
            
            if ($request->family_id) {
                $query->where('product_family_id', $request->family_id);
            }
            
            if ($request->category_id) {
                $query->where('product_category_id', $request->category_id);
            }
            
            // if ($request->is_available !== '') {
            //     $query->where('is_available_for_sale', $request->is_available);
            // }
            
            if ($request->price_min) {
                $query->where('price_ttc', '>=', $request->price_min);
            }
            
            if ($request->price_max) {
                $query->where('price_ttc', '<=', $request->price_max);
            }
            
            if ($request->stock_status) {
                switch ($request->stock_status) {
                    case 'in_stock':
                        $query->where('current_stock', '>', 0);
                        break;
                    case 'out_of_stock':
                        $query->where('current_stock', '<=', 0);
                        break;
                    case 'low_stock':
                        $query->whereRaw('current_stock <= minimum_stock AND current_stock > 0');
                        break;
                }
            }
            
            // Sorting
            switch ($request->sort_by) {
                case 'price_ttc':
                    $query->orderBy('price_ttc', 'asc');
                    break;
                case 'price_ttc_desc':
                    $query->orderBy('price_ttc', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                case 'sales_count':
                    $query->orderBy('sales_count', 'desc');
                    break;
                case 'updated_at':
                    $query->orderBy('updated_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
            
            $products = $query->paginate(15);
            
            // Transform data for frontend
            $products->getCollection()->transform(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'reference' => $product->reference,
                    'sku' => $product->sku,
                    'main_type' => $product->main_type,
                    'category_name' => $product->category?->name,
                    'family_name' => $product->family?->name,
                    'price_ht' => $product->price_ht,
                    'price_ttc' => $product->price_ttc,
                    'price_regular' => $product->price_regular ?? $product->price_ttc,
                    'has_sale' => $product->price_solde ? true : false,
                    'tax_rate' => $product->tax_rate,
                    'billing_unit' => $product->billing_unit,
                    'billing_period' => $product->billing_period,
                    'has_commitment' => $product->has_commitment,
                    'commitment_months' => $product->commitment_months,
                    'estimated_duration_minutes' => $product->estimated_duration_minutes,
                    'requires_appointment' => $product->requires_appointment,
                    'stock_management' => $product->stock_management,
                    'current_stock' => $product->current_stock,
                    'minimum_stock' => $product->minimum_stock,
                    'stock_location' => $product->stock_location,
                    // 'is_available_for_sale' => $product->is_available_for_sale,
                    'is_public' => $product->is_public,
                    'sales_count' => $product->sales_count,
                    'views_count' => $product->views_count,
                    'main_image' => $product->main_image,
                    'short_description' => $product->short_description,
                    'long_description' => $product->long_description,
                    'variants' => $product->variants->map(function($variant) {
                        return [
                            'id' => $variant->id,
                            'name' => $variant->name,
                            'attributes' => $variant->attributes,
                            'price_adjustment' => $variant->price_adjustment,
                            'stock' => $variant->stock
                        ];
                    })
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $products->items(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'prev_page_url' => $products->previousPageUrl(),
                'next_page_url' => $products->nextPageUrl()
            ]);
        }
        
        // For non-AJAX request, load the page with initial data
        $families = ProductFamily::where('is_active', true)->get();
        $categories = ProductCategory::where('is_active', true)->get();
        
        return view('ecommerce::products.index', compact('families', 'categories'));
    }
    
    public function statistics()
    {
        
        $stats = [
            'total' => Product::count(),
            'physical' => Product::where('main_type', 'produit_physique')->count(),
            'services' => Product::whereIn('main_type', ['service', 'prestation'])->count(),
            'subscriptions' => Product::where('main_type', 'abonnement')->count(),
            'total_value' => Product::sum('price_ttc'),
            'by_family' => DB::table('products')
                ->join('product_families', 'products.product_family_id', '=', 'product_families.id')
                ->select('product_families.name as family_name', DB::raw('count(*) as total'))
                ->groupBy('product_families.name')
                ->get(),
            'by_category' => DB::table('products')
                ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
                ->select('product_categories.name as category_name', DB::raw('count(*) as total'))
                ->groupBy('product_categories.name')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get(),
                'top_products' => Product::where('sales_count', '>', 0)
                ->orderBy('sales_count', 'desc')
                ->limit(5)
                ->get(['name', 'sales_count'])
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }


    /**
 * Display the specified product.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function show($id)
{
    try {
        // Récupérer le produit avec ses relations
        $product = Product::with([
            'category', 
            'family', 
            'variants',
            'etablissement'
        ])
        ->findOrFail($id);

        // Incrémenter le compteur de vues
        $product->increment('views_count');

        // Récupérer les produits associés (même catégorie ou famille)
        $relatedProducts = Product::where('etablissement_id', auth()->user()->etablissement_id)
            ->where('id', '!=', $product->id)
            ->where(function($query) use ($product) {
                $query->where('product_category_id', $product->product_category_id)
                      ->orWhere('product_family_id', $product->product_family_id);
            })
            ->where('is_available_for_sale', true)
            ->where('is_public', true)
            ->limit(4)
            ->get();

        // Statistiques supplémentaires
        $stats = [
            'total_invoices' => $product->invoiceLines()->count(),
            'total_quotes' => $product->quoteLines()->count(),
            'revenue' => $product->invoiceLines()
                ->join('invoices', 'invoice_lines.invoice_id', '=', 'invoices.id')
                ->where('invoices.status', 'payee')
                ->sum(DB::raw('invoice_lines.quantity * invoice_lines.unit_price')),
            'last_sold' => $product->invoiceLines()
                ->with('invoice')
                ->whereHas('invoice', function($q) {
                    $q->where('status', 'payee');
                })
                ->latest()
                ->first(),
        ];

        // Vérifier si le produit a des dépendances
        $hasDependencies = ($product->invoiceLines()->count() > 0 || $product->quoteLines()->count() > 0);

        return view('ecommerce::products.show', compact(
            'product', 
            'relatedProducts', 
            'stats', 
            'hasDependencies'
        ));

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Log l'erreur
        \Log::warning('Tentative d\'accès à un produit inexistant: ' . $e->getMessage());
        
        // Rediriger avec un message d'erreur
        return redirect()->route('products.index')
            ->with('error', 'Le produit demandé n\'existe pas.');
            
    } catch (\Exception $e) {
        // Log l'erreur
        \Log::error('Erreur dans ProductController@show: ' . $e->getMessage());
        
        // Rediriger avec un message d'erreur
        return redirect()->route('products.index')
            ->with('error', 'Une erreur est survenue lors du chargement du produit.');
    }
}
    
    /**
     * Display the create form for products/services.
     */
    public function create()
    {
        try {
            // Récupérer les familles de produits actives
            $families = ProductFamily::where('is_active', true)
                ->orderBy('order')
                ->orderBy('name')
                ->get();

            // Récupérer les catégories de produits actives avec leurs relations
            $categories = ProductCategory::with('family')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            // Récupérer les taxes disponibles
            $taxes = Tax::where('is_active', true)
                ->orderBy('rate')
                ->get();

            // Si pas de taxes, créer des valeurs par défaut
            if ($taxes->isEmpty()) {
                $taxes = collect([
                    (object)['id' => 1, 'name' => 'TVA 20%', 'rate' => 20.00, 'is_default' => true],
                    (object)['id' => 2, 'name' => 'TVA 10%', 'rate' => 10.00, 'is_default' => false],
                    (object)['id' => 3, 'name' => 'TVA 5.5%', 'rate' => 5.50, 'is_default' => false],
                    (object)['id' => 4, 'name' => 'TVA 0%', 'rate' => 0.00, 'is_default' => false],
                ]);
            }

            // Récupérer les types de produits disponibles (pour le filtre)
            $productTypes = [
                'produit_physique' => 'Produit physique',
                'produit_numerique' => 'Produit numérique',
                'service' => 'Service',
                'prestation' => 'Prestation',
                'forfait' => 'Forfait',
                'abonnement' => 'Abonnement',
                'licence' => 'Licence',
                'hebergement' => 'Hébergement',
                'maintenance' => 'Maintenance',
                'formation' => 'Formation'
            ];

            // Récupérer les unités de facturation
            $billingUnits = [
                'unite' => 'À l\'unité',
                'heure' => 'À l\'heure',
                'jour' => 'Par jour',
                'mois' => 'Par mois',
                'an' => 'Par an',
                'forfait' => 'Forfait',
                'projet' => 'Par projet'
            ];

            // Récupérer les périodes de facturation pour abonnements
            $billingPeriods = [
                'mensuel' => 'Mensuel',
                'trimestriel' => 'Trimestriel',
                'semestriel' => 'Semestriel',
                'annuel' => 'Annuel'
            ];

            // Statistiques pour affichage (optionnel)
            $stats = [
                'total_products' => Product::where('etablissement_id', auth()->user()->etablissement_id)->count(),
                'total_families' => $families->count(),
                'total_categories' => $categories->count(),
            ];

            // Générer une référence par défaut
            $defaultReference = 'PROD-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            return view('ecommerce::products.create', compact(
                'families',
                'categories',
                'taxes',
                'productTypes',
                'billingUnits',
                'billingPeriods',
                'stats',
                'defaultReference'
            ));

        } catch (\Exception $e) {
            // En cas d'erreur, retourner avec des collections vides
            \Log::error('Erreur dans ProductController@create: ' . $e->getMessage());
            
            $families = collect([]);
            $categories = collect([]);
            $taxes = collect([]);
            $productTypes = [];
            $billingUnits = [];
            $billingPeriods = [];
            $stats = [];
            $defaultReference = 'PROD-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            return view('products.create', compact(
                'families',
                'categories',
                'taxes',
                'productTypes',
                'billingUnits',
                'billingPeriods',
                'stats',
                'defaultReference'
            ))->with('error', 'Certaines données n\'ont pas pu être chargées.');
        }
    }

    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'main_type' => 'required|string',
            'name' => 'required|string|max:255',
            'reference' => 'nullable|string|unique:products,reference',
            'sku' => 'nullable|string|unique:products,sku',
            'price_ttc' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'product_family_id' => 'nullable|exists:product_families,id',
            'product_category_id' => 'nullable|exists:product_categories,id',
        ]);

        // Generate reference if empty
        if (empty($validated['reference'])) {
            $validated['reference'] = 'PROD-' . strtoupper(uniqid());
        }

        // Calculate HT price
        $validated['price_ht'] = $validated['price_ttc'] / (1 + $validated['tax_rate'] / 100);
        $validated['price_ht'] = round($validated['price_ht'], 2);

        // Set etablissement_id
        $etablissement = Etablissement::first();
        $validated['etablissement_id'] = $etablissement->id;

        // Generate slug if empty
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle file uploads
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('products', 'public');
            $validated['main_image'] = $path;
        }

        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('products/gallery', 'public');
            }
            $validated['gallery_images'] = json_encode($galleryPaths);
        }

        // Create product
        $product = Product::create($validated);

        // Handle variants
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (!empty($variantData['name'])) {
                    $variantData['product_id'] = $product->id;
                    
                    if (isset($variantData['image']) && $variantData['image'] instanceof UploadedFile) {
                        $variantData['image'] = $variantData['image']->store('products/variants', 'public');
                    }
                    
                    $product->variants()->create($variantData);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Produit créé avec succès',
            'data' => $product
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Erreur dans ProductController@store: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
}
   
   public function edit($id)
{
    try {
        $product = Product::with(['category', 'family', 'variants'])
            ->findOrFail($id);

        $families = ProductFamily::where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $categories = ProductCategory::with('family')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $taxes = Tax::where('is_active', true)
            ->orderBy('rate')
            ->get();

        if ($taxes->isEmpty()) {
            $taxes = collect([
                (object)['id' => 1, 'name' => 'TVA 20%', 'rate' => 20.00, 'is_default' => true],
                (object)['id' => 2, 'name' => 'TVA 10%', 'rate' => 10.00, 'is_default' => false],
                (object)['id' => 3, 'name' => 'TVA 5.5%', 'rate' => 5.50, 'is_default' => false],
                (object)['id' => 4, 'name' => 'TVA 0%', 'rate' => 0.00, 'is_default' => false],
            ]);
        }

        return view('ecommerce::products.edit', compact('product', 'families', 'categories', 'taxes'));

    } catch (\Exception $e) {
        \Log::error('Erreur dans ProductController@edit: ' . $e->getMessage());
        return redirect()->route('products.index')
            ->with('error', 'Produit non trouvé ou accès non autorisé.');
    }
}


public function update(Request $request, $id)
{
    try {
        $product = Product::where('etablissement_id', auth()->user()->etablissement_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'main_type' => 'required|string',
            'name' => 'required|string|max:255',
            'reference' => 'nullable|string|max:50|unique:products,reference,' . $id,
            'sku' => 'nullable|string|max:50',
            'barcode' => 'nullable|string|max:50',
            'product_family_id' => 'nullable|exists:product_families,id',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'short_description' => 'nullable|string|max:255',
            'long_description' => 'nullable|string',
            'price_ttc' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'billing_unit' => 'required|string',
            'purchase_price_ht' => 'nullable|numeric|min:0',
            'estimated_duration_minutes' => 'nullable|integer|min:0',
            'requires_appointment' => 'boolean',
            'billing_period' => 'nullable|required_if:main_type,abonnement',
            'has_commitment' => 'boolean',
            'commitment_months' => 'nullable|required_if:has_commitment,1|integer|min:1',
            'stock_management' => 'nullable|in:oui,non,sur_commande',
            'current_stock' => 'nullable|integer|min:0',
            'minimum_stock' => 'nullable|integer|min:0',
            'maximum_stock' => 'nullable|integer|min:0',
            'stock_location' => 'nullable|string|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $id,
            'is_available_for_sale' => 'boolean',
            'is_public' => 'boolean',
            'is_taxable' => 'boolean',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        // Calculer le prix HT
        $validated['price_ht'] = $validated['price_ttc'] / (1 + $validated['tax_rate'] / 100);
        $validated['price_ht'] = round($validated['price_ht'], 2);

        // Gérer les booléens
        $validated['requires_appointment'] = $request->has('requires_appointment');
        $validated['has_commitment'] = $request->has('has_commitment');
        $validated['is_available_for_sale'] = $request->has('is_available_for_sale');
        $validated['is_public'] = $request->has('is_public');
        $validated['is_taxable'] = $request->has('is_taxable');

        // Gérer l'image principale
        if ($request->hasFile('main_image')) {
            // Supprimer l'ancienne image
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $path = $request->file('main_image')->store('products/main', 'public');
            $validated['main_image'] = $path;
        } elseif ($request->has('delete_main_image')) {
            // Supprimer l'image sans en ajouter une nouvelle
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $validated['main_image'] = null;
        }

        // Gérer les images de galerie
        $existingGallery = $product->gallery_images ? json_decode($product->gallery_images, true) : [];
        $deletedGallery = $request->input('deleted_gallery_images', []);
        
        // Supprimer les images marquées pour suppression
        foreach ($deletedGallery as $image) {
            Storage::disk('public')->delete($image);
            $existingGallery = array_diff($existingGallery, [$image]);
        }

        // Ajouter les nouvelles images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $existingGallery[] = $image->store('products/gallery', 'public');
            }
        }

        $validated['gallery_images'] = json_encode($existingGallery);

        // Mettre à jour le produit
        $product->update($validated);

        // Gérer les variantes
        $this->handleVariants($request, $product);

        // Log l'activité

        return response()->json([
            'success' => true,
            'message' => 'Produit mis à jour avec succès !',
            'data' => $product
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Erreur mise à jour produit: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue: ' . $e->getMessage()
        ], 500);
    }
}

private function handleVariants($request, $product)
{
    // Supprimer les variantes marquées pour suppression
    if ($request->has('deleted_variants')) {
        ProductVariant::whereIn('id', $request->deleted_variants)->delete();
    }

    // Mettre à jour ou créer les variantes
    if ($request->has('variants')) {
        foreach ($request->variants as $index => $variantData) {
            if (empty($variantData['name'])) continue;

            $variantToSave = [
                'name' => $variantData['name'],
                'sku' => $variantData['sku'] ?? null,
                'price_adjustment' => $variantData['price_adjustment'] ?? 0,
                'stock' => $variantData['stock'] ?? 0,
                'attributes' => $variantData['attributes'] ?? ['generated' => true],
            ];

            // Gérer l'image de la variante
            if (isset($variantData['new_image']) && $variantData['new_image'] instanceof UploadedFile) {
                // Supprimer l'ancienne image si elle existe
                if (isset($variantData['existing_image'])) {
                    Storage::disk('public')->delete($variantData['existing_image']);
                }
                $variantToSave['image'] = $variantData['new_image']->store('products/variants', 'public');
            } elseif (isset($variantData['image']) && $variantData['image'] instanceof UploadedFile) {
                $variantToSave['image'] = $variantData['image']->store('products/variants', 'public');
            } elseif (isset($variantData['existing_image'])) {
                $variantToSave['image'] = $variantData['existing_image'];
            }

            if (isset($variantData['id'])) {
                // Mettre à jour une variante existante
                $variant = ProductVariant::find($variantData['id']);
                if ($variant) {
                    $variant->update($variantToSave);
                }
            } else {
                // Créer une nouvelle variante
                $variantToSave['product_id'] = $product->id;
                $product->variants()->create($variantToSave);
            }
        }
    }
}

 /**
     * Generate a unique reference.
     */
    public function generateReference()
    {
        try {
            $prefix = 'PROD';
            $date = date('Ymd');
            $random = strtoupper(Str::random(4));
            $reference = $prefix . '-' . $date . '-' . $random;

            // Vérifier si la référence existe déjà
            $count = 1;
            while (Product::where('reference', $reference)->exists()) {
                $reference = $prefix . '-' . $date . '-' . $random . $count;
                $count++;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'reference' => $reference
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate HT price from TTC.
     */
    public function calculatePrice(Request $request)
    {
        try {
            $ttc = $request->input('price_ttc', 0);
            $taxRate = $request->input('tax_rate', 20);

            $ht = $ttc / (1 + $taxRate / 100);
            $ht = round($ht, 2);

            return response()->json([
                'success' => true,
                'data' => [
                    'price_ht' => $ht,
                    'price_ttc' => $ttc,
                    'tax_amount' => round($ttc - $ht, 2),
                    'tax_rate' => $taxRate
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if SKU is unique.
     */
    public function checkSku(Request $request)
    {
        try {
            $sku = $request->input('sku');
            $exists = Product::where('sku', $sku)->exists();

            return response()->json([
                'success' => true,
                'data' => [
                    'unique' => !$exists,
                    'exists' => $exists
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function uploadImage(Request $request)
{
    try {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('products/editor', 'public');
            
            return response()->json([
                'success' => true,
                'url' => asset('storage/' . $path)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Aucun fichier reçu'
        ], 400);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

/**
 * Remove the specified product from storage.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function destroy($id)
{
    try {
        // Récupérer le produit avec ses relations
        $product = Product::with(['variants', 'invoiceLines', 'quoteLines'])
            ->findOrFail($id);

        // Vérifier si le produit est utilisé dans des factures
        $invoicesCount = $product->invoiceLines()->count();
        if ($invoicesCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit ne peut pas être supprimé car il est utilisé dans ' . $invoicesCount . ' facture(s).'
            ], 400);
        }

        // Vérifier si le produit est utilisé dans des devis
        $quotesCount = $product->quoteLines()->count();
        if ($quotesCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit ne peut pas être supprimé car il est utilisé dans ' . $quotesCount . ' devis.'
            ], 400);
        }

        // Vérifier si le produit est utilisé dans des contrats
        if (method_exists($product, 'contracts') && $product->contracts()->count() > 0) {
            $contractsCount = $product->contracts()->count();
            return response()->json([
                'success' => false,
                'message' => 'Ce produit ne peut pas être supprimé car il est lié à ' . $contractsCount . ' contrat(s).'
            ], 400);
        }

        // Vérifier si le produit a des dépendances dans d'autres modules
        $dependencies = $this->checkDependencies($product);
        if (!empty($dependencies)) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit a des dépendances actives.',
                'dependencies' => $dependencies
            ], 400);
        }

        // Supprimer les images associées
        $this->deleteProductImages($product);

        // Supprimer les variantes et leurs images
        $this->deleteProductVariants($product);

        // Soft delete ou hard delete selon votre besoin
        $product->delete(); // Soft delete
        // $product->forceDelete(); // Hard delete


        // Réponse selon le type de requête
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produit supprimé avec succès !',
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name
                ]
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Produit supprimé avec succès !');

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Produit non trouvé.'
            ], 404);
        }
        return redirect()->route('products.index')
            ->with('error', 'Produit non trouvé.');

    } catch (\Exception $e) {
        \Log::error('Erreur lors de la suppression du produit: ' . $e->getMessage());
        
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
        
        return redirect()->route('products.index')
            ->with('error', 'Une erreur est survenue lors de la suppression.');
    }
}

/**
 * Delete product images
 */
private function deleteProductImages($product)
{
    try {
        // Supprimer l'image principale
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }

        // Supprimer les images de la galerie
        if ($product->gallery_images) {
            $gallery = json_decode($product->gallery_images, true);
            if (is_array($gallery)) {
                foreach ($gallery as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        }
    } catch (\Exception $e) {
        \Log::warning('Erreur lors de la suppression des images: ' . $e->getMessage());
    }
}

/**
 * Delete product variants and their images
 */
private function deleteProductVariants($product)
{
    try {
        foreach ($product->variants as $variant) {
            // Supprimer l'image de la variante
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
            
            // Supprimer la variante (sera supprimée automatiquement si cascade)
            if (!$product->variants()->getQuery()->getModel()->getConnection()->getSchemaBuilder()->hasColumn('product_variants', 'deleted_at')) {
                $variant->delete();
            }
        }
    } catch (\Exception $e) {
        \Log::warning('Erreur lors de la suppression des variantes: ' . $e->getMessage());
    }
}

/**
 * Check for other dependencies
 */
private function checkDependencies($product)
{
    $dependencies = [];

    // Vérifier dans les paniers/commandes en cours
    if (method_exists($product, 'carts')) {
        $cartsCount = $product->carts()->where('status', 'active')->count();
        if ($cartsCount > 0) {
            $dependencies['carts'] = $cartsCount;
        }
    }

    // Vérifier dans les listes de souhaits
    if (method_exists($product, 'wishlists')) {
        $wishlistsCount = $product->wishlists()->count();
        if ($wishlistsCount > 0) {
            $dependencies['wishlists'] = $wishlistsCount;
        }
    }

    // Vérifier dans les programmes de fidélité
    if (method_exists($product, 'loyaltyPoints')) {
        $loyaltyCount = $product->loyaltyPoints()->count();
        if ($loyaltyCount > 0) {
            $dependencies['loyalty'] = $loyaltyCount;
        }
    }

    return $dependencies;
}

/**
 * Force delete a product (hard delete)
 */
public function forceDestroy($id)
{
    try {
        $product = Product::withTrashed()
            ->findOrFail($id);

        // Supprimer définitivement les images
        $this->deleteProductImages($product);
        $this->deleteProductVariants($product);

        // Suppression définitive
        $product->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Produit supprimé définitivement !'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Restore a soft-deleted product
 */
public function restore($id)
{
    try {
        $product = Product::withTrashed()
            ->where('etablissement_id', auth()->user()->etablissement_id)
            ->findOrFail($id);

        $product->restore();

        // Restaurer les variantes
        if (method_exists($product, 'variants')) {
            $product->variants()->withTrashed()->restore();
        }

        return response()->json([
            'success' => true,
            'message' => 'Produit restauré avec succès !'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
}
    public function export($format, Request $request)
    {
        // Logic for exporting products in different formats
        // ...
    }
}