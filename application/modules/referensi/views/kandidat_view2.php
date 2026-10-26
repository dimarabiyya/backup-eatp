<div class="box"> 
  <div class="title">
    <h5><?php echo lang('text_heading_title_tp_list') ?></h5>
  </div>
  
  <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="records">                        
  <thead>
  <tr>
		<th style="width:5%">Site ID</th>
		<th style="width:10%">Site Name</th>
		<th style="width:10%">Site ID TP</th>
		<th style="width:10%">TP Name</th>
		<th style="width:17%">Lat</th>
		<th style="width:10%">Long</th>
		<th style="width:8%">Address</th>
		<th style="width:8%">Kandidat</th>
		<th style="width:8%">Action</th>
  </tr>
  </thead>
  <tbody></tbody>
  </table>
</div>

	<?php foreach ($site_kandidat as $row) {
	?>

  <tr >
							<td><?php echo $row['site_id_text'] ?></td>
							<td><?php echo $row['site_name'] ?></td>
							<td><?php echo $row['site_id'] ?></td>
							<td><?php echo $row['tp_name'] ?></td>
							<td><?php echo $row['lattitude'] ?></td>
							<td><?php echo $row['longitude'] ?></td>
							<td><?php echo $row['address'] ?></td>
							<td><?php echo $row['kandidat'] ?></td>
    <td width="110px"></td>
  </tr>
<?php }?>

