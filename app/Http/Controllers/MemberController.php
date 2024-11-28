<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewUsers;

class MemberController extends Controller
{
    public function index()
    {
        // Get the search query string
        $search = request()->query();

        if (!empty($search)) {
            $where = [];
            if (!empty($search['email'])) {
                $where[] = ['email', 'like', '%' . $search['email'] . '%'];
            }
            if (isset($search['status'])) {
                $where[] = ['status', '=', $search['status']];
            }

            $members = NewUsers::where($where)->where('delete_flg', 0)->paginate(20);
        } else {
            $members = NewUsers::where('delete_flg', 0)->paginate(20);
        }

        $data = [
            'members' => $members
        ];
        return view('members.index', $data);
    }

    public function destroy(Request $request, $id, $type)
    {
        if ($request->ajax()) {
            $message = '';
            $errorMessage = '';

            if ($type == 'block') {
                $updated = NewUsers::where('id', $id)->update(['status' => 0]);
                $message = 'Member successfully blocked.';
                $errorMessage = 'An error occurred while blocking member. Please try again.';
            } else if ($type == 'unblock') {
                $updated = NewUsers::where('id', $id)->update(['status' => 1]);
                $message = 'Member successfully unblocked.';
                $errorMessage = 'An error occurred while unblocking member. Please try again.';
            } else {
                $updated = NewUsers::where('id', $id)->update(['delete_flg' => 1]);
                $message = 'Member successfully marked as deleted.';
                $errorMessage = 'An error occurred while deleting member. Please try again.';
            }
           
            if ($updated) {
                return response()->json([
                    'status' => 'success',
                    'message' => $message,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $errorMessage,
                ], 200);
            }
        }

        // Redirect back to the users route with a success message
        return redirect()->route('users')->with('success', '');
    }
}
