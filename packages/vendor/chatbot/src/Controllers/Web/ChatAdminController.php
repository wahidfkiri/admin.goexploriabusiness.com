<?php

namespace Vendor\Chatbot\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use Vendor\Chatbot\Models\ChatRoom;
use Vendor\Chatbot\Models\ChatAgent;
use Vendor\Chatbot\Models\ChatMessage;
use Vendor\Chatbot\Models\ChatQuickReply;
use Vendor\Chatbot\Models\ChatBotFlow;
use Vendor\Chatbot\Services\ChatService;
use Vendor\Chatbot\Requests\BotFlowRequest;
use Illuminate\Http\Request;

class ChatAdminController extends Controller
{
    public function __construct(protected ChatService $chatService) {}

    /** Dashboard principal */
    public function dashboard(Request $request, int $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);

        $stats = [
            'pending' => ChatRoom::forEtablissement($etablissementId)->pending()->count(),
            'open'    => ChatRoom::forEtablissement($etablissementId)->open()->count(),
            'closed_today' => ChatRoom::forEtablissement($etablissementId)->closed()
                ->whereDate('closed_at', today())->count(),
            'agents_online' => ChatAgent::where('etablissement_id', $etablissementId)
                ->where('status', 'online')->count(),
        ];

        $pendingRooms = ChatRoom::forEtablissement($etablissementId)
            ->pending()
            ->with(['session', 'lastMessage'])
            ->orderBy('created_at')
            ->get();

        $openRooms = ChatRoom::forEtablissement($etablissementId)
            ->open()
            ->with(['agent', 'session', 'lastMessage'])
            ->orderByDesc('updated_at')
            ->get();

        return view('chatbot::admin.dashboard', compact(
            'etablissement', 'stats', 'pendingRooms', 'openRooms'
        ));
    }

    /** Détail d'une room + interface de réponse */
    public function roomDetail(Request $request, int $etablissementId, int $roomId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $room = ChatRoom::where('id', $roomId)
            ->where('etablissement_id', $etablissementId)
            ->with(['agent', 'session', 'messages.files', 'messages.agent', 'rating'])
            ->firstOrFail();

        $quickReplies = ChatQuickReply::where('etablissement_id', $etablissementId)
            ->active()->ordered()->get();

        $agent = ChatAgent::where('user_id', auth()->id())
            ->where('etablissement_id', $etablissementId)
            ->first();

        return view('chatbot::admin.room-detail', compact(
            'etablissement', 'room', 'quickReplies', 'agent'
        ));
    }

    /** Historique des rooms fermées */
    public function history(Request $request, int $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);

        $rooms = ChatRoom::forEtablissement($etablissementId)
            ->closed()
            ->with(['agent', 'session', 'rating', 'lastMessage'])
            ->orderByDesc('closed_at')
            ->paginate(25);

        return view('chatbot::admin.history', compact('etablissement', 'rooms'));
    }

    /** Page de configuration */
    public function settings(Request $request, int $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $quickReplies  = ChatQuickReply::where('etablissement_id', $etablissementId)->orderBy('order')->get();
        $botFlows      = ChatBotFlow::where('etablissement_id', $etablissementId)->orderBy('order')->get();
        $agents        = ChatAgent::where('etablissement_id', $etablissementId)->with('user')->get();

        return view('chatbot::admin.settings', compact(
            'etablissement', 'quickReplies', 'botFlows', 'agents'
        ));
    }

    /** Statistiques */
    public function reports(Request $request, int $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $period = $request->input('period', 30);

        $from = now()->subDays($period);

        $stats = [
            'total_rooms'       => ChatRoom::forEtablissement($etablissementId)->where('created_at', '>=', $from)->count(),
            'total_messages'    => ChatMessage::whereHas('room', fn($q) => $q->where('etablissement_id', $etablissementId))
                                    ->where('created_at', '>=', $from)->count(),
            'avg_rating'        => \DB::connection('chatbot')
                                    ->table('chat_ratings')
                                    ->join('chat_rooms', 'chat_ratings.room_id', '=', 'chat_rooms.id')
                                    ->where('chat_rooms.etablissement_id', $etablissementId)
                                    ->where('chat_ratings.created_at', '>=', $from)
                                    ->avg('chat_ratings.score'),
            'rooms_per_day'     => ChatRoom::forEtablissement($etablissementId)
                                    ->where('created_at', '>=', $from)
                                    ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                    ->groupBy('date')
                                    ->orderBy('date')
                                    ->get(),
        ];

        return view('chatbot::admin.reports', compact('etablissement', 'stats', 'period'));
    }

    /** CRUD Bot Flows */
    public function storeFlow(BotFlowRequest $request, int $etablissementId)
    {
        ChatBotFlow::create(array_merge($request->validated(), ['etablissement_id' => $etablissementId]));
        return back()->with('success', 'Flow créé avec succès.');
    }

    public function updateFlow(BotFlowRequest $request, int $etablissementId, int $flowId)
    {
        ChatBotFlow::where('id', $flowId)->where('etablissement_id', $etablissementId)
            ->firstOrFail()->update($request->validated());
        return back()->with('success', 'Flow mis à jour.');
    }

    public function destroyFlow(int $etablissementId, int $flowId)
    {
        ChatBotFlow::where('id', $flowId)->where('etablissement_id', $etablissementId)
            ->firstOrFail()->delete();
        return back()->with('success', 'Flow supprimé.');
    }
}