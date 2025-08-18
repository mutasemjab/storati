<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\ClassTeacher;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Admin\FCMController; // <-- Import the FCMController here
use App\Traits\Responses;
use Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    use Responses;

    public function active()
    {
        $user = auth()->user();
        if ($user->activate == 2) {
            return $this->error_response('Your account has been InActive', null);
        }

        return $this->success_response('User retrieved successfully', $user);
    }

    public function deleteAccount(Request $request)
    {
        try {
            // Check both authentication guards
            $userApi = auth('user-api')->user();

            if ($userApi) {
                // Regular user account deactivation
                $userApi->update(['activate' => 2]);

                // Revoke all tokens for the user
                $userApi->tokens()->delete();

                return $this->success_response('User account deleted successfully', null);
            } else {
                return $this->error_response('Unauthenticated', [], 401);
            }
        } catch (\Exception $e) {
            \Log::error('Account deletion error: ' . $e->getMessage());
            return $this->error_response('Failed to delete account', ['error' => $e->getMessage()]);
        }
    }




    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error_response('Validation error', $validator->errors());
        }

        $credentials = $request->only('phone', 'password');

        $user = \App\Models\User::where('phone', $credentials['phone'])->first();


        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return $this->error_response('Invalid phone or password', []);
        }

        // Optional: Update FCM token if provided
        if ($request->filled('fcm_token')) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }

        $accessToken = $user->createToken('authToken')->accessToken;

        return $this->success_response('Login successful', [
            'token' => $accessToken,
            'user' => $user,
        ]);
    }


    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users',
            'password' => 'required',
            'email' => 'nullable|email|unique:users',
            'fcm_token' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        if ($validator->fails()) {
            return $this->error_response('Validation error', $validator->errors());
        }

        // Prepare data for user creation
        $userData = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'fcm_token' => $request->fcm_token,
            'balance' => 0,  // Default balance
        ];

        $userData['password'] = Hash::make($request->password);

        // Add photo if uploaded
        if ($request->hasFile('photo')) {
            $userData['photo'] = uploadImage('assets/admin/uploads', $request->file('photo'));
        }

        $userData['referral_code'] = $this->generateReferralCode();
        $user = User::create($userData);


        // Generate access token
        $accessToken = $user->createToken('authToken')->accessToken;

        return $this->success_response('Registration successful', [
            'token' => $accessToken,
            'user' => $user,
        ]);
    }


    public function googleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'google_id' => 'required|string',
            'access_token' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'photo' => 'nullable|string', // URL from Google
            'fcm_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error_response('Validation error', $validator->errors());
        }

        // Check if user already exists with this Google ID
        $user = User::where('google_id', $request->google_id)->first();

        if ($user) {
            // User exists, update access token and FCM token if provided
            $user->access_token = $request->access_token;

            if ($request->filled('fcm_token')) {
                $user->fcm_token = $request->fcm_token;
            }

            $user->save();
        } else {
            // Check if user exists with same email
            $existingUser = User::where('email', $request->email)->first();

            if ($existingUser) {
                // Link Google account to existing user
                $existingUser->google_id = $request->google_id;
                $existingUser->access_token = $request->access_token;
                $existingUser->type = 1; // Google login

                if ($request->filled('fcm_token')) {
                    $existingUser->fcm_token = $request->fcm_token;
                }

                $existingUser->save();
                $user = $existingUser;
            } else {
                // Create new user
                $userData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'google_id' => $request->google_id,
                    'access_token' => $request->access_token,
                    'fcm_token' => $request->fcm_token,
                    'balance' => 0,
                    'referral_code' => $this->generateReferralCode(),
                    'type' => 1, // Google login
                    'activate' => 1, // Active by default for social login
                ];

                // Handle photo from Google
                if ($request->filled('photo')) {
                    // You might want to download and store the image locally
                    // For now, we'll store the URL
                    $userData['photo'] = $request->photo;
                }

                $user = User::create($userData);
            }
        }

        // Generate access token
        $accessToken = $user->createToken('authToken')->accessToken;

        return $this->success_response('Google login successful', [
            'token' => $accessToken,
            'user' => $user,
        ]);
    }

    public function appleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'apple_id' => 'required|string', // This would be the Apple user identifier
            'access_token' => 'required|string', // Apple identity token
            'name' => 'nullable|string|max:255', // Apple might not always provide name
            'email' => 'nullable|email', // Apple might provide private relay email
            'fcm_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error_response('Validation error', $validator->errors());
        }

        $user = User::where('apple_id', $request->apple_id)->where('type', 2)->first();

        if ($user) {
            // User exists, update access token and FCM token if provided
            $user->access_token = $request->access_token;

            if ($request->filled('fcm_token')) {
                $user->fcm_token = $request->fcm_token;
            }

            $user->save();
        } else {
            // Check if user exists with same email (if email is provided)
            $existingUser = null;
            if ($request->filled('email')) {
                $existingUser = User::where('email', $request->email)->first();
            }

            if ($existingUser) {
                // Link Apple account to existing user
                $existingUser->apple_id = $request->apple_id;
                $existingUser->access_token = $request->access_token;
                $existingUser->type = 2; // Apple login

                if ($request->filled('fcm_token')) {
                    $existingUser->fcm_token = $request->fcm_token;
                }

                $existingUser->save();
                $user = $existingUser;
            } else {
                // Create new user
                $userData = [
                    'name' => $request->name ?? 'Apple User', // Default name if not provided
                    'email' => $request->email, // Can be null
                    'apple_id' => $request->apple_id, // Using google_id field for Apple ID
                    'access_token' => $request->access_token,
                    'fcm_token' => $request->fcm_token,
                    'balance' => 0,
                    'referral_code' => $this->generateReferralCode(),
                    'type' => 2, // Apple login
                    'activate' => 1, // Active by default for social login
                ];

                $user = User::create($userData);
            }
        }

        // Generate access token
        $accessToken = $user->createToken('authToken')->accessToken;

        return $this->success_response('Apple login successful', [
            'token' => $accessToken,
            'user' => $user,
        ]);
    }

    public function userProfile()
    {
        try {
            // Check both authentication guards
            $userApi = auth('user-api')->user();

            if ($userApi) {
                // If it's a regular user
                return $this->success_response('User profile retrieved', $userApi);
            } else {
                return $this->error_response('Unauthenticated', [], 401);
            }
        } catch (\Throwable $th) {
            \Log::error('Profile retrieval error: ' . $th->getMessage());
            return $this->error_response('Failed to retrieve profile', []);
        }
    }



    public function updateProfile(Request $request)
    {
        try {
            // Check both authentication guards
            $userApi = auth('user-api')->user();

            // Determine which type of user is authenticated
            if ($userApi) {
                $user = $userApi;
                $userType = 'user';
                $table = 'users';
            } else {
                return $this->error_response('Unauthenticated', [], 401);
            }

            // Base validation rules for both user types
            $validationRules = [
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:' . $table . ',email,' . $user->id,
                'phone' => 'nullable|string',
                'fcm_token' => 'nullable|string',
                'password' => 'nullable|string',
                'photo' => 'nullable|image|max:2048',
            ];

            // Validate input data
            $validator = Validator::make($request->all(), $validationRules);

            if ($validator->fails()) {
                return $this->error_response('Validation error', $validator->errors());
            }

            // Get basic fields for both user types
            $data = $request->only(['name', 'email', 'phone', 'password', 'fcm_token']);

            // Handle basic profile photo upload (for both user types)
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($user->photo && file_exists('assets/admin/uploads/' . $user->photo)) {
                    unlink('assets/admin/uploads/' . $user->photo);
                }
                $data['photo'] = uploadImage('assets/admin/uploads', $request->file('photo'));
            }


            // Update user data
            $user->update($data);

            return $this->success_response(ucfirst($userType) . ' profile updated successfully', $user);
        } catch (\Throwable $th) {
            \Log::error('Profile update error: ' . $th->getMessage());
            return $this->error_response('Failed to update profile', ['message' => $th->getMessage()]);
        }
    }

    public function notifications()
    {
        $user = auth()->user();

        $notifications = Notification::query()
            ->where(function ($query) use ($user) {
                $query->where('type', 0)
                    ->orWhere('type', 1)
                    ->orWhere('user_id', $user->id);
            })
            ->orderBy('id', 'DESC')
            ->get();

        return $this->success_response('Notifications retrieved successfully', $notifications);
    }



    private function generateReferralCode()
    {
        do {
            $referralCode = strtoupper(substr(md5(time() . rand(1000, 9999)), 0, 8));
        } while (User::where('referral_code', $referralCode)->exists());

        return $referralCode;
    }
}
