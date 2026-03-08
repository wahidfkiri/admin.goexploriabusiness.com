<?php

namespace Vendor\Etablissement\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Vendor\Etablissement\Mail\WelcomeEtablissementMail;

class EtablissementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Etablissement::with(['user', 'activities'])
            ->withCount('activities');
        
        // Recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('lname', 'like', "%$search%")
                  ->orWhere('ville', 'like', "%$search%")
                  ->orWhere('adresse', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }
        
        // Filtres
        if ($request->has('ville') && $request->ville) {
            $query->where('ville', $request->ville);
        }
        
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }
        
        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);
        
        // Si requête AJAX
        if ($request->ajax()) {
            $perPage = $request->get('per_page', 10);
            $etablissements = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $etablissements->items(),
                'current_page' => $etablissements->currentPage(),
                'last_page' => $etablissements->lastPage(),
                'per_page' => $etablissements->perPage(),
                'total' => $etablissements->total(),
                'from' => $etablissements->firstItem(),
                'to' => $etablissements->lastItem(),
            ]);
        }
        
        // Pour page normale
        $perPage = 10;
        $etablissements = $query->paginate($perPage);
        $users = User::all(); // Pour le formulaire
        
        // Villes uniques pour les filtres
        $villes = Etablissement::select('ville')
            ->distinct()
            ->orderBy('ville')
            ->pluck('ville');
        
        return view('etablissement::etablissements.index', compact('etablissements', 'users', 'villes'));
    }
    
    /**
     * Get statistics for dashboard
     */
    public function statistics()
    {
        $total = Etablissement::count();
        $active = Etablissement::where('is_active', true)->count();
        $inactive = Etablissement::where('is_active', false)->count();
        
        // Par ville
        $byVille = Etablissement::select('ville', DB::raw('count(*) as total'))
            ->groupBy('ville')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
        
        // Derniers établissements
        $latest = Etablissement::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
                'by_ville' => $byVille,
                'latest' => $latest,
                'total_activities' => DB::table('activity_etablissement')->count(),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $users = User::all();
        $activities = \App\Models\Activity::all();
        return view('etablissement::etablissements.create', compact('users', 'roles', 'activities'));
    }
/**
 * Store a newly created resource in storage.
 */


/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    Log::info('Etablissement store request', [
        'data' => $request->all(),
        'ip'   => $request->ip(),
        'user' => auth()->id(),
    ]);

    try {
        return DB::transaction(function () use ($request) {
            // ✅ Validation (is_active volontairement sans boolean)
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'fax' => 'nullable|string|max:20',
                'email_contact' => 'nullable|email|max:255',
                'website' => 'nullable|string|max:255',
                'ville_search' => 'nullable|string|max:255',
                'ville' => 'required|string|max:255',
                // 'region_id' => 'required|exists:regions,id',
                'province_id' => 'required|exists:provinces,id',
                'country_id' => 'required|exists:countries,id',
                'adresse' => 'required|string',
                'zip_code' => 'required|string|max:10',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'activities' => 'nullable|array',
                'activities.*' => 'exists:activities,id',
                'user_name' => 'required|string|max:255',
                'user_email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'role_id' => 'required|exists:roles,id',
                'is_active' => 'sometimes',
            ]);

            // 1️⃣ Create User
            $user = User::create([
                'name'     => $validated['user_name'],
                'email'    => $validated['user_email'],
                'password' => Hash::make($validated['password']),
            ]);

            // 2️⃣ Assign Role
            $role = Role::findOrFail($validated['role_id']);
            $user->assignRole($role);

            // 3️⃣ Create Etablissement
            $etablissement = Etablissement::create([
                'name'          => $validated['name'],
                'lname'         => $validated['user_name'],
                'ville'         => $validated['ville'],
                'user_id'       => $user->id,
                'adresse'       => $validated['adresse'],
                'zip_code'      => $validated['zip_code'],
                'phone'         => $validated['phone'] ?? null,
                'fax'           => $validated['fax'] ?? null,
                'email_contact' => $validated['email_contact'] ?? null,
                'website'       => $validated['website'] ?? null,
                'is_active'     => $request->boolean('is_active'),
                // 'region_id'     => $validated['region_id'],
                'province_id'   => $validated['province_id'],
                'country_id'    => $validated['country_id'],
            ]);

            // 4️⃣ Attach activities
            if (!empty($validated['activities'] ?? [])) {
                $etablissement->activities()->attach($validated['activities']);
            }

            // ✅ 5️⃣ Envoyer l'email avec les identifiants
            try {
                $this->sendWelcomeEmail($user, $validated['password'], $etablissement);
            } catch (\Exception $emailException) {
                // Loguer l'erreur mais ne pas bloquer la création
                Log::error('Erreur lors de l\'envoi de l\'email de bienvenue', [
                    'user_id' => $user->id,
                    'error' => $emailException->getMessage(),
                    'file' => $emailException->getFile(),
                    'line' => $emailException->getLine(),
                ]);
            }

            // AJAX response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Établissement créé avec succès. Un email avec les identifiants a été envoyé au client.',
                    'data'    => $etablissement->load(
                        'user',
                        'activities',
                        'region',
                        'province',
                        'country'
                    )
                ], 201);
            }

            // Normal response
            return redirect()
                ->route('etablissements.index')
                ->with('success', 'Établissement créé avec succès. Un email avec les identifiants a été envoyé au client.');
        });

    } catch (\Throwable $e) {
        Log::error('Erreur création établissement', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'request' => $request->all(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création',
            ], 500);
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Une erreur est survenue lors de la création');
    }
}


private function sendWelcomeEmail($user, $password, $etablissement)
{
    Mail::to($user->email)
        ->send(new WelcomeEtablissementMail($user, $password, $etablissement));
}




    /**
     * Display the specified resource.
     */
    public function show(Etablissement $etablissement)
    {
        $etablissement->load(['user', 'activities']);
        return view('etablissement::etablissements.show', compact('etablissement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Etablissement $etablissement)
    {
        $roles = Role::all();
        $users = User::all();
        $activities = \App\Models\Activity::all();
        $selectedActivities = $etablissement->activities->pluck('id')->toArray();
        
        return view('etablissement::etablissements.edit', compact('etablissement', 'roles', 'users', 'activities', 'selectedActivities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etablissement $etablissement)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lname' => 'nullable|string|max:255',
            'ville' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'adresse' => 'required|string',
            'zip_code' => 'required|string|max:10',
            'phone' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'website' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'activities' => 'nullable|array',
            'activities.*' => 'exists:activities,id',
        ]);
        
        $etablissement->update($validated);
        
        // Synchroniser les activités
        if ($request->has('activities')) {
            $etablissement->activities()->sync($request->activities);
        } else {
            $etablissement->activities()->detach();
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Établissement mis à jour avec succès',
                'data' => $etablissement->load('user', 'activities')
            ]);
        }
        
        return redirect()->route('etablissements.index')
            ->with('success', 'Établissement mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Etablissement $etablissement)
    {
        $etablissement->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Établissement supprimé avec succès'
            ]);
        }
        
        return redirect()->route('etablissements.index')
            ->with('success', 'Établissement supprimé avec succès');
    }

   public function search(Request $request)
{
    $search = $request->input('search');
    $limit = $request->input('limit', 20);
    
    $results = collect();
    
    // 1. Recherche dans les villes
    try {
        if (\Schema::hasTable('villes')) {
            $villes = \App\Models\Ville::with(['region', 'province', 'country'])
                ->where('name', 'LIKE', "%{$search}%")
                ->orWhereHas('region', function($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('province', function($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('country', function($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                })
                ->limit($limit)
                ->get()
                ->map(function($ville) {
                    return [
                        'id' => $ville->id,
                        'name' => $ville->name,
                        'type' => 'city',
                        'region_id' => $ville->region_id,
                        'region_name' => $ville->region->name ?? null,
                        'province_id' => $ville->province_id,
                        'province_name' => $ville->province->name ?? null,
                        'country_id' => $ville->country_id,
                        'country_name' => $ville->country->name ?? null,
                        'zip_code' => $ville->zip_code ?? null,
                        'source' => 'villes'
                    ];
                });
            
            $results = $results->merge($villes);
        }
    } catch (\Exception $e) {
        \Log::warning('Table villes non disponible: ' . $e->getMessage());
    }
    
    // 2. Recherche dans les régions si pas assez de résultats
    if ($results->count() < $limit / 2) {
        try {
            if (\Schema::hasTable('regions')) {
                $regions = \App\Models\Region::with(['province', 'province.country'])
                    ->where('name', 'LIKE', "%{$search}%")
                    ->limit($limit - $results->count())
                    ->get()
                    ->map(function($region) {
                        return [
                            'id' => $region->id,
                            'name' => $region->name,
                            'type' => 'region',
                            'region_id' => $region->id,
                            'region_name' => $region->name,
                            'province_id' => $region->province_id,
                            'province_name' => $region->province->name ?? null,
                            'country_id' => $region->province->country_id ?? null,
                            'country_name' => $region->province->country->name ?? null,
                            'zip_code' => null,
                            'source' => 'regions'
                        ];
                    });
                
                $results = $results->merge($regions);
            }
        } catch (\Exception $e) {
            \Log::warning('Table regions non disponible: ' . $e->getMessage());
        }
    }
    
    // 3. Recherche dans les provinces si pas assez de résultats
    if ($results->count() < $limit / 2) {
        try {
            if (\Schema::hasTable('provinces')) {
                $provinces = \App\Models\Province::with(['country'])
                    ->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%")
                    ->limit($limit - $results->count())
                    ->get()
                    ->map(function($province) {
                        return [
                            'id' => $province->id,
                            'name' => $province->name,
                            'type' => 'province',
                            'region_id' => null,
                            'region_name' => null,
                            'province_id' => $province->id,
                            'province_name' => $province->name,
                            'province_code' => $province->code,
                            'country_id' => $province->country_id,
                            'country_name' => $province->country->name ?? null,
                            'country_code' => $province->country->code ?? null,
                            'zip_code' => null,
                            'source' => 'provinces'
                        ];
                    });
                
                $results = $results->merge($provinces);
            }
        } catch (\Exception $e) {
            \Log::warning('Table provinces non disponible: ' . $e->getMessage());
        }
    }
    
    // 4. Recherche dans les pays si pas assez de résultats
    if ($results->count() < $limit / 2) {
        try {
            if (\Schema::hasTable('countries')) {
                $countries = \App\Models\Country::where('name', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%")
                    ->limit($limit - $results->count())
                    ->get()
                    ->map(function($country) {
                        return [
                            'id' => $country->id,
                            'name' => $country->name,
                            'type' => 'country',
                            'region_id' => null,
                            'region_name' => null,
                            'province_id' => null,
                            'province_name' => null,
                            'country_id' => $country->id,
                            'country_name' => $country->name,
                            'country_code' => $country->code,
                            'zip_code' => null,
                            'source' => 'countries'
                        ];
                    });
                
                $results = $results->merge($countries);
            }
        } catch (\Exception $e) {
            \Log::warning('Table countries non disponible: ' . $e->getMessage());
        }
    }
    
    // 5. Si aucune table n'est disponible, retourner des suggestions par défaut
    if ($results->isEmpty()) {
        $results = collect([
            [
                'id' => 1,
                'name' => 'Québec',
                'type' => 'region',
                'region_id' => 1,
                'region_name' => 'Québec',
                'province_id' => 1,
                'province_name' => 'Québec',
                'province_code' => 'QC',
                'country_id' => 1,
                'country_name' => 'Canada',
                'country_code' => 'CA',
                'zip_code' => null,
                'source' => 'default'
            ],
            [
                'id' => 2,
                'name' => 'Montréal',
                'type' => 'city',
                'region_id' => 1,
                'region_name' => 'Québec',
                'province_id' => 1,
                'province_name' => 'Québec',
                'province_code' => 'QC',
                'country_id' => 1,
                'country_name' => 'Canada',
                'country_code' => 'CA',
                'zip_code' => 'H3A 1A1',
                'source' => 'default'
            ],
            [
                'id' => 3,
                'name' => 'Toronto',
                'type' => 'city',
                'region_id' => 2,
                'region_name' => 'Ontario',
                'province_id' => 2,
                'province_name' => 'Ontario',
                'province_code' => 'ON',
                'country_id' => 1,
                'country_name' => 'Canada',
                'country_code' => 'CA',
                'zip_code' => 'M5H 2N2',
                'source' => 'default'
            ]
        ]);
    }
    
    // 6. Trier par pertinence (d'abord les villes, puis régions, puis provinces, puis pays)
    $sortedResults = $results->sortBy(function($item, $search) {
        $priority = [
            'city' => 1,
            'region' => 2,
            'province' => 3,
            'country' => 4,
            'default' => 5
        ];
        
        $priorityValue = $priority[$item['type']] ?? 6;
        
        // Ajouter un score de correspondance basé sur la recherche
        $searchScore = 0;
        $searchLower = strtolower($search);
        $nameLower = strtolower($item['name']);
        
        if (str_starts_with($nameLower, $searchLower)) {
            $searchScore = 10; // Commence par la recherche
        } elseif (str_contains($nameLower, $searchLower)) {
            $searchScore = 5; // Contient la recherche
        }
        
        return [$priorityValue, -$searchScore];
    })->values();
    
    // 7. Limiter les résultats
    $finalResults = $sortedResults->take($limit);
    
    return response()->json([
        'success' => true,
        'villes' => $finalResults,
        'sources' => $results->pluck('source')->unique()->values(),
        'count' => $finalResults->count(),
        'total' => $results->count()
    ]);
}

public function getActivities()
{
    $activities = \App\Models\Activity::select('id', 'name', 'categorie_id')
        ->orderBy('name')
        ->get();
    
    return response()->json([
        'success' => true,
        'activities' => $activities
    ]);
}
}