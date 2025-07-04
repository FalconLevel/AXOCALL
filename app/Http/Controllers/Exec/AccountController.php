<?php

namespace App\Http\Controllers\Exec;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
                $response['message'],
                'System Info',
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
}