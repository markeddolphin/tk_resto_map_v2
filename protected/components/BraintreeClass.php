<?php
class BraintreeClass
{
	
	public static function connect($credentials='')
	{
		//$path=Yii::getPathOfAlias('webroot')."/braintree";		
		spl_autoload_unregister(array('YiiBase','autoload'));
		require_once "braintree/lib/Braintree.php";
		spl_autoload_register(array('YiiBase','autoload'));
		
		Braintree_Configuration::environment( $credentials['mode'] );
		Braintree_Configuration::merchantId( $credentials['mtid'] );
        Braintree_Configuration::publicKey( $credentials['publickey'] );
        Braintree_Configuration::privateKey( $credentials['privateckey'] );            
	}
	
	public static function generateCLientToken($merchant_type='',$client_id='',$merchant_id='')
	{		
		if($credentials=self::getCredentials($merchant_type,$merchant_id)){		
			self::connect($credentials);		
			try {
				return Braintree_ClientToken::generate(); 
			} catch (Exception $e){
				return false;
			}            
		} 
		return false;
	}
	
	public static function getCredentials($merchant_type='',$merchant_id='')
	{
		if($merchant_type==2){
			//admin credentials
			$admin_btr_mode=getOptionA('admin_btr_mode');
			if($admin_btr_mode=="sandbox"){
				$brain_mtid=getOptionA('sanbox_brain_mtid');
				$brain_publickey=getOptionA('sanbox_brain_publickey');
				$brain_privateckey=getOptionA('sanbox_brain_privateckey');
			} else {
				$brain_mtid=getOptionA('live_brain_mtid');
				$brain_publickey=getOptionA('live_brain_publickey');
				$brain_privateckey=getOptionA('live_brain_privateckey');
			}
		} else {
			//merchant credentials			
			$admin_btr_mode=getOption($merchant_id,'merchant_btr_mode');
			if($admin_btr_mode=="sandbox"){
				$brain_mtid=getOption($merchant_id,'mt_sanbox_brain_mtid');
				$brain_publickey=getOption($merchant_id,'mt_sanbox_brain_publickey');
				$brain_privateckey=getOption($merchant_id,'mt_sanbox_brain_privateckey');
			} else {
				$brain_mtid=getOption($merchant_id,'mt_live_brain_mtid');
				$brain_publickey=getOption($merchant_id,'mt_live_brain_publickey');
				$brain_privateckey=getOption($merchant_id,'mt_live_brain_privateckey');
			}
			
		}
		if(!empty($brain_mtid)){
			$credentials=array(
			  'mode'=>$admin_btr_mode,
			  'mtid'=>$brain_mtid,
			  'publickey'=>$brain_publickey,
			  'privateckey'=>$brain_privateckey,
			);
			//dump($credentials);
			return $credentials;
		}
		return false;		
	}
	
	public static function displayForms($token='',$label='')
	{
		if(empty($token)){
			echo "<p class=\"text-danger\">".t("Token is empty")."</p>";
			return ;
		}
		?>
		<!--<form id="checkout" method="post" action="">-->
		<?php echo CHtml::beginForm( '' , 'post', 
		array(
		  'id'=>'checkout'
		));?>
		  <div id="payment-form"></div>
		  <input type="submit" class="orange-button top30 medium" value="<?php echo $label?>">
		<!--</form>-->
		<?php echo CHtml::endForm() ; ?>
		
		<script src="https://js.braintreegateway.com/js/braintree-2.23.0.min.js"></script>
		<script>		
		var clientToken = "<?php echo $token;?>";		
		braintree.setup(clientToken, "dropin", {
		  container: "payment-form"
		});
		</script>
		<?php
	}
	
	public static function PaymentMethod($merchant_type='',$merchant_id='',$amount='',$nonce='',$fname='',$lname='')
	{
		if(empty($nonce) || empty($amount)){
			return false;
		}
		
		if($credentials=self::getCredentials($merchant_type,$merchant_id)){				
			self::connect($credentials);
			                        
            $result = Braintree_Transaction::sale([
			  'amount' => $amount,
			  'paymentMethodNonce' => $nonce,
			  'customer'=> [
			        'firstName'=> $fname,
			        'lastName'=> $lname
			     ],
			  'options' => [			     
			    //'submitForSettlement' => True
			  ]
			]);
						
			if ($result->success) {
				//print_r("success!: " . $result->transaction->id);
				return $result->transaction->id;
			} else if ($result->transaction) {
			   /*print_r("Error processing transaction:");
               print_r("\n  code: " . $result->transaction->processorResponseCode);
               print_r("\n  text: " . $result->transaction->processorResponseText);*/
			} else {
			    /*print_r("Validation errors: \n");
			    print_r($result->errors->deepAll());*/
			}
		} 
		return false;
	}
	
} /*end class*/