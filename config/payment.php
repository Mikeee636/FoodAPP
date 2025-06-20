<?php

// FILE: config/payment.php

return [
    'bank_transfer' => [
        'name'      => env('BANK_NAME', 'Nama Bank'),
        'holder'    => env('BANK_ACCOUNT_HOLDER', 'Atas Nama'),
        'number'    => env('BANK_ACCOUNT_NUMBER', 'Nomor Rekening'),
    ],
];
