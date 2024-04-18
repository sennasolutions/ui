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
'title' => 'hey',
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

<div data-sn="google-maps" {{ $attributes->root() }}
    wire:ignore
    x-data="google_maps({
    config: @entangleProp('config'),
    markers: @entangleProp('markers')
    })">
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
    (g => { var h, a, k, p = "The Google Maps JavaScript API", c = "google", l = "importLibrary", q = "__ib__", m = document, b = window; b = b[c] || (b[c] = {}); var d = b.maps || (b.maps = {}), r = new Set, e = new URLSearchParams, u = () => h || (h = new Promise(async (f, n) => { await (a = m.createElement("script")); e.set("libraries", [...r] + ""); for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]); e.set("callback", c + ".maps." + q); a.src = `https://maps.${c}apis.com/maps/api/js?` + e; d[q] = f; a.onerror = () => h = n(Error(p + " could not load.")); a.nonce = m.querySelector("script[nonce]")?.nonce || ""; m.head.append(a) })); d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n)) })({
        key: "{{ config('senna.ui.maps.apiKey') }}",
        v: "weekly",
        // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
        // Add other bootstrap parameters as needed, using camel case.
    });

</script>

<script>
    document.addEventListener('alpine:init', () => {
        /**
        This creates the conditional field options
        */
        Alpine.data('google_maps', ({ config, markers }) => {
            let googleMap = null
            let infoWindow = null
            let googleMapMarkers = []
        
            return {
                config,
                markers,
                $map: null,
                instance: null,
                infoWindow: null,
                google: {},
                async init() {
                    this.google.maps = await google.maps.importLibrary("maps");
                    this.google.marker = await google.maps.importLibrary("marker");

                    this.$map = this.$refs.map

                    this.initMap();
                    await this.$watch('markers', async () => await this.updateMarkers());
                    this.$watch('config', () => this.updateConfig());
                },
                async initMap() {
                    const { Map } = await google.maps.importLibrary("maps")
                    const { InfoWindow } = await google.maps.importLibrary("maps");
                  
                    // Request needed libraries.
                    googleMap = new Map(this.$map, {
                        center: { lat: 37.4239163, lng: -122.0947209 },
                        zoom: 14,
                        mapId: Math.random().toString(36).substring(7),
                    });

                    infoWindow = new InfoWindow();

                    // on click close info window
                    googleMap.addListener('click', () => {
                        if (infoWindow) {
                            infoWindow.close();
                        }
                    });
               
                    await this.updateMarkers()
                },
                updateConfig() {
                    if (this.instance) {
                        this.instance.setOptions(this.config);
                    }
                },
                async updateMarkers() {
                    const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary('marker');
                    
                    googleMapMarkers.forEach(marker => marker.setMap(null));
                    googleMapMarkers = [];

                    this.markers.forEach(({position, title, popup}, i) => {
                        // Set a popup window that closes when clicked outside the marker
                        const pin = new PinElement({
                            // glyph: `${i + 1}`,
                        });

                        let gmarker = new AdvancedMarkerElement({
                            map: googleMap,
                            position,
                            title: `${i + 1}. ${title}`,
                            content: pin.element
                        });

                        if (popup) {
                            gmarker.addListener('click', () => {
                                infoWindow.close();
                                infoWindow.setContent(popup);
                                infoWindow.open(googleMap, gmarker);
                            });
                        }

                        googleMapMarkers.push(gmarker);
                    });

                    this.fitMarkers();
                },
                fitMarkers() {
                    if (!this.markers.length) return;

                    const bounds = new google.maps.LatLngBounds();
                    googleMapMarkers.forEach(marker => bounds.extend(marker.position));
                    // fitbounds with padding
                    googleMap.fitBounds(bounds, 50);
                    var zoom = googleMap.getZoom();

                    google.maps.event.addListenerOnce(googleMap, 'bounds_changed', function () {
                        this.setZoom(Math.min(12, this.getZoom()));
                    });

                    @wireMethod('onFitMarkers')(this.markers);
                }
            }
        })
    });
</script>

@endpush
@endonce

@endif