<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/icon.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/datagrid-detailview.js"></script>
<script src="<?php echo base_url()?>assets/js/overlay.js"></script>
<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>
<style type="text/css">
.datagrid-body .datagrid-cell{
	word-wrap: break-word;
	white-space: normal;
}
#gritter-notice-wrapper {
	position:fixed;		
	top: 38%;
	left: 38%;
	width:301px;
	z-index:9999;
}
</style>
	
<script>
  
$(document).ready(function() {
	
	$('#dg').datagrid({
			pageSize:10,
			fitColumns:true,
			nowrap:false,
			singleSelect:true
		});
	
        $("#btnAddData").live("click",function(){
		$("#divForm").show();
		$(this).hide();
		$("#h4judul").html("Add New Group");
		$('body,html').animate({ scrollTop: 0 }, 800);
		
		$('#add-form')[0].reset();
		$('#status').attr('checked', false);
		$("#group_id")	.val( "" );
	});
        
        $("#btnCancelAddData").live("click",function(){
		$("#btnAddData").show()
		$("#divForm").hide();
		$('#divForm').find('form')[0].reset();
	});
        
        
	$('#add-form').live("submit",function(e){
                $.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>group/saveDataDiv",
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
                                        $("#divForm").hide();
					$("#btnAddData").show()
                                        $('#divForm').find('form')[0].reset();
					$('#dg').datagrid('load');
                                        alert( "Data berhasil disimpan" );
                                        
                                }else{
                                        alert( data.hasil );
                                }
                        }
                });
                return false;
	});
});

		function doSearch(){
			$('#dg').datagrid('load',{
				q: $('#q').val(),
			});
		}
		
		function doSearchEnter( event ){
			if (event.which == 13 || event.keyCode == 13) {
                                $('#dg').datagrid('load',{
                                        q: $('#q').val(),
                                });
                        }
                        return true;
		}
		
		function doEdit(group_id){
			
			$("#divForm").show();
			$("#h4judul").html("Edit Group");
			$('body,html').animate({ scrollTop: 0 }, 800);
			
			$.ajax({
				type: "POST",
				url: "<?php echo base_url()?>group/EditDiv",
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
						
						$("#group_id")	.val( data.hasil.user_div_id );
						$("#nama")	.val( data.hasil.user_div_name );
						
						$('#status').prop('checked', (data.hasil.status == 1?true:false) );						
					}
				}
			});
		}
		
		function doDelete(group_id){
			if (confirm('Delete Group?')){
				$.ajax({
					url:"<?php echo base_url()?>group/deleteDataDiv",
					dataType: "json",
					type: 'POST',
					data: {'id':group_id},
					error: function(jqXHR, textStatus, errorThrown){
						notif(errorThrown,'error');
					},
					success: function(data){
						if(data.status == "OK"){
							$('#dg').datagrid('load');
						}
					}
				});
			}
		}
		
		function notif(data,type_notif){
			notyfy({
					layout		: 'center',
					text 		: data.hasil,
					type 		: type_notif, // alert, success, error, warning, information, confirm
					dismissQueue: false,
					modal 		: true,
					showEffect	: false,
					hideEffect	: false,
					buttons		: [{
						addClass: 'btn btn-primary',
						text: 'OK',
						onClick: function(notyfy) {
							notyfy.close();
							
							if (typeof functionAfterOKClicked != 'undefined') {
								functionAfterOKClicked();
							}
						}
					}]
				});
		}
</script>

<div class="innerLR">
	
        <div class="widget" id="divForm" style="display:none;">
		<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
		<div class="widget-head">
			<h4 class="heading glyphicons list" id="h4judul">Add New Group</h4>
		</div>
		<div class="widget-body">
                        
                        <div class="row-fluid">
				<div>
                                        <input type='hidden' name='id' id='group_id'>
					<div class="control-group" id="form_nama">
						<label class="control-label span3" for="nama">Group Name</label>
						<div class="controls">
							<input class="span9" id="nama" name="nama" type="text" value="" />
						</div>
					</div>
					<div class="control-group" id="form_status">
						<label class="control-label span3" for="status">Status</label>
						<div class="controls">
                                                        <input type="checkbox" id="status" name="status" style="margin-bottom:3px;"><span style="padding-top:5px;"> Aktif</span>
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
			<button class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right" id="btnAddData"><i></i> Add New Group</button>
		</div>
                
                <div class="widget-body">
			<div id="tb" class="form-horizontal">
				<div class="control-group">
					<label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
					<div class="input-append">
					  	<input name="q" id="q" type="text" placeholder="Type text to search .." onKeyPress="return doSearchEnter(event)"   />
					  	<button  onclick="doSearch()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
					</div>	
				</div>	
			</div>
			<table style="height:auto" id="dg"
					url="<?php echo base_url().'group/loadDataDiv';?>" 
					title="" nowrap='false'
					iconCls="icon-save"   toolbar="#tb" striped="true"
		        	rownumbers="true" pagination="true"
					singleSelect="true" fitColumns="true">
					<thead>
						<tr>
							<?php
								$arr=array('user_div_name'=>'Group Name','status'=>'Status','action'=>'Action');
								$width=array('110','250','70','70','120');
								$i=0;
								foreach ($arr as $key=>$value) {
									if ($key=='mstatus')
										echo '<th class ='.$key.' field="'.$key.'" width="'.$width[$i].'">'.$value.'</th>';
									else
										echo '<th sortable="true" field="'.$key.'" width="'.$width[$i].'">'.$value.'</th>';
									$i++;
								}
							?>
						</tr>
					</thead>
			</table>
			<hr>
			
		</div>
	</div>
</div>