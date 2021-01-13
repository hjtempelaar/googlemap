@extends('layouts.heat')
@extends('layouts.headers.g50')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-6 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3">
                <div style="margin-left: 20px;">
                    <div class="row" style="background-color: #f7b941">
                        <h1 style="padding-left: 15px">Respons G50 Monitor</h1>
                        <h3 style="padding-left: 15px">Totalen & gemiddelden 2015 - 2019</h3>
                    </div>
                    <div class="float-right">
                        <div class="row">
                            <h1>Filters</h1>
                        </div>
                        <div class="row">
                            <form action="#">
                                <!--                        <div class="form-group">
                                                            <label class="form-control-label">Aantal bezoeken</label>
                                                            <p id="aantal_bezoeken">0</p>
                                                            <input type="hidden" id="aantal_bezoeken_min" name="aantal_bezoeken_min">
                                                            <input type="hidden" id="aantal_bezoeken_max" name="aantal_bezoeken_max">
                                                            <div id="slider-range"></div>
                                                        </div>-->
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Provincie</label>
                                    <select class="form-control" id="provincie" name="provincie" data-toggle="select"
                                            multiple
                                            onchange="updateHeatmap()"
                                            data-placeholder="Select options">
                                        <option selected>Alle Provincies</option>
                                        @foreach($provincies as $provincie)
                                            <option value="{{$provincie->provincie}}">{{$provincie->provincie}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Gemeente</label>
                                    <select class="form-control" id="gemeente" name="gemeente" data-toggle="select"
                                            multiple
                                            onchange="updateHeatmap()"
                                            data-placeholder="Select options">
                                        <option selected>Alle Gemeenten</option>
                                        @foreach($gemeentes as $gemeente)
                                            <option>{{$gemeente}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group">
                                    <button type="button" onclick="updateHeatmap()"
                                            class="btn btn-success btn-lg btn-block">
                                        Filter
                                    </button>
                                </div>
                                <div class="form-group">
                                    <button type="button" onclick="initMapRespons(), form.reset()"
                                            class="btn btn-success btn-lg btn-block">
                                        Filter Reset
                                    </button>
                                </div>
                                <h4>
                                    <span id="result_count">0</span> resultaten
                                </h4>
                            </form>

                        </div>
                        <img alt="Respons" style="max-width:100%;
    height:auto;"
                             src="{{asset('argon')}}/img/brand/Respons-2018_witte-gloed_NL_resized.png">
                    </div>
                </div>

            </div>

            <div class="col-xl-9">
                <div id="map"></div>
            </div>
            {{-- <div class="col-xl-3">
                <div class="row">
                    <h1>Geselecteerde Locatie</h1>
                </div>
                <div class="row" id="info">
                   En hier komt de informatie
                </div>
            </div> --}}
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://kit.fontawesome.com/4d9b955e19.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js"></script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRJ6zKv7SqiAXRYOq5Ok3v9wys0qpLNhs&callback=initMapRespons"></script>
    <script async defer
            src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js"></script>
    <script>
        var isIE = false; // Script toevoegen voor deze check

        function setMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 52.3, lng: 5},
                zoom: 8,
                mapTypeId: 'terrain',
                maxZoom: 14,
                streetViewControl: false,
            });
        }

        const labels = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        let labelIndex = 0;

        function initMapRespons() {
            setMap();

            // Get the event data (JSON format)
            var script = document.createElement('script');
            script.setAttribute('type', "application/javascript");
            script.setAttribute('src', '{{url('getg50heatmap')}}');
            document.getElementsByTagName('head')[0].appendChild(script);
        }

        // Function callback to get data
        function g50feedcallback(results) {
            window.mapData = results.features;
            window.resultCount = results.features.length;

            document.getElementById('result_count').innerHTML = resultCount;

            var InfoWindow = new google.maps.InfoWindow();

            function InfoWindowClose() {
                InfoWindow.close();
            }

            google.maps.event.addListener(map, 'click', InfoWindowClose);

            var oms = new OverlappingMarkerSpiderfier(map, {markersWontMove: true, markersWontHide: true});


            oms.addListener('format', function (marker, status) {
                var iconURL = status == OverlappingMarkerSpiderfier.markerStatus.SPIDERFIED ? 'marker-highlight.svg' :
                    status == OverlappingMarkerSpiderfier.markerStatus.SPIDERFIABLE ? 'marker-plus.svg' :
                        status == OverlappingMarkerSpiderfier.markerStatus.UNSPIDERFIABLE ? 'marker.svg' :
                            null;

                var iconSize = new google.maps.Size(23, 32);
                marker.setIcon({
                    url: iconURL,
                    size: iconSize,
                    scaledSize: iconSize  // makes SVG icons work in IE
                });
            });

            var markers = [];
            for (var i = 0, len = window.mapData.length; i < len; i++) {
                (function () {  // make a closure over the marker and marker data
                    var markerData = window.mapData[i];
                    var coords = markerData.geometry.coordinates;
                    var latLng = new google.maps.LatLng(coords[1], coords[0]);
                    var marker = new google.maps.Marker({
                        position: latLng,
                        //label: labels[i % labels.length],
                        optimized: !isIE  // makes SVG icons work in IE,
                    });

                    // Listen to mouseover
                    google.maps.event.addListener(marker, 'mouseover', function () {
                        var infoWindowHoverContent = `
                            <div id="content">
                                <h4>${markerData.titel}</h4>
                                <p class="m-0"><em><small>Klik voor details</small></em></p>
                            </div>
                        `
                        InfoWindow.setContent(infoWindowHoverContent);
                        InfoWindow.open(map, marker);
                    });

                    // Listen to mouseout
                    google.maps.event.addListener(marker, 'mouseout', function () {
                        InfoWindow.close();
                    });

                    // Listen to click
                    google.maps.event.addListener(marker, 'click', InfoWindowClose);
                    oms.addMarker(marker, function (e) {
                        var infoWindowClickContent = `
                            <div id="content">
                                <h4>${markerData.titel}</h4>
                                <ul class="fa-ul">
                                    <li><span class="fa-li">
                                            <i class="fas fa-trophy"></i>
                                        </span>
                                       Ranking: ${markerData.overall_ranking}
                                    </li>
                                     <li>
                                        <span class="fa-li">
                                            <i class="fas fa-street-view"></i>
                                        </span>
                                       Inwoners:  ${markerData.inwoners}
                                    </li>
  <li>
                                        <span class="fa-li">
                                            <i class="fas fa-users"></i>
                                        </span>
                                        Bezoeken: ${markerData.aantal_bezoeken}
                                    </li>
 <li>
                                        <span class="fa-li">
                                            <i class="fas fa-euro-sign"></i>
                                        </span>
                                       Evenementensubsidie:  ${markerData.evenement_subsidie}
                                    </li>
 <li>
                                        <span class="fa-li">
                                            <i class="fas fa-euro-sign"></i>
                                        </span>
                                       Subsidie per inwoner:  ${markerData.subsidie_per_inwoner}
                                    </li>



                                </ul>
                            </div>
                        `

                        InfoWindow.setContent(infoWindowClickContent);
                        InfoWindow.open(map, marker);
                    });

                    markers.push(marker);
                })();
            }

            // Cluster markers
            var markerClusterer = new MarkerClusterer(map, markers, {
                imagePath: "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
            });
            // Needed to show Spiderfiers
            minClusterZoom = 10;
            markerClusterer.setMaxZoom(minClusterZoom);

            // Set some vars to window for global use
            window.map = map;
            window.oms = oms;
            window.makers = markers;
            window.markerClusterer = markerClusterer;
        }

        // Filter data and reset map
        function updateHeatmap() {
            //document.getElementById('info').innerHTML = 'En weer een update';
            setMap();

            var script = document.createElement('script');
            var formData = new FormData(document.querySelector('form'));
            var gemeentes = $('#gemeente').val();
            var provincies = $('#provincie').val();
            console.log(gemeentes);

            script.setAttribute('src', '{{url('getg50heatmap')}}' + '?provincie=' + provincies
                + '&gemeente=' + gemeentes
            );
            document.getElementsByTagName('head')[0].appendChild(script);
        }

        function numberWithDots(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        $("#slider-range").slider({
            range: true,
            min: 0,
            max: 2000000,
            step: 10000,
            values: [0, 2000000],
            slide: function (event, ui) {
                // On slide
                $("#aantal_bezoeken_min").val(ui.values[0]);
                $("#aantal_bezoeken_max").val(ui.values[1]);

                // Formatted output
                var min = numberWithDots(ui.values[0]);
                var max = numberWithDots(ui.values[1]);
                $("#aantal_bezoeken").text(min + " - " + max);
            }
        });

        // Set inital state
        $("#aantal_bezoeken_min").val($("#slider-range").slider("values", 0));
        $("#aantal_bezoeken_max").val($("#slider-range").slider("values", 1));

        // Formatted output
        var min = numberWithDots($("#slider-range").slider("values", 0));
        var max = numberWithDots($("#slider-range").slider("values", 1));
        $("#aantal_bezoeken").text(min + " - " + max);

        // On slide change
        $("#slider-range").on("slidechange", function (event, ui) {
            updateHeatmap();
        });
    </script>
@endpush

