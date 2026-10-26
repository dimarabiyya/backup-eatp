<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					
					
					
					<div class="control-group" id="form_lookup_desc">
						<label class="control-label" for="lookup_desc">Lookup Desc</label>
						<div class="controls"><input class="span12" id="lookup_desc" name="lookup_desc" type="text" value="<?php echo $lookup_desc?>" /></div>
					</div>
					
					<div class="control-group" id="form_order">
						<label class="control-label" for="order">Order</label>
						<div class="controls"><input class="span12" id="order" name="order" type="text" value="<?php echo $order?>" /></div>
					</div>
					
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>