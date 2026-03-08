<?php

namespace Vendor\Destination\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Récupérer les paramètres de recherche et de filtrage
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $perPage = $request->input('per_page', 10);

        // Construire la requête
        $query = Destination::query();

        // Appliquer les filtres
        if (!empty($search)) {
            $query->search($search);
        }

        if (!empty($status)) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } 
            elseif ($status === 'deleted') {
                $query->onlyTrashed();
            }
        }

        // Trier par date de création
        $query->orderBy('created_at', 'desc');

        // Si c'est une requête AJAX, retourner JSON
        if ($request->ajax()) {
            $destinations = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $destinations->items(),
                'current_page' => $destinations->currentPage(),
                'last_page' => $destinations->lastPage(),
                'per_page' => $destinations->perPage(),
                'total' => $destinations->total(),
                'next_page_url' => $destinations->nextPageUrl(),
                'prev_page_url' => $destinations->previousPageUrl(),
            ]);
        }

        // Pour les requêtes non-AJAX, retourner la vue
        $destinations = $query->paginate($perPage);
        return view('destination::index', compact('destinations', 'search', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Traitement de l'image
      //  $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('destinations', 'public');
        }

        // Création de la destination
        $destination = Destination::create([
            'name' => $request->name,
            'image' => $imagePath,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Destination créée avec succès !',
            'data' => $destination
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $destination = Destination::findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $destination
            ]);
        }

        return view('destinations.show', compact('destination'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $destination = Destination::withTrashed()->findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Traitement de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($destination->image && Storage::disk('public')->exists($destination->image)) {
                Storage::disk('public')->delete($destination->image);
            }
            
            $imagePath = $request->file('image')->store('destinations', 'public');
            $destination->image = $imagePath;
        }

        // Mise à jour
        $destination->name = $request->name;
        $destination->description = $request->description;
        $destination->is_active = $request->boolean('is_active', true);
        
        // Regénérer le slug si le nom a changé
        if ($destination->isDirty('name')) {
            $destination->slug = Str::slug($request->name);
        }

        $destination->save();

        return response()->json([
            'success' => true,
            'message' => 'Destination mise à jour avec succès !',
            'data' => $destination
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $destination = Destination::findOrFail($id);
        
        // Supprimer l'image si elle existe
        if ($destination->image && Storage::disk('public')->exists($destination->image)) {
            Storage::disk('public')->delete($destination->image);
        }
        
        $destination->delete();

        return response()->json([
            'success' => true,
            'message' => 'Destination supprimée avec succès !'
        ]);
    }

    /**
     * Restore the specified soft deleted resource.
     */
    public function restore($id)
    {
        $destination = Destination::onlyTrashed()->findOrFail($id);
        $destination->restore();

        return response()->json([
            'success' => true,
            'message' => 'Destination restaurée avec succès !'
        ]);
    }

    /**
     * Force delete the specified resource.
     */
    public function forceDelete($id)
    {
        $destination = Destination::onlyTrashed()->findOrFail($id);
        
        // Supprimer l'image si elle existe
        if ($destination->image && Storage::disk('public')->exists($destination->image)) {
            Storage::disk('public')->delete($destination->image);
        }
        
        $destination->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Destination définitivement supprimée !'
        ]);
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus($id)
    {
        $destination = Destination::findOrFail($id);
        $destination->is_active = !$destination->is_active;
        $destination->save();

        return response()->json([
            'success' => true,
            'message' => 'Statut modifié avec succès !',
            'is_active' => $destination->is_active
        ]);
    }

    /**
     * Get statistics for destinations.
     */
    public function statistics()
    {
        $total = Destination::count();
        $active = Destination::where('is_active', true)->count();
        $inactive = Destination::where('is_active', false)->count();
        $deleted = Destination::onlyTrashed()->count();
        
        $thisMonth = Destination::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
                'deleted' => $deleted,
                'this_month' => $thisMonth,
            ]
        ]);
    }

    public function getActiveDestinations(Request $request)
    {
        try {
            // Récupérer les destinations actives
            $destinations = Destination::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'image', 'is_active']);
            
            // Formater les données pour le frontend
            $formattedDestinations = $destinations->map(function ($destination) {
                return [
                    'id' => $destination->id,
                    'title' => $destination->name,
                    'image' => $destination->image ? asset('storage/' . $destination->image) : 'https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80',
                    'link' => route('destination.show', $destination->id),
                    'is_active' => $destination->is_active
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $formattedDestinations,
                'count' => $destinations->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des destinations',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}