<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

class TagController extends Controller
{
    public function all(): JsonResponse {
        try {
            $tags = Tag::all();
            return response()->json(['status' => 'success', 'data' => $tags]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function save(Request $request): JsonResponse {
        try {
            
            $validated = validatorHelper()->validate('tag_save', $request);
            
            if (! $validated['status']) {
                return response()->json($validated, 400);
            }
            
            $tag = Tag::create($validated['validated']);
            
            return response()->json(['status' => true, 'message' => "Tag created successfully"]);
        } catch (QueryException $e) {
            logInfo($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Duplicate Entry'], 500);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id): JsonResponse {
        try {
            $tag = Tag::find($id);
            $tag->delete();
            return response()->json(['status' => true, 'message' => 'Tag deleted successfully']);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}