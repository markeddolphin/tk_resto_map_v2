<?php
unset($_SESSION['pts_earn']);
unset($_SESSION['pts_redeem_amt']);
unset($_SESSION['kr_delivery_options']['opt_contact_delivery']);
unset($_SESSION['checkout_resp_geocode']);

$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Thank You"),
   'sub_text'=>t("Your order has been placed.")
));

$ok=false;			
if (is_array($data) && count($data)>=1){
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
		  'voucher_type'=>$data['voucher_type'],
		  'tax_set'=>$data['tax'],
		  ),$json_details,true);
		if ( Yii::app()->functions->code==1){
			$ok=true;
		}
		
		/*ITEM TAXABLE*/
		$mtid = $merchant_id;
		$apply_tax = $data['apply_food_tax'];
	    $tax_set = $data['tax'];	         	 
		if ( $apply_tax==1 && $tax_set>0){							    
		    Yii::app()->functions->details['html']=Yii::app()->controller->renderPartial('/front/cart-with-tax',array(
    		   'data'=>Yii::app()->functions->details['raw'],
    		   'tax'=>$tax_set,
    		   'receipt'=>true,    		
    		   'merchant_id'=>$mtid   
    		),true);
		}
		
		/*dump(Yii::app()->functions->details['raw']);
		die();*/
	}	
}
unset($_SESSION['kr_item']);
unset($_SESSION['kr_merchant_id']);
unset($_SESSION['voucher_code']);
unset($_SESSION['less_voucher']);
unset($_SESSION['shipping_fee']);
unset($_SESSION['shipping_distance']);

$print=array();

$order_ok=true;

$merchant_info=Yii::app()->functions->getMerchant(isset($merchant_id)?$merchant_id:'');
$full_merchant_address=$merchant_info['street']." ".$merchant_info['city']. " ".$merchant_info['state'].
" ".$merchant_info['post_code'];

$transaction_type=$data['trans_type'];
?>

<div class="sections section-grey2 section-receipt">
   <div class="container">
      
   <?php if ($ok==TRUE):?>
   <div class="inner" id="receipt-content">
	   <h1><?php echo t("Order Details")?></h1>
	   <div class="box-grey">     
	   
	   <div class="text-center bottom10">
	   <i class="ion-ios-checkmark-outline i-big-extra green-text"></i>
	   </div>
	   	   
	   <table class="table table-striped">
	    <tbody>	     
	       
	       <tr>
	         <td><?php echo Yii::t("default","Customer Name")?></td>
	         <td class="text-right"><?php echo $data['full_name']?></td>
	       </tr>	       
	       <?php $print[]=array( 'label'=>Yii::t("default","Customer Name"), 'value'=>$data['full_name'] );?>	       
	       <tr>
	         <td><?php echo Yii::t("default","Merchant Name")?></td>
	         <td class="text-right"><?php echo clearString($data['merchant_name'])?></td>
	       </tr>       
	       <?php $print[]=array( 'label'=>Yii::t("default","Merchant Name"), 'value'=>$data['merchant_name']); ?>
	       
	       <?php if (isset($data['abn']) && !empty($data['abn'])):?>	       
	       <tr>
	         <td><?php echo Yii::t("default","ABN")?></td>
	         <td class="text-right"><?php echo $data['abn']?></td>
	       </tr> 
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","ABN"),
	         'value'=>$data['abn']
	       );
	       ?>
	       <?php endif;?>
	       
	       <tr>
	         <td><?php echo Yii::t("default","Telephone")?></td>
	         <td class="text-right"><?php echo $data['merchant_contact_phone']?></td>
	       </tr>	       
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Telephone"),
	         'value'=>$data['merchant_contact_phone']
	       );
	       ?>
		   		   
	       <tr>
	         <td><?php echo Yii::t("default","Address")?></td>
	         <td class="text-right"><?php echo $full_merchant_address?></td>
	       </tr>    
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Address"),
	         'value'=>$full_merchant_address
	       );
	       ?>
	       
	       <?php $merchant_tax_number=getOption($merchant_id,'merchant_tax_number');?>
	       <?php if (!empty($merchant_tax_number)):?>
		       <tr>
		         <td><?php echo Yii::t("default","Tax number")?></td>
		         <td class="text-right"><?php echo $merchant_tax_number?></td>
		       </tr>    
		       <?php 	       
		       $print[]=array(
		         'label'=>Yii::t("default","Tax number"),
		         'value'=>$merchant_tax_number
		       );
		       ?>
	       <?php endif;?>
	       	       
	       <tr>
	         <td><?php echo Yii::t("default","TRN Type")?></td>
	         <td class="text-right"><?php echo Yii::t("default",$data['trans_type'])?></td>
	       </tr>
	       
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","TRN Type"),
	         'value'=>t($data['trans_type'])
	       );	       
	       ?>
	       	       
	       <tr>
	         <td><?php echo Yii::t("default","Payment Type")?></td>
	         <!--<td class="text-right"><?php echo strtoupper(t($data['payment_type']))?></td>-->
	         <td class="text-right">
	         <?php echo FunctionsV3::prettyPaymentType('payment_order',
	         $data['payment_type'],$_GET['id'],$data['trans_type'])?>
	         </td>
	       </tr>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Payment Type"),
	         'value'=>FunctionsV3::prettyPaymentType('payment_order',$data['payment_type'],$_GET['id'],$data['trans_type'])
	       );	       
	       ?>
	       	       
	       <?php if ( $data['payment_provider_name']):?>	      
	       <tr>
	         <td><?php echo Yii::t("default","Card#")?></td>
	         <td class="text-right"><?php echo $data['payment_provider_name']?></td>
	       </tr>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Card#"),
	         'value'=>strtoupper($data['payment_provider_name'])
	       );
	       ?>
	       <?php endif;?>	       	       
	       	       
	       <?php if ( $data['payment_type'] =="pyp"):?>
	       <?php 
	       $paypal_info=Yii::app()->functions->getPaypalOrderPayment($data['order_id']);	       
	       ?>	       
	       <tr>
	         <td><?php echo Yii::t("default","Paypal Transaction ID")?></td>
	         <td class="text-right"><?php echo isset($paypal_info['TRANSACTIONID'])?$paypal_info['TRANSACTIONID']:'';?></td>
	       </tr>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Paypal Transaction ID"),
	         'value'=>isset($paypal_info['TRANSACTIONID'])?$paypal_info['TRANSACTIONID']:''
	       );
	       ?>
	       <?php endif;?>
	       	       
	       <tr>
	         <td><?php echo Yii::t("default","Reference #")?></td>
	         <td class="text-right"><?php echo Yii::app()->functions->formatOrderNumber($data['order_id'])?></td>
	       </tr>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Reference #"),
	         'value'=>Yii::app()->functions->formatOrderNumber($data['order_id'])
	       );
	       ?>
	       
	       <?php if ( !empty($data['payment_reference'])):?>	      
	       <tr>
	         <td><?php echo Yii::t("default","Payment Ref")?></td>
	         <td class="text-right"><?php echo $data['payment_reference']?></td>
	       </tr>
	       <?php
	       $print[]=array(
	         'label'=>Yii::t("default","Payment Ref"),
	         'value'=>$data['payment_reference']
	       );
	       ?>
	       <?php endif;?>
	       	       
	       <?php if ( $data['payment_type']=="ccr" || $data['payment_type']=="ocr"):?>	       
	       <tr>
	         <td><?php echo Yii::t("default","Card #")?></td>
	         <td class="text-right"><?php echo $card=Yii::app()->functions->maskCardnumber($data['credit_card_number'])?></td>
	       </tr>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","Card #"),
	         'value'=>$card
	       );
	       ?>
	       <?php endif;?>
	       	       
	       <tr>
	         <td><?php echo Yii::t("default","TRN Date")?></td>
	         <td class="text-right">
	         <?php 
	         /*$trn_date=date('M d,Y G:i:s',strtotime($data['date_created']));
	         echo Yii::app()->functions->translateDate($trn_date);*/
	         echo $trn_date = FunctionsV3::prettyDate($data['date_created'])." ".FunctionsV3::prettyTime($data['date_created']);
	         ?>
	         </td>
	       </tr>
	       <?php 	       
	       $print[]=array(
	         'label'=>Yii::t("default","TRN Date"),
	         'value'=>$trn_date
	       );
	       ?>
	       
	       <?php if ($data['trans_type']=="delivery"):?>
		       	       
		       <?php if (isset($_SESSION['kr_delivery_options']['delivery_date'])):?>		       
		       <tr>
		         <td><?php echo Yii::t("default","Delivery Date")?></td>
		         <td class="text-right">
		         <?php 
		         /*$deliver_date=prettyDate($_SESSION['kr_delivery_options']['delivery_date']);
		         echo Yii::app()->functions->translateDate($deliver_date);*/		         
		         $deliver_date=FunctionsV3::prettyDate($_SESSION['kr_delivery_options']['delivery_date']);
		         echo $deliver_date;
		         ?>
		         </td>
		       </tr>
		       <?php 	       
		       $print[]=array(
		         'label'=>Yii::t("default","Delivery Date"),
		         'value'=>$deliver_date
		       );
		       ?>
		       <?php endif;?>
		       		     
		       <?php if($data['delivery_asap']!=1):?>
		       <?php if (isset($_SESSION['kr_delivery_options']['delivery_time'])):?>
		       <?php if ( !empty($_SESSION['kr_delivery_options']['delivery_time'])):?>		       
		       <tr>
		         <td><?php echo Yii::t("default","Delivery Time")?></td>
		         <td class="text-right">
		           <?php //echo Yii::app()->functions->timeFormat($_SESSION['kr_delivery_options']['delivery_time'],true)
		            echo $delivery_time = FunctionsV3::prettyTime($_SESSION['kr_delivery_options']['delivery_time']);
		           ?> 
		           </td>
		       </tr>
		       <?php 	       
		       $print[]=array(
		         'label'=>Yii::t("default","Delivery Time"),
		         'value'=>$delivery_time
		       );
		       ?>
		       <?php endif;?>
		       <?php endif;?>
		       <?php endif;?>
		       
		       
		       <?php if($data['delivery_asap']==1):?>
		       <?php if (isset($_SESSION['kr_delivery_options']['delivery_asap'])):?>
		       <?php if ( !empty($_SESSION['kr_delivery_options']['delivery_asap'])):?>		       
		       <tr>
		         <td><?php echo Yii::t("default","Deliver ASAP")?></td>
		         <td class="text-right">
		         <?php echo $delivery_asap=$_SESSION['kr_delivery_options']['delivery_asap']==1?t("Yes"):'';?>
		         </td>
		       </tr>
			   <?php 	       
				$print[]=array(
				 'label'=>Yii::t("default","Deliver ASAP"),
				 'value'=>$delivery_asap
				);
				?>
		       <?php endif;?>
		       <?php endif;?>
		       <?php endif;?>
		       		       
		       <tr>
		         <td><?php echo Yii::t("default","Deliver to")?></td>
		         <td class="text-right">
		         <?php 		         
		         if (!empty($data['client_full_address'])){		         	
		         	echo $delivery_address=$data['client_full_address'];
		         } else echo $delivery_address=$data['full_address'];		         
		         ?>
		         </td>
		       </tr>
				<?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Deliver to"),
				  'value'=>$delivery_address
				);
				?>
						       		      
		       <tr>
		         <td><?php echo Yii::t("default","Delivery Instruction")?></td>
		         <td class="text-right"><?php echo $data['delivery_instruction']?></td>
		       </tr>
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Delivery Instruction"),
				  'value'=>$data['delivery_instruction']
				);
				?>
		       		       
		       <tr>
		         <td><?php echo Yii::t("default","Location Name")?></td>
		         <td class="text-right">
		         <?php 
		         if (!empty($data['location_name1'])){
		         	$data['location_name']=$data['location_name1'];
		         }
		         echo $data['location_name'];
		         ?>
		         </td>
		       </tr>
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Location Name"),
				  'value'=>$data['location_name']
				);
				?>
								
		       <tr>
		         <td><?php echo Yii::t("default","Contact Number")?></td>
		         <td class="text-right">
		         <?php 
		         if ( !empty($data['contact_phone1'])){
		         	$data['contact_phone']=$data['contact_phone1'];
		         }
		         echo $data['contact_phone'];?>
		         </td>
		       </tr>       
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Contact Number"),
				  'value'=>$data['contact_phone']
				);
				?>
				
				<?php if ($data['order_change']>=0.1):?>					
		       <tr>
		         <td><?php echo Yii::t("default","Change")?></td>
		         <td class="text-right">
		         <?php echo displayPrice( baseCurrency(), normalPrettyPrice($data['order_change']))?>
		         </td>
		       </tr>     
		       <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Change"),
				  'value'=>normalPrettyPrice($data['order_change'])
				);
				?>
				<?php endif;?>
				
								
		   <?php else :?>   
		   
		      <?php 
		      $label_date=t("Pickup Date");
		      $label_time=t("Pickup Time");
		      if ($transaction_type=="dinein"){
		      	  $label_date=t("Dine in Date");
		          $label_time=t("Dine in Time");
		      }
		      ?>
		   		   		  
               <?php 
				if (isset($data['contact_phone1'])){
					if (!empty($data['contact_phone1'])){
						$data['contact_phone']=$data['contact_phone1'];
					}
				}
			   ?>		      
		       <tr>
		         <td><?php echo Yii::t("default","Contact Number")?></td>
		         <td class="text-right"><?php echo $data['contact_phone']?></td>
		       </tr>
		       <?php 	       		       
				$print[]=array(
				  'label'=>Yii::t("default","Contact Number"),
				  'value'=>$data['contact_phone']
				);
				?>
		       		     		  
		      <?php if (isset($_SESSION['kr_delivery_options']['delivery_date'])):?>		       
		       <tr>
		         <td><?php echo $label_date?></td>
		         <td class="text-right">
		         <?php echo FunctionsV3::prettyDate($_SESSION['kr_delivery_options']['delivery_date'])?>
		         </td>
		       </tr>
		       <?php 	       
				$print[]=array(
				  'label'=>$label_date,
				  'value'=>FunctionsV3::prettyDate($_SESSION['kr_delivery_options']['delivery_date'])
				);
				?>
		       <?php endif;?>
		       
		       <?php if (isset($_SESSION['kr_delivery_options']['delivery_time'])):?>
		       <?php if ( !empty($_SESSION['kr_delivery_options']['delivery_time'])):?>		       
		       <tr>
		         <td><?php echo $label_time?></td>
		         <td class="text-right">
		         <?php echo FunctionsV3::prettyTime($_SESSION['kr_delivery_options']['delivery_time'],true)?>
		         </td>
		       </tr>
		       <?php 	       
				$print[]=array(
				 'label'=>$label_time,
				 'value'=>FunctionsV3::prettyTime($_SESSION['kr_delivery_options']['delivery_time'],true)
				);
				?>
		       <?php endif;?>
		       <?php endif;?>
		       
		       <?php if ($data['order_change']>=0.1):?>					
		       <tr>
		         <td><?php echo Yii::t("default","Change")?></td>
		         <td class="text-right">
		         <?php echo displayPrice( baseCurrency(), normalPrettyPrice($data['order_change']))?>
		         </td>
		       </tr>  
		        <?php 	       
				$print[]=array(
				  'label'=>Yii::t("default","Change"),
				  'value'=>$data['order_change']
				);
				?>
				<?php endif;?> 
				
			   <?php if ($transaction_type=="dinein"):?>
			    <tr>
		         <td><?php echo t("Number of guest")?></td>
		         <td class="text-right">
		         <?php echo $data['dinein_number_of_guest']?>
		         </td>
		       </tr>  
		       <tr>
		         <td><?php echo t("Table number")?></td>
		         <td class="text-right">
		         <?php echo isset($data['dinein_table_number'])?$data['dinein_table_number']:'';?>
		         </td>
		       </tr>  
		       <tr>
		         <td><?php echo t("Special instructions")?></td>
		         <td class="text-right">
		         <?php echo stripslashes($data['dinein_special_instruction'])?>
		         </td>
		       </tr>  		       
		       <?php 	       
				$print[]=array(
				  'label'=>t("Number of guest"),
				  'value'=>$data['dinein_number_of_guest']
				);
				$print[]=array(
				  'label'=>t("Table number"),
				  'value'=>isset($data['dinein_table_number'])?$data['dinein_table_number']:''
				);
				$print[]=array(
				  'label'=>t("Special instructions"),
				  'value'=>$data['dinein_special_instruction']
				);
				?>
			   <?php endif;?>
		       
	       
	       <?php endif;?>
	       
	       <?php if($transaction_type=="delivery" && $data['opt_contact_delivery']==1):?>
	       <tr>
	        <td><?php echo t("Delivery options")?></td>
	        <td class="text-right"><?php echo t("Leave order at the door or gate")?></td>
	       </tr>
	       <?php 
	       $print[]=array(
			  'label'=>t("Delivery options"),
			  'value'=>t("Leave order at the door or gate")
			);
	       ?>
	       <?php endif;?>
	       
	       <tr>
			 <td colspan="2"></td>
		   </tr>
	       	    
	    </tbody>
	   </table>
	   
	    <div class="receipt-wrap order-list-wrap">
	    <?php echo $item_details=Yii::app()->functions->details['html'];?>
	    </div>
	   
	   </div> <!--box-grey-->
	   
   </div> <!--inner-->
   
   <div class="row">
      <div class="col-sm-12 text-right">
        <a href="javascript:;" class="print-receipt"><i class="ion-ios-printer-outline"></i></a>
      </div> <!--col-->
   </div> <!--row-->
    
   <?php else :?>
    <p class="text-warning"><?php echo t("Sorry but we cannot find what you are looking for.")?></p>
    <?php $order_ok=false;?>
   <?php endif;?>
    
   </div> <!--container-->
</div>  <!--section-receipt-->

<?php     
$data_raw=Yii::app()->functions->details['raw'];
if ( $apply_tax==1 && $tax_set>0){	
	$receipt=EmailTPL::salesReceiptTax($print,Yii::app()->functions->details['raw']);
} else $receipt=EmailTPL::salesReceipt($print,Yii::app()->functions->details['raw']);

$to=isset($data['email_address'])?$data['email_address']:'';

if (!isset($_SESSION['kr_receipt'])){
	$_SESSION['kr_receipt']='';
}

if (!in_array($data['order_id'],(array)$_SESSION['kr_receipt'])){
	if ($order_ok==true){		
		/*SEND EMAIL TO CUSTOMER*/
		FunctionsV3::notifyCustomer($data,Yii::app()->functions->additional_details,$receipt, $to);
		FunctionsV3::notifyMerchant($data,Yii::app()->functions->additional_details,$receipt);
		FunctionsV3::notifyAdmin($data,Yii::app()->functions->additional_details,$receipt);
	
	   FunctionsV3::fastRequest(FunctionsV3::getHostURL().Yii::app()->createUrl("cron/processemail"));
	   FunctionsV3::fastRequest(FunctionsV3::getHostURL().Yii::app()->createUrl("cron/processsms"));
	   
	   // SEND FAX
       Yii::app()->functions->sendFax($merchant_id,$_GET['id']);
       
       /*PRINTER ADDON*/
       if (FunctionsV3::hasModuleAddon("printer")){
			Yii::app()->setImport(array('application.modules.printer.components.*',));
			
			$html=getOptionA('printer_receipt_tpl');
			if($print_receipt = ReceiptClass::formatReceipt($html,$print,Yii::app()->functions->details['raw'],$data)){							
				PrinterClass::printReceipt($data['order_id'],$print_receipt);												
			}
			
			$html = getOption($merchant_id,'mt_printer_receipt_tpl');
			if($print_receipt = ReceiptClass::formatReceipt($html,$print,Yii::app()->functions->details['raw'],$data)){
		       PrinterClass::printReceiptMerchant($merchant_id,$data['order_id'],$print_receipt);		
			}		
			FunctionsV3::fastRequest(FunctionsV3::getHostURL().Yii::app()->createUrl("printer/cron/processprint"));		
		}
	}
}

$_SESSION['kr_receipt']=array($data['order_id']);