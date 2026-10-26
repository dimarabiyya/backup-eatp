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
	var jenis_search = "";
	$("#map").goMap({
		latitude: -8.796647, 
		longitude: 119.621416666667, 
		zoom: 5,
		maptype: 'ROADMAP',
		mapTypeControl: true
	});
	
	var legend = '<div style="width: 210px; height: 400px; background-color: rgb(255, 255, 255); overflow: auto;">'
		   + '<table>'
		   + '<tr><td><img src="<?php echo base_url('/assets/images/icon/tower_red.gif')?>" style="width: 70%; height: auto"></td><td>Nom Site</td></tr>'
			<?php
			foreach($gambar_marker as $row){
			?>
			+ '<tr><td><img src="<?php echo base_url('/assets/images/icon/'.$row['gis_marker'])?>" style="width: 60%;height: auto;"></td><td style="font-family: Roboto,Arial,sans-serif;"><?php echo ucwords(strtolower($row['company_name']));?></td></tr>'
			<?php }?>
		   + '</table>'
		   + '</div>';
	$.goMap.createSearchControl('Legend',legend);
	
	$('#tampilkanTopologi').live("submit",function(e)
	{
		//kirim data
		$.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>referensi/map/show_kandidat",
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
                                alert(errorThrown);
				$.unblockUI();
                        },
                        success: function(data){
                                $.unblockUI();
				
				if (data.status == 'ERROR') {
					alert(data.pesan);
					return;
				}
				
				
				$.goMap.clearMarkers();
				$.goMap.clearOverlays('circle');
				
				$.goMap.setMap({ 
					latitude: data.hasil.latitude, 
					longitude: data.hasil.longitude, 
					zoom: 12 
				});
				
				$.goMap.createCircle({
					id: 'lingkaran',
					latitude: data.hasil.latitude, 
					longitude: data.hasil.longitude, 
					radius: data.hasil.radius
				});
				
				if (data.jml_site > 0) {
					
					var dt= {}; 
					for (var i = 0; i < data.hasil.site.length; i++) {
						dt = data.hasil.site[i];
						var konten = "";
						if (dt.tp_name == "nomsite") {
							konten = "<table width='300px'>"+
									"<tr><td colspan=3><b>"+ dt.site_code +"</b> <a href='<?php echo base_url()?>site_info/edit/"+dt.id+"'> <button>Detail</button><a></td></tr>"+
									"<tr><td width='70px'>Site Name</td><td width='5px'>:</td><td>"+dt.site_name+"</td></tr>"+
									"<tr><td width='70px'>Vendor ID</td><td width='5px'>:</td><td>"+dt.vendor_id+"</td></tr>"+
									"<tr><td width='70px'>Nom-Lat / Lon</td><td width='5px'>:</td><td>"+dt.latitude+" / "+dt.longitude+"</td></tr>"+
									"<tr><td width='70px'>Nom-Address</td><td width='5px'>:</td><td>"+dt.address+"</td></tr>"+
									"<tr><td width='70px'>Actual-Lat / Lon</td><td width='5px'>:</td><td>"+dt.actual_latitude+" / "+dt.actual_longitude+"</td></tr>"+
									"<tr><td width='70px'>Actual-Address</td><td width='5px'>:</td><td>"+dt.actual_address+"</td></tr>"+
									"<tr><td width='70px'>Jarak</td><td width='5px'>:</td><td>"+dt.distance +" <b>meter</b></td></tr>"+
									"</table>";
							
							/*konten = "<table width='300px'>"+
									"<tr><td><b>"+dt.tp_name+" "+ dt.id +"</b> <a href='<?php echo base_url()?>site_info/edit/"+dt.id+"'> <button>Detail</button><a></td></tr>"+
									"<tr><td><b>Site Name: </b>"+dt.site_name+"</td></tr>"+
									"<tr><td><b>Nom-Lat: </b>"+dt.latitude+", <b>Nom-Lon: </b>"+dt.longitude+",</td></tr>"+
									"<tr><td><b>Nom-Address: </b>"+dt.address+"</td></tr>"+
									"<tr><td><b>Actual-Lat: </b>"+dt.actual_latitude+", <b>Actual-Lon: </b>"+dt.actual_longitude+"</td></tr>"+
									"<tr><td><b>Actual-Address: </b>"+dt.actual_address+"</td></tr>"+
									"<tr><td><b>Actual-Address: </b>"+dt.actual_address+"</td></tr>"+
									"<tr><td><b>Jarak: </b>"+(dt.distance * 1000).toFixed(2)+" <b>meter</b></td></tr>"+
									"</table>";*/
						}else if (dt.tp_name == "smfsite") {
							konten = "<table width='300px'>"+
									"<tr><td colspan=3><b>"+ dt.site_code +"</b> <a href='<?php echo base_url()?>site_info/view/"+dt.site_id+"' target='_blank'> <button>Detail</button><a></td></tr>"+
									"<tr><td width='70px'>Site Name</td><td width='5px'>:</td><td>"+dt.site_name+"</td></tr>"+
									"<tr><td width='70px'>Vendor ID</td><td width='5px'>:</td><td>"+dt.vendor_id+"</td></tr>"+
									"<tr><td width='70px'>Lat / Lon</td><td width='5px'>:</td><td>"+dt.latitude+" / "+dt.longitude+"</td></tr>"+
									"<tr><td width='70px'>Address</td><td width='5px'>:</td><td>"+dt.address+"</td></tr>"+
									"<tr><td width='70px'>Actual-Lat / Lon</td><td width='5px'>:</td><td>"+dt.actual_latitude+" / "+dt.actual_longitude+"</td></tr>"+
									"<tr><td width='70px'>Actual-Address</td><td width='5px'>:</td><td>"+dt.actual_address+"</td></tr>"+
									"<tr><td width='70px'>Jarak</td><td width='5px'>:</td><td>"+dt.distance +" <b>meter</b></td></tr>"+
									"</table>";
						}else{
							
							var instatus = ""; var tbl = "";
							if (dt.kandidat == "1") {
								instatus = "Sudah Kandidat <button class='prelease' nomor='"+dt.id+"' site_id='"+data.site_id+"'>Release</button>";
							}else if (dt.kandidat == "2") {
								instatus = "Verified";
							}else if (dt.kandidat == "3") {
								instatus = "Selected Candidate";
							}else{
								instatus = "<button class='pkandidat' nomor='"+dt.id+"' site_id='"+data.site_id+"'>Candidate</button>";
							}
							
							///////////////////////////////////////
							konten = "<table width='300px'>"+
										"<tr><td colspan=3><b>"+ dt.tp_name +"</b>"+ tbl +"</td></tr>"+
										"<tr><td width='70px'>Site Name</td><td width='5px'>:</td><td>"+dt.site_name+"</td></tr>"+
										"<tr><td width='70px'>Vendor ID</td><td width='5px'>:</td><td>"+dt.vendor_id+"</td></tr>"+
										"<tr><td width='70px'>Nom-Lat / Lon</td><td width='5px'>:</td><td>"+dt.latitude+" / "+dt.longitude+"</td></tr>"+
										"<tr><td width='70px'>Nom-Address</td><td width='5px'>:</td><td>"+dt.address+"</td></tr>"+
										"<tr><td width='70px'>Actual-Lat / Lon</td><td width='5px'>:</td><td>"+dt.actual_latitude+" / "+dt.actual_longitude+"</td></tr>"+
										"<tr><td width='70px'>Actual-Address</td><td width='5px'>:</td><td>"+dt.actual_address+"</td></tr>"+
										"<tr><td width='70px'>Jarak</td><td width='5px'>:</td><td>"+dt.distance +" <b>meter</b></td></tr>"+
										"<tr><td width='70px'>Status</td><td width='5px'>:</td><td>"+ instatus +"</td></tr>"+
										"</table>";
						}
						
						$.goMap.createMarker({ group: 'site_mark_'+i, icon:'<?php echo base_url()?>assets/images/icon/'+ dt.icon,
						     html: { 
								content: konten
							    } ,
						     latitude: dt.latitude , longitude: dt.longitude });
					}
				}
                        }
                });
		return false;
		
	});
	
	//proses pilih
	$('.pkandidat').live("click",function()
	{
		$.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>referensi/map/pilih_kandidat",
                        data: {nomor:$(this).attr('nomor'),site_id:$(this).attr('site_id')},
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
                                alert(errorThrown);
				$.unblockUI();
                        },
                        success: function(data){
                                $.unblockUI();
				
				if (data.status == 'ERROR') {
					alert(data.pesan);
					return;
				}
				
				$(this).attr('class', 'prelease');
				$(this).html('Sudah Kandidat, Release');
				n= notyfy({
					layout: 'center',
					text:  'Success', 
					type: 'success',
					modal: true
				});
				setTimeout(function(){ n.close(); }, 3000);
				return;
                        }
                });
	});
	
	//proses release
	$('.prelease').live("click",function()
	{
		$.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>referensi/map/prelease_kandidat",
                        data: {nomor:$(this).attr('nomor'),site_id:$(this).attr('site_id')},
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
                                alert(errorThrown);
				$.unblockUI();
                        },
                        success: function(data){
                                $.unblockUI();
				
				if (data.status == 'ERROR') {
					alert(data.pesan);
					return;
				}
				
				$(this).attr('class', 'pkandidat');
				$(this).html('Candidate');
				n= notyfy({
					layout: 'center',
					text:  'Success', 
					type: 'success',
					modal: true
				});
				setTimeout(function(){ n.close(); }, 3000);
				return;
                        }
                });
	});
	
	///search Site	
	$('#doSearch').live("click",function(){
		jenis_search = $(this).attr('id');
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
			$("#site_idx").val( row.site_code );
			$("#nomsite").val( row.site_id );
		}
		$.fancybox.close();
		return false;
    })
});
</script>
<div class="innerLR">
	<div class="widget">	
		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i> GIS - Candidate Selection</h4>
		</div>
		
		<div class="widget-body">
			<div class="filter-bar" style="margin-bottom: 0px;">
				<div class="row-fluid">
					<div class="span9">
						<form id="tampilkanTopologi" method="post">
							<div>
								<label>Site ID:</label>
								<div class="input-append">
									<input name="site_code" id="site_idx" value="" readonly>
									<button id="doSearch" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
								</div>
							</div>
							
							<div>
								<label>Radius:</label>
								<div class="input-append" style="width:100px;">
									<input class="span9" type="text" id="radius" name="radius" style="min-height:24px;" /> 
									<span class="add-on">meter</span>
								</div>
							</div>
							<div>
								<div class="">
									<div class="form-actions" style="text-align: left;">
										<button type="submit" id="btnShow" class="btn btn-icon btn-primary btn-small glyphicons circle_ok" style="margin-top: -20px;"><i></i>Show</button>
									</div>
								</div>
							</div>
							
						</form>
					</div>
				</div>	
			</div>
			
			<div id="map"></div>
		</div>
	</div>
</div>
