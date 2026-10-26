<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<?php echo form_open('', 'id="formGeneralInfo" class="form-horizontal"') ?>

<?php 
    echo form_hidden('siteId', set_value('siteId', $general_info['site_id'])); 
    echo form_hidden('curSiteCode', $general_info['site_code']); //andedi 20141029
?>

<div class="top-form-actions">
	<button class="btn btn-primary btn-small btn-icon glyphicons circle_ok" name="btnSave"><i></i> Save</button>
	<button class="btn btn-primary btn-small btn-icon glyphicons circle_ok" name="btnSaveExit"><i></i> Save &amp; Exit</button>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">General</h4>
	</div>
	
	<div class="widget-body">
		
		<?php if ($this->uri->segment(2) == 'edit') { ?>
		<div class="control-group" id="form_area_code">
			<label class="control-label" for="areaCode">Area Code:</label>
			<div class="controls">
				<span class="input-label"><?php echo $general_info['area_code'] ?></span>
			</div>
		</div>
		<?php } else { ?>
		<div class="control-group" id="form_area_code">
			<label class="control-label" for="areaCode">Area Code:</label>
			<div class="controls">
				<div class="input-append">
					<?php echo form_input('areaCode', '', 'id="areaCode" onfocus="doLookupAreaCode(\'areaCode\', \'areaCode2\')" class="input-small uneditable"') ?>
					<?php echo form_hidden('areaCode2', '') ?>
					<button type="button" class="btn" onclick="doLookupAreaCode('areaCode', 'areaCode2')">...</button>
				</div>
			</div>
		</div>
		<?php } ?>
		
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_site_id">
					<label class="control-label" for="siteIdText">Site ID:</label>
					<div class="controls">
						<?php echo form_input('siteIdText', set_value('siteIdText', $general_info['site_code']), 'id="siteIdText" class="uppercase" onblur="$.checkSiteID()"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_site_profile">
					<label class="control-label" for="siteProfile">Site Profile:</label>
					<div class="controls">
						<?php
						$dataSiteProfile = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'site_profile', 'active' => 1), 'order');
						$site_profile = array(0 => '- Choose one -');
						foreach ($dataSiteProfile as $profile) {
							$site_profile[$profile['lookup_value']] = $profile['lookup_desc'];
						}
						echo form_dropdown('siteProfile', $site_profile, set_value('siteProfile', $general_info['site_profile']), 'id="siteProfile" class=""');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_site_id_ref">
					<label class="control-label" for="oldSiteId">Site ID Ref.:</label>
					<div class="controls">
						<?php echo form_input('oldSiteId', set_value('oldSiteId', $general_info['site_code_old']), 'id="oldSiteId" class="medium uppercase"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_site_type">
					<label class="control-label" for="siteType">Site Type:</label>
					<div class="controls">
						<?php
						$dataSiteType = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'site_type', 'active' => 1), 'order');
						$site_type = array(0 => '- Choose one -');
						foreach ($dataSiteType as $type) {
							$site_type[$type['lookup_value']] = $type['lookup_desc'];
						}
						echo form_dropdown('siteType', $site_type, set_value('siteType', $general_info['site_type']), 'id="siteType" class=""');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_site_name">
					<label class="control-label" for="siteName">Site Name:</label>
					<div class="controls">
						<?php echo form_input('siteName', set_value('siteName', trim($general_info['site_name'])), 'id="siteName" class="medium"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_site_specification">
					<label class="control-label" for="siteSpecification">Site Specification:</label>
					<div class="controls">
						<?php
						$dataSiteSpecification = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'site_specification', 'active' => 1), 'lookup_desc');
						$site_specification = array(0 => '- Choose one -');
						foreach ($dataSiteSpecification as $specification) {
							$site_specification[$specification['lookup_value']] = $specification['lookup_desc'];
						}
						echo form_dropdown('siteSpecification', $site_specification, set_value('siteSpecification', $general_info['site_specification']), 'id="siteSpecification"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_vendor_id">
					<label class="control-label" for="vendorId">Vendor ID:</label>
					<div class="controls">
						<?php if ($this->uri->segment(2) == 'edit') { ?>
						<span class="uneditable-input"><?php echo $general_info['vendor_id'] ?></span>
						<input type="hidden" name="vendorId" value="<?php echo $general_info['vendor_id'] ?>" />
						<?php } else { ?>
						<?php echo form_input('vendorId', set_value('vendorId', $general_info['vendor_id']), 'id="vendorId" class="uppercase"') ?>
						<?php } ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_actual_latlong">
					<label class="control-label" for="actualLatitude">Actual Lat - Long:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo form_input('actualLatitude', set_value('actualLatitude', $general_info['latitude']), 'id="actualLatitude" class="input-small latlng-number"') ?>
							<?php echo form_input('actualLongitude', set_value('actualLongitude', $general_info['longitude']), 'id="actualLongitude" class="input-small latlng-number"') ?>
							<a href="javascript:void(0)" class="btn" onclick="return showMap()">Map</a>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span12">
				<div class="control-group" id="form_vendor_name">
					<label class="control-label" for="vendorName">Vendor Name:</label>
					<div class="controls">
						<?php echo form_input('vendorName', set_value('vendorName', $general_info['vendor_name']), 'id="vendorName" class=""') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
	</div>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">Tower Provider</h4>
	</div>
	
	<div class="widget-body">

		<div class="control-group" id="form_tp_site_id">
			<label class="control-label" for="tpId">TP Site ID:</label>
			<div class="controls">
				<div class="input-append">
					<span id="tpCode" class="uneditable-input input-medium"><?php echo $general_info['tp_code'] ?></span>
					<?php echo form_hidden('tpId', set_value('tpId', $general_info['tp_id']), 'id="tpId') ?>
					<a href="javascript:void(0)" class="btn" onclick="doLookupTP('tpCode', 'tpId')">...</a>
				</div>
			</div>
		</div>
		
		<div class="control-group" id="form_tp_name">
			<label class="control-label" for="tpName">TP Name:</label>
			<div class="controls">
				<span id="tpName" class="uneditable-input"><?php echo $general_info['tp_company_name'] ?></span>
			</div>
		</div>
			
		<div class="control-group" id="form_tp_group_name">
			<label class="control-label" for="tpGroupName">TP Group Name:</label>
			<div class="controls">
				<span id="tpGroupName" class="uneditable-input"><?php echo $general_info['tpgroup_name'] ?></span>
			</div>
		</div>
		
		<div class="control-group" id="form_site_category">
			<label class="control-label" for="siteCategory">Site Category:</label>
			<div class="controls">
				<span id="site-category" class="uneditable-input"><?php echo $general_info['tp_site_category'] ?></span>
			</div>
		</div>
		
		<div class="control-group" id="form_tp_kabupaten">
			<label class="control-label" for="tpKabupaten">Kabupaten:</label>
			<div class="controls">
				<span id="tpKabupaten" class="uneditable-input"><?php echo $general_info['tp_kabupaten'] ?></span>
			</div>
		</div>
		
	</div>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">Address</h4>
	</div>
	
	<div class="widget-body">
	
		<div class="control-group" id="form_site_address">
			<label class="control-label" for="siteAddress">Site Address:</label>
			<div class="controls">
				<?php echo form_textarea('siteAddress', set_value('siteAddress', strip_tags($general_info['site_address'])), 'id="siteAddress" style="width:206px; height:80px;"') ?>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_province">
					<label class="control-label" for="propinsi">Province:</label>
					<div class="controls">
						<?php
						$propinsi = array('0' => '- Choose one -');
						$dataPropinsi = get_instance()->core->view_all('t_mtr_lokasi', array('jenis' => 'P'), 'nama');
						foreach ($dataPropinsi as $prop) {
							$propinsi[$prop['lokasi_id']] = $prop['nama'];
						}
						echo form_dropdown('propinsi', $propinsi, set_value('propinsi', $general_info['propinsi_id']), 'id="propinsi"');
						?>
					</div>							
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_island">
					<label class="control-label" for="island">Island:</label>
					<div class="controls">
						<?php
						$island = $this->db
						->query("
							SELECT pul.island_name
							FROM t_mtr_lokasi lok
							LEFT JOIN t_mtr_lokasi_pulau pul ON pul.island_id = lok.island_id AND pul.active = 1
							WHERE lok.jenis = 'P' AND lok.lokasi_id = " . (int) @$general_info['propinsi_id']
						)
						->row_array();
						?>
						<span id="island" class="input-label"><?php echo @$island['island_name'] ?></span>
					</div>							
					<div class="separator bottom"></div>
				</div>
			</div>
			
		</div>
		
		<!-- <div class="row-fluid">
			<div class="span6">
				<div class="control-group">						   
					<label class="control-label" for="island">Island:</label>
					<div class="controls">
						<?php
						$island = $this->db
						->query("
							SELECT pul.island_name
							FROM t_mtr_lokasi lok
							LEFT JOIN t_mtr_lokasi_pulau pul ON pul.island_id = lok.island_id AND pul.active = 1
							WHERE lok.jenis = 'P' AND lok.lokasi_id = " . (int) @$general_info['propinsi_id']
						)
						->row_array();
						?>
						<span id="island" class="input-label"><?php echo @$island['island_name'] ?></span>
					</div>							
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span6">
				<div class="control-group">
					<label class="control-label" for="kodePos">Kode Pos:</label>
					<div class="controls">
						<?php echo form_input('kodePos', set_value('kodePos', $general_info['kode_pos']), 'id="kodePos" maxlength="6" class="input-mini just-number" style="text-align:left"') ?>
					</div>
				</div>
				<div class="separator bottom"></div>
			</div>
		</div> -->
		
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_kabupaten">
					<label class="control-label" for="kabupaten">Kabupaten:</label>
					<div class="controls">
						<?php
						$kabupaten = array('0' => '- Choose one -');
						$dataKabupaten = @get_instance()->core->view_all('t_mtr_lokasi', array('jenis' => 'KB', 'status' => 1), 'nama');
						foreach ($dataKabupaten as $kab) {
							$kabupaten[$kab['lokasi_id']] = $kab['nama'];
						}
						echo form_dropdown('kabupaten', $kabupaten, set_value('kabupaten', $general_info['kabupaten']), 'id="kabupaten"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_kelurahan">
					<label class="control-label" for="kelurahan">Kelurahan:</label>
					<div class="controls">
						<?php echo form_input('kelurahan', set_value('kelurahan', $general_info['kelurahan']), 'id="kelurahan" class="medium"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>			
		</div>
		
		<div class="row-fluid">			
			<div class="span4">
				<div class="control-group" id="form_kecamatan">
					<label class="control-label" for="kecamatan">Kecamatan:</label>
					<div class="controls">
						<?php
						$kecamatan = array('0' => '- Choose one -');
						$dataKecamatan = @get_instance()->core->view_all('t_mtr_lokasi', array('jenis' => 'KC'), 'nama');
						foreach ($dataKecamatan as $kec) {
							$kecamatan[$kec['lokasi_id']] = $kec['nama'];
						}
						echo form_dropdown('kecamatan', $kecamatan, set_value('kecamatan', $general_info['kecamatan']), 'id="kecamatan"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			<div class="span8">
				<div class="control-group" id="form_kode_pos">
					<label class="control-label" for="kodePos">Kode Pos:</label>
					<div class="controls">
						<?php echo form_input('kodePos', set_value('kodePos', $general_info['kode_pos']), 'id="kodePos" maxlength="6" class="input-mini just-number" style="text-align:left"') ?>
					</div>
				</div>
				<div class="separator bottom"></div>
			</div>
		</div>

	</div>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">Territory</h4>
	</div>
	
	<div class="widget-body">
		<div class="control-group" id="form_region">
			<label class="control-label" for="region">Region:</label>
			<div class="controls">
				<?php
				$my_region = array();
				foreach ($this->session->userdata['user_region'] as $reg) {
					if ($reg['region_id'] != '') {
						$my_region[] = $reg['region_id'];
					}
				}
				
				$regions = array('0' => '- Choose one -');
				$dataRegions = get_instance()->db
					->query("SELECT region_id, region_name FROM t_mtr_region WHERE status = 1 AND region_id IN (".implode(', ', $my_region).") ORDER BY region_name ASC")
					->result_array();
				foreach ($dataRegions as $region) {
					$regions[$region['region_id']] = $region['region_name'];
				}
				echo form_dropdown('region', $regions, set_value('region', $general_info['region_id']), 'id="region" class="medium"');
				?>
			</div>
		</div>

		<div class="control-group" id="form_cluster_ops">
			<label class="control-label" for="clusterOpr">Operational Cluster:</label>
			<div class="controls">
				<?php
				$region_id = @get_instance()->core->view_row('t_mtr_cluster_ops', array('cluster_ops_id' => $general_info['cluster_ops_id']));
				
				$clusters = array('0' => '- Choose one -');
				$dataCluster = @get_instance()->core->view_all('t_mtr_cluster_ops', array('region_id' => $region_id['region_id'], 'cluster_ops_id !=' => 0), 'cluster_name');
				foreach ($dataCluster as $cluster) {
					$clusters[$cluster['cluster_ops_id']] = $cluster['cluster_name'];
				}
				echo form_dropdown('clusterOpr', $clusters, set_value('clusterOpr', $general_info['cluster_ops_id']), 'id="clusterOpr" class="medium"');
				?>
			</div>
		</div>

		<div class="control-group" id="form_cluster_net">
			<label class="control-label" for="clusterNet">Network Cluster:</label>
			<div class="controls">
				<?php
				$region_id = @get_instance()->core->view_row('t_mtr_cluster_net', array('cluster_net_id' => $general_info['cluster_net_id']));
				
				$clusters = array('0' => '- Choose one -');
				$dataCluster = get_instance()->core->view_all('t_mtr_cluster_net', array('region_id' => @$region_id['region_id'], 'cluster_net_id !=' => 0), 'cluster_name');
				foreach ($dataCluster as $cluster) {
					$clusters[$cluster['cluster_net_id']] = $cluster['cluster_name'];
				}
				echo form_dropdown('clusterNet', $clusters, set_value('clusterNet', $general_info['cluster_net_id']), 'id="clusterNet" class="medium"');
				?>
			</div>
		</div>

		<div class="control-group" id="form_cluster_com">
			<label class="control-label" for="cluster">Commercial Cluster:</label>
			<div class="controls">
				<?php
				$region_id = @get_instance()->core->view_row('t_mtr_cluster_com', array('cluster_com_id' => $general_info['cluster_com_id']));
				
				$clusters = array('0' => '- Choose one -');
				$dataCluster = get_instance()->core->view_all('t_mtr_cluster_com', array('region_id' => @$region_id['region_id'], 'cluster_com_id !=' => 0), 'cluster_name');
				foreach ($dataCluster as $cluster) {
					$clusters[$cluster['cluster_com_id']] = $cluster['cluster_name'];
				}
				echo form_dropdown('clusterCom', $clusters, set_value('clusterCom', $general_info['cluster_com_id']), 'id="clusterCom" class="medium"');
				?>
			</div>
		</div>
	</div>
</div>
	
<div class="widget">
	<div class="widget-head">
		<h4 class="heading">Finance</h4>
	</div>
	
	<div class="widget-body">
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_finance_contract_under">
					<label class="control-label" for="contractUnder">Contract Under:</label>
					<div class="controls">
						<?php
						$contractUnder = array(0 => '- Choose one -');
						$dataContractUnder = get_instance()->core->view_all('t_mtr_lookup', array('lookup_key' => 'contract_under', 'active' => 1), 'lookup_desc');
						foreach ($dataContractUnder as $contract) {
							$contractUnder[$contract['lookup_value']] = $contract['lookup_desc'];
						}
						echo form_dropdown('contractUnder', $contractUnder, set_value('contractUnder', $general_info['contract_under']), 'id="contractUnder"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_finance_company">
					<label class="control-label" for="companyCode">Company:</label>
					<div class="controls">
						<?php
						$dataCompany = $this->core->get_lookup('company');
						$companies = array(0 => '- Choose one -');
						foreach ($dataCompany as $company) {
							$companies[$company['id']] = $company['desc'];
						}
						echo form_dropdown('companyCode', $companies, set_value('companyCode', $general_info['company_code']), 'id="companyCode"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_finance_cost_center">
					<label class="control-label" for="costCenter">Cost Center:</label>
					<div class="controls">
						<?php echo form_input('costCenter', set_value('costCenter', $general_info['cost_center']), 'id="costCenter"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_finance_price_lease">
					<label class="control-label" for="priceLease">Price Lease:</label>
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">IDR</span>
							<?php echo form_input('priceLease', set_value('priceLease', $general_info['harga_sewa']), 'id="priceLease" class="input-small currency"') ?>
							
							<?php
							$period = array(0 => '- Period -');
							$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'finance_period', 'active' => 1), 'order');
							foreach ($lookup as $loo) {
								$period[$loo['lookup_value']] = $loo['lookup_desc'];
							}
							echo form_dropdown('priceLeasePeriod', $period, set_value('priceLeasePeriod', $general_info['price_lease_period']), 'class="input-medium" style="width:100px"');
							?>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<!-- <div class="control-group">
			<label class="control-label" for="termOfPayment">Term of Payment:</label>
			<div class="controls">
				<?php
				/* $termOfPayment = array(0 => '- Choose one -');
				$dataTermOfPayment = get_instance()->core->view_all('t_mtr_lookup', array('lookup_key' => 'term_of_payment', 'active' => 1), 'lookup_desc');
				foreach ($dataTermOfPayment as $term) {
					$termOfPayment[$term['lookup_value']] = $term['lookup_desc'];
				}
				echo form_dropdown('termOfPayment', $termOfPayment, set_value('termOfPayment', $general_info['term_of_payment']), 'id="termOfPayment"'); */
				?>
			</div>
		</div>
		-->
		
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_finance_lcd_start_date">
					<label class="control-label" for="lcdStartDate">LCD Start Date:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo form_input('lcdStartDate', set_value('lcdStartDate', $general_info['lcd_start_date']), 'id="lcdStartDate" placeholder="DD-MMM-YYYY" class="input-small date"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button onclick="clearField('lcdStartDate')" type="button" class="btn">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_finance_lcd_end_date">
					<label class="control-label" for="lcdEndDate">LCD End Date:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo form_input('lcdEndDate', set_value('lcdEndDate', $general_info['lcd_end_date']), 'id="lcdEndDate" placeholder="DD-MMM-YYYY" class="input-small date"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button onclick="clearField('lcdEndDate')" type="button" class="btn">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_lcd_renewal_status">
					<label class="control-label" for="lcdRenewalStatus">LCD Renewal Status:</label>
					<div class="controls">
						<?php
						$options = array('' => '- Choose one -', 1 => 'Continue', 0 => 'Failed');
						echo form_dropdown('lcdRenewalStatus', $options, $general_info['lcd_renewal_status'], 'id="lcdRenewalStatus"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_lcd_renewal_remarks">
					<label class="control-label" for="lcdRenewalRemarks">LCD Renewal Remarks:</label>
					<div class="controls">
						<?php echo form_textarea('lcdRenewalRemarks', $general_info['lcd_renewal_remark'], 'id="lcdRenewalRemarks" style="height:60px"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="control-group" id="form_finance_remarks">
			<label class="control-label" for="financeRemarks">Remarks:</label>
			<div class="controls">
				<?php echo form_textarea('financeRemarks', set_value('financeRemarks', trim($general_info['remarks'])), 'id="financeRemarks" class="span4" style="height:100px;"') ?>
			</div>
		</div>
	</div>
</div>

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

document.getElementById('formGeneralInfo').onsubmit = function() {
	
	var okayz = true;
	if ( ! checkLCD()) {
		okayz = false;
	} else if ($.checkSiteID().error) {
		okayz = false;
	} else {
		okayz = true;
	}
	
	if (okayz) {
		currencyFormat.removeFormat();
		dateFormat.saveMode();
		
		var data = serialize(this);
		<?php if (get_instance()->uri->segment(2) == 'add') { ?>
		data += '&cmd=ADD_GENERAL_INFO';
		<?php } elseif (get_instance()->uri->segment(2) == 'edit') { ?>
		data += '&cmd=EDIT_GENERAL_INFO';
		<?php } ?>
		
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
						<?php if ($this->uri->segment(2) == 'edit') { ?>
						
						if (btnAction == 'btnSave') {
							// refresh content tab
							var tab = $('#siteInfoTab').tabs('getSelected');
							$('#siteInfoTab').tabs('update', {
								tab: tab,
								options: {
									href: document.URL + '?load_content=GENERAL_INFO&site_id=' + document.getElementsByName('siteId')[0].value
								}
							});
							
							$('#siteInfoTab').tabs({
								onLoad: function(title, index) {
									document.getElementById('field-site-id-top').innerHTML = '<strong>' + document.getElementById('siteIdText').value + '</strong>';
									document.getElementById('field-site-name-top').innerHTML = '<strong>' + document.getElementById('siteName').value + '</strong>';
									document.getElementById('field-site-profile-top').innerHTML = '<strong>' + document.getElementById('siteProfile').options[document.getElementById('siteProfile').options.selectedIndex].text + '</strong>';
								}
							});
							
						} else if (btnAction == 'btnSaveExit') {
							window.location.href = '<?php echo site_url('site_info') ?>';
						}
						
						<?php } else { ?>
						
						if (btnAction == 'btnSave') {
							window.location.href = '<?php echo site_url('site_info/edit') ?>/' + json.site_id_enc;
						} else if (btnAction == 'btnSaveExit') {
							window.location.href = '<?php echo site_url('site_info') ?>';
						}
						
						<?php } ?>
					});
				}
				
				window.scroll(0, 0);
				
				dateFormat.defaultMode();
			},
			complete: function() {
				siteOverlay.remove();
			}
		});
	}
	
	return false;
};

document.getElementById('propinsi').onchange = function() {
	
	$.ajax({
		url: '<?php echo site_url('/site_info') ?>',
		type: 'GET',
		data: { cmd: 'GET_KABUPATEN', propinsi_id: this.value },
		dataType: 'json',
		success: function(json) {
			if (typeof json.loggedin != 'undefined' && !json.loggedin) {
				alert(json.message);
				window.location.href = json.redirect_url;
			}
			
			var html = '<option value="0">- Choose one -</option>';
			for (var i = 0; i < json.province.length; i++) {
				html += '<option value="' + json.province[i]['id'] + '">' + json.province[i]['name'] + '</option>';
			}
			document.getElementById('kabupaten').innerHTML = html;
			document.getElementById('kecamatan').innerHTML = '<option value="0">- Choose one -</option>';
			
			document.getElementById('island').innerHTML = json.island.island_name != null ? json.island.island_name : '';
		}
	});	
};

document.getElementById('kabupaten').onchange = function() {
	
	$.ajax({
		url: '<?php echo site_url('/site_info') ?>',
		type: 'GET',
		data: { cmd: 'GET_KECAMATAN', kabupaten_id: this.value },
		dataType: 'json',
		success: function(json) {
			if (typeof json.loggedin != 'undefined' && !json.loggedin) {
				alert(json.message);
				window.location.href = json.redirect_url;
			}
			
			var html = '<option value="0">- Choose one -</option>';
			for (var i = 0; i < json.length; i++) {
				html += '<option value="' + json[i]['id'] + '">' + json[i]['name'] + '</option>';
			}
			document.getElementById('kecamatan').innerHTML = html;
		}
	});
};

var clusterOpr = document.getElementById('clusterOpr').value;
var clusterNet = document.getElementById('clusterNet').value;
var clusterCom = document.getElementById('clusterCom').value;

document.getElementById('region').onchange = function() {
	
	$.ajax({
		url: '<?php echo site_url('/site_info') ?>',
		type: 'GET',
		data: { cmd: 'GET_CLUSTER', region_id: this.value },
		dataType: 'json',
		success: function(json) {
			if (typeof json.loggedin != 'undefined' && !json.loggedin) {
				alert(json.message);
				window.location.href = json.redirect_url;
			}
			
			// Operational
			var html = '<option value="0">- Choose one -</option>';
			for (var i = 0; i < json.ops.length; i++) {
				var selected = '';
				if (json.ops[i]['id'] == clusterOpr) {
					selected = 'selected="selected"';
				}
				
				html += '<option value="' + json.ops[i]['id'] + '" ' + selected + '>' + json.ops[i]['name'] + '</option>';
			}
			document.getElementById('clusterOpr').innerHTML = html;
			
			// Network
			var html = '<option value="0">- Choose one -</option>';
			for (var i = 0; i < json.net.length; i++) {
				var selected = '';
				if (json.net[i]['id'] == clusterNet) {
					selected = 'selected="selected"';
				}
				
				html += '<option value="' + json.net[i]['id'] + '" ' + selected + '>' + json.net[i]['name'] + '</option>';
			}
			document.getElementById('clusterNet').innerHTML = html;
			
			// Commercial
			var html = '<option value="0">- Choose one -</option>';
			for (var i = 0; i < json.com.length; i++) {
				var selected = '';
				if (json.com[i]['id'] == clusterCom) {
					selected = 'selected="selected"';
				}
				
				html += '<option value="' + json.com[i]['id'] + '" ' + selected + '>' + json.com[i]['name'] + '</option>';
			}
			document.getElementById('clusterCom').innerHTML = html;
		}
	});
};

$(function() {
	document.getElementById('region').onchange();
	
	/*
	for(var i = 0; i < document.getElementById('clusterOpr').options.length; i++) {
		if (document.getElementById('clusterOpr').options[i].value == clusterOpr) {
			document.getElementById('clusterOpr').selectedIndex = i;
			
			break;
		}
	}
	
	for(var i = 0; i < document.getElementById('clusterNet').options.length; i++) {
		if (document.getElementById('clusterNet').options[i].value == clusterNet) {
			document.getElementById('clusterNet').selectedIndex = i;
			break;
		}
	}
	
	for(var i = 0; i < document.getElementById('clusterCom').options.length; i++) {
		if (document.getElementById('clusterCom').options[i].value == clusterCom) {
			document.getElementById('clusterCom').selectedIndex = i;
			break;
		}
	}
	*/
});

/*
document.getElementById('tpName').onchange = function() {
	
	$.ajax({
		url: '<?php echo site_url('/site_info') ?>',
		type: 'GET',
		data: { cmd: 'GET_TP_INFO', name_id: this.value },
		dataType: 'json',
		success: function(json) {
			if (typeof json.loggedin != 'undefined' && !json.loggedin) {
				alert(json.message);
				window.location.href = json.redirect_url;
			}
			console.log(json.tp_name_group);
			document.getElementById('tpNameGroup').innerHTML = json.tp_name_group != undefined ? json.tp_name_group : '';
			document.getElementsByName('tpNameGroup')[0].value = json.tp_name_group != undefined ? json.tp_name_group : '';
			document.getElementById('tpKabupaten').innerHTML = json.kabupaten != undefined ? json.kabupaten : '';
		}
	});	
};
*/

var doLookupTP = function(elem1, elem2) { // elem1: shown element, elem2: hidden element
	var url = '<?php echo site_url('/site_info') ?>';
	
	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { cmd: 'get_lookup_tower_provider', e: elem2 } }, // e: it used for selected element
		width: 600,
		scrolling: false,
		autoSize: true,
		fitToView: true,
		closeClick: false,
		openEffect: 'fade',
		closeEffect: 'fade',
		afterShow: function() {
			document.getElementById('btnDataSelectedOK').onclick = function() {
				var row = $('#dg-tp').datagrid('getSelected');
			
				if (row != null) {
					document.getElementById(elem1).innerHTML = row.tp_code;
					document.getElementsByName(elem2)[0].value = row.tp_id;
					
					document.getElementById('tpName').innerHTML = row.company_name;
					document.getElementById('tpGroupName').innerHTML = row.tpgroup_name;
					document.getElementById('site-category').innerHTML = row.site_category;
					document.getElementById('tpKabupaten').innerHTML = row.city;
					document.getElementById('siteAddress').value = row.address;
				}
				
				$.fancybox.close();
			};
		}
	});
};

var compareDates = function(startDate, endDate) {
  // parse a date in yyyy-mm-dd format
	function parseDate(input) {
		var parts = input.match(/(\d+)/g);
		//return new Date(parts[0], parts[1]-1, parts[2]); // months are 0-based
		return new Date(parts[2], parts[1]-1, parts[0]);
	}
	
	if (parseDate(endDate) < parseDate(startDate)) {
		return false;
	} else {
		return true;
	}
}

function checkLCD() {
	var lcdStartDate = document.getElementById('lcdStartDate').value;
	var lcdEndDate = document.getElementById('lcdEndDate').value;
	if (lcdStartDate != '' && lcdEndDate != '') {
		var day = lcdStartDate.split('-')[0];
		var month = lcdStartDate.split('-')[1];
		var year = lcdStartDate.split('-')[2];
	
		var sDate = day + '-' + dateFormat.monthsCode[month] + '-' + year;
		
		var day = lcdEndDate.split('-')[0];
		var month = lcdEndDate.split('-')[1];
		var year = lcdEndDate.split('-')[2];
	
		var eDate = day + '-' + dateFormat.monthsCode[month] + '-' + year;
		
		if ( ! compareDates(sDate, eDate)) {
			//alert('LCD End Date must be greater than LCD Start Date.');
			coolNotification('LCD End Date must be greater than LCD Start Date.', 'error');
			return false;
		} else {
			return true;
		}
	} else {
		return true;
	}
}

var showMap = function() {
	var x = parseInt((window.screen.availWidth - 850) / 2);
        var y = parseInt(((window.screen.availHeight - 560) / 2) - 50);
	
	var w = window.open('<?php echo site_url('/site_info') ?>?cmd=GET_MAP&draggable=true&latlngElement=actualLatitude,actualLongitude', 'map', 'height=560,width=850,left=' + x + ',top=' + y + ',scrollbars=no');
	w.focus();
	
	return false;
};

var doLookupAreaCode = function(elem1, elem2) { // elem1: shown element, elem2: hidden element
	var url = '<?php echo site_url('/site_info') ?>';
	
	$.fancybox({
		href: url,
		type: 'ajax',
		ajax: { data: { cmd: 'get_lookup_area_code', e: elem2 } }, // e: it used for selected element
		width: 600,
		scrolling: false,
		autoSize: true,
		fitToView: true,
		closeClick: false,
		openEffect: 'fade',
		closeEffect: 'fade',
		afterShow: function() {
			document.getElementById('btnDataSelectedOK').onclick = function() {
				var row = $('#dg-area-code').datagrid('getSelected');
			
				if (row != null) {
					document.getElementsByName(elem1)[0].value = row.area_code;
					document.getElementsByName(elem2)[0].value = row.area_id;
					
					$.ajax({
						url: '<?php echo site_url('/site_info') ?>',
						type: 'GET',
						data: { cmd: 'get_area_code', code: row.area_code },
						dataType: 'json',
						beforeSend: function() {
							$.blockUI({ message: 'Generating Site ID, please wait...', css: {
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
							document.getElementById('siteIdText').value = json.site_code;
							document.getElementById('siteIdText').focus();
							
							$.unblockUI();
						}
					});
				}
				
				$.fancybox.close();
			};
		}
	});
};

$.extend({
    checkSiteID: function() {
        // local var
        var theResponse = null;
		var siteId = document.getElementsByName('siteId')[0];
		var siteCode = document.getElementById('siteIdText');
        
		// jQuery ajax
        $.ajax({
            url: '<?php echo site_url('/site_info') ?>',
            type: 'GET',
            data: { cmd: 'check_site_id', site_id: siteId.value, site_code: siteCode.value },
            dataType: "json",
            async: false,
            success: function(respText) {
				theResponse = respText;
				
				if (respText.error) {
					var message = '\
						<ul style="text-align:left">';
						for (var i = 0; i < respText['message'].length; i++) {
							message += '<li>' + respText['message'][i] + '</li>';
						}
						message += '</ul>';
					
					coolNotification(message, 'error', function() {
						siteCode.select();
						
						return false;
					});
				}
            }
        });
        // Return the response text
        return theResponse;
    }
});

currencyFormat.init();
justNumberFormat.init();
latLngFormat.init();
textUpperCase.init();
clearAutoComplete.init();
dateFormat.init();
doPreviledgePerfield();
</script>