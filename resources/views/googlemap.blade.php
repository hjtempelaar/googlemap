@extends('layouts.heat')
@extends('layouts.headers.googlemap')

@section('content')


    <style type="text/css">
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 800px;
            width: 100%;
        }

        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-6 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3">
                <div class="row">
                    <h1>Filters</h1>
                </div>
                <div class="row">
                    <form>
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Provincie</label>
                            <select class="form-control" name="provincie" data-toggle="select"
                                    data-placeholder="Select options">
                                <option selected>Alle Provincies</option>
                                @foreach($provincies as $provincie)
                                    <option value="{{$provincie->provincie}}">{{$provincie->provincie}}
                                        ({{$provincie->totaal}})
                                    </option>
                                @endforeach

                            </select>

                        </div>
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Categorie</label>
                            <select class="form-control" name="categorie" data-toggle="select"
                                    data-placeholder="Select options">
                                <option selected>Alle Categoriëen</option>
                                @foreach($subcategorienamen as $subcategorienaam)
                                    <option>{{$subcategorienaam}}</option>

                                @endforeach
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Maand</label>
                            <select class="form-control" name="maand" data-toggle="select"
                                    data-placeholder="Select options">
                                <option selected>Alle Maanden</option>
                                @foreach($maanden as $maand)
                                    <option>{{$maand}}</option>

                                @endforeach
                            </select>

                        </div>

                        <button type="button" onclick="updateHeatmap()" class="btn btn-success btn-lg btn-block">
                            Filter
                        </button>
                    </form>
                </div>

            </div>

            <div class="col-xl-6">
                <div class="chart-heatmap">
                    <div id="map"></div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="row">
                    <h1>Geselecteerde Locatie</h1>
                </div>
                <div class="row" id="info">
                   En hier komt de informatie
                </div>

            </div>
        </div>


        @include('layouts.footers.auth')
    </div>

@endsection

@push('js')
    <script src="https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js"></script>
    <script>

        // [START script-body]
        var map;
        var type;
        var data;
        let markers = [];
        var markerClusterer = null;




            function initMapRespons() {

            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 52, lng: 5},
                zoom: 8,
                mapTypeId: 'terrain',
                maxZoom: 11,
                streetViewControl: false,
            });
            // Get the event data (JSONP format)
            var script = document.createElement('script');
                script.setAttribute('type',"application/javascript");
            script.setAttribute(
                'src', '{{url('getfestivalheatmap')}}');
            document.getElementsByTagName('head')[0].appendChild(script);
        }

        // Loop through the results array and place a marker for each
        // set of coordinates.

        // variable to hold number of seconds before showing infoWindow on Mouseover event
        var mouseoverTimeoutId = null;

        const eqfeedcallback = function (results) {
            //var infoWnd = new google.maps.InfoWindow();
            // create an InfoWindow -  for mouseclick
            //var infoWnd2 = new google.maps.InfoWindow();
            //var activeInfoWindow = new google.maps.InfoWindow();
            for (let i = 0; i < results.features.length; i++) {
                const coords = results.features[i].geometry.coordinates;
                const latLng = new google.maps.LatLng(coords[1], coords[0]);
                marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 12,
                        fillColor: "#627BB7",
                        fillOpacity: 0.5,
                        strokeWeight: 0.4
                    },
                    title: results.features[i].title,
                    //draggable: true,
                    //animation: google.maps.Animation.DROP,
                });
                marker.addListener("click", () => {
                    console.log('we zijn hier marker click');
                    document.getElementById('info').innerHTML = 'En weer een update' + marker.title;
                });
                console.log('we zijn hier marker ' + results.features[i].title);
                markers.push(marker);


            }

            // Add a marker clusterer to manage the markers.
            markerClusterer = new MarkerClusterer(map, markers, {
                imagePath:
                    "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
            });


        };


        // Removes the markers from the map, but keeps them in the array.
        function clearMarkers() {
            setMapOnAll(null);
        }

        // Shows any markers currently in the array.
        function showMarkers() {
            setMapOnAll(map);
        }

        // Deletes all markers in the array by removing references to them.
        function deleteMarkers() {
            clearMarkers();
            markers = [];
        }

        // Sets the map on all markers in the array.
        function setMapOnAll(map) {
            for (let i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }


        // Defines the callback function referenced in the jsonp file.
        function eqfeed_callback(data) {


        }

        function updateHeatmap() {
            console.log('we zijn hier');
            document.getElementById('info').innerHTML = 'En weer een update';
                if (markerClusterer) {
                    console.log('er is en cluster');

                    markerClusterer.clearMarkers();
                    markers = [];
                }


            var script = document.createElement('script');
            var formData = new FormData(document.querySelector('form'))
            script.setAttribute('type',"application/javascript");
            script.setAttribute(
                'src',
                //'https://developers.google.com/maps/documentation/javascript/examples/json/earthquake_GeoJSONP.js');
                '{{url('getfestivalheatmap')}}' + '?provincie=' + formData.get('provincie') + '&maand=' + formData.get('maand') + '&categorie=' + encodeURI(formData.get('categorie')));
            document.getElementsByTagName('head')[0].appendChild(script);


        }

        // Deletes all markers in the array by removing references to them.


        // [END script-body]

    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCA600mKTHv1js99C8r7xM9nSVD8yip7t0&callback=initMapRespons"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>


@endpush
