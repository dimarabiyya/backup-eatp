<div class="innerLR">
  <div class="widget">
    <div class="widget-head heading-outer">
      <h1 class="heading glyphicons notes_2"><i></i> Detail Propose New</h1>
      <a href="<?php echo base_url('isr_bts/resi'); ?>" class="btn btn-mini btn-heading btn-primary btn-icon glyphicons unshare pull-right"><i></i><span>Back</span></a>
    </div>
    <form id="ff" action="" method="post" class="form-horizontal">
      <div class="widget-body">
        <div class="row-fluid">
          <div class="span5">
            <div class="control-group">
              <label class="control-label">Resi No : </label>
              <div class="controls"><input class="span12" id="app_no" name="app_no" type="text" value="<?php echo $resi->app_no; ?>" autofocus=""></div>
            </div>
            <div class="control-group">
              <label class="control-label">Resi Issued : </label>
              <div class="controls">
                <div class="input-append">
                  <input class="datepicker" id="app_issued" name="app_issued" type="text" value="<?php echo $resi->app_issued; ?>" readonly="">
                  <span class="add-on glyphicons calendar"><i></i></span>
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Document : </label>
              <div class="controls"><?php echo $resi->download_doc; ?></div>
            </div>
            <div class="control-group">
              <label class="control-label">Remark : </label>
              <div class="controls"><input class="span12" type="text" id="remark" name="remark" value="<?php echo $resi->remark; ?>"></div>
            </div>
          </div>
        </div>
        <div class="row-fluid" style="margin-top: 10px">
          <div style="width: 100%; overflow: auto">
            <table id="bts_table" class="table table-striped table-bordered table-condensed" style="width: 4000px">
              <thead>
                <tr>
                  <th>Service ID</th>
                  <th>Subservice ID</th>
                  <th>Client ID</th>
                  <th>Application ID</th>
                  <th>Station ID</th>
                  <th>Link ID</th>
                  <th>Station Name</th>
                  <th>Height ASL</th>
                  <th>Lat / Long</th>
                  <th>Radius of Operation</th>
                  <th>Site Province</th>
                  <th>Site City</th>
                  <th>Site District</th>
                  <th>Area of Service</th>
                  <th>Equipment Type</th>
                  <th>Equipment Output Power</th>
                  <th>Bandwidth</th>
                  <th>Tx Freq</th>
                  <th>Rx Freq</th>
                  <th>Antenna Type</th>
                  <th>Feeding Loss</th>
                  <th>Connector Loss</th>
                  <th>Branch Loss</th>
                  <th>Antenna Height AGL</th>
                  <th>Transmitter Polatization</th>
                </tr>
              </thead>
              <tbody id="table_data">
                <?php
                foreach ($resi_detail as $row) {
                  $lat = "<div style='height:25px'>Lat : {$row->lat_deg}&deg{$row->lat_min}'{$row->lat_sec}\"{$row->lat_dir}</div>";
                  $long = "<div  style='height:25px';>Long : {$row->long_deg}&deg{$row->long_min}'{$row->long_sec}\"{$row->long_dir}</div>";
                  $polar = form_dropdown('transmitter_polarization[]', $transmitter_polarization_opt, $row->transmitter_polarization, 'class="input-medium"');
                  echo "
                    <tr class='row_data' id='row_data_{$row->resi_link_id}'>
                      <td>{$row->service_id}</td>
                      <td>{$row->subservice_id}</td>
                      <td>{$row->client_id}</td>
                      <td>{$row->application_identifier}</td>
                      <td>{$row->station_identifier}</td>
                      <td>{$row->link_id}</td>
                      <td>{$row->station_name}</td>
                      <td>{$row->height_asl}</td>
                      <td>{$lat} {$long}</td>  
                      <td>{$row->radius_of_operation}</td>
                      <td>{$row->site_province}</td>
                      <td>{$row->site_city}</td>
                      <td>{$row->site_district}</td>
                      <td>{$row->area_of_service}</td>
                      <td>{$row->equipment_type}</td>
                      <td>{$row->equipment_output_power}</td>
                      <td>{$row->bandwidth}</td>
                      <td>{$row->transmitter_freq}</td>
                      <td>{$row->receiver_freq}</td>
                      <td>{$row->antenna_type}</td>
                      <td>{$row->feeding_loss}</td>
                      <td>{$row->connector_loss}</td>
                      <td>{$row->branch_loss}</td>
                      <td>{$row->antenna_height_agl}</td>
                      <td>{$polar}</td> 
                    </tr>
                    ";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
  $('input').each(function (index, element){
    $(element).attr('readonly', 'readonly');
  });
  $('select').each(function (index, element){
    $(element).attr('readonly', 'readonly');
  });
</script>