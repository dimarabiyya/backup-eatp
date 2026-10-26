<div class="innerLR">

	<?php echo form_open('', 'id="frmExport" class="form-horizontal"') ?>

	<div class="widget" style="margin-top:10px">
		<div class="widget-head">
			<h4 class="heading"><i></i> PLN Usage Export</h4>
		</div>
		
		<div class="widget-body">

			<div class="control-group">
				<label class="control-label" for="expYear">Year:</label>
				<div class="controls">
					<div class="input-append">
						<?php
						$year = array(0 => '- Choose one -');
						foreach ($this->mreport->getYearRange() as $y) {
							$year[$y['value']] = $y['label'];
						}
						
						echo form_dropdown('year', $year, '', 'id="expYear"');
						?>
					</div>
				</div>
			</div>
			
			<!--
			<div class="control-group">
				<label class="control-label" for="expMonth">Month:</label>
				<div class="controls">
					<div class="input-append">
						<?php
						$month = array(0 => '- All -');
						foreach ($this->mreport->getMonthRange() as $m) {
							$month[$m['value']] = $m['label'];
						}
						
						echo form_dropdown('month', $month, '', 'id="expMonth"');
						?>
					</div>
				</div>
			</div>
			-->
			
		</div>
	</div>

	<div class="form-actions">
		<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Export</button>
		<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$.fancybox.close()"><i></i>Cancel</button>
	</div>

</div>
	
<?php echo form_close() ?>

<script>
document.getElementById('frmExport').onsubmit = function() {

	var year = document.getElementById('expYear');
	//var month = document.getElementById('expMonth');
	
	if (year.value == 0) {
		coolNotification('Year must be chosen.', 'error');
		return false;
	}
	
	window.location.href = '<?php echo site_url('/export/site_info/pln_usage') ?>?year=' + year.value;
	
	return false;
};
</script>