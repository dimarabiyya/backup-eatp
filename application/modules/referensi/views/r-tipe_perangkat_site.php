<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					
					<div class="control-group" id="form_nama">
						<label class="control-label" for="nama">Type Prangkat Site</label>
						<div class="controls"><input class="span12" id="nama" name="nama" type="text" value="<?php echo $nama?>" /></div>
					</div>
					
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>