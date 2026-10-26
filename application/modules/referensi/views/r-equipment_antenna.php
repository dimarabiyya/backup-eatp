<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading">Edit Equipment Antenna</h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					
					
					
					<div class="control-group" id="form_brand">
						<label class="control-label" for="brand">Brand</label>
						<div class="controls"><input class="span12" id="brand" maxlength="50" name="brand" type="text" value="<?php echo $brand?>" /></div>
					</div>
				
					<div class="control-group" id="form_model">
						<label class="control-label" for="model">Model</label>
						<div class="controls"><input class="span12" id="model" maxlength="100" name="model" type="text" value="<?php echo $model?>" /></div>
					</div>
					
					<div class="control-group" id="form_band">
						<label class="control-label" for="band">Band</label>
						<div class="controls"><input class="input-mini just-number" id="band" maxlength="50" name="band" type="text" value="<?php echo $band?>" /></div>
					</div>
					
					<div class="control-group" id="form_hbwd">
						<label class="control-label" for="hbwd">HBWD</label>
						<div class="controls"><input class="input-mini just-number" id="hbwd" maxlength="50" name="hbwd" type="text" value="<?php echo $hbwd?>" /></div>
					</div>
					
					<div class="control-group" id="form_vbwd">
						<label class="control-label" for="vbwd">VBWD</label>
						<div class="controls"><input class="input-mini just-number" id="vbwd" maxlength="50" name="vbwd" type="text" value="<?php echo $vbwd?>" /></div>
					</div>
					
					<div class="control-group" id="form_etilt">
						<label class="control-label" for="etilt">ETILT</label>
						<div class="controls"><input class="input-mini just-number" id="etilt" maxlength="50" name="etilt" type="text" value="<?php echo $etilt?>" /></div>
					</div>
					
					<div class="control-group" id="form_polarization">
						<label class="control-label" for="polarization">Polarization</label>
						<div class="controls"><input class="input-mini just-number" id="polarization" maxlength="32" name="polarization" type="text" value="<?php echo $polarization?>" /></div>
					</div>
					
					<div class="control-group" id="form_vswr">
						<label class="control-label" for="vswr">VSWR</label>
						<div class="controls"><input class="input-mini just-number" id="vswr" maxlength="24" name="vswr" type="text" value="<?php echo $vswr?>" /></div>
					</div>
					 
					 <div class="control-group" id="form_impedance">
						<label class="control-label" for="impedance">Impedance</label>
						<div class="controls"><input class="input-mini just-number" id="impedance" maxlength="32" name="impedance" type="text" value="<?php echo $impedance?>" /></div>
					</div>
					
					<div class="control-group" id="form_gain">
						<label class="control-label" for="gain">Gain</label>
						<div class="controls"><input class="input-mini just-number" id="gain" maxlength="50" name="gain" type="text" value="<?php echo $gain?>" /></div>
					</div>
					
					<div class="control-group" id="form_max_input_power">
						<label class="control-label" for="max_input_power">Max Input Power</label>
						<div class="controls"><input class="input-mini just-number" id="max_input_power" maxlength="50" name="max_input_power" type="text" value="<?php echo $max_input_power?>" /></div>
					</div>
					
					<div class="control-group" id="form_isolations">
						<label class="control-label" for="isolations">Isolations</label>
						<div class="controls"><input class="input-mini just-number" id="isolations" maxlength="32" name="isolations" type="text" value="<?php echo $isolations?>" /></div>
					</div>
					
					<div class="control-group" id="form_weight">
						<label class="control-label" for="weight">Weight</label>
						<div class="controls"><input class="input-mini just-number" id="weight" maxlength="24" name="weight" type="text" value="<?php echo $weight?>" /></div>
					</div>
					
					<div class="control-group" id="form_dimension_long">
						<label class="control-label" for="dimension_long">Dimension Long</label>
						<div class="controls"><input class="input-mini just-number" id="dimension_long" maxlength="24" name="dimension_long" type="text" value="<?php echo $dimension_long?>" /></div>
					</div>
					
					<div class="control-group" id="form_dimension_height">
						<label class="control-label" for="dimension_height">Dimension Height</label>
						<div class="controls"><input class="input-mini just-number" id="dimension_height" maxlength="24" name="dimension_height" type="text" value="<?php echo $dimension_height?>" /></div>
					</div>
					
					<div class="control-group" id="form_dimension_width">
						<label class="control-label" for="dimension_width">Dimension Width</label>
						<div class="controls"><input class="input-mini just-number" id="dimension_width" maxlength="24" name="dimension_width" type="text" value="<?php echo $dimension_width?>" /></div>
					</div>
					        
				</div>                  
				</div> <hr>  
			</div>
			
		</div>
		
	</div>
	
</form>