<?php //die('di view='.$site_idd);?>
<div class="left-sidebar">
<div class="row-fluid">
              <div class="span12">
                <div class="widget">
                  <div class="widget-header">
                    <div class="title">
                      <a id="dynamicTable"><?php echo lang('text_heading_title_tp_list') ?></a>
                    </div>
                    <span class="tools">
                      <a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a>
                    </span>
                  </div>
                  <div class="widget-body">
                    <div id="dt_example" class="example_alt_pagination">
					<!--
                      <form method=GET action="<?php echo site_url('referensi/map/kandidat_filter');?>">
                              Pilih angka :
                              <select name="status" onChange='this.form.submit()'>
                                      <option value="1" <?php if(isset($_GET['status']) and $_GET['status'] ==1) echo "selected"; else echo (" ");?>>Semua</option>
                                      <option value="2" <?php if(isset($_GET['status']) and $_GET['status'] ==2) echo "selected"; else echo (" ");?>>Belum Kandidat</option>
                                      <option value="3" <?php if(isset($_GET['status']) and $_GET['status'] ==3) echo "selected"; else echo (" ");?>>Kandidat</option>
                                      <option value="4" <?php if(isset($_GET['status']) and $_GET['status'] ==4) echo "selected"; else echo (" ");?>>Disewa</option>
                              </select>
                              <input type="hidden" name="site_id_filter" id="site_id_filter"  value="<?php echo($site_idd);?>">
                      </form>
					  -->
                      <?php //die('di view='.$site_idd);b.site_id_text, b.site_name, c.site_id, c.tp_name, c.latitude, c.longitude, c.address, c.kandidat?>
   <?php if($site_kandidat): ?>
                      <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">
                        
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
                        <tbody>

	<?php foreach ($site_kandidat as $row) {
		$link = '&nbsp;';
	?>
                          <tr class="gradeX ">
							<td><?php echo $row['site_id_text'] ?></td>
							<td><?php echo $row['site_name'] ?></td>
							<td><?php echo $row['site_id'] ?></td>
							<td><?php echo $row['tp_name'] ?></td>
							<td><?php echo $row['latitude'] ?></td>
							<td><?php echo $row['longitude'] ?></td>
							<td><?php echo $row['address'] ?></td>
							<td><?php echo $row['kandidat'] ?></td>
							<td align="center"><?php echo $link ?></td>
						</tr>
						<?php } ?>
	
                        </tbody>
                      </table>
  <?php else : ?>
    <tr class="gradeX "><td colspan="12">There are no records created yet.</td></tr>
  <?php endif; ?>
                      <div class="clearfix">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
</div>
