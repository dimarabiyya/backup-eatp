<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					<div class="control-group" id="form_descr">
						<label class="control-label" for="province">Province</label>
						<div class="controls">
							<!-- <select name="province" id="province">
							<option value="0">- Choose one -</option>
							<?php
							/*foreach($getProv as $z){
								$selectProv = ($z->child_id == $parent_id ? " SELECTED ":"");
								echo"<option value='$z->child_id' $selectProv >$z->nama</option>";
							}*/
							?>
							</select> -->
							<?php
							$results = $this->mkecamatan->getProv();
							$options = array(0 => '- Choose one -');
							foreach ($results as $res) {
								$options[$res->lokasi_id] = $res->nama;
							}
							echo @form_dropdown('province', $options, $propinsi['lokasi_id'], 'id="province"');
							?>
						</div>
					</div>
					
					<div class="control-group" id="form_kabupaten">
						<label class="control-label" for="kabupaten">Kabupaten</label>
						<div class="controls">
							<?php
							$options = array(0 => '- Choose one -');
							$results = $this->core->view_all('t_mtr_lokasi', array('parent_id' => $propinsi['lokasi_id'], 'jenis' => 'KB', 'status' => 1));
							foreach ($results as $res) {
								$options[$res['lokasi_id']] = $res['nama'];
							}
							echo @form_dropdown('kabupaten', $options, $kabupaten['lokasi_id'], 'id="kabupaten"');
							?>
						</div>
					</div>
					
					<div class="control-group" id="form_kecamatan">
						<label class="control-label" for="kecamatan">Kecamatan</label>
						<div class="controls">
							<?php echo @form_input('kecamatan', $kecamatan['nama'], 'id="kecamatan"') ?>
						</div>
					</div>
					
                    
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>