<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					<div class="control-group" id="form_parent">
						<label class="control-label" for="parent">Perangkat Type</label>
						<div class="controls">
							<select class="span12" name="type" id="type">
							<?php
							$getMenu = $this->mtipe_perangkat_ceklis_group->getMenu();
							foreach($getMenu as $r){
								$select = ($r->perangkat_id == $perangkat_id ? " SELECTED ":"");
								echo"<option value='$r->perangkat_id' $select >$r->tname</option>";
							}
							?>
							</select>
						</div>
					</div>
					<div class="control-group" id="form_nama">
						<label class="control-label" for="nama">Type Prangkat Site</label>
						<div class="controls"><input class="span12" id="nama" name="nama" type="text" value="<?php echo $nama?>" /></div>
					</div>
					
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>