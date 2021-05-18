@php
/**
 * @name Textfield
 * @description Icons from heroicons. https://heroicons.com/
 */
@endphp

@props([
    /**
     * @param string name The identifier of the icon. Prefix with an 'h' for heroicons. 'hs-home' is a solid heroicon home icon. 'ho-home' is an outlined heroicon home icon.
     */
    'name' => ''
])

@php
    $isHeroicon = str_contains($name, "ho-") || str_contains($name, "hs-");

    $component = "senna.icon." . $name;

    if ($isHeroicon) {
        $outline = str_contains($name, "ho-");
        $icon = str_replace("hs-", "", $name);
        $icon = str_replace("ho-", "", $icon);

        $component = "heroicon-" . ($outline ? 'o' : 's') . "-" . $icon;
    }
@endphp

<x-dynamic-component :component="$component" {{ $attributes }}></x-dynamic-component>
