<?php 
//Rafi Here
        
$terr = "";
if($this->input->post("user")) {
    $v = 1;
    $terr = "";
    foreach($this->input->post("user") as $x) {
        if($v <> 1) {
            $terr .= ",";
        }
        $terr .= $x;
        //$territory_array[] = $x;
        $v++;
    }
//echo $terr;
////$ter_array = array_unique($territory_array);
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>file/js/easyui/themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>file/js/easyui/themes/icon.css" />
<script type="text/javascript" src="<?php echo base_url()?>file/js/easyui/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>file/js/easyui/jquery.easyui.min.js"></script>
<script src="<?php echo base_url()?>file/shell/smooth/resources/scripts/jquery-ui-1.8.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
       function sendupdatelonlat(chan, lon, lat)
			{
				$.ajax({
						type: 'post',
						url:'<?php echo base_url()?>index.php/gis/updateoutletlonlat/'+chan+'/'+lon+'/'+lat,
						success: function(response) {
                            alert(response);
                        }
				});
			}            
      $(function(){
   			
                $('#cc').combogrid({
                        panelWidth:650,                        
                        idField:'id',
                        textField:'name',
                        multiple:'true',
                        nowrap: false,
                        striped: true,
                        separator:',',
                        frozenColumns:[[
                {field:'ck',checkbox:true}
                        ]],
                        
                        url:'<?php echo base_url()?>index.php/gis/user_json',
                        columns:[[
                                {field:'id',title:'ID',width:100},
                                {field:'name',title:'Name',width:240},
                                {field:'group_name',title:'SFA/DS',width:200}
                        ]]
                });
                
//                $('.src').click(function() {
//                    var c_agent = new Array();
//                    c_agent = $('#cc').combogrid('getValues');
//                });
                function reload(){
                    $('#cc').combogrid('grid').datagrid('reload');
                }
                function setValue(){
                        var val = [<?php echo  $terr ?>];
                        $('#cc').combogrid('setValues', val);
                }
                function getValue(){
                        var val = $('#cc').combogrid('getValue');
                        alert(val);
                }
                function disable(){
                        $('#cc').combogrid('disable');
                }
                function enable(){
                        $('#cc').combogrid('enable');
                }
                $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
                
                var val = [<?php echo  $terr ?>];
                $('#cc').combogrid('setValues', val);
        });
</script>
<style>
    .tableg {
        border-collapse: collapse;
        border: 1px solid #dadada;
    }
    .tableg td {
        border: 1px solid #dadada;
        padding: 5px;
    }
</style>

<script>

$("#li").hide();    

</script>
<div class="box">
     <!-- box / title -->
        <div class="title">
                <h5>SIG > Lacak Sales</h5>
                
        </div>
        <form id="form" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                <div class="form">
                        <div class="fields">
                                <div class="field">
                                        <div class="label">
                                                <label for="user">SFA/DS:</label>
                                        </div>
                                        <div class="input">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<!--                                                <select name="user[]" id="user" multiple="multiple">
                                                <?php
//                                                $dt = $this->mgis->getUser89();
//                                                $i = 0;
//                                                foreach($dt as $row){
//                                                    
//                                                        echo'<option value="'. $row->user_id .'" '.$sel[$row->user_id].'>'.$row->user_name.' ('. $row->user_id .')</option>';
//                                                        $i++;
//                                                }
                                                ?>
                                                </select>-->
                                                <input name="user[]" id="cc" style="width:250px;" multiple="true" readonly cascadeCheck="false">
                                        </div>
                                </div>
                                <div class="field">
                                        <div class="label">
                                                <label for="tanggal">Tanggal Kunjungan (YYYY-MM-DD):</label>
                                        </div>
                                        <div class="input">
                                               <input type="text" id="datepicker" name="tanggal" value="<?php echo  $this->input->post("tanggal") ? $this->input->post("tanggal") : date('Y-m-d') ?>" size="30" />
                                        </div>
                                </div>
                            <div class="buttons">
                                        <input type="submit" name="submit" value="Cari dan Tampilkan" class="src"/>                                     
                                </div>
                        </div>
                </div>
        </form>
     <div style="margin:10px;">
        Legend
        <table width="400" class="tableg">
            <thead>
                <tr><td>ID User</td><td>Nama</td><td>SFA/DS</td><td>Color</td></tr>
            </thead>
            <tbody>
                <?php
                if($hasil != null) {
                    $ux = 0;
                    if($this->input->post("user") <> "") {
                        foreach($this->input->post("user") as $usr) {
                            $name = $this->mgis->getUserName($usr);
                            $gname= $this->mgis->getgroupname($usr);
                            echo "<tr><td>$usr</td><td>$name</td><td>$gname</td><td><div id='dd$ux' style='width:50px;height:10px;'></td></tr>";
                            $ux++;
                        }
                    }
                }
                ?>
            </tbody>
           
        </table>
     </div>
        <script type="text/javascript" src="<?php echo $api?>"></script>
        <script type="text/javascript">
            function colorRand() {
                var hexno;
                arr = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");

                var n1=Math.round(Math.random()*15);
                var n2=Math.round(Math.random()*15);
                var n3=Math.round(Math.random()*15);
                var n4=Math.round(Math.random()*15);
                var n5=Math.round(Math.random()*15);
                var n6=Math.round(Math.random()*15);

                hexno = "#"+arr[n1]+""+arr[n2]+""+arr[n3]+""+arr[n4]+""+arr[n5]+""+arr[n6];
                
                return hexno;
            }
            
            (function() {
                window.onload = function() {
                // Creating a map
                
                
                
                //-by donny
                var zl=18;
                var got=false;
                var lat=[];
                var lng=[];
                <?
                
                $usr=$this->input->post("user");
                if ($usr!=null){
                    foreach($usr as $us) {
                        
                        
                        $bb = $this->mgis->getMapTracking($us,$this->input->post("tanggal"));
                        
                        $x = 0;
                        foreach($bb as $v) {

                            echo  "lat.push(".$v->lat.");";
                            echo  "lng.push(".$v->lon.");";
                        }    
                    }
                 }   
                    ?>
                    
                    
                    
               function getmax(aray){
                    var max=aray[0];
                    for (i =1; i<aray.length;i++){
                        if (max<aray[i]){
                            max=aray[i]
                        }
                        
                    }
                    return max;
                }
                
                function getmin(aray){
                    var max=aray[0];
                    for (i =1; i<aray.length;i++){
                        if (max>aray[i]){
                            max=aray[i]
                        }
                        
                    }
                    return max;
                }
                        

                
				var maxlng=getmax(lng);
				var minlng=getmin(lng);
				var maxlat=getmax(lat);
				var minlat=getmin(lat);
                


                        //alert (maxlng + "-" + minlng + "-" + maxlat + "-" + minlat );
               			var clat=minlat + ((maxlat-minlat)/2);
				        var clng=minlng + ((maxlng-minlng)/2);
                
                //donny adiwilaga was here
                if (maxlng==null || maxlat==null || minlng==null || minlat==null){got=true;zl=5;clat=-1.9112665350965847;clng=117.7294921875}else{got=false;}
                
                var options = {
                zoom: zl,
                center: new google.maps.LatLng(clat,clng),
                mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                
                var map = new google.maps.Map(document.getElementById('map'), options);
                // Creating an array that will contain the coordinates
                //-------
                
                
                
                
                
                
                
                
                
                
                
                
                
                  // Adding a LatLng object for each city
                <?php
                $bound = "";
                if($hasil != null) {
                    
//                    if($tr != null) {
//                        foreach($this->input->post("territory") as $tr) {
                            $ter = $this->mgis->getTerritoryMember($this->mgis->getTerritoryUserLogin()->mvalue);                   
                    
                            if($ter != null) {
                                $i = 0;

                                foreach($ter as $r) {
                                    $this->mgis->pathTer = "";
                                    $hal = $this->mgis->getBoundaryTerritory($r);
                                    $bound = "";
                                    $x = 0;
                                    if($hal != null) {
                                        foreach($hal as $v) {
                                            if($x <> 0) {
                                                $bound .= ",";
                                            }                       
                                            $bound .= "new google.maps.LatLng(".$v->lat.",".$v->lon.")";

                                            $x++;
                                        }   

                                    $ret = $this->mgis->getTerritoryById($r);
                                    $territory_name = $this->mgis->getParentTerritory($r);
                                    //$territory_name = $ret['territory_name'];
                                    $fill_color = $ret['fill_color'];
                                    if($fill_color == "") {
                                        $fill_color = "000000";
                                    }else {
                                        $fill_color = $ret['fill_color'];
                                    }

                                    $border_color = $ret['border_color'];
                                    if($border_color == "") {
                                        $border_color = "000000";
                                    }else {
                                        $border_color = $ret['border_color'];
                                    }

                                    ?>
                                     var bound<?php echo $i?> = [<?php echo  $bound ?>];               
                                    // Creating the polyline object
                                    var polygon<?php echo $i?> = new google.maps.Polygon({
                                        path: bound<?php echo $i?>,
                                        strokeColor: "#ff0000",
                                        strokeOpacity: 0.7,
                                        strokeWeight: 1,
                                        fillColor: '#<?php echo $fill_color?>',
                                        strokeColor: '#<?php echo $border_color?>'
                                    });
                                    // Adding the polyline to the map
                                    polygon<?php echo $i?>.setMap(map);    

                                    // Add a listener for the click event
                                    google.maps.event.addListener(polygon<?php echo $i?>, 'click', function(e) {
                                        var vertices = this.getPath();
                                        var contentString = "<b><?php echo $territory_name?></b><br />";

                                        // Iterate over the vertices.
                                        for (var i =0; i < vertices.length; i++) {
                                          var xy = vertices.getAt(i);
                                        }

                                        // Replace our Info Window's position
                                        myInfowindow.setContent(contentString);
                                        myInfowindow.setPosition(e.latLng);
                                        myInfowindow.open(map);
                                    });


                                   // Adding mouseover event to the polygon
                                    google.maps.event.addListener(polygon<?php echo $i?>, 'mouseover', function(e) {
                                    // Setting the color of the polygon to blue
                                    polygon<?php echo $i?>.setOptions({
                                        fillColor: '#0000ff',
                                        strokeColor: '#0000ff'
                                    });
                                    });

                                    // Adding mouseover event to the polygon
                                    google.maps.event.addListener(polygon<?php echo $i?>, 'mouseout', function(e) {
                                    // Setting the color of the polygon to blue
                                    polygon<?php echo $i?>.setOptions({
                                        fillColor: '#<?php echo $fill_color?>',
                                        strokeColor: '#<?php echo $border_color?>'
                                    });
                                    });

                                    <?php                            
                                    }
                                    $i++;
                                }

                            }
//                        }
//                    }                                        
                                                                
                }
                ?> 
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                //by donny
				var posmax=new google.maps.LatLng(maxlat,maxlng);
                var posmin=new google.maps.LatLng(minlat,minlng);
				var bounds= new google.maps.LatLngBounds();
                
                   google.maps.event.addListener(map, 'bounds_changed', function() {
                     if (map.getBounds().contains(posmin)==false && map.getBounds().contains(posmax)==false && got==false){
                        map.setZoom(map.getZoom()-1);                  
                     }else{
                        got=true;
                     }
                  });
                //---
                
                
               <?php
                             
                if($hasil != null) {
                    $u = 0;
                    if($this->input->post("user") <> "") {
                    foreach($this->input->post("user") as $us) {
                        $content_route = "";
                        $timetrack = "";
                        $duration = "";
                        $dur = "";
                        $jc="";
                        $prid="";
                        $add="";
                        $lon="";
                        $lat="";
                        $accuracy="";
                        $type="";
                        
                        echo "var linecolor$u = colorRand();";
                        echo "document.getElementById(\"dd$u\").style.backgroundColor = linecolor$u;";
                        
                        echo "var places$u = [];";
                        
                        
                        $bb = $this->mgis->getMapTracking($us,$this->input->post("tanggal"));
                        
                        $x = 0;
                        foreach($bb as $v) {
                            if($x <> 0) {
                                $content_route .= ",";
                                $timetrack .= ",";
                                $duration .= ",";
                                $dur .= ",";
                            }
                            echo  "places$u.push(new google.maps.LatLng(".$v->lat.",".$v->lon."));";
                            $content_route .= "new google.maps.LatLng(".$v->lat.",".$v->lon.")";

                            $firstTime = $this->mgis->getFirstTrack($v->lon,$v->lat,$us,$this->input->post("tanggal"));
                            $mytime = $this->mgis->getDurationTracking($v->lon,$v->lat,$us,$this->input->post("tanggal"));
                            $timetrack .= "\"Waktu: $firstTime\"";
                            $duration .= "\"Durasi: $mytime Minute\"";
                            $dur .= $mytime;
                            $jc .="\"".$v->partner_name."\",";
                            $prid .="\"".$v->channel_id."\",";
                            $add .="\"".$v->address."\",";
                            $lon .="\"".$v->lon."\",";
                            $lat .="\"".$v->lat."\",";
                            $accuracy .="\"".$v->accuracy."\",";
                            $type.="\"".$v->type."\",";
                            $x++;
                            
                            
                        }

                        echo "var route$u = [".$content_route."];";  
                        $username = $this->mgis->getUserName($us);
                        
                        
                        
                        

                ?>
                           
                        
                        
                        
                        
                        
                        
                        
                        
                        
                var track<?php echo $u?> = [<?php echo  $timetrack ?>];
                var duration<?php echo $u?> = [<?php echo  $duration ?>];
                var dur<?php echo $u?> = [<?php echo  $dur ?>];
                var st = "";
                var jc<?php echo $u?> = [<?php echo  $jc ?>];
                var prid<?php echo $u?> = [<?php echo  $prid ?>];
                var add<?php echo $u?> = [<?php echo  $add ?>];
                var lon<?php echo $u?> = [<?php echo  $lon ?>];
                var lat<?php echo $u?> = [<?php echo  $lat ?>];
                var acc<?php echo $u?> = [<?php echo  $accuracy ?>];
                var typ<?php echo $u?> = [<?php echo  $type ?>];
                
                var icons;
                var len = places<?php echo $u?>.length;
                for (var i = 1; i <= len; i++) {
                    // Adding the marker as usual
                    if(i == 1) {
                        st = "<b><?php echo $username?></b> 1 (start)"+" <br/>"+ track<?php echo $u?>[i-1] + "<br/>"+ duration<?php echo $u?>[i-1];
                        icons = '<?php echo base_url()?>file/images/icongis/start.png';
                    }else if(i == len) {
                        st = "<b><?php echo $username?></b> " +len + " (end)"+" <br/>"+ track<?php echo $u?>[i-1] + "<br/>"+ duration<?php echo $u?>[i-1];
                        icons = '<?php echo base_url()?>file/images/icongis/end.png';

                    }else {
                        st = "<b><?php echo $username?></b> "+ i +" <br/>"+ track<?php echo $u?>[i-1] + "<br/>"+ duration<?php echo $u?>[i-1];
                        if(jc<?php echo $u?>[i-1] !="") {
                            icons = '<?php echo base_url()?>file/images/icongis/check.png';
                        }else if(dur<?php echo $u?>[i-1] >= 45) {
                            icons = '<?php echo base_url()?>file/images/icongis/dot45.png';
                        }else{
                            icons = '<?php echo base_url()?>file/images/icongis/dot.png';
                        }
                        
                    }
                    
                    if (jc<?php echo $u?>[i-1] !=""){
                        st=st+'<br/>Id Partner:'+prid<?php echo $u?>[i-1]+'<br/>Nama Partner:'+jc<?php echo $u?>[i-1];
                        st=st+'<br/>Alamat:'+add<?php echo $u?>[i-1];
                    }
                    
                    if(acc<?php echo $u?>[i-1]!=""){
                        st=st+'<br/>Akurasi:'+acc<?php echo $u?>[i-1]+'meter';
                    }
                    if(typ<?php echo $u?>[i-1]!=""){
                        st=st+'<br/>Tipe:'+typ<?php echo $u?>[i-1];
                    }
                    
                    if(typ<?php echo $u?>[i-1]!=""){
                        st=st+'<br/><input type="button" value="Update Lokasi Partner" onclick="sendupdatelonlat(\''+prid<?php echo $u?>[i-1]+'\',\''+lon<?php echo $u?>[i-1]+'\',\''+lat<?php echo $u?>[i-1]+'\')">';
                    }
                    
                    
                    var marker<?php echo $u?> = new google.maps.Marker({
                        position: places<?php echo $u?>[i-1],
                        map: map,
                        title: st, 
                        icon: icons
                    });
                        // Wrapping the event listener inside an anonymous function
                        // that we immediately invoke and passes the variable i to.
                    (function(st, marker<?php echo $u?>) {
                        // Creating the event listener. It now has access to the values of
                        // i and marker as they were during its creation
                        google.maps.event.addListener(marker<?php echo $u?>, 'click', function() {
                        var infowindow = new google.maps.InfoWindow({
                        content: st
                        });
                        infowindow.open(map, marker<?php echo $u?>);
                        });
                    })(st, marker<?php echo $u?>);
                }

                
                               
                // Creating the polyline object
                var polyline<?php echo $u?> = new google.maps.Polyline({
                    path: route<?php echo $u?>,strokeColor: linecolor<?php echo $u?>,
                    strokeOpacity: 0.6,
                    strokeWeight: 5
                });
                // Adding the polyline to the map
                polyline<?php echo $u?>.setMap(map);
                
                <?php
                    $u++;
                    }
                    }
                }
                ?>
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                              
                
                
                
                
                
                
                
                
                
                
                
                
                
                };
                })();
        </script>
        
<div id="map" style="width:98%;height:700px;margin:10px;border:1px solid #000000;"></div>
</div>