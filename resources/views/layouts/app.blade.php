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
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- apexcharts -->
        {{--<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>--}}
        <script src="{{ asset('js/lib/apexcharts.js') }}"></script> 

        {{--<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>--}}

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-50">
            <!-- Top Navigation -->            
            <div class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 h-14">
                <livewire:navigation-menu />
            </div>
            
            <div class="flex min-h-screen pt-14"> <!-- Add padding-top equal to navbar height -->
                <!-- Sidebar -->
                <div class="fixed left-0 top-14 bottom-0 w-56 bg-white border-r border-gray-200 shadow-sm">
                    <livewire:side-bar />
                </div>
            
                <!-- Main Content -->
                <div class="flex-1 ml-56">
                    @if (isset($header))
                        <header class="bg-white border-b border-gray-200">
                            <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif
            
                    <main class="p-4 sm:p-6">
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
