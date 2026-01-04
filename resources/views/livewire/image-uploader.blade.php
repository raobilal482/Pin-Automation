<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Upload New Images</h3>

        @if (session()->has('message'))
            <div class="mb-4 text-sm text-green-600 font-bold bg-green-50 p-2 rounded">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-4 text-sm text-red-600 font-bold bg-red-50 p-2 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="save">
            
            <div 
                x-data="{ isUploading: false, progress: 0 }"
                x-on:livewire-upload-start="isUploading = true"
                x-on:livewire-upload-finish="isUploading = false"
                x-on:livewire-upload-error="isUploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress"
                class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-50 transition relative group"
            >
                <div x-show="isUploading" class="absolute top-0 left-0 w-full h-1 bg-gray-200 rounded-t-xl overflow-hidden">
                    <div class="h-full bg-red-600 transition-all duration-200" :style="'width: ' + progress + '%'"></div>
                </div>

                <input type="file" wire:model="photos" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                
                @if ($photos)
                    <div class="grid grid-cols-4 gap-4">
                        @foreach ($photos as $photo)
                            <div class="relative">
                                <img src="{{ $photo->temporaryUrl() }}" class="h-20 w-full object-cover rounded-lg border border-gray-200">
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-4 text-sm text-gray-600 font-bold">{{ count($photos) }} images selected</p>
                @else
                    <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-red-500 transition" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <p class="mt-1 text-sm text-gray-600">
                        <span class="font-medium text-red-600">Click to Upload</span> or drag and drop
                    </p>
                    <p class="mt-1 text-xs text-gray-500">PNG, JPG up to 10MB</p>
                @endif
            </div>

            @if ($photos)
                <div class="mt-4 text-right">
                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition font-medium shadow" wire:loading.attr="disabled">
                        <span wire:loading.remove>Save Images</span>
                        <span wire:loading>Processing...</span>
                    </button>
                </div>
            @endif

            @error('photos.*') <span class="text-red-500 text-xs block mt-2">{{ $message }}</span> @enderror
        </form>
    </div>
</div>