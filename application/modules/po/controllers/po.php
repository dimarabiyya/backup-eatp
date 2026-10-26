<?php

class Po extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('po_model');
		$this->load->model('reference_model');
		$this->load->library(array('PHPExcel', 'PHPExcel/IOFactory'));
		$this->load->library('parser');
	}

	public function index()
	{
		$this->_check_access();

		if ($this->core->is_ajax()) {
			if ($this->input->get('cmd') == 'getPoList') {
				$rows = $this->po_model->poList();

				echo json_encode($rows);
				exit;
			}
		}

		if ($this->input->server('REQUEST_METHOD') == 'DELETE') {
			$id = (int) $this->core->decode($this->input->get('poId'));

			$this->po_model->delete($id);

			$json = array('status' => 'success', 'message' => 'Data deleted successfully.');
			echo json_encode($json);
			exit;
		}

		$rows = $this->mglobal->getAllRegions();

		$regions = array();
		foreach ($rows as $v) {
			$regions[$v->region_id] = $v->region_name;
		}

		$data['regions'] = json_encode($regions);
		$data['content_id'] = 'index';

		$this->load->view('template/smashing', $data);
	}

	public function add()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$data  = $this->input->post();
			//print_r($data);die;
			$validation_rules = array(
				array(
					'field' => 'po_no',
					'label' => 'PO No',
					'rules' => 'trim|required',
				),
				array(
					'field' => 'po_date',
					'label' => 'PO Date',
					'rules' => 'required',
				),
				array(
					'field' => 'region',
					'label' => 'Region',
					'rules' => 'required'
				),
				array(
					'field' => 'itemtype[]',
					'label' => 'Item',
					'rules' => 'required|greater_than[0]',
				),
				array(
					'field' => 'ewo[]',
					'label' => 'EWO/NBWO No.',
					'rules' => 'required',
				)
			);

			$this->form_validation->set_rules($validation_rules);
			$this->form_validation->set_error_delimiters('<li>', '</li>');

			if ($this->form_validation->run()) {
				/*Save First Data PO*/
				$post = new stdClass;
				$post->po_no = $this->input->post("po_no");
				$post->po_date = date("Y-m-d", strtotime($this->input->post("po_date")));
				$post->region_id = (int) $this->input->post("region");
				$post->created_on = date('Y-m-d H:i:s');
				$post->created_by = $this->session->userdata('user_id');
				$current_po = $this->po_model->getByPoNo($post->po_no);
				if ($current_po) {
					$data = new stdClass;
					$data->is_deleted = 0;
					$data->updated_by = $this->session->userdata('user_id');
					$data->updated_on = date('Y-m-d H:i:s');
					$data->po_date = date("Y-m-d", strtotime($this->input->post("po_date")));
					$data->region_id = (int) $this->input->post("region");
					$this->po_model->update($data, $current_po->po_id);
					$po_id = $current_po->po_id;
				} else {
					/*Call Insert data*/
					$po_id = $this->po_model->insert($post);
				}

				$message = "Data added successfully.";
				/*Iterate PO LINK*/
				/*assign to variable*/

				$sites_ne = $this->input->post("sitene");
				$sites_fe = $this->input->post("sitefe");
				$link_ewo  = $this->input->post("ewo");
				$link_desc  = $this->input->post("description");
				$link_type = $this->input->post("type");
				$link_item_type = $this->input->post("itemtype");
				$link_order_type = $this->input->post("order_type");

				/*end assign to variable*/
				for ($i = 0; $i < count($sites_ne); $i++) {

					if (empty($sites_ne[$i]) || empty($sites_fe[$i])) {
						$message = "Missing Link when you update data";
					} else {
						$ewo = str_replace("/", "-", $link_ewo[$i]);
						$ewo = str_replace(" ", "", $ewo);

						/*Save Each Site Link*/
						$post_site =  new stdClass;
						$post_site->site_id_ne  =  $sites_ne[$i];
						$post_site->site_id_fe  = $sites_fe[$i];
						$post_site->link_id  =  $sites_ne[$i] . "<>" . $sites_fe[$i];
						$post_site->ant_model = "-";
						$post_site->created_on = date('Y-m-d H:i:s');
						$post_site->created_by = $this->session->userdata('user_id');
						$link_id_int = $this->reference_model->insert_site_link($post_site);
						// echo json_encode($post_site);
						// echo "<hr/>";
						/*Save PO Link*/
						$post_poli = new stdClass;
						$post_poli->po_id = $po_id;
						$post_poli->link_id_int = $link_id_int;
						$post_poli->ewo_no = $ewo;
						$post_poli->po_description = $link_desc[$i];
						$post_poli->po_type = $link_type[$i];
						$post_poli->item = $link_item_type[$i];
						$post_poli->order_type = $link_order_type[$i];
						$this->reference_model->insert_po_link($post_poli);
					}
				}
				$json = array('status' => 'success', 'message' => $message);
				echo json_encode($json);
				exit;
			} else {
				$json = array('status' => 'error', 'message' => validation_errors());
				echo json_encode($json);
				exit;
			}
		} else {
			/*Get All The Region Data*/
			$row_regions = $this->reference_model->regionList();
			$data['region'] = $row_regions;
			$data['title'] = "Add PO";
			$data['form_url'] = base_url() . "po/add";
			$data['po_id'] = $this->core->encode(0); /*No Id*/
			$data['content_id'] = 'po_form';
			$this->load->view('template/smashing', $data);
		}
	}
	public function detail($id)
	{
		$id = $this->core->decode($id);
		if ($this->input->server('REQUEST_METHOD') == 'GET') {
			$rs_po = $this->po_model->getPo($id);
			if ($rs_po) {
				$json = array('status' => 'success', 'message' => $rs_po);
				echo json_encode($json);
				exit;
			} else {
				$json = array('status' => 'error', 'message' => "Data is not found");
				echo json_encode($json);
				exit;
			}
		} else {
			$json = array('status' => 'error', 'message' => "Invalid Request");
			echo json_encode($json);
			exit;
		}
	}
	public function edit($id)
	{
		$id = $this->core->decode($id);

		if ($this->input->server('REQUEST_METHOD') == 'GET') {
			$rs_po = $this->po_model->getPo($id);
			/*Get All The Region Data*/
			$row_regions = $this->reference_model->regionList();
			$data['region'] = $row_regions;
			$data['title'] = "Edit  PO";
			$data["po"] = $rs_po;
			$data['form_url'] = base_url() . "po/edit/" . $this->core->encode($id);
			$data['po_id'] = $this->core->encode($id); /*No Id*/
			$data['content_id'] = 'po_form_edit';
			$this->load->view('template/smashing', $data);
		}
		// echo '<pre>' . var_export($data, true) . '</pre>';
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$data  = $this->input->post();
			//print_r($data);die;		
			/*Check Validasi*/
			$validation_rules = array(
				array(
					'field' => 'po_no',
					'label' => 'PO No',
					'rules' => 'trim|required',
				),
				array(
					'field' => 'po_date',
					'label' => 'PO Date',
					'rules' => 'required',
				),
				array(
					'field' => 'region',
					'label' => 'Region',
					'rules' => 'required'
				),
				array(
					'field' => 'itemtype[]',
					'label' => 'Item',
					'rules' => 'required|greater_than[0]',
				),
				array(
					'field' => 'ewo[]',
					'label' => 'EWO/NBWO No.',
					'rules' => 'required',
				)
			);

			$this->form_validation->set_rules($validation_rules);
			$this->form_validation->set_error_delimiters('<li>', '</li>');

			if ($this->form_validation->run()) {
				/*Uodate First Data PO*/
				$post = new stdClass;
				$post->po_no = $this->input->post("po_no");
				$post->po_date = date("Y-m-d", strtotime($this->input->post("po_date")));
				$post->region_id = (int) $this->input->post("region");
				$post->updated_on = date('Y-m-d H:i:s');
				$post->updated_by = $this->session->userdata('user_id');
				/*Call Insert data*/
				$po_id = $id;
				$this->po_model->update($post, $po_id);

				/*Loop PO Link*/
				/*Iterate PO LINK*/
				$message = "Data updated successfully.";
				/*assign to variable*/
				$link_id_int = $this->input->post("link_id_int");
				$sites_ne = $this->input->post("sitene");
				$sites_fe = $this->input->post("sitefe");

				$link_ewo  = $this->input->post("ewo");
				$link_desc  = $this->input->post("description");
				$link_type = $this->input->post("type");
				$link_item_type = $this->input->post("itemtype");
				$link_order_type = $this->input->post("order_type");

				for ($i = 0; $i < count($sites_ne); $i++) {
					/*Check Site Link is Exist*/
					$link = $sites_ne[$i] . "<>" . $sites_fe[$i];
					$rs_link = $this->reference_model->getLink($link);
					if (!empty($link_id_int[$i])) {
						$check_po_link = $this->reference_model->getLinkPO($po_id, $link_id_int[$i]);
					} else {
						$check_po_link = [];
					}
					/*Update data Poli*/
					$post_poli = new stdClass;
					$post_poli->ewo_no = $link_ewo[$i];
					$post_poli->po_description = $link_desc[$i];
					$post_poli->po_type = $link_type[$i];
					$post_poli->item = $link_item_type[$i];
					$post_poli->order_type = $link_order_type[$i];

					// print_r(!empty($check_po_link));die();

					if (!empty($rs_link)) {
						/*IF YES Update info PO LINK*/
						$this->reference_model->update_po_link($post_poli, $po_id, $link_id_int[$i]);
						// echo '<pre> 1' . var_export($link_id_int[$i], true) . '</pre>';
					}
					if (!empty($check_po_link)) {
						$data_po_link = new stdClass;
						$data_po_link->site_id_ne = $sites_ne[$i];
						$data_po_link->site_id_fe = $sites_fe[$i];
						$data_po_link->link_id = $link;
						$this->reference_model->update_site_link($data_po_link, $link_id_int[$i]);
						// echo '<pre> 2' . var_export($link_id_int[$i], true) . '</pre>';
					} else {
						/*IF NO INSERT info PO LINK*/
						/*Insert into Site Link*/
						/*Save Each Site Link*/
						if (empty($sites_ne[$i]) || empty($sites_fe[$i])) {
							$message = "Missing Link when you udpate data";
						} else {
							$post_site =  new stdClass;
							$post_site->site_id_ne  =  $sites_ne[$i];
							$post_site->site_id_fe  = $sites_fe[$i];
							$post_site->link_id  =  $sites_ne[$i] . "<>" . $sites_fe[$i];
							$post_site->ant_model = "-";
							$post_site->created_on = date('Y-m-d H:i:s');
							$post_site->created_by = $this->session->userdata('user_id');
							$link_id_int = $this->reference_model->insert_site_link($post_site);
							/*Insert into PO Link*/
							$post_poli->po_id = $po_id;
							$post_poli->link_id_int = $link_id_int;
							$this->reference_model->insert_po_link($post_poli);
						}
					}
				}
				$json = array('status' => 'success', 'message' => $message);
				echo json_encode($json);
				exit;
			} else {
				$json = array('status' => 'error', 'message' => validation_errors());
				echo json_encode($json);
				exit;
			}
		}
	}

	public function exportPo()
	{
		error_reporting(0);
		header("Content-type: application/vnd-ms-excel");
		header("Content-Disposition: attachment; filename=PO_Export_" . date('Y-m-d-H:i:s') . ".xls");


		$data = $this->po_model->exportPo()->result();

		echo "<table>
                           <tr style='background-color: #ffff00; font-weight:bold; text-align:center;'>
                            <td>PO NO </td>
							<td>PO Date </td>
							<td>Item</td>
							<td>PO Type </td>
							<td>Description </td>
							<td>Region </td>
							<td>Project Type </td>
							<td>Link ID </td>
							<td>EWO No</td>
							<td>Site ID NE </td>
							<td>Area </td>
							<td>Site Address NE </td>
							<td>Latitute NE </td>
							<td>Longitude NE </td>
							<td>Site Type NE </td>
							<td>Site ID FE </td>
							<td>Area </td>
							<td>Site Address FE </td>
							<td>Latitute FE </td>
							<td>Longitude FE </td>
							<td>Site Type FE </td>
                           </tr>";
		foreach ($data as $row) {
			echo "<tr>
                            <td>" . $row->po_no . "</td>
							<td>" . $row->po_date . "</td>
							<td>" . $row->item . "</td>
							<td>" . $row->po_type . "</td>
							<td>" . $row->description . "</td>
							<td>" . $row->region_name . "</td>
							<td>" . $row->project_type . "</td>
							<td>" . $row->link_id . "</td>
							<td>" . $row->ewo_no . "</td>
							<td>" . $row->site_id_ne . "</td>
							<td>" . $row->area_ne . "</td>
							<td>" . $row->site_address_ne . "</td>
							<td>" . $row->site_latitude_ne . "</td>
							<td>" . $row->site_longitude_ne . "</td>
							<td>" . $row->site_type_ne . "</td>
							<td>" . $row->site_id_fe . "</td> 
							<td>" . $row->area_fe . " </td>
							<td>" . $row->site_address_fe . " </td>
							<td>" . $row->site_latitude_fe . "</td>
							<td>" . $row->site_longitude_fe . "</td>
							<td>" . $row->site_type_fe . "</td>
                           </tr>";
		}

		echo  "</table>";


		redirect(base_url('po'));
	}



	public function view($view)
	{
		$this->load->view($view);
	}

	private function _check_access()
	{
		if (!$this->session->userdata('is_login')) {
			if ($this->core->is_ajax()) {
				echo json_encode(array(
					'loggedin' => FALSE,
					'message' => 'You are not logged in. You will be redirected to login page.',
					'redirect_url' => site_url('login')
				));
				exit;
			} else {
				redirect('login');
			}
		}
	}
	public function deletepolink()
	{
		$po_id = $this->core->decode($this->input->post("po_id"));
		$link = $this->input->post("site_ne") . "<>" . $this->input->post("site_fe");
		$rs_site_link = $this->reference_model->getLink($link);
		if ($rs_site_link) {
			$this->po_model->deletepolink($po_id, $rs_site_link->link_id_int);
			$json = array('status' => 'success', 'message' => 'Data Delete successfully.');
			echo json_encode($json);
			exit;
		} else {
			$json = array('status' => 'error', 'message' => "Data is not found");
			echo json_encode($json);
			exit;
		}
	}
	/*Edit POLINK when Hold*/
	public function holdpoli($id, $link_id)
	{
		$po_id = $this->core->decode($id);
		$link_id = $this->core->decode($link_id);
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$data = new stdClass;
			$data->progress_status = 2;/*SIGN untuk HOLD*/
			$data->hold_remark = $this->input->post("remark");
			$this->po_model->updatePOLIProgressStatus($po_id, $link_id, $data);
			$json = array('status' => 'success', 'message' => 'This Data is Hold sucessfully');
			echo json_encode($json);
			exit;
		} else {
			$data["title"] = "Hold this PO Link Data";
			echo $this->parser->parse('hold_polink', $data);
			exit;
		}
	}
	/*Edit POLINK when done*/
	public function donepolink($id, $link_id)
	{
		$po_id = $this->core->decode($id);
		$link_id = $this->core->decode($link_id);
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$data = new stdClass;
			$data->progress_status = 4;/*SIGN untuk Done*/
			$this->po_model->updatePOLIProgressStatus($po_id, $link_id, $data);
			$json = array('status' => 'success', 'message' => 'This Data is updated sucessfully');
			echo json_encode($json);
			exit;
		} else {
			$json = array('status' => 'error', 'message' => 'Something wrong, contact your administrator');
			echo json_encode($json);
			exit;
		}
	}
}
