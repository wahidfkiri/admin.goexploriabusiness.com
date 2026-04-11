<?php

namespace Vendor\Chatbot\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChatWidgetController extends Controller
{
    /**
     * Retourne le script JS du widget à embarquer sur le site client.
     * <script src="/chatbot/{etablissementId}/widget.js"></script>
     */
    public function widgetJs(Request $request, int $etablissementId): Response
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $config        = $etablissement->getChatWidgetConfig();

        $js = view('chatbot::widget.widget-script', [
            'etablissement' => $etablissement,
            'config'        => $config,
            'apiBase'       => url("/api/chatbot/{$etablissementId}"),
        ])->render();

        return response($js, 200, [
            'Content-Type'  => 'application/javascript; charset=utf-8',
            'Cache-Control' => 'public, max-age=300',
        ]);
    }

    /**
     * Retourne le CSS du widget.
     */
    public function widgetCss(Request $request, int $etablissementId): Response
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $primaryColor  = $etablissement->getChatSetting('primary_color', config('chatbot.widget.primary_color'));

        $css = view('chatbot::widget.widget-style', compact('primaryColor'))->render();

        return response($css, 200, [
            'Content-Type'  => 'text/css; charset=utf-8',
            'Cache-Control' => 'public, max-age=300',
        ]);
    }

    /**
     * Iframe du widget (optionnel, pour l'intégration en iframe).
     */
    public function iframe(Request $request, int $etablissementId): \Illuminate\View\View
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $config        = $etablissement->getChatWidgetConfig();

        return view('chatbot::widget.iframe', compact('etablissement', 'config'));
    }
}