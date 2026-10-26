<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<style>
.table th, .table td { vertical-align:middle; }
</style>

<?php echo form_open('', 'id="frmACPDB" class="form-horizontal"') ?>

<?php echo form_hidden('acpdbId', $acpdb['acpdb_id']) ?>

<div class="widget">	
	<div class="widget-head">
		<h4 class="heading"><i></i> ACPDB Data</h4>
	</div>
	
	<div class="widget-body">
	
		<div class="control-group">
			<label class="control-label" for="capacityR">Actual Load (R/S/T):</label>
			<div class="controls">
				<div class="input-append">
					<?php echo @form_input('capacityR', set_value('capacityR', $acpdb['capacity_r']), 'id="capacityR", class="input-mini number"') ?>
					<?php echo @form_input('capacityS', set_value('capacityS', $acpdb['capacity_s']), 'id="capacityS", class="input-mini number"') ?>
					<?php echo @form_input('capacityT', set_value('capacityT', $acpdb['capacity_t']), 'id="capacityT", class="input-mini number"') ?>
					<span class="add-on">Amp(AC)</span>
				</div>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="mcbCapacityTP">MCB Capacity of TP / kwh:</label>
			<div class="controls">
				<div class="input-append">
					<?php
					$mcbCapacity = array(0 => '- Choose one -');
					$lookup = $this->core->get_lookup('power_capacity');
					foreach ($lookup as $lo) {
						$mcbCapacity[$lo['id']] = $lo['desc'];
					}
					echo @form_dropdown('mcbCapacityTP', $mcbCapacity, set_value('mcbCapacityTP', $acpdb['mcb_capacity_tp']), 'id="mcbCapacityTP"');
					?>
					<span class="add-on">Amp(AC)</span>
				</div>
				<?php
				$mcbPhase = array(0 => '- Choose one -');
				$lookup = $this->core->get_lookup('power_phase');
				foreach ($lookup as $lo) {
					$mcbPhase[$lo['id']] = $lo['desc'];
				}
				echo @form_dropdown('mcbCapacity', $mcbPhase, set_value('mcbCapacity', $acpdb['mcb_capacity']));
				?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="mcbCapacityACPDB">MCB Capacity of ACPDB:</label>
			<div class="controls">
				<div class="input-append">
					<?php
					$mcbCapacity = array(0 => '- Choose one -');
					$lookup = $this->core->get_lookup('power_capacity');
					foreach ($lookup as $lo) {
						$mcbCapacity[$lo['id']] = $lo['desc'];
					}
					echo @form_dropdown('mcbCapacityACPDB', $mcbCapacity, set_value('mcbCapacityACPDB', $acpdb['mcb_capacity_acpdb']), 'id="mcbCapacityACPDB"');
					?>
					<span class="add-on">Amp(AC)</span>
				</div>
				<?php
				$mcbPhase = array(0 => '- Choose one -');
				$lookup = $this->core->get_lookup('power_phase');
				foreach ($lookup as $lo) {
					$mcbPhase[$lo['id']] = $lo['desc'];
				}
				echo @form_dropdown('mcbCapacity2', $mcbPhase, set_value('mcbCapacity2', $acpdb['acpdb_mcb_capacity']));
				?>
			</div>
		</div>
		
	</div>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> Photo(s)</h4>
	</div>
	
	<div class="widget-body">
	
		<table id="tblPhoto" class="table table-bordered table-condensed table-striped" style="width:auto">
			<thead>
				<tr>
					<th width="200" class="center">Type</th>
					<th class="center">Photo</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>				
				<?php
				$lookup = $this->core->get_lookup('power_acpdb_photo_type');
				$photos = array();
				foreach ($lookup as $lo) {
					$thumb = base_url('/file/upload/site-info/no_image-60x60.jpg');
					$image = 'javascript:void(0)';
					$form_hidden = form_hidden('photoACPDB'.$lo['id']);
					
					if ($this->uri->segment(3) == 'edit' && isset($acpdb['acpdb_id'])) {
						if ( $photo = $this->core->view_row('t_mtr_site_l_acpdb_photo', array('acpdb_id' => $acpdb['acpdb_id'], 'type_id' => $lo['id'])) ) {
							if (file_exists($power_dir.'/'.$photo['photo_name']) && is_file($power_dir.'/'.$photo['photo_name'])) {
								$thumb = $this->image_model->resize($power_dir.'/'.$photo['photo_name'], 60, 60, 'crop');
								$image = $power_url.'/'.$photo['photo_name'];
								$form_hidden = form_hidden('photoACPDB'.$lo['id'], $photo['photo_name']);
							}
						}
					}
					
					$photos[] = array(
						'title' => $lo['desc'],
						'image' => '<a id="photo-acpdb-'.$lo['id'].'-box" href="'.$image.'" class="photobox"><img id="photo-acpdb-'.$lo['id'].'-thumb" src="'.$thumb.'" class="img-polaroid" /></a>',
						'form_hidden' => $form_hidden,
						'btn_browse' => '<a id="photo-acpdb-'.$lo['id'].'-browse" class="btn btn-small" style="inline-block">Browse</a>',
						'btn_delete' => '<a id="photo-acpdb-'.$lo['id'].'-delete" class="btn btn-small btn-danger" style="inline-block">Delete</a>',
					
						'name_form_hidden' => 'photoACPDB'.$lo['id'],
						'id_image' => 'photo-acpdb-'.$lo['id'].'-thumb',
						'id_href_lightbox' => 'photo-acpdb-'.$lo['id'].'-box',
						'id_href_browse' => 'photo-acpdb-'.$lo['id'].'-browse',
						'id_href_delete' => 'photo-acpdb-'.$lo['id'].'-delete'
					);
				}
				
				foreach ($photos as $photo) {
				?>
				<tr>
					<td><?php echo $photo['title'] ?></td>
					<td>
						<?php echo $photo['image'] ?>
						<?php echo $photo['form_hidden'] ?>
					</td>
					<td>
						<?php echo $photo['btn_browse'] ?>
						<?php echo $photo['btn_delete'] ?>
					</td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	
	</div>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> Detail ACPDB</h4>
	</div>
	
	<div class="widget-body">
		<div style="overflow:auto">
			<table id="tbl-detail-acpdb" class="table table-bordered" style="width:auto">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th class="center">Item(s)</th>
						<th class="center">Capacity</th>
						<th class="center">Actual Capacity</th>
						<th class="center">Phase Used</th>
						<th class="center">Allocation</th>
						<th class="center">Linked to Rectifier No.</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ($this->input->post('act') == 'edit' && count($acpdb['detail']) > 0) {
						$acpdb_count = 1;
						
						foreach ($acpdb['detail'] as $detail) { ?>
							
					<tr id="row-acpdb-<?php echo $acpdb_count ?>">
						<td class="left"><a class="btn btn-small btn-danger" href="javascript:void(0)" onclick="ACPDB.removeRow(<?php echo $acpdb_count ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							$items = array(0 => '- Choose one -');
							$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_mcb_item', 'active' => 1), 'order');
							foreach ($lookup as $lo) {
								$items[$lo['lookup_value']] = $lo['lookup_desc'];
							}
							echo @form_dropdown('item[]',  $items, set_value('item[]', $detail['item']));
							?>
						</td>
						<td>
							<div class="input-append">
								<?php
								$capacity = array(0 => '- Choose one -');
								$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_capacity', 'active' => 1), 'order');
								foreach ($lookup as $lo) {
									$capacity[$lo['lookup_value']] = $lo['lookup_desc'];
								}
								echo @form_dropdown('capacity[]', $capacity, set_value('capacity[]', $detail['capacity']));
								?>
								<span class="add-on">Amp(AC)</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('capacityActual[]', set_value('capacityActual[]', $detail['capacity_actual']), 'class="input-small number"') ?>
								<span class="add-on">Amp(AC)</span>
							</div>
						</td>
						<td>
							<?php
								$phaseUsed = array(0 => '- Choose one -');
								$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_phase', 'active' => 1), 'order');
								foreach ($lookup as $lo) {
									$phaseUsed[$lo['lookup_value']] = $lo['lookup_desc'];
								}
								echo @form_dropdown('phaseUsed[]', $phaseUsed, set_value('phaseUsed[]', $detail['phase_used']));
							?>
						</td>
						<td>
							<?php
								$allocation = array(0 => '- Choose one -');
								$data_allocation = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_allocation', 'active' => 1), 'order');
								foreach ($data_allocation as $allo) {
									$allocation[$allo['lookup_value']] = $allo['lookup_desc'];
								}
								echo @form_dropdown('allocation[]', $allocation, set_value('allocation[]', $detail['allocation']));
							?>
						</td>
						<td>
							<?php
							$lookup = $this->core->get_lookup('rectifier_number');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo form_dropdown('linkedToRectifierNo[]', $options, $detail['linked_to_rectifier_no']);
							?>
						</td>
					</tr>
							<?php 
							$acpdb_count++;
						}
					
					} else { ?>
					
					<tr id="row-acpdb-1">
						<td class="left"><a class="btn btn-small btn-danger" href="javascript:void(0)" onclick="ACPDB.removeRow(1)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							$items = array(0 => '- Choose one -');
							$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_mcb_item', 'active' => 1), 'order');
							foreach ($lookup as $lo) {
								$items[$lo['lookup_value']] = $lo['lookup_desc'];
							}
							echo @form_dropdown('item[]',  $items, set_value('item[]', ''));
							?>
						</td>
						<td>
							<div class="input-append">
								<?php
								$capacity = array(0 => '- Choose one -');
								$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_capacity', 'active' => 1), 'order');
								foreach ($lookup as $lo) {
									$capacity[$lo['lookup_value']] = $lo['lookup_desc'];
								}
								echo @form_dropdown('capacity[]', $capacity, set_value('capacity[]', ''));
								?>
								<span class="add-on">Amp(AC)</span>
							</div>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('capacityActual[]', '', 'class="input-small number"') ?>
								<span class="add-on">Amp(AC)</span>
							</div>
						</td>
						<td>
							<?php
								$phaseUsed = array(0 => '- Choose one -');
								$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_phase', 'active' => 1), 'order');
								foreach ($lookup as $lo) {
									$phaseUsed[$lo['lookup_value']] = $lo['lookup_desc'];
								}
								echo @form_dropdown('phaseUsed[]', $phaseUsed, set_value('phaseUsed[]', ''));
							?>
						</td>
						<td>
							<?php
								$allocation = array(0 => '- Choose one -');
								$data_allocation = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_allocation', 'active' => 1), 'order');
								foreach ($data_allocation as $allo) {
									$allocation[$allo['lookup_value']] = $allo['lookup_desc'];
								}
								echo @form_dropdown('allocation[]', $allocation, set_value('allocation[]', ''));
							?>
						</td>
						<td>
							<?php
							$lookup = $this->core->get_lookup('rectifier_number');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo form_dropdown('linkedToRectifierNo[]', $options);
							?>
						</td>
					</tr>
					<?php } ?>
					
				</tbody>
				<tfoot>
					<tr>
						<td colspan="7" class="left"><a class="btn btn-small btn-success" href="javascript:void(0)" onclick="ACPDB.addRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></a></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<div class="form-actions">
	<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
	<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$('#frame-acpdb-list').show(); GRID_ACPDB.init(); $('#frame-acpdb-form').html(''); window.scroll(0, 0);"><i></i>Cancel</button>
</div>

<?php echo form_close() ?>

<script>
document.getElementById('frmACPDB').onsubmit = function() {

	var data = serialize(this);
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=ADD_ACPDB';
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	data += '&cmd=EDIT_ACPDB';
	<?php } ?>
	data += '&act=SAVE';
	data += '&siteId=' + document.getElementsByName('siteId')[0].value;
	
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
					$('#frame-acpdb-form').html('');
					$('#frame-acpdb-list').show();
					
					GRID_ACPDB.params.site_id = document.getElementsByName('siteId')[0].value;
					GRID_ACPDB.init();
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

var UPLOAD_PHOTO_ACPDB = {
	init: function(elem, nameFileElem) {
		<?php if ($this->uri->segment(3) == 'add') { ?>
		var cmd = 'ADD_ACPDB';
		<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
		var cmd = 'EDIT_ACPDB';
		<?php } ?>
		
		new AjaxUpload(elem, {
			action: '<?php echo site_url($this->uri->uri_string()) ?>',
			name: nameFileElem,
			//data: { cmd: cmd, act: 'UPLOAD', file: file },
			autoSubmit: true,
			responseType: 'json',
			onChange: function(file, extension) {},
			onSubmit: function(file, extension) {
				image = document.getElementsByName(nameFileElem)[0].value;
				if (image.split('.').pop() != extension) {
					image = '';
				}
				
				this.setData({ cmd: cmd, act: 'UPLOAD', image: image });
				
				$.blockUI({ message: 'Uploading, please wait...', css: {
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
				if ( ! json.error) {
					<?php foreach ($photos as $photo) { ?>
						if (json.type == '<?php echo $photo['name_form_hidden'] ?>') {
							document.getElementsByName('<?php echo $photo['name_form_hidden']?>')[0].value = json.file;
							document.getElementById('<?php echo $photo['id_image'] ?>').src = json.thumb;
							document.getElementById('<?php echo $photo['id_href_lightbox'] ?>').href = json.image;
							
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').innerHTML = 'Replace';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').style.display = 'inline-block';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').setAttribute('onclick', 'UPLOAD_PHOTO_ACPDB.deletePhoto(\'' + json.file + '\', \'' + json.type + '\')');
							}
					<?php } ?>
					
				} else {
					coolNotification(json.message, 'error');
				}
				
				$.unblockUI();
			}
		});
		
		<?php foreach ($photos as $photo) { ?>
			if (document.getElementsByName('<?php echo $photo['name_form_hidden'] ?>')[0].value != '') {
				document.getElementById('<?php echo $photo['id_href_delete'] ?>').setAttribute('onclick', 'UPLOAD_PHOTO_ACPDB.deletePhoto(\'' + document.getElementsByName('<?php echo $photo['name_form_hidden'] ?>')[0].value + '\', \'<?php echo $photo['name_form_hidden'] ?>\')');
				document.getElementById('<?php echo $photo['id_href_browse'] ?>').innerHTML = 'Replace';
			} else {
				document.getElementById('<?php echo $photo['id_href_delete'] ?>').style.display = 'none';
				document.getElementById('<?php echo $photo['id_href_delete'] ?>').removeAttribute('onclick');
			}
		<?php } ?>
		
		$('.photobox').fancybox({
			openEffect: 'elastic',
			closeEffect: 'elastic',
			closeBtn: true
		});
	},
	deletePhoto: function(photoName, photoType) {
		
		coolConfirmation('Are you sure you want to delete?', function() {
		
			<?php if ($this->uri->segment(3) == 'add') { ?>
			var cmd = 'ADD_ACPDB';
			<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
			var cmd = 'EDIT_ACPDB';
			<?php } ?>
			
			$.ajax({
				url: '<?php echo site_url($this->uri->uri_string()) ?>',
				type: 'POST',
				data: { cmd: cmd, act: 'DELETE_PHOTO', photo_name: photoName, photo_type: photoType },
				dataType: 'json',
				success: function(json) {
					<?php foreach ($photos as $photo) { ?>
						if (json.type == '<?php echo $photo['name_form_hidden'] ?>') {
							document.getElementsByName('<?php echo $photo['name_form_hidden'] ?>')[0].value = '';
							document.getElementById('<?php echo $photo['id_image'] ?>').src = '<?php echo base_url('/file/upload/site-info/no_image-60x60.jpg') ?>';
							document.getElementById('<?php echo $photo['id_href_lightbox'] ?>').href = 'javascript:void(0)';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').style.display = 'none';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').removeAttribute('onclick');
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').style.display = 'inline-block';
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').innerHTML = 'Browse';
						}
					<?php } ?>
				}
			});
		});
	}
};

<?php if ($this->uri->segment(3) == 'edit' && count($acpdb['detail']) > 0) { ?>
var acpdbCounter = <?php echo $acpdb_count ?>;
<?php } else { ?>
var acpdbCounter = 2;
<?php } ?>

var ACPDB = {
	addRow: function() {
		var html = '\
			<tr id="row-acpdb-' + acpdbCounter + '">\
				<td class="left"><a class="btn btn-small btn-danger" href="javascript:void(0)" onclick="ACPDB.removeRow(' + acpdbCounter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>\
				<td>\
					<select name="item[]">\
						<option value="0">- Choose one -</option>\
						<?php
						$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_mcb_item', 'active' => 1), 'order');
						foreach ($lookup as $lo) { ?>\
							<option value="<?php echo $lo['lookup_value'] ?>"><?php echo $lo['lookup_desc'] ?></option>\
							<?php
						} ?>\
					</select>\
				</td>\
				<td>\
					<div class="input-append">\
						<select name="capacity[]">\
							<option value="0">- Choose one -</option>\
							<?php
							$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_capacity', 'active' => 1), 'order');
							foreach ($lookup as $lo) { ?>\
								<option value="<?php echo $lo['lookup_value'] ?>"><?php echo $lo['lookup_desc'] ?></option>\
								<?php
							} ?>\
						</select>\
						<span class="add-on">Amp(AC)</span>\
					</div>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="capacityActual[]" class="input-small number" />\
						<span class="add-on">Amp(AC)</span>\
					</div>\
				</td>\
				<td>\
					<select name="phaseUsed[]">\
						<option value="0">- Choose one -</option>\
						<?php
						$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_phase', 'active' => 1), 'order');
						foreach ($lookup as $lo) { ?>\
							<option value="<?php echo $lo['lookup_value'] ?>"><?php echo $lo['lookup_desc'] ?></option>\
							<?php
						} ?>\
					</select>\
				</td>\
				<td>\
					<select name="allocation[]">\
						<option value="0">- Choose one -</option>\
						<?php
						$data_allocation = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'power_allocation', 'active' => 1), 'order');
						foreach ($data_allocation as $allo) { ?>\
							<option value="<?php echo $allo['lookup_value'] ?>"><?php echo $allo['lookup_desc'] ?></option>\
							<?php
						} ?>\
					</select>\
				</td>\
				<td>\
					<select name="linkedToRectifierNo[]">\
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
		
		$('#tbl-detail-acpdb tbody').append(html);
		
		numberFormat.init();
		clearAutoComplete.init();
		
		acpdbCounter++;
	},
	removeRow: function(row) {
		var row = document.getElementById('row-acpdb-' + row);
		
		coolConfirmation('Are you sure you want to delete?', function() {			
			row.parentNode.removeChild(row);
		});
	}
};

numberFormat.init();
clearAutoComplete.init();

<?php foreach ($photos as $photo) { ?>
UPLOAD_PHOTO_ACPDB.init(document.getElementById('<?php echo $photo['id_href_browse'] ?>'), '<?php echo $photo['name_form_hidden'] ?>');
<?php } ?>
</script>