<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Queue & History</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Keyword (File Name)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Scheduled Time</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($posts as $post)
                                                <tr class="hover:bg-gray-50 transition">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="h-12 w-12 flex-shrink-0">
                                                                <img class="h-12 w-12 rounded object-cover border border-gray-200"
                                                                    src="{{ asset('storage/' . $post->image_path) }}" alt="">
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $post->keyword }}</div>
                                                        <div class="text-xs text-gray-500">{{ $post->original_filename }}</div>
                                                    </td>

                                                    {{-- <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($post->status === 'waiting_review')
                                                            <a href="{{ route('post.review', $post->id) }}"
                                                                class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                                                                Review Now
                                                            </a>

                                                        @elseif($post->status === 'published')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Published
                                                            </span>
                                                        @else
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                {{ ucfirst($post->status) }}
                                                            </span>
                                                        @endif
                                                    </td> --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($post->status == 'scheduled')
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-blue-600 mb-1">
                                        {{ $post->scheduled_at ? $post->scheduled_at->format('M d, h:i A') : 'Ready to Publish' }}
                                    </span>

                                    <div class="flex space-x-2">
                                        <button wire:click="stopPost({{ $post->id }})"
                                            class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs hover:bg-red-200">
                                            Stop ✋
                                        </button>

                                        <a href="{{ route('post.review', $post->id) }}"
                                            class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs hover:bg-gray-200">
                                            Edit ✏️
                                        </a>
                                    </div>
                                </div>
                            @elseif($post->status == 'published')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Published
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ ucfirst($post->status) }}
                                </span>
                            @endif
                        </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        @if($post->scheduled_at)
                                                            {{ $post->scheduled_at->format('M d, h:i A') }}
                                                        @else
                                                            <span class="text-gray-400 italic">Not scheduled yet</span>
                                                        @endif
                                                    </td>
                                                </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <p class="mt-2 text-sm font-medium">No images found.</p>
                                <p class="text-xs">Upload some images to get started.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
