<?php

namespace App\Http\Controllers\Exec;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function extensionSettings(Request $request)
    {
        try {
            $response = apiHelper()->execute($request, '/api/settings/extension-settings');
            return globalHelper()->ajaxSuccessResponse(
                'scripts',
                'success',
                'fetch-extension-settings',
                '',   
                '',
                $response['data']
            );
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }

    public function saveExtensionSettings(Request $request)
    {
        try {

            $response = apiHelper()->execute($request, '/api/settings/save-extension-settings');
            
            if (!$response['status']) {
                return globalHelper()->ajaxErrorResponse($response['message'], '', 'User Error');
            }
            
            return globalHelper()->ajaxSuccessResponse(
                'toast',
                'success',
                'save-extension-settings',
                $response['message'],   
                'System Info'
            );
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
        
    }
}