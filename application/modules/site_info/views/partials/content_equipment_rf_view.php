<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<div class="form-horizontal">

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading"><i></i> Carrier Configuration</h4>
		</div>
		
		<div class="widget-body">
		
			<div class="overflow:auto">
				<table id="tblCarrier" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>System</th>
							<th>Configuration</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($this->uri->segment(3) == 'view' && count($carrier)) {
							$carrier_counter = 1;
							
							foreach ($carrier as $carr) {
						?>
						<tr id="carrier-row<?php echo $carrier_counter ?>">
							<td>
								<?php
								$system = array(0 => '');
								$data_system = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'carrier_system', 'active' => 1), 'order');
								foreach ($data_system as $sys) {
									$system[$sys['lookup_value']] = $sys['lookup_desc'];
								}
								?>
								<span><?php echo $system[$carr['system']] ?></span>
							</td>
							<td>
								<?php
								$config = array(0 => '');
								$data_config = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'carrier_configuration', 'active' => 1), 'order');
								foreach ($data_config as $conf) {
									$config[$conf['lookup_value']] = $conf['lookup_desc'];
								}
								?>
								<span><?php echo $config[$carr['configuration']] ?></span>
							</td>
						</tr>
						<?php 
								$carrier_counter++;
							}
						
						}
						?>
					</tbody>
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
				<table id="tblBTS" class="table table-bordered table-condensed">
					<thead>
						<tr>
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
						<?php if ($this->uri->segment(3) == 'view' && count($rf) > 0) {
							
							$rfCounter = 1;					
							foreach ($rf as $er_ef) {
						?>
						
						<tr id="btsRow<?php echo $rfCounter ?>">
							<td nowrap>
								<?php
								$type = array(0 => '');
								$dataType = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_rf_type', 'active' => 1), 'order');
								foreach ($dataType as $t) {
									$type[$t['lookup_value']] = $t['lookup_desc'];
								}
								?>
								<span><?php echo $type[$er_ef['type']] ?></span>
							</td>
							<td nowrap><span><?php echo $er_ef['model'] ?></span></td>
							<td nowrap><span><?php echo $er_ef['brand'] ?></span></td>
							<td nowrap>
								<?php
								$cells = array(0 => '');
								$dataCells = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_cell', 'active' => 1), 'order');
								foreach ($dataCells as $cell) {
									$cells[$cell['lookup_value']] = $cell['lookup_desc'];
								}
								?>
								<span><?php echo $cells[$er_ef['cell']] ?></span>
							</td>
							<td nowrap>
								<?php
								$lookup = $this->core->get_lookup('antenna_band');
								$options = array(0 => '');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $options[$er_ef['ant_model']] ?></span>
							</td>
							<td><span><?php echo $er_ef['hbwd'] ?></span></td>
							<td><span><?php echo $er_ef['vbwd'] ?></span></td>
							<td nowrap>
								<span><?php echo $er_ef['dimension_long'].' x '.$er_ef['dimension_width'].' x '.$er_ef['dimension_height'] ?> mm</span>
							</td>
							<td class="right">
								<span><?php echo $er_ef['weight'] ?> kg</span>
							</td>
							<td class="right"><span><?php echo $er_ef['height'] ?> M</span></td>
							<td class="right"><span><?php echo $er_ef['azimuth'] ?></span></td>
							<td class="right"><span><?php echo $er_ef['electrical_tilt'] ?></span></td>
							<td class="right"><span><?php echo $er_ef['mechanical_tilt'] ?></span></td>							
						</tr>
						<?php
								$rfCounter++;
							}
						}
						?>						
					</tbody>
				</table>
			</div>
			
		</div>
	</div>

</div>

<script>
<?php if ($this->uri->segment(3) == 'edit' && $carrier) { ?>
var carrierCounter = <?php echo $carrier_counter ?>;
<?php } else { ?>
var carrierCounter = 2;
<?php } ?>
		
var CARRIER = {
	addRow: function() {
		
		var html = '\
			<tr id="carrier-row' + carrierCounter + '">\
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
				<td class="right"><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="CARRIER.removeRow(' + carrierCounter + ')"><i></i> Remove</a></td>\
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
var btsCounter = 2;
<?php } ?>

var RF = {	
	addRow: function() {
		var html = '\
			<tr id="btsRow' + btsCounter + '">\
				<td>\
					<input type="hidden" name="rfId[]" />\
					\
					<select name="typeRF[]" id="typeRF' + btsCounter + '" class="input-medium">\
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
					<select name="model[]" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$lookup = $this->core->get_lookup('antenna_rf_model');
						foreach ($lookup as $lo) {
							echo '<option value="'.$lo['id'].'">'.$lo['desc'].'</option>';
						}
						?>\
					</select>\
				</td>\
				<td>\
					<select name="cell[]" id="cell' + btsCounter + '" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$dataCells = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_cell', 'active' => 1), 'order');
						foreach ($dataCells as $cell) {
							echo '<option value="'.$cell['lookup_value'].'">'.$cell['lookup_desc'].'</option>';
						}
						?>\
					</select>\
				</td>' +
				/* <td>\
					<select name="antenaFrequency[]" id="antenaFrequency' + btsCounter + '" class="input-medium">\
						<option value="">- Choose one -</option>\
						<?php
						$dataFrequencies = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_frequency', 'active' => 1), 'lookup_desc');
						foreach ($dataFrequencies as $freq) {
							echo '<option value="'.$freq['lookup_value'].'">'.$freq['lookup_desc'].'</option>';
						}
						?>\
					</select>\
				</td>\ */
				'<td>\
					<select name="antenaModel[]" id="antenaModel' + btsCounter + '" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$dataModels = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_band', 'active' => 1), 'lookup_desc');
						foreach ($dataModels as $model) {
							echo '<option value="'.$model['lookup_value'].'">'.$model['lookup_desc'].'</option>';
						}
						?>\
					</select>\
				</td>' +
				/* <td>\
					<select name="antenaMode[]" id="antenaMode' + btsCounter + '" class="span2">\
						<option value="">- Choose one -</option>\
					</select>\
				</td>\ */
				'<td>\
					<input type="text" name="antenaAzimuth[]" id="antenaAzimuth' + btsCounter + '" class="just-number input-small" />\
				</td>\
				<td>\
					<input type="text" name="electricalTilt[]" id="electricalTilt' + btsCounter + '" class="just-number input-small" />\
				</td>\
				<td>\
					<input type="text" name="mechanicalTilt[]" id="mechanicalTilt' + btsCounter + '" class="just-number input-small" />\
				</td>\
				<td>\
					<select name="beamwidth[]" id="beamwidth' + btsCounter + '" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$dataBeamwidth = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_rf_beamwidth', 'active' => 1), 'order');
						foreach ($dataBeamwidth as $bw) {
							echo '<option value="'.$bw['lookup_value'].'">'.$bw['lookup_desc'].'</option>';
						}
						?>\
					</select>\
				</td>\
				<td><input type="text" name="beamwidthValue[]" id="beamwidthValue' + btsCounter + '" class="number input-small" />\</td>\
				<td nowrap>\
					<div class="input-append">\
						<input type="text" name="dimensionLong[]" class="number input-small" />\
						<input type="text" name="dimensionWidth[]" class="number input-small" />\
						<input type="text" name="dimensionHeight[]" class="number input-small" />\
						<span class="add-on">CM</span>\
					</div>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="height[]" id="height" class="number input-small" />\
						<span class="add-on">M</span>\
					</div>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="weight[]" id="weight" class="number input-small" />\
						<span class="add-on">kg</span>\
					</div>\
				</td>\
				<td class="right"><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="RF.removeRow(' + btsCounter + ')"><i></i> Remove</a></td>\
			</tr>\
		';
		
		$('#tblBTS tbody').append(html);
		
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
		var azimuth = document.getElementsByName('antenaAzimuth[]');
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
		
		var electricalTilt = document.getElementsByName('electricalTilt[]');
		for (var i = 0; i < electricalTilt.length; i++) {
			electricalTilt[i].onkeyup = function() {
				if (this.value < -20 || this.value > 20) {
					alert('Electrical Tilt value must be -20 to 20');
					this.value = 0;
					return false;
				}
			}
		}
		
		var mechanicalTilt = document.getElementsByName('mechanicalTilt[]');
		for (var i = 0; i < mechanicalTilt.length; i++) {
			mechanicalTilt[i].onkeyup = function() {
				if (this.value < -20 || this.value > 20) {
					alert('Mechanical Tilt value must be -20 to 20');
					this.value = 0;
					return false;
				}
			}
		}
		
		var beamwidthValue = document.getElementsByName('beamwidthValue[]');
		for (var i = 0; i < beamwidthValue.length; i++) {
			beamwidthValue[i].onkeyup = function() {
				if (this.value < 0 || this.value > 360) {
					alert('Beamwidth Value Tilt value must be -20 to 20');
					this.value = 0;
					return false;
				}
			}
		}
	}
};

loadToUpperCase();
numberFormat.init();
justNumberFormat.init();
RFValidate.init();
clearAutocomplete();
</script>