<script>
$(document).ready(function(){
	$('.form-change-password').live("submit",function(e){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('/user/savePassword') ?>",
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
					alert('Data Berhasil Disimpan');
					window.location.replace( data.hasil );			
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
</div>
<div class="separator bottom"></div>

<div class="innerLR">
<form class="form-change-password" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<?php
			if($this->session->userdata('must_change_password') == 1) {
			?>
			<div class="row-fluid">
				<div class="span6">
					<strong>Must Change Password</strong>
					<p class="muted" style="color:red;">
						Akun Anda masih menggunakan Password standard.<br>
						Anda harus mengganti password terlebih dahulu untuk dapat mengakses halaman lainnya.</p>
				</div>
			</div>
			<hr class="separator bottom" />
			<?php } ?>
			
			<div id='alert-msg'></div>
			
			<div class="row-fluid">
				<div>
                                        <input type='hidden' name='id' value='<?php echo $id?>' >
					<div class="control-group" id="form_parent">
						<label class="control-label" for="parent"><b>User Id</b></label>
						<div class="controls">
							<b><?php echo $this->session->userdata('user_id')?></b>
						</div>
					</div>
					<div class="control-group" id="form_old_password">
						<label class="control-label" for="nama">Old Password</label>
						<div class="controls"><input class="span6" id="old_password" name="old_password" type="password" /></div>
					</div>
					<div class="control-group" id="form_new_password">
						<label class="control-label" for="url">New Password</label>
						<div class="controls"><input class="span6" id="new_password" name="new_password" type="password" /></div>
					</div>
					<div class="control-group" id="form_cnew_password">
						<label class="control-label" for="url">Confirm New Password</label>
						<div class="controls"><input class="span6" id="cnew_password" name="cnew_password" type="password" /></div>
					</div>
                                        
				</div>
			</div>
			
		<?php //-------------- cek sudah ada SQ?
		if(count($getSQ) == 0){
		?>
			<hr class="separator" />
			<div class="row-fluid">
				<h4>Security Question</h4>
				<div class="control-group" id="form_old_password">
					<label class="control-label" for="question1">Question 1</label>
					<div class="controls"><input class="span6" id="question1" name="question1" type="text"  /></div>
				</div>
				<div class="control-group" id="form_new_password">
					<label class="control-label" for="answer1">Answer</label>
					<div class="controls"><input class="span6" id="answer1" name="answer1" type="text" /></div>
				</div>
                                        
			</div>
			
			<hr class="separator" />
			<div class="row-fluid">
				
				<div class="control-group" id="form_old_password">
					<label class="control-label" for="question2">Question 2</label>
					<div class="controls"><input class="span6" id="question2" name="question2" type="text" /></div>
				</div>
				<div class="control-group" id="form_new_password">
					<label class="control-label" for="answer2">Answer</label>
					<div class="controls"><input class="span6" id="answer2" name="answer2" type="text" /></div>
				</div>
                                        
			</div>
		<?php } ?>
			<div class="form-actions">
				<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
				<button type="button" class="btn btn-icon btn-default glyphicons circle_remove" Onclick="history.go(-1)"><i></i>Cancel</button>
			</div>
		</div>
		
	</div>
</form>
</div>