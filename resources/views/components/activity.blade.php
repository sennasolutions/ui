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

<div data-sn="activity" {{ $attributes->merge(['class' => 'relative ml-1 pl-3 type-' . $mode]) }}>
    <div class="absolute -left-1 w-1 h-full {{ $color }}">
    </div>
    <div class="text-sm mb-2 text-gray-400 flex">
        {{ $header ?? null }}
    </div>
    <div class="text-sm activity-content">
        {{ $slot ?? null }}
    </div>
</div>

@once
    @push('styles')
    <style>
        [data-sn="activity"].type-success .activity-content a,
        [data-sn="activity"].type-success .activity-status a {
            color: var(--success);
        }
        [data-sn="activity"].type-error .activity-content a,
        [data-sn="activity"].type-error .activity-status a {
            color: var(--danger);
        }
        [data-sn="activity"].type-info .activity-content a,
        [data-sn="activity"].type-info .activity-status a {
            color: #858585;
        }
    </style>
    @endpush
@endonce