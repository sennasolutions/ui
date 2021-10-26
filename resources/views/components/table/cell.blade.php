@php
/**
 * @name Table cell
 * @description A table cell
 */
@endphp

@props([
    'tag' => 'td',
    /**
     * @param bool sticky Make this cell sticky
     */
    'sticky' => false,
    /**
     * @param string paddingClass String of classes applied to the element
     */
    'paddingClass' => 'px-6 py-4',
     /**
     * @param string stickyClass String of classes applied to the element when sticky
     */
    'stickyClass' => 'bg-white relative',
    'isSelected' => false
])

@php
    if($isSelected) {
        $stickyClass .= ' bg-selected';
    }
@endphp

@if(!$sticky)
<{{ $tag }} {{ $attributes->merge(['class' => $paddingClass]) }}>
    {{ $slot }}
</{{ $tag }}>
@else
@php
    $sticky = is_string($sticky) ? $sticky : 'right-0';
@endphp
<{{ $tag }} {{ $attributes->merge(['class' => $sticky . ' bg-white sticky p-0']) }}>
    <div class="{{ $paddingClass }} {{ $stickyClass }}">
        {{ $slot }}
    </div>
</{{ $tag }}>
@endif
