@props(['disabled' => false, 'type' => 'text', 'errors', 'label' => false])

@php

    $defaultClasses =
        'border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 dark:border-gray-600';
    $errorClasses = 'border-red-600 focus:border-red-600 ring-1 ring-red-600 focus:ring-red-600';
    $successClasses = 'border-emerald-500 focus:border-emerald-500 ring-1 ring-emerald-500 focus:ring-emerald-500';
    $attributeName = preg_replace('/(\w+)\[(\w+)]/', '$1.$2', $attributes['name']);
@endphp
{{-- @dump($attributeName) --}}
<div>
    @if ($label)
        <label>{{ $label }}</label>
    @endif

    @if ($type === 'select')
        <select @disabled($disabled) {!! $attributes->merge([
            'class' =>
                'rounded-md w-full ' .
                ($errors->has($attributeName) ? $errorClasses : (old($attributeName) ? $successClasses : $defaultClasses)),
        ]) !!}>
            {{ $slot }}
        </select>
    @else
        <input @disabled($disabled) {!! $attributes->merge([
            'class' =>
                'w-full rounded-md ' .
                ($errors->has($attributeName) ? $errorClasses : (old($attributeName) ? $successClasses : $defaultClasses)),
        ]) !!} type="{{ $type }}">
    @endif
    {{-- @error($attributeName)
        <small class="text-red-600"> {{ $message }}</small>
    @enderror --}}
</div>
