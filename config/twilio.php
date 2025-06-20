<?php
    declare(strict_types=1);

    return [
        'twilio' => [
            'url' => env('TWILIO_URL'),
            'sid' => env('TWILIO_SID'),
            'token' => env('TWILIO_TOKEN'),
            'number' => env('TWILIO_NUMBER'),
        ],
        'svg' => [
            'inbound' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-blue-500" data-lov-id="src\components\call-logs\CallLogTable.jsx:369:14" data-lov-name="PhoneIncoming" data-component-path="src\components\call-logs\CallLogTable.jsx" data-component-line="369" data-component-file="CallLogTable.jsx" data-component-name="PhoneIncoming" data-component-content="%7B%22className%22%3A%22h-5%20w-5%20text-blue-500%22%7D" title="Inbound"><polyline points="16 2 16 8 22 8"></polyline><line x1="22" x2="16" y1="2" y2="8"></line><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>',
            'outbound' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-green-500" data-lov-id="src\components\call-logs\CallLogTable.jsx:370:14" data-lov-name="PhoneOutgoing" data-component-path="src\components\call-logs\CallLogTable.jsx" data-component-line="370" data-component-file="CallLogTable.jsx" data-component-name="PhoneOutgoing" data-component-content="%7B%22className%22%3A%22h-5%20w-5%20text-green-500%22%7D" title="Outbound"><path d="M12 2L2 12l10 10 10-10L12 2z"></path><polyline points="2 12 12 22 22 12"></polyline></svg>',
        ],
    ];