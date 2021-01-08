<?php
$data='';
$ok=false;
$_GET['backend']=true;
if ( $data=Yii::app()->functions->getOrder2($_GET['id'])){		
	$merchant_id=$data['merchant_id'];
	$json_details=!empty($data['json_details'])?json_decode($data['json_details'],true):false;
	if ( $json_details !=false){
		Yii::app()->functions->displayOrderHTML(array(
		  'merchant_id'=>$data['merchant_id'],
		  'delivery_type'=>$data['trans_type'],
		  'delivery_charge'=>$data['delivery_charge'],
		  'packaging'=>$data['packaging'],
		  'cart_tip_value'=>$data['cart_tip_value'],
		  'cart_tip_percentage'=>$data['cart_tip_percentage']/100,
		  'card_fee'=>$data['card_fee'],
		  'tax'=>$data['tax'],
		  'points_discount'=>isset($data['points_discount'])?$data['points_discount']:'' /*POINTS PROGRAM*/,
		  'voucher_amount'=>$data['voucher_amount'],
		  'voucher_type'=>$data['voucher_type']
		  ),$json_details,true);
		if ( Yii::app()->functions->code==1){
			$ok=true;
		}
	}	
}
$print=array();
//dump(Yii::app()->functions->details['raw']);
//die();
?>

<div class="page" id="fax_page">
	<div class="main"> 
	<div class="inner">
     <?php if ($ok==TRUE):?>
         <div class="receipt-main-wrap">
         <!--<h3><?php echo Yii::t("default","Thank You")?></h3>
         <p><?php echo Yii::t("default","Your order has been placed.")?></p>-->
         
	     <div class="receipt-wrap order-list-wrap">
	       <h4><?php echo Yii::t("default","Order Details")?></h4>
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Name")?> :</div>
	         <div class="value"><?php echo $data['full_name']?></div>
	         <div class="clear"></div>
	       </div>
	       
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Name"),
	         'value'=>$data['full_name']
	       );
	       ?>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Merchant Name")?> :</div>
	         <div class="value"><?php echo stripslashes($data['merchant_name'])?></div>
	         <div class="clear"></div>
	       </div>
	       
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Merchant Name"),
	         'value'=>$data['merchant_name']
	       );
	       ?>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","TRN Type")?> :</div>
	         <div class="value"><?php echo Yii::t("default",$data['trans_type'])?></div>
	         <div class="clear"></div>
	       </div>
	       
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","TRN Type"),
	         'value'=>$data['trans_type']
	       );
	       ?>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Payment Type")?> :</div>
	         <div class="value"><?php echo strtoupper(Yii::t("default",$data['payment_type']))?></div>
	         <div class="clear"></div>
	       </div>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Payment Type"),
	         'value'=>strtoupper($data['payment_type'])
	       );
	       ?>
	       	       
	       <?php if ( $data['payment_type'] =="pyp"):?>
	       <?php 
	       $paypal_info=Yii::app()->functions->getPaypalOrderPayment($data['order_id']);	       
	       ?>
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Paypal Transaction ID")?> :</div>
	         <div class="value"><?php echo isset($paypal_info['TRANSACTIONID'])?$paypal_info['TRANSACTIONID']:'';?></div>
	         <div class="clear"></div>
	       </div>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Paypal Transaction ID"),
	         'value'=>isset($paypal_info['TRANSACTIONID'])?$paypal_info['TRANSACTIONID']:''
	       );
	       ?>
	       <?php endif;?>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Reference #")?> :</div>
	         <div class="value"><?php echo Yii::app()->functions->formatOrderNumber($data['order_id'])?></div>
	         <div class="clear"></div>
	       </div>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Reference #"),
	         'value'=>Yii::app()->functions->formatOrderNumber($data['order_id'])
	       );
	       ?>
	       
	       <?php if ( !empty($data['payment_reference'])):?>
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Payment Ref")?> :</div>
	         <div class="value"><?php echo $data['payment_reference']?></div>
	         <div class="clear"></div>
	       </div>
	       <?php
	       $print[]=array(
	         'label'=>Yii::t("default","Payment Ref"),
	         'value'=>Yii::app()->functions->formatOrderNumber($data['order_id'])
	       );
	       ?>
	       <?php endif;?>
	       	       
	       <?php if ($data['payment_type']=="ccr" || $data['payment_type']=="ocr"):?>
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","Card #")?> :</div>
	         <div class="value"><?php echo $card=Yii::app()->functions->maskCardnumber($data['credit_card_number'])?></div>
	         <div class="clear"></div>
	       </div>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Card #"),
	         'value'=>$card
	       );
	       ?>
	       <?php endif;?>
	       
	       <div class="input-block">
	         <div class="label"><?php echo Yii::t("default","TRN Date")?> :</div>
	         <div class="value"><?php $trn_date=date('M d,Y G:i:s',strtotime($data['date_created']));
	         echo Yii::app()->functions->translateDate($trn_date);
	         ?></div>
	         <div class="clear"></div>
	       </div>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","TRN Date"),
	         'value'=>$trn_date
	       );
	       ?>
	       
	       <?php if ($data['trans_type']=="delivery"):?>
		       	       
		       <?php if (isset($data['delivery_date'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Delivery Date")?> :</div>
		         <div class="value"><?php $deliver_date=prettyDate($data['delivery_date']);
		         echo Yii::app()->functions->translateDate($deliver_date);
		         ?></div>
		         <div class="clear"></div>
		       </div>
		       <?php 	       
		       $print[]=array(
		         'label'=>Yii::t("default","Delivery Date"),
		         'value'=>$deliver_date
		       );
		       ?>
		       <?php endif;?>
		       
		       <?php if (isset($data['delivery_time'])):?>
		       <?php if ( !empty($data['delivery_time'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Delivery Time")?> :</div>
		         <div class="value"><?php //echo $_SESSION['kr_delivery_options']['delivery_time']?>
		         <?php echo Yii::app()->functions->timeFormat($data['delivery_time'],true)?>
		         </div>
		         <div class="clear"></div>
		       </div>
		       <?php 	       
		       $print[]=array(
		         'label'=>Yii::t("default","Delivery Time"),
		         'value'=>$data['delivery_time']
		       );
		       ?>
		       <?php endif;?>
		       <?php endif;?>
		       
		       <?php if (isset($data['delivery_asap'])):?>
		       <?php if ( !empty($data['delivery_asap'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Deliver ASAP")?> :</div>
		         <div class="value"><?php echo $delivery_asap=$data['delivery_asap']==1?"Yes":'';?></div>
		         <div class="clear"></div>
		       </div>
			   <?php 	       
				$print[]=array(
				 'label'=>Yii::t("default","Deliver ASAP"),
				 'value'=>$delivery_asap
				);
				?>
		       <?php endif;?>
		       <?php endif;?>
		       
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Deliver to")?> :</div>
		         <div class="value">
		         <?php 		         
		         if (!empty($data['client_full_address'])){		         	
		         	echo $delivery_address=$data['client_full_address'];
		         } else echo $delivery_address=$data['full_address'];		         
		         ?>
		         </div>
		         <div class="clear"></div>
		       </div>
				<?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Deliver to"),
				  'value'=>$data['full_address']
				);
				?>
		       
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Delivery Instruction")?> :</div>
		         <div class="value"><?php echo $data['delivery_instruction']?></div>
		         <div class="clear"></div>
		       </div>
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Delivery Instruction"),
				  'value'=>$data['delivery_instruction']
				);
				?>
		       
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Location Name")?> :</div>
		         <div class="value"><?php echo $data['location_name']?></div>
		         <div class="clear"></div>
		       </div>
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Location Name"),
				  'value'=>$data['location_name']
				);
				?>
				
				<div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Contact Number")?> :</div>
		         <div class="value"><?php echo $data['contact_phone']?></div>
		         <div class="clear"></div>
		       </div>		       
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Contact Number"),
				  'value'=>$data['contact_phone']
				);
				?>
				
				<?php if ($data['order_change']>=0.1):?>	
				<div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Change")?> :</div>
		         <div class="value"><?php echo displayPrice( baseCurrency(), normalPrettyPrice($data['order_change']))?></div>
		         <div class="clear"></div>
		       </div>		       
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Change"),
				  'value'=>normalPrettyPrice($data['order_change'])
				);
				?>
				<?php endif;?>
				
		   <?php else :?>   
		   
		      <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Contact Number")?> :</div>
		         <div class="value"><?php echo $data['contact_phone']?></div>
		         <div class="clear"></div>
		       </div>
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Contact Number"),
				  'value'=>$data['contact_phone']
				);
				?>
		       		     		  
		      <?php if (isset($data['delivery_date'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Pickup Date")?> :</div>
		         <div class="value"><?php echo $data['delivery_date']?></div>
		         <div class="clear"></div>
		       </div>
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Pickup Date"),
				  'value'=>$data['delivery_date']
				);
				?>
		       <?php endif;?>
		       
		       <?php if (isset($data['delivery_time'])):?>
		       <?php if ( !empty($data['delivery_time'])):?>
		       <div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Pickup Time")?> :</div>
		         <div class="value"><?php echo $data['delivery_time']?></div>
		         <div class="clear"></div>
		       </div>
		       <?php 	  
				$print[]=array(
				 'label'=>Yii::t("default","Pickup Time"),
				 'value'=>$data['delivery_time']
				);
				?>
		       <?php endif;?>
		       <?php endif;?>
		       
		       <?php if ($data['order_change']>=0.1):?>	
				<div class="input-block">
		         <div class="label"><?php echo Yii::t("default","Change")?> :</div>
		         <div class="value"><?php echo displayPrice( baseCurrency(), normalPrettyPrice($data['order_change']))?></div>
		         <div class="clear"></div>
		       </div>		       
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Change"),
				  'value'=>$data['order_change']
				);
				?>
				<?php endif;?> 
		       
	       
	       <?php endif;?>
	       
	       <div class="spacer-small"></div>
	       
	       <?php echo $item_details=Yii::app()->functions->details['html'];?>
	     </div> <!--receipt-wrap-->
	     	    
        
        </div>
     <?php else :?>
     <p class="uk-alert uk-alert-warning"><?php echo Yii::t("default","Sorry but we cannot find what you are looking for.")?></p>
     <?php endif;?>
     </div>
    </div> <!--main-->
</div> <!--page-->