<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					
					<!--
					<div class="control-group" id="form_region_code">
						<label class="control-label" for="region_code">Region Code</label>
						<div class="controls"><input class="span12" id="region_code" name="region_code" type="text" value="<?php //echo $region_code?>" /></div>
					</div>
					-->
					
					<div class="control-group" id="form_region_name">
						<label class="control-label" for="descr">Region Name</label>
						<div class="controls"><input class="span12" id="region_name" name="region_name" type="text" value="<?php echo $region_name?>" /></div>
					</div>
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>