<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					
					<div class="control-group" id="form_tp_name">
						<label class="control-label" for="tp_name">TP Name</label>
						<div class="controls"><input class="span12" id="tp_name" name="tp_name" type="text" value="<?php echo $tp_name?>" /></div>
					</div>
						<div class="control-group" id="form_site_id">
						<label class="control-label" for="site_id">Site ID</label>
						<div class="controls">
							<select class="span12" name="site_id" id="site_id">
							<option value="">--choose--</option>
							<?php
							$getProv = $this->mtower_provider->getSiteID();
							foreach($getProv as $z){
								$selectProv = ($z->site_id == $site_id ? " SELECTED ":"");
								echo"<option value='$z->site_id' $selectProv >$z->site_code</option>";
							}
							?>
							</select>
						</div>
					</div>
						<div class="control-group" id="form_site_name">
						<label class="control-label" for="site_name">Site Name</label>
						<div class="controls" id="site_name"><input class="span12" id="site_name" name="site_name" type="text" value="<?php echo $site_name?>" /></div>
					</div>
						<div class="control-group" id="form_province">
						<label class="control-label" for="province">Province Name</label>
						<div class="controls">
							<select class="span12" name="province" id="province">
							<option value="">--choose--</option>
							<?php
							$getProv = $this->mtower_provider->getProv();
							foreach($getProv as $z){
								$selectProv = ($z->lokasi_id == $province ? " SELECTED ":"");
								echo"<option value='$z->lokasi_id' $selectProv >$z->nama</option>";
							}
							?>
							</select>
						</div>
					</div>
					<div class="control-group" id="form_city">
						<label class="control-label" for="city">City Name</label>
							<div class="controls">
							<select class="span12" name="city" id="city">
							
							<option value="">--choose--</option>
							</select>
						</div>
					</div>
						<div class="control-group" id="form_region">
						<label class="control-label" for="region">Region Name</label>
						<div class="controls">
							<select class="span12" name="region" id="region">
							<option value="">--choose--</option>
							<?php
							$getProv = $this->mtower_provider->getReg();
							foreach($getProv as $z){
								$selectProv = ($z->region_id == $region ? " SELECTED ":"");
								echo"<option value='$z->region_id' $selectProv >$z->region_name</option>";
							}
							?>
							</select>
						</div>
					</div>
						
					<div class="control-group" id="form_status">
						<label class="control-label" for="status">Status</label>
						<div class="controls">
								<input type="radio" class="radio" name="status" value="1" checked="checked" />
								Aktif
								<input type="radio" class="radio" name="status" value="0"/>
								Non Aktif
						</div>
					</div>
						<div class="control-group" id="form_lattitude">
						<label class="control-label" for="lattitude">Latitude</label>
						<div class="controls"><input class="span12" id="lattitude" name="lattitude" type="text" value="<?php echo $lattitude?>" /></div>
					</div>
						<div class="control-group" id="form_longitude">
						<label class="control-label" for="longitude">Longtitude</label>
						<div class="controls"><input class="span12" id="longitude" name="longitude" type="text" value="<?php echo $longitude?>" /></div>
					</div>
						<div class="control-group" id="form_site_type">
						<label class="control-label" for="site_type">Site Type</label>
						<div class="controls">
							<select class="span12" name="site_type" id="site_type">
							<option value="">--choose--</option>
							<?php
							$getProv = $this->mtower_provider->getSiteType();
							foreach($getProv as $z){
								$selectProv = ($z->lookup_id == $site_type ? " SELECTED ":"");
								echo"<option value='$z->lookup_id' $selectProv >$z->lookup_desc</option>";
							}
							?>
							</select>
						</div>
					</div>
						<div class="control-group" id="form_tower_type">
						<label class="control-label" for="tower_type">Tower Type</label>
						<div class="controls">
							<select class="span12" name="tower_type" id="tower_type">
							<option value="">--choose--</option>
							<?php
							$getProv = $this->mtower_provider->getTowerType();
							foreach($getProv as $z){
								$selectProv = ($z->tower_type_id == $tower_type ? " SELECTED ":"");
								echo"<option value='$z->tower_type_id' $selectProv >$z->tower_type_name</option>";
							}
							?>
							</select>
						</div>
					</div>
						<div class="control-group" id="form_building_height_mtr">
						<label class="control-label" for="building_height_mtr">Building Height</label>
						<div class="controls"><input class="span12" id="building_height_mtr" name="building_height_mtr" type="text" value="<?php echo $building_height_mtr?>" /></div>
					</div>
						<div class="control-group" id="form_tower_height_mtr">
						<label class="control-label" for="tower_height_mtr">Tower Height</label>
						<div class="controls"><input class="span12" id="tower_height_mtr" name="tower_height_mtr" type="text" value="<?php echo $tower_height_mtr?>" /></div>
					</div>
						<div class="control-group" id="form_address">
						<label class="control-label" for="address">Address</label>
						<div class="controls">
						<textarea id="address" name="address" class="wysihtml5 span12" rows="5"><?php echo $address?></textarea>
						</div>
					</div>
						<div class="control-group" id="form_tp_name">
						<label class="control-label" for="shareable_status">Shareable Status</label>
						<div class="controls"><input class="span12" id="shareable_status" name="shareable_status" type="text" value="<?php echo $shareable_status?>" /></div>
					</div>
						<div class="control-group" id="form_tp_name">
						<label class="control-label" for="site_code">Site Code</label>
						<div class="controls"><input class="span12" id="site_code" name="site_code" type="text" value="<?php echo $site_code?>" /></div>
					</div>
						<div class="control-group" id="form_tp_name">
						<label class="control-label" for="remark">Remark</label>
						<div class="controls">
						<textarea id="address" name="remark" class="wysihtml5 span12" rows="5"><?php echo $remark?></textarea>
						</div>
					</div>
						<div class="control-group" id="form_partner_id">
						<label class="control-label" for="partner_id">Partner ID</label>
						<div class="controls">
							<select class="span12" name="partner_id" id="partner_id">
							<option value="">--choose--</option>
							<?php
							$getProv = $this->mtower_provider->getPartner();
							foreach($getProv as $z){
								$selectProv = ($z->partner_id == $partner_id ? " SELECTED ":"");
								echo"<option value='$z->partner_id' $selectProv >$z->partner_name</option>";
							}
							?>
							</select>
						</div>
					</div>  
						<div class="control-group" id="form_kandidat">
						<label class="control-label" for="kandidat">Kandidat</label>
						<div class="controls"><input class="span12" id="kandidat" name="kandidat" type="text" value="<?php echo $kandidat?>" /></div>
					</div>
					
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>