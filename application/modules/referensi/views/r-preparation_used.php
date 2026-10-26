<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					<div class="control-group" id="form_jc_id">
						<label class="control-label" for="jc_id">JC ID</label>
						<div class="controls"><input class="span12" id="jc_id" name="jc_id" type="text" value="<?php echo $descr?>" /></div>
					</div>
					<div class="control-group" id="form_descr">
						<label class="control-label" for="site_id">Site ID</label>
						<div class="controls"><input class="span12" id="site_id" name="site_id" type="text" value="<?php echo $descr?>" /></div>
					</div>
					<div class="control-group" id="form_descr">
						<label class="control-label" for="descr">Preparation Item</label>
						<div class="controls"><input class="span12" id="descr" name="descr" type="text" value="<?php echo $descr?>" /></div>
					</div>
					
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>