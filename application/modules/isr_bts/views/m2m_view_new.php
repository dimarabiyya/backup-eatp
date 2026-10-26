<?php
/*
 * Andedi Tarmadi
 * 
 * 
 * ----------------
 * Tumbas Wiralodra
 * Email : andedi.foss@gmail.com
 * 087829986468
 */
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

error_reporting(0);
?>

<style>
  .form-actions {
    padding: 10px;
  }
</style>

<div class="widget" style="width: 1200px; overflow: auto;">

  <div class="widget-head heading-outer">
    <h4 class="heading glyphicons list"><i></i><?php echo  $judul ?></h4>
  </div>

  <div class="widget-body">
    <div class="widget">
      <div class="widget-head">
        <h4 class="heading">General</h4>
      </div>
      <div class="widget-body">
        <div class="row-fluid">
          <div class="span5">
            <table>
              <tr>
                <td>Concession Number</td><td> : <strong><?php echo $resi_header->concession_number; ?></strong></td>
              </tr>
              <tr>
                <td>License Number</td><td> : <strong  class="badge badge-important"><?php echo $resi_header->license_number; ?></strong></td>
              </tr>
              <tr>
                <td>Application Number</td><td> : <strong class="badge badge-important"><?php echo $resi_header->app_ref; ?></strong></td>
              </tr>
              <tr>
                <td>Application Index</td><td> : <strong><?php echo $resi_header->app_index; ?></strong></td>
              </tr>
            </table>
          </div>
          <div class="span5">
            <table>
              <tr>
                <td style="width:50%">Batch Number</td><td> : <strong><?php echo $resi_header->batch_no; ?></strong></td>
              </tr>
              <tr>
                <td>Vendor ID</td><td> : <strong><?php echo $resi_header->near_end_v_id . ' # ' . $resi_header->far_end_v_id; ?></strong></td>
              </tr>
              <tr>
                <td>Creator</td><td> : <strong><?php echo $resi_header->created_by; ?></strong></td>
              </tr>
              <tr>
                <td>Date</td><td> : <strong><?php echo $resi_header->created_on; ?></strong></td>
              </tr>
            </table>
          </div>
          <div class="span2">
            <table>
              <tr>
                <td style="width:50%">Document</td><td> : <a title="Download Document" class="btn-action btn-danger glyphicons download" href="<?php echo base_url("file/upload/isr/{$resi_header->doc}"); ?>" download> <i></i></a></strong></td>
              </tr>
            </table>
          </div>
        </div>
        <br>
        <div id='container_table' class="row-fluid">
          <table id="app_table" class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <?php
                $headers = array(
                    'service_id' => 100,
                    'subservice_id' => 100,
                    'client_id' => 100,
                    'application_identifier' => 150,
                    'station_identifier' => 150,
                    'link_id' => 100,
                    'station_name' => 200,
                    'height_asl' => 100,
                    'lat_deg' => 100,
                    'lat_min' => 100,
                    'lat_sec' => 100,
                    'lat_dir' => 100,
                    'long_deg' => 100,
                    'long_min' => 100,
                    'long_sec' => 100,
                    'long_dir' => 100,
                    'radius_of_operation' => 150,
                    'site_province' => 100,
                    'site_city' => 100,
                    'site_district' => 100,
                    'area_of_service' => 300,
                    'equipment_type' => 100,
                    'equipment_output_power' => 200,
                    'bandwidth' => 100,
                    'transmitter_freq' => 150,
                    'receiver_freq' => 100,
                    'antenna_type' => 100,
                    'feeding_loss' => 100,
                    'connector_loss' => 100,
                    'branch_loss' => 100,
                    'antenna_height_agl' => 150,
                    'transmitter_polarization' => 200,
//                    'near_end_sf_id' => 100,
//                    'far_end_sf_id' => 100,
//                    'near_end_v_id' => 100,
//                    'far_end_v_id' => 100,
//                    'company_code' => 100
                );

                foreach ($headers as $header => $width) {
                  $header = humanize($header);
                  echo "<th style='min-width:{$width}px;'>{$header}</th>";
                }
                ?>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              foreach ($resi_detail as $station) {
                ?>

                <tr>
                  <?php
                  foreach ($headers as $index => $value) {
                    echo "<td>{$station->$index}</td>";
                  }
                  ?>
                </tr>

                <?php
                $i++;
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <hr class="separator">

    <div class="widget">
      <div class="widget-head">
        <h4 class="heading">History M2M</h4>
      </div>
      <div class="widget-body">
        <div id='container_table' class="row-fluid">
          <table id="app_table" class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th style="width: 15%">Date</th><th style="width: 18%">Creator</th><th style="width: 12%">Response Code</th><th>Response Message</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($resi_history as $history) {
                echo "<tr><td>{$history->created_on}</td><td>{$history->created_by}</td><td>{$history->response_code}</td><td>{$history->response_message}</td><tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <button type="button" class="btn btn-icon btn-default glyphicons circle_remove" id="btn_close"><i></i>Close</button>
    </div>

  </div>

</div>

<script>
  $(document).ready(function() {

    $('#container_table').attr('style', 'overflow: auto; min-height: 100px; width:' + parent.viewWidth + 'px');

    $('#btn_close').click(function() {
      $.fancybox.close();
    });

  });
</script>