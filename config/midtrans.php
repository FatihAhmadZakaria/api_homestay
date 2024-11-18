<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY', 'your-sandbox-server-key'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'your-sandbox-client-key'),
    'is_production' => false, // Tetap gunakan sandbox
    'is_sanitized' => true, // Aktifkan sanitasi data
    'is_3ds' => true, // Aktifkan 3DS untuk transaksi kartu kredit
];
