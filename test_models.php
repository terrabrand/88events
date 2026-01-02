<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = new \App\Services\GeminiService();
try {
    $models = $service->listModels();
    foreach ($models as $model) {
        echo "Name: " . $model['name'] . "\n";
        echo "Supported Methods: " . implode(', ', $model['supportedGenerationMethods']) . "\n";
        echo "-------------------\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
