@props([
    'showPagination' => true,
    'showNavigation' => false,
    'options' => [],
    'wrapperClass' => ''
])

<div {{ $attributes->merge(['data-sn' => 'slider']) }}>
    <div class="swiper" data-swiper-init data-swiper-config='@json($options)' style="min-height: 10px">
        <div data-gjs-name="Wrapper" class="swiper-wrapper !h-auto flex {{ $wrapperClass }}">
            {{ $slot }}
        </div>
        @if($showPagination)
          <div data-gjs-name="Pagination" class="swiper-pagination"></div>
        @endif
        @if($showNavigation)
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        @endif
    </div>
</div>

@once
  @push('scripts')
      <script src="{{ senna_asset('addons/cms/js/swiper/swiper.js') }}"></script>
  @endpush
  @push('styles')
      <link rel="stylesheet" href="{{ senna_asset('addons/cms/css/swiper/swiper.css') }}">
      <style>
        .swiper-wrapper > * > img {
          display: block;
          width: 100%;
          height: 100%;
          object-fit: cover;
        }
      </style>
  @endpush
@endonce