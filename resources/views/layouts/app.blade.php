<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts/dist/apexcharts.css">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- apexcharts -->
        {{--<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>--}}
        <script src="{{ asset('js/lib/apexcharts.js') }}"></script> 

        {{--<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>--}}

        <!-- Styles -->
        @livewireStyles

        <style>
            :root {
                /* --nbc-primary: #003366; */
                --nbc-primary: #2D3A89;
                --nbc-secondary: #E11E1E;
                --nbc-accent: #ff9900;
                --nbc-light: #f5f8fa;
                --nbc-dark: #1a1a1a;
                --sidebar-width: 280px;
                --header-height: 70px;
            }
            .bg-nbc-primary { background-color: var(--nbc-primary); }
            .bg-nbc-secondary { background-color: var(--nbc-secondary); }
            .bg-nbc-accent { background-color: var(--nbc-accent); }
            .bg-nbc-light { background-color: var(--nbc-light); }
            .text-nbc-primary { color: var(--nbc-primary); }
            .text-nbc-secondary { color: var(--nbc-secondary); }
            .text-nbc-accent { color: var(--nbc-accent); }
            .border-nbc-primary { border-color: var(--nbc-primary); }
            .hover\:bg-nbc-secondary:hover { background-color: var(--nbc-secondary); }

            /* Custom Scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }
            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }
            ::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 4px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-nbc-light">
        <x-banner />

        <div class="min-h-screen">
            <!-- Top Navigation -->            
            <div class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 shadow-sm" style="height: var(--header-height);">
                <livewire:navigation-menu />
            </div>
            
            <div class="flex min-h-screen" style="padding-top: var(--header-height);">
                <!-- Sidebar -->
                <div class="fixed left-0 top-[var(--header-height)] bottom-0 w-[var(--sidebar-width)] bg-white border-r border-gray-200 shadow-lg transition-all duration-300 ease-in-out">
                    <livewire:side-bar />
                </div>
            
                <!-- Main Content -->
                <div class="flex-1 ml-[var(--sidebar-width)] transition-all duration-300 ease-in-out">
                    @if (isset($header))
                        <header class="bg-white border-b border-gray-200 shadow-sm">
                            <div class="w-full mx-auto py-6 px-6 sm:px-8 lg:px-10">
                                {{ $header }}
                            </div>
                        </header>
                    @endif
            
                    <main class="p-6 sm:p-8">
                        <div class="flex-1 max-w-7xl mx-auto">
                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>
        </div>

        @stack('modals')
        @livewireScripts
    </body>
</html>
