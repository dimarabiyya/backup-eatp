<style>
select { width:auto; }
</style>

<div class="innerLR">
	
	<?php echo form_open('', 'id="frmPlnUsageImport" class="form-horizontal"') ?>
	
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading glyphicons inbox_in"><i></i> PLN Usage Import</h4>
			
			<div class="btn-heading-group">
				<a href="<?php echo site_url('/site_info/pln') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
			</div>
		</div>
		
		<div class="widget-body">
		
			<div class="control-group" id="form_period">
				<label class="control-label" for="month">Period:</label>
				<div class="controls">
					<?php
					$month = array(0 => '- Month -', 1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
					echo @form_dropdown('month',  $month,  set_value('month', $pln['bulan']), 'id="month"');
					
					$year = array(0 => '- Year -');
					for ($y = date('Y'); $y > (date('Y') - 10); $y--) {
						$year[$y] = $y;
					}
					echo @form_dropdown('year',  $year,  set_value('year', $pln['tahun']), 'id="year"');
					?>
				</div>
			</div>
			
			<div class="control-group" id="form_file">
				<label class="control-label">File: </label>
				<div class="controls">
					<div class="input-append">
						<div id="file-preview" class="uneditable-input span3"></div>
						<input type="hidden" name="filePLNUsage" />
						<button id="file-pln-usage" class="btn">Browse</button>
					</div>
				</div>
			</div>
		
		</div>
	</div>
	
	<div class="form-actions">
		<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Import</button>
		<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/site_info/pln') ?>'"><i></i>Cancel</button>
	</div>
	
	<?php echo form_close() ?>
	
	<div id="error-list"></div>

</div>

<script src="<?php echo base_url('/assets/js/ajaxupload.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>
<script>
var upload = new AjaxUpload('file-pln-usage', {
	action: '<?php echo site_url('/site_info/pln/import') ?>',
	name: 'fileData',
	autoSubmit: false,
	responseType: 'json',
	onChange: function(file, extension) {
		document.getElementById('file-preview').innerHTML = '<i class="icon-file"></i>' + file;
		document.getElementsByName('filePLNUsage')[0].value = file;
	},
	onSubmit: function(file, extension) {
		this.setData({ cmd: 'upload' });
		
		$.blockUI({ message: 'Uploading, please wait...<br /><img src="<?php echo base_url('/assets/images/indicator-bert.gif') ?>" />', css: {
			border: 'none',
			padding: '15px',
			backgroundColor: '#000',
			'-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
			opacity: .5,
			color: '#fff'
		} });
	},
	onComplete: function(file, json) {
		if (json.error) {
			coolNotification(json.message, 'error');
			
			$.unblockUI();
		} else {
			// Checking validation
			$.ajax({
				url: document.URL,
				type: 'POST',
				data: { cmd: 'check_validation', month: document.getElementById('month').value, year: document.getElementById('year').value, file: file },
				dataType: 'json',
				beforeSend: function() {
					document.getElementsByClassName('blockMsg')[0].innerHTML = 'Checking validation, please wait...<br /><img src="<?php echo base_url('/assets/images/indicator-bert.gif') ?>" />';
				},
				success: function(json) {
					if ( ! json.error) {
						// Start import data
						$.ajax({
							url: document.URL,
							type: 'POST',
							data: { cmd: 'import', month: document.getElementById('month').value, year: document.getElementById('year').value, file: file },
							dataType: 'json',
							beforeSend: function() {
								document.getElementsByClassName('blockMsg')[0].innerHTML = 'Importing, please wait...<br /><img src="<?php echo base_url('/assets/images/indicator-bert.gif') ?>" />';
							},
							success: function(json) {
								if ( ! json.error) {
									coolNotification(json.message, 'success', function() {
										window.location.href = '<?php echo site_url('/site_info/pln') ?>';
									});
								}
								
								$.unblockUI();
							}
						});
					} else {
						coolNotification(json.message, 'error');
						
						var table = '\
							<div class="widget">\
								<div class="widget-head">\
									<h4 class="heading glyphicons bomb"><i></i> Failed Import</h4>\
								</div>\
								\
								<div class="widget-body">\
								\
									<div class="alert alert-error">\
										<button type="button" class="close" data-dismiss="alert">&times;</button>\
										<img src="<?php echo base_url('/assets/images/warning.gif') ?>" />&nbsp;&nbsp;\
										<strong>WARNING!</strong> ' + json.message + '\
									</div>\
									\
									<div style="height:200px; overflow:auto;">\
										<table class="table table-bordered">\
											<tr>\
												<th width="80" class="center">Row</th>\
												<th width="80" class="center">Column</th>\
												<th class="center">Cause(s)</th>\
											</tr>';
								
						for (var i in json.causes) {
							table += '\
								<tr>\
									<td class="center">' + json.causes[i].row + '</td>\
									<td class="center">' + json.causes[i].column + '</td>\
									<td>' + json.causes[i].cause + '</td>\
								</tr>\
							';
						}
						
						table += '\
											<tr>\
												<td colspan="3">Total : ' + json.total_rows + ' row(s) | Error: ' + json.causes.length + '</td>\
											</tr>\
										</table>\
									</div>\
								</div>\
							</div>\
						';
						
						document.getElementById('error-list').innerHTML = table;
						
						$.unblockUI();
					}
				}
			});
		}
		
		document.getElementById('file-preview').innerHTML = '';
		document.getElementsByName('filePLNUsage')[0].value = '';
	}
});

document.getElementById('frmPlnUsageImport').onsubmit = function() {
	
	var error = [];
	if (document.getElementById('month').value == 0 || document.getElementById('year').value == 0) {
		error.push('Please select Period.');
	}
	if (document.getElementsByName('filePLNUsage')[0].value == '') {
		error.push('Please browse File.');
	}
	
	if (error.length > 0) {
		var errMessage = '<ul style="text-align:left">';
		for (var i in error) {
			errMessage += '<li>' + error[i] + '</li>';
		}
		errMessage += '</ul>';
		
		coolNotification(errMessage, 'error');
	} else {
		upload.submit();
	}
	
	return false;
};
</script>