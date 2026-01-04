<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">My Pinterest Boards</h2>
        <button wire:click="$set('showCreateModal', true)" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow">
            + Create New Board
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($boards as $board)
        {{-- @dd($board) --}}
            <a href="{{ route('board.detail', $board['id']) }}" class="block group">
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden border border-gray-100">
                    <div class="h-40 bg-gray-200 w-full overflow-hidden">
                        @if(isset($board['media']['image_cover_url']))
                            <img src="{{ $board['media']['image_cover_url'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                         <img src="https://placehold.co/400" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-800 truncate">{{ $board['name'] }}</h3>
                        <p class="text-xs text-gray-500">{{ $board['pin_count'] }} Pins</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-4 text-center py-10 text-gray-500">
                No boards found. Create one!
            </div>
        @endforelse
    </div>

    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg w-full max-w-md p-6 shadow-xl">
                <h3 class="text-lg font-bold mb-4">Create New Board</h3>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Board Name</label>
                    <input type="text" wire:model="newBoardName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    @error('newBoardName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                    <textarea wire:model="newBoardDesc" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('showCreateModal', false)" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">Cancel</button>
                    <button wire:click="createBoard" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Create</button>
                </div>
            </div>
        </div>
    @endif
</div>
