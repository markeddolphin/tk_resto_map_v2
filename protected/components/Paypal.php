<?php
class Paypal
{
	public $config;
	public $params;
	public $mode;
	public $error;
	public $msg;
	public $debug=FALSE;
	public $query;
	
	public function __construct($config='')
	{
		$this->config=$config;
		$this->mode=$config['mode'];
	}
	
	public function setCredentials()
	{
		$this->params['USER']=trim($this->config[$this->mode]['user']);
		$this->params['PWD']=trim($this->config[$this->mode]['psw']);
		$this->params['SIGNATURE']=trim($this->config[$this->mode]['signature']);
		$this->params['VERSION']=trim($this->config[$this->mode]['version']);		
	}
	
	public function setExpressCheckout()
	{				
		$this->params['METHOD']='SetExpressCheckout';
		$this->params['PAYMENTACTION']=$this->config[$this->mode]['action'];
		$this->setCredentials();		
		if ($this->debug){
		    dump($this->config);		    
		}				
		if ($res=$this->postData()){			
			if ($res['ACK']=='Failure'){
				$this->error=$res['L_LONGMESSAGE0'];
			} elseif ($res['ACK']=='Success'){								
				$details=$this->config[$this->mode]['paypal_web'].'?cmd=_express-checkout&token='.$res['TOKEN'];
				return array(
				  'token'=>$res['TOKEN'],
				  'url'=>$details,
				  'resp'=>$res				  
				);
			} else $this->error='Undefined Error code';
		}
		return FALSE;
	}		
	
	public function getExpressDetail()
	{
		$this->params['METHOD']='GetExpressCheckoutDetails';		
		$this->setCredentials();
		$this->params['TOKEN']=$_REQUEST['token'];		
		if ($res=$this->postData()){
			if ($res['ACK']=='Failure'){
				$this->error=$res['L_LONGMESSAGE0'];
			} elseif ($res['ACK']=='Success'){								
				return $res;
			} else $this->error='Undefined Error code';
		}
		return FALSE;
	}
	
	public function expressCheckout()
	{
		$this->params['METHOD']='DoExpressCheckoutPayment';	
		$this->params['PAYMENTACTION']=$this->config[$this->mode]['action'];			
		$this->setCredentials();
		//$this->params['TOKEN']=$_GET['token'];				
		if ($res=$this->postData()){						
			if ($res['ACK']=='Failure'){
				$this->error=$res['L_LONGMESSAGE0'];
			} elseif ($res['ACK']=='Success'){								
				return $res;
			} else $this->error='Undefined Error code';			
		}
		return FALSE;
	}
	
	public function postData()
	{
		$nvp_string='';
		$url = $this->config[$this->mode]['paypal_nvp'];	    
	    foreach($this->params as $var=>$val) {
           $nvp_string .= '&'.$var.'='.urlencode($val);   
        }        
        if ($this->debug){
        	echo '<h2>Request</h2>';
        	dump($this->params);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_string);
        $result = curl_exec($curl);      
        
        if(curl_errno($curl)){        
        	$this->error='Curl Error no. '.curl_errno($curl).': '.curl_error($curl);
        } else {
        curl_close($curl);
	        if (!empty($result)){
	        	parse_str($result,$resp);
	        	if ($this->debug){
	        	   echo '<h2>Response</h2>';
	        	   dump($resp);
	            }
	        	return $resp;
	        } else $this->error='Failed. cannot post data from paypal.';       
        }
        return FALSE;
	}
	
	public function buildQuery($params)
	{
        if (count($params)>0) {     		
	     		foreach ($params as $key => $value) {
	     			$this->query .= $key .'='.trim($value).'&';	
	     		}
        }     	     		 	
        return substr($this->query,0,-1);
	}
	
	public function getError()
	{
		return $this->error;
	}
	
	public function getMsg()
	{
		return $this->msg;
	}
	
	public function payout()
	{
		$this->params['METHOD']='MassPay';
		$this->params['PAYMENTACTION']=$this->config[$this->mode]['action'];		
		$this->setCredentials();
		$this->params['VERSION']="90";
		if ($res=$this->postData()){
			return $res;
		}
		return FALSE;
	}
	
} /*END CLASS*/