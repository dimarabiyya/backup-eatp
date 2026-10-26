<!-- Uploadify -->
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/uploadify/jquery.uploadify.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/uploadify/jquery.uploadify.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url();?>assets/js/uploadify/uploadify.css" type="text/css" media="screen" />



<form class="form-horizontal"  id="add-form-app" method="post" autocomplete="off" >
		<div class="widget">
		<div class="widget-head"><h4 class="heading">Import Data</h4></div>

			<div class="widget-body" style="padding:0px">
                    
				<div class="row-fluid">
						<div class="control-group" id="divUpload">
							<label class="control-label" for="upload_file">Select file to upload</label>
							<div class="controls">
								<input id="file_upload" name="file_upload" class="file_upload" />
							</div>
						</div>
						<div class="control-group" id="uploadResult" style="display:none">
							<label class="control-label" for="generate">Upload state</label>
							<div class="controls">
								<div class="result-state" id="dState">
									
								</div>
							</div>
						</div>


                </div>
                    <div class="form-actions" style="padding: 9px 11px 11px;">
					<button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove"><i></i>Close</button>
						<script>
							$(".btn-close").click(function(){$.fancybox.close();});
						</script>
				</div>         				

			</div>

		</div>
</form>
	<script type="text/javascript">
	// Convert divs to queue widgets when the DOM is ready
	$(function() {
        <?php $timestamp = time();?>
        // Uploadify
        $('#file_upload').uploadify({
                    'formData'     : {
                        'timestamp' : '<?php echo $timestamp;?>',
                        'token'     : '<?php echo md5("unique_salt" . $timestamp);?>'
                    },
                'buttonClass' : 'small button',
                'uploader'    : '<?php echo base_url();?>import/doUpload/<?php echo $module_name;?>',
                'swf'         : 'assets/js/uploadify/uploadify.swf',
                'cancelImg' : 'assets/js/uploadify/cancel.png',
                'multi'     : true,
                'auto'      : true,
              'onQueueComplete' : function(queueData) {
                    if (queueData.uploadsSuccessful==0){
                        alert('Upload failed,please make sure the file is not corrupt.');
                    }
                },
			    'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
			            if(bytesUploaded==0){
					        $("#uploadResult").slideDown();
					        $(".result-state").prepend("<div class='loading'><img src='<?php echo base_url();?>/assets/images/loading3.gif'/> processing " + file.name + ".</div>");
			            }
			        },
				'onUploadSuccess' : function(file, data, response) {
						$(".loading").remove();
				        $("#uploadResult").slideDown();
				        $(".result-state").append(data);
				    }

              });


	});
	</script>
