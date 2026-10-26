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
		<?php
		echo $title;
		if (isset($subTitle))
        {
			echo '<span class="hidden-phone">| '.$subTitle.'</span>';
		}
		?>
	</h3>
</div>

<?php 
$group = 'false';
foreach ($this->session->userdata('user_role') as $key)
{
	if ($key['user_group_id'] == 4)
	{
		$group = 'true';
	}
}
?>

<div class="separator bottom"></div>

<div class="innerLR">
	<div class="widget widget-tabs">
		<div class="widget-head">
			<ul>
				<li class="active"><a class="glyphicons circle_ok" href="#tab-3" data-toggle="tab" onclick="getSiteSummary(3)" ><i></i>Visited Summary</a></li>
				<li><a class="glyphicons circle_info" href="#tab-1" data-toggle="tab" onclick="getSiteSummary(1)" ><i></i>Unvisited Summary</a></li>
				<li><a class="glyphicons circle_info" href="#tab-4" data-toggle="tab" onclick="getSiteSummary(4)" ><i></i>Unscheduled Summary</a></li>
				<li><a class="glyphicons circle_info" href="#declined" data-toggle="tab" onclick="revList()" ><i></i>Canceled/Declined</a></li>
			</ul>
		</div>

		<div class="widget-body">
			<div class="tab-content">
				<div class="tab-pane active" id="tab-3">
					<div id="toolBar3" style="padding: 10px 10px 0 10px; height:auto">
						
						<?php if (count($territory)) { ?>
						
						<div class="input-prepend">
							<span class="add-on">Cluster</span>
							<select name="operational_cluster_3" id="tb3OprCluster3" style="width: 130px;">
								<?php
								foreach ($territory as $tt)
								{
									echo '<optgroup label="'.$tt["region_name"].'">';
									foreach ($tt['cluster'] as $cc) 
									{
										echo '<option value="'.$cc['cluster_ops_id'].'">'.$cc["cluster_name"].'</option>';
									}
									echo '</optgroup>';
								}
								?>
							</select>
							<input type="hidden" value="cluster" id="tb3territorytype">
						</div>
						
						<?php } else { ?>
						
						<div class="input-prepend">
							<span class="add-on">Region</span>
							<select name="operational_region_3" id="tb3OprRegion3" style="width: 130px;">
								<?php
								foreach ($region as $tt)
								{
									echo '<option value="'.$tt['region_id'].'">'.$tt["region_name"].'</option>'; 
								}
								?>
							</select>
							<input type="hidden" value="region" id="tb3territorytype">
						</div>
						
						<?php } ?>
            
						<div class="input-prepend">
							<span class="add-on">Opr. Month</span>
							<select name="operational_month_3" style="width: auto" id="tb3OprMonth3">
								<?php
								for ($i = 1; $i <= 12; $i++)
								{ 
									if (date('m') ==  $i )
									{
										echo '<option value="'.$i.'" selected="selected">'.date("F", mktime(0, 0, 0, $i, 1, date('Y'))).'</option>';
									}
									else
									{
										echo '<option value="'.$i.'" >'.date("F", mktime(0, 0, 0, $i, 1, date('Y'))).'</option>';
									}
								}
								?>
							</select>
						</div>

						<div class="input-prepend">
							<span class="add-on">Opr. Year</span>
							<select name="operational_year_3" style="width: auto" id="tb3OprYear3">
								<?php
								$row = $this->db->select('extract(YEAR from min(created_on)) AS op_year_start')->get('t_trx_pm_checklist_result')->row();
								for ($y = $row->op_year_start; $y <= date('Y'); $y++)
								{
									echo '<option value="'.$y.'"'.($y == date('Y') ? ' selected="selected"' : '').'>'.$y.'</option>';
								}
								?>
							</select> &nbsp;
						</div>
						
						<button class="btn" onclick="getSiteSummary(3)" style="margin-bottom: 10px">Apply</button>
						<div class="input-append pull-right">
							<input class="" id="userIdSiteId_3" type="text" placeholder="PIC Name / Site ID" onkeyup="if(event.keyCode == 13){ getSiteSummary(3);}"/> 
							<button class="btn" onclick="getSiteSummary(3)" >Search</button>
							<button class="btn" onclick="export_bystatus(3)">Export</button>
						</div>
						<!-- <button type="button" class="btn btn-default btn-small glyphicons search btn-info" onclick="getSiteSummary(3)" style="margin-top: -10px"><i></i> Search</button> -->
					</div>

					<table id="dg3" class="easyui-datagrid" width="100%" fitColumns="true" singleSelect="true" striped="true" rownumbers="true" url="<?php echo base_url('pm/summary/getSiteSummary'); ?>" method="get" pagination="true" toolbar="#toolBar3">
						<thead>
							<tr>
								<!-- <th data-options="field:'region_name'">Region</th> -->
								<th data-options="field:'site_code'">Site ID</th>
								<th data-options="field:'site_name', width:125">Site Name</th>
								<th data-options="field:'user_name'">PIC Name</th>
								<th data-options="field:'priority'">Priority</th>
								<th data-options="field:'visit_type'">Visit Type</th>
								<th data-options="field:'visit_plan'">Visit Plan</th>
								<th data-options="field:'pm_status'">Status</th>
								<th data-options="field:'visit_date'">Actual Visit Date</th>
								<th data-options="field:'action'">Action</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div> <!-- End of tab pane-->

				<div class="tab-pane" id="tab-1">
					<div id="toolBar1" style="padding: 10px 10px 0 10px; height:auto">
						
						<?php if (count($territory)) { ?>
						
						<div class="input-prepend">
							<span class="add-on">Cluster</span>
							<select name="operational_cluster_1" id="tb1OprCluster1" style="width: 130px;">
								<?php
								foreach ($territory as $tt)
								{
									echo '<optgroup label="'.$tt["region_name"].'">';
									foreach ($tt['cluster'] as $cc) 
									{
										echo '<option value="'.$cc['cluster_ops_id'].'">'.$cc["cluster_name"].'</option>';
									}
									echo '</optgroup>';
								}
								?>
							</select>
							<input type="hidden" value="cluster" id="tb1territorytype">
						</div>
						
						<?php } else { ?>
						
						<div class="input-prepend">
							<span class="add-on">Region</span>
							<select name="operational_region_1" id="tb1OprRegion1" style="width: 130px;">
								<?php
								foreach ($region as $tt)
								{
									echo '<option value="'.$tt['region_id'].'">'.$tt["region_name"].'</option>'; 
								}
								?>
							</select>
							<input type="hidden" value="region" id="tb1territorytype">
						</div>
            
						<?php } ?>

						<div class="input-prepend">
							<span class="add-on">Opr. Month</span>
							<select name="operational_month_1" style="width: auto" id="tb1OprMonth1">
								<?php
								for ($i = 1; $i <= 12; $i++)
								{
									if (date('m') ==  $i )
									{
										echo '<option value="'.$i.'" selected="selected">'.date("F", mktime(0,0,0,$i,1,date('Y'))).'</option>';
									}
									else
									{
										echo '<option value="'.$i.'" >'.date("F", mktime(0,0,0,$i,1,date('Y'))).'</option>';
									}
								}
								?>
							</select>
						</div>

						<div class="input-prepend">
							<span class="add-on">Opr. Year</span>
							<select name="operational_year_1" style="width: auto" id="tb1OprYear1">
								<?php
								$row = $this->db->select('extract(YEAR from min(created_on)) AS op_year_start')->get('t_trx_pm_checklist_result')->row();
								for ($y = $row->op_year_start; $y <= date('Y'); $y++)
								{
									echo '<option value="'.$y.'"'.($y == date('Y') ? ' selected="selected"' : '').'>'.$y.'</option>';
								}
								?>
							</select> &nbsp;
						</div>
						
						<button class="btn" onclick="getSiteSummary(1)" style="margin-bottom: 10px">Apply</button>
						<div class="input-append pull-right">
							<input class="" id="userIdSiteId_1" type="text" placeholder="PIC Name / Site ID" onkeyup="if(event.keyCode == 13){ getSiteSummary(1);}"/> 
							<button class="btn" onclick="getSiteSummary(1)" >Search</button>
							<button class="btn" onclick="export_bystatus(1)">Export</button>
						</div>
						<!-- <button type="button" class="btn btn-default btn-small glyphicons search btn-info" onclick="getSiteSummary(1)" style="margin-top: -10px"><i></i> Search</button> -->
					</div>

					<table id="dg1" class="easyui-datagrid" width="100%" fitColumns="true" singleSelect="true" striped="true" rownumbers="true" url="<?php echo base_url('pm/summary/getSiteSummary'); ?>" method="get" pagination="true" toolbar="#toolBar1">
						<thead>
							<tr>
								<!-- <th data-options="field:'region_name'">Region</th> -->
								<th data-options="field:'site_code'">Site ID</th>
								<th data-options="field:'site_name', width:125">Site Name</th>
								<th data-options="field:'user_name'">PIC Name</th>
								<th data-options="field:'priority'">Priority</th>
								<th data-options="field:'visit_type'">Visit Type</th>
								<th data-options="field:'visit_plan'">Visit Plan</th>
								<!-- <th data-options="field:'visit_date'">Actual Visit Date</th> -->
								<th data-options="field:'pm_status'">Status</th>
								<?php if ($group) { ?>
								<th data-options="field:'security_code_checkin'">Checkin Code</th>
								<th data-options="field:'security_code_checkout'">Checkout Code</th>
								<?php } ?>
								<th data-options="field:'action'">Action</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div> <!-- End of tab pane-->

				<div class="tab-pane" id="tab-4">
					<div id="toolBar4" style="padding: 10px 10px 0 10px; height:auto">
						
						<?php if (count($territory)) { ?>
						
						<div class="input-prepend">
							<span class="add-on">Cluster</span>
							<select name="operational_cluster_4" id="tb4OprCluster4" style="width: 130px;">
								<?php
								foreach ($territory as $tt)
								{
									echo '<optgroup label="'.$tt["region_name"].'">';
									foreach ($tt['cluster'] as $cc) 
									{
										echo '<option value="'.$cc['cluster_ops_id'].'">'.$cc["cluster_name"].'</option>';
									}
									echo '</optgroup>';
								}
								?>
							</select>
							<input type="hidden" value="cluster" id="tb4territorytype">
						</div>
						
						<?php } else { ?>
						
						<div class="input-prepend">
							<span class="add-on">Region</span>
							<select name="operational_region_4" id="tb4OprRegion4" style="width: 130px;">
								<?php
								foreach ($region as $tt)
								{
									echo '<option value="'.$tt['region_id'].'">'.$tt["region_name"].'</option>'; 
								}
								?>
							</select>
							<input type="hidden" value="region" id="tb4territorytype">
						</div>
						
						<?php } ?>
            
						<div class="input-prepend">
							<span class="add-on">Opr. Month</span>
							<select name="operational_month_4" style="width: auto" id="tb4OprMonth4">
								<?php
								for ($i = 1; $i <= 12; $i++)
								{ 
									if (date('m') ==  $i )
									{
										echo '<option value="'.$i.'" selected="selected">'.date("F", mktime(0,0,0,$i,1,date('Y'))).'</option>';
									}
									else
									{
										echo '<option value="'.$i.'" >'.date("F", mktime(0,0,0,$i,1,date('Y'))).'</option>';
									}
								}
								?>
							</select>
						</div>

						<div class="input-prepend">
							<span class="add-on">Opr. Year</span>
							<select name="operational_year_4" style="width: auto" id="tb4OprYear4">
								<?php
								$row = $this->db->select('extract(YEAR from min(created_on)) AS op_year_start')->get('t_trx_pm_checklist_result')->row();
								for ($y = $row->op_year_start; $y <= date('Y'); $y++)
								{
									echo '<option value="'.$y.'"'.($y == date('Y') ? ' selected="selected"' : '').'>'.$y.'</option>';
								}
								?>
							</select> &nbsp;
						</div>
						
						<button class="btn" onclick="getSiteSummary(4)" style="margin-bottom: 10px">Apply</button>
						<div class="input-append pull-right">
							<input class="" id="userIdSiteId_4" type="text" placeholder="PIC / Site ID" onkeyup="if(event.keyCode == 13){ getSiteSummary(4);}"/> 
							<button class="btn" onclick="getSiteSummary(4)">Search</button>
							<button class="btn" onclick="export_bystatus(4)">Export</button>
						</div>
						<!-- <button type="button" class="btn btn-default btn-small glyphicons search btn-info" onclick="getSiteSummary(4)" style="margin-top: -10px"><i></i> Search</button> -->
					</div>

					<table id="dg4" class="easyui-datagrid" width="100%" fitColumns="true" singleSelect="true" striped="true" rownumbers="true" url="<?php echo base_url('pm/summary/getSiteSummary'); ?>" method="get" pagination="true" toolbar="#toolBar4">
						<thead>
							<tr>
								<!-- <th data-options="field:'region_name'">Region</th> -->
								<th data-options="field:'site_code'">Site ID</th>
								<th data-options="field:'site_name', width:125">Site Name</th>
								<th data-options="field:'user_name'">PIC Name</th>
								<th data-options="field:'priority'">Priority</th>
								<th data-options="field:'visit_type'">Visit Type</th>
								<th data-options="field:'visit_plan'">Visit Plan</th>
								<th data-options="field:'visit_date'">Actual Visit Date</th>
								<th data-options="field:'pm_status'">Status</th>
								<?php if($group) { ?>
								  <th data-options="field:'security_code_checkin'">Checkin Code</th>
								  <th data-options="field:'security_code_checkout'">Checkout Code</th>
								<?php } ?>
								<th data-options="field:'action'">Action</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div> <!-- End of tab pane-->

				<div class="tab-pane" id="declined">
					<div id="toolBar2" style="padding: 10px 10px 0 10px; height:auto">
						
						<?php if (count($territory)) { ?>
						
						<div class="input-prepend">
							<span class="add-on">Cluster</span>
							<select name="operational_cluster_2" id="tb2OprCluster2" style="width: 130px;">
								<?php
								foreach ($territory as $tt)
								{
									echo '<optgroup label="'.$tt["region_name"].'">';
									foreach ($tt['cluster'] as $cc) 
									{
										echo '<option value="'.$cc['cluster_ops_id'].'">'.$cc["cluster_name"].'</option>';
									}
									echo '</optgroup>';
								}
								?>
							</select>
							<input type="hidden" value="cluster" id="tb2territorytype">
						</div>
						
						<?php } else { ?>
						
						<div class="input-prepend">
							<span class="add-on">Region</span>
							<select name="operational_region_2" id="tb2OprRegion2" style="width: 130px;">
								<?php
								foreach ($region as $tt)
								{
									echo '<option value="'.$tt['region_id'].'">'.$tt["region_name"].'</option>'; 
								}
								?>
							</select>
							<input type="hidden" value="region" id="tb2territorytype">
						</div>
						
						<?php } ?>
          
						<div class="input-prepend">
							<span class="add-on">Opr. Year</span>
							<select name="operational_year" id="tb2OprYear" style="width: 100px;">
								<?php
								$row = $this->db->select('extract(YEAR from min(created_on)) AS op_year_start')->get('t_trx_pm_checklist_result')->row();
								for ($y = $row->op_year_start; $y <= date('Y'); $y++)
								{
									echo '<option value="'.$y.'"'.($y == date('Y') ? ' selected="selected"' : '').'>'.$y.'</option>';
								}
								?>
							</select>
						</div>

						<div class="input-prepend">
							<span class="add-on">Opr. Month</span>
							<select name="operational_month" id="tb2OprMonth" style="width: 100px;">
								<?php
								for ($i = 1; $i <= 12; $i++)
								{
									if (date('m') ==  $i )
									{
										echo '<option value="'.$i.'" selected="selected">'.date("F", mktime(0,0,0,$i,1,date('Y'))).'</option>';
									}
									else
									{
										echo '<option value="'.$i.'" >'.date("F", mktime(0,0,0,$i,1,date('Y'))).'</option>';
									}
								}
								?>
							</select>
						</div>
						
						<button class="btn" onclick="revList()" style="margin-bottom: 10px">Apply</button>

						<div class="input-append pull-right">
							<input class="" id="userIdSiteId_2" type="text" placeholder="Site ID" onkeyup="if(event.keyCode == 13){ revList();}"/> 
							<button class="btn" onclick="revList()" >Search</button>
							<button class="btn" onclick="export_by_revision(null)" >Export</button>
						</div>
						<!-- <button type="button" class="btn btn-default btn-small glyphicons search btn-info" onclick="loadSiteSchedule()" style="margin-top: -10px"><i></i> Search</button> -->
					</div>
        
					<table id="dg2" width="100%" class="easyui-datagrid" fitColumns="true" singleSelect="true" striped="true" rownumbers="true" url="<?php echo base_url() ?>pm/summary/canceledList" method="get" toolbar="#toolBar2" pagination="true">
						<thead>
							<tr>
								<!-- <th data-options="field:'region_name'">Region</th> -->
								<th data-options="field:'site_code'">Site ID</th>
								<th data-options="field:'site_name'">Site Name</th>
								<th data-options="field:'vendor_id'">Vendor ID</th>
								<!-- <th data-options="field:'user_id'">PIC ID</th> -->
								<th data-options="field:'user_name'">PIC Name</th>
								<th data-options="field:'visit_type'">Visit Type</th>
								<th data-options="field:'visit_plan'">Visit Plan</th>
								<th data-options="field:'visit_status'">Status</th>
								<th data-options="field:'reason_group'">Reason</th>
								<th data-options="field:'remarks'">Description</th>
								<th data-options="field:'created_on'">Creation Date</th>
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
$(document).ready(function() {
	getSiteSummary(3);

	//   getSiteSummary(1);
	//   getSiteSummary(4);

    $("#menu_164").parent().addClass('active').find('.collapse').css('height','auto');
});

function revList() {
	$('#dg2').datagrid('load', {
		opr_year   : $('#tb2OprYear').val(),
		opr_month  : $('#tb2OprMonth').val(),
		opr_cluster: $('#tb2OprCluster').val(),
		opr_region  : $('#tb2OprRegion2').val(),
		territorytype  : $('#tb2territorytype').val(),
		otherParam : $('#userIdSiteId_2').val(),
	});
}

function getSiteSummary(ii) {
	// console.log('getSiteSummary('+ii+')');
	$('#dg'+ii).datagrid('load', {
		sch_cond    : ii,
		opr_year    : $('#tb'+ii+'OprYear'+ii).val(),
		opr_month   : $('#tb'+ii+'OprMonth'+ii).val(),
		opr_cluster : $('#tb'+ii+'OprCluster'+ii).val(),
		opr_region  : $('#tb'+ii+'OprRegion'+ii).val(),
		territorytype  : $('#tb'+ii+'territorytype').val(),
		keyword     : $('#userIdSiteId_'+ii).val(),
	});
}

function export_bystatus (ii) {
	var opr_year    = $('#tb'+ii+'OprYear'+ii).val();
	var opr_month   = $('#tb'+ii+'OprMonth'+ii).val();
	var opr_cluster = $('#tb'+ii+'OprCluster'+ii).val();
	var opr_region  = $('#tb'+ii+'OprRegion'+ii).val();
	var territorytype  = $('#tb'+ii+'territorytype').val();
	var keyword     = $('#userIdSiteId_'+ii).val();

	var xport = document.createElement("form");
	
	inp_1 = document.createElement("input"); inp_1.type="hidden";
	inp_2 = document.createElement("input"); inp_2.type="hidden";
	inp_3 = document.createElement("input"); inp_3.type="hidden";
	inp_4 = document.createElement("input"); inp_4.type="hidden";
	inp_5 = document.createElement("input"); inp_5.type="hidden";
	inp_6 = document.createElement("input"); inp_6.type="hidden";
	
	xport.action = "<?php echo base_url('export/pmsummary'); ?>/excel/"+ii+"/";
	xport.method = "post";
	
	inp_1.name = "opr_year"; inp_1.setAttribute("value", opr_year);
	inp_2.name = "opr_month"; inp_2.setAttribute("value", opr_month);
	inp_3.name = "opr_cluster"; inp_3.setAttribute("value", opr_cluster);
	inp_4.name = "keyword"; inp_4.setAttribute("value", keyword);
	inp_5.name = "opr_region"; inp_5.setAttribute("value", opr_region);
	inp_6.name = "territorytype"; inp_6.setAttribute("value", territorytype);

	xport.appendChild(inp_1);
	xport.appendChild(inp_2);
	xport.appendChild(inp_3);
	xport.appendChild(inp_4);
	xport.appendChild(inp_5);
	xport.appendChild(inp_6);
	// xport.appendChild(inp_5);
	document.body.appendChild(xport);
	xport.submit();
}

function export_by_revision() {
	var xport = document.createElement("form");
	
	i_1   = document.createElement("input"); i_1.type="hidden";
	i_2   = document.createElement("input"); i_2.type="hidden";
	i_3   = document.createElement("input"); i_3.type="hidden";
	i_4   = document.createElement("input"); i_4.type="hidden";
	i_5   = document.createElement("input"); i_5.type="hidden";
	i_6   = document.createElement("input"); i_6.type="hidden";
	i_7   = document.createElement("input"); i_6.type="hidden";
	i_8   = document.createElement("input"); i_6.type="hidden";

	xport.action = "<?php echo base_url('export/pmschedule'); ?>/excel/";
	xport.method = "post";
	
	i_1.name  = "opr_year"; i_1.setAttribute('value', $('#tb2OprYear').val());
	i_2.name  = "opr_cluster"; i_2.setAttribute('value', $('#tb2OprCluster').val());
	i_3.name  = "keyword"; i_3.setAttribute('value', $('#userIdSiteId_2').val());
	i_4.name  = "cluster_name"; i_4.setAttribute('value', $('#tb2OprCluster :selected').html());
	i_5.name  = "tabletype"; i_5.setAttribute('value', 'revision');
	i_6.name  = "opr_month"; i_6.setAttribute('value', $('#tb2OprMonth').val());
	i_7.name  = "opr_region"; i_7.setAttribute('value', $('#tb2OprRegion2').val());
	i_8.name  = "territorytype"; i_8.setAttribute('value', $('#tb2territorytype').val());
	
	xport.appendChild(i_1);
	xport.appendChild(i_2);
	xport.appendChild(i_3);
	xport.appendChild(i_4);
	xport.appendChild(i_5);
	xport.appendChild(i_6);
	xport.appendChild(i_7);
	xport.appendChild(i_8);
	
	document.body.appendChild(xport); xport.submit();
}

function detailVisit(pm_id) {
	$.fancybox({
		href:"<?php echo base_url('pm/summary/detailSummary')?>/"+pm_id,type:'ajax',
		autoCenter:false,
		minWidth:1000,autoSize :true,fitToView : true, 
		autoCenter:false
	});

	// $.fancybox({
	//   href:"<?php echo base_url('pm/summary/detailSummary')?>/"+pm_id ,type:'ajax',
	//   minWidth:1000,scrolling:false,autoSize :true,fitToView : true, closeClick : false,openEffect : 'elastic',closeEffect : 'elastic'
	// });
}

function popupimage(image) {
	$(this).overlay().load();
}
</script>