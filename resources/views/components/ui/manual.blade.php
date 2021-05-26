@props([
    'codeView' => '',
    'componentView' => '',
    'id' => null,
    'name' => null,
    'description' => null,
    'collapseCode' => false,
    'padding' => true,
    'paddingClass' => 'bg-gray-50 p-12 rounded-md'
])

@php
    if($componentView) {
        $compContents = file_get_contents(view($componentView)->getPath());
        preg_match_all('/@param (?<type>.*?) (?<name>.*?) (?<description>.*)/', $compContents, $propertyInfo);
        preg_match_all('/@name (?<name>.*)/', $compContents, $nameInfo);
        preg_match_all('/@description (?<description>.*)/', $compContents, $descriptionInfo);

        $description = $description ?? ($descriptionInfo['description'][0] ?? '');
    }

    $id = $id ?? Illuminate\Support\Str::slug($name);

    if($codeView) {
        $contents = file_get_contents(view($codeView)->getPath());
        preg_match_all('/<x-senna\.ui\.manual.*?name=["|\']' . preg_quote($name, '/') . '["|\'].*?>([\s\S]*?)<\/x-senna\.ui\.manual>/', $contents, $contentMatches);
        $contents = $contentMatches[1] ? trim_tabs($contentMatches[1][0]) : '';
    }
@endphp

<div page-search="#{{ $id }}" page-search-name="{{ $name }}">
    <div {{ $attributes->merge(['class' => 'w-full border-t py-6']) }} x-data="{}" x-on:tab-visible="$dispatch('cm-refresh')">
        <a href="#{{ $id }}">
            <h4 class="mb-3" id="{{ $id }}">{{ $name }}</h4>
        </a>
        @if(isset($description))
        <p class="mb-3 opacity-60">
            {{ $description }}
        </p>
        @endif

        <div class="w-full mb-7">
            @if($padding)
            <div class="{{ $paddingClass }}">
                {{ $slot }}
            </div>
            @else
                {{ $slot }}
            @endif
        </div>

        @if(isset($contents))
        <div x-cloak x-data="{ open: false }" class="mb-6">
            <x-senna.button.primary size="sm" x-on:click="open = !open; $nextTick(() => $dispatch('cm-refresh'))">
                <div x-show="!open">Show code</div>
                <div x-show="open">Hide code</div>
            </x-senna.button.primary>
            <div x-show="open" class="mt-4">
                <x-senna.panel.window-dark class="mb-7">
                    <x-senna.input.codemirror :val="$contents" copyClass="-top-9 right-2" :showCopyButton="true"></x-senna.input.codemirror>
                </x-senna.panel.window-dark>
            </div>
        </div>
        @endif


        @if(isset($properties))
            {{ $properties }}
        @else
            @if($propertyInfo[0] ?? false)
            <div class="w-full overflow-y-auto">
            <x-senna.table class="w-full !whitespace-normal">
                <x-senna.table.header>
                    <x-senna.table.heading class="w-24" paddingClass="py-2 p-4">
                        Property
                    </x-senna.table.heading>
                    <x-senna.table.heading  class="w-24" paddingClass="py-2 p-4">
                        Type
                    </x-senna.table.heading>
                    <x-senna.table.heading paddingClass="py-2 p-4">
                        Description
                    </x-senna.table.heading>
                </x-senna.table.header>

                <x-senna.table.body>
                    @foreach($propertyInfo[0] as $key => $match)
                    <x-senna.table.row>
                        <x-senna.table.cell paddingClass="p-1.5 px-4">
                            {{ $propertyInfo['name'][$key] }}
                        </x-senna.table.cell>
                        <x-senna.table.cell paddingClass="p-1.5 px-4" class="opacity-50">
                            {{ $propertyInfo['type'][$key] }}
                        </x-senna.table.cell>
                        <x-senna.table.cell paddingClass="p-1.5 px-4">
                            {{ $propertyInfo['description'][$key] }}
                        </x-senna.table.cell>
                    </x-senna.table.row>
                    @endforeach
                </x-senna.table.body>
            </x-senna.table>
            </div>
            @endif
        @endif
    </div>
</div>
