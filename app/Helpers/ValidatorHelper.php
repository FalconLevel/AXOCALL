<?php

declare(strict_types=1);
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidatorHelper {
    const EXCLUDED_FIELDS = [
        'ConfirmPassword', 'PhoneNumbers', 'Tags'
    ];
    public function validate(string $type, Request $request): array {
        
        $mapped = $this->key_map($request->except([
            ...self::EXCLUDED_FIELDS
        ]));
        
        $validated = Validator::make($mapped, $this->rules($type));
        
        if ($validated->fails()) {
            return [
                'status' => false,
                'response' => $validated->errors()->first(),
            ];
        }

        return [
            'status' => true,
            'validated' => $validated->validated(),
        ]; 
    }

    private function key_map($to_map): array {

        $mapped = [];
        foreach($to_map as $key => $value) {
            if($value) {
                $mapped[keysHelper()->getKey($key)] = $value;
            }
        }

        return $mapped;
    }

    private function rules(string $type) {
        switch($type) {
            case 'tag_save':
                return [
                    'tag_name' => 'required|string|max:255',
                    'tag_color' => 'required|string|max:255',
                ];
            case 'contact_save':
                return [
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'nullable|string|max:255',
                    'notes' => 'nullable|string|max:255',
                ];
            case 'extension_settings_save':
                return [
                    'extension_expiration_days' => 'required|integer|min:1|max:365',
                    'extension_expiration_hrs' => 'sometimes|integer|min:0|max:24',
                    'random_extension_generation' => 'sometimes|boolean',
                    'timezone' => 'required|string|max:255',
                    'is_active' => 'sometimes|boolean',
                ];

            case 'account_register':
                return [
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'phone_number' => 'required|string|max:10',
                    'company' => 'required|string|max:255',
                    'password' => 'required|string|min:8',
                ];
            case 'account_login':
                return [
                    'email' => 'required|string|email|max:255',
                    'password' => 'required|string|min:8',
                ];
            case 'keywords_save':
                return [
                    'keywords' => 'required|string|max:255',
                ];
            case 'profile_update':
                return [
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255',
                    'phone_number' => 'required|string|max:10',
                    'company' => 'required|string|max:255',
                    'apartment' => 'sometimes|string|max:255',
                    'street_address' => 'required|string|max:255',
                    'city' => 'required|string|max:255',
                    'state' => 'required|string|max:255',
                    'zip_code' => 'required|string|max:255',
                    'country' => 'nullable|string|max:255',
                ];
            case 'email_settings_save':
                return [
                    'email_addresses' => 'required|string',
                    'frequency' => 'required|string',
                    'day_of_week' => 'sometimes|string',
                    'is_enabled' => 'sometimes|boolean',
                ];
            case 'smart_callback_save':
                return [
                    'hours' => 'required|integer|min:0|max:23',
                    'minutes' => 'required|integer|min:0|max:59',
                    'is_active' => 'sometimes|boolean',
                ];

            case 'toggle_theme':
                return [
                    'theme' => 'required|string',
                ];
        }
    }
}