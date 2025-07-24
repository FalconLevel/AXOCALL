<?php

namespace App\Http\Controllers\Exec;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function all(Request $request): JsonResponse {
        try {
            
            $response = apiHelper()->execute($request, '/api/contacts/all');

            if (!$response['status']) {
                return globalHelper()->ajaxErrorResponse($response['message'], '', 'System Error');
            }
            
            $response['permissions'] = globalHelper()->getPermissions()['contacts'];
            return response()->json($response);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    }

    public function save(Request $request) {
        try {
            
            $response = apiHelper()->execute($request, '/api/contacts/save');
            
            if (! $response['status']) {
                return globalHelper()->ajaxErrorResponse($response['message'], '', 'System Error');
            }

            return response()->json(['status' => 'success', 'message' => 'Contact saved successfully', 'data' => $response['message']]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());  
            return globalHelper()->ajaxErrorResponse($e->getMessage(), '', 'System Error');
        }
    } 

    public function edit($id) {
        $contact = Contact::with('phoneNumbers', 'tags')->find($id);
        return response()->json(['status' => 'success', 'data' => $contact]);
    }

    public function update(Request $request, $id) {
        $validated = validatorHelper()->validate('contact_save', $request);
        if (! $validated['status']) {
            return response()->json($validated, 400);
        }
    }

    public function delete($id) {
        $contact = Contact::with('phoneNumbers', 'tags')->find($id);
        $contact->delete();
        return response()->json(['status' => 'success', 'message' => 'Contact deleted successfully']);
    }
    
}