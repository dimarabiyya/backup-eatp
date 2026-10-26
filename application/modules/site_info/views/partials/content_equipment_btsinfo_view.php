<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<div class="form-horizontal">

  <div class="widget">
    <div class="widget-head">
      <h4 class="heading"><i></i> BTS Info</h4>
    </div>

    <div class="widget-body">

      <div class="control-group">
        <label class="control-label" style="text-align:left">Morphology:</label>
        <div class="controls">
          <span class="uneditable-input"><?php echo $bts_info[0]['morphology'] ?></span>
        </div>
        <div class="separator bottom"></div>
      </div>

      <div style="width: 100%; overflow: auto">
        <table id="tblBTS" class="table table-bordered table-condensed" style="width:3000px;">
          <thead>
            <tr>
            <th style="text-align:center; width: 100px">Vendor ID</th>
            <th style="text-align:center; width: 100px">CDMA ID</th>
            <th style="text-align:center; width: 100px">System</th>
            <th style="text-align:center; width: 100px">BTS Brand</th>
            <th style="text-align:center; width: 100px">BTS Mode</th>
            <th style="text-align:center; width: 100px">BTS Type</th>
            <?php
            $headers = array(
                'height_asl' => 100,
                'radius_of_operation' => 100,
                'equipment_type' => 100,
                'equipment_output_power' => 150,
                'bandwidth' => 100,
                'receiver_freq' => 100,
                'antenna_type' => 100,
                'feeding_loss' => 100,
                'connector_loss' => 100,
                'branch_loss' => 100,
                'antenna_height_agl' => 100,
                'transmitter_polarization' => 100,
            );

            foreach ($headers as $header => $width) {
              $header = humanize($header);
//                            $header = str_replace('_', ' ', $header);
              echo "<th style='min-width:{$width}px;'>{$header}</th>";
            }
            ?>
          </tr>
          </thead>
          <tbody>
            <?php
//            echo print_r($bts_info);
            if ($this->uri->segment(3) == 'view' && count($bts_info)) {

              foreach ($bts_info as $bts) {
                ?>
                <tr>
                  <td><?php echo $bts['vendor_id'] ?></td>
                  <td><?php echo $bts['cdma_id'] ?></td>
                  <td><?php echo $bts['bts_system'] ?></td>
                  <td><?php echo $bts['bts_brand'] ?></td>
                  <td><?php echo $bts['bts_mode'] ?></td>
                  <td><?php echo $bts['bts_type'] ?></td>
                  <td><?php echo $bts['height_asl'] ?></td>
                  <td><?php echo $bts['radius_of_operation'] ?></td>
                  <td><?php echo $bts['equipment_type'] ?></td>
                  <td><?php echo $bts['equipment_output_power'] ?></td>
                  <td><?php echo $bts['bandwidth'] ?></td>
                  <!--<td><?php echo $bts['tx_freq'] ?></td>-->
                  <td><?php echo $bts['rx_freq'] ?></td>
                  <td><?php echo $bts['antenna_type'] ?></td>
                  <td><?php echo $bts['feeding_loss'] ?></td>
                  <td><?php echo $bts['connector_loss'] ?></td>
                  <td><?php echo $bts['branch_loss'] ?></td>
                  <td><?php echo $bts['antenna_height_agl'] ?></td>
                  <td><?php echo $bts['transmitter_polarization'] ?></td>
                </tr>
                <?php
              }
            } else {
              echo '<tr colspan="18">No BTS Data.</td>';
            }
            ?>
          </tbody>
        </table>
      </div>

    </div>

  </div>

</div>

<script>
  doPreviledgePerfield();
</script>