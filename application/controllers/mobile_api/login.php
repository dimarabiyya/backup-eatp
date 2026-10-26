<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	 //error code normal -> misal json 100
	 //error code keyword salah -> 200
	 // item ada ga valid -> 300

	public function __construct()
	{
	    parent::__construct();
    	// $this->load->model('mobile/login', 'mobile');
	}

	public function index()
	{
		$data = array(
    		'usri' => $this->input->get_post('usri'), 
    		'usrp' => $this->input->get_post('usrp'),
    		'usrg' => $this->input->get_post('usrg'),
    		// "imei" => $this->input->post('imei'),
    		// "imsi" => $this->input->post('imsi')
    		);

    	$this->load->model('Mobile_model');
    	
    	$query_result = $this->Mobile_model->check_user_login($data['usri'], 
    		md5($data['usrp']), $data['usrg'], 0);

    	if ($query_result == NULL)
    	{
    		echo json_encode(
    			array(
	    			"req" => "login"
	    			, "res" => "not ok"
	    			, "msg" => "You are not a registered user."
    			)
    		);
			exit;
		}
		else if($query_result[0]['is_login'] == '1'){
			echo json_encode(
    			array(
	    			"req" => "login"
	    			, "res" => "not ok"
	    			, "msg" => "You are not an authenticated user."
    			)
    		);
			exit;
		}
		else
		{
			$mobile_token = $this->generateHash();

			if ($this->Mobile_model->update_user_login_status($data['usri'], $mobile_token)) {
    			echo json_encode(
	    			array(
		    			"req" => "login"
		    			, "res" => "ok"
		    			, "msg" => "You are an authenticated user."
		    			, "usri"=> $data['usri']
		    			, "token"=> $mobile_token
	    			)
	    		);
				exit;
    		} 
    		else {
    			echo json_encode(
	    			array(
		    			"req" => "login"
		    			, "res" => "not ok"
		    			, "msg" => "Sistem was not available right now."
	    			)
	    		);
	    		exit;
    		}
		}
	}

	private function generateHash(){
        $result = "";
        $charPool = '0123456789abcdefghijklmnopqrstuvwxyz';
     	for($p = 0; $p<15; $p++)
        	$result .= $charPool[mt_rand(0,strlen($charPool)-1)];
        return sha1(md5(sha1($result)));
    }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
/* Because  sublimetext is too mainstream*/