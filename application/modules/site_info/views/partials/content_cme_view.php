<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<div class="form-horizontal">

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">Tower</h4>
		</div>
		
		<div class="widget-body">
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_tower_ownership">
						<label class="control-label">Tower Ownership:</label>
						<div class="controls">
							<?php
							$lookup = $this->core->get_lookup_options('tower_ownership');
							?>
							<span class="input-label"><?php echo $lookup[$tower['kepemilikan_tower']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_tower_owner_name">
						<label class="control-label" for="namaPemilikTower">Name of Tower Owner:</label>
						<div class="controls">
							<span class="input-label"><?php echo $tower['nama_pemilik_tower'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_tower_owner_phone">
						<label class="control-label" for="telpPemilikTower">Phone of Tower Owner:</label>
						<div class="controls">
							<span class="input-label"><?php echo $tower['no_telp_pemilik_tower'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_tower_type">
						<label class="control-label" for="towerType">Tower Type:</label>
						<div class="controls">
							<?php
							$lookup = $this->core->get_lookup_options('site_type');
							?>
							<span class="input-label"><?php echo $lookup[$tower['tower_type']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_tower_leg_number">
						<label class="control-label" for="towerLegNo">Tower Leg No:</label>
						<div class="controls">
							<?php
							$lookup = $this->core->get_lookup_options('tower_leg_number');
							?>
							<span class="input-label"><?php echo $lookup[$tower['tower_leg_no']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_tower_height">
						<label class="control-label" for="towerHeight">Tower Height:</label>
						<div class="controls">
							<span class="input-label"><?php echo $tower['tower_height'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="control-group" id="form_tower_fabricator">
				<label class="control-label" for="towerFabricator">Tower Fabricator:</label>
				<div class="controls">
					<span class="input-label"><?php echo $tower['tower_fabricator'] ?></span>
				</div>
			</div>
			 
			<div class="control-group" id="form_tower_remarks">
				<label class="control-label" for="towerRemarks">Remarks:</label>
				<div class="controls">
					<span class="input-label"><?php echo $tower['remarks'] ?></span>
				</div>
			</div>
		</div>
	</div>

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">Shelter</h4>
		</div>
		
		<div class="widget-body">
			<div class="control-group" id="form_shelter_needed">
				<label class="control-label" for="shelterNeeded">Shelter Needed:</label>
				<div class="controls">
					<?php
					$shelterNeeded = array('' => '', 0 => 'No', 1 => 'Yes');
					?>
					<span class="input-label"><?php echo $shelterNeeded[$shelter['shelter_needed']] ?></span>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_shelter_ownership">
						<label class="control-label" for="kepemilikanShelter">Shelter Ownership:</label>
						<div class="controls">
							<?php
							$lookup = $this->core->get_lookup('shelter_ownership');
							$options = array(0 => '');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							?>
							<span class="input-label"><?php echo $options[$shelter['kepemilikan_shelter']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_shelter_owner_name">
						<label class="control-label" for="namaPemilikShelter">Name of Shelter Owner:</label>
						<div class="controls">
							<span class="input-label"><?php echo $shelter['nama_pemilik_shelter'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_shelter_owner_phone">
						<label class="control-label" for="noTelpPemilikShelter">Phone of Shelter Owner:</label>
						<div class="controls">
							<span class="input-label"><?php echo $shelter['no_telp_pemilik_shelter'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_shelter_type">
						<label class="control-label" for="shelterType">Shelter Type:</label>
						<div class="controls">
							<?php
							$dataShelterType = $this->core->get_lookup('shelter_type');
							$shelterType = array(0 => '');
							foreach ($dataShelterType  as $type) {
								$shelterType[$type['id']] = $type['desc'];
							}
							?>
							<span class="input-label"><?php echo $shelterType[$shelter['shelter_type']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_shelter_size">
						<label class="control-label" for="shelterSize">Shelter Size (LxWxH):</label>
						<div class="controls">
							<?php
							$size_l = 0;
							$size_w = 0;
							$size_h = 0;
							
							if ($this->uri->segment(2) == 'view' && isset($shelter['shelter_size'])) {
								$size = explode('x', $shelter['shelter_size']);
								$size_l = $size[0];
								$size_w = $size[1];
								$size_h = $size[2];
							}
							?>
							<span class="input-label"><?php echo $size_l.' x '.$size_w.' x '.$size_h ?> M</span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_shelter_fabricator">
						<label class="control-label" for="shelterFabricator">Shelter Fabricator:</label>
						<div class="controls">
							<span class="input-label"><?php echo $shelter['shelter_fabricator'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="control-group" id="form_shelter_remarks">
				<label class="control-label" for="shelterRemarks">Remarks:</label>
				<div class="controls">
					<span class="input-label"><?php echo $shelter['remarks'] ?></span>
				</div>
			</div>
		</div>
	</div>

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">Power (PLN)</h4>
		</div>
		
		<div class="widget-body">
			<div class="control-group" id="form_pln_handover_project">
				<label class="control-label" for="handoverProject">Handover Project:</label>
				<div class="controls">
					<span class="input-label"><?php echo isset($pln['handover_project']) && $pln['handover_project'] != '' ? date('d-M-Y', strtotime($pln['handover_project'])) : '' ?></span>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">	
					<div class="control-group" id="form_pln_customer_id">
						<label class="control-label" for="idPelangganPLN">Customer ID:</label>
						<div class="controls">
							<span class="input-label"><?php echo $pln['id_pelanggan_pln'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_pln_customer_name">
						<label class="control-label" for="atasNamaPelanggan">Customer Name:</label>
						<div class="controls">
							<span class="input-label"><?php echo $pln['atas_nama_pelanggan'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_pln_connected_date">
						<label class="control-label" for="plnConnectedDate">PLN Connected Date:</label>
						<div class="controls">
							<span class="input-label"><?php echo isset($pln['pln_connected_date']) ? date('d-M-Y', strtotime($pln['pln_connected_date'])) : '' ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_pln_disconnected_date">
						<label class="control-label" for="plnDisconnectDate">PLN Disconnected Date:</label>
						<div class="controls">
							<span class="input-label"><?php echo isset($pln['pln_disconnect_date']) ? date('d-M-Y', strtotime($pln['pln_disconnect_date'])) : '' ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_pln_nama_upj">
						<label class="control-label" for="namaUPJPLN">Nama UPJ PLN:</label>
						<div class="controls">
							<span class="input-label"><?php echo $pln['nama_upj_pln'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_pln_wo_dismantle_date">
						<label class="control-label" for="woDismantleDate">WO Dismantle Date:</label>
						<div class="controls">
							<span class="input-label"><?php echo isset($pln['wo_dismantle_date']) ? date('d-M-Y', strtotime($pln['wo_dismantle_date'])) : '' ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_pln_trafo_blast">
						<label class="control-label" for="trafoBlast">Trafo Blast:</label>
						<div class="controls">
							<?php
							$trafoBlast = array('' => '', 0 => 'No', 1 => 'Yes');
							?>
							<span class="input-label"><?php echo $trafoBlast[$pln['trafo_blast']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_pln_trafo_capacity">
						<label class="control-label" for="trafoCapacity">Trafo Capacity:</label>
						<div class="controls">
							<?php
							$lookup = $this->core->get_lookup('pln_trafo_capacity');
							$options = array(0 => '');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							?>
							<span class="input-label"><?php echo $options[$pln['trafo_capacity']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_pln_trafo_maintained_by">
						<label class="control-label" for="trafoMaintainedBySmartfren">Trafo Maintained By:</label>
						<div class="controls">
							<?php
							$trafoMaintainedBy = array(0 => '');
							$dataTrafoMaintainedBy = $this->core->get_lookup('company');
							foreach ($dataTrafoMaintainedBy as $company) {
								$trafoMaintainedBy[$company['id']] = $company['desc'];
							}
							?>
							<span class="input-label"><?php echo $trafoMaintainedBy[$pln['trafo_maintained_by']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_pln_kwh_type">
						<label class="control-label" for="kwhType">KWH Type:</label>
						<div class="controls">                           
							<?php
							$lookup = $this->core->get_lookup('pln_kwh_type');
							$options = array(0 => '');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							?>
							<span class="input-label"><?php echo $options[$pln['kwh_type']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_pln_phase">
						<label class="control-label" for="plnPhase">PLN Phase:</label>
						<div class="controls">
							<?php
							$plnPhase = array(0 => '');
							$lookup = $this->core->get_lookup('pln_phase');
							foreach ($lookup as $loo) {
								$plnPhase[$loo['id']] = $loo['desc'];
							}
							?>
							<span class="input-label"><?php echo $plnPhase[$pln['pln_phase']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_pln_capacity">
						<label class="control-label" for="plnCapacity">PLN Capacity:</label>
						<div class="controls">
							<span class="input-label"><?php echo $pln['pln_capacity'] ?> VA</span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_pln_tariff_type">
						<label class="control-label" for="tarifType">Tariff Type:</label>
						<div class="controls">
							<?php
							$tarifType = array('' => '');
							$lookup = $this->core->get_lookup('tariff_type');
							foreach ($lookup as $loo) {
								$tarifType[$loo['id']] = $loo['desc'];
							}
							?>
							<span class="input-label"><?php echo $tarifType[$pln['tarif_type']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_pln_golongan_tarif">
					   <label class="control-label" for="tarifName">Golongan Tarif:</label>
						<div class="controls">
							<?php
							$lookup = $this->core->get_lookup('pln_tariff_name');
							$options = array(0 => '');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							?>
							<span class="input-label"><?php echo $options[$pln['tarif_name']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
				
			<div class="control-group" id="form_pln_payment_method">
				<label class="control-label" for="caraPembayaran">Payment Method:</label>
				<div class="controls">
					<?php
					$lookup = $this->core->get_lookup('payment_method');
					$options = array(0 => '');
					foreach ($lookup as $lo) {
						$options[$lo['id']] = $lo['desc'];
					}
					?>
					<span class="input-label"><?php echo $options[$pln['cara_pembayaran']] ?></span>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_pln_contract_under">
						<label class="control-label" for="contractUnder">Contract Under:</label>
						<div class="controls">
							<?php
							$contractUnder = array(0 => '');
							$dataContractUnder = get_instance()->core->get_lookup('contract_under');
							foreach ($dataContractUnder as $contract) {
								$contractUnder[$contract['id']] = $contract['desc'];
							}
							?>
							<span class="input-label"><?php echo $contractUnder[$pln['contract_under']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_pln_cost_center">
						<label class="control-label" for="costCenter">Cost Center:</label>
						<div class="controls">
							<span class="input-label"><?php echo $pln['cost_center'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
			</div>
			
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group" id="form_pln_company_code">
						<label class="control-label" for="companyCode">Company Code:</label>
						<div class="controls">
							<span class="input-label"><?php echo $pln['company_code'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="control-group" id="form_pln_remarks">
				<label class="control-label" for="plnRemarks">Remarks:</label>
				<div class="controls">
					<span class="input-label"><?php echo $pln['remarks'] ?></span>
				</div>
			</div>
		
		</div>
	</div>

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">Genset</h4>
		</div>
		
		<div class="widget-body">
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_permanent_genset">
						<label class="control-label" for="permanentGenset">Permanent Genset:</label>
						<div class="controls">
							<?php
							$permanentGenset = array('' => '', 0 => 'No', 1 => 'Yes');
							?>
							<span class="input-label"><?php echo $permanentGenset[$pln['permanent_genset']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_genset_brand">
						<label class="control-label" for="gensetBrand">Genset Brand:</label>
						<div class="controls">
							<span class="input-label"><?php echo $pln['genset_brand'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_genset_capacity">
						<label class="control-label" for="gensetCapacity">Genset Capacity:</label>
						<div class="controls">
							<span class="input-label"><?php echo $pln['genset_capacity'] ?> VA</span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
					
				<div class="span6">
					<div class="control-group" id="form_genset_ownership">
						<label class="control-label" for="gensetOwnership">Genset Ownership:</label>
						<div class="controls">
							<?php
							$lookup = $this->core->get_lookup('genset_ownership');
							$options = array(0 => '');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							?>
							<span class="input-label"><?php echo $options[$pln['genset_ownership']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_tank_capacity_daily">
						<label class="control-label" for="tankCapacityDaily">Tank Capacity (Daily):</label>
						<div class="controls">
							<span class="input-label"><?php echo $pln['genset_ownership'] ?> litre</span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="tank_capacity_monthly">
						<label class="control-label" for="tankCapacityMonthly">Tank Capacity (Monthly):</label>
						<div class="controls">
							<span class="input-label"><?php echo $pln['tank_capacity_monthly'] ?> litre</span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
		
		</div>
	</div>

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">AC</h4>
		</div>
		
		<div class="widget-body">
			<div style="overflow:auto">
				<table id="tblAC" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>Brand:</th>
							<th>Type / Model:</th>
							<th>Inverter Type:</th>
							<th>Capacity:</th>
							<th>SN Indoor Part:</th>
							<th>SN Outdoor Part:</th>
						</tr>
					</thead>
					<tbody>
						<?php if (count($ac['detail']) > 0) {
						
							$ac_counter = 1;
							foreach ($ac['detail'] as $detail) {
						?>
						
						<tr id="acRow<?php echo $ac_counter ?>">
							<td>
								<?php
								echo form_hidden('acId[]', set_value('acId[]', $detail['ac_id']));
								
								$lookup = $this->core->get_lookup('ac_brand');
								$options = array(0 => '');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $options[$detail['ac_brand_id']] ?></span>
							</td>
							<td>
								<span><?php echo $detail['model'] ?></span>
							</td>
							<td>
								<?php
								$inverterType = array(0 => '');
								$lookup = $this->core->get_lookup('inverter_type');
								foreach ($lookup as $lo) {
									$inverterType[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $inverterType[$detail['inverter_type']] ?></span>
							</td>
							<td>
								<?php
								$lookup = $this->core->get_lookup('ac_capacity');
								$options = array(0 => '');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $options[$detail['ac_capacity_id']] ?></span>
							</td>
							<td>
								<span><?php echo $detail['sn_indoor_part'] ?></span>
							</td>
							<td>
								<span><?php echo $detail['sn_outdoor_part'] ?></span>
							</td>
						</tr>
						<?php 
									$ac_counter++;
								}
							}
						?>						
					</tbody>
				</table>
			</div>

			<div class="separator bottom"></div>

			<div class="control-group">
				<label class="control-label" for="acRemarks">Remarks:</label>							
				<div class="controls">
					<span class="input-label"><?php echo $ac['remarks'] ?></span>
				</div>
			</div>
			
		</div>
	</div>

</div>

<script>
doPreviledgePerfield();
</script>