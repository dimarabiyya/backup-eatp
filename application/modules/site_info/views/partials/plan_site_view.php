<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<div class="form-horizontal">

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading"><i></i> General</h4>
		</div>
		
		<div class="widget-body">
		
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_plan_site_id">
						<label class="control-label" for="siteCode">Site ID:</label>
						<div class="controls">
							<span class="input-label"><?php echo $site['nom_site_code'] ?></span>						
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_plan_site_name">
						<label class="control-label" for="siteName">Site Name:</label>
						<div class="controls">
							<span class="input-label"><?php echo $site['nom_site_name'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_plan_latlong">
						<label class="control-label" for="nominalLatitude">Lat - Long:</label>
						<div class="controls">
							<span class="input-label"><?php echo $site['nom_latitude'].' | '.$site['nom_longitude'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_plan_search_radius">
						<label class="control-label" for="searchRadius">Search Radius:</label>
						<div class="controls">
							<span class="input-label"><?php echo number_format($site['search_radius'], 2) ?> M</span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="control-group" id="form_plan_bts_type">
				<label class="control-label" for="btsType">BTS Type:</label>
				<div class="controls">
					<?php
					$lookup = $this->core->get_lookup('bts_type');
					$options = array(0 => '');
					foreach ($lookup as $lo) {
						$options[$lo['id']] = $lo['desc'];
					}
					?>
					<span class="input-label"><?php echo $options[$site['bts_type']] ?></span>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_plan_antenna_height">
						<label class="control-label" for="RFHeight">RF Antenna Height:</label>
						<div class="controls">
							<span class="input-label"><?php echo number_format($site['bts_ant_height'], 2) ?> M</span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_plan_antenna_dimension">
						<label class="control-label" for="RFDimensionL">RF Antenna Dimension (LxWxH):</label>
						<div class="controls">
							<span class="input-label"><?php echo number_format($site['bts_dimension_l'], 2).' x '.number_format($site['bts_dimension_w'], 2).' x '.number_format($site['bts_dimension_h'], 2) ?> M</span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="control-group" id="form_plan_power_requirement">
				<label class="control-label" for="powerRequirement">Power (PLN) Requirement:</label>
				<div class="controls">
					<span class="input-label"><?php echo number_format($site['bts_dimension_l'], 2) ?> kVA</span>
				</div>
			</div>
		
		</div>
	</div>

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading"><i></i> Address</h4>
		</div>
		
		<div class="widget-body">
		
			<div class="control-group" id="form_plan_address">
				<label class="control-label" for="address">Address:</label>
				<div class="controls">
					<span class="input-label"><?php echo $site['nom_address'] ?></span>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_plan_province">
						<label class="control-label" for="propinsi">Province:</label>
						<div class="controls">
							<?php
							$propinsi = array(0 => '');
							$dataPropinsi = $this->core->view_all('t_mtr_lokasi', array('jenis' => 'P'), 'nama');
							foreach ($dataPropinsi as $prop) {
								$propinsi[$prop['lokasi_id']] = $prop['nama'];
							}
							?>
							<span class="input-label"><?php echo $propinsi[$site['propinsi_id']] ?></span>
						</div>							
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_plan_kabupaten">
						<label class="control-label" for="kabupaten">Kabupaten:</label>
						<div class="controls">
							<?php
							$kabupaten = array(0 => '');
							$dataKabupaten = @$this->core->view_all('t_mtr_lokasi', array('jenis' => 'KB', 'parent_id' => $site['propinsi_id']), 'nama');
							foreach ($dataKabupaten as $kab) {
								$kabupaten[$kab['lokasi_id']] = $kab['nama'];
							}
							?>
							<span class="input-label"><?php echo $kabupaten[$site['kabupaten']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_plan_kecamatan">
						<label class="control-label" for="kecamatan">Kecamatan:</label>
						<div class="controls">
							<?php
							$kecamatan = array(0 => '');
							$dataKecamatan = @get_instance()->core->view_all('t_mtr_lokasi', array('jenis' => 'KC', 'parent_id' => $site['kabupaten']), 'nama');
							foreach ($dataKecamatan as $kec) {
								$kecamatan[$kec['lokasi_id']] = $kec['nama'];
							}
							?>
							<span class="input-label"><?php echo $kecamatan[$site['kecamatan']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_plan_kelurahan">
						<label class="control-label" for="kelurahan">Kelurahan:</label>
						<div class="controls">
							<span class="input-label"><?php echo $site['kelurahan'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="control-group" id="form_plan_kode_pos">
				<label class="control-label" for="kodePos">Kode Pos:</label>
				<div class="controls">
					<span class="input-label"><?php echo $site['kode_pos'] ?></span>
				</div>
			</div>
		
		</div>
	</div>

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">Territory</h4>
		</div>
		
		<div class="widget-body">
			<div class="control-group" id="form_plan_region">
				<label class="control-label" for="region">Region:</label>
				<div class="controls">
					<?php
					$my_region = array();
					foreach ($this->session->userdata['user_region'] as $reg) {
						if ($reg['region_id'] != '') {
							$my_region[] = $reg['region_id'];
						}
					}
					
					$regions = array(0 => '');
					$dataRegions = $this->db
						->query("SELECT region_id, region_name FROM t_mtr_region WHERE status = 1 AND region_id IN (".implode(', ', $my_region).") ORDER BY region_name ASC")
						->result_array();
					foreach ($dataRegions as $region) {
						$regions[$region['region_id']] = $region['region_name'];
					}
					?>
					<span class="input-label"><?php echo $regions[$site['region_id']] ?></span>
				</div>
			</div>

			<div class="control-group" id="form_plan_cluster_ops">
				<label class="control-label" for="clusterOpr">Operational Cluster:</label>
				<div class="controls">
					<?php
					$region_id = @$this->core->view_row('t_mtr_cluster_ops', array('cluster_ops_id' => $site['cluster_ops_id']));
					
					$clusters = array(0 => '');
					$dataCluster = @$this->core->view_all('t_mtr_cluster_ops', array('region_id' => $region_id['region_id'], 'cluster_ops_id !=' => 0), 'cluster_name');
					foreach ($dataCluster as $cluster) {
						$clusters[$cluster['cluster_ops_id']] = $cluster['cluster_name'];
					}
					?>
					<span class="input-label"><?php echo $clusters[$site['cluster_ops_id']] ?></span>
				</div>
			</div>

			<div class="control-group" id="form_plan_cluster_net">
				<label class="control-label" for="cluster">Network Cluster:</label>
				<div class="controls">
					<?php
					$region_id = @$this->core->view_row('t_mtr_cluster_net', array('cluster_net_id' => $site['cluster_net_id']));
					
					$clusters = array(0 => '');
					$dataCluster = @$this->core->view_all('t_mtr_cluster_net', array('region_id' => $region_id['region_id'], 'cluster_net_id !=' => 0), 'cluster_name');
					foreach ($dataCluster as $cluster) {
						$clusters[$cluster['cluster_net_id']] = $cluster['cluster_name'];
					}
					?>
					<span class="input-label"><?php echo $clusters[$site['cluster_net_id']] ?></span>
				</div>
			</div>

			<div class="control-group" id="form_plan_cluster_com">
				<label class="control-label" for="cluster">Commercial Cluster:</label>
				<div class="controls">
					<?php
					$region_id = @$this->core->view_row('t_mtr_cluster_com', array('cluster_com_id' => $site['cluster_com_id']));
					
					$clusters = array(0 => '');
					$dataCluster = @$this->core->view_all('t_mtr_cluster_com', array('region_id' => $region_id['region_id'], 'cluster_com_id !=' => 0), 'cluster_name');
					foreach ($dataCluster as $cluster) {
						$clusters[$cluster['cluster_com_id']] = $cluster['cluster_name'];
					}
					?>
					<span class="input-label"><?php echo $clusters[$site['cluster_com_id']] ?></span>
				</div>
			</div>
		</div>
	</div>

</div>

<script>
doPreviledgePerfield();
</script>