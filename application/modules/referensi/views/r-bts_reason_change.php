<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					
					<input type='hidden' name='order_no_ori' value='<?php echo $order?>' >
					
					<div class="control-group" id="form_lookup_desc">
						<label class="control-label" for="lookup_desc">Lookup Desc</label>
						<div class="controls"><input class="span12" id="lookup_desc" maxlength="50" name="lookup_desc" type="text" value="<?php echo $lookup_desc?>" /></div>
					</div>
					
					<div class="control-group" id="form_order">
						<label class="control-label" for="order">Order</label>
					
					<div class="controls">
					<select class="span12" name="order" id="order">
								
								<option value="">--choose--</option>
									<?php 
									$lastOrd = "";
									$getOrd = $this->mbts_reason_change->getOrder();
									foreach($getOrd as $zx){
										$selectOrd = ($zx->order == $order ? " SELECTED ":"");
										$lastOrd = $zx->order + 1;
										echo"<option value='$zx->order' $selectOrd >$zx->order</option>";
									}

									$lastOrd = ($lastOrd == "") ? "1" : $lastOrd;

									if($id == "")
										echo"<option value='$lastOrd' $selectOrd >$lastOrd</option>";
									?>

					</select>
					</div>
				</div>
					<hr>
					
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>