<?php
if ( $data=Yii::app()->functions->getOrder2($order_id)){
    $merchant_id=$data['merchant_id'];
    $json_details=!empty($data['json_details'])?json_decode($data['json_details'],true):false;
    if ( $json_details !=false){
	    Yii::app()->functions->displayOrderHTML(array(
	       'merchant_id'=>$data['merchant_id'],
	       'order_id'=>$order_id,
	       'delivery_type'=>$data['trans_type'],
	       'delivery_charge'=>$data['delivery_charge'],
	       'packaging'=>$data['packaging'],
	       'cart_tip_value'=>$data['cart_tip_value'],
		   'cart_tip_percentage'=>$data['cart_tip_percentage']/100,
		   'card_fee'=>$data['card_fee'],
		   'donot_apply_tax_delivery'=>$data['donot_apply_tax_delivery'],
		   'points_discount'=>isset($data['points_discount'])?$data['points_discount']:'' /*POINTS PROGRAM*/,
		   'voucher_amount'=>$data['voucher_amount'],
		   'voucher_type'=>$data['voucher_type']
	     ),$json_details,true);
	     
	     /*ITEM TAXABLE*/	     
	     $mtid=$merchant_id;
	     $apply_tax= $data['apply_food_tax'];
	     $tax_set = $data['tax'];	         	 
    	 if ( $apply_tax==1 && $tax_set>0){		    		    		
    		Yii::app()->functions->details['html']=Yii::app()->controller->renderPartial('/front/cart-with-tax',array(
    		   'data'=>Yii::app()->functions->details['raw'],
    		   'tax'=>$tax_set,
    		   'receipt'=>true,
    		   'merchant_id'=>$mtid
    		),true);
    		   		
    	 }	    
	     
	     $data2=Yii::app()->functions->details;
		
        $merchant_info=Yii::app()->functions->getMerchant(isset($merchant_id)?$merchant_id:'');
$full_merchant_address=$merchant_info['street']." ".$merchant_info['city']. " ".$merchant_info['state'].
" ".$merchant_info['post_code'];

		if (isset($data['contact_phone1'])){
			if (!empty($data['contact_phone1'])){
				$data['contact_phone']=$data['contact_phone1'];
			}
		}				
		if (isset($data['location_name1'])){
			if (!empty($data['location_name1'])){
				$data['location_name']=$data['location_name1'];
			}
		}			
	    ?>
	    <div id="thermal-receipt" class="view-receipt-pop currentController_merchant" >
	    <div class="receipt-main-wrap">		   
		   <div class="receipt-wrap order-list-wrap">
		   
		   <!--<a href="javascript:;" class="close-receipt"><i class="fa fa-times"></i></a>-->
		   
		   <?php 
		   $website_enabled_rcpt=getOptionA('website_enabled_rcpt');
		   $website_receipt_logo=getOptionA('website_receipt_logo');
		   if(!empty($website_receipt_logo)){		   	  
		   	  if($website_enabled_rcpt==2){
		   	  echo '<img style="max-width:190px;max-height:120px;display:block;margin:auto;margin-bottom:10px;" src="'. uploadURL()."/$website_receipt_logo" .'" />';
		   	  }
		   }
		   ?>
		   
		   <h4><?php echo Yii::t("default","Order Details")?></h4>
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Name")?> :</div>
	         <div class="value"><?php echo $data['full_name']?></div>
	         <div class="clear"></div>
	       </div>
	       	       
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Merchant Name")?> :</div>
	         <div class="value"><?php echo stripslashes($data['merchant_name'])?></div>
	         <div class="clear"></div>
	       </div>
	       	       
		   <?php if (isset($data['abn']) && !empty($data['abn'])):?>		   
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","ABN")?> :</div>
	         <div class="value"><?php echo $data['abn']?></div>
	         <div class="clear"></div>
	       </div>	       
		   <?php endif;?>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Telephone")?> :</div>
	         <div class="value"><?php echo $data['merchant_contact_phone']?></div>
	         <div class="clear"></div>
	       </div>
		   
		   <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Address")?> :</div>
	         <div class="value"><?php echo $full_merchant_address?></div>
	         <div class="clear"></div>
	       </div>	       
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","TRN Type")?> :</div>
	         <div class="value"><?php echo Yii::t("default",ucwords($data['trans_type']))?></div>
	         <div class="clear"></div>
	       </div>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Payment Type")?> :</div>
	         <!--<div class="value"><?php echo strtoupper(Yii::t("default",$data['payment_type']))?></div>-->
	         <div class="value"><?php echo FunctionsV3::prettyPaymentType('payment_order',
	           $data['payment_type'],$data['order_id']
	         )?></div>
	         <div class="clear"></div>
	       </div>
	       
	       <?php if ( $data['payment_type'] =="pyp"):?>
	       <?php 
	       $paypal_info=Yii::app()->functions->getPaypalOrderPayment($data['order_id']);	       
	       ?>
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Paypal Transaction ID")?> :</div>
	         <div class="value"><?php echo isIsset($paypal_info['TRANSACTIONID'])?></div>
	         <div class="clear"></div>
	       </div>
	       <?php endif;?>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Reference #")?> :</div>
	         <div class="value"><?php echo Yii::app()->functions->formatOrderNumber($data['order_id'])?></div>
	         <div class="clear"></div>
	       </div>
	       
	       <?php if ( $data['payment_provider_name']):?>
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Card#")?> :</div>
	         <div class="value"><?php echo $data['payment_provider_name']?></div>
	         <div class="clear"></div>
	       </div>
	       <?php endif;?>
	       
	       <?php if ( !empty($data['payment_reference'])):?>
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Payment Ref")?> :</div>
	         <div class="value"><?php echo $data['payment_reference']?></div>
	         <div class="clear"></div>
	       </div>	       
	       <?php endif;?>
	       	       
	       <?php if ($data['payment_type']=="ccr" || $data['payment_type']=="ocr" ):?>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Card #")?> :</div>
	         
	         <?php if (isset($_GET['backend'])):?>
	         <div class="value"><?php echo $data['credit_card_number']?></div>           
	         <?php else :?>
	         <div class="value"><?php echo Yii::app()->functions->maskCardnumber($data['credit_card_number'])?></div>           
	         <?php endif;?>
	         <div class="clear"></div>
	       </div>
	       	       	      
	       <?php if (isset($_GET['backendx'])):?>
	       <?php if ( $card_info=Yii::app()->functions->getCreditCardInfo($data['cc_id']) ):?>
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Card name")?> :</div>
	         <div class="value"><?php echo $card_info['card_name']?></div>
	         <div class="clear"></div>
	       </div> 
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Expiration month")?> :</div>
	         <div class="value"><?php echo $card_info['expiration_month']?></div>
	         <div class="clear"></div>
	       </div> 
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Expiration year")?> :</div>
	         <div class="value"><?php echo $card_info['expiration_yr']?></div>
	         <div class="clear"></div>
	       </div> 
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","CVV")?> :</div>
	         <div class="value"><?php echo $card_info['cvv']?></div>
	         <div class="clear"></div>
	       </div> 
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Billing Address")?> :</div>
	         <div class="value"><?php echo $card_info['billing_address']?></div>
	         <div class="clear"></div>
	       </div> 
	       <?php endif;?>
	       <?php endif;?>
	       
	       <?php endif;?><!-- ccr-->
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","TRN Date")?> :</div>
	         <div class="value"><?php 
	         $date= date('M d,Y G:i:s',strtotime($data['date_created']));
	         echo $date=Yii::app()->functions->translateDate($date);
	         ?></div>
	         <div class="clear"></div>
	       </div>
	       
	       <?php if ($data['trans_type']=="delivery"):?>
		       	       
		       <?php if (isset($data['delivery_date'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Delivery Date")?> :</div>
		         <div class="value"><?php $date = prettyDate($data['delivery_date']);
		         echo $date=Yii::app()->functions->translateDate($date);
		         ?></div>
		         <div class="clear"></div>
		       </div>
		       <?php endif;?>
		       
		       <?php if (isset($data['delivery_time'])):?>
		       <?php if ( !empty($data['delivery_time'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Delivery Time")?> :</div>
		         <div class="value">
		         <?php //echo $data['delivery_time']?>
		         <?php echo Yii::app()->functions->timeFormat($data['delivery_time'],true)?>
		         </div>
		         <div class="clear"></div>
		       </div>
		       <?php endif;?>
		       <?php endif;?>
		       
		       <?php if (isset($data['delivery_asap'])):?>
		       <?php if ( !empty($data['delivery_asap'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Deliver ASAP")?> :</div>
		         <div class="value"><?php echo $data['delivery_asap']==1?"Yes":'';?></div>
		         <div class="clear"></div>
		       </div>
		       <?php endif;?>
		       <?php endif;?>
		       
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Deliver to")?> :</div>
		         <div class="value"><?php 
		         if (!empty($data['client_full_address'])){
		         	echo $delivery_address=$data['client_full_address'];
		         } else echo $delivery_address=$data['full_address'];
		         ?></div>
		         <div class="clear"></div>
		       </div>
		       
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Delivery Instruction")?> :</div>
		         <div class="value"><?php echo $data['delivery_instruction']?></div>
		         <div class="clear"></div>
		       </div>
		       
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Location Name")?> :</div>
		         <div class="value"><?php 
		         if (!empty($data['location_name1'])){
		         	$data['location_name']=$data['location_name1'];
		         }
		         echo $data['location_name'];
		         ?></div>
		         <div class="clear"></div>
		       </div>
		       
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Contact Number")?> :</div>
		         <div class="value"><?php 
		         if ( !empty($data['contact_phone1'])){
		         	$data['contact_phone']=$data['contact_phone1'];
		         }
		         echo $data['contact_phone'];?></div>
		         <div class="clear"></div>
		       </div>
		       
		       <?php if ($data['order_change']>=0.1):?>	
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Change")?> :</div>
		         <div class="value"><?php echo displayPrice( baseCurrency(), normalPrettyPrice($data['order_change']))?></div>
		         <div class="clear"></div>
		       </div>		       
		       <?php endif;?>
		   <?php else :?>   
		   
			   <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Contact Number")?> :</div>
		         <div class="value"><?php echo $data['contact_phone']?></div>
		         <div class="clear"></div>
		       </div>		       		       
	       
		      <?php if (isset($data['delivery_date'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Pickup Date")?> :</div>
		         <div class="value"><?php echo $data['delivery_date']?></div>
		         <div class="clear"></div>
		       </div>
		       <?php endif;?>
		       
		       <?php if (isset($data['delivery_time'])):?>
		       <?php if ( !empty($data['delivery_time'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Pickup Time")?> :</div>
		         <div class="value"><?php echo $data['delivery_time']?></div>
		         <div class="clear"></div>
		       </div>
		       <?php endif;?>
		       <?php endif;?>
		       
		       <?php if ($data['order_change']>=0.1):?>	
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Change")?> :</div>
		         <div class="value"><?php echo displayPrice( baseCurrency(), normalPrettyPrice($data['order_change']))?></div>
		         <div class="clear"></div>
		       </div>		       
		       <?php endif;?>
	       
	       <?php endif;?>
	       
	       <div class="spacer-small"></div>
		   
		   <?php echo $data2['html'];?>
		   
		  </div>
		  
        </div>
        </div>
                
	    <?php
    }
} else echo "Receipt not found";