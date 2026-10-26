<!--
	<div class="heading-buttons">
    <h3><?php echo $title?><span class="hidden-phone">|<em> <?php echo $detail_title ?></em></span></h2></h3>
</div>
-->
<div class="separator bottom"></div>
<?php if(isset($component)){ ?>
<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="form_report">
<div class="innerLR">
    <div class="widget">
        <div class="widget-head"><h2 class="heading"><?php echo $subtitle?></h2></div>
        <div class="widget-body">
            <div class="row-fluid">
                <input type="hidden" id="menu_number" value="<?php echo $menu_number?>" />
                        <?php
                            echo '
                                <div class="control-group column-left">
                                     <label class="control-label" for="date"><b>Date from</b> &nbsp;</label>
                                        <select id="year1" name="u1" style="width:88px">';
                                        foreach ($years as $year) {
                                            if ($year['value']==date('Y')) $selected = 'selected';
                                            echo '<option value="'.$year['value'].'"'.$selected.'>'.$year['label'].'</option>';
                                            $selected = '';
                                        }
                                        echo '</select>';
                                        echo ' <select id="month1" name="u11" style="width:130px">';
                                        echo '</select>';
                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>to</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                        echo ' <select id="month2" name="u12" style="width:130px">';
                                        echo '</select>';
                            echo '</div>';
                            
                            //dropdown region
                            echo '<div class="control-group column-left">';
                            echo '<label class="control-label"> <b>Region</b> &nbsp;</label>';
                            echo '<select name"u2" id="region">';
                            echo '<option value="">- All Region -</option>';
                            foreach ($regions as $region) {
                                echo '<option value="'.$region['value'].'">'.$region['label'].'</option>';
                            }
                            echo '</select>';
                            
                            //dropdown cluster
                            echo '<span id="cluster">';
                            echo '&nbsp;<b>Cluster</b>&nbsp;';
                            echo '<select name"cluster_ops_id" id="cluster_ops_id">';
                            echo '<option value="">- All Cluster -</option>';
                            echo '</select>';
                            echo '</span>';
                            
                            //dropdown fops
                            echo '<span>';
                            echo '&nbsp;<b>FOPS</b> &nbsp;';
                            echo '<input name="fops" id="fops" value="" readonly>	
                                <button id="doSearchPIC" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>';
                            echo '</span>';
                            echo '</div>';
                        ?>     
            </div>  
            <div class="form-actions">
                <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok generate"><i></i>Generate</button>
            </div>
        </div>
    </div>
    <div class="widget report">
        <div class="widget-body">
            <div class="row-fluid">
                    <div class="row-fluid canvas"></div>                            
            </div>
        </div>
    </div>
</div>
</form>
<?php }else{ ?>
<div class="innerLR">
     <div class="widget">
        <div class="widget-head"><h2 class="heading"><?php echo $subtitle?></h2></div>
            <div class="report">
            <div class="widget-body">
                <div class="row-fluid">
                        <div class="row-fluid canvas"></div>                            
                </div>
            </div>
        </div>
    </div>
    
</div>
<?php } ?>

<script language="javascript" src="<?php echo base_url();?>assets/js/jquery.form.js"></script>
<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>
<script>
    
$(function() {

	$( "#u1" ).datepicker({
		dateFormat: 'yy-mm-dd'
	});
	$( "#u2" ).datepicker({
		dateFormat: 'yy-mm-dd'
	});
        
        getYear();
        filterMonth1();
        $("#month2").append('<option value=""> - Month - </option>');
});
<?php if(isset($component)){ ?>
    var url = "https://sims.smartfren.com/report/mydbr/report.php?";
    
    function generateUrl(r, m, h){
        return this.url+'r='+r+'&m='+m+'&h='+h+'&hdr=0';
    }    
    
$('.generate').live( "click", function(e) {
    var year1 = document.getElementById('year1').value;
    var month1 = document.getElementById('month1').value;
    var month2 = document.getElementById('month2').value;
    var region = document.getElementById('region').value;
    var cluster = document.getElementById('cluster_ops_id').value;
    var interval = countMonth();
    var fops = document.getElementById('fops').value;
    var reportId, hash, group, html;
    e.preventDefault();
    if (cluster!=0 && region!='' && month1!=0 && month2!=0) {
        //Year Month Region Cluster selected
        reportId = "114";
        hash = '0d17c1c63e274da1bddd9fb7d10c6bbd61c407a4';
        group = '19';
        html = "<iframe src='"+generateUrl(reportId, group, hash)+"&u1="+year1+"&u2="+region+"&u3="+cluster+"&u4="+month1+"&u5="+interval+"' height='<?php echo $height;?>' width='<?php echo $width;?>' align='center' frameborder='0'></iframe>";
    } else if (region!='' && month1!=0 && month2!=0) {
        // Year Month Region selected
        reportId = "110";
        hash = 'f378e66aad8dc0a2af6678d029c18cf0085b29e8&i=1';
        group = '19';
        html = "<iframe src='"+generateUrl(reportId, group, hash)+"&u1="+year1+"&u2="+region+"&u3="+month1+"&u4="+interval+"' height='<?php echo $height;?>' width='<?php echo $width;?>' align='center' frameborder='0'></iframe>";
    } else if (month1!=0 && month2!=0) {
        // Year Month selected
        reportId = "100";
        hash = '5842a2fae18e0aab5cda160c551da396c0709eec';
        group = '19';
        html = "<iframe src='"+generateUrl(reportId, group, hash)+"&u1="+year1+"&u2="+month1+"&u3="+interval+"' height='<?php echo $height;?>' width='<?php echo $width;?>' align='center' frameborder='0'></iframe>";
    } else if(cluster!=0 && region!='') {
        // Year Region Cluster selected
        reportId = "111";
        hash = '106d836ceed713da1f34bcc72ec0146193b647e7';
        group = '20';
        html = "<iframe src='"+generateUrl(reportId, group, hash)+"&u1="+year1+"&u2="+cluster+"' height='<?php echo $height;?>' width='<?php echo $width;?>' align='center' frameborder='0'></iframe>";
    } else if (region!='') {
        //Year Region selected
        reportId = "108";
        hash = '69b083f7b54b25eefa933a1401f5341348aa88b8';
        group = '20';
        html = "<iframe src='"+generateUrl(reportId, group, hash)+"&u1="+year1+"&u2="+region+"' height='<?php echo $height;?>' width='<?php echo $width;?>' align='center' frameborder='0'></iframe>";
    } else {
        //Year selected
        reportId = "98";
        hash = '08304e9498c9b7997d2bb7b92655b47e858f85d1';
        group = '20';
        html = "<iframe src='"+generateUrl(reportId, group, hash)+"&u1="+year1+"' height='<?php echo $height;?>' width='<?php echo $width;?>' align='center' frameborder='0'></iframe>";
    }
    console.log(html);
    $('.report').show();
    $( ".canvas" ).html(html);
});

jQuery(document).ready(function() { 
    $($( "#menu_number" ).val()).parent().addClass('active').find('.collapse').css('height','auto');
    $('.report').hide();
});

<?php }else{ ?>
    jQuery(document).ready(function() { 
        $($( "#menu_number" ).val()).parent().addClass('active').find('.collapse').css('height','auto');
        $('.report').show();
        var html = "<iframe src='<?php echo $url;?>' height='<?php echo $height;?>' width='<?php echo $width;?>' align='center' frameborder='0'></iframe>";
        $( ".canvas" ).html(html);
        console.log(html);
    });
<?php } ?>

    //andedi 05-09-2014, {^_^) Tumbas Wiralodra
        $("#region").change(function(){
                var region_id = {region_id:$("#region").val()};
                $.ajax({
                        type: "POST",
                        url : "<?php echo base_url('referensi/map/getClusterByReg')?>",
                        data: region_id,
                        success: function(msg){
                            $("#cluster").html(msg);
                        }
                    });
        });
        
    //filter PIC
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
			afterShow:function(){$('#srcSite').focus();}
		});	
	});
	
	$("#search_pic").live('submit', function () {
		
		var ss = [];
		var rows = $('#dg').datagrid('getSelections');
		for(var i=0; i<rows.length; i++){
		    var row = rows[i];
		    ss.push(row.user_id);
		}
		$("#fops").val( ss.join(';') );
		
		$.fancybox.close();
		return false;
	});
        //======================================================================
        
        //andedi 09-09-2014, {^_^) Tumbas Wiralodra
        function getYear() {
            var year1_chain = document.getElementById('year1').value;
            var selected = '';
            $("#year2").html('');
            for (var i=<?php echo date('Y')?>; i>=year1_chain; i--) {
                if (i==year1_chain) selected = 'selected';
                $("#year2").append('<option value="'+i+'" selected="'+selected+'">'+i+'</option>');
                selected = '';
            }
        }
        
        function filterMonth1() {
            var month = ['','Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September','Oktober', 'November', 'Desember'];
            var year1_chain = document.getElementById('year1').value;
            $("#month1").html('');
            $("#month1").append('<option value=""> - Month - </option>');
            for (var i=1; i<=12; i++) {
                $("#month1").append('<option value="'+i+'">'+month[i]+' '+year1_chain+'</option>');
            }
        }
        
        function filterMonth2() {
            var month = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September','Oktober', 'November', 'Desember'];
            var month1_chain = document.getElementById('month1').value;
            var year1_chain = document.getElementById('year1').value;
            $("#month2").html('');
            $("#month2").append('<option value=""> - Month - </option>');
            var selected = '';
            if (month1_chain!='') {
                for (var i=1; i<=12; i++) {
                    if (month1_chain>12) {
                        month1_chain=1;
                        year1_chain = parseInt(year1_chain)+1;
                    }
                    $("#month2").append('<option value="'+year1_chain+'-'+month1_chain+'"'+selected+'>'+month[month1_chain]+' '+year1_chain+'</option>');
                    month1_chain++;
                }
            }
            
        }
        
        function countMonth() {
            var year1 = document.getElementById('year1').value;
            var month1 = document.getElementById('month1').value;
            var month2_str = document.getElementById('month2').value;
            var year2 = month2_str.substr(0,4);
            var month2 = month2_str.substr(5,2);
            var numberOfMonths=(year2-year1)*12+(month2-(month1-1));
            
            return numberOfMonths;
        }
        
        $('#month1').change(function (){
            filterMonth2();
        });
        
         $('#month2').change(function (){
            countMonth()
        });
        
        $('#year2').change(function (){
            filterMonth2();
        });
        
        $('#year1').change(function (){
            filterMonth1()
            filterMonth2()
        });
        //======================================================================
</script>