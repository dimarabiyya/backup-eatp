<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<div class="innerLR">
	
	<?php echo form_open('', 'id="frmSeasonName" class="form-horizontal"') ?>
	
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Season Name</h4>
			
			<a href="<?php echo site_url('/site_info/season/season_name') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
		</div>
		
		<div class="widget-body">
		
			<div class="control-group" id="season_name">
				<label class="control-label" for="seasonName" style="text-align:left">Season Name:</label>
				<div class="controls">
					<?php echo form_input('seasonName',  ucwords(str_replace('_', ' ', $season_name['column_name'])), 'id="seasonName"') ?>
				</div>
			</div>
			
		</div>
	</div>
	
	<div class="form-actions">
		<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
		<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/site_info/season/season_name') ?>'"><i></i>Cancel</button>
	</div>
	
	<?php echo form_close() ?>

</div>

<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>

<script>
document.getElementById('frmSeasonName').onsubmit = function() {

	var data = $(this).serialize();
	<?php if ($this->uri->segment(3) == 'add_season_name') { ?>
	data += '&cmd=add_season_name';
	<?php } elseif ($this->uri->segment(3) == 'edit_season_name') { ?>
	data += '&cmd=edit_season_name';
	<?php } ?>
	
	$.ajax({
		url: document.URL,
		type: 'POST',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			siteOverlay.top = '200px';
			siteOverlay.create();
		},
		success: function(json) {
			if (typeof json.loggedin != 'undefined' && !json.loggedin) {
				alert(json.message);
				window.location.href = json.redirect_url;
			}
			
			if (typeof json['error'] != 'undefined' && json['error']) {
				var message = '\
					<ul style="text-align:left">';
					for (var i = 0; i < json['message'].length; i++) {
						message += '<li>' + json['message'][i] + '</li>';
					}
					message += '</ul>';
				
				coolNotification(message, 'error', function() {
					document.getElementById('seasonName').select();
				});
			} else {
				coolNotification(json['message'], 'success', function() {
					window.location.href = '<?php echo site_url('/site_info/season/season_name') ?>';
				});
			}
			
			window.scroll(0, 0);
		},
		complete: function() {
			siteOverlay.remove();
		}
	});
	
	return false;
};

document.getElementById('seasonName').select();
</script>