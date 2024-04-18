@php
/**
 * @name Maps
 * @description Location input with an optional google maps display. Be sure to set maps.apiKey in config
 */
@endphp

@props([
     /**
     * @param array config The google maps config.
     */
    'config' => [
        'zoom' => 2,
        'center' => [
            'lat' => 6.20,
            'lng' => -49.5,
        ],
        'fullscreenControl' => false,
        'mapTypeControl' => false,
        'streetViewControl' => false,
        'zoomControl' => true

    ],
    'markers' => [
        [
            'content' => 'hey',
            'position' => [
                'lat' => 51.2,
                'lng' => 5.4,
            ]
        ]
    ]
])

@wireProps

@if(!config('senna.ui.maps.apiKey'))
    <div>
        {{ __('Please set a google API Key in the config file (maps.apiKey)') }}
    </div>
@else

<div 
    data-sn="google-maps" 
    {{ $attributes->root() }}
    wire:ignore 
    x-data="{
        config: @entangleProp('config'),
        markers: @entangleProp('markers'),
        $map: null,
        instance: null,
        instanceMarkers: [],
        {{-- maxZoom:  @entangleProp('maxZoom'), --}}
        infoWindow: null,
        init() {
            if (typeof Alpine.mapComponents === 'undefined') {
                Alpine.mapComponents = [];
            }
            this.$map = this.$refs.map

            if (window.mapIsLoaded) {
                this.onReady();
            }

            Alpine.mapComponents.push(this);

            this.$watch('markers', () => this.updateMarkers());
            this.$watch('config', () => this.updateConfig());
        },
        onReady() {
            if (this.$map) {
                {{-- console.log(this.config) --}}
                this.instance = new google.maps.Map(this.$map, {...this.config});
                {{-- this.instance.addListener('zoom_changed', () => {
                    if (this.instance.getZoom() > this.maxZoom) {
                        this.instance.setZoom(this.maxZoom);
                    }
                }); --}}

                {{-- console.log(this.markers); --}}
                this.infoWindow = new google.maps.InfoWindow;

                this.instance.addListener('click', (event) => {
                    this.infoWindow.close();
                });
            }

            this.updateMarkers()
        },
        updateConfig() {
            if (this.instance) {
                this.instance.setOptions(this.config);
            }
        },
        updateMarkers() {
            this.instanceMarkers.forEach(marker => marker.setMap(null));
            this.instanceMarkers = [];

            this.markers.forEach(marker => {
                // Set a popup window that closes when clicked outside the marker
                let gmarker = new google.maps.Marker({
                    ...marker,
                    map: this.instance,
                })

                if (marker.content) {
                    gmarker.addListener('click', () => {
                        this.infoWindow.setContent(marker.content);
                        this.infoWindow.open(this.instance, gmarker);
                    });
                }

                this.instanceMarkers.push(gmarker);
            });
            
            this.fitMarkers();
        },
        fitMarkers() {
            if (!this.markers.length) return;


            const bounds = new google.maps.LatLngBounds();
            this.instanceMarkers.forEach(marker => bounds.extend(marker.getPosition()));
            // fitbounds with padding

            this.instance.fitBounds(bounds, 50);
            var zoom = this.instance.getZoom();
      
            google.maps.event.addListenerOnce(this.instance, 'bounds_changed', function() { 
                this.setZoom(Math.min(12, this.getZoom())); 
            });

            @wireMethod('onFitMarkers')(this.markers);
        }
    }">
        <div wire:ignore x-ref="map" class="sn-google-maps" {{ $attributes->namespace('map') }} ></div>

</div>

@once
    @push('senna-ui-styles')
    <style type="text/css">
        .sn-google-maps {
            height: 400px;
            width: 100%;
        }
    </style>

    @endpush
    @push('senna-ui-scripts')
    <script>
      window.mapIsLoaded = false;

      function googleMapsInit() {
        window.mapIsLoaded = true;

        // If alpine was first with loading
        if (typeof Alpine == 'undefined') return;

        if (typeof Alpine.mapComponents === 'undefined') {
            Alpine.mapComponents = [];
        }

        Alpine.mapComponents.forEach(c => {
            c.onReady();
        })
      }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('senna.ui.maps.apiKey') }}&callback=googleMapsInit&libraries=places&v=weekly"
        async>
    </script>

    @endpush
@endonce

@endif
