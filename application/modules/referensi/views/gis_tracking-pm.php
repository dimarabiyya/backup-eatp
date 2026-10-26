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
	var legend = '<div style="width: 250px; height: 180px; background-color: rgb(255, 255, 255); overflow: auto;" id="legend">'
		   + '<table>'
		   + '<tr><td><input type="checkbox" id="start" onclick="showHideMarkers(\'start\')" checked="checked"> <img src="<?php echo base_url('/assets/images/icon/orange_MarkerS.png')?>" style="width: 50%; height: 50%"></td><td>Start Point</td></tr>'
		   + '<tr><td><input type="checkbox" id="end" onclick="showHideMarkers(\'end\')" checked="checked"> <img src="<?php echo base_url('/assets/images/icon/tower_blue.gif')?>" style="width: 50%; height: 50%"></td><td>End Point</td></tr>'
		   + '<tr><td><input type="checkbox" id="in" onclick="showHideMarkers(\'in\')" checked="checked"> <img src="<?php echo base_url('/assets/images/icon/kuning.png')?>" style="width: 50%; height: 50%"></td><td>Check-in Point</td></tr>'
		   + '<tr><td><input type="checkbox" id="out" onclick="showHideMarkers(\'out\')" checked="checked"> <img src="<?php echo base_url('/assets/images/icon/biru.png')?>" style="width: 50%; height: 50%"></td><td>Check-out Point</td></tr>'
		   + '<tr><td><input type="checkbox" id="sche" onclick="showHideMarkers(\'sche\')" checked="checked"> <img src="<?php echo base_url('/assets/images/icon/merah.png')?>" style="width: 50%; height: 50%"></td><td>Site Schedule</td></tr>';
	
	$('#tampilkanTopologi').live("submit",function(e)
	{
		//kirim data
		$.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>referensi/map/show_tracking_pm", 
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
				$(".new_legend").remove();
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
					var i, randomColor, linetext,  mark_count;
                                        var j = 0;
					$.each(cubes, function(key, value) { 
						//this is each data... data[0], data[1]... etc, the value being value and the index being key.
						//console.log("Key: "+ key);
						i=0;
                                                mark_count = 0;
						//randomColor = "#"+Math.floor(Math.random()*16777215).toString(16);
						randomColor = "#"+((1<<24)*Math.random()|0).toString(16);
						var koordinat = [];
						linetext = "";
                                                var pic_sch, date_sch;
						$.each(value, function(innerKey, innerValue){
							koordinat.push({ latitude: innerValue['latitude'], longitude: innerValue['longitude'] });
							var konten=" "; 
							var ticon = "merah.png";
							var tampil = "";
                                                        var grup = "";
                                                        var id = "";
                                                        pic_sch = innerValue['user_name'];
                                                        date_sch = innerValue['created_on'];
							if (i == 0) {
								ticon = "orange_MarkerS.png";
								konten = "Start Point";
								linetext = "<b>Schedule<br/>PIC : "+pic_sch+", Date : "+date_sch+"</b><br/>"+ schedule[key] ;
								tampil = "ya";
                                                                grup = 'start';
                                                                id='pl'+j+'-'+mark_count;
							}else if (i == value.length-1) {
								ticon = "tower_blue.gif";
								konten = "End Point";
								tampil = "ya";
                                                                grup = 'end';
                                                                id='pl'+j+'-'+mark_count;
							}else{
								if (innerValue['type_id']==2) {
									ticon = "kuning.png";
									konten = "Check-in point";
									tampil = "ya";
                                                                        grup = 'in';
                                                                        id='pl'+j+'-'+mark_count;
								}else if (innerValue['type_id']==3) {
									ticon = "biru.png";
									konten = "Check-out point";
									tampil = "ya";
                                                                        grup = 'out';
                                                                        id='pl'+j+'-'+mark_count;
								}
							}
							if ( tampil == "ya" ) {
                                                            //create marker
                                                            $.goMap.createMarker({id:id, group:grup, icon:'<?php echo base_url()?>assets/images/icon/'+ ticon,
                                                                    html: { 
                                                                               content: konten
                                                                           },
                                                                    latitude: innerValue['latitude'] , longitude: innerValue['longitude'] }
                                                            );
                                                                mark_count++;
                                                                //console.log('ID:'+id);
							}
							i++;
						});
                                                //create polyline
                                                $.goMap.createPolyline({
                                                        id: 'pl'+j,
							color: randomColor,
							opacity: 1,
							weight:	2,
							coords:	koordinat,
							titel:linetext,
							showArrow: true
						});
                                                var mc = mark_count-1;
                                                // Add map legend
                                                $("#legend").append('<tr class="new_legend"><td><input type="checkbox" id="pl'+j+'" onclick="showHideLine(\'pl'+j+'\','+mc+' )" checked="checked"> <span style="color:'+randomColor+'; font-weight:bold; font-size:20pt">&#10145; </span>'+date_sch+' '+pic_sch+'</td></tr>');
                                                j++;
					});
                                         $.goMap.fitBounds();
					//show marker schedule
                                        if (data.hasil_link.jml_schedule>0) {
                                            var dt= {};
                                            for (var i = 0; i < data.hasil_link.smarker.length; i++) {
                                                    dt = data.hasil_link.smarker[i];
                                                    $.goMap.createMarker({ group: 'sche', icon:'<?php echo base_url()?>assets/images/icon/merah.png',
                                                         html: { 
                                                                    content: "<table width='300px'>"+
                                                                            "<tr><td width='70px'>Site ID</td><td width='5px'>:</td><td>"+dt.site_code+"</td></tr>"+
                                                                            "<tr><td width='70px'>Site Name</td><td width='5px'>:</td><td>"+(dt.site_name||'-')+"</td></tr>"+
                                                                            "<tr><td width='70px'>Vendor ID</td><td width='5px'>:</td><td>"+(dt.vendor_id||'-')+"</td></tr>"+
                                                                            "<tr><td width='70px' valign='top'>Address</td><td width='5px' valign='top'>:</td><td>"+ (dt.address||'-') +"</td></tr>"+
                                                                            "</table>"
                                                                } ,
                                                         latitude: dt.latitude , longitude: dt.longitude });
                                            }
                                        }
					
//                                            $.goMap.fitBounds();
                                        
				}else{
					$.goMap.setMap({latitude: -8.796647, 
							longitude: 119.621416666667, 
						       zoom: 5}); 
                                        n= notyfy({
						layout: 'center',
						text:  'Tracking not found', 
						type: 'error',
						modal: true
						});
					setTimeout(function(){ n.close(); }, 1500);           
				}
                        }
                });
		return false;
		
	});
        
        legend = legend + '</table>'
		   + '</div>';
	$.goMap.createSearchControl('Legend',legend);
	//filter PIC
	$('#doSearchPIC').live("click",function(){
		$.fancybox({
			href:"<?php echo base_url()?>referensi/map/searchPIC/11",type:'ajax',
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
//		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		dateFormat: 'yy-mm-dd',
		onClose: function( selectedDate ) {
		  $( "#date_to" ).datepicker( "option", "minDate", selectedDate );
		}
	      });
	$( "#date_to" ).datepicker({
//		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		dateFormat: 'yy-mm-dd',
		onClose: function( selectedDate ) {
		  $( "#date_from" ).datepicker( "option", "maxDate", selectedDate );
		}
	});
});

        // show hide marker schedule, start, end, check in, check out
        function showHideMarkers($grup) {
            var xxx = $('#'+$grup).attr('checked');
            if(xxx=='checked')
                $.goMap.showHideMarkerByGroup($grup, true);
            else
                $.goMap.showHideMarkerByGroup($grup, false);
            $.goMap.fitBounds('visible');
        }

        //show hide polyline tracking
        function showHideLine(line, ids) {
            var xxx = $('#'+line).attr('checked');
            var a = parseInt(ids);
                if(xxx=='checked') {
                    $.goMap.showHideOverlay('polyline', line, true);
                    for(var k=0; k<=a; k++){
                     $.goMap.showHideMarker(line+'-'+k);
                    }
                }else{
                    $.goMap.showHideOverlay('polyline', line, false);
                    for(var k=0; k<=a; k++){
                     $.goMap.showHideMarker(line+'-'+k);
                    }
                }
                $.goMap.fitBounds('visible');
        }

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
                                                                    <input name="pic" id="pic" value="" readonly="readonly">
									<input type='hidden' name="site_code" id="site_code" value="">		
									<button id="doSearchPIC" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>	
								</div>
							</div>
							
							<div>
								<label>Date:</label>
								<div class="input-append">
                                                                    <input type="text" id="date_from" name="date_from" style="width:110px;margin-right:15px;" placeholder="Date from..." readonly value="<?php echo date('Y-m-d'); ?>"/>
                                                                    <input type="text" id="date_to" name="date_to" style="width:110px;" placeholder="Date to ..." readonly value="<?php echo date('Y-m-d'); ?>"/>
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