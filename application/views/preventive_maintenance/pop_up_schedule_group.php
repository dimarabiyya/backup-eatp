<div class="innerLR">
<h4 style="margin-top: 20px;">PM Schedule Summary <?php echo $month_year?></h4>

<?php if($type == 'user') { if(isset($user_info)) { ?>
<table class="table table-bordered" style="margin-bottom: 10px; width: 50%">
  <tr><td width="25%">User ID</td><td><?php echo $user_info['user_id']; ?></td></tr>
  <tr><td width="25%">User Name</td><td><?php echo $user_info['user_name']; ?></td></tr>
  <!-- <tr><td>Region</td><td><?php echo $user_info['']; ?></td></tr> -->
</table>
<?php } } else { if(isset($site_info)) {?>
<table class="table table-bordered" style="margin-bottom: 10px; width: 50%">
  <tr><td width="25%">Site ID</td><td><?php echo $site_info['site_code']; ?></td></tr>
  <tr><td width="25%">Vendor ID</td><td><?php echo $site_info['vendor_id']; ?></td></tr>
  <tr><td width="25%">Site Name</td><td><?php echo $site_info['site_name']; ?></td></tr>
  <tr><td width="25%">Site Address</td><td><?php echo $site_info['site_address']; ?></td></tr>
  <tr><td width="25%">Region</td><td><?php echo $site_info['region_name'].', '.$site_info['cluster_name']; ?></td></tr>
</table>
<?php } } ?>

<?php $request_url = base_url().'pm/schedule/rdszef/'.$type.'/'.$year.'/'.$month.'/'.$cluster_id.'/'.$param.'/'.$territoryType; ?>

<table style="width:960px !important; height:250px;" id="visitdg" fitColumns="true" url="<?php echo $request_url;?>">
  <thead>
    <tr>
      <?php if($type == 'user') { ?>
      <th data-options="field:'site_code', width:25">Site ID</th>
      <th data-options="field:'vendor_id', width:25">Vendor ID</th>
      <th data-options="field:'site_name', width:25">Assigned Site</th>
      <?php } else { ?>
      <th data-options="field:'user_name', width:25">Assigned PIC</th>
      <?php } ?>
      <th data-options="field:'visit_plan'">Visit Plan</th>
      <th data-options="field:'last_visit_date'">Visit Date</th>
      <th data-options="field:'visit_status'">Status</th>
      <th data-options="field:'action_button'" >Action</th>
    </tr>
  </thead>

</table>


</div>

<div class="form-actions">
  <button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove"><i></i>Close</button>
  <script>
    $(".btn-close").click(function(){$.fancybox.close();});
  </script>
</div>

<script>
  
  function detailVisit(pm_id) {
    $.fancybox({
      href:"<?php echo base_url('pm/summary/detailSummary')?>/"+pm_id ,type:'ajax',
      minWidth:1000,scrolling:false,autoSize :true,fitToView : true, closeClick : false,openEffect : 'elastic',closeEffect : 'elastic'
    });
  }

  $(function(){
    var data_type = '<?php echo $type; ?>';
    if(data_type == 'user') {
      $('#visitdg').datagrid({
        view:detailview,
        nowrap:true,
        singleSelect:true,
        detailFormatter:function(index,row){
          return '<div style="padding:2px"><table class="ddv"></table></div>';
        },
        onExpandRow: function(index,row){
          var ddv = $(this).datagrid('getRowDetail',index).find('table.ddv');
          ddv.datagrid({
            url:'<?php echo base_url()."pm/schedule/subres/" ?>'+row.parent_id+'/'+data_type,
            fitColumns:true,
            singleSelect:true,
            rownumbers:true,
            loadMsg:'',
            height:'auto',
            title:'Edited',
            columns:[[
              {field:'site_code',title:'Site ID', width: 25},
              {field:'vendor_id',title:'Vendor ID', width: 25},
              {field:'site_name',title:'Assigned Site', width: 25},
              {field:'visit_plan',title:'Visit Plan'},
              {field:'visit_status',title:'Status'},
              {field:'updated_on',title:'Reschedule Date'}
            ]],
            onResize:function(){
              $('#visitdg').datagrid('fixDetailRowHeight',index);
            },
            onLoadSuccess:function(){
              setTimeout(function(){
                  $('#visitdg').datagrid('fixDetailRowHeight',index);
              },0);
            }
          });
          $('#visitdg').datagrid('fixDetailRowHeight',index);
        }
      });
    }
    else {
      $('#visitdg').datagrid({
        view:detailview,
        nowrap:true,
        singleSelect:true,
        detailFormatter:function(index,row){
          return '<div style="padding:2px"><table class="ddv"></table></div>';
        },
        onExpandRow: function(index,row){
          var ddv = $(this).datagrid('getRowDetail',index).find('table.ddv');
          ddv.datagrid({
            url:'<?php echo base_url()."pm/schedule/subres/" ?>'+row.parent_id+'/'+data_type,
            fitColumns:true,
            singleSelect:true,
            nowrap:true,
            rownumbers:true,
            loadMsg:'',
            height:'auto',
            columns:[[
              {field:'user_name',title:'Assigned PIC', width: 25},
              {field:'visit_plan',title:'Visit Plan'},
              {field:'visit_status',title:'Status'},
              {field:'updated_on',title:'Creation Date'}
            ]],
            onResize:function(){
              $('#visitdg').datagrid('fixDetailRowHeight',index);
            },
            onLoadSuccess:function(){
              setTimeout(function(){
                  $('#visitdg').datagrid('fixDetailRowHeight',index);
              },0);
            }
          });
          $('#visitdg').datagrid('fixDetailRowHeight',index);
        }
      });
    }
  });
</script>