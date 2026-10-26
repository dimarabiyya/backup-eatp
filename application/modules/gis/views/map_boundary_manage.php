<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ?>assets/css/polygon/polygon-style.css">


<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<!--<script type="text/javascript" src="<?php echo  base_url() ?>assets/js/polygon/jquery-1.4.2.min.js"></script>-->
<script type="text/javascript" src="<?php echo  base_url() ?>assets/js/polygon/polygon.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ?>file/js/easyui/themes/default/easyui.css" />
<!--<link rel="stylesheet" type="text/css" href="<?php echo  base_url() ?>file/js/easyui/themes/icon.css" />-->
<!--<script type="text/javascript" src="<?php echo  base_url() ?>file/js/easyui/jquery-1.7.1.min.js"></script>-->
<script type="text/javascript" src="<?php echo  base_url() ?>file/js/easyui/jquery.easyui.min.js"></script>

<script type="text/javascript" src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<script type="text/javascript">
    $('.container-fluid:first').toggleClass('menu-hidden');
    $('#menu').toggleClass('hidden-phone');
    $(document).ready(function() {
        var val = [];
        $('#cc').combotree('setValue', val);

    });
</script>

<script type="text/javascript">
    $(function() {
        //create map
        // var posisiCenter=new google.maps.LatLng(-6.8118984663854905,112.8955078125);
        var posisiCenter = new google.maps.LatLng(-8.796647, 119.621416666667);
        var myOptions = {
            zoom: 5,
            center: posisiCenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map(document.getElementById('main-map'), myOptions);

        var creator = new PolygonCreator(map);


        //load existing polygon
        $(".combo-text").live("change", function() {
            alert('ubah');
            var val = $('#cc').combotree('getValue');
            alert(val);
        });
        $("#showBoundary").live("click", function() {

            var val = $('#cc').combotree('getValue');
            var tr = val.split('-');
            if (val == '') {
                 coolNotification('Please select a Region/Cluster.', 'error');
//                n = notyfy({
//                    layout: 'center',
//                    text: 'Please first select a Region/Cluster',
//                    type: 'error',
//                    modal: true
//                });
//                setTimeout(function() {
//                    n.close();
//                }, 3000);
                return;
            }


            $.ajax({
                url     : '<?php echo  base_url() ?>gis/showBoundary',
                type    : 'POST',
                data    : {"terr_id": val},
                dataType: 'json',
                beforeSend: function() {
                    block.show('Processing');
                },
                success: function(data) {
                    if (data.status == 'KOSONG') {
                        coolNotification('No Boundary.', 'error');
                    } else {
                        var latlng = [];
                        $.when($.each($.grep(data.bound, function(n, i) {
                            return (n.territory_id === tr[0]);
                        }), function() {
                            latlng.push(new google.maps.LatLng(this.lat, this.lon));
                        })).done(function() {

                            mapPoly = new google.maps.Polygon({
                                paths: latlng,
                                strokeColor: "#FF8800",
                                strokeOpacity: 0.8,
                                strokeWeight: 3,
                                fillColor: "#FF8800",
                                fillOpacity: 0.35
                            });
                            //mapPoly.setMap(null);
                            mapPoly.setMap(map);
                        });
                    }
                },
                error : function (xhr, status, message) {
                    coolNotification('Showing boundary failed.<br>'+status.toUpperCase()+' : '+message, 'error');
                },
                complete : function () {
                    block.close();
                }
            });
        });

        //reset
        $('#reset').click(function() {
            $('#dataCoord').val('');
            creator.destroy();
            creator = null;

            creator = new PolygonCreator(map);
        });


        //show paths
        $('#showData').click(function() {
            $('#dataCoord').empty();
            if (null == creator.showData()) {
                coolNotification('Please create a polygon.', 'error');
            } else {
                $('#dataCoord').val(creator.showData());
            }
        });

        //simpan data 6dec@dody
        $('#formSimpan').live("submit", function(e) {

            if (null == creator.showData()) {
                coolNotification('Please create a polygon.', 'error');
                return false;
            }
            $('#dataCoord').val(creator.showData());
            $.ajax({
                type    : "POST",
                url     : "<?php echo  base_url() ?>gis/save_boundary",
                data    : $(e.target).serialize(),
                dataType: "json",
                beforeSend: function() {
                   block.show('Saving data');
                },
                success: function(data) {
                    $.unblockUI();
                    var pesan = '';
                    var tipe = 'success';
                    if (data.status == "OK") {
                        pesan   = 'Boundary is saved.' + '<br>' + data.hasil;
                        tipe    = 'success';
                        $('#reset').click();
                        var isi = data.territory;
                        $("#terrRedirect").val(isi);
                        coolNotification(pesan, 'success', function (){
                            $('#formRedirect').submit();
                        });
//                        n = notyfy({
//                            layout: 'center',
//                            text: pesan,
//                            type: 'success',
//                            modal: true
//                        });
//                        setTimeout(function() {
//                            n.close();
//                            $('#formRedirect').submit();
//                        }, 3000);
                    } else {
                        pesan = 'Please first select a Region/Cluster';
                        tipe = 'error';
                        
                        coolNotification(pesan, tipe, function (){
                            $('#formRedirect').submit();
                        });
                        
//                        notyfy({
//                            layout: 'center',
//                            text: pesan,
//                            type: tipe,
//                            modal: true,
//                            buttons: [
//                                {addClass: 'btn btn-danger', text: 'OK', onClick: function($noty) {
//                                        $noty.close();
//                                    }
//                                }
//                            ]
//                        });
                    }
                },
                error : function (xhr, status, message) {
                    coolNotification('Showing boundary failed.<br>'+status.toUpperCase()+' : '+message, 'error');
                },
                complete : function () {
                    block.close();
                }
            });
            return false;
        });
    });
</script>
<style>
    #map-canvas img{
        max-width:none;
    }
    .combo-text {margin-bottom: 0px !important; padding:0px !important;}
</style>

<div class="innerLR">
    <div class="widget">	
        <div class="widget-head heading-outer">
            <h4 class="heading"><i></i> Boundary Manage</h4>
            <div class="btn-heading-group">
                <a class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right" href="<?php echo  base_url() ?>gis/boundary"><i></i>Back</a>
            </div>
        </div>

        <div class="widget-body">
            <div id="header_ins">
                <p>
                    <span class="instruction">Instruction:</span>
                    Left click on the map to create markers, when last marker meets first marker, it will form a polygon.
                </p>
            </div>

            <div class="row-fluid">
                <div class="span10" id="main-map" style="height:700px;"></div>
                <div class="span2" style="margin-left: 1.2%;">

                    <form id="formRedirect" method="post" action='<?php echo  base_url() ?>gis/boundary'>
                        <input type='hidden' name='territory' id='terrRedirect' >
                    </form>
                    <form id="formSimpan" method="post" class="form-horizontal">

                        <div class="control-group">
                            Region/Cluster:<br>
                            <input id="cc" name="territory"
                                   class="span12 easyui-combotree"
                                   url="<?php echo  base_url() ?>index.php/gis/territory_json_new"
                                   cascadeCheck="false" style="width:150%;margin-bottom: 0px;">
                            <button type="button" id="showBoundary" class="btn"><i></i>Show</button>
                        </div>

                        <div class="form-actions">
                            <button type="submit" id="showColor" class="btn"><i></i>Save</button>
                            <button type="button" id="reset" class="btn"><i></i>Reset</button>
                        </div>

                        <?php //	//<input id="showData"  value="Show Paths " type="button" class="navi"/> ?>
                        <textarea id="dataCoord" name="dataCoord" cols="50" rows="6" class="span12" ></textarea>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>