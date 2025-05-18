<div>

    <style>
        .main-color {
            color: #374151;
        }

        .main-color-hover:hover {
            color: white; /* Change this to the desired hover color for the main color */
        }

        .secondary-color {
            color: red;
        }

        .secondary-color-hover:hover {
            color: white; /* Change this to the desired hover color for the secondary color */
        }
        .main-color-bg {
            background-color: #374151;
        }
        .main-color-bg-hover:hover {
            background-color: #374151; /* Change this to the desired hover color for the main color */
            color: white;
        }
        .icon-hover:hover {
            color: #374151; /* Change this to the desired hover color for the main color */
        }


        .box-button {
            color: #374151;
        }

        .box-button:hover {
            background-color: #374151;
            color: white;
        }

        .box-button:hover .icon-svg {
            stroke: white !important;;
        }

        .icon-svg {
            fill: #374151;
        }

        .icon-color {
            color: #374151;
        }

        /* Sidebar scrollbar styles */
        #sidebar {
            scrollbar-width: thin;
            scrollbar-color: #374151 transparent;
        }

        #sidebar::-webkit-scrollbar {
            width: 4px;
        }

        #sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background-color: #374151;
            border-radius: 2px;
        }

    </style>

    <div class="h-full">
        <!-- Sidebar backdrop (mobile only) -->
        <div class="fixed inset-0 bg-slate-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
            :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'"
            aria-hidden="true"
            x-cloak
        ></div>

        <!-- Sidebar -->
        <div class="h-full overflow-y-auto lg:translate-x-0 mt-10"
            :class="{'translate-x-0': sidebarOpen, '-translate-x-56': !sidebarOpen}"
            @click.outside="sidebarOpen = false"
            @keydown.escape.window="sidebarOpen = false"
            x-data="{ sidebarOpen: true }"
            x-cloak="lg"
        >
            <!-- Navigation -->
            <nav id="sidebar" class="px-2 py-3 space-y-0.5">
                @foreach($this->menuItems as $item)   
                <li wire:click="menuItemClicked({{$item['id']}})" class="group flex items-center px-2.5 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200
                    @if($this->tab_id == $item['id']) 
                        main-color-bg text-white border-l-4 border-[#374151] font-semibold
                    @else 
                        text-gray-600 hover:bg-gray-50 hover:text-[#374151] border-l-4 border-transparent
                    @endif">
                    <div class="flex items-center w-full">
                        <div wire:loading wire:target="menuItemClicked({{$item['id']}})">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="gray" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4 animate-spin">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                            </svg>
                        </div>
                        <div wire:loading.remove wire:target="menuItemClicked({{$item['id']}})">
                            @if($item['id'] == 1)
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2.5 h-4 w-4 @if($this->tab_id == $item['id']) text-white @else text-gray-400 group-hover:text-[#374151] @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                            @endif

                            @if($item['id'] == 2)
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2.5 h-4 w-4 @if($this->tab_id == $item['id']) text-white @else text-gray-400 group-hover:text-[#374151] @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @endif

                            @if($item['id'] == 3)
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2.5 h-4 w-4 @if($this->tab_id == $item['id']) text-white @else text-gray-400 group-hover:text-[#374151] @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z"></path>
                            </svg>
                            @endif
                        </div>
                        <span>{{ $item['menu_name'] }}</span>
                    </div>
                </li>
                @endforeach
            </nav>
        </div>
    </div>

</div>
