<?php
$url_add_edit = base_url()."user/addeditdata";
?>
<div id='url_add_edit' url='<?php echo $url_add_edit?>'></div>
<script>
          
$(document).ready(function() {
        
        $('#add-form').live("submit",function(e){
                $.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>user/saveEditProfile",
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
                                        alert( "Data berhasil disimpan" );
                                        window.location.reload()
                                }else{
                                        alert( data.hasil );
                                }
                        }
                });
                return false;
	});
        
        
});
</script>

<div class="heading-buttons">
	<h3><?php echo $judul?></h3>
	<div class="buttons pull-right">
		<a href='<?php echo base_url()?>user/ChangePassword' class='btn btn-primary btn-icon glyphicons lock' ><i></i>Change Password</a>
                <a href='<?php echo base_url()?>user/ChangeSecurityQuestion' class='btn btn-primary btn-icon glyphicons circle_question_mark' ><i></i>Change Security Question</a>
	</div>
</div>
<div class="separator bottom"></div>

<?php
$nama = ""; $email="";$hp="";
$getData = $this->muser->getDetailUser( $this->session->userdata('user_id') );
foreach($getData as $r){
        $nama   = $r->user_name;
        $email  = $r->email;
        $hp     = $r->mobile_number;
}
?>
<div class="innerLR">
        <div class="widget">
		
		<div class="widget-head">
			<h4 class="heading glyphicons list" id="h4judul"><?php echo $judul?></h4>
		</div>
		<div class="widget-body">
                        <form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
                        <div class="row-fluid">
				<div class="row-fluid">
                                        <div class="span12">
                                                <input type='hidden' name='id' id='id' />
                                                <div class="control-group" id="form_user_id">
                                                        <label class="control-label" for="user_id">User ID</label>
                                                        <div class="controls"><input type="text" class="span6" id="user_id" name="user_id" value="<?php echo $this->session->userdata('user_id')?>" disabled />
                                                        </div>
                                                </div>
                                                <div class="control-group" id="form_nama">
                                                        <label class="control-label" for="nama">Name</label>
                                                        <div class="controls"><input type="text" class="span6" id="nama" name="nama" value="<?php echo $nama?>" /></div>
                                                </div>
                                                
                                                
                                                <div class="control-group" id="form_hape">
                                                        <label class="control-label" for="hape">Mobile Number</label>
                                                        <div class="controls"><input type="text" class="span6" id="hape" name="hape" value="<?php echo $hp?>" /></div>
                                                </div>
                                                <div class="control-group" id="form_email">
                                                        <label class="control-label" for="email">E-Mail</label>
                                                        <div class="controls"><input type="text" class="span6" id="email" name="email" value="<?php echo $email?>" /></div>
                                                </div>
                                                
                                                <div class="form-actions">
                                                        <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
                                                        <button type="button" class="btn btn-icon btn-default glyphicons circle_remove" id="btnCancelAddData"><i></i>Cancel</button>
                                                </div>  
                                        </div>
                                </div>
			</div>
                        </form>
		</div>
	</div>
        
        
</div>