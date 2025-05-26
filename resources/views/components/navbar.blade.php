<nav aria-label="secondary" x-data="{ open: false }"
    class="sticky top-0 z-10 flex items-center justify-between px-4 py-4 transition-transform duration-500 shadow"
    :class="{
        '-translate-y-full': scrollingDown,
        'translate-y-0': scrollingUp,
    }">

    <div class="flex items-center gap-3">
        <button type="button" class="text-black" srText="Open main menu" @click="isSidebarOpen = !isSidebarOpen">
            <x-icons.menu x-show="!isSidebarOpen" aria-hidden="true" class="w-7 h-7" />
            <x-icons.x x-show="isSidebarOpen" aria-hidden="true" class="w-7 h-7" />
        </button>
    </div>

    <div class="flex items-center gap-4">
        <div class="md:flex hidden flex-wrap space-x-4 items-center">
            <button href="{{ route('front.index')}}" class="text-gray-800" >
                <i class="fa fa-eye w-6 h-6"></i>
            </button>
            <button type="button" class="text-gray-800"  id="fullScreen">
                <i class="fa fa-expand w-6 h-6"></i>
            </button>
        </div>

        <x-language-dropdown />

        <ul class="flex-col md:flex-row list-none items-center md:flex">
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    @click.outside="open = false"
                    type="button"
                    class="flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-300 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                    aria-haspopup="true"
                    :aria-expanded="open"
                >
                    <i class="fa fa-user mr-2"></i> <!-- Icon for the trigger button -->
                    {{ Auth::user()->first_name }}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-60 bg-white border border-gray-200 rounded-md shadow-lg z-10"
                >
                    <div class="py-1">
                        <a
                            href="{{ route('admin.settings') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150"
                        >
                            <i class="fa fa-cog mr-2"></i>
                            {{ __('Settings') }}
                        </a>
                        <div
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150"
                        >
                            <i class="fa fa-trash mr-2"></i>
                            @livewire('admin.cache')
                        </div>
                        <div class="border-t border-gray-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150"
                            >
                                <i class="fa fa-sign-out-alt mr-2"></i>
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </ul>


    </div>
</nav>


@push('scripts')
<script>

    function toggleFullscreen(elem) {
        elem = elem || document.documentElement;
        if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                } else if (elem.mozRequestFullScreen) {
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) {
                    elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        }
    }

    if(('#fullScreen').length > 0) {
       document.getElementById('fullScreen').addEventListener('click', function() {
           toggleFullscreen();
        });
    }

</script>
@endpush
