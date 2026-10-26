<div class="form-horizontal">

	<div class="widget">
		<div class="widget-head">
			<h4 class="heading">File / Photo Reference Data</h4>
		</div>
		
		<div class="widget-body">
		
			<div class="overflow:auto">
				<table id="tblPhotoReference" class="table table-bordered table-striped table-condensed" style="width:auto">
					<thead>
						<tr>
							<th>Type:</th>
							<th>File:</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if ($this->uri->segment(2) == 'view' && count($photo_reference) > 0) {
							$photo_counter = 1;
							foreach ($photo_reference as $ref) {
							
								if (is_file($ref['file_path']) && file_exists($ref['file_path'])) {
						?>					
						<tr id="photo-row<?php echo $photo_counter ?>">
							<td><?php 
								$fileType = array(0 => '');
								$dataFileType = $this->core->get_lookup('site_info_type_file');
								foreach ($dataFileType as $type) {
									$fileType[$type['id']] = $type['desc'];
								}
								
								?>
								<span><?php echo $fileType[$ref['photo_type']] ?></span>
							</td>
							<td nowrap>
								<div id="fileRef<?php echo $photo_counter ?>" style="text-align:center; width:100px;">
									<?php if ($ref['thumb'] != '') { ?>
									<a id="thumb-<?php echo $photo_counter ?>" class="real-image" href="<?php echo $ref['file'] ?>"><img src="<?php echo $ref['thumb'] ?>" class="img-polaroid" /></a>
									<?php } else {?>
									<a id="thumb-<?php echo $photo_counter ?>" href="<?php echo $ref['file'] ?>" class="btn btn-mini btn-success"><strong>Download</strong></a>
									<?php } ?>
								</div>
								<input type="hidden" name="file[<?php echo $photo_counter ?>]" value="<?php echo $ref['photo_name'] ?>" />
							</td>
						</tr>
						
						<?php 
								}
								
								$photo_counter++;
							}
						}
						?>
					</tbody>
				</table>
			</div>
		
		</div>
	</div>

</div>

<script>
$('.real-image').fancybox({
	type: 'image',
	transitionIn : 'elastic',
	transitionOut: 'elastic',
	closeBtn: true
});
</script>