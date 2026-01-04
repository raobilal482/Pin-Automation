<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Carbon\Carbon;
use App\Services\GeminiService;

class BulkScheduler extends Component
{
    public $scheduleDate;
    public $startTime;
    public $endTime;
    public $postCount = 3; // Default 3 posts
    
    public function mount()
    {
        // Default: Aaj ki date
        $this->scheduleDate = Carbon::now()->format('Y-m-d');
        $this->startTime = '10:00';
        $this->endTime = '11:00';
    }

    public function schedulePosts(GeminiService $ai)
    {
        // 1. Validation
        $this->validate([
            'postCount' => 'required|integer|min:1',
            'scheduleDate' => 'required|date',
            'startTime' => 'required',
            'endTime' => 'required|after:startTime',
        ]);

        // 2. Pending Images Uthana
        $posts = Post::where('status', 'pending')
                     ->inRandomOrder() // Random uthao taake mix content jaye
                     ->take($this->postCount)
                     ->get();

        if ($posts->count() < $this->postCount) {
            session()->flash('error', "Database mein sirf {$posts->count()} images hain. Please aur upload karein.");
            return;
        }

        // 3. Time Slot Calculation (Math Logic)
        $start = Carbon::parse("{$this->scheduleDate} {$this->startTime}");
        $end = Carbon::parse("{$this->scheduleDate} {$this->endTime}");
        
        // Total minutes dhoondo (e.g., 60 mins)
        $diffInMinutes = $start->diffInMinutes($end);
        
        // Interval nikalo (e.g., 60 / 3 = 20 mins gap)
        $interval = floor($diffInMinutes / $this->postCount);

        $currentTime = $start->copy();

        foreach ($posts as $index => $post) {
            
            // --- AI Content Generation (Yehi kar lete hain taake review k liye ready ho) ---
            $post->update(['status' => 'processing']);
            $content = $ai->generatePinContent($post->keyword);
            
            // Agar AI fail ho jaye to purana title hi rakh lo
            $title = $content ? $content['title'] : $post->original_filename;
            $desc = $content ? $content['description'] : '';

            // --- Scheduling ---
            $post->update([
                'title' => $title,
                'description' => $desc,
                'status' => 'scheduled', // Auto-Approve (Lekin future date hai to ruk jayega)
                'scheduled_at' => $currentTime->copy(), // Har post ka alag time
            ]);

            // Agle post ka time set karo
            $currentTime->addMinutes($interval);
        }

        session()->flash('message', "Success! {$this->postCount} Posts scheduled automatically.");
    }

    public function render()
    {
        return view('livewire.bulk-scheduler')->layout('layouts.app');
    }
}