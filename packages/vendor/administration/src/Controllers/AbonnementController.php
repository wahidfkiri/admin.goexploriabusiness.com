<?php

namespace Vendor\Administration\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use App\Models\Plan;
use App\Models\Abonnement;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Vendor\Administration\Exports\AbonnementsExport;
use Vendor\Administration\Exports\EtablissementsSubscriptionExport;

class AbonnementController extends Controller
{
    
public function index(Request $request)
{
    $query = Abonnement::with(['etablissement', 'plan']);
    
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    if ($request->filled('payment_status')) {
        $query->where('payment_status', $request->payment_status);
    }
    
    if ($request->filled('plan_id')) {
        $query->where('plan_id', $request->plan_id);
    }
    
    if ($request->filled('date_from')) {
        $query->whereDate('start_date', '>=', $request->date_from);
    }
    
    if ($request->filled('date_to')) {
        $query->whereDate('end_date', '<=', $request->date_to);
    }
    
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereHas('etablissement', function($q2) use ($search) {
                $q2->where('name', 'like', "%{$search}%")
                  ->orWhere('lname', 'like', "%{$search}%");
            })->orWhere('reference', 'like', "%{$search}%");
        });
    }
    
    $abonnements = $query->orderBy('created_at', 'desc')->paginate(15);
    
    $stats = [
        'total' => Abonnement::count(),
        'active' => Abonnement::where('status', 'active')->where('end_date', '>=', now())->count(),
        'expired' => Abonnement::where('status', 'expired')->orWhere('end_date', '<', now())->count(),
        'total_revenue' => Paiement::where('status', 'completed')->sum('amount')
    ];
    
    $plans = Plan::active()->get();
    
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'abonnements' => [
                'data' => $abonnements->items(),
                'links' => (string) $abonnements->links() // Convertir en string pour éviter l'objet
            ],
            'stats' => $stats
        ]);
    }
    
    return view('administration::abonnements.index', compact('abonnements', 'stats', 'plans'));
}

public function create()
{
    $etablissements = Etablissement::orderBy('name')->get();
    $plans = Plan::active()->ordered()->get();
    
    return view('administration::abonnements.create', compact('etablissements', 'plans'));
}

public function edit($id)
{
    $abonnement = Abonnement::with(['etablissement', 'plan', 'paiements'])->findOrFail($id);
    $etablissements = Etablissement::orderBy('name')->get();
    $plans = Plan::active()->ordered()->get();
    
    return view('administration::abonnements.edit', compact('abonnement', 'etablissements', 'plans'));
}

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'etablissement_id' => 'required|exists:etablissements,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'amount_paid' => 'required|numeric|min:0',
            'status' => 'required|in:active,expired,cancelled,pending',
            'payment_status' => 'required|in:paid,unpaid,partial,refunded',
            'auto_renew' => 'boolean',
            'payment_method' => 'required_if:payment_status,paid|in:card,bank_transfer,mobile_money,cash,other'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $plan = Plan::find($request->plan_id);
            
            $abonnement = Abonnement::create([
                'etablissement_id' => $request->etablissement_id,
                'plan_id' => $request->plan_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'amount_paid' => $request->amount_paid,
                'currency' => $plan->currency,
                'status' => $request->status,
                'payment_status' => $request->payment_status,
                'auto_renew' => $request->boolean('auto_renew')
            ]);

            // Si le paiement est effectué, créer l'enregistrement de paiement
            if ($request->payment_status === 'paid') {
                $paiement = Paiement::create([
                    'etablissement_id' => $request->etablissement_id,
                    'abonnement_id' => $abonnement->id,
                    'amount' => $request->amount_paid,
                    'currency' => $plan->currency,
                    'payment_method' => $request->payment_method,
                    'status' => 'completed',
                    'paid_at' => now(),
                    'notes' => 'Paiement pour abonnement #' . $abonnement->reference
                ]);
                
                $paiement->markAsCompleted();
            }

            // Mettre à jour l'établissement si l'abonnement est actif
            if ($request->status === 'active') {
                $etablissement = Etablissement::find($request->etablissement_id);
                $etablissement->current_abonnement_id = $abonnement->id;
                $etablissement->subscription_expires_at = $request->end_date;
                $etablissement->subscription_status = 'active';
                $etablissement->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Abonnement créé avec succès',
                'redirect' => route('abonnements.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création: ' . $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $abonnement = Abonnement::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'etablissement_id' => 'required|exists:etablissements,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'amount_paid' => 'required|numeric|min:0',
            'status' => 'required|in:active,expired,cancelled,pending',
            'payment_status' => 'required|in:paid,unpaid,partial,refunded',
            'auto_renew' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $oldStatus = $abonnement->status;
            
            $abonnement->update([
                'etablissement_id' => $request->etablissement_id,
                'plan_id' => $request->plan_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'amount_paid' => $request->amount_paid,
                'status' => $request->status,
                'payment_status' => $request->payment_status,
                'auto_renew' => $request->boolean('auto_renew')
            ]);

            // Mettre à jour l'établissement si le statut a changé
            if ($oldStatus !== $request->status) {
                $etablissement = Etablissement::find($request->etablissement_id);
                
                if ($request->status === 'active') {
                    $etablissement->current_abonnement_id = $abonnement->id;
                    $etablissement->subscription_expires_at = $request->end_date;
                    $etablissement->subscription_status = 'active';
                } else {
                    if ($etablissement->current_abonnement_id == $abonnement->id) {
                        $etablissement->current_abonnement_id = null;
                        $etablissement->subscription_status = 'expired';
                    }
                }
                $etablissement->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Abonnement mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
{
    $abonnement = Abonnement::with(['etablissement', 'plan', 'paiements'])->findOrFail($id);
    
    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'abonnement' => $abonnement,
            'html' => view('admin.abonnements.partials.show', compact('abonnement'))->render()
        ]);
    }
    
    return view('administration::abonnements.show', compact('abonnement'));
}

    public function destroy($id)
    {
        try {
            $abonnement = Abonnement::findOrFail($id);
            
            // Vérifier s'il y a des paiements associés
            if ($abonnement->paiements()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer cet abonnement car il a des paiements associés'
                ], 400);
            }
            
            $abonnement->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Abonnement supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancel(Request $request, $id)
    {
        $abonnement = Abonnement::findOrFail($id);
        
        try {
            $abonnement->cancel($request->reason);
            
            return response()->json([
                'success' => true,
                'message' => 'Abonnement annulé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function renew($id)
    {
        try {
            $oldAbonnement = Abonnement::findOrFail($id);
            
            DB::beginTransaction();
            
            $newEndDate = Carbon::parse($oldAbonnement->end_date)->addDays($oldAbonnement->plan->duration_days);
            
            $newAbonnement = Abonnement::create([
                'etablissement_id' => $oldAbonnement->etablissement_id,
                'plan_id' => $oldAbonnement->plan_id,
                'start_date' => $oldAbonnement->end_date,
                'end_date' => $newEndDate,
                'amount_paid' => $oldAbonnement->amount_paid,
                'currency' => $oldAbonnement->currency,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'auto_renew' => $oldAbonnement->auto_renew
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Renouvellement initié avec succès',
                'abonnement' => $newAbonnement
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du renouvellement: ' . $e->getMessage()
            ], 500);
        }
    }

    public function etablissements(Request $request)
    {
        $query = Etablissement::with(['currentAbonnement.plan']);
        
        // Filtres
        if ($request->filled('subscription_status')) {
            if ($request->subscription_status === 'paid') {
                $query->whereNotNull('current_abonnement_id')
                      ->where('subscription_expires_at', '>=', now());
            } elseif ($request->subscription_status === 'unpaid') {
                $query->where(function($q) {
                    $q->whereNull('current_abonnement_id')
                      ->orWhere('subscription_expires_at', '<', now());
                });
            }
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('lname', 'like', "%{$search}%")
                  ->orWhere('email_contact', 'like', "%{$search}%");
            });
        }
        
        $etablissements = $query->orderBy('name')->paginate(15);
        
        $stats = [
            'total' => Etablissement::count(),
            'paid' => Etablissement::whereNotNull('current_abonnement_id')
                        ->where('subscription_expires_at', '>=', now())->count(),
            'unpaid' => Etablissement::where(function($q) {
                            $q->whereNull('current_abonnement_id')
                              ->orWhere('subscription_expires_at', '<', now());
                        })->count(),
            'expiring_soon' => Etablissement::whereNotNull('current_abonnement_id')
                                ->where('subscription_expires_at', '<=', now()->addDays(30))
                                ->where('subscription_expires_at', '>=', now())
                                ->count()
        ];
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => view('admin.abonnements.partials.etablissements_table', compact('etablissements'))->render(),
                'pagination' => (string) $etablissements->links(),
                'stats' => $stats
            ]);
        }
        
        return view('admin.abonnements.etablissements', compact('etablissements', 'stats'));
    }

   public function historique($id)
{
    $etablissement = Etablissement::findOrFail($id);
    $historique = Abonnement::with(['plan', 'paiements'])
                            ->where('etablissement_id', $id)
                            ->orderBy('created_at', 'desc')
                            ->get();
    
    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'html' => view('admin.abonnements.partials.historique', compact('etablissement', 'historique'))->render()
        ]);
    }
    
    return view('administration::abonnements.historique', compact('etablissement', 'historique'));
}

 public function export(Request $request)
    {
        try {
            $filters = $request->only(['status', 'payment_status', 'plan_id', 'date_from', 'date_to', 'search']);
            
            // Remove empty filters
            $filters = array_filter($filters, function($value) {
                return !is_null($value) && $value !== '';
            });
            
            $export = new AbonnementsExport($filters);
            $filename = 'abonnements_' . date('Y-m-d_His') . '.xlsx';
            
            return Excel::download($export, $filename);
        } catch (\Exception $e) {
            \Log::error('Export error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'export: ' . $e->getMessage());
        }
    }


    public function exportEtablissements(Request $request)
{
    $filters = $request->only(['subscription_status', 'search']);
    
    $export = new EtablissementsSubscriptionExport($filters);
    
    $filename = 'etablissements_subscription_' . date('Y-m-d_His') . '.xlsx';
    
    return Excel::download($export, $filename);
}


}