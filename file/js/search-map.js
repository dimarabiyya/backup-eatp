$(function() {
	$('#cariJalan').live("submit",function(e){
		
		$.ajax({
                        type: "POST",
                        url: baseurl+"referensi/map/show_jalan",
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
				if (data.jalan =='') {
					alert("Search Field is required");
					return;
				}
				if (data.jenis ==1) {
					$.goMap.setMap({ 
						address: data.jalan, 
						zoom: 15 
						});
					$.goMap.createMarker({  
						address: data.jalan
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
								zoom: 15 
								});
							
							$.goMap.createMarker({ group: 'found_mark', icon:baseurl+'assets/images/icon/'+ dt.icon,
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
	
	
});