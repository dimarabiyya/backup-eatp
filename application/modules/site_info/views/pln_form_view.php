<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<style>
input.number, input.currency, input.just-number { text-align:right; }
.combo .combo-text { padding:2px 0 2px 2px !important; }
select { width:auto; }
</style>

<!--
<div class="heading-buttons">
	<?php if (get_instance()->uri->segment(3) == 'add') { ?>
	<h3>Add <span>| PLN Usage</span></h3>
	<?php } else { ?>
	<h3>Edit <span>| PLN Usage</span></h3>
	<?php } ?>
	
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
								<?php if ($this->uri->segment(3) == 'edit') { ?>
									
								<span class="input-label"><?php echo $pln['site_code'] ?></span>
								<?php echo @form_hidden('siteId', set_value('siteId', $pln['site_id'])) ?>
								
								<?php } else { ?>
								
								<div class="input-append">
									<?php echo @form_input('siteCode', set_value('siteCode', $pln['site_code']), 'id="siteCode" class="input-medium uneditable" onfocus="doLookupSite(\'siteCode\', \'siteId\')"') ?>
									<?php echo @form_hidden('siteId', set_value('siteId', $pln['site_id'])) ?>
									<button type="button" class="btn" onclick="doLookupSite('siteCode', 'siteId')"><i class="icon-search"></i></button>
								</div>
								<?php } ?>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_site_name">
							<label class="control-label" for="siteName">Site Name:</label>
							<div class="controls">
								<span id="siteName" class="input-label"><?php echo @$pln['site_name'] ?></span>
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
								<span id="siteIdRef" class="input-label"><?php echo @$pln['site_code_old'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_vendor_id">
							<label class="control-label" for="vendorId">Vendor ID:</label>
							<div class="controls">
								<span id="vendorId" class="input-label"><?php echo @$pln['vendor_id'] ?></span>
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
								<span id="region" class="input-label"><?php echo @$pln['region_name'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_company_code">
							<label class="control-label">Company Code:</label>
							<div class="controls">
								<span id="companyCode" class="input-label"><?php echo @$pln['company_code'] ?></span>
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
								<span id="customerId" class="input-label"><?php echo @$pln['id_pelanggan_pln'] ?></span>
								<?php echo form_hidden('customerId', $pln['id_pelanggan_pln']) ?>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span8">
						<div class="control-group" id="form_company">
							<label class="control-label" for="company">Company:</label>
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
								<span id="tariffType" class="input-label"><?php echo $pln['tariff_type'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
			</div>
		
		</div>
	</div>
	
	<?php echo form_open('', 'id="frmPlnUsage" class="form-horizontal"') ?>
	
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading"><i></i> PLN Usage Data</h4>
		</div>
		
		<div class="widget-body">
			
			<div class="row-fluid">					
				<div class="span4">
					<div class="control-group" id="form_payment_date">
						<label class="control-label" for="paymentDate">Payment Date:</label>
						<div class="controls">
							<div class="input-append">
								<?php echo @form_input('paymentDate',  set_value('paymentDate', $pln['tanggal_bayar']), 'id="paymentDate" class="input-small date" placeholder="DD-MMM-YYYY"') ?>
								<span class="add-on"><i class="icon-calendar"></i></span>
							</div>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span8">
					<div class="control-group" id="form_period">
						<label class="control-label" for="month">Period:</label>
						<div class="controls">
							<?php
							$month = array(0 => '- Month -', 1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
							echo @form_dropdown('month',  $month,  set_value('month', $pln['bulan']), 'id="month" onchange="loadDataAfter()"');
							
							$year = array(0 => '- Year -');
							for ($y = date('Y'); $y > (date('Y') - 10); $y--) {
								$year[$y] = $y;
							}
							echo @form_dropdown('year',  $year,  set_value('year', $pln['tahun']), 'id="year" onchange="loadDataAfter()"');
							?>
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
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo form_dropdown('paymentMethod',  $options,  set_value('paymentMethod', $pln['payment_method_id']), 'id="paymentMethod"');
							?>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				<!-- <div class="span8">
					<div class="control-group" id="form_payment_under">
						<label class="control-label" for="paymentUnder">Under Payment:</label>
						<div class="controls">
							<?php
							$paymentUnder = array('0' => '- Choose one -');
							$dataPaymentUnder = get_instance()->core->view_all('t_mtr_lookup', array('lookup_key' => 'payment_under', 'active' => 1), 'order');
							foreach ($dataPaymentUnder as $payment) {
								$paymentUnder[$payment['lookup_value']] = $payment['lookup_desc'];
							}
							echo @form_dropdown('paymentUnder',  $paymentUnder,  set_value('paymentUnder', $pln['payment_under_id']), 'id="paymentUnder"');
							?>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div> -->
				
				<div class="span8">
					<div class="control-group" id="form_amount">
						<label class="control-label" for="amount">Amount:</label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on">IDR</span>
								<?php echo @form_input('amount', set_value('amount', $pln['biaya']), 'id="amount" class="input-small currency"') ?>
							</div>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">					
				<div class="span4">
					<div class="control-group" id="form_payment_type">
						<label class="control-label" for="paymentType">Payment Type:</label>
						<div class="controls">
							<?php
							$lookup = $this->core->get_lookup('payment_type');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo form_dropdown('paymentType',  $options,  set_value('paymentType', $pln['payment_type']), 'id="paymentType"');
							?>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span8">
				
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group" id="form_kwh_before">
						<label class="control-label" for="kwhBefore">KWH Before:</label>
						<div class="controls">
							<?php echo @form_input('kwhBefore', set_value('kwhBefore', $pln['kwh_awal']), 'id="kwhBefore" class="input-small just-number"') ?>
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
							<?php echo @form_input('kwhAfter', set_value('kwhAfter', $pln['kwh_akhir']), 'id="kwhAfter" class="input-small just-number"') ?>
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
							<?php echo @form_hidden('kwhUsage', set_value('kwhUsage', $pln['pemakaian_kwh'])) ?>
							<span class="uneditable-input input-small just-number" id="kwhUsageLabel"><?php echo $pln['pemakaian_kwh'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>	
			</div>
			
			<div class="control-group" id="form_remarks">
				<label class="control-label" for="remarks">Remarks:</label>
				<div class="controls">
					<?php echo @form_textarea('remarks', set_value('remarks', strip_tags($pln['remark'])), 'id="remarks" class="span5" style="height:100px"') ?>
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
						if ($this->uri->segment(3) == 'edit' && count($pln['attachment']) > 0) {
							$file_counter = 1;
							foreach ($pln['attachment'] as $file) {
						?>					
						<tr id="attachment-row<?php echo $file_counter ?>">
							<td nowrap>
								<div id="fileAttachment<?php echo $file_counter ?>" style="text-align:center; width:124px;">
									<?php if ($file['thumb'] != '') { ?>
									<a id="thumb-<?php echo $file_counter ?>" class="real-image" href="<?php echo $file['file'] ?>"><img src="<?php echo $file['thumb'] ?>" class="img-polaroid" /></a>
									<?php } else {?>
									<a id="thumb-<?php echo $file_counter ?>" href="<?php echo $file['file'] ?>" class="btn btn-success"><strong>Download</strong></a>
									<?php } ?>
								</div>
								<input type="hidden" name="file[<?php echo $file_counter ?>]" value="<?php echo $file['attachment'] ?>" />
							</td>
							<td style="white-space:nowrap; vertical-align:middle;">
								<a id="file-attachment-<?php echo $file_counter ?>" class="file-attachment btn" style="display:inline-block">Replace</a>
								<a id="delete-<?php echo $file_counter ?>" href="javascript:void(0)" onclick="ATTACHMENT.removeFile('<?php echo $file['attachment'] ?>', <?php echo $file_counter ?>)" class="btn btn-danger" style="display:inline-block">Delete</a>
							</td>
							<td width="100%" class="right" style="vertical-align:middle;"><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="ATTACHMENT.removeRow(<?php echo $file_counter ?>)"><i></i> Remove</a></td>
						</tr>
						
						<?php 
								$file_counter++;
							}
						} else { ?>
						
						<tr id="attachment-row1">
							<td>
								<div id="fileAttachment1" style="text-align:center; width:124px;"></div>
								<input type="hidden" name="file[1]" />
							</td>
							<td style="white-space:nowrap; vertical-align:middle;">
								<a id="file-attachment-1" class="file-attachment btn">Browse</a>
								<a id="delete-1" href="javascript:void(0)" class="btn btn-danger" style="display:none">Delete</a>
							</td>
							<td width="100%" class="right" style="vertical-align:middle;"><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="ATTACHMENT.removeRow(1)"><i></i> Remove</a></td>
						</tr>
						<?php } ?>
						
					</tbody>
					<tfoot>
						<tr>
							<td colspan="4" class="right"><a class="btn btn-icon glyphicons circle_plus" href="javascript:void(0)" onclick="ATTACHMENT.addRow()"><i></i> Add</a></td>
						</tr>
					</tfoot>
				</table>
			</div>
		
		</div>
	</div>
	
	<div class="form-actions">
		<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
		<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/site_info/pln') ?>'"><i></i>Cancel</button>
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
document.getElementById('frmPlnUsage').onsubmit = function() {

	dateFormat.saveMode();
	currencyFormat.removeFormat();
	
	<?php if (get_instance()->uri->segment(3) == 'add') { ?>
	var url = '<?php echo site_url('/site_info/pln/add') ?>';
	<?php } else { ?>
	var url = '<?php echo site_url('/site_info/pln/edit/'.$this->uri->segment(4)) ?>';
	<?php } ?>
	
	var data = $(this).serialize();
	data += '&siteId=' + document.getElementsByName('siteId')[0].value;
	data += '&customerId=' + document.getElementsByName('customerId')[0].value;
	
	$.ajax({
		url: url,
		type: 'POST',
		data: data + '&act=check_validation',
		dataType: 'json',
		beforeSend: function() {
			$.blockUI({ message: 'Checking validation, please wait...', css: {
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
			var alert = document.getElementsByClassName('alert');
			for (var i = 0; i < alert.length; i++) {
				alert[i].parentNode.removeChild(alert[i]);
			}
			
			if (typeof json.loggedin != 'undefined' && !json.loggedin) {
				$.unblockUI();
				window.alert(json.message);
				window.location.href = json.redirect_url;
			}
			
			if (typeof json.error != 'undefined' && json.error) {
				var message = '\
					<ul style="text-align:left">';
						for (var i = 0; i < json['message'].length; i++) {
							message += '<li>' + json['message'][i] + '</li>';
						}
					message += '</ul>';
				
				coolNotification(message, 'error', function() {
					currencyFormat.init();
				});
			} else if (typeof json.warning != 'undefined' && json.warning) {
				var message = '\
					<ul style="text-align:left">';
						for (var i = 0; i < json['message'].length; i++) {
							message += '<li>' + json['message'][i] + '</li>';
						}
					message += '</ul>';
					message += '<br /><p>Do you want still to continue?</p>';
				
				coolConfirmation(message, function() {
					// Save
					$.ajax({
						url: url,
						type: 'POST',
						data: data + '&act=save',
						dataType: 'json',
						beforeSend: function() {
							$.blockUI({ message: 'Saving, please wait...', css: {
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
							$.unblockUI();
							
							coolNotification(json.message, 'success', function() {
								window.location.href = '<?php echo site_url('/site_info/pln') ?>';
							});
						}
					});
				});
			
			} else {
				// Save
				$.ajax({
					url: url,
					type: 'POST',
					data: data + '&act=save',
					dataType: 'json',
					beforeSend: function() {
						$.blockUI({ message: 'Saving, please wait...', css: {
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
						$.unblockUI();
						
						coolNotification(json.message, 'success', function() {
							window.location.href = '<?php echo site_url('/site_info/pln') ?>';
						});
					}
				});
			}
			
			dateFormat.defaultMode();
		},
		complete: function() {
			$.unblockUI();
		}
	});
	
	return false;
};

var doLookupSite = function(elem1, elem2) { // elem1: shown element, elem2: hidden element
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
					
					document.getElementById('siteIdRef').innerHTML = row.site_code_old;
					document.getElementById('siteName').innerHTML = row.site_name;
					document.getElementById('vendorId').innerHTML = row.vendor_id;
					document.getElementById('region').innerHTML = row.region_name;
					
					$.ajax({
						url: '<?php echo site_url('/site_info/pln') ?>',
						type: 'GET',
						data: { cmd: 'GET_ROW_SITE', siteId: row.site_id },
						dataType: 'json',
						beforeSend: function() {},
						success: function(json) {
							if (typeof json.loggedin != 'undefined' && !json.loggedin) {
								siteOverlay.remove();
								window.alert(json.message);
								window.location.href = json.redirect_url;
							}
							
							document.getElementById('costCenter').innerHTML = json.cost_center != undefined ? json.cost_center : '';
							document.getElementById('customerId').innerHTML = json.id_pelanggan_pln != undefined ? json.id_pelanggan_pln : '';
							document.getElementsByName('customerId')[0].value = json.id_pelanggan_pln != undefined ? json.id_pelanggan_pln : '';
							document.getElementById('companyCode').innerHTML = json.company_code != undefined ? json.company_code : '';
							document.getElementById('company').innerHTML = json.company != undefined ? json.company : '';
							//document.getElementById('paymentUnder').value = json.company_code != undefined ? json.company_code : '';
							document.getElementById('customerName').innerHTML = json.atas_nama_pelanggan != undefined ? json.atas_nama_pelanggan : '';
							document.getElementById('plnCapacity').innerHTML = json.pln_capacity != undefined ? json.pln_capacity + ' VA' : '';
							document.getElementById('tariffName').innerHTML = json.tariff_name != undefined ? json.tariff_name : '';
							document.getElementById('tariffType').innerHTML = json.tariff_type != undefined ? json.tariff_type : '';
						},
						complete: function() {}
					});
				}
				
				$.fancybox.close();
			};
		}
	});
};

var loadDataAfter = function() {
	var siteId = document.getElementsByName('siteId')[0].value;
	var month = document.getElementById('month').value;
	var year = document.getElementById('year').value;
	var date = new Date();
	
	$.ajax({
		url: '<?php echo site_url('/site_info/pln') ?>',
		type: 'GET',
		data: { cmd: 'GET_DATA_AFTER', siteId: siteId, month: month, year: year },
		dataType: 'json',
		beforeSend: function() {},
		success: function(json) {
			var alert = document.getElementsByClassName('alert');
			for (var i = 0; i < alert.length; i++) {
				alert[i].parentNode.removeChild(alert[i]);
			}
			
			if (typeof json.loggedin != 'undefined' && !json.loggedin) {
				siteOverlay.remove();
				window.alert(json.message);
				window.location.href = json.redirect_url;
			}
			
			if (month != 0 && year != 0) {
				//if (date.getMonth() == (month - 1)) {
					document.getElementById('kwhBefore').value = json.kwh_awal != undefined ? json.kwh_akhir : 0;
					document.getElementById('paymentMethod').value = json.payment_method_id != undefined ? json.payment_method_id : 0;
					//document.getElementById('paymentUnder').value = json.payment_under_id != undefined ? json.payment_under_id : 0;
				//}
			}
			
			NUMBER.init();
		},
		complete: function() {}
	});
};

var JUST_NUMBER = {
	fields: document.getElementsByClassName('just-number'),
	init: function() {
		for (var i = 0; i < this.fields.length; i++) {
			// load default
			this.fields[i].value = number_format(this.fields[i].value, 0, '.', '');
			this.fields[i].innerHTML = number_format(this.fields[i].innerHTML, 0, '.', '');
			this.fields[i].style.textAlign = 'right';
			
			// onfocus
			this.fields[i].onfocus = function(e) {
				if ( ! isNumeric(this.value)) {
					setTimeout(function() { e.target.select() });
					return false;
				} else {
					this.value = number_format(this.value, 0, '.', '');
					
					if (this.value == 0) {
						this.value = '';
					}
				}
			};
			
			// onblur
			this.fields[i].onblur = function(e) {
				if ( ! isNumeric(this.value)) {					
					alert('Please enter only numbers or decimal points.');
					setTimeout(function() { e.target.select() });
					return false;
				} else {
					this.value = number_format(this.value, 0, '.', '');
				}
			};
			
			// onkeyup
			this.fields[i].onkeyup = function(e) {
				var code = e.keyCode;
				if ( ! isNumeric(this.value)) {
					if (code != 37 && code != 38 && code != 39 && code != 40 && code != 13 && code != 8 && code != 46) {
						this.blur();
					}
				}
				
				var kwhBefore = document.getElementById('kwhBefore');
				var kwhAfter = document.getElementById('kwhAfter');
				var kwhUsage = document.getElementsByName('kwhUsage')[0];
				var kwhUsageLabel = document.getElementById('kwhUsageLabel');

				if (this.id == 'kwhBefore') {
					kwhUsage.value = number_format((kwhAfter.value - this.value), 0, '.', '');
					kwhUsageLabel.innerHTML = number_format((kwhAfter.value - this.value), 0, '.', '');
				}
				if (this.id == 'kwhAfter') {
					kwhUsage.value = number_format((this.value - kwhBefore.value), 0, '.', '');
					kwhUsageLabel.innerHTML = number_format((this.value - kwhBefore.value), 0, '.', '');
				}
			};
			
			// onkeypress
			this.fields[i].onkeypress = function(e) {
				var code = e.keyCode;
				if ( ! isNumeric(this.value)) {
					if (code == 13) {
						setTimeout(function() { e.target.select() });
						return false;
					}
				}
			};
		}
	},
	removeFormat: function() {
		for (var i = 0; i < this.fields.length; i++) {
			var prec = this.fields[i].value.split('.');
			if (prec[1] == '00') {
				this.fields[i].value = number_format(this.fields[i].value, 0, '', '');
			} else {
				this.fields[i].value = number_format(this.fields[i].value, 2, '.', '');
			}
		}
	}
};

<?php if ($this->uri->segment(3) == 'edit') { ?>
var cmd = 'edit_attachment';
<?php } else { ?>
var cmd = 'add_attachment';
<?php } ?>

<?php if ($this->uri->segment(3) == 'edit' && count($file_counter) > 0) { ?>
var counter = <?php echo $file_counter ?>;
<?php } else { ?>
var counter = 2;
<?php } ?>

var ATTACHMENT = {
	initUpload: function(row) {
		new AjaxUpload('file-attachment-' + row, {
			action: '<?php echo site_url($this->uri->uri_string()) ?>',
			name: 'fileAttachment' + row,
			//data: { cmd: cmd, act: 'UPLOAD', image: document.getElementsByName('file[' + row + ']')[0].value },
			autoSubmit: true,
			responseType: 'json',
			onChange: function(file, extension) {},
			onSubmit: function(file, extension) {
				var image = document.getElementsByName('file[' + row + ']')[0].value;
				if (image.split('.').pop() != extension) {
					image = '';
				}
				
				this.setData({ cmd: cmd, act: 'upload', image: image });
				
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
						document.getElementById(json.ref).innerHTML = '<a href="' + json.file + '" class="btn btn-success"><strong>Download</strong></a>';
					}
					
					document.getElementsByName('file[' + row + ']')[0].value = json.file_name;
					document.getElementById('delete-' + row).setAttribute('onclick',  'ATTACHMENT.removeFile(\'' + json.file_name + '\', ' + row + ')');
					document.getElementById('delete-' + row).style.display = 'inline-block';
					document.getElementById('file-attachment-' + row).innerHTML = 'Replace';
					document.getElementById('file-attachment-' + row).style.display = 'inline-block';
				
				} else {
					coolNotification(json.message, 'error');
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
				data: { cmd: cmd, act: 'delete', file_name: fileName },
				dataType: 'json',
				success: function(json) {
					if ( ! json.error) {
						document.getElementById('delete-' + row).removeAttribute('onclick');
						document.getElementById('delete-' + row).style.display = 'none';
						document.getElementById('file-attachment-' + row).style.display = 'inline-block';
						document.getElementById('file-attachment-' + row).innerHTML = 'Browse';
						document.getElementById('fileAttachment' + row).innerHTML = '';
						document.getElementsByName('file[' + row + ']')[0].value = '';
						
						ATTACHMENT.initUpload(row);
					}
				}
			});
		});
	},
	addRow: function() {
		var html = '\
			<tr id="attachment-row' + counter + '">\
				<td>\
					<div id="fileAttachment' + counter + '" style="text-align:center; width:124px;"></div>\
					<input type="hidden" name="file[' + counter + ']" />\
				</td>\
				<td style="white-space:nowrap; vertical-align:middle;">\
					<a id="file-attachment-' + counter + '" class="file-ref btn">Browse</a>\
					<a id="delete-' + counter + '" href="javascript:void(0)" class="btn btn-danger" style="display:none">Delete</a>\
				</td>\
				<td width="100%" class="right" style="vertical-align:middle;"><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="ATTACHMENT.removeRow(' + counter + ')"><i></i> Remove</a></td>\
			</tr>\
		';
		
		$('#tblAttachment tbody').append(html);
		
		ATTACHMENT.initUpload(counter);
		
		counter++;
	},
	removeRow: function(row) {
		var row = document.getElementById('attachment-row' + row);
		coolConfirmation('Are you sure you want to delete?', function() {			
			row.parentNode.removeChild(row);
		});
	}
};

<?php if ($this->uri->segment(3) == 'edit' && count($pln['attachment']) > 0) { ?>
for (var i = 1; i <= <?php echo ($file_counter - 1) ?>; i++) {
	ATTACHMENT.initUpload(i);
}
<?php } else { ?>
ATTACHMENT.initUpload(1);
<?php } ?>

dateFormat.init();
TEXT.uneditable();
TEXT.clearAutoComplete();
currencyFormat.init();
JUST_NUMBER.init();
mainMenu();

$('.real-image').fancybox({
	type: 'image',
	transitionIn : 'elastic',
	transitionOut: 'elastic',
	closeBtn: true
});
</script>