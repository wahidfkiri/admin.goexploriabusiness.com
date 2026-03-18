<?php

namespace Vendor\MailMarketing\Controllers;

use App\Http\Controllers\Controller;
use App\Services\MailMarketingService;
use Illuminate\Http\Request;

class MailTrackingController extends Controller
{
    protected $mailService;

    public function __construct(MailMarketingService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function trackOpen(Request $request, int $campaign, int $subscriber, ?string $token = null)
    {
        $this->mailService->trackOpen($campaign, $subscriber);
        
        // Retourne un pixel transparent
        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        
        return response($pixel, 200)
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function trackClick(Request $request, int $campaign, int $subscriber)
    {
        $url = $request->query('url');
        
        if (!$url) {
            abort(400, 'URL manquante');
        }

        $this->mailService->trackClick($campaign, $subscriber, $url);

        return redirect()->away(urldecode($url));
    }

    public function unsubscribe(Request $request, string $email)
    {
        $token = $request->query('token');
        
        $unsubscribed = $this->mailService->unsubscribe($email, $token);

        if (!$unsubscribed) {
            abort(404, 'Abonné non trouvé ou token invalide');
        }

        return view('mail-marketing::unsubscribe', ['email' => $email]);
    }
}