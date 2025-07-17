<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keyword;
use App\Models\SettingExtension;
use App\Models\SettingEmailSummaries;
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

    public function keywordSettings(): JsonResponse {
        try {
            $keywords = Keyword::first();
            return response()->json(['status' => 'success', 'data' => $keywords]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function saveExtensionSettings(Request $request): JsonResponse {
        try {
            
            $validated = validatorHelper()->validate('extension_settings_save', $request);

            if (! $validated['status']) {
                return response()->json($validated, 400);
            }

            $validated['validated']['is_active'] = true;
            $validated['validated']['extension_expiration_hrs'] = $request->ExtensionExpirationHrs;
            $validated['validated']['random_extension_generation'] = $request->IsRandomExtensionGeneration;

            
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

    public function saveKeywords(Request $request): JsonResponse {
        try {
            $validated = validatorHelper()->validate('keywords_save', $request);

            if (! $validated['status']) {
                return response()->json($validated, 400);
            }
            
            $validated['validated']['keywords'] = $request->Keywords;
            
            $keywords = Keyword::first();
            
            if (! $keywords) {
                Keyword::create($validated['validated']);
            } else {
                $keywords->update($validated['validated']);
            }

            return response()->json(['status' => true, 'response' => 'Keywords saved successfully']);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json(['status' => false, 'response' => $e->getMessage()], 500);
        }
    }

    public function saveEmailSettings(Request $request): JsonResponse {
        try {
            
            $validated = validatorHelper()->validate('email_settings_save', $request);

            if (! $validated['status']) {
                return response()->json($validated, 400);
            }

            $validated['validated']['is_enabled'] = $request->IsEnabled == 1 ? true : false;
            $validated['validated']['day_of_week'] = $request->DayOfWeek == 'null' ? null : $request->DayOfWeek;
            
            $settings = SettingEmailSummaries::first();
            if (! $settings) {
                SettingEmailSummaries::create($validated['validated']);
            } else {
                $settings->update($validated['validated']);
            }

            return response()->json(['status' => true, 'response' => 'Email settings saved successfully']);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json(['status' => false, 'response' => $e->getMessage()], 500);
        }
    }

    public function emailSettings(): JsonResponse {
        try {
            $settings = SettingEmailSummaries::first();
            return response()->json(['status' => true, 'data' => $settings ? $settings : [] ]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json(['status' => false, 'data' => []], 500);
        }
    }
}