<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; // Livewire 3 ka event listener
use App\Models\Post;
use Illuminate\Support\Facades\Session;

class PostList extends Component
{
    // Yeh function tab chalega jab ImageUploader bolega "post-uploaded"
    #[On('post-uploaded')]
    public function refreshList()
    {
        // Sirf render dubara chalega, naya data fetch ho jayega
    }
public function stopPost($id)
    {
        $post = \App\Models\Post::find($id);
        if ($post) {
            $post->update([
                'status' => 'pending', // Wapis pending mein daal do
                'scheduled_at' => null
            ]);
            session()->flash('message', 'Post stopped and moved back to Pending.');
        }
    }
    public function render()
    {
        $posts = [];
        $activeAccountId = session('active_pinterest_account_id');

        if ($activeAccountId) {
            // Active account ki posts fetch karein (Newest pehle)
            $posts = Post::where('pinterest_account_id', $activeAccountId)
                         ->orderBy('created_at', 'desc')
                         ->get();
        }

        return view('livewire.post-list', [
            'posts' => $posts
        ]);
    }
}
