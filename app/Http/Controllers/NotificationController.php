<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function mark_all_read()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back();
    }
}
