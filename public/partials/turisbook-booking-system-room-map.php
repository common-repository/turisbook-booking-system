<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $map_key; ?>&callback=initRoomMap&libraries=places"></script>

<div style="position: relative; width: <?php echo $a['width']; ?>; height: <?php echo $a['height']; ?>;">
	<input id="pac-input" class="controls" type="text" placeholder="<?php echo __('See distance from...', 'turisbook-booking-system' ) ?>">
	<div id="room_map_location"></div>
	<div id="distancePanel" ></div>
	<img id="mapTypeButton" src="<?php echo TURISBOOK_PUBLIC_URL . '/img/map_satellite_button_map.jpg'; ?>" alt="Change Map Type">
</div>




<script type="text/javascript">
	var map;
    var marker; // Marcador original
    var distanceService, directionsRenderer, directionsService,infowindow;

    var mapTypes = ['roadmap', 'hybrid'];
    var mapTypeImages = [
    	'<?php echo TURISBOOK_PUBLIC_URL.'/img/map_satellite_button_satellite.jpg'; ?>',
    	'<?php echo TURISBOOK_PUBLIC_URL.'/img/map_satellite_button_map.jpg'; ?>',
    	];
    var currentMapTypeIndex = 0;

    function initRoomMap() {
    infowindow = new google.maps.InfoWindow(); // Use apenas uma instância de InfoWindow para todos os marcadores
    distanceService = new google.maps.DistanceMatrixService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsService = new google.maps.DirectionsService();
    map = new google.maps.Map(document.getElementById('room_map_location'), {
    	center: {lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?>},
    	zoom: 18,
    	mapTypeId: 'hybrid',
    	fullscreenControl: false,
    	streetViewControl: false,
    	rotateControl: false,
    	mapTypeControl:false
    });

    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);



    map.addListener('bounds_changed', function() {
    	searchBox.setBounds(map.getBounds());
    });

    var location = {lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?>};
    marker = new google.maps.Marker({
    	position: location,
    	map: map,
    });
    directionsRenderer.setMap(map);

    var mapTypeButton = document.getElementById('mapTypeButton');
    mapTypeButton.addEventListener('click', function() {
    	currentMapTypeIndex = (currentMapTypeIndex + 1) % mapTypes.length;
    	map.setMapTypeId(mapTypes[currentMapTypeIndex]);
    	mapTypeButton.src = mapTypeImages[currentMapTypeIndex];
    });

    searchBox.addListener('places_changed', function() {
    	var places = searchBox.getPlaces();
    	if (places.length == 0) {
    		return;
    	}

            // Limpa o marcador antigo da busca
    	infowindow.close();
    	marker.setMap(null); 

    	var bounds = new google.maps.LatLngBounds();
            bounds.extend(marker.getPosition()); // Inclui o marcador original nos limites

            places.forEach(function(place) {
            	if (!place.geometry) {
            		console.log('<?php echo __('Returned place contains no geometry', 'turisbook-booking-system' ) ?>');
            		return;
            	}

            	marker = new google.maps.Marker({
            		map: map,
            		title: place.name,
            		position: place.geometry.location
            	});

            	if (place.geometry.viewport) {
            		bounds.union(place.geometry.viewport);
            	} else {
            		bounds.extend(place.geometry.location);
            	}

            	calculateAndDisplayDistance(place, marker);
            });
            map.fitBounds(bounds);
        });


}

function calculateAndDisplayDistance(place, searchedMarker) {
	var destination = place.geometry.location;
        var origin = {lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?>}; // Certifique-se de substituir isto pelo seu marcador inicial, se variável
        directionsService.route({
        	origin: origin,
        	destination: destination,
        	travelMode: google.maps.TravelMode.DRIVING,
        }, function(response, status) {
        	if (status === 'OK') {
        		directionsRenderer.setDirections(response);
        		var route = response.routes[0];

        		var bounds = new google.maps.LatLngBounds();
        		bounds.extend(route.legs[0].start_location);
        		bounds.extend(route.legs[0].end_location);

            // Atualize o mapa para se ajustar aos novos limites
        		map.fitBounds(bounds);

        		var distance = route.legs[0].distance.text;
        		var duration = route.legs[0].duration.text;
        		var photoUrl = place.photos ? place.photos[0].getUrl({'maxWidth': 600, 'maxHeight': 400}) : '';

        		var contentString = '<div style="display:flex; overflow:hidden;">' + 
        		'<div style="flex:1; display: flex; justify-content: center; align-items: center;"><img style="max-width:100px; max-height:100px;" src="' + photoUrl + '"></div>' +
        		'<div style="flex:2; margin-left: 10px;">' + 
        		'<div><strong style="text-decoration:underline;">' + place.name + '</strong></div><br>' + 
        		'<div style="display: flex;">' +
        		'<div style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin-right: 10px;">' + 
        		'<div><i class="fas fa-road"></i></div>' + 
        		'<div><i class="fas fa-hourglass-start"></i></div>' + 
        		'</div>' + 
        		'<div style="display: flex; flex-direction: column; justify-content: center;">' +
        		'<div>' + distance + '</div>' +
        		'<div>' + duration + '</div>' + 
        		'</div></div></div></div>';

        		infowindow.setContent(contentString);
        		infowindow.open(map, searchedMarker);
        	} else {
        		window.alert('Directions request failed due to ' + status);
        	}
        });
    }

    jQuery(function($){
    	setTimeout(function() {
        // Código a ser executado após 2 segundos
    		var parent = $('#pac-input').closest('div');
    		parent.addClass('searchBox');
    	}, 2000);

    });

</script>


<style type="text/css">
	#room_map_location{
		width: 100%;
		height: 100%;
	}
	.controls {
		position:relative;
		box-sizing: border-box;
		-moz-box-sizing: border-box;
		height: 32px;
		max-width:250px;
		outline: none;
		position: relative;
		margin-left:15px!important;
		border:none!important;
		background-color:transparent;
		font-size:15px;

	}
	.searchBox{
		position:relative!important;
		box-sizing:content-box;
		-moz-box-sizing: content-box;
		background-color:white;
		top:10px!important;
		left:10px!important;
		box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
		z-index:10;
		border-radius:5px;
		width:250px;
		margin-left:10px;
		height: 32px;
	}
	.searchBox::before {
		content: '\f689'; /* Este é o código de conteúdo para o ícone de lupa da FontAwesome */
		font-family: "Font Awesome 5 Free"; /* Isto aplica a fonte da FontAwesome */
		position: absolute;
		left: 10px;
		top: 50%;
		transform: translateY(-50%);
		font-weight:900;
		font-size: 16px; /* Ajuste o tamanho do ícone conforme necessário */
		color: #757575; /* Ajuste a cor do ícone conforme necessário */
		z-index:9999;
	}

	.searchBox::after {
		content: '\f057'; /* Este é o código de conteúdo para o ícone de lupa da FontAwesome */
		font-family: "Font Awesome 5 Free"; /* Isto aplica a fonte da FontAwesome */
		position: absolute;
		right: 10px;
		top: 50%;
		font-weight:900;
		transform: translateY(-50%);
		font-size: 16px; /* Ajuste o tamanho do ícone conforme necessário */
		color: #757575; /* Ajuste a cor do ícone conforme necessário */
		z-index:9999;
		display:none;
	}

	#distancePanel{
		position: absolute;
		bottom: 20px;
		left: 20px;
		background-color: grey;
		color: white;
		padding: 10px;
		border-radius: 5px;
		display: none;
	}

	#mapTypeButton{
		position: absolute;
		bottom: 25px;
		right: 60px;
		width: 36px;
		height: 36px;
		border-radius: 3px;
		box-shadow: 0 1px 4px -1px rgba(0, 0, 0, 0.3);
		cursor:pointer;
	}
</style>
