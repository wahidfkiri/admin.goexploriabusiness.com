<?php

namespace Vendor\Cms\Controllers\Admin;

use App\Http\Controllers\Controller;
use Vendor\Cms\Models\Page;
use Vendor\Cms\Models\Theme;
use Vendor\Cms\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Etablissement;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Affiche le dashboard CMS
     */
    public function index(Request $request)
    {
        // Méthode 1: Récupérer l'établissement depuis l'utilisateur connecté
        $user = Auth::user();
        
        // Si l'utilisateur a une relation avec établissement
        if ($user && method_exists($user, 'etablissement')) {
            $etablissement = $user->etablissement;
        } 
        // Sinon, récupérer depuis la session ou le premier établissement
      //  else {
            $etablissement = Etablissement::first();
      //  }
        
        // Vérifier si un établissement a été trouvé
        // if (!$etablissement) {
        //     // Créer un établissement par défaut si nécessaire
        //     $etablissement = Etablissement::create([
        //         'name' => 'Établissement par défaut',
        //         'user_id' => $user ? $user->id : 1,
        //         'is_active' => true,
        //     ]);
        // }
        
        // Récupérer les statistiques
        $stats = [
            'total_pages' => Page::where('etablissement_id', $etablissement->id)->count(),
            'published_pages' => Page::where('etablissement_id', $etablissement->id)
                ->where('status', 'published')
                ->count(),
            'draft_pages' => Page::where('etablissement_id', $etablissement->id)
                ->where('status', 'draft')
                ->count(),
            'total_themes' => Theme::where('etablissement_id', $etablissement->id)->count(),
            'active_theme' => Theme::where('etablissement_id', $etablissement->id)
                ->where('is_active', true)
                ->first(),
            'recent_pages' => Page::where('etablissement_id', $etablissement->id)
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get(),
            'etablissement' => $etablissement, // Important: passer l'établissement
        ];
        
        return view('cms::admin.dashboard', compact('stats'));
    }
}