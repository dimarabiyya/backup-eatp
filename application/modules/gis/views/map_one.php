<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false">
</script>

<script>
var lat=<?php echo $lat?>;
var lon=<?php echo $long?>;
var myCenter=new google.maps.LatLng(<?php echo $lat?>,<?php echo $long?>);

function initialize()
{
var mapProp = {
  center:myCenter,
  zoom:10,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("map-canvas"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,
  });

marker.setMap(map);
var infowindow = new google.maps.InfoWindow({
  content:"Lat:"+lat+", Lon:"+lon
  });

infowindow.open(map,marker);
google.maps.event.addListener(marker, 'click', function() {
  infowindow.open(map,marker);
});

}


google.maps.event.addDomListener(window, 'load', initialize);
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
<div id="map-canvas" style="height:700px;"></div>

</div>
