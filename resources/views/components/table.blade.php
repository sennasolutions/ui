@props([
    'data' => [], // [{prop: 1}, {prop: 2}]
    'rotate' => false,
    'whitelist' => null,
    'blacklist' => null,
    'html' => false,
    'cols' => []
])

@if(!$data)
    <table data-sn='table' {{ $attributes->merge(['class' => 'whitespace-nowrap rounded-lg bg-white divide-y divide-gray-100']) }}>
        {{ $slot }}
    </table>
@else

    @php
        $slots = $__laravel_slots;
        if (isset($whitelist)) {
            $indexedWhitelist = [];
            foreach($whitelist as $item) {
                $indexedWhitelist[$item] = true;
            }
            $whitelist = $indexedWhitelist;
        }

        if (isset($blacklist)) {
            $indexedBlacklist = [];
            foreach($blacklist as $item) {
                $indexedBlacklist[$item] = true;
            }
            $blacklist = $indexedBlacklist;
        }

        if (!($data[0] ?? null)) {
            if ($data instanceof Illuminate\Support\Collection) {
                $data = $data->toArray();
            }
            $data = [$data];
        }

        $data = collect($data);
        $props = $data->reduce(function(&$carry, $row, $key) use($whitelist, $blacklist) {
            foreach($row as $prop => $value) {
                if (!($blacklist[$prop] ?? false) && (!$whitelist || $whitelist[$prop] ?? false)) {
                    $carry[$prop] = 1;
                }
            }

            return $carry;
        }, collect())->keys();

        $getRowProp = function($row, $prop) {
            if (!isset($row[$prop])) return null;

            $value = $row[$prop];

            return $value;
        }
    @endphp

    <div {{ $attributes->merge(['class' => 'main-table overflow-x-auto shadow-xl border rounded-md border-gray-200']) }}>
    @if ($rotate)
    <x-senna.table class="w-full !whitespace-normal table-fixed">
        <x-senna.table.body>
            @foreach($props as $prop)
                <x-senna.table.row>

                    <x-senna.table.heading paddingClass="py-2 p-4" style="width: {{ $cols[0]['width'] ?? 'auto' }}">
                        {{ $prop}}
                    </x-senna.table.heading>
                    @foreach($data as $key => $row)
                        @php $id = "$key.$prop" @endphp
                        <x-senna.table.cell paddingClass="p-1.5 px-4" :data-slot="$id" style="width: {{ $cols[$key+1]['width'] ?? 'auto' }}">
                            @if(isset($slots[$id]))
                                {{ $slots[$id] }}
                            @else
                                @php
                                    $value = $getRowProp($row, $prop);
                                @endphp

                                @if (!is_scalar($value) && $value !== null)
                                    <pre class="bg-gray-100 rounded p-5 overflow-y-auto">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                @else
                                    @if($html) {!! $value !!} @else {{ $value }} @endif
                                @endif
                            @endif
                        </x-senna.table.cell>
                    @endforeach
                </x-senna.table.row>
            @endforeach
        </x-senna.table.body>
    </x-senna.table>
    @else
    <x-senna.table class="w-full !whitespace-normal">
        <x-senna.table.header>
            @foreach($props as $key => $prop)
                <x-senna.table.heading paddingClass="py-2 p-4" style="width: {{ $cols[$key]['width'] ?? 'auto' }}">
                    {{ $prop}}
                </x-senna.table.heading>
            @endforeach
        </x-senna.table.header>

        <x-senna.table.body>
            @foreach($data as $row)
            <x-senna.table.row>
                @foreach($props as $key => $prop)
                    @php $id = "$key.$prop" @endphp
                    <x-senna.table.cell paddingClass="p-1.5 px-4" :data-slot="$id">
                        @if(isset($slots[$id]))
                            {{ $slots[$id] }}
                        @else
                            @php
                                $value = $getRowProp($row, $prop);
                            @endphp

                            @if (!is_scalar($value) && $value !== null)
                                <pre class="bg-gray-100 rounded p-5 overflow-y-auto">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                            @else
                                @if($html) {!! $value !!} @else {{ $value }} @endif
                            @endif
                        @endif
                    </x-senna.table.cell>
                @endforeach
            </x-senna.table.row>
            @endforeach
        </x-senna.table.body>
    </x-senna.table>
    @endif

    </div>

@endif
