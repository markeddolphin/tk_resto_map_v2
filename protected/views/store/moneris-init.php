<?php
$DbExt=new DbExt;  $amount_to_pay=0; $error='';
$payment_description=Yii::t("default",'Payment to merchant')." ";

if ( $data=Yii::app()->functions->getOrder($_GET['id'])){	
	 $merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';	 
	 $amount_to_pay=isset($data['total_w_tax'])?Yii::app()->functions->normalPrettyPrice($data['total_w_tax']):'';	 	 	
	 $payment_description.=isset($data['merchant_name'])?$data['merchant_name']:'';		 	 
} else $error=Yii::t("default","Sorry but we cannot find what your are looking for.");

if (isset($_POST)){
   if (is_array($_POST) && count($_POST)>=1){
   	   require_once 'mpgClasses.php';
	   $data_post=$_POST;				
	   $payment_ref="ODR-" . Moneris::generatePaymentRef(). "-"  .Moneris::lastID('payment_order');
	   
	   $txnArray=array(
	       'type'=>"purchase",
		   'order_id'=>$payment_ref,		   
	       'amount'=>$amount_to_pay,
		   'pan'=>$data_post['x_card_num'],
		   'expdate'=>substr($data_post['expiration_yr'],2,2).$data_post['expiration_month'],
		   'crypt_type'=>Moneris::cryptType(),
		   'dynamic_descriptor'=>$payment_description
	   );
	   
	   $cvdTemplate = array(
		    'cvd_indicator' => 1,
		    'cvd_value' => $data_post['x_cvv'] 
		);
		
	   //dump($cvdTemplate);
	   	   	 	  
	   if ( $credentials=Moneris::getCredentials('merchant',$merchant_id)){
	   	    //dump($credentials); die();
	   	    
	   	    $mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);
	   	    
	   	    $mpgTxn = new mpgTransaction($txnArray);
	   	    $mpgTxn->setCvdInfo($mpgCvdInfo);
	   	    
	    	$mpgRequest = new mpgRequest($mpgTxn);
	    	$mpgRequest->setProcCountryCode( $credentials['country_code'] );
	    	$mpgRequest->setTestMode( $credentials['mode'] );
	    		    	
	    	$mpgHttpPost  =new mpgHttpsPost(trim($credentials['store_id']), trim($credentials['token']) ,$mpgRequest);	    	
	    	$resp=$mpgHttpPost->getMpgResponse();
	    		 
	    	//dump($resp);
	    	
	    	$cvv_response = $resp->getCvdResultCode();
	    	if (!empty($cvv_response)){
	    		$cvv_response=str_replace("1","",$cvv_response);
	    	}
	    	//dump("cvv_response : $cvv_response");
	    		    		    	
		    if ( in_array($resp->getResponseCode(),Moneris::approvedResponsenCode() )){
		    	//if ( $cvv_response=="M" || $cvv_response=="1M"){
		    		
		    		$full_response=json_encode($resp->responseData);
		    		
		    		$params_logs=array(
			          'order_id'=>$_GET['id'],
			          'payment_type'=>Moneris::getPaymentCode(),
			          'payment_reference'=>$resp->getReferenceNum(),
			          'raw_response'=>$full_response,
			          'date_created'=>FunctionsV3::dateNow(),
			          'ip_address'=>$_SERVER['REMOTE_ADDR']
			        );		        
			        $DbExt->insertData("{{payment_order}}",$params_logs);
			        
			        $params_update=array(
		              'status'=>'paid'
		            );	        
		            $DbExt->updateData("{{order}}",$params_update,'order_id',$_GET['id']);
		        
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
			        
			        Yii::app()->end();
			        
		    	//} else $error= Moneris::CvvResult( $cvv_response );
		    } else $error=$resp->getMessage();	    	
	    	
	   } else $error=t("Credentials not yet set");
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
		  <a href="<?php echo Yii::app()->createUrl('store/paymentoption')?>">
          <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
          </div>	
          
          </form>

          
          </div> <!--box-->
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->          