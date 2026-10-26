
<!--<h3><?php echo lang('text_heading_gis_map') ?></h3>-->
<!-- GIS -->
 <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="<?php echo site_url('/assets/js/jquery-1.4.3.min.js'); ?>"></script>
<script type="text/javascript">

var peta;
var pertama = 0;
var jenis = "biru";
var idx = new Array();
var judulx = new Array();
var jarakx = new Array();
var desx = new Array();
var tpx = new Array();
var sitex = new Array();
var status_kandidatx = new Array();
var status_disewax = new Array();
var i;
var url;
var gambar_tanda;
var nomsite= <?php echo (isset($_POST['nomsite']) ? $_POST['nomsite'] : 0) ?>;
var radiusx=<?php echo (isset($_POST['radius']) ? $_POST['radius'] : 100) ?>;
var lokasi_nom_x= 0;
var lokasi_nom_y= 0;
peta = new google.maps.Map(document.getElementById("petaku"),petaoption);

function peta_awal(){
    var rad;
    rad=5000;
    <?php 
    /*
        $rad = 5000;
        if(isset($_POST['radius'])){
            $rad = $_POST['radius'];
            //echo "window.location = '".site_url('/referensi/map?site_id=$site_id')."'";
        } ;
        echo "rad = ".$rad.";" ;    
        */
    ?>
    //console.log('koord_nom_lat: '+<?php echo $koord_nom_lat;?>);
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
        zoom: 10,
        center: jakarta,
        mapTypeId: google.maps.MapTypeId.ROADMAP
        };
    var myCity = new google.maps.Circle({
      center:jakarta,
      radius:radiusx,
      strokeColor:"#ed1d2a",
      strokeOpacity:0.8,
      strokeWeight:2,
      fillColor:"#ed1d2a",
      fillOpacity:0.4
      });

    
    peta = new google.maps.Map(document.getElementById("petaku"),petaoption);
    myCity.setMap(peta);
    google.maps.event.addListener(peta,'click',function(event){
        kasihtanda(event.latLng);
    });
    ambildatabase('awal');
}

$(document).ready(function(){
    $("#tombol_simpan").click(function(){
        var x = $("#x").val();
        var y = $("#y").val();
        var judul = $("#judul").val();
        var des = $("#deskripsi").val();
        $("#loading").show();
        $.ajax({

            url: "simpanlokasi.php",
            data: "x="+x+"&y="+y+"&judul="+judul+"&des="+des+"&jenis="+jenis,
            cache: false,
            success: function(msg){
                alert(msg);
                $("#loading").hide();
                $("#x").val("");
                $("#y").val("");
                $("#judul").val("");
                $("#deskripsi").val("");
                ambildatabase('akhir');
            }
        });
    });
    $("#tutup").click(function(){
        $("#jendelainfox").fadeOut();//, function() { console.log("done"); });   show().delay(200).hide();
        //$("#jendelainfox").stop().animate({opacity:0.6},1000);
    
    });
});
function kasihtanda(lokasi){
    set_icon(jenis);
    tanda = new google.maps.Marker({
            position: lokasi,
            map: peta,
            icon: gambar_tanda
    });
    $("#x").val(lokasi.lat());
    $("#y").val(lokasi.lng());
}

function set_icon(jenisnya){
    switch(jenisnya){
        case "biru":
            gambar_tanda = "<?php echo base_url('/assets/images/icon/biru.png'); ?>";
            break;
        case "nomsite":
            gambar_tanda = "<?php echo base_url('/assets/images/icon/icon_smart.png'); ?>";
            break;
        case "kuning":
            gambar_tanda = "<?php echo base_url('/assets/images/icon/kuning.png'); ?>";
            break;
        case  "ungu":
            gambar_tanda = "<?php echo base_url('/assets/images/icon/ungu.png'); ?>";
            break;
        case  "merah":
            gambar_tanda = "<?php echo base_url('/assets/images/icon/merah.png'); ?>";
            break;
    }
}
function ambildatabase(akhir){
    if(akhir=="akhir"){
        url = "<?php echo site_url('/site_info/map?akhir=1'); ?>";
    }else{
        url = "<?php echo site_url('/site_info/map?akhir=0'); ?>";
    }
    $.ajax({
        url: url,
        type: 'GET',
        data: { cmd: 'GET_MAP_DATA', nomsite: nomsite, radius: radiusx },
        dataType: 'json',
        cache: false,
        success: function(msg){
            for(i=0;i<msg.wilayah.petak.length;i++){
                idx[i] = msg.wilayah.petak[i].id;
                judulx[i] = msg.wilayah.petak[i].judul;
                desx[i] = msg.wilayah.petak[i].deskripsi;
                jarakx[i] = msg.wilayah.petak[i].jarak;
                tpx[i] = msg.wilayah.petak[i].tp;
                sitex[i] = msg.wilayah.petak[i].site_id;
                status_kandidatx[i] = msg.wilayah.petak[i].status_kandidat;
                status_disewax[i] = msg.wilayah.petak[i].status_disewa;

                if(tpx[i]=="NOM"){
                    lokasi_nom_x=parseFloat(msg.wilayah.petak[i].x);
                    lokasi_nom_y=parseFloat(msg.wilayah.petak[i].y);
                    console.log('x='+lokasi_nom_x+' y='+lokasi_nom_y);

                }    
                set_icon(msg.wilayah.petak[i].jenis);
                var point = new google.maps.LatLng(
                    parseFloat(msg.wilayah.petak[i].x),
                    parseFloat(msg.wilayah.petak[i].y));
                tanda = new google.maps.Marker({
                    position: point,
                    map: peta,
                    icon: gambar_tanda
                });
                setinfo(tanda,i);
                

            }
            
        }
    });

}

function setjenis(jns){
    jenis = jns;
}

function setinfo(petak, nomor){
    google.maps.event.addListener(petak, 'click', function() {
        if(tpx[nomor]=="NOM"){
            linkdetil= "<a href='<?php echo site_url('/site_info?nomor="+sitex[nomor]+"');?>'>[Detil]</a>. ";
        }else{
            linkdetil= "";
        }

        if(tpx[nomor]=="NOM" || parseInt(status_kandidatx[nomor])== 1){
            link_kandidat= "";
        }else{
            link_kandidat= " <a href='<?php echo site_url('/site_info/map/kandidat?nomor="+idx[nomor]+"&site_id="+sitex[nomor]+"');?>'>[Kandidat]</a> ";
        }

        if(tpx[nomor]=="NOM" || status_disewax[nomor]== "1" || status_kandidatx[nomor]== "0"){
            link_disewa= "";
        }else if(tpx[nomor]=="TP" && status_kandidatx[nomor]== "1"){
            link_disewa= " <a href='<?php echo site_url('/site_info/map/disewa?nomor="+idx[nomor]+"&site_id="+sitex[nomor]+"');?>'>[Sewa]</a> ";
        }

        $("#jendelainfox").fadeIn();
        $("#teksjudul").html(judulx[nomor]);
        $("#teksdes").html(tpx[nomor]+" "+desx[nomor]+'. Jarak:'+jarakx[nomor]+ "m. "+linkdetil+link_kandidat+link_disewa);
        //peta.setCenter(new google.maps.LatLng(-6.247281, 106.532724));
    });
}

$(function() {
	peta_awal();
});
</script>
<style>
#jendelainfox{position:absolute;z-index:1000;top:100;
left:400;background-color:yellow;display:none;}

</style>
<!-- GIS -End-->
<body onload="peta_awal()">
<div class="box">
		
	<div class="title">
		<h5><?php echo lang('text_heading_title_map') ?></h5>
	</div>
	<form id="formfilter" action="<?php echo site_url('/site_info/map');?>" method="post">
	  <div class="input-append">
		<select name='nomsite' id="nomsite" >
			<option value="" selected>-Pilih Site-</option>
			<?php
			 foreach ($list_site_id as $row) {
				  echo "<option value='". $row['site_general_id'] ."'>". $row['site_id'] . "</option>";
			  } 
			?>
			<!--<option value="0" >Semua</option> -->
		 </select>
		<input class="span2" type="text" id="radius" name="radius" placeholder="Radius(m)">
		<input type="submit" name="submit" value="Tampilkan" class="btn btn-info" />                                    
	  </div>
	</form>
	<table id="jendelainfox" border="1" cellpadding="4" cellspacing="0" style="border-collapse: collapse" bordercolor="#FFCC00" width="300" height="136">
		  <tr>
				<td width="248" bgcolor="#e52020" height="19"><font color=white><span id="teksjudul"></span></font></td>
				<td width="30" bgcolor="#e52020" height="19">
				<p align="center"><font color="#FFFFFF"><a style="cursor:pointer" id="tutup" onclick="$('#jendelainfox').fadeOut('slow')"><b>X</b></a></font></td>
		  </tr>
		  <tr>
				<td width="290" bgcolor="#fcdd9a" height="100" valign="top" colspan="2">
				<p align="center"><span id="teksdes"></span></td>
		  </tr>
	</table>
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
				<ul>
					<li><img src="<?php echo base_url('/assets/images/icon/biru.png')?>">Tower Provider PT.Blue
					<li><img src="<?php echo base_url('/assets/images/icon/kuning.png')?>"> Tower Provider PT.Yellow
					<li><img src="<?php echo base_url('/assets/images/icon/ungu.png')?>"> Tower Provider PT.Purple
					<li><img src="<?php echo base_url('/assets/images/icon/merah.png')?>"> Tower Provider PT.Red
				</ul>
				<!--
				<p>
				X : <input type=text id=x><br>
				Y : <input type=text id=y><p>
				Nama:<br>
				<input type=text id="judul" size=30><p>
				Deskripsi:<br>
				<textarea cols=20 rows=8 id="deskripsi"></textarea><p>
				<button id="tombol_simpan" class="btn btn-info">Simpan</button>
				<img src="ajax-loader.gif" style="display:none" id="loading"> -->
			</td>
		</tr>
	</table>

</div>
</body>