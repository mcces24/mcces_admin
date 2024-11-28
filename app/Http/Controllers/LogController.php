<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginLog;

class LogController extends Controller
{
    public function index()
    {  
        // Get the search query string
        $search = request()->query();

        if (!empty($search)) {
            $where = [];
            if (!empty($search['attemp'])) {
                $where[] = ['attemp', 'like', '%' . $search['attemp'] . '%'];
            }
            if (isset($search['type'])) {
                $where[] = ['type', '=', $search['type']];
            }
            if (isset($search['portal'])) {
                $where[] = ['portal', '=', $search['portal']];
            }
            $logs = LoginLog::orderBy('id', 'DESC')->where($where)->paginate(20);
        } else {
            //select all user data model User where delete_flg is 0
            $logs = LoginLog::orderBy('id', 'DESC')->paginate(20);

        }

        $data = [
            'logs' => $logs
        ];
        // Passing data to the view as an array
        return view('logs.index', $data);
    }

    //view
    public function view(Request $request, $id)
    {
        
        $log = LoginLog::find($id);

        if ($log->viewed_flg == 0) {
            $log->viewed_flg = 1;
            $log->save();
        }
        $data = [
            'log' => $log
        ];
        return view('logs.view', $data);
    }

    public function mark_all_read()
    {
        LoginLog::where('viewed_flg', 0)->update(['viewed_flg' => 1]);

        return response()->json([
            'status' => 'success'
        ], 200);
    }

    public function mark_read(Request $request, $id)
    {
        $log = LoginLog::find($id);
        $log->viewed_flg = 1;
        $log->save();

        return response()->json([
            'status' => 'success'
        ], 200);
    }
}
