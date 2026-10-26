	<style type="text/css">
		select{width:auto;}
	 	table input[type="checkbox"]{
	 		margin: -3px 5px 0 0;
	 	}
	.widget { border:none; }
	.form-horizontal .control-label { text-align:left; }
	fieldset { margin-bottom:20px; border-bottom:1px solid #CCCCCC; padding:0px; }
	legend { border-bottom:1px solid #CCCCCC; margin:0 0 4px; }
	legend + .control-group { margin-top:0px; }
	.form-horizontal .column-left { float:left; }
	.form-horizontal .column-right { float:left; margin-left:80px; }
	.form-horizontal .control-group { margin-bottom:4px; }
	.form-horizontal .control-group:last-child { margin:0 0 4px; }
	.btn { padding:4px 10px; }
	.table-primary thead th { font-size:12px; }
	.panel { background:#FFFFFF; }
	fieldset{margin-top:0px;margin-bottom:none;border-bottom: none;}
	legend{margin-bottom:10px;}
	.equipment{display: none;}
	.sinfo{font-size:10px;color:gray;}
	</style>

<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form2" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >					
					
					<div class="control-group" id="form_name">
						<label class="control-label" for="checklist_group_id">Parent</label>
						<div class="controls">
							
<select class="span12" name="name" id="name">
<option value='1'>>>> Checklist Group <<<</option>
<?
if (count($parentx) > 0) {
foreach($parentx as $parent) 
echo '<option value="'.$parent['group_id'].'"'.($parent['group_id'] == $checklist_group_id ? ' selected' : '').'>'.str_repeat('&nbsp;',$parent['level']*3).$parent['group_name'].'</option>';
}

?>
</select>

						</div>
					</div> 
					
					<div class="control-group" id="form_descr">
						<label class="control-label" for="descr">Checklist Name</label>
						<div class="controls"><input placeholder="Description" class="span12" id="descr" name="descr" type="text" value="<?php echo $checklist_name?>" /></div>
					</div>
					
					<div class="control-group" id="form_urutan">
						<label class="control-label" for="urutan">Order</label>
						<div class="controls"><input class="input-small number" placeholder="" maxlength="5" id="urutan" name="urutan" type="text" value="<?php echo $order_number?>" /></div>
					</div>
					
					<div class="control-group" id="form_interface">
						<label class="control-label" for="interface">Interface</label>
						<div class="controls">
								<input type="radio" class="radio" name="interface" value="android" <? if($interface=="android"){ echo'checked="checked"';} ?> checked />
								Android&nbsp;&nbsp;
								<input type="radio" class="radio" name="interface" value="desktop" <? if($interface=="desktop"){ echo'checked="checked"';} ?> />
								Desktop&nbsp;&nbsp;
						</div>
					</div>
					
					<div class="control-group" id="form_descr">					
						<label class="control-label" for="descr">Photo</label>
						<div class="controls">
						<?
							if($has_photo==1){
								$selecthas_photo = 'checked="checked"';
							}else{
								$selecthas_photo = '';
							}
						?>
								<input type="checkbox" class="checkbox" name="has_photo" value="1" <?php echo $selecthas_photo?>  />
							Has Photo&nbsp;&nbsp; | 
							Total Photo &nbsp;&nbsp;&nbsp;<input class="input-mini just-number" onKeyUp="this.value = this.value.replace (/\D/, '')" id="totphoto" placeholder="123" 
							name="totphoto" type="text" value="<?php echo $total_photo?>" />

						</div>
					</div>
					
										
					<div class="control-group" id="form_mandatory">					
						<label class="control-label" for="mandatory">Mandatory</label>
						<div class="controls">
						<?
							if($mandatory==1){
								$mandatory_checked = 'checked="checked"';
							}else{
								$mandatory_checked = '';
							}					
						?>
								<input type="checkbox" class="checkbox" name="mandatory" value="1" <?php echo $mandatory_checked?>  />
						</div>
					</div>
					
					
					<div class="control-group" id="form_property_id">
						<label class="control-label" for="property_id">Answer Type</label>
						<div class="controls">
						<select style="text-transform:capitalize;" class="span4" name="property_id" id="property_id">
							<option value="">--choose--</option>
							<?php
								$getProperty = $this->mpm_checklist->getProperty();
								foreach($getProperty as $r){
									$select = ($r->checklist_type_id == $checklist_type ? " SELECTED ":"");
									echo"<option value='$r->checklist_type_id' $select>$r->tnama</option>";
								}
							?>
						</select>
						</div>
					</div>
					
					<div class="control-group" id="form_option">
						<label class="control-label" for="option">Option</label>
						<div class="controls">
					<table class="table table-bordered table-striped">
							<thead class="text-center"><th>Option</th><th>Follow Up</th><th>Action</th></thead>
							<tbody id="tambah">
						<?php if (!empty($id)) {		
								$rfCounter = 1;	
								foreach ($option as $rf) {	
										
								 ?>
									<tr id="control<?php echo $rfCounter ?>" class="controls ctl-option">
									<td><input class="span12 myoption" id="option<?php echo $rfCounter;?>" name="option[]" type="text" value="<?php echo $rf['option']?>" /></td>
									<?
											if($rf['is_critical']==1){
												$is_critical_checked = 'checked = checked';
												
											}else{
												$is_critical_checked = '';
											}				
									?>
									<td>
										<input id="is_critical2_<?php echo $rfCounter;?>" onclick="setIsCritical(<?php echo $rfCounter;?>)" class="span4 checkbox" type="checkbox" name="is_critical2[]" value="1" <?php echo $is_critical_checked?>/>
										<input id="is_critical_<?php echo $rfCounter;?>" type='hidden' name='is_critical[]' value='<?php echo $rf['is_critical']?>' >					
										</td> 
									<td><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="removeBTSRow(<?php echo $rfCounter ?>)"><i></i> Remove</a></td>
									<!-- <br /> -->
									</tr>
								
							<?  $rfCounter++;
								}
							}else{
							?>
							<tr id="control1" class="controls ctl-option">
							<td><input class="span12" id="option1" name="option[]" type="text" /></td>
							<td><input id="is_critical2_" class="span4 checkbox" type="checkbox" name="is_critical2[]" value="1"/><input id="is_critical_" type='hidden' name='is_critical[]' value='' >	</td> 
							<td><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="removeBTSRow(1)"><i></i> Remove</a></td>
<?php 							
							}
						 ?>

</tbody>
						<table>
						<div  style="padding-top : 5px">
						<a class="btn btn-icon glyphicons circle_plus" href="javascript:void(0)" onclick="addBTSRow()"><i></i> Add Option</a>
						</div>
						</div>	
					</div>
				
				<div class="form-actions">
						<button type="button" class="btn btn-icon btn-primary glyphicons circle_remove" onclick="closeFancybox()"><i></i>Close</button>
						<button type="button" class="btn btn-icon btn-primary glyphicons circle_ok" onclick="saveForm()"><i></i>Save</button>
				</div>
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>
<script>
<?php if (!empty($id)) { ?>
var btsCounter = <?php echo $rfCounter ?>;
<?php } else { ?>
var btsCounter = 2;
<?php } ?>

var addBTSRow = function() {
	var html = '<tr id="control' + btsCounter + '" class="controls ctl-option"><td><input placeholder="option' + btsCounter + '" class="span12" id="option' + btsCounter + '" name="option[]" type="text"  />&#32</td><td><input id=is_critical2_'+ btsCounter +' onclick="setIsCritical('+ btsCounter +')" type="checkbox" class="span4 checkbox" name="is_critical2[]" value="1"/><input id="is_critical_' + btsCounter + '" type="hidden" name="is_critical[]" value="" ></td><td><a class="btn btn-icon glyphicons remove" href="javascript:void(0)" onclick="removeBTSRow(' + btsCounter + ')"><i></i> Remove</a></td>';

	if (checkOptions()) {
		$('#tambah').append(html);
	}
	
	btsCounter++;
};

var removeBTSRow = function(counter) {
	var btsRowElem = document.getElementById('control' + counter);
	btsRowElem.parentNode.removeChild(btsRowElem);
};

var checkOptions = function() {
	var opt = document.getElementsByName('option[]');
	
	var a = [];
	for (var i = 0; i < opt.length; i++) {
		a[i] = opt[i].value;
	}
	var sorted_arr = a.sort();
	var results = [];
	for (var i = 0; i < a.length - 1; i++) {
		if (sorted_arr[i + 1] == sorted_arr[i]) {
			results[i] = sorted_arr[i];
		}
	}
	
	if (results.length > 0) {
		alert('Option can not be duplicate value.');
		var ctlOption = document.getElementsByClassName('ctl-option');
		//ctlOption[ctlOption.length - 1].parentNode.removeChild(ctlOption[ctlOption.length - 1]);
		opt[opt.length - 1].select();
		return false;
	} else {
		return true;
	}
};

var isNumeric = function(sText) {
	var validChars = "0123456789";
	var isNumber = true;
	var character;
 
	for (var i = 0; i < sText.length && isNumber == true; i++) {
		character = sText.charAt(i);
		if (validChars.indexOf(character) == -1) {
			isNumber = false;
		}
	}
	
	return isNumber;
};




</script>
<script>

					
function saveForm (){
	var property_id = $('#property_id').val();

	var opt = document.getElementsByName('option[]');
	var a = [];
	for (var i = 0; i < opt.length; i++) {
		if(opt[i].value !='')
		{
			a[i] = opt[i].value;
		}
	}

				var okayz = true;
			if (a.length == 0 && property_id==2) {
				okayz = false;
				alert('Option required.');
			} else {
				okayz = true;
				
			}
		
		if (okayz) {					
						$.ajax({
							type: "POST",
							url: "<?php echo base_url()?>referensi/pm_checklist_group/saveDataChecklist",
							data: $('#add-form2').serialize(),
							dataType: "json",
							beforeSend:function(){
									//$('.alert-error,.alert-success').hide();
							},
							error: function(jqXHR, textStatus, errorThrown){
									alert(errorThrown);
							},
							success: function(data){
									if(data.status == "OK"){
										notyfy({layout: 'center',text: 'Data Saved', type: 'success'});
										window.location.href = "<?php echo base_url();?>referensi/pm_checklist_group/index";
									}else{
											alert( data.hasil );
									}
							}
						});
						return false;					
			}
			}		
    function setIsCritical(id){
    	 if($('#is_critical2_'+id).is(':checked'))
    	 {
    	 $('#is_critical_'+id).val(1);
    	 }else {
    	 $('#is_critical_'+id).val(0);
    	 }
    	 }

</script>