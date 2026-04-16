<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Province;
use App\Models\Region;
use App\Models\Secteur;
use App\Models\Ville;
use App\Models\Etablissement;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['success' => true, 'results' => []]);
        }

        $results = [];

        // Search Continents
        $continents = Continent::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get();
        foreach ($continents as $continent) {
            $results[] = [
                'type' => 'continent',
                'title' => $continent->name,
                'subtitle' => $continent->countries_count ? "{$continent->countries_count} pays" : '',
                'url' => route('continents.index', $continent->id),
                'badge' => $continent->code ?? null,
            ];
        }

        // Search Countries
        $countries = Country::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('code', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get();
        foreach ($countries as $country) {
            $results[] = [
                'type' => 'country',
                'title' => $country->name,
                'subtitle' => $country->continent->name ?? '',
                'url' => route('countries.index', $country->id),
                'badge' => $country->code,
            ];
        }

        // Search Provinces
        $provinces = Province::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get();
        foreach ($provinces as $province) {
            $results[] = [
                'type' => 'province',
                'title' => $province->name,
                'subtitle' => $province->country->name ?? '',
                'url' => route('provinces.index', $province->id),
                'badge' => $province->code,
            ];
        }

        // Search Regions
        $regions = Region::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get();
        foreach ($regions as $region) {
            $results[] = [
                'type' => 'region',
                'title' => $region->name,
                'subtitle' => $region->province->name ?? '',
                'url' => route('regions.index', $region->id),
                'badge' => null,
            ];
        }

        // Search Secteurs
        $secteurs = Secteur::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get();
        foreach ($secteurs as $secteur) {
            $results[] = [
                'type' => 'secteur',
                'title' => $secteur->name,
                'subtitle' => $secteur->region->name ?? '',
                'url' => route('secteurs.index', $secteur->id),
                'badge' => $secteur->classification,
            ];
        }

        // Search Villes
        $villes = Ville::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get();
        foreach ($villes as $ville) {
            $results[] = [
                'type' => 'ville',
                'title' => $ville->name,
                'subtitle' => $ville->province->name ?? $ville->region->name ?? '',
                'url' => route('villes.index', $ville->id),
                'badge' => $ville->classification,
            ];
        }

        // Search Activities
        $activities = Activity::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get();
        foreach ($activities as $activity) {
            $results[] = [
                'type' => 'activity',
                'title' => $activity->name,
                'subtitle' => $activity->description,
                'url' => route('activities.show', $activity->id),
                'badge' => $activity->type,
            ];
        }

        // Search Etablissements
        $etablissements = Etablissement::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('lname', 'like', "%{$query}%")
                  ->orWhere('ville', 'like', "%{$query}%");
            })
            ->limit(3)
            ->get();
        foreach ($etablissements as $etablissement) {
            $results[] = [
                'type' => 'etablissement',
                'title' => $etablissement->name,
                'subtitle' => $etablissement->ville . ' - ' . $etablissement->zip_code,
                'url' => url('admin/cms/' . $etablissement->id . '/dashboard'),
                'badge' => $etablissement->is_active ? 'Actif' : 'Inactif',
            ];
        }

        // Search Projects
        $projects = Project::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get();
        foreach ($projects as $project) {
            $results[] = [
                'type' => 'project',
                'title' => $project->name,
                'subtitle' => $project->status,
                'url' => route('projects.show', $project->id),
                'badge' => $project->progress . '%',
            ];
        }

        // Search Tasks
        $tasks = Task::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get();
        foreach ($tasks as $task) {
            $results[] = [
                'type' => 'task',
                'title' => $task->name,
                'subtitle' => $task->project->name ?? '',
                'url' => route('tasks.show', $task->id),
                'badge' => $task->status,
            ];
        }

        // Sort results (you can customize the order)
        usort($results, function($a, $b) {
            $order = ['continent', 'country', 'province', 'region', 'ville', 'activity', 'project', 'task'];
            $posA = array_search($a['type'], $order) ?? 999;
            $posB = array_search($b['type'], $order) ?? 999;
            return $posA - $posB;
        });

        return response()->json([
            'success' => true,
            'results' => $results,
            'total' => count($results),
        ]);
    }
}