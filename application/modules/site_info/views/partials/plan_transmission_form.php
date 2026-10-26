<?php echo form_open('', 'id="frmTransmission" class="form-horizontal"') ?>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> Transmission Data</h4>
	</div>
	
	<div class="widget-body">
	
		<div style="overflow:auto">
			<table id="tblTRANSM" class="table table-bordered" style="width:auto">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Far End:</th>
						<th>Link Type:</th>
						<th>Main Ant. Height:</th>
						<th>Main Ant. Diameter:</th>
						<th>Frequency:</th>
						<th>Equipment Model:</th>
						<th>Capacity:</th>
						<th>Owner:</th>
					</tr>
				</thead>
				<tbody>
					<?php if ( $this->uri->segment(3) == 'edit' && count($transm) > 0 || $this->core->view_row('t_mtr_site_plan_mw', array('site_plan_id' => (int) $this->input->get('site_id'))) ) {
						
						$mwCounter = 1;
						foreach ($transm as $mw) {
					?>
					
					<tr id="transm-row-<?php echo $mwCounter ?>">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="TRANSMISSION.removeRow(<?php echo $mwCounter ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php echo @form_hidden('id['.$mwCounter.']', $mw['site_plan_mw_id']) ?>
							
							<div class="input-append">
								<?php echo @form_input('farEndCode['.$mwCounter.']', $mw['site_code'], 'onfocus="doLookupTransm(\'farEndCode['.$mwCounter.']\', \'farEnd['.$mwCounter.']\', '.$mwCounter.')" class="input-medium uneditable"') ?>
								<?php echo @form_hidden('farEnd['.$mwCounter.']', $mw['site_id_fe']) ?>
								<button type="button" onclick="doLookupTransm('farEndCode[<?php echo $mwCounter ?>]', 'farEnd[<?php echo $mwCounter ?>]', <?php echo $mwCounter ?>)" class="btn"><i class="icon-search"></i></button>
							</div>
						</td>
						<td>
							<?php
							$options = array(0 => '- Choose one -');
							$lookup = $this->core->get_lookup('transmission_link_type');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('linkType['.$mwCounter.']', $options, $mw['link_type'], 'class="input-medium"');									
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('antHeight['.$mwCounter.']', $mw['ant_height'], 'class="input-small number"') ?>
								<span class="add-on">M</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('antDiameter['.$mwCounter.']', $mw['ant_diameter'], 'class="input-small number"') ?>
								<span class="add-on">M</span>
							</div>
						</td>
						<td>
							<?php echo @form_input('antFrequency['.$mwCounter.']', $mw['frequency'], 'class="input-small number"') ?>							
						</td>
						<td>
							<?php
							$options = array(0 => '- Choose one -');
							$lookup = $this->core->get_lookup('equipment_model');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('eqModel['.$mwCounter.']', $options, $mw['equip_model'], 'class="input-medium"');									
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('antCapacity['.$mwCounter.']', $mw['capacity'], 'class="input-small number"') ?>
								<span class="add-on">MB</span>
							</div>
						</td>
						<td>
							<?php echo @form_input('antOwner['.$mwCounter.']', $mw['owner'], 'class="input-medium"') ?>							
						</td>
					</tr>
					
					<?php
							$mwCounter++;
						}
					
					} else { ?>
					
					<tr id="transm-row-1">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="TRANSMISSION.removeRow(1)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php echo form_hidden('id[1]') ?>
							
							<div class="input-append">
								<?php echo @form_input('farEndCode[1]', '', 'onfocus="doLookupTransm(\'farEndCode[1]\', \'farEnd[1]\', 1)" class="input-medium uneditable"') ?>
								<?php echo @form_hidden('farEnd[1]') ?>
								<button type="button" onclick="doLookupTransm('farEndCode[1]', 'farEnd[1]', 1)" class="btn"><i class="icon-search"></i></button>
							</div>
						</td>
						<td>
							<?php
							$options = array(0 => '- Choose one -');
							$lookup = $this->core->get_lookup('transmission_link_type');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('linkType[1]', $options, '', 'class="input-medium"');									
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('antHeight[1]', '', 'class="input-small number"') ?>
								<span class="add-on">M</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('antDiameter[1]', '', 'class="input-small number"') ?>
								<span class="add-on">M</span>
							</div>
						</td>
						<td>
							<?php echo @form_input('antFrequency[1]', '', 'class="input-small number"') ?>							
						</td>
						<td>
							<?php
							$options = array(0 => '- Choose one -');
							$lookup = $this->core->get_lookup('equipment_model');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('eqModel[1]', $options, '', 'class="input-medium"');									
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('antCapacity[1]', '', 'class="input-small number"') ?>
								<span class="add-on">MB</span>
							</div>
						</td>
						<td>
							<?php echo @form_input('antOwner[1]', '', 'class="input-medium"') ?>							
						</td>
					</tr>
					
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="9" class="left"><a class="btn btn-success btn-small" href="javascript:void(0)" onclick="TRANSMISSION.addRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></a></td>
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
document.getElementById('frmTransmission').onsubmit = function() {

	var data = $(this).serialize();
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=ADD_TRANSMISSION';
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	data += '&cmd=EDIT_TRANSMISSION';
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
							href: document.URL + '?load_content=TRANSMISSION&site_id=' + json.site_id
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

var doLookupTransm = function(elem1, elem2, elemIdx) { // elem1: shown element, elem2: hidden element, elemIdx: index of element
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
					
					// generate Link ID
					/* $.ajax({
						url: '<?php echo site_url('/site_info/equipment') ?>',
						method: 'GET',
						data: { cmd: 'GET_LINKID', nearend: document.getElementsByName('siteId')[0].value, farend: row.site_id },
						dataType: 'json',
						success: function(json) {
							document.getElementsByName('linkId[' + elemIdx + ']')[0].value = json.link_id;
						}
					}); */
				}
				
				$.fancybox.close();
			};
		}
	});
};

<?php if ( $this->uri->segment(3) == 'edit' && count($transm) > 0 || $this->core->view_row('t_mtr_site_plan_mw', array('site_plan_id' => (int) $this->input->get('site_id'))) ) { ?>
var mwCounter = <?php echo $mwCounter ?>;
<?php } else { ?>
var mwCounter = 2;
<?php } ?>
		
var TRANSMISSION = {
	
	addRow: function() {
	
		var html = '\
			<tr id="transm-row-' + mwCounter + '">\
				<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="TRANSMISSION.removeRow(' + mwCounter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>\
				<td>\
					<input type="hidden" name="id[' + mwCounter + ']" />\
					\
					<div class="input-append">\
						<input type="text" name="farEndCode[' + mwCounter + ']" onfocus="doLookupTransm(\'farEndCode[' + mwCounter + ']\', \'farEnd[' + mwCounter + ']\', ' + mwCounter + ')" class="input-medium uneditable" />\
						<input type="hidden" name="farEnd[' + mwCounter + ']" />\
						<button type="button" onclick="doLookupTransm(\'farEndCode[' + mwCounter + ']\', \'farEnd[' + mwCounter + ']\', ' + mwCounter + ')" class="btn"><i class="icon-search"></i></button>\
					</div>\
				</td>\
				<td>\
					<select name="linkType[' + mwCounter + ']" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$lookup = $this->core->get_lookup('transmission_link_type');
						foreach ($lookup as $lo) {
						?>\
						<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
						<?php
						}
						?>\
					</select>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="antHeight[' + mwCounter + ']" class="input-small number" />\
						<span class="add-on">M</span>\
					</div>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="antDiameter[' + mwCounter + ']" class="input-small number" />\
						<span class="add-on">M</span>\
					</div>\
				</td>\
				<td>\
					<input type="text" name="antFrequency[' + mwCounter + ']" class="input-small number" />\
				</td>\
				<td>\
					<select name="eqModel[' + mwCounter + ']" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$lookup = $this->core->get_lookup('equipment_model');
						foreach ($lookup as $lo) {
						?>\
						<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
						<?php
						}
						?>\
					</select>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="antCapacity[' + mwCounter + ']" class="input-small number" />\
						<span class="add-on">MB</span>\
					</div>\
				</td>\
				<td>\
					<input type="text" name="antOwner[' + mwCounter + ']" class="input-medium" />\
				</td>\
			</tr>\
		';
		
		$('#tblTRANSM tbody').append(html);
		
		numberFormat.init();
		TEXT.uneditable();
		TEXT.clearAutoComplete();
		
		mwCounter++;
	},
	removeRow: function(row) {
		var row = document.getElementById('transm-row-' + row);
		coolConfirmation('Are you sure you want to delete?', function() {
			row.parentNode.removeChild(row);
		});
	}
};

numberFormat.init();
TEXT.uneditable();
TEXT.clearAutoComplete();
doPreviledgePerfield();
</script>