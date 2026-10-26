<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<style>
/* .widget .widget-body { padding:20px 15px; } */
</style>

<!--
<div class="heading-buttons">
	<h3>Tower Provider</h3>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/tower_provider') ?>" class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back</a>
	</div>
</div>
<div class="separator top"></div>
-->

<div class="innerLR">

	<div class="form-horizontal">
	
		<div class="widget">
			<div class="widget-head heading-outer">
				<h4 class="heading"><i></i> Tower Provider Data</h4>
				
				<a href="<?php echo site_url('/site_info/tower_provider') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
			</div>
			
			<div class="widget-body">
			
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group" id="form_tp_name">
							<label class="control-label" for="tpName">TP Name:</label>
							<div class="controls">
								<span class="input-label"><?php echo $tp['tp_company_name'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span6">
						<div class="control-group" id="form_tp_site_name">
							<label class="control-label" for="tpSiteName">TP Site Name:</label>
							<div class="controls">
								<span class="input-label"><?php echo $tp['tp_site_name'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group" id="form_tp_code">
							<label class="control-label" for="tpCode">TP Code:</label>
							<div class="controls">
								<span class="input-label"><?php echo $tp['tp_code'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span6">
						<div class="control-group" id="form_latitude">
							<label class="control-label" for="latitude">Lat - Long:</label>
							<div class="controls">
								<span class="input-label"><?php echo $tp['latitude'].' | '.$tp['longitude'] ?></span>
								
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group" id="form_status_tp">
							<label class="control-label" for="statusTP">Status TP:</label>
							<div class="controls">
								<?php
								$lookup = $this->core->get_lookup('tower_status');
								$options = array(0 => '');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span class="input-label"><?php echo $options[$tp['status_tp_id']] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span6">
						<div class="control-group" id="form_site_type">
							<label class="control-label" for="siteType">Site Type:</label>
							<div class="controls">
								<?php
								$lookup = $this->core->get_lookup('site_type');
								$options = array(0 => '');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span class="input-label"><?php echo $options[$tp['site_type_id']] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="control-group" id="form_tower_type">
					<label class="control-label" for="towerType">Tower Type:</label>
					<div class="controls">
						<?php
						$lookup = $this->core->get_lookup('tower_type');
						$options = array(0 => '');
						foreach ($lookup as $lo) {
							$options[$lo['id']] = $lo['desc'];
						}
						?>
						<span class="input-label"><?php echo $options[$tp['tower_type_id']] ?></span>
					</div>
				</div>
				
				<div class="control-group" id="form_building_height">
					<label class="control-label" for="buildingHeight">Building Height:</label>
					<div class="controls">
						<span class="input-label"><?php echo number_format((float) $tp['building_height_mtr'], 2, '.', '') ?> M</span>
					</div>
				</div>
				
				<div class="control-group" id="form_tower_heigth">
					<label class="control-label" for="towerHeight">Tower Height:</label>
					<div class="controls">
						<span class="input-label"><?php echo number_format((float) $tp['tower_height_mtr'], 2, '.', '') ?> M</span>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group" id="form_propinsi">
							<label class="control-label" for="propinsi">Province:</label>
							<div class="controls">
								<?php
								$propinsi = array(0 => '');
								$dataPropinsi = $this->core->view_all('t_mtr_lokasi', array('jenis' => 'P'), 'nama');
								foreach ($dataPropinsi as $prop) {
									$propinsi[$prop['lokasi_id']] = $prop['nama'];
								}
								?>
								<span class="input-label"><?php echo $propinsi[$tp['propinsi_id']] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span6">
						<div class="control-group" id="form_city">
							<label class="control-label" for="city">City:</label>
							<div class="controls">
								<?php
								$kabupaten = array(0 => '');
								$dataKabupaten = @$this->core->view_all('t_mtr_lokasi', array('jenis' => 'KB', 'parent_id' => $tp['propinsi_id']), 'nama');
								foreach ($dataKabupaten as $kab) {
									$kabupaten[$kab['lokasi_id']] = $kab['nama'];
								}
								?>
								<span class="input-label"><?php echo $kabupaten[$tp['city_id']] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group" id="form_address">
							<label class="control-label" for="address">Address:</label>
							<div class="controls">
								<span class="input-label"><?php echo $tp['address'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span6"></div>
				</div>
				
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group" id="form_region">
							<label class="control-label" for="region">Region:</label>
							<div class="controls">
								<?php
								$regions = array(0 => '');
								$dataRegions = $this->core->view_all('t_mtr_region', array('region_id !=' => 0), 'region_name');
								foreach ($dataRegions as $region) {
									$regions[$region['region_id']] = $region['region_name'];
								}
								?>
								<span class="input-label"><?php echo $regions[$tp['region_id']] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span6">
						<div class="control-group" id="form_sharable_status">
							<label class="control-label" for="shareableStatus">Shareable Status:</label>
							<div class="controls">
								<span class="input-label"><?php echo $tp['shareable_status'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group" id="form_site_code">
							<label class="control-label" for="siteCode">Site Code:</label>
							<div class="controls">
								<span class="input-label"><?php echo $tp['site_code'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
					
					<div class="span6">
						<div class="control-group" id="form_cme_partner">
							<label class="control-label" for="cmePartner">Partner:</label>
							<div class="controls">                            
								<?php
								$dataCmePartner = get_instance()->core->view_all('t_mtr_partner', array('status' => 1), 'partner_name');
								$cmePartner = array(0 => '');
								foreach ($dataCmePartner as $partner) {
									$cmePartner[$partner['partner_id']] = $partner['partner_name'];
								}
								?>
								<span class="input-label"><?php echo $cmePartner[$tp['partner_id']] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group" id="form_remarks">
							<label class="control-label" for="remarks">Remarks:</label>
							<div class="controls">
								<span class="input-label"><?php echo $tp['remark'] ?></span>
							</div>
						</div>
						<div class="separator bottom"></div>
					</div>
					
					<div class="span6"></div>
				</div>
			
			</div>
		</div>
	</div>

</div>

<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script>
var showMap = function() {
	var x = parseInt((window.screen.availWidth - 850) / 2);
    var y = parseInt(((window.screen.availHeight - 560) / 2) - 50);
	
	var w = window.open('<?php echo site_url('/site_info') ?>?cmd=GET_MAP&draggable=false&latlngElement=latitude,longitude', 'map', 'height=560,width=850,left=' + x + ',top=' + y + ',scrollbars=no');
	w.focus();
	
	return false;
};

mainMenu();
</script>