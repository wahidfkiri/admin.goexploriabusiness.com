<?php

namespace Vendor\Administration\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    
public function index(Request $request)
{
    $query = Plan::withCount(['abonnements as subscribers_count'])
        ->withCount(['abonnements as active_abonnements_count' => function($q) {
            $q->where('status', 'active')->where('end_date', '>=', now());
        }]);
    
    if ($request->filled('status')) {
        $query->where('is_active', $request->status === 'active');
    }
    
    if ($request->filled('billing_cycle')) {
        $query->where('billing_cycle', $request->billing_cycle);
    }
    
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }
    
    $plans = $query->orderBy('sort_order')->orderBy('price')->get();
    
    $totalPlans = Plan::count();
    $activePlans = Plan::where('is_active', true)->count();
    $popularPlans = Plan::where('is_popular', true)->count();
    $activeSubscribers = \App\Models\Abonnement::where('status', 'active')
                            ->where('end_date', '>=', now())
                            ->count();
    
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'plans' => $plans,
            'stats' => compact('totalPlans', 'activePlans', 'popularPlans', 'activeSubscribers')
        ]);
    }
    
    return view('administration::plans.index', compact('plans', 'totalPlans', 'activePlans', 'popularPlans', 'activeSubscribers'));
}

public function create()
{
    return view('administration::plans.create');
}

public function edit($id)
{
    $plan = Plan::withCount(['abonnements as abonnements_count'])
        ->withCount(['abonnements as active_abonnements_count' => function($q) {
            $q->where('status', 'active')->where('end_date', '>=', now());
        }])
        ->withSum(['abonnements as total_revenue' => function($q) {
            $q->where('payment_status', 'paid');
        }], 'amount_paid')
        ->findOrFail($id);
    
    return view('administration::plans.edit', compact('plan'));
}

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:plans',
            'description' => 'nullable|string',
            'services' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'duration_days' => 'required|integer|min:1',
            'billing_cycle' => 'required|in:monthly,yearly,custom',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_popular' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $plan = Plan::create([
                'name' => $request->name,
                'description' => $request->description,
                'services' => $request->services,
                'price' => $request->price,
                'currency' => $request->currency,
                'duration_days' => $request->duration_days,
                'billing_cycle' => $request->billing_cycle,
                'features' => $request->features ?? [],
                'limits' => $request->limits ?? [],
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->boolean('is_active'),
                'is_popular' => $request->boolean('is_popular')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Plan créé avec succès',
                'plan' => $plan
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du plan: ' . $e->getMessage()
            ], 500);
        }
    }

    

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:plans,name,' . $id,
            'description' => 'nullable|string',
            'services' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'duration_days' => 'required|integer|min:1',
            'billing_cycle' => 'required|in:monthly,yearly,custom',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_popular' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $plan->update([
                'name' => $request->name,
                'description' => $request->description,
                'services' => $request->services,
                'price' => $request->price,
                'currency' => $request->currency,
                'duration_days' => $request->duration_days,
                'billing_cycle' => $request->billing_cycle,
                'features' => $request->features ?? [],
                'limits' => $request->limits ?? [],
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->boolean('is_active'),
                'is_popular' => $request->boolean('is_popular')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Plan mis à jour avec succès',
                'plan' => $plan
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du plan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $plan = Plan::findOrFail($id);
            
            if ($plan->abonnements()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer ce plan car il est utilisé par des abonnements'
                ], 400);
            }
            
            $plan->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Plan supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $plan = Plan::findOrFail($id);
            $plan->is_active = !$plan->is_active;
            $plan->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Statut modifié avec succès',
                'is_active' => $plan->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification du statut'
            ], 500);
        }
    }

    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:plans,id',
            'orders.*.order' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            
            foreach ($request->orders as $item) {
                Plan::where('id', $item['id'])->update(['sort_order' => $item['order']]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Ordre mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'ordre'
            ], 500);
        }
    }
}