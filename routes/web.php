<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    EditorController,
    TemplateController,
    OpenAIController,
    AuthController,
    GeminiController,
    HomeController
};

use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\AjaxAuthController;
use App\Http\Controllers\TemplateScraperController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
Route::post('/chat/clear-history', [ChatController::class, 'clearHistory'])->name('chat.clear-history');
// Page de login
Route::get('/', function () {
    return view('welcome');
});
Route::get('/welcome', function () {
    return view('welcome1');
});
Route::get('/test', function () {
    return view('test');
});
// Page de login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Route pour le dashboard (à protéger)
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard')->middleware('auth');

// Route pour le dashboard (à protéger)
Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');

// Authentification Ajax
Route::post('/ajax-login', [AjaxAuthController::class, 'login'])->name('ajax.login');
Route::post('/ajax/register', [AuthController::class, 'ajaxRegister'])->name('ajax.register');
Route::get('/ajax-register', [AjaxAuthController::class, 'showRegisterForm'])->name('register');
// Routes d'authentification sociale
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback']);

// Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');


// Éditeur (protégé)
Route::middleware('auth')->group(function () {



    
    // Profil utilisateur
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        
        // Mise à jour des informations
        Route::put('/info', [ProfileController::class, 'updateInfo'])->name('update.info');
        Route::post('/avatar', [ProfileController::class, 'updateAvatar'])->name('update.avatar');
        
        // Sécurité
        Route::put('/password', [ProfileController::class, 'changePassword'])->name('change.password');
        Route::post('/2fa/toggle', [ProfileController::class, 'toggleTwoFactor'])->name('2fa.toggle');
        Route::delete('/sessions/{session}', [ProfileController::class, 'revokeSession'])->name('sessions.revoke');
        
        // Préférences
        Route::put('/preferences', [ProfileController::class, 'updatePreferences'])->name('update.preferences');
        
        // Notifications
        Route::put('/notifications', [ProfileController::class, 'updateNotifications'])->name('update.notifications');
        
        // Activités
        Route::get('/activities', [ProfileController::class, 'getActivities'])->name('activities');
    });


// Template scraping routes
Route::prefix('/scrape/templates')->group(function () {
    Route::get('/', [TemplateScraperController::class, 'index'])->name('templates.index');
    Route::get('/create', [TemplateScraperController::class, 'create'])->name('templates.create');
    Route::post('/', [TemplateScraperController::class, 'store'])->name('scrape.templates.store');
    Route::get('/{template}', [TemplateScraperController::class, 'show'])->name('templates.show');
    Route::get('/{template}/edit', [TemplateScraperController::class, 'edit'])->name('templates.edit');
    Route::put('/{template}', [TemplateScraperController::class, 'update'])->name('templates.update');
    Route::delete('/{template}', [TemplateScraperController::class, 'destroy'])->name('templates.destroy');
    Route::post('/scrape-now', [TemplateScraperController::class, 'scrapeNow'])->name('templates.scrape-now');
    Route::get('/{template}/preview', [TemplateScraperController::class, 'preview'])->name('templates.preview');
    Route::get('/{template}/raw-html', [TemplateScraperController::class, 'rawHtml'])->name('templates.raw-html');
    Route::get('/{template}/raw-css', [TemplateScraperController::class, 'rawCss'])->name('templates.raw-css');
});

// API Routes
Route::prefix('api')->group(function () {
    Route::get('/templates', [TemplateScraperController::class, 'apiIndex']);
    Route::post('/templates/scrape', [TemplateScraperController::class, 'apiScrape']);
    Route::get('/templates/{template}', [TemplateScraperController::class, 'apiShow']);
});

// Batch scraping route
Route::post('/batch-scrape', [TemplateScraperController::class, 'batchScrape'])->name('batch.scrape');



// routes/web.php ou routes/api.php

// routes/web.php
Route::get('/gemini/generate', [GeminiController::class, 'generate'])->name('gemini.generate');
Route::get('/gemini/test', [GeminiController::class, 'test'])->name('gemini.test');
});

// API Routes (protégées par Sanctum)
Route::prefix('api')->group(function () {
    
    // Routes publiques
    Route::post('/auth/login', [AuthController::class, 'apiLogin']);
    Route::post('/auth/register', [AuthController::class, 'apiRegister']);
    Route::get('/status', function () {
        return response()->json([
            'status' => 'online',
            'version' => '1.0.0',
            'timestamp' => now()
        ]);
    });
    
    // Routes démo (limitées)
    Route::prefix('demo')->group(function () {
        Route::post('/ai/generate', [OpenAIController::class, 'demoGenerate'])
            ->middleware('throttle:5,1440'); // 5 requêtes par jour
            
        Route::get('/templates', [TemplateController::class, 'demoTemplates']);
    });
    
    // Routes authentifiées
    Route::middleware('auth:sanctum')->group(function () {
        
        // User info
        Route::get('/user', function () {
            return response()->json([
                'user' => auth()->user(),
                'token' => auth()->user()->currentAccessToken()
            ]);
        });
        
        Route::post('/auth/logout', [AuthController::class, 'apiLogout']);
        
        // Templates CRUD
        Route::apiResource('templates', TemplateController::class);
        Route::get('/templates/{id}/preview', [TemplateController::class, 'preview'])->name('templates.preview');
        Route::get('/templates/search/{query}', [TemplateController::class, 'search']);
        Route::post('/templates/{id}/clone', [TemplateController::class, 'clone']);
        
        // OpenAI Routes
        Route::prefix('ai')->group(function () {
            Route::post('/generate', [OpenAIController::class, 'generate'])
                ->middleware('throttle:30,1'); // 30 requêtes par minute
                
            Route::post('/optimize', [OpenAIController::class, 'optimize']);
            Route::post('/chat', [OpenAIController::class, 'chat'])
                ->middleware('throttle:60,1');
                
            Route::post('/code', [OpenAIController::class, 'code']);
            Route::post('/variations', [OpenAIController::class, 'variations']);
            Route::post('/improve', [OpenAIController::class, 'improve']);
            Route::get('/models', [OpenAIController::class, 'models']);
            Route::get('/usage', [OpenAIController::class, 'usage']);
            Route::get('/usage/stats', [OpenAIController::class, 'usageStats']);
            Route::get('/status', [OpenAIController::class, 'status']);
        });
    });

 
});

// Routes de santé
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'services' => [
            'laravel' => app()->version(),
            'php' => PHP_VERSION,
            'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected'
        ]
    ]);
});







Route::get('/search', function () {
    return null;
})->name('search');


Route::get('/test-email', function () {

    $to = "wahidfkiri5@gmail.com";

    Mail::raw('Ceci est un email de test envoyé depuis Laravel avec SMTP GoDaddy.', function ($message) use ($to) {
        $message->to($to)
                ->subject('Test SMTP GoDaddy Laravel')
                ->from('info@goexploriabusiness.com', 'GoExploria Business');
    });

    return "Email envoyé avec succès";
});