<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>
<script>
$(document).ready(function(){
	$('.FormForgotPassword').live("submit",function(e){
		$.ajax({
			type: "POST",
			url: "<?php echo  base_url()?>/login/cek_forgot_step_1",
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
					window.location.reload();
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
				<li class='active'><a href=#>Tahap Pertama</a></li>
				<li><a href=#>Tahap Kedua</a></li>
				<li><a href=#>Tahap Ketiga</a></li>
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
									<strong>Masukkan User Id Anda</strong>
									<p class="muted">Silakan masukkan <b>User Id</b> Anda, kemudian klik tombol "Next".</p>
								</div>
								<div class="span9">
									<label for="inputTitle">User Id</label>
									<input type="text" id="username" name="username" class="span6" value="" placeholder="User Id" />
									
									<label for="inputTitle">Kode Validasi</label>
									<input type="text" id="ractha" name="ractha" class="span2" value="" placeholder="Kode Validasi" />
									<img src="<?php echo  base_url(); ?>login/capcay" id="ratcha_image" alt="CAPTCHA">
									<div class="separator"></div>
								</div>							
							</div>
							<div class="form-actions">
								<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Lanjut ke Tahap Kedua</button>
								<a href='<?php echo base_url()?>login/logout' class='btn btn-icon btn-default glyphicons circle_remove'><i></i>Batal</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>