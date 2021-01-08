<?php
$paymode=Yii::app()->functions->getOptionAdmin('admin_mercado_mode');
$admin_mercado_id=Yii::app()->functions->getOptionAdmin('admin_mercado_id');
$admin_mercado_key=Yii::app()->functions->getOptionAdmin('admin_mercado_key');

require_once 'mercadopago/mercadopago.php';

$db_ext=new DbExt;
$payment_code=Yii::app()->functions->paymentCode("mercadopago");
$error='';

if (isset($_GET['status'])){	
	$status=$_GET['status'];
	$reference=isset($_GET['external_reference'])?$_GET['external_reference']:'';
	
	if ( $status=="success" || $status=="pending"){		
		try {
			$mp = new MP($admin_mercado_id, $admin_mercado_key);	
			$filters = array(            
               "external_reference" => $reference
            );      
            //dump($filters);
            $searchResult = $mp->search_payment($filters);           
            //dump($searchResult);            
            if (is_array($searchResult) && count($searchResult)>=1){
            	if ($searchResult['status']==200){
            		$ref=explode("-",$reference);            	
            		$package_id=isset($ref[0])?$ref[0]:'';
            		if ( $res=Yii::app()->functions->getSMSPackagesById($package_id) ){
            			
            			$amount_to_pay=$res['price'];
						if ( $res['promo_price']>0){
							$amount_to_pay=$res['promo_price'];
						}	    										
						$amount_to_pay=is_numeric($amount_to_pay)?unPrettyPrice($amount_to_pay):'';		
						//dump($amount_to_pay);
						
						$params=array(
						  'merchant_id'=>Yii::app()->functions->getMerchantID(),
						  'sms_package_id'=>$package_id,
						  'payment_type'=>$payment_code,
						  'package_price'=>$amount_to_pay,
						  'sms_limit'=>isset($res['sms_limit'])?$res['sms_limit']:'',
						  'date_created'=>FunctionsV3::dateNow(),
						  'ip_address'=>$_SERVER['REMOTE_ADDR'],
						  'payment_gateway_response'=>json_encode($_GET),
						  'status'=>$status=="success"?'paid':$status,
						  'payment_reference'=>$reference
						);	    							
						if (!Yii::app()->functions->mercadoGetPayment($reference)){
							if ( $db_ext->insertData("{{sms_package_trans}}",$params)){										
header('Location: '.Yii::app()->request->baseUrl."/merchant/smsReceipt/id/".Yii::app()->db->getLastInsertID());
			                } else $error=Yii::t("default","ERROR: Cannot insert record.");	
						} else  $error=Yii::t("default","Failed. This transaction has already been process");            		
            		} else $error=Yii::t("default","Failed. Cannot process payment");            		        
                } else $error=Yii::t("default","Failed. Cannot process payment")." ".$searchResult['status'];
            } else $error=Yii::t("default","ERROR: Invalid response from Mercadopago");
            
		} catch (Exception $e){
			$error=$e->getMessage();
		}
			
	} else $error=Yii::t("default","Failed. Cannot process payment");
	
	if (!empty($error)){
	     echo "<p class=\"uk-text-danger\">".$error."</p>"; 
	}
	
} else  {
		
	
	$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';
	if ( $res=Yii::app()->functions->getSMSPackagesById($package_id) ){
		
		$amount_to_pay=$res['price'];
		if ( $res['promo_price']>0){
			$amount_to_pay=$res['promo_price'];
		}	    										
		$amount_to_pay=is_numeric($amount_to_pay)?normalPrettyPrice($amount_to_pay):'';		
		
						
		try {
		   $mp = new MP($admin_mercado_id, $admin_mercado_key);	
		   
		   $reference=$_GET['package_id']."-".Yii::app()->functions->generateRandomKey();
		   $_SESSION['mcd_ref']=$reference;
		   
		   $preference_data = array(
				"items" => array(
					array(
					"title" => $res['title'],
					"currency_id" => Yii::app()->functions->adminCurrencyCode(),
					"category_id" => "services",
					"quantity" => 1,
					"unit_price" => (float)$amount_to_pay
					)
				  ),
				"back_urls" => array(
				"success" => Yii::app()->getBaseUrl(true)."/merchant/mercadopagoInit/status/success",
				"failure" => Yii::app()->getBaseUrl(true)."/merchant/mercadopagoInit/status/failure",
				"pending" => Yii::app()->getBaseUrl(true)."/merchant/mercadopagoInit/status/pending"
			    ),
			    "auto_return"=>"approved",
			    "external_reference" => $reference,
		    );   
		    
		    $preference = $mp->create_preference($preference_data);       
		    ?>
		    <h2><?php echo Yii::t("default","Pay using Mercadopago")?></h2>
		    <?php if ($paymode=="Sandbox"):?>
		    <a href="<?php echo $preference["response"]["sandbox_init_point"];; ?>" name="MP-Checkout" class="lightblue-M-Ov-ArOn">Pay</a>
		    <?php else :?>
		    <a href="<?php echo $preference["response"]["init_point"]; ?>" name="MP-Checkout" class="lightblue-M-Ov-ArOn">
		    <?php echo Yii::t("default","Pay")?>
		    </a>
		    <?php endif;?>
		    <script type="text/javascript" src="http://mp-tools.mlstatic.com/buttons/render.js"></script>
		    <?php
		   
		} catch (Exception $e) {
			echo "<p class=\"uk-text-danger\">".$e->getMessage()."</p>";
		}
	} else echo "<p class=\"uk-text-danger\">".Yii::t("default","Sorry but we cannot find what your are looking for.")."</p>";
}
?>