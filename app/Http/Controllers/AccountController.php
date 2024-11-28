<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        //login users data
        $user = Auth::user();

        return view('account.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        if (isset($request->type) && $request->type == "changePass") {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|current_password',
                'new_password' => 'required|string|min:8|confirmed',
                'new_password_confirmation' => 'required|string|min:8',
            ]);

            if (!$validator->passes()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(), 
                    'message' => 'The provided data is invalid.'
                ], 200);
            }

            //new pass check
            if (strcmp($request->current_password, $request->new_password) == 0) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'new_password' => 'New Password cannot be same as your current password. Please choose a different password.',
                    ],
                    'message' => 'New Password cannot be same as your current password. Please choose a different password.',
                ], 200);
            }

            $user->password = Hash::make($request->new_password);

            if ($user->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password changed successfully!',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while changing password. Please try again.',
                ], 200);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'about' => 'required|string|max:500',
                'position' => 'required|string|max:100',
                'address' => 'required|string|max:255',
                'contact' => ['required', 'regex:/^\d{10}$/'],
                'email' => 'required|email|max:255',
            ]);
            
            if (!$validator->passes()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(), 
                    'message' => 'The provided data is invalid.'
                ], 200);
            }
    
            if ($request->hasFile('profile_pic')) {
                if ($user->profile_pic && file_exists(public_path($user->profile_pic))) {
                    unlink(public_path($user->profile_pic));
                }
    
                $file = $request->file('profile_pic');
                
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $filePath = public_path('assets/profile');
        
                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777, true);
                }
        
                $file->move($filePath, $fileName);
        
                $user->profile_pic = 'assets/profile/' . $fileName;
            }
    
            $user->name = $request->name;
            $user->about = $request->about;
            $user->position = $request->position;
            $user->address = $request->address;
            $user->contact = $request->contact;
            $user->email = $request->email;

            if (isset($request->face_recognition_flg) && $request->face_recognition_flg == 'on') {
                $user->face_recognition_flg = 1;
            } else {
                $user->face_recognition_flg = 0;
            }
            
            if ($request->ajax()) {
                if ($user->save()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Profile updated successfully!',
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'An error occurred while updating profile. Please try again.',
                    ]);
                }
            }
        }
        
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    public function uploadFaceImage(Request $request)
    {
        $user = auth()->user();

        // Ensure the `face_recognition_image` field is present
        $validator = Validator::make($request->all(), [
            'face_recognition_image' => 'required|string', // Validate Base64 string
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'The provided data is invalid.',
            ], 422);
        }

        // Decode the Base64 image
        try {
            $imageData = base64_decode($request->face_recognition_image);

            // Generate a unique filename
            $fileName = time() . '.png'; // Save as PNG

            // Set the upload path
            $filePath = public_path('assets/face_recognition_images');

            // Ensure the directory exists
            if (!file_exists($filePath)) {
                mkdir($filePath, 0777, true);
            }

            // Save the file
            $fileFullPath = $filePath . '/' . $fileName;
            file_put_contents($fileFullPath, $imageData);

            // Delete the old image if it exists
            if ($user->face_recognition_image && file_exists(public_path($user->face_recognition_image))) {
                unlink(public_path($user->face_recognition_image));
            }

            // Update the user's profile
            $user->face_recognition_image = 'assets/face_recognition_images/' . $fileName;

            if ($user->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Face image uploaded successfully!',
                    'imagePath' => $user->face_recognition_image,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update the user profile.',
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process the image. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
