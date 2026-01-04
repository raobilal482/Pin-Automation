<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class PostReview extends Component
{
    public Post $post;

    // Form Fields (Editable)
    public $title;
    public $description;

    public function mount($id)
    {
        $this->post = Post::findOrFail($id);
        $this->title = $this->post->title;
        $this->description = $this->post->description;
    }

    public function approve()
    {
        // User changes save karein aur status 'scheduled' kar dein
        $this->post->update([
            'title' => $this->title,
            'description' => $this->description,
            'status' => 'scheduled' // Ab Automation isay utha k post kr degi
        ]);

        return redirect()->route('dashboard')->with('message', 'Post Approved & Scheduled!');
    }

    public function deletePost()
    {
        $this->post->delete();
        return redirect()->route('dashboard')->with('error', 'Post Deleted.');
    }

    public function render()
    {
        return view('livewire.post-review')->layout('layouts.app');
    }
}
