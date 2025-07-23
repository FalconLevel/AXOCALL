<?php

namespace App\Http\Controllers\Exec;

use App\Http\Controllers\Controller;
use App\Mail\AxoMailer;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AccountController extends Controller
{
    public function register(Request $request) {
        try {
            $response = apiHelper()->execute($request, '/api/account/register');
            
            if (!$response['status']) {
                return globalHelper()->ajaxErrorResponse($response['message'], '', 'System Error');
            }

            return globalHelper()->ajaxSuccessResponse(
                'scripts',
                'success',
                'account-register',
                'OTP sent successfully',
                'System Info',
                $response['otp_details']
            );
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }

    public function login(Request $request) {
        try {
            $response = apiHelper()->execute($request, '/api/account/login');

            if (!$response['status']) {
                return globalHelper()->ajaxErrorResponse($response['message'], '', 'System Error');
            }

            return globalHelper()->ajaxSuccessResponse(
                'scripts',
                'success',
                'account-login',
                $response['message'],
                'System Info',
            );
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }

    public function logout(Request $request) {
        try {
            $response = apiHelper()->execute($request, '/api/account/logout');

            if (!$response['status']) {
                return globalHelper()->ajaxErrorResponse($response['message'], '', 'System Error');
            }

            return globalHelper()->ajaxSuccessResponse( 
                'scripts',
                'success',
                'account-logout',
                $response['message'],
                'System Info',
            );
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }

    public function verifyOtp(Request $request) {
        try {
            $token = request()->get('token');
            $otp = Otp::where('token', $token)->where('is_used', 0)->first();
            
            if (!$otp) {
                return redirect()->route('login');
            }

            return view('pages.verify-otp', ['token' => $token, 'otp_details' => $otp->toArray()]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return redirect()->route('login');
        }
    }

    public function countdown(Request $request) {
        try {
            $token = request()->get('token');
            $otp = Otp::where('token', $token)->where('is_used', 0)->first();
            $expiry = date('Y-m-d H:i:s', strtotime($otp->expires_at)); 
            $now = date('Y-m-d H:i:s', strtotime(now()));
            $diff = strtotime($expiry) - strtotime($now);
            $minutes = floor($diff / 60);
            $seconds = $diff % 60;
            if ($minutes < 0 || $seconds < 0) {
                return response()->json(['status' => false, 'message' => '
                <a href="javascript:void(0)" class="text-primary" data-id="'.$otp->user_id.'" id="resend-otp">Resend OTP</a>']);
            }

            return response()->json(['status' => true, 'message' => $minutes . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT), 'expires_at' => $expiry]);
            
        } catch (\Exception $e) {
            logInfo(json_encode($e->getTrace()));
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    public function resendOtp(Request $request) {
        try { 
            
            $user = User::find($request->id)->toArray();
            $send_otp = globalHelper()->sendOtp($user);

            return response()->json($send_otp);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }

    public function validateOtp(Request $request) {
        try {
            $token = request()->get('token');
            $otp = Otp::where('token', $token)->where('is_used', 0)->first();

            if (!$otp) {
                return globalHelper()->ajaxErrorResponse('OTP expired', '', 'System Error');
            }

            Otp::where('token', $token)->update(['is_used' => 1]);
            User::where('id', $otp->user_id)->update(['email_verified_at' => now()]);

            return response()->json([
                'status' => true,
                'message' => 'OTP validated successfully',
                'token' => $token,
                'js' => 'window.location.href = "'.route('login').'";',
            ]);
            
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }

    public function getUsers() {
        try {
            $response = apiHelper()->execute(new Request(), '/api/account/get-users');
            
            if (!$response['status']) {
                return globalHelper()->ajaxErrorResponse($response['message'], '', 'System Error');
            }

            return globalHelper()->ajaxSuccessResponse( 
                'scripts',
                'success',
                'users',
                $response['message'],
                'System Info',
                $response['data']
            );
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }
}