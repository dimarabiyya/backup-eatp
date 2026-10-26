<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui-1.8.16/jquery-ui-1.8.16.custom.min.js"></script>

<script src="<?php echo base_url('/assets/js/easyui/jquery.easyui.min.js') ?>"></script>
<link href="<?php echo base_url('/assets/js/easyui/themes/default/easyui.css') ?>" rel="stylesheet" />
<div class="innerLR">
<form class="form-horizontal" style="margin-bottom: 0;" method="POST" id="add-form" >
	
	<div class="widget">
		<div class="widget-head"><h4 class="heading"><?php echo $judul?></h4></div>
		<div class="widget-body">
			<div class="row-fluid">
				<div>
                    <input type='hidden' name='id' value='<?php echo $id?>' >
					
					
					
					<div class="control-group" id="form_template_namel">
						<label class="control-label" for="template_name">Template Name</label>
						<div class="controls"><input class="span12" id="template_name" maxlength="10" name="template_name" type="text" value="<?php echo $template_name?>" /></div>
					</div>
					
					<div class="control-group" id="form_action_desc">
						<label class="control-label" for="action_desc">Template Descr</label>
						<div class="controls"><input class="span12" id="action_desc" maxlength="255" name="action_desc" type="text" value="<?php echo $action_desc?>" /></div>
					</div>
					
					<div class="control-group" id="form_subject">
						<label class="control-label" for="subject">Subject</label>
						<div class="controls"><input class="span12" id="subject" maxlength="50" name="subject" type="text" value="<?php echo $subject?>" /></div>
					</div>
					
					<div class="control-group" id="form_parameter">
						<label class="control-label" for="parameter">Parameter</label>
						<div class="controls"><input class="span12" id="parameter" maxlength="50" name="parameter" type="text" value="<?php echo $parameter?>" /></div>
					</div>
					
					<div class="control-group" id="form_recipient_division">
						<label class="control-label" for="recipient_division">Recipient Division</label>
						<div class="controls"><input class="span12" id="recipient_division" maxlength="50" name="recipient_division" type="text" value="" /></div>
					</div>
					
					<div class="control-group" id="form_recipient_user">
						<label class="control-label" for="recipient_user">Recipient User</label>
						<div class="controls"><input class="span12" id="recipient_user" maxlength="50" name="recipient_user" type="text" value="" /></div>
					</div>
                        <div class="form-actions" style="display:none">
						<input TYPE="button" class="btn btn-icon btn-primary glyphicons unshare" VALUE="Back" onClick="history.go(-1);">
						<input type="submit" class="btn btn-icon btn-primary glyphicons circle_ok" value="Save" >
					</div>                
				</div>
			</div>
			
		</div>
		
	</div>
	
</form>
	</div>