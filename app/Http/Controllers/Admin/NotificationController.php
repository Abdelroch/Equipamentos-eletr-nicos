<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    /**
     * Lista as notificações do admin autenticado.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = $request->boolean('only_unread')
            ? $user->unreadNotifications()
            : $user->notifications();

        $perPage = (int) $request->input('per_page', 10);

        $notifications = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'unread_count' => $user->unreadNotifications()->count(),
            'data' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
                'total'        => $notifications->total(),
            ],
        ]);
    }

    /**
     * Retorna apenas o contador de notificações não lidas.
     */
    public function unread_count(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'unread_count' => $request->user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * Marca uma notificação específica como lida.
     */
    public function mark_read(Request $request, string $id): JsonResponse|RedirectResponse
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if (!$notification) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notificação não encontrada.',
                ], 404);
            }

            return redirect()->back()->with('error', 'Notificação não encontrada.');
        }

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notificação marcada como lida.',
                'unread_count' => $request->user()->unreadNotifications()->count(),
            ]);
        }

        return redirect()->back();
    }

    /**
     * Marca todas as notificações não lidas do usuário autenticado como lidas.
     *
     * Suporta dois modos de chamada:
     * - via fetch/AJAX (Accept: application/json) → devolve JSON, usado pelo
     *   dropdown do header para atualizar o badge sem recarregar a página.
     * - via submit normal de <form> → devolve um redirect de volta à página
     *   atual, evitando que o navegador exiba o JSON cru.
     */
    public function mark_all_read(Request $request): JsonResponse|RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notificações marcadas como lidas.',
                'unread_count' => 0,
            ]);
        }

        return redirect()->back();
    }

    /**
     * Apaga uma notificação específica do usuário autenticado.
     */
    public function destroy(Request $request, string $id): JsonResponse|RedirectResponse
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if (!$notification) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notificação não encontrada.',
                ], 404);
            }

            return redirect()->back()->with('error', 'Notificação não encontrada.');
        }

        $notification->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notificação apagada.',
                'unread_count' => $request->user()->unreadNotifications()->count(),
            ]);
        }

        return redirect()->back();
    }

    /**
     * Apaga todas as notificações (lidas e não lidas) do usuário autenticado.
     */
    public function destroy_all(Request $request): JsonResponse|RedirectResponse
    {
        $request->user()->notifications()->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Todas as notificações foram apagadas.',
                'unread_count' => 0,
            ]);
        }

        return redirect()->back();
    }
}
