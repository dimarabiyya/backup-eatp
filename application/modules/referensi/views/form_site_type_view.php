<div class="box">
	<div class="title"><h5><?php echo lang('text_heading_title_site_type_list') ?></h5></div>
	
	<?php if ($errors = validation_errors()) { ?>
	<div class="message-error" style="padding:10px">
		<?php echo $errors ?>
	</div>
	<?php } ?>
	
	<?php echo form_open() ?>
	<div class="form">
		<div class="fields">			
			
			<div class="field">
				<div class="label"><label for="statusTpName">Site Type Name</label></div>
				<div class="input">
					<?php echo @form_input('siteTypeName', set_value('siteTypeName', $site_type['site_type_name']), 'id="siteTypeName" class="medium"') ?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="idText">ID Text</label></div>
				<div class="input">
					<?php echo @form_input('idText', set_value('idText', $site_type['id_text']), 'id="idText" class="medium"') ?>
				</div>
			</div>
			
			
			<div class="buttons">
				<?php echo form_submit('submit', 'Submit') ?>
			</div>
			
		</div>
	</div>
	<?php echo form_close() ?>
	
</div>