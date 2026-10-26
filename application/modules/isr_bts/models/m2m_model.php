<?php

/**
 * This model provide data for Engineering Approval at ISR BTS
 *
 * @author tumbas
 */
class M2m_model extends CI_Model {

  private $_user_now;
  private $_date_now;

  //  ISR Status
  //Engineering Approval

  const ENGIN_APPROV_NEW = '1161';
  const ENGIN_APPROV_RENEWAL = '1261';
  const ENGIN_APPROV_DISC_DISMANTLE = '1361';
  const ENGIN_APPROV_DISC_ISR_CHANGE = '1661';
  //M2M
  const M2M_NEW = '1171';
  const M2M_RENEWAL = '1271';
  const M2M_DISC_DISMANTLE = '1371';
  const M2M_DISC_ISR_CHANGE = '1671';
  //SPP to Finance
  const SPP_FIN_NEW = '1121';
  const SPP_FIN_RENEWAL = '1211';

  public function __construct() {
    parent::__construct();
    $this->_user_now = $this->session->userdata('user_id');
    $this->_date_now = date('Y-m-d H:i:s');
  }

  public function get_m2m_new_list($limit, $offset, $param = '') {
    $where = " WHERE link.milestone_status = '" . self::M2M_NEW . "' ";
    if ($param != '') {
      $where .= " AND (app.app_no ILIKE '%{$param}%' OR CAST(app.app_issued AS VARCHAR) ILIKE '%{$param}%' OR CAST(app.app_expired AS VARCHAR) ILIKE '%{$param}%' OR
        app.doc ILIKE '%{$param}%' OR app.remark ILIKE '%{$param}%')";
    }

    $sql = " SELECT DISTINCT app.app_resi_id, app.app_no, to_char(app.app_issued, 'DD/MM/YYYY') AS app_issued , app.doc, app.remark, f_get_user(app.created_by) AS created_by, 
            app.created_on, (SELECT COUNT(DISTINCT(resi_link_id)) FROM t_trx_isr_bts_app_resi_link  WHERE app_resi_id=app.app_resi_id) as total_links, app.\"isUsed\"
            FROM t_trx_isr_bts_app_resi app
            LEFT JOIN t_trx_isr_bts_app_resi_link link ON app.app_resi_id=link.app_resi_id
            {$where}
            ORDER BY app.app_resi_id DESC
            LIMIT {$limit} OFFSET {$offset}";

    $result['rows'] = $this->db->query($sql)->result();

    $sql = "SELECT COUNT (DISTINCT app.app_resi_id) AS count
                FROM t_trx_isr_bts_app_resi app
                LEFT JOIN t_trx_isr_bts_app_resi_link link ON app.app_resi_id=link.app_resi_id
                {$where}";
    $result['total'] = $this->db->query($sql)->row()->count;

    return $result;
  }

  public function get_m2m_discontinued_list($limit, $offset, $param = '') {
    $where = " WHERE link.milestone_status IN ('" . self::M2M_DISC_DISMANTLE . "', '".self::M2M_DISC_ISR_CHANGE."') ";
    if ($param != '') {
      $where .= " AND (app.app_no ILIKE '%{$param}%' OR CAST(app.app_issued AS VARCHAR) ILIKE '%{$param}%' OR CAST(app.app_expired AS VARCHAR) ILIKE '%{$param}%' OR
        app.doc ILIKE '%{$param}%' OR app.remark ILIKE '%{$param}%')";
    }

    $sql = " SELECT DISTINCT app.app_id, app.app_no, to_char(app.app_issued, 'DD/MM/YYYY') AS app_issued , app.doc, app.remark, f_get_user(app.created_by) AS created_by, 
            app.created_on, (SELECT COUNT(DISTINCT(app_link_id)) FROM t_trx_isr_bts_app_link  WHERE app_id=app.app_id) as total_links, app.\"isUsed\"
            FROM t_trx_isr_bts_app app
            LEFT JOIN t_trx_isr_bts_app_link link ON app.app_id=link.app_id
            {$where}
            ORDER BY app.app_id DESC
            LIMIT {$limit} OFFSET {$offset}";

    $result['rows'] = $this->db->query($sql)->result();

    $sql = "SELECT COUNT (DISTINCT app.app_id) AS count
                FROM t_trx_isr_bts_app app
                LEFT JOIN t_trx_isr_bts_app_link link ON app.app_id=link.app_id
                {$where}";
    $result['total'] = $this->db->query($sql)->row()->count;

    return $result;
  }

  public function get_m2m_history_new_list($limit, $offset, $param = '') {
    $where = '';
    if ($param != '') {
      $keyword = $param;
      $where = "WHERE app.app_no ILIKE '%{$keyword}%'";
    }

    $sql = "SELECT DISTINCT app.app_resi_id, app.app_no AS resi_no, to_char(app.app_issued, 'DD/MM/YYYY') AS resi_issued, app.app_expired, app.doc, app.remark, app.\"isUsed\", f_get_user(app.created_by) AS created_by, app.created_on,
                (SELECT COUNT(DISTINCT(resi_link_id)) FROM t_trx_isr_bts_app_resi_link WHERE app_resi_id=app.app_resi_id) as total_links, COALESCE(resp.concession_number, ' - ') AS concession_number , 
                resp.app_index, COALESCE(resp.app_ref, ' - ') AS app_ref, resp.batch_no, COALESCE(resp.license_number, ' - ') AS license_number
                FROM t_trx_isr_bts_app_resi app
                JOIN t_trx_isr_bts_app_resi_link link ON app.app_resi_id=link.app_resi_id
                JOIN (SELECT MAX(m2m_log_new_id) AS m2m_log_new_id, app_id FROM t_trx_isr_bts_m2m_upload_excel_new_header GROUP BY app_id) AS upload_header ON upload_header.app_id = app.app_resi_id
                LEFT JOIN (
                        SELECT *
                        FROM t_trx_isr_bts_m2m_response_new_detail resp_detail
                        JOIN t_trx_isr_bts_m2m_response_new_header resp_header ON resp_header.isr_response_header_new_id = resp_detail.isr_response_header_new_id
												
                ) AS resp ON resp.m2m_log_new_id = upload_header.m2m_log_new_id
                {$where}  
                ORDER BY app.app_resi_id DESC
                LIMIT {$limit} OFFSET {$offset}";
    $query = $this->db->query($sql);
    $result['rows'] = $query->result();

    $sql = "SELECT COUNT(DISTINCT (app.app_resi_id, resp.license_number))
                FROM t_trx_isr_bts_app_resi app
                JOIN t_trx_isr_bts_app_resi_link link ON app.app_resi_id=link.app_resi_id
                JOIN (SELECT MAX(m2m_log_new_id) AS m2m_log_new_id, app_id FROM t_trx_isr_bts_m2m_upload_excel_new_header GROUP BY app_id) AS upload_header ON upload_header.app_id = app.app_resi_id
                LEFT JOIN (
                        SELECT *
                        FROM t_trx_isr_bts_m2m_response_new_detail resp_detail
                        JOIN t_trx_isr_bts_m2m_response_new_header resp_header ON resp_header.isr_response_header_new_id = resp_detail.isr_response_header_new_id
												
                ) AS resp ON resp.m2m_log_new_id = upload_header.m2m_log_new_id
                {$where}";
    $query = $this->db->query($sql);
    $result['total'] = $query->row()->count;

    return $result;
  }
  
  public function get_m2m_history_discontinued_list($limit, $offset, $param = '') {
    $where = '';
    if ($param != '') {
      $keyword = $param;
      $where = "WHERE app.app_no ILIKE '%{$keyword}%'";
    }

    $sql = "SELECT DISTINCT app.app_id, app.app_no AS app_no, license_number, to_char(app.app_issued, 'DD/MM/YYYY') AS app_issued, app.app_expired, 
                upload_header.att_file_name_1 AS doc, app.remark, app.\"isUsed\", f_get_user(app.created_by) AS created_by, resp.created_on2 AS created_on,
                (SELECT COUNT(DISTINCT(app_link_id)) FROM t_trx_isr_bts_app_link WHERE app_id=app.app_id) as total_links
                FROM t_trx_isr_bts_app app
                JOIN t_trx_isr_bts_app_link link ON app.app_id=link.app_id
                JOIN (SELECT MAX(m2m_log_cancel_id) AS m2m_log_cancel_id, ap_name_reference, att_file_name_1 FROM t_trx_isr_bts_m2m_upload_excel_cancel GROUP BY ap_name_reference, att_file_name_1) AS upload_header ON upload_header.ap_name_reference::INT = app.app_id
                LEFT JOIN (
                        SELECT *, resp_header.created_on AS created_on2
                        FROM t_trx_isr_bts_m2m_response_cancel_detail resp_detail
                        JOIN t_trx_isr_bts_m2m_response_cancel_header resp_header ON resp_header.isr_response_header_cancel_id = resp_detail.isr_response_header_cancel_id
												
                ) AS resp ON resp.m2m_log_cancel_id = upload_header.m2m_log_cancel_id
                {$where}  
                ORDER BY app.app_id DESC
                LIMIT {$limit} OFFSET {$offset}";
    $query = $this->db->query($sql);
    $result['rows'] = $query->result();

    $sql = "SELECT COUNT(DISTINCT (app.app_id, license_number))
                FROM t_trx_isr_bts_app app
                JOIN t_trx_isr_bts_app_link link ON app.app_id=link.app_id
                JOIN (SELECT MAX(m2m_log_cancel_id) AS m2m_log_cancel_id, ap_name_reference FROM t_trx_isr_bts_m2m_upload_excel_cancel GROUP BY ap_name_reference) AS upload_header ON upload_header.ap_name_reference::INT = app.app_id
                LEFT JOIN (
                        SELECT *
                        FROM t_trx_isr_bts_m2m_response_cancel_detail resp_detail
                        JOIN t_trx_isr_bts_m2m_response_cancel_header resp_header ON resp_header.isr_response_header_cancel_id = resp_detail.isr_response_header_cancel_id
												
                ) AS resp ON resp.m2m_log_cancel_id = upload_header.m2m_log_cancel_id
                {$where}";
    $query = $this->db->query($sql);
    $result['total'] = $query->row()->count;

    return $result;
  }

  public function get_m2m_detail_list($resi_id = '') {
    $sql = "SELECT bts.*, polar.lookup_desc AS transmitter_polarization_desc 
        FROM t_trx_isr_bts_app_resi_link bts
        LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = bts.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
        WHERE app_resi_id = '{$resi_id}'
      ORDER BY station_identifier ASC";

    return $this->db->query($sql)->result();
  }
  
  function m2m_save_new_application($data, $app_id, $header_id) {

    if ($data != NULL) {
//      $this->db->trans_strict(FALSE);
      $user = $this->session->userdata('user_id');
      $date_now = date('Y-m-d H:i:s');
      $date_now1 = date('Y-m-d');
      $headers = array('service_id', 'subservice_id', 'client_id', 'application_identifier', 'station_identifier', 'link_id', 'station_name', 'height_asl', 'lat_deg', 'lat_min', 'lat_sec', 'lat_dir_ind_n_s',
          'long_deg', 'long_min', 'long_sec', 'long_dir_ind_e_w', 'radius_of_operation', 'site_province', 'site_city', 'site_district', 'area_of_service', 'equipment_type', 'equipment_output_power',
          'bandwidth', 'transmitter_freq', 'receiver_freq', 'antenna_type', 'feeding_loss', 'connector_loss', 'branch_loss', 'antenna_height_agl', 'transmitter_polarization', 'f1_name', 'f1_desc', 'f1_blob', 'f2_name', 'f2_desc', 'f2_blob');

      foreach ($headers as $value) {
        $$value = $data[$value];
      }

      $log_data = array(
          'uploaded_by' => $user,
          'uploaded_on' => $date_now,
      );

      $this->db->insert('t_trx_isr_bts_m2m_exchange_log_new', $log_data);
      $log_id = $this->db->insert_id();

//$log_id = date('YmdHis');
//$batch_id = 'B-' . date('YmdHis');
//$batch_id = date('YmdHis');
      $username = 'smartfren';
      $password = $username;

      $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://ws.m2m.sdppi/">
                    <soapenv:Header/>
                    <soapenv:Body>
                    <ws:importXML>
                    <xmlContent><![CDATA[<?xml version="1.0" encoding="utf-8" standalone="yes"?>
                    <SPECTRAExchangeEssential xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.lstelcom.com/Schema/SPECTRAExchangeEssential" version="1.1.14">';

      $xml .= "
                            <APPLICATION>
                                <SV_SV_ID>{$service_id[0]}</SV_SV_ID>
                                <SS_SS_ID>{$subservice_id[0]}</SS_SS_ID>
                                <AP_NAME_REFERENCE>$header_id</AP_NAME_REFERENCE>
                                <AP_PRJ_IDENT>{$log_id}</AP_PRJ_IDENT>
                                <AP_ACTION_TYPE>N</AP_ACTION_TYPE>
                                <AD_MAN_NUMBER>{$client_id[0]}</AD_MAN_NUMBER>";

      $i = 0;
//      $j = 1;
//      $this->db->trans_start();
      foreach ($station_identifier as $row) {

//        if ($j % 2 == 1) {
//          $xml .= "
//                            <APPLICATION>
//                                <SV_SV_ID>{$service_id[$i]}</SV_SV_ID>
//                                <SS_SS_ID>{$subservice_id[$i]}</SS_SS_ID>
//                                <AP_NAME_REFERENCE>$header_id</AP_NAME_REFERENCE>
//                                <AP_PRJ_IDENT>{$log_id}</AP_PRJ_IDENT>
//                                <AP_ACTION_TYPE>N</AP_ACTION_TYPE>
//                                <AD_MAN_NUMBER>{$client_id[$i]}</AD_MAN_NUMBER>";
//        }
        $transmitter_freq = $transmitter_freq[$i] * 1000000;
        $receiver_freq = $receiver_freq[$i] * 1000000;
        $xml .= "
                                <STATION>
                                    <TCS_NAME>{$station_name[$i]}</TCS_NAME>
                                    <SID_H_NN>{$height_asl[$i]}</SID_H_NN>
                                    <SID_LONG_DEG>{$long_deg[$i]}</SID_LONG_DEG>
                                    <SID_LONG_E_W>{$long_dir_ind_e_w[$i]}</SID_LONG_E_W>
                                    <SID_LONG_MIN>{$long_min[$i]}</SID_LONG_MIN>
                                    <SID_LONG_SEC>{$long_sec[$i]}</SID_LONG_SEC>    
                                    <SID_LAT_DEG>{$lat_deg[$i]}</SID_LAT_DEG>
                                    <SID_LAT_N_S>{$lat_dir_ind_n_s[$i]}</SID_LAT_N_S>
                                    <SID_LAT_MIN>{$lat_min[$i]}</SID_LAT_MIN>
                                    <SID_LAT_SEC>{$lat_sec[$i]}</SID_LAT_SEC>    
                                    <ADDRESSES>    
                                        <ADDRESS AD_TYPE='S'>
                                            <AD_STREET>{$area_of_service[$i]}</AD_STREET>
                                            <AD_CITY>{$site_city[$i]}</AD_CITY>
                                            <AD_DISTRICT>{$site_district[$i]}</AD_DISTRICT>    
                                            <AD_COUNTY>{$site_province[$i]}</AD_COUNTY>
                                        </ADDRESS>
                                    </ADDRESSES>
                                    <TRANSMITTER EAN_ANT_IDENT='{$antenna_type[$i]}'>
                                        <EQP_EQUIP_IDENT>{$equipment_type[$i]}</EQP_EQUIP_IDENT>
                                        <ETX_EQ_OUTPUT>{$equipment_output_power[$i]}</ETX_EQ_OUTPUT>
                                        <EAC_AN_H>{$antenna_height_agl[$i]}</EAC_AN_H>
                                        <EAC_AN_POL>" . trim($transmitter_polarization[$i]) . "</EAC_AN_POL>    
                                        <EAC_FEEDING_LOSS>{$feeding_loss[$i]}</EAC_FEEDING_LOSS>
                                        <EAC_CONNECTOR_LOSS>{$connector_loss[$i]}</EAC_CONNECTOR_LOSS>
                                        <EAC_BRANCH_LOSS>{$branch_loss[$i]}</EAC_BRANCH_LOSS>
                                        <FREQUENCY>
                                            <EFL_FREQ>{$transmitter_freq}</EFL_FREQ>
                                            <EFL_RF_BWIDTH>{$bandwidth[$i]}</EFL_RF_BWIDTH>
                                        </FREQUENCY>
                                    </TRANSMITTER>
                                    <RECEIVER EAN_ANT_IDENT='{$antenna_type[$i]}'>
                                        <EQP_EQUIP_IDENT>{$equipment_type[$i]}</EQP_EQUIP_IDENT>
                                        <EAC_AN_H>{$antenna_height_agl[$i]}</EAC_AN_H>
                                        <EAC_AN_POL>" . trim($transmitter_polarization[$i]) . "</EAC_AN_POL>    
                                        <EAC_FEEDING_LOSS>{$feeding_loss[$i]}</EAC_FEEDING_LOSS>
                                        <EAC_CONNECTOR_LOSS>{$connector_loss[$i]}</EAC_CONNECTOR_LOSS>
                                        <EAC_BRANCH_LOSS>{$branch_loss[$i]}</EAC_BRANCH_LOSS>
                                        <FREQUENCY>
                                            <EFL_FREQ>{$receiver_freq}</EFL_FREQ>
                                            <EFL_RF_BWIDTH>{$bandwidth[$i]}</EFL_RF_BWIDTH>
                                        </FREQUENCY>
                                    </RECEIVER>
                                </STATION>";
//
//        if ($j % 2 == 0) {
//          $xml .= "
//                            <ATTACHMENT>
//                            <FILE_NAME>{$f1_name}</FILE_NAME>
//                            <DESCRIPTION>{$f1_desc}</DESCRIPTION>
//                            <FILE_BLOB>{$f1_blob}</FILE_BLOB>
//                            </ATTACHMENT>
//                            <ATTACHMENT>
//                            <FILE_NAME>{$f2_name}</FILE_NAME>
//                            <DESCRIPTION>{$f2_desc}</DESCRIPTION>
//                            <FILE_BLOB>{$f2_blob}</FILE_BLOB>
//                            </ATTACHMENT>
//                          </APPLICATION>";
//        }

        $i++;
        // $j++;
      }

      $xml .= "
                            <ATTACHMENT>
                            <FILE_NAME>{$f1_name}</FILE_NAME>
                            <DESCRIPTION>{$f1_desc}</DESCRIPTION>
                            <FILE_BLOB>{$f1_blob}</FILE_BLOB>
                            </ATTACHMENT>
                            <ATTACHMENT>
                            <FILE_NAME>{$f2_name}</FILE_NAME>
                            <DESCRIPTION>{$f2_desc}</DESCRIPTION>
                            <FILE_BLOB>{$f2_blob}</FILE_BLOB>
                            </ATTACHMENT>
                          </APPLICATION>";

      $xml .= "</SPECTRAExchangeEssential>
                    ]]>
                    </xmlContent>
                    <actionType>N</actionType>
                    <serviceId>{$service_id[0]}</serviceId>
                    <subServiceId>{$subservice_id[0]}</subServiceId>
                    <clientId>{$client_id[0]}</clientId>
                    <username>{$username}</username>
                    <password>{$password}</password>
                    </ws:importXML>
                    </soapenv:Body>
                    </soapenv:Envelope>";
//
//      header('Content-Type: application/xml; charset=utf-8');
//      echo $xml;
//      exit();

      $log_data = array(
          'xml_request' => $xml,
      );
      $this->db->where('m2m_log_new_id', $log_id);
      $this->db->update('t_trx_isr_bts_m2m_exchange_log_new', $log_data);

      $result = $this->_m2m_submit($xml);
      $xml_result = $result['xml_string'];
      $apps = $result['apps'];

      if (!empty($apps)) {
        if ($apps->responseCode == 1) {

          $data_header_update = array(
              'isr_upload_header_new_id' => $header_id,
              'delivered' => 1
          );
          $this->mglobal->saveData('t_trx_isr_bts_m2m_upload_excel_new_header', $data_header_update, 'isr_upload_header_new_id');

          $data_header = array(
              'm2m_log_new_id' => $log_id,
              'concession_number' => (string) $apps->concessionNumber,
              'response_code' => (string) $apps->responseCode,
              'response_message' => (string) $apps->responseMessage,
          );
          $this->mglobal->saveData('t_trx_isr_bts_m2m_response_new_header', $data_header);
          $resp_header_id = $this->db->insert_id();

          foreach ($apps->resultDetails as $detail) {
            $data_detail = array(
                'isr_response_header_new_id' => $resp_header_id,
                'app_index' => (string) $detail->appIndex,
                'app_ref' => (string) $detail->appRef,
                'batch_no' => (string) $detail->batchNo,
                'code_result' => (string) $detail->codeResult,
                'license_number' => (string) $detail->licenseNumber,
                'message' => (string) $detail->message,
                'transaction_id' => (string) $detail->transactionId,
            );
            $this->mglobal->saveData('t_trx_isr_bts_m2m_response_new_detail', $data_detail);
          }

          $response = array(
              'type' => 'success',
              'message' => '<br><b>SDPPI</b><br>' . (string) $apps->responseMessage,
          );
        } else {

          $data_header_update = array(
              'isr_upload_header_new_id' => $header_id,
              'delivered' => 1
          );
          $this->mglobal->saveData('t_trx_isr_bts_m2m_upload_excel_new_header', $data_header_update, 'isr_upload_header_new_id');

          $data_header = array(
              'm2m_log_new_id' => $log_id,
              'response_code' => (string) $apps->responseCode,
              'response_message' => (string) $apps->responseMessage,
          );
          $this->mglobal->saveData('t_trx_isr_bts_m2m_response_new_header', $data_header);
          $resp_header_id = $this->db->insert_id();

          foreach ($apps->resultDetails as $detail) {
            $data_detail = array(
                'isr_response_header_new_id' => $resp_header_id,
                'app_index' => (string) $detail->appIndex,
                'code_result' => (string) $detail->codeResult,
                'message' => (string) $detail->message,
                'transaction_id' => (string) $detail->transactionId,
            );
            $this->mglobal->saveData('t_trx_isr_bts_m2m_response_new_detail', $data_detail);
          }

          $response = array(
              'log_id' => $log_id,
              'type' => 'error',
              'message' => ' but not send to SDPPI.<br><b>SDPPI</b><br>' . (string) $apps->responseMessage
          );
        }
      } else {
        $response = array(
            'type' => 'error',
            'message' => ' but not send to SDPPI.<br><b>SDPPI</b><br> No XML Response',
            'log_id' => $log_id
        );
        $data_header = array(
            'm2m_log_new_id' => $log_id,
            'response_code' => '-1',
            'response_message' => 'No XML Response',
        );
        $this->mglobal->saveData('t_trx_isr_bts_m2m_response_new_header', $data_header);
      }

      //Update some table below
      $data_app_update = array(
          'isr_upload_header_new_id' => $header_id,
          'app_id' => $app_id,
          'm2m_log_new_id' => $log_id
      );
      $this->mglobal->saveData('t_trx_isr_bts_m2m_upload_excel_new_header', $data_app_update, 'isr_upload_header_new_id');

      $data_app_link_update = array(
          'isr_upload_header_new_id' => $header_id,
          'm2m_log_new_id' => $log_id
      );
      $this->mglobal->saveData('t_trx_isr_bts_m2m_upload_excel_new_detail', $data_app_link_update, 'isr_upload_header_new_id');
    } else {
      $response = array('type' => 'error', 'message' => ' but not send to SDPPI.<br><b>SDPPI</b><br> No Data');
    }

    $log_data = array(
        'xml_response' => $xml_result,
        'replied_on' => date('Y-m-d H:i:s')
    );
    $this->db->where('m2m_log_new_id', $log_id);
    $this->db->update('t_trx_isr_bts_m2m_exchange_log_new', $log_data);

    return $response;
  }

  protected function _m2m_submit($xml = '') {
    error_reporting(E_ALL);
    ini_set('display_errors ', true);
    ini_set('display_startup_errors ', true);

    //DEVELOPMENT
    $location_URL = 'http://103.17.164.185/ws-m2m-dev/m2mManager.php';
    $action_URL = "http://103.17.164.185/ws-m2m-dev/wsdl.asmx?wsdl";
    //        
    //PRODUCTION
//        $location_URL = 'http://m2m.postel.go.id/m2mws/m2mManager.php';
//        $action_URL = "http://103.17.164.188/m2mws/wsdl.asmx?wsdl";

    $client = new SoapClient(null, array(
        'location' => $location_URL,
        'uri' => "",
        'trace' => 1,
    ));

    try {
      //$order_return = $client->__doRequest($xml, $location_URL, $action_URL, 1);
//      TESTING
//      $order_return = $this->response_cancel_success();
      $order_return = $this->response_success();
//      $order_return = $this->response_error();
//            header('Content-Type: application/xml; charset=utf-8');
//            echo $order_return;
//            exit();

      if ($order_return == '')
        return NULL;

      $xml = simplexml_load_string($order_return);
      $apps = $xml->children('S', true)->Body->children('ns2', true)->importXMLResponse->children()->return;

      $result['xml_string'] = $order_return;
      $result['apps'] = $apps;
      return $result;
    } catch (Exception $exception) {
      return null;
    }
  }

  protected function _m2m_submit_cancel($xml = '') {
    error_reporting(E_ALL);
    ini_set('display_errors ', true);
    ini_set('display_startup_errors ', true);

    //DEVELOPMENT
    $location_URL = 'http://103.17.164.185/ws-m2m-dev/m2mManager.php';
    $action_URL = "http://103.17.164.185/ws-m2m-dev/wsdl.asmx?wsdl";
    //        
    //PRODUCTION
//        $location_URL = 'http://m2m.postel.go.id/m2mws/m2mManager.php';
//        $action_URL = "http://103.17.164.188/m2mws/wsdl.asmx?wsdl";

    $client = new SoapClient(null, array(
        'location' => $location_URL,
        'uri' => "",
        'trace' => 1,
    ));

    try {
      //$order_return = $client->__doRequest($xml, $location_URL, $action_URL, 1);
//      TESTING
      $order_return = $this->response_cancel_success();
      //$order_return = $this->response_success();
//      $order_return = $this->response_error();
//            header('Content-Type: application/xml; charset=utf-8');
//            echo $order_return;
//            exit();

      if ($order_return == '')
        return NULL;

      $xml = simplexml_load_string($order_return);
      $apps = $xml->children('S', true)->Body->children('ns2', true)->importXMLResponse->children()->return;

      $result['xml_string'] = $order_return;
      $result['apps'] = $apps;
      return $result;
    } catch (Exception $exception) {
      return null;
    }
  }
  
  protected function response_success() {
    $concessionNumber = '1' . date('ymdHi');
    $appRef = '01' . date('ymdHi');
    $licenseNumber = '02' . date('Hidmy');
    $xml = '<?xml version="1.0" ?>
      <S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
      <S:Body><ns2:importXMLResponse xmlns:ns2="http://webservice.SPweb.Products.lsgermany.de/">
      <return>
      <concessionNumber>' . $concessionNumber . '</concessionNumber>
      <responseCode>1</responseCode>
      <responseMessage>All applications are exported successfully to SPECTRAplus.</responseMessage>
      <resultDetails>
      <appIndex>1</appIndex>
      <appRef>' . $appRef . '</appRef>
      <batchNo>198</batchNo>
      <codeResult>1</codeResult>
      <licenseNumber>' . $licenseNumber . '</licenseNumber>
      <message>Successfully Export to SPECTRAplus</message>
      <transactionId>8071014</transactionId></resultDetails>
      </return>
      </ns2:importXMLResponse>
      </S:Body>
      </S:Envelope>';

    return $xml;
  }

  protected function response_error() {
    $xml = '<?xml version="1.0" ?>
      <S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
      <S:Body><ns2:importXMLResponse xmlns:ns2="http://webservice.SPweb.Products.lsgermany.de/">
      <return>
      <responseCode>-1</responseCode>
      <responseMessage>Invalid data AD_CITY of Station with TCS_NAME: ZTE_2971_ZTEIBS_0104_Ancol Beach City. The correct value should be: PADEMANGAN</responseMessage>
      <resultDetails>
      <appIndex>1</appIndex>
      <codeResult>-1</codeResult>
      <message>Invalid data AD_CITY of Station with TCS_NAME: ZTE_2971_ZTEIBS_0104_Ancol Beach City. The correct value should be: PADEMANGAN</message>
      <transactionId>20150510210231</transactionId>
      </resultDetails>
      </return>
      </ns2:importXMLResponse>
      </S:Body>
      </S:Envelope>';

    return $xml;
  }

  public function m2m_cancel_application($data) {
    $username = 'smartfren';
    $password = $username;

    $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://ws.m2m.sdppi/">
              <soapenv:Header/>
              <soapenv:Body>
              <ws:importXML>
                <xmlContent><![CDATA[<?xml version="1.0" encoding="utf-8" standalone="yes"?>
                  <SPECTRAExchangeEssential xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.lstelcom.com/Schema/SPECTRAExchangeEssential" version="1.1.14">
                  <APPLICATION>
                    <SV_SV_ID>'.$data['service_id'][0].'</SV_SV_ID>
                    <SS_SS_ID>'.$data['subservice_id'][0].'</SS_SS_ID>
                    <AP_NAME>' . $data['license_number'] . '</AP_NAME>
                    <AP_NAME_REFERENCE>' . $data['app_id'] . '</AP_NAME_REFERENCE>
                    <AP_ACTION_TYPE>C</AP_ACTION_TYPE>
                    <AD_MAN_NUMBER>' . $data['client_id'] . '</AD_MAN_NUMBER>
                    <ATTACHMENT>
                      <FILE_NAME>' . $data['f1_name'] . '</FILE_NAME>
                      <DESCRIPTION>' . $data['f1_desc'] . '</DESCRIPTION>
                      <FILE_BLOB>' . $data['f1_blob'] . '</FILE_BLOB>
                    </ATTACHMENT>
                    <ATTACHMENT>
                      <FILE_NAME>' . $data['f2_name'] . '</FILE_NAME>
                      <DESCRIPTION>' . $data['f2_desc'] . '</DESCRIPTION>
                      <FILE_BLOB>' . $data['f2_blob'] . '</FILE_BLOB>
                    </ATTACHMENT>
                    </APPLICATION>
                  </SPECTRAExchangeEssential>]]></xmlContent>
                <actionType>C</actionType>
                <serviceId>'.$data['service_id'][0].'</serviceId>
                <subServiceId>'.$data['subservice_id'][0].'</subServiceId>
                <clientId>' . $data['client_id'] . '</clientId>
                <username>' . $username . '</username>
                <password>' . $password . '</password>
                </ws:importXML>
                </soapenv:Body>
                </soapenv:Envelope>';

    $data_log = array(
        'xml_request' => $xml,
        'uploaded_by' => $this->_user_now,
        'uploaded_on' => $this->_date_now
    );
    $this->db->insert('t_trx_isr_bts_m2m_exchange_log_cancel', $data_log);
    $log_id = $this->db->insert_id();

    $data_cancel = array(
        'm2m_log_cancel_id' => $log_id,
        'service_id' => $data['service_id'][0],
        'subservice_id' => $data['subservice_id'][0],
        'client_id' => $data['client_id'],
        'ap_name' => $data['license_number'],
        'ap_name_reference' => $data['app_id'],
        'action_type' => 'C',
        'att_file_name_1' => $data['f1_name'],
        'att_file_description_1' => $data['f1_desc'],
        'att_file_blob_1' => $data['f1_blob'],
        'att_file_name_2' => $data['f2_name'],
        'att_file_description_2' => $data['f2_desc'],
        'att_file_blob_2' => $data['f2_blob'],
    );
    $header_id = $this->mglobal->saveData('t_trx_isr_bts_m2m_upload_excel_cancel', $data_cancel);

    $result = $this->_m2m_submit_cancel($xml);
    $xml_result = $result['xml_string'];
    $apps = $result['apps'];

    if (!empty($apps)) {

      $data_header_update = array(
          'isr_upload_cancel_id' => $header_id,
          'delivered' => 1
      );
      $this->mglobal->saveData('t_trx_isr_bts_m2m_upload_excel_cancel', $data_header_update, 'isr_upload_cancel_id');

      $data_header = array(
          'm2m_log_cancel_id' => $log_id,
          'response_code' => (string) $apps->responseCode,
          'response_message' => (string) $apps->responseMessage,
      );
      $this->mglobal->saveData('t_trx_isr_bts_m2m_response_cancel_header', $data_header);
      $resp_header_id = $this->db->insert_id();

      foreach ($apps->resultDetails as $detail) {
        $data_detail = array(
            'isr_response_header_cancel_id' => $resp_header_id,
            'app_index' => (string) $detail->appIndex,
            'code_result' => (string) $detail->codeResult,
            'transaction_id' => (string) $detail->transactionId,
        );
        $this->mglobal->saveData('t_trx_isr_bts_m2m_response_cancel_detail', $data_detail);
      }

      if ($apps->responseCode == 1) { // Success
        //change link milestone
        $data_link = array(
            'app_id' => $data['app_id'],
            'prev_completed_milestone' => $data['milestone_status'],
            'milestone_status' => intval($data['milestone_status']) - 40
        );
        $this->mglobal->saveData('t_trx_isr_bts_app_link', $data_link, 'app_id');

        $response = array(
            'type' => 'success',
            'message' => '<br><b>SDPPI</b><br>' . (string) $apps->responseMessage,
        );
      } else { //Error
        $response = array(
            'log_id' => $log_id,
            'type' => 'error',
            'message' => ' but not send to SDPPI.<br><b>SDPPI</b><br>' . (string) $apps->responseMessage
        );
      }
    } else {
      $response = array(
          'type' => 'error',
          'message' => ' but not send to SDPPI.<br><b>SDPPI</b><br> No XML Response',
          'log_id' => $log_id
      );
      $data_header = array(
          'm2m_log_cancel_id' => $log_id,
          'response_code' => '-1',
          'response_message' => 'No XML Response',
      );
      $this->mglobal->saveData('t_trx_isr_bts_response_cancel_header', $data_header);
    }

    $log_data = array(
        'xml_response' => $xml_result,
        'replied_on' => date('Y-m-d H:i:s')
    );
    $this->db->where('m2m_log_new_id', $log_id);
    $this->db->update('t_trx_isr_bts_m2m_exchange_log_cancel', $log_data);

    return $response;
  }

  protected function response_cancel_success() {
    $xml = '<?xml version="1.0" ?>
      <S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
      <S:Body>
      <ns2:importXMLResponse xmlns:ns2="http://webservice.SPweb.Products.lsgermany.de/">
      <return>
      <responseCode>1</responseCode>
      <responseMessage>All applications are exported successfully to SPECTRAplus.</responseMessage>
      <resultDetails>
      <appIndex>1</appIndex>
      <codeResult>1</codeResult>
      <transactionId>' . $this->input->post('app_id') . '</transactionId>
        </resultDetails>
        </return>
        </ns2:importXMLResponse>
        </S:Body>
        </S:Envelope>';

    return $xml;
  }

  protected function response_cancel_error() {
    $xml = '<?xml version="1.0" ?>
      <S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
      <S:Body><ns2:importXMLResponse xmlns:ns2="http://webservice.SPweb.Products.lsgermany.de/">
      <return>
      <responseCode>-1</responseCode>
      <responseMessage>Invalid data AD_CITY of Station with TCS_NAME: ZTE_2971_ZTEIBS_0104_Ancol Beach City. The correct value should be: PADEMANGAN</responseMessage>
      <resultDetails>
      <appIndex>1</appIndex>
      <codeResult>-1</codeResult>
      <message>Invalid data AD_CITY of Station with TCS_NAME: ZTE_2971_ZTEIBS_0104_Ancol Beach City. The correct value should be: PADEMANGAN</message>
      <transactionId>20150510210231</transactionId>
      </resultDetails>
      </return>
      </ns2:importXMLResponse>
      </S:Body>
      </S:Envelope>';

    return $xml;
  }

  public function m2m_new_header($app_id = 0) {
    $sql = "SELECT app.app_resi_id, app.app_no, app.app_issued, app.doc, app.remark, app.\"isUsed\", near_end_v_id, far_end_v_id, f_get_user(app.created_by) AS created_by,
                to_char(resp.issued_date, 'DD Mon YYYY HH24:MI:SS') AS created_on, (select count(*) from t_trx_isr_bts_app_resi_link where app_resi_id=app.app_resi_id) as count, 
                COALESCE(resp.concession_number, ' - ') AS concession_number, resp.app_index, COALESCE(resp.app_ref, ' - ') AS app_ref, COALESCE(resp.batch_no::VARCHAR, ' - ') AS batch_no,
                COALESCE(resp.license_number, ' - ') AS license_number
                FROM t_trx_isr_bts_app_resi app
                LEFT JOIN t_trx_isr_bts_m2m_upload_excel_new_header upload_header ON upload_header.app_id = app.app_resi_id
                LEFT JOIN (
                        SELECT *, resp_detail.created_on AS issued_date
                        FROM t_trx_isr_bts_m2m_response_new_detail resp_detail
                        JOIN t_trx_isr_bts_m2m_response_new_header resp_header ON resp_header.isr_response_header_new_id = resp_detail.isr_response_header_new_id
                ) AS resp ON resp.m2m_log_new_id = upload_header.m2m_log_new_id
                WHERE app.app_resi_id={$app_id} ORDER BY upload_header.isr_upload_header_new_id DESC LIMIT 1";
    $query = $this->db->query($sql);

    return $query->row();
  }

  function m2m_new_detail_history($app_id) {
    $sql = "SELECT resp_header.response_code, resp_header.response_message, f_get_user(resp_header.created_by) AS created_by, to_char(resp_header.created_on, 'DD Mon YYYY HH24:MI:SS') AS created_on
                FROM t_trx_isr_bts_app_resi app
                JOIN t_trx_isr_bts_m2m_upload_excel_new_header upload_header ON upload_header.app_id = app.app_resi_id
                JOIN t_trx_isr_bts_m2m_response_new_header resp_header  ON resp_header.m2m_log_new_id = upload_header.m2m_log_new_id
                WHERE app.app_resi_id={$app_id} ORDER BY upload_header.isr_upload_header_new_id DESC";
    $query = $this->db->query($sql);

    return $query->result();
  }

  public function get_m2m_discontinued_header($app_id = 0) {
    $sql = "SELECT DISTINCT app.app_id, app_no, license_number, f_get_user(app.updated_by) AS created_by, app.created_on, client_id, milestone_status
      FROM t_trx_isr_bts_app app
      LEFT JOIN t_trx_isr_bts_app_link link ON link.app_id = app.app_id
      WHERE app.app_id = {$app_id}";
    $query = $this->db->query($sql);

    return $query->row();
  }

  public function get_m2m_detail_list2($param = 0) {
    $sql = "SELECT link.*, lookup_desc AS transmitter_polarization_desc, f_get_sitecode(link.site_id) AS site_code, 
            f_get_regionnamebysiteid_nocluster(link.site_id) AS region
            FROM t_trx_isr_bts_app_link link 
            LEFT JOIN t_mtr_lookup polar ON polar.lookup_value = link.transmitter_polarization AND polar.lookup_key = 'bts_polarization'
            WHERE app_id = {$param} ORDER BY station_identifier ASC";

    return $this->db->query($sql)->result();
  }

  function m2m_discontinued_detail_history($app_id) {
    $sql = "SELECT resp_header.response_code, resp_header.response_message, f_get_user(resp_header.created_by) AS created_by, to_char(resp_header.created_on, 'DD Mon YYYY HH24:MI:SS') AS created_on
                FROM t_trx_isr_bts_app app
                LEFT JOIN t_trx_isr_bts_m2m_upload_excel_cancel upload_header ON upload_header.ap_name_reference::INT = app.app_id AND upload_header.app_type = 4
                LEFT JOIN t_trx_isr_bts_m2m_response_cancel_header resp_header  ON resp_header.m2m_log_cancel_id = upload_header.m2m_log_cancel_id
                WHERE app.app_id={$app_id} ORDER BY upload_header.isr_upload_cancel_id DESC";
    $query = $this->db->query($sql);

    return $query->result();
  }

}

?>
