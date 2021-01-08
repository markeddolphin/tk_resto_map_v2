<?php
$db_ext=new DbExt;
$payment_code=Yii::app()->functions->paymentCode("payline");
$error='';

$data=isset($_GET)?$_GET:'';

$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';
if ( $res=Yii::app()->functions->getSMSPackagesById($package_id) ){
	
	$amount_to_pay=$res['price'];
	if ( $res['promo_price']>0){
		$amount_to_pay=$res['promo_price'];
	}	    										
	$amount_to_pay=is_numeric($amount_to_pay)?normalPrettyPrice($amount_to_pay):'';
	$amount_to_pay=unPrettyPrice($amount_to_pay);	
	
	$merchant_payline_api=Yii::app()->functions->getOptionAdmin('admin_payline_api');   
	$merchant_payline_mode=Yii::app()->functions->getOptionAdmin('admin_payline_mode');   
		
    if (isset($data['verify']) && $data['verify']=="true"){
    	
      if ( $merchant_payline_mode=="Sandbox"){
	      $api_url = 'http://payline.ir/payment-test/gateway-result-second';
	  } else $api_url = 'http://payline.ir/payment/gateway-result-second';
	
	  $data_post=$_POST;
		
	 $result = get($api_url,$merchant_payline_api,$data_post['trans_id'],$data_post['id_get']); 

	 switch($result){ 
	    case '-1' : 
	        $error=Yii::t("default","you api is not recognized with api of payline.ir");  
	        break; 
	    case '-2' : 
	        $error=Yii::t("default","trans_id is not valid");
	        break; 
	    case '-3' : 	         
	         $error=Yii::t("default","id_get is not valid");
	         break; 
	    case '-4' : 	         
	         $error=Yii::t("default","this transaction is not in our system");
	    break; 
	         case '1' : 	         	         	         		     	             	         	       
	         $params=array(
			  'merchant_id'=>Yii::app()->functions->getMerchantID(),
			  'sms_package_id'=>$package_id,
			  'payment_type'=>$payment_code,
			  'package_price'=>$amount_to_pay,
			  'sms_limit'=>isset($res['sms_limit'])?$res['sms_limit']:'',
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			  'payment_gateway_response'=>json_encode($data_post),
			  'status'=>"paid",
			  'payment_reference'=>$data_post['trans_id']
			);	    										
			if ( $db_ext->insertData("{{sms_package_trans}}",$params)){										
                 header('Location: '.Yii::app()->request->baseUrl."/merchant/smsReceipt/id/".Yii::app()->db->getLastInsertID());
            } else $error=Yii::t("default","ERROR: Cannot insert record.");				
	         
	         break; 
	    default:
	    	$error=Yii::t("default","Something went wrong during processing your request. Please try again later.");
	    	break;
	 }	
    	
    } else {
    	
    	if ( $merchant_payline_mode=="Sandbox"){
			$url = 'http://payline.ir/payment-test/gateway-send'; 
		} else $url = 'http://payline.ir/payment/gateway-send'; 		
		
    	$api = $merchant_payline_api; 				
$redirect =Yii::app()->getBaseUrl(true)."/merchant/paylineinit/type/purchaseSMScredit/package_id/$package_id/verify/true";
				
		$result = send($url,$api,$amount_to_pay,$redirect);
		
		if($result > 0 && is_numeric($result)){ 
			if ( $merchant_payline_mode=="Sandbox"){
				$go = "http://payline.ir/payment-test/gateway-$result"; 
			} else $go = "http://payline.ir/payment/gateway-$result"; 					  		
		    header("Location: $go");
		} else {
			 switch($result){ 
			   case '-1': 
			    $error=Yii::t("default","you api is not recognized with api of payline.ir"); 
			    break; 
			 case '-2': 
			    $error=Yii::t("default",'amount must more than 1000');
			    break; 
			 case '-3': 
			    $error=Yii::t("default","redirect url is null");
			    break; 
			 case '-4': 
			    $error=Yii::t("default","payment gateway is not find with your information that posted");
			    break; 
			  default:
			  	$error=Yii::t("default","Something went wrong during processing your request. Please try again later.");
			  	break;  
			}
		}
    	
    }	
} else $error=Yii::t("default","Sorry but we cannot find what your are looking for.");
?>
<div class="page-right-sidebar payment-option-page">
  <div class="main">  
  <?php if ( !empty($error)):?>
  <p class="uk-text-danger"><?php echo $error;?></p>    
  <?php else :?>
  <?php echo Yii::t("default","processing.")?>
  <?php endif;?>
  </div>
</div>