<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SettingExtension;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function extensionSettings(): JsonResponse {
        try {
            $settings = SettingExtension::first();
            return response()->json(['status' => 'success', 'data' => $settings]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function saveExtensionSettings(Request $request): JsonResponse {
        try {
            // return response()->json($request->IsRandomExtensionGeneration);
            $validated = validatorHelper()->validate('extension_settings_save', $request);
            if (! $validated['status']) {
                return response()->json($validated, 400);
            }

            $validated['validated']['is_active'] = true;
            
            $settings = SettingExtension::first();
            if (! $settings) {
                SettingExtension::create($validated['validated']);
            } else {
                $settings->update($validated['validated']);
            }

            return response()->json(['status' => true, 'message' => 'Extension settings saved successfully']);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}