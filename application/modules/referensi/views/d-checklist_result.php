<div class="box"> 
  <div class="title">
   
  </div>
  <table class="table table-bordered table-primary table-vertical-center "> <thead>
			<tr>
										<th>Result</th>
										<th>Photo Before</th>
										<th>Photo After</th>
										<th>Descr</th>
            </tr></thead><tbody id="document-data">
	<?php foreach ($view_data as $key => $data): 
		$filesample = base_url().'file/upload/sample.jpg';
		$filenotfound = base_url().'file/upload/not-found.png';
		$fileafter = base_url().'file/upload/'.$data['photo_after'];
		$filebefore= base_url().'file/upload/'.$data['photo_before'];
		$patchafter = dirname(FCPATH).'/sims2/file/upload/'.$data['photo_after'] ;
		$patchbefore = dirname(FCPATH).'/sims2/file/upload/'.$data['photo_before'] ;
	?>
	<tr>
		<td><?php echo $data['pm_result']; ?></td>
		<td>
			
				<a href="<?php echo $filebefore;?>" target='_blank' class="thumb view" data-gallery="gallery">
							<?php 
							
							if(empty($data['photo_before'])){ 
								echo"<img width='100px' src='".$filenotfound."' alt='Album'/>"; 
							}else{ 
								if (file_exists($patchbefore)) {
									echo "<img width='100px' src='".$filebefore."' alt='Album'/>";
								} else {
									echo "<img width='100px' src='".$filenotfound."' alt='Album'/>";
								} 
							} ?> 
				</a>
			</td>
		<td>
				<a href="<?php echo $fileafter;?>" target='_blank' class="thumb view" data-gallery="gallery">
							<?php 
							
							if(empty($data['photo_after'])){ 
								echo"<img width='100px' src='".$filenotfound."' alt='Album'/>"; 
							}else{ 
								if (file_exists($patchafter)) {
									echo "<img width='100px' src='".$fileafter."' alt='Album'/>";
								} else {
									echo "<img width='100px' src='".$filenotfound."' alt='Album'/>";
								} 
							} ?> 
				</a>
			</td>
		<td><?php echo $data['descr']; ?></td>
	</tr>
	 <?php endforeach; ?></tbody>
  </table>
  </div>
