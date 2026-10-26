<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/default/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>

<style>
.widget { border:none; }
.form-horizontal .control-label { text-align:left; }
fieldset { margin-bottom:20px; border-bottom:1px solid #CCCCCC; padding:0px; }
legend { border-bottom:1px solid #CCCCCC; margin:0 0 4px; }
legend + .control-group { margin-top:0px; }
.form-horizontal .column-left { float:left; }
.form-horizontal .column-right { float:left; margin-left:80px; }
.form-horizontal .control-group { margin-bottom:4px; }
.form-horizontal .control-group:last-child { margin:0 0 4px; }
.btn { padding:4px 10px; }
</style>

<h3>Site Info</h3>

<div class="innerLR">

	<!-- start:siteInfo -->
	<div id="siteInfo" class="easyui-tabs" style="width:auto; height:auto;">
		<!-- start:contentGeneralInfo -->
		<div id="contentGeneralInfo" title="General Info">
			<?php echo form_open('', 'id="formGeneralInfo" class="form-horizontal"') ?>
				
			<?php echo @form_hidden('siteId', set_value('siteId', $general_info['site_id'])) ?>
			
			<div class="widget">
				<div class="widget-body">
					<div class="row-fluid">
						
						<div class="control-group">
							<label class="control-label" for="siteIdText">Site ID:</label>							
							<div class="controls">
								<label class="control-label"><?php echo $general_info['site_id_text'] ?></label>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="oldSiteId">Reference Site ID:</label>
							<div class="controls">
								<label class="control-label"><?php echo $general_info['old_site_id'] ?></label>
							</div>
						  
						</div>
						
						<div class="control-group">
							<label class="control-label" for="siteName">Site Name:</label>
							<div class="controls">
								<label class="control-label"><?php echo trim($general_info['site_name']) ?></label>
							</div>
						</div>
					  
						<div class="control-group">
							<label class="control-label" for="siteType">Site Type:</label>
							<div class="controls">
								<?php echo @form_dropdown('siteType', $site_type, set_value('siteType', $general_info['site_type']), 'id="siteType" class=""') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="siteProfile">Site Profile:</label>
							<div class="controls">
								<?php echo @form_dropdown('siteProfile', $site_profile, set_value('siteProfile', $general_info['site_profile']), 'id="siteProfile" class=""') ?>
							</div>
						</div>
					  
						<div class="control-group column-left">
							<label class="control-label" for="siteLocationLat">Nominal Latitude:</label>
							<div class="controls">
								<label class="control-label"><?php echo $general_info['latitude'] ?></label>
							</div>
						</div>
						
						<div class="control-group column-right">
							<label class="control-label" for="siteLocationActualLat">Actual Latitude:</label>
							<div class="controls">
								<label class="control-label"><?php echo @$general_info['actual_latitude'] ?></label>
							</div>
						</div>
						<div class="clearfix"></div>
						
						<div class="control-group" style="float:left">
							<label class="control-label" for="siteLocationLng">Nominal Longitude:</label>
							<div class="controls">
								<label class="control-label"><?php echo $general_info['longitude'] ?></label>
							</div>
						</div>
						
						<div class="control-group" style="float:left; margin-left:80px;">
							<label class="control-label" for="siteLocationActualLng">Actual Longitude:</label>
							<div class="controls">
								<label class="control-label"><?php echo @$general_info['actual_longitude'] ?></label>
							</div>
						</div>
						<div class="clearfix"></div>
					  
						<div class="control-group">
							<label class="control-label" for="siteAddress">Site Address:</label>
							<div class="controls">
								<label class="control-label"><?php echo $general_info['site_address'] ?></label>
							</div>
						</div>
						
						<div class="control-group">						   
							<label class="control-label" for="propinsi">Province:</label>
							<div class="controls">
								<?php echo @form_dropdown('propinsi', $propinsi, set_value('propinsi', $general_info['propinsi']), 'id="propinsi"') ?>
							</div>								
						</div>
						
						<div class="control-group">
							<label class="control-label" for="kabupaten">Kabupaten:</label>
							<div class="controls">
								<?php echo @form_dropdown('kabupaten', $kabupaten, set_value('kabupaten', $general_info['kabupaten']), 'id="kabupaten"') ?>						    
							</div>
						</div>
					  
						<div class="control-group">
							<label class="control-label" for="kecamatan">Kecamatan:</label>
							<div class="controls">
								<?php echo @form_dropdown('kecamatan', $kecamatan, set_value('kecamatan', $general_info['kecamatan']), 'id="kecamatan"') ?>
							</div>
						</div>
						
						<div class="control-group">						   
							<label class="control-label" for="kelurahan">Kelurahan:</label>
							<div class="controls">
								<label class="control-label"><?php echo $general_info['kelurahan'] ?></label>							
							</div>
						</div>
					  
						<div class="control-group">
							<label class="control-label" for="kodePos">Kode Pos:</label>
							<div class="controls">
								<label class="control-label"><?php echo $general_info['kode_pos'] ?></label>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="name">Site Specification:</label>
							<div class="controls">
								<?php echo @form_dropdown('siteSpecification', $site_specification, set_value('siteSpecification', $general_info['site_specification']), 'id="siteSpecification"') ?>
							</div>
						</div>
					  
						<div class="control-group">
							<label class="control-label" for="region">Region:</label>
							<div class="controls">
								<?php
								$regions = array('- Choose One -');
								$dataRegions = get_instance()->core->view_all('t_mtr_region', NULL, 'region_name');
								foreach ($dataRegions as $region) {
									$regions[$region['region_id']] = $region['region_name'];
								}
								echo @form_dropdown('region', $regions, set_value('region', $general_info['region_id']), 'id="region" class="medium"');
								?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="cluster">Cluster:</label>
							<div class="controls">
								<label class="control-label"><?php echo $general_info['cluster'] ?></label>
							</div>
						</div>
					  
						<div class="control-group">
							<label class="control-label" for="guardHouse">Guard House:</label>
							<div class="controls">
								<?php echo @form_checkbox('guardHouse', 1, set_value('guardHouse', $general_info['guard_house']), 'id="guardHouse"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="24HourAccess">24 Hour Access:</label>
							<div class="controls">
								<label>
									<?php echo @form_checkbox('24HourAccess', 1, set_value('24HourAccess', $general_info['24_hour_access']), 'id="24HourAccess"') ?>
								</label>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="cmeInsured">CME Insured:</label>
							<div class="controls">
								<label>
									<?php echo @form_checkbox('cmeInsured', 1, set_value('cmeInsured', $general_info['cme_insured']), 'id="cmeInsured"') ?>
								</label>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="equipmentInsured">Equipment Insured:</label>
							<div class="controls">
								<label>
									<?php echo @form_checkbox('equipmentInsured', 1, set_value('equipmentInsured', $general_info['equipment_insured']), 'id="equipmentInsured"') ?>
								</label>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="polisNumber">Polis Number:</label>
							<div class="controls">
								<label class="control-label"><?php echo $general_info['polis_number'] ?></label>
							</div>
						</div>
					  
						<div class="control-group">
							<label class="control-label" for="cmePartnerName">CME Partner:</label>
							<div class="controls">                            
								<?php
								$cmePartner = @get_instance()->core->view_row('t_mtr_cme_partner', array('cme_partner_id' => (int) $general_info['cme_partner']));
								
								echo @form_input('cmePartnerName', set_value('cmePartnerName', trim($cmePartner['nama'])), 'id="cmePartnerName" class="medium" placeholder="Auto complete..."');
								echo @form_hidden('cmePartner', set_value('cmePartner', $general_info['cme_partner']));
								?>
								<span class="add-on">
									<span class="fs1" aria-hidden="true" data-icon="&#xe086;"></span>
								</span>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="picSmart">PIC Smart:</label>
							<div class="controls">
								<label class="control-label"><?php echo $general_info['pic_smart'] ?></label>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="phonePicSmart">Phone PIC Smart:</label>
							<div class="controls">
								<label class="control-label"><?php echo $general_info['phone1'] ?></label>
							</div>
						</div>
					 
						<div class="control-group">
							<label class="control-label" for="picCmePartner">PIC CME Partner:</label>
							<div class="controls">
								<label class="control-label"><?php echo $general_info['pic_cme_partner'] ?></label>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="picCmePartner">Phone PIC CME Partner:</label>
							<div class="controls">
								<label class="control-label"><?php echo $general_info['phone2'] ?></label>
							</div>
						</div>
					  
						<div class="control-group">
							<label class="control-label" for="cutOverDate">Cut Over Date:</label>
							<div class="controls">
								<label class="control-label"><?php echo $general_info['cut_over_date'] ?></label>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="verifiedOnAir">Verified On Air (PAT):</label>
							<div class="controls">
								<label class="control-label"><?php echo $general_info['verified_on_air'] ?></label>
							</div>
						</div>
						
						<div class="control-group">
							<div class="controls">
								<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
							</div>
						</div>
					  
					</div>
				</div>
			</div>
			
			<?php echo form_close() ?>
		</div>
		<!-- end:contentGeneralInfo -->
		
		<!-- start:contentSiteKeepers -->
		<div id="contentSiteKeepers" title="Site Keepers">
			
			<!-- form:Site Keepers -->				
			<?php echo form_open('', 'id="formSiteKeepers" class="form-horizontal"') ?>
			
			<?php echo @form_hidden('siteId', set_value('siteId', $general_info['site_id'])) ?>
			
			<div class="widget">
				<div class="widget-body">
					<div class="row-fluid">
					
						<div class="control-group">
							<label class="control-label" for="name">Name:</label>
							<div class="controls">
								<?php echo @form_input('name', set_value('name', trim($site_keepers['name'])), 'id="name" class="medium"') ?>
							</div>
						</div>
						  
						<div class="control-group">
							<label class="control-label" for="address">Address:</label>
							<div class="controls">
								<?php echo @form_input('address', set_value('address', trim($site_keepers['address'])), 'id="address" class="medium"') ?>
							</div>
						</div>
						 
						<div class="control-group">
							<label class="control-label" for="phone">Phone:</label>
							<div class="controls">
								<?php echo @form_input('phone', set_value('phone', trim($site_keepers['phone'])), 'id="phone" class="medium"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="salary">Salary / Month:</label>
							<div class="controls">
								<?php echo @form_input('salary', set_value('salary', $site_keepers['salary_month']), 'id="salary" class="medium currency"') ?>
							</div>
						</div>
						  
						<div class="control-group">
							<label class="control-label" for="hiredBySmart">Hired By Smart:</label>
							<div class="controls">
								<?php echo @form_checkbox('hiredBySmart', 1, set_value('hiredBySmart', $site_keepers['hired_by_smart']), 'id="hiredBySmart"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="remark">Remark:</label>
							<div class="controls">                      
								<?php echo @form_textarea('remark', set_value('remark', trim($site_keepers['remark'])), 'id="remark" style="width:550px; height:100px;"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<div class="controls">
								<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<?php echo form_close() ?>
			
			<!-- / form:Site Keepers -->
		</div>
		<!-- end:contentSiteKeepers -->
		
		<!-- start:contentPLN -->
		<div id="contentPLN" title="Power (PLN)">
				
			<!-- form:PLN -->			
			<?php echo form_open('', 'id="formPLN" class="form-horizontal"') ?>
			
			<?php echo @form_hidden('siteId', set_value('siteId', $general_info['site_id'])) ?>
			
			<div class="widget">
				<div class="widget-body">
					<div class="row-fluid">
					
						<div class="control-group">
							<label class="control-label" for="handoverProject">Handover Project:</label>
							<div class="controls">
								<?php echo @form_input('handoverProject', set_value('handoverProject', $pln['handover_project']), 'id="handoverProject" class="medium"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="handoverRemark">Handover Remark:</label>
							<div class="controls">
								<?php echo @form_input('handoverRemark', set_value('handoverRemark', $pln['handover_remark']), 'id="handoverRemark" class="medium"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="idPelangganPLN">Customer ID:</label>
							<div class="controls">
								<?php echo @form_input('idPelangganPLN', set_value('idPelangganPLN', $pln['id_pelanggan_pln']), 'id="idPelangganPLN" class="medium"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="atasNamaPelanggan">Customer Name:</label>
							<div class="controls">
								<?php echo @form_input('atasNamaPelanggan', set_value('atasNamaPelanggan', trim($pln['atas_nama_pelanggan'])), 'id="atasNamaPelanggan" class="medium"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="plnConnectedDate">PLN Connected Date:</label>
							<div class="controls">
								<div class="input-append">
									<?php echo @form_input('plnConnectedDate', set_value('plnConnectedDate', $pln['pln_connected_date']), 'id="plnConnectedDate" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
									<button onclick="clearField('plnConnectedDate')" type="button" class="btn">Clear</button>
								</div>
							</div>
						</div>
						  
						<div class="control-group">
							<label class="control-label" for="namaUPJPLN">Name of UPJ PLN:</label>
							<div class="controls">
								<?php echo @form_input('namaUPJPLN', set_value('namaUPJPLN', trim($pln['nama_upj_pln'])), 'id="namaUPJPLN" class="medium"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="woDismantleDate">WO Dismantle Date:</label>
							<div class="controls">
								<div class="input-append">
									<?php echo @form_input('woDismantleDate', set_value('woDismantleDate', $pln['wo_dismantle_date']), 'id="woDismantleDate" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
									<button onclick="clearField('woDismantleDate')" type="button" class="btn">Clear</button>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="plnDisconnectDate">PLN Disconnect Date:</label>
							<div class="controls">
								<div class="input-append">
									<?php echo @form_input('plnDisconnectDate', set_value('plnDisconnectDate', $pln['wo_dismantle_date']), 'id="plnDisconnectDate" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
									<button onclick="clearField('plnDisconnectDate')" type="button" class="btn">Clear</button>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="trafoBlast">Trafo Blast:</label>
							<div class="controls">
								<?php
								$dataTrafoBlast = get_instance()->core->view_all('t_mtr_trafo', NULL, 'nama');
								$trafoBlast = array('' => '- Choose one -');
								foreach ($dataTrafoBlast as $trafo) {
									$trafoBlast[$trafo['trafo_id']] = $trafo['nama'];
								}
								echo @form_dropdown('trafoBlast', $trafoBlast, set_value('trafoBlast', $pln['trafo_blast']), 'id="trafoBlast"');
								?>
							</div>
						</div>
						  
						<div class="control-group">
							<label class="control-label" for="trafoCapacity">Trafo Capacity:</label>
							<div class="controls">
								<?php
								$dataTrafoCapacity = get_instance()->core->view_all('t_mtr_trafo_capacity', NULL, 'nama');
								$trafoCapacity = array('' => '- Choose one -');
								foreach ($dataTrafoCapacity as $trafo) {
									$trafoCapacity[$trafo['trafo_capacity_id']] = $trafo['nama'];
								}
								echo @form_dropdown('trafoCapacity', $trafoCapacity, set_value('trafoCapacity', $pln['trafo_capacity']), 'id="trafoCapacity"');
								?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="trafoMaintainedBySmartfren">Trafo Maintained by Smartfren:</label>
							<div class="controls">
								<?php
								$trafoMaintainedBySmartfren = array('' => '- Choose one -', 1 => 'Yes', 0 => 'No');
								echo @form_dropdown('trafoMaintainedBySmartfren', $trafoMaintainedBySmartfren, set_value('trafoMaintainedBySmartfren', $pln['trafo_maintained_by_smartfren']), 'id="trafoMaintainedBySmartfren" class="span3"');
								?>
							</div>
						</div>
						  
						<div class="control-group">
							<label class="control-label" for="kwhType">KWH Type:</label>
							<div class="controls">                           
								<?php
								$dataKwhType = get_instance()->core->view_all('t_mtr_kwh_type', NULL, 'descr');
								$kwhType = array('' => '- Choose one -');
								foreach ($dataKwhType as $kwh) {
									$kwhType[$kwh['kwh_type_id']] = $kwh['descr'];
								}
								echo @form_dropdown('kwhType', $kwhType, set_value('kwhType', $pln['kwh_type']), 'id="kwhType"');
								?>
							</div>		
						</div>
						
						<div class="control-group">
							<label class="control-label" for="plnPhase">PLN Phase:</label>
							<div class="controls">
								<?php echo @form_input('plnPhase', set_value('plnPhase', $pln['pln_phase']), 'id="plnPhase" class="medium number"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="plnCapacity">PLN Capacity (KVA):</label>
							<div class="controls">
								<?php echo @form_input('plnCapacity', set_value('plnCapacity', $pln['pln_capacity']), 'id="plnCapacity" class="medium number"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="tarifType">Tarif Type:</label>
							<div class="controls">
								<?php
								$tarifType = array(1 => 'Reguler', 2 => 'Non-Reguler');
								echo @form_dropdown('tarifType', $tarifType, set_value('tarifType', $pln['tarif_type']), 'id="tarifType"');
								?>
							</div>
						</div>
						
						<div class="control-group">
						   <label class="control-label" for="tarifName">Tarif Name:</label>
							<div class="controls">
								<?php
								$dataTarifName = get_instance()->core->view_all('t_mtr_tarif_name', NULL, 'nama');
								$tarifName = array('' => '- Choose one -');
								foreach ($dataTarifName as $tarif) {
									$tarifName[$tarif['tarif_name_id']] = $tarif['nama'];
								}
								echo @form_dropdown('tarifName', $tarifName, set_value('tarifName', $pln['tarif_name']), 'id="tarifName"');
								?>
							</div>
						</div>
							
						<div class="control-group">
							<label class="control-label" for="caraPembayaran">Payment Method:</label>
							<div class="controls">
								<?php
								$dataCaraPembayaran = get_instance()->core->view_all('t_mtr_cara_pembayaran', NULL, 'descr');
								$caraPembayaran = array('' => '- Choose one -');
								foreach ($dataCaraPembayaran as $pembayaran) {
									$caraPembayaran[$pembayaran['cara_pembayaran_id']] = $pembayaran['descr'];
								}
								echo @form_dropdown('caraPembayaran', $caraPembayaran, set_value('caraPembayaran', $pln['cara_pembayaran']), 'id="caraPembayaran"');
								?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="remarks">Remarks:</label>
							<div class="controls">
								<?php echo @form_textarea('remarks', set_value('remarks', trim($pln['remarks'])), 'id="remarks" style="width:550px; height:100px;"') ?>
							</div>
						</div>
						
						<fieldset>  
							<legend>Genset</legend>
							
							<div class="control-group">
								<label class="control-label" for="permanentGenset">Permanent Genset:</label>
								<div class="controls">
									<?php
									$permanentGenset = array(1 => 'Yes', 0 => 'No');
									echo @form_dropdown('permanentGenset', $permanentGenset, set_value('permanentGenset', $pln['permanent_genset']), 'id="permanentGenset"');
									?>
								</div>
							</div>
								
							<div class="control-group">
								<label class="control-label" for="gensetBrand">Genset Brand:</label>
								<div class="controls">
									<?php echo @form_input('gensetBrand', set_value('gensetBrand', trim($pln['genset_brand'])), 'id="gensetBrand" class="medium"') ?>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="gensetCapacity">Genset Capacity (KVA):</label>
								<div class="controls">
									<?php echo @form_input('gensetCapacity', set_value('gensetCapacity', $pln['genset_capacity']), 'id="gensetCapacity" class="number"') ?>
								</div>
							</div>
								
							<div class="control-group">
								<label class="control-label" for="gensetOwnership">Genset Ownership:</label>
								<div class="controls">
									<?php
									$dataGensetOwnership = get_instance()->core->view_all('t_mtr_genset_ownership', NULL, 'nama');
									$gensetOwnership = array('' => '- Choose one -');
									foreach ($dataGensetOwnership as $owner) {
										$gensetOwnership[$owner['genset_ownership_id']] = $owner['nama'];
									}
									echo @form_dropdown('gensetOwnership', $gensetOwnership, set_value('gensetOwnership', $pln['genset_ownership']), 'id="gensetOwnership"');
									?>
								</div>
							</div>
						</fieldset>
						
						<div class="control-group">
							<div class="controls">
								<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
							</div>
						</div>
					
					</div>
				</div>
			</div>
			<?php echo form_close() ?>			
			<!-- form:PLN -->
		</div>
		<!-- end:contentPLN -->
		
		<!-- start:contentTower -->
		<div id="contentTower" title="Tower">
			<?php echo form_open('', 'id="formTower" class="form-horizontal"') ?>
			
			<?php echo @form_hidden('siteId', set_value('siteId', $general_info['site_id'])) ?>
			
			<div class="widget">
				<div class="widget-body">
					<div class="row-fluid">
					
						<div class="control-group">
							<label class="control-label" for="kepemilikanTower">Tower Ownership:</label>
							<div class="controls">
								<?php
								$dataKepemilikanTower = get_instance()->core->view_all('t_mtr_tower_provider', NULL, 'vendor_id');
								$kepemilikanTower = array('' => '- Choose one -');
								foreach ($dataKepemilikanTower as $pemilikTower) {
									$kepemilikanTower[$pemilikTower['tower_provider_id']] = $pemilikTower['vendor_id'].'|'.$pemilikTower['nama_pemilik_tower'];
								}
								echo @form_dropdown('kepemilikanTower', $kepemilikanTower, set_value('kepemilikanTower', $tower['kepemilikan_tower']), 'id="kepemilikanTower"');
								?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="namaPemilikTower">Name of Tower Owner:</label>
							<div class="controls">
								<?php echo @form_input('namaPemilikTower', set_value('namaPemilikTower', $tower['nama_pemilik_tower']), 'id="namaPemilikTower" class="medium"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="telpPemilikTower">Phone of Tower Owner:</label>
							<div class="controls">
								<?php echo @form_input('telpPemilikTower', set_value('telpPemilikTower', $tower['no_telp_pemilik_tower']), 'id="telpPemilikTower" class="medium"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="towerType">Tower Type:</label>
							<div class="controls">
								<?php
								$dataTowerType = get_instance()->core->view_all('t_mtr_tower_type', NULL, 'tower_type_name');
								$towerType = array('' => '- Choose one -');
								foreach ($dataTowerType as $type) {
									$towerType[$type['tower_type_id']] = $type['tower_type_name'];
								}
								echo @form_dropdown('towerType', $towerType, set_value('towerType', $tower['tower_type']), 'id="towerType"');
								?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="towerLegNo">Tower Leg No:</label>
							<div class="controls">
								<?php
								$dataTowerLegNo = get_instance()->core->view_all('t_mtr_tower_leg_no', NULL, 'nama');
								$towerLegNo = array('' => '- Choose one -');
								foreach ($dataTowerLegNo as $no) {
									$towerLegNo[$no['tower_leg_no_id']] = $no['nama'];
								}
								echo @form_dropdown('towerLegNo', $towerLegNo, set_value('towerLegNo', $tower['tower_leg_no']), 'id="towerLegNo"');
								?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="towerHeight">Tower Height:</label>
							<div class="controls">
								<?php echo @form_input('towerHeight', set_value('towerHeight', $tower['tower_height']), 'id="towerHeight" class="number"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="towerFabricator">Tower Fabricator:</label>
							<div class="controls">
								<?php echo @form_input('towerFabricator', set_value('towerFabricator', trim($tower['tower_fabricator'])), 'id="towerFabricator" class="medium"') ?>
							</div>
						</div>
						 
						<div class="control-group">
							<label class="control-label" for="remarks">Remarks:</label>
							<div class="controls">
								<?php echo @form_textarea('remarks', set_value('remarks', trim($tower['remarks'])), 'id="remarks" style="width:550px; height:100px;"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<div class="controls">
								<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
							</div>
						</div>
						
					</div>				
				</div>
			</div>
			<?php echo form_close() ?>
			
		</div>
		<!-- end:contentTower -->
		
		<!-- start:contentShelter -->
		<div id="contentShelter" title="Shelter">
			<?php echo form_open('', 'id="formShelter" class="form-horizontal"') ?>
			
			<?php echo @form_hidden('siteId', set_value('siteId', $general_info['site_id'])) ?>
			
			<div class="widget">
				<div class="widget-body">
					<div class="row-fluid">
				   
						<div class="control-group">
							<label class="control-label" for="kepemilikanShelter">Shelter Ownership:</label>
							<div class="controls">
								<?php
								$dataKepemilikanShelter = get_instance()->core->view_all('t_mtr_kepemilikan_shelter', NULL, '');
								$kepemilikanShelter = array('' => '- Choose one -');
								foreach ($dataKepemilikanShelter  as $pemilik) {
									$kepemilikanShelter[$pemilik['kepemilikan_shelter_id']] = $pemilik['descr'];
								}
								echo @form_dropdown('kepemilikanShelter', $kepemilikanShelter, set_value('kepemilikanShelter', $shelter['kepemilikan_shelter']), 'id="kepemilikanShelter"');
								?>
							</div>
						</div>
					  
						<div class="control-group">
							<label class="control-label" for="namaPemilikShelter">Name of Shelter Owner:</label>
							<div class="controls">
								<?php echo @form_input('namaPemilikShelter', set_value('namaPemilikShelter', trim($shelter['nama_pemilik_shelter'])), 'id="namaPemilikShelter" class="medium"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="noTelpPemilikShelter">Phone of Shelter Owner:</label>
							<div class="controls">
								<?php echo @form_input('noTelpPemilikShelter', set_value('noTelpPemilikShelter', $shelter['no_telp_pemilik_shelter']), 'id="noTelpPemilikShelter" class="medium"') ?>
							</div>
						</div>
					  
						<div class="control-group">
							<label class="control-label" for="shelterType">Shelter Type:</label>
							<div class="controls">
								<?php
								$dataShelterType = $this->core->get_lookup('shelter_type');
								$shelterType = array('' => '- Choose one -');
								foreach ($dataShelterType  as $type) {
									$shelterType[$type['id']] = $type['desc'];
								}
								echo @form_dropdown('shelterType', $shelterType, set_value('shelterType', $shelter['shelter_type']), 'id="shelterType"');
								?>
							</div>
						</div>
					
						<div class="control-group">
							<label class="control-label" for="shelterSize">Shelter Size (LxWxH):</label>
							<div class="controls">
								<?php echo @form_input('shelterSize', set_value('shelterSize', $shelter['shelter_size']), 'id="shelterSize" class="medium"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="shelterFabricator">Shelter Fabricator:</label>
							<div class="controls">
								<?php echo @form_input('shelterFabricator', set_value('shelterFabricator', trim($shelter['shelter_fabricator'])), 'id="shelterFabricator" class="medium"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="remarks">Remarks:</label>
							<div class="controls">
								<?php echo @form_textarea('remarks', set_value('remarks', trim($shelter['remarks'])), 'id="remarks" style="width:550px; height:100px;"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<div class="controls">
								<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
							</div>
						</div>
					
					</div>
				</div>
			</div>
			<?php echo form_close() ?>
		</div>
		<!-- end:contentShelter -->
		
		<!-- start:contentLahan -->
		<div id="contentLahan" title="Space">
			
			<?php echo form_open('', 'id="formLahan" class="form-horizontal"') ?>
			
			<?php echo @form_hidden('siteId', set_value('siteId', $general_info['site_id'])) ?>
			
			<div class="widget">
				<div class="widget-body">
					<div class="row-fluid">
					
						<div class="control-group">
							<label class="control-label" for="kepemilikanLahan">Land Ownership:</label>
							<div class="controls">
								<?php
								$kepemilikanLahan = array(0 => 'Beli', 1 => 'Sewa');
								echo @form_dropdown('kepemilikanLahan', $kepemilikanLahan, set_value('kepemilikanLahan', $lahan['kepemilikan_lahan']), 'id="kepemilikanLahan"');
								?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="jenisSuratLahan">Land Certificate Type:</label>
							<div class="controls">
								<?php
								$dataJenisSuratLahan = get_instance()->core->view_all('t_mtr_jenis_surat_lahan', NULL,'nama');
								$jenisSuratLahan = array('' => '- Choose one -');
								foreach ($dataJenisSuratLahan as $jenis) {
									$jenisSuratLahan[$jenis['jenis_surat_lahan_id']] = $jenis['nama'];
								}
								echo @form_dropdown('jenisSuratLahan', $jenisSuratLahan, set_value('jenisSuratLahan', $lahan['jenis_surat_lahan']), 'id="jenisSuratLahan"');
								?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="noAJB">No. AJB/PKS:</label>
							<div class="controls">
								<?php echo @form_input('noAJB', set_value('noAJB', $lahan['no_ajb']), 'id="noAJB" class="medium"') ?>
							</div>
						</div>
						  
						<div class="control-group">
							<label class="control-label" for="dateAJB">Date AJB/PKS:</label>
							<div class="controls">
								<?php echo @form_input('dateAJB', set_value('dateAJB', $lahan['date_ajb']), 'id="dateAJB"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="noSuratLahan">Land Certificate Number:</label>
							<div class="controls">
								<?php echo @form_input('noSuratLahan', set_value('noSuratLahan', $lahan['no_surat_lahan']), 'id="noSuratLahan" class="medium"') ?>
							</div>
						</div>
						  
						<div class="control-group">
							<label class="control-label" for="luasLahan">Land Size:</label>
							<div class="controls">
								<div class="input-append">
									<?php echo @form_input('luasLahan', set_value('luasLahan', $lahan['luas_lahan']), 'id="luasLahan" class="number span16"') ?>
									<span class="add-on">M<sup>2</sup></span>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">Road Access:</label>
							<div class="controls controls-row">
								<label class="control-label" style="width:50px">Width:</label>
								<?php echo @form_input('aksesJalanLebar', set_value('aksesJalanLebar', $lahan['akses_jalan_lbr']), 'id="aksesJalanLebar" class="input-mini number" style="float:left"') ?>
								
								<label class="control-label" style="width:80px; margin-left:20px">x &nbsp;&nbsp;&nbsp; Height:</label>
								<?php echo @form_input('aksesJalanPanjang', set_value('aksesJalanPanjang', $lahan['akses_jalan_pjg']), 'id="aksesJalanPanjang" class="input-mini number" style="float:left"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="sudahBalikNama">Sudah Balik Nama:</label>
							<div class="controls">
								<?php
								$sudahBalikNama = array(0 => 'No', 1 => 'Yes');
								echo @form_dropdown('sudahBalikNama', $sudahBalikNama, set_value('sudahBalikNama', $lahan['sudah_balik_nama']), 'id="sudahBalikNama"');
								?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="noBaru">New Number:</label>
							<div class="controls">
								<?php echo @form_input('noBaru', set_value('noBaru', $lahan['no_baru']), 'id="noBaru" class="medium"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="leaseTerm">Lease Term (year):</label>
							<div class="controls">
								<?php echo @form_input('leaseTerm', set_value('leaseTerm', $lahan['lease_term']), 'id="leaseTerm" class=""') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="leaseStartDate">Lease Start Date:</label>
							<div class="controls">
								<div class="input-append">
									<?php echo @form_input('leaseStartDate', set_value('leaseStartDate', $lahan['lease_start_date']), 'id="leaseStartDate" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
									<button class="btn" type="button" onclick="clearField('leaseStartDate')">Clear</button>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="leaseFinishDate">Lease Finish Date:</label>
							<div class="controls">
								<div class="input-append">
									<?php echo @form_input('leaseFinishDate', set_value('leaseFinishDate', $lahan['lease_finish_date']), 'id="leaseFinishDate" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
									<button class="btn" type="button" onclick="clearField('leaseFinishDate')">Clear</button>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="namaPemilikLahan">Name of Land Ownership:</label>
							<div class="controls">
								<?php echo @form_input('namaPemilikLahan', set_value('namaPemilikLahan', trim($lahan['nama_pemilik_lahan'])), 'id="namaPemilikLahan" class="medium"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="noTelpPemilikLahan">Phone of Land Ownership:</label>
							<div class="controls">
								<?php echo @form_input('noTelpPemilikLahan', set_value('noTelpPemilikLahan', $lahan['no_telp_pemilik_lahan']), 'id="noTelpPemilikLahan" class=""') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="contactPersonLahan">Contact Person of Land:</label>
							<div class="controls">
								<?php echo @form_input('contactPersonLahan', set_value('contactPersonLahan', trim($lahan['contact_person_lahan'])), 'id="contactPersonLahan" class="medium"') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="noTelpContactPerson">Phone of Contact Person:</label>
							<div class="controls">
								<?php echo @form_input('noTelpContactPerson', set_value('noTelpContactPerson', $lahan['no_telp_contact_person']), 'id="noTelpContactPerson" class=""') ?>
							</div>
						</div>
						
						<fieldset>
							<legend>SMG Property</legend>
							<div class="control-group">
								<label class="control-label" for="status">Status:</label>
								<div class="controls">
									<?php
									$status = array(0 => 'No', 1 => 'Yes');
									echo @form_dropdown('status', $status, set_value('status', $lahan['status']), 'id="status"');
									?>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="company">Company:</label>
								<div class="controls">
									<?php echo @form_input('company', set_value('company', trim($lahan['company'])), 'id="company" class="medium"') ?>
								</div>
							</div>
						</fieldset>
						
						<div class="control-group">
							<div class="controls">
								<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
							</div>
						</div>
					
					</div>
				</div>
			</div>
			
			<?php echo form_close() ?> 
		</div>
		<!-- end:contentLahan -->
		
		<!-- start:contentPerijinan -->
		<div id="contentPerijinan" title="Permission">
			<?php echo form_open('', 'id="formPerijinan" class="form-horizontal"') ?>
			
			<?php echo @form_hidden('siteId', set_value('siteId', $general_info['site_id'])) ?>
			
			<div class="widget">
				<div class="widget-body">
					<div class="row-fluid">
				
						<div class="control-group column-left">
							<label class="control-label" for="iwNeeded">IW Needed:</label>
							<div class="controls">
								<?php
								$iwNeeded = array(0 => 'No', 1 => 'Yes');
								echo @form_dropdown('iwNeeded', $iwNeeded, set_value('iwNeeded', $perijinan['iw_needed']), 'id="iwNeeded"');
								?>
							</div>
						</div>
						
						<div class="control-group column-right">
							<label class="control-label" for="iwDate">IW Date:</label>
							<div class="controls">
								<div class="input-append">
									<?php echo @form_input('iwDate', set_value('iwDate', $perijinan['iw_date']), 'id="iwDate" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
									<button onclick="clearField('iwDate')" type="button" class="btn">Clear</button>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						
						<div class="control-group column-left">
							<label class="control-label" for="retribusi">Retribution:</label>
							<div class="controls">
								<?php
								$retribusi = array(0 => 'No', 1 => 'Yes');
								echo @form_dropdown('retribusi', $retribusi, set_value('retribusi', $perijinan['retribusi']), 'id="retribusi"');
								?>
							</div>
						</div>
						
						<div class="control-group column-right">
							<label class="control-label" for="biayaRetribusi">Retribution Fee:</label>
							<div class="controls">
								<?php echo @form_input('biayaRetribusi', set_value('biayaRetribusi', trim($perijinan['biaya_retribusi'])), 'id="biayaRetribusi" class="currency"') ?>
							</div>
						</div>
						<div class="clearfix"></div>
						
						<fieldset>
							<legend>HO Info</legend>
							
							<div class="control-group">
								<label class="control-label" for="hoNumber">HO Number:</label>
								<div class="controls">
									<?php echo @form_input('hoNumber', set_value('hoNumber', trim($perijinan['ho_number'])), 'id="hoNumber" class="medium"') ?>
								</div>
							</div>
							
							<div class="control-group column-left">
								<label class="control-label" for="hoDate">HO Date:</label>
								<div class="controls">
									<div class="input-append">
										<?php echo @form_input('hoDate', set_value('hoDate', $perijinan['ho_date']), 'id="hoDate" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
										<button onclick="clearField('hoDate')" type="button" class="btn">Clear</button>
									</div>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="hoExpired">HO Expired:</label>
								<div class="controls">
									<div class="input-append">
										<?php echo @form_input('hoExpired', set_value('hoExpired', $perijinan['ho_expired']), 'id="hoExpired" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
										<button onclick="clearField('hoExpired')" type="button" class="btn">Clear</button>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</fieldset>
						
						<fieldset>
							<legend>PBB</legend>
							
							<div class="control-group column-left">
								<label class="control-label" for="nomorPBB">PBB Number:</label>
								<div class="controls">
									<?php echo @form_input('nomorPBB', set_value('nomorPBB', $perijinan['nomor_pbb']), 'id="nomorPBB" class="medium"') ?>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="atasNama">Atas Nama:</label>
								<div class="controls">
									<?php echo @form_input('atasNama', set_value('atasNama', trim($perijinan['atas_nama'])), 'id="atasNama" class="medium"') ?>
								</div>
							</div>
							<div class="clearfix"></div>
							
							<div class="control-group column-left">
								<label class="control-label" for="jatuhTempo">Due Date:</label>
								<div class="controls">
									<div class="input-append">
										<?php echo @form_input('jatuhTempo', set_value('jatuhTempo', $perijinan['jatuh_tempo']), 'id="jatuhTempo" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
										<button onclick="clearField('jatuhTempo')" type="button" class="btn">Clear</button>
									</div>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="nilaiPBB">Nilai PBB:</label>
								<div class="controls">
									<?php echo @form_input('nilaiPBB', set_value('nilaiPBB', $perijinan['nilai_pbb']), 'id="nilaiPBB" class=""') ?>
								</div>
							</div>
							<div class="clearfix"></div>
							
							<div class="control-group">
								<label class="control-label" for="picPBB">PIC PBB:</label>
								<div class="controls">
									<?php
									$dataPICPBB = get_instance()->core->view_all('t_mtr_pic_pbb', NULL, 'descr');
									$picPBB = array('' => '- Choose one -');
									foreach ($dataPICPBB as $pic) {
										$picPBB[$pic['pic_pbb_id']] = $pic['descr'];
									}
									echo @form_dropdown('picPBB', $picPBB, set_value('picPBB', $perijinan['pic_pbb']), 'id="picPBB"');
									?>
								</div>
							</div>
						</fieldset>
						
						<fieldset>
							<legend>IMB</legend>
							
							<div class="control-group">
								<label class="control-label" for="nomorIMB">IMB Number:</label>
								<div class="controls">
									<?php echo @form_input('nomorIMB', set_value('nomorIMB', trim($perijinan['nilai_pbb'])), 'id="nomorIMB" class="medium"') ?>
								</div>
							</div>
							
							<div class="control-group column-left">
								<label class="control-label" for="imbDate">Date:</label>
								<div class="controls">
									<div class="input-append">
										<?php echo @form_input('imbDate', set_value('imbDate', $perijinan['date']), 'id="imbDate" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
										<button onclick="clearField('imbDate')" type="button" class="btn">Clear</button>
									</div>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="imbExpired">Expired:</label>
								<div class="controls">
									<div class="input-append">
										<?php echo @form_input('imbExpired', set_value('imbExpired', $perijinan['expired']), 'id="imbExpired" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
										<button onclick="clearField('imbExpired')" type="button" class="btn">Clear</button>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</fieldset>
						
						<fieldset>
							<legend>Rekomendasi Ketinggian Tower (RKT)</legend>
							
							<div class="control-group column-left">
								<label class="control-label" for="rktNumber">RKT Number:</label>
								<div class="controls">
									<?php echo @form_input('rktNumber', set_value('rktNumber', trim($perijinan['rkt_number'])), 'id="rktNumber" class="medium"') ?>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="rktDate">RKT Date:</label>
								<div class="controls">
									<div class="input-append">
										<?php echo @form_input('rktDate', set_value('rktDate', $perijinan['rkt_date']), 'id="rktDate" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
										<button onclick="clearField('rktDate')" type="button" class="btn">Clear</button>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							
							<div class="control-group">
								<label class="control-label" for="remarks">Remarks:</label>
								<div class="controls">
									<?php echo @form_textarea('remarks', set_value('remarks', trim($perijinan['remarks'])), 'id="remarks" style="width:550px; height:100px;"') ?>
								</div>
							</div>
						</fieldset>
					
						<div class="control-group">
							<div class="controls">
								<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
							</div>
						</div>
					
					</div>
				</div>
			</div>
			<?php echo form_close() ?>
		</div>
		<!-- end:contentPerijinan -->
		
		<!-- start:contentTenancy -->
		<div id="contentTenancy" title="Tenancy">
			<?php echo form_open('', 'id="formTenancy" class="form-horizontal"') ?>
			
			<?php echo @form_hidden('siteId', set_value('siteId', $general_info['site_id'])) ?>
			
			<div class="widget">
				<div class="widget-body">
					<div class="row-fluid">
						
						<fieldset>
							<legend>Colloc Sell</legend>
							
							<div class="control-group">
								<label class="control-label" for="availableForRent">Available for Rent:</label>
								<div class="controls">
									<?php
									$availableForRent = array(0 => 'No', 1 => 'Yes');
									echo @form_dropdown('availableForRent', $availableForRent, set_value('availableForRent', $tenancy_sell[0]['available_for_rent']), 'id="availableForRent"');
									?>
								</div>
							</div>
							
							<?php
							if (get_instance()->uri->segment(2) == 'edit' && $tenancy_sell) {
							
								$sellcounter = count($tenancy_sell);
								$i = 1;
								foreach ($tenancy_sell as $sell) {
									$loop = '';
									if ($i == 1) {
										$loop = $i.'st';
									} elseif ($i == 2) {
										$loop = $i.'nd';
									} elseif ($i == 3) {
										$loop = $i.'rd';
									} else {
										$loop = $i.'th';
									}
							?>						
							<div class="control-group column-left" id="fieldTenantCollocSell-<?php echo $i ?>">
								<label class="control-label" for="tenantCollocSell-<?php echo $i ?>">Tenant <?php echo $loop ?>:</label>
								<div class="controls">
									<?php //echo @form_input('tenantCollocSell[]', set_value('tenantCollocSell', trim($item['name'])), 'id="tenantCollocSell-'.$i.'" class="tenant-data" size="40"') ?>
									<?php
									$tenantCollocSell = array('' => '- Choose one -');
									$dataTenantCollocSell = get_instance()->core->view_all('t_mtr_tenant', array('status' => 1), 'tenant_name');
									foreach ($dataTenantCollocSell as $tenant) {
										$tenantCollocSell[$tenant['tenant_id']] = $tenant['tenant_name'];
									}
									echo @form_dropdown('tenantCollocSell[]', $tenantCollocSell, set_value('tenantCollocSell[]', $sell['tenant_id']), 'id="tenantCollocSell-'.$i.'" style="float:left"');
									?>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="tenantDateCollocSell-<?php echo $i ?>">Date:</label>
								<div class="controls">
									<?php echo @form_input('tenantDateCollocSell[]', set_value('tenantDateCollocSell', $sell['tenancy_date']), 'id="tenantDateCollocSell-'.$i.'" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
									<button class="btn" type="button" onclick="clearField('tenantDateCollocSell-<?php echo $i ?>')">Clear</button>
								</div>
							</div>
							<div class="clearfix"></div>
							
							<?php
									$i++;
								}
							} else { // default one
							?>
							
							<div class="control-group column-left" id="fieldTenantCollocSell-1">
								<label class="control-label" for="tenantCollocSell-1">Tenant 1st:</label>
								<div class="controls">
									<?php
									$tenantCollocSell = array('' => '- Choose one -');
									$dataTenantCollocSell = get_instance()->core->view_all('t_mtr_tenant', array('status' => 1), 'tenant_name');
									foreach ($dataTenantCollocSell as $tenant) {
										$tenantCollocSell[$tenant['tenant_id']] = $tenant['tenant_name'];
									}
									echo @form_dropdown('tenantCollocSell[]', $tenantCollocSell, '', 'id="tenantCollocSell-1" style="float:left"');
									?>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="tenantDateCollocSell-1">Date:</label>
								<div class="controls">
									<div class="input-append">
										<?php echo @form_input('tenantDateCollocSell[]', set_value('tenantDateCollocSell', ''), 'id="tenantDateCollocSell-1" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
										<button onclick="clearField('tenantDateCollocSell-1')" type="button" class="btn">Clear</button>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							
							<?php } ?>
							
							<div id="addCollocSell" class="control-group">
								<div class="controls">[ <a href="javascript:void(0)" onclick="addCollocSellRow()">Add more</a> ]</div>
							</div>
							
						</fieldset>
						
						<fieldset>
							<legend>Colloc Buy</legend>
							
							<div class="control-group">
								<label class="control-label" for="seqNumberOfTenant">Seq. Number of Tenant:</label>
								<div class="controls">
									<?php echo @form_input('seqNumberOfTenant', set_value('seqNumberOfTenant', $tenancy_buy[0]['seq_number']), 'id="seqNumberOfTenant" class="medium"') ?>
								</div>
							</div>
							
							<?php
							if (get_instance()->uri->segment(2) == 'edit' && $tenancy_buy) {
							
								$buycounter = count($tenancy_buy);
								$i = 1;
								foreach ($tenancy_buy as $buy) {
									$loop = '';
									if ($i == 1) {
										$loop = $i.'st';
									} elseif ($i == 2) {
										$loop = $i.'nd';
									} elseif ($i == 3) {
										$loop = $i.'rd';
									} else {
										$loop = $i.'th';
									}
							?>
							<div class="control-group column-left" id="fieldTenantCollocBuy-<?php echo $i ?>">
								<label class="control-label" for="tenantCollocBuy-<?php echo $i ?>">Tenant <?php echo $loop ?>:</label>
								<div class="controls">
									<?php
									$tenantCollocBuy = array('' => '- Choose one -');
									$dataTenantCollocBuy = get_instance()->core->view_all('t_mtr_tenant', array('status' => 1), 'tenant_name');
									foreach ($dataTenantCollocBuy as $tenant) {
										$tenantCollocBuy[$tenant['tenant_id']] = $tenant['tenant_name'];
									}
									echo @form_dropdown('tenantCollocBuy[]', $tenantCollocBuy, set_value('tenantCollocBuy[]', $buy['tenant_id']), 'id="tenantCollocBuy-'.$i.'" style="float:left"');
									?>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="tenantDateCollocBuy-<?php echo $i ?>">Date:</label>
								<div class="controls">
									<div class="input-append">
										<?php echo @form_input('tenantDateCollocBuy[]', set_value('tenantDateCollocBuy', $buy['tenancy_date']), 'id="tenantDateCollocBuy-'.$i.'" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
										<button onclick="clearField('tenantDateCollocBuy-<?php echo $i ?>')" type="button" class="btn">Clear</button>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							
							<?php
									$i++;
								}
							} else { // default one
							?>
							
							<div class="control-group column-left" id="fieldTenantCollocBuy-1">
								<label class="control-label" for="tenantCollocBuy-1">Tenant 1st:</label>
								<div class="controls">
									<?php //echo @form_input('tenantCollocBuy[]', set_value('tenantCollocBuy', ''), 'id="tenantCollocBuy-1" class="tenant-data" size="40"') ?>
									<?php
									$tenantCollocBuy = array('' => '- Choose one -');
									$dataTenantCollocBuy = get_instance()->core->view_all('t_mtr_tenant', array('status' => 1), 'tenant_name');
									foreach ($dataTenantCollocBuy as $tenant) {
										$tenantCollocBuy[$tenant['tenant_id']] = $tenant['tenant_name'];
									}
									echo @form_dropdown('tenantCollocBuy[]', $tenantCollocBuy, '', 'id="tenantCollocBuy-1" style="float:left"');
									?>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="tenantDateCollocBuy-1">Date:</label>
								<div class="controls">
									<div class="input-append">
										<?php echo @form_input('tenantDateCollocBuy[]', set_value('tenantDateCollocBuy', ''), 'id="tenantDateCollocBuy-1" class="date input-medium" readonly="readonly" placeholder="YYYY-MM-DD"') ?>
										<button onclick="clearField('tenantDateCollocBuy-1')" type="button" class="btn">Clear</button>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							
							<?php } ?>
							
							<div id="addCollocBuy" class="control-group">
								<div class="controls">[ <a href="javascript:void(0)" onclick="addCollocBuyRow()">Add more</a> ]</div>
							</div>
							
						</fieldset>
					
						<div class="control-group">
							<div class="controls">
								<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<?php echo form_close() ?>
		</div>
		<!-- end:contentTenancy -->
		
		<!-- start:contentAC -->
		<div id="contentAC" title="Air Conditioner (AC)">
			<?php echo form_open('', 'id="formAC" class="form-horizontal"') ?>
			
			<?php echo @form_hidden('siteId', set_value('siteId', $general_info['site_id'])) ?>
			
			<div class="widget">
				<div class="widget-body">
					<div class="row-fluid">
						
						<fieldset>
							<legend>AC1</legend>
							
							<div class="control-group column-left">
								<label class="control-label" for="brandAC1">Brand:</label>
								<div class="controls">
									<?php
									$dataBrandAc1 = get_instance()->core->view_all('t_mtr_ac_brand', NULL, 'nama');
									$brandAC1 = array('' => '- Choose one -');
									foreach ($dataBrandAc1 as $brand1) {
										$brandAC1[$brand1['ac_brand_id']] = $brand1['nama'];
									}
									echo @form_dropdown('brandAC1', $brandAC1, set_value('brandAC1', $ac['ac1_brand']), 'id="brandAC1"');
									?>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="typeAC1">Type / Model:</label>
								<div class="controls">
									<?php echo @form_input('typeAC1', set_value('typeAC1', $ac['ac1_type']), 'id="typeAC1" class="medium"') ?>
								</div>
							</div>
							<div class="clearfix"></div>
							
							<div class="control-group column-left">
								<label class="control-label" for="capacityAC1">Capacity:</label>
								<div class="controls">
									<?php
									$dataCapacity1 = get_instance()->core->view_all('t_mtr_ac_capacity', NULL, 'nama');
									$capacityAC1 = array('' => '- Choose one -');
									foreach ($dataCapacity1 as $capacity1) {
										$capacityAC1[$capacity1['ac_capacity_id']] = $capacity1['nama'];
									}
									echo @form_dropdown('capacityAC1', $capacityAC1, set_value('capacityAC1', $ac['ac1_capacity']), 'id="capacityAC1"');
									?>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="serialNumberAC1">Serial Number:</label>
								<div class="controls">
									<?php echo @form_input('serialNumberAC1', set_value('serialNumberAC1', $ac['ac1_serial']), 'id="serialNumberAC1" class="medium"') ?>
								</div>
							</div>
							<div class="clearfix"></div>
						</fieldset>
						
						<fieldset>
							<legend>AC2</legend>
							
							<div class="control-group column-left">
								<label class="control-label" for="brandAC2">Brand:</label>
								<div class="controls">
									<?php
									$dataBrandAc2 = get_instance()->core->view_all('t_mtr_ac_brand', NULL, 'nama');
									$brandAC2 = array('' => '- Choose one -');
									foreach ($dataBrandAc2 as $brand2) {
										$brandAC2[$brand2['ac_brand_id']] = $brand2['nama'];
									}
									echo @form_dropdown('brandAC2', $brandAC2, set_value('brandAC2', $ac['ac2_brand']), 'id="brandAC2"');
									?>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="typeAC2">Type / Model:</label>
								<div class="controls">
									<?php echo @form_input('typeAC2', set_value('typeAC2', $ac['ac2_type']), 'id="typeAC2" class="medium"') ?>
								</div>
							</div>
							<div class="clearfix"></div>
							
							<div class="control-group column-left">
								<label class="control-label" for="capacityAC2">Capacity:</label>
								<div class="controls">
									<?php
									$dataCapacity2 = get_instance()->core->view_all('t_mtr_ac_capacity', NULL, 'nama');
									$capacityAC2 = array('' => '- Choose one -');
									foreach ($dataCapacity2 as $capacity2) {
										$capacityAC2[$capacity2['ac_capacity_id']] = $capacity2['nama'];
									}
									echo @form_dropdown('capacityAC2', $capacityAC2, set_value('capacityAC2', $ac['ac2_capacity']), 'id="capacityAC2"');
									?>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="serialNumberAC2">Serial Number:</label>
								<div class="controls">
									<?php echo @form_input('serialNumberAC2', set_value('serialNumberAC2', $ac['ac2_serial']), 'id="serialNumberAC2" class="medium"') ?>
								</div>
							</div>
							<div class="clearfix"></div>
						</fieldset>
						
						<fieldset>
							<legend>AC3</legend>
							
							<div class="control-group column-left">
								<label class="control-label" for="brandAC3">Brand:</label>
								<div class="controls">
									<?php
									$dataBrandAc3 = get_instance()->core->view_all('t_mtr_ac_brand', NULL, 'nama');
									$brandAC3 = array('' => '- Choose one -');
									foreach ($dataBrandAc3 as $brand3) {
										$brandAC3[$brand3['ac_brand_id']] = $brand3['nama'];
									}
									echo @form_dropdown('brandAC3', $brandAC3, set_value('brandAC3', $ac['ac3_brand']), 'id="brandAC3"');
									?>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="typeAC3">Type / Model:</label>
								<div class="controls">
									<?php echo @form_input('typeAC3', set_value('typeAC3', $ac['ac3_type']), 'id="typeAC3" class="medium"') ?>
								</div>
							</div>
							<div class="clearfix"></div>
							
							<div class="control-group column-left">
								<label class="control-label" for="capacityAC3">Capacity:</label>
								<div class="controls">
									<?php
									$dataCapacity3 = get_instance()->core->view_all('t_mtr_ac_capacity', NULL, 'nama');
									$capacityAC3 = array('' => '- Choose one -');
									foreach ($dataCapacity3 as $capacity3) {
										$capacityAC3[$capacity3['ac_capacity_id']] = $capacity3['nama'];
									}
									echo @form_dropdown('capacityAC3', $capacityAC3, set_value('capacityAC3', $ac['ac3_capacity']), 'id="capacityAC3"');
									?>
								</div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="serialNumberAC3">Serial Number:</label>
								<div class="controls">
									<?php echo @form_input('serialNumberAC3', set_value('serialNumberAC3', $ac['ac2_serial']), 'id="serialNumberAC3" class="medium"') ?>
								</div>
							</div>
							<div class="clearfix"></div>
						</fieldset>
						
						<div class="control-group">
							<label class="control-label" for="remarks">Remarks:</label>							
							<div class="controls">
								<?php echo @form_textarea('remarks', set_value('remarks', trim($ac['remarks'])), 'id="remarks" style="width:550px; height:100px;"') ?>
							</div>
						</div>
					
						<div class="control-group">
							<div class="controls">
								<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
							</div>
						</div>
					
					</div>
				</div>
			</div>
			
			<?php echo form_close() ?>
		</div>
		<!-- end:contentAC -->

		<!-- start:contentEquipmet -->
		<div id="contentEquipment" title="Equipment">
			<?php echo form_open('', 'id="formEquipment" class="form-horizontal"') ?>
			
			<?php echo @form_hidden('siteId', set_value('siteId', $general_info['site_id'])) ?>
			
			<div class="widget">
				<div class="widget-body">
					<div class="row-fluid">
				
						<fieldset>
							<legend>Antena BTS</legend>
							
							<div class="control-group column-left">
								<label class="control-label" for="jumlahAntenaBTS">Jumlah Antena BTS:</label>
								<div class="controls"><?php echo @form_input('jumlahAntenaBTS', set_value('jumlahAntenaBTS', $equipment['jumlah_antena_bts']), 'id="jumlahAntenaBTS" class="just-number"') ?></div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="ketinggianAntenaBTS">Ketinggian Antena BTS:</label>
								<div class="controls"><?php echo @form_input('ketinggianAntenaBTS', set_value('ketinggianAntenaBTS', $equipment['ketinggian_antena_bts']), 'id="ketinggianAntenaBTS" class="just-number"') ?></div>
							</div>
							<div class="clearfix"></div>
							
							<div class="control-group">
								<label class="control-label" for="azimuthBTS">Azimuth:</label>
								<div class="controls"><?php echo @form_input('azimuthBTS', set_value('azimuthBTS', $equipment['azimuth_bts']), 'id="azimuthBTS" class="just-number"') ?></div>
							</div>
						</fieldset>
						
						<fieldset>
							<legend>Antena MW</legend>
							<div class="control-group column-left">
								<label class="control-label" for="jumlahAntenaMW">Jumlah Antena MW:</label>
								<div class="controls"><?php echo @form_input('jumlahAntenaMW', set_value('jumlahAntenaMW', $equipment['jumlah_antena_mw']), 'id="jumlahAntenaMW" class="just-number"') ?></div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="ketinggianAntenaMW">Ketinggian Antena MW:</label>
								<div class="controls"><?php echo @form_input('ketinggianAntenaMW', set_value('ketinggianAntenaMW', $equipment['ketinggian_antena_mw']), 'id="ketinggianAntenaMW" class="just-number"') ?></div>
							</div>
							<div class="clearfix"></div>
							
							<div class="control-group column-left">
								<label class="control-label" for="azimuthMW">Azimuth:</label>
								<div class="controls"><?php echo @form_input('azimuthMW', set_value('azimuthMW', $equipment['azimuth_mw']), 'id="azimuthMW" class="just-number"') ?></div>
							</div>
							
							<div class="control-group column-right">
								<label class="control-label" for="diameterMW">Diameter:</label>
								<div class="controls"><?php echo @form_input('diameterMW', set_value('diameterMW', $equipment['diameter_mw']), 'id="diameterMW" class="just-number"') ?></div>
							</div>
							<div class="clearfix"></div>
						</fieldset>
						
						<div class="control-group">
							<label class="control-label" for="farEnd">Far End:</label>
							<div class="controls"><?php echo @form_input('farEnd', set_value('farEnd', $equipment['far_end']), 'id="farEnd" class=""') ?></div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="siteClassificationTransmisi">Site Classification Transmisi:</label>
							<div class="controls">
								<?php
								$siteClassificationTransmisi = array(
									'' => '- Choose one -',
									'End Site' => 'End Site',
									'Collector' => 'Collector',
									'Sub Hub' => 'Sub Hub',
									'Hub' => 'Hub',
									'Super Hub' => 'Super Hub'
								);
								echo @form_dropdown('siteClassificationTransmisi', $siteClassificationTransmisi, set_value('siteClassificationTransmisi', $equipment['site_classification_transmisi']), 'id="siteClassificationTransmisi" class=""');
								?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="morphology">Morphology:</label>
							<div class="controls">
								<?php
								$morphology = array(
									'' => '- Choose one -',
									'Sub Urban' => 'Sub Urban',
									'Urban' => 'Urban',
									'Dense Urban' => 'Dense Urban',
									'Rural' => 'Rural'
								);
								echo @form_dropdown('morphology', $morphology, set_value('morphology', $equipment['morphology']), 'id="morphology" class=""');
								?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="btsMode">BTS Mode:</label>
							<div class="controls">
								<?php
								$btsMode = array('' => '- Choose one -', 'SINGLE_BAND' => 'Single Band', 'DUAL_BAND' => 'Dual Band');
								echo @form_dropdown('btsMode', $btsMode, set_value('btsMode', $equipment['bts_mode']), 'id="btsMode" class=""');
								?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="btsSpec">BTS Spec.:</label>
							<div class="controls">
								<?php
								$dataBtsSpec = get_instance()->core->view_all('t_mtr_bts_spec', array('status' => 1), 'bts_spec_name');
								$btsSpec = array('' => '- Choose one -');
								foreach ($dataBtsSpec as $spec) {
									$btsSpec[$spec['bts_spec_id']] = $spec['bts_spec_name'];
								}
								echo @form_dropdown('btsSpec', $btsSpec, set_value('btsSpec', $equipment['bts_spec']), 'id="btsSpec" class=""');
								?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="btsType">BTS Type:</label>
							<div class="controls">
								<?php
								$btsType = array('' => '- Choose one -', 'INDOOR' => 'Indoor', 'OUTDOOR' => 'Outdoor');
								echo @form_dropdown('btsType', $btsType, set_value('btsType', $equipment['bts_type']), 'id="btsType" class=""');
								?>
							</div>
						</div>
						
						<div class="control-group">
							<div class="controls">
								<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			
			<?php echo form_close() ?>
		</div>
		<!-- end:contentEquipment -->

	</div>
	<!-- end:siteInfo -->

</div>

<script src="<?php echo base_url('/assets/js/jquery-1.7.1.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-ui-1.8.16/jquery-ui-1.8.16.custom.min.js') ?>"></script>

<script><!--

var formSubmit = function(form) {
	
	<?php if (get_instance()->uri->segment(2) == 'add') { ?>
	var url = '<?php echo site_url('/site_info/add') ?>';
	<?php } else { ?>
	var url = '<?php echo site_url('/site_info/edit/'.get_instance()->uri->segment(3)) ?>';
	<?php } ?>
	
	$.ajax({
		url: url,
		data: $(form).serialize(),
		type: 'POST',
		dataType: 'json',
		beforeSend: function() {
			siteOverlay.top = '10px';
			siteOverlay.create();
		},
		success: function(json) {
			var alert = document.getElementsByClassName('alert');
			for (var i = 0; i < alert.length; i++) {
				alert[i].parentNode.removeChild(alert[i]);
			}
			
			var siteId = document.getElementsByName('siteId');
			for (var i = 0; i < siteId.length; i++) {
				siteId[i].value = json['site_id'];
			}
		
			if (json['error']) {
				var message = '\
					<div class="alert alert-error">';
						message += '<ul style="margin:0 10px">';
						for (var i = 0; i < json['message'].length; i++) {
							message += '<li>' + json['message'][i] + '</li>';
						}
						message += '</ul>';
					message += '</div>';
				
				$('#siteInfo').prepend(message);
				$('#siteInfo').css('height', 'auto');
			} else {
				var message = '\
					<div class="alert alert-success">' + json['message'] + '</div>';
				
				$('#siteInfo').prepend(message);
				$('#siteInfo').css('height', 'auto');
			}
		},
		complete: function() {
			siteOverlay.remove();
		}
	});
};

document.getElementById('formGeneralInfo').onsubmit = function() {
	formSubmit(this);
	window.scroll(0, 0);
	
	return false;
};

document.getElementById('formSiteKeepers').onsubmit = function() {
	currencyOnSubmit();
	
	formSubmit(this);
	window.scroll(0, 0);
	
	return false;
};

document.getElementById('formPLN').onsubmit = function() {
	numberOnSubmit();
	
	formSubmit(this);
	window.scroll(0, 0);
	
	return false;
};

document.getElementById('formTower').onsubmit = function() {
	formSubmit(this);
	window.scroll(0, 0);
	
	return false;
};

document.getElementById('formShelter').onsubmit = function() {
	formSubmit(this);
	window.scroll(0, 0);
	
	return false;
};

document.getElementById('formLahan').onsubmit = function() {
	formSubmit(this);
	window.scroll(0, 0);
	
	return false;
};

document.getElementById('formPerijinan').onsubmit = function() {
	formSubmit(this);
	window.scroll(0, 0);
	
	return false;
};

document.getElementById('formTenancy').onsubmit = function() {
	formSubmit(this);
	window.scroll(0, 0);
	
	return false;
};

document.getElementById('formAC').onsubmit = function() {
	formSubmit(this);
	window.scroll(0, 0);
	
	return false;
};

document.getElementById('formEquipment').onsubmit = function() {
	formSubmit(this);
	window.scroll(0, 0);
	
	return false;
};

document.getElementById('propinsi').onchange = function() {
	
	$.ajax({
		url: '<?php echo site_url('/site_info/add') ?>',
		type: 'GET',
		data: { cmd: 'GET_KABUPATEN', propinsi_id: this.value },
		dataType: 'json',
		success: function(json) {
			var html = '<option value="">- Choose one -</option>';
			for (var i = 0; i < json.length; i++) {
				html += '<option value="' + json[i]['id'] + '">' + json[i]['name'] + '</option>';
			}
			document.getElementById('kabupaten').innerHTML = html;
			document.getElementById('kecamatan').innerHTML = '<option value="">- Choose one -</option>';
		}
	});	
};

document.getElementById('kabupaten').onchange = function() {
	
	$.ajax({
		url: '<?php echo site_url('/site_info/add') ?>',
		type: 'GET',
		data: { cmd: 'GET_KECAMATAN', kabupaten_id: this.value },
		dataType: 'json',
		success: function(json) {
			var html = '<option value="">- Choose one -</option>';
			for (var i = 0; i < json.length; i++) {
				html += '<option value="' + json[i]['id'] + '">' + json[i]['name'] + '</option>';
			}
			document.getElementById('kecamatan').innerHTML = html;
		}
	});
};

<?php if (get_instance()->uri->segment(2) == 'edit' && isset($sellcounter)) { ?>
var collocSellCounter = <?php echo ($sellcounter + 1) ?>;
<?php } else { ?>
var collocSellCounter = 2;
<?php } ?>

var addCollocSellRow = function() {
	var labelTenantSell = 'Tenant ';
	var labelTenantDateSell = 'Date';
	
	if (collocSellCounter == 1) {
		labelTenantSell += '1st';
	} else if (collocSellCounter == 2) {
		labelTenantSell += '2nd';
	} else if (collocSellCounter == 3) {
		labelTenantSell += '3rd';
	} else {
		labelTenantSell += collocSellCounter + 'th';
	}
		
	<?php
	$tenantCollocSell = array('' => '- Choose one -');
	$dataTenantCollocSell = get_instance()->core->view_all('t_mtr_tenant', array('status' => 1), 'tenant_name');
	?>
	
	var html = '\
		<div class="control-group column-left" id="fieldTenantCollocSell-' + collocSellCounter + '">\
			<label class="control-label" for="tenantCollocSell-' + collocSellCounter + '">' + labelTenantSell + ':</label>\
			<div class="controls">\
				<select name="tenantCollocSell[]" id="tenantCollocSell-' + collocSellCounter + '" style="float:left">\
					<option value="0">- Choose one -</option>\
					<?php
					foreach ($dataTenantCollocSell as $tenant) {
						echo '<option value="'.$tenant['tenant_id'].'">'.$tenant['tenant_name'].'</option>';
					}
					?>\
				</select>\
			</div>\
		</div>\
		\
		<div class="control-group column-right">\
			<label class="control-label" for="tenantDateCollocSell-' + collocSellCounter + '">' + labelTenantDateSell + ':</label>\
			<div class="controls">\
				<div class="input-append">\
					<input type="text" name="tenantDateCollocSell[]" id="tenantDateCollocSell-' + collocSellCounter + '" class="date input-medium" autocomplete="off" readonly="readonly" placeholder="YYYY-MM-DD" />\
					<button class="btn" type="button" onclick="clearField(\'tenantDateCollocSell-' + collocSellCounter + '\')">Clear</button>\
				</div>\
			</div>\
		</div>\
		<div class="clearfix"></div>\
	';
	
	$('#addCollocSell').before(html);
	
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	
	collocSellCounter++;
};

<?php if (get_instance()->uri->segment(2) == 'edit' && isset($buycounter)) { ?>
var collocBuyCounter = <?php echo ($buycounter + 1) ?>;
<?php } else { ?>
var collocBuyCounter = 2;
<?php } ?>

var addCollocBuyRow = function() {
	var labelTenantBuy = 'Tenant ';
	var labelTenantDateBuy = 'Date';
	
	if (collocBuyCounter == 1) {
		labelTenantBuy += '1st';
	} else if (collocBuyCounter == 2) {
		labelTenantBuy += '2nd';
	} else if (collocBuyCounter == 3) {
		labelTenantBuy += '3rd';
	} else {
		labelTenantBuy += collocBuyCounter + 'th';
	}
	
	<?php
	$tenantCollocBuy = array('' => '- Choose one -');
	$dataTenantCollocBuy = get_instance()->core->view_all('t_mtr_tenant', array('status' => 1), 'tenant_name');
	?>
	
	var html = '\
		<div class="control-group column-left" id="fieldTenantCollocBuy-' + collocBuyCounter + '">\
			<label class="control-label" for="tenantCollocBuy-' + collocBuyCounter + '">' + labelTenantBuy + ':</label>\
			<div class="controls">\
				<select name="tenantCollocBuy[]" id="tenantCollocBuy-' + collocBuyCounter + '" style="float:left">\
					<option value="0">- Choose one -</option>\
					<?php
					foreach ($dataTenantCollocBuy as $tenant) {
						echo '<option value="'.$tenant['tenant_id'].'">'.$tenant['tenant_name'].'</option>';
					}
					?>\
				</select>\
			</div>\
			\
		</div>\
		\
		<div class="control-group column-right">\
			<label class="control-label" for="tenantDateCollocBuy-' + collocBuyCounter + '">' + labelTenantDateBuy + ':</label>\
			<div class="controls">\
				<div class="input-append">\
					<input type="text" name="tenantDateCollocBuy[]" id="tenantDateCollocBuy-' + collocBuyCounter + '" class="date input-medium" autocomplete="off" readonly="readonly" placeholder="YYYY-MM-DD" />\
					<button class="btn" type="button" onclick="clearField(\'tenantDateCollocBuy-' + collocBuyCounter + '\')">Clear</button>\
				</div>\
			</div>\
		</div>\
		<div class="clearfix"></div>\
	';
	
	$('#addCollocBuy').before(html);
	
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	
	collocBuyCounter++;
};

/*
var initTenantData = function() {
	var tenantData = document.getElementsByName('tenantCollocSell[]');
	for (var i = 0; i < tenantData.length; i++) {
		$(tenantData[i]).autocomplete({
			minLength: 2,
			source: function(req, resp) {
				$.ajax({
					url: '<?php echo site_url('/site_info/add') ?>',
					dataType: 'json',
					type: 'GET',
					data: { cmd: 'GET_TENANT', key: req.term },
					beforeSend: function() {},
					success: function(json) {
						resp(json.data);
					},
					complete: function() {}
				});
			},
			open: function(event, ui) {
				var  acData = $(this).data('autocomplete');
				var termTemplate = '<strong>%s</strong>';
				
				acData.menu.element.find('a').each(function() {
					var me = $(this);
					var regex = new RegExp(acData.term, "gi");
					me.html( me.text().replace(regex, function (matched) {
						return termTemplate.replace('%s', matched);
					}) );
				});
			},
			select: function(event, ui) {
				if (typeof(document.getElementsByName('tenantCollocSell[]')[i]) != 'undefined') {
				document.getElementsByName('tenantCollocSell[]')[i].value = ui.item.id;
				}
				
			},
			change: function(event, ui) {
				if (ui.item == null || ui.item.id == '') {
					$(this).val('');
				}
			}
		});console.log(i);
	}
};
initTenantData();
*/

//--></script>

<script><!--

$(function() {
	
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#cmePartnerName').autocomplete({
		minLength: 2,
      	source: function(req, resp) {
			$.ajax({
				url: '<?php echo site_url('/site_info/add') ?>',
				dataType: 'json',
				type: 'GET',
				data: { cmd: 'GET_CME_PARTNER', key: req.term },
				beforeSend: function() {
					//$('input[name="merchantBranchName"]').after('<span id="loading" class="price"><img src="<?php echo base_url('/assets/images/loading.gif') ?>" /></span>');
				},
				success: function(json) {
					resp(json.data);
				},
				complete: function() {
					//$('#loading').remove();
				}
			});
		},
		open: function(e, ui) {
            var  acData = $(this).data('autocomplete');
			var termTemplate = '<strong>%s</strong>';
            
			acData.menu.element.find('a').each(function() {
                var me = $(this);
                var regex = new RegExp(acData.term, "gi");
                me.html( me.text().replace(regex, function (matched) {
                    return termTemplate.replace('%s', matched);
                }) );
            });
        },
		select: function(event, ui) {
			document.getElementsByName('cmePartner')[0].value = ui.item.id;
		},
		change: function(event, ui) {
			if (ui.item.id == '') {
				document.getElementById('cmePartnerName').value = '';
			}
		}
	});
	
});


var clearAutocomplete = function() {
	var input = document.getElementsByTagName('input');
	for (var i = 0; i < input.length; i++) {
		if (input[i].type == 'text') {
			input[i].setAttribute('autocomplete', 'off');
		}
	}
};
clearAutocomplete();

//--></script>

<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script><!--
var currency = document.getElementsByClassName('currency');
var number = document.getElementsByClassName('number');
var latLngNumber = document.getElementsByClassName('latlng-number');
var justNumber = document.getElementsByClassName('just-number');

var currencyOnSubmit = function() {
	for (var i = 0; i < currency.length; i++) {
		var prec = currency[i].value.split('.');
		if (prec[1] == '00') {
			currency[i].value = number_format(currency[i].value, '', '', '');
		} else {
			currency[i].value = number_format(currency[i].value, 2, '.', '');
		}
	}
};

var numberOnSubmit = function() {
	for (var i = 0; i < number.length; i++) {
		var prec = number[i].value.split('.');
		if (prec[1] == '00') {
			number[i].value = number_format(number[i].value, '', '', '');
		} else {
			number[i].value = number_format(number[i].value, 2, '.', '');
		}
	}
};

for (var i = 0; i < currency.length; i++) {
	// load default
	currency[i].value = number_format(currency[i].value, 2);
	
	// onfocus
	currency[i].onfocus = function() {
		var prec = this.value.split('.');
		if (prec[1] == '00') {
			this.value = number_format(this.value, '', '', '');
		} else {
			this.value = number_format(this.value, 2, '.', '');
		}
	};
	
	// onblur
	currency[i].onblur = function(e) {
		this.value = number_format(this.value, 2);
	};
	
	// onkeyup
	currency[i].onkeyup = function(e) {
		if (!isNumeric(this.value)) {
			alert('Please enter only numbers or decimal points.'); 
			setTimeout(function() { e.target.focus() });
			return false; 
		}
	};
}

for (var i = 0; i < number.length; i++) {
	// load default
	number[i].value = number_format(number[i].value, 2, '.', '');
	
	// onfocus
	number[i].onfocus = function() {
		var prec = this.value.split('.');
		if (prec[1] == '00') {
			this.value = number_format(this.value, '', '', '');
		} else {
			this.value = number_format(this.value, 2, '.', '');
		}
	};
	
	// onblur
	number[i].onblur = function() {
		this.value = number_format(this.value, 2, '.', '');
	};
	
	// onkeyup
	number[i].onkeyup = function(e) {
		if (!isNumeric(this.value)) {
			alert('Please enter only numbers or decimal points.'); 
			setTimeout(function() { e.target.focus() });
			return false; 
		}
	};
}

for (var i = 0; i < latLngNumber.length; i++) {
	latLngNumber[i].onkeyup = function(e) {
		if (!isNumeric(this.value)) {
			alert('Please enter only numbers or decimal points.'); 
			setTimeout(function() { e.target.focus() });
			return false; 
		}
	};
	
	latLngNumber[i].onblur = function(e) {
		if (this.value.indexOf('.') != -1) {
			var prec = this.value.split('.');
			if (prec[1].length >= 6) {
				this.value = number_format(this.value, 6, '.', '');
			} else {
				this.value = number_format(this.value, prec[1].length, '.', '');
			}
		}
	};
}

for (var i = 0; i < justNumber.length; i++) {
	justNumber[i].onkeyup = function(e) {
		if (!isNumeric(this.value)) {
			alert('Please enter only numbers.');
			this.value = this.value.replace(/[^\d.]/g, "");
			setTimeout(function() { e.target.focus() });
			return false; 
		}
	};
}

var isNumeric = function(sText) {
	var validChars = "0123456789.-";
	var isNumber = true;
	var character;
 
	for (var i = 0; i < sText.length && isNumber == true; i++) { 
		character = sText.charAt(i);
		if (validChars.indexOf(character) == -1) {
			isNumber = false;
		}
	}
	
	return isNumber;
};

var clearField = function(field) {
	document.getElementById(field).value = '';
};

var uppercaseElement = document.getElementsByClassName('uppercase');
for (var i = 0; i < uppercaseElement.length; i++) {
	uppercaseElement[i].onkeyup = function() {
		this.value = this.value.toUpperCase();
	};
};

var siteOverlay = {
	
	/**
	 * for window overlay
	 * Author: ttg <tatangalmubarak@gmail.com>
	 * Desc: hehehe...
	 */
	
	message: null,	// text message
	left: null,		// pixel
	top: null,		// pixel
	create: function() {
		var overlay = document.createElement('div');
		
		overlay.setAttribute('id', 'site-overlay');
		overlay.style.position = 'fixed';
		overlay.style.top = '0px';
		overlay.style.bottom = '0px';
		overlay.style.zIndex = 99999;
		overlay.style.width = '100%';
		overlay.style.height = '100%';
		overlay.style.background = 'rgba(0, 0, 0, 0.6)';
		document.body.appendChild(overlay);
		
		var h1 = document.createElement('h1');
		if (this.message == null) {
			this.message = document.createTextNode('Loading, please wait...');
		}
		
		h1.setAttribute('id', 'overlay-message');
		h1.style.width = '300px';
		h1.appendChild(this.message);
		overlay.appendChild(h1);
		
		var windowWidth = document.body.clientWidth;
		var windowHeight = document.body.clientHeight;
		var h1Width = document.getElementById('overlay-message').offsetWidth;
		var h1Height = document.getElementById('overlay-message').offsetHeight;
		if (this.left == null) {
			this.left = (windowWidth - h1Width) / 2 + 'px';
		}
		if (this.top == null) {
			this.top = (windowHeight - h1Height) / 2 + 'px';
		}
		
		var overlayMessage = document.getElementById('overlay-message');
		overlayMessage.style.position = 'absolute';
		overlayMessage.style.zIndex = 999999;
		overlayMessage.style.top = this.top;
		overlayMessage.style.left = this.left;
		overlayMessage.style.width = '300px';
		overlayMessage.style.border = '2px solid #468847';
		overlayMessage.style.background = '#FCF8E3';
		overlayMessage.style.textAlign = 'center';
		overlayMessage.style.padding = '10px';
		overlayMessage.style.color = '#C09853';
		overlayMessage.style.fontSize = '16px';
	},
	remove: function() {
		var siteOverlay = document.getElementById('site-overlay');
		
		if (siteOverlay != null) {
			siteOverlay.parentNode.removeChild(siteOverlay);
		}
	}
};
//--></script>