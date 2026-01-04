<div class="relative" x-data="{ open: false }">

    <button @click="open = !open"
        class="flex items-center space-x-2 bg-gray-50 hover:bg-gray-100 px-3 py-2 rounded-lg transition border border-gray-200">
        @if($activeAccount)
            @if($activeAccount->avatar_url)
                <img src="{{ $activeAccount->avatar_url }}" class="w-8 h-8 rounded-full border border-gray-300">
            @else
                <div class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center font-bold">
                    {{ substr($activeAccount->nickname, 0, 1) }}
                </div>
            @endif

            <div class="text-left hidden md:block">
                <p class="text-sm font-semibold text-gray-700 leading-none">{{ $activeAccount->nickname }}</p>
                <p class="text-[10px] text-green-600 font-bold uppercase tracking-wide">Active</p>
            </div>
        @else
            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold">?</div>
            <div class="text-left hidden md:block">
                <p class="text-sm font-medium text-gray-500">No Account</p>
            </div>
        @endif

        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div x-show="open" @click.away="open = false"
        class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-100 z-50 py-1"
        style="display: none;">

        <div class="px-4 py-2 border-b border-gray-50 bg-gray-50">
            <span class="text-xs uppercase text-gray-400 font-bold tracking-wider">My Accounts</span>
        </div>

        @forelse($accounts as $account)
            <button wire:click="switchAccount({{ $account->id }})"
                class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 transition border-b border-gray-50 text-left">
                @if($account->avatar_url)
                    <img src="{{ $account->avatar_url }}" class="w-8 h-8 rounded-full">
                @else
                    <div
                        class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-xs font-bold">
                        {{ substr($account->nickname, 0, 1) }}
                    </div>
                @endif

                <div class="flex-1">
                    <p
                        class="text-sm font-medium text-gray-800 {{ $activeAccount && $activeAccount->id == $account->id ? 'text-red-600' : '' }}">
                        {{ $account->nickname }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $account->username ?? 'No username' }}</p>
                </div>

                @if($activeAccount && $activeAccount->id == $account->id)
                    <span class="text-green-500 text-xs font-bold">●</span>
                @endif
            </button>
        @empty
            <div class="px-4 py-3 text-sm text-gray-400 italic text-center">No accounts connected</div>
        @endforelse

        <a href="{{ route('pinterest.connect') }}"
            class="block text-center text-sm text-red-600 font-bold py-3 hover:bg-red-50 transition">
            + Connect New Pinterest
        </a>
    </div>
</div>
