<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PinterestAccount;
use App\Services\PinterestService;

class BoardDetail extends Component
{
    public $boardId;
    public $pins = [];
    public $boardName = 'Board Details'; // Asal API m naam b fetch hoga

    public function mount($id, PinterestService $service)
    {
        $this->boardId = $id;
        $activeId = session('active_pinterest_account_id');
        // dd($activeId);
        if($activeId) {
            $account = PinterestAccount::find($activeId);
            // dd($account);
            $this->pins = $service->getPinsFromBoard($account, $id);
        }
    }

    public function render()
    {
        return view('livewire.board-detail')->layout('layouts.app');
    }
}
