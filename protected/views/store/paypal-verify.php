<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));

$paypal_con=Yii::app()->functions->getPaypalConnection($_SESSION['kr_merchant_id']); 

//if ( Yii::app()->functions->isMerchantCommission($_SESSION['kr_merchant_id'])){
if (FunctionsV3::isMerchantPaymentToUseAdmin($_SESSION['kr_merchant_id'])){
	   unset($paypal_con);   	   
	   $paypal_con=Yii::app()->functions->getPaypalConnectionAdmin();
} 

$paypal=new Paypal($paypal_con);
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
    <div class="row top30">
       <div class="inner">
          <h1><?php echo t("Review your order")?></h1>
          <div class="box-grey rounded">	     
                                                
           <?php if ($res_paypal=$paypal->getExpressDetail()): //dump($res_paypal);?>
              <?php $token=$res_paypal['TOKEN'];?>
              <?php if ($order_info=Yii::app()->functions->getOrderByPayPalToken($token)):
                    $order_id=$order_info['order_id'];                                        
              ?>              
                   <?php if ( $data=Yii::app()->functions->getOrder($order_id)):?>
                   <?php 
                    $merchant_id=$data['merchant_id'];
					$json_details=!empty($data['json_details'])?json_decode($data['json_details'],true):false;					
                    ?>
                    <?php if ( $json_details !=false):?>
                    
                    <?php                     
                    Yii::app()->functions->displayOrderHTML(array(
				       'merchant_id'=>$data['merchant_id'],
				       'order_id'=>$order_id,
				       'delivery_type'=>$data['trans_type'],
				       'delivery_charge'=>$data['delivery_charge'],
				       'packaging'=>$data['packaging'],
				       'cart_tip_value'=>$data['cart_tip_value'],
					   'cart_tip_percentage'=>$data['cart_tip_percentage']/100,
					   'card_fee'=>$data['card_fee'],
				       'voucher_amount'=>$data['voucher_amount'],
		               'voucher_type'=>$data['voucher_type']
				    ),$json_details,true,$order_id);
					
				    /*ITEM TAXABLE*/				    
					$apply_tax = $data['apply_food_tax'];
				    $tax_set = $data['tax'];	         	 
					if ( $apply_tax==1 && $tax_set>0){								
					    Yii::app()->functions->details['html']=Yii::app()->controller->renderPartial('/front/cart-with-tax',array(
			    		   'data'=>Yii::app()->functions->details['raw'],
			    		   'tax'=>$tax_set,
			    		   'receipt'=>true,
			    		   'merchant_id'=>$data['merchant_id']
			    		),true);
					}
				    
					$data2=Yii::app()->functions->details;
                    ?>      

                    <div class="row top10">
                      <div class="col-md-4 border" ><?php echo t("Paypal Name")?></div>
                      <div class="col-md-8 border" ><?php echo $res_paypal['FIRSTNAME']." ".$res_paypal['LASTNAME']?></div>
                    </div>
                    
                    <div class="row top10">
                      <div class="col-md-4 border" ><?php echo t("Paypal Email")?></div>
                      <div class="col-md-8 border" ><?php echo $res_paypal['EMAIL']?></div>
                    </div>
                    
                    <div class="top10">
                    <?php echo $data2['html'];?>                                       
                    </div>
                    
                    <form method="POST" id="forms" class="forms" onsubmit="return false;">				
					<input type="hidden" name="token" value="<?php echo $_GET['token']?>">
					<input type="hidden" name="payerid" value="<?php echo $res_paypal['PAYERID']?>">
					<input type="hidden" name="amount" value="<?php echo $res_paypal['AMT']?>">
					<?php echo CHtml::hiddenField('action','paypalCheckoutPayment')?>
					<?php echo CHtml::hiddenField('currentController','store')?>
					<input type="hidden" name="order_id" value="<?php echo $order_id;?>">
					
					<div class="top10">
					<input type="submit" value="<?php echo t("Pay Now")?>" class="paypal_paynow black-button medium inline">
					</div>					
					</form>
					                    
                    <?php endif;?>
                   <?php else :?>
                     <p class="text-danger"><?php echo t("ERROR: Cannot get order details.")?></p>
                   <?php endif;?>
                                  
               <?php else :?>
                  <p class="text-danger"><?php echo Yii::t("default","ERROR: Under ID not found.")?></p>
               <?php endif;?>              
           <?php else :?>
               <p class="text-danger"><?php echo $paypal->getError();?></p>
           <?php endif;?>
          
          </div> <!--box-->
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->
