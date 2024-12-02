<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Admin; // Import the Admin model if necessary
use Illuminate\Support\Facades\View; // Import the View facade
use App\Models\LoginLog;
use App\Models\User;

abstract class Controller
{
    protected $name;
    protected $profile_pic;
    protected $alerts;
    protected $messages;
    protected $user;
    protected $id;

    // Constructor
    public function __construct()
    {
        $user = auth()->user();
        $alerts = array();
        $notifications = $this->getUserNotifications();

        if (!empty($notifications['alerts'])) {
            $alerts = $notifications['alerts'];
        }

        if (!empty($notifications['messages'])) {
            $messages = $notifications['messages'];
        }

        if ($user) {
            $this->name = $user->name;
            $this->profile_pic = $user->profile_pic; 
            $this->alerts = $alerts;
            $this->messages = $messages;
            $this->user = $user;
            $this->id = hash('sha256', $user->id);
        } else {
            $this->name = null;
            $this->profile_pic = null;
            $this->alerts = null;
            $this->messages = null;
            $this->user = null;
            $this->id = null;
        }

        if ($user && $user->face_recognition_flg == 1) {
            //check if cookie exists
            if (!isset($_COOKIE['face_recognition'])) {
                //set cookie
                setcookie('face_recognition', 'true', time() + (86400 * 30), "/");
            }   
        } else {
            //delete
            setcookie('face_recognition', '', time() - 3600, "/");
            setcookie('recognition_success', '', time() - 3600, "/");
            setcookie('__PHP_FACE_ID__', '', time() - 3600, "/");
        }

        // Share the data with all views
        View::share('name', $this->name);
        View::share('user', $this->user);
        View::share('alerts', $this->alerts);
        View::share('messages', $this->messages);
        View::share('profile_pic', $this->profile_pic);
        View::share('id', $this->id);
    }

    // Method to get user notifications (for example purposes)
    protected function getUserNotifications()
    {
        $alerts = LoginLog::selectRaw('*, (SELECT COUNT(*) FROM login_logs WHERE viewed_flg = 0) as alert_count')
            ->where('viewed_flg', 0)
            ->orderBy('id', 'DESC')
            ->get();
        
        $messages = User::selectRaw('*, (SELECT COUNT(*) FROM users WHERE code IS NOT NULL) as message_count')
            ->whereNotNull('code')  // Check that 'code' is not NULL
            ->orderBy('id', 'DESC')
            ->get();

        return [
            'alerts' => $alerts,
            'messages' => $messages
        ];
        
        // If you have a Notification model, you could use:
        // return Notification::where('user_id', $userId)->get();
    }
}
