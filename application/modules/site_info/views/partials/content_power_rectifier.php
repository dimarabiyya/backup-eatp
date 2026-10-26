<style>
.table-primary thead th { font-size:12px; }
th a { padding:0 20px 0 0; }
th a, th a:focus { color:#FFFFFF; text-decoration:underline; }
th a.sort-asc { background:url('<?php echo base_url('/themes/sims/images/asc.png') ?>') no-repeat right center; }
th a.sort-desc { background:url('<?php echo base_url('/themes/sims/images/desc.png') ?>') no-repeat right center; }
</style>

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

<!-- start: rectifier header -->
<div id="rectifier-header-box" style="display:none">

	<?php echo form_open_multipart('', 'id="frmRectifier" class="form-horizontal"') ?>
	<?php echo form_hidden('rectifierId') ?>

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading"><i></i> Rectifier Data</h4>
		</div>
		
		<div class="widget-body">
	
			<div class="control-group" id="form_rectifier_no">
				<label class="control-label" for="rectifierNo">Rectifier No.:</label>
				<div class="controls form-inline">
					<?php
					$lookup = $this->core->get_lookup('rectifier_number');
					$options = array(0 => '- Choose one -');
					foreach ($lookup as $lo) {
						$options[$lo['id']] = $lo['desc'];
					}
					
					echo @form_dropdown('rectifierNo', $options, '', 'id="rectifierNo"');
					?>
				</div>
			</div>
			
			<div class="control-group" id="form_rectifier_slot_used">
				<label class="control-label">Slot used for Rectifier Module:</label>
				<div class="controls form-inline">
					<?php
					$lookup = $this->core->get_lookup('power_rectifier_slot');
					$i = 1;
					foreach ($lookup as $lo) { ?>
						<div class="span1" style="margin-left:0px;"><label class="checkbox inline"><?php echo @form_checkbox('slotUsed[]', set_value('slotUsed[]', $lo['id'])) ?> <?php echo $lo['desc'] ?></label></div>
						<?php
						if ($i % 4 == 0) {
							echo '<div class="clearfix"></div>';
						}
						$i++;
					}
					?>
				</div>
			</div>

			<div class="control-group" id="form_rectifier_dc_power">
				<label class="control-label" for="capacityR">DC Power:</label>
				<div class="controls">
					<?php
					$dcPower = array(0 => '- Choose one -');
					$lookup = $this->core->get_lookup('power_dc');
					foreach ($lookup as $lo) {
						$dcPower[$lo['id']] = $lo['desc'];
					}
					echo @form_dropdown('dcPower', $dcPower, set_value('dcPower', ''), 'id="dcPower"');
					?>
				</div>
			</div>

			<div class="control-group" id="form_rectifier_dcdc_power_capacity">
				<label class="control-label" for="capacityR">DC DC Power Capacity:</label>
				<div class="controls">
					<div class="input-append">
						<?php echo @form_input('dcdcPowerCapacity', '', 'id="dcdcPowerCapacity" class="input-small number"') ?>
						<span class="add-on">Amp</span>
					</div>
				</div>
			</div>

			<div class="control-group" id="form_rectifier_main_mcb_capacity">
				<label class="control-label" for="capacityR">Main MCB Capacity:</label>
				<div class="controls">
					<div class="input-append">
						<?php
						$mcbCapacity = array(0 => '- Choose one -');
						$lookup = $this->core->get_lookup('power_capacity');
						foreach ($lookup as $lo) {
							$mcbCapacity[$lo['id']] = $lo['desc'];
						}
						echo @form_dropdown('mcbCapacityTP', $mcbCapacity, set_value('mcbCapacityTP', ''), 'id="mcbCapacityTP"');
						?>
						<span class="add-on">Amp(AC)</span>
					</div>
					<?php
					$mcbPhase = array(0 => '- Choose one -');
					$lookup = $this->core->get_lookup('power_phase');
					foreach ($lookup as $lo) {
						$mcbPhase[$lo['id']] = $lo['desc'];
					}
					echo @form_dropdown('mcbCapacity', $mcbPhase, set_value('mcbCapacity', ''), 'id="mcbCapacity"');
					?>
				</div>
			</div>

			<div class="control-group" id="form_rectifier_capacity_r">
				<label class="control-label" for="capacityR">Actual Load (R/S/T):</label>
				<div class="controls">
					<div class="input-append">
						<?php echo @form_input('capacityR', set_value('capacityR', ''), 'id="capacityR" class="input-mini number"') ?>
						<?php echo @form_input('capacityS', set_value('capacityS', ''), 'id="capacityS" class="input-mini number"') ?>
						<?php echo @form_input('capacityT', set_value('capacityT', ''), 'id="capacityT" class="input-mini number"') ?>
						<span class="add-on">Amp(AC)</span>
					</div>
				</div>
			</div>
			
		</div>
	</div>

	<div class="widget">	
		<div class="widget-head">
			<h4 class="heading"><i></i> Photo(s)</h4>
		</div>
		
		<div class="widget-body">
		
			<table class="table table-bordered table-condensed table-striped" style="width:auto">
				<thead>
					<tr>
						<th width="200" class="center">Type</th>
						<th class="center">Photo</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$lookup = $this->core->get_lookup('power_rectifier_photo_type');
					$photos = array();
					foreach ($lookup as $lo) {
						$photos[] = array(
							'title' => $lo['desc'],
							'image' => '<a id="photo-rectifier-'.$lo['id'].'-box" href="javascript:void(0)" class="photobox"><img id="photo-rectifier-'.$lo['id'].'-thumb" src="'.base_url('/file/upload/site-info/no_image-60x60.jpg').'" class="img-polaroid" /></a>',
							'form_hidden' => form_hidden('photoRectifier'.$lo['id']),
							'btn_browse' => '<a id="photo-rectifier-'.$lo['id'].'-browse" class="btn btn-mini" style="inline-block">Browse</a>',
							'btn_delete' => '<a id="photo-rectifier-'.$lo['id'].'-delete" class="btn btn-mini btn-danger" style="inline-block">Delete</a>',
						
							'name_form_hidden' => 'photoRectifier'.$lo['id'],
							'id_image' => 'photo-rectifier-'.$lo['id'].'-thumb',
							'id_href_lightbox' => 'photo-rectifier-'.$lo['id'].'-box',
							'id_href_browse' => 'photo-rectifier-'.$lo['id'].'-browse',
							'id_href_delete' => 'photo-rectifier-'.$lo['id'].'-delete'
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

	<div class="form-actions">
		<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
		<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$('#btn-rectifier-addnew').show(); $('#rectifier-header-box').hide(); GRID_RECTIFIER.resetForm(); window.scroll(0, 0);"><i></i>Cancel</button>
	</div>

	<?php echo form_close() ?>
	
</div>
<!-- end: rectifier header -->

<!-- start: rectifier detail -->
<div id="rectifier-detail-box" style="display:none"></div>
<!-- end: rectifier detail -->

<!-- start: datagrid Rectifier -->
<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> Rectifiers</h4>
	</div>
	
	<div class="widget-body">
		
		<div class="heading-buttons">
			<div class="buttons" style="margin-right:0px">
				<a id="btn-rectifier-addnew" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)" onclick="$('#rectifier-header-box').show(); $(this).hide(); GRID_RECTIFIER.resetForm();"><i></i> Add New</a>
			</div>
		</div>
		<div class="separator top"></div>
		
		<div id="tb-rectifier" class="form-search">
			<div class="input-append">
				<?php echo form_input('query-rectifier', '', 'id="query-rectifier" class="input-medium search-query" placeholder="Search..."') ?>
				<button class="btn" onclick="GRID_RECTIFIER.search()"><i class="icon-search"></i></button>
			</div>
		</div>
		
		<table style="height:450px" id="grid-rectifier" class="jgrid"></table>
	
	</div>
</div>
<!-- end: datagrid Rectifier -->

<script>
document.getElementById('frmRectifier').onsubmit = function() {

	var data = serialize(this);
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=ADD_RECTIFIER';
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	data += '&cmd=EDIT_RECTIFIER';
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
				coolNotification(json['message'], 'success', function() {
				
					GRID_RECTIFIER.resetForm();
					GRID_RECTIFIER.params.cmd = 'GET_DATAGRID_RECTIFIER';
					GRID_RECTIFIER.params.site_id = document.getElementsByName('siteId')[0].value;
					GRID_RECTIFIER.init();
					
					$('#rectifier-header-box').hide();
					$('#btn-rectifier-addnew').show();
				
					//window.location.href = powerURL;
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

var UPLOAD_PHOTO_RECTIFIER = {
	init: function(elem, nameFileElem) {
		<?php if ($this->uri->segment(3) == 'add') { ?>
		var cmd = 'ADD_RECTIFIER';
		<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
		var cmd = 'EDIT_RECTIFIER';
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
						if (json.type == '<?php echo $photo['name_form_hidden']?>') {
							document.getElementsByName('<?php echo $photo['name_form_hidden']?>')[0].value = json.file;
							document.getElementById('<?php echo $photo['id_image'] ?>').src = json.thumb;
							document.getElementById('<?php echo $photo['id_href_lightbox'] ?>').href = json.image;
							
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').style.display = 'inline-block'
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').innerHTML = 'Replace'
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').style.display = 'inline-block';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').setAttribute('onclick', 'UPLOAD_PHOTO_RECTIFIER.deletePhoto(\'' + json.file + '\', \'' + json.type + '\')');
						} else {
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').style.display = 'inline-block';
						}
					<?php } ?>
				} else {
					alert(json.message);
				}
				
				$.unblockUI();
			}
		});
		
		$('.photobox').fancybox({
			openEffect: 'elastic',
			closeEffect: 'elastic',
			closeBtn: true
		});
	},
	deletePhoto: function(photoName, photoType) {
		
		coolConfirmation('Are you sure you want to delete?', function() {
		
			<?php if ($this->uri->segment(3) == 'add') { ?>
			var cmd = 'ADD_RECTIFIER';
			<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
			var cmd = 'EDIT_RECTIFIER';
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
							
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').innerHTML = 'Browse';
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').style.display = 'inline-block';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').style.display = 'none';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').removeAttribute('onclick');
						}
					<?php } ?>
				}
			});
		});
	}
};

var doLookupSiteDetailRectifier = function(elem1, elem2, elemIdx) { // elem1: shown element, elem2: hidden element, elemIdx: index of element
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
				}
				
				$.fancybox.close();
			};
		}
	});
};

var GRID_RECTIFIER = {
	params: {
		cmd: 'GET_DATAGRID_RECTIFIER',
		site_id: document.getElementsByName('siteId')[0].value
	},
	loadData: function() {
		$('#grid-rectifier').datagrid({
			url: '<?php echo site_url('/site_info/power') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'rectifier_id', hidden: true },
				{ field: 'rectifier_no', title: 'Rectifier No.', width: 100, sortable: true },
				{ field: 'slot_used', title: 'Slot used for Rectifier module', width: 180, sortable: true },
				{ field: 'dc_power', title: 'DC Power', width: 100, sortable: true, align: 'right' },
				{ field: 'dcdc_power_capacity', title: 'DC DC Power Capacity', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? number_format(value, 2, '.', '') : 0) + ' Amp'; } },
				{ field: 'mcb_capacity_tp', title: 'Main MCB Capacity', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? value : 0) + ' Amp(AC) ' + (row.mcb_capacity != null ? row.mcb_capacity : ''); } },
				{ field: 'capacity_r', title: 'Capacity R', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? number_format(value, 2, '.', '') : 0) + ' Amp(AC)'; } },
				{ field: 'capacity_s', title: 'Capacity S', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? number_format(value, 2, '.', '') : 0) + ' Amp(AC)'; } },
				{ field: 'capacity_t', title: 'Capacity T', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? number_format(value, 2, '.', '') : 0) + ' Amp(AC)'; } },
				
				<?php
				$lookup = $this->core->get_lookup('power_rectifier_photo_type');
				foreach ($lookup as $lo) {
				?>
				{ field: 'photo-<?php echo $lo['id'] ?>', title: '<?php echo $lo['desc'] ?>', width: 120, align: 'center', formatter: function(value, row, index) {
					return ( row.photo['<?php echo $lo['id'] ?>'] != null ? '<a href="' + row.photo.large['<?php echo $lo['id'] ?>'] + '" class="photobox"><img src="' + row.photo['<?php echo $lo['id'] ?>'] + '" width="30" border="0" class="img-polaroid" /></a>' : '');
				} },
				<?php
				}
				?>
				
				{ field: 'actions', title: 'Actions', width: 180, align: 'center', formatter: function(value, row, index) {
					return '\
						<a href="javascript:GRID_RECTIFIER.addDetail(' + row.rectifier_id + ')" class="btn btn-small edit-detail" title="Add or Edit Detail">Edit Detail</a>\
						<a href="javascript:GRID_RECTIFIER.edit(' + row.rectifier_id + ')" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
						<a href="javascript:GRID_RECTIFIER.delete(' + row.rectifier_id + ')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
					';
				} }
			]],
			toolbar: '#tb-rectifier',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: false,
			rownumbers: true,
		//	height: 'auto',
			nowrap: false,
			onLoadSuccess: function() {
				for (var i = 0; i < document.getElementsByClassName('photobox').length; i++) {
					document.getElementsByClassName('photobox')[i].onclick = function() {
						$.fancybox({
							type:'image',
							href: this.href,
							openEffect: 'elastic',
							closeEffect: 'elastic',
							closeBtn: true
						});
						
						return false;
					};
				}
			},
			view: detailview,
			detailFormatter: function(index, row) {
				return '<div style="padding:2px 0"><table class="ddv-rectifier"></table></div>';
			},
			onExpandRow: function(index, row) {
				var ddv = $(this).datagrid('getRowDetail', index).find('table.ddv-rectifier');
				
				ddv.datagrid({
					url: '<?php echo site_url('/site_info/power') ?>',
					queryParams: { cmd: 'GET_DATAGRID_RECTIFIER_DETAIL', rectifier_id: row.rectifier_id },
					method: 'GET',
					fitColumns: true,
					singleSelect: true,
					rownumbers: true,
					loadMsg: '',
					height: 'auto',
					pagination: true,
					fitColumns: true,
					striped: true,
					columns: [[
						{ field: 'item', title: 'Item(s)', width: 100, sortable: true },
						{ field: 'capacity', title: 'Capacity', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? value : 0) + ' Amp(DC)'; } },
						{ field: 'capacity_actual', title: 'Actual Capacity', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2, '.', '') + ' Amp(DC)'; } },
						{ field: 'llvd_info', title: 'LLVD Info', width: 100, sortable: true },
						{ field: 'allocation', title: 'Allocation', width: 120, sortable: true },
						{ field: 'site_code', title: 'FE', width: 120, sortable: true },
						{ field: 'system', title: 'System', width: 120, sortable: true }
					]],
					onResize: function() {
						$('#grid-rectifier').datagrid('fixDetailRowHeight', index);
					},
					onLoadSuccess: function() {
						setTimeout(function() {
							$('#grid-rectifier').datagrid('fixDetailRowHeight', index);
						}, 0);
					}
				});
				
				$('#grid-rectifier').datagrid('fixDetailRowHeight', index);
			}
		});
	},
	edit: function(id) {
		this.params.cmd = 'GET_RECTIFIER_DATA';
		this.params.id = id;
		$.ajax({
			url: '<?php echo site_url('/site_info/power') ?>',
			type: 'GET',
			data: this.params,
			dataType: 'json',
			success: function(json) {
				if (typeof json.data == 'object') {
					
					// reset first
					GRID_RECTIFIER.resetForm();
					
					document.getElementsByName('rectifierNo')[0].value = json.data.rectifier_no;
					
					for (var i = 0; i < document.getElementsByName('slotUsed[]').length; i++) {
						for (var j = 0; j < json.data.slot_used.length; j++) {
							if (document.getElementsByName('slotUsed[]')[i].value == json.data.slot_used[j]) {
								document.getElementsByName('slotUsed[]')[i].checked = true;
							}
						}
					}
					document.getElementsByName('rectifierId')[0].value = json.data.rectifier_id;
					document.getElementById('dcPower').value = json.data.dc_power;
					document.getElementById('dcdcPowerCapacity').value = json.data.dcdc_power_capacity;
					document.getElementById('mcbCapacityTP').value = json.data.mcb_capacity_tp;
					document.getElementById('mcbCapacity').value = json.data.mcb_capacity;
					document.getElementById('capacityR').value = json.data.capacity_r;
					document.getElementById('capacityS').value = json.data.capacity_s;
					document.getElementById('capacityT').value = json.data.capacity_t;
					
					<?php
					$lookup = $this->core->get_lookup('power_rectifier_photo_type');
					foreach ($lookup as $lo) {
					?>
					document.getElementsByName('photoRectifier<?php echo $lo['id'] ?>')[0].value = json.data.photo_<?php echo $lo['id'] ?>;
					document.getElementById('photo-rectifier-<?php echo $lo['id'] ?>-thumb').src = json.data.photo_<?php echo $lo['id'] ?>_thumb;
					document.getElementById('photo-rectifier-<?php echo $lo['id'] ?>-box').href = json.data.photo_<?php echo $lo['id'] ?>_large;
					<?php
					}
					?>
					
					<?php foreach ($photos as $photo) { ?>
						if (document.getElementsByName('<?php echo $photo['name_form_hidden']?>')[0].value != '') {
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').style.display = 'inline-block';
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').innerHTML = 'Replace';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').style.display = 'inline-block';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').setAttribute('onclick', 'UPLOAD_PHOTO_RECTIFIER.deletePhoto(\'' + document.getElementsByName('<?php echo $photo['name_form_hidden']?>')[0].value + '\', \'<?php echo $photo['name_form_hidden']?>\')');
						} else {
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').style.display = 'inline-block';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').style.display = 'none';
						}
					<?php } ?>
					
					GRID_RECTIFIER.params.cmd = 'GET_DATAGRID_RECTIFIER';
					numberFormat.init();
					
					$('#rectifier-header-box').show();
					$('#rectifier-detail-box').hide();
					$('#btn-rectifier-addnew').hide();
					
					window.scroll(0, 0);
				}
			}
		});
	},
	addDetail: function(id) {
		$.ajax({
			url: document.URL,
			type: 'GET',
			data: { load_content: 'RECTIFIER_DETAIL', rectifier_id: id },
			dataType: 'html',
			success: function(html) {
				$('#rectifier-detail-box').html(html);
				
				$('#rectifier-detail-box').show();
				$('#rectifier-header-box').hide();
				$('#btn-rectifier-addnew').hide();
				
				window.scroll(0, 0);
			}
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-rectifier').value;
		this.loadData();
	},
	delete: function(id) {
		this.params.cmd = 'DELETE_RECTIFIER';
		this.params.id = id;
		var params = this.params;
		
		coolConfirmation('Are you sure you want to delete?', function() {
			$.ajax({
				url: '<?php echo site_url('/site_info/power') ?>',
				type: 'GET',
				data: params,
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						GRID_RECTIFIER.params.cmd = 'GET_DATAGRID_RECTIFIER';
						GRID_RECTIFIER.loadData();
					}
				}
			});
		});
	},
	resetForm: function() {
		formField.clearForm(document.getElementById('frmRectifier'));
		numberFormat.init();
		
		<?php foreach ($photos as $photo) { ?>
		document.getElementById('<?php echo $photo['id_href_browse'] ?>').style.display = 'inline-block';
		document.getElementById('<?php echo $photo['id_href_browse'] ?>').innerHTML = 'Browse';
		document.getElementById('<?php echo $photo['id_href_delete'] ?>').style.display = 'none';
		document.getElementById('<?php echo $photo['id_href_delete'] ?>').removeAttribute('onclick');
		document.getElementById('<?php echo $photo['id_image'] ?>').src = '<?php echo base_url('/file/upload/site-info/no_image-60x60.jpg') ?>';
		document.getElementById('<?php echo $photo['id_href_lightbox'] ?>').href = 'javascript:void(0)';
		<?php } ?>
	},
	init: function() {
		this.loadData();
		
		document.getElementById('query-rectifier').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_RECTIFIER.search();
				this.focus();
			}
		};
	}
};

$(function() {
	GRID_RECTIFIER.init();
});

numberFormat.init();
clearAutoComplete.init();

<?php foreach ($photos as $photo) { ?>
UPLOAD_PHOTO_RECTIFIER.init(document.getElementById('<?php echo $photo['id_href_browse'] ?>'), '<?php echo $photo['name_form_hidden'] ?>');
<?php } ?>
</script>