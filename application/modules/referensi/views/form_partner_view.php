<div class="box">
	<div class="title">
		<h5><?php echo lang('text_heading_title_partner_list') ?></h5>
		<ul class="links">
			<li>
				<a href="http://localhost/pmts/index.php/referensi/referensi/partner">List Data</a>
			</li>
			<li>
				<a href="http://localhost/pmts/index.php/referensi/referensi/add_partner">Add Data</a>
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
				<div class="label"><label for="partnerCode">Partner Code</label></div>
				<div class="input">
					<?php echo @form_input('partnerCode', set_value('partnerCode', $partner['partner_code']), 'id="partnerCode" class="medium"') ?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="partnerName">Partner Name</label></div>
				<div class="input">
					<?php echo @form_input('partnerName', set_value('partnerName', $partner['partner_name']), 'id="partnerName" class="medium"') ?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="address">Addres</label></div>
				<div class="input">
					<?php echo @form_input('address', set_value('address', $partner['address']), 'id="address" class="medium"') ?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="provinceId">Province</label></div>
				<div class="input">
					<?php
					$dataProvince = get_instance()->core->view_all('t_mtr_propinsi', NULL, 'descr');
					$province = array('' => '');
					foreach ($dataProvince as $prov) {
						$province[$prov['propinsi_id']] = $prov['descr'];
					}
					echo @form_dropdown('provinceId', $province, set_value('provinceId', $partner['province_id']), 'id="provinceId" class="medium"');
					?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="city">City</label></div>
				<div class="input">
					<?php echo @form_dropdown('city', $city, set_value('city', $partner['city']), 'id="city" class="medium"') ?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="image_path">Image Path</label></div>
				<div class="input">
					<?php echo @form_input('imagePath', set_value('imagePath', $partner['image_path']), 'id="imagePath" class="medium"') ?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="lon">Lon</label></div>
				<div class="input">
					<?php echo @form_input('lon', set_value('lon', $partner['lon']), 'id="lon" class="medium"') ?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="lat">Lat</label></div>
				<div class="input">
					<?php echo @form_input('lat', set_value('lat', $partner['lat']), 'id="lat" class="medium"') ?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="partner_type">Partner Type</label></div>
				<div class="input">
					<?php echo @form_input('partnerType', set_value('partnerType', $partner['partner_type']), 'id="partnerType" class="medium"')
						//echo @form_dropdown('partnerType', $partnerType, set_value('partnerType', $partner['partner_type']), 'id="partnerType" class="medium"') 
					?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="status">Status</label></div>
				<div class="input">
					<?php echo @form_input('status', set_value('status', $partner['status']), 'id="status" class="medium"') ?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="partner_id_text">Partner ID Text</label></div>
				<div class="input">
					<?php echo @form_input('partnerIdText', set_value('partnerIdText', $partner['partner_id_text']), 'id="partnerIdText" class="medium"') ?>
				</div>
			</div>
			
			<div class="field">
				<div class="label"><label for="gis_marker">Gis Marker</label></div>
				<div class="input">
					<?php echo @form_input('gisMarker', set_value('gisMarker', $partner['gis_marker']), 'id="gisMarker" class="medium"') ?>
				</div>
			</div>
			
			<div class="buttons">
				<?php echo form_submit('submit', 'Submit') ?>
			</div>
			
		</div>
	</div>
	<?php echo form_close() ?>
	
</div>

<script>
document.getElementById('provinceId').onchange = function() {
	
	$.ajax({
		url: '<?php echo site_url('/referensi/referensi/partner') ?>',
		type: 'GET',
		data: { cmd: 'GET_KABUPATEN', propinsi_id: this.value },
		dataType: 'json',
		success: function(json) {
			var html = '<option value=""></option>';
			for (var i = 0; i < json.length; i++) {
				html += '<option value="' + json[i]['id'] + '">' + json[i]['name'] + '</option>';
			}
			document.getElementById('city').innerHTML = html;
		}
	});	
};
</script>