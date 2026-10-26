<div class="innerLR">
	
	<?php echo form_open('', 'id="frmTenant" class="form-horizontal"') ?>
	
	<div class="widget">	
		<div class="widget-head heading-outer">
			<h4 class="heading"><i></i> <?php echo $sub_title ?></h4>
			
			<a href="<?php echo site_url('/referensi/tenant') ?>" class="btn btn-primary btn-mini btn-heading btn-icon glyphicons unshare pull-right"><i></i> Back</a>
		</div>
		
		<div class="widget-body">
		
			<div class="form-horizontal">
			
				<div class="control-group" id="form_site_id">
					<label class="control-label" for="name">Name</label>
					<div class="controls">
						<?php echo form_input('name', $tenant->tenant_name, 'id="name" class="input-xlarge"') ?>
					</div>
				</div>
			
			</div>
			
			<div class="form-actions">
				<button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
				<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href='<?php echo site_url('/referensi/tenant') ?>'"><i></i>Cancel</button>
			</div>
			
		</div>

	</div>
	
	<?php echo form_close() ?>
	
</div>

<script src="<?php echo base_url('/assets/js/hehehe.js') ?>"></script>

<script>
var form = 'frmTenant';
var redirectURL = '<?php echo site_url('referensi/tenant') ?>';

document.getElementById(form).onsubmit = function() {

    $.ajax({
        url: this.action,
        type: 'post',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(json) {console.log(json);
            $('div.state-error').remove();

            if (json.error) {
                var frmElements = document.getElementById(form).elements;

                for (var i = 0 ; i < frmElements.length; i++) {
                    for (var el in json.message) {
                        if (el == frmElements[i].name) {
                            $('#' + el).after('<div class="state-error text-error">' + json.message[el] + '</div>');
                        }
                    }
                }
            } else {
				coolNotification(json.message, 'success', function() {
					window.location.href = redirectURL;
				});
            }
        }
    });

    return false;
};
</script>