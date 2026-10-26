<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<?php echo form_open('', 'id="frmTransmission" class="form-horizontal') ?>
<div class="widget">	
  <div class="widget-head">
    <h4 class="heading"><i></i> Transmission Info</h4>
  </div>

  <div class="widget-body">

    <div class="control-group" id="form_class_site_transmission">
      <label class="control-label" for="oldSiteId" style="width:180px; text-align:left;">Site Class Transmission:</label>
      <div class="controls" style="margin-left:180px">
        <?php
        $lookup = $this->core->get_lookup('site_class_transm');
        $options = array(0 => '- Choose one -');
        foreach ($lookup as $lo) {
          $options[$lo['id']] = $lo['desc'];
        }
        echo form_dropdown('siteClassTransm', $options, $site_class_transm, 'id="siteClassTransm"');
        ?>
      </div>
    </div>
    <div class="separator bottom"></div>

    <div id="tbl-transmission" style="overflow:auto">
      <table id="tblMW" class="table table-bordered table-condensed" style="width:auto">
        <thead>
          <tr style="background:#f8f8f8">
            <th>&nbsp;</th>
            <th class="center" style="width: 100px">Site ID</th>
            <th class="center" style="width: 100px">Path Length (km)</th>
            <th class="center" style="width: 100px">Link ID</th>
            <th class="center" style="width: 100px">Link Type</th>
            <th class="center">Freq. Used</th>
            <th class="center">Config</th>
            <th class="center">Power</th>
            <th class="center">IP Address</th>
            <th class="center">Main Ant. Model</th>
            <th class="center">Main Ant. Height</th>
            <th class="center">Main Ant. Diameter</th>
            <th class="center">SD Ant. Model</th>
            <th class="center">SD Ant. Height</th>
            <th class="center">SD Ant. Diameter</th>
            <th class="center">Azimuth</th>
            <th class="center">Eq. Model</th>
            <th class="center">Req. Capacity (MB/E1)</th>
            <th class="center">Inst. Capacity (MB/E1)</th>
            <th class="center">Used Capacity (MB/E1)</th>
            <th class="center">Treshold</th>
            <th class="center">Plan RSL (dBm)</th>
            <th class="center">Actual RSL (dBm)</th>
            <th class="center">FM</th>
            <th class="center">Availability (%)</th>
            <!-- <th class="center">Remark</th> -->
          </tr>
        </thead>
        <tbody id="main-body">
          <?php
          $transm_counter = 0;
          $sub_transm_counter = 0;
          if (count($transmission) > 0) {
            $site_fe = 0;
            foreach ($transmission as $transm) {
              if ($site_fe != $transm['fe']['site_id_ne']) {
                $transm_counter++;
                $sub_transm_counter++;
                ?>
                <tr id="transmission-separator-<?php echo $transm_counter ?>">
                  <td colspan="28"><i></i></td>
                </tr>  
                <tr id="transmission-row-<?php echo $transm_counter ?>" class="row-<?php echo $transm_counter; ?>">
                  <td rowspan="4" class="left" style="vertical-align:middle"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="TRANSMISSION.removeRow(<?php echo $transm_counter ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
                  <td>
                    <?php echo @form_input('siteID1Code[' . $transm_counter . ']', $transm['ne']['site_code'], 'data-vendor-id="' . $transm['ne']['vendor_id'] . '" class="input-medium site-id-ne-code" readonly="readonly"') ?>
                    <?php echo form_hidden('siteID1[' . $transm_counter . ']', $transm['ne']['site_id_ne']) ?>
                  </td>
                  <td>
                    <?php echo @form_input('pathLength1[' . $transm_counter . ']', $transm['ne']['path_length'], 'class="input-mini number"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('linkID1[' . $transm_counter . ']', $transm['ne']['link_id'], 'class="input-medium link-id-1"') ?>
                  </td>
                  <td>
                    <?php
                    $options = array(0 => '- Choose one -');
                    $lookup = $this->core->get_lookup('transmission_link_type');
                    foreach ($lookup as $lo) {
                      $options[$lo['id']] = $lo['desc'];
                    }
                    echo @form_dropdown('linkType1[' . $transm_counter . ']', $options, $transm['ne']['link_type']);
                    ?>
                  </td>
                  <td>
                    <?php echo @form_input('freqUsed1[' . $transm_counter . ']', $transm['ne']['frequency_used'], 'class="input-mini just-number freq-used"') ?>
                  </td>
                  <td>
                    <?php
                    $lookup = $this->core->get_lookup('transmission_config_hop');
                    $options = array(0 => '- Choose one -');
                    foreach ($lookup as $lo) {
                      $options[$lo['id']] = $lo['desc'];
                    }
                    echo form_dropdown('config1[' . $transm_counter . ']', $options, $transm['ne']['config']);
                    ?>

                  </td>
                  <td>
                    <?php echo @form_input('power1[' . $transm_counter . ']', $transm['ne']['power'], 'class="input-mini number" onchange="copyPaste(this, \'power2\', ' . $transm_counter . ')"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('ipAddress1[' . $transm_counter . ']', $transm['ne']['ip_address'], 'class="input-small ipaddress"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('mainAntModel1[' . $transm_counter . ']', $transm['ne']['ant_model'], 'class="input-medium"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('mainAntHeight1[' . $transm_counter . ']', $transm['ne']['ketinggian'], 'class="input-mini number"') ?>
                  </td>
                  <td>
                    <?php
                    $options = array(0 => '- Choose one -');
                    $lookup = get_instance()->core->get_lookup('antenna_diameter');
                    foreach ($lookup as $lo) {
                      $options[$lo['id']] = $lo['desc'];
                    }
                    echo @form_dropdown('mainAntDiameter1[' . $transm_counter . ']', $options, $transm['ne']['ant_diameter'])
                    ?>
                  </td>
                  <td>
                    <?php echo @form_input('sdAntModel1[' . $transm_counter . ']', $transm['ne']['sd_ant_model'], 'class="input-medium"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('sdAntHeight1[' . $transm_counter . ']', $transm['ne']['sd_ant_height'], 'class="input-mini number"') ?>
                  </td>
                  <td>
                    <?php
                    $options = array(0 => '- Choose one -');
                    $lookup = get_instance()->core->get_lookup('antenna_diameter');
                    foreach ($lookup as $lo) {
                      $options[$lo['id']] = $lo['desc'];
                    }
                    echo @form_dropdown('sdAntDiameter1[' . $transm_counter . ']', $options, $transm['ne']['sd_ant_diameter'])
                    ?>
                  </td>
                  <td>
                    <?php echo @form_input('azimuth1[' . $transm_counter . ']', $transm['ne']['azimuth'], 'class="input-mini number"') ?>
                  </td>
                  <td>
                    <?php
                    $options = array(0 => '- Choose one -');
                    $lookup = $this->core->get_lookup('equipment_model');
                    foreach ($lookup as $lo) {
                      $options[$lo['id']] = $lo['desc'];
                    }
                    echo @form_dropdown('eqModel1[' . $transm_counter . ']', $options, $transm['ne']['equipment_model']);
                    ?>
                  </td>
                  <td>
                    <?php echo @form_input('reqCapacity1[' . $transm_counter . ']', $transm['ne']['req_capacity'], 'class="input-mini" onchange="copyPaste(this, \'reqCapacity2\', ' . $transm_counter . ')"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('instCapacity1[' . $transm_counter . ']', $transm['ne']['inst_capacity'], 'class="input-mini" onchange="copyPaste(this, \'instCapacity2\', ' . $transm_counter . ')"'); ?>
                  </td>
                  <td>
                    <?php echo @form_input('usedCapacity1[' . $transm_counter . ']', $transm['ne']['used_capacity'], 'class="input-mini" onchange="copyPaste(this, \'usedCapacity2\', ' . $transm_counter . ')"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('treshold1[' . $transm_counter . ']', $transm['ne']['treshold'], 'class="input-mini number" onchange="copyPaste(this, \'treshold2\', ' . $transm_counter . ')"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('planRSL1[' . $transm_counter . ']', $transm['ne']['plan_rsl'], 'class="input-mini number" onchange="copyPaste(this, \'planRSL2\', ' . $transm_counter . ')"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('actualRSL1[' . $transm_counter . ']', $transm['ne']['actual_rsl'], 'class="input-mini number"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('fm1[' . $transm_counter . ']', $transm['ne']['fm'], 'class="input-mini number"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('availability1[' . $transm_counter . ']', $transm['ne']['availability'], 'class="input-mini percent" onchange="copyPaste(this, \'availability2\', ' . $transm_counter . ')"') ?>
                  </td>
                  <!-- <td>
                    <?php //echo @form_input('remark1[' . $transm_counter . ']', $transm['ne']['remark'], 'class="input-medium"') ?>
                  </td> -->
                </tr>
                <tr class="row-<?php echo $transm_counter ?>">
                  <td colspan="24">
                    <table class="table table-bordered table-condensed sub_table" style="width:auto; margin-left:0px;">
                      <tbody id="sub_table_<?php echo $transm_counter ?>_1">
                        <tr style="background:#f8f8f8">
                          <th width="35"></th>
                          <th>Tx Freq</th>
                          <th>Rx Freq</th>
                          <th>Polarization</th>
                        </tr>
                        <tr id="<?php echo "sub_table_1_row_$sub_transm_counter"; ?>">
                          <td>
                            <a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="TRANSMISSION.removeSubTableRow(<?php echo $sub_transm_counter;?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a>
                            <?php 
                            echo form_hidden('id1[' . $transm_counter . '][' . $sub_transm_counter . ']', $transm['ne']['equipment_mw_id']);
                            echo form_hidden('pairID1[' . $transm_counter . '][' . $sub_transm_counter . ']', $transm['ne']['pair_id']);
                            ?>
                          </td>
                          <td>
                            <?php echo form_input('txFreq1[' . $transm_counter . '][' . $sub_transm_counter . ']', $transm['ne']['tx_frequency'], 'class="input-mini just-number" onchange="copyPaste(this, \'rxFreq2[' . $transm_counter . ']\', ' . $sub_transm_counter . ')" style="height:20px"') ?>
                          </td>
                          <td>
                            <?php echo form_input('rxFreq1[' . $transm_counter . '][' . $sub_transm_counter . ']', $transm['ne']['rx_frequency'], 'class="input-mini just-number" onchange="copyPaste(this, \'txFreq2[' . $transm_counter . ']\', ' . $sub_transm_counter . ')" style="height:20px"') ?>
                          </td>
                          <td>
                            <?php
                            $options = array(0 => '- Choose one -');
                            $lookup = $this->core->get_lookup('transmission_polarization');
                            foreach ($lookup as $lo) {
                              $options[$lo['id']] = $lo['desc'];
                            }
                            echo @form_dropdown('polarization1[' . $transm_counter . '][' . $sub_transm_counter . ']', $options, $transm['ne']['polarization'])
                            ?>
                          </td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td><a class="btn btn-success btn-small" href="javascript:void(0)" onclick="TRANSMISSION.addSubTableRow(<?php echo $transm_counter ?>)" title="Add sub table row"><i class="icon-plus-sign icon-white"></i></a></td>
                          <td colspan="3"></td>
                        </tr>
                      </tfoot>
                    </table>
                  </td>
                </tr>
                <tr class="row-<?php echo $transm_counter; ?>">
                  <td>
                    <div class="input-append">
                      <?php echo @form_input('siteID2Code[' . $transm_counter . ']', $transm['fe']['site_code'], 'data-vendor-id="' . $transm['fe']['vendor_id'] . '" class="input-medium site-id-fe-code" readonly="readonly"') ?>
                      <?php echo form_hidden('siteID2[' . $transm_counter . ']', $transm['fe']['site_id_ne']) ?>
                      <button type="button" class="btn" onclick="doLookupSiteTransmission('siteID2Code[<?php echo $transm_counter ?>]', 'siteID2[<?php echo $transm_counter ?>]', <?php echo $transm_counter ?>)"><i class="icon-search"></i></button>
                    </div>
                  </td>
                  <td>
                    <?php echo @form_input('pathLength2[' . $transm_counter . ']', $transm['fe']['path_length'], 'class="input-mini number"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('linkID2[' . $transm_counter . ']', $transm['fe']['link_id'], 'class="input-medium link-id-2"') ?>
                  </td>
                  <td>
                    <?php
                    $options = array(0 => '- Choose one -');
                    $lookup = $this->core->get_lookup('transmission_link_type');
                    foreach ($lookup as $lo) {
                      $options[$lo['id']] = $lo['desc'];
                    }
                    echo @form_dropdown('linkType2[' . $transm_counter . ']', $options, $transm['fe']['link_type']);
                    ?>
                  </td>
                  <td>
                    <?php echo @form_input('freqUsed2[' . $transm_counter . ']', $transm['fe']['frequency_used'], 'class="input-mini just-number freq-used"') ?>
                  </td>
                  <td>
                    <?php
                    $lookup = $this->core->get_lookup('transmission_config_hop');
                    $options = array(0 => '- Choose one -');
                    foreach ($lookup as $lo) {
                      $options[$lo['id']] = $lo['desc'];
                    }
                    ?>
                    <?php echo form_dropdown('config2[' . $transm_counter . ']', $options, $transm['fe']['config']) ?>
                  </td>
                  <td>
                    <?php echo @form_input('power2[' . $transm_counter . ']', $transm['fe']['power'], 'class="input-mini number" readonly="readonly"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('ipAddress2[' . $transm_counter . ']', $transm['fe']['ip_address'], 'class="input-small ipaddress"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('mainAntModel2[' . $transm_counter . ']', $transm['fe']['ant_model'], 'class="input-medium"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('mainAntHeight2[' . $transm_counter . ']', $transm['fe']['ketinggian'], 'class="input-mini number"') ?>
                  </td>
                  <td>
                    <?php
                    $options = array(0 => '- Choose one -');
                    $lookup = get_instance()->core->get_lookup('antenna_diameter');
                    foreach ($lookup as $lo) {
                      $options[$lo['id']] = $lo['desc'];
                    }
                    ?>
                    <?php echo @form_dropdown('mainAntDiameter2[' . $transm_counter . ']', $options, $transm['fe']['ant_diameter']) ?>
                  </td>
                  <td>
                    <?php echo @form_input('sdAntModel2[' . $transm_counter . ']', $transm['fe']['sd_ant_model'], 'class="input-medium"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('sdAntHeight2[' . $transm_counter . ']', $transm['fe']['sd_ant_height'], 'class="input-mini number"') ?>
                  </td>
                  <td>
                    <?php
                    $options = array(0 => '- Choose one -');
                    $lookup = get_instance()->core->get_lookup('antenna_diameter');
                    foreach ($lookup as $lo) {
                      $options[$lo['id']] = $lo['desc'];
                    }
                    ?>
                    <?php echo @form_dropdown('sdAntDiameter2[' . $transm_counter . ']', $options, $transm['fe']['sd_ant_diameter']) ?>
                  </td>
                  <td>
                    <?php echo @form_input('azimuth2[' . $transm_counter . ']', $transm['fe']['azimuth'], 'class="input-mini number"') ?>
                  </td>
                  <td>
                    <?php
                    $options = array(0 => '- Choose one -');
                    $lookup = $this->core->get_lookup('equipment_model');
                    foreach ($lookup as $lo) {
                      $options[$lo['id']] = $lo['desc'];
                    }
                    echo @form_dropdown('eqModel2[' . $transm_counter . ']', $options, $transm['fe']['equipment_model']);
                    ?>
                  </td>
                  <td>
                    <?php echo @form_input('reqCapacity2[' . $transm_counter . ']', $transm['fe']['req_capacity'], 'class="input-mini" readonly="readonly"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('instCapacity2[' . $transm_counter . ']', $transm['fe']['inst_capacity'], 'class="input-mini" readonly="readonly"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('usedCapacity2[' . $transm_counter . ']', $transm['fe']['used_capacity'], 'class="input-mini" readonly="readonly"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('treshold2[' . $transm_counter . ']', $transm['fe']['treshold'], 'class="input-mini number" readonly="readonly"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('planRSL2[' . $transm_counter . ']', $transm['fe']['plan_rsl'], 'class="input-mini number" readonly="readonly"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('actualRSL2[' . $transm_counter . ']', $transm['fe']['actual_rsl'], 'class="input-mini number"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('fm2[' . $transm_counter . ']', $transm['fe']['fm'], 'class="input-mini number"') ?>
                  </td>
                  <td>
                    <?php echo @form_input('availability2[' . $transm_counter . ']', $transm['fe']['availability'], 'class="input-mini percent" readonly="readonly"') ?>
                  </td>
                  <!-- <td>
                    <?php //echo @form_input('remark2[' . $transm_counter . ']', $transm['fe']['remark'], 'class="input-medium"') ?>
                  </td> -->
                </tr>
                <tr class="row-<?php echo $transm_counter ?>">
                  <td colspan="24">
                    <table class="table table-bordered table-condensed sub_table" style="width:auto; margin-left:0px;">
                      <tr style="background:#f8f8f8">
                        <th width="35"></th>
                        <th>Tx Freq</th>
                        <th>Rx Freq</th>
                        <th>Polarization</th>
                      </tr>
                      <tbody id="sub_table_<?php echo $transm_counter ?>_2">
                        <tr id="<?php echo "sub_table_2_row_$sub_transm_counter"; ?>">
                          <td>
                            <?php
                            echo form_hidden('id2[' . $transm_counter . '][' . $sub_transm_counter . ']', $transm['fe']['equipment_mw_id']); 
                            echo form_hidden('pairID2[' . $transm_counter . '][' . $sub_transm_counter . ']', $transm['ne']['pair_id']);
                            ?>
                            
                          </td>
                          <td>
                            <?php echo form_input('txFreq2[' . $transm_counter . '][' . $sub_transm_counter . ']', $transm['fe']['tx_frequency'], 'class="input-mini just-number" style="height:20px"') ?>
                          </td>
                          <td>
                            <?php echo form_input('rxFreq2[' . $transm_counter . '][' . $sub_transm_counter . ']', $transm['fe']['rx_frequency'], 'class="input-mini just-number" style="height:20px"') ?>
                          </td>
                          <td>
                            <?php
                            $options = array(0 => '- Choose one -');
                            $lookup = $this->core->get_lookup('transmission_polarization');
                            foreach ($lookup as $lo) {
                              $options[$lo['id']] = $lo['desc'];
                            }
                            echo @form_dropdown('polarization2[' . $transm_counter . '][' . $sub_transm_counter . ']', $options, $transm['fe']['polarization'])
                            ?>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <?php
              } else {
                $sub_transm_counter++;
                //$transm_counter++;
                ?>
              <script>
                    var html1 = '<tr id="sub_table_1_row_' + <?php echo $sub_transm_counter; ?> + '">\n\
                  <td>\n\
                    <input type="hidden" name="id1[' + <?php echo $transm_counter; ?> + '][' + <?php echo $sub_transm_counter; ?> + ']" value="'+<?php echo $transm['ne']['equipment_mw_id']; ?>+'">\n\
                    <input type="hidden" name="pairID1[' + <?php echo $transm_counter; ?> + '][' + <?php echo $sub_transm_counter; ?> + ']"  value="'+<?php echo $transm['ne']['pair_id']; ?>+'">\n\
                    <a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="TRANSMISSION.removeSubTableRow(' + <?php echo $sub_transm_counter; ?> + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a>\n\
                  </td>\n\
                <td><input type="text" name="txFreq1[' + <?php echo $transm_counter; ?> + '][' + <?php echo $sub_transm_counter; ?> + ']" onchange="copyPaste(this, \'rxFreq2[' + <?php echo $transm_counter; ?> + ']\', ' + <?php echo $sub_transm_counter; ?> + ')" value="<?php echo $transm['ne']['tx_frequency']; ?>" class="input-mini just-number" style="height:20px" autocomplete="off"></td>\n\
                <td><input type="text" name="rxFreq1[' + <?php echo $transm_counter; ?> + '][' + <?php echo $sub_transm_counter; ?> + ']" onchange="copyPaste(this, \'txFreq2[' + <?php echo $transm_counter; ?> + ']\', ' + <?php echo $sub_transm_counter; ?> + ')" value="<?php echo $transm['ne']['rx_frequency']; ?> " class="input-mini just-number" style="height:20px" autocomplete="off">\</td>\n\
                <td>\n\
                <select name="polarization1[' + <?php echo $transm_counter; ?> + '][' + <?php echo $sub_transm_counter; ?> + ']">\
                                                        <option value="0">- Choose one -</option>\
      <?php
      $lookup = $this->core->get_lookup('transmission_polarization');
      foreach ($lookup as $lo) {
        $selected = '';
        if (trim($lo['id']) == trim($transm['ne']['polarization'])) {
          $selected = 'selected="selected"';
        }
        ?>\
                      <option value="<?php echo $lo['id'] ?>" <?php echo $selected; ?>><?php echo $lo['desc'] ?> </option>\
        <?php
      }
      ?>\
                </select>\
                </td>\n\
                </tr>';
                    $('#sub_table_<?php echo $transm_counter ?>_1').append(html1);

                    var html2 = '<tr id="sub_table_2_row_' + <?php echo $sub_transm_counter; ?> + '"><td><input type="hidden" name="id2[' + <?php echo $transm_counter; ?> + '][' + <?php echo $sub_transm_counter; ?> + ']" value="'+<?php echo $transm['fe']['equipment_mw_id']; ?>+'"><input type="hidden" name="pairID2[' + <?php echo $transm_counter; ?> + '][' + <?php echo $sub_transm_counter; ?> + ']"  value="'+<?php echo $transm['fe']['pair_id']; ?>+'"></td>\n\
                <td><input type="text" name="txFreq2[' + <?php echo $transm_counter; ?> + '][' + <?php echo $sub_transm_counter; ?> + ']" onchange="copyPaste(this, \'rxFreq1[' + <?php echo $transm_counter; ?> + ']\', ' + <?php echo $sub_transm_counter; ?> + ')" value="<?php echo $transm['fe']['tx_frequency']; ?>" class="input-mini just-number" style="height:20px" autocomplete="off"></td>\n\
                <td><input type="text" name="rxFreq2[' + <?php echo $transm_counter; ?> + '][' + <?php echo $sub_transm_counter; ?> + ']" onchange="copyPaste(this, \'txFreq1[' + <?php echo $transm_counter; ?> + ']\', ' + <?php echo $sub_transm_counter; ?> + ')" value="<?php echo $transm['fe']['rx_frequency']; ?>" class="input-mini just-number" style="height:20px" autocomplete="off">\</td>\n\
                <td>\n\
                <select name="polarization2[' + <?php echo $transm_counter; ?> + '][' + <?php echo $sub_transm_counter; ?> + ']">\
                                                        <option value="0">- Choose one -</option>\
      <?php
      $lookup = $this->core->get_lookup('transmission_polarization');
      foreach ($lookup as $lo) {
        $selected = '';
        if (trim($lo['id']) == trim($transm['fe']['polarization'])) {
          $selected = 'selected="selected"';
        }
        ?>\
                      <option value="<?php echo $lo['id'] ?>" <?php echo $selected; ?>><?php echo $lo['desc'] ?></option>\
        <?php
      }
      ?>\
                </select>\
                </td>\n\
                </tr>';
                    $('#sub_table_<?php echo $transm_counter ?>_2').append(html2);
              </script>
              <?php
              //$sub_transm_counter++;
            }

            $site_fe = $transm['fe']['site_id_ne'];
          }
        } else {
          $transm_counter++;
          $sub_transm_counter++;
          ?>

          <tr id="transmission-row-<?php echo $transm_counter ?>" class="row-<?php echo $transm_counter ?>">
            <td class="left" rowspan="4" style="vertical-align:middle"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="TRANSMISSION.removeRow(<?php echo $transm_counter ?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
            <td>
              <?php
              echo form_hidden('pairID[' . $transm_counter . ']', '');
              echo form_hidden('id1[' . $transm_counter . ']', '');
              ?>

              <?php echo @form_input('siteID1Code[' . $transm_counter . ']', '', 'class="input-medium site-id-ne-code" readonly="readonly"') ?>
              <?php echo form_hidden('siteID1[' . $transm_counter . ']', '') ?>
            </td>
            <td>
              <?php echo @form_input('pathLength1[' . $transm_counter . ']', '', 'class="input-mini number"') ?>
            </td>
            <td>
              <?php echo @form_input('linkID1[' . $transm_counter . ']', '', 'class="input-medium link-id-1"') ?>
            </td>
            <td>
              <?php
              $options = array(0 => '- Choose one -');
              $lookup = $this->core->get_lookup('transmission_link_type');
              foreach ($lookup as $lo) {
                $options[$lo['id']] = $lo['desc'];
              }
              echo @form_dropdown('linkType1[' . $transm_counter . ']', $options);
              ?>
            </td>
            <td>
              <?php echo form_input('freqUsed1[' . $transm_counter . ']', '', 'class="input-mini just-number freq-used"') ?>
            </td>
            <td>
              <?php
              $lookup = $this->core->get_lookup('transmission_config_hop');
              $options = array(0 => '- Choose one -');
              foreach ($lookup as $lo) {
                $options[$lo['id']] = $lo['desc'];
              }
              echo form_dropdown('config1[' . $transm_counter . ']', $options);
              ?>
            </td>
            <td>
              <?php echo @form_input('power1[' . $transm_counter . ']', '', 'class="input-mini number" onchange="copyPaste(this, \'power2\', ' . $transm_counter . ')"') ?>
            </td>
            <td>
              <?php echo @form_input('ipAddress1[' . $transm_counter . ']', '', 'class="input-small ipaddress"') ?>
            </td>
            <td>
              <?php echo @form_input('mainAntModel1[' . $transm_counter . ']', '', 'class="input-medium"') ?>
            </td>
            <td>
              <?php echo @form_input('mainAntHeight1[' . $transm_counter . ']', '', 'class="input-mini number"') ?>
            </td>
            <td>
              <?php
              $options = array(0 => '- Choose one -');
              $lookup = get_instance()->core->get_lookup('antenna_diameter');
              foreach ($lookup as $lo) {
                $options[$lo['id']] = $lo['desc'];
              }
              echo @form_dropdown('mainAntDiameter1[' . $transm_counter . ']', $options)
              ?>
            </td>
            <td>
              <?php echo @form_input('sdAntModel1[' . $transm_counter . ']', '', 'class="input-medium"') ?>
            </td>
            <td>
              <?php echo @form_input('sdAntHeight1[' . $transm_counter . ']', '', 'class="input-mini number"') ?>
            </td>
            <td>
              <?php
              $options = array(0 => '- Choose one -');
              $lookup = get_instance()->core->get_lookup('antenna_diameter');
              foreach ($lookup as $lo) {
                $options[$lo['id']] = $lo['desc'];
              }
              echo @form_dropdown('sdAntDiameter1[' . $transm_counter . ']', $options)
              ?>
            </td>
            <td>
              <?php echo @form_input('azimuth1[' . $transm_counter . ']', '', 'class="input-mini number"') ?>
            </td>
            <td>
              <?php
              $options = array(0 => '- Choose one -');
              $lookup = $this->core->get_lookup('equipment_model');
              foreach ($lookup as $lo) {
                $options[$lo['id']] = $lo['desc'];
              }
              echo @form_dropdown('eqModel1[' . $transm_counter . ']', $options, '');
              ?>
            </td>
            <td>
              <?php echo @form_input('reqCapacity1[' . $transm_counter . ']', '', 'class="input-mini" onchange="copyPaste(this, \'reqCapacity2\', ' . $transm_counter . ')"') ?>
            </td>
            <td>
              <?php echo @form_input('instCapacity1[' . $transm_counter . ']', '', 'class="input-mini" onchange="copyPaste(this, \'instCapacity2\', ' . $transm_counter . ')"'); ?>
            </td>
            <td>
              <?php echo @form_input('usedCapacity1[' . $transm_counter . ']', '', 'class="input-mini" onchange="copyPaste(this, \'usedCapacity2\', ' . $transm_counter . ')"') ?>
            </td>
            <td>
              <?php echo @form_input('treshold1[' . $transm_counter . ']', '', 'class="input-mini number" onchange="copyPaste(this, \'treshold2\', ' . $transm_counter . ')"') ?>
            </td>
            <td>
              <?php echo @form_input('planRSL1[' . $transm_counter . ']', '', 'class="input-mini number" onchange="copyPaste(this, \'planRSL2\', ' . $transm_counter . ')"') ?>
            </td>
            <td>
              <?php echo @form_input('actualRSL1[' . $transm_counter . ']', '', 'class="input-mini number"') ?>
            </td>
            <td>
              <?php echo @form_input('fm1[' . $transm_counter . ']', '', 'class="input-mini number"') ?>
            </td>
            <td>
              <?php echo @form_input('availability1[' . $transm_counter . ']', '', 'class="input-mini percent" onchange="copyPaste(this, \'availability2\', ' . $transm_counter . ')"') ?>
            </td>
          </tr>
          <tr class="row-<?php echo $transm_counter ?>">
            <td colspan="24">
              <table class="table table-bordered table-condensed sub_table" style="width:auto; margin-left:0px;">
                <tbody id="sub_table_1_1">
                  <tr style="background:#f8f8f8">
                    <th width="35"></th>
                    <th>Tx Freq</th>
                    <th>Rx Freq</th>
                    <th>Polarization</th>
                  </tr>
                  <tr id="sub_table_1_row_1">
                    <td><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="TRANSMISSION.removeSubTableRow(<?php echo $sub_transm_counter;?>)" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>
                    <td>
                      <?php echo form_input('txFreq1[1][' . $transm_counter . ']', '', 'class="input-mini just-number" onchange="copyPaste(this, \'rxFreq2[1]\', ' . $transm_counter . ')" style="height:20px"') ?>
                    </td>
                    <td>
                      <?php echo form_input('rxFreq1[1][' . $transm_counter . ']', '', 'class="input-mini just-number" onchange="copyPaste(this, \'txFreq2[1]\', ' . $transm_counter . ')" style="height:20px"') ?>
                    </td>
                    <td>
                      <?php
                      $options = array(0 => '- Choose one -');
                      $lookup = $this->core->get_lookup('transmission_polarization');
                      foreach ($lookup as $lo) {
                        $options[$lo['id']] = $lo['desc'];
                      }
                      echo @form_dropdown('polarization1[1][' . $transm_counter . ']', $options)
                      ?>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td><a class="btn btn-success btn-small" href="javascript:void(0)" onclick="TRANSMISSION.addSubTableRow(<?php echo $transm_counter ?>)" title="Add sub table row"><i class="icon-plus-sign icon-white"></i></a></td>
                    <td colspan="3"></td>
                  </tr>
                </tfoot>
              </table>
            </td>
          </tr>
          <tr class="row-<?php echo $transm_counter ?>">
            <td>
              <?php
              echo form_hidden('id2[' . $transm_counter . ']', '');
              ?>
              <div class="input-append">
                <?php echo @form_input('siteID2Code[' . $transm_counter . ']', '', 'class="input-medium site-id-fe-code" readonly="readonly"') ?>
                <?php echo form_hidden('siteID2[' . $transm_counter . ']') ?>
                <button type="button" class="btn" onclick="doLookupSiteTransmission('siteID2Code[<?php echo $transm_counter ?>]', 'siteID2[<?php echo $transm_counter ?>]', <?php echo $transm_counter ?>)"><i class="icon-search"></i></button>
              </div>
            </td>
            <td>
              <?php echo @form_input('pathLength2[' . $transm_counter . ']', '', 'class="input-mini number"') ?>
            </td>
            <td>
              <?php echo @form_input('linkID2[' . $transm_counter . ']', '', 'class="input-medium link-id-2"') ?>
            </td>
            <td>
              <?php
              $options = array(0 => '- Choose one -');
              $lookup = $this->core->get_lookup('transmission_link_type');
              foreach ($lookup as $lo) {
                $options[$lo['id']] = $lo['desc'];
              }
              echo @form_dropdown('linkType2[' . $transm_counter . ']', $options);
              ?>
            </td>
            <td>
              <?php echo form_input('freqUsed2[' . $transm_counter . ']', '', 'class="input-mini just-number freq-used"') ?>
            </td>
            <td>
              <?php
              $lookup = $this->core->get_lookup('transmission_config_hop');
              $options = array(0 => '- Choose one -');
              foreach ($lookup as $lo) {
                $options[$lo['id']] = $lo['desc'];
              }
              echo form_dropdown('config2[' . $transm_counter . ']', $options)
              ?>
            </td>
            <td>
              <?php echo @form_input('power2[' . $transm_counter . ']', '', 'class="input-mini number" readonly="readonly"') ?>
            </td>
            <td>
              <?php echo @form_input('ipAddress2[' . $transm_counter . ']', '', 'class="input-small ipaddress"') ?>
            </td>
            <td>
              <?php echo @form_input('mainAntModel2[' . $transm_counter . ']', '', 'class="input-medium"') ?>
            </td>
            <td>
              <?php echo @form_input('mainAntHeight2[' . $transm_counter . ']', '', 'class="input-mini number"') ?>
            </td>
            <td>
              <?php
              $options = array(0 => '- Choose one -');
              $lookup = get_instance()->core->get_lookup('antenna_diameter');
              foreach ($lookup as $lo) {
                $options[$lo['id']] = $lo['desc'];
              }
              echo @form_dropdown('mainAntDiameter2[' . $transm_counter . ']', $options)
              ?>
            </td>
            <td>
              <?php echo @form_input('sdAntModel2[' . $transm_counter . ']', '', 'class="input-medium"') ?>
            </td>
            <td>
              <?php echo @form_input('sdAntHeight2[' . $transm_counter . ']', '', 'class="input-mini number"') ?>
            </td>
            <td>
              <?php
              $options = array(0 => '- Choose one -');
              $lookup = get_instance()->core->get_lookup('antenna_diameter');
              foreach ($lookup as $lo) {
                $options[$lo['id']] = $lo['desc'];
              }
              echo @form_dropdown('sdAntDiameter2[' . $transm_counter . ']', $options)
              ?>
            </td>
            <td>
              <?php echo @form_input('azimuth2[' . $transm_counter . ']', '', 'class="input-mini number"') ?>
            </td>
            <td>
              <?php
              $options = array(0 => '- Choose one -');
              $lookup = $this->core->get_lookup('equipment_model');
              foreach ($lookup as $lo) {
                $options[$lo['id']] = $lo['desc'];
              }
              echo @form_dropdown('eqModel2[' . $transm_counter . ']', $options, '');
              ?>
            </td>
            <td>
              <?php echo @form_input('reqCapacity2[' . $transm_counter . ']', '', 'class="input-mini" readonly="readonly"') ?>
            </td>
            <td>
              <?php echo @form_input('instCapacity2[' . $transm_counter . ']', '', 'class="input-mini" readonly="readonly"') ?>
            </td>
            <td>
              <?php echo @form_input('usedCapacity2[' . $transm_counter . ']', '', 'class="input-mini" readonly="readonly"') ?>
            </td>
            <td>
              <?php echo @form_input('treshold2[' . $transm_counter . ']', '', 'class="input-mini number" readonly="readonly"') ?>
            </td>
            <td>
              <?php echo @form_input('planRSL2[' . $transm_counter . ']', '', 'class="input-mini number" readonly="readonly"') ?>
            </td>
            <td>
              <?php echo @form_input('actualRSL2[' . $transm_counter . ']', '', 'class="input-mini number"') ?>
            </td>
            <td>
              <?php echo @form_input('fm2[' . $transm_counter . ']', '', 'class="input-mini number"') ?>
            </td>
            <td>
              <?php echo @form_input('availability2[' . $transm_counter . ']', '', 'class="input-mini percent" readonly="readonly"') ?>
            </td>
          </tr>
          <tr class="row-<?php echo $transm_counter ?>">
            <td colspan="24">
              <table class="table table-bordered table-condensed sub_table" style="width:auto; margin-left:0px;">
                <tr style="background:#f8f8f8">
                  <th width="35"></th>
                  <th>Tx Freq</th>
                  <th>Rx Freq</th>
                  <th>Polarization</th>
                </tr>
                <tbody id="sub_table_1_2">
                  <tr <tr id="sub_table_2_row_1">
                    <td></td>
                    <td>
                      <?php echo form_input('txFreq2[1][' . $transm_counter . ']', '', 'class="input-mini just-number" style="height:20px"') ?>
                    </td>
                    <td>
                      <?php echo form_input('rxFreq2[1][' . $transm_counter . ']', '', 'class="input-mini just-number" style="height:20px"') ?>
                    </td>
                    <td>
                      <?php
                      $options = array(0 => '- Choose one -');
                      $lookup = $this->core->get_lookup('transmission_polarization');
                      foreach ($lookup as $lo) {
                        $options[$lo['id']] = $lo['desc'];
                      }
                      echo @form_dropdown('polarization2[1][' . $transm_counter . ']', $options)
                      ?>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr id="transmission-separator-<?php echo $transm_counter ?>" class="row-<?php echo $transm_counter ?>">
            <td colspan="28"><i></i></td>
          </tr>

          <?php
        }
        ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="28" class="left"><a class="btn btn-success btn-small" href="javascript:void(0)" onclick="TRANSMISSION.addRow()" title="Add row"><i class="icon-plus-sign icon-white"></i></a></td>
          </tr>
        </tfoot>
      </table>
    </div>

  </div>
</div>

<div class="form-actions">
  <button class="btn btn-primary btn-icon glyphicons circle_ok"><i></i> Save</button>
  <button type="button" class="btn btn-icon btn-close btn-default glyphicons circle_remove" onclick="window.location.href = '<?php echo site_url('/site_info/equipment') ?>'"><i></i>Cancel</button>
</div>

<?php echo form_close() ?>

<script>
              var copyPaste = function(currentElem, secondElem, counter) {
                document.getElementsByName(secondElem + '[' + counter + ']')[0].value = currentElem.value;
                numberFormat.init();
                percentFormat.init();
              };

              var generateLinkId = function() {
                var siteIdNE = document.getElementsByClassName('site-id-ne-code');
                var siteIdFE = document.getElementsByClassName('site-id-fe-code');
                var linkId1 = document.getElementsByClassName('link-id-1');
                var linkId2 = document.getElementsByClassName('link-id-2');

                for (var i = 0; i < siteIdNE.length; i++) {
                  if (siteIdNE[i].value != '' && siteIdFE[i].value != '') {
                    linkId1[i].value = siteIdNE[i].getAttribute('data-vendor-id') + '#' + siteIdFE[i].getAttribute('data-vendor-id') + '#1';
                    linkId2[i].value = siteIdFE[i].getAttribute('data-vendor-id') + '#' + siteIdNE[i].getAttribute('data-vendor-id') + '#1';
                  }
                }
              };

              document.getElementById('frmTransmission').onsubmit = function() {

                var data = serialize(this);
                data += '&siteId=' + document.getElementsByName('siteId')[0].value;

<?php if ($this->uri->segment(3) == 'add') { ?>
                  data += '&cmd=ADD_TRANSMISSION';
<?php } else { ?>
                  data += '&cmd=EDIT_TRANSMISSION';
<?php } ?>

                $.ajax({
                  url: document.URL,
                  type: 'POST',
                  data: data,
                  dataType: 'json',
                  beforeSend: function() {
                    siteOverlay.top = '200px';
                    siteOverlay.create();
                  },
                  success: function(json) {
                    if (typeof json.loggedin != 'undefined' && !json.loggedin) {
                      siteOverlay.remove();
                      alert(json.message);
                      window.location.href = json.redirect_url;
                    }

                    var messageAlert = document.getElementsByClassName('alert');
                    for (var i = 0; i < messageAlert.length; i++) {
                      messageAlert[i].parentNode.removeChild(messageAlert[i]);
                    }

                    if (typeof json.error != 'undefined' && json.error) {
                      var message = '<h3>Warning</h3>\
                                        <ul style="text-align:left">';
                      for (var i = 0; i < json['message'].length; i++) {
                        message += '<li>' + json['message'][i] + '</li>';
                      }
                      message += '</ul>';

                      coolNotification(message, 'error');
                      document.getElementById('notyfy_container_center').style.top = '100px';
                    } else {
                      coolNotification(json['message'], 'success', function() {
                        window.location.href = equipmentURL;
                      });

                      // refresh content tab
                      var tab = $('#equipmentTab').tabs('getSelected');
                      $('#equipmentTab').tabs('update', {
                        tab: tab,
                        options: {
                          href: document.URL + '?load_content=TRANSMISSION&site_id=' + document.getElementsByName('siteId')[0].value
                        }
                      });
                    }
                  },
                  error : function () {
                    coolNotification('<h3>Error!</h3> An error occured, please try again or contact the administrator.', 'error');
                  },
                  complete: function() {
                    siteOverlay.remove();
                  }
                });

                window.scroll(0, 0);

                return false;
              };

<?php if (get_instance()->uri->segment(3) == 'edit' && count($transmission) > 0) { ?>
                var mwCounter = <?php echo $transm_counter ?>;
                var subTableRowCunter = <?php echo $sub_transm_counter ?>;
<?php } else { ?>
                var mwCounter = 1;
                var subTableRowCunter = 1;
<?php } ?>

              var TRANSMISSION = {
                addRow: function() {
                  mwCounter++;
                  var html = '\
                        <tr id="transmission-row-' + mwCounter + '" class="row-' + mwCounter + '">\
                                <td class="right" rowspan="4" style="vertical-align:middle"><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="TRANSMISSION.removeRow(' + mwCounter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>\
                                <td>\
                                        <input type="hidden" name="pairID[' + mwCounter + ']" />\
                                        <input type="hidden" name="id1[' + mwCounter + ']" />\
                                        <input type="text" name="siteID1Code[' + mwCounter + ']" class="input-medium site-id-ne-code" readonly="readonly" />\
                                        <input type="hidden" name="siteID1[' + mwCounter + ']" />\
                                </td>\
                                <td>\
                                        <input type="text" name="pathLength1[' + mwCounter + ']" class="input-mini number" />\
                                </td>\
                                <td>\
                                        <input type="text" name="linkID1[' + mwCounter + ']" class="input-medium link-id-1" />\
                                </td>\
                                <td>\
<?php
$lookup = $this->core->get_lookup('transmission_link_type');
?>\
                                        <select name="linkType1[' + mwCounter + ']">\
                                                <option value="0">- Choose one -</option>\
<?php foreach ($lookup as $lo) { ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                                        </select>\
                                </td>\
                                <td>\
                                        <input type="text" name="freqUsed1[' + mwCounter + ']" class="input-mini just-number freq-used" />\
                                </td>\
                                <td>\
<?php
$lookup = $this->core->get_lookup('transmission_config_hop');
?>\
                                        <select name="config1[' + mwCounter + ']">\
                                                <option value="0">- Choose one -</option>\
<?php foreach ($lookup as $lo) { ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                                        </select>\
                                </td>\
                                <td>\
                                        <input type="text" name="power1[' + mwCounter + ']" class="input-mini number" onchange="copyPaste(this, \'power2\', ' + mwCounter + ')" />\
                                </td>\
                                <td>\
                                        <input type="text" name="ipAddress1[' + mwCounter + ']" class="input-small ipaddress" />\
                                </td>\
                                <td>\
                                        <input type="text" name="mainAntModel1[' + mwCounter + ']" class="input-medium" />\
                                </td>\
                                <td>\
                                        <input type="text" name="mainAntHeight1[' + mwCounter + ']" class="input-mini number" />\
                                </td>\
                                <td>\
<?php $lookup = get_instance()->core->get_lookup('antenna_diameter') ?>\
                                        <select name="mainAntDiameter1[' + mwCounter + ']">\
                                                <option value="0">- Choose one -</option>\
<?php foreach ($lookup as $lo) { ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                                        </select>\
                                </td>\
                                <td>\
                                        <input type="text" name="sdAntModel1[' + mwCounter + ']" class="input-medium" />\
                                </td>\
                                <td>\
                                        <input type="text" name="sdAntHeight1[' + mwCounter + ']" class="input-mini number" />\
                                </td>\
                                <td>\
<?php $lookup = get_instance()->core->get_lookup('antenna_diameter') ?>\
                                        <select name="sdAntDiameter1[' + mwCounter + ']">\
                                                <option value="0">- Choose one -</option>\
<?php foreach ($lookup as $lo) { ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                                        </select>\
<?php $lookup = get_instance()->core->get_lookup('antenna_diameter') ?>\
                                </td>\
                                <td>\
                                        <input type="text" name="azimuth1[' + mwCounter + ']" class="input-mini number" />\
                                </td>\
                                <td>\
                                        <select name="eqModel1[' + mwCounter + ']">\
                                                <option value="0">- Choose one -</option>\
<?php
$options = array(0 => '- Choose one -');
$lookup = $this->core->get_lookup('equipment_model');
foreach ($lookup as $lo) {
  ?>\
                                                                                              <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
  <?php
}
?>\
                                        </select>\
                                </td>\
                                <td>\
                                        <input type="text" name="reqCapacity1[' + mwCounter + ']" class="input-mini" onchange="copyPaste(this, \'reqCapacity2\', ' + mwCounter + ')" />\
                                </td>\
                                <td>\
                                        <input type="text" name="instCapacity1[' + mwCounter + ']" class="input-mini" onchange="copyPaste(this, \'instCapacity2\', ' + mwCounter + ')" />\
                                </td>\
                                <td>\
                                        <input type="text" name="usedCapacity1[' + mwCounter + ']" class="input-mini" onchange="copyPaste(this, \'usedCapacity2\', ' + mwCounter + ')" />\
                                </td>\
                                <td>\
                                        <input type="text" name="treshold1[' + mwCounter + ']" class="input-mini number" onchange="copyPaste(this, \'treshold2\', ' + mwCounter + ')" />\
                                </td>\
                                <td>\
                                        <input type="text" name="planRSL1[' + mwCounter + ']" class="input-mini number" onchange="copyPaste(this, \'planRSL2\', ' + mwCounter + ')" />\
                                </td>\
                                <td>\
                                        <input type="text" name="actualRSL1[' + mwCounter + ']" class="input-mini number" />\
                                </td>\
                                <td>\
                                        <input type="text" name="fm1[' + mwCounter + ']" class="input-mini number" />\
                                </td>\
                                <td>\
                                        <input type="text" name="availability1[' + mwCounter + ']" class="input-mini percent" onchange="copyPaste(this, \'availability2\', ' + mwCounter + ')" />\
                                </td>\
                        </tr>\
                        <tr class="row-' + mwCounter + '">\
                          <td colspan="24">\
                            <table class="table table-bordered table-condensed  sub_table" style="width:auto; margin-left:0px;">\
                              <thead>\
                              <tr style="background:#f8f8f8">\
                                <th width="35"></th><th>Tx Freq</th><th>Rx Freq</th><th>Polarization</th>\
                              </tr>\
                              </thead>\
                              <tbody id="sub_table_' + mwCounter + '_1">\
                                <tr>\
                                 <td></td>\
                                 <td>\
                                  <input type="text" name="txFreq1[' + mwCounter + '][' + subTableRowCunter + ']" onchange="copyPaste(this, \'rxFreq2[' + mwCounter + ']\', ' + subTableRowCunter + ')" value="" class="input-mini just-number" style="height:20px" autocomplete="off">\
                                 </td>\
                                 <td>\
                                  <input type="text" name="rxFreq1[' + mwCounter + '][' + subTableRowCunter + ']" onchange="copyPaste(this, \'txFreq2[' + mwCounter + ']\', ' + subTableRowCunter + ')" value="" class="input-mini just-number" style="height:20px" autocomplete="off">\
                                 </td>\
                                 <td>\
                                   <select name="polarization1[' + mwCounter + '][' + subTableRowCunter + ']">\
                                                <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('transmission_polarization');
foreach ($lookup as $lo) {
  ?>\
                                                                            <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
  <?php
}
?>\
                                        </select>\
                                 </td>\
                                </tr>\
                              </tbody>\
                              <tfoot>\
                                <tr>\
                                  <td><a class="btn btn-success btn-small" href="javascript:void(0)" onclick="TRANSMISSION.addSubTableRow(' + mwCounter + ')" title="Add sub table row"><i class="icon-plus-sign icon-white"></i></a></td>\
                                  <td colspan="3"></td>\
                                </tr>\
                              </tfoot>\
                            </table>\
                           </td>\
                        </tr>\
                        <tr class="row-' + mwCounter + '">\
                                <td>\
                                        <input type="hidden" name="id2[' + mwCounter + ']" />\
                                        <div class="input-append">\
                                                <input type="text" name="siteID2Code[' + mwCounter + ']" class="input-medium site-id-fe-code" readonly="readonly" />\
                                                <input type="hidden" name="siteID2[' + mwCounter + ']" />\
                                                <button type="button" class="btn" onclick="doLookupSiteTransmission(\'siteID2Code[' + mwCounter + ']\', \'siteID2[' + mwCounter + ']\', 1)"><i class="icon-search"></i></button>\
                                        </div>\
                                </td>\
                                <td>\
                                        <input type="text" name="pathLength2[' + mwCounter + ']" class="input-mini number" />\
                                </td>\
                                <td>\
                                        <input type="text" name="linkID2[' + mwCounter + ']" class="input-medium link-id-2" />\
                                </td>\
<?php $lookup = $this->core->get_lookup('transmission_link_type'); ?>\
                                <td>\
                                        <select name="linkType2[' + mwCounter + ']">\
                                                <option value="0">- Choose one -</option>\
<?php foreach ($lookup as $lo) { ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                                        </select>\
                                </td>\
                                <td>\
                                        <input type="text" name="freqUsed2[' + mwCounter + ']" class="input-mini just-number freq-used" />\
                                </td>\
<?php $lookup = $this->core->get_lookup('transmission_config_hop'); ?>\
                                <td>\
                                        <select name="config2[' + mwCounter + ']">\
                                                <option value="0">- Choose one -</option>\
<?php foreach ($lookup as $lo) { ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                                        </select>\
                                </td>\
                                <td>\
                                        <input type="text" name="power2[' + mwCounter + ']" class="input-mini number" readonly="readonly" />\
                                </td>\
                                <td>\
                                        <input type="text" name="ipAddress2[' + mwCounter + ']" class="input-small ipaddress" />\
                                </td>\
                                <td>\
                                        <input type="text" name="mainAntModel2[' + mwCounter + ']" class="input-medium" />\
                                </td>\
                                <td>\
                                        <input type="text" name="mainAntHeight2[' + mwCounter + ']" class="input-mini number" />\
                                </td>\
<?php $lookup = get_instance()->core->get_lookup('antenna_diameter') ?>\
                                <td>\
                                        <select name="mainAntDiameter2[' + mwCounter + ']">\
                                                <option value="0">- Choose one -</option>\
<?php foreach ($lookup as $lo) { ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                                        </select>\
                                </td>\
                                <td>\
                                        <input type="text" name="sdAntModel2[' + mwCounter + ']" class="input-medium" />\
                                </td>\
                                <td>\
                                        <input type="text" name="sdAntHeight2[' + mwCounter + ']" class="input-mini number" />\
                                </td>\
<?php $lookup = get_instance()->core->get_lookup('antenna_diameter') ?>\
                                <td>\
                                        <select name="sdAntDiameter2[' + mwCounter + ']">\
                                                <option value="0">- Choose one -</option>\
<?php foreach ($lookup as $lo) { ?>\
                                                                                      <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
<?php } ?>\
                                        </select>\
                                </td>\
                                <td>\
                                        <input type="text" name="azimuth2[' + mwCounter + ']" class="input-mini number" />\
                                </td>\
                                <td>\
                                        <select name="eqModel2[' + mwCounter + ']">\
                                                <option value="0">- Choose one -</option>\
<?php
$options = array(0 => '- Choose one -');
$lookup = $this->core->get_lookup('equipment_model');
foreach ($lookup as $lo) {
  ?>\
                                                                                              <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
  <?php
}
?>\
                                        </select>\
                                </td>\
                                <td>\
                                        <input type="text" name="reqCapacity2[' + mwCounter + ']" class="input-mini" readonly="readonly" />\
                                </td>\
                                <td>\
                                        <input type="text" name="instCapacity2[' + mwCounter + ']" class="input-mini" readonly="readonly" />\
                                </td>\
                                <td>\
                                        <input type="text" name="usedCapacity2[' + mwCounter + ']" class="input-mini" readonly="readonly" />\
                                </td>\
                                <td>\
                                        <input type="text" name="treshold2[' + mwCounter + ']" class="input-mini number" readonly="readonly" />\
                                </td>\
                                <td>\
                                        <input type="text" name="planRSL2[' + mwCounter + ']" class="input-mini number" readonly="readonly" />\
                                </td>\
                                <td>\
                                        <input type="text" name="actualRSL2[' + mwCounter + ']" class="input-mini number" />\
                                </td>\
                                <td>\
                                        <input type="text" name="fm2[' + mwCounter + ']" class="input-mini number" />\
                                </td>\
                                <td>\
                                        <input type="text" name="availability2[' + mwCounter + ']" class="input-mini percent" readonly="readonly" />\
                                </td>\
                        </tr>\
                        <tr class="row-' + mwCounter + '">\
                          <td colspan="24">\
                            <table class="table table-bordered table-condensed sub_table" style="width:auto; margin-left:0px;">\
                              <thead>\
                              <tr style="background:#f8f8f8">\
                                <th width="35"></th><th>Tx Freq</th><th>Rx Freq</th><th>Polarization</th>\
                              </tr>\
                              </thead>\
                              <tbody id="sub_table_' + mwCounter + '_2">\
                                <tr>\
                                 <td></td>\
                                 <td>\
                                  <input type="text" name="txFreq2[' + mwCounter + '][' + subTableRowCunter + ']" value="" class="input-mini just-number" style="height:20px" autocomplete="off">\
                                 </td>\
                                 <td>\
                                  <input type="text" name="rxFreq2[' + mwCounter + '][' + subTableRowCunter + ']" value="" class="input-mini just-number" style="height:20px" autocomplete="off">\
                                 </td>\
                                 <td>\
                                   <select name="polarization2[' + mwCounter + '][' + subTableRowCunter + ']">\
                                                <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('transmission_polarization');
foreach ($lookup as $lo) {
  ?>\
                                                                              <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
  <?php
}
?>\
                                        </select>\
                                 </td>\
                                </tr>\
                              </tbody>\
                            </table>\
                           </td>\
                        </tr>\
                        <tr id="transmission-separator-' + mwCounter + '" class="row-' + mwCounter + '">\
                                <td colspan="28"><i></i></td>\
                        </tr>';

                  $('#main-body').append(html);

                  numberFormat.init();
                  justNumberFormat.init();
                  percentFormat.init();
                  loadToUpperCase();
                  clearAutocomplete();
                  //farEnd.init();
                  uneditable.init();
                  IPAddress.init();
                  copyNearEnd();
                  funnyTextarea();
                  defaultValueFreqUsed();
                },
                addSubTableRow: function(row) {
                  subTableRowCunter++;
                  var subTableRow1 = '<tr id="sub_table_1_row_' + subTableRowCunter + '"><td><a class="btn btn-danger btn-small" href="javascript:void(0)" onclick="TRANSMISSION.removeSubTableRow(' + subTableRowCunter + ')" title="Remove row"><i class="icon-minus-sign icon-white"></i></a></td>\
                        <td><input type="text" name="txFreq1[' + row + '][' + subTableRowCunter + ']" value="" class="input-mini just-number" onchange="copyPaste(this, \'rxFreq2[' + row + ']\', ' + subTableRowCunter + ')" style="height:20px" autocomplete="off"></td>\
                        <td><input type="text" name="rxFreq1[' + row + '][' + subTableRowCunter + ']" value="" class="input-mini just-number" onchange="copyPaste(this, \'txFreq2[' + row + ']\', ' + subTableRowCunter + ')" style="height:20px" autocomplete="off"></td>\
                        <td><select name="polarization1[' + row + '][' + subTableRowCunter + ']">\
                              <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('transmission_polarization');
foreach ($lookup as $lo) {
  ?>\
                                            <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
  <?php
}
?>\
                            </select></td>\
                        </tr>';
                  $('#sub_table_' + row + '_1').append(subTableRow1);

                  var subTableRow2 = '<tr id="sub_table_2_row_' + subTableRowCunter + '"><td></td>\
                        <td><input type="text" name="txFreq2[' + row + '][' + subTableRowCunter + ']" value="" class="input-mini just-number" onchange="copyPaste(this, \'rxFreq1[' + mwCounter + ']\', ' + subTableRowCunter + ')" style="height:20px" autocomplete="off"></td>\
                        <td><input type="text" name="rxFreq2[' + row + '][' + subTableRowCunter + ']" value="" class="input-mini just-number" onchange="copyPaste(this, \'txFreq1[' + mwCounter + ']\', ' + subTableRowCunter + ')" style="height:20px" autocomplete="off"></td>\
                        <td><select name="polarization2[' + row + '][' + subTableRowCunter + ']">\
                              <option value="0">- Choose one -</option>\
<?php
$lookup = $this->core->get_lookup('transmission_polarization');
foreach ($lookup as $lo) {
  ?>\
                                            <option value="<?php echo $lo['id'] ?>"><?php echo $lo['desc'] ?></option>\
  <?php
}
?>\
                            </select></td>\
                        </tr>';
                  $('#sub_table_' + row + '_2').append(subTableRow2);

                  justNumberFormat.init();
                },
                removeRow: function(row) {
                  var mwRowElem = document.getElementById('transmission-row-' + row);
                  var mwSeparatorElem = document.getElementById('transmission-separator-' + row);

                  coolConfirmation('<h4>Confirmation</h4>Are you sure you want to delete?', function() {
                    mwRowElem.parentNode.removeChild(mwRowElem);
                    mwSeparatorElem.parentNode.removeChild(mwSeparatorElem);
                    $('.row-' + row).remove();
                  });
                },
                removeSubTableRow: function(row) {
                  coolConfirmation('<h4>Confirmation</h4>Are you sure you want to delete?', function() {
                    $('#sub_table_1_row_' + row).remove();
                    $('#sub_table_2_row_' + row).remove();
                    //subTableRowCunter--;
                  });
                }
              };

              var doLookupSiteTransmission = function(elem1, elem2, elemIdx) { // elem1: shown element, elem2: hidden element, elemIdx: index of element
                var url = '<?php echo site_url('/site_info') ?>';

                $.fancybox({
                  href: url,
                  type: 'ajax',
                  ajax: {data: {cmd: 'GET_LOOKUP_SITE', e: elem2}}, // e: it used for selected element
                  width: 600,
                  scrolling: false,
                  autoSize: true,
                  fitToView: true,
                  closeClick: false,
                  openEffect: 'fade',
                  closeEffect: 'fade',
                  afterShow: function() {
                    document.getElementById('btnSiteSelectedOK').onclick = function() {
                      var row = $('#dg-sites').datagrid('getSelected');

                      if (row != null) {
                        document.getElementsByName(elem1)[0].value = row.site_code;
                        document.getElementsByName(elem1)[0].setAttribute('data-vendor-id', row.vendor_id);
                        document.getElementsByName(elem2)[0].value = row.site_id;

                        generateLinkId();

                        // generate Link ID
                        /*
                         $.ajax({
                         url: '<?php echo site_url('/site_info/equipment') ?>',
                         method: 'GET',
                         data: { cmd: 'GET_LINKID', nearend: document.getElementsByName('siteId')[0].value, farend: row.site_id },
                         dataType: 'json',
                         success: function(json) {
                         document.getElementsByName('linkId[' + elemIdx + ']')[0].value = json.link_id;
                         }
                         });
                         */
                      }

                      $.fancybox.close();
                    };
                  }
                });
              };

              var funnyTextarea = function() {
                var textarea = document.getElementsByClassName('funny-textarea');
                for (var i = 0; i < textarea.length; i++) {
                  textarea[i].style.height = '20px';
                  textarea[i].style.overflowY = 'hidden';

                  textarea[i].onfocus = function() {
                    $(this).hide();
                    this.style.height = '100px';
                    this.style.overflowY = 'auto';
                    this.className = 'input-medium funny-textarea';
                    $(this).fadeIn('slow');
                  };

                  textarea[i].onblur = function() {
                    this.style.height = '20px';
                    this.style.overflowY = 'hidden';
                    this.className = 'input-mini funny-textarea';
                  };
                }
              };

              defaultValueFreqUsed = function() {
                var freqUsed = document.getElementsByClassName('freq-used');
                for (var i = 0; i < freqUsed.length; i++) {
                  if (freqUsed[i].value == 0) {
                    freqUsed[i].value = 1;
                  }
                }
              };

              loadToUpperCase();
              clearAutocomplete();
              numberFormat.init();
              justNumberFormat.init();
              percentFormat.init();
              uneditable.init();
              IPAddress.init();
              copyNearEnd();
              funnyTextarea();
              doPreviledgePerfield();
              defaultValueFreqUsed();
</script>