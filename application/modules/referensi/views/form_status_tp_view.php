<div class="box">
	<div class="title">
		<h5><?php echo lang('text_heading_title_status_tp_list') ?></h5>
		<ul class="links">
			<li>
				<a href="http://localhost/pmts/index.php/referensi/referensi/status_tp">List Data</a>
			</li>
			<li>
				<a href="http://localhost/pmts/index.php/referensi/referensi/add_status_tp">Add Data</a>
			</li>
		</ul>
	</div>
	
	<?php if ($errors = validation_errors()) { ?>
	<div class="message-error" style="padding:10px">
		<?php echo $errors ?>
	</div>
	<?php } ?>
	
	<?php echo form_open() ?>
	<div class="form">
		<div class="fields">			
			
			<div class="field">
				<div class="label"><label for="statusTpName">Status TP Name</label></div>
				<div class="input">
					<?php echo @form_input('statusTpName', set_value('statusTpName', $status_tp['status_tp_name']), 'id="statusTpName" class="medium"') ?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="statusTpIdText">Status TP Id Text</label></div>
				<div class="input">
					<?php echo @form_input('statusTpIdText', set_value('statusTpIdText', $status_tp['status_tp_id_text']), 'id="statusTpIdText" class="medium"') ?>
				</div>
			</div>
			
			
			<div class="buttons">
				<?php echo form_submit('submit', 'Submit') ?>
			</div>
			
		</div>
	</div>
	<?php echo form_close() ?>
	
</div>