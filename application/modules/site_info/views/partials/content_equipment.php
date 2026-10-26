<?php
error_reporting(E_ALL ^ E_NOTICE);

$site_id = (int) $this->core->decode($this->uri->segment(3));

$this->load->model('site_info/equipment_model');
?>

<div class="easyui-tabs">
	<!-- start: Tab Equipment -->
	<div title="Equipment">
		
		<div id="tabs-group-equipment" class="easyui-tabs">
			<div title="BTS Info" class="form-horizontal">
			
				<?php
				$bts_infos = $this->db
					->query("
						SELECT
							f_get_lookup_name('bts_morphology', morphology) AS morphology,
							f_get_lookup_name('bts_brand', bts_brand) AS bts_brand,
							f_get_lookup_name('bts_mode', bts_mode) AS bts_mode,
							f_get_lookup_name('bts_type', bts_type) AS bts_type
						FROM t_mtr_site_l_equipment_btsinfo WHERE site_id = {$site_id} AND active = 1
						ORDER BY bts_id ASC
					")
					->result_array();
				
				$row = $this->db->query("SELECT vendor_id FROM t_mtr_site WHERE site_id = {$site_id}")->row_array();
				$vendor_ids = explode(';', $row['vendor_id']);
				
				$rows = array();
				foreach ($vendor_ids as $key => $val) {
					$bts_infos[$key]['vendor_id'] = trim($val);
					
					$rows[] = $bts_infos[$key];
				}
				
				$bts_info = $rows;
				?>
				
				<!-- start: BTS Info -->
				<div class="widget">
					<div class="widget-head">
						<h4 class="heading"><i></i> BTS Info</h4>
					</div>
					
					<div class="widget-body">

						<div class="control-group">
							<label class="control-label" style="text-align:left">Morphology:</label>
							<div class="controls">
								<span class="uneditable-input"><?php echo $bts_info[0]['morphology'] ?></span>
							</div>
							<div class="separator bottom"></div>
						</div>
						
						<table class="table table-bordered table-condensed">
							<thead>
								<tr>
									<th style="text-align:center">Vendor ID</th>
									<th style="text-align:center">BTS Brand</th>
									<th style="text-align:center">BTS Mode</th>
									<th style="text-align:center">BTS Type</th>
								</tr>
							</thead>
							<tbody>
								<?php if (count($bts_info)) {
									
									foreach ($bts_info as $bts) {
								?>
								<tr>
									<td><?php echo $bts['vendor_id'] ?></td>
									<td><?php echo $bts['bts_brand'] ?></td>
									<td><?php echo $bts['bts_mode'] ?></td>
									<td><?php echo $bts['bts_type'] ?></td>
								</tr>
								<?php 
									}
								} ?>
							</tbody>
						</table>
						
					</div>
				</div>
				<!-- end: BTS Info -->
			
			</div>
			
			<div title="RF Antenna">
			
				<!-- start: Grid RF Antenna -->
				<div class="widget">

					<div class="widget-head">
						<h4 class="heading"><i></i> RF Antenna</h4>
					</div>
					
					<div class="widget-body">
						<table id="tblCarrier" class="table table-bordered table-condensed">
							<thead>
								<tr>
									<th>System</th>
									<th>Configuration</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$carrier = $this->equipment_model->get_equipment_carrier($site_id);
								
								foreach ($carrier as $carr) {?>
								<tr>
									<td>
										<?php
										$system = array(0 => '');
										$data_system = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'carrier_system', 'active' => 1), 'order');
										foreach ($data_system as $sys) {
											$system[$sys['lookup_value']] = $sys['lookup_desc'];
										}
										?>
										<span><?php echo $system[$carr['system']] ?></span>
									</td>
									<td>
										<?php
										$config = array(0 => '');
										$data_config = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'carrier_configuration', 'active' => 1), 'order');
										foreach ($data_config as $conf) {
											$config[$conf['lookup_value']] = $conf['lookup_desc'];
										}
										?>
										<span><?php echo $config[$carr['configuration']] ?></span>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						
						<div class="separator"></div>
						
						<div style="overflow:auto">
							<table class="table table-bordered table-condensed">
								<thead>
									<tr>
										<th class="center">Type</th>
										<th class="center">Model</th>
										<th class="center">Brand</th>
										<th class="center">Cell</th>
										<th class="center">Band</th>
										<th class="center">HBWD</th>
										<th class="center">VBWD</th>
										<th class="center">Dimension (LxWxH)</th>
										<th class="center">Weight</th>
										<th class="center">Height</th>
										<th class="center">Azimuth</th>
										<th class="center">Electrical Tilt</th>
										<th class="center">Mechanical Tilt</th>						
									</tr>
									<?php
									$rfs = $this->equipment_model->get_equipment_rf($site_id);
									foreach ($rfs as $rf) {
									?>
									<tr>
										<td nowrap>
											<?php
											$type = array(0 => '');
											$dataType = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_rf_type', 'active' => 1), 'order');
											foreach ($dataType as $t) {
												$type[$t['lookup_value']] = $t['lookup_desc'];
											}
											?>
											<span><?php echo $type[$rf['type']] ?></span>
										</td>
										<td nowrap><span><?php echo $rf['model'] ?></span></td>
										<td nowrap><span><?php echo $rf['brand'] ?></span></td>
										<td nowrap>
											<?php
											$cells = array(0 => '');
											$dataCells = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_cell', 'active' => 1), 'order');
											foreach ($dataCells as $cell) {
												$cells[$cell['lookup_value']] = $cell['lookup_desc'];
											}
											?>
											<span><?php echo $cells[$rf['cell']] ?></span>
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
										<td><span><?php echo $rf['hbwd'] ?></span></td>
										<td><span><?php echo $rf['vbwd'] ?></span></td>
										<td nowrap>
											<span><?php echo $rf['dimension_long'].' x '.$rf['dimension_width'].' x '.$rf['dimension_height'] ?> mm</span>
										</td>
										<td class="right">
											<span><?php echo $rf['weight'] ?> kg</span>
										</td>
										<td class="right"><span><?php echo $rf['height'] ?> M</span></td>
										<td class="right"><span><?php echo $rf['azimuth'] ?></span></td>
										<td class="right"><span><?php echo $rf['electrical_tilt'] ?></span></td>
										<td class="right"><span><?php echo $rf['mechanical_tilt'] ?></span></td>							
									</tr>
									<?php
									}
									?>
								</thead>
							</table>
						</div>
					</div>
				</div>
				<!-- end: Grid RF Antenna -->
			
			</div>
			
			<div title="Transmission">
			
				<?php
				$transmission = array();
				
				$results1 = $this->db
					->query("
						SELECT mw.equipment_mw_id, mw.site_id_ne, mw.site_id_fe, mw.pair_id, mw.link_code_id, site.site_code, mw.ip_address, mw.ant_model, mw.ketinggian, mw.ant_diameter, mw.ant_diameter,
							mw.azimuth, mw.equipment_model, mw.polarization, mw.path_length, mw.tx_frequency, mw.rx_frequency, mw.power, mw.sd_ant_model, mw.sd_ant_height, mw.frequency_used, lo1.lookup_desc AS config,
							mw.sd_ant_diameter, mw.req_capacity, mw.inst_capacity, mw.used_capacity, mw.treshold, mw.plan_rsl, mw.actual_rsl, mw.fm, mw.availability
						FROM t_mtr_site_l_equipment_mw mw
						LEFT JOIN t_mtr_site site ON site.site_id = mw.site_id_ne
						LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = mw.config::VARCHAR AND lo1.lookup_key = 'transmission_config_hop' AND lo1.active = 1
						WHERE mw.site_id_ne = {$site_id} AND mw.active = 1
						ORDER BY mw.pair_id ASC
					")
					->result_array();
				
				$a = array();
				foreach ($results1 as $res1) {
					$res2 = $this->db
						->query("
							SELECT mw.equipment_mw_id, mw.site_id_ne, mw.site_id_fe, mw.pair_id, mw.link_code_id, site.site_code, mw.ip_address, mw.ant_model, mw.ketinggian, mw.ant_diameter, mw.ant_diameter,
								mw.azimuth, mw.equipment_model, mw.polarization, mw.path_length, mw.tx_frequency, mw.rx_frequency, mw.power, mw.sd_ant_model, mw.sd_ant_height, mw.frequency_used, lo1.lookup_desc AS config,
								mw.sd_ant_diameter, mw.req_capacity, mw.inst_capacity, mw.used_capacity, mw.treshold, mw.plan_rsl, mw.actual_rsl, mw.fm, mw.availability
							FROM t_mtr_site_l_equipment_mw mw
							LEFT JOIN t_mtr_site site ON site.site_id = mw.site_id_ne
							LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = mw.config::VARCHAR AND lo1.lookup_key = 'transmission_config_hop' AND lo1.active = 1
							WHERE mw.site_id_ne = ".$res1['site_id_fe']." AND mw.pair_id = ".$res1['pair_id']." AND mw.active = 1
							ORDER BY mw.pair_id ASC
						")
						->row_array();
					
					$a[] = array('ne' => $res1, 'fe' => $res2);
				}
				
				$transmission = $a;
				$site_class_transm = $this->db->query("SELECT site_class_transm FROM t_mtr_site WHERE site_id = {$site_id}")->row_array();
				?>
				<!-- start: Grid Transmission -->
				<div class="widget">

					<div class="widget-head">
						<h4 class="heading"><i></i> Transmission</h4>
					</div>
					
					<div class="widget-body">
					
						<div class="form-horizontal">
							<div class="control-group" id="form_class_site_transmission">
								<label class="control-label" for="oldSiteId" style="width:180px; text-align:left;">Site Class Transmission:</label>
								<div class="controls" style="margin-left:180px">
									<?php
									$lookup = $this->core->get_lookup('site_class_transm');
									$options = array(0 => '');
									foreach ($lookup as $lo) {
										$options[$lo['id']] = $lo['desc'];
									}
									?>
									<span class="uneditable-input"><?php echo $options[(int) $site_class_transm['site_class_transm']] ?></span>
								</div>
							</div>
						</div>
						
						<div class="separator bottom"></div>
						
						<div id="tbl-transmission" style="overflow:auto">
							<table class="table table-bordered table-condensed" style="width:auto">
								<thead>
									<tr>
										<th>No.</th>
										<th>Site ID</th>
										<th>Path Length (km)</th>
										<th>Link ID</th>
										<th>TX Freq.</th>
										<th>RX Freq.</th>
										<th>Freq. Used</th>
										<th>Config</th>
										<th>Power</th>
										<th>IP Address</th>
										<th>Main Ant. Model</th>
										<th>Main Ant. Height</th>
										<th>Main Ant. Diameter</th>
										<th>SD Ant. Model</th>
										<th>SD Ant. Height</th>
										<th>SD Ant. Diameter</th>
										<th>Azimuth</th>
										<th>Eq. Model</th>
										<th>Polarization</th>
										<th>Req. Capacity (MB/E1)</th>
										<th>Inst. Capacity (MB/E1)</th>
										<th>Used. Capacity (MB/E1)</th>
										<th>Treshold</th>
										<th>Plan RSL (dBm)</th>
										<th>Actual RSL (dBm)</th>
										<th>FM</th>
										<th>Availability (%)</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (count($transmission) > 0) {
									
										$transm_counter = 1;
										foreach ($transmission as $transm) {
									?>
									<tr>
										<td rowspan="2" style="text-align:center">
											<span><?php echo $transm_counter ?></span>
										</td>
										<td>
											<span><?php echo $transm['ne']['site_code'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['ne']['path_length'], 2) ?></span>
										</td>
										<td>
											<span><?php echo $transm['ne']['link_code_id'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo $transm['ne']['tx_frequency'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo $transm['ne']['rx_frequency'] ?></span>
										</td>
										<td>
											<span><?php echo $transm['ne']['frequency_used'] ?></span>
										</td>
										<td>
											<span><?php echo $transm['ne']['config'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['ne']['power'], 2) ?></span>
										</td>
										<td>
											<span><?php echo $transm['ne']['ip_address'] ?></span>
										</td>
										<td>
											<span><?php echo $transm['ne']['ant_model'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['ne']['ketinggian'], 2) ?></span>
										</td>
										<td>
											<?php
											$options = array(0 => '');
											$lookup = get_instance()->core->get_lookup('antenna_diameter');
											foreach ($lookup as $lo) {
												$options[$lo['id']] = $lo['desc'];
											}
											?>
											<span><?php echo $options[(int) $transm['ne']['ant_diameter']] ?></span>
										</td>
										<td>
											<span><?php echo $transm['ne']['sd_ant_model'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['ne']['sd_ant_height'], 2) ?></span>
										</td>
										<td>
											<?php
											$options = array(0 => '');
											$lookup = get_instance()->core->get_lookup('antenna_diameter');
											foreach ($lookup as $lo) {
												$options[$lo['id']] = $lo['desc'];
											}
											?>
											<span><?php echo $options[(int) $transm['ne']['sd_ant_diameter']] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['ne']['azimuth'], 2) ?></span>
										</td>
										<td>
											<?php
											$options = array(0 => '');
											$lookup = $this->core->get_lookup('equipment_model');
											foreach ($lookup as $lo) {
												$options[$lo['id']] = $lo['desc'];
											}
											?>
											<span><?php echo $options[$transm['ne']['equipment_model']] ?></span>
										</td>
										<td>
											<?php
											$options = array(0 => '');
											$lookup = $this->core->get_lookup('transmission_polarization');
											foreach ($lookup as $lo) {
												$options[$lo['id']] = $lo['desc'];
											}
											?>
											<span><?php echo $options[$transm['ne']['polarization']] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo $transm['ne']['req_capacity'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo $transm['ne']['inst_capacity'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo $transm['ne']['used_capacity'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['ne']['treshold'], 2) ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['ne']['plan_rsl'], 2) ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['ne']['actual_rsl'], 2) ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['ne']['fm'], 2) ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['ne']['availability'], 5) ?></span>
										</td>
									</tr>
									<tr>
										<td>
											<span><?php echo $transm['fe']['site_code'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['fe']['path_length'], 2) ?></span>
										</td>
										<td>
											<span><?php echo $transm['fe']['link_code_id'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo $transm['fe']['tx_frequency'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo $transm['fe']['rx_frequency'] ?></span>
										</td>
										<td>
											<span><?php echo $transm['fe']['frequency_used'] ?></span>
										</td>
										<td>
											<span><?php echo $transm['fe']['config'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['fe']['power'], 2) ?></span>
										</td>
										<td>
											<span><?php echo $transm['fe']['ip_address'] ?></span>
										</td>
										<td>
											<span><?php echo $transm['fe']['ant_model'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['fe']['ketinggian'], 2) ?></span>
										</td>
										<td>
											<?php
											$options = array(0 => '');
											$lookup = get_instance()->core->get_lookup('antenna_diameter');
											foreach ($lookup as $lo) {
												$options[$lo['id']] = $lo['desc'];
											}
											?>
											<span><?php echo $options[(int) $transm['fe']['ant_diameter']] ?></span>
										</td>
										<td>
											<span><?php echo $transm['fe']['sd_ant_model'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['fe']['sd_ant_height'], 2) ?></span>
										</td>
										<td>
											<?php
											$options = array(0 => '');
											$lookup = get_instance()->core->get_lookup('antenna_diameter');
											foreach ($lookup as $lo) {
												$options[$lo['id']] = $lo['desc'];
											}
											?>
											<span><?php echo $options[(int) $transm['fe']['sd_ant_diameter']] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['fe']['azimuth'], 2) ?></span>
										</td>
										<td>
											<?php
											$options = array(0 => '');
											$lookup = $this->core->get_lookup('equipment_model');
											foreach ($lookup as $lo) {
												$options[$lo['id']] = $lo['desc'];
											}
											?>
											<span><?php echo $options[$transm['fe']['equipment_model']] ?></span>
										</td>
										<td>
											<?php
											$options = array(0 => '');
											$lookup = $this->core->get_lookup('transmission_polarization');
											foreach ($lookup as $lo) {
												$options[$lo['id']] = $lo['desc'];
											}
											?>
											<span><?php echo $options[$transm['fe']['polarization']] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo $transm['fe']['req_capacity'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo $transm['fe']['inst_capacity'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo $transm['fe']['used_capacity'] ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['fe']['treshold'], 2) ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['fe']['plan_rsl'], 2) ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['fe']['actual_rsl'], 2) ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['fe']['fm'], 2) ?></span>
										</td>
										<td>
											<span class="pull-right"><?php echo number_format($transm['fe']['availability'], 5) ?></span>
										</td>
									</tr>
									<tr>
										<td colspan="27"><i></i></td>
									</tr>
									<?php
											$transm_counter++;
										}
									}
									?>
								</tbody>
							</table>
						</div>
					
					</div>
				</div>
				<!-- end: Grid Transmission -->
			
			</div>
			
			<div title="GPS Antenna">
			
				<?php
				$gps = $this->equipment_model->get_equipment_gps($site_id);
				?>
				<!-- start: Grid GPS Antenna -->
				<div class="widget">

					<div class="widget-head">
						<h4 class="heading"><i></i> GPS Antenna</h4>
					</div>
					
					<div class="widget-body">
					
						<div style="overflow:auto">
							<table class="table table-bordered table-condensed">
								<thead>
									<tr>
										<th>Model</th>
										<th>Height</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (count($gps) > 0) {
										
										$gpsCounter = 1;
										foreach ($gps as $ji_pi_es) {
									?>
									<tr id="gps-row-<?php echo $gpsCounter ?>">
										<td>
											<?php
											$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_gps_model', 'active' => 1), 'order');
											$gpsModel = array(0 => '');
											foreach ($lookup as $lo) {
												$gpsModel[$lo['lookup_value']] = $lo['lookup_desc'];
											}
											?>
											<span><?php echo $gpsModel[$ji_pi_es['ant_gps_model']] ?></span>
										</td>
										<td>
											<span><?php echo number_format($ji_pi_es['ant_gps_height'], 2, '.', '') ?> M</span>
										</td>
									</tr>
									
									<?php
											$gpsCounter++;
										}
									}
									?>
								</tbody>
							</table>
						</div>
					
					</div>
				</div>
				<!-- end: Grid GPS Antenna -->
			
			</div>
		</div>
		
	</div>
	<!-- end: Tab Equipment -->

	<!-- start: Tab Power Equipment -->
	<div title="Power Equipment">
	
		<div id="tabs-group-power-equipment" class="easyui-tabs">
			<div title="ACPDB">
			
				<?php
				$acpdb = $this->db
					->query("
						SELECT acpdb.acpdb_id, acpdb.capacity_r, acpdb.capacity_s, acpdb.capacity_t, lo1.lookup_desc AS mcb_capacity_tp, lo2.lookup_desc AS mcb_capacity
						FROM t_mtr_site_l_acpdb acpdb
						LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(acpdb.mcb_capacity_tp AS VARCHAR) AND lo1.lookup_key = 'power_capacity' AND lo1.active = 1
						LEFT JOIN t_mtr_lookup lo2 ON lo2.lookup_value = CAST(acpdb.mcb_capacity AS VARCHAR) AND lo2.lookup_key = 'power_phase' AND lo2.active = 1
						WHERE acpdb.site_id = {$site_id}
					")
					->row_array();
				?>
				
				<div class="widget">	
					<div class="widget-head">
						<h4 class="heading"><i></i> ACPDB Data</h4>
					</div>
					
					<div class="widget-body form-horizontal">
					
						<div class="control-group">
							<label class="control-label" for="capacityR">Capacity R:</label>
							<div class="controls">
								<span class="input-label"><?php echo @$acpdb['capacity_r'] ?> Amp(AC)</span>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="capacityS">Capacity S:</label>
							<div class="controls">
								<span class="input-label"><?php echo @$acpdb['capacity_s'] ?> Amp(AC)</span>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="capacityT">Capacity T:</label>
							<div class="controls">
								<span class="input-label"><?php echo @$acpdb['capacity_t'] ?> Amp(AC)</span>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="capacityT">MCB Capacity of TP:</label>
							<div class="controls">
								<span class="input-label"><?php echo @$acpdb['mcb_capacity_tp'] ?> Amp(AC) <?php echo @$acpdb['mcb_capacity'] ?></span>
							</div>
						</div>
						
					</div>
				</div>
			
				<!-- start: photo(s) ACPDB -->
				<div class="widget">	
					<div class="widget-head">
						<h4 class="heading"><i></i> Photo(s)</h4>
					</div>
					
					<div class="widget-body">
					
						<table class="table table-bordered table-striped table-condensed" style="width:auto">
							<thead>
								<tr>
									<th width="200" class="center">Type</th>
									<th class="center">Photo</th>
								</tr>
							</thead>
							<tbody>				
								<?php
								$power_dir = FCPATH.'file/upload/site-info/power-equipment';
								$power_url = base_url('/file/upload/site-info/power-equipment');
								
								$lookup = $this->core->get_lookup('power_acpdb_photo_type');
								$photos = array();
								foreach ($lookup as $lo) {
									$thumb = base_url('/file/upload/site-info/no_image-60x60.jpg');
									$image = 'javascript:void(0)';
									
									if ($this->uri->segment(2) == 'edit' && isset($acpdb['acpdb_id'])) {
										if ( $photo = $this->core->view_row('t_mtr_site_l_acpdb_photo', array('acpdb_id' => $acpdb['acpdb_id'], 'type_id' => $lo['id'])) ) {
											if (file_exists($power_dir.'/'.$photo['photo_name']) && is_file($power_dir.'/'.$photo['photo_name'])) {
												$thumb = $this->image_model->resize($power_dir.'/'.$photo['photo_name'], 60, 60, 'crop');
												$image = $power_url.'/'.$photo['photo_name'];
											}
										}
									}
									
									$photos[] = array(
										'title' => $lo['desc'],
										'image' => '<a id="photo-acpdb-'.$lo['id'].'-box" href="'.$image.'" class="photobox"><img id="photo-acpdb-'.$lo['id'].'-thumb" src="'.$thumb.'" class="img-polaroid" /></a>',
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
				<!-- end: photo(s) ACPDB -->
				
				<!-- start: Grid Detail ACPDB -->
				<div class="widget">

					<div class="widget-head">
						<h4 class="heading"><i></i> Detail ACPDB</h4>
					</div>
					
					<div class="widget-body">
					
						<div id="tb-power-acpdb" class="form-search">
							<div class="input-append">
								<?php echo form_input('query-power-acpdb', '', 'id="query-power-acpdb" class="input-medium search-query" placeholder="Search..."') ?>
								<button class="btn" onclick="GRID_POWER_ACPDB.search()"><i class="icon-search"></i></button>
							</div>
						</div>
						
						<table id="grid-power-acpdb"></table>
					
					</div>
				</div>
				<!-- end: Grid Detail ACPDB -->
			
			</div>

			<div title="Rectifier">
			
				<!-- start: datagrid Rectifier -->
				<div class="widget">
					<div class="widget-head">
						<h4 class="heading"><i></i> Rectifier(s)</h4>
					</div>
					
					<div class="widget-body">
						
						<div id="tb-power-rectifier" class="form-search">
							<div class="input-append">
								<?php echo form_input('query-power-rectifier', '', 'id="query-power-rectifier" class="input-medium search-query" placeholder="Search..."') ?>
								<button class="btn" onclick="GRID_POWER_RECTIFIER.search()"><i class="icon-search"></i></button>
							</div>
						</div>
						
						<table id="grid-power-rectifier"></table>
					
					</div>
				</div>
				<!-- end: datagrid Rectifier -->
			
			</div>

			<div title="DCPDB">
			
				<!-- start: datagrid DCPDB -->
				<div class="widget">
					<div class="widget-head">
						<h4 class="heading"><i></i> DCPDB(s)</h4>
					</div>
					
					<div class="widget-body">
						
						<div id="tb-power-dcpdb" class="form-search">
							<div class="input-append">
								<?php echo form_input('query-power-dcpdb', '', 'id="query-power-dcpdb" class="input-medium search-query" placeholder="Search..."') ?>
								<button class="btn" onclick="GRID_POWER_DCPDB.search()"><i class="icon-search"></i></button>
							</div>
						</div>
						
						<table id="grid-power-dcpdb"></table>
					
					</div>
				</div>
				<!-- end: datagrid DCPDB -->
			
			</div>

			<div title="Battery">
			
				<!-- start: Grid Battery -->
				<div class="widget">

					<div class="widget-head">
						<h4 class="heading"><i></i> Battery Data</h4>
					</div>
					
					<div class="widget-body">
					
						<div id="tb-power-battery" class="form-search">
							<div class="input-append">
								<?php echo form_input('query-power-battery', '', 'id="query-power-battery" class="input-medium search-query" placeholder="Search..."') ?>
								<button class="btn" onclick="GRID_POWER_BATTERY.search()"><i class="icon-search"></i></button>
							</div>
						</div>
						
						<table id="grid-power-battery"></table>
					
					</div>
				</div>
				<!-- end: Grid Battery -->
			
			</div>

			<div title="Genset">
			
				<?php
				$genset = $this->db
					->query("
						SELECT 
						(CASE genset.permanent_genset
							WHEN 0 THEN 'No'
							WHEN 1 THEN 'Yes'
							ELSE '-'
						END) AS permanent_genset, genset.genset_brand, genset.genset_capacity, lo1.lookup_desc AS genset_ownership, genset.tank_capacity_daily, genset.tank_capacity_monthly
						FROM t_mtr_site_l_pln genset
						LEFT JOIN t_mtr_lookup lo1 ON lo1.lookup_value = CAST(genset.genset_ownership AS VARCHAR) AND lo1.lookup_key = 'genset_ownership' AND lo1.active = 1
						WHERE genset.site_id = {$site_id} AND genset.active = 1
					")
					->row_array();
				?>
				
				<div class="widget">
					<div class="widget-head">
						<h4 class="heading"><i></i> Genset</h4>
					</div>
					
					<div class="widget-body form-horizontal">
					
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label" for="permanentGenset">Permanent Genset:</label>
									<div class="controls">
										<span class="input-label"><?php echo @$genset['permanent_genset'] ?></span>
									</div>
									<div class="separator bottom"></div>
								</div>
							</div>
							
							<div class="span6">
								<div class="control-group">
									<label class="control-label" for="gensetBrand">Genset Brand:</label>
									<div class="controls">
										<span class="input-label"><?php echo @$genset['genset_brand'] ?></span>
									</div>
									<div class="separator bottom"></div>
								</div>
							</div>
						</div>

						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label" for="gensetCapacity">Genset Capacity:</label>
									<div class="controls">
										<span class="input-label"><?php echo @$genset['genset_capacity'] ?> VA</span>
									</div>
									<div class="separator bottom"></div>
								</div>
							</div>
								
							<div class="span6">
								<div class="control-group">
									<label class="control-label" for="gensetOwnership">Genset Ownership:</label>
									<div class="controls">
										<span class="input-label"><?php echo @$genset['genset_ownership'] ?></span>
									</div>
									<div class="separator bottom"></div>
								</div>
							</div>					
						</div>
						
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label" for="tankCapacityDaily">Tank Capacity (Daily):</label>
									<div class="controls">
										<span class="input-label"><?php echo @$genset['tank_capacity_daily'] ?> litre</span>
									</div>
									<div class="separator bottom"></div>
								</div>
							</div>
							
							<div class="span6">
								<div class="control-group">
									<label class="control-label" for="tankCapacityMonthly">Tank Capacity (Monthly):</label>
									<div class="controls">
										<span class="input-label"><?php echo @$genset['tank_capacity_monthly'] ?> litre</span>
									</div>
									<div class="separator bottom"></div>
								</div>
							</div>
						</div>
					
					</div>
				</div>
			
			</div>
		</div>
	
	</div>
	<!-- end: Tab Power Equipment -->
</div>

<script>
var GRID_EQUIPMENT_RF = {
	params: {
		cmd: 'GET_DATAGRID_EQUIPMENT_RF',
		site_id: <?php echo $site_id ?>,
		query: ''
	},
	loadData: function() {
		$('#grid-equipment-rf').datagrid({
			url: '<?php echo site_url('/site_info/equipment') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'type', title: 'Type', width: 150, sortable: true },
				{ field: 'model', title: 'Model', width: 150, sortable: true },
				{ field: 'cell', title: 'Cell', width: 150, sortable: true },
				{ field: 'ant_model', title: 'Ant. Model', width: 150, sortable: true },
				{ field: 'azimuth', title: 'Azimuth', width: 150, sortable: true, align: 'right' },
				{ field: 'electrical_tilt', title: 'Electrical Tilt', width: 150, sortable: true, align: 'right' },
				{ field: 'mechanical_tilt', title: 'Mechanical Tilt', width: 150, sortable: true, align: 'right' },
				{ field: 'beamwidth', title: 'Beamwidth', width: 150, sortable: true },
				{ field: 'beamwidth_value', title: 'Beamwidth Value', width: 150, sortable: true, align: 'right' },
				{ field: 'dimension', title: 'Dimension', width: 150, sortable: false, align: 'right', formatter: function(value, row, index) { return row.dimension_long + ' x ' + row.dimension_width + ' x ' +  row.dimension_height + ' CM'; } },
				{ field: 'height', title: 'Height', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' M'; } },
				{ field: 'weight', title: 'Weight', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' kg'; } },
			]],
			toolbar: '#tb-equipment-rf',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: false,
			rownumbers: true,
			height: 'auto'
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-equipment-rf').value;
		this.loadData();
		document.getElementById('query-equipment-rf').select();
	},
	init: function() {
		this.loadData();
		
		document.getElementById('query-equipment-rf').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_EQUIPMENT_RF.search();
			}
		};
	}
};

var GRID_EQUIPMENT_MW = {
	params: {
		cmd: 'GET_DATAGRID_EQUIPMENT_MW',
		site_id: <?php echo $site_id ?>,
		query: ''
	},
	loadData: function() {
		$('#grid-equipment-mw').datagrid({
			url: '<?php echo site_url('/site_info/equipment') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'type', title: 'Type', width: 150, sortable: true },
				{ field: 'ip_address', title: 'IP Address', width: 150, sortable: true },
				{ field: 'vendor_id', title: 'Far End', width: 150, sortable: true },
				{ field: 'link_code_id', title: 'Link ID', width: 150, sortable: true },
				{ field: 'ant_model', title: 'Ant. Model', width: 150, sortable: true },
				{ field: 'frequency', title: 'Frequency', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2); } },
				{ field: 'capacity', title: 'Capacity', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2) + ' MB'; } },
				{ field: 'equipment_model', title: 'Eq. Model', width: 150, sortable: true },
				{ field: 'bandwidth', title: 'Bandwidth', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2) + ' MHz'; } },
				{ field: 'protection_config', title: 'Protection Confing', width: 150, sortable: true },
				{ field: 'tx_power', title: 'TX Power', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2); } },
				{ field: 'ant_diameter', title: 'Ant. Diameter', width: 150, sortable: true, align: 'right' },
				{ field: 'number_of_channel', title: 'Number of Channel', width: 150, sortable: true, align: 'right' }
			]],
			toolbar: '#tb-equipment-mw',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: false,
			rownumbers: true,
			height: 'auto'
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-equipment-mw').value;
		this.loadData();
		document.getElementById('query-equipment-mw').select();
	},
	init: function() {
		this.loadData();
		
		document.getElementById('query-equipment-mw').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_EQUIPMENT_MW.search();
			}
		};
	}
};

var GRID_EQUIPMENT_GPS = {
	params: {
		cmd: 'GET_DATAGRID_EQUIPMENT_GPS',
		site_id: <?php echo $site_id ?>,
		query: ''
	},
	loadData: function() {
		$('#grid-equipment-gps').datagrid({
			url: '<?php echo site_url('/site_info/equipment') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'ant_gps_model', title: 'Model', width: 150, sortable: true },
				{ field: 'ant_gps_height', title: 'Height', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2, '.', '') + ' M'; } }
			]],
			toolbar: '#tb-equipment-gps',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			height: 'auto'
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-equipment-gps').value;
		this.loadData();
		document.getElementById('query-equipment-gps').select();
	},
	init: function() {
		this.loadData();
		
		document.getElementById('query-equipment-gps').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_EQUIPMENT_GPS.search();
			}
		};
	}
};

var GRID_POWER_ACPDB = {
	params: {
		cmd: 'GET_DATAGRID_SITEINFO_POWER_ACPDB',
		acpdb_id: <?php echo (int) @$acpdb['acpdb_id'] ?>,
		query: ''
	},
	loadData: function() {
		$('#grid-power-acpdb').datagrid({
			url: '<?php echo site_url('/site_info/power') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'item', title: 'Item(s)', width: 150, sortable: true },
				{ field: 'capacity', title: 'Capacity', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' Amp(AC)'; } },
				{ field: 'capacity_actual', title: 'Actual Capacity', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return number_format(value, 2, '.', '') + ' Amp(AC)'; } },
				{ field: 'phase_used', title: 'Phase Used', width: 150, sortable: true },
				{ field: 'allocation', title: 'Allocation', width: 150, sortable: true }
			]],
			toolbar: '#tb-power-acpdb',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			height: 'auto'
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-power-acpdb').value;
		this.loadData();
		document.getElementById('query-power-acpdb').select();
	},
	init: function() {
		this.loadData();
		
		document.getElementById('query-power-acpdb').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_POWER_ACPDB.search();
			}
		};
	}
};

var GRID_POWER_RECTIFIER = {
	params: {
		cmd: 'GET_DATAGRID_SITEINFO_POWER_RECTIFIER',
		site_id: <?php echo $site_id ?>,
		query: ''
	},
	loadData: function() {
		$('#grid-power-rectifier').datagrid({
			url: '<?php echo site_url('/site_info/power') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'slot_used', title: 'Slot used for Rectifier module', width: 180, sortable: true },
				{ field: 'dc_power', title: 'DC Power', width: 100, sortable: true, align: 'right' },
				{ field: 'dcdc_power_capacity', title: 'DC DC Power Capacity', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? value : 0) + ' Amp'; } },
				{ field: 'mcb_capacity_tp', title: 'MCB Capacity', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? value : 0) + ' Amp(AC) ' + (row.mcb_capacity != null ? row.mcb_capacity : ''); } },
				{ field: 'capacity_r', title: 'Capacity R', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? value : 0) + ' Amp(DC)'; } },
				{ field: 'capacity_s', title: 'Capacity S', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? value : 0) + ' Amp(DC)'; } },
				{ field: 'capacity_t', title: 'Capacity T', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? value : 0) + ' Amp(DC)'; } },
				
				<?php
				$lookup = $this->core->get_lookup('power_rectifier_photo_type');
				foreach ($lookup as $lo) {
				?>
				{ field: 'photo-<?php echo $lo['id'] ?>', title: '<?php echo $lo['desc'] ?>', width: 120, align: 'center', formatter: function(value, row, index) {
					return ( row.photo['<?php echo $lo['id'] ?>'] != null ? '<a href="' + row.photo.large['<?php echo $lo['id'] ?>'] + '" class="photobox"><img src="' + row.photo['<?php echo $lo['id'] ?>'] + '" width="30" border="0" class="img-polaroid" /></a>' : '');
				} },
				<?php
				}
				?>
			]],
			toolbar: '#tb-power-rectifier',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: false,
			rownumbers: true,
			height: 'auto',
			onLoadSuccess: function() {
				for (var i = 0; i < document.getElementsByClassName('photobox').length; i++) {
					document.getElementsByClassName('photobox')[i].onclick = function() {
						$.fancybox({
							type:'image',
							href: this.href,
							openEffect: 'elastic',
							closeEffect: 'elastic',
							closeBtn: true
						});
						
						return false;
					};
				}
			},
			view: detailview,
			detailFormatter: function(index, row) {
				return '<div style="padding:2px 0"><table class="ddv-rectifier"></table></div>';
			},
			onExpandRow: function(index, row) {
				var ddv = $(this).datagrid('getRowDetail', index).find('table.ddv-rectifier');
				
				ddv.datagrid({
					url: '<?php echo site_url('/site_info/power') ?>',
					queryParams: { cmd: 'GET_DATAGRID_RECTIFIER_DETAIL', rectifier_id: row.rectifier_id },
					method: 'GET',
					fitColumns: true,
					singleSelect: true,
					rownumbers: true,
					loadMsg: '',
					height: 'auto',
					pagination: true,
					fitColumns: true,
					striped: true,
					columns: [[
						{ field: 'item', title: 'Item(s)', width: 100, sortable: true },
						{ field: 'capacity', title: 'Capacity', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? value : 0) + ' Amp(DC)'; } },
						{ field: 'capacity_actual', title: 'Actual Capacity', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' Amp(DC)'; } },
						{ field: 'llvd_info', title: 'LLVD Info', width: 100, sortable: true },
						{ field: 'allocation', title: 'Allocation', width: 120, sortable: true },
						{ field: 'site_code', title: 'FE', width: 120, sortable: true },
						{ field: 'system', title: 'System', width: 120, sortable: true }
					]],
					onResize: function() {
						$('#grid-power-rectifier').datagrid('fixDetailRowHeight', index);
					},
					onLoadSuccess: function() {
						setTimeout(function() {
							$('#grid-power-rectifier').datagrid('fixDetailRowHeight', index);
						}, 0);
					}
				});
				
				$('#grid-power-rectifier').datagrid('fixDetailRowHeight', index);
			}
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-power-rectifier').value;
		this.loadData();
		document.getElementById('query-power-rectifier').select();
	},
	init: function() {
		this.loadData();
		
		document.getElementById('query-power-rectifier').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_POWER_RECTIFIER.search();
			}
		};
	}
};

var GRID_POWER_DCPDB = {
	params: {
		cmd: 'GET_DATAGRID_SITEINFO_POWER_DCPDB',
		site_id: <?php echo $site_id ?>,
		query: ''
	},
	loadData: function() {
		$('#grid-power-dcpdb').datagrid({
			url: '<?php echo site_url('/site_info/power') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'dcpdb_id', hidden: true },
				{ field: 'main_mcb_capacity', title: 'Main MCB Capacity', width: 120, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? value : 0) + ' Amp'; } },
				{ field: 'cable_diameter', title: 'Cable Diameter', width: 120, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? value : 0) + ' Mm<sup>2</sup>'; } },
				
				<?php
				$lookup = $this->core->get_lookup('power_dcpdb_photo_type');
				foreach ($lookup as $lo) {
				?>
				{ field: 'photo-<?php echo $lo['id'] ?>', title: '<?php echo $lo['desc'] ?>', width: 120, align: 'center', formatter: function(value, row, index) {
					return ( row.photo['<?php echo $lo['id'] ?>'] != null ? '<a href="' + row.photo.large['<?php echo $lo['id'] ?>'] + '" class="photobox"><img src="' + row.photo['<?php echo $lo['id'] ?>'] + '" width="30" border="0" class="img-polaroid" /></a>' : '');
				} },
				<?php
				}
				?>
			]],
			toolbar: '#tb-power-dcpdb',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			height: 'auto',
			onLoadSuccess: function() {
				for (var i = 0; i < document.getElementsByClassName('photobox').length; i++) {
					document.getElementsByClassName('photobox')[i].onclick = function() {
						$.fancybox({
							type:'image',
							href: this.href,
							openEffect: 'elastic',
							closeEffect: 'elastic',
							closeBtn: true
						});
						
						return false;
					};
				}
			},
			view: detailview,
			detailFormatter: function(index, row) {
				return '<div style="padding:2px 0"><table class="ddv-dcpdb"></table></div>';
			},
			onExpandRow: function(index, row) {
				var ddv = $(this).datagrid('getRowDetail', index).find('table.ddv-dcpdb');
				
				ddv.datagrid({
					url: '<?php echo site_url('/site_info/power') ?>',
					queryParams: { cmd: 'GET_DATAGRID_DCPDB_DETAIL', dcpdb_id: row.dcpdb_id },
					method: 'GET',
					fitColumns: true,
					singleSelect: true,
					rownumbers: true,
					loadMsg: '',
					height: 'auto',
					pagination: true,
					fitColumns: true,
					striped: true,
					columns: [[
						{ field: 'item', title: 'Item(s)', width: 100, sortable: true },
						{ field: 'capacity', title: 'Capacity', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return (value != null ? value : 0) + ' Amp(DC)'; } },
						{ field: 'actual_capacity', title: 'Actual Capacity', width: 100, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' Amp(DC)'; } },
						{ field: 'allocation', title: 'Allocation', width: 120, sortable: true },
						{ field: 'site_code', title: 'FE ID', width: 120, sortable: true },
						{ field: 'system', title: 'System', width: 120, sortable: true }
					]],
					onResize: function() {
						$('#grid-power-dcpdb').datagrid('fixDetailRowHeight', index);
					},
					onLoadSuccess: function() {
						setTimeout(function() {
							$('#grid-power-dcpdb').datagrid('fixDetailRowHeight', index);
						}, 0);
					}
				});
				
				$('#grid-power-dcpdb').datagrid('fixDetailRowHeight', index);
			}
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-power-dcpdb').value;
		this.loadData();
		document.getElementById('query-power-dcpdb').select();
	},
	init: function() {
		this.loadData();
		
		document.getElementById('query-power-dcpdb').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_POWER_DCPDB.search();
			}
		};
	}
};

var GRID_POWER_BATTERY = {
	params: {
		cmd: 'GET_DATAGRID_SITEINFO_POWER_BATTERY',
		site_id: <?php echo $site_id ?>,
		query: ''
	},
	loadData: function() {
		$('#grid-power-battery').datagrid({
			url: '<?php echo site_url('/site_info/power') ?>',
			queryParams: this.params,
			method: 'GET',
			pageSize: 10,
			columns:[[
				{ field: 'brand', title: 'Brand / Model', width: 150, sortable: true },
				{ field: 'capacity', title: 'Capacity per Cell', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' Ah'; } },
				{ field: 'total_bank', title: 'Total Bank', width: 150, sortable: true, align: 'right' },
				{ field: 'total_shunt', title: 'Total Shunt', width: 150, sortable: true, align: 'right' },
				{ field: 'weight_per_cell', title: 'Weight per Cell', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' kg'; } },
				{ field: 'voltage_per_cell', title: 'Voltage per Cell', width: 150, sortable: true, align: 'right', formatter: function(value, row, index) { return value + ' Volt'; } }
			]],
			toolbar: '#tb-power-battery',
			striped: true,
			pagination: true,
			singleSelect: true,
			fitColumns: true,
			rownumbers: true,
			height: 'auto'
		});
	},
	search: function() {
		this.params.query = document.getElementById('query-power-battery').value;
		this.loadData();
		document.getElementById('query-power-battery').select();
	},
	init: function() {
		this.loadData();
		
		document.getElementById('query-power-battery').onkeydown = function(e) {
			if (e.keyCode == 13) {
				GRID_POWER_BATTERY.search();
			}
		};
	}
};

$(function() {
	/*var loadTransmissionContent = function() {
		var tab = $('#equipmentTab').tabs('getSelected');
		var index = $('#equipmentTab').tabs('getTabIndex', tab);
		if (index == 2) {
			$('#equipmentTab').tabs('update', {
				tab: tab,
				options: {
					href: document.URL + '?load_content=TRANSMISSION&site_id=' + document.getElementsByName('siteId')[0].value
				}
			});
		}
	};*/
	
	//GRID_EQUIPMENT_RF.init();
	//GRID_EQUIPMENT_MW.init();
	//GRID_EQUIPMENT_GPS.init();
	GRID_POWER_ACPDB.init();
	GRID_POWER_RECTIFIER.init();
	GRID_POWER_DCPDB.init();
	GRID_POWER_BATTERY.init();
	
	$('.photobox').fancybox({
		openEffect: 'elastic',
		closeEffect: 'elastic',
		closeBtn: true
	});
});
</script>