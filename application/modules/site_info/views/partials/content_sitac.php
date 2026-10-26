<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<?php echo form_open('', 'id="formSitac" class="form-horizontal"') ?>

<?php echo form_hidden('siteId', set_value('siteId', '')) ?>

<div class="top-form-actions">
	<button class="btn btn-primary btn-small btn-icon glyphicons circle_ok"><i></i> Save</button>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">Space (Lahan)</h4>
	</div>
	
	<div class="widget-body">
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_space_land_ownership">
					<label class="control-label" for="kepemilikanLahan">Land Ownership:</label>
					<div class="controls">
						<?php
						$kepemilikanLahan = array('- Choose one -');
						$dataKepemilikanLahan = get_instance()->core->view_all('t_mtr_lookup', array('lookup_key' => 'land_ownership', 'active' => 1), 'lookup_desc');
						foreach ($dataKepemilikanLahan as $owner) {
							$kepemilikanLahan[$owner['lookup_value']] = $owner['lookup_desc'];
						}
						echo @form_dropdown('kepemilikanLahan', $kepemilikanLahan, set_value('kepemilikanLahan', $lahan['kepemilikan_lahan']), 'id="kepemilikanLahan"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_space_land_certificate_type">
					<label class="control-label" for="jenisSuratLahan">Land Certificate Type:</label>
					<div class="controls">
						<?php
						//$dataJenisSuratLahan = get_instance()->core->view_all('t_mtr_jenis_surat_lahan', array('status' => 1),'nama');
						$dataJenisSuratLahan = $this->core->get_lookup('jenis_surat_lahan');
						$jenisSuratLahan = array(0 => '- Choose one -');
						foreach ($dataJenisSuratLahan as $jenis) {
							$jenisSuratLahan[$jenis['id']] = $jenis['desc'];
						}
						echo @form_dropdown('jenisSuratLahan', $jenisSuratLahan, set_value('jenisSuratLahan', $lahan['jenis_surat_lahan']), 'id="jenisSuratLahan"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_space_no_ajb">
					<label class="control-label" for="noAJB">No. AJB/PKS:</label>
					<div class="controls">
						<?php echo @form_input('noAJB', set_value('noAJB', $lahan['no_ajb']), 'id="noAJB" class="uppercase"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_space_date_ajb">
					<label class="control-label" for="dateAJB">Date AJB/PKS:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('dateAJB', set_value('dateAJB', $lahan['date_ajb']), 'id="dateAJB" class="input-small date" placeholder="DD-MMM-YYYY"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button onclick="clearField('dateAJB')" type="button" class="btn">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_space_land_certificate_number">
					<label class="control-label" for="noSuratLahan">Land Certificate Number:</label>
					<div class="controls">
						<?php echo @form_input('noSuratLahan', set_value('noSuratLahan', $lahan['no_surat_lahan']), 'id="noSuratLahan" class="uppercase"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_space_land_size">
					<label class="control-label" for="luasLahan">Land Size:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('luasLahan', set_value('luasLahan', $lahan['luas_lahan']), 'id="luasLahan" class="input-mini number"') ?>
							<span class="add-on">M<sup>2</sup></span>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="control-group" id="form_space_road_access">
			<label class="control-label">Road Access (WxH):</label>
			<div class="controls">
				<div class="input-append">
					<?php echo @form_input('aksesJalanLebar', set_value('aksesJalanLebar', $lahan['akses_jalan_lbr']), 'id="aksesJalanLebar" class="input-mini number"') ?>
					<?php echo @form_input('aksesJalanPanjang', set_value('aksesJalanPanjang', $lahan['akses_jalan_pjg']), 'id="aksesJalanPanjang" class="input-mini number"') ?>
					<span class="add-on">M</span>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_space_sudah_balik_nama">
					<label class="control-label" for="sudahBalikNama">Sudah Balik Nama:</label>
					<div class="controls">
						<?php
						$sudahBalikNama = array('' => '- Choose one -', 0 => 'No', 1 => 'Yes');
						echo @form_dropdown('sudahBalikNama', $sudahBalikNama, set_value('sudahBalikNama', $lahan['sudah_balik_nama']), 'id="sudahBalikNama"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_space_new_number">
					<label class="control-label" for="noBaru">New Number:</label>
					<div class="controls">
						<?php echo @form_input('noBaru', set_value('noBaru', $lahan['no_baru']), 'id="noBaru" class="medium"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="control-group" id="form_space_lease_term">
			<label class="control-label" for="leaseTerm">Lease Term (year):</label>
			<div class="controls">
				<?php echo @form_input('leaseTerm', set_value('leaseTerm', $lahan['lease_term']), 'id="leaseTerm" class="input-mini just-number"') ?>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_space_lease_start_date">
					<label class="control-label" for="leaseStartDate">Lease Start Date:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('leaseStartDate', set_value('leaseStartDate', $lahan['lease_start_date']), 'id="leaseStartDate" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button class="btn" type="button" onclick="clearField('leaseStartDate')">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_space_lease_finish_date">
					<label class="control-label" for="leaseFinishDate">Lease Finish Date:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('leaseFinishDate', set_value('leaseFinishDate', $lahan['lease_finish_date']), 'id="leaseFinishDate" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button class="btn" type="button" onclick="clearField('leaseFinishDate')">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_space_name_land_ownership">
					<label class="control-label" for="namaPemilikLahan">Name of Land Ownership:</label>
					<div class="controls">
						<?php echo @form_input('namaPemilikLahan', set_value('namaPemilikLahan', trim($lahan['nama_pemilik_lahan'])), 'id="namaPemilikLahan" class="medium"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_space_phone_land_ownership">
					<label class="control-label" for="noTelpPemilikLahan">Phone of Land Ownership:</label>
					<div class="controls">
						<?php echo @form_input('noTelpPemilikLahan', set_value('noTelpPemilikLahan', $lahan['no_telp_pemilik_lahan']), 'id="noTelpPemilikLahan" class=""') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_space_contact_person_land">
					<label class="control-label" for="contactPersonLahan">Contact Person of Land:</label>
					<div class="controls">
						<?php echo @form_input('contactPersonLahan', set_value('contactPersonLahan', trim($lahan['contact_person_lahan'])), 'id="contactPersonLahan" class="medium"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_space_phone_contact_person">
					<label class="control-label" for="noTelpContactPerson">Phone of Contact Person:</label>
					<div class="controls">
						<?php echo @form_input('noTelpContactPerson', set_value('noTelpContactPerson', $lahan['no_telp_contact_person']), 'id="noTelpContactPerson" class=""') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="control-group" id="form_space_remarks">
			<label class="control-label" for="spaceRemarks">Remarks:</label>
			<div class="controls">
				<?php echo @form_textarea('spaceRemarks', set_value('spaceRemarks', trim($lahan['remarks'])), 'id="spaceRemarks" class="span4" style="height:100px;"') ?>
			</div>
		</div>
		
	</div>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">IW, Retribution &amp; SMG Property</h4>
	</div>
	
	<div class="widget-body">
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_smg_property_status">
					<label class="control-label" for="smgStatus">SMG Property Status:</label>
					<div class="controls">
						<?php
						$status = array('' => '- Choose one -', 0 => 'No', 1 => 'Yes');
						echo @form_dropdown('smgStatus', $status, set_value('smgStatus', $lahan['status']), 'id="smgStatus"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_smg_property_company">
					<label class="control-label" for="smgCompany">SMG Property Company:</label>
					<div class="controls">
						<?php echo @form_input('smgCompany', set_value('smgCompany', trim($lahan['company'])), 'id="smgCompany" class="medium"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_iw_needed">
					<label class="control-label" for="iwNeeded">IW Needed:</label>
					<div class="controls">
						<?php
						$iwNeeded = array('' => '- Choose one -', 0 => 'No', 1 => 'Yes');
						echo @form_dropdown('iwNeeded', $iwNeeded, set_value('iwNeeded', $perijinan['iw_needed']), 'id="iwNeeded"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
				
			<div class="span8">
				<div class="control-group" id="form_iw_date">
					<label class="control-label" for="iwDate">IW Date:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('iwDate', set_value('iwDate', $perijinan['iw_date']), 'id="iwDate" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button onclick="clearField('iwDate')" type="button" class="btn">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_retribution_needed">
					<label class="control-label" for="retribusi">Retribution Needed:</label>
					<div class="controls">
						<?php
						$retribusi = array('' => '- Choose one -', 0 => 'No', 1 => 'Yes');
						echo @form_dropdown('retribusi', $retribusi, set_value('retribusi', $perijinan['retribusi']), 'id="retribusi"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_retribution_type">
					<label class="control-label" for="retributionType">Retribution Type:</label>
					<div class="controls">
						<?php
						$retributionType = array(0 => '- Choose one -');
						$dataRetributionType = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'retribution_type', 'active' => 1), 'order');
						foreach ($dataRetributionType as $type) {
							$retributionType[$type['lookup_value']] = $type['lookup_desc'];
						}
						echo @form_dropdown('retributionType', $retributionType, set_value('retributionType', $perijinan['tipe_retribusi']), 'id="retributionType"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>				
			</div>
		</div>
		
		<div class="control-group" id="form_retribution_fee">
			<label class="control-label" for="biayaRetribusi">Retribution Fee:</label>
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on">IDR</span>
					<?php echo @form_input('biayaRetribusi', set_value('biayaRetribusi', trim($perijinan['biaya_retribusi'])), 'id="biayaRetribusi" class="input-small currency"') ?>
				</div>
			</div>
			<div class="separator bottom"></div>
		</div>
		
		<div class="control-group" id="form_iw_remarks">
			<label class="control-label" for="iwRemarks">Remarks:</label>
			<div class="controls">
				<?php echo @form_textarea('iwRemarks', set_value('iwRemarks', trim($perijinan['iw_remarks'])), 'id="iwRemarks" class="span4" style="height:100px;"') ?>
			</div>
		</div>
		
	</div>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">HO Info</h4>
	</div>
	
	<div class="widget-body">
		
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_ho_needed">
					<label class="control-label" for="hoNeeded">HO Needed:</label>
					<div class="controls">
						<?php
						$hoNeeded = array('' => '- Choose one -', 0 => 'No', 1 => 'Yes');
						echo @form_dropdown('hoNeeded', $hoNeeded, set_value('hoNeeded', $perijinan['ho_needed']), 'id="hoNeeded"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_ho_type">
					<label class="control-label" for="hoType">HO Type:</label>
					<div class="controls">
						<?php
						$hoType = array(0 => '- Choose one -');
						$dataHoType = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'ho_type', 'active' => 1), 'order');
						foreach ($dataHoType as $type) {
							$hoType[$type['lookup_value']] = $type['lookup_desc'];
						}
						echo @form_dropdown('hoType', $hoType, set_value('hoType', $perijinan['ho_type']), 'id="hoType"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="control-group" id="form_ho_number">
			<label class="control-label" for="hoNumber">HO Number:</label>
			<div class="controls">
				<?php echo @form_input('hoNumber', set_value('hoNumber', trim($perijinan['ho_number'])), 'id="hoNumber" class="uppercase"') ?>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_ho_date">
					<label class="control-label" for="hoDate">HO Date:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('hoDate', set_value('hoDate', $perijinan['ho_date']), 'id="hoDate" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button onclick="clearField('hoDate')" type="button" class="btn">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
				
			<div class="span8">
				<div class="control-group" id="form_ho_expired">
					<label class="control-label" for="hoExpired">HO Expired:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('hoExpired', set_value('hoExpired', $perijinan['ho_expired']), 'id="hoExpired" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button onclick="clearField('hoExpired')" type="button" class="btn">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="control-group" id="form_ho_remarks">
			<label class="control-label" for="hoRemarks">Remarks:</label>
			<div class="controls">
				<?php echo @form_textarea('hoRemarks', set_value('hoRemarks', trim($perijinan['ho_remarks'])), 'id="hoRemarks" class="span4" style="height:100px;"') ?>
			</div>
		</div>
		
	</div>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">PBB</h4>
	</div>
	
	<div class="widget-body">
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_pbb_needed">
					<label class="control-label" for="pbbNeeded">PBB Needed:</label>
					<div class="controls">
						<?php
						$pbbNeeded = array('' => '- Choose one -', 0 => 'No', 1 => 'Yes');
						echo @form_dropdown('pbbNeeded', $pbbNeeded, set_value('pbbNeeded', $perijinan['pbb_needed']), 'id="pbbNeeded"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
				
			<div class="span8">
				<div class="control-group" id="form_pbb_number">
					<label class="control-label" for="nomorPBB">PBB Number (NOP):</label>
					<div class="controls">
						<?php echo @form_input('nomorPBB', set_value('nomorPBB', $perijinan['nomor_pbb']), 'id="nomorPBB" class="uppercase"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_pbb_atas_nama">
					<label class="control-label" for="atasNama">Atas Nama:</label>
					<div class="controls">
						<?php echo @form_input('atasNama', set_value('atasNama', trim($perijinan['atas_nama'])), 'id="atasNama"') ?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
				
			<div class="span8">
				<div class="control-group" id="form_pbb_due_date">
					<label class="control-label" for="jatuhTempo">Due Date:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('jatuhTempo', set_value('jatuhTempo', $perijinan['jatuh_tempo']), 'id="jatuhTempo" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button onclick="clearField('jatuhTempo')" type="button" class="btn">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_pbb_nilai">
					<label class="control-label" for="nilaiPBB">Nilai PBB:</label>
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">IDR</span>
							<?php echo @form_input('nilaiPBB', set_value('nilaiPBB', $perijinan['nilai_pbb']), 'id="nilaiPBB" class="input-small currency"') ?>
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_pbb_pic">
					<label class="control-label" for="picPBB">PIC PBB:</label>
					<div class="controls">
						<?php
						$dataPICPBB = get_instance()->core->view_all('t_mtr_pic_pbb', array('status' => 1), 'descr');
						$picPBB = array('0' => '- Choose one -');
						foreach ($dataPICPBB as $pic) {
							$picPBB[$pic['pic_pbb_id']] = $pic['descr'];
						}
						echo @form_dropdown('picPBB', $picPBB, set_value('picPBB', $perijinan['pic_pbb']), 'id="picPBB"');
						?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="control-group" id="form_pbb_remarks">
			<label class="control-label" for="pbbRemarks">Remarks:</label>
			<div class="controls">
				<?php echo @form_textarea('pbbRemarks', set_value('pbbRemarks', trim($perijinan['pbb_remarks'])), 'id="pbbRemarks" class="span4" style="height:100px;"') ?>
			</div>
		</div>
			
	</div>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">IMB</h4>
	</div>
	
	<div class="widget-body">
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_imb_needed">
					<label class="control-label" for="imbNeeded">IMB Needed:</label>
					<div class="controls">
						<?php
						$imbNeeded = array('' => '- Choose one -', 0 => 'No', 1 => 'Yes');
						echo @form_dropdown('imbNeeded', $imbNeeded, set_value('imbNeeded', $perijinan['imb_needed']), 'id="imbNeeded"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_imb_type">
					<label class="control-label" for="imbType">IMB Type:</label>
					<div class="controls">
						<?php
						$imbType = array(0 => '- Choose one -');
						$dataImbType = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'imb_type', 'active' => 1), 'order');
						foreach ($dataImbType as $type) {
							$imbType[$type['lookup_value']] = $type['lookup_desc'];
						}
						echo @form_dropdown('imbType', $imbType, set_value('imbType', $perijinan['imb_type']), 'id="imbType"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="control-group" id="form_imb_number">
			<label class="control-label" for="nomorIMB">IMB Number:</label>
			<div class="controls">
				<?php echo @form_input('nomorIMB', set_value('nomorIMB', trim($perijinan['nomor_imb'])), 'id="nomorIMB" class="uppercase"') ?>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_imb_date">
					<label class="control-label" for="imbDate">Date:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('imbDate', set_value('imbDate', $perijinan['date']), 'id="imbDate" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button onclick="clearField('imbDate')" type="button" class="btn">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
				
			<div class="span8">
				<div class="control-group" id="form_imb_expired">
					<label class="control-label" for="imbExpired">Expired:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('imbExpired', set_value('imbExpired', $perijinan['expired']), 'id="imbExpired" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button onclick="clearField('imbExpired')" type="button" class="btn">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="control-group" id="form_imb_remarks">
			<label class="control-label" for="imbRemarks">Remarks:</label>
			<div class="controls">
				<?php echo @form_textarea('imbRemarks', set_value('imbRemarks', trim($perijinan['imb_remarks'])), 'id="imbRemarks" class="span4" style="height:100px;"') ?>
			</div>
		</div>
		
	</div>
</div>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading">Rekomendasi Ketinggian Tower (RKT)</h4>
	</div>
	
	<div class="widget-body">
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_rkt_needed">
					<label class="control-label" for="rktNeeded">RKT Needed:</label>
					<div class="controls">
						<?php
						$rktNeeded = array('' => '- Choose one -', 0 => 'No', 1 => 'Yes');
						echo @form_dropdown('rktNeeded', $rktNeeded, set_value('rktNeeded', $perijinan['rkt_needed']), 'id="rktNeeded"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_rkt_type">
					<label class="control-label" for="rktType">RKT Type:</label>
					<div class="controls">
						<?php
						$rktType = array(0 => '- Choose one -');
						$dataRktType = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'rkt_type', 'active' => 1), 'order');
						foreach ($dataRktType as $type) {
							$rktType[$type['lookup_value']] = $type['lookup_desc'];
						}
						echo @form_dropdown('rktType', $rktType, set_value('rktType', $perijinan['rkt_type']), 'id="rktType"');
						?>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>
		
		<div class="control-group" id="form_rkt_number">
			<label class="control-label" for="rktNumber">RKT Number:</label>
			<div class="controls">
				<?php echo @form_input('rktNumber', set_value('rktNumber', trim($perijinan['rkt_number'])), 'id="rktNumber" class="uppercase"') ?>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span4">
				<div class="control-group" id="form_rkt_date">
					<label class="control-label" for="rktDate">RKT Date:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('rktDate', set_value('rktDate', $perijinan['rkt_date']), 'id="rktDate" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button onclick="clearField('rktDate')" type="button" class="btn">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
			
			<div class="span8">
				<div class="control-group" id="form_rkt_expired">
					<label class="control-label" for="rktExpired">RKT Expired:</label>
					<div class="controls">
						<div class="input-append">
							<?php echo @form_input('rktExpired', set_value('rktExpired', $perijinan['rkt_expired']), 'id="rktExpired" class="date input-small" placeholder="DD-MMM-YYYY"') ?>
							<span class="add-on"><i class="icon-calendar"></i></span>
							<!-- <button onclick="clearField('rktExpired')" type="button" class="btn">Clear</button> -->
						</div>
					</div>
					<div class="separator bottom"></div>
				</div>
			</div>
		</div>

		<div class="control-group" id="form_rkt_remarks">
			<label class="control-label" for="remarks">Remarks:</label>
			<div class="controls">
				<?php echo @form_textarea('remarks', set_value('remarks', trim($perijinan['remarks'])), 'id="remarks" class="span4" style="height:100px;"') ?>
			</div>
		</div>
		
	</div>
</div>

<?php echo form_close() ?>

<script>
document.getElementById('formSitac').onsubmit = function() {
	
	currencyFormat.removeFormat();
	dateFormat.saveMode();
	
	var data = serialize(this);
	<?php if ($this->uri->segment(2) == 'add') { ?>
	data += '&cmd=ADD_SITAC';
	<?php } elseif ($this->uri->segment(2) == 'edit') { ?>
	data += '&cmd=EDIT_SITAC';
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
				coolNotification(json['message'],  'success', function() {
					// refresh content tab
					var tab = $('#siteInfoTab').tabs('getSelected');
					$('#siteInfoTab').tabs('update', {
						tab: tab,
						options: {
							href: document.URL + '?load_content=SITAC&site_id=' + document.getElementsByName('siteId')[0].value
						}
					});
				});
			}
			
			window.scroll(0, 0);
			
			currencyFormat.init();
			dateFormat.defaultMode();
		},
		complete: function() {
			siteOverlay.remove();
		}
	});
	
	return false;
};

if (document.getElementById('iwNeeded').value == 0 || document.getElementById('iwNeeded').value == '') {
	formField.disable(['iwDate']);
}

document.getElementById('iwNeeded').onchange = function() {
	if (this.value == 1) {
		formField.enable(['iwDate']);
	} else {
		formField.disable(['iwDate']);
	}
};

if (document.getElementById('retribusi').value == 0 || document.getElementById('retribusi').value == '') {
	formField.disable(['retributionType', 'biayaRetribusi']);
}

document.getElementById('retribusi').onchange = function() {
	if (this.value == 1) {
		formField.enable(['retributionType', 'biayaRetribusi']);
	} else {
		formField.disable(['retributionType', 'biayaRetribusi']);
	}
};

if (document.getElementById('hoNeeded').value == 0 || document.getElementById('hoNeeded').value == '') {
	formField.disable(['hoNumber', 'hoType', 'hoDate', 'hoExpired', 'hoRemarks']);
}

document.getElementById('hoNeeded').onchange = function() {
	if (this.value == 1) {
		formField.enable(['hoNumber', 'hoType', 'hoDate', 'hoExpired', 'hoRemarks']);
	} else {
		formField.disable(['hoNumber', 'hoType', 'hoDate', 'hoExpired', 'hoRemarks']);
	}
};

if (document.getElementById('pbbNeeded').value == 0 || document.getElementById('pbbNeeded').value == '') {
	formField.disable(['nomorPBB', 'atasNama', 'jatuhTempo', 'nilaiPBB', 'picPBB', 'pbbRemarks']);
}

document.getElementById('pbbNeeded').onchange = function() {
	if (this.value == 1) {
		formField.enable(['nomorPBB', 'atasNama', 'jatuhTempo', 'nilaiPBB', 'picPBB', 'pbbRemarks']);
	} else {
		formField.disable(['nomorPBB', 'atasNama', 'jatuhTempo', 'nilaiPBB', 'picPBB', 'pbbRemarks']);
	}
};

if (document.getElementById('imbNeeded').value == 0 || document.getElementById('imbNeeded').value == '') {
	formField.disable(['imbType', 'nomorIMB', 'imbDate', 'imbExpired', 'imbRemarks']);
}

document.getElementById('imbNeeded').onchange = function() {
	if (this.value == 1) {
		formField.enable(['imbType', 'nomorIMB', 'imbDate', 'imbExpired', 'imbRemarks']);
	} else {
		formField.disable(['imbType', 'nomorIMB', 'imbDate', 'imbExpired', 'imbRemarks']);
	}
};

if (document.getElementById('rktNeeded').value == 0 || document.getElementById('rktNeeded').value == '') {
	formField.disable(['rktType', 'rktNumber', 'rktDate', 'rktExpired', 'remarks']);
}

document.getElementById('rktNeeded').onchange = function() {
	if (this.value == 1) {
		formField.enable(['rktType', 'rktNumber', 'rktDate', 'rktExpired', 'remarks']);
	} else {
		formField.disable(['rktType', 'rktNumber', 'rktDate', 'rktExpired', 'remarks']);
	}
};

numberFormat.init();
justNumberFormat.init();
currencyFormat.init();
clearAutoComplete.init();
textUpperCase.init();
dateFormat.init();
doPreviledgePerfield();
</script>