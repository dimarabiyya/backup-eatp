<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					
					<div class="control-group" id="form_cluster_code">
						<label class="control-label" for="cluster_code">Cluster Code</label>
						<div class="controls"><input class="span12" id="cluster_code" name="cluster_code" type="text" value="<?php echo $cluster_code?>" /></div>
					</div>
					
					<div class="control-group" id="form_cluster_name">
						<label class="control-label" for="cluster_name">Cluster Name</label>
						<div class="controls"><input class="span12" id="cluster_name" name="cluster_name" type="text" value="<?php echo $cluster_name?>" /></div>
					</div>
					
					<div class="control-group" id="form_region_id">
						<label class="control-label" for="region_id">Region Name</label>
						<div class="controls">
						<!--<input class="span12" id="region_id" name="region_id" type="text" value="<?//=$region_id?>" />-->
						<select style="text-transform:capitalize;" class="span12" name="region_id" id="region_id">
							<option value="">--choose--</option>
							<?php
								$getRegion = $this->mcluster_net->getRegion();
								foreach($getRegion as $r){
									$select = ($r->region_id == $region_id ? " SELECTED ":"");
									echo"<option value='$r->region_id' $select>$r->rname</option>";
								}
							?>
						</select>
						</div>
					</div>
                                     
				</div> <hr>  
			</div>
			
		</div>
		
	</div>
	
</form>