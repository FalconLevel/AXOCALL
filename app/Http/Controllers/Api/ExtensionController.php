<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Extension;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ExtensionController extends Controller
{
    /**
     * Display a listing of the extensions.
     */
    public function all()
    {
        $extensions = Extension::with('contact', 'phone')->orderBy('created_at', 'desc')->get();

        $extensions = $extensions->map(function ($extension) {
            $extension->status = $extension->expiration > now() ? "active" : "inactive";
            return $extension;
        });

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
                'expiration' => 'nullable|string',
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
    
            $validated = $validator->validated();
            $validated['expiration'] = Carbon::parse(str_replace([' AM', ' PM'], '', strtoupper($validated['expiration'])))->format('Y-m-d H:i:s');
            
            $extension = Extension::create($validated);
    
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
        try {
        $extension = Extension::find($id);
        
        if (!$extension) {
            return response()->json([
                'status' => 'error',
                'message' => 'Extension not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'extension_number' => 'sometimes|required|string|max:255|unique:extensions,extension_number,' . $id,
            'expiration' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            logInfo($validator->errors());
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $validated['expiration'] = Carbon::parse(str_replace([' AM', ' PM'], '', strtoupper($validated['expiration'])))->format('Y-m-d H:i:s');
        $extension->update($validated);

        return response()->json([
            'status' => 'success',
                'data' => $extension
            ]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update extension'
            ], 500);
        }
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

    public function export()
    {
        try {
            $extensions = Extension::with('contact', 'phone')->get();
            $filename = 'extensions_'.date('Y-m-d_H-i-s').'.csv';
            $path = public_path('assets/axocall/exports/'.$filename);
            $file = fopen($path, 'w');
            fputcsv($file, ['Name', 'Phone', 'Extension', 'Timezone', 'Expiration', 'Notes', 'Status']);
            foreach ($extensions as $extension) {
                fputcsv($file, [
                    $extension->contact->first_name . ' ' . 
                    $extension->contact->last_name, 
                    $extension->phone->phone_number, 
                    $extension->extension_number, 
                    $extension->contact->timezone,
                    $extension->expiration, 
                    $extension->notes, 
                    $extension->status]);
            }
            fclose($file);
            return response()->json(['status' => 'success', 'data' => URL::to('assets/axocall/exports/'.$filename)]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to export extensions'
            ], 500);
        }
    }   

    public function reActivate($id)
    {
        try {
            $extension = Extension::find($id);
            $extension_data = globalHelper()->generateExtension($extension->contact_id);
            
            $expiration_date = $extension_data['expiration_date'];

            if (!$extension) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Extension not found'
                ], 404);
            }

            $extension->status = 'active';
            $extension->expiration = Carbon::parse(str_replace([' AM', ' PM'], '', strtoupper($expiration_date)))->format('Y-m-d H:i:s');
            $extension->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Extension re-activated successfully'
            ]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to re-activate extension'
            ], 500);
        }
    }
}