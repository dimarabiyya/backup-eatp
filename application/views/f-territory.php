<script>
$(document).ready(function() {
    
    $('.d_parent').change(function() {
        var ter_id        = $(this).val();
        var search        = 0;
        $.post(
            '<?php echo base_url()?>index.php/territory/territory_edit', 
            {ter_id:ter_id,search:search},
            function(data) {
                if(data.status == 'OK') {
                    document.getElementById("divkomponen").innerHTML = data.pesan;
                }
            },
            "json"
        ).error(function() {
            alert("-");
        });
    });
	
	$('.d_save').live("click",function() {
        var id 	 = $("#id").val();
		var name 	 = $("#name").val();
		var parent  = $("#parent_id").val();
		var user 	 = $("#muser").val();
		var territory_type = $("#territory_type").val();
		var territory_code = $("#territory_code").val();
		var territory_code2 = $("#territory_code2").val();
		var proses = $("#proses").val();

        $.post(
            '<?php echo base_url()?>index.php/territory/save_territory', 
            {id:id,name:name,parent:parent, user:user, territory_type:territory_type, territory_code:territory_code, territory_code2:territory_code2, proses:proses},
            function(data) {
                if(data.status == 'OK') {
                    alert(data.pesan);
					window.location = "<?php echo base_url()?>index.php/territory/tree"
                }else if(data.status == 'ERROR') {
					alert(data.pesan);
				}
            },
            "json"
        ).error(function() {
            alert("-");
        });
    });
    
});


function jump_url(targ,selObj,restore){ //v3.0
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}
</script>

<div class="heading-buttons">
	<h3>Territory</h3>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget">
	
		<div class="widget-head">
			<h4 class="heading glyphicons list"><i></i> Manage Territory</h4>
		</div>
		
		<div class="widget-body">
		
			<input type=hidden id=proses value ='<?php echo $proses?>' >
			<input type=hidden id=id value ='<?php echo $id?>' >
			<?php if($proses=="add" || $proses=="update"){?>
			<?php
			#### tampilkan alert jika terdapat kesalahan dalam memasukkan data
					$salah = validation_errors();
					if($salah <> ""){
							echo'<div id="box-messages">
									<div class="messages">
											<div id="message-error" class="message message-error">
													<div class="image">
															<img src="'. base_url() .'file/shell/smooth/resources/images/icons/error.png" alt="Error" height="32" />
													</div>
													<div class="text">
															<h6>Terdapat kesalahan dalam memasukkan data:</h6>
															<span>'. validation_errors() .'</span>
													</div>
													<div class="dismiss">
															<a href="#message-error"></a>
													</div>
											</div>
									</div>
							</div>';
					}
			?>
			
					<form id=form method=post action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data" >
					<div class="form">
							<div class="fields">
									<div class="field">
											<div class="label">
													<label for="parent_id">Parent:</label>
											</div>
											<div class="input">
													<select name="parent_id" id="parent_id" class="d_parent" disabled=disabled >
													<?php
													$dt = $this->mservices->getValueFromTable("t_mtr_territory",array("territory_type_id <="=>2));
													foreach($dt as $row){
															echo'<option value="'. $row->territory_id .'" '. ($data['parent_id'] == $row->territory_id ?"SELECTED":"") .'>'. $row->territory_name .'</option>';
													}
													?>
													</select>
											</div>
									</div>
									<div class="field">
											<div class="label">
													<label for="name">Nama:</label>
											</div>
											<div class="input">
													<input type="text" id="name" name="name" value="<?php echo $data['name']?>" class="medium" />
											</div>
									</div>
									<div id="divkomponen">
									<?php
									$data['type'] = $type;
									$data['code'] = $code;
									$data['codes'] = $codes;
									$data['icon'] = $icon;
									$data['search'] = 0;
									
									$this->load->view('f-territory-add',$data);
									?>
									</div>
									<div class="buttons">
											<input type="button" class="d_save" name="submit" value="Submit" />
											<input type="reset" name="reset" value="Reset" />
									</div>
							</div>
					</div>
					</form><br><br>&nbsp;
			<?php } ?>
			
			<?php
			$msg = $this->session->flashdata('message');
			if($msg <> ""){
				  echo'<div id="box-messages">
							<div class="messages">
									<div id="message-success" class="message message-success">
											<div class="image">
													<img src="'. base_url() .'file/shell/smooth/resources/images/icons/success.png" alt="Success" height="32" />
											</div>
											<div class="text">
													<h6>'. $msg .'</h6>
											</div>
											<div class="dismiss">
													<a href="#message-success"></a>
											</div>
									</div>
							</div>
					</div>';
			}
			?>       
			
			<div class="table">
					
					<?php
					if($jenis == "tree"){
							$this->load->view('f-territory-tree');
					}elseif($jenis == "chart"){
							echo'<iframe src="'.base_url().'index.php/territory/territory_iframe/chart" width=100% height=600px>';
					}
					?>
					
					
			</div>
			
		</div>
	</div>
</div>