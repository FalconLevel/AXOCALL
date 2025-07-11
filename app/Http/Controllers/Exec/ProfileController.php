<?php

namespace App\Http\Controllers\Exec;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        try {
            $response = apiHelper()->execute($request, '/api/profile/update');

            return globalHelper()->ajaxSuccessResponse(
                'toast',
                'success',
                'update-profile',
                'Profile updated successfully',   
                'System Info',
                $response
            );
        } catch (\Throwable $th) {
            logInfo($th->getMessage());
            return globalHelper()->ajaxErrorResponse($th->getMessage(), '', 'System Error');
        }
    }
}