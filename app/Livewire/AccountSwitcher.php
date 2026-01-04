<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\PinterestAccount;

class AccountSwitcher extends Component
{
    public $accounts = [];
    public $activeAccount = null; // Yeh variable missing tha

    public function mount()
    {
        // 1. User ke saray accounts fetch karein
        $this->accounts = Auth::user()->pinterestAccounts;

        // 2. Check karein session mein koi account select hai?
        $activeId = session('active_pinterest_account_id');

        if ($activeId) {
            $this->activeAccount = $this->accounts->where('id', $activeId)->first();
        }

        // 3. Agar session khali hai, lekin accounts hain, to pehle walay ko auto-select karein
        if (!$this->activeAccount && $this->accounts->count() > 0) {
            $this->switchAccount($this->accounts->first()->id);
        }
    }

    public function switchAccount($accountId)
    {
        // Security check: Kya yeh account isi user ka hai?
        $account = Auth::user()->pinterestAccounts->where('id', $accountId)->first();

        if ($account) {
            session(['active_pinterest_account_id' => $account->id]);
            $this->activeAccount = $account;

            // Reload page taake Schedule list update ho jaye
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.account-switcher');
    }
}
