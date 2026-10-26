<script>
$(document).ready(function(){
	$('.form-change-password').live("submit",function(e){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('/user/saveSecurityQuestion') ?>",
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
					alert("Security Question Berhasil disimpan");
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
				
				<input type='hidden' name='id' value='<?php echo $id?>' >
				<div class="control-group" id="form_parent">
					<label class="control-label" for="parent"><b>User Id</b></label>
					<div class="controls">
						<b><?php echo $this->session->userdata('user_id')?></b>
					</div>
				</div>
				<div class="control-group" id="form_old_password">
					<label class="control-label" for="question1">Question 1</label>
					<div class="controls"><input class="span6" id="question1" name="question1" type="text" value="<?php echo $question1?>" /></div>
				</div>
				<div class="control-group" id="form_new_password">
					<label class="control-label" for="answer1">Answer</label>
					<div class="controls"><input class="span6" id="answer1" name="answer1" type="text" value="<?php echo $answer1?>" /></div>
				</div>
                                        
			</div>
			
			<hr class="separator" />
			<div class="row-fluid">
				
				<div class="control-group" id="form_old_password">
					<label class="control-label" for="question2">Question 2</label>
					<div class="controls"><input class="span6" id="question2" name="question2" type="text" value="<?php echo $question2?>" /></div>
				</div>
				<div class="control-group" id="form_new_password">
					<label class="control-label" for="answer2">Answer</label>
					<div class="controls"><input class="span6" id="answer2" name="answer2" type="text" value="<?php echo $answer2?>" /></div>
				</div>
                                        
			</div>
			
			<hr class="separator" />
			
			<div class="form-actions">
				<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
				<button type="button" class="btn btn-icon btn-default glyphicons circle_remove"><i></i>Cancel</button>
			</div>
		</div>
		
	</div>
</form>
</div>