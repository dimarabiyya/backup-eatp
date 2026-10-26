<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<!--
<div class="heading-buttons">
	<h3>PLN Usage</h3>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/pln') ?>" class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back</a>
	</div>
</div>
<div class="separator top"></div>
-->

<div class="innerLR">
	
	<div class="widget">	
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Site</h4>
			
			<a href="<?php echo site_url('/site_info/pln') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
		</div>
		
		<div class="widget-body">
		
			<div class="form-horizontal">
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_site_id">
							<label class="control-label" for="siteId">Site ID:</label>
							<div class="controls">
								<span class="input-label"><?php echo $pln['site_code'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_site_name">
							<label class="control-label" for="siteName">Site Name:</label>
							<div class="controls">
								<span id="siteName" class="input-label"><?php echo $pln['site_name'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_site_id_ref">
							<label class="control-label" for="siteIdRef">Site ID Ref.:</label>
							<div class="controls">
								<span id="siteIdRef" class="input-label"><?php echo $pln['site_code_old'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_vendor_id">
							<label class="control-label" for="vendorId">Vendor ID:</label>
							<div class="controls">
								<span id="vendorId" class="input-label"><?php echo $pln['vendor_id'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_region">
							<label class="control-label" for="region">Region:</label>
							<div class="controls">
								<span id="region" class="input-label"><?php echo $pln['region_name'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_company_code">
							<label class="control-label">Company Code:</label>
							<div class="controls">
								<span id="companyCode" class="input-label"><?php echo $pln['company_code'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_customer_id">
							<label class="control-label">Customer ID:</label>
							<div class="controls">
								<span id="customerId" class="input-label"><?php echo $pln['id_pelanggan_pln'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_company">
							<label class="control-label">Company:</label>
							<div class="controls">
								<span id="company" class="input-label"><?php echo $pln['company'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_customer_name">
							<label class="control-label">Customer Name:</label>
							<div class="controls">
								<span id="customerName" class="input-label"><?php echo $pln['atas_nama_pelanggan'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_cost_center">
							<label class="control-label">Cost Center:</label>
							<div class="controls">
								<span id="costCenter" class="input-label"><?php echo $pln['cost_center'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_capacity">
							<label class="control-label">Capacity:</label>
							<div class="controls">
								<span id="plnCapacity" class="input-label"><?php echo $pln['pln_capacity'] ?> VA</span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_golongan_tarif">
							<label class="control-label">Gol. Tarif:</label>
							<div class="controls">
								<span id="tariffName" class="input-label"><?php echo $pln['tariff_name'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_tariff_type">
							<label class="control-label">Tipe Tarif:</label>
							<div class="controls">
								<span id="tariffName" class="input-label"><?php echo $pln['tariff_type'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
			</div>
		
		</div>
	</div>
	
	<div class="widget">	
		<div class="widget-head">
			<h4 class="heading"><i></i> PLN Usage Data</h4>
		</div>
		
		<div class="widget-body">
		
			<div class="form-horizontal">
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_payment_date">
							<label class="control-label" for="paymentDate">Payment Date:</label>
							<div class="controls">
								<span class="uneditable-input input-small"><?php echo $pln['tanggal_bayar'] ? date('d-M-Y', strtotime($pln['tanggal_bayar'])) : '' ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_period">
							<label class="control-label" for="month">Period:</label>
							<div class="controls">
								<?php
								$month = array(0 => '', 1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
								?>
								<span class="uneditable-input input-medium"><?php echo $month[$pln['bulan']].' '.$pln['tahun'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						<div class="control-group" id="form_payment_method">
							<label class="control-label" for="paymentMethod">Payment Method:</label>
							<div class="controls">
								<?php
								$lookup = $this->core->get_lookup('payment_method');
								$options = array(0 => '');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span class="uneditable-input input-medium"><?php echo $options[$pln['payment_method_id']] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					<!-- <div class="span8">
						<div class="control-group" id="form_payment_under">
							<label class="control-label" for="paymentUnder">Under Payment:</label>
							<div class="controls">
								<?php
								$paymentUnder = array(0 => '');
								$dataPaymentUnder = get_instance()->core->view_all('t_mtr_lookup', array('lookup_key' => 'payment_under', 'active' => 1), 'order');
								foreach ($dataPaymentUnder as $payment) {
									$paymentUnder[$payment['lookup_value']] = $payment['lookup_desc'];
								}
								?>
								<span class="input-label"><?php echo $paymentUnder[$pln['payment_under_id']] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div> -->
					
					<div class="span8">
						<div class="control-group" id="form_amount">
							<label class="control-label" for="amount">Amount:</label>
							<div class="controls">
								<span class="uneditable-input input-medium right">IDR <?php echo number_format($pln['biaya'], 2) ?></span>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="control-group" id="form_payment_type">
							<label class="control-label" for="paymentType">Payment Type:</label>
							<div class="controls">
								<?php
								$lookup = $this->core->get_lookup('payment_type');
								$options = array(0 => '');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span class="uneditable-input input-medium"><?php echo $options[$pln['payment_type']] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="control-group" id="form_kwh_before">
							<label class="control-label" for="kwhBefore">KWH Before:</label>
							<div class="controls">
								<span class="uneditable-input input-small right"><?php echo number_format($pln['kwh_awal'], 0, '.', '') ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="control-group" id="form_kwh_after">
							<label class="control-label" for="kwhAfter">KWH After:</label>
							<div class="controls">
								<span class="uneditable-input input-small right"><?php echo number_format($pln['kwh_akhir'], 0, '.', '') ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="control-group" id="form_kwh_usage">
							<label class="control-label" for="kwhUsage">KWH Usage:</label>
							<div class="controls">
								<span class="uneditable-input input-small right"><?php echo number_format($pln['pemakaian_kwh'], 0, '.', '') ?></span>
							</div>
						</div>
					</div>
				</div>
				
				<div class="control-group" id="form_remarks">
					<label class="control-label" for="remarks">Remarks:</label>
					<div class="controls">
						<span class="uneditable-textarea span4"><?php echo $pln['remark'] ?></span>
					</div>
				</div>
				
			</div>
			
		</div>
	</div>
	
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading glyphicons paperclip"><i></i> Attachment</h4>
		</div>
		
		<div class="widget-body">
		
			<div class="overflow:auto">
				<table id="tblAttachment" class="table table-striped">
					<tbody>
						<?php 
						if ($this->uri->segment(3) == 'view' && count($pln['attachment']) > 0) {
							$file_counter = 1;
							foreach ($pln['attachment'] as $file) {
						?>					
						<tr id="attachment-row<?php echo $file_counter ?>">
							<td nowrap="nowrap">
								<div id="fileAttachment<?php echo $file_counter ?>" style="text-align:center; width:124px;">
									<?php if ($file['thumb'] != '') { ?>
									<a id="thumb-<?php echo $file_counter ?>" class="real-image" href="<?php echo $file['file'] ?>"><img src="<?php echo $file['thumb'] ?>" class="img-polaroid" /></a>
									<?php } else {?>
									<a id="thumb-<?php echo $file_counter ?>" href="<?php echo $file['file'] ?>" class="btn btn-success"><strong>Download</strong></a>
									<?php } ?>
								</div>
								<input type="hidden" name="file[<?php echo $file_counter ?>]" value="<?php echo $file['attachment'] ?>" />
							</td>
						</tr>
						
						<?php 
								$file_counter++;
							}
						} else { ?>
						
						<tr id="attachment-row1">
							<td colspan="2">
								<div class="alert alert-error">Attachment not available.</div>
								<script>
								document.getElementById('tblAttachment').removeAttribute('class');
								document.getElementById('tblAttachment').style.width = '100%';
								document.getElementsByClassName('alert')[0].style.margin = '0px';
								</script>
							</td>
						</tr>
						<?php } ?>
						
					</tbody>
				</table>
			</div>
		
		</div>
	</div>
	
</div>

<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script>
mainMenu();

$('.real-image').fancybox({
	type: 'image',
	transitionIn : 'elastic',
	transitionOut: 'elastic',
	closeBtn: true
});
</script>