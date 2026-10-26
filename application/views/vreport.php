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
                            foreach($component as $key=>$value){
                                echo '<div class="control-group column-left" id="param_'.$value['id'].'">
                                    <label class="control-label" for="'.$value['id'].'"><b>'.$value['name'].'</b></label>
                                    <div class="controls">'; 
                                    
                             if($value['type']=='option'){
                                    echo "<select id='".$value['id']."' name='".$value['id']."' >";
                                        foreach($value['param'] as $key=>$param){                                        
                                            echo "<option value=".$param['value'].">".$param['label']."</option>"; 
                                        }
                                    echo '</select>';
                             }elseif($value['type']=='calendar1') {
                                echo '<div class="input-append">
										<input id="'.$value['id'].'" name="'.$value['id'].'" type="text" value="'.$value['param'].'" style="width:110px;margin-right:15px;" />
        				            </div>';
                             }elseif($value['type']=='calendar2') {
                                echo '<div class="input-append">
										<input id="'.$value['id'].'" name="'.$value['id'].'" type="text" value="'.$value['param'].'" style="width:110px;margin-right:15px;" />
        				            </div>';
                             }
                                                             
                                    
                                    echo '</div></div>';
                                }
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
});
<?php if(isset($component)){ ?>
$('.generate').live( "click", function(e) {
    e.preventDefault();
    if (!document.getElementById('u3')) {
        if (!document.getElementById('u2')) {
            
        } else{
            var html = "<iframe src='<?php echo $url;?>&u1="+document.getElementById('u1').value+"&u2="+document.getElementById('u2').value+"' height='<?php echo $height;?>' width='<?php echo $width;?>' align='center' frameborder='0'></iframe>";
        };
    } else{
        var html = "<iframe src='<?php echo $url;?>&u1="+document.getElementById('u1').value+"&u2="+document.getElementById('u2').value+"&u3="+document.getElementById('u3').value+"' height='<?php echo $height;?>' width='<?php echo $width;?>' align='center' frameborder='0'></iframe>";
    };
    if (!document.getElementById('u3')) {
        if (!document.getElementById('u2')) {
            var html = "<iframe src='<?php echo $url;?>&u1="+document.getElementById('u1').value+"' height='<?php echo $height;?>' width='<?php echo $width;?>' align='center' frameborder='0'></iframe>";
        } else{
            var html = "<iframe src='<?php echo $url;?>&u1="+document.getElementById('u1').value+"&u2="+document.getElementById('u2').value+"' height='<?php echo $height;?>' width='<?php echo $width;?>' align='center' frameborder='0'></iframe>";
        };
    } else{
        var html = "<iframe src='<?php echo $url;?>&u1="+document.getElementById('u1').value+"&u2="+document.getElementById('u2').value+"&u3="+document.getElementById('u3').value+"' height='<?php echo $height;?>' width='<?php echo $width;?>' align='center' frameborder='0'></iframe>";
    };
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
</script>