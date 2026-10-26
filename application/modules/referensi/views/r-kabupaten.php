<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					<div class="control-group" id="form_descr">
						<label class="control-label" for="descr">Province Name</label>
						<div class="controls">
							<select class="span12" name="province" id="province">
							<option value="">--Choose--</option>
							<?php
							$getProv = $this->mkabupaten->getProv();
							foreach($getProv as $z){
								$selectProv = ($z->child_id == $parent_id ? " SELECTED ":"");
								echo"<option value='$z->child_id' $selectProv >$z->nama</option>";
							}
							?>
							</select>
						</div>
					</div>
					<div class="control-group" id="form_descr">
						<label class="control-label" for="descr">City Name</label>
						<div class="controls"><input class="span12" id="descr" name="descr" type="text" value="<?php echo $nama?>" /></div>
					</div>
					
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>