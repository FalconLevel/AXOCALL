<?php

declare(strict_types=1);
namespace App\Helpers;

class KeysHelper {
    const KEYS = [
        'TagName' => 'tag_name',
        'TagColor' => 'tag_color',
    ];

    public function getKey(string $key_index): string {
        return self::KEYS[$key_index];
    }
}