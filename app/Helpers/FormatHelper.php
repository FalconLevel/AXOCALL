<?php

declare(strict_types=1);
namespace App\Helpers;

class FormatHelper {
    public static function formatPhoneNumber(string $phone_number): string {
        $formatted_phone_number = preg_replace('/[^0-9]/', '', $phone_number);
        
        return strlen($formatted_phone_number) == 10 ? 
            '+1' . $formatted_phone_number : 
            $formatted_phone_number;
    }
}