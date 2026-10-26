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
		$("#h4judul").html("Add New");
		$('body,html').animate({ scrollTop: 0 }, 800);
		
		$('#add-form')[0].reset();
		$('#status').attr('checked', false);
		$("#group_id")	.val( "" );
	});
        
        $("#btnCancelAddData").live("click",function(){
		$("#divForm").hide();
		$('#divForm').find('form')[0].reset();
	});
        
        $(".btnEditData").live("click",function(){
		$("#divForm").show();
		$("#h4judul").html("Edit Data Menu");
                $('body,html').animate({ scrollTop: 0 }, 800);
		
		$("#group_id")	.val( $(this).attr('group_id') );
		$("#nama")	.val( $("td:eq(1)", $(this).parent().parent()).text() );
		// $("#parent")	.val( $(this).attr('p_id') );
                
                $('#status').prop('checked', ($("td:eq(2)", $(this).parent().parent()).text() == "Aktif"?true:false) );
                
	});
                
        $('#add-form').live("submit",function(e){
                $.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>user/saveDataMobileUser",
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
                                        $('#dg').datagrid('load');
                                        $('#divForm').find('form')[0].reset();
                                        alert( "Data berhasil disimpan" );
                                        
                                }else{
                                        alert( data.hasil );
                                }
                        }
                });
                return false;
	});
});

function doSearchEsc(){
		
	$.fancybox({
		href:"<?php echo base_url()?>user/searchEsc",type:'ajax',
		minWidth:100,scrolling:false,autoSize	:true,fitToView	: true,	closeClick	: false,openEffect	: 'fade',closeEffect	: 'fade',afterShow:function(){$('#app_no').focus();}
	});
}

		function doSearch(){
			$('#dg').datagrid('load',{
				q: $('#q').val(),
			});
		}
		function doEdit(group_id){
			
			$("#divForm").show();
			$("#h4judul").html("Edit Data Menu");
			$('body,html').animate({ scrollTop: 0 }, 800);
			
			$("#group_id")	.val( $(this).attr('group_id') );
			$("#nama")	.val( $("td:eq(1)", $(this).parent().parent()).text() );
			// $("#parent")	.val( $(this).attr('p_id') );
			
			$('#status').prop('checked', ($("td:eq(2)", $(this).parent().parent()).text() == "Aktif"?true:false) );
		}
		
		function doDelete(group_id){
			if (confirm('Delete Mobile User?')){
				$.ajax({
					url:"<?php echo base_url()?>user/deleteDataMobileUser",
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
			<h4 class="heading glyphicons list" id="h4judul">Mobile User</h4>
		</div>
		<div class="widget-body">
                        
                        <div class="row-fluid">
				<div>
                                        <input type='hidden' name='id' id='group_id'>
					<div class="control-group" id="form_parent">
						<label class="control-label span3" for="mobile_type">Mobile App Type</label>
						<div class="controls">
                                                        <select id="mobile_type" name="mobile_type">
                                                        <?php
							$getMobileType = $this->muser->getMobileType();
							foreach ($getMobileType as $r) {
							     echo '<option value="'.$r->lookup_value.'">'.$r->lookup_desc.'</option>';
						        }
                                                        ?>
                                                        </select>
                                                </div>
					</div>
					
					<div class="control-group">
						<label class="control-label span12">User</label>
						<div>
						<div class="controls" id="esc">
							<input name="escnull" id="escnull" class="span8" readonly>
							<button onclick="doSearchEsc();" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>	
						</div>
						<input type='hidden' name='user_id' id='user_id' />	
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
			<button class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right" id="btnAddData"><i></i> Add New</button>
		</div>
                
                <div class="widget-body">
			<div id="tb" class="form-horizontal">
				<div class="control-group">
					<label class="control-label" style="text-align:left;margin-left:10px;width:55px">Search</label>
					<div class="input-append">
					  	<input name="q" id="q" type="text" placeholder="Type text to search ..">
					  	<button  onclick="doSearch()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
					</div>	
				</div>	
			</div>
			<table style="height:auto" id="dg"
					url="<?php echo base_url().'user/loadDataMobileUser';?>" 
					title="" nowrap='false'
					iconCls="icon-save"   toolbar="#tb" striped="true"
					rownumbers="true" pagination="true"
					singleSelect="true" fitColumns="true">
					<thead>
						<tr>
							<?php
								$arr=array('user_id'=>'User ID','user_name'=>'User Name','mobile_name'=>'Mobile App.','action'=>'Action');
								$width=array('125','250','170','70','120');
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