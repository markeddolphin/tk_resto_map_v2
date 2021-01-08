<?php
class voguepayClass
{
	
	public static function getTransaction($transaction_id='', $demo=true , $type='json')
	{
		$link="https://voguepay.com/?v_transaction_id=".urlencode($transaction_id)."&type=$type";
		if($demo){
			$link.="&demo=true";
		}		
		$file_res=@file_get_contents($link);
		//$file_res=Yii::app()->functions->Curl($link);
		if($file_res){
			$resp=json_decode($file_res,true);
			if(is_array($resp) && count($resp)>=1){
				return $resp;
			}
		}
		return false;
	}
	
} /*end class*/