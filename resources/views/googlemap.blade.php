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
<script>var isIE = false;</script><!--[if IE]><script>isIE = true;</script><![endif]-->
<script>
    function setMap() {
        map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: 52, lng: 5},
			zoom: 8,
			mapTypeId: 'terrain',
			maxZoom: 11,
			streetViewControl: false,
		});
    }
	function initMapRespons() {
		map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: 52, lng: 5},
			zoom: 8,
			mapTypeId: 'terrain',
			maxZoom: 11,
			streetViewControl: false,
		});

		// Get the event data (JSON format)
		var script = document.createElement('script');
			script.setAttribute('type',"application/javascript");
			script.setAttribute('src', '{{url('getfestivalheatmap')}}');
			document.getElementsByTagName('head')[0].appendChild(script);
	}
	
	// Function callback to get data
	function eqfeedcallback (results) {
		window.mapData = results.features;

		var InfoWindow = new google.maps.InfoWindow();
		function InfoWindowClose() { InfoWindow.close(); }
		google.maps.event.addListener(map, 'click', InfoWindowClose);

		var oms = new OverlappingMarkerSpiderfier(map, { markersWontMove: true, markersWontHide: true });

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
					optimized: !isIE  // makes SVG icons work in IE
				});
				google.maps.event.addListener(marker, 'click', InfoWindowClose);
				oms.addMarker(marker, function (e) {
                    var content = `
                        <div id="content">
                            <h4>${markerData.titel}</h4>
                            <ul>
                                <li>Van ${markerData.startdatum} t/m ${markerData.einddatum}</li>
                                <li>${markerData.locatie}</li>
                                <li>${markerData.plaats}</li>
                                <li>${markerData.bezoekersaantal} personen</li>
                            </ul>
                        </div>
                    `
					InfoWindow.setContent(content);
					InfoWindow.open(map, marker);
				});

				markers.push(marker);
			})();
		}

		// Cluster markers
		var markerClusterer = new MarkerClusterer(map, markers, {
			imagePath: "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
		});
		minClusterZoom = 9;
		markerClusterer.setMaxZoom(minClusterZoom);

		google.maps.event.addListener(markerClusterer, 'clusterclick', function(cluster) {
			map.fitBounds(cluster.getBounds()); // Fit the bounds of the cluster clicked on
			if( map.getZoom() > minClusterZoom + 1 ) // If zoomed in past 10 (first level without clustering), zoom out to 10
				map.setZoom(minClusterZoom + 1);
		});

		// Set some vars to window for global use
		window.map = map;
		window.oms = oms;
		window.makers = markers;
		window.markerClusterer = markerClusterer;
	}

	// Filter data and reset map
	function updateHeatmap() {
		document.getElementById('info').innerHTML = 'En weer een update';
		setMap();

		var script = document.createElement('script');
		var formData = new FormData(document.querySelector('form'))
		script.setAttribute('src','{{url('getfestivalheatmap')}}' + '?provincie=' + formData.get('provincie') + '&maand=' + formData.get('maand') + '&categorie=' + encodeURI(formData.get('categorie')));
		document.getElementsByTagName('head')[0].appendChild(script);
	}
</script>
<script src="https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCA600mKTHv1js99C8r7xM9nSVD8yip7t0&callback=initMapRespons"></script>
<script async defer src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js"></script>
@endpush
