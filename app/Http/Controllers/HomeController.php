<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Etablissement;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Template;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Récupérer les statistiques depuis la base de données
        $totalPays = Country::count();
        $totalEtablissements = Etablissement::count();
        $totalProjetsEnCours = Project::whereIn('status', ['en_cours', 'active'])->count();
        $totalTasks = Task::count();
        $totalUsers = User::count();
        $totalTemplates = Template::count();
        
        // Récupérer les activités récentes
        $recentActivities = $this->getRecentActivities();
        
        // Récupérer les projets avec leur progression
        $projects = Project::with(['etablissement', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        return view('home', compact(
            'totalPays',
            'totalEtablissements', 
            'totalProjetsEnCours',
            'totalTasks',
            'totalUsers',
            'totalTemplates',
            'recentActivities',
            'projects'
        ));
    }
    
    private function getRecentActivities()
    {
        // Logique pour récupérer les activités récentes
        // Vous pouvez créer une table activities ou combiner plusieurs sources
        
        return collect([
            // Ces données devraient venir de votre base de données
            // Exemple avec des modèles différents
        ]);
    }
}