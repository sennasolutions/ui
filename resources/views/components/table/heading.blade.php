@php
/**
 * @name Table heading
 * @description The table heading allows for displaying a sorting arrow. The sorting will not work here because there is no livewire component with live data.
 */
@endphp

@props([
    /**
     * @param string sortBy Livewire only. When clicking on the header it will call the 'sortBy' method with this property as it's value.
     */
    'sortBy' => null,
    /**
     * @param string sortDirection 'asc' or 'desc'. Displays an arrow.
     */
    'sortDirection' => null,
    // 'sortField' => null,
    // 'multiColumn' => null,
    /**
     * @param bool sticky Make this column sticky
     */
    'sticky' => false,
    /**
     * @param string paddingClass String of classes applied to the element. Default: px-6 py-2
     */
    'paddingClass' => 'px-6 py-2',
     /**
     * @param string stickyClass String of classes applied to the element when sticky. default: bg-gray-50
     */
    'stickyClass' => 'bg-gray-50'
])

@php
    // // $sortField = $sortField === $sortBy ? $sortDirection : null;
    $stickyProps = '';


    if ($sticky) {
        $stickyProps = "sticky !p-0 " . (is_string($sticky) ? $sticky : 'right-0');
    }
@endphp

<th {!! $sortBy ? "wire:click=\"sortBy('$sortBy')\"" : "" !!} {{ $attributes->merge(['class' => $paddingClass . ' text-gray-900 text-left text-[0.92rem] ' .  $stickyProps, 'scope' => 'col'])->only(['class','style']) }}>
    @if($sticky)
    <div class="{{ $paddingClass }} {{ $stickyClass }}">
    @endif
    @unless ($sortBy)
        <span {{ $attributes->except(['class','style']) }}>{{ $slot }} </span>
    @else
        <span class="flex space-x-1 cursor-pointer">
            <span {{ $attributes->except(['class','style']) }}>{{ $slot }}</span>

            <span class="relative flex items-center">
                @if ($sortDirection === 'asc')
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                @elseif ($sortDirection === 'desc')
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                @else
                    <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                @endif
            </span>
        </span>
    @endif
    @if($sticky)
    </div>
    @endif
</th>
