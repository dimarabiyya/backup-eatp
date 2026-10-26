<script>
$(document).ready(function() {
	$('#nama').attr('disabled', 'disabled');	
});
				</script>
<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                                        <input type='hidden' name='id' value='<?php echo $id?>' >
					<div class="control-group" id="form_parent">
						<label class="control-label" for="parent">Parent Menu</label>
						<div class="controls">
							<select class="span12" name="parent" id="parent">
							<?php
							$getMenu = $this->mmenu->getMenu();
							foreach($getMenu as $r){
								$select = ($r->menu_id == $parent_id ? " SELECTED ":"");
								echo"<option value='$r->menu_id' $select >$r->name</option>";
							}
							?>
							</select>
						</div>
					</div>
					<div class="control-group" id="form_nama">
						<label class="control-label" for="nama">Menu Name</label>
						<div class="controls"><input class="span12" id="nama" name="nama" type="text" value="<?php echo $name?>" /></div>
					</div>
					<div class="control-group" id="form_url">
						<label class="control-label" for="url">URL</label>
						<div class="controls"><input class="span12" id="url" name="url" type="text" value="<?php echo $alias?>" /></div>
					</div>
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>