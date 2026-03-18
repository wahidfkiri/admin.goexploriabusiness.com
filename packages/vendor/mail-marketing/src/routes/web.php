<?php

use Illuminate\Support\Facades\Route;
use Vendor\MailMarketing\Controllers\MailCampaignController;
use Vendor\MailMarketing\Controllers\MailSubscriberController;
use Vendor\MailMarketing\Controllers\MailTrackingController;

/*
|--------------------------------------------------------------------------
| Routes du module d'email marketing
|--------------------------------------------------------------------------
*/

// Routes protégées par authentification
Route::middleware(['auth','web'])->group(function () {
    
    /**
     * ===========================================================
     * CAMPAGNES EMAIL
     * ===========================================================
     */
    Route::prefix('mail-campaigns')->name('mail-campaigns.')->group(function () {
        
        // CRUD de base
        Route::get('/', [MailCampaignController::class, 'index'])->name('index');
        Route::get('/create', [MailCampaignController::class, 'create'])->name('create');
        Route::post('/', [MailCampaignController::class, 'store'])->name('store');
        Route::get('/{campaign}', [MailCampaignController::class, 'show'])->name('show');
        Route::get('/{campaign}/edit', [MailCampaignController::class, 'edit'])->name('edit');
        Route::put('/{campaign}', [MailCampaignController::class, 'update'])->name('update');
        Route::delete('/{campaign}', [MailCampaignController::class, 'destroy'])->name('destroy');
        
        // Actions spécifiques aux campagnes
        Route::post('/{campaign}/send', [MailCampaignController::class, 'send'])->name('send');
        Route::post('/{campaign}/duplicate', [MailCampaignController::class, 'duplicate'])->name('duplicate');
        Route::get('/{campaign}/stats', [MailCampaignController::class, 'stats'])->name('stats');
        Route::post('/{campaign}/cancel', [MailCampaignController::class, 'cancel'])->name('cancel');
        Route::get('/{campaign}/preview', [MailCampaignController::class, 'preview'])->name('preview');
        Route::post('/{campaign}/test', [MailCampaignController::class, 'sendTest'])->name('test');
        
        // Gestion des destinataires
        Route::get('/{campaign}/recipients', [MailCampaignController::class, 'recipients'])->name('recipients');
        Route::post('/{campaign}/recipients/add', [MailCampaignController::class, 'addRecipients'])->name('recipients.add');
        Route::delete('/{campaign}/recipients/{subscriber}', [MailCampaignController::class, 'removeRecipient'])->name('recipients.remove');
        
        // Export des résultats
        Route::get('/{campaign}/export/opens', [MailCampaignController::class, 'exportOpens'])->name('export.opens');
        Route::get('/{campaign}/export/clicks', [MailCampaignController::class, 'exportClicks'])->name('export.clicks');
        Route::get('/{campaign}/export/bounces', [MailCampaignController::class, 'exportBounces'])->name('export.bounces');
    });
    
    /**
     * ===========================================================
     * ABONNÉS
     * ===========================================================
     */
    Route::prefix('mail-subscribers')->name('mail-subscribers.')->group(function () {
        
        // CRUD de base
        Route::get('/', [MailSubscriberController::class, 'index'])->name('index');
        Route::get('/create', [MailSubscriberController::class, 'create'])->name('create');
        Route::post('/', [MailSubscriberController::class, 'store'])->name('store');
        Route::get('/{subscriber}', [MailSubscriberController::class, 'show'])->name('show');
        Route::get('/{subscriber}/edit', [MailSubscriberController::class, 'edit'])->name('edit');
        Route::put('/{subscriber}', [MailSubscriberController::class, 'update'])->name('update');
        Route::delete('/{subscriber}', [MailSubscriberController::class, 'destroy'])->name('destroy');
        
        // Import/Export
        Route::post('/import', [MailSubscriberController::class, 'import'])->name('import');
        Route::get('/export', [MailSubscriberController::class, 'export'])->name('export');
        Route::get('/export/template', [MailSubscriberController::class, 'exportTemplate'])->name('export.template');
        
        // Actions groupées
        Route::post('/bulk-unsubscribe', [MailSubscriberController::class, 'bulkUnsubscribe'])->name('bulk-unsubscribe');
        Route::post('/bulk-resubscribe', [MailSubscriberController::class, 'bulkResubscribe'])->name('bulk-resubscribe');
        Route::post('/bulk-delete', [MailSubscriberController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/bulk-tag', [MailSubscriberController::class, 'bulkTag'])->name('bulk-tag');
        
        // Actions individuelles
        Route::post('/{subscriber}/unsubscribe', [MailSubscriberController::class, 'unsubscribe'])->name('unsubscribe');
        Route::post('/{subscriber}/resubscribe', [MailSubscriberController::class, 'resubscribe'])->name('resubscribe');
        Route::get('/{subscriber}/history', [MailSubscriberController::class, 'history'])->name('history');
        Route::get('/{subscriber}/activity', [MailSubscriberController::class, 'activity'])->name('activity');
        
        // Tags (si vous implémentez un système de tags)
        Route::post('/{subscriber}/tags', [MailSubscriberController::class, 'addTag'])->name('tags.add');
        Route::delete('/{subscriber}/tags/{tag}', [MailSubscriberController::class, 'removeTag'])->name('tags.remove');
    });
    
    
    /**
     * ===========================================================
     * TABLEAU DE BORD ET STATISTIQUES GLOBALES
     * ===========================================================
     */
    Route::prefix('mail-marketing')->name('mail-marketing.')->group(function () {
        
        // Dashboard principal
        Route::get('/dashboard', [MailDashboardController::class, 'index'])->name('dashboard');
        Route::get('/stats', [MailDashboardController::class, 'stats'])->name('stats');
        
        // Graphiques et rapports
        Route::get('/charts/opens', [MailDashboardController::class, 'opensChart'])->name('charts.opens');
        Route::get('/charts/clicks', [MailDashboardController::class, 'clicksChart'])->name('charts.clicks');
        Route::get('/charts/subscribers', [MailDashboardController::class, 'subscribersChart'])->name('charts.subscribers');
        
        // Export des rapports
        Route::get('/reports/daily', [MailDashboardController::class, 'dailyReport'])->name('reports.daily');
        Route::get('/reports/weekly', [MailDashboardController::class, 'weeklyReport'])->name('reports.weekly');
        Route::get('/reports/monthly', [MailDashboardController::class, 'monthlyReport'])->name('reports.monthly');
        
        // Configuration du module
        Route::get('/settings', [MailSettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [MailSettingsController::class, 'update'])->name('settings.update');
        Route::get('/settings/templates', [MailSettingsController::class, 'templates'])->name('settings.templates');
        Route::post('/settings/templates', [MailSettingsController::class, 'saveTemplate'])->name('settings.templates.save');
    });
});

/**
 * ===========================================================
 * ROUTES PUBLIQUES (TRACKING ET DÉSABONNEMENT)
 * ===========================================================
 * Ces routes ne nécessitent pas d'authentification
 * car elles sont appelées depuis les emails
 */
Route::prefix('mail-marketing')->name('mail-marketing.')->group(function () {
    
    // Tracking des ouvertures (pixel invisible)
    Route::get('/track/open/{campaign}/{subscriber}', [MailTrackingController::class, 'trackOpen'])
        ->name('track.open')
        ->where(['campaign' => '[0-9]+', 'subscriber' => '[0-9]+']);
    
    // Tracking des clics (redirection)
    Route::get('/track/click/{campaign}/{subscriber}', [MailTrackingController::class, 'trackClick'])
        ->name('track.click')
        ->where(['campaign' => '[0-9]+', 'subscriber' => '[0-9]+']);
    
    // Tracking des désabonnements (via lien dans l'email)
    Route::get('/unsubscribe/{email}', [MailTrackingController::class, 'unsubscribe'])
        ->name('unsubscribe')
        ->where(['email' => '.*']);
    
    // Confirmation de désabonnement (avec token)
    Route::get('/unsubscribe/confirm/{email}/{token}', [MailTrackingController::class, 'confirmUnsubscribe'])
        ->name('unsubscribe.confirm')
        ->where(['email' => '.*', 'token' => '.*']);
    
    // Réabonnement (optionnel)
    Route::post('/resubscribe', [MailTrackingController::class, 'resubscribe'])
        ->name('resubscribe');
    
    // Tracking des bounce (retours d'erreurs) - appelé par webhook SMTP
    Route::post('/webhook/bounce', [MailWebhookController::class, 'bounce'])
        ->name('webhook.bounce');
    
    // Tracking des plaintes (spam) - appelé par webhook
    Route::post('/webhook/complaint', [MailWebhookController::class, 'complaint'])
        ->name('webhook.complaint');
    
    // Webhook pour les fournisseurs de service (SendGrid, Mailgun, etc.)
    Route::post('/webhook/{provider}', [MailWebhookController::class, 'handle'])
        ->name('webhook.handle')
        ->where(['provider' => 'sendgrid|mailgun|ses|postmark']);
});

/**
 * ===========================================================
 * API ROUTES (si besoin de requêtes AJAX supplémentaires)
 * ===========================================================
 */
Route::prefix('api/mail-marketing')->middleware(['auth'])->name('api.mail-marketing.')->group(function () {
    
    // API pour les campagnes
    Route::get('/campaigns', [MailCampaignApiController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/{campaign}', [MailCampaignApiController::class, 'show'])->name('campaigns.show');
    Route::get('/campaigns/{campaign}/stats', [MailCampaignApiController::class, 'stats'])->name('campaigns.stats');
    
    // API pour les abonnés
    Route::get('/subscribers', [MailSubscriberApiController::class, 'index'])->name('subscribers.index');
    Route::get('/subscribers/search', [MailSubscriberApiController::class, 'search'])->name('subscribers.search');
    Route::get('/subscribers/{subscriber}', [MailSubscriberApiController::class, 'show'])->name('subscribers.show');
    
    // API pour les statistiques
    Route::get('/stats/overview', [MailStatsApiController::class, 'overview'])->name('stats.overview');
    Route::get('/stats/timeline', [MailStatsApiController::class, 'timeline'])->name('stats.timeline');
});

/**
 * ===========================================================
 * COMMANDES DE TEST (à retirer en production)
 * ===========================================================
 */
if (app()->environment('local', 'testing')) {
    Route::middleware(['auth'])->prefix('test')->name('test.')->group(function () {
        Route::get('/mail/send-test', [TestMailController::class, 'sendTest'])->name('send-test');
        Route::get('/mail/preview-campaign/{campaign}', [TestMailController::class, 'preview'])->name('preview-campaign');
    });
}