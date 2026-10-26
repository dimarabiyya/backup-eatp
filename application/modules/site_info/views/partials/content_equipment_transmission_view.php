<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<div class="form-horizontal">

	<div class="widget">	
		<div class="widget-head">
			<h4 class="heading"><i></i> Transmission Info</h4>
		</div>
		
		<div class="widget-body">
		
			<div class="control-group" id="form_class_site_transmission">
				<label class="control-label" for="oldSiteId" style="width:180px; text-align:left;">Site Class Transmission:</label>
				<div class="controls" style="margin-left:180px">
					<?php
					$lookup = $this->core->get_lookup('site_class_transm');
					$options = array(0 => '');
					foreach ($lookup as $lo) {
						$options[$lo['id']] = $lo['desc'];
					}
					?>
					<span class="uneditable-input"><?php echo $options[(int) $site_class_transm['site_class_transm']] ?></span>
				</div>
			</div>
			<div class="separator bottom"></div>
		
			<div id="tbl-transmission" style="overflow:auto">
				<table id="tblMW" class="table table-bordered table-condensed" style="width:auto">
					<thead>
						<tr>
							<th>No.</th>
							<th>Site ID</th>
							<th>Path Length (km)</th>
							<th>Link ID</th>
							<th>TX Freq.</th>
							<th>RX Freq.</th>
							<th>Freq. Used</th>
							<th>Config</th>
							<th>Power</th>
							<th>IP Address</th>
							<th>Main Ant. Model</th>
							<th>Main Ant. Height</th>
							<th>Main Ant. Diameter</th>
							<th>SD Ant. Model</th>
							<th>SD Ant. Height</th>
							<th>SD Ant. Diameter</th>
							<th>Azimuth</th>
							<th>Eq. Model</th>
							<th>Polarization</th>
							<th>Req. Capacity (MB/E1)</th>
							<th>Inst. Capacity (MB/E1)</th>
							<th>Used. Capacity (MB/E1)</th>
							<th>Treshold</th>
							<th>Plan RSL (dBm)</th>
							<th>Actual RSL (dBm)</th>
							<th>FM</th>
							<th>Availability (%)</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$transm_counter = 1;
						if ($this->uri->segment(3) == 'view' && count($transmission) > 0) {
						
							foreach ($transmission as $transm) {
						?>
						<tr id="transmission-row-<?php echo $transm_counter ?>">
							<td rowspan="2" style="text-align:center">
								<span><?php echo $transm_counter ?></span>
							</td>
							<td>
								<span><?php echo $transm['ne']['site_code'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['ne']['path_length'], 2) ?></span>
							</td>
							<td>
								<span><?php echo $transm['ne']['link_code_id'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo $transm['ne']['tx_frequency'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo $transm['ne']['rx_frequency'] ?></span>
							</td>
							<td>
								<span><?php echo $transm['ne']['frequency_used'] ?></span>
							</td>
							<td>
								<span><?php echo $transm['ne']['config'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['ne']['power'], 2) ?></span>
							</td>
							<td>
								<span><?php echo $transm['ne']['ip_address'] ?></span>
							</td>
							<td>
								<span><?php echo $transm['ne']['ant_model'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['ne']['ketinggian'], 2) ?></span>
							</td>
							<td>
								<?php
								$options = array(0 => '');
								$lookup = get_instance()->core->get_lookup('antenna_diameter');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $options[$transm['ne']['ant_diameter']] ?></span>
							</td>
							<td>
								<span><?php echo $transm['ne']['sd_ant_model'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['ne']['sd_ant_height'], 2) ?></span>
							</td>
							<td>
								<?php
								$options = array(0 => '');
								$lookup = get_instance()->core->get_lookup('antenna_diameter');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $options[$transm['ne']['sd_ant_diameter']] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['ne']['azimuth'], 2) ?></span>
							</td>
							<td>
								<?php
								$options = array(0 => '');
								$lookup = $this->core->get_lookup('equipment_model');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $options[$transm['ne']['equipment_model']] ?></span>
							</td>
							<td>
								<?php
								$options = array(0 => '');
								$lookup = $this->core->get_lookup('transmission_polarization');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $options[$transm['ne']['polarization']] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo $transm['ne']['req_capacity'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo $transm['ne']['inst_capacity'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo $transm['ne']['used_capacity'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['ne']['treshold'], 2) ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['ne']['plan_rsl'], 2) ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['ne']['actual_rsl'], 2) ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['ne']['fm'], 2) ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['ne']['availability'], 5) ?></span>
							</td>
						</tr>
						<tr id="transmission-row-<?php echo $transm_counter ?>">
							<td>
								<span><?php echo $transm['fe']['site_code'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['fe']['path_length'], 2) ?></span>
							</td>
							<td>
								<span><?php echo $transm['fe']['link_code_id'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo $transm['fe']['tx_frequency'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo $transm['fe']['rx_frequency'] ?></span>
							</td>
							<td>
								<span><?php echo $transm['fe']['frequency_used'] ?></span>
							</td>
							<td>
								<span><?php echo $transm['fe']['config'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['fe']['power'], 2) ?></span>
							</td>
							<td>
								<span><?php echo $transm['fe']['ip_address'] ?></span>
							</td>
							<td>
								<span><?php echo $transm['fe']['ant_model'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['fe']['ketinggian'], 2) ?></span>
							</td>
							<td>
								<?php
								$options = array(0 => '');
								$lookup = get_instance()->core->get_lookup('antenna_diameter');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $options[$transm['fe']['ant_diameter']] ?></span>
							</td>
							<td>
								<span><?php echo $transm['fe']['sd_ant_model'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['fe']['sd_ant_height'], 2) ?></span>
							</td>
							<td>
								<?php
								$options = array(0 => '');
								$lookup = get_instance()->core->get_lookup('antenna_diameter');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $options[$transm['fe']['sd_ant_diameter']] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['fe']['azimuth'], 2) ?></span>
							</td>
							<td>
								<?php
								$options = array(0 => '');
								$lookup = $this->core->get_lookup('equipment_model');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $options[$transm['fe']['equipment_model']] ?></span>
							</td>
							<td>
								<?php
								$options = array(0 => '');
								$lookup = $this->core->get_lookup('transmission_polarization');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $options[$transm['fe']['polarization']] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo $transm['fe']['req_capacity'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo $transm['fe']['inst_capacity'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo $transm['fe']['used_capacity'] ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['fe']['treshold'], 2) ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['fe']['plan_rsl'], 2) ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['fe']['actual_rsl'], 2) ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['fe']['fm'], 2) ?></span>
							</td>
							<td>
								<span class="pull-right"><?php echo number_format($transm['fe']['availability'], 5) ?></span>
							</td>
						</tr>
						<tr id="transmission-separator-<?php echo $transm_counter ?>">
							<td colspan="27"><i></i></td>
						</tr>
						<?php
								$transm_counter++;
							}
						}
						?>
					</tbody>
				</table>
			</div>
		
		</div>
	</div>

</div>

<style>
.table th { white-space:nowrap; }
</style>

<script>
var copyPaste = function(currentElem, secondElem, counter) {
	document.getElementsByName(secondElem + '[' + counter + ']')[0].value = currentElem.value;
	
	numberFormat.init();
};

var generateLinkId = function() {
	var siteIdNE = document.getElementsByClassName('site-id-ne-code');
	var siteIdFE = document.getElementsByClassName('site-id-fe-code');
	var linkId1 = document.getElementsByClassName('link-id-1');
	var linkId2 = document.getElementsByClassName('link-id-2');
	
	for (var i = 0; i < siteIdNE.length; i++) {
		linkId1[i].value = siteIdNE[i].value + '#' + siteIdFE[i].value + '#1';
		linkId2[i].value = siteIdFE[i].value + '#' + siteIdNE[i].value + '#1';
	}
};

<?php if (get_instance()->uri->segment(3) == 'view' && count($transmission) > 0) { ?>
var mwCounter = <?php echo $transm_counter ?>;
<?php } else { ?>
var mwCounter = 2;
<?php } ?>

var TRANSMISSION = {
	
	addRow: function() {
	
		var html = '\
			<tr id="transmission-row-' + mwCounter + '">\
				<td>\
					<input type="hidden" name="pairID[' + mwCounter + ']" />\
					<input type="hidden" name="id1[' + mwCounter + ']" />\
					<input type="hidden" name="id2[' + mwCounter + ']" />\
					\
					<input type="text" name="siteID1Code[' + mwCounter + ']" class="input-medium site-id-ne-code" readonly="readonly" />\
					<input type="hidden" name="siteID1[' + mwCounter + ']" />\
					<div class="input-append">\
						<input type="text" name="siteID2Code[' + mwCounter + ']" class="input-medium site-id-fe-code" readonly="readonly" />\
						<input type="hidden" name="siteID2[' + mwCounter + ']" />\
						<button type="button" class="btn" onclick="doLookupSiteTransmission(\'siteID2Code[' + mwCounter + ']\', \'siteID2[' + mwCounter + ']\', 1)"><i class="icon-search"></i></button>\
					</div>\
				</td>\
				<th>\
					<input type="text" name="pathLength1[' + mwCounter + ']" class="input-mini number" /><br />\
					<input type="text" name="pathLength2[' + mwCounter + ']" class="input-mini number" /><br />\
				</td>\
				<td>\
					<input type="text" name="linkID1[' + mwCounter + ']" class="input-xlarge link-id-1" /><br />\
					<input type="text" name="linkID2[' + mwCounter + ']" class="input-xlarge link-id-2" />\
				</td>\
				<td>\
					<input type="text" name="txFreq1[' + mwCounter + ']" class="input-mini number" onchange="copyPaste(this, \'rxFreq2\', + ' + mwCounter + ')" /><br />\
					<input type="text" name="txFreq2[' + + mwCounter + ']" class="input-mini number" />\
				</td>\
				<td>\
					<input type="text" name="rxFreq1[' + mwCounter + ']" class="input-mini number" onchange="copyPaste(this, \'txFreq2\', ' + mwCounter + ')" /><br />\
					<input type="text" name="rxFreq2[' + mwCounter + ']" class="input-mini number" />\
				</td>\
				<td>\
					<input type="text" name="power1[' + mwCounter + ']" class="input-mini number" onchange="copyPaste(this, \'power2\', ' + mwCounter + ')" /><br />\
					<input type="text" name="power2[' + mwCounter + ']" class="input-mini number" readonly="readonly" />\
				</td>\
				<td>\
					<input type="text" name="ipAddress1[' + mwCounter + ']" class="input-small ipaddress" /><br />\
					<input type="text" name="ipAddress2[' + mwCounter + ']" class="input-small ipaddress" />\
				</td>\
				<td>\
					<input type="text" name="mainAntModel1[' + mwCounter + ']" class="input-medium" /><br />\
					<input type="text" name="mainAntModel2[' + mwCounter + ']" class="input-medium" />\
				</td>\
				<td>\
					<input type="text" name="mainAntHeight1[' + mwCounter + ']" class="input-mini number" /><br />\
					<input type="text" name="mainAntHeight2[' + mwCounter + ']" class="input-mini number" />\
				</td>\
				<td>\
					<?php $lookup = get_instance()->core->get_lookup('antenna_diameter') ?>\
					<select name="mainAntDiameter1[' + mwCounter + ']">\
						<option value="0">- Choose one -</option>\
						<?php foreach ($lookup as $lo) { ?>\
						<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
						<?php } ?>\
					</select><br />\
					<select name="mainAntDiameter2[' + mwCounter + ']">\
						<option value="0">- Choose one -</option>\
						<?php foreach ($lookup as $lo) { ?>\
						<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
						<?php } ?>\
					</select>\
				</td>\
				<td>\
					<input type="text" name="sdAntModel1[' + mwCounter + ']" class="input-medium" /><br />\
					<input type="text" name="sdAntModel2[' + mwCounter + ']" class="input-medium" />\
				</td>\
				<td>\
					<input type="text" name="sdAntHeight1[' + mwCounter + ']" class="input-mini number" /><br />\
					<input type="text" name="sdAntHeight2[' + mwCounter + ']" class="input-mini number" />\
				</td>\
				<td>\
					<?php $lookup = get_instance()->core->get_lookup('antenna_diameter') ?>\
					<select name="sdAntDiameter1[' + mwCounter + ']">\
						<option value="0">- Choose one -</option>\
						<?php foreach ($lookup as $lo) { ?>\
						<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
						<?php } ?>\
					</select><br />\
					<?php $lookup = get_instance()->core->get_lookup('antenna_diameter') ?>\
					<select name="sdAntDiameter2[' + mwCounter + ']">\
						<option value="0">- Choose one -</option>\
						<?php foreach ($lookup as $lo) { ?>\
						<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
						<?php } ?>\
					</select>\
				</td>\
				<td>\
					<input type="text" name="azimuth1[' + mwCounter + ']" class="input-mini number" /><br />\
					<input type="text" name="azimuth2[' + mwCounter + ']" class="input-mini number" />\
				</td>\
				<td>\
					<select name="eqModel1[' + mwCounter + ']">\
						<option value="0">- Choose one -</option>\
						<?php
						$options = array(0 => '- Choose one -');
						$lookup = $this->core->get_lookup('equipment_model');
						foreach ($lookup as $lo) { ?>\
							<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
							<?php
						}
						?>\
					</select>\
					<br />\
					<select name="eqModel2[' + mwCounter + ']">\
						<option value="0">- Choose one -</option>\
						<?php
						$options = array(0 => '- Choose one -');
						$lookup = $this->core->get_lookup('equipment_model');
						foreach ($lookup as $lo) { ?>\
							<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
							<?php
						}
						?>\
					</select>\
				</td>\
				<td>\
					<select name="polarization1[' + mwCounter + ']">\
						<option value="0">- Choose one -</option>\
						<?php
						$lookup = $this->core->get_lookup('transmission_polarization');
						foreach ($lookup as $lo) { ?>\
							<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
							<?php
						}
					?>\
					</select>\
					<br />\
					<select name="polarization2[' + mwCounter + ']">\
						<option value="0">- Choose one -</option>\
						<?php
						$lookup = $this->core->get_lookup('transmission_polarization');
						foreach ($lookup as $lo) { ?>\
							<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
							<?php
						}
						?>\
					</select>\
				</td>\
				<td>\
					<input type="text" name="reqCapacity1[' + mwCounter + ']" class="input-mini number" onchange="copyPaste(this, \'reqCapacity2\', ' + mwCounter + ')" /><br />\
					<input type="text" name="reqCapacity2[' + mwCounter + ']" class="input-mini number" readonly="readonly" />\
				</td>\
				<td>\
					<input type="text" name="instCapacity1[' + mwCounter + ']" class="input-mini number" onchange="copyPaste(this, \'instCapacity2\', ' + mwCounter + ')" /><br />\
					<input type="text" name="instCapacity2[' + mwCounter + ']" class="input-mini number" readonly="readonly" />\
				</td>\
				<td>\
					<input type="text" name="usedCapacity1[' + mwCounter + ']" class="input-mini number" onchange="copyPaste(this, \'usedCapacity2\', ' + mwCounter + ')" /><br />\
					<input type="text" name="usedCapacity2[' + mwCounter + ']" class="input-mini number" readonly="readonly" />\
				</td>\
				<td>\
					<input type="text" name="treshold1[' + mwCounter + ']" class="input-mini number" onchange="copyPaste(this, \'treshold2\', ' + mwCounter + ')" /><br />\
					<input type="text" name="treshold2[' + mwCounter + ']" class="input-mini number" readonly="readonly" />\
				</td>\
				<td>\
					<input type="text" name="planRSL1[' + mwCounter + ']" class="input-mini number" onchange="copyPaste(this, \'planRSL2\', ' + mwCounter + ')" /><br />\
					<input type="text" name="planRSL2[' + mwCounter + ']" class="input-mini number" readonly="readonly" />\
				</td>\
				<td>\
					<input type="text" name="actualRSL1[' + mwCounter + ']" class="input-mini number" /><br />\
					<input type="text" name="actualRSL2[' + mwCounter + ']" class="input-mini number" />\
				</td>\
				<td>\
					<input type="text" name="fm1[' + mwCounter + ']" class="input-mini number" /><br />\
					<input type="text" name="fm2[' + mwCounter + ']" class="input-mini number" />\
				</td>\
				<td>\
					<input type="text" name="availability1[' + mwCounter + ']" class="input-mini number" onchange="copyPaste(this, \'availability2\', ' + mwCounter + ')" /><br />\
					<input type="text" name="availability2[' + mwCounter + ']" class="input-mini number" readonly="readonly" />\
				</td>\
				<td class="right"><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="TRANSMISSION.removeRow(' + mwCounter + ')"><i></i> Remove</a></td>\
			</tr>\
			<tr id="transmission-separator-' + mwCounter + '">\
				<td colspan="25">&nbsp;</td>\
			</tr>\
		';
		
		$('#tbl-transmission tbody').append(html);
		
		numberFormat.init();
		justNumberFormat.init();
		loadToUpperCase();
		clearAutocomplete();
		//farEnd.init();
		uneditable.init();
		IPAddress.init();
		copyNearEnd();
		
		mwCounter++;
	},
	removeRow: function(row) {
		var mwRowElem = document.getElementById('transmission-row-' + row);
		var mwSeparatorElem = document.getElementById('transmission-separator-' + row);
		
		coolConfirmation('Are you sure you want to delete?', function() {			
			mwRowElem.parentNode.removeChild(mwRowElem);
			mwSeparatorElem.parentNode.removeChild(mwSeparatorElem);
		});
	}
};

var doLookupSiteTransmission = function(elem1, elem2, elemIdx) { // elem1: shown element, elem2: hidden element, elemIdx: index of element
	var url = '<?php echo site_url('/site_info') ?>';
	
	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { cmd: 'GET_LOOKUP_SITE', e: elem2 } }, // e: it used for selected element
		width: 600,
		scrolling: false,
		autoSize: true,
		fitToView: true,
		closeClick: false,
		openEffect: 'fade',
		closeEffect: 'fade',
		afterShow: function() {
			document.getElementById('btnSiteSelectedOK').onclick = function() {
				var row = $('#dg-sites').datagrid('getSelected');
				
				if (row != null) {
					document.getElementsByName(elem1)[0].value = row.site_code;
					document.getElementsByName(elem2)[0].value = row.site_id;
					
					generateLinkId();
					
					// generate Link ID
					/*
					$.ajax({
						url: '<?php echo site_url('/site_info/equipment') ?>',
						method: 'GET',
						data: { cmd: 'GET_LINKID', nearend: document.getElementsByName('siteId')[0].value, farend: row.site_id },
						dataType: 'json',
						success: function(json) {
							document.getElementsByName('linkId[' + elemIdx + ']')[0].value = json.link_id;
						}
					});
					*/
				}
				
				$.fancybox.close();
			};
		}
	});
};

loadToUpperCase();
clearAutocomplete();
//farEnd.init();
numberFormat.init();
justNumberFormat.init();
uneditable.init();
IPAddress.init();
doPreviledgePerfield();
</script>