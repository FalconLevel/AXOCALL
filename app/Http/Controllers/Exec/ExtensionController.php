<?php

namespace App\Http\Controllers\Exec;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExtensionController extends Controller
{
public function all(Request $request)
{
    // Fetch all extensions, you may want to add pagination or filters as needed
    $extensions = \App\Models\Extension::orderBy('created_at', 'desc')->get();

    return response()->json([
        'status' => 'success',
        'data' => $extensions
    ]);
}

public function save(Request $request)
{
    $validated = $request->validate([
        'extension_number' => 'required|string|max:255',
        'expiration' => 'nullable|date',
        'notes' => 'nullable|string|max:1000',
    ]);

    $extension = new \App\Models\Extension();
    $extension->extension_number = $validated['extension_number'];
    $extension->expiration = $validated['expiration'] ?? null;
    $extension->notes = $validated['notes'] ?? null;
    $extension->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Extension saved successfully',
        'data' => $extension
    ]);
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'extension_number' => 'required|string|max:255',
        'expiration' => 'nullable|date',
        'notes' => 'nullable|string|max:1000',
    ]);

    $extension = \App\Models\Extension::find($id);
    if (!$extension) {
        return response()->json([
            'status' => 'error',
            'message' => 'Extension not found'
        ], 404);
    }

    $extension->extension_number = $validated['extension_number'];
    $extension->expiration = $validated['expiration'] ?? null;
    $extension->notes = $validated['notes'] ?? null;
    $extension->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Extension updated successfully',
        'data' => $extension
    ]);
}

public function delete(Request $request, $id)
{
    $extension = \App\Models\Extension::find($id);
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

public function edit(Request $request, $id)
{
    $extension = \App\Models\Extension::find($id);
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
}