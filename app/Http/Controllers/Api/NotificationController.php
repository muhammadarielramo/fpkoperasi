<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class NotificationController extends Controller
{
    public function getByUser() {
        $id = auth()->user()->id;
        $notifications = Notification::where('id_user', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        if(isNull($notifications)) {
            return response()->json([
                'message' => 'Tidak ada notifikasi'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Notifikasi ditemukan',
                'data' => $notifications
            ], 200);
        }
    }

    public function read($id) {
        $notification = Notification::findOrFail($id);
        $notification->read_at = now();
        $notification->save();

        return response()->json([
            'message' => 'Notifikasi berhasil dibaca'
        ], 200);
    }
}
