<!DOCTYPE html>
<html>
<head>
	<title>Map Location</title>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<link href="<?php echo base_url('/file/template/smashing/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" />
	<style>
	body { margin:10px; }
	#map-canvas img { max-width:none; }
	</style>
</head>
<body>

<h4>Map Location</h4>

<hr class="separator" />

<table>
	<tr>
		<td><div id="map-canvas" style="width:600px; height:400px;"></div></td>
		<td valign="top">
			<label><strong>Latitude:</strong><br /><input type="hidden" name="lat" /><span id="lat"></span></label>
			<label><strong>Longitude:</strong><br /><input type="hidden" name="lng" /><span id="lng"></span></label>
			<div id="address"></div>
		</td>
	</tr>
</table>

<hr class="separator" />

<button onclick="self.close()" class="btn btn-primary pull-right"><i class="icon-ok icon-white"></i> OK</button>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script>
var map;
var defaultLat = -6.1750;
var defaultLng = 106.8283;
var geocoder = new google.maps.Geocoder();
var draggable = <?php echo $this->input->get('draggable') ?>;

<?php
list($lat_elem, $lng_elem) = explode(',', $this->input->get('latlngElement'));
?>
var openerLat = window.opener.document.getElementById('<?php echo $lat_elem ?>').value;
var openerLng = window.opener.document.getElementById('<?php echo $lng_elem ?>').value;

if (openerLat.length != 0 && openerLng.length != 0) {
	defaultLat = openerLat;
	defaultLng = openerLng;
}

var initialize = function() {
	var latLng = new google.maps.LatLng(defaultLat, defaultLng);
	var mapOptions = {
		zoom: 12,
		center: latLng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	
	var marker = new google.maps.Marker({
		position: latLng,
		title: 'Point A',
		map: map,
		draggable: draggable
	});
	
	google.maps.event.addListener(marker, 'drag', function() {
		updateMarkerPosition(marker.getPosition());
		geocodePosition(marker.getPosition());
	});
	
	google.maps.event.addListener(marker, 'dragend', function() {
		geocodePosition(marker.getPosition());
	});
	
	if (openerLat.length != 0 && openerLng.length != 0) {
		// show addres if determined
		geocodePosition(marker.getPosition());
	}
};

var geocodePosition = function(pos) {
	geocoder.geocode({
		latLng: pos
	}, function(responses) {
		if (responses && responses.length > 0) {
			var address = '<strong>Address:</strong><br />' + responses[0].formatted_address;
			updateMarkerAddress(address);
		} else {
			// Cannot determine address at this location
			updateMarkerAddress('');
		}
	});
};

var updateMarkerPosition = function(latLng) {
	document.getElementsByName('lat')[0].value = latLng.lat();
	document.getElementsByName('lng')[0].value = latLng.lng();
	document.getElementById('lat').innerHTML = latLng.lat();
	document.getElementById('lng').innerHTML = latLng.lng();
	
	window.opener.document.getElementById('<?php echo $lat_elem ?>').value = latLng.lat();
	window.opener.document.getElementById('<?php echo $lng_elem ?>').value = latLng.lng();	
};

var updateMarkerAddress = function(str) {
  	document.getElementById('address').innerHTML = str;
};

// initialize
google.maps.event.addDomListener(window, 'load', initialize);

document.getElementsByName('lat')[0].value = defaultLat;
document.getElementsByName('lng')[0].value = defaultLng;
document.getElementById('lat').innerHTML = defaultLat;
document.getElementById('lng').innerHTML = defaultLng;
</script>

</body>
</html>