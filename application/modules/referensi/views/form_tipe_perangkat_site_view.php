<div class="box">
	<div class="title">
		<h5><?php echo lang('text_heading_title_tipe_perangkat_site_tp_list') ?></h5>
		<ul class="links">
			<li>
				<a href="http://localhost/pmts/index.php/referensi/referensi/tipe_perangkat_site">List Data</a>
			</li>
			<li>
				<a href="http://localhost/pmts/index.php/referensi/referensi/add_tipe_perangkat_site">Add Data</a>
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
				<div class="label"><label for="nama">Nama</label></div>
				<div class="input">
					<?php echo @form_input('nama', set_value('nama', $tipe_perangkat_site['nama']), 'id="nama" class="medium"') ?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="status">Status</label></div>
				<div class="input">
					<?php echo @form_input('status', set_value('status', $tipe_perangkat_site['status']), 'id="status" class="medium"') ?>
				</div>
			</div>
			
			
			<div class="buttons">
				<?php echo form_submit('submit', 'Submit') ?>
			</div>
			
		</div>
	</div>
	<?php echo form_close() ?>
	
</div>