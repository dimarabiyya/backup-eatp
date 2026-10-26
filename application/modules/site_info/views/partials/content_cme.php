<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<?php echo form_open('', 'id="formCme" class="form-horizontal"') ?>

<?php echo form_hidden('siteId', set_value('siteId', '')) ?>

<div class="top-form-actions">
	<button class="btn btn-primary btn-small btn-icon glyphicons circle_ok"><i></i> Save</button>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">Tower</h4>
	</div>
	
	<div class="widget-body">
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_tower_ownership">
					<label class="control-label">Tower Ownership:</label>
					<div class="controls">
						<?php
						$lookup = $this->core->get_lookup('tower_ownership');
						$options = array(0 => '- Choose one -');
						foreach ($lookup as $lo) {
							$options[$lo['id']] = $lo['desc'];
						}
						echo @form_dropdown('kepemilikanTower', $options, $tower['kepemilikan_tower']);
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_tower_owner_name">
					<label class="control-label" for="namaPemilikTower">Name of Tower Owner:</label>
					<div class="controls">
						<?php echo @form_input('namaPemilikTower', set_value('namaPemilikTower', $tower['nama_pemilik_tower']), 'id="namaPemilikTower" class="medium"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_tower_owner_phone">
					<label class="control-label" for="telpPemilikTower">Phone of Tower Owner:</label>
					<div class="controls">
						<?php echo @form_input('telpPemilikTower', set_value('telpPemilikTower', $tower['no_telp_pemilik_tower']), 'id="telpPemilikTower" class="medium"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_tower_type">
					<label class="control-label" for="towerType">Tower Type:</label>
					<div class="controls">
						<?php
						$lookup = $this->core->get_lookup('site_type');
						$options = array(0 => '- Choose one -');
						foreach ($lookup as $lo) {
							$options[$lo['id']] = $lo['desc'];
						}
						echo @form_dropdown('towerType', $options, set_value('towerType', $tower['tower_type']), 'id="towerType"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_tower_leg_number">
					<label class="control-label" for="towerLegNo">Tower Leg No:</label>
					<div class="controls">
						<?php
						$lookup = $this->core->get_lookup('tower_leg_number');
						$options = array(0 => '- Choose one -');
						foreach ($lookup as $lo) {
							$options[$lo['id']] = $lo['desc'];
						}
						echo @form_dropdown('towerLegNo', $options, set_value('towerLegNo', $tower['tower_leg_no']), 'id="towerLegNo"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_tower_height">
					<label class="control-label" for="towerHeight">Tower Height:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('towerHeight', set_value('towerHeight', $tower['tower_height']), 'id="towerHeight" class="input-small number"') ?>
							<span class="add-on">M</span>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="control-group" id="form_tower_fabricator">
			<label class="control-label" for="towerFabricator">Tower Fabricator:</label>
			<div class="controls">
				<?php echo @form_input('towerFabricator', set_value('towerFabricator', trim($tower['tower_fabricator'])), 'id="towerFabricator" class="medium"') ?>
			</div>
		</div>
		 
		<div class="control-group" id="form_tower_remarks">
			<label class="control-label" for="towerRemarks">Remarks:</label>
			<div class="controls">
				<?php echo @form_textarea('towerRemarks', set_value('towerRemarks', trim($tower['remarks'])), 'id="towerRemarks" class="span4" style="height:100px;"') ?>
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
				$shelterNeeded = array('' => '- Choose one -', 0 => 'No', 1 => 'Yes');
				echo @form_dropdown('shelterNeeded', $shelterNeeded, set_value('shelterNeeded', $shelter['shelter_needed']), 'id="shelterNeeded"');
				?>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_shelter_ownership">
					<label class="control-label" for="kepemilikanShelter">Shelter Ownership:</label>
					<div class="controls">
						<?php
						$lookup = $this->core->get_lookup('shelter_ownership');
						$options = array(0 => '- Choose one -');
						foreach ($lookup as $lo) {
							$options[$lo['id']] = $lo['desc'];
						}
						echo @form_dropdown('kepemilikanShelter', $options, set_value('kepemilikanShelter', $shelter['kepemilikan_shelter']), 'id="kepemilikanShelter"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_shelter_owner_name">
					<label class="control-label" for="namaPemilikShelter">Name of Shelter Owner:</label>
					<div class="controls">
						<?php echo @form_input('namaPemilikShelter', set_value('namaPemilikShelter', trim($shelter['nama_pemilik_shelter'])), 'id="namaPemilikShelter" class="medium"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_shelter_owner_phone">
					<label class="control-label" for="noTelpPemilikShelter">Phone of Shelter Owner:</label>
					<div class="controls">
						<?php echo @form_input('noTelpPemilikShelter', set_value('noTelpPemilikShelter', $shelter['no_telp_pemilik_shelter']), 'id="noTelpPemilikShelter" class="medium"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_shelter_type">
					<label class="control-label" for="shelterType">Shelter Type:</label>
					<div class="controls">
						<?php
						$dataShelterType = $this->core->get_lookup('shelter_type');
						$shelterType = array(0 => '- Choose one -');
						foreach ($dataShelterType  as $type) {
							$shelterType[$type['id']] = $type['desc'];
						}
						echo @form_dropdown('shelterType', $shelterType, set_value('shelterType', $shelter['shelter_type']), 'id="shelterType"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_shelter_size">
					<label class="control-label" for="shelterSize">Shelter Size (LxWxH):</label>
					<div class="controls">
						<?php
						$size_l = 0;
						$size_w = 0;
						$size_h = 0;
						
						if ($this->uri->segment(2) == 'edit' && isset($shelter['shelter_size'])) {
							$size = explode('x', $shelter['shelter_size']);
							$size_l = $size[0];
							$size_w = $size[1];
							$size_h = $size[2];
						}
						?>
						<div class="input-append">
							<?php echo @form_input('shelterSizeL', set_value('shelterSizeL', $size_l), 'id="shelterSizeL" class="input-mini number"') ?>
							<?php echo @form_input('shelterSizeW', set_value('shelterSizeW', $size_w), 'id="shelterSizeW" class="input-mini number"') ?>
							<?php echo @form_input('shelterSizeH', set_value('shelterSizeH', $size_h), 'id="shelterSizeH" class="input-mini number"') ?>
							<span class="add-on">M</span>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_shelter_fabricator">
					<label class="control-label" for="shelterFabricator">Shelter Fabricator:</label>
					<div class="controls">
						<?php echo @form_input('shelterFabricator', set_value('shelterFabricator', trim($shelter['shelter_fabricator'])), 'id="shelterFabricator" class="medium"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="control-group" id="form_shelter_remarks">
			<label class="control-label" for="shelterRemarks">Remarks:</label>
			<div class="controls">
				<?php echo @form_textarea('shelterRemarks', set_value('shelterRemarks', trim($shelter['remarks'])), 'id="shelterRemarks" class="span4" style="height:100px;"') ?>
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
				<div class="input-append">
					<?php echo @form_input('handoverProject', set_value('handoverProject', $pln['handover_project']), 'id="handoverProject" class="input-small date" placeholder="DD-MMM-YYYY"') ?>
					<span class="add-on"><i class="icon-calendar"></i></span>
					<!-- <button onclick="clearField('handoverProject')" type="button" class="btn">Clear</button> -->
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_pln_customer_id">
					<label class="control-label" for="idPelangganPLN">Customer ID:</label>
					<div class="controls">
						<?php echo @form_input('idPelangganPLN', set_value('idPelangganPLN', $pln['id_pelanggan_pln']), 'id="idPelangganPLN" class="uppercase" maxlength="200"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_pln_customer_name">
					<label class="control-label" for="atasNamaPelanggan">Customer Name:</label>
					<div class="controls">
						<?php echo @form_input('atasNamaPelanggan', set_value('atasNamaPelanggan', trim($pln['atas_nama_pelanggan'])), 'id="atasNamaPelanggan" class="input-xlarge"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_pln_connected_date">
					<label class="control-label" for="plnConnectedDate">PLN Connected Date:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('plnConnectedDate', set_value('plnConnectedDate', $pln['pln_connected_date']), 'id="plnConnectedDate" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button onclick="clearField('plnConnectedDate')" type="button" class="btn">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_pln_disconnected_date">
					<label class="control-label" for="plnDisconnectDate">PLN Disconnected Date:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('plnDisconnectDate', set_value('plnDisconnectDate', $pln['wo_dismantle_date']), 'id="plnDisconnectDate" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button onclick="clearField('plnDisconnectDate')" type="button" class="btn">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_pln_nama_upj">
					<label class="control-label" for="namaUPJPLN">Nama UPJ PLN:</label>
					<div class="controls">
						<?php echo @form_input('namaUPJPLN', set_value('namaUPJPLN', trim($pln['nama_upj_pln'])), 'id="namaUPJPLN" class="medium"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_pln_wo_dismantle_date">
					<label class="control-label" for="woDismantleDate">WO Dismantle Date:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('woDismantleDate', set_value('woDismantleDate', $pln['wo_dismantle_date']), 'id="woDismantleDate" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button onclick="clearField('woDismantleDate')" type="button" class="btn">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_pln_trafo_blast">
					<label class="control-label" for="trafoBlast">Trafo Blast:</label>
					<div class="controls">
						<?php
						$trafoBlast = array('' => '- Choose one -', 0 => 'No', 1 => 'Yes');
						echo @form_dropdown('trafoBlast', $trafoBlast, set_value('trafoBlast', $pln['trafo_blast']), 'id="trafoBlast"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_pln_trafo_capacity">
					<label class="control-label" for="trafoCapacity">Trafo Capacity:</label>
					<div class="controls">
						<?php
						$lookup = $this->core->get_lookup('pln_trafo_capacity');
						$options = array(0 => '- Choose one -');
						foreach ($lookup as $lo) {
							$options[$lo['id']] = $lo['desc'];
						}
						echo @form_dropdown('trafoCapacity', $options, set_value('trafoCapacity', $pln['trafo_capacity']), 'id="trafoCapacity"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_pln_trafo_maintained_by">
					<label class="control-label" for="trafoMaintainedBySmartfren">Trafo Maintained By:</label>
					<div class="controls">
						<?php
						$trafoMaintainedBy = array(0 => '- Choose one -');
						$dataTrafoMaintainedBy = $this->core->get_lookup('company');
						foreach ($dataTrafoMaintainedBy as $company) {
							$trafoMaintainedBy[$company['id']] = $company['desc'];
						}
						echo @form_dropdown('trafoMaintainedBy', $trafoMaintainedBy, set_value('trafoMaintainedBy', $pln['trafo_maintained_by']), 'id="trafoMaintainedBy"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_pln_kwh_type">
					<label class="control-label" for="kwhType">KWH Type:</label>
					<div class="controls">                           
						<?php
						$lookup = $this->core->get_lookup('pln_kwh_type');
						$options = array(0 => '- Choose one -');
						foreach ($lookup as $lo) {
							$options[$lo['id']] = $lo['desc'];
						}
						echo @form_dropdown('kwhType', $options, set_value('kwhType', $pln['kwh_type']), 'id="kwhType"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_pln_phase">
					<label class="control-label" for="plnPhase">PLN Phase:</label>
					<div class="controls">
						<?php
						$plnPhase = array(0 => '- Choose one -');
						$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'pln_phase', 'active' => 1), 'order');
						foreach ($lookup as $loo) {
							$plnPhase[$loo['lookup_value']] = $loo['lookup_desc'];
						}
						echo @form_dropdown('plnPhase', $plnPhase, set_value('plnPhase', $pln['pln_phase']), 'id="plnPhase"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_pln_capacity">
					<label class="control-label" for="plnCapacity">PLN Capacity:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('plnCapacity', set_value('plnCapacity', $pln['pln_capacity']), 'id="plnCapacity" class="input-small number"') ?>
							<span class="add-on">VA</span>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_pln_tariff_type">
					<label class="control-label" for="tarifType">Tariff Type:</label>
					<div class="controls">
						<?php
						$tarifType = array('' => '- Choose one -');
						$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'tariff_type', 'active' => 1), 'order');
						foreach ($lookup as $loo) {
							$tarifType[$loo['lookup_value']] = $loo['lookup_desc'];
						}
						echo @form_dropdown('tarifType', $tarifType, set_value('tarifType', $pln['tarif_type']), 'id="tarifType"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_pln_golongan_tarif">
				   <label class="control-label" for="tarifName">Golongan Tarif:</label>
					<div class="controls">
						<?php
						$lookup = $this->core->get_lookup('pln_tariff_name');
						$options = array(0 => '- Choose one -');
						foreach ($lookup as $lo) {
							$options[$lo['id']] = $lo['desc'];
						}
						echo @form_dropdown('tarifName', $options, set_value('tarifName', $pln['tarif_name']), 'id="tarifName"');
						?>
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
				$options = array(0 => '- Choose one -');
				foreach ($lookup as $lo) {
					$options[$lo['id']] = $lo['desc'];
				}
				echo @form_dropdown('caraPembayaran', $options, set_value('caraPembayaran', $pln['cara_pembayaran']), 'id="caraPembayaran"');
				?>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_pln_contract_under">
					<label class="control-label" for="contractUnder">Contract Under:</label>
					<div class="controls">
						<?php
						$contractUnder = array(0 => '- Choose one -');
						$dataContractUnder = get_instance()->core->view_all('t_mtr_lookup', array('lookup_key' => 'contract_under', 'active' => 1), 'lookup_desc');
						foreach ($dataContractUnder as $contract) {
							$contractUnder[$contract['lookup_value']] = $contract['lookup_desc'];
						}
						echo @form_dropdown('contractUnder', $contractUnder, $pln['contract_under'], 'id="contractUnder"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_pln_cost_center">
					<label class="control-label" for="costCenterPLN">Cost Center:</label>
					<div class="controls">
						<?php echo @form_input('costCenter', set_value('costCenter', $pln['cost_center']), 'id="costCenterPLN" readonly="readonly"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			<!--
			<div class="span6">
				<div class="control-group">
					<label class="control-label" for="company">Company:</label>
					<div class="controls">
						<?php
						$dataCompany = get_instance()->core->view_all('t_mtr_company_merge', NULL, 'company_name');
						$companies = array('0' => '- Choose one -');
						foreach ($dataCompany as $company) {
							$companies[$company['company_id']] = $company['company_name'];
						}
						echo @form_dropdown('company', $companies, set_value('company', $pln['company_code']), 'id="company"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			-->
			
		</div>
		
		<div class="row-fluid">
			<div class="span12">
				<div class="control-group" id="form_pln_company_code">
					<label class="control-label" for="companyCodePLN">Company Code:</label>
					<div class="controls">
						<?php echo @form_input('companyCode', $pln['company_code'], 'id="companyCodePLN"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="control-group" id="form_pln_remarks">
			<label class="control-label" for="plnRemarks">Remarks:</label>
			<div class="controls">
				<?php echo @form_textarea('plnRemarks', set_value('plnRemarks', trim($pln['remarks'])), 'id="plnRemarks" class="span4" style="height:100px;"') ?>
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
			<div class="span4">
				<div class="control-group" id="form_permanent_genset">
					<label class="control-label" for="permanentGenset">Permanent Genset:</label>
					<div class="controls">
						<?php
						$permanentGenset = array('' => '- Choose one -', 0 => 'No', 1 => 'Yes');
						echo @form_dropdown('permanentGenset', $permanentGenset, set_value('permanentGenset', $pln['permanent_genset']), 'id="permanentGenset"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_genset_brand">
					<label class="control-label" for="gensetBrand">Genset Brand:</label>
					<div class="controls">
						<?php echo @form_input('gensetBrand', set_value('gensetBrand', trim($pln['genset_brand'])), 'id="gensetBrand"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_genset_capacity">
					<label class="control-label" for="gensetCapacity">Genset Capacity:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('gensetCapacity', set_value('gensetCapacity', $pln['genset_capacity']), 'id="gensetCapacity" class="input-small number"') ?>
							<span class="add-on">VA</span>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
				
			<div class="span8">
				<div class="control-group" id="form_genset_ownership">
					<label class="control-label" for="gensetOwnership">Genset Ownership:</label>
					<div class="controls">
						<?php
						$lookup = $this->core->get_lookup('genset_ownership');
						$options = array(0 => '- Choose one -');
						foreach ($lookup as $lo) {
							$options[$lo['id']] = $lo['desc'];
						}
						echo @form_dropdown('gensetOwnership', $options, set_value('gensetOwnership', $pln['genset_ownership']), 'id="gensetOwnership"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_tank_capacity_daily">
					<label class="control-label" for="tankCapacityDaily">Tank Capacity (Daily):</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('tankCapacityDaily', set_value('tankCapacityDaily', $pln['tank_capacity_daily']), 'id="tankCapacityDaily" class="input-small number"') ?>
							<span class="add-on">litre</span>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_tank_capacity_monthly">
					<label class="control-label" for="tankCapacityMonthly">Tank Capacity (Monthly):</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('tankCapacityMonthly', set_value('tankCapacityMonthly', $pln['tank_capacity_monthly']), 'id="tankCapacityMonthly" class="input-small number"') ?>
							<span class="add-on">litre</span>
						</div>
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
			<table id="tblAC" class="table table-bordered" style="width:auto">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Brand:</th>
						<th>Type / Model:</th>
						<th>Inverter Type:</th>
						<th>Capacity:</th>
						<th>SN Indoor Part:</th>
						<th>SN Outdoor Part:</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($this->uri->segment(2) == 'edit' && count($ac['detail']) > 0) {
					
						$ac_counter = 1;
						foreach ($ac['detail'] as $detail) {
					?>
					
					<tr id="acRow<?php echo $ac_counter ?>">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="ac.removeRow(<?php echo $ac_counter ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							echo form_hidden('acId[]', set_value('acId[]', $detail['ac_id']));
							
							$lookup = $this->core->get_lookup('ac_brand');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('brandAC[]', $options, set_value('brandAC[]', $detail['ac_brand_id']));
							?>
						</td>
						<td>
							<?php echo @form_input('typeAC[]', set_value('typeAC[]', $detail['model']), 'class="input-medium uppercase"') ?>
						</td>
						<td>
							<?php
							$inverterType = array(0 => '- Choose one -');
							$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'inverter_type', 'active' => 1), 'order');
							foreach ($lookup as $lo) {
								$inverterType[$lo['lookup_value']] = $lo['lookup_desc'];
							}
							echo @form_dropdown('inverterType[]', $inverterType, set_value('inverterType[]', $detail['inverter_type']));
							?>
						</td>
						<td>
							<?php
							$lookup = $this->core->get_lookup('ac_capacity');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('capacityAC[]', $options, set_value('capacityAC[]', $detail['ac_capacity_id']));
							?>
						</td>
						<!-- <td>
							<?php //echo @form_input('serialNumberAC[]', set_value('serialNumberAC', $detail['serial_number']), 'class="uppercase"') ?>
						</td> -->
						<td>
							<?php echo @form_input('snIndoorPart[]', set_value('snIndoorPart', $detail['sn_indoor_part']), 'class="input-medium uppercase"') ?>
						</td>
						<td>
							<?php echo @form_input('snOutdoorPart[]', set_value('snOutdoorPart', $detail['sn_outdoor_part']), 'class="input-medium uppercase"') ?>
						</td>
					</tr>
					<?php 
								$ac_counter++;
							}
						} else {
					?>
					
					<tr id="acRow1">
						<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="ac.removeRow(1)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
						<td>
							<?php
							echo form_hidden('acId[]', set_value('acId[]', ''));
							
							$lookup = $this->core->get_lookup('ac_brand');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('brandAC[]', $options, set_value('brandAC[]', ''));
							?>
						</td>
						<td>
							<?php echo @form_input('typeAC[]', set_value('typeAC[]', ''), 'class="input-medium uppercase"') ?>
						</td>
						<td>
							<?php
							$inverterType = array(0 => '- Choose one -');
							$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'inverter_type', 'active' => 1), 'order');
							foreach ($lookup as $lo) {
								$inverterType[$lo['lookup_value']] = $lo['lookup_desc'];
							}
							echo @form_dropdown('inverterType[]', $inverterType, set_value('inverterType[]', $detail['inverter_type']));
							?>
						</td>
						<td>
							<?php
							$lookup = $this->core->get_lookup('ac_capacity');
							$options = array(0 => '- Choose one -');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							echo @form_dropdown('capacityAC[]', $options, set_value('capacityAC[]', ''));
							?>
						</td>
						<td>
							<?php echo @form_input('snIndoorPart[]', set_value('snIndoorPart', ''), 'class="input-medium uppercase"') ?>
						</td>
						<td>
							<?php echo @form_input('snOutdoorPart[]', set_value('snOutdoorPart', ''), 'class="input-medium uppercase"') ?>
						</td>
					</tr>
					<?php } ?>
					
				</tbody>
				<tfoot>
					<tr>
						<td colspan="7" class="left"><a class="btn btn-success btn-small" href="javascript:void(0)" onclick="ac.addRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></a></td>
					</tr>
				</tfoot>
			</table>
		</div>

		<div class="separator bottom"></div>

		<div class="control-group" id="form_ac_remarks">
			<label class="control-label" for="acRemarks">Remarks:</label>							
			<div class="controls">
				<?php echo @form_textarea('acRemarks', set_value('acRemarks', trim($ac['remarks'])), 'id="acRemarks" class="span4" style="height:100px;"') ?>
			</div>
		</div>
		
	</div>
</div>

<?php echo form_close() ?>

<script>
document.getElementById('formCme').onsubmit = function() {
	
	currencyFormat.removeFormat();
	dateFormat.saveMode();
	
	var data = serialize(this);
	<?php if ($this->uri->segment(2) == 'add') { ?>
	data += '&cmd=ADD_CME';
	<?php } elseif ($this->uri->segment(2) == 'edit') { ?>
	data += '&cmd=EDIT_CME';
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
					// refresh content tab
					var tab = $('#siteInfoTab').tabs('getSelected');
					$('#siteInfoTab').tabs('update', {
						tab: tab,
						options: {
							href: document.URL + '?load_content=CME&site_id=' + document.getElementsByName('siteId')[0].value
						}
					});
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

<?php if ($this->uri->segment(2) == 'edit' && count($ac['detail']) > 0) { ?>
var acCounter = <?php echo $ac_counter ?>;
<?php } else { ?>
var acCounter = 2;
<?php } ?>

var ac = {
	counter: acCounter,
	addRow: function() {
		var html = '\
			<tr id="acRow' + this.counter + '">\
				<td class="left"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="ac.removeRow(' + this.counter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>\
				<td>\
					<input type="hidden" name="acId[]" />\
					\
					<?php $lookup = $this->core->get_lookup('ac_brand'); ?>\
					<select name="brandAC[]">\
						<option value="">- Choose one -</option>\
						<?php foreach ($lookup as $lo) { ?>\
							<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
						<?php } ?>\
						?>\
					</select>\
				</td>\
				<td>\
					<input type="text" name="typeAC[]" class="input-medium uppercase" />\
				</td>\
				<td>\
					<?php $lookup = $this->core->get_lookup('inverter_type') ?>\
					<select name="inverterType[]">\
						<option value="">- Choose one -</option>\
						<?php foreach ($lookup as $lo) { ?>\
							<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
						<?php } ?>\
					</select>\
				</td>\
				<td>\
					<?php $lookup = $this->core->get_lookup('ac_capacity') ?>\
					<select name="capacityAC[]">\
						<option value="">- Choose one -</option>\
						<?php foreach ($lookup as $lo) { ?>\
							<option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
						<?php } ?>\
					</select>\
				</td>\
				<td>\
					<input type="text" name="snIndoorPart[]" class="input-medium uppercase" />\
				</td>\
				<td>\
					<input type="text" name="snOutdoorPart[]" class="input-medium uppercase" />\
				</td>\
			</tr>\
		';
		
		$('#tblAC tbody').append(html);
		
		this.counter++;
		
		textUpperCase.init();
		clearAutoComplete.init();
	},
	removeRow: function(row) {
		var row = document.getElementById('acRow' + row);
		
		coolConfirmation('Are you sure you want to delete?', function() {
			row.parentNode.removeChild(row);
		});
	}
};

if (document.getElementById('shelterNeeded').value == 0 || document.getElementById('shelterNeeded').value == '') {
	formField.disable(['kepemilikanShelter', 'namaPemilikShelter', 'noTelpPemilikShelter', 'shelterType', 'shelterSizeL', 'shelterSizeW', 'shelterSizeH', 'shelterFabricator', 'shelterRemarks']);
}

document.getElementById('shelterNeeded').onchange = function() {
	if (this.value == 1) {
		formField.enable(['kepemilikanShelter', 'namaPemilikShelter', 'noTelpPemilikShelter', 'shelterType', 'shelterSizeL', 'shelterSizeW', 'shelterSizeH', 'shelterFabricator', 'shelterRemarks']);
	} else {
		formField.disable(['kepemilikanShelter', 'namaPemilikShelter', 'noTelpPemilikShelter', 'shelterType', 'shelterSizeL', 'shelterSizeW', 'shelterSizeH', 'shelterFabricator', 'shelterRemarks']);
	}
};

var financeToPLN = function() {
	var costCenter = document.getElementById('costCenter');
	var costCenterPLN = document.getElementById('costCenterPLN');
	var companyCode = document.getElementById('companyCode');
	var companyCodePLN = document.getElementById('companyCodePLN');
	
	if (costCenter != null && costCenter.value != '' && costCenterPLN.value == '') {
		costCenterPLN.value = costCenter.value;
	}
	
	if (companyCode != null && companyCode.value != 0 && companyCodePLN.value == '') {
		companyCodePLN.value = companyCode.value;
	}
};

numberFormat.init();
textUpperCase.init();
clearAutoComplete.init();
dateFormat.init();
financeToPLN();
doPreviledgePerfield();
</script>