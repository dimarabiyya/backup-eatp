
<style type="text/css">
	.form-horizontal .control-label { text-align:left; }
	.uppercase {text-transform:uppercase;}
</style>
<script language="javascript" src="<?php echo base_url();?>assets/js/jquery.form.js"></script>
<script src="<?php echo base_url()?>assets/scripts/DataTables/media/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" media="screen" href="<?php echo base_url();?>>assets/scripts/DataTables/media/css/DT_bootstrap.css">


<form action="<?php echo base_url()?>user/MobileUserManagement/save" class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form-app" enctype="multipart/form-data">
	<div class="widget">
		<div class="widget-head"><h4 class="heading">Edit Mobile user</h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>

                                                <div class="control-group user_form" id="form_user_id">
                                                        <label class="control-label" for="user_id">User ID</label>
                                                        <div class="controls"><input class="span6" id="user_id" maxlength="50" name="user_id" type="text" value="<?php echo $data->user_id;?>" readonly="readonly" /></div>
                                                </div>
                                                <div class="control-group" id="form_mobileaccess">
                                                        <label class="control-label" for="mobile_type">Mobile App Type</label>
                                                        <div class="controls">
                                                                <select id="mobile_type" name="mobile_type">
                                                                        <option value="0">--- [None] ---</option>
                                                                        <?php
                                                                        $getMobileType = $this->muser->getMobileType();
                                                                        $thereselected = false;
                                                                        foreach ($getMobileType as $r) {
                                                                        	 if ($data->mobile_id == $r->lookup_value){
                                                                             $thereselected=true;
                                                                             echo '<option selected="selected" value="'.$r->lookup_value.'">'.$r->lookup_desc.'</option>';
                                                                         	 }else
                                                                             echo '<option value="'.$r->lookup_value.'">'.$r->lookup_desc.'</option>';
                                                                       }
                                                                        ?>
                                                                        <?php if ($thereselected == false){?>
                                                                        <option selected="selected" value="3">SI Mobile + PM Mobile</option>
                                                                        <?php }else{?>
                                                                        <option value="3">SI Mobile + PM Mobile</option>
                                                                        <?php }?>
                                                                </select>
                                                        </div>
                                                </div>      
                                                <div class="control-group mobile_form" id="form_msisdn">
                                                        <label class="control-label" for="msisdn">Phone No.</label>
                                                        <div class="controls"><input class="span6" id="msisdn" maxlength="50" name="msisdn" type="text" value="<?php echo $data->msisdn;?>" /></div>
                                                </div>
                                                <div class="control-group mobile_form" id="form_imei">
                                                        <label class="control-label" for="imei">MEID</label>
                                                        <div class="controls"><input class="span6 uppercase" id="imei" maxlength="50" name="imei" type="text" value="<?php echo $data->imei;?>" /></div>
                                                </div>

                                                <div class="control-group mobile_form" id="form_handset_model">
                                                        <label class="control-label" for="handset_model">Handset Model</label>
                                                        <div class="controls"><input class="span6" id="handset_model" maxlength="50" name="handset_model" type="text" value="<?php echo $data->handset_model;?>" /></div>
                                                </div>
                                                
                                                <div class="control-group mobile_form" id="form_handset_man">
                                                        <label class="control-label" for="handset_man">Handset Manufacture</label>
                                                        <div class="controls"><input class="span6" id="handset_man" maxlength="50" name="handset_man" type="text" value="<?php echo $data->handset_man;?>" /></div>
                                                </div>


                    <div class="control-group" style="display:none">
                    	<label class="control-label"></label>
						<div class="controls" id="dState">
						</div>
                    </div>
					<div class="form-actions">
						<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok submit"><i></i>Save</button>
						<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove"><i></i>Cancel</button>
						<script>
							$(".btn-close").click(function(){$.fancybox.close();});
						</script>
					</div>              
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<script type="text/javascript">

  jQuery(document).ready(function() {
    $('.fancybox-wrap').draggable({ handle: ".widget-head" });
        $("#msisdn").focusout(function(){
            hp = $(this).val();
            if (hp.substring(0,2) == '08')
                format_hp = '628' + hp.substring(2);
            else if (hp.substring(0,2) == '88')
                format_hp = '62' + hp;
            else
                format_hp = hp;

            format_hp = format_hp.replace(/\s/g, "");

            $(this).val(format_hp);
        });
		$("#add-form-app").ajaxForm({ dataType:  'json',success:   processJson ,beforeSubmit: validate});
	    $("#add-form-app").submit(function(event) {});



	    function validate(){
				$('.submit').attr('disabled',true);
		        $('#dState').html('<span class="popnotif loading">loading,saving data..</span>').parent().slideDown();
				if($("#add-form-app").valid()==false){
					$('.submit').removeAttr('disabled');
			     	$('#dState').html('<span class="popnotif warning">Please complete document form data</span>');
					return false;
				}
	    }
		function processJson(data) { 
			if (data.status=='OK'){
					$('#dg').datagrid('load');	


				$.fancybox.close();

				// $.gritter.add({
				// 	image: '<?php echo base_url();?>file/images/logosmartfren.png',
		  //           title: 'Save Success',
		  //           text: data.hasil
		  //       });
				coolNotification(data.hasil, 'success', function() { });
		     }else{
				$('.submit').removeAttr('disabled');
		     	$('#dState').html('<span class="popnotif warning">'+data.hasil+'</span>');
		     }
		}
		
       $( ".datepicker" ).datepicker({
           dateFormat: "dd M yy",
           changeMonth: true,
           numberOfMonths: 1,
           changeYear : true
       }).css({"z-index":8300});




		$("#add-form-app").validate({
			rules: {
				imei: "required",
				msisdn: "required"
			},
			messages: {
				imei: "Please input imei",
			}
		});
	});
</script>