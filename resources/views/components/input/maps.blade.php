@php
/**
 * @name Maps
 * @description Location input with an optional google maps display. Be sure to set maps.apiKey in config
 */
@endphp

@props([
    /**
     * @param array autocompleteConfig The google maps autocomplete config.
     */
    'autocompleteConfig' => [],
     /**
     * @param array mapsConfig The google maps config.
     */
    'mapsConfig' => [
        'zoom' => 2,
        'center' => [
            'lat' => 6.20,
            'lng' => -49.5,
        ]
    ],
    /**
     * @param string inputClass The classes for the input field
     */
    'inputClass' => '',

    /**
     * @param string label The label
     */
    'label' => null,

    /**
     * @param string error Error text
     */
    'error' => null,
    /**
     * @param string errorClass String of classes applied to the error element
     */
    'errorClass' => '',
    /**
     * @param string size 'xl', 'lg' or 'sm'
     */
    'size' => 'lg',
    /**
     * @param bool showMap Whether to show the map
     */
    'showMap' => true,
    /**
     * @param array value The initial value, if wire:model is not used
     */
    'value' => null
])

@if(!config('senna.ui.maps.apiKey'))
    <div>
        {{ __('Please set a google API Key in the config file (maps.apiKey)') }}
    </div>
@else

<div wire:ignore {{ $attributes->merge(['class' => 'sn-input-maps rounded ' . ($showMap ? 'border' : 'border-none') ])->only('class') }} x-data="initMap(@safe_entangle($attributes->wire('model')))"
    x-init='init(@json($mapsConfig), @json($autocompleteConfig))' >
    <div x-ref="search" class="{{ $showMap ? 'p-3' : '' }}">
        <x-senna.input.group :label="$label">
            <div class="sn-input-text flex-grow relative block">
                <div class="{{ class_concat('absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-black opacity-70 sm:text-sm') }}">
                    <x-senna.icon class="w-5 h-5" name="hs-location-marker"></x-senna.icon>
                </div>
                <input type="text" x-ref="input" class="{{ class_concat(default_input_chrome($size, $error), $inputClass, "pl-10" ) }}"/>
            </div>
        </x-senna.input.group>
    </div>
    @if($showMap)
    <div wire:ignore x-ref="map" class="sn-input-maps-map" ></div>
    @endif
</div>

@once
    @push('senna-ui-styles')
    <style type="text/css">
        .sn-input-maps-map {
            height: 400px;
            width: 100%;
        }
    </style>

    @endpush
    @push('senna-ui-scripts')
    <script>
      window.mapIsLoaded = false;

      function initMap(value) {
        return {
            value: value,
            map: null,
            $map: null,
            $search: null,
            markers: [],
            mapsConfig: {},
            autocompleteConfig: {},
            init(mapsConfig, autocompleteConfig) {
                this.mapsConfig = mapsConfig
                this.autocompleteConfig = autocompleteConfig

                if (typeof Alpine.mapComponents === 'undefined') {
                    Alpine.mapComponents = [];
                }

                this.$map = this.$refs.map
                this.$search = this.$refs.search.querySelector('input')

                if (window.mapIsLoaded) {
                    this.onReady();
                }

                Alpine.mapComponents.push(this);
            },
            onReady() {
                if (this.$map) {
                    this.map = new google.maps.Map(this.$map, {...this.mapsConfig});
                }

                if (this.value) {
                    this.addMarkerFromValue(this.value)
                }

                this.initSearch()
            },
            initSearch() {
                const options = {
                    fields: ["formatted_address", "geometry", "name"],
                    ...this.autocompleteConfig,
                };

                this.search = new google.maps.places.Autocomplete(this.$search, options);
                this.$search.addEventListener('keydown', (ev) => {
                    if(ev.code === "Enter" && !document.querySelector(".pac-item-selected")) {
                        var simDown = new KeyboardEvent("keydown", {
                            keyCode: 40,
                            which: 40
                        });

                        this.$search.dispatchEvent(simDown);
                    }
                })
                this.search.addListener('place_changed', (ev) => {
                    let place = this.search.getPlace();

                    if (!place.geometry || !place.geometry.location) {
                        return;
                    }

                    if (typeof place === "object") {
                        this.onSearch(place)
                    }
                })
            },
            setMapOnAll(map) {
                for (let i = 0; i < this.markers.length; i++) {
                    this.markers[i].setMap(map);
                }
            },
            addMarker(data) {
                this.markers.push(new google.maps.Marker(data))
            },

            clearMarkers() {
                this.setMapOnAll(null);
                this.markers = []
            },
            onSearch(place) {
                if (this.map) {
                    this.clearMarkers();

                    this.addMarker({
                        position: place.geometry.location,
                        map: this.map
                    })

                    this.map.fitBounds(place.geometry.viewport)
                }

                this.setValueFromPlace(place)
            },
            setValueFromPlace(place) {
                if (place && place.geometry && place.geometry.location) {
                    this.value = {
                        name: place.formatted_address,
                        shortName: place.name,
                        lat: place.geometry.location.lat(),
                        lng: place.geometry.location.lng(),
                        viewport: place.geometry.viewport.toJSON()
                    }
                }
            },
            addMarkerFromValue(value) {
                if (value.name) {
                    this.$search.value = value.name
                }

                if (this.map) {
                    this.clearMarkers();
                    let point = {lat:value.lat, lng:value.lng};

                    this.addMarker({
                        position: point,
                        map: this.map
                    })

                    if (value.viewport) {
                        this.maps.fitBounds(value.viewport);
                    } else {
                        let bounds = new google.maps.LatLngBounds();
                        bounds.extend(point)

                        var originalMaxZoom = this.map.maxZoom;
                        this.map.setOptions({maxZoom: 14});
                        this.map.fitBounds(bounds, 100);
                        setTimeout(() => {
                            this.map.setOptions({maxZoom: originalMaxZoom});
                        }, 1000)
                    }
                }
            },
        }
      }
        // Initialize and add the map
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
    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('senna.ui.maps.apiKey') }}&callback=googleMapsInit&libraries=places&v=weekly"
        async>
    </script>

    @endpush
@endonce

@endif
