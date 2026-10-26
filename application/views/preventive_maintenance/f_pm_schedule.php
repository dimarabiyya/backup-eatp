<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/scripts/jeasyui/icon.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jeasyui/datagrid-detailview.js"></script>
<script src="<?php echo base_url(); ?>assets/js/overlay.js"></script>
<style type="text/css">
  .input-prepend .add-on {
    background: #efefef !important;
  }
</style>

<div class="heading-buttons">
  <h3>
    <?php echo $title;
      if(isset($subTitle) ) 
        { echo '<span class="hidden-phone">| '.$subTitle.'</span>'; } 
    ?>
  </h3>
</div>

<div class="separator bottom"></div>
<div class="innerLR">
  <div class="widget widget-tabs">
    <div class="widget-head">
      <ul>
        <li class="active"><a class="glyphicons wifi_alt" href="#tab-1" data-toggle="tab" onclick="loadSiteSchedule()"><i></i>Schedule By Site</a></li>
        <li><a class="glyphicons user" href="#tab-2" data-toggle="tab" onclick="userSearch()"><i></i>Schedule By PIC</a></li>
        <li><a class="glyphicons user" href="#tab-3" data-toggle="tab" onclick="revList()"><i></i>Cancellation/Revision</a></li>
      </ul>
    </div>

    <div class="widget-body">

      <div class="tab-content">
        <div class="tab-pane active" id="tab-1">
          <div id="toolBar1" style="padding: 10px 10px 0 10px; height:auto">
            <?php if(count($territory)) { ?>
            <div class="input-prepend">
              <span class="add-on">Cluster</span>
              <select name="operational_cluster" id="tb1OprCluster" style="width: 130px;">
              <?php 
                foreach ($territory as $tt) {
                  echo '<optgroup label="'.$tt["region_name"].'">';
                  foreach ($tt['cluster'] as $cc) 
                    { echo '<option value="'.$cc['cluster_ops_id'].'">'.$cc["cluster_name"].'</option>'; }
                  echo '</optgroup>';
                }
              ?>
              </select>
              <input type="hidden" value="cluster" id="tb1territorytype">
            </div>
            <?php } else { ?>
            <div class="input-prepend">
              <span class="add-on">Region</span>
              <select name="operational_region" id="tb1OprRegion" style="width: 130px;">
              <?php 
                foreach ($region as $tt) {
                  echo '<option value="'.$tt['region_id'].'">'.$tt["region_name"].'</option>'; 
                }
              ?>
              </select>
              <input type="hidden" value="region" id="tb1territorytype">
            </div>
            <?php } ?>
            <div class="input-prepend">
              <span class="add-on">Opr. Year</span>
              <select name="operational_year" id="tb1OprYear" style="width: 100px;" onchange="loadSiteSchedule()">
                <option value="<?php echo date('Y')-1; ?>"><?php echo date('Y')-1; ?></option>
                <option value="<?php echo date('Y'); ?>" selected="selected"><?php echo date('Y'); ?></option>
                <option value="<?php echo date('Y')+1; ?>"><?php echo date('Y')+1; ?></option>
              </select> &nbsp;
            </div>
            <button class="btn" onclick="loadSiteSchedule()" style="margin-bottom: 10px">Apply</button>

            <div class="input-append pull-right">
              <input class="" id="userIdSiteId_1" type="text" placeholder="Search..." onkeyup="if(event.keyCode == 13){ loadSiteSchedule();}"/> 
              <button class="btn" onclick="loadSiteSchedule()" >Search</button>
              <button class="btn" onclick="export_by_site(null)" >Export</button>
            </div>
            <!-- <button type="button" class="btn btn-default btn-small glyphicons search btn-info" onclick="loadSiteSchedule()" style="margin-top: -10px"><i></i> Search</button> -->
          </div>
          <table id="dg1" width="100%" class="easyui-datagrid" fitColumns="true" singleSelect="true" striped="true" rownumbers="true" url="<?php echo base_url() ?>pm/schedule/getSites" method="get" toolbar="#toolBar1" pagination="true">
            <thead>
              <tr>
                <!-- <th data-options="field:'region_name'">Region</th> -->
                <th data-options="field:'site_code'" sortable="true">Site ID</th>
                <th data-options="field:'site_name', width:200" sortable="true">Site Name</th>
                <th data-options="field:'vendor_id'" sortable="true">Vendor ID</th>
                <th data-options="field:'site_pic_name'" sortable="true">Site PIC</th>
                <th data-options="field:'m_01'" width="40" align="center" >Jan</th>
                <th data-options="field:'m_02'" width="40" align="center" >Feb</th>
                <th data-options="field:'m_03'" width="40" align="center" >Mar</th>
                <th data-options="field:'m_04'" width="40" align="center" >Apr</th>
                <th data-options="field:'m_05'" width="40" align="center" >May</th>
                <th data-options="field:'m_06'" width="40" align="center" >Jun</th>
                <th data-options="field:'m_07'" width="40" align="center" >Jul</th>
                <th data-options="field:'m_08'" width="40" align="center" >Aug</th>
                <th data-options="field:'m_09'" width="40" align="center" >Sep</th>
                <th data-options="field:'m_10'" width="40" align="center" >Oct</th>
                <th data-options="field:'m_11'" width="40" align="center" >Nov</th>
                <th data-options="field:'m_12'" width="40" align="center" >Dec</th>
                <th data-options="field:'btnAction'">Action</th>
              </tr>
            </thead>
            <tbody> <!-- Put something in here --> </tbody>
          </table>
        </div>

        <div class="tab-pane" id="tab-2">
          <table class="easyui-datagrid" id="dg2" title="" rownumbers="true" pagination="true" toolbar="#tb2" fitColumns="true" singleSelect="true" url="<?php echo base_url('pm/schedule/getUserSchedule');?>" method="get">
            <thead>
              <tr>
                <th data-options="field:'user_id'" sortable="true">User ID</th>
                <th data-options="field:'user_name', width:200" sortable="true">User Name</th>
                <th data-options="field:'m_01'" width="40" align="center" >Jan</th>
                <th data-options="field:'m_02'" width="40" align="center" >Feb</th>
                <th data-options="field:'m_03'" width="40" align="center" >Mar</th>
                <th data-options="field:'m_04'" width="40" align="center" >Apr</th>
                <th data-options="field:'m_05'" width="40" align="center" >May</th>
                <th data-options="field:'m_06'" width="40" align="center" >Jun</th>
                <th data-options="field:'m_07'" width="40" align="center" >Jul</th>
                <th data-options="field:'m_08'" width="40" align="center" >Aug</th>
                <th data-options="field:'m_09'" width="40" align="center" >Sep</th>
                <th data-options="field:'m_10'" width="40" align="center" >Oct</th>
                <th data-options="field:'m_11'" width="40" align="center" >Nov</th>
                <th data-options="field:'m_12'" width="40" align="center" >Dec</th>
              </tr>
            </thead>
          </table>

          <div id="tb2" style="padding: 10px 10px 0px 10px; height:auto">
            <?php if(count($territory)) { ?>
            <div class="input-prepend">
              <span class="add-on">Cluster</span>
              <select name="operational_cluster" id="opr_cluster_2" style="width: 130px;">
              <?php 
                foreach ($territory as $tt) {
                  echo '<optgroup label="'.$tt["region_name"].'">';
                  foreach ($tt['cluster'] as $cc) 
                    { echo '<option value="'.$cc['cluster_ops_id'].'">'.$cc["cluster_name"].'</option>'; }
                  echo '</optgroup>';
                }
              ?>
              </select>
              <input type="hidden" value="cluster" id="tb2territorytype">
            </div>
            <?php } else { ?>
            <div class="input-prepend">
              <span class="add-on">Region</span>
              <select name="operational_region" id="opr_region_2" style="width: 130px;">
              <?php 
                foreach ($region as $tt) {
                  echo '<option value="'.$tt['region_id'].'">'.$tt["region_name"].'</option>'; 
                }
              ?>
              </select>
              <input type="hidden" value="region" id="tb2territorytype">
            </div>
            <?php } ?>
                
            <div class="input-prepend">
              <span class="add-on">Opr. Year</span>
                <select name="operational_year" id="opr_year_2" style="width: 100px;">
                  <option value="<?php echo date('Y')-1; ?>"><?php echo date('Y')-1; ?></option>
                  <option value="<?php echo date('Y'); ?>" selected="selected"><?php echo date('Y'); ?></option>
                  <option value="<?php echo date('Y')+1; ?>"><?php echo date('Y')+1; ?></option>
                </select> 
            </div>
            <button class="btn" onclick="userSearch()" style="margin-bottom: 10px">Apply</button>
            <div class="input-append pull-right">
              <input class="" id="userIdSiteId_2" type="text" placeholder="Search..." onkeyup="if(event.keyCode == 13){ userSearch();}"/> 
              <button class="btn" onclick="userSearch()" >Search</button>
              <button class="btn" onclick="export_by_pic(2)" >Export</button>
            </div>
                <!-- <a href="#" class="easyui-linkbutton" iconCls="icon-search">Search</a> -->
          </div>
        </div> <!-- tab-pane-2 -->
      
        <div class="tab-pane" id="tab-3">
          <div id="toolBar3" style="padding: 10px 10px 0 10px; height:auto">
            <?php if(count($territory)) { ?>
            <div class="input-prepend">
              <span class="add-on">Cluster</span>
              <select name="operational_cluster" id="tb3OprCluster" style="width: 130px;">
              <?php 
                foreach ($territory as $tt) {
                  echo '<optgroup label="'.$tt["region_name"].'">';
                  foreach ($tt['cluster'] as $cc) 
                    { echo '<option value="'.$cc['cluster_ops_id'].'">'.$cc["cluster_name"].'</option>'; }
                  echo '</optgroup>';
                }
              ?>
              </select>
              <input type="hidden" value="cluster" id="tb3territorytype">
            </div>
            <?php } else { ?>
            <div class="input-prepend">
              <span class="add-on">Region</span>
              <select name="operational_region" id="tb3OprRegion" style="width: 130px;">
              <?php 
                foreach ($region as $tt) {
                  echo '<option value="'.$tt['region_id'].'">'.$tt["region_name"].'</option>'; 
                }
              ?>
              </select>
              <input type="hidden" value="region" id="tb3territorytype">
            </div>
            <?php } ?>
            
            <div class="input-prepend">
              <span class="add-on">Opr. Year</span>
              <select name="operational_year" id="tb3OprYear" style="width: 100px;">
                <option value="<?php echo date('Y')-1; ?>"><?php echo date('Y')-1; ?></option>
                <option value="<?php echo date('Y'); ?>" selected="selected"><?php echo date('Y'); ?></option>
                <option value="<?php echo date('Y')+1; ?>"><?php echo date('Y')+1; ?></option>
              </select>
            </div>

            <div class="input-prepend">
              <span class="add-on">Opr. Month</span>
              <select name="operational_month" id="tb3OprMonth" style="width: 100px;">
                <?php 
                  for ($i=1; $i <= 12; $i++) { 
                    if(date('m') ==  $i )
                      { echo '<option value="'.$i.'" selected="selected">'.date("F", mktime(0,0,0,$i,1,date('Y'))).'</option>'; }
                    else
                      { echo '<option value="'.$i.'" >'.date("F", mktime(0,0,0,$i,1,date('Y'))).'</option>'; }
                  }
                ?>
              </select>
            </div>
            <button class="btn" onclick="revList()" style="margin-bottom: 10px">Apply</button>

            <div class="input-append pull-right">
              <input class="" id="userIdSiteId_3" type="text" placeholder="Search..." onkeyup="if(event.keyCode == 13){revList()}"/> 
              <button class="btn" onclick="revList()" >Search</button>
              <button class="btn" onclick="export_by_revision(null)" >Export</button>
            </div>
            <!-- <button type="button" class="btn btn-default btn-small glyphicons search btn-info" onclick="loadSiteSchedule()" style="margin-top: -10px"><i></i> Search</button> -->
          </div>
          <table id="dg3" width="100%" class="easyui-datagrid" fitColumns="true" singleSelect="true" striped="true" rownumbers="true" url="<?php echo base_url() ?>pm/schedule/revList" method="get" toolbar="#toolBar3" pagination="true">
            <thead>
              <tr>
                <!-- <th data-options="field:'region_name'">Region</th> -->
                <th data-options="field:'site_code'">Site ID</th>
                <th data-options="field:'site_name', width:200">Site Name</th>
                <th data-options="field:'vendor_id'">Vendor ID</th>
                <!-- <th data-options="field:'user_id'">PIC ID</th> -->
                <th data-options="field:'user_name'">PIC Name</th>
                <th data-options="field:'visit_type'">Visit Type</th>
                <th data-options="field:'visit_plan'">Visit Plan</th>
                <th data-options="field:'visit_status'">Status</th>
                <th data-options="field:'reason_group'">Reason</th>
                <th data-options="field:'remarks'">Description</th>
                <th data-options="field:'created_on'">Revision Date</th>
              </tr>
            </thead>
            <tbody> <!-- Put something in here --> </tbody>
          </table>
        </div><!-- tab-pane-3 -->

    </div>
  </div>
</div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $("#menu_164").parent().addClass('active').find('.collapse').css('height','auto');
    loadSiteSchedule();

  });

  function loadSiteSchedule() {
    $('#dg1').datagrid('load',{
      opr_year      : $('#tb1OprYear').val(),
      opr_cluster   : $('#tb1OprCluster').val(),
      opr_region    : $('#tb1OprRegion').val(),
      territoryType : $('#tb1territorytype').val(),
      otherParam    : $('#userIdSiteId_1').val().trim()
    });
  }

  function userSearch() {
    // console.log('ksjlfkajdf');
    $('#dg2').datagrid('load',{
      opr_year      : $('#opr_year_2').val(),
      opr_cluster   : $('#opr_cluster_2').val(),
      opr_region    : $('#opr_region_2').val(),
      opr_month     : $('#opr_month_2').val(),
      territoryType : $('#tb2territorytype').val(),
      otherParam    : $('#userIdSiteId_2').val().trim()
    });
  }

  function revList() {
    $('#dg3').datagrid('load',{
      opr_year      : $('#tb3OprYear').val(),
      opr_month     : $('#tb3OprMonth').val(),
      opr_cluster   : $('#tb3OprCluster').val(),
      opr_region    : $('#tb3OprRegion').val(),
      territoryType : $('#tb3territorytype').val(),
      otherParam    : $('#userIdSiteId_3').val().trim()
    });
  }

  function addSchedule(site_id, year) {
    var nForm   = document.createElement("form");
        input_1 = document.createElement("input");
        input_2 = document.createElement("input");

    nForm.action = "<?php echo base_url('pm/schedule/create'); ?>";
    nForm.method = "post";
    
    input_1.name = "year";
    input_1.setAttribute("value", year);

    input_2.name = "site_id";
    input_2.setAttribute("value", site_id);

    nForm.appendChild(input_1);
    nForm.appendChild(input_2);
    document.body.appendChild(nForm);

    nForm.submit();
  }

  function getSiteVisit (site_id, month, visits) {
    if(visits > 0) {
      var nForm   = document.createElement("form");
          input_1 = document.createElement("input");
          input_2 = document.createElement("input");
          input_3 = document.createElement("input");
          input_4 = document.createElement("input");

      // nForm.action = "<?php echo base_url('pm/schedule/popUpSchedule'); ?>";
      nForm.action = "<?php echo base_url('pm/schedule/detail'); ?>";
      nForm.method = "post";

      input_1.name = "month_opr";      input_1.setAttribute("value", month);
      input_2.name = "year_opr";       input_2.setAttribute("value", $('#tb1OprYear').val());
      input_3.name = "site_id";        input_3.setAttribute("value", site_id);
      input_4.name = "cluster_ops_id"; input_4.setAttribute("value", $('#tb1OprCluster').val());
      input_5.name = "datatype";       input_5.setAttribute("value", 'site');

      nForm.appendChild(input_1);
      nForm.appendChild(input_2);
      nForm.appendChild(input_3);
      nForm.appendChild(input_4);
      document.body.appendChild(nForm);

      nForm.submit();

    }
    else 
    {
      console.log('eya');
    }
  }
  
  function getUserVisit (user_id, month, visits) {
    if(visits > 0) {
      var nForm   = document.createElement("form");
          input_1 = document.createElement("input");
          input_2 = document.createElement("input");
          input_3 = document.createElement("input");
          input_4 = document.createElement("input");

      nForm.action = "<?php echo base_url('pm/schedule/detail'); ?>";
      nForm.method = "post";
      
      input_1.name = "month_opr";      input_1.setAttribute("value", month);
      input_2.name = "year_opr";       input_2.setAttribute("value", $('#opr_year_2').val());
      input_3.name = "user_id";        input_3.setAttribute("value", user_id);
      input_4.name = "cluster_ops_id"; input_4.setAttribute("value", $('#opr_cluster_2').val());
      input_5.name = "datatype";       input_5.setAttribute("value", 'user');

      nForm.appendChild(input_1);
      nForm.appendChild(input_2);
      nForm.appendChild(input_3);
      nForm.appendChild(input_4);
      document.body.appendChild(nForm);

      nForm.submit();

    }
    else 
    {
      console.log('eya');
    }
  }

  function visitDetail (visit_id, datatype) {
    var nForm   = document.createElement("form");
        input_1 = document.createElement("input");
        input_2 = document.createElement("input");

    nForm.action = "<?php echo base_url('pm/schedule/visitdetail'); ?>";
    nForm.method = "post";
    
    input_1.name = "visit_id"; input_1.setAttribute("value", visit_id);
    input_2.name = "datatype"; input_2.setAttribute("value", datatype);

    nForm.appendChild(input_1);
    nForm.appendChild(input_2);
    document.body.appendChild(nForm);

    nForm.submit();
  }

  function export_by_site(param) {
    var nForm   = document.createElement("form");
        input_1 = document.createElement("input"); input_1.type="hidden";
        input_2 = document.createElement("input"); input_2.type="hidden";
        input_3 = document.createElement("input"); input_3.type="hidden";
        input_4 = document.createElement("input"); input_4.type="hidden";
        input_5 = document.createElement("input"); input_5.type="hidden";
        input_6 = document.createElement("input"); input_6.type="hidden";
        input_7 = document.createElement("input"); input_7.type="hidden";

    nForm.action = "<?php echo base_url('export/pmschedule'); ?>/excel/"+param+"/";
    nForm.method = "post";

    // var dfer = {"opr_year":,"opr_cluster":, "keyword":};
    input_1.name = "opr_year"; input_1.setAttribute("value", $('#tb1OprYear').val());
    input_2.name = "opr_cluster"; input_2.setAttribute("value", $('#tb1OprCluster').val());
    input_3.name = "keyword"; input_3.setAttribute("value", $('#userIdSiteId_1').val());
    input_4.name = "cluster_name"; input_4.setAttribute("value", $('#tb1OprCluster :selected').html());
    input_5.name = "tabletype"; input_5.setAttribute("value", 'sites');
    input_6.name = "opr_region"; input_6.setAttribute("value", $('#tb1OprRegion').val());
    input_7.name = "region_name"; input_7.setAttribute("value", $('#tb1OprRegion :selected').html());

    nForm.appendChild(input_1);
    nForm.appendChild(input_2);
    nForm.appendChild(input_3);
    nForm.appendChild(input_4);
    nForm.appendChild(input_5);
    nForm.appendChild(input_6);
    nForm.appendChild(input_7);
    document.body.appendChild(nForm);
    nForm.submit();
  }

  function export_by_pic(param) {
    var nForm   = document.createElement("form");
        input_1 = document.createElement("input"); input_1.type="hidden";
        input_2 = document.createElement("input"); input_2.type="hidden";
        input_3 = document.createElement("input"); input_3.type="hidden";
        input_4 = document.createElement("input"); input_4.type="hidden";
        input_5 = document.createElement("input"); input_5.type="hidden";
    nForm.action = "<?php echo base_url('export/pmschedule'); ?>/excel/"+param+"/";
    nForm.method = "post";

    // var dfer = {"opr_year":,"opr_cluster":, "keyword":};
    input_1.name = "opr_year"; input_1.setAttribute("value", $('#opr_year_2').val());
    input_2.name = "opr_cluster"; input_2.setAttribute("value", $('#opr_cluster_2').val());
    input_3.name = "keyword"; input_3.setAttribute("value", $('#userIdSiteId_2').val());
    input_4.name = "cluster_name"; input_4.setAttribute("value", $('#opr_cluster_2 :selected').html());
    input_5.name = "tabletype"; input_5.setAttribute("value", 'pic');
    nForm.appendChild(input_1);
    nForm.appendChild(input_2);
    nForm.appendChild(input_3);
    nForm.appendChild(input_4);
    nForm.appendChild(input_5);
    document.body.appendChild(nForm);
    nForm.submit();
  }

  function export_by_revision() {
    var xport = document.createElement("form");
        i_1   = document.createElement("input"); i_1.type="hidden";
        i_2   = document.createElement("input"); i_2.type="hidden";
        i_3   = document.createElement("input"); i_3.type="hidden";
        i_4   = document.createElement("input"); i_4.type="hidden";
        i_5   = document.createElement("input"); i_5.type="hidden";
        i_6   = document.createElement("input"); i_6.type="hidden";

    xport.action = "<?php echo base_url('export/pmschedule'); ?>/excel/";
    xport.method = "post";
    i_1.name  = "opr_year"; i_1.setAttribute('value', $('#tb3OprYear').val());
    i_2.name  = "opr_cluster"; i_2.setAttribute('value', $('#tb3OprCluster').val());
    i_3.name  = "keyword"; i_3.setAttribute('value', $('#userIdSiteId_3').val());
    i_4.name  = "cluster_name"; i_4.setAttribute('value', $('#tb3OprCluster :selected').html());
    i_5.name  = "tabletype"; i_5.setAttribute('value', 'revision');
    i_6.name  = "opr_month"; i_6.setAttribute('value', $('#tb3OprMonth').val());
    xport.appendChild(i_1);
    xport.appendChild(i_2);
    xport.appendChild(i_3);
    xport.appendChild(i_4);
    xport.appendChild(i_5);
    xport.appendChild(i_6);
    document.body.appendChild(xport); xport.submit();
  }

  function siteDetail(year, month, site_id, datacount) {
    if ( $('#tb1OprCluster').length==0){
      var region_id = $("#tb1OprRegion").val();              
      var url = "<?php echo base_url('pm/schedule/cKqAWE')?>/site/"+year+"/"+month+"/"+region_id+"/"+site_id+"/region";
    }else{
      var cluster_id = $('#tb1OprCluster').val();
      var url = "<?php echo base_url('pm/schedule/cKqAWE')?>/site/"+year+"/"+month+"/"+cluster_id+"/"+site_id+"/cluster";    
    }
    if( datacount > 0 ) { 
      $.fancybox({
        href:url ,type:'ajax',
        minWidth:1000,scrolling:false,autoSize :true,fitToView : true, closeClick : false,openEffect : 'elastic',closeEffect : 'elastic'
      });
    }
  }
  function userDetail(year, month, user_id, datacount) {
    if ( $('#opr_cluster_2').length==0){
      var region_id = $("#opr_region_2").val();              
      var url = "<?php echo base_url('pm/schedule/cKqAWE')?>/user/"+year+"/"+month+"/"+region_id+"/"+user_id+"/region";
    }else{
      var cluster_id = $('#opr_cluster_2').val();
      var url = "<?php echo base_url('pm/schedule/cKqAWE')?>/user/"+year+"/"+month+"/"+cluster_id+"/"+user_id+"/cluster";    
    }

    if( datacount > 0 ) { 
      $.fancybox({
        href:url ,type:'ajax',
        minWidth:1000,scrolling:false,autoSize :true,fitToView : true, closeClick : false,openEffect : 'elastic',closeEffect : 'elastic'
      });
    }
  }

</script>