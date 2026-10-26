<html>
<head>
<title>GIS Maps</title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="jquery-1.4.3.min.js"></script>
<script type="text/javascript">

var peta;
var i;
var url;

function peta_awal(){
    var jakarta = new google.maps.LatLng(-6.234077, 106.7211914);
    var petaoption = {
        zoom: 14,
        center: jakarta,
        mapTypeId: google.maps.MapTypeId.ROADMAP
        };
    peta = new google.maps.Map(document.getElementById("petaku"),petaoption);
    google.maps.event.addListener(peta,'click',function(event){
        kasihtanda(event.latLng);
    });
}

$(document).ready(function(){
    $("#tombol_simpan").click(function(){
        var x = $("#x").val();
        var y = $("#y").val();
        $("#loading").show();
        $.ajax({
            url: "simpanlokasi.php",
            data: "x="+x+"&y="+y,
            cache: false,
            success: function(msg){
                alert(msg);
                $("#loading").hide();
                $("#x").val("");
                $("#y").val("");
            }
        });
    });
    $("#tutup").click(function(){
        $("#jendelainfo").fadeOut();
    });
});
function kasihtanda(lokasi){
    tanda = new google.maps.Marker({
            position: lokasi,
            map: peta
    });
    $("#x").val(lokasi.lat());
    $("#y").val(lokasi.lng());
}


</script>
<style>
#jendelainfo{position:absolute;z-index:1000;top:100;
left:400;background-color:yellow;display:none;}
#petaku img{
	max-width:none;
}

</style>
</head>
<body onload="peta_awal()">
<center>
<h1>GIS Map</h1>
	<table border=0 width=1000>
	<tr><td>
	<div id="petaku" style="width:700px; height:500px"></div>
	</td>
	<td valign=top>
	X : <input type=text id=x><br>
	Y : <input type=text id=y><p>
	<button id="tombol_simpan">Simpan</button>
	<img src="ajax-loader.gif" style="display:none" id="loading">
	</td>
	</tr>
	</table>
</body>
</html>
