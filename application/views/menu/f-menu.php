<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/icon.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/datagrid-detailview.js"></script>
<script src="<?php echo base_url()?>assets/js/overlay.js"></script>
<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>

<style type="text/css">
        .icon-level-1{
		background:url('<?php echo base_url()?>file/images/red.png') no-repeat 0px 0px;
	}
	.icon-level-2{
		background:url('<?php echo base_url()?>file/images/orange.png') no-repeat 0px 0px;
	}
	.icon-level-3{
		background:url('<?php echo base_url()?>file/images/green.png') no-repeat 0px 0px;
	}
</style>

<script type="text/javascript">

$(document).ready(function(){
	$("#divForm").hide();
        
	$("#btnAddData").live("click",function(){
		$("#divForm").show();
		$("#h4judul").html("Add New Menu");
	});
	
	$(".btnEditData").live("click",function(){
		$("#divForm").show();
		$("#h4judul").html("Edit Data Menu");
		var id 		= $(this).attr('menu_id');
		var parent 	= $(this).attr('parent_id');
		var nama 	= $("td:eq(0)", $(this).parent().parent()).text();
		var url 	= $("td:eq(1)", $(this).parent().parent()).text();
		
		$("#menu_id")	.val( id );
		$("#parent")	.val( parent );
		$("#nama")	.val( nama );
		$("#url")	.val( url );
                $("#order")	.val( $("td:eq(2)", $(this).parent().parent()).text() );
                $("#status").prop('checked', ($("td:eq(3)", $(this).parent().parent()).text() == "YES"?true:false) );
                
	});
	
	$(".btnDeleteData").live("click",function(){
		var checkstr =  confirm('Data yang telah dihapus tidak dapat dikembalikan lagi. \n Anda yakin ingin menghapus data ini ?');
                if(checkstr == true){
                        var id 		= $(this).attr('menu_id');
                        $.ajax({
                                url: '<?php echo base_url()?>menu/deleteData',
                                type: 'POST',
                                dataType: 'json',
                                data: {id:id},
                                success: function() {
					alert( "Data berhasil dihapus" );
                                        location.reload();
                                }
                        });
                                
                        
                }
                return false;
		
		
		$("#divForm").show();
		
		var id 		= $(this).attr('menu_id');
		var parent 	= $(this).attr('parent_id');
		var nama 	= $("td:eq(0)", $(this).parent().parent()).text();
		var url 	= $("td:eq(1)", $(this).parent().parent()).text();
		
		$("#menu_id")	.val( id );
		$("#parent")	.val( parent );
		$("#nama")	.val( nama );
		$("#url")	.val( url );
	});
	
	$("#btnCancelAddData").live("click",function(){
		$("#divForm").hide();
		$('#divForm').find('form')[0].reset();
		$("#menu_id")	.val( '' );
	});
	
	$('#add-form').live("submit",function(e){
                $.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>menu/saveData",
                        data: $(e.target).serialize(),
                        dataType: "json",
                        beforeSend:function(){
                                $.blockUI({ message: 'Processing, please wait...', css: {
					border: 'none',
					padding: '15px',
					backgroundColor: '#000',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					opacity: .5,
					color: '#fff'
				} });
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                                alert(errorThrown);
                                $.unblockUI();
                        },
                        success: function(data){
				$.unblockUI();
                                if(data.status == "OK"){
                                        alert( "Data berhasil disimpan" );
					location.reload();
                                }else{
                                        alert( data.hasil );
                                }
                        }
                });
                return false;
	});
	
	
});

function doEdit(group_id){
			
		$("#divForm").show();
		$("#h4judul").html("Edit Menu");
		$('body,html').animate({ scrollTop: 0 }, 800);
		
		$.ajax({
			type: "POST",
			url: "<?php echo base_url()?>menu/EditMenu",
			data: {'id':group_id},
			dataType: "json",
			beforeSend:function(){
				$.blockUI({ message: 'Processing, please wait...', css: {
					border: 'none',
					padding: '15px',
					backgroundColor: '#000',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					opacity: .5,
					color: '#fff'
				} });
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
				$.unblockUI();
			},
			success: function(data){
				$.unblockUI();
				if(data.status == "OK"){
					
					$("#parent")	.val( data.hasil.parent_id );
					$("#menu_id")	.val( data.hasil.id );
					$("#nama")	.val( data.hasil.name );
					$("#url")	.val( data.hasil.alias );
					$("#order")	.val( data.hasil.ordering );
					
					$('#status').prop('checked', (data.hasil.published == 1?true:false) );						
				}
			}
		});
	}
	
	function doDelete(id){
		if (confirm('Delete Menu?')){
			$.ajax({
				url:"<?php echo base_url()?>menu/deleteData",
				dataType: "json",
				type: 'POST',
				data: {'id':id},
				error: function(jqXHR, textStatus, errorThrown){
					notif(errorThrown,'error');
				},
				success: function(data){
					if(data.status == "OK"){
						alert(data.hasil);
						location.reload();
					}
				}
			});
		}
	}
	
</script>

<div class="innerLR">
	
	<div class="widget" id="divForm" style="display:none;">
		<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
		<div class="widget-head">
			<h4 class="heading glyphicons list" id="h4judul">Add New Menu</h4>
		</div>
		<div class="widget-body">
                        
                        <div class="row-fluid">
				<div>
                                        <input type='hidden' name='id' id='menu_id' value='' >
					<div class="control-group" id="form_parent">
						<label class="control-label span3" for="parent">Parent</label>
						<div class="controls">
							<select class="span4" name="parent" id="parent">
								<option value='0'>>>> TOP MENU <<<</option>
							<?php
								foreach ($this->mmenu->getgridmenu(0, 1)->result() as $k) {
									echo"<option value='$k->id'>$k->menu_name</option>";
									foreach ($this->mmenu->getgridmenu($k->id,1)->result() as $c){
										echo"<option value='$c->id'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$c->menu_name</option>";
									}
								}
							?>
							</select>
						</div>
					</div>
					<div class="control-group" id="form_nama">
						<label class="control-label span3" for="nama">Menu Name</label>
						<div class="controls"><input class="span4" id="nama" name="nama" type="text" value="" /></div>
					</div>
					<div class="control-group" id="form_url">
						<label class="control-label span3" for="url">URL</label>
						<div class="controls"><b><?php echo base_url()?></b><input class="span9" id="url" name="url" type="text" value="" /></div>
					</div>
                                        <div class="control-group" id="form_order">
						<label class="control-label span3" for="order">Order #</label>
						<div class="controls">
                                                        <select name="order" id="order">
                                                        <?php
                                                        for($i=1;$i<=20;$i++){
                                                                echo"<option>$i</option>";
                                                        }
                                                        ?>
                                                        </select>
                                                </div>
					</div>
                                        <div class="control-group" id="form_status">
						<label class="control-label span3" for="status">Status</label>
						<div class="controls">
                                                        <input type="checkbox" id="status" name="status"><span> Published</span>
                                                </div>
					</div>
                                        
				</div>
				
                                <div class="form-actions">
                                        <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
                                        <button type="button" class="btn btn-icon btn-default glyphicons circle_remove" id="btnCancelAddData"><i></i>Cancel</button>
                                </div>
			</div>
		</div>
		</form>
	</div>
	
	
	
	
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading"><i></i> <?php echo $judul?></h4>
			<button class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right" id="btnAddData"><i></i> Add New Menu</button>
		</div>
		<div class="widget-body">
			
			<table title="Folder Browser" class="easyui-treegrid" style="min-height:400px"
				data-options="
				    url: '<?php echo base_url()?>menu/testTree',
				    method: 'get',
				    rownumbers: true,
				    idField: 'id',
				    treeField: 'menu_name',
				    fitColumns: true
				">
			    <thead>
				<tr>
				    <th data-options="field:'menu_name'" width="220">Menu Name</th>
				    <th data-options="field:'alias'" width="100">URL</th>
				    <th data-options="field:'ordering'" width="150" align="right">Order #</th>
				    <th data-options="field:'published'" width="50" align="center">Published</th>
				    <th data-options="field:'action'" width="90" align="center"></th>
				</tr>
			    </thead>
			</table>
			
			
			
			
		</div>
	</div>
</div>