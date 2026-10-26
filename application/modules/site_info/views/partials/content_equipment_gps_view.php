<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<div class="form-horizontal">

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading"><i></i> GPS Antenna</h4>
		</div>
		
		<div class="widget-body">
			
			<div style="overflow:auto">
				<table id="tblGPS" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>Model</th>
							<th>Height</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($this->uri->segment(3) == 'view' && count($gps) > 0) {
							
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

</div>

<script>
<?php if ($this->uri->segment(3) == 'edit' && count($gps) > 0) { ?>
var gpsCounter = <?php echo $gpsCounter ?>;
<?php } else { ?>
var gpsCounter = 2;
<?php } ?>

var GPS = {	
	addRow: function() {
		var html = '\
			<tr id="gps-row-' + gpsCounter + '">\
				<td>\
					<select name="gpsModel[]" class="input-medium">\
						<option value="0">- Choose one -</option>\
						<?php
						$lookup = $this->core->view_all('t_mtr_lookup', array('lookup_key' => 'antenna_gps_model', 'active' => 1), 'order');
						$gpsModel = array(0 => '- Choose one -');
						foreach ($lookup as $lo) {
							echo '<option value="'.$lo['lookup_value'].'">'.$lo['lookup_desc'].'</option>';
						}
						?>\
					</select>\
				</td>\
				<td>\
					<div class="input-append">\
						<input type="text" name="gpsHeight[]" class="input-small number" />\
						<span class="add-on">M</span>\
					</div>\
				</td>\
				<td class="right"><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="GPS.removeRow(' + gpsCounter + ')"><i></i> Remove</a></td>\
			</tr>\
		';
		
		$('#tblGPS tbody').append(html);
		
		numberFormat.init();
		clearAutocomplete();
		
		gpsCounter++;
	},
	removeRow: function(row) {
		var row = document.getElementById('gps-row-' + row);
		
		coolConfirmation('Are you sure you want to delete?', function() {
			row.parentNode.removeChild(row);
		});
	}
};

numberFormat.init();
clearAutocomplete();
doPreviledgePerfield();
</script>