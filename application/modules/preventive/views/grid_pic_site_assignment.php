<div class="innerLR">
  <div class="widget">
    <div class="widget-head heading-outer">
      <h4 class="heading">Assignment PIC to Site</h4>
      <a href="<?php echo site_url('/preventive/assignment_pic/add') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right"><i></i> Add</a>
    </div>

    <div class="widget-body">
      <div id="toolbar" class="" style="background-color: #ededed; padding: 10px 10px 0 10px">
        <div class="input-prepend">
          <span class="add-on">Region</span>
          <select name="user_region" id="user_region" onchange="reloadDg(1)" style="width: 130px;">
          	<option value="">ALL</option>
          <?php 
            $region = $this->session->userdata('user_region');
            foreach ($region as $tt) {
			// onchange
            echo '<option value="'.$tt['region_id'].'">'.$tt["region_name"].'</option>'; 
            }
          ?>
          </select>

          <span class="add-on">Cluster</span>
          <select name="cluster_ops_id" id="cluster_ops_id" style="width: 130px;" onchange="reloadDg()">
<!--           	<option value="">ALL</option> -->

          </select>

          
        </div>
        <div class="input-append pull-right">
          <input type="text" value="" id="q" onkeyup="if(event.keyCode == 13){ reloadDg();}" class="input-medium" placeholder="Keyword..." autocomplete="off">
          <button class="btn" onclick="reloadDg()">Search</button>
        </div>
      </div>
      
      <table class="jgrid" id="dataGridUserPic" style="height: 450px" fitColumns="true" nowrap="false" url="<?php echo base_url() ?>preventive/grid/pic_site" rownumbers="true" pagination="true" singleSelect="true" fitColumns="true">
        <thead>
          <tr>
          	<th field="username" width="180px">User Name</th>
            <th field="pic_smart" width="180px">User ID</th>
            <th field="imei" width="120px">MEID</th>
            <th field="msisdn" width="120px">MDN</th>
            <th field="user_roles" width="120px">Roles</th>
            <th field="user_region"  width="200px">Region</th>
            <th field="cluster_name">Opr. Cluster</th>
            <th field="fops_ac">FOPS AC</th>
            <th field="buttons" width="60px" align="center">Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/icon.css">
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/datagrid-detailview.js"></script>

<script src="<?php echo base_url()?>assets/scripts/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/scripts/DataTables/media/js/DT_bootstrap.js"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/jquery.noty.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/layouts/center.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/notty/noty/themes/default.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css"/>
<link rel="stylesheet" href="<?php echo base_url()?>file/template/smashing/theme/scripts/notyfy/themes/default.css"/>


<script type="text/javascript">
$(document).on('change', '#cluster_ops_id', function() {
	reloadDg(0);
})
$('#user_region').change(function() {
	$('#cluster_ops_id > option').remove();
	cluster();
})
// $(document).on('change', '#user_region', function() {
	// reloadDg();
// })
cluster();
function cluster () {
	var region_idx = $('#user_region').val();
	$.post(
		'<?php echo site_url('/preventive/grid/getCluster') ?>',
		{region_id: region_idx},
		function(data){
			// console.log(data);
			// var x = jQuery.parseJSON(data);		
			// console.log(data);
			//$('#cluster_ops_id > option').remove();
			$('#cluster_ops_id').append(data);
		}
	)
}

  function reloadDg(cluster_change){

  	if (cluster_change == 1){
  		var cluster_ops_id ='';
  	} else {
  		var cluster_ops_id = $('#cluster_ops_id').val();
  	}
  	  	// console.log(cluster_ops_id);
    $('#dataGridUserPic').datagrid('load',{
      region_id: $('#user_region').val(),
      cluster_ops_id: cluster_ops_id,
      // cluster_ops_id: '4',
      keyword: $('#q').val()
    });
    // $('#cluster_ops_id > option').remove();

    //cluster();
  }


  $('#dataGridUserPic').datagrid({
        queryParams: {region_id: $('#user_region').val(), keyword: $('#q').val()},
        method: 'POST',
        view:detailview,
        detailFormatter:function(index,row){
          var x = '<div id="toolbarx-' + index + '" style="padding:2px; background-color: #ededed"> <div class="input-append" style="margin:0px"> <input type="text" name="q" value="" id="keyword-'+index+'" class="input-medium" placeholder="Keyword..." autocomplete="off" onkeyup="if(event.keyCode == 13){ loadSubgrid(\''+row.pic_smart+'\', '+index+');}"><button class="btn" onclick="loadSubgrid(\''+row.pic_smart+'\', '+index+')">Search</button></div></div><table id="ddv-' + index + '"></table>';
          return x;
        },
        onExpandRow: function(index,row){
          $('#dataGridUserPic').datagrid('fixDetailRowHeight',index);
          var ddv = $(this).datagrid('getRowDetail',index).find('table#ddv-'+index);
          ddv.datagrid({
            url:'<?php echo base_url()."preventive/grid/pic_site_mapping/" ?>',
            queryParams: {user_id: row.pic_smart, table_id : '#ddv-'+index},
            method: 'POST',
            singleSelect:true,
            rownumbers:true,
            pagination:true,
            loadMsg:'Loading',
            height:'auto',
            columns:[[
              {field:'region_name',title:'Region'},
              {field:'cluster_name',title:'Cluster'},
              {field:'site_code',width:100,title:'Site ID'},
              {field:'vendor_id',width:100,title:'Vendor ID'},
              {field:'site_name',title:'Site Name'},
              {field:'latitude',width:100, align: 'right', title:'Latitude'},
              {field:'longitude',width:100, align: 'right', title:'Longitude'},
              {field:'site_address',title:'Site Address', width: 250},
              {field:'button',title:'Remove'}
            ]],

            onResize:function(){
              $('#dataGridUserPic').datagrid('fixDetailRowHeight',index);
            },
            onLoadSuccess:function(){
              setTimeout(function(){
                  $('#dataGridUserPic').datagrid('fixDetailRowHeight',index);
              },0);
            }
          });
          $('#dataGridUserPic').datagrid('fixDetailRowHeight',index);
          doPreviledgePerfield("ddv-"+index);
        }
  });

  function toolbar8 (a) {
    var subtoolbar = '<div class="toolbarx" id="toolbarx-'+a+'" style="background-color: #ededed; height: 32px; display: none; " >'+
      '<div class="input-append pull-right">'+
        '<input type="text" name="q" value="" id="keyword-'+a+'" class="input-medium" placeholder="Keyword..." autocomplete="off">'+
        '<button class="btn" onclick="">Search</button>'+
      '</div>'+
    '</div>';
    
    // console.log(subtoolbar);
    return subtoolbar;
    // return subtoolbar.attr('id', 'toolbarx-'+a).context;
  }
  function DeleteMapping(x, y, z) {
    var n = noty({
      layout       : 'center',
      theme        : 'defaultTheme',
      width        : '500px',
      type         : 'error',
      text         : 'This action cannot be reversed Click "Delete !" to proceed',
      buttons: [
        {addClass: 'btn btn-danger', text: 'Delete !', onClick: function($noty) {
            $noty.close();
            proceedDelete(x, y, z);
          }
        },
        {addClass: 'btn btn-success', text: 'Abort', onClick: function($noty) {
            $noty.close();
            // noty({dismissQueue: true, force: true, layout: layout, theme: 'defaultTheme', text: 'You clicked "Cancel" button', type: 'error'});
          }
        }
      ],
      dismissQueue : true,
      modal        : true
    });
  }

  function proceedDelete(x, y, z) {
    $.post(
      "<?php echo base_url() ?>preventive/assignment_pic/deletePicSiteMapping",
      {site_id: x, user_id: y},
      function(data) {
        $(z).datagrid('reload');
      }
    )
  }

  function loadSubgrid (userId, tId) {
    $('#ddv-'+tId).datagrid('load', {
      table_id: '#ddv-'+tId,
      keyword: $('#keyword-'+tId).val(),
      user_id: userId
    });
  }

  function deleteSiteAssignmentPic(userid, username) {
    var n = noty({
      layout       : 'center',
      theme        : 'defaultTheme',
      width        : '500px',
      type         : 'error',
      text         : 'By Deleting this, mean "'+username+'" will no longer maintain any site',
      buttons: [
        {addClass: 'btn btn-danger', text: 'Delete !', onClick: function($noty) {
            $noty.close();
            $.post(
              '<?php echo base_url() ?>preventive/assignment_pic/deleteSiteAssignmentPic',
              {user_id: userid},
              function(data) {
                $('#dataGridUserPic').datagrid('reload');
              }
            );
          }
        },
        {addClass: 'btn btn-success', text: 'Abort', onClick: function($noty) {
            $noty.close();
          }
        }
      ],
      dismissQueue : true,
      modal        : true
    });
  }
</script>