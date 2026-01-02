<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
    }

    public function listModels(): array
    {
        if (empty($this->apiKey)) {
            throw new \Exception('Gemini API Key is missing.');
        }

        $response = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$this->apiKey}");

        if ($response->failed()) {
            throw new \Exception('Failed to list Gemini models: ' . $response->body());
        }

        return $response->json()['models'] ?? [];
    }

    public function generateContent(string $prompt, string $model = 'gemini-2.5-pro'): string
    {
        if (empty($this->apiKey)) {
            throw new \Exception('Gemini API Key is missing. Please set GEMINI_API_KEY in your .env file.');
        }

        // Clean model name if full resource path is passed
        $model = str_replace('models/', '', $model);
        
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

        $response = Http::post("{$url}?key={$this->apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);

        if ($response->failed()) {
            throw new \Exception('Gemini API Error: ' . $response->body());
        }

        $data = $response->json();

        return $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
    }

    public function generateImage(string $prompt, string $model = 'nano-banana-pro'): ?string
    {
        if (empty($this->apiKey)) {
            throw new \Exception('Gemini API Key is missing.');
        }

        // Map custom model name to actual Imagen model or user requested preview
        // Using Imagen 4.0 as valid endpoints found in user's account
        if ($model === 'nano-banana-pro' || $model === 'gemini-3-pro-image-preview') {
             $model = 'imagen-4.0-generate-001';
        } elseif ($model === 'gemini-2.5-flash-image') {
             $model = 'imagen-4.0-fast-generate-001';
        }

        // Clean model name
        $model = str_replace('models/', '', $model);
        
        // Use Imagen predict endpoint for both (Imagen 4 uses predict)
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:predict"; 
        
        // Imagen-style payload
        $payload = [
            'instances' => [['prompt' => $prompt]],
            'parameters' => [
                'sampleCount' => 1,
                'aspectRatio' => '16:9'
            ]
        ];

        $response = Http::timeout(120)->post("{$url}?key={$this->apiKey}", $payload);

        if ($response->failed()) {
             throw new \Exception('Image Generation Error (' . $model . '): ' . $response->body());
        }

        $data = $response->json();

        // Handle Imagen output
        return $data['predictions'][0]['bytesBase64Encoded'] ?? null;
    }
}
