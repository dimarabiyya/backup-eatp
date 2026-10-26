<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<div class="form-horizontal">

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading"><i></i> RF Antenna Data</h4>
		</div>
		
		<div class="widget-body">
		
			<div style="overflow:auto">
				<table id="tblRF" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>Antenna:</th>
							<th>Height:</th>
							<th>Dimension (LxWxH):</th>
							<th>Weight:</th>
						</tr>
					</thead>
					<tbody>
						<?php if ( $this->uri->segment(3) == 'view' && count($rf) > 0 || $this->core->view_row('t_mtr_site_plan_rf', array('site_plan_id' => (int) $this->input->get('site_id'))) ) {
							
							$rfCounter = 1;								
							foreach ($rf as $er_ef) {
						?>
						
						<tr id="rf-row-<?php echo $rfCounter ?>">
							<td>
								<?php
								echo @form_hidden('id[]', $er_ef['site_plan_rf_id']);
								
								$lookup = $this->core->get_lookup('antenna_type');
								$options = array(0 => '');
								foreach ($lookup as $lo) {
									$options[$lo['id']] = $lo['desc'];
								}
								?>
								<span><?php echo $options[$er_ef['antenna']] ?></span>
							</td>
							<td>
								<span><?php echo number_format($er_ef['height'], 2) ?> M</span>
							</td>
							<td nowrap>
								<span><?php echo number_format($er_ef['dimension_l'], 2).' x '.number_format($er_ef['dimension_w'], 2).' x '.number_format($er_ef['dimension_h'], 2) ?> CM</span>
							</td>
							<td>
								<span><?php echo number_format($er_ef['weight'], 2) ?> kg</span>
							</td>
						</tr>
						<?php
								$rfCounter++;
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