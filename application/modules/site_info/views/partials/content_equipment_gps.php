<?php echo form_open('', 'id="frmGPS" class="form-horizontal') ?>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> GPS Antenna</h4>
	</div>
	
	<div class="widget-body">
		
		<div style="overflow:auto">
			<table id="tblGPS" class="table table-bordered" style="width:auto">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Model:</th>
						<th>Height:</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ($this->uri->segment(3) == 'edit' && count($gps) > 0) {
						
						$gpsCounter = 1;
						foreach ($gps as $ji_pi_es) {
					?>
					<tr id="gps-row-<?php echo $gpsCounter ?>">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="GPS.removeRow(<?php echo $gpsCounter ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_gps_model', 'active' => 1), 'order');
							$gpsModel = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$gpsModel[$lo['lookup_value']] = $lo['lookup_desc'];
							}
							echo @form_dropdown('gpsModel[]', $gpsModel, set_value('gpsModel[]', $ji_pi_es['ant_gps_model']), 'class="input-medium"');
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('gpsHeight[]', set_value('gpsHeight[]', $ji_pi_es['ant_gps_height']), 'class="input-small number"') ?>
								<span class="add-on">M</span>
							</div>
						</td>
					</tr>
					
					<?php
							$gpsCounter++;
						}
					} else {
					?>
					
					<tr id="gps-row-1">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="GPS.removeRow(1)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_gps_model', 'active' => 1), 'order');
							$gpsModel = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$gpsModel[$lo['lookup_value']] = $lo['lookup_desc'];
							}
							echo @form_dropdown('gpsModel[]', $gpsModel, set_value('gpsModel[]', ''), 'class="input-medium"');
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('gpsHeight[]', set_value('gpsHeight[]', ''), 'class="input-small number"') ?>
								<span class="add-on">M</span>
							</div>
						</td>
					</tr>
					
					<?php
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3" class="left"><a class="btn btn-success btn-small" href="javascript:void(0)" onclick="GPS.addRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></a></td>
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
document.getElementById('frmGPS').onsubmit = function() {
	
	var data = serialize(this);
	data += '&siteId=' + document.getElementsByName('siteId')[0].value;
	
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=ADD_GPS';
	<?php } else { ?>
	data += '&cmd=EDIT_GPS';
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
						href: document.URL + '?load_content=GPS&site_id=' + document.getElementsByName('siteId')[0].value
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

<?php if ($this->uri->segment(3) == 'edit' && count($gps) > 0) { ?>
var gpsCounter = <?php echo $gpsCounter ?>;
<?php } else { ?>
var gpsCounter = 2;
<?php } ?>

var GPS = {	
	addRow: function() {
		var html = '\
			<tr id="gps-row-' + gpsCounter + '">\
				<td class="right"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="GPS.removeRow(' + gpsCounter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>\
				<td>\
					<select name="gpsModel[]" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_gps_model', 'active' => 1), 'order');
						$gpsModel = array(0 => '- Choose one -');
						foreach ($lookup as $lo) {
							echo '<option value="'.$lo['lookup_value'].'">'.$lo['lookup_desc'].'</option>';
						}
						?>\
					</select>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="gpsHeight[]" class="input-small number" />\
						<span class="add-on">M</span>\
					</div>\
				</td>\
			</tr>\
		';
		
		$('#tblGPS tbody').append(html);
		
		numberFormat.init();
		clearAutocomplete();
		
		gpsCounter++;
	},
	removeRow: function(row) {
		var row = document.getElementById('gps-row-' + row);
		
		coolConfirmation('Are you sure you want to delete?', function() {
			row.parentNode.removeChild(row);
		});
	}
};

numberFormat.init();
clearAutocomplete();
doPreviledgePerfield();
</script>