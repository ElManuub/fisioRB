@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block bg-sky-150 w-full ps-3 pe-4 border-l-4 border-indigo-400 text-start text-base font-medium text-indigo-700 focus:outline-none focus:text-indigo-800 focus:bg-sky-100 focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-80 hover:border-gray-500 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
