<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<style>
.table th, .table td { vertical-align:middle; }
select { width:auto; }
</style>

<div class="innerLR">

	<div class="widget">	
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Site</h4>
			
			<a href="<?php echo site_url('/site_info/bts_optimization') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
		</div>
		
		<div class="widget-body">
			
			<div class="form-horizontal">
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_bts_optim_site_id">
							<label class="control-label" for="siteId">Site ID:</label>
							<div class="controls">
								<?php if ($this->uri->segment(3) == 'new_visit') { ?>
								
								<span class="input-label"><?php echo $bts_optimization['site_code'] ?></span>
								<?php echo @form_hidden('siteId', set_value('siteId', $bts_optimization['site_id'])) ?>
								
								<?php } else { ?>
								
								<div class="input-append">
									<?php echo @form_input('siteCode', set_value('siteCode', $bts_optimization['site_code']), 'onfocus="doLookupSite(\'siteCode\', \'siteId\')" id="siteCode" class="input-medium uneditable"') ?>
									<?php echo @form_hidden('siteId', set_value('siteId', $bts_optimization['site_id'])) ?>
									<button type="button" class="btn" onclick="doLookupSite('siteCode', 'siteId')">...</button>
								</div>
								<?php } ?>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_bts_optim_site_id_ref">
							<label class="control-label" for="siteIdRef">Site ID Ref.:</label>
							<div class="controls">
								<span id="siteIdRef" class="input-label"><?php echo @$bts_optimization['site_code_old'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_bts_optim_site_name">
							<label class="control-label" for="siteName">Site Name:</label>
							<div class="controls">
								<span id="siteName" class="input-label"><?php echo @$bts_optimization['site_name'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_bts_optim_vendor_id">
							<label class="control-label" for="vendorId">Vendor ID:</label>
							<div class="controls">
								<span id="vendorId" class="input-label"><?php echo @$bts_optimization['vendor_id'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="control-group" id="form_bts_optim_region">
					<label class="control-label" for="region">Region:</label>
					<div class="controls">
						<span id="region" class="input-label"><?php echo @$bts_optimization['region_name'] ?></span>
					</div>
				</div>
			</div>
		
		</div>
	</div>
	
	<?php echo form_open('', 'id="frmBTSOptimization" class="form-horizontal"') ?>
	
		<div class="widget">
			<div class="widget-head">
				<h4 class="heading"><i></i> Optimization Data:</h4>
			</div>
			
			<div class="widget-body">
			
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_visit_date">
							<label class="control-label" for="visitDate">Visit Date:</label>
							<div class="controls">
								<div class="input-append">
									<?php echo @form_input('visitDate', set_value('visitDate', $bts_optimization['visit_date']), 'id="visitDate" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
									<span class="add-on"><i class="icon-calendar"></i></span>
									<!-- <button onclick="clearField('visitDate')" type="button" class="btn"><i class="icon-remove-sign"></i></button> -->
								</div>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_reason_for_change">
							<label class="control-label" for="reason">Reason for Change:</label>
							<div class="controls">
								<?php
								$options = array(0 => '- Choose one -');
								$lookup = $this->core->get_lookup('bts_optimization_reason');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								echo form_dropdown('reason', $options, set_value('reason', $bts_optimization['reason']), 'id="reason"');
								?>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_vendor_engineer">
							<label class="control-label" for="vendorEngineer">Vendor Engineer:</label>
							<div class="controls">
								<?php echo @form_input('vendorEngineer', $bts_optimization['vendor_engineer'], 'id="vendorEngineer" class="input-large"') ?>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_sf_engineer">
							<label class="control-label" for="sfEngineer">SF Engineer:</label>
							<div class="controls">
								<?php echo @form_input('sfEngineer', $bts_optimization['sf_engineer'], 'id="sfEngineer" class="input-large"') ?>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_vendor_engineer_contact_no">
							<label class="control-label" for="vendorEngineerContactNo">Vendor Engineer Contact No.:</label>
							<div class="controls">
								<div class="input-prepend">
									<span class="add-on"><i class="icon-book"></i></span>
									<?php echo @form_input('vendorEngineerContactNo', $bts_optimization['vendor_engineer_contact_no'], 'id="vendorEngineerContactNo" class="input-medium"') ?>
								</div>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_sf_engineer_contact_no">
							<label class="control-label" for="sfEngineerContactNo">SF Engineer Contact No.:</label>
							<div class="controls">
								<div class="input-prepend">
									<span class="add-on"><i class="icon-book"></i></span>
									<?php echo @form_input('sfEngineerContactNo', $bts_optimization['sf_engineer_contact_no'], 'id="sfEngineerContactNo" class="input-medium"') ?>
								</div>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_vendor_rigger">
							<label class="control-label" for="vendorRigger">Vendor Rigger:</label>
							<div class="controls">
								<?php echo @form_input('vendorRigger', $bts_optimization['vendor_rigger'], 'id="vendorRigger" class="input-large"') ?>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_sf_rigger">
							<label class="control-label" for="sfRigger">SF Rigger:</label>
							<div class="controls">
								<?php echo @form_input('sfRigger', $bts_optimization['sf_rigger'], 'id="sfRigger" class="input-large"') ?>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_vendor_rigger_contact_no">
							<label class="control-label" for="vendorRiggerContactNo">Vendor Rigger Contact No.:</label>
							<div class="controls">
								<div class="input-prepend">
									<span class="add-on"><i class="icon-book"></i></span>
									<?php echo @form_input('vendorRiggerContactNo', $bts_optimization['vendor_rigger_contact_no'], 'id="vendorRiggerContactNo" class="input-medium"') ?>
								</div>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_sf_rigger_contact_no">
							<label class="control-label" for="sfRiggerContactNo">SF Rigger Contact No.:</label>
							<div class="controls">
								<div class="input-prepend">
									<span class="add-on"><i class="icon-book"></i></span>
									<?php echo @form_input('sfRiggerContactNo', $bts_optimization['sf_rigger_contact_no'], 'id="sfRiggerContactNo" class="input-medium"') ?>
								</div>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<!--
				<div class="control-group">
					<label class="control-label" for="reason">Reason:</label>
					<div class="controls">
						<?php echo @form_input('reason', set_value('reason', trim($bts_optimization['reason'])), 'id="reason" class="span6"') ?>
					</div>
				</div>
				-->
				
				<div class="row-fluid">
					<div class="span12">
						<div class="control-group" id="form_remarks">
							<label class="control-label" for="remarks">Remarks:</label>
							<div class="controls">
								<?php echo @form_textarea('remarks', set_value('remarks', trim($bts_optimization['remarks'])), 'id="remarks" class="span4" style="height:100px;"') ?>
							</div>
						</div>
					</div>
				</div>
			
			</div>
		</div>
		
		<div id="content-RF"></div>
		
		<div class="widget">	
			<div class="widget-head">
				<h4 class="heading"><i></i> Photo(s)</h4>
			</div>
			
			<div class="widget-body">
			
				<table id="tblPhoto" class="table table-bordered table-striped table-condensed" style="width:auto">
					<thead>
						<tr>
							<th width="200" class="center">Type</th>
							<th class="center">Photo</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$lookup = $this->core->get_lookup('bts_jenis_foto');
						$photos = array();
						foreach ($lookup as $lo) {
							$thumb = base_url('/file/upload/site-info/no_image-60x60.jpg');
							$image = 'javascript:void(0)';
							$form_hidden = form_hidden('photoRF'.$lo['id']);
							
							if ($this->uri->segment(3) == 'new_visit' && isset($bts_optimization['visit_id'])) {
								if ( $photo = $this->core->view_row('t_trx_bts_optimization_photo', array('visit_id' => $bts_optimization['visit_id'], 'photo_cat' => $lo['id'])) ) {
									if (file_exists($bts_optimization_dir.'/'.$photo['photo_name']) && is_file($bts_optimization_dir.'/'.$photo['photo_name'])) {
										$old_name = $photo['photo_name'];
										$ext = strtolower(strchr($photo['photo_name'], '.'));
										$new_name = strtoupper(md5($photo['photo_name'])).$ext;
										copy($bts_optimization_dir.'/'.$old_name, $bts_optimization_dir.'/'.$new_name);
										
										$thumb = $this->image_model->resize($bts_optimization_dir.'/'.$new_name, 60, 60, 'crop');
										$image = $bts_optimization_url.'/'.$new_name;
										$form_hidden = form_hidden('photoRF'.$lo['id'], $new_name);
									}
								}
							}
							
							$photos[] = array(
								'title' => $lo['desc'],
								'image' => '<a id="photo-rf-'.$lo['id'].'-box" href="'.$image.'" class="photobox"><img id="photo-rf-'.$lo['id'].'-thumb" src="'.$thumb.'" class="img-polaroid" /></a>',
								'form_hidden' => $form_hidden,
								'btn_browse' => '<a id="photo-rf-'.$lo['id'].'-browse" class="btn btn-small" style="inline-block">Browse</a>',
								'btn_delete' => '<a id="photo-rf-'.$lo['id'].'-delete" class="btn btn-small btn-danger" style="inline-block">Delete</a>',
							
								'name_form_hidden' => 'photoRF'.$lo['id'],
								'id_image' => 'photo-rf-'.$lo['id'].'-thumb',
								'id_href_lightbox' => 'photo-rf-'.$lo['id'].'-box',
								'id_href_browse' => 'photo-rf-'.$lo['id'].'-browse',
								'id_href_delete' => 'photo-rf-'.$lo['id'].'-delete'
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
			<button id="btnSave" class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
			<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/site_info/bts_optimization') ?>'"><i></i>Cancel</button>
		</div>
	
	<?php echo form_close() ?>
	
</div>

<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/ajaxupload.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>

<script>
document.getElementById('frmBTSOptimization').onsubmit = function() {

	var data = $(this).serialize();
	data += '&cmd=ADD_BO';
	data += '&siteId=' + document.getElementsByName('siteId')[0].value;
	data += '&act=SAVE';
	
	dateFormat.saveMode();
	
	$.ajax({
		url: '<?php echo site_url('/site_info/bts_optimization/add') ?>',
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
					window.location.href = '<?php echo site_url('/site_info/bts_optimization') ?>';
				});
			}
			
			dateFormat.defaultMode();
			
			window.scroll(0, 0);
		},
		complete: function() {
			siteOverlay.remove();
		}
	});
	
	return false;
};

var RF = {
	loadAntennaRF: function() {
		$.ajax({
			url: '<?php echo site_url('/site_info/bts_optimization') ?>',
			type: 'GET',
			data: { load_content: 'ANTENNA_RF_OPTIM', site_id: document.getElementsByName('siteId')[0].value, act: 'add', visit_id: '<?php echo $bts_optimization['visit_id'] ?>' },
			dataType: 'html',
			beforeSend: function() {},
			success: function(html) {
				$('#content-RF').html(html);
			},
			complete: function() {}
		});
	}
};

var doLookupSite = function(elem1, elem2) { // elem1: shown element, elem2: hidden element
	var url = '<?php echo site_url('/site_info/bts_optimization') ?>';
	
	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { cmd: 'get_lookup_site', e: elem2 } }, // e: it used for selected element
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
					
					document.getElementById('siteIdRef').innerHTML = row.site_code_old;
					document.getElementById('siteName').innerHTML = row.site_name;
					document.getElementById('vendorId').innerHTML = row.vendor_id;
					document.getElementById('region').innerHTML = row.region_name;
					
					RF.loadAntennaRF();
				}
				
				$.fancybox.close();
			};
		}
	});
};

var UPLOAD_PHOTO_RF = {
	init: function(elem, nameFileElem) {
		var cmd = 'ADD_BO';
		
		new AjaxUpload(elem, {
			action: '<?php echo site_url('/site_info/bts_optimization/add') ?>',
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
							
							document.getElementById('<?php echo $photo['id_href_browse'] ?>').innerHTML = 'Replace';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').style.display = 'inline-block';
							document.getElementById('<?php echo $photo['id_href_delete'] ?>').setAttribute('onclick', 'UPLOAD_PHOTO_RF.deletePhoto(\'' + json.file + '\', \'' + json.type + '\')');
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
				document.getElementById('<?php echo $photo['id_href_delete'] ?>').setAttribute('onclick', 'UPLOAD_PHOTO_RF.deletePhoto(\'' + document.getElementsByName('<?php echo $photo['name_form_hidden'] ?>')[0].value + '\', \'<?php echo $photo['name_form_hidden'] ?>\')');
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
		
			var cmd = 'ADD_BO';
			
			$.ajax({
				url: '<?php echo site_url('/site_info/bts_optimization/add') ?>',
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

var clearField = function(field) {
	document.getElementById(field).value = '';
};

/* Initialize */

dateFormat.init();
clearField('visitDate');
numberFormat.init();
TEXT.clearAutoComplete();
TEXT.uneditable();
mainMenu();

<?php foreach ($photos as $photo) { ?>
UPLOAD_PHOTO_RF.init(document.getElementById('<?php echo $photo['id_href_browse'] ?>'), '<?php echo $photo['name_form_hidden'] ?>');
<?php } ?>

RF.loadAntennaRF();
</script>