<div class="box">
	<div class="title"><h5><?php echo lang('text_heading_title_region_tp_list') ?></h5></div>
	
	<?php if ($errors = validation_errors()) { ?>
	<div class="message-error" style="padding:10px">
		<?php echo $errors ?>
	</div>
	<?php } ?>
	
	<?php echo form_open() ?>
	<div class="form">
		<div class="fields">			
			
			<div class="field">
				<div class="label"><label for="regionName">Region Name</label></div>
				<div class="input">
					<?php echo @form_input('regionName', set_value('regionName', $region['region_name']), 'id="regionName" class="medium"') ?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="regionIdText">Region ID Text</label></div>
				<div class="input">
					<?php echo @form_input('regionIdText', set_value('regionIdText', $region['region_id_text']), 'id="regionIdText" class="medium"') ?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="status">Status</label></div>
				<div class="input">
					<?php echo @form_input('status', set_value('status', $region['status']), 'id="status" class="medium"') ?>
				</div>
			</div>
			
			
			<div class="buttons">
				<?php echo form_submit('submit', 'Submit') ?>
			</div>
			
		</div>
	</div>
	<?php echo form_close() ?>
	
</div>