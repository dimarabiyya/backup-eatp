<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> DCPDB Detail</h4>
	</div>
	
	<div class="widget-body">
	
		<?php echo form_open('', 'id="frmDCPDBDetail" class="form-horizontal"') ?>
		
		<?php echo @form_hidden('DCPDBDetailId', set_value('DCPDBDetailId', $dcpdb_id)) ?>
		
		<div style="overflow:auto">
			<table id="tbl-detail-dcpdb" class="table table-bordered" style="width:auto">
				<thead>
					<tr>
						<th class="center">&nbsp;</th>
						<th class="center">Item:</th>
						<th class="center">Capacity:</th>
						<th class="center">Actual Capacity:</th>
						<th class="center">Allocation:</th>
						<th class="center">FE ID:</th>
						<th class="center">System:</th>
						<th class="center">Linked to Rectifier No.</th>
					</tr>
				</thead>
				<tbody>
					<?php if (count($dcpdb_detail) > 0) {
						
						$dcpdb_count = 1;
						foreach ($dcpdb_detail as $detail) {
					?>
					<tr id="row-dcpdb-<?php echo $dcpdb_count ?>">
						<td><a class="btn btn-small btn-danger" href="javascript:void(0)" onclick="DCPDB_DETAIL.removeRow(<?php echo $dcpdb_count ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							$items = array(0 => '- Choose one -');
							$lookup = $this->core->get_lookup('power_mcb_item');
							foreach ($lookup as $lo) {
								$items[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('item['.$dcpdb_count.']',  $items, set_value('item['.$dcpdb_count.']', $detail['item']), 'class="input-medium"');
							?>
						</td>
						<td>
							<div class="input-append">
								<?php
								$capacities = array(0 => '- Choose one -');
								$lookup = $this->core->get_lookup('power_capacity');
								foreach ($lookup as $lo) {
									$capacities[$lo['id']] = $lo['desc'];
								}
								echo @form_dropdown('capacity['.$dcpdb_count.']', $capacities, set_value('capacity['.$dcpdb_count.']', $detail['capacity']), 'class="input-medium"');
								?>
								<span class="add-on">Amp(DC)</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('actualCapacity['.$dcpdb_count.']', set_value('actualCapacity['.$dcpdb_count.']', $detail['actual_capacity']), 'class="input-small number"') ?>
								<span class="add-on">Amp(DC)</span>
							</div>
						</td>
						<td>
							<?php
								$allocation = array(0 => '- Choose one -');
								$data_allocation = $this->core->get_lookup('power_allocation');
								foreach ($data_allocation as $allo) {
									$allocation[$allo['id']] = $allo['desc'];
								}
								echo @form_dropdown('allocation['.$dcpdb_count.']', $allocation, set_value('allocation['.$dcpdb_count.']', $detail['allocation']), 'class="input-medium"');
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('feCode['.$dcpdb_count.']', set_value('feCode['.$dcpdb_count.']', $detail['site_code']), 'class="input-medium uneditable" onfocus="doLookupSiteDetailDCPDB(\'feCode['.$dcpdb_count.']\', \'feId['.$dcpdb_count.']\', '.$dcpdb_count.')"') ?>
								<?php echo @form_hidden('feId['.$dcpdb_count.']', set_value('feId['.$dcpdb_count.']', $detail['fe_id'])) ?>
								<button type="button" class="btn" onclick="doLookupSiteDetailDCPDB('feCode[<?php echo $dcpdb_count ?>]', 'feId[<?php echo $dcpdb_count ?>]', <?php echo $dcpdb_count ?>)"><i class="icon-search"></i></button>
							</div>
						</td>
						<td>
							<?php
								$system = array(0 => '- Choose one -');
								$data_system = $this->core->get_lookup('power_system');
								foreach ($data_system as $sys) {
									$system[$sys['id']] = $sys['desc'];
								}
								echo @form_dropdown('system['.$dcpdb_count.']', $system, set_value('system['.$dcpdb_count.']', $detail['system']), 'class="input-medium"');
							?>
						</td>
						<td>
							<?php
							$lookup = $this->core->get_lookup('rectifier_number');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo form_dropdown('linkedToRectifierNo['.$dcpdb_count.']', $options, $detail['linked_to_rectifier_no']);
							?>
						</td>
					</tr>
					
					<?php 
							$dcpdb_count++;
						}					
					} else {
					?>
					
					<tr id="row-dcpdb-1">
						<td><a class="btn btn-small btn-danger" href="javascript:void(0)" onclick="DCPDB_DETAIL.removeRow(1)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							$items = array(0 => '- Choose one -');
							$lookup = $this->core->get_lookup('power_mcb_item');
							foreach ($lookup as $lo) {
								$items[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('item[1]',  $items, set_value('item[1]', ''), 'class="input-medium"');
							?>
						</td>
						<td>
							<div class="input-append">
								<?php
								$capacities = array(0 => '- Choose one -');
								$lookup = $this->core->get_lookup('power_capacity');
								foreach ($lookup as $lo) {
									$capacities[$lo['id']] = $lo['desc'];
								}
								echo @form_dropdown('capacity[1]', $capacities, '', 'class="input-medium"');
								?>
								<span class="add-on">Amp</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('actualCapacity[1]', set_value('actualCapacity[1]', ''), 'class="input-small number"') ?>
								<span class="add-on">Amp(DC)</span>
							</div>
						</td>
						<td>
							<?php
								$allocation = array(0 => '- Choose one -');
								$data_allocation = $this->core->get_lookup('power_allocation');
								foreach ($data_allocation as $allo) {
									$allocation[$allo['id']] = $allo['desc'];
								}
								echo @form_dropdown('allocation[1]', $allocation, set_value('allocation[1]', ''), 'class="input-medium"');
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('feCode[1]', '', 'class="input-medium uneditable" onfocus="doLookupSiteDetailDCPDB(\'feCode[1]\', \'feId[1]\', 1)"') ?>
								<?php echo @form_hidden('feId[1]') ?>
								<button type="button" class="btn" onclick="doLookupSiteDetailDCPDB('feCode[1]', 'feId[1]', 1)"><i class="icon-search"></i></button>
							</div>
						</td>
						<td>
							<?php
								$system = array(0 => '- Choose one -');
								$data_system = $this->core->get_lookup('power_system');
								foreach ($data_system as $sys) {
									$system[$sys['id']] = $sys['desc'];
								}
								echo @form_dropdown('system[1]', $system, set_value('system[1]', ''), 'class="input-medium"');
							?>
						</td>
						<td>
							<?php
							$lookup = $this->core->get_lookup('rectifier_number');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo form_dropdown('linkedToRectifierNo[1]', $options);
							?>
						</td>
					</tr>
					<?php } ?>
					
				</tbody>
				<tfoot>
					<tr>
						<td colspan="8" class="left"><a class="btn btn-small btn-success" href="javascript:void(0)" onclick="DCPDB_DETAIL.addRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></a></td>
					</tr>
				</tfoot>
			</table>
		</div>
		
		<div class="form-actions">
			<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
			<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$('#btn-dcpdb-addnew').show(); $('#dcpdb-detail-box').hide(); window.scroll(0, 0);"><i></i>Cancel</button>
		</div>
		
		<?php echo form_close() ?>
		
	</div>
</div>

<script>
document.getElementById('frmDCPDBDetail').onsubmit = function() {
	var data = serialize(this);
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=ADD_DCPDB_DETAIL';
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	data += '&cmd=EDIT_DCPDB_DETAIL';
	<?php } ?>
	data += '&detail_id=' + document.getElementsByName('DCPDBDetailId')[0].value;
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
				
				GRID_DCPDB.resetForm();
				GRID_DCPDB.params.cmd = 'GET_DATAGRID_DCPDB';
				GRID_DCPDB.init();
				
				$('#dcpdb-detail-box').hide();
				$('#btn-dcpdb-addnew').show();
			}
			
			window.scroll(0, 0);
		},
		complete: function() {
			siteOverlay.remove();
		}
	});
	
	return false;
};

<?php if (count($dcpdb_detail) > 0) { ?>
var dcpdbCounter = <?php echo $dcpdb_count ?>;
<?php } else { ?>
var dcpdbCounter = 2;
<?php } ?>

var DCPDB_DETAIL = {
	addRow: function() {
		var html = '\
			<tr id="row-dcpdb-' + dcpdbCounter + '">\
				<td><a class="btn btn-small btn-danger" href="javascript:void(0)" onclick="DCPDB_DETAIL.removeRow(' + dcpdbCounter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>\
				<td>\
					<select name="item[' + dcpdbCounter + ']" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$lookup = $this->core->get_lookup('power_mcb_item');
						foreach ($lookup as $lo) { ?>\
							<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
							<?php
						} ?>\
					</select>\
				</td>\
				<td>\
					<div class="input-append">\
						<select name="capacity[' + dcpdbCounter + ']" class="input-medium">\
							<option value="0">- Choose one -</option>\
							<?php
							$lookup = $this->core->get_lookup('power_capacity');
							foreach ($lookup as $lo) { ?>\
								<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
								<?php
							} ?>\
						</select>\
						<span class="add-on">Amp(DC)</span>\
					</div>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="actualCapacity[' + dcpdbCounter + ']" class="input-small number" />\
						<span class="add-on">Amp(DC)</span>\
					</div>\
				</td>\
				<td>\
					<select name="allocation[' + dcpdbCounter + ']" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$data_allocation = $this->core->get_lookup('power_allocation');
						foreach ($data_allocation as $allo) { ?>\
							<option value="<?php echo $allo['id'] ?>"><?php echo $allo['desc'] ?></option>\
							<?php
						} ?>\
					</select>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="feCode[' + dcpdbCounter + ']" class="input-medium uneditable" onfocus="doLookupSiteDetailDCPDB(\'feCode[' + dcpdbCounter + ']\', \'feId[' + dcpdbCounter + ']\', ' + dcpdbCounter + ')" />\
						<input type="hidden" name="feId[' + dcpdbCounter + ']" />\
						<button type="button" class="btn" onclick="doLookupSiteDetailDCPDB(\'feCode[' + dcpdbCounter + ']\', \'feId[' + dcpdbCounter + ']\', ' + dcpdbCounter + ')"><i class="icon-search"></i></button>\
					</div>\
				</td>\
				<td>\
					<select name="system[' + dcpdbCounter + ']" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$data_system = $this->core->get_lookup('power_system');
						foreach ($data_system as $sys) { ?>\
							<option value="<?php echo $sys['id'] ?>"><?php echo $sys['desc'] ?></option>\
							<?php
						} ?>\
					</select>\
				</td>\
				<td>\
					<select name="linkedToRectifierNo[' + dcpdbCounter + ']">\
						<option value="0">- Choose one -</option>\
						<?php
						$lookup = $this->core->get_lookup('rectifier_number');
						foreach ($lookup as $lo) { ?>\
							<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
							<?php
						} ?>\
					</select>\
				</td>\
			</tr>\
		';
		
		$('#tbl-detail-dcpdb tbody').append(html);
		
		numberFormat.init();
		clearAutoComplete.init();
		uneditable.init();
		
		dcpdbCounter++;
	},
	removeRow: function(row) {
		var row = document.getElementById('row-dcpdb-' + row);
		
		coolConfirmation('Are you sure you wan to delete?', function() {			
			row.parentNode.removeChild(row);
		});
	}
};

numberFormat.init();
clearAutoComplete.init();
uneditable.init();
</script>