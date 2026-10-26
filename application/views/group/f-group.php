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
// masukkan kode javascript berikutnya disini
var Document = {
        param: {
                dataperpage: 10, // jumlah data per halaman
                query: '',
                curpage: 0,
                numpage: 0,
                id: 0
        },
        url: '<?php echo base_url()?>group/loadData',
        search: function() {
                this.param.query = $('#query').val();
                this.param.curpage = 0;
                this.loadData();
                return false;
        },
        setPage: function(n) {
                this.param.curpage = n;
                this.loadData();
                return false;
        },
        prevPage: function() {
                if (this.param.curpage > 0) {
                        this.param.curpage--;
                        this.loadData();
                }
                return false;
        },
        nextPage: function() {
                if (this.param.curpage < this.param.numpage) {
                        this.param.curpage++;
                        this.loadData();
                }
                return false;
        },
        deleteData: function(c,id) {
                var checkstr =  confirm('Are you sure to delete this data?');
                if(checkstr == true){
                        this.param.id = id;
                        this.param.curpage = c;
                        
                        $.ajax({
                                url: '<?php echo base_url()?>group/deleteData',
                                type: 'POST',
                                dataType: 'json',
                                data: jQuery.param(Document.param),
                                success: function() {
                                        Document.loadData();
                                }
                        });
                                
                        
                }
                return false;
        },
        loadData: function() {
                $.ajax({
                        url: Document.url,
                        type: 'POST',
                        dataType: 'json',
                        data: jQuery.param(Document.param),
                        success: function(d) {
                                $('#pagination').html(d.pagination);
                                Document.param.numpage = d.numpage;
                                var t = '', dt = {};
                                for (var i = 0; i < d.data.length; i++) {
                                        dt = d.data[i];
                                        var bgcolor = "";
                                        if( (i+1) % 2 == 0) bgcolor = "#eeeeee";
                                        t += '<tr class="selectable">' +
                                                '<td class="grid_id">'+ ((i+1+(Document.param.dataperpage *Document.param.curpage)) ) +'</td>' +
                                                '<td class="grid_alias">'+ dt.nama_grup +'</td>' +
                                                '<td class="grid_alias">'+ dt.caption +'</td>' +
                                                '<td class="grid_alias">'+ dt.status +'</td>' +
                                                
                                                '<td class="grid_aksi" align="center">' +
                                                        '<span class="btnEditData btn-action glyphicons pencil btn-success" group_id="'+ dt.id_grup +'" p_id="'+ dt.parent_id +'" ter_id="'+ dt.territory_type_id +'" style="cursor:pointer;"><i></i></span>' +
                                                        '<span class="btn-action glyphicons remove_2 btn-danger" onclick="return Document.deleteData('+ d.curpage +','+ dt.id_grup +')" style="cursor:pointer;"><i></i></span>' +
                                                '</td>' +
                                             '</tr>';
                                }
                                $('#document-data').html(t); 
                        }
                });
        }
}
           
$(document).ready(function() {
        $('#dg').datagrid({
		pageSize:10,
		fitColumns:true,
		nowrap:false,
		singleSelect:true
	});
        
	$("#btnAddData").live("click",function(){
		$(this).hide();
		$("#divForm").show();
		$("#h4judul").html("Add New Role");
		$('body,html').animate({ scrollTop: 0 }, 800);
		
		$("#group_id")	.val( '' );
		$('#add-form')[0].reset();
		$('#status').attr('checked', false);
	});
        
        $("#btnCancelAddData").live("click",function(){
		$("#btnAddData").show();
		$("#divForm").hide();
		$('#divForm').find('form')[0].reset();
		$("#group_id")	.val( '' );
	});
        
        $(".btnEditData").live("click",function(){
		$("#divForm").show();
		$("#h4judul").html("Edit Data Menu");
                $('body,html').animate({ scrollTop: 0 }, 800);
		
		$("#group_id")	.val( $(this).attr('group_id') );
		$("#nama")	.val( $("td:eq(1)", $(this).parent().parent()).text() );
		$("#caption")	.val( $("td:eq(2)", $(this).parent().parent()).text() );
		$("#teritori")	.val( $(this).attr('ter_id') );
                $("#parent")	.val( $(this).attr('p_id') );
                $("#teritori")	.val( $(this).attr('ter_id') );
                
                $('#status').prop('checked', ($("td:eq(5)", $(this).parent().parent()).text() == "Aktif"?true:false) );
		
                $("#having_stock").prop('checked', ($(this).attr('hav_stock') == "1"?true:false) ); 
                $("#is_use_top").prop('checked', ($(this).attr('is_top') == "1"?true:false) ); 	
                
	});
                
        $('#add-form').live("submit",function(e){
                $.ajax({
                        type: "POST",
                        url: "<?php echo base_url()?>group/saveData",
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
			$("#h4judul").html("Edit Role");
			$('body,html').animate({ scrollTop: 0 }, 800);
			$('#area_restriction').val('');
			$.ajax({
				type: "POST",
				url: "<?php echo base_url()?>group/EditGroup",
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
						
						$("#group_id")	.val( data.hasil.user_group_id );
						$("#nama")	.val( data.hasil.user_group_name );
						$("#caption")	.val( data.hasil.user_group_caption );
						$.each($('#area_restriction').children(),function(){
              if($(this).val() == data.hasil.area_restriction) {
                $(this).attr('selected', true);
              }
            });
						$('#status').prop('checked', (data.hasil.status == 1?true:false) );						
					}
				}
			});
		}
		
		function doDelete(group_id){
			if (confirm('Delete Role?')){
				$.ajax({
					url:"<?php echo base_url()?>group/deleteData",
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

<div class="separator bottom"></div>

<div class="innerLR">
	
        <div class="widget" id="divForm" style="display:none;">
		<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
		<div class="widget-head">
			<h4 class="heading glyphicons list" id="h4judul">Add New Role</h4>
		</div>
		<div class="widget-body">
                        
                        <div class="row-fluid">
				<div>
                                        <input type='hidden' name='id' id='group_id'>
					<div class="control-group" id="form_nama">
						<label class="control-label span3" for="nama">Role Name</label>
						<div class="controls">
							<input class="span4" id="nama" name="nama" type="text" value="" />
						</div>
					</div>
					<div class="control-group" id="form_caption">
						<label class="control-label span3" for="caption">Description</label>
						<div class="controls">
                                                        <input class="span9" id="caption" name="caption" type="text" value="" />
                                                </div>
					</div>
          <div class="control-group" id="form_caption">
            <label class="control-label span3" for="caption">Area Restriction</label>
            <div class="controls">
              <select name="area_restriction" id="area_restriction">
                <option value=""></option>
                <?php 
                  foreach ($area_restriction as $key) {
                    echo '<option value="'.$key->lookup_value.'">'.$key->lookup_desc.'</option>';
                  }
                ?>
              </select>
            </div>
          </div>
					<div class="control-group" id="form_status">
						<label class="control-label span3" for="status">Status</label>
						<div class="controls">
                                                        <input type="checkbox" id="status" name="status" style="margin-bottom:3px;"><span> Aktif</span>
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
			<button class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right" id="btnAddData"><i></i> Add New Role</button>
			
			
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
					url="<?php echo base_url().'group/loadData';?>" 
					title="" nowrap='false'
					iconCls="icon-save"   toolbar="#tb" striped="true"
		        	rownumbers="true" pagination="true"
					singleSelect="true" fitColumns="true">
					<thead>
						<tr>
							<?php
								$arr=array('user_group_name'=>'Role Name','user_group_caption'=>'Description','status'=>'Status','area_restriction' => 'Area Restriction','action'=>'Action');
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