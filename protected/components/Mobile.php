<?php
class Mobile
{
	
	public function clientLogin($user='',$pass='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
	    	{{client}}
	    	WHERE
	    	email_address=".Yii::app()->db->quoteValue($user)."
	    	AND
	    	password=".Yii::app()->db->quoteValue(md5($pass))."
	    	AND
	    	status IN ('active')
	    	LIMIT 0,1
	    	";		
		if ( $res=$DbExt->rst($stmt)) {	 
			return $res[0];
		} 
		return false;	
	}
	
	public function generateToken(){
    		
		srand(microtime()*356345); 
	    $zufallszahl    = rand(1,32767); 
	    $zufallszahl2   = $zufallszahl*microtime();
	    $token          = sha1(md5("8743zuhre76t34rffd".$zufallszahl2)); 
	    return  $token.session_id();
	}
	
	public function updateToken($token='',$user_id='')
	{
		$params=array('token'=>$token,'last_login'=>FunctionsV3::dateNow(),'ip_address'=>$_SERVER['REMOTE_ADDR']);		
		$db_ext=new DbExt;
		if ($db_ext->updateData("{{client}}",$params,'client_id',$user_id)){
			return true;
		}
		return false;
	}	
	
}/* END CLASS*/