<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<style>
.table-primary thead th { font-size:12px; }
th a { padding:0 20px 0 0; }
th a, th a:focus { color:#FFFFFF; text-decoration:underline; }
th a.sort-asc { background:url('<?php echo base_url('/themes/sims/images/asc.png') ?>') no-repeat right center; }
th a.sort-desc { background:url('<?php echo base_url('/themes/sims/images/desc.png') ?>') no-repeat right center; }
</style>

<div class="form-horizontal">

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">Site Keeper &amp; Access Permit</h4>
		</div>
		
		<div class="widget-body">
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_site_keeper_availability">
						<label class="control-label">Site Keeper Availability:</label>
						<div class="controls">
							<?php $options = array(0 => 'No', 1 => 'Yes') ?>
							<span class="input-label"><?php echo $options[$site_keeper['site_keeper_availability']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			
				<div class="span6">
					<div class="control-group" id="form_site_keeper_address">
						<label class="control-label" for="siteKeeperAddress">Address:</label>
						<div class="controls">
							<span class="input-label"><?php echo $site_keeper['address'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_site_keeper_name">
						<label class="control-label" for="siteKeeperName">Name:</label>
						<div class="controls">
							<span class="input-label"><?php echo $site_keeper['name'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_site_keeper_salary">
						<label class="control-label" for="siteKeeperSalary">Salary / Month:</label>
						<div class="controls">
							<span class="input-label">IDR <?php echo number_format($site_keeper['salary'], 2) ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_site_keeper_phone">
						<label class="control-label" for="siteKeeperPhone">Phone:</label>
						<div class="controls">
							<span class="input-label"><?php echo $site_keeper['phone'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_site_keeper_hired_by">
						<label class="control-label" for="hiredBy">Hired By:</label>
						<div class="controls form-inline">
							<?php
							$hiredBy = array(0 => '');
							$dataHiredBy = $this->core->get_lookup('company');
							foreach ($dataHiredBy as $company) {
								$hiredBy[$company['id']] = $company['desc'];
							}
							?>
							<span class="input-label"><?php echo $hiredBy[$site_keeper['hired_by']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_site_keeper_access_permit">
						<label class="control-label" for="accessPermit">Access Permit (Hour):</label>
						<div class="controls">
							<span class="input-label"><?php echo $general_info['access_permit'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			
				<div class="span6">
					<div class="control-group" id="form_site_keeper_guard_house_availability">
						<label class="control-label">Guard House Available:</label>
						<div class="controls">
							<?php $options = array(0 => 'No', 1 => 'Yes') ?>
							<span class="input-label"><?php echo $options[$site_keeper['guard_house']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="control-group" id="form_site_keeper_access_permit_detail">
				<label class="control-label">Access Permit Detail:</label>
				<div class="controls">
					<span class="input-label"><?php echo 'from: '.$general_info['access_permit_hour_from'].' to: '.$general_info['access_permit_hour_to'] ?></span>
				</div>
			</div>

			<div class="control-group" id="form_site_keeper_remarks">
				<label class="control-label" for="siteKeepersRemarks">Remarks:</label>
				<div class="controls">                      
					<span class="input-label"><?php echo $site_keeper['remark'] ?></span>
				</div>
			</div>
		
		</div>
	</div>

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">PIC</h4>
		</div>
		
		<div class="widget-body">
			
			<div class="row-fluid">
				<div class="span6"><h4><u>PIC Smartfren</u></h4></div>
				<div class="span6"><h4><u>PIC CME Partner</u></h4></div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_pic_smartfren">
						<label class="control-label" for="picSmart">PIC Smartfren:</label>
						<div class="controls">
							<?php
							$picSmart = array('' => '');
							$dataPicSmart = get_instance()->db
								/*->query("
									SELECT user_id, user_name FROM t_mtr_user mu 
									INNER JOIN t_mtr_user_group ug ON mu.user_group_id = ug.user_group_id
									WHERE ug.user_group_caption = 'PICS'
								")*/
								->query("
									SELECT mrole.user_id, usr.user_name
									FROM t_mtr_user_multi_role mrole
									LEFT JOIN t_mtr_user usr ON usr.user_id = mrole.user_id
									LEFT JOIN t_mtr_user_group grp ON grp.user_group_id = mrole.user_group_id
									WHERE usr.status = 1 AND grp.user_group_caption = 'PICS'
									ORDER BY usr.user_name ASC
								")
								->result_array();
							
							foreach ($dataPicSmart as $pic) {
								$picSmart[$pic['user_id']] = $pic['user_name'];
							}
							?>
							<span class="input-label"><?php echo $picSmart[$general_info['pic_smart']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_pic_maintenance_type">
						<label class="control-label" for="cmeMaintenanceType">Maintenance Type:</label>
						<div class="controls">
							<?php
							$lookup = $this->core->get_lookup('maintenance_type');
							$options = array(0 => '');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							?>
							<span class="input-label"><?php echo $options[$general_info['maintenance_type']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_pic_smartfren_phone">
						<label class="control-label" for="phonePicSmart">Phone PIC Smartfren:</label>
						<div class="controls">
							<span class="input-label"><?php echo $general_info['pic_smart_phone'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>				
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_pic_cme_partner">
						<label class="control-label" for="cmePartner">CME Partner:</label>
						<div class="controls">
							<?php
							$dataCmePartner = get_instance()->core->view_all('t_mtr_partner', array('status' => 1), 'partner_name');
							$cmePartner = array(0 => '');
							foreach ($dataCmePartner as $partner) {
								$cmePartner[$partner['partner_id']] = $partner['partner_name'];
							}
							?>
							<span class="input-label"><?php echo $cmePartner[$general_info['cme_partner_id']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6"></div>
				
				<div class="span6">
					<div class="control-group" id="form_pic_pic_cme_partner">
						<label class="control-label" for="picCmePartner">PIC CME Partner:</label>
						<div class="controls">
							<?php
							$picCmePartner = array(0 => '');
							$dataPicCmePartner = get_instance()->core->view_all('t_mtr_partner_pic', NULL, 'pic_name');
							foreach ($dataPicCmePartner as $pic) {
								$picCmePartner[$pic['partner_pic_id']] = $pic['pic_name'];
							}
							?>
							<span class="input-label"><?php echo $picCmePartner[$general_info['pic_cme_partner']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>				
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6"></div>
				
				<div class="span6">
					<div class="control-group" id="form_pic_cme_partner_phone">
						<label class="control-label" for="picCmePartner">Phone PIC CME Partner:</label>
						<div class="controls">
							<span class="input-label"><?php echo $general_info['pic_cme_partner_phone'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
		</div>
	</div>

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">Colloc Sell</h4>
		</div>
		
		<div class="widget-body">
			<div class="control-group" id="form_colloc_sell_available_for_rent">
				<label class="control-label" for="availableForRent">Available for Rent:</label>
				<div class="controls">
					<?php
					$availableForRent = array('' => '', 0 => 'No', 1 => 'Yes');
					?>
					<span class="input-label"><?php echo $availableForRent[$tenancy_sell['available_for_rent']] ?></span>
				</div>
			</div>

			<div style="overflow:auto">
				<table id="tblCollocSell" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th width="50%">Tenant:</th>
							<th>Date:</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($this->uri->segment(2) == 'view' && count($tenancy_sell['detail']) > 0) {
						
							$sell_counter = 1;
							foreach ($tenancy_sell['detail'] as $detail) {
						?>
						<tr id="sellTenantRow<?php echo $sell_counter ?>">
							<td>
								<?php
								$tenantCollocSell = array(0 => '');
								$dataTenantCollocSell = get_instance()->core->view_all('t_mtr_tenant', array('status' => 1), 'tenant_name');
								foreach ($dataTenantCollocSell as $tenant) {
									$tenantCollocSell[$tenant['tenant_id']] = $tenant['tenant_name'];
								}
								?>
								<span><?php echo $tenantCollocSell[$detail['tenant_id']] ?></span>
							</td>
							<td>
								<span><?php echo $detail['tenancy_date'] != '' ? date('d-M-Y', strtotime($detail['tenancy_date'])) : '&nbsp;' ?></span>
							</td>
						</tr>
						
						<?php 
								$sell_counter++;
							}
						}
						?>
					</tbody>
				</table>
			</div>
			
		</div>
	</div>

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">Colloc Buy</h4>
		</div>
		
		<div class="widget-body">
			<div class="control-group" id="form_colloc_buy_seq_number_tenant">
				<label class="control-label" for="seqNumberOfTenant">Seq. Number of Tenant:</label>
				<div class="controls">
					<span class="input-label"><?php echo $tenancy_buy['seq_number'] ?></span>
				</div>
			</div>

			<div style="overflow:auto">
				<table id="tblCollocBuy" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th width="50%">Tenant:</th>
							<th>Date:</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($this->uri->segment(2) == 'view' && count($tenancy_buy['detail']) > 0) {
						
							$buy_counter = 1;
							foreach ($tenancy_buy['detail'] as $detail) {
						?>
						
						<tr id="buyTenantRow<?php echo $buy_counter ?>">
							<td>
								<?php
								$tenantCollocBuy = array('' => '');
								$dataTenantCollocBuy = get_instance()->core->view_all('t_mtr_tenant', array('status' => 1), 'tenant_name');
								foreach ($dataTenantCollocBuy as $tenant) {
									$tenantCollocBuy[$tenant['tenant_id']] = $tenant['tenant_name'];
								}
								?>
								<span><?php echo $tenantCollocBuy[$detail['tenant_id']] ?></span>
							</td>
							<td>
								<span><?php echo $detail['tenancy_date'] != '' ? date('d-M-Y', strtotime($detail['tenancy_date'])) : '&nbsp;' ?></span>
							</td>
						</tr>
						
						<?php
								$buy_counter++;
							}
						}
						?>
					</tbody>
				</table>
			</div>
			
		</div>
	</div>

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">Insurance</h4>
		</div>
		
		<div class="widget-body">
		
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_insurance_cme_insured">
						<label class="control-label" for="cmeInsured">CME Insured:</label>
						<div class="controls">
							<?php
							$cmeInsured = array('' => '', 0 => 'No', 1 => 'Yes');
							?>
							<span class="input-label"><?php echo $cmeInsured[$general_info['cme_insured']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
					
				<div class="span6">
					<div class="control-group" id="form_insurance_cme_polis_number">
						<label class="control-label" for="cmePolisNumber">CME Polis Number:</label>
						<div class="controls">
							<span class="input-label"><?php echo $general_info['cme_polis_number'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_insurance_equipment_insured">
						<label class="control-label" for="equipmentInsured">Equipment Insured:</label>
						<div class="controls">
							<?php
							$equipmentInsured = array('' => '', 0 => 'No', 1 => 'Yes');
							?>
							<span class="input-label"><?php echo $equipmentInsured[$general_info['equipment_insured']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_insurance_equipment_polis_number">
						<label class="control-label" for="equipmentPolisNumber">Equipment Polis No.:</label>
						<div class="controls">
							<span class="input-label"><?php echo $general_info['equipment_polis_number'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
		
		</div>
	</div>
	
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">On Air / Off Air</h4>
		</div>
		
		<div class="widget-body">
			
			<div class="overflow:auto">
				<table id="tblSiteStatus" class="table table-bordered table-condensed" style="width:auto">
					<thead>
						<tr>
							<th class="center">Date</th>
							<th class="center">Status</th>
							<th class="center">Remarks</th>
							<th class="center">Created Date</th>
							<th class="center">Created By</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($site_last_status as $status) { ?>
						<tr>
							<td><?php echo date('d-M-Y', strtotime($status['date_status'])) ?></td>
							<td><?php echo $status['status'] ?></td>
							<td><?php echo $status['remarks'] ?></td>
							<td><?php echo $status['created_on'] != '' ? date('d-M-Y H:i', strtotime($status['created_on'])) : '&nbsp;' ?></td>
							<td><?php echo $status['created_by'] ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">Site Status</h4>
		</div>
		
		<div class="widget-body">
			
			<div class="overflow:auto">
				<table id="tblSiteStatus" class="table table-bordered table-condensed" style="width:auto">
					<thead>
						<tr>
							<th class="center">Date</th>
							<th class="center">Status</th>
							<th class="center">Remarks</th>
							<th class="center">Created Date</th>
							<th class="center">Created By</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($site_status as $status) { ?>
						<tr>
							<td><?php echo date('d-M-Y', strtotime($status['date_status'])) ?></td>
							<td><?php echo $status['status'] == 1 ? 'Active' : 'Inactive' ?></td>
							<td><?php echo $status['remarks'] ?></td>
							<td><?php echo $status['created_on'] != '' ? date('d-M-Y H:i', strtotime($status['created_on'])) : '&nbsp;' ?></td>
							<td><?php echo $status['created_by'] ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">Problem History</h4>
		</div>
		
		<div class="widget-body">
			<div class="dataTables_wrapper form-search">
				<div class="row-fluid" style="padding:0 0 15px">
					<div class="span6">
						<div class="dataTables_length">
							<label>Show 
								<select name="perPage" id="perPage" style="width:80px" onchange="listProblemHistory.changePerPage()">
									<option value="10">10</option>
									<option value="25">25</option>
									<option value="50">50</option>
									<option value="100">100</option>
								</select> entries
							</label>
						</div>
					</div>
					<div class="span6">
						<div class="input-append pull-right">
							<input type="text" style="margin:0px; padding:4px;" class="search-query input-medium" id="query" name="query" placeholder="Search..." autocomplete="off" />
							<input type="button" onclick="return listProblemHistory.search();" value="Search" class="btn" />
						</div>
					</div>
				</div>
				
				<div style="overflow:auto">
					<table id="tblProblem" class="table table-bordered table-primary table-vertical-center">
						<thead>
							<tr>
								<th><a href="javascript:void(0)" data-order="open_date">Open Date</a></th>
								<th><a href="javascript:void(0)" data-order="problem_group">Group</a></th>
								<th><a href="javascript:void(0)" data-order="problem_descr">Problem Description</a></th>
								<th><a href="javascript:void(0)" data-order="impact">Impact</a></th>
								<th><a href="javascript:void(0)" data-order="status">Status</a></th>
								<th><a href="javascript:void(0)" data-order="close_date">Close Date</a></th>
								<th><a href="javascript:void(0)" data-order="issued_by">Issued By</a></th>
								<th class="center">Action</th>
							</tr>
						</thead>
						<tbody id="document-data"></tbody>
					</table>
				</div>
				
				<div class="row-fluid">
					<div class="span6">
						<div class="dataTables_info" id="recinfoArea" style="padding:20px 0"></div>
					</div>
					<div class="span6">
						<div class="dataTables_paginate paging_bootstrap pagination" id="paginationProblemHistory"></div>
					</div>
				</div>

			</div>

		</div>
	</div>

</div>

<script>
var listProblemHistory = {
	params: {
		cmd: '',
		per_page: 10,
		query: ''
	},
	url: '<?php echo site_url('/site_info') ?>',
	loadData: function() {
		this.params.cmd = 'LOAD_DATA_PROBLEM';
		this.params.site_id = <?php echo (int) $this->core->decode($this->uri->segment(3)) ?>;
		this.params.per_page = document.getElementById('perPage').value;
		
		$.ajax({
			url: this.url,
			data: this.params,
			dataType: 'json',
			beforeSend: function() {},
			success: function(json) {
				if (typeof json.loggedin != 'undefined' && !json.loggedin) {
					alert(json.message);
					window.location.href = json.redirect_url;
				}
				
				var html = ''; var tooltip = '';
				for (var i = 0; i < json.data.length; i++) {
					html += '\
						<tr>\
							<td>' + json.data[i].open_date + '</td>\
							<td>' + (json.data[i].problem_group != null ? json.data[i].problem_group : '&nbsp;') + '</td>\
							<td>' + json.data[i].problem_descr + '</td>\
							<td>' + json.data[i].impact + '</td>\
							<td>' + (json.data[i].status == 1 ? 'Open' : 'Closed') + '</td>\
							<td>' + (json.data[i].close_date != null ? json.data[i].close_date : '&nbsp') + '</td>\
							<td>' + json.data[i].issued_by + '</td>\
							<td class="center"><a id="tooltip-' + i + '" href="javascript:void(0)" class="btn-action glyphicons eye_open btn-success clickTip"><i></i></a>\</td>\
						</tr>\
					';
					
					tooltip += '\
						<div id="tooltip-content-' + i + '" class="tooltip-content" style="width:600px; background:#7F7C7B; color:#ffffff; border:2px solid #444444; box-shadow:2px 2px 5px #111111; border-radius:12px 12px 0px 12px; padding:4px; position:absolute; z-index:99999; display:none;">\
							<div style="text-align:right">[<a href="javascript:void(0)" onclick="$(\'#tooltip-content-' + i + '\').hide()">Close</a>]</div>\
							<h4 style="color:#ffffff">Problem Description</h4>\
							<table class="table table-bordered">\
								<tr>\
									<th>Description</th>\
									<th>To</th>\
								</tr>';
									for (var j = 0; j < json.data[i].detail.length; j++) {
										tooltip += '<tr><td>' + json.data[i].detail[j].problem_detail_desc + '</td>';
										tooltip += '<td>' + json.data[i].detail[j].recipe_to + '</td></tr>';
									}
							tooltip += '\
							</table>\
						</div>\
					';
				}
				
				document.getElementById('document-data').innerHTML = html;
				document.getElementById('recinfoArea').innerHTML = json.rec_info;
				document.getElementById('paginationProblemHistory').innerHTML = json.pagination;
				$('body').append(tooltip);
				
				for (var i = 0; i < json.data.length; i++) {
					(function(idx) {
						document.getElementById('tooltip-' + idx).onclick = function(e) {
							
							$('#tooltip-content-' + idx).show();
							
							var elemHeight = document.getElementById('tooltip-content-0').offsetHeight;
							$('#tooltip-content-' + idx).css({ top: e.pageY - elemHeight, left: e.pageX - 610 });
							$('.tooltip-content').hide();
							$('#tooltip-content-' + idx).show();
							
						};
					})(i);
				}
			},
			complete: function() {}
		});
	},
	search: function() {
		this.params.cmd = 'LOAD_DATA_PROBLEM';
		this.params.query = document.getElementById('query').value;
		this.url = '<?php echo site_url('/site_info') ?>';
		this.loadData();
		
		return false;
	},
	changePerPage: function() {
		this.url = '<?php echo site_url('/site_info') ?>';
		this.loadData();
	},
	order: function() {
		var sort = 'DESC';
		var sortClass = 'sort-desc';

		$('#tblProblem th a').live('click', function() {
			if (sort == 'DESC') {
				sort = 'ASC';
				sortClass = 'sort-asc';
			} else {
				sort = 'DESC';
				sortClass = 'sort-desc';
			}
			
			$('#tblProblem th a').removeClass('sort-asc');
			$('#tblProblem th a').removeClass('sort-desc');
			
			$(this).addClass(sortClass);
			
			listProblemHistory.params.cmd = 'LOAD_DATA_PROBLEM';
			listProblemHistory.params.order = this.getAttribute('data-order');
			listProblemHistory.params.sort = sort;
			listProblemHistory.loadData();
		});
	},
	pagination: function() {
		$('#paginationProblemHistory a').live('click', function() {
			listProblemHistory.params.cmd = 'LOAD_DATA_PROBLEM';
			listProblemHistory.url = this.href;
			listProblemHistory.loadData();
			
			return false;
		});
	},
	init: function() {
		this.loadData();
		this.order();
		this.pagination();
	}
};

document.getElementById('query').onkeydown = function(e) {
	if (e.keyCode == 13) {
		listProblemHistory.params.cmd = 'LOAD_DATA_PROBLEM';
		listProblemHistory.params.query = document.getElementById('query').value;
		listProblemHistory.url = '<?php echo site_url('/site_info') ?>';
		listProblemHistory.loadData();
		
		return false;
	}
};

listProblemHistory.init();
</script>