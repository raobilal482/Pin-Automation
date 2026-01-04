<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Services\GeminiService;
use App\Services\PinterestService;
use App\Enums\PostStatus; // Agar Enum use kar rahe hain

class RunPinterestAutomation extends Command
{
    protected $signature = 'automation:run';
    protected $description = 'Generate AI Content and Post Immediately';

    // public function handle(GeminiService $ai, PinterestService $pinterest)
    // {
    //     // Sirf Pending posts uthao
    //     $posts = Post::where('status', 'pending')->get();

    //     if ($posts->isEmpty()) {
    //         $this->info("No pending posts found to process.");
    //         return;
    //     }

    //     foreach ($posts as $post) {
    //         $this->info("------------------------------------------------");
    //         $this->info("Processing Image: {$post->original_filename}");

    //         // Step 1: Status Processing karo
    //         $post->update(['status' => 'processing']);

    //         // Step 2: AI se Content Likhwao
    //         $this->info("1. Generating AI Content...");
    //         $content = $ai->generatePinContent($post->keyword);

    //         if (!$content) {
    //             $this->error("AI Failed. Skipping.");
    //             $post->update(['status' => 'failed']);
    //             continue;
    //         }

    //         $this->info("AI Success! Title: " . $content['title']);

    //         // Step 3: Database mein Content Save karo
    //         $post->update([
    //             'title' => $content['title'],
    //             'description' => $content['description'],
    //         ]);

    //         // Step 4: Pinterest Board Dhoondo
    //         $this->info("2. Finding Pinterest Board...");
    //         $boards = $pinterest->getBoards($post->account);
    //         $boardId = $boards[0]['id'] ?? null;

    //         if (!$boardId) {
    //             $this->error("Error: No Boards found on Pinterest Account.");
    //             $post->update(['status' => 'failed']);
    //             continue;
    //         }

    //         // Step 5: FINAL UPLOAD (Directly)
    //         $this->info("3. Uploading to Pinterest (Board ID: $boardId)...");

    //         $result = $pinterest->createPin(
    //             $post->account,
    //             $post->title,
    //             $post->description,
    //             'https://yourwebsite.com',
    //             $post->image_path,
    //             $boardId
    //         );

    //         // Step 6: Success/Fail Check
    //         if ($result && isset($result['id'])) {
    //             $post->update([
    //                 'status' => 'published',
    //                 'pinterest_pin_id' => $result['id'],
    //                 'published_at' => now()
    //             ]);
    //             $this->info("SUCCESS! Pin Published. ID: " . $result['id']);
    //         } else {
    //             $post->update(['status' => 'failed']);
    //             $this->error("Upload Failed.");
    //         }

    //         $this->info("------------------------------------------------");
    //     }
    // }
    public function handle(GeminiService $ai, PinterestService $pinterest)
    {
        // ----------------------------------------------------
        // PART A: SIRF AI CONTENT GENERATE KARO (Post mat karo)
        // ----------------------------------------------------
        $pendingPosts = Post::where('status', 'pending')->take(5)->get();

        foreach ($pendingPosts as $post) {
            $this->info("Generating AI for: {$post->original_filename}");
            $post->update(['status' => 'processing']);
            
            $content = $ai->generatePinContent($post->keyword);

            if ($content) {
                // Yahan hum Status 'scheduled' kar rahe hain, lekin
                // kyunke 'scheduled_at' null hai, ye abhi post nahi hogi (Manual Wait)
                // Agar Bulk Scheduler se ayi hogi to usme 'scheduled_at' pehle se hoga.
                $post->update([
                    'title' => $content['title'],
                    'description' => $content['description'],
                    'status' => 'scheduled'
                ]);
                $this->info("Moved to Schedule Queue.");
            } else {
                $post->update(['status' => 'failed']);
            }
        }

        // ----------------------------------------------------
        // PART B: TIME CHECKER (Asal Scheduler Logic)
        // ----------------------------------------------------
        
        $postsToPublish = Post::where('status', 'scheduled')
            ->where(function ($query) {
                $query->whereNotNull('scheduled_at') // Time set hona zaroori hai
                      ->where('scheduled_at', '<=', now()); // Aur waqt ho chuka ho
            })
            ->get();

        if ($postsToPublish->isEmpty()) {
            $this->info("No posts ready due for this time slot.");
            return;
        }

        foreach ($postsToPublish as $post) {
            $this->info("Time Matched! Publishing: {$post->title}");
            
            // 1. Board ID Fetch
            $boards = $pinterest->getBoards($post->account);
            $boardId = $boards[0]['id'] ?? null;

            if (!$boardId) {
                $this->error("No Board Found.");
                continue;
            }

            // 2. Post to Pinterest
            $result = $pinterest->createPin(
                $post->account,
                $post->title,
                $post->description,
                'https://yourwebsite.com', 
                $post->image_path,
                $boardId
            );

            // 3. Update Status
            if ($result && isset($result['id'])) {
                $post->update([
                    'status' => 'published',
                    'pinterest_pin_id' => $result['id'],
                    'published_at' => now()
                ]);
                $this->info("SUCCESS! Published.");
            } else {
                $post->update(['status' => 'failed']);
            }
        }
    }
}
