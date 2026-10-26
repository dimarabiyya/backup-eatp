<?php
/**
 * By ttg
 *
 */
class Rest {
	
	public $response;
	
	public function __construct()
	{
		$this->response = new stdClass();
	}
	
	public function dispatchParam($keys, $object, &$response)
	{
		if (is_object($object))
		{
			$response = (object) $response;
			
			foreach ($keys as $key)
			{
				if (array_key_exists($key, $object))
				{
					$response->{$key} = $object->$key;
				}
				else
				{
					$this->sendError(400, "Parameter [$key] not found.");
					return 0;
				}
			}
			return 1;
		}
		else
		{
			$this->sendError(400, "Invalid json format.");
			return 0;
		}
	}

	public function sendError($errorCode, $errorMessage, $param = '')
    {
		$this->response->errorCode = $errorCode;
		$this->response->errorMessage = $errorMessage;
		
		if ($param != '')
		{
			foreach((array) $param as $key => $value)
			{
				$this->response->{$key} = $value;
			}
		}
		
		$this->response($this->response, $errorCode);
    }

	public function sendData($errorMessage, $data = '', $errorCode = 0)
	{
		$return = new stdClass();
		
		$return->errorCode = $errorCode;
		$return->errorMessage = $errorMessage;
		
		if ($data != '')
		{
			$return = (object) array_merge((array) $return, (array) $data);
		}
		
		$this->response = $return;
		
		$this->response($return);
	}
	
	public function response($data = array(), $httpCode = null)
	{
		if (empty($data) && $httpCode === null)
		{
			$httpCode = 404;
			
			// create the output variable here in the case of $this->response(array());
			$output = NULL;
		}
		
		// If data is empty but http code provided, keep the output empty
		else if (empty($data) && is_numeric($httpCode))
		{
			$output = NULL;
		}
		else
		{
			$output = new stdClass();
			
			$output->status = $httpCode;
			
			foreach ($data as $k => $v)
			{
				$output->{$k} = $v;
			}
			
			$output->timestamp = date('YmdHis');
			
			$output = json_encode($output);
			
			$this->response = $output;
		}
		
		header('Content-Type: application/json');
		//header('HTTP/1.1: '.$httpCode);
		//header('Status: '.$httpCode);
		header('HTTP/1.1: 200');
		header('Status: 200');
		
		echo $output;
	}
}
