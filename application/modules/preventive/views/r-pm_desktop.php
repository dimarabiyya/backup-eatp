	<div class="innerLR">
	<div class="heading-buttons">
		<h3><?php echo $judul?></h3>
		
	</div>
	<div class="innerLR">
	<div class="widget">	
		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i>Detail Info</h4>
			<a href="<?php echo base_url()?>preventive/pm_desktop" ><button class="btn btn-primary btn-icon glyphicons unshare btn-heading pull-right" style="margin-right:-8px"><i></i>Back to Checklist Desktop</button></a>
		</div>
		<div class="widget-body">
		<table class="table table-bordered table-condensed">
			<tr>
				<td  width="20%">Assigned PIC</td>
				<td><?php echo $site->user_name; ?></td>
			</tr>
			<tr>
				<td>Site ID </td>
				<td><?php echo $site->site_code; ?></td>
			</tr>
			<tr>
				<td>Region </td>
				<td><?php echo $site->region_name; ?></td>
			</tr>
			<tr>
				<td>Cluster</td>
				<td><?php echo $site->cluster_name; ?></</td>
			</tr>
			<tr>
				<td>Site Name </td>
				<td><?php echo $site->site_name; ?></td>
			</tr>	
			<tr>
				<td>Visit Type</td>
				<td><?php echo $site->visit_type; ?></</td>
			</tr>
			<tr>
				<td>Visit Date</td>
				<td><?php echo $site->visit_date; ?></</td>
			</tr>
		</table>
		<div class="separator bottom"></div>
		<div class='separator'></div>
		<?php
			foreach (get_instance()->mpm_desktop->getGridParent($ids) as $k) {
				/* $s = $k->checklist_group_id;
				$xx = get_instance()->mpm_desktop->getOrder($ids);
				if( $xx->order_number == $k->order_number){
					$active = "active";
				}else{
					$active = "";
				} */
				echo '<div class="accordion-group active">';
				echo '<div class="accordion-heading">';
				echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#tab-'.$k->checklist_group_id.'">'.$k->group_name.'</a>';
				echo '</div>';
				#######################################################
				echo '<div id="tab-'.$k->checklist_group_id.'" class="accordion-body collapse">';
				echo '<div class="accordion-inner">';
				foreach (get_instance()->mpm_desktop->getGridDesktop($ids,$k->checklist_group_id) as $h) {
					echo'	<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#checklist-'.$h->checklist_id.'">'.$h->checklist_name.'</a>
								</div>
								<div id="checklist-'.$h->checklist_id.'" class="accordion-body collapse">
									<div class="accordion-inner">';
										echo"<form class='form-horizontal add-form' action='".base_url()."preventive/pm_desktop/saveData' enctype='multipart/form-data' style='margin-bottom: 0;' method='POST' id='add-form-".$h->checklist_id."' ><div class='row-fluid'>
										<div class='span3'>
											<strong>Checklist Name ".get_instance()->session->flashdata('message')." </strong>
											<p class='muted'>$h->checklist_name </p>
										</div>
										<div class='span9'>
											<label for='inputTitle'>Answer</label>
											<input type='hidden' id='cid' name='cid' value='$h->checklist_id' >
											<input type='hidden' id='vid' name='vid' value='$ids' >
											<input type='hidden' id='sid' name='sid' value='$h->site_id' >";
										foreach (get_instance()->mpm_desktop->getAttribute($ids,$h->checklist_group_id, $h->checklist_id) as $r) {
											//print_r($value = get_instance()->mpm_desktop->getValue($ids,$r->checklist_id));
											if($r->type_name=="textbox"){
												echo "<input type='hidden' id='rid[0]' name='rid[0]' value='$r->checklist_result_id' />";
												echo "<input type='text' name='answer[]' class='span6' value='$r->pm_result' maxlength='100' required='required'/>";
											}elseif($r->type_name=="textarea"){
												echo "<input type='hidden' id='rid[]' name='rid[]' value='$r->checklist_result_id' />";
												echo "<textarea name='answer[]' class='span6' required='required' maxlength='100'/>$r->pm_result</textarea>";
											}elseif($r->type_name=="datepicker"){
												echo "<input type='hidden' id='rid[]' name='rid[]' value='$r->checklist_result_id' />";
												echo "<input type='text' name='answer[]' class='span6 date' value='$r->pm_result' required='required'/>";
											}elseif($r->type_name=="radiobutton"){
												foreach (get_instance()->mpm_desktop->getAnswerRadio($r->checklist_id) as $z) {
													if($r->pm_result == $z->option){
														echo "<input type='hidden' id='rid[]' name='rid[]' value='$r->checklist_result_id' />";
														echo "<input type='radio' name='answer[]' value='$z->option' checked/> $z->option <br>";
													}else{
														echo "<input type='radio' name='answer[]' value='$z->option'/> $z->option <br>";
													}
												}												
											}
											/* foreach(get_instance()->mpm_desktop->getPhoto($ids,$h->checklist_id) as $t){
												//echo'</br>';
												//<a id="delete-'.$r->result_photo_id.'" class="btn btn-primary fileupload-exists delete" data-group="'.$r->result_photo_id.'::'.$r->photo_name.'::'.$r->visit_id.'::'.$r->checklist_id.'" style="inline-block">Delete</a>
												echo'<div class="span 6" style="margin-top:10px; margin-left:0px;">
														<input type="hidden" id="pid" name="pid" value="'.$t->result_photo_id.'" style="width:100px">
														<input type="hidden" id="pname" name="pname" value="'.$t->photo_name.'" style="width:100px">
														<a id="photo" href="'.base_url().'file/upload/pm/'.$t->photo_name.'" data-group="'.$t->result_photo_id.'::'.$t->photo_name.'::'.$t->visit_id.'::'.$t->checklist_id.'" class="real-image"><img src="'.base_url().'file/upload/pm/'.$t->photo_name.'" width="125" height="125" class="img-polaroid"/></a>
														<div class="fileupload fileupload-new input-append"  data-provides="fileupload">
																<div class="uneditable-input span3" style="width:150px;"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div>
																<span class="btn btn-primary btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="userfile'.$x.'" required="required"/></span><a href="#" class="btn btn-primary fileupload-exists" data-dismiss="fileupload">Remove</a>
															</div>
													</div>';
											}*/
										}
										foreach (get_instance()->mpm_desktop->getAttribute2($ids,$h->checklist_group_id, $h->checklist_id) as $r) {
											if($r->type_name=="checkbox"){
												foreach (get_instance()->mpm_desktop->getAnswerCheck($ids,$r->checklist_id) as $z) {
													echo "<input type='hidden' id='rid[]' name='rid[]' value='$z->checklist_result_id' />";
													echo "<input type='checkbox' name='answer[]' value='$z->option' checked /> $z->option <br>";
												}
												foreach (get_instance()->mpm_desktop->getAnswer($ids,$r->checklist_id) as $z) {
													echo "<input type='checkbox' name='answer[]' value='$z->option' /> $z->option <br>";
												}
											}
										}
										foreach (get_instance()->mpm_desktop->getAttribute($ids,$h->checklist_group_id, $h->checklist_id) as $r) {
											if($r->has_photo=="1"){
												echo"<label for='inputTitle'>File Upload - Max. Upload $r->total_photo Files</label>";
												for ($x=1; $x<=$h->total_photo; $x++){
													echo'<div class="fileupload fileupload-new" data-provides="fileupload">
															<div class="input-append">
																<div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div>
																	<span class="btn btn-primary btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="u'.$x.'" required="required"/></span><a href="#" class="btn btn-primary fileupload-exists" data-dismiss="fileupload">Remove</a>
																</div>
															</div>';
												} 
													echo'<div class="span 6" style="margin-top:10px; margin-left:0px;">';
													foreach(get_instance()->mpm_desktop->getPhoto($ids,$h->checklist_id) as $t){
														echo'<input type="hidden" id="pid[]" name="pid[]" value="'.$t->result_photo_id.'" style="width:100px">
																<input type="hidden" id="pname" name="pname" value="'.$t->photo_name.'" style="width:100px">
																<a id="photo" href="'.base_url().$t->photo_name.'" data-group="'.$t->result_photo_id.'::'.$t->photo_name.'::'.$t->visit_id.'::'.$t->checklist_id.'" class="real-image">
																	<img loading="lazy" decoding="async" src="'.base_url().$t->photo_name.'" width="125" height="125" class="img-polaroid"/>
																</a>';
													}
													echo'</div>';
												}
										}
											
										echo"<div class='separator'></div>
										</div>";
										echo"</div>
											<div class='form-actions'>
											<input type='submit' class='btn btn-icon btn-primary glyphicons circle_ok save' value='Save' >
											</div> </form>";
										echo'</div>
								</div>
							</div>';
				}								
					get_instance()->mpm_desktop->status($ids);
					echo"</div>";
					echo"</div>";
				#######################################################
					echo"</div>";
			}								
					/* echo"<div class='form-actions'>
							<a href='".base_url()."preventive/pm_desktop/saveData'><input type='submit' class='btn btn-icon btn-primary glyphicons circle_ok save' value='Save All'></a>
						</div>"; */
		?>
	</div>
	</div>		
	</div>
	</div>
		
	<!-- Bootstrap Form Wizard -->
	<script type="text/javascript">
	$(document).ready(function() {
		$("#menu_164").parent().addClass('active').find('.collapse').css('height','auto');
	});
	$('.real-image').fancybox({
		type: 'image',
		transitionIn : 'elastic',
		transitionOut: 'elastic',
		closeBtn: true
	});
	
	
	
	/* $('.add-form').submit(function(e) {
			$.ajax({
				type: "POST",
				url: "<?php echo base_url()?>preventive/pm_desktop/saveData",
				data: $(e.target).serialize(),
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
						//$('#add-form').trigger('reset');
						//window.location.href = "<?php echo base_url();?>preventive/pm_desktop/addeditdata/"+id;
					}else{
						notyfy({layout: 'center',text: data.hasil, type: 'error'});
					}
				}
			});
		return false;
	}); */
	
	
	$(document).find('.datepicker').datepicker({dateFormat: $.datepicker.W3C, changeMonth: true, changeYear: true, yearRange: '2011:2020'})
	
	var dateFormat = {
	
	monthsName: { '01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr', '05': 'May', '06': 'Jun', '07': 'Jul', '08': 'Aug', '09': 'Sep', '10': 'Oct', '11': 'Nov', '12': 'Dec' },
	monthsCode: { 'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'May': '05', 'Jun': '06', 'Jul': '07', 'Aug': '08', 'Sep': '09', 'Oct': '10', 'Nov': '11', 'Dec': '12' },
	fields: document.getElementsByClassName('date'),
	
	init: function() {
		$('.date').datepicker({
			dateFormat: 'dd-M-yy',
			changeMonth: true,
			changeYear: true
		});
		
		this.defaultMode();
		
		for (var i = 0; i < this.fields.length; i++) {
			(function(idx) {
			
				dateFormat.fields[idx].onfocus = function() {
					var day = this.value.split('-')[0];
					var month = this.value.split('-')[1];
					var year = this.value.split('-')[2];
					
					if (typeof dateFormat.monthsCode[month] != 'undefined') {						
						this.value = day + '-' + dateFormat.monthsCode[month] + '-' + year;
					}
				};
			
				dateFormat.fields[idx].onblur = function() {
					if (this.value != '') {
						if (!dateFormat.checkValidDate(this.value)) {
							this.value = '';
						}
					}
					
					var day = this.value.split('-')[0];
					var month = this.value.split('-')[1];
					var year = this.value.split('-')[2];
					
					if (typeof dateFormat.monthsName[month] != 'undefined') {
						this.value = day + '-' + dateFormat.monthsName[month] + '-' + year;
					}
				};
				
				dateFormat.fields[idx].onkeypress = function(e) {
					var restrictionFormat = /[0-9\-]/g; // cuma angka dan dash
					
					if (!e) var e = window.event
					if (e.keyCode) code = e.keyCode;
					else if (e.which) code = e.which;
					var character = String.fromCharCode(code);

					// if they pressed esc... remove focus from field...
					if (code == 27) { this.blur(); return false; }
					
					// ignore if they are press other keys
					// strange because code: 39 is the down key AND ' key...
					// and DEL also equals .
					if (!e.ctrlKey && code != 9 && code != 8 && code != 36 && code != 37 && code != 38 && (code != 39 || (code == 39 && character == "'")) && code != 40) {
						if (character.match(restrictionFormat)) {
							return true;
						} else {
							return false;
						}
						
					}
				};
			
			})(i);
		}
	},
	
	defaultMode: function() { // Call this after call 'saveMode'
		for (var i = 0; i < this.fields.length; i++) {
			if (this.fields[i].value != '') {
				var day = this.fields[i].value.split('-')[2];
				var month = this.fields[i].value.split('-')[1];
				var year = this.fields[i].value.split('-')[0];
			
				if (typeof this.monthsName[month] != 'undefined') {
					this.fields[i].value = day + '-' + this.monthsName[month] + '-' + year;
				}
			}
		}
	},
	saveMode: function() { // Call this after call 'defaultMode'
		for (var i = 0; i < this.fields.length; i++) {
			if (this.fields[i].value != '') {
				var day = this.fields[i].value.split('-')[0];
				var month = this.fields[i].value.split('-')[1];
				var year = this.fields[i].value.split('-')[2];
			
				if (typeof this.monthsCode[month] != 'undefined') {
					this.fields[i].value = year + '-' + this.monthsCode[month] + '-' + day;
				}
			}
		}
	},
	
	checkValidDate: function(strDate) {
		var validformat = /^\d{2}-\d{2}-\d{4}$/; // Basic check for format validity
		var returnval = false;
		
		if (!validformat.test(strDate)) {
			alert('Invalid date format.');
		} else { // Detailed check for valid date ranges
			var monthfield = strDate.split('-')[1];
			var dayfield = strDate.split('-')[0];
			var yearfield = strDate.split('-')[2];
			var dayobj = new Date(yearfield, monthfield - 1, dayfield);
			if ((dayobj.getMonth() + 1) != monthfield || dayobj.getDate() != dayfield || dayobj.getFullYear() != yearfield) {
				alert('Invalid day, month or year range.');
			} else {
				returnval = true;
			}
		}
		
		//if (returnval == false) {
		//	input.select();
		//}
		
		return returnval;
	}
};
dateFormat.init();
	</script>
	
