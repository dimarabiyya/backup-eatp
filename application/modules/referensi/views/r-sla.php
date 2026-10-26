<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
						
					<div class="control-group" id="form_loc_id">
						<label class="control-label" for="loc_id">Location</label>
						<div class="controls">
						<select style="text-transform:capitalize;" class="span12" name="loc_id" id="loc_id">
							<option value="">--choose--</option>
							<?php
								$getLocation = $this->msla->getLocation();
								foreach($getLocation as $l){//echo $l->loc_id .'=='. $loc_id;
									$select = ($l->rlov1 == $loc_id ? " SELECTED ":"");
									echo"<option value='$l->rlov1' $select>$l->rloc</option>";
								}
							?>
						</select>
						</div>
					</div>
					
					<div class="control-group" id="form_sev_id">
						<label class="control-label" for="sev_id">Network Severity</label>
						<div class="controls">
						<select style="text-transform:capitalize;" class="span12" name="sev_id" id="sev_id">
							<option value="">--choose--</option>
							<?php
								$getSeverity = $this->msla->getSeverity();
								foreach($getSeverity as $s){
									$select = ($s->rlov2 == $sev_id ? " SELECTED ":"");
									echo"<option value='$s->rlov2' $select>$s->rsev</option>";
								}
							?>
						</select>
						</div>
					</div>
				
					<div class="control-group" id="form_sla">
						<label class="control-label" for="sla">SLA</label>
						<div class="controls"><input class="span12" id="sla" name="sla" type="text" value="<?php echo $sla?>" /></div>
					</div>
                                        
				</div><hr>
			</div>
			
		</div>
		
	</div>
	
</form>