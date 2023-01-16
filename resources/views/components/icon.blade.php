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
    // ray($name);
    $isHeroicon = str_contains($name, "ho-") || str_contains($name, "hs-");
    $isFontAwesome = str_contains($name, "fas-");
    $isSenna = str_contains($name, "senna-");

    $component = $name;

    if ($isHeroicon) {
        $outline = str_contains($name, "ho-");
        $icon = str_replace("hs-", "", $name);
        $icon = str_replace("ho-", "", $icon);

        $component = "heroicon-" . ($outline ? 'o' : 's') . "-" . $icon;
    }

    if ($isSenna) {
        $name = str_replace("senna-", "", $name);

        $component = "senna.icon." . $name;
    }

    $component = str_replace("s-chat-alt", "s-chat-bubble-oval-left-ellipsis", $component);
    $component = str_replace("s-music-note", "s-musical-note", $component);
    $component = str_replace("o-download", "o-arrow-down-tray", $component);
@endphp

@if($isSenna) 
    <x-dynamic-component data-sn='icon' :component="$component" {{ $attributes }}></x-dynamic-component>
@else
    @svg($component, $attributes->get('class'))
@endif
