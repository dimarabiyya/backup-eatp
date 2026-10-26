<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
						
					<div class="control-group" id="form_tpgroup_name">
						<label class="control-label" for="tpgroup_name">TP Group Name</label>
						<div class="controls"><input class="span12" maxlength="100" id="tpgroup_name" name="tpgroup_name" type="text" value="<?php echo $tpgroup_name?>" /></div>
					</div>
					
					<div class="control-group" id="form_tpgroup_description">
						<label class="control-label" for="tpgroup_description">TP Group Description</label>
						<div class="controls"><input class="span12" maxlength="100" id="tpgroup_description" name="tpgroup_description" type="text" value="<?php echo $tpgroup_description?>" /></div>
					</div>
                                        
				</div><hr>
			</div>
			
		</div>
		
	</div>
	
</form>