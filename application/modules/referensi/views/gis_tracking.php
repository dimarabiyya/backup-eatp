<script src="https://maps.google.com/maps/api/js?sensor=false&amp;language=en" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript" src="<?php echo base_url()?>file/js/jquery.gomap-1.3.2.js?a=<?php echo rand()?>"></script>
<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>file/js/search-map.css" />
<script>var baseurl = '<?php echo base_url()?>';</script>
<script src="<?php echo base_url()?>file/js/search-map.js" type="text/javascript"></script>

<style> 
em, .map, #map { 

	width:100%;
	margin:0 auto;
	height:800px;
	display:block;
}
#map img{
	max-width:none;
}
</style> 

<script>
$('.container-fluid:first').toggleClass('menu-hidden');
$('#menu').toggleClass('hidden-phone');
$(function() {
	var peta;
	$("#map").goMap({
		latitude: -8.796647, 
		longitude: 119.621416666667, 
		zoom: 5,
		maptype: 'ROADMAP',
		mapTypeControl: true
	});
	
	var legend = '<div style="width: 250px; height: 120px; background-color: rgb(255, 255, 255); overflow: auto;">'
		   + '<table>'
		   + '<tr><td><img src="<?php echo base_url('/assets/images/icon/orange_MarkerS.png')?>" style="width: 50%; height: 50%"></td><td>Start Point</td></tr>'
		   + '<tr><td><img src="<?php echo base_url('/assets/images/icon/tower_blue.gif')?>" style="width: 50%; height: 50%"></td><td>End Point</td></tr>'
		   
		   + '<tr><td><img src="<?php echo base_url('/assets/images/icon/kuning.png')?>" style="width: 50%; height: 50%"></td><td>Check-in Point</td></tr>'
		   + '<tr><td><img src="<?php echo base_url('/assets/images/icon/biru.png')?>" style="width: 50%; height: 50%"></td><td>Check-out Point</td></tr>'
		   + '<tr><td><img src="<?php echo base_url('/assets/images/icon/merah.png')?>" style="width: 50%; height: 50%"></td><td>Site Schedule</td></tr>'
		   
		   + '</table>'
		   + '</div>';
	$.goMap.createSearchControl('Legend',legend);
	
	$('#tampilkanTopologi').live("submit",function(e)
	{
		//kirim data
		$.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>referensi/map/show_tracking", 
                        data: $(e.target).serialize(),
                        dataType: "json",
                        beforeSend:function(){
                                $.blockUI({ message: 'Processing, please wait...', css: {
					border: 'none',
					padding: '15px',
					backgroundColor: '#000',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					opacity: .5,
					color: '#fff'
				} });
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                                $.unblockUI();
				alert(errorThrown);
                        },
                        success: function(data){
                                $.unblockUI();
				$.goMap.clearMarkers();
				$.goMap.clearOverlays('polyline');
				
				if(data.hasil_link.jml_schedule > data.hasil_link.batas_tracking){
					//kena batas
					n= notyfy({
						layout: 'center',
						text:  'Limit tracking is '+data.hasil_link.batas_tracking, 
						type: 'error',
						modal: true
						});
					setTimeout(function(){ n.close(); }, 3000);
					return;
				}
				
				if (data.jml_link > 0) {
					//loop multiarray
					
					var schedule = data.hasil_link.schedule;
					
					var cubes = data.hasil_link.site;
					var i, randomColor, linetext;
					$.each(cubes, function(key, value) { 
						//this is each data... data[0], data[1]... etc, the value being value and the index being key.
						console.log("Key: "+ key);
						i=0;
						//randomColor = "#"+Math.floor(Math.random()*16777215).toString(16);
						randomColor = "#"+((1<<24)*Math.random()|0).toString(16);
						
						var koordinat = [];
						linetext = "";
						$.each(value, function(innerKey, innerValue){
							koordinat.push({ latitude: innerValue['latitude'], longitude: innerValue['longitude'] });
							
							var konten=" "; 
							var ticon = "merah.png";
							var tampil = "";
							if (i == 0) {
								ticon = "orange_MarkerS.png";
								konten = "Start Point";
								linetext = "<b>Schedule<br/>PIC: "+innerValue['created_by']+", Date: "+innerValue['tracking_date']+"</b><br/>"+ schedule[key] ;
								tampil = "ya";
							}else if (i == value.length-1) {
								ticon = "tower_blue.gif";
								konten = "End Point";
								tampil = "ya";
							}else{
								if (innerValue['type_id']==2) {
									ticon = "kuning.png";
									konten = "Check-in point";
									tampil = "ya";
								}else if (innerValue['type_id']==3) {
									ticon = "biru.png";
									konten = "Check-out point";
									tampil = "ya";
								}
							}
							if ( tampil == "ya" ) {
								$.goMap.createMarker({ group: 'site_mark_'+i, icon:'<?php echo base_url()?>assets/images/icon/'+ ticon,
									html: { 
										   content: konten
									       },
									latitude: innerValue['latitude'] , longitude: innerValue['longitude'] }
								);
							}
							i++;
						});
						
						
						$.goMap.createPolyline({
							color: randomColor,
							opacity: 1,
							weight:	2,
							coords:	koordinat,
							titel:linetext,
							showArrow: true
						});
					});
					
					//show marker schedule
					var dt= {};
					for (var i = 0; i < data.hasil_link.smarker.length; i++) {
						dt = data.hasil_link.smarker[i];
						$.goMap.createMarker({ group: 'sch_mark_'+i, icon:'<?php echo base_url()?>assets/images/icon/merah.png',
						     html: { 
								content: "<table width='300px'>"+
									"<tr><td width='70px'>Site ID</td><td width='5px'>:</td><td>"+dt.site_code+"</td></tr>"+
									"<tr><td width='70px'>Site Name</td><td width='5px'>:</td><td>"+(dt.site_name||'-')+"</td></tr>"+
									"<tr><td width='70px'>Vendor ID</td><td width='5px'>:</td><td>"+(dt.vendor_id||'-')+"</td></tr>"+
									"<tr><td width='70px'>Address</td><td width='5px'>:</td><td>"+ (dt.address||'-') +"</td></tr>"+
									"</table>"
							    } ,
						     latitude: dt.latitude , longitude: dt.longitude });
					}
					$.goMap.fitBounds();
				}else{
					alert("Tracking not found");
					$.goMap.setMap({latitude: -8.796647, 
							longitude: 119.621416666667, 
						       zoom: 5}); 
				}
				
                        }
                });
		return false;
		
	});
	
	//filter PIC
	$('#doSearchPIC').live("click",function(){
		$.fancybox({
			href:"<?php echo base_url()?>referensi/map/searchPIC/8",type:'ajax',
			minWidth	:100,
			scrolling	: false,
			autoSize	:true,
			fitToView	: true,
			closeClick	: false,
			openEffect	: 'fade',
			closeEffect	: 'fade',
			afterShow:function(){$('#app_no').focus();}
		});	
	});
	
	$("#search_pic").live('submit', function () {
		
		var ss = [];
		var rows = $('#dg').datagrid('getSelections');
		for(var i=0; i<rows.length; i++){
		    var row = rows[i];
		    ss.push(row.user_id);
		}
		$("#pic").val( ss.join(';') );
		
		$.fancybox.close();
		return false;
	});
	
	///filter date
	$( "#date_from" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		dateFormat: 'yy-mm-dd',
		onClose: function( selectedDate ) {
		  $( "#date_to" ).datepicker( "option", "minDate", selectedDate );
		}
	      });
	$( "#date_to" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		dateFormat: 'yy-mm-dd',
		onClose: function( selectedDate ) {
		  $( "#date_from" ).datepicker( "option", "maxDate", selectedDate );
		}
	});
});
</script>

<div class="innerLR">
	<div class="widget">	
		<div class="widget-head">
			<h4 class="heading"><i></i> Tracking Map</h4>
		</div>
		
		<div class="widget-body">
			<div class="filter-bar" style="margin-bottom: 0px;">
				<div class="row-fluid">
					<div class="span12">
						<form id="tampilkanTopologi" method="post">
							<div>
								<label>PIC:</label>
								<div class="input-append">
									<input name="pic" id="pic" value="" readonly>
									<input type='hidden' name="site_code" id="site_code" value="">		
									<button id="doSearchPIC" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>	
								</div>
								
								
							</div>
							
							<div>
								<label>Date:</label>
								<div class="input-append">
									<input type="text" id="date_from" name="date_from" style="width:110px;margin-right:15px;" placeholder="Date from..." />  <input type="text" id="date_to" name="date_to" style="width:110px;" placeholder="Date to ..." />
								</div>
							</div>
							
							<button type="submit" id="btnShow" class="btn btn-icon btn-primary glyphicons circle_ok pull-right"><i></i>Show</button>
						</form>
					</div>
					
				</div>
			</div>
			
			<div id="map"></div>
		</div>
	</div>
</div>