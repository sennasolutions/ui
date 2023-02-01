@php
/**
 * @name Textfield
 * @description A basic textfield, attributes are passed to the input element. Allows for a prefix and a suffix slot.
 */
@endphp

@props([

])

@wireProps

<div
    senna="form.textbox"
    {{ $attributes->root()->merge([ 'class' => 'textbox' ]) }}
    >
    @isset($prefix)
        <div {{ $prefix->attributes->merge([ 'class' => 'textbox__prefix' ])}}>
            {{ $prefix }}
        </div>
    @endisset

    <input {{ $attributes->namespace('input')->merge([ 
        'class' => implode(" ", [
            'textbox__input',
            isset($prefix) ? '!pl-10' : '',
            isset($suffix) ? '!pr-10' : '',
        ]), 
        'type' => 'text' 
    ]) }} />

    @isset($suffix)
        <div {{ $suffix->attributes->merge([ 'class' => 'textbox__suffix' ])}}>
            {{ $suffix }}
        </div>
    @endisset
</div>
