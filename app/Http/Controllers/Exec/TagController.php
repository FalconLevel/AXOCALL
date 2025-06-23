<?php
declare(strict_types=1);
namespace App\Http\Controllers\Exec;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function all(Request $request): JsonResponse {
        try {
            $response = apiHelper()->execute($request, '/api/tags/all');

            if (!$response['status']) {
                return globalHelper()->ajaxErrorResponse($response['message'], '', 'System Error');
            }
            
            return globalHelper()->ajaxSuccessResponse(
                'scripts',
                'success',
                'fetch-tag',
                '',
                '',
                $response['data']
            );
            
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }

    
    public function save(Request $request): JsonResponse {
        try {

            $response = apiHelper()->execute($request, '/api/tags/save');
            
            if (!$response['status']) {
                return globalHelper()->ajaxErrorResponse($response['message'], '', 'User Error');
            }
            
            return globalHelper()->ajaxSuccessResponse(
                'scripts',
                'success',
                'add-tag',
                $response['message'],   
                'System Info'
            );
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
        
    }

    public function delete(Request $request, string $id): JsonResponse {
        try {
            $response = apiHelper()->execute($request, '/api/tags/delete/' . $id);

            if (!$response['status']) {
                return globalHelper()->ajaxErrorResponse($response['message'], '', 'User Error');
            }
            
            return globalHelper()->ajaxSuccessResponse(
                'scripts',
                'success',
                'delete-tag',
                $response['message'],
                'System Info'
            );
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }
}