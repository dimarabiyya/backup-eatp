<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					<div class="control-group" id="form_parent">
						<label class="control-label" for="parent">Tools Type</label>
						<div class="controls">
							<select class="span12" name="type" id="type">
							<?php
							$getMenu = $this->mpreparation_item->getMenu();
							foreach($getMenu as $r){
								$select = ($r->preparation_type_id == $type_id ? " SELECTED ":"");
								echo"<option value='$r->preparation_type_id' $select >$r->tdescr</option>";
							}
							?>
							</select>
						</div>
					</div>
					<div class="control-group" id="form_descr">
						<label class="control-label" for="descr">Tools Item</label>
						<div class="controls"><input class="span12" id="descr" name="descr" type="text" value="<?php echo $descr?>" /></div>
					</div>
					<hr>
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>