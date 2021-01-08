<?php
$DbExt=new DbExt;  $error='';

$payment_ref="mri-".Yii::app()->functions->getLastIncrement('{{package_trans}}')."-".Yii::app()->functions->generateCode();

$merchant_name=Yii::app()->functions->getOptionAdmin('website_title');
if (empty($name)){		 	
	$merchant_name=Yii::app()->name;
}
 
$my_token=isset($_GET['token'])?$_GET['token']:'';

$payment_code='mri';

$data=Yii::app()->functions->getMerchantByToken($my_token);

$amount_to_pay=0;
$payment_description=Yii::t("default",'Membership Package - ');

$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';
$extra_params='';

if (isset($_GET['renew'])) {
	$extra_params="renew/1/package_id/".$package_id;
	if ($new_info=Yii::app()->functions->getPackagesById($package_id)){		    
	    $data['package_price']=$new_info['price'];
	    if ( $new_info['promo_price']>0){
		    $data['package_price']=$new_info['promo_price'];
	    }			
	    $data['package_name']=$new_info['title'];
	    $data['package_id']=$package_id;
	}
}
if ($data){
	
	$amount_to_pay=isset($data['package_price'])?Yii::app()->functions->normalPrettyPrice($data['package_price']):'';
	$payment_description.=isset($data['package_name'])?$data['package_name']:'';	
			
} else $error=Yii::t("default","Sorry but we cannot find what your are looking for.");

if (isset($_POST)){
	if (is_array($_POST) && count($_POST)>=1){		
		
		require_once 'mpgClasses.php';
		$data_post=$_POST;		
		
		$payment_ref="SGN-". Moneris::generatePaymentRef(). "-" .Moneris::lastID('package_trans');
		
		$txnArray=array(
           'type'=>"purchase",
		   'order_id'=>$payment_ref,		   
	       'amount'=>$amount_to_pay,
		   'pan'=>$data_post['x_card_num'],
		   'expdate'=>substr($data_post['expiration_yr'],2,2).$data_post['expiration_month'],
		   'crypt_type'=>Moneris::cryptType(),
		   'dynamic_descriptor'=>$payment_description
	    );
	    //dump($txnArray);
	    
	    $cvdTemplate = array(
		    'cvd_indicator' => 1,
		    'cvd_value' => $data_post['x_cvv'] 
		);
	    
	    if ( $credentials=Moneris::getCredentials()){
	    	
	    	$mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);
	    	
	    	$mpgTxn = new mpgTransaction($txnArray);
	    	
	    	$mpgTxn->setCvdInfo($mpgCvdInfo);
	    	
	    	$mpgRequest = new mpgRequest($mpgTxn);
	    	$mpgRequest->setProcCountryCode( $credentials['country_code'] );
	    	$mpgRequest->setTestMode( $credentials['mode'] );
	    		    	
	    	$mpgHttpPost  =new mpgHttpsPost(trim($credentials['store_id']), trim($credentials['token']) ,$mpgRequest);	    	
	    	$resp=$mpgHttpPost->getMpgResponse();
	    	
	    	$cvv_response = $resp->getCvdResultCode();
	    	if (!empty($cvv_response)){
	    		$cvv_response=str_replace("1","",$cvv_response);
	    	}
	    	
	    	//if ( $resp->getResponseCode()=="027"){
	    	if ( in_array($resp->getResponseCode(),Moneris::approvedResponsenCode() )){
	    		if ( $cvv_response=="M" || $cvv_response=="1M"){
				
				$full_response=json_encode($resp->responseData);
				
				if (isset($_GET['renew'])){   
					
					if ($new_info=Yii::app()->functions->getPackagesById($package_id)){	    					
						$data['package_name']=$new_info['title'];
						$data['package_price']=$new_info['price'];
						if ($new_info['promo_price']>0){
							$data['package_price']=$new_info['promo_price'];
						}			
					}
																													
					$membership_info=Yii::app()->functions->upgradeMembership($data['merchant_id'],$package_id);
													
    				$params=array(
			          'package_id'=>$package_id,	          
			          'merchant_id'=>$data['merchant_id'],
			          'price'=>$data['package_price'],
			          'payment_type'=>Moneris::getPaymentCode(),
			          'membership_expired'=>$membership_info['membership_expired'],
			          'date_created'=>FunctionsV3::dateNow(),
			          'ip_address'=>$_SERVER['REMOTE_ADDR'],
			          'PAYPALFULLRESPONSE'=>json_encode($full_response),
			          'TRANSACTIONID'=>$resp->getReferenceNum(),	
			          'TOKEN'=>$resp->getReceiptId(),		           
			          'status'=>"paid"
			        );					        
			        
			        $DbExt->insertData("{{package_trans}}",$params);
			        
					$params_update=array(
					  'package_id'=>$package_id,
					  'package_price'=>$membership_info['package_price'],
					  'membership_expired'=>$membership_info['membership_expired'],				  
					  'status'=>'active'
				 	 );		
				 	 
					$DbExt->updateData("{{merchant}}",$params_update,'merchant_id',$data['merchant_id']);	  
					
					$redirect_url=Yii::app()->createUrl('store/renewsuccesful');
					header("Location: $redirect_url");
	                Yii::app()->end();
			        
				} else {
				
					$params=array(
			           'package_id'=>$data['package_id'],	          
			           'merchant_id'=>$data['merchant_id'],
			           'price'=>$amount_to_pay,
			           'payment_type'=>Moneris::getPaymentCode(),
			           'membership_expired'=>$data['membership_expired'],
			           'date_created'=>FunctionsV3::dateNow(),
			           'ip_address'=>$_SERVER['REMOTE_ADDR'],
			           'PAYPALFULLRESPONSE'=>json_encode($full_response),
			           'TRANSACTIONID'=>$resp->getReferenceNum(),
			           'TOKEN'=>$resp->getReceiptId(),
			           'status'=>"paid"
			        );
			        		        
			        $DbExt->insertData("{{package_trans}}",$params);	
			        
			        $redirect_url=Yii::app()->createUrl('store/merchantsignup',array(
	                   'Do'=>"step4",
	                   'token'=>$my_token
	                ));
	               
	                $DbExt->updateData("{{merchant}}",
							  array(
							    'payment_steps'=>3,
							    'membership_purchase_date'=>FunctionsV3::dateNow()
							  ),'merchant_id',$data['merchant_id']);
	                    
	               header("Location: $redirect_url");
	               Yii::app()->end();
			   }
				
			   } else $error= Moneris::CvvResult( $cvv_response );
			} else $error=$resp->getMessage();
	    	
	    } else $error=t("Credentials not yet set");
	}
}


$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("step 3 of 4")
));

/*PROGRESS ORDER BAR*/
$this->renderPartial('/front/progress-merchantsignup',array(
   'step'=>3,
   'show_bar'=>true
));
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
    <div class="row top30">
       <div class="inner">
          <h1><?php echo t("Pay using moneris")?></h1>
          <div class="box-grey rounded">	     
          
          <?php if ( !empty($error)):?>
             <p class="text-danger"><?php echo $error;?></p>  
          <?php endif;?>
          
          <form id="forms-normal" class="forms"  method="POST" >
          
          	<div class="row top10">
			  <div class="col-md-3"><?php echo t("Amount")?></div>
			  <div class="col-md-8">
			    <?php echo CHtml::textField('amount',
				  isset($amount_to_pay)?$amount_to_pay:''
				  ,array(
				  'class'=>'grey-fields full-width',
				  'disabled'=>true
				  ))?>
			  </div>
			</div>
			
			<div class="row top10">
			  <div class="col-md-3"><?php echo t("Credit Card Number")?></div>
			  <div class="col-md-8">
			    <?php echo CHtml::textField('x_card_num',
			  isset($data_post['x_card_num'])?$data_post['x_card_num']:''
			  ,array(
			  'class'=>'grey-fields numeric_only full-width' ,
			  'data-validation'=>"required",
			  'maxlength'=>16
			  ))?>
			  </div>
			</div>
			
		  <div class="row top10">
			  <div class="col-md-3"><?php echo t("Exp. month")?></div>
			  <div class="col-md-8">
			      <?php echo CHtml::dropDownList('expiration_month',
			      isset($data_post['expiration_month'])?$data_post['expiration_month']:''
			      ,
			      Yii::app()->functions->ccExpirationMonth()
			      ,array(
			       'class'=>'grey-fields full-width',
			       'placeholder'=>Yii::t("default","Exp. month"),
			       'data-validation'=>"required"  
			      ))?>
			  </div>
			</div>
								
            <div class="row top10">
			  <div class="col-md-3"><?php echo t("Exp. year")?></div>
			  <div class="col-md-8">
			   <?php echo CHtml::dropDownList('expiration_yr',
			      isset($data_post['expiration_yr'])?$data_post['expiration_yr']:''
			      ,
			      Yii::app()->functions->ccExpirationYear()
			      ,array(
			       'class'=>'grey-fields full-width',
			       'placeholder'=>Yii::t("default","Exp. year") ,
			       'data-validation'=>"required"  
			      ))?>
			  </div>
			</div>	
			
		<div class="row top10">
			  <div class="col-md-3"><?php echo t("CVV")?></div>
			  <div class="col-md-8">
			    <?php echo CHtml::textField('x_cvv',
			  isset($data_post['x_cvv'])?$data_post['x_cvv']:''
			  ,array(
			  'class'=>'grey-fields numeric_only full-width' ,
			  'data-validation'=>"required",
			  'maxlength'=>4
			  ))?>
			  </div>
	    </div>		
          
		 <div class="row" style="margin-top:20px;">
		  <div class="col-md-3"></div>
		  <div class="col-md-8">
		  <input type="submit" value="<?php echo Yii::t("default","Pay Now")?>" class="black-button inline medium">
		  </div>
		 </div>		 
			
		  <div class="top25">
		  <a href="<?php echo Yii::app()->getBaseUrl(true)."/store/merchantsignup/do/step3/token/$my_token/$extra_params"?>">
          <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
          </div>	
          
          </form>

          
          </div> <!--box-->
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->          