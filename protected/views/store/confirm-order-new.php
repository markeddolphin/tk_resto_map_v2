<?php
$this->renderPartial('/front/default-header',array(
   'h1'=>t("Confirm Order"),
   'sub_text'=>t("please review your order below")
));?>

<?php 
$this->renderPartial('/front/order-progress-bar',array(
   'step'=>isset($step)?$step:5,
   'show_bar'=>true,
   'guestcheckout'=>isset($guestcheckout)?$guestcheckout:false
));
?>
<form id="frm-delivery" class="frm-delivery" method="POST" onsubmit="return false;">
<?php 
$mtid=isset($data['merchant_id'])?$data['merchant_id']:'';
echo CHtml::hiddenField('action','placeOrder');
foreach ($data as $key=>$val) {	
	switch ($key) {
		case "payment_opt":
			echo CHtml::radioButton($key,true,array(
			  'value'=>$val,
			  'class'=>"payment_option hide_inputs"
			));
			break;

		case "payment_provider_name":	
		   echo CHtml::radioButton($key,true,array(
			  'value'=>$val,
			  'class'=>"hide_inputs"
			));
			break;
		   break;
		   
		case "cc_id":	
		    echo CHtml::radioButton($key,true,array(
			  'value'=>$val,
			  'class'=>"cc_id hide_inputs"
			));
		    break; 
		    
		case "card_fee":    
		   $cs = Yii::app()->getClientScript();			
		   $cs->registerScript(
			  'card_fee',
			 "var card_fee='$val';",
			  CClientScript::POS_HEAD
		   );
		   break; 
		   
		default:
			echo CHtml::hiddenField($key,$val);	
			break;
	}
}

$transaction_type=isset($data['delivery_type'])?$data['delivery_type']:'';

switch ($transaction_type) {
	case "delivery":
		$header_1='Delivery information';
		$header_2='Delivery Address';
		$label_1='Delivery Date';
		$label_2='Delivery Time';
		
		$address=$data['street']." ";
		if (isset($data['area_name'])){
			$address.=$data['area_name']." ";
		}
		if (isset($data['city'])){
		    $address.=$data['city']." ";
		}		
		if (isset($data['state'])){
		   $address.=$data['state']." ";
		}
		if (isset($data['zipcode'])){
		   $address.=$data['zipcode']." ";
		}
		
		if (isset($data['address_book_id'])){
			if ( $address_book = Yii::app()->functions->getAddressBookByID($data['address_book_id'])){
				$address=$address_book['street'];
				$address.=" ".$address_book['city'];
				$address.=" ".$address_book['state'];
				$address.=" ".$address_book['zipcode'];
				$address.=" ".$address_book['country_code'];
			}
		}
				
		if (isset($data['map_address_lat'])){
			if(!empty($data['map_address_lat'])){
				$lat_res=FunctionsV3::latToAdress($data['map_address_lat'],$data['map_address_lng']);				
				if($lat_res){					
					$address=$lat_res['formatted_address'];
				}
			}
		}
		
		break;
		
	case "pickup":	
	    $header_1='Pickup information';
	    $header_2='Pickup Address';
	    $label_1='Pickup Date';
		$label_2='Pickup Time';
						
	    $address='';
	    if ( $merchant_info=FunctionsV3::getMerchantInfo($mtid)){
	    	$address=$merchant_info['complete_address'];
	    }
	    break;
	    
	case "dinein":    
	    $header_1='Dine in information';
	    $header_2='Dine in Address';
	    $label_1='Dine in Date';
		$label_2='Dine in Time';
		$address='';
	    if ( $merchant_info=FunctionsV3::getMerchantInfo($mtid)){
	    	$address=$merchant_info['complete_address'];
	    }
	   break;

	default:
		break;
}
if (!isset($s['kr_delivery_options'])){
   $s['kr_delivery_options']='';
}

if (!isset($data['is_guest_checkout'])){
	$data['is_guest_checkout']='';
}
?>
<?php if ( getOptionA('captcha_order')==2):?>             
   <div class="recaptcha_v3"><?php GoogleCaptchaV3::init();?></div>      
<?php endif;?>     

<div class="sections section-grey2 section-confirmorder">
   <div class="container">
     <div class="row">
        <div class="col-md-7">
          <div class="box-grey rounded">
                     
           <?php if ($data['is_guest_checkout']==2):?>
           <?php FunctionsV3::sectionHeader("Customer Information")?>
           <table class="table-order-details">
            <tr>
              <td class="a"><?php echo t("Name")?></td>
              <td class="b">: <?php echo $data['first_name']." ".$data['last_name']?></td>
            </tr>
           </table>
           <?php endif;?>          
          
           <?php FunctionsV3::sectionHeader($header_1)?>
           <table class="table-order-details">
            <tr>
              <td class="a"><?php echo t("Merchant Name")?></td>
              <td class="b">: <?php echo clearString($merchant_info['restaurant_name'])?></td>
            </tr>
            
            <?php if (isset($s['kr_delivery_options']['delivery_date'])):?>
            <?php if (!empty($s['kr_delivery_options']['delivery_date'])):?>
            <tr>
              <td class="a"><?php echo t($label_1)?></td>
              <td class="b">: <?php echo FunctionsV3::prettyDate($s['kr_delivery_options']['delivery_date'])?></td>
            </tr>
            <?php endif;?>
            <?php endif;?>
            
            <?php if (isset($s['kr_delivery_options']['delivery_time'])):?>
            <?php if (!empty($s['kr_delivery_options']['delivery_time'])):?>
            <tr>
              <td class="a"><?php echo t($label_2)?></td>
              <td class="b">: <?php echo FunctionsV3::prettyTime($s['kr_delivery_options']['delivery_time'])?></td>
            </tr>
            <?php endif;?>
            <?php endif;?>
            
            <?php if($transaction_type=="delivery"):?>
            <?php if (isset($data['delivery_instruction'])):?>
            <?php if (!empty($data['delivery_instruction'])):?>
            <tr>
              <td class="a"><?php echo t("Delivery instructions")?></td>
              <td class="b">: <?php echo $data['delivery_instruction']?></td>
            </tr>
            <?php endif;?>
            <?php endif;?>
            
            <?php if(isset($s['kr_delivery_options']['opt_contact_delivery'])):?>
             <?php if($s['kr_delivery_options']['opt_contact_delivery']==1):?>
             <tr>
              <td class="a"><?php echo t("Delivery options")?></td>
              <td class="b">: <?php echo t("Leave order at the door or gate")?></td>
             </tr>
             <?php endif;?>
            <?php endif;?>
            
            <?php endif;?>
                        
            <?php if($transaction_type=="dinein"):?>
               <tr>
               <td class="a"><?php echo t("Number of guest")?></td>
                <td class="b">: <?php echo $data['dinein_number_of_guest']?></td>
              </tr>
              <?php if(!empty($data['dinein_special_instruction'])):?>
              <tr>
               <td class="a"><?php echo t("Special instructions")?></td>
                <td class="b">: <?php echo $data['dinein_special_instruction']?></td>
              </tr>
              <?php endif;?>
            <?php endif;?>
            
            <?php if (isset($s['kr_delivery_options']['delivery_asap'])):?>
            <?php if (!empty($s['kr_delivery_options']['delivery_asap'])):?>
            <tr>
              <td class="a"><?php echo t("Delivery Time")?></td>
              <td class="b">: <?php echo t("ASAP")?></td>
            </tr>
            <?php endif;?>
            <?php endif;?>
            
           </table>
           
           <?php FunctionsV3::sectionHeader($header_2)?>
           <p class="spacer3"><?php echo $address;?></p>
           
           <?php FunctionsV3::sectionHeader('Payment Information')?>
                      
           <p>
           <?php 
           if (array_key_exists($data['payment_opt'],$paymentlist)){
           	   switch ($data['payment_opt']) {
           	   	case "cod":
           	   		if ($data['delivery_type']=="pickup"){
           	   			echo t("Cash On Pickup");
           	   		} elseif ( $data['delivery_type']=="dinein" ) {
         		           echo t("Pay in person");   	
           	   		} else echo t($paymentlist[$data['payment_opt']]);
           	   		break;
           	   		
           	   		case "pyr":
           	   			if ($data['delivery_type']=="pickup"){
           	   			   echo t("Pay On Pickup");
           	   		    } else echo t($paymentlist[$data['payment_opt']]);
           	   		break;
           	   
           	   	default:
           	   		echo t($paymentlist[$data['payment_opt']]);
           	   		break;
           	   }
           } else echo t($data['payment_opt']);
           
           switch ($data['payment_opt']) {
           	case "cod":
           		 if(!isset($data['order_change'])){
           		 	$data['order_change']=0;
           		 }
           		 if ($data['order_change']>0){
	           		 echo '<p class="text-muted text-small">'.t("change for").
	           		 " ". FunctionsV3::prettyPrice($data['order_change']) .'</p>';
           		 }
           		 break;
           	case "ocr":
           		if ( $card_info=Yii::app()->functions->getCreditCardInfo($data['cc_id'])){
           			echo "<p class=\"text-muted text-small\">".$card_info['card_name']."</p>";
           			echo "<p class=\"text-muted text-small\">".
           			Yii::app()->functions->maskCardnumber($card_info['credit_card_number'])."</p>";
           		}
           		break;
           
           	default:
           		break;
           }
           ?>
           </p>
           
          </div><!-- box-grey-->
                    
          <a href="<?php echo $guestcheckout==true?Yii::app()->createUrl('/store/guestcheckout'):Yii::app()->createUrl('/store/paymentoption')?>">
           <i class="ion-ios-arrow-thin-left"></i> <?php echo t("Back")?>
          </a>
          
        </div> <!--col-->
        
        <div class="col-md-5 sticky-div">
        
          <div class="box-grey rounded  relative top-line-green">
             <i class="order-icon your-order-icon"></i>
             
             <div class="order-list-wrap">   
	       
	           <p class="bold center"><?php echo t("Your Order")?></p>
	           <div class="item-order-wrap"></div>
	         
	           <div class="text-center top25">
	             <a href="javascript:;" class="place_order green-button medium inline block">
	             <?php echo t("Confirm Order")?>
	             </a>
	           </div>
	           
	         </div> <!--order-list-wrap-->
             
          </div> <!--box-grey sticky-div--> 
        
        </div> <!--col-->
        
     </div> <!--row-->
   </div> <!--container-->
</div><!-- sections-->   
</form>