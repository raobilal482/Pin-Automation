<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-fit sticky top-6">
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Active Schedule</h3>

            @if (session()->has('message'))
                <span class="text-xs text-green-600 font-bold fade-out">{{ session('message') }}</span>
            @endif
            @if (session()->has('error'))
                <span class="text-xs text-red-600 font-bold">{{ session('error') }}</span>
            @endif
        </div>

        <div class="space-y-3">
            @forelse($slots as $slot)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-100 group">
                    <div>
                        <div class="text-sm font-bold text-gray-700">
                            {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} -
                            {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                        </div>
                        <div class="text-xs text-gray-500">{{ $slot->posts_count }} Posts (Randomized)</div>
                    </div>

                    <button wire:click="deleteSlot({{ $slot->id }})"
                        class="text-gray-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </div>
            @empty
                <div class="text-center text-gray-400 text-sm py-4">
                    No time slots set.<br> Automation is paused.
                </div>
            @endforelse
        </div>

        @if($showForm)
            <div class="mt-4 p-4 bg-gray-100 rounded-lg border border-gray-200">
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-bold text-gray-600">Start Time</label>
                        <input type="time" wire:model="start_time"
                            class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        @error('start_time') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-600">End Time</label>
                        <input type="time" wire:model="end_time"
                            class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        @error('end_time') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-600">Posts Count</label>
                        <input type="number" wire:model="posts_count" min="1" max="20"
                            class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    </div>

                    <div class="flex space-x-2 pt-2">
                        <button wire:click="saveSlot"
                            class="flex-1 bg-gray-900 text-white text-xs py-2 rounded hover:bg-black transition">Save</button>
                        <button wire:click="$set('showForm', false)"
                            class="px-3 bg-gray-300 text-gray-700 text-xs py-2 rounded hover:bg-gray-400">Cancel</button>
                    </div>
                </div>
            </div>
        @else
            <button wire:click="$set('showForm', true)"
                class="mt-6 w-full bg-red-600 text-white py-2 rounded-lg text-sm hover:bg-red-700 transition font-medium shadow-sm">
                + Add Time Slot
            </button>
        @endif
    </div>
</div>
