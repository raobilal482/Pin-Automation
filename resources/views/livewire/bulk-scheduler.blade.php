<div class="max-w-4xl mx-auto py-12 px-4">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Auto-Schedule Manager</h2>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Select Date</label>
                <input type="date" wire:model="scheduleDate" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">How many posts?</label>
                <input type="number" wire:model="postCount" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Start Time</label>
                <input type="time" wire:model="startTime" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">End Time</label>
                <input type="time" wire:model="endTime" class="w-full border rounded p-2">
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <div wire:loading wire:target="schedulePosts" class="mr-4 text-indigo-600 font-bold items-center">
                Processing AI & Scheduling... (Please wait)
            </div>

            <button wire:click="schedulePosts" wire:loading.attr="disabled"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow">
                Generate & Schedule
            </button>
        </div>
    </div>
</div>