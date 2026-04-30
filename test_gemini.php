<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$geminiKey = config('services.gemini.key');
$model = "gemini-1.5-flash"; // Try gemini-1.5-flash

echo "Using API Key: " . substr($geminiKey, 0, 5) . "...\n";

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . $geminiKey;

$prompt = "Buatkan siklus hidup (lifecycle) yang akurat dan realistis untuk tanaman Padi. 
Format WAJIB JSON persis seperti ini:
{
    \"plant\": \"Padi\",
    \"total_days\": 100,
    \"stages\": [
        {\"phase\": \"Perkecambahan\", \"days\": 10, \"action\": \"Tes\"}
    ]
}
Hanya kembalikan JSON object murni tanpa markdown blocks.";

try {
    $response = \Illuminate\Support\Facades\Http::withoutVerifying()
        ->withHeaders(['Content-Type' => 'application/json'])
        ->timeout(10)
        ->post($url, [
            "contents" => [
                ["parts" => [["text" => $prompt]]]
            ]
        ]);

    if ($response->successful()) {
        echo "Success!\n";
        echo $response->body() . "\n";
    } else {
        echo "Failed!\n";
        echo $response->body() . "\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
