
<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='lookup_id' value='<?php echo $lookup_id?>' >
					
					<div class="control-group" id="form_lookup_key">
						<label class="control-label" for="lookup_key">Referensi Type</label>
						<div class="controls">
							<select class="span12" name="lookup_key" id="lookup_key">
							<option value="">--Pilih--</option>
							<?php
							$datanya = $this->mmr_reference->getLookupKey();
							//die($datanya);
							foreach($datanya as $z){
								$selectnya = ($z->lookup_key == $lookup_key ? " SELECTED ":"");
								echo"<option value='$z->lookup_key' $selectnya >$z->lookup_key</option>";
							}
							?>
							</select>
						</div>
					</div> 
					
					<div class="control-group" id="form_lookup_value">
						<label class="control-label" for="lookup_value">Lookup Value</label>
						<div class="controls"><input class="span12" id="lookup_value" name="lookup_value" type="text" value="<?php echo $lookup_value?>" /></div>
					</div>
					
					
					<div class="control-group" id="form_lookup_desc">
						<label class="control-label" for="lookup_desc">Lookup Description</label>
						<div class="controls"><input class="span12" id="lookup_desc" name="lookup_desc" type="text" value="<?php echo $lookup_desc?>" /></div>
					</div>
					
					
					<div class="control-group" id="form_order">
						<label class="control-label" for="order">Order</label>
						<div class="controls"><input class="span12" id="order" name="order" type="text" value="<?php echo $order?>" /></div>
					</div>
					<?php
					$vactive = "";
					if($active="1") {
						$vactive = "checked";
					}
					?>
					<div class="control-group" id="form_active">
						<label class="control-label" for="form_active">Active</label>
						<div class="controls"><input class="span1" id="active" name="active" type="checkbox" <?php echo $vactive?>/></div>
						<!--<div class="controls"><input class="span1" id="active" name="active" type="checkbox" <?php echo $active?>/></div>-->
					</div>
					
										
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>