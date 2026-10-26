
<div class="innerLR">
  <div class="widget">
    <div class="widget-head heading-outer">
      <h4 class="heading">Assignment Cluster Coordinator</h4>
      <a href="<?php echo site_url('/preventive/assignment_ssh/add') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons circle_plus pull-right"><i></i> Add</a>
    </div>

    <div class="widget-body">
      <div id="toolbar" class="" style="background-color: #ededed; padding: 10px 10px 0 10px">
        <div class="input-prepend">
          <span class="add-on">Region</span>
          <select name="user_region" id="user_region" onchange="getCluster()" style="width: 130px;">
          <?php 
            $region = $this->session->userdata('user_region');
            foreach ($region as $tt) {
              echo '<option value="'.$tt['region_id'].'">'.$tt["region_name"].'</option>'; 
            }
          ?>
          </select>
        </div>
        <div class="input-prepend">
          <span class="add-on">Cluster</span>
          <select name="user_cluster" id="user_cluster" onchange="reloadDg()" style="width: 130px;">
          </select>
           <button class="btn" onclick="reloadDg()">Apply</button>
        </div>
       
   
        <div class="input-append pull-right">
        <input type="text" value="" id="q" onkeyup="if(event.keyCode == 13){ reloadDg();}" class="input-medium" placeholder="Keyword..." autocomplete="off">
		<!-- <button style="margin: 0 0 10px 0" class="btn" onclick="reloadDg()">Search</button> -->                           
		<button class="btn" onclick="reloadDg()">Search</button>
        </div>     
      </div>
      
      <table id="dataGridClusterCoordinator" style="height: 450px" fitColumns="true" nowrap="false" url="<?php echo base_url() ?>preventive/grid/cluster_coordinator" rownumbers="true" pagination="true" singleSelect="true">
        <thead>
          <tr>
            <th field="username" width="250px">User Name</th>
            <th field="mobile_number" width="250px">Mobile Number</th>
            <th field="user_group_role" width="250px">Roles</th>
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
  // $('#user_cluster option').prependTo('<option value=""></option>');
  getCluster();
  


  $('#dataGridClusterCoordinator').datagrid({
    queryParams: {region_id: $('#user_region').val(),cluster_id: $('#user_cluster').val(),keyword: $('#q').val()},
    method: 'POST',
    view:detailview,
        detailFormatter:function(index,row){
          var x = '<div id="toolbarx-' + index + '" style="padding:2px; background-color: #ededed"> <div class="input-append" style="margin:0px"> <input type="text" name="q" value="" id="keyword-'+index+'" class="input-medium" placeholder="Keyword..." autocomplete="off" onkeyup="if(event.keyCode == 13){ loadSubgrid(\''+row.user_id+'\', '+index+');}"><button class="btn" onclick="loadSubgrid(\''+row.user_id+'\', '+index+')">Search</button></div></div><table id="ddv-' + index + '"></table>';
          return x;
        },
        onExpandRow: function(index,row){
          $('#dataGridClusterCoordinator').datagrid('fixDetailRowHeight',index);
          var ddv = $(this).datagrid('getRowDetail',index).find('table#ddv-'+index);
          ddv.datagrid({
            url:'<?php echo base_url()."preventive/grid/cluster_coordinator_mapping/" ?>',
            //queryParams: {user_id: row.user_id,region_id: row.region_id, table_id : '#ddv-'+index},
           	queryParams: {user_id: row.user_id, table_id : '#ddv-'+index},
            method: 'POST',
            singleSelect:true,
            fitColumns: true,
            rownumbers:true,
            pagination:true,
            loadMsg:'Loading',
            height:'auto',
            columns:[[
              {field:'region_name',title:'Region'},
              {field:'cluster_name',width:100,title:'Opr. Cluster '},         
              {field:'button',title:'Remove'}
            ]],

            onResize:function(){
              $('#dataGridClusterCoordinator').datagrid('fixDetailRowHeight',index);
            },
            onLoadSuccess:function(){
              setTimeout(function(){
                  $('#dataGridClusterCoordinator').datagrid('fixDetailRowHeight',index);
              },0);
            }
          });
          $('#dataGridClusterCoordinator').datagrid('fixDetailRowHeight',index);
          doPreviledgePerfield("ddv-"+index);
        }
  });

  function getCluster() {
    var iR = $('#user_region').val();
    $.post(
      '<?php echo base_url() ?>preventive/assignment_ssh/get_cluster',
      {region_id: iR},
      function(data){
        var datax = jQuery.parseJSON(data);
        $('#user_cluster').empty();
        $("#user_cluster").prepend(new Option('', '', true, true));
        $.each(datax, function(k,v){
          $('#user_cluster').append(new Option(v.cluster_name, v.cluster_ops_id));
        }
        );
      }
    )
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


  function proceedDelete(user_id, cluster_id, z) {
    $.post(
      "<?php echo base_url() ?>preventive/assignment_ssh/deleteAssignment",
      {cluster_id: cluster_id, user_id: user_id},
      function(data) {
        //reloadDg();
        //loadSubgrid (userId, tId);
         $(z).datagrid('reload');
      }
    )
  }


/*
  function DeleteMapping(x) {
    var n = noty({
      layout       : 'center',
      theme        : 'defaultTheme',
      width        : '500px',
      type         : 'error',
      text         : 'This action cannot be reversed Click "Delete !" to proceed',
      buttons: [
        {addClass: 'btn btn-danger', text: 'Delete !', onClick: function($noty) {
            $noty.close();
            proceedDelete(x);
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

  function proceedDelete(user_id) {
    $.post(
      "<?php //echo base_url() ?>preventive/assignment_ssh/deleteAssignment",
      {user_id: user_id},
      function(data) {
        reloadDg();
      }
    )
  }*/

  function reloadDg(){
    $('#dataGridClusterCoordinator').datagrid('load',{
      region_id: $('#user_region').val(),
      cluster_id: $('#user_cluster').val(),
      keyword: $('#q').val()
    });
  }
  
    function loadSubgrid (userId, tId) {
    $('#ddv-'+tId).datagrid('load', {
      table_id: '#ddv-'+tId,
      keyword: $('#keyword-'+tId).val(),
      user_id: userId
    });
  }


function deleteAllSiteAssignment(userid, username) {
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
              '<?php echo base_url() ?>preventive/assignment_ssh/deleteAllAssignment',
              {user_id: userid},
              function(data) {
                $('#dataGridClusterCoordinator').datagrid('reload');
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

<!--
<script type="text/javascript">

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
    
    console.log(subtoolbar);
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
  function reloadDg(){
    $('#dataGridUserPic').datagrid('load',{
      region_id: $('#user_region').val(),
      keyword: $('#q').val()
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
-->
