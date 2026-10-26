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
      </ul>
    </div>

    <div class="widget-body">
	
		<div id="toolBar1" style="padding: 10px 10px 0 10px; height:auto">
			<div class="input-append pull-right">
			  <input class="" id="userIdSiteId_1" type="text" placeholder="Site ID" /> 
			  <button class="btn" onclick="loadSiteSchedule()" >Search</button>
			</div>            
		</div>
		  
		<table id="dg1" width="100%" class="easyui-datagrid" fitColumns="true" singleSelect="true" striped="true" rownumbers="true" url="<?php echo base_url() ?>pm/schedule/popUpSchedule" method="get" toolbar="#toolBar1" pagination="true">
			<thead>
			  <tr>
				<!-- <th data-options="field:'region_name'">Region</th> -->
				<th data-options="field:'site_code'">Site ID</th>
				<th data-options="field:'site_name'">Site Name</th>
				<th data-options="field:'vendor_id'">Vendor</th>
				<th data-options="field:'site_profile'">Site Profile</th>				
				<th data-options="field:'btnAction'">Action</th>
			  </tr>
			</thead>

			<tbody> <!-- Put something in here --> </tbody>
		</table>
	
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
      opr_year: $('#tb1OprYear').val(),
      opr_cluster: $('#tb1OprCluster').val(),
      otherParam : $('#userIdSiteId_1').val(),
    });
  }

  function userSearch() {
    // console.log('ksjlfkajdf');
    $('#dg2').datagrid('load',{
      opr_year   : $('#opr_year_2').val(),
      opr_cluster: $('#opr_cluster_2').val(),
      opr_month  : $('#opr_month_2').val(),
      otherParam : $('#userIdSiteId_2').val(),
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

      nForm.action = "<?php echo base_url('pm/schedule/detail'); ?>";
      nForm.method = "post";
      
      input_1.name = "month_opr";      input_1.setAttribute("value", month);
      input_2.name = "year_opr";       input_2.setAttribute("value", $('#tb1OprYear').val());
      input_3.name = "site_id";        input_3.setAttribute("value", site_id);
      input_4.name = "cluster_ops_id"; input_4.setAttribute("value", $('#tb1OprCluster').val());

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

      nForm.action = "<?php echo base_url('pm/schedule/detail/user'); ?>";
      nForm.method = "post";
      
      input_1.name = "month_opr";      input_1.setAttribute("value", month);
      input_2.name = "year_opr";       input_2.setAttribute("value", $('#opr_year_2').val());
      input_3.name = "user_id";        input_3.setAttribute("value", user_id);
      input_4.name = "cluster_ops_id"; input_4.setAttribute("value", $('#opr_cluster_2').val());

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

</script>