<?php
defined('BASEPATH') or exit('No direct script access allowed');

class api_approval extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_approval_list()
    {
        try {
            $check_auth = $this->input->get('API_KEY');
            $page = $this->input->get('page') == null ? 1 : (int)$this->input->get('page');
            $size = $this->input->get('size') == null ? 10 : (int)$this->input->get('size');
            $offset = ($page - 1) * $size;
            $total_data = $this->db->count_all('t_trx_schedule');
            $last = ($total_data / $size) + 1;
            if ($check_auth != $this->core->encode('alita_eatp_approval')) {
                $this->output
                    ->set_status_header(403)
                    ->set_content_type('application/json')
                    ->set_output(
                        json_encode([
                            'status' => '403',
                            'message' => 'Unauthorized',
                        ])
                    )->_display();;
                exit;
            }

            $data = $this->db
                ->select('visit_id, tpd_id')
                ->from('t_trx_schedule')
                ->order_by('visit_id', 'DESC')
                ->limit($size, $offset)
                ->get()
                ->result_array();
            $result_array = [];
            $result_array['pagination'] = [
                'sizePage' => $size,
                'currentPage' => $page,
                'lastPage' => (int)$last,
                'totalData' => $total_data
            ];
            $result_array['list'] = [];
            foreach ($data as $value) {
                $approval = $this->db
                    ->select('user_id, updated_on, remark')
                    ->select('CASE 
                           WHEN status = 1 THEN \'Pass\'
                           WHEN status = 0 THEN \'Reject\'
                           ELSE \'Not Yet\'
                       END as desc_status', false)
                    ->from('t_trx_schedule_result_approval')
                    ->where('visit_id', $value['visit_id'])
                    ->order_by('visit_id, order_no', 'ASC')
                    ->get()
                    ->result_array();
                $value['approval'][] = $approval;
                $result_array['list'][] = $value;
            }

            $json_data = json_encode(array(
                'status' => '200',
                'message' => 'success',
                'data' => $result_array
            ));

            $this->output->set_content_type('application/json');
            $this->output->set_output($json_data);
        } catch (\Throwable $th) {
            $json_data = json_encode(array(
                'message' => $th->getMessage(),
            ));

            $this->output->set_content_type('application/json');
            $this->output->set_output($json_data);
        }
    }

    public function search_approval()
    {
        // YWxpdGFfZWF0cF9hcHByb3ZhbA
        // Check if api_key wrong
        try {
            $check_auth = $this->input->get('API_KEY');
            $visit_id = (int)$this->input->get('visit_id');
            $tpd_id = $this->input->get('tpd_id');

            if ($check_auth != $this->core->encode('alita_eatp_approval')) {
                $this->output
                    ->set_status_header(403)
                    ->set_content_type('application/json')
                    ->set_output(
                        json_encode([
                            'status' => '403',
                            'message' => 'Unauthorized',
                        ])
                    )->_display();
                exit;
            }
            $query = $this->db
                ->select('a.visit_id, b.tpd_id, a.user_id, a.updated_on, a.remark')
                ->select('CASE 
                           WHEN a.status = 1 THEN \'Pass\'
                           WHEN a.status = 0 THEN \'Reject\'
                           ELSE \'Not Yet\'
                       END as desc_status', false)
                ->from('t_trx_schedule_result_approval a')
                ->join('t_trx_schedule b', 'b.visit_id = a.visit_id', 'left');
            if (!empty($visit_id) && !empty($tpd_id)) {
                $data = $query->where('b.tpd_id', $tpd_id)
                    ->where('a.visit_id', $visit_id)
                    ->order_by('a.visit_id', 'DESC')
                    ->order_by('a.order_no', 'ASC')
                    ->get()
                    ->result();
            } elseif (!empty($visit_id) && empty($tpd_id)) {
                $data = $query
                    ->where('a.visit_id', $visit_id)
                    ->order_by('a.visit_id', 'DESC')
                    ->order_by('a.order_no', 'ASC')
                    ->get()
                    ->result();
            } elseif (empty($visit_id) && !empty($tpd_id)) {
                $data = $query
                    ->where('b.tpd_id', $tpd_id)
                    ->order_by('a.visit_id', 'DESC')
                    ->order_by('a.order_no', 'ASC')
                    ->get()
                    ->result();
            } else {
                $data = [];
            }

            if (empty($data)) {
                $this->output
                    ->set_status_header(404)
                    ->set_content_type('application/json')
                    ->set_output(
                        json_encode([
                            'status' => '404',
                            'message' => 'Data Not Found',
                        ])
                    )->_display();
                exit;
            }

            $temp_array = array();
            $result_array = array();
            foreach ($data as $value) {
                $visit_id_temp = $value->visit_id;
                $obj_approval = new stdClass;
                $obj_approval->user_id = $value->user_id;
                $obj_approval->updated_on = $value->updated_on;
                $obj_approval->remark = $value->remark;
                $obj_approval->desc_status = $value->desc_status;

                if (isset($temp_array[$visit_id_temp])) {
                    $temp_array[$visit_id_temp]['visit_id'] = $value->visit_id;
                    $temp_array[$visit_id_temp]['tpd_id'] = $value->tpd_id;
                    $temp_array[$visit_id_temp]['list_approval'][] = $obj_approval;
                } else {
                    $temp_array[$visit_id_temp]['visit_id'] = $value->visit_id;
                    $temp_array[$visit_id_temp]['tpd_id'] = $value->tpd_id;
                    $temp_array[$visit_id_temp]['list_approval'] = array($obj_approval);
                }
            }

            foreach ($temp_array as $value) {
                $result_array[] = $value;
            }

            $json_data = json_encode(array(
                'status' => '200',
                'message' => 'success',
                'data' => $result_array
            ));

            $this->output->set_content_type('application/json');
            $this->output->set_output($json_data);
        } catch (\Throwable $th) {
            $json_data = json_encode(array(
                'message' => $th->getMessage(),
            ));

            $this->output->set_content_type('application/json');
            $this->output->set_output($json_data);
            $this->output->_display();
        }
    }
}
