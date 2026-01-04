<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PinterestAccount;
use App\Services\PinterestService;
use Illuminate\Support\Facades\Auth;

class BoardManager extends Component
{
    public $boards = [];

    // Create Board Form
    public $newBoardName = '';
    public $newBoardDesc = '';
    public $showCreateModal = false;

    public function mount(PinterestService $service)
    {
        $this->fetchBoards($service);
    }

    public function fetchBoards(PinterestService $service)
    {
        $activeId = session('active_pinterest_account_id');
        if($activeId) {
            $account = PinterestAccount::find($activeId);
            $this->boards = $service->getBoards($account);
        }
    }

    public function createBoard(PinterestService $service)
    {
        $this->validate(['newBoardName' => 'required|min:3']);

        $activeId = session('active_pinterest_account_id');
        $account = PinterestAccount::find($activeId);

        $service->createBoard($account, $this->newBoardName, $this->newBoardDesc);

        $this->newBoardName = '';
        $this->newBoardDesc = '';
        $this->showCreateModal = false;

        // Refresh list (Asal API m yaha dobara fetch hoga, dummy m hum push kr dete hain)
        $this->boards[] = ['id' => rand(99,999), 'name' => $this->newBoardName, 'pin_count' => 0, 'image' => 'https://via.placeholder.com/150'];

        session()->flash('message', 'Board Created Successfully!');
    }

    public function render()
    {
        return view('livewire.board-manager')->layout('layouts.app');
    }
}
