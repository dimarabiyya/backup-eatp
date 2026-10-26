<!--
<div class="heading-buttons">
	<h3>Sites</h3>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info') ?>" class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back</a>
	</div>
</div>
<div class="separator top"></div>
-->

<div class="innerLR">
	
	<?php if (get_instance()->uri->segment(2) == 'view') { ?>
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Site</h4>
			
			<a href="<?php echo site_url('/site_info') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
		</div>
		
		<div class="widget-body">
			<div class="row-fluid">
				<div class="span6">
					<table>
						<tr id="form_site_id">
							<td width="100">Site ID:</td>
							<td><strong><?php echo $general_info['site_code'] ?></strong></td>
						</tr>
						<tr id="form_site_name">
							<td>Site Name:</td>
							<td><strong><?php echo $general_info['site_name'] ?></strong></td>
						</tr>
					</table>
				</div>
				
				<div class="span6">
					<table>
						<tr id="form_vendor_id">
							<td width="100">Vendor ID:</td>
							<td><strong><?php echo $general_info['vendor_id'] ?></strong></td>
						</tr>
						<tr id="form_site_profile">
							<td>Site Profile:</td>
							<td>
								<?php
								if ($lookup = get_instance()->core->view_row('t_mtr_lookup', array('lookup_key' => 'site_profile', 'lookup_value' => $general_info['site_profile'])) ) {
									echo '<strong>'.$lookup['lookup_desc'].'</strong>';
								}
								?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	
	<div class="widget" style="border:none">
		<div id="siteInfoTab" class="easyui-tabs" style="width:auto; height:auto;">
			<div title="General Info" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=GENERAL_INFO_VIEW'"></div>
			<div title="SITAC" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=SITAC_VIEW'"></div>
			<div title="CME" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=CME_VIEW'"></div>
			<div title="Operational" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=OPERATIONAL_VIEW'"></div>
			<div title="Equipment" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=EQUIPMENT_VIEW'"></div>
			<?php $tab_selected = get_instance()->input->get('src') == 'cnd' ? 'selected:true' : 'selected:false'; ?>
			<div title="Candidate List" data-options="<?php echo $tab_selected ?>,href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=CANDIDATE_VIEW'"></div>
			<div title="PLan Info" data-options="href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=PLAN_INFO_VIEW'"></div>
			<?php $tab_selected = get_instance()->session->flashdata('input_photo_ref_succ') ? 'selected:true' : 'selected:false'; ?>
			<div title="File / Photo Reference" data-options="<?php echo $tab_selected ?>,href:'<?php echo site_url(get_instance()->uri->uri_string()) ?>?load_content=PHOTO_REFERENCE_VIEW'"></div>
		</div>
	</div>
</div>

<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/serialize-0.2.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/timepicker/jquery.timepicker.js') ?>"></script>
<link href="<?php echo base_url('/assets/js/timepicker/jquery.timepicker.css') ?>" rel="stylesheet" />

<script src="<?php echo base_url('/assets/js/ajaxupload.js') ?>"></script>
<script src="<?php echo base_url('/file/template/smashing/theme/scripts/notyfy/jquery.notyfy.js') ?>"></script>
<link href="<?php echo base_url('/file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('/file/template/smashing/theme/scripts/notyfy/themes/default.css') ?>" rel="stylesheet" />

<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/datagrid-detailview.js') ?>"></script>

<style>
.btn { padding:4px 10px; }
.table-primary thead th { font-size:12px; }
.tabs_ { height:36px; }
.tabs-panels_ { padding:10px; }
.tabs li a.tabs-inner_ { border-radius:0px; height:35px; line-height:35px; }
.tabs-title { font-size:13px; }
input.number, input.currency, input.just-number { text-align:right; }
.widget .widget-body_{padding:20px 15px;}
.widget .widget-body form{margin:0;}
.table th, .table td { vertical-align:middle; }
select { width:auto; }
</style>

<script>
mainMenu();
</script>