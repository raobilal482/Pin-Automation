<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TimeSlot;
use App\Models\PinterestAccount;
use Illuminate\Support\Facades\Auth;

class ScheduleManager extends Component
{
    public $slots = [];

    // Form Inputs
    public $start_time;
    public $end_time;
    public $posts_count = 1;

    public $showForm = false; // Add Form dikhane/chupane k liye

    protected $rules = [
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'posts_count' => 'required|integer|min:1|max:20',
    ];

    public function render()
    {
        // Active Account ka ID session se uthao
        $activeAccountId = session('active_pinterest_account_id');

        if ($activeAccountId) {
            // Sirf active account k slots dikhao
            $this->slots = TimeSlot::where('pinterest_account_id', $activeAccountId)
                                   ->orderBy('start_time')
                                   ->get();
        } else {
            $this->slots = [];
        }

        return view('livewire.schedule-manager');
    }

    public function saveSlot()
    {
        $this->validate();

        $activeAccountId = session('active_pinterest_account_id');

        if (!$activeAccountId) {
            // Agar account select nahi h to error do
            session()->flash('error', 'Please select a Pinterest Account first!');
            return;
        }

        TimeSlot::create([
            'pinterest_account_id' => $activeAccountId,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'posts_count' => $this->posts_count,
        ]);

        $this->reset(['start_time', 'end_time', 'posts_count', 'showForm']);
        session()->flash('message', 'Time Slot Added!');
    }

    public function deleteSlot($id)
    {
        TimeSlot::find($id)->delete();
    }
}
