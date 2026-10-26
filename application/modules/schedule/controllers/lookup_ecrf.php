<?php

class lookup_ecrf extends MX_Controller
{
	// development: https://gateway.staging-egw.xl.co.id/
	private $api_key;
	private $url = "https://gateway.egw.xl.co.id/";
	private $auth;

	public function __construct()
	{
		parent::__construct();
		$this->mglobal->is_logged_in();
		$this->api_key = $this->config->item('ecrf_api_key');
		$this->load->library('parser');
	}

	public function index()
	{
		$data = array();
		$token = $this->input->get('token');
		$token_type = $this->input->get('token_type');
		$search = $this->input->get('query');
		$total_search = $this->input->get('total_search');

		if (empty($token) && empty($token_type)) {
			echo "<p>Koneksi API XL gagal!</p>";
			exit;
		}
		if ($this->input->get("cmd") == "getJson") {
			if ($this->core->is_ajax()) {
				$page_number = $this->input->get("page") == null ? 0 : $this->input->get("page") - 1;
				$page_size = $this->input->get("rows") == null ? 5 : $this->input->get("rows");
				if (empty($search)) {
					$result = $this->get_document_list($token, $token_type, $page_number, $page_size);
					$document_list = array();
					$document_list['total'] = $result->data->paginationDto->totalData;
					$document_list['rows'] = $result->data->list;
					echo json_encode($document_list);
					exit;
				} else {
					$result_search = $this->search_ecrf($token, $token_type, $search, $total_search);
					$document_list = array();
					$document_list['total'] = $result_search['total'];
					$document_list['rows'] = $result_search['rows'];
					echo json_encode($document_list);
					exit;
				}
			}
		}
		$data['token'] = $token;
		$data['token_type'] = $token_type;
		echo $this->parser->parse('lookup_ecrf', $data);
		exit;
	}

	private function get_document_list($token, $token_type, $page_number, $page_size)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->url . 'proxy/lms/ecrf/v1/s/post-submit-install-document-list?API_KEY=' . $this->api_key,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => '{
				"pageNumber": ' . $page_number . ',
				"pageSize": ' . $page_size . '
			}',
			CURLOPT_HTTPHEADER => array(
				'Authorization: ' . $token_type . ' ' . $token,
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return json_decode($response);
	}


	public function search_ecrf($token, $token_type, $ecrf_number, $total_row)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->url . 'proxy/lms/ecrf/v1/s/post-submit-install-document-list?API_KEY=' . $this->api_key,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => '{
				"pageNumber": 0,
				"pageSize": ' . $total_row . '
			}',
			CURLOPT_HTTPHEADER => array(
				'Authorization: ' . $token_type . ' ' . $token,
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$data =  json_decode($response);

		// Search ecrf number
		$array = $data->data->list;
		$result = [];
		$total_data = $data->data->paginationDto->totalData;
		foreach ($array as $value) {
			if ($value->ecrfNumber == $ecrf_number) {
				$result[] = $value;
			}
		}
		return [
			'total' => $total_data,
			'rows' => $result
		];
	}
}
