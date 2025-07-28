@props(['messages'])

@if ($messages)
    <ul
        {{ $attributes->merge(['class' => 'text-sm bg-red-600 dark:bg-red-400 text-gray-200 dark:text-gray-600 space-y-1 px-2 py-3 rounded']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
