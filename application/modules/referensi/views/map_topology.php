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

        //show hide polyline tracking
        function showHideLine() {
            var xxx = $('#link').attr('checked');
            var count_link = $('#count_link').val();
//            var a = parseInt(ids);
//                if(xxx=='checked') {
//                    $.goMap.showHideOverlay('polyline', 'site_link', true);
//                    console.log('Cek');
//                }else{
//                    $.goMap.showHideOverlay('polyline', 'site_link', false);
//                    console.log('Uncek');
//                }
                
                if(xxx=='checked') {
                    for (var i=0; i<count_link; i++) {
			$.goMap.showHideOverlay('polyline', 'site_link_'+i, true);
                         //console.log('Cek');
                    } 
                }else {
                    for (var i=0; i<count_link; i++) {
                        $.goMap.showHideOverlay('polyline',  'site_link_'+i, false);
                         //console.log('uncek');
                    }

                }  
        }

$(function() {
	//latitude: -8.1929906,  longitude: 115.1383717, 
	var peta;
	$("#map").goMap({
		latitude: -8.796647, 
		longitude: 119.621416666667, 
		zoom: 5,
		maptype: 'ROADMAP',
		mapTypeControl: true
	});
	
	var legend = '<div style="width: 210px; height: 70px; background-color: rgb(255, 255, 255); overflow: auto;">'
		   + '<table>'
		   + '<tr style="padding-top:25px"><td>&nbsp;<img src="<?php echo base_url('/assets/images/icon/merah.png')?>" ></td><td>BTS</td></tr>'
//		   + '<tr><td>&nbsp;<img src="<?php //echo base_url('/assets/images/icon/hijau.png')?>"></td><td>BB;BTS</td></tr>'
//		   + '<tr><td>&nbsp;<img src="<?php //echo base_url('/assets/images/icon/kuning.png')?>"></td><td>Others</td></tr>'
		   + '<tr><td>&nbsp;<span style="color:blue; font-weight:bold; font-size:20pt">&#10145; </span></td><td>Link Transmisi Polyline</td></tr>'
		   + '</table>'
		   + '</div>';
	$.goMap.createSearchControl('Legend',legend);
	
//	$("#link").live('click', function () {
//		$("#tampilkanTopologi").submit();
//	});
	
	$('#tampilkanTopologi').live("submit",function(e)
	{
		//kirim data
		$.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>referensi/map/show_topology",
                        data: $(e.target).serialize(),
                        dataType: "json",
                        beforeSend:function(){
                                //$('.alert-error,.alert-success').hide();
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
                            //alert(errorThrown);   
                            $.unblockUI();
                            notyfy({layout:'center', type:'error', timeout:5000, text:'Error!'});
                        },
                        success: function(data){
                                $.unblockUI();
				$.goMap.clearMarkers();
				$.goMap.clearOverlays('polyline');
				
				if (data.jml_site > 0) {
					
					var dt= {}; 
					for (var i = 0; i < data.hasil.site.length; i++) {
						dt = data.hasil.site[i];
						$.goMap.createMarker({ group: 'site_mark_'+i, icon:'<?php echo base_url()?>assets/images/icon/'+ dt.icon,
						     html: { 
								content: "<table width='300px'>"+
									"<tr><td width='70px'>Site ID</td><td width='5px'>:</td><td>"+dt.site_code+"</td></tr>"+
									"<tr><td width='70px'>Site Name</td><td width='5px'>:</td><td>"+(dt.site_name||'-')+"</td></tr>"+
									"<tr><td width='70px'>Vendor ID</td><td width='5px'>:</td><td>"+(dt.vendor_id||'-')+"</td></tr>"+
									"<tr><td width='70px' valign='top'>Address</td><td width='5px' valign='top'>:</td><td valign='top'>"+ (dt.site_address||'-') +"</td></tr>"+
									"<tr><td width='70px'>Site Profile</td><td width='5px'>:</td><td>"+(dt.site_profile||'-')+"</td></tr>"+
									"</table>"
							    } ,
						     latitude: dt.latitude , longitude: dt.longitude });
					}
				} else {
                                    console.log('Tidak ada site');
                                }
				var j=0;
				if (data.jml_link >0) {
					
					var dt= {}; 
					for (var i = 0; i < data.hasil_link.site.length; i++) {
						dt = data.hasil_link.site[i];
						
						//console.log(i + " lat: "+ dt.latitude1 +" lon: "+dt.longitude1);
						//console.log(i + " lat: "+ dt.latitude2 +" lon: "+dt.longitude2);
						
						$.goMap.createPolyline({
								id: 'site_link_'+i,
								color: '#2b26fb',
								opacity: 0.5,
								weight:	2,
								showSingleArrow: true,
								titel:   "<table width='300px'>"+
									"<tr><td width='120px'>Link ID</td><td width='5px'>:</td><td>"+dt.link_code_id+"</td></tr>"+
									"<tr><td width='120px'>Link Type</td><td width='5px'>:</td><td>"+(dt.lookup_desc||'-')+"</td></tr>"+
									"<tr><td width='120px'>Link Capacity</td><td width='5px'>:</td><td>"+(dt.capacity||'-')+"</td></tr>"+
									"<tr><td width='120px'>Tx Freq </td><td width='5px'>:</td><td>"+ (dt.frequency1 || '-') +"</td></tr>"+
									"<tr><td width='120px'>Rx Freq</td><td width='5px'>:</td><td>"+ (dt.frequency2 ||'-')+"</td></tr>"+
									"<tr><td width='120px'>Diameter NE</td><td width='5px'>:</td><td>"+(dt.diameter1||'-')+"</td></tr>"+
									"<tr><td width='120px'>Diameter FE</td><td width='5px'>:</td><td>"+(dt.diameter2||'-')+"</td></tr>"+
									"</table>",
								coords:	[{
										latitude: dt.latitude1,
										longitude: dt.longitude1
									},{ 
										latitude: dt.latitude2, 
										longitude: dt.longitude2
									}]
							});
                                                        j++;
					}
                                       $('#count_link').val(j);
                                       console.log($('#count_link').attr('value'));
				} else {
                                    console.log('Tidak ada link :-(');
                                }
				if (data.jml_site != 0 || data.jml_link != 0) {
                                    $.goMap.fitBounds();
				}else{
                                    $.goMap.setMap({latitude:-1.9112665350965847, longitude:117.7294921875, zoom: 5}); 
				}
				
                        }
                });
		return false;
		
	});
	
	$('#doSearch').live("click",function(){
		$.fancybox({
			href:"<?php echo base_url()?>referensi/site_kandidat/searchSite",type:'ajax',
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
	
	$("#search_site").live('submit', function () {
		
		var row = $('#dg').datagrid('getSelected');
		if (row){
			//Document.searchSiteID( row.site_id );
			$(".txtSearch").val( row.site_code );
			//$("#nomsite").val( row.site_id );
		}
		$.fancybox.close();
		return false;
	});
});
</script>
<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget">	
		<div class="widget-head">
			<h4 class="heading"><i></i>GIS - Network Topology</h4>
		</div>
		
		<div class="widget-body">
			<div class="filter-bar">
				<div class="row-fluid">
					<div class="span9">
						<form id="tampilkanTopologi" method="post">
							<div>
								<label>Region:</label>
								<div class="input-append">
									<select id="region" name="region">
										<option value=''>ALL</option>
										<?php
										$region = get_instance()->muser->getRegionByID();
										foreach($region as $r){	
											echo"<option value='$r->region_id'>$r->region_name</option>";
										}
										?>
									</select>
								</div>
							</div>
							
							<div>
								<div class="input-append">
									<label class="checkbox" for="site">
										<input type="checkbox" class="checkbox" id="site" name="site" checked="checked" disabled />
										Site Smartfren
									</label>
								</div>
							</div>
							<div>
								<div class="input-append">
									<label class="checkbox" for="link">
                                                                            <input type="checkbox" class="checkbox" id="link" name="link" checked="checked" onclick="showHideLine()"/>
										Show Link
									</label>
								</div>
							</div>
							<div>
								<div class="input-append">
									<div class="form-actions" style="text-align: left;">
										<button type="submit" id="btnShow" class="btn btn-icon btn-primary btn-small glyphicons circle_ok" style="margin-top: -7px;"><i></i>Show</button>
									</div>
								</div>
							</div>
							
						</form>
					</div>
					<input type="hidden" id="count_link" value="">
				</div>
			</div>
			
			<div id="map"></div>
		</div>
	</div>
</div>