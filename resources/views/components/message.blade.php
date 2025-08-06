@props(['variant','label','class'])
@php
    $baseClasses = 'rounded-lg p-3 transition-colors my-1 font-medium select-none disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-dark-eval-2 '.@$class;
    switch (@$variant) {
        case 'primary':
            $variantClasses = 'bg-green-700 text-white hover:bg-green-900 focus:ring-green-700';
        break;
        case 'secondary':
            $variantClasses = 'bg-white text-gray-500 shadow hover:bg-gray-100 focus:ring-green-700 dark:text-gray-400 dark:bg-dark-eval-1 dark:hover:bg-dark-eval-2 dark:hover:text-gray-200';
        break;
        case 'success':
            $variantClasses = 'bg-green-500 text-white hover:bg-green-600 focus:ring-green-500';
        break;
        case 'danger':
            $variantClasses = 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500 animate-pulse';
        break;
        case 'warning':
            $variantClasses = 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-500';
        break;
        case 'info':
            $variantClasses = 'bg-cyan-500 text-white hover:bg-cyan-600 focus:ring-cyan-500';
        break;
        case 'black':
            $variantClasses = 'bg-black text-gray-300 hover:text-white hover:bg-gray-800 focus:ring-black dark:hover:bg-dark-eval-3';
        break;
        default:
            $variantClasses = 'bg-green-700 text-white hover:bg-green-900 focus:ring-green-700';
    }
    $classes = $baseClasses . ' ' . $variantClasses;
@endphp
<div {{ $attributes->merge(['class' => $classes]) }} >
    @if(isset($label) && $label)
        <div class="text-lg font-semibold">{{ $label }}</div>
    @endif
    <p>
        {!! $slot !!}
    </p>
</div>
