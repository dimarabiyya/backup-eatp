<h1><?php echo lang('text_heading_title_create_user') ?></h1>

<form method="post" action="" id="form">
	<div class="form">
		<div class="fields">

		<input type="hidden" value="add" id="proses">

			<div class="field">
					<div class="label">
							<label for="username">User ID:</label>
					</div>
					<div class="input">
							<input type="text" class="medium" value="" name="username" id="username">
					</div>
			</div>
			<div class="field">
					<div class="label">
							<label for="name">Name:</label>
					</div>
					<div class="input">
							<input type="text" class="medium" value="" name="name" id="name">
					</div>
			</div>
			<div class="field">
					<div class="label">
							<label for="nik">NIK:</label>
					</div>
					<div class="input">
							<input type="text" class="medium" value="" name="nik" id="nik">
					</div>
			</div>
			<div class="field">
					<div class="label"><label for="status">Status</label></div>
					<div class="input">
					<?php echo @form_dropdown('status', $user_status, set_value('status', 2), 'id="status"') ?>
					</div>
			</div>
			<div class="field">
					<div class="label">
							<label for="usergroup">User Group:</label>
					</div>
					<div class="input">
						<?php echo @form_dropdown('usergroup', $users_group, set_value('usergroup', ''), 'id="usergroup"') ?>
					</div>
			</div>
			
			<div id="divReporting">
				<div class="field">
					<div class="label">
					<label for="reportingto">Reporting To:</label>
					</div>
					<div class="input">
					<?php echo @form_dropdown('reportingto', $reporting_to, set_value('reportingto', 1), 'id="reportingto"') ?>
					</div>
					</div>
				</div>
				<div class="field">
						<div class="label">
								<label for="hape">Mobile Number:</label>
						</div>
						<div class="input">
								<input type="text" class="medium" value="" name="hape" id="hape">
						</div>
				</div>
				
				<div class="field">
						<div class="label">
								<label for="email">E-Mail:</label>
						</div>
						<div class="input">
								<input type="text" class="medium" value="" name="email" id="email">
						</div>
				</div>
				<div class="field">
						<!-- <div class="label">
								&nbsp;
						</div>
						<div class="input">
								<input type="checkbox" checked="checked" value="1" id="mcp" name="must_change_password">
								<label for="mcp"> Change password on the next logon</label>
						</div> -->
				</div>		
				
				<div class="buttons">
					<input type="button" class="d_save" value="Submit" name="submit" />
				</div>
		</div>
	</div>
</form>

<script><!--
document.getElementById('usergroup').onchange = function() {
	var usergroup        = $(this).val();
	var reportingto        = $('#reportingto').val();        
	
	$.post(
		'<?php echo base_url('users/create_user') ?>', 
		{cmd:'USER_REPORTING_TO',usergroup:usergroup, reportingto:reportingto},
		function(data) {
			if(data.status == 'OK') {
				//document.getElementById("divReporting").innerHTML = data.pesan;
				var html = '';
				for (i in data.users_to) {
					html += '<option value="' + data.users_to[i]['user_id'] + '">' + data.users_to[i]['user_name'] + '</option>';
				}
				
				document.getElementById('reportingto').innerHTML = html;
			}
		},
		"json"
	).error(function() {
		//alert("-");
	});
};

document.getElementsByClassName('d_save')[0].onclick = function() {
	var proses = $('#proses').val();

	var name = $('#name').val();
	var email = $('#email').val();
	var username = $('#username').val();
	var olduser = $('#olduser').val();
	var nik = $('#nik').val();
	var usergroup = $('#usergroup').val();
	var reportingto = $('#reportingto').val();
	var hape = $('#hape').val();
	var password = $('#password').val();
	var istatus = $('#status').val();
	var mcp = $('#mcp:checked').val();
	
	if (reportingto == '') 
		alert ('Anda harus memilih Reporting to');
	else {
		$.post(
			'<?php echo base_url('users/create_user') ?>', 
			{
				cmd:'SAVE_USER',
				proses:proses, name:name, email:email, username:username, nik:nik, 
				usergroup:usergroup, reportingto:reportingto, hape:hape, password:password,
				istatus:istatus, must_change_password:mcp, olduser:olduser
			},
			function(data) {
				if(data.status == 'ERROR') {
					alert(data.pesan);
				}else if(data.status == 'OK') {
					alert(data.pesan);
					window.location = "<?php echo base_url('users/user_management') ?>"
				}
			},
			"json"
		).error(function() {
			//alert("-");
		});
	}
};
//--></script>