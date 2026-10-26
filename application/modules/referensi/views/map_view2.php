<?php
/* Map for Candidate Selection
 * Google Map API 3
 * by Wirda Hilwa
 */
?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
<script type="text/javascript" src="<?php echo site_url('/assets/js/jquery-1.4.3.min.js'); ?>"></script>
<script type="text/javascript">
$('.container-fluid:first').toggleClass('menu-hidden');
$('#menu').toggleClass('hidden-phone');

var peta;
var pertama = 0;
var jenis = "biru";
var jen = "biru";
var idx = new Array();
var id_encx = new Array();
var judulx = new Array();
var latitudex = new Array();
var longitudex = new Array();
var actuallatitudex = new Array();
var actuallongitudex = new Array();
var site_id_tpx = new Array();
var rankx = new Array();
var jarakx = new Array();
var desx = new Array();
var actualdesx = new Array();
var status_kandidatx = new Array();
var partner_idx = new Array();
var i,j;
var url;
var gambar_tanda;
var nomsite= <?php echo (isset($_POST['nomsite']) ? $_POST['nomsite'] : 0) ?>;
var radiusx=<?php echo ($radiuss) ?>;
var radius_peta= radiusx;
var lokasi_nom_x= 0;
var lokasi_nom_y= 0;
var site_id_nom= 23;
// peta = new google.maps.Map(document.getElementById("petaku"),petaoption);
function peta_awal(){
    <?php
    
    if($koord_nom_lat== null){
        $koord_nom_lat=-8.796647; // default center disini 
    }
    if($koord_nom_long== null){
        $koord_nom_long=119.621416666667;
    }
        ?>
    var jakarta = new google.maps.LatLng(parseFloat(<?php echo $koord_nom_lat;?>),parseFloat(<?php echo $koord_nom_long;?>));
    var petaoption = {
        zoom: 10,
        center: jakarta,
	mapTypeId: google.maps.MapTypeId.ROADMAP,
	  mapTypeControl: false
        };

    var myCity = new google.maps.Circle({
      center:jakarta,
      radius:radius_peta,
      strokeColor:"#ed1d2a",
      strokeOpacity:0.8,
      strokeWeight:2,
      fillColor:"#ed1d2a",
      fillOpacity:0.4
      });

    
    peta = new google.maps.Map(document.getElementById("petaku"),petaoption);
    
    
    ///start legend
	var legend = '<div style="width: 210px; height: 400px; background-color: rgb(255, 255, 255); overflow: auto;">'
		   + '<table>'
		   + '<tr><td>&nbsp;<img src="<?php echo base_url('/assets/images/icon/merah.png')?>" style="width: 15px; height: auto"></td><td>Nom Site</td></tr>'
		   + '<tr><td>&nbsp;<img src="<?php echo base_url('/assets/images/icon/icon_smart.png')?>" style="width: 15px; height: auto"></td><td>Site Existing</td></tr>'
			<?php
			foreach($gambar_marker as $row){
			?>
			+ '<tr><td ><img src="<?php echo base_url('/assets/images/icon/'.$row['gis_marker'])?>" style="width: 15px;height: auto;"></td><td><?php echo $row['partner_name'];?></td></tr>'
			<?php }?>
		   + '</table>'
		   + '</div>';
	
	var form = document.createElement('FORM');
	$(form).attr('id', 'cariJalan');
	
	var divAppend = document.createElement('DIV');
	$(form).addClass('input-append');
	
	var controlSearch = $('<select class="span1" id="jeniscari" name="jenis" style="width:80px;">'
				+'<option value="1">Street</option>'
				+'<option value="2">Site ID</option>'
				+'</select>'
				+'<input class="txtSearch span3" id="appendedInputButtons" name="key" type="text" placeholder="Search .." />'
				+'<button class="btn" id="doSearchLegend" type="button" style="display:none;"><i class="icon-search"></i></button>'
				+'<button class="btn gmap-control" type="submit">Search</button> &nbsp; ');
	
	var controlDiv = document.createElement('DIV');
	$(controlDiv).addClass('geoSearchBox');
	$(controlDiv).attr('style', 'margin-top:5px;margin-right:40px;border: solid black 1px;padding:3px;background-color: #cccccc;');
	
	var controlUI = $('<button class="btn btn-danger gmap-control" type="button">Legend</button>');
	var controlUI = document.createElement('button');
	$(controlUI).addClass('btn btn-danger gmap-control');
	$(controlUI).attr('type','button');
	$(controlUI).html('Legend');
			   
	var controlLegend = document.createElement('DIV');
	$(controlLegend).addClass('gmap-control-legend');
	$(controlLegend).html(legend);
	$(controlLegend).hide();
	
	
	$(divAppend).append(controlSearch);
	$(divAppend).append(controlUI);
	$(divAppend).append(controlLegend);
	$(form).append(divAppend);
	$(controlDiv).append(form);
	
	$(controlUI)
		.mouseenter(function() {
			$(controlLegend).show();
		})
		.mouseleave(function() {
			$(controlLegend).hide();
	});
    
	$(controlLegend)
		.mouseenter(function() {
			$(controlLegend).show();
	})
	.mouseleave(function() {
	    $(controlLegend).hide();
	});
	
	peta.controls[google.maps.ControlPosition.TOP_RIGHT].push(controlDiv);
	///end legend
    
    
    
    myCity.setMap(peta);
    ambildatabase('awal');
}

function set_icon(jenisnya){
    switch(jenisnya){
        case 0:
            gambar_tanda = "<?php echo base_url('/assets/images/icon/icon_smart.png'); ?>";
            break;
        case 6:
            gambar_tanda = "<?php echo base_url('/assets/images/icon/tower_blue.gif'); ?>";
            break;
		<?php foreach($gambar_marker as $rowtp){?>
        case <?php echo $rowtp['partner_id'];?>:
            gambar_tanda = "<?php echo base_url("/assets/images/icon/".$rowtp['gis_marker']); ?>";
            break;
		<?php } ?>
		default:
			gambar_tanda = "<?php echo base_url('/assets/images/icon/merah.png'); ?>";
    }
}
function ambildatabase(akhir){
    if(akhir=="akhir"){
        url = "<?php echo site_url('/site_info/map?akhir=1'); ?>";
    }else{
        url = "<?php echo site_url('/site_info/map?akhir=0'); ?>";
    }
    //id, tp_name,site_name,address,distance,latitude,longitude
	j=0;
    <?php 
    $i=0;
    for($i=0;$i<sizeof($petak);$i++){?>
        idx[j] = "<?php echo $petak[$i]['id'];?>";
        id_encx[j] = "<?php echo base64_encode($petak[$i]['id']);?>";
        judulx[j] = "<?php echo $petak[$i]['tp_name'];?>";
        latitudex[j] = "<?php echo $petak[$i]['latitude'];?>";
        longitudex[j] = "<?php echo $petak[$i]['longitude'];?>";
        actuallatitudex[j] = "<?php echo $petak[$i]['actual_latitude'];?>";
        actuallongitudex[j] = "<?php echo $petak[$i]['actual_longitude'];?>";
        site_id_tpx[j] = "<?php echo $petak[$i]['site_id_tp'];?>";
        rankx[j] = "<?php echo $petak[$i]['rank'];?>";
        jarakx[j] = (<?php echo $petak[$i]['distance'];?>).toFixed(2);
        status_kandidatx[j] = <?php echo $petak[$i]['kandidat'];?>;
        partner_idx[j] = <?php echo $petak[$i]['partner_id'];?>;
		<?php $des1= ($petak[$i]['address']);?>
		<?php $des2= ($petak[$i]['actual_address']);?>
        // desx[j] = "<?php echo str_replace(array('\'','"','<', '>', '&', '{', '}', '*', "\n"), array(' '), $des1);?>";
        // actualdesx[j] = "<?php echo str_replace(array('\'','"','<', '>', '&', '{', '}', '*', ',', "\n"), array(' '), $des2);?>";
        //desx[j] = "<?php echo PREG_REPLACE("/[^0-9a-zA-Z]/i", ' ', $des1);?>";
        //actualdesx[j] = "<?php echo PREG_REPLACE("/[^0-9a-zA-Z]/i", ' ', $des2);?>";
	
	desx[j] = "<?php echo trim(preg_replace('/\s\s+/', ' ', $des1));?>";
	actualdesx[j] = "<?php echo trim(preg_replace('/\s\s+/', ' ', $des2));?>";
		if(judulx[j]=='nomsite'){
			lokasi_nom_x=parseFloat(<?php echo $petak[$i]['latitude'];?>);
			lokasi_nom_y=parseFloat(<?php echo $petak[$i]['longitude'];?>);
			site_id_nom=id_encx[j];
			console.log('x='+lokasi_nom_x+' y='+lokasi_nom_y);
		}    
		jen="<?php echo $petak[$i]['tp_name'];?>";
		//set_icon(jen.trim());
		if(status_kandidatx[j]==3 && judulx[j]!='nomsite'){
			set_icon(6);
		}else if(judulx[j]=='nomsite'){
			set_icon(10000000);
		}else if(judulx[j]=='smfsite'){
			set_icon(0);
		}else{
			set_icon(partner_idx[j]);		
		}
		var point = new google.maps.LatLng(
			parseFloat(<?php echo $petak[$i]['latitude'];?>),
			parseFloat(<?php echo $petak[$i]['longitude'];?>));
		tanda = new google.maps.Marker({
			position: point,
			map: peta,
			icon: gambar_tanda
		});
        
		setinfo(tanda,j);

		j++;
	<?php    
    }
    ?>
}

function setinfo(tanda, nomor){
    google.maps.event.addListener(tanda, 'click', function() {
	var sitee = idx[nomor];
	if(judulx[nomor]=='nomsite'){
            var sitee = "<?php echo $nomname?>";
	    linkdetil= "<a href='<?php echo site_url('/site_info/edit/"+idx[nomor]+"');?>'><button>Detail</button></a>. ";
            link_kandidat= "";
            info_custom= " <br>Nom-Lat: "+latitudex[nomor]+ " .  Nom-Lon: "+longitudex[nomor]+"</b> <br>Nom-Adress: "+desx[nomor]+" <br>Actual-Lat: "+actuallatitudex[nomor]+ " .  Actual-Lon: "+actuallongitudex[nomor]+"</b> <br>Actual-Adress: "+actualdesx[nomor]+"<br> ";
        }else if(judulx[nomor]=='smfsite'){
            linkdetil= "<a href='<?php echo site_url('/site_info/edit/"+idx[nomor]+"');?>'><button>Detail</button></a>. ";
            link_kandidat= "";
            info_custom= " <br>Lat: "+latitudex[nomor]+ " .  Lon: "+longitudex[nomor]+"</b> <br>Adress: "+desx[nomor]+" <br>Actual-Lat: "+actuallatitudex[nomor]+ " .  Actual-Lon: "+actuallongitudex[nomor]+"</b> <br>Actual-Adress: "+actualdesx[nomor]+"<br> ";
        }else if (status_kandidatx[nomor]==1){
            linkdetil= "";
            link_kandidat= " Status: Sudah Kandidat <a href='<?php echo site_url('/referensi/map/release_kandidat?nomor="+id_encx[nomor]+"&site_id="+site_id_nom+"');?>'><button>Release</button></a>";
            info_custom= " - <b>"+site_id_tpx[nomor]+" </b><br>Lat: "+latitudex[nomor]+ " .  Lon: "+longitudex[nomor]+"</b> <br>Adress: "+desx[nomor]+"<br> Rank: "+rankx[nomor]+"<br> ";
        }else if (status_kandidatx[nomor]==2){
            linkdetil= "";
            link_kandidat= " Status: Verified";
            info_custom= " - <b>"+site_id_tpx[nomor]+" </b><br>Lat: "+latitudex[nomor]+ " .  Lon: "+longitudex[nomor]+"</b> <br>Adress: "+desx[nomor]+"<br> Rank: "+rankx[nomor]+"<br> ";
        }else if (status_kandidatx[nomor]==3){
            linkdetil= "";
            link_kandidat= " Status: Selected Candidate";
            info_custom= " - <b>"+site_id_tpx[nomor]+" </b><br>Lat: "+latitudex[nomor]+ " .  Lon: "+longitudex[nomor]+"</b> <br>Adress: "+desx[nomor]+"<br> Rank: "+rankx[nomor]+"<br> ";
        }else{
            linkdetil= "";
            link_kandidat= " <a href='<?php echo site_url('/referensi/map/kandidat?nomor="+id_encx[nomor]+"&site_id="+site_id_nom+"');?>'><button>Candidate</button></a> ";
            info_custom= " - <b>"+site_id_tpx[nomor]+" </b><br>Lat: "+latitudex[nomor]+ " .  Lon: "+longitudex[nomor]+"</b> <br>Adress: "+desx[nomor]+"<br> Rank: "+rankx[nomor]+"<br> ";
        }

		var infowindow = new google.maps.InfoWindow({
		content:"<b>"+judulx[nomor]+" "+sitee+"</b>"+info_custom+'Jarak: '+jarakx[nomor]*1000+ " m. "+linkdetil+link_kandidat
		});

		google.maps.event.addListener(tanda, 'click', function() {
		infowindow.open(peta,tanda);
		});
    });
}

$(function() {
	peta_awal();
});

function validateForm()
{
var x=document.forms["formfilter"]["radius"].value;
if (isNaN(x))
  {
  alert("Please enter a number");
  return false;
  }
}

$(document).ready(function() {
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
			$("#site_idx").val( row.site_code );
			$("#nomsite").val( row.site_id );
		}
		$.fancybox.close();
		return false;
    });
});
</script>
<style>
#petaku img{
	max-width:none;
}

.combo-text {margin-bottom: 0px !important; padding:0px !important;}

.gmap-control-container {
    margin: 5px;
}
.gmap-control {
    cursor: pointer;
    font-family: Arial,sans-serif;
    user-select: none;
}
.gmap-control-active {
    background-color: -moz-linear-gradient(center top , #6D8ACC, #7B98D9);
    background-color: #6D8ACC;
    color: #fff;
    font-weight: bold;
    border: 1px solid #678AC7;
}
.gmap-control-legend {
    position: absolute;
    text-align: left;
    z-index: -1;
    top: 30px;
    right: 0;
    width: 200px;
    font-size: 10px;
    background: #FEFEFE;
    border: 1px solid #A9BBDF;
    padding: 10px;
    font-size: 14px;
    box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.35);
}
.gmap-control-legend ul {
    margin: 0;
    padding: 0;
    list-style-type: none;
}
.gmap-control-legend li {
    line-height: 160%;
}
</style>
<!-- GIS -End-->
<body onload="peta_awal()">
<div class="heading-buttons">
	
</div>
<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget">	
		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i> Map</h4>
		</div>
		
		<div class="widget-body">
			<div class="filter-bar">
				<form id="formfilter" action="<?php echo site_url('/referensi/map');?>" method="POST" onsubmit="return validateForm()">
					<div>
						<label>Site ID:</label>
						<div class="input-append">
							<input name="site_code" id="site_idx" value="" class="input-medium" readonly>
							<button id="doSearch" class="btn btn-small" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
						</div>
					</div>
					
					<div>
						<label>Radius:</label>
						<div class="input-append">
							<input class="span2" type="text" id="radius" name="radius" placeholder="Radius(m)"> 
							<span class="add-on">M</span>
						</div>
					</div>
					
					<div>
						
						<div class="input-append">
							<div class="form-actions" style="text-align: left;">
								<button type="submit" id="btnShow" class="btn btn-icon btn-primary btn-small glyphicons circle_ok" ><i></i>Show</button>
							</div>
							<input type='hidden' name='nomsite' id="nomsite" />
						</div>
						<label> <?php IF($nomname!=null) { echo "Site ID: <b>".$nomname."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Radius: <b>".$radiuss."</b>"; }; ?></label>
					</div>
					<div class="clearfix"></div>
				</form>
			</div>
			
			<div id="petaku" style="height:700px;" ></div>
			
			

		</div>
		</div>
		</div>
		</div>
</body>