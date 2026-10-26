<div class="innerLR">
	<div class="widget">
		<div class="widget-head">
			<h4 class="heading glyphicons edit"><i></i> Detail Checklist</h4>
		</div>
		<div class="widget-body">
			<table class="table table-condensed">
				<tbody>
					<tr>
						<td><h5>Group Checklist</h5></td>
						<td>: <?php echo $view_data[0]['name']?></td>
					</tr>
					<tr>
						<td><h5>Checklist</h5></td>
						<td>: <?php echo $view_data[0]['descr']?></td>
					</tr>
					<tr>
						<td><h5>Option</h5></td>
						<td><?php foreach ($view_data as $key => $data): ?><li><?php echo $data['option']; ?></li><br /><?php endforeach; ?></td>
					</tr>
				</tbody>
			</table>
			
		</div>
	</div>
</div>