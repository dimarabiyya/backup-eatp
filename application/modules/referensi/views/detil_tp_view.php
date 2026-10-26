<div class="box"> 
  <div class="title">
    <h5><?php echo lang('text_heading_title_tp_kandidat_list') ?></h5>
  </div>
  <table>                        
	<tr><td>ID</td><td>:</td><td><?php echo $tp_detil[0]['no'];?></td></tr>
	<tr><td>Site ID</td><td>:</td><td><?php echo $tp_detil[0]['site_id'];?></td></tr>
	<tr><td>Site Name</td><td>:</td><td><?php echo $tp_detil[0]['site_name'];?></td></tr>
	<tr><td>TP Name</td><td>:</td><td><?php echo $tp_detil[0]['tp_name'];?></td></tr>
	<tr><td>Province</td><td>:</td><td><?php echo $tp_detil[0]['province'];?></td></tr>
	<tr><td>Region</td><td>:</td><td><?php echo $tp_detil[0]['region'];?></td></tr>
	<tr><td>City</td><td>:</td><td><?php echo $tp_detil[0]['city'];?></td></tr>
	<tr><td>Status</td><td>:</td><td><?php echo $tp_detil[0]['status'];?></td></tr>
	<tr><td>Latitude</td><td>:</td><td><?php echo $tp_detil[0]['lattitude'];?></td></tr>
	<tr><td>Longitude</td><td>:</td><td><?php echo $tp_detil[0]['longitude'];?></td></tr>
	<tr><td>Site Type</td><td>:</td><td><?php echo $tp_detil[0]['site_type'];?></td></tr>
	<tr><td>Tower Type</td><td>:</td><td><?php echo $tp_detil[0]['tower_type'];?></td></tr>
	<tr><td>Building Height(m)</td><td>:</td><td><?php echo $tp_detil[0]['building_height_mtr'];?></td></tr>
	<tr><td>Tower Height(m)</td><td>:</td><td><?php echo $tp_detil[0]['tower_height_mtr'];?></td></tr>
	<tr><td>Address</td><td>:</td><td><?php echo $tp_detil[0]['address'];?></td></tr>
	<tr><td>Shareable</td><td>:</td><td><?php echo $tp_detil[0]['shareable_status'];?></td></tr>
  </table>
<input type="button" value="Back" onclick="history.go(-1);" />
  </div>
