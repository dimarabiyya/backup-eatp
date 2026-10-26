<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					
					<div class="control-group" id="form_property_id">
						<label class="control-label" for="property_id">Property ID</label>
						<div class="controls"><input class="span12" id="property_id" name="property_id" type="text" value="<?php echo $property_id?>" /></div>
					</div>
					
					<div class="control-group" id="form_option">
						<label class="control-label" for="option">Option</label>
						<div class="controls"><input class="span12" id="option" name="option" type="text" value="<?php echo $option?>" /></div>
					</div>
					
					<div class="control-group" id="form_bobot">
						<label class="control-label" for="bobot">Bobot</label>
						<div class="controls"><input class="span12" id="bobot" name="bobot" type="text" value="<?php echo $bobot?>" /></div>
					</div>
					
					<div class="control-group" id="form_urutan">
						<label class="control-label" for="urutan">Order</label>
						<div class="controls"><input class="span12" id="urutan" name="urutan" type="text" value="<?php echo $urutan?>" /></div>
					</div>
					
					<div class="control-group" id="form_remarks">
						<label class="control-label" for="remarks">Remarks</label>
						<div class="controls"><input class="span12" id="remarks" name="remarks" type="text" value="<?php echo $remarks?>" /></div>
					</div>
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>