<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PinterestService
{
    // protected $baseUrl = 'https://api.pinterest.com/v5';
    // Sandbox URL hona chahiye
protected $baseUrl = 'https://api-sandbox.pinterest.com/v5';

    public function getBoards($account)
    {
        $response = Http::withToken($account->access_token)->get("{$this->baseUrl}/boards?");
        // dd($response);
        return $response->json()['items'] ?? [];
    }
    public function getPinsFromBoard($account,$id){
        // dd($account);
        $response = Http::withToken($account->access_token)->get("{$this->baseUrl}/boards/$id/pins?");
        return $response->json()['items'];
        return $response->json()['items'] ?? ['error while fetching pins'];
    }
public function createBoard($account, $name, $description)
    {
        $response = Http::withToken($account->access_token)
            ->post("{$this->baseUrl}/boards", [
                'name' => $name,
                'description' => $description,
                'privacy' => 'PUBLIC' // Hamesha Public board banega
            ]);

        // Error Handling
        if ($response->failed()) {
            Log::error("Create Board Failed: " . $response->body());

            // Pinterest ka asal error message nikal kar bhejen
            $errorMessage = $response->json()['message'] ?? 'Failed to create board. Check API permissions.';
            return ['error' => $errorMessage];
        }

        return $response->json();
    }
    // public function createPin($account, $title, $description, $link, $imagePath, $boardId)
    // {
    //     echo "\n[1/3] Uploading Image...";
    //     $mediaId = $this->uploadMedia($account, $imagePath);

    //     if (!$mediaId) {
    //         echo "\n[ERROR] Image Upload Failed! Check if image exists.\n";
    //         return null;
    //     }

    //     echo " Done (Media ID: $mediaId)\n[2/3] Creating Pin...";

    //     $response = Http::withToken($account->access_token)
    //         ->post("{$this->baseUrl}/pins", [
    //             'board_id' => $boardId,
    //             'media_source' => [
    //                 'source_type' => 'image_id',
    //                 'media_id' => $mediaId
    //             ],
    //             'title' => substr($title, 0, 100),
    //             'description' => substr($description, 0, 500),
    //             'link' => $link
    //         ]);

    //     if ($response->failed()) {
    //         echo "\n[ERROR] Pinterest API Reject: " . $response->body() . "\n";
    //         Log::error("Pin Failed: " . $response->body());
    //         return null;
    //     }

    //     return $response->json();
    // }
// --- WRITING PINS (REAL) ---
// --- LOUD DEBUGGING MODE ON ---
public function createPin($account, $title, $description, $link, $imagePath, $boardId)
    {
        echo "\n[DEBUG] Preparing Image for Base64 Upload...\n";

        // 1. Image ka Asal Path nikalo
        $realPath = \Illuminate\Support\Facades\Storage::disk('public')->path($imagePath);

        if (!file_exists($realPath)) {
            echo "\n[ERROR] File Not Found: $realPath\n";
            return null;
        }

        // 2. Image Type Check karo (JPG ya PNG)
        $extension = pathinfo($realPath, PATHINFO_EXTENSION);
        $contentType = 'image/jpeg'; // Default

        if (strtolower($extension) === 'png') {
            $contentType = 'image/png';
        }

        // 3. Image ko Base64 String mein convert karo
        $imageData = base64_encode(file_get_contents($realPath));

        echo "[DEBUG] Sending Pin to Pinterest (Base64 Mode)...\n";

        // 4. Pinterest ko Bhejo
        $response = Http::withToken($account->access_token)
            ->post("https://api-sandbox.pinterest.com/v5/pins?", [
                'board_id' => $boardId,
                // --- YEH HAI MAGIC CHANGE ---
                'media_source' => [
                    'source_type' => 'image_base64',
                    'content_type' => $contentType,
                    'data' => $imageData
                ],
                // ----------------------------
                'title' => substr($title, 0, 100),
                'description' => substr($description, 0, 500),
                'link' => $link
            ]);

        // Error Catching
        if ($response->failed()) {
            echo "\n[CRITICAL API ERROR]: " . $response->body() . "\n";
            Log::error("Pin Creation Failed: " . $response->body());
            return null;
        }

        echo "\n[SUCCESS] Pin Created! Response: " . $response->body() . "\n";
        return $response->json();
    }
    // public function createPin($account, $title, $description, $link, $imagePath, $boardId)
    // {
    //     echo "\n[DEBUG] Starting Pin Creation Process...\n";

    //     // 1. Image Upload
    //     $mediaId = $this->uploadMedia($account, $imagePath);

    //     if (!$mediaId) {
    //         echo "\n[ERROR] Stopping because Media Upload Failed.\n";
    //         return null;
    //     }

    //     echo "\n[DEBUG] Media ID Received: $mediaId\n";
    //     echo "[DEBUG] Sending Pin Data to Pinterest...\n";

    //     // 2. Pin Create
    //     $response = Http::withToken($account->access_token)
    //         ->post("{$this->baseUrl}/pins", [
    //             'board_id' => $boardId,
    //             'media_source' => [
    //                 'source_type' => 'image_id',
    //                 'media_id' => $mediaId
    //             ],
    //             'title' => substr($title, 0, 100),
    //             'description' => substr($description, 0, 500),
    //             'link' => $link
    //         ]);

    //     // Error Catching
    //     if ($response->failed()) {
    //         echo "\n[CRITICAL API ERROR]: " . $response->body() . "\n";
    //         Log::error("Pin Creation Failed: " . $response->body());
    //         return null;
    //     }

    //     echo "\n[SUCCESS] Pin Created! Response: " . $response->body() . "\n";
    //     return $response->json();
    // }

    protected function uploadMedia($account, $imagePath)
    {
        echo "[DEBUG] Locating Image...\n";
        $realPath = \Illuminate\Support\Facades\Storage::disk('public')->path($imagePath);

        if (!file_exists($realPath)) {
            echo "[ERROR] File Not Found on Disk: $realPath\n";
            return null;
        }

        // A. Init
        echo "[DEBUG] Registering Upload with Pinterest...\n";
        $init = Http::withToken($account->access_token)->post("{$this->baseUrl}/media", [
            'media_type' => 'image'
        ]);

        if ($init->failed()) {
            echo "[API ERROR - Init]: " . $init->body() . "\n";
            return null;
        }

        $data = $init->json();
        $uploadUrl = $data['upload_url'];
        $uploadParams = $data['upload_parameters'];
        $mediaId = $data['media_id'];

        // B. Upload
        echo "[DEBUG] Uploading Bytes to Amazon S3 (Pinterest Storage)...\n";
        $response = Http::asMultipart();
        foreach ($uploadParams as $key => $value) {
            $response->attach($key, $value);
        }
        $response->attach('file', file_get_contents($realPath), basename($realPath));

        $upload = $response->post($uploadUrl);

        if ($upload->failed()) {
            echo "[API ERROR - Upload]: " . $upload->body() . "\n";
            return null;
        }

        echo "[DEBUG] Upload Success. Waiting for processing...\n";
        sleep(2);
        return $mediaId;
    }
    // public function createPin($account, $title, $description, $link, $imagePath, $boardId)
    // {
    //     // 1. Image Upload Karein
    //     $mediaId = $this->uploadMedia($account, $imagePath);

    //     if (!$mediaId) {
    //         Log::error("Media Upload Failed for: " . $imagePath);
    //         return null;
    //     }

    //     // 2. Pin Create Karein
    //     $response = Http::withToken($account->access_token)
    //         ->post("{$this->baseUrl}/pins", [
    //             'board_id' => $boardId,
    //             'media_source' => [
    //                 'source_type' => 'image_id',
    //                 'media_id' => $mediaId
    //             ],
    //             'title' => substr($title, 0, 100),
    //             'description' => substr($description, 0, 500),
    //             'link' => $link
    //         ]);

    //     if ($response->failed()) {
    //         Log::error("Pin Creation Failed: " . $response->body());
    //         // Debugging k liye screen par bhi dikhayen
    //         echo "\n[PINTEREST ERROR]: " . $response->body() . "\n";
    //         return null;
    //     }

    //     return $response->json();
    // }

    // protected function uploadMedia($account, $imagePath)
    // {
    //     // Image ka asal path dhoondna
    //     $realPath = \Illuminate\Support\Facades\Storage::disk('public')->path($imagePath);

    //     if (!file_exists($realPath)) {
    //         echo "\n[ERROR] File not found at: $realPath\n";
    //         return null;
    //     }

    //     // A. Upload Register Karein
    //     $init = Http::withToken($account->access_token)->post("{$this->baseUrl}/media", [
    //         'media_type' => 'image'
    //     ]);

    //     if ($init->failed()) {
    //         echo "\n[MEDIA INIT ERROR]: " . $init->body() . "\n";
    //         return null;
    //     }

    //     $data = $init->json();
    //     $uploadUrl = $data['upload_url'];
    //     $uploadParams = $data['upload_parameters'];
    //     $mediaId = $data['media_id'];

    //     // B. File Bhejen
    //     $response = Http::asMultipart();
    //     foreach ($uploadParams as $key => $value) {
    //         $response->attach($key, $value);
    //     }
    //     $response->attach('file', file_get_contents($realPath), basename($realPath));

    //     $upload = $response->post($uploadUrl);

    //     if ($upload->failed()) {
    //         echo "\n[MEDIA UPLOAD ERROR]: " . $upload->body() . "\n";
    //         return null;
    //     }

    //     // C. Processing ka wait karein
    //     sleep(2);
    //     return $mediaId;
    // }
    // protected function uploadMedia($account, $imagePath)
    // {
    //     // 1. Init Upload
    //     $init = Http::withToken($account->access_token)->post("{$this->baseUrl}/media", [
    //         'media_type' => 'image'
    //     ]);

    //     if ($init->failed()) {
    //         echo "\n[Media Init Error]: " . $init->body();
    //         return null;
    //     }

    //     $data = $init->json();
    //     $uploadUrl = $data['upload_url'];
    //     $uploadParams = $data['upload_parameters'];
    //     $mediaId = $data['media_id'];

    //     // 2. Send File
    //     $realPath = Storage::disk('public')->path($imagePath);

    //     if (!file_exists($realPath)) {
    //         echo "\n[File Not Found]: $realPath";
    //         return null;
    //     }

    //     $response = Http::asMultipart();
    //     foreach ($uploadParams as $key => $value) {
    //         $response->attach($key, $value);
    //     }
    //     $response->attach('file', file_get_contents($realPath), basename($realPath));

    //     $upload = $response->post($uploadUrl);

    //     if ($upload->failed()) {
    //         echo "\n[Media Upload Error]: " . $upload->body();
    //         return null;
    //     }

    //     sleep(2); // Wait for processing
    //     return $mediaId;
    // }
}
