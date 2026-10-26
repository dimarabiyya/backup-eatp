<?php
/* Map Boundary
 * Google Map API 3
 * by Wirda Hilwa
 * edited by Andedi
 */
?>
<style>
    #map-canvas img{
        max-width:none;
    }
    .combo-text {margin-bottom: 0px !important; padding:0px !important;}

    .gmap-control-container {
        margin: 5px;
    }
    .gmap-control {
        cursor: pointer;
        /*font-family: Arial,sans-serif;*/
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
        /*font-size: 14px;*/
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

<div class="innerLR">
    <div class="widget">	
        <div class="widget-head heading-outer">
            <h4 class="heading glyphicons globe_af"><i></i> GIS Boundary</h4>
            <div class="btn-heading-group">
                <a class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right" href="<?php echo  base_url() ?>gis/boundary_manage"><i></i>Manage</a>
            </div>
        </div>
        <div class="widget-body">
            <div class="filter-bar" style="margin-bottom: 0px;">
                <div class="row-fluid">
                    <div class="span12">
                        <form id="form1" name="form1" action="<?php echo  base_url() ?>gis/boundary" method="post">
                            Region 
                            <select name="region_id" id="region_id" style="height: 30px;">
                                <option value="0">- All Region - </option>
                                <?php
                                foreach ($regions as $region) {
                                    echo "<option value='$region->region_id'>$region->region_name</option>";
                                }
                                ?>
                            </select>
                            Cluster
                            <select name="cluster_id" id="cluster_id" style="height: 30px;">
                                <option value="0">- All Cluster - </option>
                            </select>
                            <button type="submit" name="submit" class="btn btn-icon btn-primary glyphicons circle_ok" style="padding: 2px 5px 2px 30px;"/><i></i>Show</button>
                        </form>
                    </div>
                </div>
            </div>

            <div id="map-canvas" style="width:100%;margin:0 auto;height:800px;display:block;" ></div>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
<script type="text/javascript" src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<script>
    $('.container-fluid:first').toggleClass('menu-hidden');
    $('#menu').toggleClass('hidden-phone');
    var map;
    var infoPolygon;
    var polygonRegion = [];
    var polygonCluster = [];

    function mapInit() {
        var myLatLng = new google.maps.LatLng(-8.796647, 119.621416666667);
        var mapOptions = {
            zoom: 5,
            center: myLatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: false
        };

        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

        ///start legend
        var legend = '<div style="width: 210px; height: 50px; background-color: rgb(255, 255, 255); overflow: auto;">'
                + '<table>'
                + '<tr><td><input id="region-cb" type="checkbox" checked="checked" onclick="showHideRegion();"> <img src="<?php echo base_url('/assets/images/icon/polygon-red.jpg') ?>" style="width: 40%; "></td><td>Region Polygon</td></tr>'
                + '<tr><td><input id="cluster-cb" type="checkbox" checked="checked" onclick="showHideCluster();"> <img src="<?php echo base_url('/assets/images/icon/polygon-blue.png') ?>" style="width: 40%; "></td><td>Cluster Polygon</td></tr>'
                + '</table>'
                + '</div>';

        var form = document.createElement('FORM');
        $(form).attr('id', 'cariJalan');

        var divAppend = document.createElement('DIV');
        $(form).addClass('input-append');

        var controlSearch = $('<select class="span1" id="jeniscari" name="jenis" style="width:80px;">'
                + '<option value="1">Street</option>'
                + '<option value="2">Site ID</option>'
                + '</select>'
                + '<input class="txtSearch span3" id="appendedInputButtons" name="key" type="text" placeholder="Search .." />'
                + '<button class="btn" id="doSearchLegend" type="button" style="display:none;"><i class="icon-search"></i></button>'
                + '<button class="btn gmap-control" type="submit">Search</button> &nbsp; ');

        var controlDiv = document.createElement('DIV');
        $(controlDiv).addClass('geoSearchBox');
        $(controlDiv).attr('style', 'margin-top:5px;margin-right:40px;border: solid black 1px;padding:3px;background-color: #cccccc;');

        var controlUI = $('<button class="btn btn-danger gmap-control" type="button">Legend</button>');
        var controlUI = document.createElement('button');
        $(controlUI).addClass('btn btn-danger gmap-control');
        $(controlUI).attr('type', 'button');
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

        $(controlUI).click(function (){
            $(controlLegend).slideToggle('fast');
        });

        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(controlDiv);
        ///end legend
        
//        google.maps.event.addDomListener(window, 'load', mapInit);
    }
    
    $(document).ready(function() {
        //$('#cc').combotree('setValue', '<?php echo  $ter2 ?>');
        
        mapInit();
        showMap();
        //start legend
        $('#doSearch, #doSearchLegend').live("click", function() {
            $.fancybox({
                href: "<?php echo  base_url() ?>referensi/site_kandidat/searchSite", type: 'ajax',
                minWidth: 100,
                scrolling: false,
                autoSize: true,
                fitToView: true,
                closeClick: false,
                openEffect: 'fade',
                closeEffect: 'fade',
                afterShow: function() {
                    $('#app_no').focus();
                }
            });
        });

        $('#jeniscari').live("change", function(e)
        {
            if ($(this).val() == 1) { //address
                $("#doSearchLegend").hide();
            } else {
                $("#doSearchLegend").show();
            }
        });

        $('#cariJalan').live("submit", function(e) {
            $.ajax({
                type: "POST",
                url: "<?php echo  base_url() ?>referensi/map/show_jalan",
                data: $(e.target).serialize(),
                dataType: "json",
                beforeSend: function() {
                    //$('.alert-error,.alert-success').hide();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //alert(errorThrown);
                },
                success: function(data) {

                    if (data.jenis == 1) {


                        var address = data.jalan;
                        geocoder = new google.maps.Geocoder();
                        geocoder.geocode({'address': address}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                peta.setCenter(results[0].geometry.location);
                                peta.setZoom(9);
                                /*var marker = new google.maps.Marker({
                                 map: peta,
                                 position: results[0].geometry.location
                                 });*/
                            } else {
                                alert('Geocode was not successful for the following reason: ' + status);
                            }
                        });
                    } else {
                        if (data.long == 0) {
                            alert("Site ID Not Found");
                        } else {
                            //marker.setMap(null);
                            if (data.jml_site > 0) {

                                var dt = {};
                                dt = data.hasil.site[0];

                                peta.setCenter(new google.maps.LatLng(dt.latitude, dt.longitude));
                                peta.setZoom(9);

                                var myLatlng = new google.maps.LatLng(dt.latitude, dt.longitude);
                                marker = new google.maps.Marker({
                                    map: peta,
                                    position: myLatlng,
                                    icon: '<?php echo  base_url() ?>assets/images/icon/' + dt.icon
                                });

                                google.maps.event.addListener(marker, 'click', function(e) {
                                    var contentString = "<table width='300px'>" +
                                            "<tr><td width='70px'>Site ID</td><td width='5px'>:</td><td>" + dt.site_id + "</td></tr>" +
                                            "<tr><td width='70px'>Site Name</td><td width='5px'>:</td><td>" + dt.site_name + "</td></tr>" +
                                            "<tr><td width='70px'>Vendor ID</td><td width='5px'>:</td><td>" + dt.vendor_id + "</td></tr>" +
                                            "<tr><td width='70px'>Address</td><td width='5px'>:</td><td>" + dt.site_address + "</td></tr>" +
                                            "<tr><td width='70px'>Site Profile</td><td width='5px'>:</td><td>" + dt.site_profile + "</td></tr>" +
                                            "</table>";
                                    infoWindow.setContent(contentString);
                                    infoWindow.setPosition(e.latLng);

                                    infoWindow.open(peta);
                                });
                                infoWindow = new google.maps.InfoWindow();
                            }
                        }
                    }

                }
            });
            return false;
        });

        $("#search_site").live('submit', function() {

            var row = $('#dg').datagrid('getSelected');
            if (row) {
                //Document.searchSiteID( row.site_id );
                $(".txtSearch").val(row.site_code);
                //$("#nomsite").val( row.site_id );
            }
            $.fancybox.close();
            return false;
        });
        //end legend

        $('#region_id').change(function() { // {^_^) andedi 20141212
            $.ajax({
                url: '<?php echo base_url('gis/cluster_json'); ?>',
                data: {region_id: $(this).val()},
                type: 'post',
                dataType: 'json',
                beforeSend: function() {

                },
                success: function(data) {
                    $('#cluster_id').html('<option value="0">- All Cluster -</option>');
                    $.each(data.clusters, function(index, value) {
                        $('#cluster_id').append('<option value="' + value.cluster_ops_id + '">' + value.cluster_name + '</option>');
                    });
                },
                error: function(xhr, status, message) {
                    coolNotification('Cannot load cluster.' + status.toUpperCase() + ' : ' + message, 'error');
                },
                complete: function() {

                }
            });
        });

        $('#form1').submit(function(e) { // {^_^) andedi 20141212
            e.preventDefault();
            showMap($(this).serialize());
        });
    });
    
    function showMap(formData) {
        clearPolygons();
        $.ajax({
            url : '<?php echo base_url('gis/territory_border'); ?>',
            data: formData,
            type: 'post',
            dataType: 'json',
            beforeSend: function() {
                block.show('Processing');
            },
            success: function(data) {
                if (data.regions.length > 0 || data.clusters.length > 0) {
                    var latLongBound, content;
                    var bounds = new google.maps.LatLngBounds();

                    if (data.regions != null) {
                        var path    = [];
                        var regionGroup = 0;
                        var regionName;
                        $.each(data.regions, function(index, rgBound) {
                            if (index==0) regionGroup=rgBound.territory_id;

                            latLongBound = new google.maps.LatLng(rgBound.lat, rgBound.lon);
                            bounds.extend(latLongBound);

                            if (regionGroup==rgBound.territory_id) {
                                path.push(latLongBound); 
                            } else {
                                content = '<div><b>'+regionName+'</b></div>';
                                addPolygon(path, content, 1);
                                path=[];
                                path.push(latLongBound); 
                            }

                            regionGroup = rgBound.territory_id;
                            regionName = rgBound.region_name;
                        });
                        content = '<div><b>'+regionName+'</b></div>';
                        addPolygon(path, content, 1);
                        map.fitBounds(bounds);
                    }

                    if (data.clusters != null) {
                        var path    = [];
                        var clusterGroup = 0;
                        var regionName, clusterName;
                        $.each(data.clusters, function(index, clBound) {
                            if (index==0) clusterGroup=clBound.territory_id;

                            latLongBound = new google.maps.LatLng(clBound.lat, clBound.lon);
                            bounds.extend(latLongBound);

                            if (clusterGroup==clBound.territory_id) {
                                 path.push(latLongBound); 
                            } else {
                                content = '<div><b>'+regionName+' > '+clusterName+'</b></div>';
                                addPolygon(path, content, 2);
                                path=[];
                                path.push(latLongBound); 
                            }

                            clusterGroup = clBound.territory_id;
                            regionName = clBound.region_name;
                            clusterName = clBound.cluster_name;
                        });
                        content = '<div><b>'+regionName+' > '+clusterName+'</b></div>';
                        addPolygon(path, content, 2);
                        map.fitBounds(bounds);
                    }
                } else {
                    var region = $('#region_id option:selected').text();
                    var cluster = $('#cluster_id option:selected').text();
                    coolNotification('Sorry, no data for : '+region+' > '+cluster, 'error');
                }
            },
            error: function(xhr, status, message) {
                coolNotification('Load boundary error.<br>' + status.toUpperCase() + ' : ' + message, 'error');
            },
            complete: function() {
                block.close();
            }
        });
    }
    
    function addPolygon(path, content, type) {
        var color;
        
        if (type==1) { //region
            color = 'red';
        } else { //cluster
            color = 'blue';
        }
        
        var polygon = new google.maps.Polygon({
            path            : path,
            strokeColor     : 'grey',
            strokeOpacity   : 0.8,
            strokeWeight    : 1,
//            fillColor       : getRandomColor(),
            fillColor       : color,
            fillOpacity     : 0.35
        });
        
        if (type==1) { //region
            polygonRegion.push(polygon);
            polygon.setMap(map);
        } else { //cluster
            polygonCluster.push(polygon);
            polygon.setMap(map);
        }
        
        //info window buat polygon
        infoPolygon = new google.maps.InfoWindow();
        google.maps.event.addListener(polygon, 'click', function (event){
            infoPolygon.setContent(content);
            infoPolygon.setPosition(event.latLng);
            infoPolygon.open(map);
        });
    }
    
    function clearPolygons() {
        for (var i=0; i<polygonRegion.length; i++) {
            polygonRegion[i].setMap(null);
        }
        polygonRegion = [];
        
        for (var i=0; i<polygonCluster.length; i++) {
            polygonCluster[i].setMap(null);
        }
        polygonCluster = [];
    }
    
    function showHideRegion() {
        if ($('#region-cb').attr('checked')=='checked') {
            for (var i=0; i<polygonRegion.length; i++) {
                polygonRegion[i].setMap(map);
            }
            console.log('Hide Region');
        } else {
            for (var i=0; i<polygonRegion.length; i++) {
                polygonRegion[i].setMap(null);
            }
            console.log('Show Region');
        }
    }
    
    function showHideCluster() {
        if ($('#cluster-cb').attr('checked')=='checked') {
            for (var i=0; i<polygonCluster.length; i++) {
                polygonCluster[i].setMap(map);
            }
            console.log('Hide Cluster');
        } else {
            for (var i=0; i<polygonCluster.length; i++) {
                polygonCluster[i].setMap(null);
            }
            console.log('Show Cluster');
        }
    }
    
</script>