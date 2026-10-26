<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					
					<input type='hidden' name='order_no_ori' value='<?php echo $order_no?>' >
					
					<div class="control-group" id="form_descr">
						<label class="control-label" for="descr">Group Name</label>
						<div class="controls"><input class="span12" id="descr" name="descr" type="text" value="<?php echo $descr?>" /></div>
					</div>
					
					<div class="control-group" id="form_order_no">
						<label class="control-label" for="order_no">Order No.</label>

						
						<div class="controls">
							
							<select class="span12" name="order_no" id="order_no">
								
								<option value="">--choose--</option>
									<?php 
									$lastOrd = "";
									$getOrd = $this->mpreparation_type->getOrder();
									foreach($getOrd as $zx){
										$selectOrd = ($zx->order_no == $order_no ? " SELECTED ":"");
										$lastOrd = $zx->order_no + 1;
										echo"<option value='$zx->order_no' $selectOrd >$zx->order_no</option>";
									}

									$lastOrd = ($lastOrd == "") ? "1" : $lastOrd;

									if($id == "")
										echo"<option value='$lastOrd' $selectOrd >$lastOrd</option>";
									?>

							</select>

						</div>
					                                        
				</div>
                                      
				</div><hr>  
			</div>
			
		</div>
		
	</div>
	
</form>