<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2">

            <div class="p-6 bg-gray-50 flex items-center justify-center">
                <img src="{{ asset('storage/' . $post->image_path) }}" class="max-h-[500px] rounded-lg shadow-lg">
            </div>

            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Review AI Content</h2>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Pin Title</label>
                    <input type="text" wire:model="title"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <textarea wire:model="description" rows="6"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Hashtags included by AI.</p>
                </div>

                <div class="flex items-center justify-between mt-8">
                    <button wire:click="deletePost" onclick="return confirm('Are you sure?')"
                        class="text-red-600 hover:text-red-800 font-bold text-sm">
                        Delete Post
                    </button>

                    <button wire:click="approve"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow transition transform hover:scale-105">
                        Approve & Schedule
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
