<script>
$(document).ready(function() {
    
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
    
	$('.d_cari').click(function() {
        var uid  	= $("#username").val();
		var name	= $("#name").val();
		var nik		= $("#nik").val();
		var group	= $("#group").val();
		var report	= $("#reportingto").val();
		var hape	= $("#hape").val();
		var email	= $("#email").val();
        
        $.post(
            '<?php echo base_url()?>index.php/user_management/search_result', 
            {uid:uid, name:name, nik:nik, group:group, report:report, hape:hape, email:email},
            function(data) {
                if(data.status == 'OK') {
					if(data.jumlah==0){
							alert("Data Pengguna tidak ditemukan");
					}
                    document.getElementById("tabel").innerHTML = data.pesan;
                }
            },
            "json"
        ).error(function() {
            alert("-");
        });
    });
	
	
	$('.d_keluarkan').live("click",function() {
        var id  	= $(this).attr("id");
        
        $.post(
            '<?php echo base_url()?>index.php/user_management/keluarkan_user', 
            {id:id},
            function(data) {
                if(data.status == 'OK') {
                    alert("User berhasil dikeluarkan");
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
	<h3>Users Management :: Search</h3>
</div>
<div class="separator bottom"></div>

<div class="innerLR">
        
                <form id="form" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
		<div class="widget">
					<div class="widget-head"><h4 class="heading">New User</h4></div>
					<div class="widget-body">
						<div class="row-fluid">
							<div class="span12">
								<div class="control-group">
									<label class="control-label" for="firstname">User ID</label>
									<div class="controls"><input type="text" id="username" name="username" class="medium" /></div>
								</div>
								<div class="control-group">
									<label class="control-label" for="lastname">Name</label>
									<div class="controls"><input type="text" id="name" name="name" class="medium" /></div>
								</div>
								<div class="control-group">
									<label class="control-label" for="username">NIK</label>
									<div class="controls"><input type="text" id="nik" name="nik" class="medium" /></div>
								</div>
								
								
								<div class="control-group">
									<label class="control-label" for="username">User Group</label>
									<div class="controls">
									    <?php
										$getUserGroup	= $this->muserrole->getUserGroup();
										echo'<select name="usergroup" id="group">
																<option value="">Pilih</option>';
										foreach($getUserGroup as $r){
											echo'<option value="'. $r->user_group_id .'" >'. $r->user_group_name .'</option>';
										}
										echo'</select>';
									    ?>
									</div>
								</div>
								<div class="control-group">
									
									
									<label class="control-label" for="username">Reporting To</label>
									<div class="controls">
									    <?php
									    $getUser	= $this->musers->getUser();
									    echo'<select name="reportingto" id="reportingto">
										    <option value="">Pilih</option>';
									    foreach($getUser as $r){
										    echo'<option value="'. $r->user_id .'">'. $r->user_name .'</option>';
									    }
									    echo'</select>';
									    ?>
									    
									</div>
									
								</div>
								<div class="control-group">
									<label class="control-label" for="username">Mobile Number</label>
									<div class="controls"><input type="text" id="hape" name="hape" class="medium" /></div>
								</div>
								<div class="control-group">
									<label class="control-label" for="username">E-Mail</label>
									<div class="controls"><input type="text" id="email" name="email" class="medium" /></div>
								</div>
								
							</div>
						</div>
						
						
					
						<div class="form-actions">
						    <button type="button" name="submit" class="d_cari btn btn-icon btn-primary glyphicons circle_ok"><i></i>Cari Data</button>
						    
						</div>
					</div>
				</div>
                </form><br><br>&nbsp;
        <div class="table" id="tabel"></div>
</div>