@php
/**
 * @name Textfield
 * @description A basic textfield, attributes are passed to the input element. Allows for a prefix and a suffix slot.
 */
@endphp

@props([
    /**
     * @param string theme The theme of the textbox. 'default'
     */
    'theme' => 'default'
])

@wireProps

<div
    {{ $attributes->root()->merge([ 'class' => 'sui-textbox' ]) }}
    >
    @isset($prefix)
        <div {{ $prefix->attributes->merge([ 'class' => 'sui-textbox__prefix' ])}}>
            {{ $prefix }}
        </div>
    @endisset

    <input {{ $attributes->namespace('input')->merge([ 
        'class' => implode(" ", [
            'sui-textbox__input',
            isset($prefix) ? '!pl-10' : '',
            isset($suffix) ? '!pr-10' : '',
        ]), 
        'type' => 'text' 
    ]) }} />

    @isset($suffix)
        <div {{ $suffix->attributes->merge([ 'class' => 'sui-textbox__suffix' ])}}>
            {{ $suffix }}
        </div>
    @endisset
</div>
