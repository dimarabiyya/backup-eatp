<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report extends CI_Controller {

    private $url = "https://sims.smartfren.com/report/mydbr/report.php?";
    private $user_now;

    function __construct() {
        parent::__construct();
        $this->mglobal->is_logged_in();
        $this->load->model('mreport');
        $this->user_now = $this->session->userdata('user_id');
//            $this->load->model('map_model');
    }

    public function index($report) {
        $this->$report();
    }

    private function DeclinationReason() {

        $reportId = "28";
        $hash = '7ddee7f5ac3fc9d9e8176f68415e13685e24a59c';
        $group = 4;

        $data['title'] = 'Preventive Maintenance';
        $data['subtitle'] = 'Declination Reason';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_164";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();
        $data['component'][1]['type'] = 'option';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'Region';
        $data['component'][1]['param'] = $this->mreport->getRegionList();
        $data['content_id'] = 'vreport';

        $this->load->view('template/smashing', $data);
        //$this->load->view("isr/form_reference", $data);
    }

    private function isrCurrentStateReport() {

        $reportId = "34";
        $hash = 'a3861686015a2420c2cc3ff38c035cf8777a8059';
        $group = 9;

        $data['title'] = 'ISR';
        $data['subtitle'] = 'Link Current State';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_351";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Chart Type';
        $data['component'][0]['param'] = $this->mreport->getChartType();
        $data['content_id'] = 'vreport';

        $this->load->view('template/smashing', $data);
        //$this->load->view("isr/form_reference", $data);
    }

    private function isrFinanceReport() {

        $reportId = "35";
        $hash = '677560e5ea988af0961ce9d54a61a218fa8af938';
        $group = 9;

        $data['title'] = 'ISR';
        $data['subtitle'] = 'Finance Report';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_351";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Region';
        $data['component'][0]['param'] = $this->mreport->getRegionListByPrevilege();
        $data['content_id'] = 'vreport';

        $this->load->view('template/smashing', $data);
        //$this->load->view("isr/form_reference", $data);
    }

    private function totalVisit() {
//        $reportId   ="";
//        $hash       ='';
//        $group      =0;

        $reportId = "89";
        $hash = 'd427ba060ad8afd3b79477f57de3f7a5122c695f';
        $group = 4;

        $data['title'] = 'Preventive Maintenance';
        $data['subtitle'] = 'Visit Status';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_164";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();
        $data['component'][1]['type'] = 'option';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'Region';
        $data['component'][1]['param'] = $this->mreport->getRegionList();
//        $data['component'] = 'option';
        $data['regions'] = $this->mreport->getRegionList();
        $data['years'] = $this->mreport->getYearRange2();
//        $data['years'] = $this->mreport->getYearRange();
        $data['content_id'] = 'vreport_pm_visit_status';
        $this->load->view('template/smashing', $data);
        //$this->load->view("isr/form_reference", $data);
    }

    private function achievement() {
//        $reportId   ="";
//        $hash       ='';
//        $group      =0;

        $reportId = "89";
        $hash = 'd427ba060ad8afd3b79477f57de3f7a5122c695f';
        $group = 4;

        $data['title'] = 'Preventive Maintenance';
        $data['subtitle'] = 'Achievement';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_164";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();
        $data['component'][1]['type'] = 'option';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'Region';
        $data['component'][1]['param'] = $this->mreport->getRegionList();
//        $data['component'] = 'option';
        $data['regions'] = $this->mreport->getRegionList();
        $data['years'] = $this->mreport->getYearRange();
        $data['content_id'] = 'vreport_pm_achievement';
        $this->load->view('template/smashing', $data);
        //$this->load->view("isr/form_reference", $data);
    }

    private function visitProfile() {
//        $reportId   ="";
//        $hash       ='';
//        $group      =0;

        $reportId = "89";
        $hash = 'd427ba060ad8afd3b79477f57de3f7a5122c695f';
        $group = 4;

        $data['title'] = 'Preventive Maintenance';
        $data['subtitle'] = 'Visit Profile';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_164";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();
        $data['component'][1]['type'] = 'option';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'Region';
        $data['component'][1]['param'] = $this->mreport->getRegionList();
//        $data['component'] = 'option';
        $data['regions'] = $this->mreport->getRegionList();
        $data['years'] = $this->mreport->getYearRange();
        $data['content_id'] = 'vreport_pm_visit_profile';
        $this->load->view('template/smashing', $data);
        //$this->load->view("isr/form_reference", $data);
    }

    private function totalVisit2() {
        $reportId = "89";
        $hash = 'd427ba060ad8afd3b79477f57de3f7a5122c695f';
        $group = 4;

        $data['title'] = 'Preventive Maintenance';
        $data['subtitle'] = 'Total Visit';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_164";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();
        $data['component'][1]['type'] = 'option';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'Region';
        $data['component'][1]['param'] = $this->mreport->getRegionList();
        $data['regions'] = $this->mreport->getRegionList();
        $data['years'] = $this->mreport->getYearRange();
        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
        //$this->load->view("isr/form_reference", $data);
    }

    private function totalVisitPerCluster() {
        $reportId = "26";
        $hash = '1a4a44353eff5b42cdec57c97a6c441d2ac28f02';
        $group = 4;

        $data['title'] = 'Preventive Maintenance';
        $data['subtitle'] = 'Visit Cluster';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_164";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();
        $data['component'][1]['type'] = 'option';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'Region';
        $data['component'][1]['param'] = $this->mreport->getRegionList();
        $data['regions'] = $this->mreport->getRegionList();
        $data['years'] = $this->mreport->getYearRange();
        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
        //$this->load->view("isr/form_reference", $data);
    }

    //PM new report aug 2014
    private function totalVisitYearly() {
        $reportId = "89";
        $hash = 'd427ba060ad8afd3b79477f57de3f7a5122c695f';
        $group = 4;

        $data['title'] = 'Preventive Maintenance';
        $data['subtitle'] = 'Total Visit Tahunan';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_164";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();
        $data['regions'] = $this->mreport->getRegionList();
        $data['years'] = $this->mreport->getYearRange();
        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function totalVisitMonthly() {
        $reportId = "91";
        $hash = '2e7829a4f2189ca1090cd39c7729e03f24908b09';
        $group = 4;

        $data['title'] = 'Preventive Maintenance';
        $data['subtitle'] = 'Total Visit Bulanan';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_164";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();

        $data['component'][1]['type'] = 'option';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'From';
        $data['component'][1]['param'] = $this->mreport->getMonthRange();

        $data['component'][2]['type'] = 'option';
        $data['component'][2]['id'] = 'u3';
        $data['component'][2]['name'] = 'To';
        $data['component'][2]['param'] = $this->mreport->getMonthRange();
        $data['regions'] = $this->mreport->getRegionList();
        $data['years'] = $this->mreport->getYearRange();
        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function pmAchievementYearly() {
        $reportId = "98";
        $hash = '39a4e4459d062a0af3743816dee8eee25b460d84';
        $group = 4;

        $data['title'] = 'Preventive Maintenance';
        $data['subtitle'] = 'PM Achivement Tahunan';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_164";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();
        $data['regions'] = $this->mreport->getRegionList();
        $data['years'] = $this->mreport->getYearRange();
        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function pmAchievementMonthly() {
        $reportId = "100";
        $hash = '69bdc522c0355de2f2b44896a099c08e41ee6732';
        $group = 4;

        $data['title'] = 'Preventive Maintenance';
        $data['subtitle'] = 'PM Achivement Bulanan';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_164";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();
        $data['component'][1]['type'] = 'option';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'From';
        $data['component'][1]['param'] = $this->mreport->getMonthRange();

        $data['component'][2]['type'] = 'option';
        $data['component'][2]['id'] = 'u3';
        $data['component'][2]['name'] = 'To';
        $data['component'][2]['param'] = $this->mreport->getMonthRange();
        $data['regions'] = $this->mreport->getRegionList();
        $data['years'] = $this->mreport->getYearRange();
        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function pmVisitProfileYearly() {
        $reportId = "102";
        $hash = '8920dbc26d84b2dc8af22a9313dabe05f6f10883';
        $group = 4;

        $data['title'] = 'Preventive Maintenance';
        $data['subtitle'] = 'PM Visit Profile Tahunan';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_164";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();
        $data['regions'] = $this->mreport->getRegionList();
        $data['years'] = $this->mreport->getYearRange();
        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    //End of PM new report aug 2014

    private function siDetailAudit() {
        $reportId = "30";
        $hash = 'd8cb957137917ac75e8c91f8351c1e30c5fc2380';
        $group = 5;

        $data['title'] = 'Site Inspection';
        $data['subtitle'] = 'Detail Audit';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_469";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();
        $data['component'][1]['type'] = 'option';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'Region';
        $data['component'][1]['param'] = $this->mreport->getRegionList();
        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function siCMEFinding() {
        $reportId = "31";
        $hash = '99939ff81abf9d1bedb9eb0eb1b7c9a216b24e01';
        $group = 5;

        $data['title'] = 'Site Inspection';
        $data['subtitle'] = 'CME Finding';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_469";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();
        $data['component'][1]['type'] = 'option';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'Region';
        $data['component'][1]['param'] = $this->mreport->getRegionList();
        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function siVisitScheduling() {
        $reportId = "32";
        $hash = '7030b6c832df3ff91db6ae6f689209c5f57aba6f';
        $group = 5;

        $data['title'] = 'Site Inspection';
        $data['subtitle'] = 'Visit Scheduling';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_469";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();

        $data['component'][1]['type'] = 'option';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'Month';
        $data['component'][1]['param'] = $this->mreport->getMonthRange();

        $data['component'][2]['type'] = 'option';
        $data['component'][2]['id'] = 'u3';
        $data['component'][2]['name'] = 'Region';
        $data['component'][2]['param'] = $this->mreport->getRegionList();
        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function siQAAudit() {
        $reportId = "33";
        $hash = '541984439eaa639a20dd98a5a745325238b7badd';
        $group = 5;

        $data['title'] = 'Site Inspection';
        $data['subtitle'] = 'Quality Assurance Audit';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_469";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();

        $data['component'][1]['type'] = 'option';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'Month';
        $data['component'][1]['param'] = $this->mreport->getMonthRange();

        $data['component'][2]['type'] = 'option';
        $data['component'][2]['id'] = 'u3';
        $data['component'][2]['name'] = 'Region';
        $data['component'][2]['param'] = $this->mreport->getRegionList();
        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function probmonProblemFigurePerRegion() {
        $reportId = "37";
        $hash = 'c4abc4eb1225639d0c9c26760d2ee74e24136c41';
        $group = 6;

        $data['title'] = 'Problem Monitoring';
        $data['subtitle'] = 'Problem Figure Per Region';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_484";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'calendar1';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'From';
        $data['component'][0]['param'] = date('Y-m-d');

        $data['component'][1]['type'] = 'calendar2';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'To';
        $data['component'][1]['param'] = date('Y-m-d');

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function probmonProblemFigurePerGroup() {
        $reportId = "36";
        $hash = 'a5d125abdbf4a6412ca70b89133885a61be7081b';
        $group = 6;

        $data['title'] = 'Problem Monitoring';
        $data['subtitle'] = 'Problem Figure Per Group';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_484";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'calendar1';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'From';
        $data['component'][0]['param'] = date('Y-m-d');

        $data['component'][1]['type'] = 'calendar2';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'To';
        $data['component'][1]['param'] = date('Y-m-d');

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function problemAgeing() {
        $reportId = "38";
        $hash = 'fe42b4b660ba9d03bb0293a92da0f29cca02743a';
        $group = 6;

        $data['title'] = 'Problem Monitoring';
        $data['subtitle'] = 'Problem Ageing';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_484";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'calendar1';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'From';
        $data['component'][0]['param'] = date('Y-m-d');

        $data['component'][1]['type'] = 'calendar2';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'To';
        $data['component'][1]['param'] = date('Y-m-d');

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function problemTrends() {
        $reportId = "39";
        $hash = '893adda2f2dc7faefac42e3b6029e3f474dcabc3';
        $group = 6;

        $data['title'] = 'Problem Monitoring';
        $data['subtitle'] = 'Problem Trends';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_484";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'calendar1';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'From';
        $data['component'][0]['param'] = date('Y-m-d');

        $data['component'][1]['type'] = 'calendar2';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'To';
        $data['component'][1]['param'] = date('Y-m-d');

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function generateUrl($r, $m, $h) {
        return $this->url . "r=$r&m=$m&h=$h&hdr=0";
    }

//START DISMANTLE
    private function disVendorPerformance() {
        $reportId = "62";
        $hash = '65260cd5672928c73591ce7ccfa5e779fad19be7';
        $group = 10;

        $data['title'] = 'Dismantle';
        $data['subtitle'] = 'Vendor Performance';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_353";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type2'] = 'calendar3';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'From';
        $data['component'][0]['param'] = date('Y-m-d');

        $data['component'][1]['type2'] = 'calendar4';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'To';
        $data['component'][1]['param'] = date('Y-m-d');

        $data['component'][3]['type2'] = 'option';
        $data['component'][3]['id'] = 'u3';
        $data['component'][3]['name'] = 'Vendor';
        $data['component'][3]['param'] = $this->mreport->getDismantleVendor();

        $data['content_id'] = 'dismantles/vreport';
        $this->load->view('template/smashing', $data);
    }

    private function disWODoneAgeing() {
        $reportId = "63";
        $hash = '02a37f82e3e9ed70ff598604c3936947a1af61e9';
        $group = 10;

        $data['title'] = 'Dismantle';
        $data['subtitle'] = 'Aging of WO Done Ageing Finish';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_353";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type2'] = 'calendar3';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'From';
        $data['component'][0]['param'] = date('Y-m-d');

        $data['component'][1]['type2'] = 'calendar4';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'To';
        $data['component'][1]['param'] = date('Y-m-d');

        $data['component'][3]['type2'] = 'option';
        $data['component'][3]['id'] = 'u3';
        $data['component'][3]['name'] = 'Vendor';
        $data['component'][3]['param'] = $this->mreport->getDismantleVendor();

        $data['content_id'] = 'dismantles/vreport';
        $this->load->view('template/smashing', $data);
    }

    private function disWONotDoneAgeing() {
        $reportId = "64";
        $hash = '57cab0634630bda3fd192ce4e55b0e3c8e3c61df';
        $group = 10;

        $data['title'] = 'Dismantle';
        $data['subtitle'] = 'Aging of WO Not Done Ageing Balance';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_353";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type2'] = 'calendar3';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'From';
        $data['component'][0]['param'] = date('Y-m-d');

        $data['component'][1]['type2'] = 'calendar4';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'To';
        $data['component'][1]['param'] = date('Y-m-d');

        $data['component'][3]['type2'] = 'option';
        $data['component'][3]['id'] = 'u3';
        $data['component'][3]['name'] = 'Vendor';
        $data['component'][3]['param'] = $this->mreport->getDismantleVendor();

        $data['content_id'] = 'dismantles/vreport';
        $this->load->view('template/smashing', $data);
    }

    private function disBatch() {
        $reportId = "56";
        $hash = 'f2152db6c99735aa506c575b05287bdc1737e49f';
        $group = 10;

        $data['title'] = 'Dismantle';
        $data['subtitle'] = 'Batch Report';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_353";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);
        $data['content_id'] = 'dismantles/vreport';

        $data['component'][0]['type'] = 'calendar1';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'From';
        $data['component'][0]['param'] = date('Y-m');

        $data['component'][1]['type'] = 'calendar2';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'To';
        $data['component'][1]['param'] = date('Y-m');

        $data['component'][3]['type'] = 'option';
        $data['component'][3]['id'] = 'u3';
        $data['component'][3]['name'] = 'Vendor';
        $data['component'][3]['param'] = $this->mreport->getDismantleVendor();

        $this->load->view('template/smashing', $data);
    }

    private function disProgressWO() {
        $reportId = "61";
        $hash = '0760ae0fdf297a50f68f9a5f999e486db8f09fce';
        $group = 10;

        $data['title'] = 'Dismantle';
        $data['subtitle'] = 'Dismantle Progress based on Work Order';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_353";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);


        $data['component'][0]['type'] = 'calendar1';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'From';
        $data['component'][0]['param'] = date('Y-m');

        $data['component'][1]['type'] = 'calendar2';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'To';
        $data['component'][1]['param'] = date('Y-m');

        $data['component'][3]['type'] = 'option';
        $data['component'][3]['id'] = 'u3';
        $data['component'][3]['name'] = 'Vendor';
        $data['component'][3]['param'] = $this->mreport->getDismantleVendor();

        $data['content_id'] = 'dismantles/vreport';
        $this->load->view('template/smashing', $data);
    }

    private function disProgressSOW() {
        $reportId = "57";
        $hash = 'd68ed705f90cfdac853b4bd34c93da63bf113fab';
        $group = 10;

        $data['title'] = 'Dismantle';
        $data['subtitle'] = 'Dismantle Progress based on Scope of Work';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_353";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);


        $data['component'][0]['type'] = 'calendar1';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'From';
        $data['component'][0]['param'] = date('Y-m');

        $data['component'][1]['type'] = 'calendar2';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'To';
        $data['component'][1]['param'] = date('Y-m');

        $data['component'][3]['type'] = 'option';
        $data['component'][3]['id'] = 'u3';
        $data['component'][3]['name'] = 'Vendor';
        $data['component'][3]['param'] = $this->mreport->getDismantleVendor();

        $data['content_id'] = 'dismantles/vreport';
        $this->load->view('template/smashing', $data);
    }

//END DISMANTLE

    private function btsType() {
        $reportId = "54";
        $hash = 'ba2a1d351ea72cac0331e3b9d339a5170fe815a9';
        $group = 8;

        $data['title'] = 'Site Info';
        $data['subtitle'] = 'BTS Type';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_353";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['content_id'] = 'site_info/report_view';
        $this->load->view('template/smashing', $data);
    }

    private function maintenanceClassification() {
        $reportId = "55";
        $hash = 'bac19a654525fd5409a9999139eb206fdc0fd097';
        $group = 8;

        $data['title'] = 'Site Info';
        $data['subtitle'] = 'Maintenance Classification';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_353";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['content_id'] = 'site_info/report_view';
        $this->load->view('template/smashing', $data);
    }

    private function statusProject() {
        $reportId = "52";
        $hash = '0c7270a27a751ab4de1d6b45f782a6ba85eac443';
        $group = 8;

        $data['title'] = 'Site Info';
        $data['subtitle'] = 'Status Project';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_353";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['content_id'] = 'site_info/report_view';
        $this->load->view('template/smashing', $data);
    }

    private function statusSitePerRegional() {
        $reportId = "53";
        $hash = '82566b94d696e9d49a2e4f9377367f842691eb3d';
        $group = 8;

        $data['title'] = 'Site Info';
        $data['subtitle'] = 'Status Site per Regional';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_353";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['content_id'] = 'site_info/report_view';
        $this->load->view('template/smashing', $data);
    }

    private function summaryTp() {
        $reportId = "51";
        $hash = '66c94d29059e400ef5ddcfc235cb452cdf0ebc99';
        $group = 8;

        $data['title'] = 'Site Info';
        $data['subtitle'] = 'Summary TP';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_353";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['content_id'] = 'site_info/report_view';
        $this->load->view('template/smashing', $data);
    }

    private function siteType2() {
        $reportId = "40";
        $hash = '9744671bbd470ff790aada61993e23fac994fa80';
        $group = 8;

        $data['title'] = 'Site Info';
        $data['subtitle'] = 'Site Type';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_353";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['content_id'] = 'site_info/report_view';
        $this->load->view('template/smashing', $data);
    }

    private function btsOptimTotalSite() {
        if ($this->input->post() == FALSE) {
            $data['title'] = 'Site Info';
            $data['subtitle'] = 'BTS Optim Total Site Summary';
            $data['detail_title'] = 'Report';
            $data['width'] = "100%";
            $data['height'] = "1200px";
            $data['menu_number'] = "#menu_353";
            // {^_^) andedi 20141211
            //        $data['component'][0]['type']   = 'option';
            //        $data['component'][0]['id']     = 'u1';
            //        $data['component'][0]['name']   = 'Year';
            //        $data['component'][0]['param']  = $this->mreport->getYearRange();
            //        
            //        $data['component'][1]['type']   = 'option';
            //        $data['component'][1]['id']     = 'u2';
            //        $data['component'][1]['name']   = 'Month';
            //        $data['component'][1]['param']  = $this->mreport->getMonthRange2();

            $data['years'] = $this->mreport->getYearRange();
            $data['months'] = $this->mreport->getMonthRange2();
//            $url                = $this->generateUrl($reportId,$group,$hash);
//            $data['url']        = $url.'&u3='.$this->session->userdata('user_id');
            $data['user_id'] = $this->session->userdata('user_id'); // {^_^) andedi 20141124
            $data['content_id'] = 'vreport_bts_optim';
            $this->load->view('template/smashing', $data);
        } else {
            $group = 8;
            $reportId = "121";
            $hash = '14cc0a0437b836756213eb25cd4e3c66d165b1f8';
            $param = '';
            $user = $this->user_now;

            $year_from = $this->input->post('year_from');
            $year_to = $this->input->post('year_to');
            $month = $this->input->post('month');

            if ($year_from == $year_to) {
                if ($month > 0) {
                    $reportId = '126';
                    $hash = 'eeca52cc64a76e9d5b0bb0ca40b9d8fc1c9523af';
                    $param .= "&u1={$year_from}&u2={$month}&u3={$user}";
                } else {
                    $reportId = '124';
                    $hash = 'c021287d0ddfcafedbe6958bf757d9ed84e7aaf3';
                    $param .= "&u1={$year_from}&u2={$user}";
                }
            } else {
                if ($month > 0) {
                    $reportId = '123';
                    $hash = '7824953d4408102358c423aa2f6804727d582536';
                    $param .= "&u1={$year_from}&u2={$year_to}&u3={$month}&u4={$user}";
                } else {
                    $reportId = '125';
                    $hash = 'e8a61b7535669119e71f62ccb3dff4ea56c9c0d4';
                    $param .= "&u1={$year_from}&u2={$year_to}&u3={$user}";
                }
            }

            $data = array(
                'status' => 'success',
                'message' => 'Report generated',
                'result' => $this->generateUrl($reportId, $group, $hash) . $param
            );

            echo json_encode($data);
        }
    }

    private function qsReportSummary() {
        $reportId = "44";
        $hash = '2363a0556ab89c152a124dc2dedd6179e35bc05a';
        $group = 7;

        $data['title'] = 'Quarantine Site';
        $data['subtitle'] = 'Quarantine Summary';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_354";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][2]['type'] = 'option';
        $data['component'][2]['id'] = 'u3';
        $data['component'][2]['name'] = 'Region';
        $data['component'][2]['param'] = $this->mreport->getRegionList();

        $data['component'][0]['type'] = 'calendar1';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'From';
        $data['component'][0]['param'] = date('Y-m-d');

        $data['component'][1]['type'] = 'calendar2';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'To';
        $data['component'][1]['param'] = date('Y-m-d');

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function qsReportStatus() {
        $reportId = "58";
        $hash = '8cdf4e11abfc2c675c1264b4ddba787c20591256';
        $group = 7;

        $data['title'] = 'Quarantine Site';
        $data['subtitle'] = 'Quarantine Status';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_354";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][2]['type'] = 'option';
        $data['component'][2]['id'] = 'u3';
        $data['component'][2]['name'] = 'Region';
        $data['component'][2]['param'] = $this->mreport->getRegionList();

        $data['component'][0]['type'] = 'calendar1';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'From';
        $data['component'][0]['param'] = date('Y-m-d');

        $data['component'][1]['type'] = 'calendar2';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'To';
        $data['component'][1]['param'] = date('Y-m-d');

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function qsReportAgeing() {
        $reportId = "59";
        $hash = '47fdb29ce9278fa85eb9e827790540ad211b2ea8';
        $group = 7;

        $data['title'] = 'Quarantine Site';
        $data['subtitle'] = 'Quarantine Ageing';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_354";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][2]['type'] = 'option';
        $data['component'][2]['id'] = 'u3';
        $data['component'][2]['name'] = 'Region';
        $data['component'][2]['param'] = $this->mreport->getRegionList();

        $data['component'][0]['type'] = 'calendar1';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'From';
        $data['component'][0]['param'] = date('Y-m-d');

        $data['component'][1]['type'] = 'calendar2';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'To';
        $data['component'][1]['param'] = date('Y-m-d');

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function qsReportTrends() {
        $reportId = "60";
        $hash = 'd2034ff0328fa7513592d0069867c288413d7785';
        $group = 7;

        $data['title'] = 'Quarantine Site';
        $data['subtitle'] = 'Quarantine Trends';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_354";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][2]['type'] = 'option';
        $data['component'][2]['id'] = 'u3';
        $data['component'][2]['name'] = 'Region';
        $data['component'][2]['param'] = $this->mreport->getRegionList();

        $data['component'][0]['type'] = 'calendar1';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'From';
        $data['component'][0]['param'] = date('Y-m-d');

        $data['component'][1]['type'] = 'calendar2';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'To';
        $data['component'][1]['param'] = date('Y-m-d');

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function projectDashboardZte() {
        $reportId = "65";
        $hash = '352c85be45c9f211e3be04fdc20131e020c32efc';
        $group = 11;

        $data['title'] = 'Project Monitoring';
        $data['subtitle'] = 'Project Dashboard ZTE';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_462";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function projectDashboardSsg() {
        $reportId = "66";
        $hash = '4a9de675d80e1122405aeac90628d78e2adc93ae';
        $group = 11;

        $data['title'] = 'Project Monitoring';
        $data['subtitle'] = 'Project Dashboard SSG';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_462";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function projectProgressZte() {
        $reportId = "67";
        $hash = 'ce5ba566b53e04aad76bc297507fcfc1d47c9d39';
        $group = 11;

        $data['title'] = 'Project Monitoring';
        $data['subtitle'] = 'Project Progress ZTE';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_462";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function projectProgressSsg() {
        $reportId = "68";
        $hash = '42ecff08483e131b9a895c6eebb740d0449d0324';
        $group = 11;

        $data['title'] = 'Project Monitoring';
        $data['subtitle'] = 'Project Progress SSG';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_462";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function projectStatusMw() {
        $reportId = "69";
        $hash = '0f4119d6e622a49a1c1c698cb0bad3f9d9861ff8';
        $group = 11;

        $data['title'] = 'Project Monitoring';
        $data['subtitle'] = 'Project Status MW';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_462";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function projectPerRegion() {
        $reportId = "70";
        $hash = '757a54bebd85ba79e82d3c4cfd1e7c855ea77d11';
        $group = 11;

        $data['title'] = 'Project Monitoring';
        $data['subtitle'] = 'Project per Region';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_462";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function projectSummary() {
        $reportId = "71";
        $hash = '24cbd5f2901fe57939b359c3ca0e855bca5ae69a';
        $group = 11;

        $data['title'] = 'Project Monitoring';
        $data['subtitle'] = 'Project Summary';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_462";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function btsOptimizationSummary() {
        if ($this->input->post() == FALSE) {
            $data['title'] = 'Site Info';
            $data['subtitle'] = 'BTS Optimization Summary Reason for Change';
            $data['detail_title'] = 'Report';
            $data['width'] = "100%";
            $data['height'] = "1200px";
            $data['menu_number'] = "#menu_334";

            $data['years'] = $this->mreport->getYearRange();
            $data['months'] = $this->mreport->getMonthRange2();
            $data['user_id'] = $this->session->userdata('user_id');
            $data['content_id'] = 'vreport_bts_optim_sum';
            $this->load->view('template/smashing', $data);
        } else {
            $group = '8';
            $reportId = '';
            $hash = '';
            $param = '';
            $user = $this->user_now;

            $year_from = $this->input->post('year_from');
            $year_to = $this->input->post('year_to');
            $month = $this->input->post('month');

            if ($year_from == $year_to) {
                if ($month == 0) $month=12;
                $reportId = '127';
                $hash = '51c27523983b92a2f8ba60775a6eab3a1f074a18';
                $param = "&u1={$year_from}&u2={$year_to}&u3={$month}&u4={$user}";
            } else {
                if ($month > 0) {
                    $reportId = '127';
                    $hash = '51c27523983b92a2f8ba60775a6eab3a1f074a18';
                    $param = "&u1={$year_from}&u2={$year_to}&u3={$month}&u4={$user}";
                } else {
                    $reportId = '128';
                    $hash = '5a483db0013e0c720c37531e4f0a7bce0d0c3c3a';
                    $param = "&u1={$year_from}&u2={$year_to}&u3={$user}";
                }
            }

            $data = array(
                'status' => 'success',
                'message' => 'Report generated',
                'result' => $this->generateUrl($reportId, $group, $hash) . $param
            );

            echo json_encode($data);
        }
//        $reportId = 72;
//        $hash = 'cde6753ed456aa2a3c16a5d80ec0640204c8824f';
//        $group = 8;
//
//        $data['title'] = 'Site Info';
//        $data['subtitle'] = 'BTS Optimization Summary';
//        $data['detail_title'] = 'Report';
//        $data['width'] = "100%";
//        $data['height'] = "1200px";
//        $data['menu_number'] = "#menu_334";
//        $url = $this->generateUrl($reportId, $group, $hash);
//        $data['url'] = $url . '&u3=' . $this->session->userdata('user_id');
//
//        $data['component'][0]['type'] = 'option';
//        $data['component'][0]['id'] = 'u1';
//        $data['component'][0]['name'] = 'Year';
//        $data['component'][0]['param'] = $this->mreport->getYearRange();
//
//        $data['component'][1]['type'] = 'option';
//        $data['component'][1]['id'] = 'u2';
//        $data['component'][1]['name'] = 'Month';
//        $data['component'][1]['param'] = $this->mreport->getMonthRange();
//
//        $data['content_id'] = 'vreport';
//        $this->load->view('template/smashing', $data);
    }

    private function pln_usage_summary() {
        $reportId = 77;
        $hash = 'e52a93608667c645167dade9f78c8a758b75ff57';
        $group = 8;

        $data['title'] = 'Site Info';
        $data['subtitle'] = 'PLN Usage Summary';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_334";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();

        $data['component'][1]['type'] = 'option';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'Month';
        $data['component'][1]['param'] = $this->mreport->getMonthRange();

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function pln_usage_data() {
        $reportId = 78;
        $hash = '5f9059503b90bc8296fa96cda3472dcb50600b9f';
        $group = 8;

        $data['title'] = 'Site Info';
        $data['subtitle'] = 'PLN Data';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_334";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['component'][0]['type'] = 'option';
        $data['component'][0]['id'] = 'u1';
        $data['component'][0]['name'] = 'Year';
        $data['component'][0]['param'] = $this->mreport->getYearRange();

        $data['component'][1]['type'] = 'option';
        $data['component'][1]['id'] = 'u2';
        $data['component'][1]['name'] = 'Month';

        $months = $this->mreport->getMonthRange();
        $months[0]['value'] = 0;
        $months[0]['label'] = 'All';
        sort($months);

        $data['component'][1]['param'] = $months;

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    private function site_figure() {
        $reportId = 82;
        $hash = 'a6a1ccc5ca4ffb6a614cb59d8aa6341f6ddd5108';
        $group = 8;

        $data['title'] = 'Site Info';
        $data['subtitle'] = 'Site Figure';
        $data['detail_title'] = 'Report';
        $data['width'] = "100%";
        $data['height'] = "1200px";
        $data['menu_number'] = "#menu_334";
        $data['url'] = $this->generateUrl($reportId, $group, $hash);

        $data['content_id'] = 'vreport';
        $this->load->view('template/smashing', $data);
    }

    ########################################################################
    #######################################################################
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */