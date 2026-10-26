<?php error_reporting(E_ALL ^ E_NOTICE) ?>

<div class="form-horizontal">

  <div class="widget">
    <div class="widget-head">
      <h4 class="heading">General</h4>
    </div>

    <div class="widget-body">
      <div class="row-fluid">
        <div class="span6">
          <div class="control-group form-inline" id="form_site_id">
            <label class="control-label" for="siteIdText">Site ID:</label>
            <div class="controls">
              <span class="input-label"><?php echo $general_info['site_code'] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>			

        <div class="span6">
          <div class="control-group" id="form_site_profile">
            <label class="control-label" for="siteProfile">Site Profile:</label>
            <div class="controls">
              <?php $options = $this->core->get_lookup_options('site_profile') ?>
              <span class="input-label"><?php echo @$options[$general_info['site_profile']] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>
      </div>

      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_site_id_ref">
            <label class="control-label" for="oldSiteId">Site ID Ref.:</label>
            <div class="controls">
              <span class="input-label"><?php echo $general_info['site_code_old'] ?> </span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>

        <div class="span6">
          <div class="control-group" id="form_site_type">
            <label class="control-label" for="siteType">Site Type:</label>
            <div class="controls">
              <?php $options = $this->core->get_lookup_options('site_type') ?>
              <span class="input-label"><?php echo @$options[$general_info['site_type']] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>
      </div>

      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_site_name">
            <label class="control-label" for="siteName">Site Name:</label>
            <div class="controls">
              <span class="input-label"><?php echo $general_info['site_name'] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>

        <div class="span6">
          <div class="control-group" id="form_site_specification">
            <label class="control-label" for="siteSpecification">Site Specification:</label>
            <div class="controls">
              <?php $options = $this->core->get_lookup_options('site_specification') ?>
              <span class="input-label"><?php echo @$options[$general_info['site_specification']] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>
      </div>

      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_vendor_id">
            <label class="control-label" for="vendorId">Vendor ID:</label>
            <div class="controls">
              <span class="input-label"><?php echo $general_info['vendor_id'] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>

        <div class="span6">
          <div class="control-group" id="form_actual_latlong">
            <label class="control-label" for="actualLatitude">Actual Lat - Long:</label>
            <div class="controls">
              <span class="input-label"><?php echo $general_info['latitude'] . ' | ' . $general_info['longitude'] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>
      </div>

      <div class="row-fluid">
        <div class="span12">
          <div class="control-group" id="form_vendor_name">
            <label class="control-label" for="vendorName">Vendor Name:</label>
            <div class="controls">
              <span class="input-label"><?php echo $general_info['vendor_name'] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="widget">
    <div class="widget-head">
      <h4 class="heading">Tower Provider</h4>
    </div>

    <div class="widget-body">

      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_tp_name">
            <label class="control-label" for="tpName">TP Name:</label>
            <div class="controls">
              <?php
              $options = array();
              $lookup = $this->db
                      //->query("SELECT tp_company_name FROM t_mtr_tower_provider WHERE active = 1 GROUP BY tp_company_name ORDER BY tp_company_name")
                      ->query("SELECT company_id, company_name FROM t_mtr_tp_company ORDER BY company_name ASC")
                      ->result_array();
              $options = array(0 => '- Choose one -');
              foreach ($lookup as $lo) {
                $options[$lo['company_id']] = $lo['company_name'];
              }
              ?>
              <span class="input-label"><?php echo @$options[$general_info['tp_name']] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>

        <div class="span6">
          <div class="control-group" id="form_tp_site_id">
            <label class="control-label" for="tpId">TP Site ID:</label>
            <div class="controls">
              <?php
              $row = $this->core->view_row('t_mtr_tower_provider', array('tp_id' => $general_info['tp_id']));
              ?>
              <span class="input-label"><?php echo $row['tp_code'] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>
      </div>

      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_tp_name_group">
            <label class="control-label" for="tpNameGroup">TP Name Group:</label>
            <div class="controls">
              <span class="input-label"><?php echo $general_info['tp_name_group'] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>

        <div class="span6">
          <div class="control-group" id="form_site_category">
            <label class="control-label" for="siteCategory">Site Category:</label>
            <div class="controls">
              <?php
              $options = get_instance()->core->get_lookup_options('tp_category');

              $row = $this->core->view_row('t_mtr_lookup', array('lookup_key' => 'tp_category', 'lookup_value' => $general_info['site_category'], 'active' => 1));
              ?>
              <span class="input-label"><?php echo $row['lookup_desc'] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>
      </div>

      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_tp_kabupaten">
            <label class="control-label" for="tpKabupaten">Kabupaten:</label>
            <div class="controls">
              <?php
              $kab = $this->db
                      ->query("
									SELECT lok.nama AS kabupaten
									FROM t_mtr_tower_provider tp
									LEFT JOIN t_mtr_lokasi lok ON lok.lokasi_id = tp.city_id AND UPPER(lok.jenis) = 'KB'
									WHERE tp.company_id = " . (int) @$general_info['tp_name'] . " AND lok.nama IS NOT NULL
									LIMIT 1
								")
                      ->row_array();
              ?>
              <span id="tpKabupaten" class="input-label"><?php echo @$kab['kabupaten'] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="widget">
    <div class="widget-head">
      <h4 class="heading">Address</h4>
    </div>

    <div class="widget-body">

      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_site_address">
            <label class="control-label" for="siteAddress">Site Address:</label>
            <div class="controls">
              <span class="input-label"><?php echo $general_info['site_address'] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>				
        </div>
        <div class="span6"></div>
      </div>

      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_province">
            <label class="control-label" for="propinsi">Province:</label>
            <div class="controls">
              <?php
              $propinsi = array();
              $dataPropinsi = get_instance()->core->view_all('t_mtr_lokasi', array('jenis' => 'P'), 'nama');
              foreach ($dataPropinsi as $prop) {
                $propinsi[$prop['lokasi_id']] = $prop['nama'];
              }
              ?>
              <span class="input-label"><?php echo @$propinsi[$general_info['propinsi_id']] ?></span>
            </div>							
            <div class="separator bottom"></div>
          </div>
        </div>

        <div class="span6">
          <div class="control-group" id="form_island">
            <label class="control-label" for="island">Island:</label>
            <div class="controls">
              <?php
              $island = $this->db
                      ->query("
								SELECT pul.island_name
								FROM t_mtr_lokasi lok
								LEFT JOIN t_mtr_lokasi_pulau pul ON pul.island_id = lok.island_id AND pul.active = 1
								WHERE lok.jenis = 'P' AND lok.lokasi_id = " . (int) @$general_info['propinsi_id']
                      )
                      ->row_array();
              ?>
              <span id="island" class="input-label"><?php echo @$island['island_name'] ?></span>
            </div>							
            <div class="separator bottom"></div>
          </div>
        </div>			
      </div>

      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_kabupaten">
            <label class="control-label" for="kabupaten">Kabupaten:</label>
            <div class="controls">
              <?php
              $kabupaten = array();
              $dataKabupaten = @get_instance()->core->view_all('t_mtr_lokasi', array('jenis' => 'KB'), 'nama');
              foreach ($dataKabupaten as $kab) {
                $kabupaten[$kab['lokasi_id']] = $kab['nama'];
              }
              ?>
              <span id="island" class="input-label"><?php echo @$kabupaten[$general_info['kabupaten']] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>

        <div class="span6">
          <div class="control-group" id="form_kelurahan">
            <label class="control-label" for="kelurahan">Kelurahan:</label>
            <div class="controls">
              <span class="input-label"><?php echo $general_info['kelurahan'] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>			
      </div>

      <div class="row-fluid">			
        <div class="span6">
          <div class="control-group" id="form_kecamatan">
            <label class="control-label" for="kecamatan">Kecamatan:</label>
            <div class="controls">
              <?php
              $kecamatan = array();
              $dataKecamatan = @get_instance()->core->view_all('t_mtr_lokasi', array('jenis' => 'KC'), 'nama');
              foreach ($dataKecamatan as $kec) {
                $kecamatan[$kec['lokasi_id']] = $kec['nama'];
              }
              ?>
              <span id="island" class="input-label"><?php echo @$kecamatan[$general_info['kecamatan']] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>

        <div class="span6">
          <div class="control-group" id="form_kode_pos">
            <label class="control-label" for="kodePos">Kode Pos:</label>
            <div class="controls">
              <span id="island" class="input-label"><?php echo $general_info['kode_pos'] ?></span>
            </div>
          </div>
          <div class="separator bottom"></div>
        </div>
      </div>

    </div>
  </div>

  <div class="widget">
    <div class="widget-head">
      <h4 class="heading">Territory</h4>
    </div>

    <div class="widget-body">
      <div class="control-group" id="form_region">
        <label class="control-label" for="region">Region:</label>
        <div class="controls">
          <?php
          $my_region = array();
          foreach ($this->session->userdata['user_region'] as $reg) {
            if ($reg['region_id'] != '') {
              $my_region[] = $reg['region_id'];
            }
          }

          $regions = array();
          $dataRegions = get_instance()->db
                  ->query("SELECT region_id, region_name FROM t_mtr_region WHERE status = 1 AND region_id IN (" . implode(', ', $my_region) . ") ORDER BY region_name ASC")
                  ->result_array();
          foreach ($dataRegions as $region) {
            $regions[$region['region_id']] = $region['region_name'];
          }
          ?>
          <span id="island" class="input-label"><?php echo @$regions[$general_info['region_id']] ?></span>
        </div>
      </div>

      <div class="control-group" id="form_cluster_ops">
        <label class="control-label" for="clusterOpr">Operational Cluster:</label>
        <div class="controls">
          <?php
          $region_id = @get_instance()->core->view_row('t_mtr_cluster_ops', array('cluster_ops_id' => $general_info['cluster_ops_id']));

          $clusters = array();
          $dataCluster = @get_instance()->core->view_all('t_mtr_cluster_ops', array('region_id' => $region_id['region_id'], 'cluster_ops_id !=' => 0), 'cluster_name');
          foreach ($dataCluster as $cluster) {
            $clusters[$cluster['cluster_ops_id']] = $cluster['cluster_name'];
          }
          ?>
          <span id="island" class="input-label"><?php echo @$clusters[$general_info['cluster_ops_id']] ?></span>
        </div>
      </div>

      <div class="control-group" id="form_cluster_net">
        <label class="control-label" for="cluster">Network Cluster:</label>
        <div class="controls">
          <?php
          $region_id = @get_instance()->core->view_row('t_mtr_cluster_net', array('cluster_net_id' => $general_info['cluster_net_id']));

          $clusters = array();
          $dataCluster = get_instance()->core->view_all('t_mtr_cluster_net', array('region_id' => @$region_id['region_id'], 'cluster_net_id !=' => 0), 'cluster_name');
          foreach ($dataCluster as $cluster) {
            $clusters[$cluster['cluster_net_id']] = $cluster['cluster_name'];
          }
          ?>
          <span id="island" class="input-label"><?php echo @$clusters[$general_info['cluster_net_id']] ?></span>
        </div>
      </div>

      <div class="control-group" id="form_cluster_com">
        <label class="control-label" for="cluster">Commercial Cluster:</label>
        <div class="controls">
          <?php
          $region_id = @get_instance()->core->view_row('t_mtr_cluster_com', array('cluster_com_id' => $general_info['cluster_com_id']));

          $clusters = array();
          $dataCluster = get_instance()->core->view_all('t_mtr_cluster_com', array('region_id' => @$region_id['region_id'], 'cluster_com_id !=' => 0), 'cluster_name');
          foreach ($dataCluster as $cluster) {
            $clusters[$cluster['cluster_com_id']] = $cluster['cluster_name'];
          }
          ?>
          <span id="island" class="input-label"><?php echo @$clusters[$general_info['cluster_com_id']] ?></span>
        </div>
      </div>
    </div>
  </div>

  <div class="widget">
    <div class="widget-head">
      <h4 class="heading">Finance</h4>
    </div>

    <div class="widget-body">
      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_finance_contract_under">
            <label class="control-label" for="contractUnder">Contract Under:</label>
            <div class="controls">
              <?php $options = get_instance()->core->get_lookup_options('contract_under') ?>
              <span id="island" class="input-label"><?php echo @$options[$general_info['contract_under']] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>

        <div class="span6">
          <div class="control-group" id="form_finance_company">
            <label class="control-label" for="companyCode">Company:</label>
            <div class="controls">
              <?php
              $dataCompany = $this->core->get_lookup('company');
              $companies = array(0 => '');
              foreach ($dataCompany as $company) {
                $companies[$company['id']] = $company['desc'];
              }
              ?>
              <span id="island" class="input-label"><?php echo @$companies[$general_info['company_code']] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>
      </div>

      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_finance_cost_center">
            <label class="control-label" for="costCenter">Cost Center:</label>
            <div class="controls">
              <span id="island" class="input-label"><?php echo $general_info['cost_center'] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>

        <div class="span6">
          <div class="control-group" id="form_finance_price_lease">
            <label class="control-label" for="priceLease">Price Lease:</label>
            <div class="controls">
              <?php $options = $this->core->get_lookup_options('finance_period') ?>
              <span id="island" class="input-label">IDR <?php echo number_format($general_info['harga_sewa'], 2) . ' / ' . @$options[$general_info['price_lease_period']] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>
      </div>

      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_finance_lcd_start_date">
            <label class="control-label" for="lcdStartDate">LCD Start Date:</label>
            <div class="controls">
              <span id="island" class="input-label"><?php echo isset($general_info['lcd_start_date']) ? date('d-M-Y', strtotime($general_info['lcd_start_date'])) : '' ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>

        <div class="span6">
          <div class="control-group" id="form_finance_lcd_end_date">
            <label class="control-label" for="lcdEndDate">LCD End Date:</label>
            <div class="controls">
              <span id="island" class="input-label"><?php echo isset($general_info['lcd_end_date']) ? date('d-M-Y', strtotime($general_info['lcd_end_date'])) : '' ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>
      </div>

      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_lcd_renewal_status">
            <label class="control-label" for="lcdRenewalStatus">LCD Renewal Status:</label>
            <div class="controls">
              <?php
              $options = array('' => '', 1 => 'Continue', 0 => 'Failed');
              ?>
              <span class="input-label"><?php echo $options[$general_info['lcd_renewal_status']] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>

        <div class="span6">
          <div class="control-group" id="form_lcd_renewal_remarks">
            <label class="control-label" for="lcdRenewalRemarks">LCD Renewal Remarks:</label>
            <div class="controls">
              <span class="input-label" style="white-space:pre-wrap"><?php echo $general_info['lcd_renewal_remark'] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>
      </div>

      <div class="row-fluid">
        <div class="span6">
          <div class="control-group" id="form_finance_remarks">
            <label class="control-label" for="financeRemarks">Remarks:</label>
            <div class="controls">
              <span id="island" class="input-label"><?php echo $general_info['remarks'] ?></span>
            </div>
            <div class="separator bottom"></div>
          </div>
        </div>

        <div class="span6"></div>
      </div>

    </div>
  </div>

</div>

<script>
  var showMap = function() {
    var x = parseInt((window.screen.availWidth - 850) / 2);
    var y = parseInt(((window.screen.availHeight - 560) / 2) - 50);

    var w = window.open('<?php echo site_url('/site_info') ?>?cmd=GET_MAP&draggable=false&latlngElement=actualLatitude,actualLongitude', 'map', 'height=560,width=850,left=' + x + ',top=' + y + ',scrollbars=no');
    w.focus();

    return false;
  };

  doPreviledgePerfield();
</script>