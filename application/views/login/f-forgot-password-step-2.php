<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>
<script>
$(document).ready(function(){
	$('.FormForgotPassword').live("submit",function(e){
		$.ajax({
			type: "POST",
			url: "<?php echo  base_url()?>/login/cek_forgot_step_2",
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
				<li><a href=#>Tahap Pertama</a></li>
				<li class='active'><a href=#>Tahap Kedua</a></li>
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
									<strong>Security Question</strong>
									<p class="muted">Jawablah Pertanyaan Keamanan yang pernah Anda masukkan.</p>
								</div>
								<div class="span9">
									<label for="inputTitle" id="txt_user_id">User Id : <b><?php echo $user_id?></b></label>
									<label for="inputTitle" id="txt_q1">Pertanyaan Pertama: <b><?php echo $pertanyaan1?></b> </label>
									<input type="text" name="jawaban1" id="inputTitle" class="span6" value="" placeholder="Jawaban ..." />
									
									<label for="inputTitle" id="txt_q2">Pertanyaan Kedua: <b><?php echo $pertanyaan2?></b></label>
									<input type="text" name="jawaban2" id="inputTitle" class="span6" value="" placeholder="Jawaban ..." />
								</div>
							</div>
							<div class="form-actions">
								<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Lanjut ke Tahap Ketiga</button>
								<a href='<?php echo base_url()?>login/logout' class='btn btn-icon btn-default glyphicons circle_remove'><i></i>Batal</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>