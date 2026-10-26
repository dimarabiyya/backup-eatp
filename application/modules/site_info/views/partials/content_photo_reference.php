<?php echo form_open_multipart('', 'id="formPhotoReference" class="form-horizontal"') ?>

<div class="top-form-actions">
	<button class="btn btn-primary btn-small btn-icon glyphicons circle_ok"><i></i> Save</button>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">File / Photo Reference Data</h4>
	</div>
	
	<div class="widget-body">
	
		<div class="overflow:auto">
			<table id="tblPhotoReference" class="table table-bordered table-striped table-condensed" style="width:auto">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Type:</th>
						<th>File:</th>
						<th width="100%">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if ($this->uri->segment(2) == 'edit' && count($photo_reference) > 0) {
						$photo_counter = 1;
						foreach ($photo_reference as $ref) {
						
							if (is_file($ref['file_path']) && file_exists($ref['file_path'])) {
					?>					
					<tr id="photo-row<?php echo $photo_counter ?>">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="photoReference.removeRow(<?php echo $photo_counter ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td><?php 
							$fileType = array(0 => '- Choose one -');
							$dataFileType = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'site_info_type_file', 'active' => 1), 'order');
							foreach ($dataFileType as $type) {
								$fileType[$type['lookup_value']] = $type['lookup_desc'];
							}
							
							echo @form_dropdown('fileType['.$photo_counter.']', $fileType, $ref['photo_type'], 'class="input-medium"');
							?>
						</td>
						<td nowrap>
							<div id="fileRef<?php echo $photo_counter ?>" style="text-align:center; width:100px;">
								<?php if ($ref['thumb'] != '') { ?>
								<a id="thumb-<?php echo $photo_counter ?>" class="real-image" href="<?php echo $ref['file'] ?>"><img src="<?php echo $ref['thumb'] ?>" class="img-polaroid" /></a>
								<?php } else {?>
								<a id="thumb-<?php echo $photo_counter ?>" href="<?php echo $ref['file'] ?>" class="btn btn-small btn-success"><strong>Download</strong></a>
								<?php } ?>
							</div>
							<input type="hidden" name="file[<?php echo $photo_counter ?>]" value="<?php echo $ref['photo_name'] ?>" />
						</td>
						<td>
							<a id="file-ref-<?php echo $photo_counter ?>" class="file-ref btn btn-small" style="display:inline-block">Replace</a>
							<a id="delete-<?php echo $photo_counter ?>" href="javascript:void(0)" onclick="uploadPhoto.removeFile('<?php echo $ref['photo_name'] ?>', <?php echo $photo_counter ?>)" class="btn btn-danger btn-small" style="display:inline-block">Delete</a>
						</td>
					</tr>
					
					<?php 
							}
							
							$photo_counter++;
						}
					} else { ?>
					
					<tr id="photo-row1">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="photoReference.removeRow(1)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td><?php 
							$fileType = array(0 => '- Choose one -');
							$dataFileType = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'site_info_type_file', 'active' => 1), 'order');
							foreach ($dataFileType as $type) {
								$fileType[$type['lookup_value']] = $type['lookup_desc'];
							}
							
							echo @form_dropdown('fileType[1]', $fileType, '', 'class="input-medium"');
							?>
						</td>
						<td>
							<div id="fileRef1" style="text-align:center; width:100px;"></div>
							<input type="hidden" name="file[1]" />
						</td>
						<td>
							<a id="file-ref-1" class="file-ref btn btn-small">Browse</a>
							<a id="delete-1" href="javascript:void(0)" class="btn btn-danger btn-small" style="display:none">Delete</a>
						</td>
					</tr>
					<?php } ?>
					
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" class="left"><a class="btn btn-success btn-small" href="javascript:void(0)" onclick="photoReference.addRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></a></td>
					</tr>
				</tfoot>
			</table>
		</div>
	
	</div>
</div>

<?php echo form_close() ?>

<script>
document.getElementById('formPhotoReference').onsubmit = function() {
	
	var fileType = document.getElementsByName('fileType[]');
	var file = document.getElementsByName('file[]');
	for (var i = 0; i < fileType.length; i++) {
		if (fileType[i].value == 0 || file[i].value == '') {
			alert('File Type or File Reference still empty.');
			fileType[i].focus();
			return false;
		}
	}
	
	var data = serialize(this);
	<?php if ($this->uri->segment(2) == 'add') { ?>
	data += '&cmd=ADD_PHOTO_REFERENCE';
	<?php } elseif ($this->uri->segment(2) == 'edit') { ?>
	data += '&cmd=EDIT_PHOTO_REFERENCE';
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
			
			var siteId = document.getElementsByName('siteId');
			for (var i = 0; i < siteId.length; i++) {
				siteId[i].value = json['site_id'];
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
					var tab = $('#siteInfoTab').tabs('getSelected');
					$('#siteInfoTab').tabs('update', {
						tab: tab,
						options: {
							href: document.URL + '?load_content=PHOTO_REFERENCE&site_id=' + document.getElementsByName('siteId')[0].value
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

<?php if ($this->uri->segment(2) == 'edit') { ?>
var cmd = 'EDIT_PHOTO_REFERENCE';
<?php } else { ?>
var cmd = 'ADD_PHOTO_REFERENCE';
<?php } ?>

var uploadPhoto = {
	init: function(row) {
		new AjaxUpload('file-ref-' + row, {
			action: '<?php echo site_url($this->uri->uri_string()) ?>',
			name: 'fileRef' + row,
			//data: { cmd: cmd, act: 'UPLOAD', image: document.getElementsByName('file[' + row + ']')[0].value },
			autoSubmit: true,
			responseType: 'json',
			onChange: function(file, extension) {},
			onSubmit: function(file, extension) {
				var image = document.getElementsByName('file[' + row + ']')[0].value;
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
					if (json.thumb != '') {
						document.getElementById(json.ref).innerHTML = '<a href="' + json.file + '" class="real-image"><img src="' + json.thumb + '" border="0" class="img-polaroid" /></a>';
					} else {
						document.getElementById(json.ref).innerHTML = '<a href="' + json.file + '" class="btn btn-small btn-success"><strong>Download</strong></a>';
					}
					
					document.getElementsByName('file[' + row + ']')[0].value = json.file_name;
					document.getElementById('delete-' + row).setAttribute('onclick',  'uploadPhoto.removeFile(\'' + json.file_name + '\', ' + row + ')');
					document.getElementById('delete-' + row).style.display = 'inline-block';
					document.getElementById('file-ref-' + row).innerHTML = 'Replace';
				
				} else {
					alert(json.message);
				}
				
				$.unblockUI();
			}
		});
	},
	remove: function(row) {
		$('#file-uploaded-' + row).remove();
		$('#file-ref-' + row).show();
		$('#file-' + row).val('');
	},
	removeFile: function(fileName, row) {
		coolConfirmation('Are you sure you want to delete?', function() {
			$.ajax({
				url: '<?php echo site_url($this->uri->uri_string()) ?>',
				type: 'POST',
				data: { cmd: cmd, act: 'DELETE', file_name: fileName },
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						document.getElementById('delete-' + row).removeAttribute('onclick');
						document.getElementById('delete-' + row).style.display = 'none';
						document.getElementById('file-ref-' + row).style.display = 'inline-block';
						document.getElementById('file-ref-' + row).innerHTML = 'Browse';
						document.getElementById('fileRef' + row).innerHTML = '';
						document.getElementsByName('file[' + row + ']')[0].value = '';
						
						uploadPhoto.init(row);
					}
				}
			});
		});
	}
};

<?php if ($this->uri->segment(2) == 'edit' && count($photo_reference) > 0) { ?>
var counter = <?php echo $photo_counter ?>;
<?php } else { ?>
var counter = 2;
<?php } ?>

var photoReference = {
	addRow: function() {
		var html = '\
			<tr id="photo-row' + counter + '">\
				<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="photoReference.removeRow(' + counter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>\
				<td>\
					<select name="fileType[' + counter + ']" class="input-medium">\
						<option value="">- Choose one -</option>\
						<?php
						$dataFileType = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'site_info_type_file', 'active' => 1), 'order');
						foreach ($dataFileType as $type) {
						?>\
						<option value="<?php echo $type['lookup_value'] ?>"><?php echo $type['lookup_desc'] ?></option>\
						<?php
						}
						?>\
					</select>\
				</td>\
				<td>\
					<div id="fileRef' + counter + '" style="text-align:center; width:100px;"></div>\
					<input type="hidden" name="file[' + counter + ']" />\
				</td>\
				<td>\
					<a id="file-ref-' + counter + '" class="file-ref btn btn-small">Browse</a>\
					<a id="delete-' + counter + '" href="javascript:void(0)" class="btn btn-danger btn-small" style="display:none">Delete</a>\
				</td>\
			</tr>\
		';
		
		$('#tblPhotoReference tbody').append(html);
		
		uploadPhoto.init(counter);
		
		counter++;
	},
	removeRow: function(row) {
		var row = document.getElementById('photo-row' + row);
		coolConfirmation('Are you sure you want to delete?', function() {			
			row.parentNode.removeChild(row);
		});
	}
};

$('.real-image').fancybox({
	type: 'image',
	transitionIn : 'elastic',
	transitionOut: 'elastic',
	closeBtn: true
});

<?php if ($this->uri->segment(2) == 'edit' && count($photo_reference) > 0) { ?>
for (var i = 1; i <= <?php echo ($photo_counter - 1) ?>; i++) {
	uploadPhoto.init(i);
}
<?php } else { ?>
uploadPhoto.init(1);
<?php } ?>
</script>