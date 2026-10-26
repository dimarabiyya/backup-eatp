<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>
<script>
$(document).ready(function(){
	$('.FormForgotPassword').live("submit",function(e){
		$.ajax({
			type: "POST",
			url: "<?php echo  base_url()?>validate_security_code/cek_allow_step_2",
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
									<strong>Security Code</strong>
									<p>Masukkan Kode Keamanan yang sudah dikirimkan ke email anda</p>
								</div>
								<div class="span9">
									<label for="inputTitle" id="txt_user_id">User Id : <b><?php echo $user_id?></b></label>
									<label for="inputTitle" id="txt_q1">Kode Keamanan:  </label>
									<input type="text" name="sc" id="inputTitle" class="span6" value="" placeholder="" />

								<!--	<label for="inputTitle" id="txt_q2">Pertanyaan Kedua: <b><?php echo $pertanyaan2?></b></label>
									<input type="text" name="jawaban2" id="inputTitle" class="span6" value="" placeholder="Jawaban ..." />
								-->
                                </div>
							</div>
							<div class="form-actions">
								<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Submit</button>
								<a href='<?php echo base_url()?>login/logout' class='btn btn-icon btn-default glyphicons circle_remove'><i></i>Batal</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
	$(function(){
		alert("Masukkan Kode Keamanan yang sudah dikirimkan ke email anda !");
	});
	</script>
