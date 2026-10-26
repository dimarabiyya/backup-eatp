<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>file/js/easyui-1.3.5/themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>file/js/easyui-1.3.5/themes/icon.css" />
<script type="text/javascript" src="<?php echo base_url()?>file/js/easyui-1.3.5/jquery.easyui.min.js"></script>

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

        // show hide marker pic & site
        function showHideMarkers($grup) {
            var xxx = $('#'+$grup).attr('checked');
            if(xxx=='checked')
                $.goMap.showHideMarkerByGroup($grup, true);
            else
                $.goMap.showHideMarkerByGroup($grup, false);
            $.goMap.fitBounds('visible');
//            $.goMap.setMap({zoom: 7});
        }

$(function() {
	var peta;
	$("#map").goMap({
		latitude: -8.796647,    
		longitude: 119.621416666667, 
		zoom: 5,
		maptype: 'ROADMAP',
		mapTypeControl: true,
//                maxZoom:7
	});
	
        $.goMap.setMap({maxZoom: 15});
        
	$('#tampilkanTopologi').live("submit",function(e)
	{
		//kirim data
		$.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>referensi/map/show_pic_position", 
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
                            if ($("#pic").val()=="") {
                                $.unblockUI();
                                n= notyfy({
                                            layout: 'center',
                                            text:  'PIC is required.', 
                                            type: 'error',
                                            modal: true
                                            });
                                setTimeout(function(){ n.close(); }, 1500);
                                return false;
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                                alert(errorThrown);
				$.unblockUI();
                        },
                        success: function(data){
                                $.unblockUI();
				$.goMap.clearMarkers();
                                //show schedule
				if (data.jml_link > 0) {
                                    
                                    //show site
                                    //var sites = new Array();
                                    if (data.jml_site > 0) {
                                            dt = 0;
                                            for (var i = 0; i < data.hasil_site.site.length; i++) {
                                                    var dt= {};
                                                    dt = data.hasil_site.site[i];
                                                    $.goMap.createMarker({ group: 'site', id:'site'+i,
                                                                         icon: '<?php echo base_url()?>assets/images/icon/icon_site_small.png',
                                                         html: { 
                                                                    content: "<table width='300px'>"+
                                                                            "<tr><td width='70px'>Site Code</td><td width='5px'>:</td><td>"+dt.site_code+"</td></tr>"+
                                                                            "<tr><td width='70px'>Site Name</td><td width='5px'>:</td><td>"+dt.site_name+"</td></tr>"+
                                                                            "<tr><td width='70px'>Address</td><td width='5px'>:</td><td>"+ dt.site_address +"</td></tr>"+
                                                                            "<tr><td width='70px'>Vendor ID</td><td width='5px'>:</td><td>"+dt.vendor_id+"</td></tr>"+
                                                                            "</table>"
                                                                } ,
                                                         latitude: dt.latitude , longitude: dt.longitude });
                                            }
                                    }
                                    
                                    //show pic position
					for (var i = 0; i < data.hasil_link.site.length; i++) {
						var dt= {};
						dt = data.hasil_link.site[i];
						var status = "";
						if (dt.status == 0 || dt.status=='') {
							status = "Belum dikunjungi";
						}else if (dt.status == 1) {
							status = "Confirm";
						}else if (dt.status == 2) {
							status = "Decline";
						}else if (dt.status == 3) {
							status = "Visited";
						}else if (dt.status == 4) {
							status = "Unschedule";
						}else if (dt.status == 5) {
							status = "Reschedule";
						}else if (dt.status == -5) {
							status = "Deleted";
						}
						$.goMap.createMarker({ group: 'pic_marker', icon: '<?php echo base_url()?>assets/images/icon/red_MarkerP.png',
						     html: { 
								content: "<table width='300px'>"+
                                                                        "<tr><td width='70px'>PIC</td><td width='5px'>:</td><td>"+ dt.created_by+"</td></tr>"+
									"<tr><td width='70px'>Site Code</td><td width='5px'>:</td><td>"+dt.site_code+"</td></tr>"+
									"<tr><td width='70px'>Site Name</td><td width='5px'>:</td><td>"+dt.site_name+"</td></tr>"+
									"<tr><td width='70px' valign='top'>Address</td><td width='5px' valign='top'>:</td><td>"+ dt.site_address +"</td></tr>"+
									"<tr><td width='70px'>Status</td><td width='5px'>:</td><td>"+status+"</td></tr>"+
									"</table>"
							    } ,
						     latitude: dt.latitude , longitude: dt.longitude });
					}
                                        
                                    $.goMap.fitBounds();
				}else{
                                    $.goMap.setMap({latitude: -8.796647, longitude: 119.621416666667, zoom: 5})
                                     n= notyfy({
                                            layout: 'center',
                                            text:  'PIC position not found', 
                                            type: 'error',
                                            modal: true
                                            });
                                    setTimeout(function(){ n.close(); }, 1500);    
				}

//				if (data.jml_link > 0 || data.jml_site > 0) {
//					$.goMap.fitBounds();
//				}else{
//					$.goMap.setMap({latitude: -8.796647, longitude: 119.621416666667, zoom: 5}); 
//				}
				
                        }
                });
		return false;
		
	});
	
	//create map legend
        var legend = '<div style="width: 175px; height: auto; background-color: rgb(255, 255, 255); overflow: auto;">'
		   + '<table>'
			+'<tr><td><input type="checkbox" id="pic_marker" onclick="showHideMarkers(\'pic_marker\')" checked="checked"> <img src="<?php echo base_url('/assets/images/icon/red_MarkerP.png')?>"></td><td>PIC Position</td></tr>'
			+'<tr><td><input type="checkbox" id="site" onclick="showHideMarkers(\'site\')" checked="checked"> <img src="<?php echo base_url('/assets/images/icon/orange_MarkerS.png')?>" style="width: 50%; height: 50%"></td><td>Region Site</td></tr>'
		   + '</table>'
		   + '</div>';
	$.goMap.createSearchControl('Legend',legend);
        
	$('#cariJalan').live("submit",function(e){
		$.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>referensi/map/show_jalan",
                        data: $(e.target).serialize(),
                        dataType: "json",
                        beforeSend:function(){
                                //$('.alert-error,.alert-success').hide();
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                                //alert(errorThrown);
                        },
                        success: function(data){
				
				if (data.jenis ==1) {
					$.goMap.setMap({ 
						address: data.jalan, 
						zoom: 9 
						});
				}else{
					if (data.long == 0) {
						alert("Site ID Not Found");
					}else{
						$.goMap.removeMarker('found_mark'); 
						if (data.jml_site > 0) {
							
							var dt= {}; 							
							dt = data.hasil.site[0];
							
							$.goMap.setMap({ 
								latitude: dt.latitude, 
								longitude: dt.longitude, 
								zoom: 9 
								});
							
							$.goMap.createMarker({ group: 'found_mark', icon:'<?php echo base_url()?>assets/images/icon/'+ dt.icon,
							     html: { 
									content: "<table width='300px'>"+
										"<tr><td width='70px'>Site ID</td><td width='5px'>:</td><td>"+dt.site_id+"</td></tr>"+
										"<tr><td width='70px'>Site Name</td><td width='5px'>:</td><td>"+dt.site_name+"</td></tr>"+
										"<tr><td width='70px'>Vendor ID</td><td width='5px'>:</td><td>"+dt.vendor_id+"</td></tr>"+
										"<tr><td width='70px'>Address</td><td width='5px'>:</td><td>"+ dt.site_address +"</td></tr>"+
										"<tr><td width='70px'>Site Profile</td><td width='5px'>:</td><td>"+dt.site_profile+"</td></tr>"+
										"</table>",
									popup: true
								    } ,
							     latitude: dt.latitude , longitude: dt.longitude });
							
						}
					}
				}
				
			}
		});
		return false;
	});
	
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
                var reg = $('#territory').val();
                var clus= $('#cluster_ops_id').val();
		$('#region_id').val(reg);
                $('#cluster_id').val(clus);
		$.fancybox.close();
		return false;
	});
        
	// filter date
	var val = [];
        $('#cc').combotree('setValue', val);
	
	$( "#date_from" ).datepicker({
		dateFormat: 'yy-mm-dd'
	});
});
</script>
<div class="innerLR">
	<div class="widget">	
		<div class="widget-head">
			<h4 class="heading"><i></i> GIS PIC Position</h4>
		</div>
		
		<div class="widget-body">
			<div class="filter-bar" style="margin-bottom: 0px;">
				<div class="row-fluid">
					<div class="span12">
						<form id="tampilkanTopologi" method="post">
                                                    <div>
								<label>PIC:</label>
								<div class="input-append">
                                                                    <input name="pic" id="pic" value="" readonly="readonly" onclick="berapaZoom()">
									<input type='hidden' name="site_code" id="site_code" value="">		
                                                                        <input type='hidden' name="region_id" id="region_id">	
                                                                        <input type='hidden' name="cluster_id" id="cluster_id">	
									<button id="doSearchPIC" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>	
								</div>
							</div>
<!--							<div>
								Region :
								<div class="input-append">
                                                                    <?php
                                                                        //echo form_dropdown("region_id",$regions,'');
                                                                    ?>
									<input id="cc" name="territory" class="span12 easyui-combotree" url="<?php //echo base_url()?>index.php/gis/territory_json_new" cascadeCheck="false" style="width:150%;margin-bottom: 0px;">
								</div>
							</div>
							
							<div>
								<label>PIC:</label>
								<div class="input-append">
									<select name="pic[]" class="easyui-combogrid" style="width:250px" data-options="
										panelWidth: 500,
										multiple: true,
										idField: 'user_id',
										textField: 'user_name',
										url: '<?php //echo base_url()?>referensi/map/show_pic',
										method: 'get',
										columns: [[
										    {field:'ck',checkbox:true},
										    {field:'user_id',title:'User ID',width:50},
										    {field:'user_name',title:'User Name',width:90}
										]],
										fitColumns: true
									    ">
									</select>
								</div>
							</div>-->
							
							<div>
								<label>Date:</label>
								<div class="input-append">
									<input type="text" id="date_from" name="date_from" style="width:110px;margin-right:15px;" value="<?php echo date("Y-m-d")?>" readonly/>
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