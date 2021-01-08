<?php
class SMSGlobal 
{	
	private $_err_msg;
	public $_smsuser='';
	public $_smspass='';
	public $_sms_url='';
	public $_smssender='';
	public $_debug=FALSE;
	
	public function set_error($err=''){		
		$this->_err_msg = $err;
	}
	
	public function get_error(){		
		return $this->_err_msg;
	}
				
	public function sendSMS_HTTPOST($mobile_nos='',$msg='') 
	{									
		if (substr($mobile_nos,0,1) == "+") {
			$mobile_nos = substr($mobile_nos,1,strlen($mobile_nos));
		}		
        if (empty($mobile_nos)) {
        	$this->set_error('Mobile number is empty');
        	return false;
        }
        if (empty($msg)) {
        	$this->set_error('Text message is empty');
        	return false;
        }
        if (strlen($mobile_nos)<=8){	        
	        $this->set_error('Invalid mobile number.');
        	return false;
        }
                                                                   
        $content =  '?action=sendsms'.
	                '&user='.rawurlencode($this->_smsuser).
	                '&password='.rawurlencode($this->_smspass).
	                '&to='.rawurlencode($mobile_nos).
	                '&from='.rawurlencode($this->_smssender).
	                '&text='.rawurlencode($msg);

	    //dump($this->_sms_url . $content); die();
        $resp  = @file_get_contents($this->_sms_url . $content);        
        
        /*$ch = curl_init($this->_sms_url . $content);
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$res=curl_exec ($ch);		*/
                
        $explode_resp = explode('SMSGlobalMsgID:', $resp);
        
        if ($this->_debug==TRUE){
            echo "<h2>Request</h2>".$this->_sms_url . $content;
            echo "<h2>Response</h2>".$explode_resp;
            dump($explode_resp);
        }
        
		if(count($explode_resp) == 2) {
			if (preg_match("/ERROR/i", $explode_resp[0])) {
				$this->set_error('Failed .'. $explode_resp[0]);
                return false;
            } 
            		    
		    return $explode_resp;
		} else { 		    		    
            $this->set_error($resp);
		    return false;
		}
        
	}
	/*END: sendSMS_HTTPOST*/		
}
?>