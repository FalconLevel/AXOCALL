<?php

namespace App\Http\Controllers\Api;

use App\AxocallEnum;
use App\Http\Controllers\Controller;
use App\Mail\AxoMailer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AccountController extends Controller
{
    public function register(Request $request): JsonResponse {

        try {
            $validated = validatorHelper()->validate('account_register', $request);

            if (!$validated['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $validated['response'],
                ], 400);
            }

            $user = User::create($validated['validated']);
            
            $send_otp = globalHelper()->sendOtp($user);

            return response()->json([
                'status' => true,
                'token_type' => 'Bearer',
                'message' => 'Account registered successfully. Please check your email for OTP.',
                'otp_details' => $send_otp,
            ], 200);    
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to register account',
            ], 500);
        }   
    }

    public function login(Request $request): JsonResponse {
        try {
            $validated = validatorHelper()->validate('account_login', $request); 

            if (!$validated['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $validated['response'],
                ], 400);
            }

            if (!Auth::attempt($validated['validated'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid email or password',
                ], 400);
            }

            $user = User::where('email', $validated['validated']['email'])->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid email or password',
                ], 400);
            }

            if ($user->email_verified_at == null) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please verify your email',
                ], 400);
            }
            
            $token = $user->createToken('authToken')->plainTextToken;
            
            return response()->json([
                'status' => true,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'message' => 'Login successful',
            ], 200);
            
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to login',
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse {
        try {
            Auth::user()->tokens->each(function ($token, $key) {
                $token->delete();
            });
            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully',
            ], 200);
        } catch (\Exception $e) {
            logInfo($e->getMessage());  
            return response()->json([
                'status' => false,
                'message' => 'Failed to logout',
            ], 500);
        }
    }

    public function sendOtp(Request $request) {
        try {
            $recipient = $request->input('recipient');
            $profile = globalHelper()->getProfileViaEmail($recipient);
            $emailDetails = globalHelper()->getEmailDetails('otp', $profile);
            // dd($emailDetails);
            Mail::to("$recipient")->send(new AxoMailer($emailDetails));
            return "Email sent!";
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getUsers() {
        try {
            $users = User::with('role')->get();
            
            return response()->json([
                'status' => true,
                'message' => 'Users fetched successfully',
                'data' => $users,
            ], 200);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),  
            ], 500);
        }
    }

    public function resetPassword(Request $request) {
        try {
            $user = User::find($request->id);
            $new_password = globalHelper()->generatePassword();
            $emailDetails = globalHelper()->getEmailDetails('reset_password', ['new_password' => $new_password]);
            $emailDetails['data']['first_name'] = $user->first_name;
            
            Mail::to("$user->email")->send(new AxoMailer($emailDetails));
            
            $user->password = Hash::make($new_password);
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Password reset successfully',
            ], 200);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function blockUser(Request $request) {
        try { 
            $user = User::find($request->id);
            $user->status = AxocallEnum::USER_STATUS_BLOCKED->label();
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'User blocked successfully',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }   

    public function activateUser(Request $request) {
        try {
            $user = User::find($request->id);
            $user->status = AxocallEnum::USER_STATUS_ACTIVE->label();
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'User activated successfully',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }   

    public function saveRole(Request $request) {
        try {
            $validated = validatorHelper()->validate('account_save_role', $request);

            if (!$validated['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $validated['response'],
                ], 400);
            }

            $role = Role::create($validated['validated']);

            return response()->json([
                'status' => true,
                'message' => 'Role saved successfully',
                'data' => $role,
            ], 200);
            
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getRoles() {
        try {
            $roles = Role::all();

            return response()->json([
                'status' => true,
                'message' => 'Roles fetched successfully',
                'data' => $roles,
            ], 200);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function editRole(Request $request) {
        try {
            $role = Role::find($request->id);
            
            return response()->json([
                'status' => true,
                'message' => 'Role fetched successfully',
                'data' => $role,
            ], 200);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateRole(Request $request) {
        try {
            
            $validated = validatorHelper()->validate('account_save_role', $request);
            
            if (!$validated['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $validated['response'],
                ], 400);
            }
            
            $role = Role::find($request->ID);
            $role->update($validated['validated']);

            return response()->json([
                'status' => true,
                'message' => 'Role updated successfully',
                'data' => $role,
            ], 200);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteRole(Request $request) {
        try {
            $role = Role::find($request->ID);
            $role->delete();

            return response()->json([
                'status' => true,
                'message' => 'Role deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateUserRole(Request $request) {
        try {
            $user = User::find($request->user_id);
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                ], 404);
            }

            $role = Role::find($request->role_id);
            
            if (!$role) {
                return response()->json([
                    'status' => false,
                    'message' => 'Role not found',
                ], 404);
            }

            $user->role = $role->role;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'User role updated successfully',
                'data' => $user->load('role'),
            ], 200);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}