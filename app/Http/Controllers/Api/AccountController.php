<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'status' => true,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'message' => 'Account registered successfully',
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
            $request->user()->tokens()->delete();
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
}