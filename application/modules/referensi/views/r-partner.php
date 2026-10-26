
<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					<div class="control-group" id="form_partner_code">
						<label class="control-label" for="partner_code">Partner Code</label>
						<div class="controls"><input class="span12" id="partner_code" name="partner_code" type="text" value="<?php echo $partner_code?>" /></div>
					</div>
					<div class="control-group" id="form_partner_name">
						<label class="control-label" for="partner_name">Partner Name</label>
						<div class="controls"><input class="span12" id="partner_name" name="partner_name" type="text" value="<?php echo $partner_name?>" /></div>
					</div>
					<div class="control-group" id="form_gis_marker">
						<label class="control-label" for="gis_marker">GIS Marker</label>
						<div class="controls"><div class="fileupload fileupload-new" data-provides="fileupload">
					  	<div class="input-append">
					    	<div class="uneditable-input span2"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"><?php echo $gis_marker?></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" /></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					  	</div>
					</div></div>
					</div>
					
					<div class="control-group" id="form_province_id">
						<label class="control-label" for="province_id">Province</label>
						<div class="controls">
							<select class="span12" name="province_id" id="province_id">
							<option value="">--Choose--</option>
							<?php
							$getProv = $this->mpartner->getProv();
							foreach($getProv as $z){
								$selectProv = ($z->lokasi_id == $province_id ? " SELECTED ":"");
								echo"<option value='$z->lokasi_id' $selectProv >$z->nama</option>";
							}
							?>
							</select>
						</div>
					</div> <div class="control-group" id="form_city_id">
						<label class="control-label" for="city_id">City</label>
						<div class="controls">
							<select class="span12" name="city_id" id="city_id">
							<option value="">--Choose--</option>
							</select>
						</div>
					</div> 
					<div class="control-group" id="form_partner_type">
						<label class="control-label" for="partner_type">Partner Type</label>
						<div class="controls">
							<select class="span12" name="partner_type" id="partner_type">
							<option value="">--Choose--</option>
							<?php
							$getMenu = $this->mpartner->getMenu();
							foreach($getMenu as $r){
								$select = ($r->lookup_id == $partner_type ? " SELECTED ":"");
								echo"<option value='$r->lookup_id' $select >$r->lookup_desc</option>";
							}
							?>
							</select>
						</div>
					</div> 
					
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>