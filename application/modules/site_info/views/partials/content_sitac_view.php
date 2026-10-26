<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<div class="form-horizontal">

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">Space (Lahan)</h4>
		</div>
		
		<div class="widget-body">
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_space_land_ownership">
						<label class="control-label" for="kepemilikanLahan">Land Ownership:</label>
						<div class="controls">
							<?php $options = $this->core->get_lookup_options('land_ownership') ?>
							<span class="input-label"><?php echo @$options[$lahan['kepemilikan_lahan']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_space_land_certificate_type">
						<label class="control-label" for="jenisSuratLahan">Land Certificate Type:</label>
						<div class="controls">
							<?php
							$dataJenisSuratLahan = $this->core->get_lookup('jenis_surat_lahan');
							$jenisSuratLahan = array(0 => '');
							foreach ($dataJenisSuratLahan as $jenis) {
								$jenisSuratLahan[$jenis['id']] = $jenis['desc'];
							}
							?>
							<span class="input-label"><?php echo @$jenisSuratLahan[$lahan['jenis_surat_lahan']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_space_no_ajb">
						<label class="control-label" for="noAJB">No. AJB/PKS:</label>
						<div class="controls">
							<span class="input-label"><?php echo @$lahan['no_ajb'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_space_date_ajb">
						<label class="control-label" for="dateAJB">Date AJB/PKS:</label>
						<div class="controls">
							<span class="input-label"><?php echo isset($lahan['date_ajb']) ? date('d-M-Y', strtotime($lahan['date_ajb'])) : '' ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_space_land_certificate_number">
						<label class="control-label" for="noSuratLahan">Land Certificate Number:</label>
						<div class="controls">
							<span class="input-label"><?php echo @$lahan['no_surat_lahan'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_space_land_size">
						<label class="control-label" for="luasLahan">Land Size:</label>
						<div class="controls">
							<span class="input-label"><?php echo @$lahan['luas_lahan'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="control-group" id="form_space_road_access">
				<label class="control-label">Road Access (WxH):</label>
				<div class="controls">
					<span class="input-label"><?php echo @$lahan['akses_jalan_lbr'].' x '.@$lahan['akses_jalan_pjg'] ?> M<sup>2</sup></span>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_space_sudah_balik_nama">
						<label class="control-label" for="sudahBalikNama">Sudah Balik Nama:</label>
						<div class="controls">
							<?php
							$sudahBalikNama = array('' => '', 0 => 'No', 1 => 'Yes');
							?>
							<span class="input-label"><?php echo @$sudahBalikNama[$lahan['sudah_balik_nama']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_space_new_number">
						<label class="control-label" for="noBaru">New Number:</label>
						<div class="controls">
							<span class="input-label"><?php echo @$lahan['no_baru'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="control-group" id="form_space_lease_term">
				<label class="control-label" for="leaseTerm">Lease Term (year):</label>
				<div class="controls">
					<span class="input-label"><?php echo @$lahan['lease_term'] ?></span>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_space_lease_start_date">
						<label class="control-label" for="leaseStartDate">Lease Start Date:</label>
						<div class="controls">
							<span class="input-label"><?php echo isset($lahan['lease_start_date']) ? date('d-M-Y', strtotime($lahan['lease_start_date'])) : '' ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_space_lease_finish_date">
						<label class="control-label" for="leaseFinishDate">Lease Finish Date:</label>
						<div class="controls">
							<span class="input-label"><?php echo isset($lahan['lease_finish_date']) ? date('d-M-Y', strtotime($lahan['lease_finish_date'])) : '' ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_space_name_land_ownership">
						<label class="control-label" for="namaPemilikLahan">Name of Land Ownership:</label>
						<div class="controls">
							<span class="input-label"><?php echo @$lahan['nama_pemilik_lahan'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_space_phone_land_ownership">
						<label class="control-label" for="noTelpPemilikLahan">Phone of Land Ownership:</label>
						<div class="controls">
							<span class="input-label"><?php echo @$lahan['no_telp_pemilik_lahan'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_space_contact_person_land">
						<label class="control-label" for="contactPersonLahan">Contact Person of Land:</label>
						<div class="controls">
							<span class="input-label"><?php echo @$lahan['contact_person_lahan'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_space_phone_contact_person">
						<label class="control-label" for="noTelpContactPerson">Phone of Contact Person:</label>
						<div class="controls">
							<span class="input-label"><?php echo @$lahan['no_telp_contact_person'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_space_remarks">
						<label class="control-label" for="spaceRemarks">Remarks:</label>
						<div class="controls">
							<span class="input-label"><?php echo @$lahan['remarks'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6"></div>
			</div>
			
		</div>
	</div>

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">IW, Retribution &amp; SMG Property</h4>
		</div>
		
		<div class="widget-body">
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_smg_property_status">
						<label class="control-label" for="smgStatus">SMG Property Status:</label>
						<div class="controls">
							<?php
							$status = array('' => '', 0 => 'No', 1 => 'Yes');
							?>
							<span class="input-label"><?php echo @$status[$lahan['status']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_smg_property_company">
						<label class="control-label" for="smgCompany">SMG Property Company:</label>
						<div class="controls">
							<span class="input-label"><?php echo @$lahan['company'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_iw_needed">
						<label class="control-label" for="iwNeeded">IW Needed:</label>
						<div class="controls">
							<?php
							$iwNeeded = array('' => '', 0 => 'No', 1 => 'Yes');
							?>
							<span class="input-label"><?php echo @$iwNeeded[$perijinan['iw_needed']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
					
				<div class="span6">
					<div class="control-group" id="form_iw_date">
						<label class="control-label" for="iwDate">IW Date:</label>
						<div class="controls">
							<span class="input-label"><?php echo isset($perijinan['iw_date']) ? date('d-M-Y', strtotime($perijinan['iw_date'])) : '' ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_retribution_needed">
						<label class="control-label" for="retribusi">Retribution Needed:</label>
						<div class="controls">
							<?php
							$retribusi = array('' => '', 0 => 'No', 1 => 'Yes');
							?>
							<span class="input-label"><?php echo @$retribusi[$perijinan['retribusi']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_retribution_type">
						<label class="control-label" for="retributionType">Retribution Type:</label>
						<div class="controls">
							<?php $options = $this->core->get_lookup_options('retribution_type') ?>
							<span class="input-label"><?php echo @$options[$perijinan['tipe_retribusi']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>				
				</div>
			</div>
			
			<div class="control-group" id="form_retribution_fee">
				<label class="control-label" for="biayaRetribusi">Retribution Fee:</label>
				<div class="controls">
					<div class="input-prepend">
						<span class="input-label">IDR <?php echo @$perijinan['biaya_retribusi'] ?></span>
					</div>
				</div>
				<div class="separator bottom"></div>
			</div>
			
			<div class="control-group" id="form_iw_remarks">
				<label class="control-label" for="iwRemarks">Remarks:</label>
				<div class="controls">
					<span class="input-label"><?php echo @$perijinan['iw_remarks'] ?></span>
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
				<div class="span6">
					<div class="control-group" id="form_ho_needed">
						<label class="control-label" for="hoNeeded">HO Needed:</label>
						<div class="controls">
							<?php
							$hoNeeded = array('' => '', 0 => 'No', 1 => 'Yes');
							?>
							<span class="input-label"><?php echo @$hoNeeded[$perijinan['ho_needed']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_ho_type">
						<label class="control-label" for="hoType">HO Type:</label>
						<div class="controls">
							<?php
							$options = $this->core->get_lookup_options('ho_type');
							?>
							<span class="input-label"><?php echo @$options[$perijinan['ho_type']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="control-group" id="form_ho_number">
				<label class="control-label" for="hoNumber">HO Number:</label>
				<div class="controls">
					<span class="input-label"><?php echo @$perijinan['ho_number'] ?></span>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_ho_date">
						<label class="control-label" for="hoDate">HO Date:</label>
						<div class="controls">
							<span class="input-label"><?php echo isset($perijinan['ho_date']) ? date('d-M-Y', strtotime($perijinan['ho_date'])) : '' ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
					
				<div class="span6">
					<div class="control-group" id="form_ho_expired">
						<label class="control-label" for="hoExpired">HO Expired:</label>
						<div class="controls">
							<span class="input-label"><?php echo isset($perijinan['ho_expired']) ? date('d-M-Y', strtotime($perijinan['ho_expired'])) : '' ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="control-group" id="form_ho_remarks">
				<label class="control-label" for="hoRemarks">Remarks:</label>
				<div class="controls">
					<span class="input-label"><?php echo @$perijinan['ho_remarks'] ?></span>
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
				<div class="span6">
					<div class="control-group" id="form_pbb_needed">
						<label class="control-label" for="pbbNeeded">PBB Needed:</label>
						<div class="controls">
							<?php
							$pbbNeeded = array('' => '', 0 => 'No', 1 => 'Yes');
							?>
							<span class="input-label"><?php echo @$pbbNeeded[$perijinan['pbb_needed']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
					
				<div class="span6">
					<div class="control-group" id="form_pbb_number">
						<label class="control-label" for="nomorPBB">PBB Number (NOP):</label>
						<div class="controls">
							<span class="input-label"><?php echo @$perijinan['nomor_pbb'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_pbb_atas_nama">
						<label class="control-label" for="atasNama">Atas Nama:</label>
						<div class="controls">
							<span class="input-label"><?php echo @$perijinan['atas_nama'] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
					
				<div class="span6">
					<div class="control-group" id="form_pbb_due_date">
						<label class="control-label" for="jatuhTempo">Due Date:</label>
						<div class="controls">
							<span class="input-label"><?php echo isset($perijinan['jatuh_tempo']) ? date('d-M-Y', strtotime($perijinan['jatuh_tempo'])) : '' ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_pbb_nilai">
						<label class="control-label" for="nilaiPBB">Nilai PBB:</label>
						<div class="controls">
							<span class="input-label">IDR <?php echo @number_format($perijinan['nilai_pbb'], 2) ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_pbb_pic">
						<label class="control-label" for="picPBB">PIC PBB:</label>
						<div class="controls">
							<?php
							$dataPICPBB = get_instance()->core->view_all('t_mtr_pic_pbb', array('status' => 1), 'descr');
							$picPBB = array(0 => '');
							foreach ($dataPICPBB as $pic) {
								$picPBB[$pic['pic_pbb_id']] = $pic['descr'];
							}
							?>
							<span class="input-label"><?php echo @$picPBB[$perijinan['pic_pbb']] ?></span>
						</div>
					</div>
				</div>
			</div>
			
			<div class="control-group" id="form_pbb_remarks">
				<label class="control-label" for="pbbRemarks">Remarks:</label>
				<div class="controls">
					<span class="input-label"><?php echo @$perijinan['pbb_remarks'] ?></span>
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
				<div class="span6">
					<div class="control-group" id="form_imb_needed">
						<label class="control-label" for="imbNeeded">IMB Needed:</label>
						<div class="controls">
							<?php
							$imbNeeded = array('' => '', 0 => 'No', 1 => 'Yes');
							?>
							<span class="input-label"><?php echo @$imbNeeded[$perijinan['imb_needed']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_imb_type">
						<label class="control-label" for="imbType">IMB Type:</label>
						<div class="controls">
							<?php
							$options = $this->core->get_lookup_options('imb_type');
							?>
							<span class="input-label"><?php echo @$options[$perijinan['imb_type']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="control-group" id="form_imb_number">
				<label class="control-label" for="nomorIMB">IMB Number:</label>
				<div class="controls">
					<span class="input-label"><?php echo @$perijinan['nomor_imb'] ?></span>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_imb_date">
						<label class="control-label" for="imbDate">Date:</label>
						<div class="controls">
							<span class="input-label"><?php echo isset($perijinan['date']) ? date('d-M-Y', strtotime($perijinan['date'])) : '' ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
					
				<div class="span6">
					<div class="control-group" id="form_imb_expired">
						<label class="control-label" for="imbExpired">Expired:</label>
						<div class="controls">
							<span class="input-label"><?php echo isset($perijinan['expired']) ? date('d-M-Y', strtotime($perijinan['expired'])) : '' ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="control-group" id="form_imb_remarks">
				<label class="control-label" for="imbRemarks">Remarks:</label>
				<div class="controls">
					<span class="input-label"><?php echo @$perijinan['imb_remarks'] ?></span>
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
				<div class="span6">
					<div class="control-group" id="form_rkt_needed">
						<label class="control-label" for="rktNeeded">RKT Needed:</label>
						<div class="controls">
							<?php
							$rktNeeded = array('' => '', 0 => 'No', 1 => 'Yes');
							?>
							<span class="input-label"><?php echo @$rktNeeded[$perijinan['rkt_needed']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_rkt_type">
						<label class="control-label" for="rktType">RKT Type:</label>
						<div class="controls">
							<?php
							$options = $this->core->get_lookup_options('rkt_type');
							?>
							<span class="input-label"><?php echo @$options[$perijinan['rkt_type']] ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>
			
			<div class="control-group" id="form_rkt_number">
				<label class="control-label" for="rktNumber">RKT Number:</label>
				<div class="controls">
					<span class="input-label"><?php echo @$perijinan['rkt_number'] ?></span>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group" id="form_rkt_date">
						<label class="control-label" for="rktDate">RKT Date:</label>
						<div class="controls">
							<span class="input-label"><?php echo isset($perijinan['rkt_date']) ? date('d-M-Y', strtotime($perijinan['rkt_date'])) : '' ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
				
				<div class="span6">
					<div class="control-group" id="form_rkt_expired">
						<label class="control-label" for="rktExpired">RKT Expired:</label>
						<div class="controls">
							<span class="input-label"><?php echo isset($perijinan['rkt_expired']) ? date('d-M-Y', strtotime($perijinan['rkt_expired'])) : '' ?></span>
						</div>
						<div class="separator bottom"></div>
					</div>
				</div>
			</div>

			<div class="control-group" id="form_rkt_remarks">
				<label class="control-label" for="remarks">Remarks:</label>
				<div class="controls">
					<span class="input-label"><?php echo @$perijinan['remarks'] ?></span>
				</div>
			</div>
			
		</div>
	</div>
	
</div>

<script>
doPreviledgePerfield();
</script>