<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					
					<div class="control-group" id="form_area_code">
						<label class="control-label" for="area_code">Area Code</label>
						<div class="controls"><input class="span12" id="area_code" name="area_code" type="text" value="<?php echo $area_code?>" /></div>
					</div>
					
					<div class="control-group" id="form_description">
						<label class="control-label" for="description">Description</label>
						<div class="controls"><input class="span12" id="description" name="description" type="text" value="<?php echo $description?>" /></div>
					</div>
					<hr>
					
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>