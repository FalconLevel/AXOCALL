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

    /**
     * Convert seconds to hh:mm:ss format
     */
    public static function formatDuration($seconds): string {
        if (!$seconds || $seconds <= 0) {
            return '00:00:00';
        }
        
        $seconds = (int) $seconds;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;
        
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
    }
}