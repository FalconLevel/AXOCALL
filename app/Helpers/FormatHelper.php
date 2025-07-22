<?php

declare(strict_types=1);
namespace App\Helpers;

class FormatHelper {
    public static function unFormatPhoneNumber(string $phone_number): string {
        return preg_replace('/[^0-9]/', '', $phone_number);
    }

    public static function formatPhoneNumber(string $phone_number): string {
        $formatted_phone_number = preg_replace('/[^0-9]/', '', $phone_number);
        
        return strlen($formatted_phone_number) == 10 ? 
            '+1' . $formatted_phone_number : 
            "+" . $formatted_phone_number;
    }

    public static function formatPhoneNumberWithParenthesis(string $phone_number): string {
        $formatted_phone_number = preg_replace('/[^0-9]/', '', $phone_number);
        if (strlen($formatted_phone_number) == 10) {
            return '(' . substr($formatted_phone_number, 0, 3) . ') ' . substr($formatted_phone_number, 3, 3) . '-' . substr($formatted_phone_number, 6);
        } else {
            return '(' . substr($formatted_phone_number, 1, 3) . ') ' . substr($formatted_phone_number, 4, 3) . '-' . substr($formatted_phone_number, 7);
        }
    }

    public static function formatPhoneNumbersFull(string $phone_number): string {
        $formatted_phone_number = preg_replace('/[^0-9]/', '', $phone_number);
        if (strlen($formatted_phone_number) == 10) {
            return '+1 (' . substr($formatted_phone_number, 0, 3) . ') ' . substr($formatted_phone_number, 3, 3) . '-' . substr($formatted_phone_number, 6);
        } else {
            return '+1 (' . substr($formatted_phone_number, 1, 3) . ') ' . substr($formatted_phone_number, 4, 3) . '-' . substr($formatted_phone_number, 7);
        }
    }

    /**
     * Convert seconds to hh:mm:ss format
     */
    public static function formatDuration($seconds): string {
        if (!$seconds || $seconds <= 0) {
            return '00:00';
        }
        
        $seconds = (int) $seconds;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
        } else {
            return sprintf('%02d:%02d', $minutes, $remainingSeconds);
        }
    }

    public static function formatDate(string $date): string {
        return date('m/d/y h:i A', strtotime($date));
    }
}