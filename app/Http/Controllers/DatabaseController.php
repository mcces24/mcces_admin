<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backup;

class DatabaseController extends Controller
{
    public function index()
    {
        $backups = Backup::orderBy('created_at', 'desc')->paginate(20);
        $data = [
            'backups' => $backups
        ];
        
        return view('database.index', $data);
    }
}
