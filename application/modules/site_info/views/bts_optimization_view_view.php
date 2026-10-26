<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<style>
.table th, .table td { vertical-align:middle; }
.btn { padding:4px 10px; }
</style>

<!--
<div class="heading-buttons">
	<h3>BTS Optimization</h3>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/bts_optimization') ?>" class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back</a>
	</div>
</div>
<div class="separator top"></div>
-->

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
								<span class="input-label"><?php echo $bts_optim['site_code'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_bts_optim_site_id_ref">
							<label class="control-label" for="siteIdRef">Site ID Ref.:</label>
							<div class="controls">
								<span class="input-label"><?php echo $bts_optim['site_code_old'] ?></span>
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
								<span class="input-label"><?php echo $bts_optim['site_name'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_bts_optim_vendor_id">
							<label class="control-label" for="vendorId">Vendor ID:</label>
							<div class="controls">
								<span class="input-label"><?php echo $bts_optim['vendor_id'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="control-group" id="form_bts_optim_region">
					<label class="control-label" for="region">Region:</label>
					<div class="controls">
						<span class="input-label"><?php echo $bts_optim['region_name'] ?></span>
					</div>
				</div>
			</div>
		
		</div>
	</div>
	
	<div class="form-vertical">
	
		<div class="widget">
			<div class="widget-head">
				<h4 class="heading"><i></i> Optimization Data</h4>
			</div>
			
			<div class="widget-body">
			
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_visit_date">
							<label class="control-label" for="visitDate">Visit Date:</label>
							<div class="controls">
								<span class="input-small uneditable-input"><?php echo date('d-M-Y', strtotime($bts_optim['visit_date'])) ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_reason_for_change">
							<label class="control-label" for="site">Reason for Change:</label>
							<div class="controls">
								<?php
								$options = array(0 => '');
								$lookup = $this->core->get_lookup('bts_optimization_reason');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span class="input-medium uneditable-input"><?php echo $options[$bts_optim['reason']] ?></span>
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
								<span class="input-xlarge uneditable-input"><?php echo $bts_optim['vendor_engineer'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_sf_engineer">
							<label class="control-label" for="sfEngineer">SF Engineer:</label>
							<div class="controls">
								<span class="input-xlarge uneditable-input"><?php echo $bts_optim['sf_engineer'] ?></span>
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
								<span class="input-medium uneditable-input"><?php echo $bts_optim['vendor_engineer_contact_no'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_sf_engineer_contact_no">
							<label class="control-label" for="sfEngineerContactNo">SF Engineer Contact No.:</label>
							<div class="controls">
								<span class="input-medium uneditable-input"><?php echo $bts_optim['sf_engineer_contact_no'] ?></span>
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
								<span class="input-xlarge uneditable-input"><?php echo $bts_optim['vendor_rigger'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_sf_rigger">
							<label class="control-label" for="sfRigger">SF Rigger:</label>
							<div class="controls">
								<span class="input-xlarge uneditable-input"><?php echo $bts_optim['sf_rigger'] ?></span>
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
								<span class="input-medium uneditable-input"><?php echo $bts_optim['vendor_rigger_contact_no'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_sf_rigger_contact_no">
							<label class="control-label" for="sfRiggerContactNo">SF Rigger Contact No.:</label>
							<div class="controls">
								<span class="input-medium uneditable-input"><?php echo $bts_optim['sf_rigger_contact_no'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_remarks">
							<label class="control-label" for="remarks">Remarks:</label>
							<div class="controls">
								<span class="span12 uneditable-textarea"><?php echo $bts_optim['remarks'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8"></div>
				</div>
			
			</div>
		</div>
		
		<div class="widget">
			<div class="widget-head">
				<h4 class="heading"><i></i> RF Antenna</h4>
			</div>
			
			<div class="widget-body">
				<div style="overflow:auto">
					<table id="tblRF" class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th class="center" rowspan="2">Type</th>
								<th class="center" rowspan="2">Model</th>
								<th class="center" rowspan="2">Brand</th>
								<th class="center" rowspan="2">Cell</th>
								<th class="center" rowspan="2">Band</th>
								<th class="center" rowspan="2">HBWD</th>
								<th class="center" rowspan="2">VBWD</th>
								<th class="center" rowspan="2">Dimension (LxWxH)</th>								
								<th class="center" rowspan="2">Weight</th>
								<th class="center" colspan="2">Height</th>
								<th class="center" colspan="2">Azimuth</th>
								<th class="center" colspan="2">Electrical Tilt</th>
								<th class="center" colspan="2">Mechanical Tilt</th>
							</tr>
							<tr>
								<th class="center">Before</th>
								<th class="center">After</th>
								<th class="center">Before</th>
								<th class="center">After</th>
								<th class="center">Before</th>
								<th class="center">After</th>
								<th class="center">Before</th>
								<th class="center">After</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($bts_optim['rf'] as $idx => $rf) { ?>
							
							<tr>
								<td nowrap>
									<?php
									$lookup = $this->core->get_lookup('antenna_rf_type');
									$options = array(0 => '');						
									foreach ($lookup as $lo) {
										$options[$lo['id']] = $lo['desc'];
									}
									?>
									<span><?php echo $options[$rf['type']] ?></span>
								</td>
								<td nowrap><span><?php echo $rf['model'] ?></span></td>
								<td nowrap><span><?php echo $rf['brand'] ?></span></td>
								<td nowrap>
									<?php
									$options = array(0 => '');
									$lookup = $this->core->get_lookup('antenna_cell');
									foreach ($lookup as $lo) {
										$options[$lo['id']] = $lo['desc'];
									}
									?>
									<span><?php echo $options[$rf['cell']] ?></span>
								</td>
								<td nowrap>
									<?php
									$lookup = $this->core->get_lookup('antenna_band');
									$options = array(0 => '');
									foreach ($lookup as $lo) {
										$options[$lo['id']] = $lo['desc'];
									}
									?>
									<span><?php echo $options[$rf['ant_model']] ?></span>
								</td>
								<td class="right" nowrap>
									<span><?php echo $rf['hbwd'] ?></span>
								</td>
								<td class="right" nowrap>
									<span><?php echo $rf['vbwd'] ?></span>
								</td>
								<td class="right" nowrap>
									<span><?php echo number_format($rf['dimension_long'], 2).' x '.number_format($rf['dimension_width'], 2).' x '.number_format($rf['dimension_height'], 2) ?> mm</span>
								</td>
								<td class="right" nowrap>
									<span><?php echo number_format($rf['weight'], 2) ?> kg</span>
								</td>
								<td class="right" nowrap>
									<span><?php echo number_format($rf['height_before'], 2) ?> M</span>
								</td>
								<td class="right" nowrap>
									<span><?php echo number_format($rf['height_after'], 2) ?> M</span>
								</td>
								<td class="right" nowrap><span><?php echo $rf['azimuth_before'] ?></span></td>
								<td class="right" nowrap><span><?php echo $rf['azimuth_after'] ?></span></td>
								<td class="right" nowrap><span><?php echo $rf['electrical_tilt_before'] ?></span></td>
								<td class="right" nowrap><span><?php echo $rf['electrical_tilt_after'] ?></span></td>
								<td class="right" nowrap><span><?php echo $rf['mechanical_tilt_before'] ?></span></td>
								<td class="right" nowrap><span><?php echo $rf['mechanical_tilt_after'] ?></span></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			
			</div>
		</div>

		<div class="widget">	
			<div class="widget-head">
				<h4 class="heading"><i></i> Photos</h4>
			</div>
			
			<div class="widget-body">
			
				<table id="tblPhoto" class="table table-bordered table-striped table-condensed" style="width:auto">
					<thead>
						<tr>
							<th width="200" class="center">Type</th>
							<th class="center">Photo</th>
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
							
							if ($this->uri->segment(3) == 'view' && isset($bts_optim['visit_id'])) {
								if ( $photo = $this->core->view_row('t_trx_bts_optimization_photo', array('visit_id' => $bts_optim['visit_id'], 'photo_cat' => $lo['id'])) ) {
									if (file_exists($bts_optimization_dir.'/'.$photo['photo_name']) && is_file($bts_optimization_dir.'/'.$photo['photo_name'])) {
										$thumb = $this->image_model->resize($bts_optimization_dir.'/'.$photo['photo_name'], 60, 60, 'crop');
										$image = $bts_optimization_url.'/'.$photo['photo_name'];
										$form_hidden = form_hidden('photoRF'.$lo['id'], $photo['photo_name']);
									}
								}
							}
							
							$photos[] = array(
								'title' => $lo['desc'],
								'image' => '<a id="photo-rf-'.$lo['id'].'-box" href="'.$image.'" class="photobox"><img id="photo-rf-'.$lo['id'].'-thumb" src="'.$thumb.'" class="img-polaroid" /></a>',
								'form_hidden' => $form_hidden,
								'btn_browse' => '<a id="photo-rf-'.$lo['id'].'-browse" class="btn btn-mini" style="inline-block">Browse</a>',
								'btn_delete' => '<a id="photo-rf-'.$lo['id'].'-delete" class="btn btn-mini btn-danger" style="inline-block">Delete</a>',
							
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
							<td><?php echo $photo['image'] ?></td>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			
			</div>
		</div>
	
	</div>
	
</div>

<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script>
$(function() {
	$('.photobox').fancybox({
		openEffect: 'elastic',
		closeEffect: 'elastic',
		closeBtn: true
	});
});

mainMenu();

//var data = [];
//data[0] = 'hello world!';
//data[1] = 'good luck!';
//data[2] = 'bye!';
//eraseCookie('hehehe');
//var c=getCookie('hehehe');
//console.log(c);
//createCookie('hehehe', JSON.stringify(data), 1);
//console.log(data);

//console.log(getCookie('hehehe'));
//console.log(menuChild);
</script>