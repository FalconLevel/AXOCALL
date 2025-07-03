<?php

declare(strict_types=1);
namespace App\Helpers;

class KeysHelper {
    const KEYS = [
        'TagName' => 'tag_name',
        'TagColor' => 'tag_color',

        'FirstName' => 'first_name',
        'LastName' => 'last_name',
        'Notes' => 'notes',
        
        'PhoneNumber' => 'phone_number',
        'PhoneExt' => 'phone_ext',
        'PhoneType' => 'phone_type',

        'ContactId' => 'contact_id',
        'ExtensionNumber' => 'extension_number',
        'Expiration' => 'expiration',
        'ExtensionNotes' => 'extension_notes',
        'ExtensionStatus' => 'extension_status',
        'ExtensionDateCreated' => 'extension_date_created',
        'ExtensionExpiration' => 'extension_expiration',
        'ExtensionPhoneNumber' => 'extension_phone_number',
        'ExtensionPhoneExt' => 'extension_phone_ext',
        'ExtensionPhoneType' => 'extension_phone_type',
        'ExtensionContactId' => 'extension_contact_id',
        'ExtensionPhoneId' => 'extension_phone_id',

        'ExtensionExpirationDays' => 'extension_expiration_days',
        'ExtensionExpirationHrs' => 'extension_expiration_hrs',
        'IsRandomExtensionGeneration' => 'random_extension_generation',
        'IsActive' => 'is_active',
    ];

    public function getKey(string $key_index): string {
        return self::KEYS[$key_index];
    }
}