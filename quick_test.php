<?php
$env = file_get_contents(__DIR__ . '/.env');
preg_match('/GEMINI_API_KEY=(.*)/', $env, $matches);
$apiKey = trim($matches[1] ?? '');

if (empty($apiKey)) {
    die("No API Key found in .env\n");
}

$url = "https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
if (isset($data['models'])) {
    foreach ($data['models'] as $model) {
        echo "Model: " . $model['name'] . "\n";
        echo "Methods: " . implode(', ', $model['supportedGenerationMethods']) . "\n";
        echo "----------------\n";
    }
} else {
    echo "Error: " . $response . "\n";
}
