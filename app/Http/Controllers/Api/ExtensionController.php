<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Extension;
use Illuminate\Support\Facades\Validator;

class ExtensionController extends Controller
{
    /**
     * Display a listing of the extensions.
     */
    public function all()
    {
        $extensions = Extension::with('contact', 'phone')->get();
        return response()->json([
            'status' => 'success',
            'data' => $extensions
        ]);
    }

    /**
     * Store a newly created extension in storage.
     */
    public function save(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'contact_id' => 'required|exists:contacts,id',
                'phone_id' => 'required|exists:phone_numbers,id',
                'extension_number' => 'required|string|max:255|unique:extensions,extension_number',
                'expiration' => 'nullable|date',
                'notes' => 'nullable|string',
                'status' => 'nullable|string',
            ]);
    
            if ($validator->fails()) {
                logInfo($validator->errors());
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            $extension = Extension::create($validator->validated());
    
            return response()->json([
                'status' => 'success',
                'data' => $extension
            ], 201);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save extension' 
            ], 500);
        }

        
    }

    /**
     * Display the specified extension.
     */
    public function edit($id)
    {
        $extension = Extension::with('contact', 'phone')->find($id);

        if (!$extension) {
            return response()->json([
                'status' => 'error',
                'message' => 'Extension not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $extension
        ]);
    }

    /**
     * Update the specified extension in storage.
     */
    public function update(Request $request, $id)
    {
        $extension = Extension::find($id);

        if (!$extension) {
            return response()->json([
                'status' => 'error',
                'message' => 'Extension not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'contact_id' => 'sometimes|required|exists:contacts,id',
            'extension_number' => 'sometimes|required|string|max:255',
            'expiration' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $extension->update($validator->validated());

        return response()->json([
            'status' => 'success',
            'data' => $extension
        ]);
    }

    /**
     * Remove the specified extension from storage (soft delete).
     */
    public function delete($id)
    {
        $extension = Extension::find($id);

        if (!$extension) {
            return response()->json([
                'status' => 'error',
                'message' => 'Extension not found'
            ], 404);
        }

        $extension->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Extension deleted successfully'
        ]);
    }

    public function generate(Request $request)
    {
        try {
            $extension_data = globalHelper()->generateExtension();
            return response()->json([
                'status' => 'success',
                'data' => $extension_data
            ]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate extension'
            ], 500);
        }
    } 
}