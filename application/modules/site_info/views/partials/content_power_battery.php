<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<?php echo form_open('', 'id="frmBattery" class="form-horizontal"') ?>

<?php if ($this->uri->segment(3) == 'edit') { ?>
<div class="row-fluid">
	<div class="span12">
		<div class="widget">				
			<div class="widget-body list">
				<ul>
					<li><?php echo $last_updated ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> Battery Data</h4>
	</div>
	
	<div class="widget-body">

		<div style="overflow:auto">
			<table id="tbl-battery" class="table table-bordered" style="width:auto">
				<thead>
					<tr>
						<th class="center">&nbsp;</th>
						<th class="center">Item(s)</th>
						<th class="center">Brand / Model</th>
						<th class="center">Capacity per Cell</th>
						<th class="center">Total Bank</th>
						<th class="center">Total Shunt</th>
						<th class="center">Weight per Cell</th>
						<th class="center">Voltage per Cell</th>
						<th class="center">Linked to Rectifier No</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (count($battery) > 0) {
						$battery_counter = 1;
						foreach ($battery as $batt) {
					?>
					<tr id="battery-row-<?php echo $battery_counter ?>">
						<td><a class="btn btn-small btn-danger" href="javascript:void(0)" onclick="BATTERY.removeRow(<?php echo $battery_counter ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							echo @form_hidden('batteryId['.$battery_counter.']', set_value('batteryId['.$battery_counter.']', $batt['battery_id']));
							
							$items = array(0 => '- Choose one -');
							$lookup = $this->core->get_lookup('power_mcb_item');
							foreach ($lookup as $lo) {
								$items[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('mcbItem['.$battery_counter.']',  $items, $batt['mcb_item']);
							?>
						</td>
						<td>
							<?php
							$batteryBrand = array(0 => '- Choose one -');
							$dataBatteryBrand = get_instance()->core->view_all('t_mtr_lookup', array('lookup_key' => 'battery_brand', 'active' => 1), 'lookup_desc');
							foreach ($dataBatteryBrand as $brand) {
								$batteryBrand[$brand['lookup_value']] = $brand['lookup_desc'];
							}
							echo @form_dropdown('batteryBrand['.$battery_counter.']', $batteryBrand, set_value('batteryBrand['.$battery_counter.']', $batt['brand']));
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('batteryCapacity['.$battery_counter.']', set_value('batteryCapacity['.$battery_counter.']', $batt['capacity']), 'class="input-mini number"') ?>
								<span class="add-on">Ah</span>
							</div>
						</td>
						<td>
							<?php echo @form_input('batteryTotalBank['.$battery_counter.']', set_value('batteryTotalBank['.$battery_counter.']', $batt['total_bank']), 'class="input-mini just-number"') ?>
						</td>
						<td>
							<?php echo @form_input('batteryTotalShunt['.$battery_counter.']', set_value('batteryTotalShunt['.$battery_counter.']', $batt['total_shunt']), 'class="input-mini just-number"') ?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('weightPerCell['.$battery_counter.']', set_value('weightPerCell['.$battery_counter.']', $batt['weight_per_cell']), 'class="input-mini number"') ?>
								<span class="add-on">kg</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php
								$voltagePerCell = array(0 => '- Choose one -');
								$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'voltage_per_cell', 'active' => 1), 'order');
								foreach ($lookup as $lo) {
									$voltagePerCell[$lo['lookup_value']] = $lo['lookup_desc'];
								}
								echo @form_dropdown('voltagePerCell['.$battery_counter.']', $voltagePerCell, set_value('voltagePerCell['.$battery_counter.']', $batt['voltage_per_cell']));
								?>
								<span class="add-on">Volt</span>
							</div>
						</td>
						<td>
							<?php
							$lookup = $this->core->get_lookup('rectifier_number');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							
							echo @form_dropdown('linkedToRectifierNo['.$battery_counter.']', $options, $batt['rectifier_no']);
							?>
						</td>
					</tr>
					<?php
							$battery_counter++;
						}
					} else {
					?>
					<tr id="battery-row-1">
						<td class="right"><a class="btn btn-small btn-danger" href="javascript:void(0)" onclick="BATTERY.removeRow(1)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							echo @form_hidden('batteryId['.$battery_counter.']');
							
							$items = array(0 => '- Choose one -');
							$lookup = $this->core->get_lookup('power_mcb_item');
							foreach ($lookup as $lo) {
								$items[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('mcbItem['.$battery_counter.']',  $items);
							?>
						</td>
						<td>
							<?php
							$batteryBrand = array(0 => '- Choose one -');
							$dataBatteryBrand = get_instance()->core->view_all('t_mtr_lookup', array('lookup_key' => 'battery_brand', 'active' => 1), 'lookup_desc');
							foreach ($dataBatteryBrand as $brand) {
								$batteryBrand[$brand['lookup_value']] = $brand['lookup_desc'];
							}
							echo @form_dropdown('batteryBrand['.$battery_counter.']', $batteryBrand);
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('batteryCapacity['.$battery_counter.']', '', 'class="input-mini number"') ?>
								<span class="add-on">Ah</span>
							</div>
						</td>
						<td>
							<?php echo @form_input('batteryTotalBank['.$battery_counter.']', '', 'class="input-mini just-number"') ?>
						</td>
						<td>
							<?php echo @form_input('batteryTotalShunt['.$battery_counter.']', '', 'class="input-mini just-number"') ?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('weightPerCell['.$battery_counter.']', '', 'class="input-mini number"') ?>
								<span class="add-on">kg</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php
								$voltagePerCell = array(0 => '- Choose one -');
								$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'voltage_per_cell', 'active' => 1), 'order');
								foreach ($lookup as $lo) {
									$voltagePerCell[$lo['lookup_value']] = $lo['lookup_desc'];
								}
								echo @form_dropdown('voltagePerCell['.$battery_counter.']', $voltagePerCell);
								?>
								<span class="add-on">Volt</span>
							</div>
						</td>
						<td>
							<?php
							$lookup = $this->core->get_lookup('rectifier_number');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							
							echo @form_dropdown('linkedToRectifierNo['.$battery_counter.']', $options, '');
							?>
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="9" class="left"><a class="btn btn-small btn-success" href="javascript:void(0)" onclick="BATTERY.addRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></a></td>
					</tr>
				</tfoot>
			</table>
		</div>
		
	</div>
</div>

<div class="form-actions">
	<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
	<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/site_info/power') ?>'"><i></i>Cancel</button>
</div>

<?php echo form_close() ?>

<script>
document.getElementById('frmBattery').onsubmit = function() {

	var data = serialize(this);
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=ADD_BATTERY';
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	data += '&cmd=EDIT_BATTERY';
	<?php } ?>
	data += '&siteId=' + document.getElementsByName('siteId')[0].value;
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
					//window.location.href = powerURL;
					var tab = $('#powerTab').tabs('getSelected');
					
					<?php if ($this->uri->segment(3) == 'edit') { ?>
					var params = '?load_content=BATTERY';
					<?php } else { ?>
					var params = '?load_content=BATTERY&site_id=' + document.getElementsByName('siteId')[0].value;
					<?php } ?>
					
					$('#powerTab').tabs('update', {
						tab: tab,
						options: {
							href: document.URL + params
						}
					});
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

<?php if (count($battery) > 0) { ?>
var batteryCounter = <?php echo $battery_counter ?>;
<?php } else { ?>
var batteryCounter = 2;
<?php } ?>

var BATTERY = {
	addRow: function() {
		var html = '\
			<tr id="battery-row-' + batteryCounter + '">\
				<td><a class="btn btn-small btn-danger" href="javascript:void(0)" onclick="BATTERY.removeRow(' + batteryCounter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>\
				<td>\
					<input type="hidden" name="batteryId[' + batteryCounter + ']" />\
					<?php
					$lookup = $this->core->get_lookup('power_mcb_item');
					?>\
					<select name="mcbItem[' + batteryCounter + ']">\
						<option value="0">- Choose one -</option>\
						<?php foreach ($lookup as $lo) { ?>\
						<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
						<?php } ?>\
					</select>\
				</td>\
				<td>\
					<?php
					$dataBatteryBrand = $this->core->get_lookup('battery_brand');
					?>\
					<select name="batteryBrand[' + batteryCounter + ']">\
						<option value="0">- Choose one -</option>\
						<?php foreach ($dataBatteryBrand as $brand) { ?>\
						<option value="<?php echo $brand['id'] ?>"><?php echo $brand['desc'] ?></option>\
						<?php } ?>\
					</select>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="batteryCapacity[' + batteryCounter + ']" class="input-mini number" />\
						<span class="add-on">Ah</span>\
					</div>\
				</td>\
				<td>\
					<input type="text" name="batteryTotalBank[' + batteryCounter + ']" class="input-mini just-number" />\
				</td>\
				<td>\
					<input type="text" name="batteryTotalShunt[' + batteryCounter + ']" class="input-mini just-number" />\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="weightPerCell[' + batteryCounter + ']" class="input-mini number" />\
						<span class="add-on">kg</span>\
					</div>\
				</td>\
				<td>\
					<div class="input-append">\
						<?php
						$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'voltage_per_cell', 'active' => 1), 'order');
						?>\
						<select name="voltagePerCell[' + batteryCounter + ']">\
							<option value="0">- Choose one -</option>\
							<?php foreach ($lookup as $lo) { ?>\
							<option value="<?php echo $lo['lookup_value'] ?>"><?php echo $lo['lookup_desc'] ?></option>\
							<?php } ?>\
						</select>\
						<span class="add-on">Volt</span>\
					</div>\
				</td>\
				<td>\
					<?php
					$lookup = $this->core->get_lookup('rectifier_number');
					?>\
					<select name="linkedToRectifierNo[' + batteryCounter + ']"">\
						<option value="0">- Choose one -</option>\
						<?php foreach ($lookup as $lo) { ?>\
						<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
						<?php } ?>\
					</select>\
				</td>\
			</tr>\
		';
		
		$('#tbl-battery tbody').append(html);
		
		numberFormat.init();
		justNumberFormat.init();
		clearAutoComplete.init();

		batteryCounter++;
	},
	removeRow: function(row) {
		var row = document.getElementById('battery-row-' + row);
		
		coolConfirmation('Are you sure you want to delete?', function() {
			row.parentNode.removeChild(row);
		});
	}
};

numberFormat.init();
justNumberFormat.init();
clearAutoComplete.init();
</script>