<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					
					
					
					<div class="control-group" id="form_type">
						<label class="control-label" for="type">Type</label>
						<div class="controls"><input class="span12" id="type" maxlength="100" name="type" type="text" value="<?php echo $type?>" /></div>
					</div>
					
					<div class="control-group" id="form_band">
						<label class="control-label" for="band">Band</label>
						<div class="controls"><input class="input-small number" id="band" maxlength="5" onKeyUp="this.value = this.value.replace (/\D/, '')" name="band" type="text" value="<?php echo $band?>" /></div>
					</div>
					
					<div class="control-group" id="form_beamwidth">
						<label class="control-label" for="beamwidth">Beamwidth</label>
						<div class="controls"><input class="input-small number" id="beamwidth" maxlength="5" onKeyUp="this.value = this.value.replace (/\D/, '')" name="beamwidth" type="text" value="<?php echo $beamwidth?>" /></div>
					</div>
					
					<div class="control-group" id="form_beamwidth_value">
						<label class="control-label" for="beamwidth_value">Beamwidth Value</label>
						<div class="controls"><input class="input-small number" id="beamwidth_value" maxlength="5" onKeyUp="this.value = this.value.replace (/\D/, '')" name="beamwidth_value" type="text" value="<?php echo $beamwidth_value?>" /></div>
					</div>
					
					<div class="control-group" id="form_dimension_l">
						<label class="control-label" for="dimension_l">Dimension L</label>
						<div class="controls"><input class="input-small number" id="dimension_l" maxlength="5" onKeyUp="this.value = this.value.replace (/\D/, '')" name="dimension_l" type="text" value="<?php echo $dimension_l?>" /></div>
					</div>
					
					<div class="control-group" id="form_dimension_h">
						<label class="control-label" for="dimension_h">Dimension H</label>
						<div class="controls"><input class="input-small number" id="dimension_h" maxlength="5" onKeyUp="this.value = this.value.replace (/\D/, '')" name="dimension_h" type="text" value="<?php echo $dimension_h?>" /></div>
					</div>
					
					<div class="control-group" id="form_dimension_w">
						<label class="control-label" for="dimension_w">Dimension W</label>
						<div class="controls"><input class="input-small number" id="dimension_w" maxlength="5" onKeyUp="this.value = this.value.replace (/\D/, '')" name="dimension_w" type="text" value="<?php echo $dimension_w?>" /></div>
					</div>
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>