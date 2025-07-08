<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mostrar todas las notificaciones del usuario
     */
    public function index()
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marcar notificación como leída
     */
    public function markAsRead($id)
    {
        $user = Auth::user();

        $notification = $user->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Obtener notificaciones no leídas para el dropdown
     */
    public function getUnread()
    {
        $user = Auth::user();

        $notifications = $user->unreadNotifications()
            ->take(5)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'count' => $user->unreadNotifications()->count()
        ]);
    }

    /**
     * Eliminar notificación
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $notification = $user->notifications()->find($id);

        if ($notification) {
            $notification->delete();
        }

        return response()->json(['success' => true]);
    }
}
