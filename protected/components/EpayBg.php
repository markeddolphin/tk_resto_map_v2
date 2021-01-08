<?php
class EpayBg
{
	public $params;	
	public $fields;
	public $errors;	
	
	public $min;
	public $secret;
	
	public $encoded;
	
	public function generateForms()
	{
		if (is_array($this->params) && count($this->params)>=1){						
			$data = <<<DATA
MIN={$this->params[MIN]}
INVOICE={$this->params[INVOICE]	}
AMOUNT={$this->params[AMOUNT]}
CURRENCY={$this->params[CURRENCY]}
EXP_TIME={$this->params[EXP_TIME]}
DESCR={$this->params[DESCR]}
DATA;
				
					
            $ENCODED  = base64_encode($data);
            $CHECKSUM = $this->hmac('sha1', $ENCODED, $this->secret); 
            
            $this->encoded=$CHECKSUM;
            $this->fields['ENCODED']=$ENCODED;
            $this->fields['CHECKSUM']=$CHECKSUM;
            
            $inputs='';
            if (is_array($this->fields) && count($this->fields)>=1){
            	foreach ($this->fields as $key=>$val) {
            		$inputs.="<input type=\"hidden\" name=\"$key\" value=\"$val\" >";
            	}
            }
            
            if ( $this->params['mode']=="sandbox"){
            	$action='https://devep2.datamax.bg/ep2/epay2_demo/';
            } else $action='https://www.epay.bg/';
            
            $forms="<form action=\"$action\" method=POST>";
            $forms.=$inputs;
            $forms.="<input type=\"submit\" value=\"".t("Pay Now")."\" class=\"black-button medium inline\">";
            $forms.="</form>";
                        
            return $forms;
		}
		return false;
	}
	
	public function hmac($algo,$data,$passwd){
        /* md5 and sha1 only */
        $algo=strtolower($algo);
        $p=array('md5'=>'H32','sha1'=>'H40');
        if(strlen($passwd)>64) $passwd=pack($p[$algo],$algo($passwd));
        if(strlen($passwd)<64) $passwd=str_pad($passwd,64,chr(0));

        $ipad=substr($passwd,0,64) ^ str_repeat(chr(0x36),64);
        $opad=substr($passwd,0,64) ^ str_repeat(chr(0x5C),64);

        return($algo($opad.pack($p[$algo],$algo($ipad.$data))));
	}
	
	public function getEncoded()
	{
		return $this->encoded;
	}
		
}/* END CLASS*/