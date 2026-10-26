<script>
$(document).ready(function() {
    
    $('.d_parent').change(function() {
        var ter_id        = $(this).val();
		var search        = 1;
        
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
	
	$('.d_cari').click(function() {
        var parentid        = $("#parent_id").val();
		var name        = $("#name").val();
		var maintenance	= $("#user").val();
        
        $.post(
            '<?php echo base_url()?>index.php/territory/search_result', 
            {parentid:parentid,name:name, maintenance:maintenance},
            function(data) {
                if(data.status == 'OK') {
					if(data.jumlah==0){
							alert("Data Territori tidak ditemukan");
					}
                    document.getElementById("tabel").innerHTML = data.pesan;
                }
            },
            "json"
        ).error(function() {
            alert("-");
        });
    });
	
	$('#deleteRow').click(function() {
        var index= $(this).attr('index');
		var ter_id= $("#name").attr('ter_id');
		var jml= $("#user").attr('jml');
		
		if(jml == 0){
			$.messager.confirm('Confirm','Are you sure to delete this data ?',function(r){
				if (r){
					$.post(
						'<?php echo base_url()?>index.php/territory/territory_delete', 
						{ter_id:ter_id},
						function(data) {
						    if(data.status == 'OK') {
							$('#tt').datagrid('deleteRow', index);
							//setTimeout("location.reload(true);",100);
							//proses 
						    }
						},
						"json"
					    ).error(function() {
						alert("-");
					    });
				}
			});
		}else {
			$.messager.alert('Failed to delete data',"You cannot delete this data, because data have a node",'warning');
		}
    });
	
	function deleterow(index,ter_id,jml){
		if(jml == 0){
			$.messager.confirm('Confirm','Are you sure to delete this data ?',function(r){
				if (r){
					$.post(
						'<?php echo base_url()?>index.php/territory/territory_delete', 
						{ter_id:ter_id},
						function(data) {
						    if(data.status == 'OK') {
							$('#tt').datagrid('deleteRow', index);
							//updateActions();
							setTimeout("location.reload(true);",100);
						    }
						},
						"json"
					    ).error(function() {
						alert("-");
					    });
				}
			});
		}else {
			$.messager.alert('Failed to delete data',"You cannot delete this data, because data have a node",'warning');
		}
	}
    
});


function jump_url(targ,selObj,restore){ //v3.0
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}
</script>

<div class="box">
        <!-- box / title -->
        <div class="title">
                <h5>Territory :: Search</h5>
                <ul class="links">
                        <!-- <li><a href="<?php echo base_url().'index.php/'.$this->uri->segment(1)?>/tree">Territory Tree</a></li>
                        <li><a href="<?php echo base_url().'index.php/'.$this->uri->segment(1)?>/chart">Territory Chart</a></li>
						<li><a href="<?php echo base_url().'index.php/'.$this->uri->segment(1)?>/search">Search</a></li> -->
                </ul>
        </div>
        <!-- end box / title -->
        
                <form id=form method=post action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data" >
                <div class="form">
                        <div class="fields">
                                <div class="field">
                                        <div class="label">
                                                <label for="parent_id">Parent:</label>
                                        </div>
                                        <div class="input">
                                                <select name="parent_id" id="parent_id" class="d_parent">
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
                                                <label for="name">Name:</label>
                                        </div>
                                        <div class="input">
                                                <input type="text" id="name" name="name" value="<?php echo $data['name']?>" class="medium" />
                                        </div>
                                </div>
                                <div id="divkomponen">
									<div class="field">
											<div class="label">
													<label for="user">Maintenance By:</label>
											</div>
											<div class="input">
													<select name="user" id="user">
													</select>
											</div>
									</div>
									<div class="field">
											<div class="label">
													<label for="territory_type">Territory Type:</label>
											</div>
											<div class="input">
													<b>&nbsp;</b>
											</div>
									</div>
                                </div>
                                <div class="buttons">
								<input type="button" name="btn" class="d_cari" value="Cari Data" />
						</div>
                        </div>
                </div>
                </form><br><br>&nbsp;
        
        <div class="table" id="tabel">
                
        </div>
</div>