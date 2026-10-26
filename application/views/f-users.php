<script>
$(document).ready(function() {
    
    $('button._reset').click(function() {
        var username = $('#username').val();
        $.post(
            '<?php echo base_url()?>index.php/user_management/reset_password/'+username, 
            function(data) {
                if(data) {
                    alert("Password sudah di reset menjadi "+data );
                }
            });
    });

    $('.d_reporting_to').change(function() {
        var usergroup        = $(this).val();
        var reportingto        = $('#reportingto').val();        
        
        $.post(
            '<?php echo base_url()?>index.php/user_management/user_repto', 
            {usergroup:usergroup, reportingto:reportingto},
            function(data) {
                if(data.status == 'OK') {
                    document.getElementById("divReporting").innerHTML = data.pesan;
                }
            },
            "json"
        ).error(function() {
            alert("-");
        });
    });

	
	$('.d_save').click(function() {
		var proses = $('#proses').val();

        var name = $('#name').val();
	    var email = $('#email').val();
        var username = $('#username').val();
	    var olduser = $('#olduser').val();
	    var nik = $('#nik').val();
	    var usergroup = $('#usergroup').val();
	    var reportingto = $('#reportingto').val();
	    var hape = $('#hape').val();
	    var password = $('#password').val();
        var istatus = $('#status').val();
        var mcp = $('#mcp:checked').val();
        
        // alert(proses);
        if (reportingto == '') 
            alert ('Anda harus memilih Reporting to');
        else {
            $.post(
                '<?php echo base_url()?>index.php/user_management/save_user/'+olduser, 
                {
                    proses:proses, name:name, email:email, username:username, nik:nik, 
                    usergroup:usergroup, reportingto:reportingto, hape:hape, password:password,
                    istatus:istatus, must_change_password:mcp, olduser:olduser
                },
                function(data) {
                    if(data.status == 'ERROR') {
                        alert(data.pesan);
                    }else if(data.status == 'OK') {
                        alert(data.pesan);
    					window.location = "<?php echo base_url()?>index.php/user_management"
                    }
                },
                "json"
            ).error(function() {
                alert("-");
            });
        }
    });
    
            $('.edit-user-name').hide();
            $('.edit-user-id').hide();
            $('.edit-nik').hide();
            
        if ("$proses != 'add' ") {
            isCreateByOIS("<?php echo $data['username']?>");
        }

    function isCreateByOIS(userid) {
        var created_by = "<?php echo $created_by?>"
        if (created_by == 'ois') {
            $('.d_reporting_to').attr('disabled', true).css('background-color', '#dedede');
            $('#hape').attr('disabled', true).css('background-color', '#dedede');
            $('#email').attr('disabled', true).css('background-color', '#dedede');

            $('.editUserName').removeAttr('title');
            $('.editUserId').removeAttr('title');
            $('.editNik').removeAttr('title');
        }
        else {

            // Edit User ID
            $('.editUserId').click(function(){
                $('.edit-user-id').show();
                $('#username').focus();
                $('.editUserId').hide();
            });
            $('.cancel-edit-user-id').click(function(){
                $('#username').val('<?php echo $data["username"]?>');
                $('.edit-user-id').hide();
                $('.editUserId').show();
            });

            // Edit Name
            $('.editUserName').click(function(){
                $('.edit-user-name').show();
                $('#name').focus();
                $('.editUserName').hide();
            });
            $('.cancel-edit-user-name').click(function(){
                $('#name').val('<?php echo $data["name"]?>');
                $('.edit-user-name').hide();
                $('.editUserName').show();
            });

            // Edit NIK
            $('.editNik').click(function(){
                $('.edit-nik').show();
                $('#nik').focus();
                $('.editNik').hide();
            });
            $('.cancel-edit-nik').click(function(){
                $('#nik').val('<?php echo $data["nik"]?>');
                $('.edit-nik').hide();
                $('.editNik').show();
            });
        }
    }

});
</script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>file/js/easyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>file/js/easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo base_url()?>file/js/easyui/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>file/js/easyui/jquery.easyui.min.js"></script>

<script>
	$(function(){
		$('#records').treegrid({
			title:'User Management',
			//iconCls:'icon-save',
			width: '100%',
			height:650,
			nowrap: false,
			rownumbers: true,
			animate:true,
			//collapsible:true,
			url:'<?php echo base_url()?>index.php/user_management/user_json',
			idField:'id',
			treeField:'name',
			onBeforeLoad:function(row,param){
				if (row){
					$(this).treegrid('options').url = '<?php echo base_url()?>index.php/user_management/user_json/'+row.id+'/'+row.p;
				} 
			}
		});
	});
	
	var codeIndex = 1000;
	function append(id,user_id){
		var items = [];
		$.getJSON('<?php echo base_url()?>index.php/user_management/user_json/'+user_id, function(data) {	
			$.each(data, function(key, val) {
			  items.push(  
					{ 'id': val.id,
					  'name': val.name,
					  'size': val.size,
					  'date': val.date,
					  'tipe': val.tipe,
					  'iconCls': val.iconCls
				});
			});
			var prev = 'o';
			if( tipe == 6) prev = 'a';
			$('#records').treegrid('remove', id);
			$('#records').treegrid('append', {				
				parent: prev + ter_id,
				data: items
			});
		});
	}
	function updateActions(){
		var rowcount = $('#tt').datagrid('getRows').length;
		for(var i=0; i<rowcount; i++){
			$('#tt').datagrid('updateRow',{
				index:i,
				row:{action:''}
			});
		}
	}
		
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
	
</script>

<div class="heading-buttons">
	<h3>Manajemen Pengguna</h3>
	<div class="buttons pull-right">
		<a href="<?php echo base_url()?>user_management/index/add" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> New User</a>
	</div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">


        <?php if($this->uri->segment(3)=="add" || $this->uri->segment(3)=="update"){?>
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
        
                <form id="form" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                <div class="form">
                        <div class="fields">
                        <?php   if($proses == "add"){   ?>

                        <input type="hidden" id="proses" value="add" />
			<div class="widget">
					<div class="widget-head"><h4 class="heading">New User</h4></div>
					<div class="widget-body">
						<div class="row-fluid">
							<div class="span12">
								<div class="control-group">
									<label class="control-label" for="firstname">User ID</label>
									<div class="controls"><input type="text" class="span6" id="username" name="username" value="<?php echo $data['username']?>" class="medium" /></div>
								</div>
								<div class="control-group">
									<label class="control-label" for="lastname">Name</label>
									<div class="controls"><input type="text" class="span6" id="name" name="name" value="<?php echo $data['name']?>" class="medium" /></div>
								</div>
								<div class="control-group">
									<label class="control-label" for="username">NIK</label>
									<div class="controls"><input type="text" class="span6" id="nik" name="nik" value="<?php echo $data['nik']?>" class="medium" /></div>
								</div>
								
								
								<div class="control-group">
									<label class="control-label" for="username">Status</label>
									<div class="controls">
									    <select name="status" id="status" class="status">
										    <?php 
										      foreach ($user_status as $key => $value) {
											     if ($value['member_value'] == 2) 
											      { echo '<option value="'.$value['member_value'].'" selected="selected" >'.$value['member_display'].'</option>'; }
											     else 
											      { echo '<option value="'.$value['member_value'].'">'.$value['member_display'].'</option>'; }
										      }
										    ?>
									    </select>
									</div>
								</div>
								<div class="control-group" id="divReporting">
									<?php
									$data['usergroup']=1;
									$this->load->view('f-user-reporting-to',$data);
									?>
								</div>
								<div class="control-group">
									<label class="control-label" for="username">Mobile Number</label>
									<div class="controls"><input type="text" class="span6" id="hape" name="hape" value="<?php echo $data['hape']?>" class="medium" /></div>
								</div>
								<div class="control-group">
									<label class="control-label" for="username">E-Mail</label>
									<div class="controls"><input type="text" class="span6" id="email" name="email" value="<?php echo $data['email']?>" class="medium" /></div>
								</div>
								<div class="control-group">
									<label class="checkbox" for="mcp">
										<input type="checkbox" class="checkbox" name="must_change_password" id="mcp" value="1" checked="checked" />
										Change password on the next logon
									</label>
								</div>
							</div>
						</div>
						
						
					
						<div class="form-actions">
						    <button type="button" name="submit" class="d_save btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
						    <button type="reset" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Reset</button>
						    
						</div>
					</div>
				</div>
                        
                        <?php }elseif($proses == "update"){ ?>
                                
				<input type="hidden" id="proses" value="update" />
                                
				<div class="widget">
					<div class="widget-head"><h4 class="heading">Update User</h4></div>
					<div class="widget-body">
						<div class="row-fluid">
							<div class="span12">
								<div class="control-group">
									<label class="control-label" for="firstname">User ID</label>
									<div class="editUserId controls" title="Click to Edit"><b><?php echo $data['username']?></b></div>
									<span class='edit-user-id'>
										<input id="username" type="text" name="username" value="<?php echo $data['username']?>" />
										<input id="olduser" type="hidden" name="olduser" value="<?php echo $data['username']?>" />
										<span class="cancel-edit-user-id">
											<img src="<?php echo base_url()?>file/images/cross.png" style="top:8px; left:8px; position:relatives;"/>
										</span>
									</span>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="firstname">Name</label>
									<div class="editUserName controls" title="Click to Edit"><b><?php echo $data['name']?></b></div>
									<span class='edit-user-name'>
										<input id="name" type="text" name="name" value="<?php echo $data['name']?>" />
										<span class="cancel-edit-user-name">
											<img src="<?php echo base_url()?>file/images/cross.png" style="top:8px; left:8px; position:relatives;"/>
										</span>
									</span>
								</div>
								<div class="control-group">
									<label class="control-label" for="username">NIK</label>
									<div class="controls"><input type="text" class="span6" id="nik" name="nik" value="<?php echo $data['nik']?>" class="medium" /></div>
								</div>
								
								
								<div class="control-group">
									<label class="control-label" for="username">Status</label>
									<div class="controls">
									    <select name="status" id="status" class="status">
										    <?php 
										      foreach ($user_status as $key => $value) {
											     if ($value['member_value'] == 2) 
											      { echo '<option value="'.$value['member_value'].'" selected="selected" >'.$value['member_display'].'</option>'; }
											     else 
											      { echo '<option value="'.$value['member_value'].'">'.$value['member_display'].'</option>'; }
										      }
										    ?>
									    </select>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="username">User Group</label>
									<div class="controls">
									    <?php
									    $getUserGroup	= $this->muserrole->getUserGroup();
									    echo'<select name="usergroup" id="usergroup" class="d_reporting_to">';
									    foreach($getUserGroup as $r){
										    $SELECTED = $r->user_group_id==$data['usergroup']?"SELECTED":"";
										    echo'<option value="'. $r->user_group_id .'" '. $SELECTED .'>'.$r->user_group_id .'. '. $r->user_group_name .'</option>';
									    }
									    echo'</select>';
									    //<input type=hidden id=reportingto value="'. $data['reportingto'] .'">
									    ?>
									</div>
								</div>
								
								<div class="control-group" id="divReporting">
									<?php
									$data['usergroup']=1;
									$this->load->view('f-user-reporting-to',$data);
									?>
								</div>
								<div class="control-group">
									<label class="control-label" for="username">Mobile Number</label>
									<div class="controls"><input type="text" class="span6" id="hape" name="hape" value="<?php echo $data['hape']?>" class="medium" /></div>
								</div>
								<div class="control-group">
									<label class="control-label" for="username">E-Mail</label>
									<div class="controls"><input type="text" class="span6" id="email" name="email" value="<?php echo $data['email']?>" class="medium" /></div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="username">Password</label>
									<div class="controls">
									    <input type="password" id="password" name="password" class="medium" /><br>
									    * Password is not required. If password is not empty, password will change.
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="username">Last time change password</label>
									<div class="controls">
									    <?php if ($last_time_change_password) { echo date("d-F-Y", strtotime($last_time_change_password)); } else echo 'Password never change'; ?>
									</div>
								</div>
								
								
                                
								<div class="control-group">
									<label class="checkbox" for="mcp">
										<input type="checkbox" class="checkbox" name="must_change_password" id="mcp" value="1"
										       <?php if ($must_change_password == 1) echo 'checked="checked"'; ?> />
										Change password on the next logon
									</label>
								</div>
							</div>
						</div>
						
						
					
						<div class="form-actions">
						    <button type="button" name="submit" class="d_save btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
						    <button type="reset" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Reset</button>
						    <button type="button" name="_reset" class="_reset btn btn-icon btn-primary glyphicons lock"><i></i>Reset Password</button>
						</div>
					</div>
				</div>

                        <?php } ?>
                                
                                
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
                <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>file/js/easyui/themes/gray/easyui.css">
                <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>file/js/easyui/themes/icon.css">
                <script type="text/javascript" src="<?php echo base_url()?>file/js/easyui/jquery-1.7.1.min.js"></script>
                <script type="text/javascript" src="<?php echo base_url()?>file/js/easyui/jquery.easyui.min.js"></script>
                
                <table id="records">
                        <thead>
                                <tr>
                                        <th field="name" width="250">Name</th>
                                        <th id="id" field="size" width="140">Username</th>
                                        <th field="nik" width="90">NIK</th>
                                        <th field="emails" width="140">Email</th>
                                        <th field="hapes" width="110">Mobile Phone</th>
										<th field="namagroup" width="160">Nama Group</th>
										<th field="istatus" width="60">Status</th>
                                        <th field="date" width="100">Process</th>
                                </tr>
                        </thead>
                </table>

				<script type="text/javascript">
					function formatActionw(value,row,index){
						
						var e = '<a href="#" onclick="editrow('+index+')">Edit</a> ';
						var d = '<a href="#" onclick="deleterow('+index+')">Delete</a>';
						return e+d;
						
					}

				</script>

        </div>
</div>