<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<style>
.table-primary thead th { font-size:12px; }
th a { padding:0 20px 0 0; }
th a, th a:focus { color:#FFFFFF; text-decoration:underline; }
th a.sort-asc { background:url('<?php echo base_url('/themes/sims/images/asc.png') ?>') no-repeat right center; }
th a.sort-desc { background:url('<?php echo base_url('/themes/sims/images/desc.png') ?>') no-repeat right center; }
</style>

<?php echo form_open('', 'id="formOperational" class="form-horizontal"') ?>

<?php echo form_hidden('siteId', set_value('siteId', '')) ?>

<div class="top-form-actions">
	<button class="btn btn-primary btn-small btn-icon glyphicons circle_ok" name="btnSave"><i></i> Save</button>
	<button class="btn btn-primary btn-small btn-icon glyphicons circle_ok" name="btnSaveExit"><i></i> Save &amp; Exit</button>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">Site Keeper &amp; Access Permit</h4>
	</div>
	
	<div class="widget-body">
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_site_keeper_availability">
					<label class="control-label">Site Keeper Availability:</label>
					<div class="controls form-inline">
						<label class="radio inline"><?php echo @form_radio('siteKeeperAvailability', 1, $site_keeper['site_keeper_availability'] == 1) ?> Yes</label>
						&nbsp;
						<label class="radio inline"><?php echo @form_radio('siteKeeperAvailability', 0, $site_keeper['site_keeper_availability'] == 0) ?> No</label>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		
			<div class="span8">
				<div class="control-group" id="form_site_keeper_address">
					<label class="control-label" for="siteKeeperAddress">Address:</label>
					<div class="controls">
						<?php echo @form_textarea('siteKeeperAddress', set_value('siteKeeperAddress', trim($site_keeper['address'])), 'id="siteKeeperAddress" style="width:206px; height:60px;"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_site_keeper_name">
					<label class="control-label" for="siteKeeperName">Name:</label>
					<div class="controls">
						<?php echo @form_input('siteKeeperName', set_value('siteKeeperName', trim($site_keeper['name'])), 'id="siteKeeperName"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_site_keeper_salary">
					<label class="control-label" for="siteKeeperSalary">Salary / Month:</label>
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">IDR</span>
							<?php echo @form_input('siteKeeperSalary', set_value('siteKeeperSalary', $site_keeper['salary']), 'id="siteKeeperSalary" class="input-small currency"') ?>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_site_keeper_phone">
					<label class="control-label" for="siteKeeperPhone">Phone:</label>
					<div class="controls">
						<?php echo @form_input('siteKeeperPhone', set_value('siteKeeperPhone', $site_keeper['phone']), 'id="siteKeeperPhone" class="input-small') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_site_keeper_hired_by">
					<label class="control-label" for="hiredBy">Hired By:</label>
					<div class="controls form-inline">
						<?php
						$hiredBy = array(0 => '- Choose one -');
						$dataHiredBy = $this->core->get_lookup('company');
						foreach ($dataHiredBy as $company) {
							$hiredBy[$company['id']] = $company['desc'];
						}
						echo @form_dropdown('hiredBy', $hiredBy, set_value('hiredBy', $site_keeper['hired_by']));
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_site_keeper_access_permit">
					<label class="control-label" for="accessPermit">Access Permit (Hour):</label>
					<div class="controls">
						<?php echo @form_input('accessPermit', set_value('accessPermit', $general_info['access_permit']), 'id="accessPermit" class="input-mini just-number"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		
			<div class="span8">
				<div class="control-group" id="form_site_keeper_guard_house_availability">
					<label class="control-label">Guard House Available:</label>
					<div class="controls form-inline">
						<label class="radio inline"><?php echo @form_radio('guardHouse', 1, $site_keeper['guard_house'] == 1) ?> Yes</label>
						&nbsp;
						<label class="radio inline"><?php echo @form_radio('guardHouse', 0, $site_keeper['guard_house'] == 0) ?> No</label>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="control-group" id="form_site_keeper_access_permit_detail">
			<label class="control-label">Access Permit Detail:</label>
			<div class="controls controls-row">
				<label style="width:30px; margin:0 10px 0 0" class="control-label">from:</label>
				<div class="input-append" style="float:left">
					<?php echo @form_input('accessPermitHourFrom', set_value('accessPermitHourFrom', $general_info['access_permit_hour_from']), 'id="accessPermitHourFrom" class="input-mini time"') ?>
					<span class="add-on"><i class="icon-time"></i></span>
				</div>
				
				<label style="width:30px; margin:0 10px;" class="control-label">to:</label>
				<div class="input-append" style="float:left">
					<?php echo @form_input('accessPermitHourTo', set_value('accessPermitHourTo', $general_info['access_permit_hour_to']), 'id="accessPermitHourTo" class="input-mini time"') ?>
					<span class="add-on"><i class="icon-time"></i></span>
				</div>
			</div>
		</div>

		<div class="control-group" id="form_site_keeper_remarks">
			<label class="control-label" for="siteKeepersRemarks">Remarks:</label>
			<div class="controls">                      
				<?php echo @form_textarea('siteKeepersRemarks', set_value('siteKeepersRemarks', trim($site_keeper['remark'])), 'id="siteKeepersRemarks" class="span4" style="height:60px;"') ?>
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
			<div class="span4"><h4><u>PIC Smartfren</u></h4></div>
			<div class="span8"><h4><u>PIC CME Partner</u></h4></div>
		</div>
		
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_pic_smartfren">
					<label class="control-label" for="picSmart">PIC Smartfren:</label>
					<div class="controls">
						<?php
						$picSmart = array('' => '- Choose one -');
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
						//echo @form_dropdown('picSmart', $picSmart, set_value('picSmart', trim($general_info['pic_smart'])), 'id="picSmart" class="medium"');
						echo form_hidden('picSmart', $general_info['pic_smart']);
						?>
						<span class="input-label"><?php echo  $general_info['pic_smart'];//$picSmart[$general_info['pic_smart']] ?></span>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_pic_maintenance_type">
					<label class="control-label" for="cmeMaintenanceType">Maintenance Type:</label>
					<div class="controls">
						<?php
						$lookup = $this->core->get_lookup('maintenance_type');
						$options = array(0 => '- Choose one -');
						foreach ($lookup as $lo) {
							$options[$lo['id']] = $lo['desc'];
						}
						echo @form_dropdown('cmeMaintenanceType', $options, $general_info['maintenance_type'], 'class="input-large"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_pic_smartfren_phone">
					<label class="control-label" for="phonePicSmart">Phone PIC Smartfren:</label>
					<div class="controls">
						<?php
						//echo @form_input('phonePicSmart', set_value('phonePicSmart', $general_info['pic_smart_phone']), 'id="phonePicSmart" class="input-small"');
						
						$res = $this->db->query("SELECT mobile_number FROM t_mtr_user WHERE user_id = '".$general_info['pic_smart']."'")->row_array();
						echo form_hidden('phonePicSmart', $res['mobile_number']);
						?>
						<span class="input-label"><?php echo $res['mobile_number'] ?></span>
					</div>
					<div class="separator bottom"></div>
				</div>				
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_pic_cme_partner">
					<label class="control-label" for="cmePartner">CME Partner:</label>
					<div class="controls">
						<?php
						$dataCmePartner = get_instance()->core->view_all('t_mtr_partner', array('status' => 1), 'partner_name');
						$cmePartner = array(0 => '- Choose one -');
						foreach ($dataCmePartner as $partner) {
							$cmePartner[$partner['partner_id']] = $partner['partner_name'];
						}
						echo @form_dropdown('cmePartner', $cmePartner, set_value('cmePartner', $general_info['cme_partner_id']), 'id="cmePartner"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4"></div>
			
			<div class="span8">
				<div class="control-group" id="form_pic_pic_cme_partner">
					<label class="control-label" for="picCmePartner">PIC CME Partner:</label>
					<div class="controls">
						<?php
						$picCmePartner = array('0' => '- Choose one -');
						$dataPicCmePartner = get_instance()->core->view_all('t_mtr_partner_pic', NULL, 'pic_name');
						foreach ($dataPicCmePartner as $pic) {
							$picCmePartner[$pic['partner_pic_id']] = $pic['pic_name'];
						}
						echo @form_dropdown('picCmePartner', $picCmePartner, set_value('picCmePartner', trim($general_info['pic_cme_partner'])), 'id="picCmePartner" class="medium"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>				
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4"></div>
			
			<div class="span8">
				<div class="control-group" id="form_pic_cme_partner_phone">
					<label class="control-label" for="picCmePartner">Phone PIC CME Partner:</label>
					<div class="controls">
						<?php echo @form_input('phonePicCmePartner', set_value('phonePicCmePartner', $general_info['pic_cme_partner_phone']), 'id="phonePicCmePartner" class="input-small"') ?>
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
			<label class="control-label" for="availableForRent" style="text-align:left">Available for Rent:</label>
			<div class="controls">
				<?php
				$availableForRent = array('' => '- Choose one -', 0 => 'No', 1 => 'Yes');
				echo @form_dropdown('availableForRent', $availableForRent, set_value('availableForRent', $tenancy_sell['available_for_rent']), 'id="availableForRent"');
				?>
			</div>
		</div>

		<div style="overflow:auto">
			<table id="tblCollocSell" class="table table-bordered table-condensed" style="width:auto">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th class="center">Tenant</th>
						<th class="center">Date</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($this->uri->segment(2) == 'edit' && count($tenancy_sell['detail']) > 0) {
					
						$sell_counter = 1;
						foreach ($tenancy_sell['detail'] as $detail) {
					?>
					<tr id="sellTenantRow<?php echo $sell_counter ?>">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="collocSell.removeRow(<?php echo $sell_counter ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							$tenantCollocSell = array(0 => '- Choose one -');
							$dataTenantCollocSell = get_instance()->core->view_all('t_mtr_tenant', array('status' => 1), 'tenant_name');
							foreach ($dataTenantCollocSell as $tenant) {
								$tenantCollocSell[$tenant['tenant_id']] = $tenant['tenant_name'];
							}
							echo @form_dropdown('tenantCollocSell[]', $tenantCollocSell, set_value('tenantCollocSell[]', $detail['tenant_id']));
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('tenantDateCollocSell[]', set_value('tenantDateCollocSell[]', $detail['tenancy_date']), 'id="tenantCollocSellDate-'.$sell_counter.'" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
								<span class="add-on"><i class="icon-calendar"></i></span>
								<!-- <button onclick="clearField('tenantCollocSellDate-<?php echo $sell_counter ?>')" type="button" class="btn">Clear</button> -->
							</div>
						</td>
					</tr>
					
					<?php 
							$sell_counter++;
						}
					} else { ?>
					
					<tr id="sellTenantRow1">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="collocSell.removeRow(1)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							$tenantCollocSell = array(0 => '- Choose one -');
							$dataTenantCollocSell = get_instance()->core->view_all('t_mtr_tenant', array('status' => 1), 'tenant_name');
							foreach ($dataTenantCollocSell as $tenant) {
								$tenantCollocSell[$tenant['tenant_id']] = $tenant['tenant_name'];
							}
							echo @form_dropdown('tenantCollocSell[]', $tenantCollocSell, '', 'id="tenantCollocSell-1"');
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('tenantDateCollocSell[]', set_value('tenantDateCollocSell', ''), 'id="tenantCollocSellDate-1" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
								<span class="add-on"><i class="icon-calendar"></i></span>
								<!-- <button onclick="clearField('tenantCollocSellDate-1')" type="button" class="btn">Clear</button> -->
							</div>
						</td>
					</tr>
					
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3" class="left"><a class="btn btn-success btn-small" href="javascript:void(0)" onclick="collocSell.addRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></a></td>
					</tr>
				</tfoot>
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
			<label class="control-label" for="seqNumberOfTenant" style="text-align:left">Seq. Number of Tenant:</label>
			<div class="controls">
				<?php echo @form_input('seqNumberOfTenant', set_value('seqNumberOfTenant', $tenancy_buy['seq_number']), 'id="seqNumberOfTenant" class="input-mini just-number"') ?>
			</div>
		</div>

		<div style="overflow:auto">
			<table id="tblCollocBuy" class="table table-bordered table-condensed" style="width:auto">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th class="center">Tenant</th>
						<th class="center">Date</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($this->uri->segment(2) == 'edit' && count($tenancy_buy['detail']) > 0) {
					
						$buy_counter = 1;
						foreach ($tenancy_buy['detail'] as $detail) {
					?>
					
					<tr id="buyTenantRow<?php echo $buy_counter ?>">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="collocBuy.removeRow(<?php echo $buy_counter ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							$tenantCollocBuy = array(0 => '- Choose one -');
							$dataTenantCollocBuy = get_instance()->core->view_all('t_mtr_tenant', array('status' => 1), 'tenant_name');
							foreach ($dataTenantCollocBuy as $tenant) {
								$tenantCollocBuy[$tenant['tenant_id']] = $tenant['tenant_name'];
							}
							echo @form_dropdown('tenantCollocBuy[]', $tenantCollocBuy, set_value('tenantCollocBuy[]', $detail['tenant_id']), 'id="tenantCollocBuy-'.$i.'"');
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('tenantDateCollocBuy[]', set_value('tenantDateCollocBuy', $detail['tenancy_date']), 'id="tenantCollocBuyDate-'.$buy_counter.'" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
								<span class="add-on"><i class="icon-calendar"></i></span>
								<!-- <button onclick="clearField('tenantCollocBuyDate-<?php echo $buy_counter ?>')" type="button" class="btn">Clear</button> -->
							</div>
						</td>
					</tr>
					
					<?php 		$buy_counter++;
							}
						} else {
					?>
					
					<tr id="buyTenantRow1">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="collocBuy.removeRow(1)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							$tenantCollocBuy = array(0 => '- Choose one -');
							$dataTenantCollocBuy = get_instance()->core->view_all('t_mtr_tenant', array('status' => 1), 'tenant_name');
							foreach ($dataTenantCollocBuy as $tenant) {
								$tenantCollocBuy[$tenant['tenant_id']] = $tenant['tenant_name'];
							}
							echo @form_dropdown('tenantCollocBuy[]', $tenantCollocBuy, set_value('tenantCollocBuy[]', ''));
							?>
						</td>
						<td>
							<div class="input-append">
								<?php echo @form_input('tenantDateCollocBuy[]', set_value('tenantDateCollocBuy', ''), 'id="tenantCollocBuyDate-1" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
								<span class="add-on"><i class="icon-calendar"></i></span>
								<!-- <button onclick="clearField('tenantCollocBuyDate-1')" type="button" class="btn">Clear</button> -->
							</div>
						</td>
					</tr>
					
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3" class="left"><a class="btn btn-success btn-small" href="javascript:void(0)" onclick="collocBuy.addRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></a></td>
					</tr>
				</tfoot>
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
			<div class="span4">
				<div class="control-group" id="form_insurance_cme_insured">
					<label class="control-label" for="cmeInsured">CME Insured:</label>
					<div class="controls">
						<?php
						$cmeInsured = array('' => '- Choose one -', 0 => 'No', 1 => 'Yes');
						echo @form_dropdown('cmeInsured', $cmeInsured, set_value('cmeInsured', $general_info['cme_insured']), 'id="cmeInsured"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
				
			<div class="span8">
				<div class="control-group" id="form_insurance_cme_polis_number">
					<label class="control-label" for="cmePolisNumber">CME Polis Number:</label>
					<div class="controls">
						<?php echo @form_input('cmePolisNumber', set_value('cmePolisNumber', $general_info['cme_polis_number']), 'id="cmePolisNumber" class="uppercase"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_insurance_equipment_insured">
					<label class="control-label" for="equipmentInsured">Equipment Insured:</label>
					<div class="controls">
						<?php
						$equipmentInsured = array('' => '- Choose one -', 0 => 'No', 1 => 'Yes');
						echo @form_dropdown('equipmentInsured', $equipmentInsured, set_value('equipmentInsured', $general_info['equipment_insured']), 'id="equipmentInsured"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_insurance_equipment_polis_number">
					<label class="control-label" for="equipmentPolisNumber">Equipment Polis No.:</label>
					<div class="controls">
						<?php echo @form_input('equipmentPolisNumber', set_value('equipmentPolisNumber', $general_info['equipment_polis_number']), 'id="equipmentPolisNumber" class="uppercase"') ?>
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
		
		<div class="control-group">
			<label class="control-label" for="siteStatus" style="text-align:left">Status:</label>
			<div class="controls">
				<?php
				$options = array(1 => 'Active', 0 => 'Inactive');
				echo form_dropdown('siteStatus', $options, $general_info['site_status'], 'id="siteStatus"');
				?>
			</div>
		</div>
		
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
					<?php
					if (is_array($site_last_status)) {
						foreach ($site_last_status as $status) { ?>
						<tr>
							<td><?php echo date('d-M-Y', strtotime($status['date_status'])) ?></td>
							<td><?php echo $status['status'] ?></td>
							<td><?php echo $status['remarks'] ?></td>
							<td><?php echo $status['created_on'] != '' ? date('d-M-Y H:i', strtotime($status['created_on'])) : '&nbsp;' ?></td>
							<td><?php echo $status['created_by'] ?></td>
						</tr>
						<?php
						}
					} ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5">
							<button type="button" id="btnChangeStatus" class="btn btn-success btn-small" href="javascript:void(0)" onclick="SITE_LAST_STATUS.change()" title="Change Status"><i class="icon-pencil icon-white"></i> Change Status</button>
							<button type="button" id="btnCancelStatus" class="btn btn-danger btn-small" href="javascript:void(0)" onclick="SITE_LAST_STATUS.cancel()" title="Cancel" style="display:none"><i class="icon-remove icon-white"></i> Cancel</button>
						</td>
					</tr>
				</tfoot>
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
			<table id="tblStatusOfSite" class="table table-bordered table-condensed" style="width:auto">
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
					<?php
					if (is_array($site_status)) {
						foreach ($site_status as $status) { ?>
						<tr>
							<td><?php echo date('d-M-Y', strtotime($status['date_status'])) ?></td>
							<td><?php echo $status['status'] == 1 ? 'Active' : 'Inactive' ?></td>
							<td><?php echo $status['remarks'] ?></td>
							<td><?php echo $status['created_on'] != '' ? date('d-M-Y H:i', strtotime($status['created_on'])) : '&nbsp;' ?></td>
							<td><?php echo $status['created_by'] ?></td>
						</tr>
						<?php
						}
					} ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5">
							<button type="button" id="btnChangeStatusOfSite" class="btn btn-success btn-small" href="javascript:void(0)" onclick="STATUS_OF_SITE.change()" title="Change Status"><i class="icon-pencil icon-white"></i> Change Status</button>
							<button type="button" id="btnCancelStatusOfSite" class="btn btn-danger btn-small" href="javascript:void(0)" onclick="STATUS_OF_SITE.cancel()" title="Cancel" style="display:none"><i class="icon-remove icon-white"></i> Cancel</button>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<?php if ($this->uri->segment(2) == 'edit') { ?>

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


<?php } ?>

<?php echo form_close() ?>

<script>
var btnAction = '';
for (var i = 0; i < document.getElementsByName('btnSave').length; i++) {
	document.getElementsByName('btnSave')[i].onclick = function() {
		btnAction = this.name;
	};
}
for (var i = 0; i < document.getElementsByName('btnSaveExit').length; i++) {
	document.getElementsByName('btnSaveExit')[i].onclick = function() {
		btnAction = this.name;
	}
}

document.getElementById('formOperational').onsubmit = function() {
	
	currencyFormat.removeFormat();
	dateFormat.saveMode();
	
	var data = serialize(this);
	<?php if ($this->uri->segment(2) == 'add') { ?>
	data += '&cmd=ADD_OPERATIONAL';
	<?php } elseif ($this->uri->segment(2) == 'edit') { ?>
	data += '&cmd=EDIT_OPERATIONAL';
	<?php } ?>
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
					if (btnAction == 'btnSave') {
						// refresh content tab
						var tab = $('#siteInfoTab').tabs('getSelected');
						$('#siteInfoTab').tabs('update', {
							tab: tab,
							options: {
								href: document.URL + '?load_content=OPERATIONAL&site_id=' + document.getElementsByName('siteId')[0].value
							}
						});
					
					} else if (btnAction == 'btnSaveExit') {
						window.location.href = '<?php echo site_url('site_info') ?>';
					}
				});
			}
			
			window.scroll(0, 0);
			
			dateFormat.defaultMode();
		},
		complete: function() {
			siteOverlay.remove();
		}
	});
	
	return false;
};

<?php if ($this->uri->segment(2) == 'edit' && count($tenancy_sell['detail']) > 0) { ?>
var sellCounter = <?php echo $sell_counter ?>;
<?php } else { ?>
var sellCounter = 2;
<?php } ?>

var collocSell = {
	counter: sellCounter,
	addRow: function() {
		var html = '\
			<tr id="sellTenantRow' + this.counter + '">\
				<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="collocSell.removeRow(' + this.counter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>\
				<td>\
					<?php $dataTenantCollocSell = get_instance()->core->view_all('t_mtr_tenant', array('status' => 1), 'tenant_name') ?>\
					<select name="tenantCollocSell[]">\
						<option value="0">- Choose one -</option>\
						<?php foreach ($dataTenantCollocSell as $tenant) { ?>\
							<option value="<?php echo $tenant['tenant_id'] ?>"><?php echo $tenant['tenant_name'] ?></option>\
						<?php } ?>\
					</select>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="tenantDateCollocSell[]" id="tenantCollocSellDate-' + this.counter + '" class="date input-small" placeholder="DD-MMM-YYYY" />\
						<span class="add-on"><i class="icon-calendar"></i></span>\
					</div>\
				</td>\
			</tr>\
		';
		
		$('#tblCollocSell tbody').append(html);
		
		this.counter++;
		
		dateFormat.init();
		clearAutoComplete.init();
	},
	removeRow: function(row) {
		var row = document.getElementById('sellTenantRow' + row);
		coolConfirmation('Are you sure you want to delete?', function() {
			row.parentNode.removeChild(row);
		});
	}
};

var chStatus = function() {
	if (document.getElementById('siteStatus').value == 0) {
		document.getElementById('btnChangeStatus').disabled = true;
	} else {
		document.getElementById('btnChangeStatus').disabled = false;
	}
};
chStatus();
document.getElementById('siteStatus').onchange = chStatus;
document.getElementsByTagName('body')[0].onmouseover = chStatus;

var SITE_LAST_STATUS = {
	change: function() {
		var html = '\
			<tr id="rowNewStatus">\
				<td>\
					<div class="input-append">\
						<input type="text" name="dateStatus" class="date input-small" placeholder="DD-MMM-YYYY" />\
						<span class="add-on"><i class="icon-calendar"></i></span>\
					</div>\
				</td>\
				<td>\
					<select name="status">\
						<option value="0">- Choose one -</option>\
						<?php
							$lookup = $this->core->get_lookup('site_last_status');
							foreach ($lookup as $lo) { ?>\
							<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
						<?php } ?>\
					</select>\
				</td>\
				<td>\
					<textarea name="statusRemarks"></textarea>\
				</td>\
				<td colspan="2"></td>\
			</tr>\
		';
		
		$('#tblSiteStatus tbody').append(html);
		
		dateFormat.init();
		
		var statusRemarks = document.getElementsByName('statusRemarks')[0];
		statusRemarks.style.height = '20px';
		statusRemarks.style.overflowY = 'hidden';
		statusRemarks.className = 'input-large';
		
		statusRemarks.onfocus = function() {
			$(this).hide();
			this.style.height = '60px';
			this.style.overflowY = 'auto';
			this.className = 'input-large';
			$(this).fadeIn('slow');
		};
		
		statusRemarks.onblur = function() {
			this.style.height = '20px';
			this.style.overflowY = 'hidden';
		};
		
		$('#btnChangeStatus').hide();
		$('#btnCancelStatus').show();
	},
	cancel: function() {
		$('#btnChangeStatus').show();
		$('#btnCancelStatus').hide();
		$('#rowNewStatus').remove();
	}
};

var STATUS_OF_SITE = {
	change: function() {
		var html = '\
			<tr id="row-status-of-site">\
				<td>\
					<div class="input-append">\
						<input type="text" name="dateStatusOfSite" class="date input-small" placeholder="DD-MMM-YYYY" />\
						<span class="add-on"><i class="icon-calendar"></i></span>\
					</div>\
				</td>\
				<td>\
					<select name="statusOfSiteUsed">\
						<option value="">- Choose one -</option>\
						<option value="1">Active</option>\
						<option value="0">Inactive</option>\
					</select>\
				</td>\
				<td>\
					<textarea name="statusOfSiteRemarks"></textarea>\
				</td>\
				<td colspan="2"></td>\
			</tr>\
		';
		
		$('#tblStatusOfSite tbody').append(html);
		
		dateFormat.init();
		
		var statusOfSiteRemarks = document.getElementsByName('statusOfSiteRemarks')[0];
		statusOfSiteRemarks.style.height = '20px';
		statusOfSiteRemarks.style.overflowY = 'hidden';
		statusOfSiteRemarks.className = 'input-large';
		
		statusOfSiteRemarks.onfocus = function() {
			$(this).hide();
			this.style.height = '60px';
			this.style.overflowY = 'auto';
			this.className = 'input-large';
			$(this).fadeIn('slow');
		};
		
		statusOfSiteRemarks.onblur = function() {
			this.style.height = '20px';
			this.style.overflowY = 'hidden';
		};
		
		$('#btnChangeStatusOfSite').hide();
		$('#btnCancelStatusOfSite').show();
	},
	cancel: function() {
		$('#btnChangeStatusOfSite').show();
		$('#btnCancelStatusOfSite').hide();
		$('#row-status-of-site').remove();
	}
};

<?php if ($this->uri->segment(2) == 'edit' && count($tenancy_buy['detail']) > 0) { ?>
var buyCounter = <?php echo $buy_counter ?>;
<?php } else { ?>
var buyCounter = 2;
<?php } ?>

var collocBuy = {
	counter: buyCounter,
	addRow: function() {
		var html = '\
			<tr id="buyTenantRow' + this.counter + '">\
				<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="collocBuy.removeRow(' + this.counter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>\
				<td>\
					<?php $dataTenantCollocBuy = get_instance()->core->view_all('t_mtr_tenant', array('status' => 1), 'tenant_name') ?>\
					<select name="tenantCollocBuy[]">\
						<option value="0">- Choose one -</option>\
						<?php foreach ($dataTenantCollocBuy as $tenant) { ?>\
							<option value="<?php echo $tenant['tenant_id'] ?>"><?php echo $tenant['tenant_name'] ?></option>\
						<?php } ?>\
					</select>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="tenantDateCollocBuy[]" id="tenantCollocBuyDate-' + this.counter + '" class="date input-small" placeholder="DD-MMM-YYYY" />\
						<span class="add-on"><i class="icon-calendar"></i></span>\
					</div>\
				</td>\
			</tr>\
		';
		
		$('#tblCollocBuy tbody').append(html);
		
		this.counter++;
		
		dateFormat.init();
		clearAutoComplete.init();
	},
	removeRow: function(row) {
		var row = document.getElementById('buyTenantRow' + row);
		coolConfirmation('Are you sure you want to delete?', function() {
			row.parentNode.removeChild(row);
		});
	}
};

<?php if ($this->uri->segment(2) == 'edit') { ?>
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
<?php } ?>

$('.time').timepicker({ step: 1, minTime: '9:00', timeFormat: 'H:i' });

currencyFormat.init();
justNumberFormat.init();
textUpperCase.init();
clearAutoComplete.init();
timeFormat.init();
dateFormat.init();

<?php if ($this->uri->segment(2) == 'edit') { ?>
listProblemHistory.init();
<?php } ?>
</script>