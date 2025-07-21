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
                $response['data'] ? $response['data'] : []
            );
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }

    public function keywordSettings(Request $request)
    {
        try {
            $response = apiHelper()->execute($request, '/api/settings/keyword-settings');

            return globalHelper()->ajaxSuccessResponse(
                'scripts',
                'success',
                'fetch-keywords',
                '',   
                '',
                $response['data'] ? $response['data'] : []
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

    public function saveKeywords(Request $request)
    {
        try {
            $response = apiHelper()->execute($request, '/api/settings/save-keywords');

            if (!$response['status']) {
                return globalHelper()->ajaxErrorResponse($response['message'], '', 'User Error');
            }
            
            return globalHelper()->ajaxSuccessResponse(
                'toast',
                'success',
                'save-keywords',
                $response['response'],   
                'System Info'
            );
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }

    public function saveEmailSettings(Request $request)
    {
        try {
            $response = apiHelper()->execute($request, '/api/settings/save-email-settings');

            if (!$response['status']) {
                return globalHelper()->ajaxErrorResponse($response['message'], '', 'User Error');
            }
            
            return globalHelper()->ajaxSuccessResponse(
                'toast',
                'success',
                'save-email-settings',
                $response['response'],   
                'System Info'
            );
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
        
    }

    public function emailSettings(Request $request)
    {
        try {
            $response = apiHelper()->execute($request, '/api/settings/email-settings');

            return globalHelper()->ajaxSuccessResponse(
                'scripts',
                'success',
                'fetch-email-settings',
                '',   
                '',
                $response['data'] ? $response['data'] : []
            );
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }

    public function smartCallback(Request $request)
    {
        try {
            $response = apiHelper()->execute($request, '/api/settings/smart-callback');

            return globalHelper()->ajaxSuccessResponse(
                'scripts',
                'success',
                'fetch-smart-callback',
                '',   
                '', 
                $response['data'] ? $response['data'] : []
            );
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }

    public function saveSmartCallback(Request $request)
    {
        try {
            $response = apiHelper()->execute($request, '/api/settings/save-smart-callback');

            if (!$response['status']) {
                return globalHelper()->ajaxErrorResponse($response['response'], '', 'User Error');
            }
            
            return globalHelper()->ajaxSuccessResponse(
                'toast',
                'success',
                'save-smart-callback',
                $response['response'],   
                'System Info'
            );
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }   
}