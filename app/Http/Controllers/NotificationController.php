<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /** List notifications for the authenticated user */
    public function index(Request $request)
    {
        $query = Notification::where('user_id', $request->user()->id)
            ->orderByDesc('created_at');

        if ($request->has('unread') && $request->boolean('unread')) {
            $query->whereNull('read_at');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $perPage = $request->input('per_page', 50);
        $notifications = $query->paginate($perPage);

        return response()->json([
            'data'        => $notifications->items(),
            'total'       => $notifications->total(),
            'unread_count' => Notification::where('user_id', $request->user()->id)
                                ->whereNull('read_at')
                                ->count(),
        ]);
    }

    /** Mark a single notification as read */
    public function markRead(Request $request, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();
        return response()->json($notification);
    }

    /** Mark all notifications as read */
    public function markAllRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read.']);
    }

    /** Delete a notification */
    public function destroy(Request $request, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->delete();
        return response()->json(['message' => 'Notification deleted.']);
    }

    /** Delete all notifications for the user */
    public function clearAll(Request $request)
    {
        Notification::where('user_id', $request->user()->id)->delete();
        return response()->json(['message' => 'All notifications deleted.']);
    }
}
