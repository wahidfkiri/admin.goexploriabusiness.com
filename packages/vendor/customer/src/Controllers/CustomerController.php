<?php

namespace Vendor\Customer\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Vendor\Customer\Requests\StoreCustomerRequest;
use Vendor\Customer\Requests\UpdateCustomerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerController extends Controller
{
    // Liste des pays pour les filtres
    private $countries = [
        'France', 'Belgique', 'Suisse', 'Canada', 'États-Unis', 'Royaume-Uni',
        'Allemagne', 'Espagne', 'Italie', 'Portugal', 'Pays-Bas', 'Luxembourg'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::with(['user', 'websites'])->withCount('websites');
        
        // Recherche
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%")
                  ->orWhere('country', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Filtres
        if ($request->has('country') && $request->country != '') {
            $query->where('country', $request->country);
        }
        
        if ($request->has('city') && $request->city != '') {
            $query->where('city', 'LIKE', "%{$request->city}%");
        }
        
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filtre par utilisateur associé
        if ($request->has('has_user')) {
            if ($request->has_user === 'yes') {
                $query->whereNotNull('user_id');
            } elseif ($request->has_user === 'no') {
                $query->whereNull('user_id');
            }
        }
        
        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        // Pagination
        $perPage = $request->get('per_page', 15);
        $customers = $query->paginate($perPage);
        
        // Si c'est une requête AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $customers->items(),
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'per_page' => $customers->perPage(),
                'total' => $customers->total(),
                'prev_page_url' => $customers->previousPageUrl(),
                'next_page_url' => $customers->nextPageUrl(),
            ]);
        }
        
        // Pour la vue normale
        $users = User::doesntHave('customer')->get();
        
        return view('customer::index', [
            'customers' => $customers,
            'users' => $users,
            'countries' => $this->countries
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        try {
            DB::beginTransaction();
            
             $data = $request->validated();
           
            
                // Valider les données de l'utilisateur
                $userValidator = validator($request->all(), [
                    'name' => 'required|string|max:255',
                    'login' => 'required|email|unique:users,email',
                    'password' => 'required|string|min:8'
                ], [
                    'name.required' => 'Le nom de l\'utilisateur est requis.',
                    'login.required' => 'L\'email de l\'utilisateur est requis.',
                    'login.email' => 'L\'email de l\'utilisateur doit être valide.',
                    'login.unique' => 'Cet email est déjà utilisé.',
                    'password.required' => 'Le mot de passe est requis.',
                    'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.'
                ]);
                
                if ($userValidator->fails()) {
                    throw ValidationException::withMessages($userValidator->errors()->toArray());
                }
                
                // Créer l'utilisateur
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->login,
                    'password' => Hash::make($request->password),
                    'email_verified_at' => now(),
                ]);
                
            
            // Créer le client
            $customer = Customer::create($data);
            $customer->user_id = $user->id;
            $customer->save();
            
            // Si un utilisateur existe mais n'a pas de nom, mettre à jour avec le nom du client
            if ($customer->user && empty($customer->user->name)) {
                $customer->user->update(['name' => $customer->name]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Client créé avec succès.',
                'customer' => $customer->load('user')
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de la création du client : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du client : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            DB::beginTransaction();
            
            $data = $request->validated();
            
            
            // Mettre à jour le client
            $customer->update($data);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Client mis à jour avec succès.',
                'customer' => $customer->load('user')
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de la mise à jour du client : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du client : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            // Vérifier si le client a des sites web
            if ($customer->websites()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer un client avec des sites web associés.'
                ], 422);
            }
            
            $customer->delete();
            
            // Option: Supprimer l'utilisateur associé si demandé
            // if ($request->has('delete_user') && $customer->user) {
            //     $customer->user->delete();
            // }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Client supprimé avec succès.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du client : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete customers.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'customer_ids' => 'required|array',
            'customer_ids.*' => 'exists:customers,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $customerIds = $request->input('customer_ids');
            $customers = Customer::whereIn('id', $customerIds)->get();
            
            $deletedCount = 0;
            $failedCount = 0;
            
            foreach ($customers as $customer) {
                if ($customer->websites()->count() === 0) {
                    $customer->delete();
                    $deletedCount++;
                } else {
                    $failedCount++;
                }
            }
            
            DB::commit();
            
            $message = "{$deletedCount} client(s) supprimé(s) avec succès.";
            
            if ($failedCount > 0) {
                $message .= " {$failedCount} client(s) n'ont pas pu être supprimés (sites web associés).";
            }
            
            return response()->json([
                'success' => $deletedCount > 0,
                'message' => $message,
                'deleted_count' => $deletedCount,
                'failed_count' => $failedCount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression en masse : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Link customer to existing user.
     */
    public function linkToUser(Request $request, Customer $customer): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $userId = $request->input('user_id');
            
            // Vérifier si l'utilisateur n'est pas déjà associé
            $existingCustomer = Customer::where('user_id', $userId)->first();
            if ($existingCustomer && $existingCustomer->id != $customer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet utilisateur est déjà associé à un autre client.'
                ], 422);
            }
            
            $customer->update(['user_id' => $userId]);
            
            // Mettre à jour le nom de l'utilisateur si vide
            $user = User::find($userId);
            if ($user && empty($user->name)) {
                $user->update(['name' => $customer->name]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Client lié à l\'utilisateur avec succès.',
                'customer' => $customer->load('user')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la liaison : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unlink customer from user.
     */
    public function unlinkFromUser(Customer $customer): JsonResponse
    {
        try {
            $customer->update(['user_id' => null]);
            
            return response()->json([
                'success' => true,
                'message' => 'Client dissocié de l\'utilisateur avec succès.',
                'customer' => $customer->load('user')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la dissociation : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available users for linking.
     */
    public function availableUsers(): JsonResponse
    {
        try {
            $users = User::doesntHave('customer')
                ->select('id', 'name', 'email')
                ->orderBy('name')
                ->get();
            
            return response()->json([
                'success' => true,
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des utilisateurs.'
            ], 500);
        }
    }

    /**
     * Get customer statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $totalCustomers = Customer::count();
            $customersWithWebsites = Customer::has('websites')->count();
            $customersWithUsers = Customer::whereNotNull('user_id')->count();
            $countriesCount = Customer::distinct('country')->count('country');
            $thisMonth = Customer::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            $customersByCountry = Customer::select('country', DB::raw('count(*) as count'))
                ->whereNotNull('country')
                ->groupBy('country')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $totalCustomers,
                    'with_websites' => $customersWithWebsites,
                    'with_users' => $customersWithUsers,
                    'without_users' => $totalCustomers - $customersWithUsers,
                    'countries_count' => $countriesCount,
                    'this_month' => $thisMonth,
                    'by_country' => $customersByCountry
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des statistiques.'
            ], 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show(Customer $customer): JsonResponse
    {
        try {
            $customer->load(['user', 'websites.category', 'websites' => function($query) {
                $query->latest()->limit(5);
            }]);
            
            return response()->json([
                'success' => true,
                'customer' => $customer,
                'websites_count' => $customer->websites()->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement du client.'
            ], 500);
        }
    }
}