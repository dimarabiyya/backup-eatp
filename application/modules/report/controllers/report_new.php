<?php
ini_set('memory_limit', '-1');
require_once APPPATH . 'libraries/tcpdf/tcpdf.php';

class Report_new extends CI_Controller
{

    public function index()
    {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        // print_r($this->core->decode($this->uri->segment(4)));die();

        // set_time_limit(0);
        //* EDITED 2023-09-23 Mengedit error_reporting
        error_reporting(E_ERROR);
        //*
        $this->load->library('parser');

        // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('Engineering Form');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING);
        //$pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // add/remove default header/footer
        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP - 10, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM-120);
        $pdf->SetAutoPageBreak(true, 13);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        //$pdf->SetFont('dejavusans', '', 10, '', true);
        $pdf->SetFont('helvetica', 'BU', 16);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        //$resolution = array(1122.24, 793.92);
        //$pdf->AddPage('P', $resolution);
        $pdf->AddPage('P', 'A4');
        $pdf->SetFont('helvetica', '', 10);

        $html = '
				<style>
				table{padding:2px; border-spacing:0px;}
				table th{font-weight:bold;}
				</style>
		';

        //get visit_id visit_type dari url
        $visitID =  $this->core->decode($this->uri->segment(4));
        $visit_type =  $this->core->decode($this->uri->segment(5));
        // var_dump($visit_type);

        // if ($visit_type == 109 || $visit_type == 110 || $visit_type == 120) //1
        // {
        //     $is_ttd_exist =  $this->uri->segment(5);
        //     $data = $this->load->model('report_new_model')->report1($visitID);
        //     $data = str_replace("< >", "<>", $data);
        //     $data['visitID'] = $visitID;
        //     $data['visit_type'] = $visit_type;
        //     $db2 = $this->load->database('mysql', TRUE);
        //     $sql = "SELECT purch_doc,item,material_code,short_text,po_qty,region,ewo,link FROM tbl_gac_data WHERE link='" . $data['link_id'] . "'";
        //     $res = $db2->query($sql)->result();
        //     $data['data_gac'] = $res;

        //     if ($visit_type == 109) {
        //         $html .= $this->parser->parse('instalasi_busol', $data);
        //     } else {
        //         $html .= $this->parser->parse('instalasi_ipasolink', $data);
        //     }
        // } else {
        // if ($visit_type == 109 || $visit_type == 110 || $visit_type == 120 || $visit_type == 114) //1
        // {
        //     $data = $this->load->model('report_new_model')->report1($visitID);
        // }
        $data = $this->load->model('report_new_model')->report1($visitID);
        $visitinfo = $this->getDetailSummary($visitID);
        //GET Visit Type Value
        $this->db->select('*');
        $this->db->where('option_id =', $visit_type);
        $q = $this->db->get('t_mtr_checklist_options');
        $data['visit_type_label'] = @$q->result_array()[0]["option_label"];

        $properties = $this->siteProperties();

        $data['checklist_item'] = array();
        $answers_by_checklist = array();
        foreach ($visitinfo['answer'] as $answer) {
            $answers_by_checklist[$answer['checklist_id']][] = $answer;
        }
        foreach ($visitinfo['checklists'] as $x => $checklistitem) {
            $index_name = $checklistitem['checklist_id'] . '_' . ltrim(rtrim($checklistitem['checklist_name']));
            $data['checklist_item'][$index_name] = $checklistitem;
            $data['checklist_item'][$index_name]['answer'] = array();
            if (isset($answers_by_checklist[$checklistitem['checklist_id']])) {
                $data['checklist_item'][$index_name]['answer'] = $answers_by_checklist[$checklistitem['checklist_id']];
            }
        }

        $siteProperties = array();
        $x = 0;
        $items_by_group = array();
        foreach ($data['checklist_item'] as $ii) {
            $items_by_group[$ii['checklist_group']][] = $ii;
        }
        foreach ($properties['group'] as $gg) {
            $siteProperties[$x] = $gg;
            if (isset($items_by_group[$gg['group_id']])) {
                $siteProperties[$x]['checklists'] = $items_by_group[$gg['group_id']];
            }
            $x++;
        }

        $new = array();
        foreach ($siteProperties as $a) {
            $new[$a['parent_id']][] = $a;
        }
        $data['checklistsgrouping'] = $this->treeGenerator($new, $new[598]);
        // echo '<pre>' . var_export($data['checklistsgrouping'][5], true) . '</pre>';

        $is_ttd_exist =  $this->uri->segment(5);
        $data['visitID'] = $visitID;
        $data['visit_type'] = $visit_type;

        $schedule = $this->load->model('report_new_model')->schedule($visitID);
        $ttd = $this->load->model('report_new_model')->ttd($visitID);
        $data = array_merge($schedule, $data, $ttd);

        //ACCEPTANCE STATUS && ADDITIONAL NOTES
        $sql_acpt = "SELECT * 
						FROM t_trx_schedule_result_approval_sum
						WHERE visit_id = $visitID ORDER BY created_on DESC LIMIT 1";
        $query_acpt = $this->db->query($sql_acpt);
        $acceptance = array_shift($query_acpt->result_array());
        $data['acceptance_date'] = $acceptance['created_on'];
        if (!empty($acceptance)) {
            $data['acceptance_status'] = $acceptance['acceptance_status'];
            $data['notes'] = $acceptance['remark'];
        } else {
            $data['acceptance_status'] = 0;
            $data['notes'] = '';
        }
        // Chek Photo and Sign
        $query_fop = "SELECT visit_id,fop_photo,latitude,longitude,created_on
                            FROM t_trx_schedule_fop_photo 
	                        WHERE visit_id = $visitID  ORDER BY created_on DESC LIMIT 1";
        $query_sign = "SELECT visit_id,sign_type,image_sign,latitude,longitude,created_on
	                         FROM t_trx_schedule_sign
	                         WHERE sign_type = 2 AND visit_id = $visitID ORDER BY created_on DESC LIMIT 1";
        if (!empty($query_fop)) {
            $data['fop_status'] = 1;
            $data['fop_show'] = $query_fop;
        } else {
            $data['fop_status'] = 0;
        }
        if (!empty($query_sign)) {
            $data['sign_status'] = 1;
            $data['sign_show'] = $query_sign;
        } else {
            $data['sign_status'] = 0;
        }

        //PENDING ITEMS
        $sql_pending = "SELECT * 
						FROM t_trx_schedule_pending_item
						WHERE visit_id = $visitID ";
        $query_pending = $this->db->query($sql_pending);
        $data['pending'] = $query_pending->result_array();

        $db2 = $this->load->database('mysql', TRUE);
        $sql = "SELECT purch_doc,item,material_code,short_text,po_qty,region,ewo,link FROM tbl_gac_data WHERE link='" . $data['link_id'] . "'";
        $res = $db2->query($sql)->result();
        $data['data_gac'] = $res;

        // print_r($data);die();

        // if ($visit_type == "110" || $visit_type == "120" || $visit_type == "112") {
        //     $html .= $this->parser->parse('report_dinamis', $data);
        // } else {
        $sql = "SELECT user_name, mobile_number, image_signature, updated_on FROM t_mtr_user WHERE user_id='" . $data['pic_id'] . "'";
        $res = $this->db->query($sql)->result_array();
        $data['pic_alita_name'] = $res[0]['user_name'];
        $data['pic_alita_number'] = $res[0]['mobile_number'];
        // $path_if_empty = 'file/upload/fop_if_empty/'.$data['pic_id'].'.png';

        $blob = base64_decode(pg_unescape_bytea('iVBORw0KGgoAAAANSUhEUgAAAakAAAEwCAIAAACCJxmHAAAABGdBTUEAALGPC/xhBQAAAAlwSFlzAAAWJQAAFiUBSVIk8AAAA81JREFUeF7t1AEBAAAIwyD7l75BBiG4AfS4DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DitwHFLkPKHIfUOQ+oMh9QJH7gCL3AUXuA4rcBxS5DyhyH1DkPqDIfUCR+4Ai9wFF7gOK3AcUuQ8och9Q5D6gyH1AkfuAIvcBRe4DerYHfu9+TQMxZ5gAAAAASUVORK5CYII='));
        $image_ttd_alita = imagecreatefromstring($blob);
        $font_path = './file/verdana.ttf';

        $color_alita = imagecolorallocate($image_ttd_alita, 0, 0, 0); //black ( ,0,0,0)
        imagettftext($image_ttd_alita, 24, 0, 10, 40, $color_alita, $font_path, $res[0]['updated_on']);
        imagettftext($image_ttd_alita, 24, 0, 10, 80, $color_alita, $font_path, $res[0]['mobile_number']);
        imagejpeg($image_ttd_alita, './file/upload/ttd/' . $data['pic_id'] . '.png');    // Send Image to Browser
        imagedestroy($image_ttd_alita);    // Clear Memory

        $data['ttd_pic_alita'] = '/file/upload/ttd/' . $data['pic_id'] . '.png';

        $html .= $this->parser->parse('report_dinamis_other_type_new', $data);
        // }
        // }

        // Print text using writeHTMLCell()
        $pdf->writeHTML($html, true, false, true, false, '');
        //log_message('debug', 'writeHTMLToPDF3='.$html);
        // Close and output PDF document
        $date = date("Y-m-d hisa");
        $report_name = $this->load->model('report_new_model')->getReportName($visit_type);
        $pdf->SetTitle($report_name);

        ob_end_clean();
        $pdf->Output($report_name . ' ' . $date . '.pdf', 'I');
    }

    function imagecreatefromfile()
    {
        $filename  = 'D:\ttd.jpeg';
        if (!file_exists($filename)) {
            throw new InvalidArgumentException('File "' . $filename . '" not found.');
        }
        switch (strtolower(pathinfo($filename, PATHINFO_EXTENSION))) {
            case 'jpeg':
                return imagecreatefromjpeg($filename);
                break;

            case 'png':
                return imagecreatefrompng($filename);
                break;

            case 'gif':
                return imagecreatefromgif($filename);
                break;

            default:
                throw new InvalidArgumentException('File "' . $filename . '" is not valid jpg, png or gif image.');
                break;
        }
    }

    // 05/03/2021 by Ilham
    public function getDetailSummary($visit_id)
    {

        $user_id = $this->session->userdata('user_id');
        //start edited by Hadi 2018-03-29
        // edit query, same from visitsummary
        $sql2 = "SELECT cc.checklist_id, cc.checklist_name, cc.checklist_group, cc.has_photo, cc.total_photo, pr.latitude as lat_chk, pr.longitude as lon_chk,
        cc.mandatory, pr.checklist_result_id, pr.checklist_result pm_result,rsp.photo_name,rsp.created_on, rsp.photo_thumbnail, ttd.image_sign, ttd.latitude as ttdlat, ttd.longitude as ttdlong, fop.fop_photo, fop.latitude as foplat, fop.longitude as foplong, fopvideo.fop_video as fopvideo,
        (
           SELECT ap2.status from t_trx_checklist_result_approval ap2
           where visit_id = {$visit_id} and ap2.user_id = '{$user_id}'
         LIMIT 1
       )as status,
        cc.interface, gp.group_path
       FROM t_trx_schedule_checklist sc
       join t_mtr_checklist cc on cc.checklist_id = sc.checklist_id
       left join (
           select g.checklist_group, f_get_checklistgroup(g.checklist_group) group_path
           from (select distinct c2.checklist_group from t_trx_schedule_checklist s2
                 join t_mtr_checklist c2 on c2.checklist_id = s2.checklist_id
                 where s2.visit_id = $visit_id) g
       ) gp on gp.checklist_group IS NOT DISTINCT FROM cc.checklist_group
       left join t_trx_checklist_result pr on pr.checklist_id = sc.checklist_id
       and sc.visit_id = pr.visit_id
       left join t_trx_checklist_result_photo rsp on rsp.checklist_id = sc.checklist_id
       and sc.visit_id = rsp.visit_id
       left join (
                            select visit_id,
                            sign_type,
                            image_sign,
                            latitude,
                            longitude,
                            created_on
                            from t_trx_schedule_sign
                            where sign_type = 2 and visit_id = $visit_id
                            order by created_on desc
                            limit 1
       ) ttd on sc.visit_id = ttd.visit_id
       left join (
                            SELECT
                                visit_id,
                                fop_photo,
                                latitude,
                                longitude,
                                created_on
                            FROM t_trx_schedule_fop_photo 
                            WHERE visit_id = $visit_id
                            ORDER BY created_on DESC
                            LIMIT 1
                            ) fop on sc.visit_id = fop.visit_id
        left join (
                            SELECT
                                visit_id,
                                fop_video,
                                created_on
                            FROM t_trx_schedule_fop_video 
                            WHERE visit_id = $visit_id
                            ORDER BY created_on DESC
                            LIMIT 1
                            ) fopvideo on sc.visit_id = fopvideo.visit_id
                                            where sc.visit_id = $visit_id ";
        //end
        $x['checklists'] = $this->db->query($sql2)->result_array();

        $sql3 = "SELECT sc.checklist_id, ca.option_id, ca.option, ca.is_critical
	             FROM t_trx_schedule_checklist sc
	             join t_mtr_checklist_answer ca on ca.checklist_id = sc.checklist_id
	             where  ca.active = 1 and
	             sc.visit_id = $visit_id ";
        $x['answer'] = $this->db->query($sql3)->result_array();

        return $x;
    }

    public function siteProperties()
    {
        $this->db->select('cc.checklist_id, cc.checklist_group group_id, cc.checklist_name, cc.interface');
        $this->db->join('t_mtr_checklist_group gg', 'gg.checklist_group_id = cc.checklist_group');
        $this->db->where('gg.parent_id !=', 0);
        $this->db->where('gg.group_key', 'prev.maintenance');
        $this->db->where('gg.status', 1);
        $this->db->where('cc.status', 1);
        $this->db->order_by('cc.checklist_group, cc.checklist_group, gg.order_number, cc.order_number');
        $q = $this->db->get('t_mtr_checklist cc');
        $data["item"] = $q->result_array();

        $this->db->select('checklist_group_id group_id, group_name, parent_id');
        $this->db->where('checklist_group_id !=', 1);
        $this->db->where('group_key', 'prev.maintenance');
        $this->db->where('status', 1);
        $this->db->order_by('order_number, parent_id, checklist_group_id');
        $q = $this->db->get('t_mtr_checklist_group');
        $data["group"] = $q->result_array();

        $this->db->select('rr.checklist_id, oo.option_id, oo.option_value, oo.locked');
        $this->db->join('t_mtr_checklist_template rr', 'rr.option_id = oo.option_id');
        $this->db->where('oo.option_key', 'pm_visit_type');
        $this->db->where('oo.status', 1);
        $q = $this->db->get('t_mtr_checklist_options oo');
        $data["options"] = $q->result_array();

        return $data;
    }

    public function treeGenerator(&$list, $parent)
    {
        $tree = array();
        foreach ($parent as $k => $l) {
            if (isset($list[$l['group_id']])) {
                $l['sub_group'] = $this->treeGenerator($list, $list[$l['group_id']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }
    // 05/03/2021 by Ilham

    //END
}

// extend TCPF with custom functions
class MYPDF extends TCPDF
{

    public function Header()
    {
        // // Logo
        // $image_file = K_PATH_IMAGES.'logo_example.jpg';
        // $this->Image($image_file, '', '', 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // // Set font
        // $this->SetFont('helvetica', '', 9);
        // // Title
        // $this->Cell(0, 15, 'Engineering Form: Conformance/Non Conformance of Commisioning', 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        $this->SetX(20);
        // Set font
        $this->SetFont('helvetica', '', 8);
        // Page number
        $this->Cell(0, 10, 'ATP FORM MICROWAVE SYSTEM                                                                Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '                                                                                                 PT. XL AXIATA Tbk', 0, false, 'J', 0, '', 0, false, 'T', 'M');
    }
}
