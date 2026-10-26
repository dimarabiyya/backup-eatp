
<!--<h3><?php echo lang('text_heading_gis_map') ?></h3>-->
<!-- GIS -->
 <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="<?php echo site_url('/assets/js/jquery-1.4.3.min.js'); ?>"></script>
<script type="text/javascript">

var peta;
var pertama = 0;
var jenis = "biru";
var jen = "biru";
var idx = new Array();
var judulx = new Array();
var jarakx = new Array();
var desx = new Array();
var status_kandidatx = new Array();
var i,j;
var url;
var gambar_tanda;
var nomsite= <?php echo (isset($_POST['nomsite']) ? $_POST['nomsite'] : 0) ?>;
//var radiusx=<?php echo (isset($_POST['radius']) ? $_POST['radius'] : 100) ?>;
//var radius_peta= radiusx;
var lokasi_nom_x= 0;
var lokasi_nom_y= 0;
var site_id_nom= 0;
peta = new google.maps.Map(document.getElementById("petaku"),petaoption);
console.log('koord_nom_lat: '+<?php echo $koord_nom_lat;?>);
function peta_awal(){
    <?php 
    if($koord_nom_lat== null){
        $koord_nom_lat=-6.237281; // default center disini 
    }
    if($koord_nom_long== null){
        $koord_nom_long=106.332724;
    }
        ?>
    var jakarta = new google.maps.LatLng(parseFloat(<?php echo $koord_nom_lat;?>),parseFloat(<?php echo $koord_nom_long;?>));
    var petaoption = {
        zoom: 7,
        center: jakarta,
        mapTypeId: google.maps.MapTypeId.ROADMAP
        };
	
    var myCity = new google.maps.Circle({
      center:jakarta,
      //radius:radius_peta,
      strokeColor:"#ed1d2a",
      strokeOpacity:0.8,
      strokeWeight:2,
      fillColor:"#ed1d2a",
      fillOpacity:0.4
      });

    
    peta = new google.maps.Map(document.getElementById("petaku"),petaoption);
    myCity.setMap(peta);
    ambildatabase('awal');
}

function set_icon(jenisnya){
    switch(jenisnya){
        case "Indosat":
            gambar_tanda = "<?php echo base_url('/assets/images/icon/yellow_MarkerI.png'); ?>";
            break;
        case "nomsite":
            gambar_tanda = "<?php echo base_url('/assets/images/icon/icon_smart.png'); ?>";
            break;
        case "Protelindo":
            gambar_tanda = "<?php echo base_url('/assets/images/icon/blue_MarkerP.png'); ?>";
            break;
        case  "ungu":
            gambar_tanda = "<?php echo base_url('/assets/images/icon/pink_MarkerT.png'); ?>";
            break;
        case  "merah":
            gambar_tanda = "<?php echo base_url('/assets/images/icon/red_MarkerE.png'); ?>";
            break;
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
        idx[j] = <?php echo $petak[$i]['id'];?>;
        judulx[j] = "<?php echo $petak[$i]['tp_name'];?>";
        jarakx[j] = <?php echo $petak[$i]['distance'];?>;
        status_kandidatx[j] = <?php echo $petak[$i]['kandidat'];?>;
        desx[j] = "<?php echo str_replace(array('\'','\"','<', '>', '&', '{', '}', '*', "\n"), array(' '), $petak[$i]['address']);?>";
		if(judulx[j]=='nomsite'){
			lokasi_nom_x=parseFloat(<?php echo $petak[$i]['latitude'];?>);
			lokasi_nom_y=parseFloat(<?php echo $petak[$i]['longitude'];?>);
			site_id_nom=idx[j];
			console.log('x='+lokasi_nom_x+' y='+lokasi_nom_y);
		}    
		jen="<?php echo $petak[$i]['tp_name'];?>";
		set_icon(jen.trim());
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
        if(judulx[nomor]=='nomsite'){
            linkdetil= "<a href='<?php echo site_url('/site_info?nomor="+idx[nomor]+"');?>'>[Detil]</a>. ";
            link_kandidat= "";
        }else if (status_kandidatx[nomor]==1){
            linkdetil= "";
            link_kandidat= " Status: Sudah Kandidat";
        }else{
            linkdetil= "";
            link_kandidat= " <a href='<?php echo site_url('/referensi/map/kandidat?nomor="+idx[nomor]+"&site_id="+site_id_nom+"');?>'>[Kandidat]</a> ";
        }

		var infowindow = new google.maps.InfoWindow({
		content:judulx[nomor]+" "+idx[nomor]+" <br>Adress: "+desx[nomor]+'. <br>Jarak: '+jarakx[nomor]*1000+ " m. <br>"+linkdetil+link_kandidat
		});

		google.maps.event.addListener(tanda, 'click', function() {
		infowindow.open(peta,tanda);
		});
    });
}

$(function() {
	peta_awal();
});
</script>
<!-- GIS -End-->
<body onload="peta_awal()">
<div class="box">
		
	<div class="title">
		<h5><?php echo lang('text_heading_title_map') ?></h5>
	</div>
	<form id="formfilter" action="<?php echo site_url('/referensi/map/mapnsf');?>" method="POST">
	  <div class="input-append">
		<select name='nomsite' id="nomsite" >
			<option value="" selected>-Pilih Site-</option>
			<?php
			 foreach ($list_site_id as $row) {
				  echo "<option value='". $row['site_id'] ."'>". $row['site_id_text'] . "</option>";
			  } 
			?>
			<!--<option value="0" >Semua</option> -->
		 </select>
		<input type="submit" name="submit" value="Tampilkan" class="btn btn-info" />                                    
	  </div>
	</form>
	<div>
	<?php IF($nomname!=null) { echo "Site ID: ".$nomname; }; ?>
	</div>
	<table border="0">
		<tr>
			<td>
				<div id="petaku" style="width:900px; height:600px;max-width:none;"></div>
			</td>
			<td>
				&#32;
			</td>
			<td valign='top' width="200px" style='margin-left:2px; vertical-align:top;'>
				Keterangan:<p>
				<table>
				<tr><td><img src="<?php echo base_url('/assets/images/icon/icon_smart.png')?>"></td><td>Nom Site</td></tr>
				<?php 
				foreach($gambar_marker as $row){
				?>
				<tr><td><img src="<?php echo base_url('/assets/images/icon/'.$row['gis_marker'])?>"></td><td><?php echo $row['partner_name'];?></td></tr>
				<?php }?>
				</table>
			</td>
		</tr>
	</table>

</div>
</body>