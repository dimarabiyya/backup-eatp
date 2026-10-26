<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<!DOCTYPE html>
<html>
<head>
	<title>Print Data Optimization</title>
	<link href="<?php echo base_url('/file/template/smashing/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" />
</head>
<body>
<!-- eatp-no-theme: halaman cetak, tidak diberi tema aplikasi -->

<div id="print-area">
	<style>
	.table-print { width:100%; border-collapse:collapse; font-family:"Helvetica Neue",Helvetica,Arial,sans-serif; }
	.table-print .print-header { border:1px solid #000000; font-size:11px; font-weight:bold; text-align:center; background:#cccccc; }
	.table-print td { padding:4px; font-size:11px; color:#000000; border:1px solid #000000; }
	.table-print td.title { font-size:16px; text-align:center; font-weight:bold; }
	.table-print td.center { text-align:center; }
	.table-print td.right { text-align:right; }
	.table-print td.strong { font-weight:bold; }
	@media print
	{
	.noprint { display: none; }
	}
	</style>
	
	<table class="table-print">
		<tr>
			<td colspan="9" class="title">Site Visit Form to check and rectify RF Antenna Physical Adjustments</td>
		</tr>
		<tr>
			<td colspan="9">&nbsp;</td>
		</tr>
		<tr>
			<td class="print-header">Site ID</td>
			<td class="print-header">Site Name</td>
			<td class="print-header">Region</td>
			<td class="print-header">City</td>
			<td class="print-header">RF Cluster ID</td>
			<td class="print-header">Latitude</td>
			<td class="print-header">Longitude</td>
			<td class="print-header">Phase</td>
			<td class="print-header">Site Type</td>
		</tr>
		<tr>
			<td class="center"><?php echo $visit['site_code'] ?></td>
			<td class="center"><?php echo $visit['site_name'] ?></td>
			<td class="center"><?php echo $visit['region_name'] ?></td>
			<td class="center"><?php echo $visit['city'] ?></td>
			<td class="center"><?php echo $visit['rf_cluster_id'] ?></td>
			<td class="center"><?php echo $visit['latitude'] ?></td>
			<td class="center"><?php echo $visit['longitude'] ?></td>
			<td class="center"><?php echo $visit['phase'] ?></td>
			<td class="center"><?php echo $visit['site_type'] ?></td>
		</tr>
		<tr>
			<td colspan="3">Site Address</td>
			<td colspan="6"><?php echo $visit['site_address'] ?></td>
		</tr>
		<tr>
			<td colspan="3">Date &amp; Time of Visit</td>
			<td colspan="6">
				<?php echo date('d-M-Y', strtotime($visit['visit_date'])) ?>
				<?php echo isset($visit['visit_time']) ? date('H:i a', strtotime($visit['visit_time'])) : '' ?>
			</td>
		</tr>
		<tr>
			<td colspan="3">Reason for Change requested</td>
			<td colspan="6"><?php echo $visit['reason'] ?></td>
		</tr>
		<tr>
			<td colspan="3"><?php echo $visit['vendor_name'] != '' ? $visit['vendor_name'] : 'Vendor' ?> Engineer Name & Contact No.</td>
			<td colspan="4"><?php echo $visit['vendor_engineer'].', phone: '.$visit['vendor_engineer_contact_no'] ?></td>
			<td>Sign</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="3"><?php echo $visit['vendor_name'] != '' ? $visit['vendor_name'] : 'Vendor' ?> Rigger Name & Contact No.</td>
			<td colspan="4"><?php echo $visit['vendor_rigger'].', phone: '.$visit['vendor_rigger_contact_no'] ?></td>
			<td>Sign</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="3">SF Engineer Name & Contact No.</td>
			<td colspan="4"><?php echo $visit['sf_engineer'].', phone: '.$visit['sf_engineer_contact_no'] ?></td>
			<td>Sign</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="3">SF Rigger Name & Contact No.</td>
			<td colspan="4"><?php echo $visit['sf_rigger'].', phone: '.$visit['sf_rigger_contact_no'] ?></td>
			<td>Sign</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="9">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="9" class="strong">Antenna Details</td>
		</tr>
		<tr>
			<td colspan="9">
			
				<table class="table-print">
					<tr>
						<td class="print-header" rowspan="2">Cell</td>
						<td class="print-header" colspan="2">Antenna Height (M)</td>
						<td class="print-header" rowspan="2">Antenna Model</td>
						<td class="print-header" colspan="2">Azimuth</td>
						<td class="print-header" colspan="2">Electrical Tilt</td>
						<td class="print-header" colspan="2">Mechanical Tilt</td>
					</tr>
					<tr>
						<td class="print-header">Before</td>
						<td class="print-header">After</td>
						<td class="print-header">Before</td>
						<td class="print-header">After</td>
						<td class="print-header">Before</td>
						<td class="print-header">After</td>
						<td class="print-header">Before</td>
						<td class="print-header">After</td>
					</tr>
					
					<?php foreach ($antenna as $ant) { ?>
					<tr>
						<td><?php echo $ant['cell'] ?></td>
						<td class="center"><?php echo $ant['height_before'] ?></td>
						<td class="center"><?php echo $ant['height_after'] ?></td>
						<td class="center"><?php echo $ant['ant_model'] ?></td>
						<td class="center"><?php echo $ant['azimuth_before'] ?></td>
						<td class="center"><?php echo $ant['azimuth_after'] ?></td>
						<td class="center"><?php echo $ant['electrical_tilt_before'] ?></td>
						<td class="center"><?php echo $ant['electrical_tilt_after'] ?></td>
						<td class="center"><?php echo $ant['mechanical_tilt_before'] ?></td>
						<td class="center"><?php echo $ant['mechanical_tilt_after'] ?></td>
					</tr>
					<?php } ?>
				</table>
			
			</td>
		</tr>
		<tr class="noprint">
			<td colspan="9">&nbsp;</td>
		</tr>
		<tr class="noprint">
			<td colspan="9" class="print-header">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="9">
			
				<div style="page-break-after:always"></div>
			
				<table class="table-print">
					<tr>
					<?php
					$lookup = $this->core->get_lookup('bts_jenis_foto');
					$i = 1;
					foreach ($lookup as $lo) { ?>
						<td class="center strong" valign="top" width="50%">
							<div style="border-bottom:1px solid #000; line-height:14px; padding-bottom:4px; margin-bottom:2px;"><?php echo $lo['desc'] ?></div>
							<div style="height:160px">
								<?php if (file_exists($bts_optimization_dir.'/'.$photos[$lo['id']]) && is_file($bts_optimization_dir.'/'.$photos[$lo['id']])) { ?>
								<img src="<?php echo $this->image_model->resize($bts_optimization_dir.'/'.$photos[$lo['id']], 220, 140, 'crop') ?>" class="img-polaroid" />
								<?php } ?>
							</div>
							<?php
							if ($i == 10) {
								echo '<div style="page-break-after:always"></div>';
							}
							?>
						</td>
						
						
						<?php
						if ($i % 2 == 0) {
							echo '</tr>';
						}
						
						$i++;
					}
					
					echo ($i % 2 == 0) ? '<td class="center strong" valign="top" width="50%">
						<div style="height:160px"></div>
					</td>' : '';
					?>
					</tr>
					
				</table>
			
			</td>
		</tr>
	</table>
</div>

<div class="noprint" style="text-align:center; padding:10px;">
	<button id="btnPrint" type="button" class="btn btn-success"><i class="icon-print icon-white"></i> Print</button>
	<button id="btnClose" type="button" class="btn btn-danger"><i class="icon-remove icon-white"></i> Close</button>
</div>

<script src="<?php echo base_url('/assets/js/jquery-1.4.2.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jqprint.js') ?>"></script>

<script>
document.getElementById('btnPrint').onclick = function() {
	$('#print-area').jqprint({ operaSupport: true });
	return false;
};

document.getElementById('btnClose').onclick = function() {
	self.close();
};
</script>

</body>
</html>