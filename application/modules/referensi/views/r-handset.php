<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui-1.8.16/jquery-ui-1.8.16.custom.min.js"></script>

<script src="<?php echo base_url('/assets/js/easyui/jquery.easyui.min.js') ?>"></script>
<link href="<?php echo base_url('/assets/js/easyui/themes/default/easyui.css') ?>" rel="stylesheet" />
<div class="innerLR">
<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					
					
					<div class="control-group" id="form_user_id">
						<label class="control-label" for="user_id">User ID</label>
						<div class="controls"><input class="span12" id="user_id" maxlength="30" name="user_id" type="text" value="<?php echo $user_id?>" /></div>
					</div>
										
					
					<div class="control-group" id="form_imei">
						<label class="control-label" for="imei">IMEI</label>
						<div class="controls"><input class="span12" id="imei" maxlength="50" name="imei" type="text" value="<?php echo $imei?>" /></div>
					</div>
					
					<div class="control-group" id="form_imsi">
						<label class="control-label" for="imsi">IMSI</label>
						<div class="controls"><input class="span12" id="imsi" maxlength="50" name="imsi" type="text" value="<?php echo $imsi?>" /></div>
					</div>
					
					<div class="control-group" id="form_msisdn">
						<label class="control-label" for="msisdn">MSISDN<br>
						<span style="color:gray;font-size:10px">Ex: 6288123456789</span>
						</label>
						<div class="controls"><input class="span12" id="msisdn" maxlength="50" name="msisdn" type="text" value="<?php echo $msisdn?>" /></div>
					</div>
					<div class="control-group" id="form_handset_model">
						<label class="control-label" for="handset_model">Handset Model</label>
						<div class="controls"><input class="span12" id="handset_model" maxlength="50" name="handset_model" type="text" value="<?php echo $handset_model?>" /></div>
					</div>
					
					<div class="control-group" id="form_handset_man">
						<label class="control-label" for="handset_man">Handset Manufactur</label>
						<div class="controls"><input class="span12" id="handset_man" maxlength="50" name="handset_man" type="text" value="<?php echo $handset_man?>" /></div>
					</div>
					
                        <div class="form-actions" style="display:none">
						<input TYPE="button" class="btn btn-icon btn-primary glyphicons unshare" VALUE="Back" onClick="history.go(-1);">
						<input type="submit" class="btn btn-icon btn-primary glyphicons circle_ok" value="Save" >
					</div>                
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>
	</div>
<script>

  $('#add-forms').live("submit",function(e){
                $.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>referensi/handset/saveData",
                        data: $(e.target).serialize(),
                        dataType: "json",
                        beforeSend:function(){
                                //$('.alert-error,.alert-success').hide();
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                                alert(errorThrown);
                        },
                        success: function(data){
                                if(data.status == "OK"){
                                        $('#add-form').trigger('reset');
                                        Document.loadData();
                                        bootbox.hideAll();
                                        alert( "Data berhasil disimpan" );
                                        /*
                                        $.gritter.add({
                                                                // (string | mandatory) the heading of the notification
                                                                title: 'Confirmation',
                                                                // (string | mandatory) the text inside the notification
                                                                text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                                                                // (string | optional) the image to display on the left
                                                                image: 'http://a0.twimg.com/profile_images/59268975/jquery_avatar_bigger.png',
                                                                // (bool | optional) if you want it to fade out on its own or just sit there
                                                                sticky: false,
                                                                // (int | optional) the time you want it to be alive for before fading out
                                                                time: ''
                                                        });
                                        */
                                }else{
                                        alert( data.pesan );
                                }
                        }
                });
                return false;
	});
function doExecCombo(){
	$('#user_id').combogrid({
		delay: 500,
		panelWidth: 500,
		mode: 'remote',
		url: '<?php echo base_url()?>referensi/handset/searchUser',
		idField: 'user_id',
		textField: 'user_name',
		columns: [[ 
			{field:'user_id',title:'user_id',width:120,sortable:true},
			{field:'user_name',title:'user_name',width:400,sortable:true}
		]],
		fitColumns: true
    });

}
$(function(){
	doExecCombo();
});

</script>