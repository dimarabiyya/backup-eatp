<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					
					<div class="control-group" id="form_name">
						<label class="control-label" for="name">Name</label>
						<div class="controls"><input class="span12" id="name" name="name" type="text" value="<?php echo $name?>" /></div>
					</div>
					
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>