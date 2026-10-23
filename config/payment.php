<?php

return [
    'bank_account' => env('PAYMENT_BANK_ACCOUNT', ''),
    'recipient_name' => env('PAYMENT_RECIPIENT_NAME', ''),
    'recipient_address' => env('PAYMENT_RECIPIENT_ADDRESS', ''),
    'payment_purpose' => env('PAYMENT_PURPOSE', ''),
    'support_whatsapp' => env('PAYMENT_SUPPORT_WHATSAPP', ''),
];
