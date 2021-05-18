@props([
    'view' => '',
])

@php
    $contents = file_get_contents(view($view)->getPath());

    preg_match_all('/x-senna.ui.manual-header (.*?)name=[\'|"](?<navGroup>.*?)[\'|"]|x-senna.ui.manual (.*?)name=[\'|"](?<name>.*?)[\'|"]/', $contents, $list);
@endphp

@foreach($list[0] as $key => $value)
    @php
        $groupName = $list['navGroup'][$key] ?? false;
        $name = $list['name'][$key] ?? false;
        $groupId = Illuminate\Support\Str::slug($groupName);
        $id = Illuminate\Support\Str::slug($name);
    @endphp

    @if($groupName)
    <a href="#{{ $groupId }}"><h5 class="uppercase font-bold opacity-40 mb-3 mt-6">{{ $groupName }}</h5></a>
    @else
    <div class="mb-2">
        <a href="#{{ $id }}">{{ $name }}</a>
    </div>
    @endif
@endforeach
