<?php
$getUserRole	= $this->muser->getUserGroup();
$region= $this->muser->getRegionByUserRegion();

?>
<div class="widget">
	<div class="widget-head"><h4 class="heading">User List</h4></div>
	
	<div class="widget-body">
	
			<div id="tbpop" style="padding: 10px 10px 0px 10px; height:auto">
                <div class="input-prepend">
                  <span class="add-on">Region</span>
                  <select name="region" id="cmb_region" style="width: 130px;">
                        <option value="ALL">ALL</option>
                      <?php 
                        foreach ($region as $tt) {
                          echo '<option value="'.$tt->region_id.'">'.$tt->region_name.'</option>'; 
                        }
                      ?>
                  </select>
                </div>
                <div class="input-prepend">
                  <span class="add-on">User Role</span>
                  <select name="role" id="cmb_role" style="width: 130px;">
                        <option value="ALL">ALL</option>
                      <?php 
                        foreach ($getUserRole as $tt) {
                          echo '<option value="'.$tt->user_group_id.'">'.$tt->user_group_name.'</option>'; 
                        }
                      ?>
                  </select>
                </div>
                <button class="btn" onclick="doPopSearch()" style="margin-bottom: 10px">Apply</button>

            <div class="input-append pull-right">
              <input class=""  id="qpop" type="text" placeholder="Type text to search .." onKeyPress="return doPopSearchEnter(event)"/> 
              <button class="btn" onclick="doPopSearch()" >Search</button>
            </div>

			</div>
			<table style="height:auto;width:900px" id="dg-users"
					url="<?php echo base_url().'user/loadData';?>" 
					idField="user_id"
					iconCls="icon-save"   toolbar="#tbpop"
                                        rownumbers="true" pagination="true"
					singleSelect="false">
					<thead>
						<tr>
							<th data-options="field:'ck',checkbox:true"></th>
							<?php
                        $arr=array('user_name'=>'User Name',
                                   'user_id'=>'User ID',
                                   'user_group_name'=>'User Role',
                                   'mobile_number'=>'Mobile Number',
                                   'email'=>'Email',
                                   'status_name'=>'Status');
								$width=array('100','90','100','70','160','35');
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
			
		<div class="form-actions">
			<button type="button" class="cancel btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="$.fancybox.close()"><i></i>Close</button>
		</div>
	
	</div>
	
</div>

<script>
        function doPopSearch(){
			$('#dg-users').datagrid('load',{
				q: $('#qpop').val(),
		        region: $("#cmb_region").val(),
		        role: $("#cmb_role").val(),
			});
		}
        function doPopSearchEnter( event ){
			if (event.which == 13 || event.keyCode == 13) {
                $('#dg-users').datagrid('load',{
                    q: $('#qpop').val(),
                    region: $("#cmb_region").val(),
                    role: $("#cmb_role").val(),
                    });
             }
            return true;
		}


var lookupUsers = {
	params: {
		cmd: 'get_lookup_users',
		act: 'get_data',
		query: ''
	},
	loadData: function() {

        $('#dg-users').datagrid({
			pageSize:10,
			fitColumns:true,
			nowrap:false,
			onCheck:function(rowIndex,rowData){
				doAddUsertoTable(rowData);
			},
			onUncheck:function(rowIndex,rowData){
				doRemoveUserFromTable(rowData);
			},
			onCheckAll:function(rows){
				$.each(rows, function(index, val) {
					doAddUsertoTable(val);
				});
			},
			onUncheckAll:function(rows){
				$.each(rows, function(index, val) {
					doRemoveUserFromTable(val);
				});
			},
		});

		// $('#dg-users').datagrid({
		// 	url: '<?php echo site_url('/user/loadData') ?>',
		// 	queryParams: this.params,
		// 	method: 'GET',
		// 	pageSize: 10,
		// 	frozenColumns: [[{ field: 'chkData', checkbox: true }]],
		// 	columns:[[
		// 		{ field: 'user_id', hidden: true },
		// 		{ field: 'user_name', title: 'User Name', width: 120, sortable: true }
		// 	]],
		// 	iconCls: 'icon-save',
		// 	toolbar: '#tb-users',
		// 	striped: true,
		// 	pagination: true,
		// 	singleSelect: true,
		// 	fitColumns: true,
		// 	rownumbers: true,
		// 	width: 740,
		// 	height: 'auto',
		// 	onLoadSuccess: function(data) {
		// 		for (var i = 0; i < data.rows.length; i++) {
		// 			if (data.rows[i].user_id == document.getElementsByName('<?php echo $this->input->get('e') ?>')[0].value) {
		// 				$(this).datagrid('checkRow', i);
		// 			}
		// 		}
		// 	},
		// 	onDblClickCell: function(index, field, value) {
		// 		document.getElementById('btnDataSelectedOK').click();
		// 	}
		// });
	},
	search: function() {
		this.params.query = document.getElementById('q').value;
		this.loadData();
		document.getElementById('q').select();
		
		return false;
	},
	init: function() {
		this.params.query = document.getElementById('q').value;
		this.loadData();
		
		document.getElementById('q').onkeydown = function(e) {
			if (e.keyCode == 13) {
				lookupUsers.search();
			}
		};
	}
};

$(function() {
	    $('.fancybox-wrap').draggable({ handle: ".widget-head" });

	lookupUsers.init();
});
</script>