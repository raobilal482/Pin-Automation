<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 0C5.373 0 0 5.373 0 12c0 4.99 3.063 9.248 7.396 11.137-.103-.948-.19-2.405.04-3.442.206-.928 1.328-5.63 1.328-5.63s-.338-.678-.338-1.68c0-1.574.912-2.75 2.048-2.75.966 0 1.432.725 1.432 1.594 0 .97-.617 2.422-.936 3.766-.267 1.126.564 2.044 1.674 2.044 2.01 0 3.555-2.12 3.555-5.18 0-2.71-1.948-4.604-4.726-4.604-3.442 0-5.46 2.582-5.46 5.25 0 1.04.4 2.155.9 2.76.098.12.112.226.083.348-.09.376-.293 1.19-.333 1.356-.053.218-.173.264-.4.16-1.492-.695-2.424-2.875-2.424-4.628 0-3.77 2.738-7.23 7.9-7.23 4.144 0 7.365 2.953 7.365 6.904 0 4.12-2.596 7.436-6.198 7.436-1.21 0-2.347-.63-2.736-1.373 0 0-.598 2.278-.743 2.836-.268 1.033-.993 2.308-1.48 3.09C9.186 23.81 10.553 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0z" />
                        </svg>
                        <span class="font-bold text-xl text-gray-800 tracking-tight">Pinterest<span
                                class="text-red-600">Auto</span></span>
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-3">
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>
    
    <x-nav-link :href="route('boards')" :active="request()->routeIs('boards')">
        {{ __('My Boards') }}
    </x-nav-link>
</div>
                <livewire:account-switcher />

                <div class="h-6 w-px bg-gray-300 mx-2"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
