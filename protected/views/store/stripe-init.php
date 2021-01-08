<?php
require_once('stripe/lib/Stripe.php');

$step2=false;
$amount_to_pay=0;
$payment_description=Yii::t("default",'Payment to merchant')." ";
$merchant_name='';

$secret_key='';
$publishable_key='';

if ( $data=Yii::app()->functions->getOrder($_GET['id'])){	
	$merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';
	
	$mode=Yii::app()->functions->getOption('stripe_mode',$merchant_id);   
	$mode=strtolower($mode);
	
	if ( $mode=="sandbox"){
		$secret_key=Yii::app()->functions->getOption('sanbox_stripe_secret_key',$merchant_id);   
		$publishable_key=Yii::app()->functions->getOption('sandbox_stripe_pub_key',$merchant_id);   
	} elseif ($mode=="live"){
		$secret_key=Yii::app()->functions->getOption('live_stripe_secret_key',$merchant_id);   
		$publishable_key=Yii::app()->functions->getOption('live_stripe_pub_key',$merchant_id);   
	}
	
	/*COMMISSION*/
	//if ( Yii::app()->functions->isMerchantCommission($merchant_id)){
	if (FunctionsV3::isMerchantPaymentToUseAdmin($merchant_id)){
		$mode=Yii::app()->functions->getOptionAdmin('admin_stripe_mode');   				
		if ( $mode=="Sandbox"){
			$secret_key=Yii::app()->functions->getOptionAdmin('admin_sanbox_stripe_secret_key');   
			$publishable_key=Yii::app()->functions->getOptionAdmin('admin_sandbox_stripe_pub_key');   
		} elseif ($mode=="live"){
			$secret_key=Yii::app()->functions->getOptionAdmin('admin_live_stripe_secret_key');   
			$publishable_key=Yii::app()->functions->getOptionAdmin('admin_live_stripe_pub_key');   
		}	
	}
	
	//dump($secret_key);  dump($publishable_key); die();
	
	if ( !empty($mode) && !empty($secret_key) && !empty($publishable_key) ){
		$amount_to_pay=isset($data['total_w_tax'])?Yii::app()->functions->standardPrettyFormat($data['total_w_tax']):'';
		$amount_to_pay=is_numeric($amount_to_pay)?unPrettyPrice($amount_to_pay*100):'';
		$amount_to_pay=Yii::app()->functions->normalPrettyPrice2($amount_to_pay);				
			
		$payment_description.=isset($data['merchant_name'])?clearString($data['merchant_name']):'';	
		$merchant_name=isset($data['merchant_name'])?clearString($data['merchant_name']):'';	
		
		$stripe = array(
	     "secret_key"      => $secret_key,
	     "publishable_key" => $publishable_key
	    );
	    Stripe::setApiKey($stripe['secret_key']);
    
	} else $error=Yii::t("default","Stripe payment is not properly configured on merchant portal.");
} else $error=Yii::t("default","Sorry but we cannot find what your are looking for.");

if (isset($_POST)){
	if (is_array($_POST) && count($_POST)>=1){		
		$step2=true;
		$token=isset($_POST['stripeToken'])?$_POST['stripeToken']:'';
		
		try {
			$customer = Stripe_Customer::create(array(
		      'email' => isset($_POST['stripeEmail'])?$_POST['stripeEmail']:'',
		      'card'  => $token
		    ));
		    
		   /* dump($_POST);
		    dump($customer);
		    die();*/
		    	           
	        $charge = Stripe_Charge::create(array(
	          'customer' => $customer->id,
	          'amount'   => $amount_to_pay,
	          'currency' => Yii::app()->functions->adminCurrencyCode()
	        ));	        
	        	        
	        $chargeArray = $charge->__toArray(true);
            /*dump("pay ->".$chargeArray['paid']);
            echo json_encode($chargeArray);*/            	        
	        $db_ext=new DbExt;
	        $params_logs=array(
	          'order_id'=>$_GET['id'],
	          'payment_type'=>"stp",
	          'raw_response'=>json_encode($chargeArray),
	          'date_created'=>FunctionsV3::dateNow(),
	          'ip_address'=>$_SERVER['REMOTE_ADDR']
	        );
	        $db_ext->insertData("{{payment_order}}",$params_logs);
	        
	        $params_update=array(
	         'status'=>'paid'
	        );	        
	        $db_ext->updateData("{{order}}",$params_update,'order_id',$_GET['id']);
	        
	        
	        /*POINTS PROGRAM*/ 
	        if (FunctionsV3::hasModuleAddon("pointsprogram")){
	           PointsProgram::updatePoints($_GET['id']);
	        }
	        
	        /*Driver app*/
			if (FunctionsV3::hasModuleAddon("driver")){
			   Yii::app()->setImport(array(			
				  'application.modules.driver.components.*',
			   ));
			   Driver::addToTask($_GET['id']);
			}
	        	        
	        $this->redirect( Yii::app()->createUrl('/store/receipt',array(
	          'id'=>$_GET['id']
	        )) );
	    } catch (Exception $e)   {	    	
	    	$error=$e->getMessage();
	    }    
	}
}

$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));
?>
<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">
  <h1><?php echo t("Pay using Stripe Payment")?></h1>
  <div class="box-grey rounded">	     
  <?php if ( !empty($error)):?>
  <p class="text-danger"><?php echo $error;?></p>  
  <?php else :?>
  
  <?php if ( $step2==TRUE):?>
  
  <?php else :?>      
  <?php echo CHtml::beginForm( Yii::app()->createUrl('store/stripeInit',array('id'=>$_GET['id'])), 'post');?>
  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key="<?php echo $stripe['publishable_key']; ?>"
          data-name="<?php echo ucwords($merchant_name);?>"
          data-amount="<?php echo $amount_to_pay;?>" 
          data-currency="<?php echo Yii::app()->functions->adminCurrencyCode();?>"
          data-description="<?php echo ucwords($payment_description);?>">
  </script>
  
  <p style="margin-top:20px;" class="text-small"><?php echo t("Please don't close the window during payment, wait until you redirected to receipt page")?></p>
  <!--</form>-->
  <?php echo CHtml::endForm() ; ?>
  <?php endif;?>
  
  <?php endif;?>
    
   <div class="top25">
     <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
     <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
    </div>
  
    </div>
   </div>
  </div>
 </div>
</div>