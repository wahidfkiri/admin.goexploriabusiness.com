<?php

namespace Vendor\Activitie\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Activity::with(['categoryRelation']);
        
        // Recherche
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhereHas('categorie', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filtre par catégorie
        if ($request->has('categorie_id') && $request->categorie_id !== '') {
            $query->where('categorie_id', $request->categorie_id);
        }
        
        // Filtre par statut
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }
        
            $query->orderBy('created_at', 'desc');
        
        // Si requête AJAX
        if ($request->ajax()) {
            $perPage = $request->per_page ?? 10;
            $activities = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $activities->items(),
                'current_page' => $activities->currentPage(),
                'last_page' => $activities->lastPage(),
                'per_page' => $activities->perPage(),
                'total' => $activities->total(),
                'prev_page_url' => $activities->previousPageUrl(),
                'next_page_url' => $activities->nextPageUrl(),
            ]);
        }
        
        // Pour la vue normale
        $activities = $query->paginate(10);
        $categories = Category::where('is_active', true)->get();
        
        return view('activitie::activities.index', compact('activities', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('activities.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'categorie_id' => 'required|exists:categories,id',
        'slug' => 'required|string|max:255|unique:activities,slug',
        'is_active' => 'nullable',
    ]);

    // S'assurer que le slug est valide et unique
    $validated['slug'] = Str::slug($validated['slug']);
    
    // Vérifier à nouveau l'unicité après le formatage
    $counter = 1;
    $originalSlug = $validated['slug'];
    
    while (Activity::where('slug', $validated['slug'])->exists()) {
        $validated['slug'] = $originalSlug . '-' . $counter;
        $counter++;
    }
// Logique is_active : présent = true, absent = false
    $validated['is_active'] = $request->has('is_active');
    $activity = Activity::create($validated);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Activité créée avec succès!',
            'data' => $activity
        ]);
    }

    return redirect()->route('activities.index')
        ->with('success', 'Activité créée avec succès!');
}


    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        $activity->load(['categorie']);
        
        $statistics = [
            'participants_count' => $activity->participants_count,
            'bookings_count' => $activity->bookings_count,
            'occupancy_rate' => $activity->max_participants ? 
                round(($activity->participants_count / $activity->max_participants) * 100, 2) : 0,
        ];
        
        return view('activities.show', compact('activity', 'statistics'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        $categories = Category::where('is_active', true)->get();
        return view('activities.edit', compact('activity', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    
public function update(Request $request, Activity $activity)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'categorie_id' => 'required|exists:categories,id',
        'slug' => 'required|string|max:255|unique:activities,slug,' . $activity->id,
        'is_active' => 'nullable',
    ]);

    // Formater le slug
    $validated['slug'] = Str::slug($validated['slug']);
    
    // Vérifier l'unicité après formatage (sauf pour l'activité courante)
    if ($validated['slug'] !== $activity->slug) {
        $counter = 1;
        $originalSlug = $validated['slug'];
        
        while (Activity::where('slug', $validated['slug'])
                      ->where('id', '!=', $activity->id)
                      ->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }
    }
// Logique is_active : présent = true, absent = false
    $validated['is_active'] = $request->has('is_active');
    $activity->update($validated);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Activité mise à jour avec succès!',
            'data' => $activity
        ]);
    }

    return redirect()->route('activities.index')
        ->with('success', 'Activité mise à jour avec succès!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Activity $activity)
    {
        try {
            DB::beginTransaction();
            
            // Vérifier si l'activité est utilisée
            if ($activity->participants()->count() > 0 || $activity->bookings()->count() > 0) {
                throw new \Exception('Cette activité ne peut pas être supprimée car elle est utilisée par des participants ou des réservations.');
            }
            
            // Supprimer l'activité
            $activity->delete();
            
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Activité supprimée avec succès!'
                ]);
            }

            return redirect()->route('activities.index')
                ->with('success', 'Activité supprimée avec succès!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('activities.index')
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    

    /**
     * Search activities (autocomplete)
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $activities = Activity::with('categorie')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('slug', 'like', "%{$query}%")
            ->orWhereHas('categorie', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'slug', 'categorie_id', 'is_active']);
            
        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }

    /**
     * Get activities for dropdown
     */
    public function getForDropdown()
    {
        $activities = Activity::with('categorie')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'categorie_id']);

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }

    /**
     * Toggle activity status
     */
    public function toggleStatus(Request $request, Activity $activity)
    {
        try {
            $activity->update([
                'is_active' => !$activity->is_active
            ]);

            $status = $activity->is_active ? 'activée' : 'désactivée';

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Activité ' . $status . ' avec succès!',
                    'data' => $activity
                ]);
            }

            return redirect()->route('activities.index')
                ->with('success', 'Activité ' . $status . ' avec succès!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du changement de statut: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('activities.index')
                ->with('error', 'Erreur lors du changement de statut: ' . $e->getMessage());
        }
    }

    /**
     * Export activities data
     */
    public function export(Request $request)
    {
        $activities = Activity::with(['categorie'])
            ->withCount(['participants', 'bookings'])
            ->orderBy('name')
            ->get();

        if ($request->format === 'csv') {
            return response()->json([
                'success' => true,
                'message' => 'Export CSV non implémenté',
                'data' => $activities
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $activities,
            'total' => $activities->count()
        ]);
    }

    /**
     * Bulk update activities
     */
    public function bulkUpdate(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:activities,id',
                'action' => 'required|in:activate,deactivate,delete',
            ]);

            $count = 0;
            $message = '';

            switch ($validated['action']) {
                case 'activate':
                    Activity::whereIn('id', $validated['ids'])->update(['is_active' => true]);
                    $count = count($validated['ids']);
                    $message = $count . ' activité(s) activée(s) avec succès!';
                    break;
                    
                case 'deactivate':
                    Activity::whereIn('id', $validated['ids'])->update(['is_active' => false]);
                    $count = count($validated['ids']);
                    $message = $count . ' activité(s) désactivée(s) avec succès!';
                    break;
                    
                case 'delete':
                    // Vérifier qu'aucune activité n'est utilisée
                    $usedActivities = Activity::whereIn('id', $validated['ids'])
                        ->where(function($query) {
                            $query->has('participants')->orHas('bookings');
                        })
                        ->count();
                        
                    if ($usedActivities > 0) {
                        throw new \Exception('Certaines activités ne peuvent pas être supprimées car elles sont utilisées.');
                    }
                    
                    Activity::whereIn('id', $validated['ids'])->delete();
                    $count = count($validated['ids']);
                    $message = $count . ' activité(s) supprimée(s) avec succès!';
                    break;
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'count' => $count
                ]);
            }

            return redirect()->route('activities.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'opération en masse: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('activities.index')
                ->with('error', 'Erreur lors de l\'opération en masse: ' . $e->getMessage());
        }
    }

    /**
     * Get activities by category
     */
    public function getByCategory(Category $categorie)
    {
        $activities = $categorie->activities()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'price', 'duration']);

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }


public function checkSlug(Request $request)
{
    $request->validate([
        'slug' => 'required|string'
    ]);
    
    $slug = $request->input('slug');
    $exists = Activity::where('slug', $slug)->exists();
    
    return response()->json([
        'available' => !$exists,
        'slug' => $slug
    ]);
}
}