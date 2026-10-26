<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<style>
select { width:auto; }
</style>

<div class="innerLR">
	
	<?php echo form_open('', 'id="frmSiteSeason" class="form-horizontal"') ?>
	
	<div class="widget">	
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Site Season</h4>
			
			<a href="<?php echo site_url('/site_info/season') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
		</div>
		
		<div class="widget-body">
		
			<?php
			$site_code = $season['site_code'];
			
			unset($season['site_id']);
			unset($season['site_code']);
			?>
			
			<div class="row-fluid">
				<div class="span6">
					<table class="table table-bordered">
						<tr>
							<th>Season Name</th>
							<th>Season Value</th>
						</tr>
						<?php foreach ($season as $idx_season => $val_season) { ?>
						<tr>
							<td><?php echo ucwords(str_replace('_', ' ', $idx_season)) ?></td>
							<td><?php echo $val_season ?></td>
						</tr>
						<?php } ?>
					</table>
				</div>
			</div>
			
			<div class="separator bottom"></div>
			
			<div class="control-group">
				<label class="control-label" for="site" style="text-align:left">Site:</label>
				<div class="controls">
					<span class="input-label"><?php echo $site_code ?></span>
				</div>
			</div>
		
		</div>
	</div>
	
	<div class="form-actions">
		<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
		<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/site_info/season') ?>'"><i></i>Cancel</button>
	</div>
	
	<?php echo form_close() ?>
	
</div>