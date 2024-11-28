<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {  
        // Get the search query string
        $search = request()->query();

        if (!empty($search)) {
            $where = [];
            if (!empty($search['name'])) {
                $where[] = ['name', 'like', '%' . $search['name'] . '%'];
            }
            if (!empty($search['username'])) {
                $where[] = ['username', 'like', '%' . $search['username'] . '%'];
            }
            if (!empty($search['contact'])) {
                $where[] = ['contact', 'like', '%' . $search['contact'] . '%'];
            }
            if (!empty($search['role'])) {
                $where[] = ['role', '=', $search['role']];
            }
            if (isset($search['status'])) {
                $where[] = ['status', '=', $search['status']];
            }
            if (!empty($search['department'])) {
                $where[] = ['department', 'like', '%' . $search['department'] . '%'];
            }

            $users = User::where($where)->where('delete_flg', 0)->paginate(20);
        } else {
            //select all user data model User where delete_flg is 0
            $users = User::where('delete_flg', 0)->paginate(20);

        }

        $data = [
            'users' => $users
        ];
        // Passing data to the view as an array
        return view('users.index', $data);
    }

    public function add(Request $request)
    {
        if ($request->ajax()) {
            $user = new User();
            $validator = Validator::make($request->all(), [
                'profile' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'name' => 'required|string|max:70',
                'address' => 'required|string|max:100',
                'contact' => ['required', 'regex:/^\d{10}$/'],
                'role' => 'required|string|max:100',
                'department' => 'required|string|max:50',
                'status' => 'required|string|max:10',
                'username' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:8',
            ]);

            if (!$validator->passes()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(), 
                    'message' => 'The provided data is invalid.'
                ], 200);
            }
            if ($request->hasFile('profile')) {
                if ($user->profile && file_exists(public_path($user->profile))) {
                    unlink(public_path($user->profile));
                }
    
                $file = $request->file('profile');
                
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $filePath = public_path('assets/profile');
        
                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777, true);
                }
        
                $file->move($filePath, $fileName);
        
                $user->profile = 'assets/profile/' . $fileName;
            }

            $user->name = $request->name;
            $user->address = $request->address;
            $user->contact = $request->contact;
            $user->role = $request->role;
            $user->department = $request->department;
            $user->status = $request->status;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);

            if ($user->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User added successfully!',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while adding user. Please try again.',
                ], 200);
            }
        }
        return view('users.add');
    }

    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'name' => 'required|string|max:70',
                'address' => 'required|string|max:100',
                'contact' => ['required', 'regex:/^\d{10}$/'],
                'role' => 'required|string|max:100',
                'department' => 'required|string|max:50',
                'status' => 'required|string|max:10',
                'username' => [
                    'required',
                    'string',
                    'email',
                    'max:100',
                    Rule::unique('users')->ignore($user->id), // Ignore the current user's record
                ],
                'password' => 'nullable|string|min:8',
            ]);            

            if (!$validator->passes()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(), 
                    'message' => 'The provided data is invalid.'
                ], 200);
            }

            if ($request->hasFile('profile') && !empty($request->file('profile'))) {
                if ($user->profile && file_exists(public_path($user->profile))) {
                    unlink(public_path($user->profile));
                }
    
                $file = $request->file('profile');
                
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $filePath = public_path('assets/profile');
        
                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777, true);
                }
        
                $file->move($filePath, $fileName);
        
                $user->profile = 'assets/profile/' . $fileName;
            }

            $user->name = $request->name;
            $user->address = $request->address;
            $user->contact = $request->contact;
            $user->role = $request->role;
            $user->department = $request->department;
            $user->status = $request->status;
            $user->username = $request->username;
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            //update user data
            if ($user->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully!',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating user. Please try again.',
                ], 200);
            }
        }

        return view('users.edit', ['user' => $user]);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            // Find the user by ID
            $user = User::findOrFail($id);

            // Update the delete_flg field to 1
            $user->delete_flg = 1;
          
            if ($user->save()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User successfully marked as deleted.',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred while deleting user. Please try again.',
                ], 200);
            }
        }

        // Redirect back to the users route with a success message
        return redirect()->route('users')->with('success', 'User successfully marked as deleted.');
    }

}
