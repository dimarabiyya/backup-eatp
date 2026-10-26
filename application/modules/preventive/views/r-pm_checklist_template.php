
<div class="innerLR">
<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
					
					
					
					<div class="control-group" id="form_name">
						<label class="control-label" for="name">Template Option</label>
						<div class="controls">
							<select class="span12" name="name" id="name">
							<option value="">--Choose--</option>
							<?php
							$getPro = get_instance()->mpm_checklist_template->getOption();
							foreach($getPro as $z){	
							
								if(($z->option_id)==$checklist_group_id){
									$selected='selected';
								}else{
								$selected='';
								}
								echo"<option value='$z->option_id' $selected >$z->option_label</option>";
							}
							?>
							</select>
						</div>
					</div> 
					<div class="control-group" id="form_descr">
						<label class="control-label" for="descr">Checklist</label>
						<div class="controls">
						<div class="accordion" id="accordion2">
						<?php
								foreach (get_instance()->mpm_checklist_template->getGridParent(1) as $k) {
									echo"<div class='accordion-group'>
											<div class='accordion-heading'>
												<a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion2' href='#collapse-$k->checklist_group_id'>
													$k->group_name
												</a>
											</div>
											<div id='collapse-$k->checklist_group_id' class='accordion-body collapse'>
												<div class='accordion-inner' id='check$k->checklist_group_id'>"; ?>
												<input class="btn btn-small" type="button" value="Check All" onclick="checkByParent('check<?php echo $k->checklist_group_id?>', true);">
												<input class="btn btn-small" type="button" value="Uncheck All" onclick="checkByParent('check<?php echo $k->checklist_group_id?>', false);">
											<?	echo"<hr>";
													foreach (get_instance()->mpm_checklist_template->getChecklist($k->checklist_group_id) as $h){
														if($h->interface=='android'){
															echo"<img title='Android' src='".base_url()."assets/images/interface-android.png' />";
														}else{
															echo"<img title='Desktop' src='".base_url()."assets/images/interface-desktop.png' />";
														}
														echo" <input type=checkbox class='checkbox' name=ck[] value='$h->checklist_id'> $h->checklist_name<br />";
													}
												echo"</div>
											</div>
										</div>";
									foreach (get_instance()->mpm_checklist_template->getGridParent($k->checklist_group_id) as $c){
									echo"<div class='accordion-group'>
											<div class='accordion-heading'>
												<a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion2' href='#collapse-$c->checklist_group_id'>
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=> $c->group_name
												</a>
											</div>
											<div id='collapse-$c->checklist_group_id' class='accordion-body collapse'>
												<div class='accordion-inner' id='check$c->checklist_group_id'>"; ?>
												<input class="btn btn-small" type="button" value="Check All" onclick="checkByParent('check<?php echo $c->checklist_group_id?>', true);">
												<input class="btn btn-small" type="button" value="Uncheck All" onclick="checkByParent('check<?php echo $c->checklist_group_id?>', false);">
											<?	echo"<hr>";
													foreach (get_instance()->mpm_checklist_template->getChecklist($c->checklist_group_id) as $j){
														if($j->interface=='android'){
															echo"<img title='Android' src='".base_url()."assets/images/interface-android.png' />";
														}else{
															echo"<img title='Desktop' src='".base_url()."assets/images/interface-desktop.png' />";
														}
														echo" <input type=checkbox class='checkbox' name=ck[] value='$j->checklist_id'> $j->checklist_name<br />";
													}
												echo"</div>
											</div>
										</div>";
										foreach (get_instance()->mpm_checklist_template->getGridParent($c->checklist_group_id) as $d){
										echo"<div class='accordion-group'>
											<div class='accordion-heading'>
												<a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion2' href='#collapse-$d->checklist_group_id'>
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=> $d->group_name
												</a>
											</div>
											<div id='collapse-$d->checklist_group_id' class='accordion-body collapse'>
												<div class='accordion-inner' id='check$d->checklist_group_id'>"; ?>
												<input class="btn btn-small" type="button" value="Check All" onclick="checkByParent('check<?php echo $d->checklist_group_id?>', true);">
												<input class="btn btn-small" type="button" value="Uncheck All" onclick="checkByParent('check<?php echo $d->checklist_group_id?>', false);">
											<?	echo"<hr>";
													foreach (get_instance()->mpm_checklist_template->getChecklist($d->checklist_group_id) as $k){
														if($k->interface=='android'){
															echo"<img title='Android' src='".base_url()."assets/images/interface-android.png' />";
														}else{
															echo"<img title='Desktop' src='".base_url()."assets/images/interface-desktop.png' />";
														}
														echo" <input type=checkbox class='checkbox' name=ck[] value='$k->checklist_id'> $k->checklist_name<br />";
													}
												echo"</div>
											</div>
										</div>";
									}
									}
								}
							?>
						
						</div>
						</div>
					</div>
					
					<div class="form-actions">
						<a href="<?php echo base_url()?>preventive/pm_checklist_template" ><input TYPE="button" class="btn btn-icon btn-primary glyphicons circle_ok" VALUE="Back"></a>
						<input type="submit" class="btn btn-icon btn-primary glyphicons circle_ok" value="Save" >
					</div>
                                        
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>
</div>
<script type="text/javascript">

function checkByParent(aId, aChecked) {
    var collection = document.getElementById(aId).getElementsByTagName('INPUT');
    for (var x=0; x<collection.length; x++) {
        if (collection[x].type.toUpperCase()=='CHECKBOX')
            collection[x].checked = aChecked;
    }
}
</script>
<script>
$(document).ready(function() {
		$("#menu_164").parent().addClass('active').find('.collapse').css('height','auto');
});

$('#add-form').submit(function() {

			if ($('input[name=ck[]]:checked').length == 0) {
				notyfy({layout: 'center', text: 'At least one checklist item must be selected', type: 'error'});
				//alert("At least one checklist item must be selected");
			}else{
                $.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>preventive/pm_checklist_template/saveData",
                        data: $(this).serialize(),
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
                                        $('#add-form').trigger('reset');
										window.location.href = "<?php echo base_url();?>preventive/pm_checklist_template";
                                }else{
										notyfy({layout: 'center',text: data.hasil, type: 'error'});
                                }
                        }
                });
				}
				 return false;
	});
	</script>