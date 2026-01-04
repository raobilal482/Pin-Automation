<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

    <div class="flex items-center mb-8">
        <a href="{{ route('boards') }}" class="mr-4 p-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition">
            ← Back
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Board Content</h1>
            <p class="text-sm text-gray-500">Board ID: {{ $boardId }}</p>
        </div>
    </div>

     <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
    @forelse($pins as $pin)
        <div class="break-inside-avoid bg-white rounded-xl shadow overflow-hidden group relative">
            
            @if(isset($pin['media']['images']['600x']['url']))
                <img src="{{ $pin['media']['images']['600x']['url'] }}" 
                     alt="{{ $pin['alt_text'] ?? $pin['title'] ?? 'Pin Image' }}" 
                     class="w-full object-cover">
            @else
                <div class="h-40 bg-gray-200 flex items-center justify-center text-gray-400">
                    No Image
                </div>
            @endif

            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition flex items-end justify-between p-4 opacity-0 group-hover:opacity-100">
                
                @if(!empty($pin['link']))
                    <a href="{{ $pin['link'] }}" target="_blank" class="text-white text-xs font-bold bg-red-600 px-3 py-1 rounded hover:bg-red-700 transition">
                        Visit Site ↗
                    </a>
                @else
                    <span class="text-white text-xs font-bold bg-black bg-opacity-50 px-2 py-1 rounded">
                        {{ \Carbon\Carbon::parse($pin['created_at'])->format('M d, Y') }}
                    </span>
                @endif
            </div>

            <div class="p-3">
                <p class="text-sm font-semibold text-gray-800 truncate">
                    {{ $pin['title'] ?? 'Untitled Pin' }}
                </p>
                
                <p class="text-xs text-gray-500 truncate mt-1">
                    {{ $pin['description'] ?? '' }}
                </p>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-10 text-gray-500">
            <p>No pins found in this board.</p>
        </div>
    @endforelse
</div>

</div>
