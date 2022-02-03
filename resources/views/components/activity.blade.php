@props([
    'mode' => 'info',
])

@php
    use Senna\Activity\Enums\ActivityType;

    $color = "bg-gray-300";
    $color = $mode === ActivityType::Success ? 'bg-success' : $color; 
    $color = $mode === ActivityType::Error ? 'bg-danger' : $color; 
    $color = $mode === ActivityType::Info ? 'bg-gray-300' : $color; 
@endphp

<div data-sn="activity" {{ $attributes->merge(['class' => 'relative pl-4 pb-6 type-' . $mode]) }}>
    <div class="absolute -left-1 w-1 h-full {{ $color }}">
    </div>
    <div class="text-sm mb-2 text-gray-400 flex">
        {{ $header ?? null }}
    </div>
    <div class="text-sm">
        {{ $slot ?? null }}
    </div>
</div>

@once
    @push('styles')
    <style>
        [data-sn="activity"].type-success a {
            color: var(--success);
        }
        [data-sn="activity"].type-error a {
            color: var(--danger);
        }
        [data-sn="activity"].type-info a {
            color: #858585;
        }
    </style>
    @endpush
@endonce