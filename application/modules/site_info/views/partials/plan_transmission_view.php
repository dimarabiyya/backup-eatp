<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<div class="form-horizontal">

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading"><i></i> Transmission Data</h4>
		</div>
		
		<div class="widget-body">
		
			<div style="overflow:auto">
				<table id="tblTRANSM" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>Far End:</th>
							<th>Link Type:</th>
							<th>Main Ant. Height:</th>
							<th>Main Ant. Diameter:</th>
							<th>Frequency:</th>
							<th>Equipment Model:</th>
							<th>Capacity:</th>
							<th>Owner:</th>
						</tr>
					</thead>
					<tbody>
						<?php if ( $this->uri->segment(3) == 'view' && count($transm) > 0 || $this->core->view_row('t_mtr_site_plan_mw', array('site_plan_id' => (int) $this->input->get('site_id'))) ) {
							
							$mwCounter = 1;
							foreach ($transm as $mw) {
						?>
						
						<tr id="transm-row-<?php echo $mwCounter ?>">
							<td>
								<span><?php echo $mw['site_code'] ?></span>
							</td>
							<td nowrap>
								<?php
								$options = array(0 => '');
								$lookup = $this->core->get_lookup('transmission_link_type');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $options[$mw['link_type']] ?></span>
							</td>
							<td>
								<span><?php echo number_format($mw['ant_height'], 2) ?> M</span>
							</td>
							<td>
								<span><?php echo number_format($mw['ant_diameter'], 2) ?> M</span>
							</td>
							<td>
								<span><?php echo number_format($mw['frequency'], 2) ?> M</span>
							</td>
							<td>
								<?php
								$options = array(0 => '');
								$lookup = $this->core->get_lookup('equipment_model');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $options[$mw['equip_model']] ?></span>
							</td>
							<td>
								<span><?php echo number_format($mw['capacity'], 2) ?> MB</span>
							</td>
							<td>
								<span><?php echo $mw['owner'] ?></span>
							</td>
						</tr>
						
						<?php
								$mwCounter++;
							}
						
						}
						?>
						
					</tbody>
				</table>
			</div>
		
		</div>
	</div>

</div>

<script>
doPreviledgePerfield();
</script>