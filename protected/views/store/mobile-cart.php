<?php 

$this->renderPartial('/front/mobile_header',array(
    'slug'=> isset($data['restaurant_slug'])?$data['restaurant_slug']:'',
    'title'=>t("Cart")
));

$min_fees=FunctionsV3::getMinOrderByTableRates($merchant_id,
   $distance,
   $distance_type_raw,
   $data['minimum_order']
);

echo CHtml::hiddenField('merchant_id',$merchant_id);
echo CHtml::hiddenField('currentController','store');

$now=date('Y-m-d');
$now_time='';
$checkout=FunctionsV3::isMerchantcanCheckout($merchant_id); 


echo CHtml::hiddenField('is_merchant_open',isset($checkout['code'])?$checkout['code']:'' );

/*hidden TEXT*/
echo CHtml::hiddenField('restaurant_slug',$data['restaurant_slug']);
echo CHtml::hiddenField('merchant_id',$merchant_id);
echo CHtml::hiddenField('is_client_login',Yii::app()->functions->isClientLogin());

echo CHtml::hiddenField('website_disbaled_auto_cart',
Yii::app()->functions->getOptionAdmin('website_disbaled_auto_cart'));

$hide_foodprice=Yii::app()->functions->getOptionAdmin('website_hide_foodprice');
echo CHtml::hiddenField('hide_foodprice',$hide_foodprice);

echo CHtml::hiddenField('accept_booking_sameday',getOption($merchant_id
,'accept_booking_sameday'));

echo CHtml::hiddenField('customer_ask_address',getOptionA('customer_ask_address'));

echo CHtml::hiddenField('merchant_required_delivery_time',
  Yii::app()->functions->getOption("merchant_required_delivery_time",$merchant_id));   
  
/** add minimum order for pickup status*/
$merchant_minimum_order_pickup=Yii::app()->functions->getOption('merchant_minimum_order_pickup',$merchant_id);
if (!empty($merchant_minimum_order_pickup)){
	  echo CHtml::hiddenField('merchant_minimum_order_pickup',$merchant_minimum_order_pickup);
	  
	  echo CHtml::hiddenField('merchant_minimum_order_pickup_pretty',
         displayPrice(baseCurrency(),prettyFormat($merchant_minimum_order_pickup)));
}
 
$merchant_maximum_order_pickup=Yii::app()->functions->getOption('merchant_maximum_order_pickup',$merchant_id);
if (!empty($merchant_maximum_order_pickup)){
	  echo CHtml::hiddenField('merchant_maximum_order_pickup',$merchant_maximum_order_pickup);
	  
	  echo CHtml::hiddenField('merchant_maximum_order_pickup_pretty',
         displayPrice(baseCurrency(),prettyFormat($merchant_maximum_order_pickup)));
}  

/*add minimum and max for delivery*/
//$minimum_order=Yii::app()->functions->getOption('merchant_minimum_order',$merchant_id);
$minimum_order=$min_fees;
if (!empty($minimum_order)){
	echo CHtml::hiddenField('minimum_order',unPrettyPrice($minimum_order));
	echo CHtml::hiddenField('minimum_order_pretty',
	 displayPrice(baseCurrency(),prettyFormat($minimum_order))
	);
}
$merchant_maximum_order=Yii::app()->functions->getOption("merchant_maximum_order",$merchant_id);
 if (is_numeric($merchant_maximum_order)){
 	echo CHtml::hiddenField('merchant_maximum_order',unPrettyPrice($merchant_maximum_order));
    echo CHtml::hiddenField('merchant_maximum_order_pretty',baseCurrency().prettyFormat($merchant_maximum_order));
 }

$is_ok_delivered=1;
if (is_numeric($merchant_delivery_distance)){
	if ( $distance>$merchant_delivery_distance){
		$is_ok_delivered=2;
		/*check if distance type is feet and meters*/
		//if($distance_type=="ft" || $distance_type=="mm" || $distance_type=="mt"){
		if($distance_type=="ft" || $distance_type=="mm" || $distance_type=="mt" || $distance_type=="meter"){
			$is_ok_delivered=1;
		}
	}
} 

echo CHtml::hiddenField('is_ok_delivered',$is_ok_delivered);
echo CHtml::hiddenField('merchant_delivery_miles',$merchant_delivery_distance);
echo CHtml::hiddenField('unit_distance',$distance_type);
echo CHtml::hiddenField('from_address', FunctionsV3::getSessionAddress() );

echo CHtml::hiddenField('merchant_close_store',getOption($merchant_id,'merchant_close_store'));

echo CHtml::hiddenField('merchant_close_msg',
isset($checkout['msg'])?$checkout['msg']:t("Sorry merchant is closed."));

echo CHtml::hiddenField('disabled_website_ordering',getOptionA('disabled_website_ordering'));
echo CHtml::hiddenField('web_session_id',session_id());

echo CHtml::hiddenField('merchant_map_latitude',$data['latitude']);
echo CHtml::hiddenField('merchant_map_longtitude',$data['lontitude']);
echo CHtml::hiddenField('restaurant_name',$data['restaurant_name']);


echo CHtml::hiddenField('current_page','menu');

/*add meta tag for image*/
Yii::app()->clientScript->registerMetaTag(
Yii::app()->getBaseUrl(true).FunctionsV3::getMerchantLogo($merchant_id)
,'og:image');

$remove_delivery_info=false;
if($data['service']==3 || $data['service']==6 || $data['service']==7 ){	
	$remove_delivery_info=true;
}
?>
<div class="container">

<div style="padding:10px;padding-bottom:30px;">
  <p class="bold center"><?php echo t("Your Order")?></p>
  <div class="item-order-wrap"></div>
  
  
         <!--CONTACT LESS DELIVERY-->
       <?php if($merchant_opt_contact_delivery==1):
        $opt_contact_delivery = 0;
        if(isset($_SESSION['kr_delivery_options']['opt_contact_delivery'])){
        	$opt_contact_delivery = (integer)$_SESSION['kr_delivery_options']['opt_contact_delivery'];
        }     
        ?>
        <div class="inner line-top relative center opt_contact_delivery_wrap">
         <div class="box_green">           
           <div class="row">
             <div class="col-md-2 col-sm-2">
                <?php echo CHtml::checkBox('opt_contact_delivery',$opt_contact_delivery==1?true:false,array(
                 'class'=>"icheck",                 
                ))?>
             </div>
             <div class="col-md-10 col-sm-10 text_left">
              <p class="bold"><?php echo t("Opt in for no contact delivery")?></p>
              <?php echo t("Our delivery executive will leave the order at your door/gate (not applicable for offline payment like COD)")?>
             </div>
           </div>
         </div> <!--box_green-->   
        </div>
        <?php endif;?>
        <!--CONTACT LESS DELIVERY-->
  
        <!--DELIVERY OPTIONS-->
        <div class="inner line-top relative delivery-option center" style="padding-top:15px;">
           <i class="order-icon delivery-option-icon"></i>           
           
           <?php if ($remove_delivery_info==false):?>
             <p class="bold"><?php echo t("Delivery Options")?></p>
           <?php else :?>
             <p class="bold"><?php echo t("Options")?></p>
           <?php endif;?>
           
           <?php echo CHtml::dropDownList('delivery_type',$now,
           (array)Yii::app()->functions->DeliveryOptions($merchant_id),array(
             'class'=>'grey-fields'
           ))?>
           
           <?php            
           if($website_use_date_picker==2){
           	  echo CHtml::dropDownList('delivery_date','',
            	(array)FunctionsV3::getDateList($merchant_id)
            	,array(
            	  'class'=>'grey-fields date_list'
            	));           	
           } else {
	           echo CHtml::hiddenField('delivery_date',$now);
	           echo CHtml::textField('delivery_date1',
	            FormatDateTime($now,false),array('class'=>"j_date grey-fields",'data-id'=>'delivery_date'));
           }
            ?>
           
           <div class="delivery_asap_wrap">                       
             <?php                           
             echo CHtml::dropDownList('delivery_time',$now_time,
             (array)FunctionsV3::getTimeList($merchant_id,$now)
             ,array(
              'class'=>"grey-fields time_list"
             ))
             ?>            
	          <?php if ( $checkout['is_pre_order']==2):?>         
	          <span class="delivery-asap">
	           <?php echo CHtml::checkBox('delivery_asap',false,array('class'=>"icheck"))?>
	            <span class="text-muted"><?php echo Yii::t("default","Delivery ASAP?")?></span>	          
	         </span>       	         	        	     
	         <?php endif;?>    
	         
           </div><!-- delivery_asap_wrap-->
           
           <?php if ( $checkout['code']==1):?>
              <a href="javascript:;" class="orange-button medium checkout"><?php echo $checkout['button']?></a>
           <?php else :?>
              <?php if ( $checkout['holiday']==1):?>
                 <?php echo CHtml::hiddenField('is_holiday',$checkout['msg'],array('class'=>'is_holiday'));?>
                 <p class="text-danger"><?php echo $checkout['msg']?></p>
              <?php else :?>
                 <p class="text-danger"><?php echo $checkout['msg']?></p>
                 <p class="small">
                 <?php echo Yii::app()->functions->translateDate(date('F d l')."@".timeFormat(date('c'),true));?></p>
              <?php endif;?>
           <?php endif;?>
                                                                
        </div> <!--inner-->
        <!--END DELIVERY OPTIONS-->
  
</div> <!--padding-->

</div> <!--mobile-cart-->