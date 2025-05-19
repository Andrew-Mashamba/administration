<div>

    <style>
        .main-color {
            color: var(--nbc-primary);
        }

        .main-color-hover:hover {
            color: white;
        }

        .secondary-color {
            color: var(--nbc-accent);
        }

        .secondary-color-hover:hover {
            color: white;
        }
        .main-color-bg {
            background-color: var(--nbc-primary);
        }
        .main-color-bg-hover:hover {
            background-color: var(--nbc-secondary);
            color: white;
        }
        .icon-hover:hover {
            color: var(--nbc-primary);
        }

        .box-button {
            color: var(--nbc-primary);
        }

        .box-button:hover {
            background-color: var(--nbc-primary);
            color: white;
        }

        .box-button:hover .icon-svg {
            stroke: white !important;
        }

        .icon-svg {
            fill: var(--nbc-primary);
        }

        .icon-color {
            color: var(--nbc-primary);
        }

        /* Sidebar scrollbar styles */
        #sidebar {
            scrollbar-width: thin;
            scrollbar-color: var(--nbc-primary) transparent;
        }

        #sidebar::-webkit-scrollbar {
            width: 4px;
        }

        #sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background-color: var(--nbc-primary);
            border-radius: 2px;
        }

    </style>

    <div class="h-full">
   

        <!-- Sidebar -->
        <div class="h-full overflow-y-auto lg:translate-x-0 mt-10"
        >
            <!-- Navigation -->
            <nav id="sidebar" class="px-2 py-3 space-y-0.5">
                @foreach($this->menuItems as $item)   
                <li wire:click="menuItemClicked({{$item['id']}})" class="group flex items-center px-2.5 py-2 text-sm font-medium rounded-md cursor-pointer transition-all duration-200
                    @if($this->tab_id == $item['id']) 
                        bg-nbc-primary text-white border-l-4 border-nbc-accent font-semibold
                    @else 
                        text-nbc-primary hover:bg-nbc-light hover:text-nbc-secondary border-l-4 border-transparent
                    @endif">
                    <div class="flex items-center w-full">
                        <div wire:loading wire:target="menuItemClicked({{$item['id']}})">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4 animate-spin">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                            </svg>
                        </div>
                        <div wire:loading.remove wire:target="menuItemClicked({{$item['id']}})">
                            @if($item['id'] == 1)
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2.5 h-4 w-4 @if($this->tab_id == $item['id']) text-white @else text-nbc-primary group-hover:text-nbc-secondary @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                            @endif

                            @if($item['id'] == 2)
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2.5 h-4 w-4 @if($this->tab_id == $item['id']) text-white @else text-nbc-primary group-hover:text-nbc-secondary @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @endif

                            @if($item['id'] == 3)
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2.5 h-4 w-4 @if($this->tab_id == $item['id']) text-white @else text-nbc-primary group-hover:text-nbc-secondary @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"></path>
                            </svg>
                            @endif

                            @if($item['id'] == 4)
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2.5 h-4 w-4 @if($this->tab_id == $item['id']) text-white @else text-nbc-primary group-hover:text-nbc-secondary @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"></path>
                            </svg>
                            @endif

                            @if($item['id'] == 5)
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2.5 h-4 w-4 @if($this->tab_id == $item['id']) text-white @else text-nbc-primary group-hover:text-nbc-secondary @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
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
