@php
    $classes = match(strtolower($status)) {
        'active' => 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800',
        'blocked' => 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800',
        'deleted' => 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800',
        'pending' => 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800',
        'inactive' => 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800',
        default => 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800'
    };

    $icon = match(strtolower($status)) {
        'active' => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        'blocked', 'inactive' => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
        'pending' => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        default => ''
    };
@endphp

<span class="{{ $classes }}">
    {!! $icon !!}
    {{ ucfirst($status) }}
</span> 