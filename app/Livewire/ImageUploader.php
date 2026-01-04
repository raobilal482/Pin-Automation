<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; // File upload k liye zaroori
use App\Models\Post;
use Illuminate\Support\Str;

class ImageUploader extends Component
{
    use WithFileUploads;

    public $photos = []; // Multiple files yahan ayengi

    // Validation rules
    protected $rules = [
        'photos.*' => 'image|max:10240', // Max 10MB per image
    ];

    public function updatedPhotos()
    {
        $this->validate();
    }

    public function save()
    {
        $this->validate();

        $activeAccountId = session('active_pinterest_account_id');

        if (!$activeAccountId) {
            session()->flash('error', 'Please select a Pinterest Account first!');
            return;
        }

        foreach ($this->photos as $photo) {
            // 1. Image ko 'public/uploads' folder mein save karein
            $path = $photo->store('uploads', 'public');

            // 2. Keyword Extraction Logic (Filename se)
            // Example: 'bedroom_decor_ideas.jpg' -> 'Bedroom Decor Ideas'
            $filenameWithExt = $photo->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            
            $keyword = Str::of($filename)
                        ->replace(['-', '_'], ' ') // Underscore/Dash hatao
                        ->title(); // Capitalize karo

            // 3. Database mein entry
            Post::create([
                'pinterest_account_id' => $activeAccountId,
                'image_path' => $path,
                'original_filename' => $filenameWithExt,
                'keyword' => $keyword, // AI isay use karega
                'status' => 'pending'
            ]);
        }

        // Cleanup & Success Message
        $this->reset('photos');
        session()->flash('message', 'Images uploaded successfully!');
        
        // Queue List ko refresh karne k liye event bhejen (Hum ye aglay step m banayenge)
        $this->dispatch('post-uploaded'); 
    }

    public function render()
    {
        return view('livewire.image-uploader');
    }
}