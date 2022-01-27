@props([
    'showPagination' => true,
    'showNavigation' => false,
    'options' => []
])

<div {{ $attributes->merge(['data-sn' => 'slider', 'class' => "h-96"]) }}>
    <div class="swiper h-full" x-data="createSwiper(@js($options))">
      <div class="swiper-wrapper flex h-full">
        {{ $slot }}
      </div>
      @if($showPagination)
        <div class="swiper-pagination"></div>
      @endif
      @if($showNavigation)
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      @endif
    </div>
</div>

@once
    {{-- @todo include in project --}}
    @push('styles')
    <link
        rel="stylesheet"
        href="https://unpkg.com/swiper@7/swiper-bundle.min.css"
        />
    <style>
      :root {
        --swiper-pagination-bullet-size: 16px;
        --swiper-pagination-bullet-inactive-color: #fff;
        --swiper-theme-color: rgb(14 165 233);
        --swiper-pagination-bullet-opacity: 1;
        --swiper-pagination-bullet-inactive-opacity: 1.0;
      }

      .swiper {
        width: 100%;
        height: 100%;
      }

      .swiper-wrapper {
       
      }

      .swiper-wrapper > * > img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

        <script>
        function createSwiper(options) {
            return {
                init() {
                    var swiper = new Swiper(this.$el, {
                        ...options,
                        pagination: {  
                            el: this.$el.querySelector(".swiper-pagination"),
                            clickable: true,
                            ...options.pagination
                        },
                        navigation: {
                          nextEl: '.swiper-button-next',
                          prevEl: '.swiper-button-prev',
                        },
                    });
                }
            }
        }
        </script>
    @endpush
@endonce