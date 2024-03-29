@props([
    'align' => 'right',
    'dropdownClass' => '',
    'open' => false,
    'closeOnInnerclick' => true
])

@php  
switch ($align) {
    case 'left':
        $alignmentClasses = 'origin-top-left left-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top bottom-full right-0';
        break;
    case 'top-left':
        $alignmentClasses = 'origin-top-left bottom-full left-0';
        break;
    case 'none':
    case 'false':
        $alignmentClasses = '';
        break;
    case 'right':
    default:
        $alignmentClasses = 'origin-top-right right-0';
        break;
}

@endphp

<div data-sn='dropdown' {{ $attributes->merge(['class' => 'relative']) }} x-data="{ open: {{ $open ? 'true' : 'false' }} }" @click.away="open = false" @close.stop="open = false">
    <div @click="open = ! open" {{ $trigger->attributes }}>
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            {{ $attributes->namespace('dropdown')->merge(['class' => "absolute z-50 mt-2 rounded-md w-48 $alignmentClasses"]) }}
            style="display: none;"
            @if($closeOnInnerclick)
            @click="open = false"
            @endif
    >
        <div {{ $content->attributes->merge(['class' => 'rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white' ]) }}>
            {{ $content }}
        </div>
    </div>
</div>
