@props(['disabled' => false, 'errors', 'label' => false])

@php

    $errorClasses = 'border-red-600 focus:border-red-600 ring-1 ring-red-600 focus:ring-red-600';
    $defaultClasses = '';
    $successClasses = 'border-emerald-500 focus:border-emerald-500 ring-1 ring-emerald-500 focus:ring-emerald-500';
@endphp

@if ($label)
    <label>{{ $label }}</label>
@endif

<input @disabled($disabled)
    {{ $attributes->merge([
        'class' =>
            'w-full rounded-md border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 dark:border-purple-600 ' .
            ($errors->has($attributes['name'])
                ? $errorClasses
                : (old($attributes['name'])
                    ? $successClasses
                    : $defaultClasses)),
    ]) }}>
