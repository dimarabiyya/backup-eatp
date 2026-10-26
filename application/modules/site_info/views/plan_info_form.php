<link href="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/themes/gray/easyui.css') ?>" rel="stylesheet" />
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/jquery.easyui.min.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-easyui-1.3.3/datagrid-detailview.js') ?>"></script>

<style>
.widget .widget-body_ { padding:20px 15px; }
.tabs_ { height:36px; }
.tabs-panels_ { padding:10px; }
.tabs li a.tabs-inner_ { border-radius:0px; height:35px; line-height:35px; }
.tabs-title_ { font-size:13px; }
.table th, .table td { vertical-align:middle; }
</style>

<!--
<div class="heading-buttons">
	<?php if ($this->uri->segment(3) == 'add') { ?>
	<h3>Add <span>| Plan Info</span></h3>
	<?php } elseif ($this->uri->segment(3) == 'edit') { ?>
	<h3>Edit <span>| Plan Info</span></h3>
	<?php } ?>
	
	<div class="buttons pull-right">
		<a href="<?php echo site_url('/site_info/plan_info') ?>" class="btn btn-primary btn-icon glyphicons unshare"><i></i> Back</a>
	</div>
</div>
<div class="separator top"></div>
-->

<div class="innerLR">
	
	<?php echo @form_hidden('siteId', $plan['nom_site_id']) ?>
	
	<?php if ($this->uri->segment(3) == 'edit') { ?>
	<div class="widget">
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> Site</h4>
			
			<a href="<?php echo site_url('/site_info/plan_info') ?>" class="btn btn-primary btn-mini btn-icon btn-heading glyphicons unshare pull-right"><i></i> Back</a>
		</div>
		
		<div class="widget-body">
			
			<div class="form-horizontal">
				<div class="control-group" id="form_site_id">
					<label class="control-label" for="siteName">Site ID:</label>
					<div class="controls">
						<span class="input-label"><?php echo $plan['nom_site_code'] ?></span>						
					</div>
				</div>
				
				<div class="control-group" id="form_site_name">
					<label class="control-label" for="siteName">Site Name:</label>
					<div class="controls">
						<span class="input-label"><?php echo $plan['nom_site_name'] ?></span>
					</div>
				</div>
			</div>
		
		</div>
	</div>
	<?php } ?>
	
	<div class="widget" style="border:none; position:relative;">
		
		<div id="planTab" class="easyui-tabs" style="width:auto; height:auto;">
			<div title="General Info" data-options="href:'<?php echo site_url($this->uri->uri_string()) ?>?load_content=SITE'"></div>
			<div title="RF Antenna" data-options="href:'<?php echo site_url($this->uri->uri_string()) ?>?load_content=RF'"></div>
			<div title="Transmission" data-options="href:'<?php echo site_url($this->uri->uri_string()) ?>?load_content=TRANSMISSION'"></div>
		</div>
		
		<?php if ($this->uri->segment(3) == 'add') { ?>
		<div class="buttons" style="position:absolute; top:0px; right:7px;">
			<a href="<?php echo site_url('/site_info/plan_info') ?>" class="btn btn-primary btn-mini pull-right btn-heading btn-icon glyphicons unshare"><i></i> Back</a>
		</div>
		<?php } ?>
		
	</div>

</div>

<script src="<?php echo base_url('/assets/js/number_format.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/overlay.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>
<script src="<?php echo base_url('/file/template/smashing/theme/scripts/notyfy/jquery.notyfy.js') ?>"></script>
<link href="<?php echo base_url('/file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('/file/template/smashing/theme/scripts/notyfy/themes/default.css') ?>" rel="stylesheet" />

<script>
var planURL = '<?php echo site_url('/site_info/plan_info') ?>';

mainMenu();
</script>