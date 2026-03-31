<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

echo "1. Testing JSON Post...\n";
$res1 = Http::withHeaders([
    'Authorization' => 'invalid-token-123'
])->post('https://api.fonnte.com/send', [
    'target' => '081234567890',
    'message' => 'Test'
]);
echo "JSON Response: " . $res1->body() . "\n\n";

echo "2. Testing Form URL-encoded Post...\n";
$res2 = Http::asForm()->withHeaders([
    'Authorization' => 'invalid-token-123'
])->post('https://api.fonnte.com/send', [
    'target' => '081234567890',
    'message' => 'Test'
]);
echo "Form Response: " . $res2->body() . "\n\n";

echo "3. Testing Form Data with delay...\n";
$res3 = Http::asForm()->withHeaders([
    'Authorization' => 'invalid-token-123'
])->post('https://api.fonnte.com/send', [
    'target' => '081234567890',
    'message' => 'Test',
    'delay' => '1'
]);
echo "Form Data Response: " . $res3->body() . "\n\n";

