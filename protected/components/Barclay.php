<?php
class Barclay
{
	public $params;
	public $sha_password;
	public $errors;
	public $mode;

	public function generateForms()
	{
	    if (is_array($this->params) && count($this->params)>=1){
	    	$inputs='';
	    	$sha_string='';
	    	foreach ($this->params as $key=>$val) {
	    		if (!empty($val)){
	    			$inputs.="<input type=\"hidden\" name=\"$key\" value=\"$val\">";
	    			$sha_string.="$key=$val".$this->sha_password;
	    		}	    		
	    	}
	    		    	
	    	$sha_string = strtoupper(sha1($sha_string));	    			    	
	    		    	
	    	$forms='';
	    	if ($this->mode=="sandbox"){
	    		$forms.="<form name=\"OrderForm\" id=\"OrderForm\" action=\"https://mdepayments.epdq.co.uk/ncol/test/orderstandard.asp\" method=\"POST\">";
	    	} else {
	    		$forms.="<form name=\"OrderForm\" id=\"OrderForm\" action=\"https://mdepayments.epdq.co.uk/ncol/prod/orderstandard.asp\" method=\"POST\">";
	    	}
	    	
	    	$forms.=$inputs;
	    	$forms.="<input type=\"hidden\" name=\"SHASign\" value=\"$sha_string\">";	    	
	    	$forms.='<input onclick="document.OrderForm.submit(); return false;" value="'.t("Pay Now").'" type="submit" class="black-button inlne medium" id="tstbtn" />';	    	
	    	$forms.="</form>";	    	
	    		    	
	    	return $forms;
	    } else $this->errors=t("Parameter is missing");	    
	    return false;
	}
	
}/* END CLASS*/