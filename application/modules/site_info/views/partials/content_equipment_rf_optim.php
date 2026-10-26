<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<div class="widget">
	<div class="widget-head">
		<h4 class="heading"><i></i> RF Antenna:</h4>
	</div>
	
	<div class="widget-body">
		<div style="overflow:auto">
			<table id="tblRF" class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th rowspan="2" class="center">Type</th>
						<th rowspan="2" class="center">Model</th>
						<th rowspan="2" class="center">Brand</th>
						<th rowspan="2" class="center">Cell</th>
						<th rowspan="2" class="center">Band</th>
						<th rowspan="2" class="center">HBWD</th>
						<th rowspan="2" class="center">VBWD</th>
						<th rowspan="2" class="center">Dimension (LxWxH)</th>
						<th rowspan="2" class="center">Weight</th>
						<th colspan="2" class="center">Height</th>
						<th colspan="2" class="center">Azimuth</th>
						<th colspan="2" class="center">Electrical Tilt</th>
						<th colspan="2" class="center">Mechanical Tilt</th>
					</tr>
					<tr>
						<th class="center">Before</th>
						<th class="center">After</th>
						<th class="center">Before</th>
						<th class="center">After</th>
						<th class="center">Before</th>
						<th class="center">After</th>
						<th class="center">Before</th>
						<th class="center">After</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (count($bts_rf) > 0) {
					
						$rf_counter = 1;
						foreach ($bts_rf as $idx => $rf) { ?>
					
					<tr id="rf-row-<?php echo $rf_counter ?>">
						<td nowrap>
							<?php
							echo @form_hidden('rfId[]', $rf['equipment_rf_id']);
							
							$lookup = $this->core->get_lookup('antenna_rf_type');
							$options = array(0 => '');						
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							?>
							<span><?php echo $options[$rf['type']] ?></span>
						</td>
						<td nowrap><span><?php echo $rf['model'] ?></span></td>
						<td nowrap><span><?php echo $rf['brand'] ?></span></td>
						<td nowrap>
							<?php
							$options = array(0 => '');
							$lookup = $this->core->get_lookup('antenna_cell');
							foreach ($lookup as $lo) {
								$options[$lo['id']] = $lo['desc'];
							}
							?>
							<span><?php echo $options[$rf['cell']] ?></span>
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
						<td style="text-align:right">
							<span><?php echo $rf['hbwd'] ?></span>
						</td>
						<td style="text-align:right">
							<span><?php echo $rf['vbwd'] ?></span>
						</td>
						<td style="text-align:right" nowrap>
							<span><?php echo number_format($rf['dimension_long'], 2).' x '.number_format($rf['dimension_width'], 2).' x '.number_format($rf['dimension_height'], 2) ?> mm</span>
						</td>
						<td style="text-align:right" nowrap>
							<span><?php echo number_format($rf['weight'], 2) ?> kg</span>
						</td>
						<td class="right"><span class="before"><?php echo $rf['height_before'] ?></span> M</td>
						<td>
							<div class="input-append">
								<?php echo form_input('height[]', ($rf['height_after'] != 0 ? $rf['height_after'] : $rf['height_before']), 'class="input-mini number after"') ?>
								<span class="add-on">M</span>
							</div>
						</td>
						<td class="right"><span class="before"><?php echo $rf['azimuth_before'] ?></span></td>
						<td><?php echo form_input('azimuth[]', ($rf['azimuth_after'] != 0 ? $rf['azimuth_after'] : $rf['azimuth_before']), 'class="input-mini just-number after"') ?></td>
						<td class="right"><span class="before"><?php echo $rf['electrical_tilt_before'] ?></span></td>
						<td><?php echo form_input('electricalTilt[]', ($rf['electrical_tilt_after'] != 0 ? $rf['electrical_tilt_after'] : $rf['electrical_tilt_before']), 'class="input-mini just-number after"') ?></td>
						<td class="right"><span class="before"><?php echo $rf['mechanical_tilt_before'] ?></span></td>
						<td><?php echo form_input('mechanicalTilt[]', ($rf['mechanical_tilt_after'] != 0 ? $rf['mechanical_tilt_after'] : $rf['mechanical_tilt_before']), 'class="input-mini just-number after"') ?></td>
					</tr>
					<?php 
							$rf_counter++;
						}
					} else {
					?>
					<tr>
						<td colspan="17">
							<div class="alert alert-warning">Data master Antenna RF not found.</div>
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	
	</div>
</div>

<script>
numberFormat.init();
justNumberFormat.init();
</script>