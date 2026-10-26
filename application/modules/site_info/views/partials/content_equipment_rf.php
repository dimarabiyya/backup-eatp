<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<?php echo form_open('', 'id="frmRF" class="form-horizontal') ?>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> Carrier Configuration</h4>
	</div>
	
	<div class="widget-body">
	
		<div class="overflow:auto">
			<table id="tblCarrier" class="table table-bordered table-condensed" style="width:auto">
				<thead>
					<tr>
						<th class="center">&nbsp;</th>
						<th class="center">System</th>
						<th class="center">Configuration</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($this->uri->segment(3) == 'edit' && count($carrier)) {
						$carrier_counter = 1;
						
						foreach ($carrier as $carr) {
					?>
					<tr id="carrier-row<?php echo $carrier_counter ?>">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="CARRIER.removeRow(<?php echo $carrier_counter ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							echo form_hidden('carrierId[]', $carr['equipment_carrier_id']);
							
							$system = array(0 => '- Choose one -');
							$data_system = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'carrier_system', 'active' => 1), 'order');
							foreach ($data_system as $sys) {
								$system[$sys['lookup_value']] = $sys['lookup_desc'];
							}
							echo @form_dropdown('system[]', $system, set_value('system[]', $carr['system']), 'class="input-medium"');
							?>
						</td>
						<td>
							<?php
							$config = array(0 => '- Choose one -');
							$data_config = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'carrier_configuration', 'active' => 1), 'order');
							foreach ($data_config as $conf) {
								$config[$conf['lookup_value']] = $conf['lookup_desc'];
							}
							echo @form_dropdown('config[]', $config, set_value('config[]', $carr['configuration']), 'class="input-medium"');
							?>
						</td>						
					</tr>
					<?php 
							$carrier_counter++;
						}
					
					} else { ?>
					
					<tr id="carrier-row1">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="CARRIER.removeRow(1)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							echo form_hidden('carrierId[]', '');
							
							$system = array(0 => '- Choose one -');
							$data_system = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'carrier_system', 'active' => 1), 'order');
							foreach ($data_system as $sys) {
								$system[$sys['lookup_value']] = $sys['lookup_desc'];
							}
							echo @form_dropdown('system[]', $system, set_value('system[]', ''), 'class="input-medium"');
							?>
						</td>
						<td>
							<?php
							$config = array(0 => '- Choose one -');
							$data_config = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'carrier_configuration', 'active' => 1), 'order');
							foreach ($data_config as $conf) {
								$config[$conf['lookup_value']] = $conf['lookup_desc'];
							}
							echo @form_dropdown('config[]', $config, set_value('config[]', ''), 'class="input-medium"');
							?>
						</td>
					</tr>
					<?php } ?>
					
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3" class="left"><a class="btn btn-success btn-small" href="javascript:void(0)" onclick="CARRIER.addRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></a></td>
					</tr>
				</tfoot>
			</table>
		</div>
	
	</div>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> RF Antenna</h4>
	</div>
	
	<div class="widget-body">
		
		<div style="overflow:auto">
			<table id="tblRF" class="table table-bordered table-condensed" style="width:auto">
				<thead>
					<tr>
						<th class="center">&nbsp;</th>
						<th class="center">Type</th>
						<th class="center">Model</th>
						<th class="center">Brand</th>
						<th class="center">Cell</th>
						<th class="center">Band</th>
						<th class="center">HBWD</th>
						<th class="center">VBWD</th>
						<th class="center">Dimension (LxWxH)</th>
						<th class="center">Weight</th>
						<th class="center">Height</th>
						<th class="center">Azimuth</th>
						<th class="center">Electrical Tilt</th>
						<th class="center">Mechanical Tilt</th>						
					</tr>
				</thead>
				<tbody>
					<?php if ($this->uri->segment(3) == 'edit' && count($rf) > 0) {
						
						$rfCounter = 1;								
						foreach ($rf as $er_ef) {
					?>
					
					<tr id="btsRow<?php echo $rfCounter ?>">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="RF.removeRow(<?php echo $rfCounter ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							echo form_hidden('rfId['.$rfCounter.']', $er_ef['equipment_rf_id']);
							
							$type = array(0 => '- Choose one -');
							$dataType = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_rf_type', 'active' => 1), 'order');
							foreach ($dataType as $t) {
								$type[$t['lookup_value']] = $t['lookup_desc'];
							}
							echo @form_dropdown('typeRF['.$rfCounter.']', $type, $er_ef['type'], 'id="typeRF" class="input-medium"');									
							?>
						</td>
						<td>
							<div class="input-append">
								<?php
								echo form_input('rfModel['.$rfCounter.']', $er_ef['model'], 'class="input-medium" readonly="readonly"');
								echo form_hidden('rfModelId['.$rfCounter.']', $er_ef['model_id']);
								?>
								<button type="button" class="btn" onclick="doLookupMasterAntennaRF('rfModel[<?php echo $rfCounter ?>]', 'rfModelId[<?php echo $rfCounter ?>]', <?php echo $rfCounter ?>)">...</button>
							</div>
						</td>
						<td><?php echo form_input('rfBrand['.$rfCounter.']', $er_ef['brand'], 'class="input-medium" readonly="readonly"') ?></td>
						<td>
							<?php
							$cells = array(0 => '- Choose one -');
							$dataCells = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_cell', 'active' => 1), 'order');
							foreach ($dataCells as $cell) {
								$cells[$cell['lookup_value']] = $cell['lookup_desc'];
							}
							echo @form_dropdown('cell['.$rfCounter.']', $cells, $er_ef['cell'], 'id="cell" class="input-medium"');
							?>
						</td>
						<td>
							<?php //echo form_input('rfBand[1]', $er_ef['band'], 'class="input-small" readonly="readonly"') ?>
							<?php
							$lookup = $this->core->get_lookup('antenna_band');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo form_dropdown('rfBand['.$rfCounter.']', $options, $er_ef['ant_model']);
							?>
						</td>
						<td><?php echo @form_input('horizontalBeamwidth['.$rfCounter.']', $er_ef['hbwd'], 'class="number__ beamwidth input-small" readonly="readonly"') ?></td>
						<td><?php echo @form_input('verticalBeamwidth['.$rfCounter.']', $er_ef['vbwd'], 'class="number__ beamwidth input-small" readonly="readonly"') ?></td>
						<td nowrap>
							<div class="input-append">
								<?php echo @form_input('dimensionLong['.$rfCounter.']', $er_ef['dimension_long'], 'id="dimensionLong" class="number input-small" readonly="readonly"') ?>
								<?php echo @form_input('dimensionWidth['.$rfCounter.']', $er_ef['dimension_width'], 'id="dimensionWidth" class="number input-small" readonly="readonly"') ?>
								<?php echo @form_input('dimensionHeight['.$rfCounter.']', $er_ef['dimension_height'], 'id="dimensionHeight" class="number input-small" readonly="readonly"') ?>
								<span class="add-on">mm</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('weight['.$rfCounter.']', $er_ef['weight'], 'id="weight" class="number input-small" readonly="readonly"') ?>
								<span class="add-on">kg</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('height['.$rfCounter.']', $er_ef['height'], 'id="height" class="number input-small"') ?>
								<span class="add-on">M</span>
							</div>
						</td>
						<td><?php echo @form_input('antenaAzimuth['.$rfCounter.']', $er_ef['azimuth'], 'id="antenaAzimuth" class="just-number azimuth input-small"') ?></td>
						<td><?php echo @form_input('electricalTilt['.$rfCounter.']', $er_ef['electrical_tilt'], 'id="electricalTilt" class="just-number electrical-tilt input-small"') ?></td>
						<td><?php echo @form_input('mechanicalTilt['.$rfCounter.']', $er_ef['mechanical_tilt'], 'id="mechanicalTilt" class="just-number mechanical-tilt input-small"') ?></td>
					</tr>
					<?php
							$rfCounter++;
						}
					} else {
					
						$lookup = $this->core->get_lookup('antenna_cell');
						$rfCounter = 1;
						foreach ($lookup as $lo) {
					?>
					
					<tr id="btsRow<?php echo $rfCounter ?>">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="RF.removeRow(<?php echo $rfCounter ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							echo @form_hidden('rfId['.$rfCounter.']', '');
							
							$type = array(0 => '- Choose one -');
							$dataType = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_rf_type', 'active' => 1), 'order');
							foreach ($dataType as $t) {
								$type[$t['lookup_value']] = $t['lookup_desc'];
							}
							echo @form_dropdown('typeRF['.$rfCounter.']', $type, '', 'id="typeRF" class="input-medium"');									
							?>
						</td>
						<td>
							<div class="input-append">
								<?php
								echo form_input('rfModel['.$rfCounter.']', '', 'class="input-medium" readonly="readonly"');
								echo form_hidden('rfModelId['.$rfCounter.']');
								?>
								<button type="button" class="btn" onclick="doLookupMasterAntennaRF('rfModel[<?php echo $rfCounter ?>]', 'rfModelId[<?php echo $rfCounter ?>]', <?php echo $rfCounter ?>)">...</button>
							</div>
						</td>
						<td><?php echo form_input('rfBrand['.$rfCounter.']', '', 'class="input-medium" readonly="readonly"') ?></td>
						<td>
							<?php
							$cells = array(0 => '- Choose one -');
							$dataCells = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_cell', 'active' => 1), 'order');
							foreach ($dataCells as $cell) {
								$cells[$cell['lookup_value']] = $cell['lookup_desc'];
							}
							echo form_dropdown('cell['.$rfCounter.']', $cells, $lo['id'], 'id="cell" class="input-medium"');									
							?>
						</td>
						<td>
							<?php //echo form_input('rfBand[1]', '', 'class="input-small" readonly="readonly"') ?>
							<?php
							$lookup = $this->core->get_lookup('antenna_band');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo form_dropdown('rfBand['.$rfCounter.']', $options, '');
							?>
						</td>
						<td><?php echo @form_input('horizontalBeamwidth['.$rfCounter.']', '', 'class="number__ beamwidth input-small" readonly="readonly"') ?></td>
						<td><?php echo @form_input('verticalBeamwidth['.$rfCounter.']', '', 'class="number__ beamwidth input-small" readonly="readonly"') ?></td>
						<td nowrap>
							<div class="input-append">
								<?php echo @form_input('dimensionLong['.$rfCounter.']', '', 'id="dimensionLong" class="number input-small" readonly="readonly"') ?>
								<?php echo @form_input('dimensionWidth['.$rfCounter.']', '', 'id="dimensionWidth" class="number input-small" readonly="readonly"') ?>
								<?php echo @form_input('dimensionHeight['.$rfCounter.']', '', 'id="dimensionHeight" class="number input-small" readonly="readonly"') ?>
								<span class="add-on">mm</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('weight['.$rfCounter.']', '', 'id="weight" class="number input-small" readonly="readonly"') ?>
								<span class="add-on">kg</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('height['.$rfCounter.']', '', 'id="height" class="number input-small"') ?>
								<span class="add-on">M</span>
							</div>
						</td>
						<td><?php echo @form_input('antenaAzimuth['.$rfCounter.']', '', 'id="antenaAzimuth" class="just-number azimuth input-small"') ?></td>
						<td><?php echo @form_input('electricalTilt['.$rfCounter.']', '', 'id="electricalTilt" class="just-number electrical-tilt input-small"') ?></td>
						<td><?php echo @form_input('mechanicalTilt['.$rfCounter.']', '', 'id="mechanicalTilt" class="just-number mechanical-tilt input-small"') ?></td>
					</tr>
					<?php
							$rfCounter++;
						}
					}
					?>
					
				</tbody>
				<tfoot>
					<tr>
						<td colspan="14" class="left"><a class="btn btn-success btn-small" href="javascript:void(0)" onclick="RF.addRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></a></td>
					</tr>
				</tfoot>
			</table>
		</div>
		
	</div>
</div>

<div class="form-actions">
	<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
	<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/site_info/equipment') ?>'"><i></i>Cancel</button>
</div>

<?php echo form_close() ?>

<script>
document.getElementById('frmRF').onsubmit = function() {
	
	var data = serialize(this);
	data += '&siteId=' + document.getElementsByName('siteId')[0].value;
	
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=ADD_RF';
	<?php } else { ?>
	data += '&cmd=EDIT_RF';
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
				siteOverlay.remove();
				alert(json.message);
				window.location.href = json.redirect_url;
			}
			
			var messageAlert = document.getElementsByClassName('alert');
			for (var i = 0; i < messageAlert.length; i++) {
				messageAlert[i].parentNode.removeChild(messageAlert[i]);
			}
			
			if (typeof json.error != 'undefined' && json.error) {
				var message = '\
					<ul style="text-align:left">';
					for (var i = 0; i < json['message'].length; i++) {
						message += '<li>' + json['message'][i] + '</li>';
					}
					message += '</ul>';
				
				coolNotification(message, 'error');
			} else {
				coolNotification(json['message'], 'success', function() {
					window.location.href = equipmentURL;
				});
				
				// refresh content tab
				var tab = $('#equipmentTab').tabs('getSelected');
				$('#equipmentTab').tabs('update', {
					tab: tab,
					options: {
						href: document.URL + '?load_content=RF&site_id=' + document.getElementsByName('siteId')[0].value
					}
				});
			}
		},
		complete: function() {
			siteOverlay.remove();
		}
	});
	
	window.scroll(0, 0);
	
	return false;
};

<?php if ($this->uri->segment(3) == 'edit' && $carrier) { ?>
var carrierCounter = <?php echo $carrier_counter ?>;
<?php } else { ?>
var carrierCounter = 2;
<?php } ?>
		
var CARRIER = {
	addRow: function() {
		
		var html = '\
			<tr id="carrier-row' + carrierCounter + '">\
				<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="CARRIER.removeRow(' + carrierCounter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>\
				<td>\
					<input type="hidden" name="carrierId[]">\
					\
					<select name="system[]" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php 
						$data_system = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'carrier_system', 'active' => 1), 'order');
						foreach ($data_system as $sys) {
						?>\
						<option value="<?php echo $sys['lookup_value'] ?>"><?php echo $sys['lookup_desc'] ?></option>\
						<?php } ?>\
					</select>\
				</td>\
				<td>\
					<select name="config[]" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php 
						$data_config = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'carrier_configuration', 'active' => 1), 'order');
						foreach ($data_config as $conf) {
						?>\
						<option value="<?php echo $conf['lookup_value'] ?>"><?php echo $conf['lookup_desc'] ?></option>\
						<?php } ?>\
					</select>\
				</td>\
			</tr>\
		';
		
		$('#tblCarrier tbody').append(html);
		
		carrierCounter++;
	},
	removeRow: function(row) {
		var row = document.getElementById('carrier-row' + row);
		
		coolConfirmation('Are you sure you want to delete?', function() {
			row.parentNode.removeChild(row);
		});
	}
};

<?php if ($this->uri->segment(3) == 'edit' && count($rf) > 0) { ?>
var btsCounter = <?php echo $rfCounter ?>;
<?php } else { ?>
var btsCounter = <?php echo $rfCounter ?>;
<?php } ?>

var RF = {	
	addRow: function() {
		var html = '\
			<tr id="btsRow' + btsCounter + '">\
				<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="RF.removeRow(' + btsCounter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>\
				<td>\
					<input type="hidden" name="rfId[' + btsCounter + ']" />\
					\
					<select name="typeRF[' + btsCounter + ']" id="typeRF' + btsCounter + '" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$dataType = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_rf_type', 'active' => 1), 'order');
						foreach ($dataType as $t) {
							echo '<option value="'.$t['lookup_value'].'">'.$t['lookup_desc'].'</option>';
						}
						?>\
					</select>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="rfModel[' + btsCounter + ']" class="input-medium" readonly="readonly" />\
						<input type="hidden" name="rfModelId[' + btsCounter + ']" />\
						<button type="button" class="btn" onclick="doLookupMasterAntennaRF(\'rfModel[' + btsCounter + ']\', \'rfModelId[' + btsCounter + ']\', ' + btsCounter + ')">...</button>\
					</div>\
				</td>\
				<td>\
					<input type="text" name="rfBrand[' + btsCounter + ']" class="input-medium" readonly="readonly" />\
				</td>\
				<td>\
					<select name="cell[' + btsCounter + ']" id="cell' + btsCounter + '" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$dataCells = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_cell', 'active' => 1), 'order');
						foreach ($dataCells as $cell) {
							echo '<option value="'.$cell['lookup_value'].'">'.$cell['lookup_desc'].'</option>';
						}
						?>\
					</select>\
				</td>\
				<td>\
					<select name="rfBand[' + btsCounter + ']">\
						<option value="0">- Choose one -</option>\
						<?php
						$lookup = $this->core->get_lookup('antenna_model');
						foreach ($lookup as $lo) {
							?>\
							<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
							<?php
						}
						?>\
					</select>\
				</td>\
				<td>\
					<input type="text" name="horizontalBeamwidth[' + btsCounter + ']" class="number__ beamwidth input-small" readonly="readonly" />\
				</td>\
				<td>\
					<input type="text" name="verticalBeamwidth[' + btsCounter + ']" class="number__ beamwidth input-small" readonly="readonly" />\
				</td>\
				<td nowrap>\
					<div class="input-append">\
						<input type="text" name="dimensionLong[' + btsCounter + ']" class="number input-small" readonly="readonly" />\
						<input type="text" name="dimensionWidth[' + btsCounter + ']" class="number input-small" readonly="readonly" />\
						<input type="text" name="dimensionHeight[' + btsCounter + ']" class="number input-small" readonly="readonly" />\
						<span class="add-on">mm</span>\
					</div>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="weight[' + btsCounter + ']" id="weight" class="number input-small" readonly="readonly" />\
						<span class="add-on">kg</span>\
					</div>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="height[' + btsCounter + ']" id="height" class="number input-small" />\
						<span class="add-on">M</span>\
					</div>\
				</td>\
				<td>\
					<input type="text" name="antenaAzimuth[' + btsCounter + ']" id="antenaAzimuth' + btsCounter + '" class="just-number azimuth input-small" />\
				</td>\
				<td>\
					<input type="text" name="electricalTilt[' + btsCounter + ']" id="electricalTilt' + btsCounter + '" class="just-number electrical-tilt input-small" />\
				</td>\
				<td>\
					<input type="text" name="mechanicalTilt[' + btsCounter + ']" id="mechanicalTilt' + btsCounter + '" class="just-number mechanical-tilt input-small" />\
				</td>\
			</tr>\
		';
		
		$('#tblRF tbody').append(html);
		
		numberFormat.init();
		justNumberFormat.init();
		RFValidate.init();
		clearAutocomplete();
		
		btsCounter++;
	},
	removeRow: function(row) {
		var btsRowElem = document.getElementById('btsRow' + row);
		
		coolConfirmation('Are you sure you want to delete?', function() {
			btsRowElem.parentNode.removeChild(btsRowElem);
		});
	}
};

var RFValidate = {
	init: function() {
		var azimuth = document.getElementsByClassName('azimuth');
		for (var i = 0; i < azimuth.length; i++) {
			azimuth[i].onkeyup = function(e) {
				if (this.value < 0 || this.value > 360) {
					var code = e.keyCode;
					if (code != 37 && code != 38 && code != 39 && code != 40 && code != 13 && code != 8 && code != 46) {
						alert('Azimuth value must be 0 to 360');
						this.value = 0;
					}
				}
			}
		}
		
		var electricalTilt = document.getElementsByClassName('electrical-tilt');
		for (var i = 0; i < electricalTilt.length; i++) {
			electricalTilt[i].onkeyup = function() {
				if (this.value < -20 || this.value > 20) {
					alert('Electrical Tilt value must be -20 to 20');
					this.value = 0;
					return false;
				}
			}
		}
		
		var mechanicalTilt = document.getElementsByClassName('mechanical-tilt');
		for (var i = 0; i < mechanicalTilt.length; i++) {
			mechanicalTilt[i].onkeyup = function() {
				if (this.value < -20 || this.value > 20) {
					alert('Mechanical Tilt value must be -20 to 20');
					this.value = 0;
					return false;
				}
			}
		}
		
		/* var beamwidthValue = document.getElementsByClassName('beamwidth');
		for (var i = 0; i < beamwidthValue.length; i++) {
			beamwidthValue[i].onkeyup = function() {
				if (this.value < 0 || this.value > 360) {
					alert('Beamwidth Value Tilt value must be -20 to 20');
					this.value = 0;
					return false;
				}
			}
		} */
	}
};

var doLookupMasterAntennaRF = function(elem1, elem2, elemIdx) { // elem1: shown element, elem2: hidden element, elemIdx: index of element
	var url = '<?php echo site_url('/site_info/equipment') ?>';
	
	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { cmd: 'get_lookup_master_antenna_rf', e: elem2 } }, // e: it used for selected element
		width: 600,
		scrolling: false,
		autoSize: true,
		fitToView: true,
		closeClick: false,
		openEffect: 'fade',
		closeEffect: 'fade',
		afterShow: function() {
			document.getElementById('btnDataSelectedOK').onclick = function() {
				var row = $('#dg-master-antenna-rf').datagrid('getSelected');
				
				if (row != null) {
					document.getElementsByName('rfModel[' + elemIdx + ']')[0].value = row.model;
					document.getElementsByName('rfModelId[' + elemIdx + ']')[0].value = row.antenna_id;
					document.getElementsByName('rfBrand[' + elemIdx + ']')[0].value = row.brand;
					//document.getElementsByName('rfBand[' + elemIdx + ']')[0].value = row.band;
					document.getElementsByName('horizontalBeamwidth[' + elemIdx + ']')[0].value = row.hbwd;
					document.getElementsByName('verticalBeamwidth[' + elemIdx + ']')[0].value = row.vbwd;
					document.getElementsByName('dimensionLong[' + elemIdx + ']')[0].value = row.dimension_long;
					document.getElementsByName('dimensionWidth[' + elemIdx + ']')[0].value = row.dimension_width;
					document.getElementsByName('dimensionHeight[' + elemIdx + ']')[0].value = row.dimension_height;
					document.getElementsByName('weight[' + elemIdx + ']')[0].value = row.weight;
					
					numberFormat.init();
				}
				
				$.fancybox.close();
			};
		}
	});
};

loadToUpperCase();
numberFormat.init();
justNumberFormat.init();
RFValidate.init();
clearAutocomplete();
</script>