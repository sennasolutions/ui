@props([
    'hasPagination' => true
])

<div {{ $attributes->merge(['class' => "sn-slider h-96"]) }}>
    <div class="swiper" x-data="createSwiper">
      <div class="swiper-wrapper">
        {{ $slot }}
      </div>
      @if($hasPagination)
      <div class="swiper-pagination"></div>
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
        function createSwiper() {
            return {
                init() {
                    var swiper = new Swiper(this.$el, {
                        pagination: {  
                            el: this.$el.querySelector(".swiper-pagination"),
                            clickable: true,
                        },
                    });
                }
            }
        }
        </script>
    @endpush
@endonce