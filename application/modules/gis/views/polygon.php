<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
    <script>

      var map;
      var infoWindow;

      function initialize2() {
        var myLatLng = new google.maps.LatLng(-6.8118984663854905,112.8955078125);
        var mapOptions = {
          zoom: 7,
          center: myLatLng,
          mapTypeId: google.maps.MapTypeId.TERRAIN
        };

        var polygon1;

        map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);

        var polyCoords1 = [
            new google.maps.LatLng(-6.8118984663854905,112.8955078125),
            new google.maps.LatLng(-6.806444048123682, 113.4173583984375),
            new google.maps.LatLng(-6.828261348825097, 114.06005859375),
            new google.maps.LatLng(-6.980954426458483, 114.2578125),
            new google.maps.LatLng(-7.318881730366743, 114.10400390625),
            new google.maps.LatLng(-7.373362480979634, 113.0328369140625),
            new google.maps.LatLng(-7.411495021091396, 112.4505615234375),
            new google.maps.LatLng(-7.144498849647323, 111.9781494140625),
            new google.maps.LatLng(-6.844623671722899, 112.1978759765625)
        ];


        polygon1 = new google.maps.Polygon({
          paths: polyCoords1,
          strokeColor: '#FF0000',
          strokeOpacity: 0.8,
          strokeWeight: 1,
          fillColor: '#FF0000',
          fillOpacity: 0.35
        });

        polygon1.setMap(map);

        // Add a listener for the click event
        google.maps.event.addListener(polygon1, 'click', showArrays);

        infowindow = new google.maps.InfoWindow();
      }

      function showArrays(event) {

        // Since this Polygon only has one path, we can call getPath()
        // to return the MVCArray of LatLngs
        var vertices = this.getPath();

        var contentString = '<b>Belajar Polygon</b><br>';
        contentString += 'Clicked Location: <br>' + event.latLng.lat() + ',' + event.latLng.lng() + '<br>';

        // Iterate over the vertices.
        for (var i =0; i < vertices.length; i++) {
          var xy = vertices.getAt(i);
          contentString += '<br>' + 'Coordinate: ' + i + '<br>' + xy.lat() +',' + xy.lng();
        }

        // Replace our Info Window's content and position
        infowindow.setContent(contentString);
        infowindow.setPosition(event.latLng);

        infowindow.open(map);
      }
      
      google.maps.event.addDomListener(window, 'load', initialize2);
    </script>
	<style>
#map-canvas img{
	max-width:none;
}
</style>

    <br />
<div class="heading-buttons">
	<h3>GIS</h3>
	
</div>
<div class="separator bottom"></div>

<div class="innerLR">

  <div id="map-canvas" style="height:700px;" ></div>
</div>
