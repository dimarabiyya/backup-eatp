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

<!-- start: dcpdb header -->
<div id="dcpdb-header-box" style="display:none">

	<?php echo form_open_multipart('', 'id="frmDCPDB" class="form-horizontal"') ?>
	<?php echo form_hidden('dcpdbId') ?>
	
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading"><i></i> DCPDB Data</h4>
		</div>
		
		<div class="widget-body">
	
			<div class="control-group" id="form_dcpdb_linked_to_rectifier_no">
				<label class="control-label" for="linkedToRectifierNo">Linked to Rectifier No.:</label>
				<div class="controls form-inline">
					<?php
					$lookup = $this->core->get_lookup('rectifier_number');
					$options = array(0 => '- Choose one -');
					foreach ($lookup as $lo) {
						$options[$lo['id']] = $lo['desc'];
					}
					
					echo @form_dropdown('linkedToRectifierNo', $options, '', 'id="linkedToRectifierNo"');
					?>
				</div>
			</div>
			
			<div class="control-group" id="form_dcpdb_main_mcb_capacity">
				<label class="control-label" for="mainMcbCapacity">Main MCB Capacity:</label>
				<div class="controls">
					<div class="input-append">
						<?php echo @form_input('mainMcbCapacity', set_value('mainMcbCapacity', set_value('mainMcbCapacity', $dcpdb['main_mcb_capacity'])), 'id="mainMcbCapacity", class="input-small number"') ?>
						<span class="add-on">Amp</span>
					</div>
				</div>
			</div>

			<div class="control-group" id="form_dcpdb_cable_input_diameter">
				<label class="control-label" for="cableDiameter">Cable Input Diameter:</label>
				<div class="controls">
					<div class="input-append">
						<?php echo @form_input('cableDiameter', set_value('cableDiameter', $dcpdb['cable_diameter']), 'id="cableDiameter" class="input-small number"') ?>
						<span class="add-on">mm<sup>2</sup></span>
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
		
			<table class="table table-bordered" style="width:auto">
				<thead>
					<tr>
						<th width="200" class="center">Type</th>
						<th class="center">Photo</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$lookup = $this->core->get_lookup('power_dcpdb_photo_type');
					$photos = array();
					foreach ($lookup as $lo) {
						$photos[] = array(
							'title' => $lo['desc'],
							'image' => '<a id="photo-dcpdb-'.$lo['id'].'-box" href="javascript:void(0)" class="photobox"><img id="photo-dcpdb-'.$lo['id'].'-thumb" src="'.base_url('/file/upload/site-info/no_image-60x60.jpg').'" class="img-polaroid" /></a>',
							'form_hidden' => form_hidden('photoDCPDB'.$lo['id']),
							'btn_browse' => '<a id="photo-dcpdb-'.$lo['id'].'-browse" class="btn btn-mini" style="display:inline-block">Browse</a>',
							'btn_delete' => '<a id="photo-dcpdb-'.$lo['id'].'-delete" class="btn btn-mini btn-danger" style="display:inline-block">Delete</a>',
						
							'name_form_hidden' => 'photoDCPDB'.$lo['id'],
							'id_image' => 'photo-dcpdb-'.$lo['id'].'-thumb',
							'id_href_lightbox' => 'photo-dcpdb-'.$lo['id'].'-box',
							'id_href_browse' => 'photo-dcpdb-'.$lo['id'].'-browse',
							'id_href_delete' => 'photo-dcpdb-'.$lo['id'].'-delete'
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
		<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$('#btn-dcpdb-addnew').show(); $('#dcpdb-header-box').hide(); GRID_DCPDB.resetForm(); window.scroll(0, 0);"><i></i>Cancel</button>
	</div>

	<?php echo form_close() ?>
	
</div>
<!-- end: dcpdb header -->

<!-- start: detail DCPDB -->
<div id="dcpdb-detail-box" style="display:none"></div>
<!-- end: detail DCPDB -->

<!-- start: datagrid DCPDB -->
<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> DCPDB</h4>
	</div>
	
	<div class="widget-body">
	
		<div class="heading-buttons">
			<div class="buttons" style="margin-right:0px">
				<a id="btn-dcpdb-addnew" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)" onclick="$('#dcpdb-header-box').show(); $(this).hide(); GRID_DCPDB.resetForm();"><i></i> Add New</a>
			</div>
		</div>
		<div class="separator top"></div>
		
		<div id="tb-dcpdb" class="form-search">
			<div class="input-append">
				<?php echo form_input('query-dcpdb', '', 'id="query-dcpdb" class="input-medium search-query" placeholder="Search..."') ?>
				<button class="btn" onclick="GRID_DCPDB.search()"><i class="icon-search"></i></button>
			</div>
		</div>
		
		<table style="height:450px" id="grid-dcpdb"></table>
	
	</div>
</div>
<!-- end: datagrid DCPDB -->

<script>
document.getElementById('frmDCPDB').onsubmit = function() {

	var data = serialize(this);
	<?php if ($this->uri->segment(3) == 'add') { ?>
	data += '&cmd=ADD_DCPDB';
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	data += '&cmd=EDIT_DCPDB';
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
				
					GRID_DCPDB.resetForm();
					GRID_DCPDB.params.cmd = 'GET_DATAGRID_DCPDB';
					GRID_DCPDB.params.site_id = document.getElementsByName('siteId')[0].value;
					GRID_DCPDB.init();
					
					$('#dcpdb-header-box').hide();
					$('#btn-dcpdb-addnew').show();
				
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

/* var farEnd = {
	init: function() {
		var fe = document.getElementsByName('feId[]');
		for (var i = 0; i < fe.length; i++) {
			$(fe[i]).combogrid({
				panelWidth: 630,
				url: '<?php echo site_url('/site_info/power') ?>',
				method: 'GET',
				queryParams: { cmd: 'GET_SITE', site_id: fe[i].value },
				mode: 'remote',	
				idField: 'site_id',
				textField: 'site_code',
				//pagination: false,
				//pageSize: 10,
				columns:[[
					{ field: 'site_code', title: 'Site ID', width: 150, sortable: true },
					{ field: 'site_name', title: 'Site Name', width: 150, sortable: true },
					{ field: 'vendor_id', title: 'Vendor ID', width: 150, sortable: true },
					{ field: 'vendor_name', title: 'Vendor Name', width: 150, sortable: true }
				]],
				onSelect: function(index, row) {
					//document.getElementById('siteName').value = row.site_name;
					//document.getElementById('vendorId').value = row.vendor_id;
					//document.getElementById('vendorName').value = row.vendor_name;
					//loadBTSList(row.site_id);
				}
			});
		}
	}
}; */

var doLookupSiteDetailDCPDB = function(elem1, elem2, elemIdx) { // elem1: shown element, elem2: hidden element, elemIdx: index of element
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

var GRID_DCPDB = {
	params: {
		cmd: 'GET_DATAGRID_DCPDB',
		site_id: document.getElementsByName('siteId')[0].value
	},
	loadData: function() {
		$('#grid-dcpdb').datagrid({
			url: '<?php echo site_url('/site_info/power') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'dcpdb_id', hidden: true },
				{ field: 'rectifier_no', title: 'Linked to Rectifier No.', width: 120, sortable: true },
				{ field: 'main_mcb_capacity', title: 'Main MCB Capacity', width: 120, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? number_format(value, 2, '.', '') : 0) + ' Amp'; } },
				{ field: 'cable_diameter', title: 'Cable Input Diameter', width: 120, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? number_format(value, 2, '.', '') : 0) + ' mm<sup>2</sup>'; } },
				
				<?php
				$lookup = $this->core->get_lookup('power_dcpdb_photo_type');
				foreach ($lookup as $lo) {
				?>
				{ field: 'photo-<?php echo $lo['id'] ?>', title: '<?php echo $lo['desc'] ?>', width: 120, align: 'center', formatter: function(value, row, index) {
					return ( row.photo['<?php echo $lo['id'] ?>'] != null ? '<a href="' + row.photo.large['<?php echo $lo['id'] ?>'] + '" class="photobox"><img src="' + row.photo['<?php echo $lo['id'] ?>'] + '" width="30" border="0" class="img-polaroid" /></a>' : '');
				} },
				<?php
				}
				?>
				
				{ field: 'actions', title: 'Actions', width: 180, align: 'center', fixed: true, formatter: function(value, row, index) {
					return '\
						<a href="javascript:GRID_DCPDB.addDetail(' + row.dcpdb_id + ')" class="btn btn-small edit-detail" title="Add or Edit Detail">Edit Detail</a>\
						<a href="javascript:GRID_DCPDB.edit(' + row.dcpdb_id + ')" class="btn-action glyphicons pencil btn-success" title="Edit"><i></i></a>\
						<a href="javascript:GRID_DCPDB.delete(' + row.dcpdb_id + ')" class="btn-action glyphicons remove_2 btn-danger" title="Delete"><i></i></a>\
					';
				} }
			]],
			toolbar: '#tb-dcpdb',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
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
				return '<div style="padding:2px 0"><table class="ddv-dcpdb"></table></div>';
			},
			onExpandRow: function(index, row) {
				var ddv = $(this).datagrid('getRowDetail', index).find('table.ddv-dcpdb');
				
				ddv.datagrid({
					url: '<?php echo site_url('/site_info/power') ?>',
					queryParams: { cmd: 'GET_DATAGRID_DCPDB_DETAIL', dcpdb_id: row.dcpdb_id },
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
						{ field: 'actual_capacity', title: 'Actual Capacity', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2, '.', '') + ' Amp(DC)'; } },
						{ field: 'allocation', title: 'Allocation', width: 120, sortable: true },
						{ field: 'site_code', title: 'FE ID', width: 120, sortable: true },
						{ field: 'system', title: 'System', width: 120, sortable: true },
						{ field: 'linked_to_rectifier_no', title: 'Linked to Rectifier No.', width: 120, sortable: true, align: 'right' }
					]],
					onResize: function() {
						$('#grid-dcpdb').datagrid('fixDetailRowHeight', index);
					},
					onLoadSuccess: function() {
						setTimeout(function() {
							$('#grid-dcpdb').datagrid('fixDetailRowHeight', index);
						}, 0);
					}
				});
				
				$('#grid-dcpdb').datagrid('fixDetailRowHeight', index);
			}
		});
	},
	addDetail: function(id) {
		$.ajax({
			url: document.URL,
			type: 'GET',
			data: { load_content: 'DCPDB_DETAIL', dcpdb_id: id },
			dataType: 'html',
			success: function(html) {
				$('#dcpdb-detail-box').html(html);
				
				$('#dcpdb-detail-box').show();
				$('#dcpdb-header-box').hide();
				$('#btn-dcpdb-addnew').hide();
				
				window.scroll(0, 0);
			}
		});
	},
	edit: function(id) {
		this.params.cmd = 'GET_DCPDB_DATA';
		this.params.id = id;
		$.ajax({
			url: '<?php echo site_url('/site_info/power') ?>',
			type: 'GET',
			data: this.params,
			dataType: 'json',
			success: function(json) {
				if (typeof json.data == 'object') {
					
					// reset first
					GRID_DCPDB.resetForm();
					
					document.getElementById('linkedToRectifierNo').value = json.data.rectifier_no != null ? json.data.rectifier_no : 0;
					document.getElementsByName('dcpdbId')[0].value = json.data.dcpdb_id;
					document.getElementById('mainMcbCapacity').value = json.data.main_mcb_capacity;
					document.getElementById('cableDiameter').value = json.data.cable_diameter;
					
					<?php
					$lookup = $this->core->get_lookup('power_dcpdb_photo_type');
					foreach ($lookup as $lo) {
					?>
					document.getElementsByName('photoDCPDB<?php echo $lo['id'] ?>')[0].value = json.data.photo_<?php echo $lo['id'] ?>;
					document.getElementById('photo-dcpdb-<?php echo $lo['id'] ?>-thumb').src = json.data.photo_<?php echo $lo['id'] ?>_thumb;
					document.getElementById('photo-dcpdb-<?php echo $lo['id'] ?>-box').href = json.data.photo_<?php echo $lo['id'] ?>_large;
					<?php
					}
					?>
					
					<?php foreach ($photos as $photo) { ?>
						if (document.getElementsByName('<?php echo $photo['name_form_hidden']?>')[0].value != '') {
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').style.display = 'inline-block';
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').innerHTML = 'Replace';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').style.display = 'inline-block';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').setAttribute('onclick', 'UPLOAD_PHOTO_DCPDB.deletePhoto(\'' + document.getElementsByName('<?php echo $photo['name_form_hidden']?>')[0].value + '\', \'<?php echo $photo['name_form_hidden']?>\')');
						} else {
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').style.display = 'inline-block';
						}
					<?php } ?>
					
					GRID_DCPDB.params.cmd = 'GET_DATAGRID_DCPDB';
					numberFormat.init();
					
					$('#dcpdb-header-box').show();
					$('#dcpdb-detail-box').hide();
					$('#btn-dcpdb-addnew').hide();
					
					window.scroll(0, 0);
				}
			}
		});
	},
	resetForm: function() {
		formField.clearForm(document.getElementById('frmDCPDB'));
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
	search: function() {
		this.params.query = document.getElementById('query-dcpdb').value;
		this.loadData();
	},
	delete: function(id) {
		this.params.cmd = 'DELETE_DCPDB';
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
						GRID_DCPDB.params.cmd = 'GET_DATAGRID_DCPDB';
						GRID_DCPDB.loadData();
					}
				}
			});
		});
	},
	init: function() {
		this.loadData();
		
		document.getElementById('query-dcpdb').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_DCPDB.search();
				this.focus();
			}
		};
	}
};

var UPLOAD_PHOTO_DCPDB = {
	init: function(elem, nameFileElem) {
		<?php if ($this->uri->segment(3) == 'add') { ?>
		var cmd = 'ADD_DCPDB';
		<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
		var cmd = 'EDIT_DCPDB';
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
							
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').style.display = 'inline-block';
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').innerHTML = 'Replace';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').style.display = 'inline-block';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').setAttribute('onclick', 'UPLOAD_PHOTO_DCPDB.deletePhoto(\'' + json.file + '\', \'' + json.type + '\')');
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
			var cmd = 'ADD_DCPDB';
			<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
			var cmd = 'EDIT_DCPDB';
			<?php } ?>
			
			$.ajax({
				url: '<?php echo site_url($this->uri->uri_string()) ?>',
				type: 'POST',
				data: { cmd: cmd, act: 'DELETE_PHOTO', photo_name: photoName, photo_type: photoType },
				dataType: 'json',
				success: function(json) {
					<?php foreach ($photos as $photo) { ?>
						if (json.type == '<?php echo $photo['name_form_hidden'] ?>') {
							document.getElementsByName('<?php echo $photo['name_form_hidden']?>')[0].value = '';
							document.getElementById('<?php echo $photo['id_image'] ?>').src = '<?php echo base_url('/file/upload/site-info/no_image-60x60.jpg') ?>';
							document.getElementById('<?php echo $photo['id_href_lightbox'] ?>').href = 'javascript:void(0)';
							
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').style.display = 'inline-block';
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').innerHTML = 'Browse';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').style.display = 'none';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').removeAttribute('onclick');
						}
					<?php } ?>
				}
			});
		});
	}
};

/* var loadRectifierLinked = function(siteId) {
	$.ajax({
		url: '<?php echo site_url('/site_info/power') ?>',
		type: 'GET',
		data: { cmd: 'GET_RECTIFIER_NO', siteId: siteId },
		dataType: 'json',
		beforeSend: function() {},
		success: function(json) {
			if (typeof json.loggedin != 'undefined' && !json.loggedin) {
				alert(json.message);
				window.location.href = json.redirect_url;
			}
			
			
		},
		complete: function() {}
	});
}; */

$(function() {
	GRID_DCPDB.init();
});

numberFormat.init();
justNumberFormat.init();
clearAutoComplete.init();
uneditable.init();

<?php foreach ($photos as $photo) { ?>
UPLOAD_PHOTO_DCPDB.init(document.getElementById('<?php echo $photo['id_href_browse'] ?>'), '<?php echo $photo['name_form_hidden'] ?>');
<?php } ?>
</script>