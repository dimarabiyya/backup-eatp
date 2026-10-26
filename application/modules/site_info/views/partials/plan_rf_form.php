<?php echo form_open('', 'id="frmRF" class="form-horizontal"') ?>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> RF Antenna Data</h4>
	</div>
	
	<div class="widget-body">
	
		<div style="overflow:auto">
			<table id="tblRF" class="table table-bordered" style="width:auto">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Antenna:</th>
						<th>Height:</th>
						<th>Dimension (LxWxH):</th>
						<th>Weight:</th>						
					</tr>
				</thead>
				<tbody>
					<?php if ( $this->uri->segment(3) == 'edit' && count($rf) > 0 || $this->core->view_row('t_mtr_site_plan_rf', array('site_plan_id' => (int) $this->input->get('site_id'))) ) {
						
						$rfCounter = 1;								
						foreach ($rf as $er_ef) {
					?>
					
					<tr id="rf-row-<?php echo $rfCounter ?>">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="RF.removeRow(1)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							echo @form_hidden('id[]', $er_ef['site_plan_rf_id']);
							
							$lookup = $this->core->get_lookup('antenna_type');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('antennaType[]', $options, $er_ef['antenna'], 'class="input-medium"');
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('height[]', $er_ef['height'], 'class="number input-small"') ?>
								<span class="add-on">M</span>
							</div>
						</td>
						<td nowrap>
							<div class="input-append">
								<?php echo @form_input('dimensionL[]', $er_ef['dimension_l'], 'class="number input-small"') ?>
								<?php echo @form_input('dimensionW[]', $er_ef['dimension_w'], 'class="number input-small"') ?>
								<?php echo @form_input('dimensionH[]', $er_ef['dimension_h'], 'class="number input-small"') ?>
								<span class="add-on">CM</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('weight[]', $er_ef['weight'], 'class="number input-small"') ?>
								<span class="add-on">kg</span>
							</div>
						</td>
					</tr>
					<?php
							$rfCounter++;
						}
					} else {
					?>
					
					<tr id="rf-row-1">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="RF.removeRow(1)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							echo @form_hidden('id[]');
							
							$lookup = $this->core->get_lookup('antenna_type');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('antennaType[]', $options, '', 'class="input-medium"');
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('height[]', '', 'class="number input-small"') ?>
								<span class="add-on">M</span>
							</div>
						</td>
						<td nowrap>
							<div class="input-append">
								<?php echo @form_input('dimensionL[]', '', 'class="number input-small"') ?>
								<?php echo @form_input('dimensionW[]', '', 'class="number input-small"') ?>
								<?php echo @form_input('dimensionH[]', '', 'class="number input-small"') ?>
								<span class="add-on">CM</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('weight[]', '', 'id="weight" class="number input-small"') ?>
								<span class="add-on">kg</span>
							</div>
						</td>
					</tr>
					<?php
					}
					?>
					
				</tbody>
				<tfoot>
					<tr>
						<td colspan="12" class="left"><a class="btn btn-success btn-small" href="javascript:void(0)" onclick="RF.addRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></a></td>
					</tr>
				</tfoot>
			</table>
		</div>
	
	</div>
</div>

<div class="form-actions">
	<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
	<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/site_info/plan_info') ?>'"><i></i>Cancel</button>
</div>

<?php echo form_close() ?>

<script>
document.getElementById('frmRF').onsubmit = function() {

	var data = $(this).serialize();
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=ADD_RF';
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	data += '&cmd=EDIT_RF';
	<?php } ?>
	data += '&siteId=' + document.getElementsByName('siteId')[0].value;
	
	$.ajax({
		url: document.URL,
		type: 'POST',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			$.blockUI({ message: 'Loading, please wait...', css: {
				border: 'none',
				padding: '15px',
				backgroundColor: '#000',
				'-webkit-border-radius': '10px',
				'-moz-border-radius': '10px',
				opacity: .5,
				color: '#fff'
			} });
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
				
				coolNotification(message, 'error');
			} else {
				coolNotification(json['message'], 'success', function() {
					// refresh content tab
					var tab = $('#planTab').tabs('getSelected');
					$('#planTab').tabs('update', {
						tab: tab,
						options: {
							href: document.URL + '?load_content=RF&site_id=' + json.site_id
						}
					});
					
					window.location.href = planURL;
				});
			}
			
			window.scroll(0, 0);
			
			$.unblockUI();
		},
		complete: function() {}
	});
	
	return false;
};

<?php if ( $this->uri->segment(3) == 'edit' && count($rf) > 0 || $this->core->view_row('t_mtr_site_plan_rf', array('site_plan_id' => (int) $this->input->get('site_id'))) ) { ?>
var rfCounter = <?php echo $rfCounter ?>;
<?php } else { ?>
var rfCounter = 2;
<?php } ?>

var RF = {
	addRow: function() {
		var html = '\
			<tr id="rf-row-' + rfCounter + '">\
				<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="RF.removeRow(' + rfCounter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>\
				<td>\
					<input type="hidden" name="id[]" />\
					\
					<select name="antennaType[]" class="input-medium">\
						<option value="">- Choose one -</option>\
						<?php
						$lookup = $this->core->get_lookup('antenna_type');
						foreach ($lookup as $lo) {
							echo '<option value="'.$lo['id'].'">'.$lo['desc'].'</option>';
						}
						?>\
					</select>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="height[]" class="number input-small" />\
						<span class="add-on">M</span>\
					</div>\
				</td>\
				<td nowrap>\
					<div class="input-append">\
						<input type="text" name="dimensionL[]" class="number input-small" />\
						<input type="text" name="dimensionW[]" class="number input-small" />\
						<input type="text" name="dimensionH[]" class="number input-small" />\
						<span class="add-on">CM</span>\
					</div>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="weight[]" class="number input-small" />\
						<span class="add-on">kg</span>\
					</div>\
				</td>\
			</tr>\
		';
		
		$('#tblRF tbody').append(html);
		
		numberFormat.init();
		justNumberFormat.init();
		TEXT.clearAutoComplete();
		
		rfCounter++;
	},
	removeRow: function(row) {
		var row = document.getElementById('rf-row-' + row);
		coolConfirmation('Are you sure you want to delete?', function() {
			row.parentNode.removeChild(row);
		});
	}
};

numberFormat.init();
justNumberFormat.init();
TEXT.clearAutoComplete();
doPreviledgePerfield();
</script>