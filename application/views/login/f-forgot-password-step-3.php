<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>
<?php
$digits=6;
echo rand(pow(10, $digits-1), pow(10, $digits)-1);
?>

<script>
$(document).ready(function(){
	$('.FormForgotPassword').live("submit",function(e){
		$.ajax({
			type: "POST",
			url: "<?php echo  base_url()?>/login/cek_forgot_step_3",
			data: $(e.target).serialize(),
			dataType: "json",
			beforeSend:function(){
				$.blockUI({ message: 'Processing, please wait...', css: {
                                        border: 'none',
                                        padding: '15px',
                                        backgroundColor: '#000',
                                        '-webkit-border-radius': '10px',
                                        '-moz-border-radius': '10px',
                                        opacity: .5,
                                        color: '#fff'
                                } });
			},
			error: function(jqXHR, textStatus, errorThrown){
				$.unblockUI();
				alert(errorThrown);
			},
			success: function(data){
				if(data.status == "OK"){
					window.location.replace( data.hasil );
				}else{
					alert( data.hasil );
					var random = new Date().getTime();
					$('#ratcha_image').attr('src','<?php echo site_url('login/capcay') ?>?random='+random);
				}
				$.unblockUI();
			}
		});
		return false;
	});
});
	
</script>


	<h3><?php echo $judul?></h3>
	<div id="rootwizard" class="wizard">
		<div class="wizard-head">
			<ul>
				<li><a href=#>Tahap Pertama</a></li>
				<li><a href=#>Tahap Kedua</a></li>
				<li class='active'><a href=#>Tahap Ketiga</a></li>
			</ul>
		</div>
		<div class="widget">
			<div class="widget-head progress progress-primary" id="bar">
				<div class="bar">Step <strong class="step-current">1</strong> of <strong class="steps-total">3</strong> - <strong class="steps-percent">100%</strong></div>
			</div>
			<div class="widget-body">
				<form class="FormForgotPassword" method="post">
					<div class="tab-content">
						<div class="tab-pane active" id="tab1">
							<div class="row-fluid">
								<div class="span3">
									<strong>Type New Password</strong>
									<p class="muted">Silakan masukkan password baru Anda.</p>
								</div>
								<div class="span9">
									<label for="inputTitle">User Id : <b><?php echo $user_id?></b></label>
									<label for="new_password">Password Baru</label>
									<input type="password" name="new_password" id="new_password" class="span6" value="" placeholder="Password Baru ..." />
									
									<label for="cnew_password">Ulangi Password Baru</label>
									<input type="password" name="cnew_password" id="cnew_password" class="span6" value="" placeholder="Ulangi Password Baru ..." />
									
									<label for="password_code">Password Code</label>
									<input type="text" name="password_code" id="password_code" class="span6" value="" placeholder="Password Code ..." />
								</div>
							</div>
							<div class="form-actions">
								<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Simpan Password</button>
								<a href='<?php echo base_url()?>login/logout' class='btn btn-icon btn-default glyphicons circle_remove'><i></i>Batal</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>