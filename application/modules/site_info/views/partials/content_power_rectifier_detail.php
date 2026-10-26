<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> Rectifier Detail</h4>
	</div>
	
	<div class="widget-body">
		
		<?php echo form_open('', 'id="frmRectifierDetail" class="form-horizontal"') ?>
		
		<?php echo @form_hidden('rectifierDetailId', set_value('rectifierDetailId', $rectifier_id)) ?>
		
		<div style="overflow:auto">
			<table id="tbl-detail-rectifier" class="table table-bordered" style="width:auto">
				<thead>
					<tr>
						<th>Item:</th>
						<th>Capacity:</th>
						<th>Actual Capacity:</th>
						<th>LLVD Info:</th>
						<th>Allocation:</th>
						<th>FE ID:</th>
						<th>System:</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php if (count($rectifier_detail) > 0) {
						
						$rec_count = 1;
						foreach ($rectifier_detail as $detail) {
					?>
					
					<tr id="row-rectifier-<?php echo $rec_count ?>">
						<td>
							<?php
							$items = array(0 => '- Choose one -');
							$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_mcb_item', 'active' => 1), 'order');
							foreach ($lookup as $lo) {
								if (filter_var($lo['lookup_value'], FILTER_VALIDATE_INT)) {
									$items[$lo['lookup_value']] = $lo['lookup_desc'];
								}
							}
							echo @form_dropdown('item['.$rec_count.']',  $items, set_value('item['.$rec_count.']', $detail['item']), 'class="input-medium"');
							?>
						</td>
						<td>
							<div class="input-append">
								<?php
								$capacity = array(0 => '- Choose one -');
								$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_capacity', 'active' => 1), 'order');
								foreach ($lookup as $lo) {
									if (filter_var($lo['lookup_value'], FILTER_VALIDATE_INT)) {
										$capacity[$lo['lookup_value']] = $lo['lookup_desc'];
									}
								}
								echo @form_dropdown('capacity['.$rec_count.']', $capacity, set_value('capacity['.$rec_count.']', $detail['capacity']), 'class="input-medium"');
								?>
								<span class="add-on">Amp(DC)</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('capacityActual['.$rec_count.']', set_value('capacityActual['.$rec_count.']', $detail['capacity_actual']), 'class="input-small number"') ?>
								<span class="add-on">Amp(DC)</span>
							</div>
						</td>
						<td>
							<?php
								$llvdInfo = array(0 => '- Choose one -');
								$data_llvdInfo = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'llvd_info', 'active' => 1), 'order');
								foreach ($data_llvdInfo as $inf) {
									if (filter_var($inf['lookup_value'], FILTER_VALIDATE_INT)) {
										$llvdInfo[$inf['lookup_value']] = $inf['lookup_desc'];
									}
								}
								echo @form_dropdown('llvdInfo['.$rec_count.']', $llvdInfo, set_value('llvdInfo['.$rec_count.']', $detail['llvd_info']), 'class="input-medium"');
							?>
						</td>
						<td>
							<?php
								$allocation = array(0 => '- Choose one -');
								$data_allocation = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_allocation', 'active' => 1), 'order');
								foreach ($data_allocation as $allo) {
									if (filter_var($allo['lookup_value'], FILTER_VALIDATE_INT)) {
										$allocation[$allo['lookup_value']] = $allo['lookup_desc'];
									}
								}
								echo @form_dropdown('allocation['.$rec_count.']', $allocation, set_value('allocation['.$rec_count.']', $detail['allocation']), 'class="input-medium"');
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('farEndCode['.$rec_count.']', set_value('farEndCode['.$rec_count.']', $detail['site_code']), 'class="input-medium uneditable"') ?>
								<?php echo @form_hidden('farEnd['.$rec_count.']', set_value('farEnd['.$rec_count.']', $detail['fe_id'])) ?>
								<button type="button" class="btn" onclick="doLookupSiteDetailRectifier('farEndCode[<?php echo $rec_count ?>]', 'farEnd[<?php echo $rec_count ?>]', <?php echo $rec_count ?>)"><i class="icon-search"></i></button>
							</div>
						</td>
						<td>
							<?php
								$system = array(0 => '- Choose one -');
								$data_system = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_system', 'active' => 1), 'order');
								foreach ($data_system as $sys) {
									if (filter_var($sys['lookup_value'], FILTER_VALIDATE_INT)) {
										$system[$sys['lookup_value']] = $sys['lookup_desc'];
									}
								}
								echo @form_dropdown('system['.$rec_count.']', $system, set_value('system['.$rec_count.']', $detail['system']), 'class="input-medium"');
							?>
						</td>
						<td class="right"><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="RECTIFIER_DETAIL.removeRow(<?php echo $rec_count ?>)"><i></i> Remove</a></td>
					</tr>
					
					<?php 
							$rec_count++;
						}
					} else { ?>
					
					<tr id="row-rectifier-1">
						<td>
							<?php
							$items = array(0 => '- Choose one -');
							$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_mcb_item', 'active' => 1), 'order');
							foreach ($lookup as $lo) {
								if (filter_var($lo['lookup_value'], FILTER_VALIDATE_INT)) {
									$items[$lo['lookup_value']] = $lo['lookup_desc'];
								}
							}
							echo @form_dropdown('item[1]',  $items, set_value('item[1]', ''), 'class="input-medium"');
							?>
						</td>
						<td>
							<div class="input-append">
								<?php
								$capacity = array(0 => '- Choose one -');
								$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_capacity', 'active' => 1), 'order');
								foreach ($lookup as $lo) {
									if (filter_var($lo['lookup_value'], FILTER_VALIDATE_INT)) {
										$capacity[$lo['lookup_value']] = $lo['lookup_desc'];
									}
								}
								echo @form_dropdown('capacity[1]', $capacity, set_value('capacity[1]', ''), 'class="input-medium"');
								?>
								<span class="add-on">Amp(DC)</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('capacityActual[1]', set_value('capacityActual[1]', ''), 'class="input-small number"') ?>
								<span class="add-on">Amp(DC)</span>
							</div>
						</td>
						<td>
							<?php
								$llvdInfo = array(0 => '- Choose one -');
								$data_llvdInfo = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'llvd_info', 'active' => 1), 'order');
								foreach ($data_llvdInfo as $inf) {
									if (filter_var($inf['lookup_value'], FILTER_VALIDATE_INT)) {
										$llvdInfo[$inf['lookup_value']] = $inf['lookup_desc'];
									}
								}
								echo @form_dropdown('llvdInfo[1]', $llvdInfo, set_value('llvdInfo[1]', ''), 'class="input-medium"');
							?>
						</td>
						<td>
							<?php
								$allocation = array(0 => '- Choose one -');
								$data_allocation = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_allocation', 'active' => 1), 'order');
								foreach ($data_allocation as $allo) {
									if (filter_var($allo['lookup_value'], FILTER_VALIDATE_INT)) {
										$allocation[$allo['lookup_value']] = $allo['lookup_desc'];
									}
								}
								echo @form_dropdown('allocation[1]', $allocation, set_value('allocation[1]', ''), 'class="input-medium"');
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('farEndCode[1]', set_value('farEndCode[1]', ''), 'class="input-medium uneditable"') ?>
								<?php echo @form_hidden('farEnd[1]', set_value('farEnd[1]', '')) ?>
								<button type="button" class="btn" onclick="doLookupSiteDetailRectifier('farEndCode[1]', 'farEnd[1]', 1)"><i class="icon-search"></i></button>
							</div>
						</td>
						<td>
							<?php
								$system = array(0 => '- Choose one -');
								$data_system = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_system', 'active' => 1), 'order');
								foreach ($data_system as $sys) {
									if (filter_var($sys['lookup_value'], FILTER_VALIDATE_INT)) {
										$system[$sys['lookup_value']] = $sys['lookup_desc'];
									}
								}
								echo @form_dropdown('system[1]', $system, set_value('system[1]', ''), 'class="input-medium"');
							?>
						</td>
						<td class="right"><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="RECTIFIER_DETAIL.removeRow(1)"><i></i> Remove</a></td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="8" class="right"><a class="btn btn-icon glyphicons circle_plus" href="javascript:void(0)" onclick="RECTIFIER_DETAIL.addRow()"><i></i> Add</a></td>
					</tr>
				</tfoot>
			</table>
		</div>
		
		<div class="form-actions">
			<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
			<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$('#btn-rectifier-addnew').show(); $('#rectifier-detail-box').hide(); window.scroll(0, 0);"><i></i>Cancel</button>
		</div>
		
		<?php echo form_close() ?>
		
	</div>
</div>

<script>
document.getElementById('frmRectifierDetail').onsubmit = function() {
	var data = serialize(this);
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=ADD_RECTIFIER_DETAIL';
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	data += '&cmd=EDIT_RECTIFIER_DETAIL';
	<?php } ?>
	data += '&detail_id=' + document.getElementsByName('rectifierDetailId')[0].value;
	data += '&act=SAVE';
	
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
			
			var alertMessage = document.getElementsByClassName('alert');
			for (var i = 0; i < alertMessage.length; i++) {
				alertMessage[i].parentNode.removeChild(alertMessage[i]);
			}
			
			/* var siteId = document.getElementsByName('siteId');
			for (var i = 0; i < siteId.length; i++) {
				siteId[i].value = json['site_id'];
			} */
			
			if (typeof json['error'] != 'undefined' && json['error']) {
				var message = '\
					<ul style="text-align:left">';
					for (var i = 0; i < json['message'].length; i++) {
						message += '<li>' + json['message'][i] + '</li>';
					}
					message += '</ul>';
				
				coolNotification(message, 'error');
			} else {
				coolNotification(json['message'], 'success');
				
				GRID_RECTIFIER.resetForm();
				GRID_RECTIFIER.params.cmd = 'GET_DATAGRID_RECTIFIER';
				GRID_RECTIFIER.init();
				
				$('#rectifier-detail-box').hide();
				$('#btn-rectifier-addnew').show();
			}
			
			window.scroll(0, 0);
		},
		complete: function() {
			siteOverlay.remove();
		}
	});
	
	return false;
};

<?php if (count($rectifier_detail) > 0) { ?>
var rectifierCounter = <?php echo $rec_count ?>;
<?php } else { ?>
var rectifierCounter = 2;
<?php } ?>

var RECTIFIER_DETAIL = {
	addRow: function() {
		var html = '\
			<tr id="row-rectifier-' + rectifierCounter + '">\
				<td>\
					<select name="item[' + rectifierCounter + ']" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_mcb_item', 'active' => 1), 'order');
						foreach ($lookup as $lo) {
							if (filter_var($lo['lookup_value'], FILTER_VALIDATE_INT)) {
								?>\
								<option value="<?php echo $lo['lookup_value'] ?>"><?php echo $lo['lookup_desc'] ?></option>\
								<?php
							}
						} ?>\
					</select>\
				</td>\
				<td>\
					<div class="input-append">\
						<select name="capacity[' + rectifierCounter + ']" class="input-medium">\
							<option value="0">- Choose one -</option>\
							<?php
							$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_capacity', 'active' => 1), 'order');
							foreach ($lookup as $lo) {
								if (filter_var($lo['lookup_value'], FILTER_VALIDATE_INT)) {
									?>\
									<option value="<?php echo $lo['lookup_value'] ?>"><?php echo $lo['lookup_desc'] ?></option>\
									<?php
								}
							} ?>\
						</select>\
						<span class="add-on">Amp(DC)</span>\
					</div>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="capacityActual[' + rectifierCounter + ']" class="input-small number" />\
						<span class="add-on">Amp(DC)</span>\
					</div>\
				</td>\
				<td>\
					<select name="llvdInfo[' + rectifierCounter + ']" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$data_llvdInfo = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'llvd_info', 'active' => 1), 'order');
						foreach ($data_llvdInfo as $inf) {
							if (filter_var($inf['lookup_value'], FILTER_VALIDATE_INT)) {
								?>\
								<option value="<?php echo $inf['lookup_value'] ?>"><?php echo $inf['lookup_desc'] ?></option>\
								<?php
							}
						} ?>\
					</select>\
				</td>\
				<td>\
					<select name="allocation[' + rectifierCounter + ']" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$data_allocation = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_allocation', 'active' => 1), 'order');
						foreach ($data_allocation as $allo) {
							if (filter_var($allo['lookup_value'], FILTER_VALIDATE_INT)) {
								?>\
								<option value="<?php echo $allo['lookup_value'] ?>"><?php echo $allo['lookup_desc'] ?></option>\
								<?php
							}
						} ?>\
					</select>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="farEndCode[' + rectifierCounter + ']" class="input-medium uneditable" />\
						<input type="hidden" name="farEnd[' + rectifierCounter + ']" />\
						<button type="button" class="btn" onclick="doLookupSiteDetailRectifier(\'farEndCode[' + rectifierCounter + ']\', \'farEnd[' + rectifierCounter + ']\', ' + rectifierCounter + ')"><i class="icon-search"></i></button>\
					</div>\
				</td>\
				<td>\
					<select name="system[' + rectifierCounter + ']" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$data_system = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_system', 'active' => 1), 'order');
						foreach ($data_system as $sys) {
							if (filter_var($sys['lookup_value'], FILTER_VALIDATE_INT)) {
								?>\
								<option value="<?php echo $sys['lookup_value'] ?>"><?php echo $sys['lookup_desc'] ?></option>\
								<?php
							}
						} ?>\
					</select>\
				</td>\
				<td class="right"><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="RECTIFIER_DETAIL.removeRow(' + rectifierCounter + ')"><i></i> Remove</a></td>\
			</tr>\
		';
		
		$('#tbl-detail-rectifier tbody').append(html);
		
		numberFormat.init();
		clearAutoComplete.init();
		uneditable.init();
		
		rectifierCounter++;
	},
	removeRow: function(row) {
		var row = document.getElementById('row-rectifier-' + row);
		
		coolConfirmation('Are you sure you want to delete?', function() {			
			row.parentNode.removeChild(row);
		});
	}
};

numberFormat.init();
clearAutoComplete.init();
uneditable.init();
</script>