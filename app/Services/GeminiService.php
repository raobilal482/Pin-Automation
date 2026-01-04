<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    // protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
    // protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

   public function __construct()
    {
        $this->apiKey = 'AIzaSyCD1RpiIGLZ_BeyIktx1DPFwWva2SxhcFM';

        // --- DEBUG LINE ---
        echo "\n[DEBUG] Loaded Key: '" . $this->apiKey . "'\n";

        if (empty($this->apiKey)) {
            echo "[ERROR] Key is EMPTY! Check .env file.\n";
        }
        // ------------------
    }

    public function generatePinContent($keyword)
    {
        // AI ko instructions (Prompt) dena
        $prompt = "You are a Pinterest Marketing Expert.
        I have an image about: '$keyword'.

        Please generate a JSON response with:
        1. 'title': A catchy, click-worthy Pinterest title (max 60 chars).
        2. 'description': An engaging SEO description (max 400 chars) including 3-5 relevant hashtags.

        Respond ONLY in raw JSON format like: {\"title\": \"...\", \"description\": \"...\"}";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

           if ($response->failed()) {
                // --- DEBUG LINE ADD KAREIN ---
                echo "\n[GEMINI API ERROR]: " . $response->body() . "\n";
                // -----------------------------
                Log::error('Gemini API Error: ' . $response->body());
                return null;
            }

            $data = $response->json();

            // Response se text nikalna
            $rawText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$rawText) return null;

            // JSON clean karna (Kabhi kabhi AI ```json ``` laga deta hai)
            $rawText = str_replace(['```json', '```'], '', $rawText);

            return json_decode($rawText, true);

        } catch (\Exception $e) {
            // --- DEBUG LINE ADD KAREIN ---
            echo "\n[GEMINI EXCEPTION]: " . $e->getMessage() . "\n";
            // -----------------------------
            Log::error('Gemini Service Exception: ' . $e->getMessage());
            return null;
        }
    }
}
