<?php
/* Map Tracking
 * Google Map API 3
 * by Wirda Hilwa
 */
?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>file/js/easyui/themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>file/js/easyui/themes/icon.css" />
<script type="text/javascript" src="<?php echo base_url()?>file/js/easyui/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>file/js/easyui/jquery.easyui.min.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>

	function initialize() {
	  var mapOptions = {
		zoom: 18,
		center: new google.maps.LatLng(<?php echo $track[0]['latitude']?>,<?php echo $track[0]['longitude']?>),
		mapTypeId: google.maps.MapTypeId.ROAD
	  };

	  var map = new google.maps.Map(document.getElementById('map-canvas'),
		  mapOptions);

		//tambahan 6dec2013
		//start point
		marker0 = new google.maps.Marker({
			map:map,
			draggable:true,
			icon: "<?php echo base_url('/assets/images/icon/orange_MarkerS.png'); ?>",
			position: new google.maps.LatLng(<?php echo $track[0]['latitude']?>,<?php echo $track[0]['longitude']?>)
		});
		
		//end point
		marker1 = new google.maps.Marker({
			map:map,
			draggable:true,
			icon: "<?php echo base_url('/assets/images/icon/green_blinking.gif'); ?>",
			position: new google.maps.LatLng(<?php echo $track[sizeOf($track)-1]['latitude']?>,<?php echo $track[sizeOf($track)-1]['longitude']?>)
		});


	  var flightPlanCoordinates = [
	  <?php
		$i=0;
		$total_row=sizeof($track);
		foreach ($track as $row){
	  ?>
		new google.maps.LatLng(<?php echo $row['latitude']?>, <?php echo $row['longitude']?>)
		<?php if($i!=$total_row-1){?>,<?php } ?>
		
		<?php
		$i++;
		}
		?>
		];
	  var flightPath = new google.maps.Polyline({
		path: flightPlanCoordinates,
		geodesic: true,
		strokeColor: '#FF0000',
		strokeOpacity: 1.0,
		strokeWeight: 2
	  });

	  flightPath.setMap(map);
	}

	google.maps.event.addDomListener(window, 'load', initialize);

    </script>

	<style>
#map-canvas img{
	max-width:none;
}
.combo-text {margin-bottom: 0px !important; padding:0px !important;}
</style>

<br />
<div class="heading-buttons">
<h3>Tracking Map</h3>	
</div>
<div class="separator bottom"></div>

<div class="innerLR">
	<div class="input">
			<form id="form1" name="form1" action="<?php echo base_url()?>gis/gis_tracking" method="post">
			PIC:&nbsp;&nbsp;<input id="user" name="user" style="width:200px;margin-bottom: 0px;">                                          
			Date:&nbsp;&nbsp;<input id="date" name="date" style="width:200px;margin-bottom: 0px;">  <input type="submit" name="submit" value="Show"> 
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PIC: &nbsp;<b><?php echo isset($user) ? $user : ' '; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Date:&nbsp;<b><?php echo isset($date) ? $date : ' '; ?></b>
	</div>
    
	<div id="map-canvas" style="height:700px;" ></div>
</div>
