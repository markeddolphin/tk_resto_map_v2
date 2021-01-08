<?php
/*POINTS PROGRAM*/
if (FunctionsV3::hasModuleAddon("pointsprogram")){
	unset($_SESSION['pts_redeem_amt']);
	unset($_SESSION['pts_redeem_points']);
}

$merchant_photo_bg=getOption($merchant_id,'merchant_photo_bg');
if ( !file_exists(FunctionsV3::uploadPath()."/$merchant_photo_bg")){
	$merchant_photo_bg='';
} 

$merchant_info=array(   
  'merchant_id'=>$merchant_id ,  
  'minimum_order'=>$min_fees,
  'ratings'=>$ratings,
  'merchant_address'=>$data['merchant_address'],
  'cuisine'=>$data['cuisine'],
  'restaurant_name'=>$data['restaurant_name'],
  'background'=>$merchant_photo_bg,
  'merchant_website'=>$merchant_website,
  'merchant_logo'=>FunctionsV3::getMerchantLogo($merchant_id),
  'contact_phone'=>$data['contact_phone'],
  'restaurant_phone'=>$data['restaurant_phone'],
  'social_facebook_page'=>$social_facebook_page,
  'social_twitter_page'=>$social_twitter_page,
  'social_google_page'=>$social_google_page,
);
$this->renderPartial('/front/menu-header',$merchant_info);

/*ADD MERCHANT INFO AS JSON */
$cs = Yii::app()->getClientScript();
$cs->registerScript(
  'merchant_information',
  "var merchant_information =".json_encode($merchant_info)."",
  CClientScript::POS_HEAD
);		

/*PROGRESS ORDER BAR*/
$this->renderPartial('/front/order-progress-bar',array(
   'step'=>3,
   'show_bar'=>true
));

$now=date('Y-m-d');
$now_time='';

$todays_day = date("l");

$checkout=FunctionsV3::isMerchantcanCheckout($merchant_id); 
$menu=Yii::app()->functions->getMerchantMenu($merchant_id , isset($_GET['sname'])?$_GET['sname']:'' , $todays_day ); 


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
if($merchant_delivery_distance>0){
	if ( $distance>$merchant_delivery_distance){
		$is_ok_delivered=2;				
	}
} 

echo CHtml::hiddenField('is_ok_delivered',$is_ok_delivered);
echo CHtml::hiddenField('merchant_delivery_miles',$merchant_delivery_distance);
echo CHtml::hiddenField('unit_distance',$unit_pretty);
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

if ($search_by_location){
	echo CHtml::hiddenField('search_by_location',$search_by_location);
}

echo CHtml::hiddenField('minimum_order_dinein',FunctionsV3::prettyPrice($minimum_order_dinein));
echo CHtml::hiddenField('maximum_order_dinein',FunctionsV3::prettyPrice($maximum_order_dinein));

/*add meta tag for image*/
Yii::app()->clientScript->registerMetaTag(
Yii::app()->getBaseUrl(true).FunctionsV3::getMerchantLogo($merchant_id)
,'og:image');

$remove_delivery_info=false;
if($data['service']==3 || $data['service']==6 || $data['service']==7 ){	
	$remove_delivery_info=true;
}

/*CHECK IF MERCHANT SET TO PREVIEW*/
$is_preview=false;
if ($food_viewing_private==2){		
	if (isset($_GET['preview'])){
		if($_GET['preview']=='true'){
			if(!isset($_GET['token'])){
				$_GET['token']='';
			}
			if (md5($data['password'])==$_GET['token']){
			   $is_preview=true;
			}
		}
	}
	if($is_preview==false){
		$menu='';
		$enabled_food_search_menu='';
	}
}
?>

<div class="sections section-menu section-grey2">
<div class="container">
  <div class="row">

     <div class="col-md-8 border menu-left-content">
         
        <div class="tabs-wrapper" id="menu-tab-wrapper">
	    <ul id="tabs">
		    <li class="active">
		       <span><?php echo t("Menu")?></span>
		       <i class="ion-fork"></i>
		    </li>
		    
		    <?php if ($theme_hours_tab==""):?>
		    <li>
		       <span><?php echo t("Opening Hours")?></span>
		       <i class="ion-clock"></i>
		    </li>
		    <?php endif;?>
		    
		    <?php if ($theme_reviews_tab==""):?>
		    <li class="view-reviews">
		       <span><?php echo t("Reviews")?></span>
		       <i class="ion-ios-star-half"></i>
		       <div class="badge badge_review badge_review_count"></div>
		    </li>
		    <?php endif;?>
		    
		    <?php if ($theme_map_tab==""):?>
		    <li class="view-merchant-map">
		       <span><?php echo t("Map")?></span>
		       <i class="ion-ios-navigate-outline"></i>
		    </li>
		    <?php endif;?>
		    
		    <?php if ($booking_enabled):?>
		      <li>
		      <span><?php echo t("Book a Table")?></span>
		      <i class="ion-coffee"></i>
		      </li>
		    <?php endif;?>
		    
		    <?php if ($photo_enabled):?>
		      <li class="view-merchant-photos">
		       <span><?php echo t("Photos")?></span>
		       <i class="ion-images"></i>
		      </li>
		    <?php endif;?>
		    
		    <?php if ($theme_info_tab==""):?>
		    <li>
		      <span><?php echo t("Information")?></span>
		      <i class="ion-ios-information-outline"></i>
		    </li>
		    <?php endif;?>
		    
		    <?php if ( $promo['enabled']==2 && $theme_promo_tab==""):?>
		      <li>
		       <span><?php echo t("Promos")?></span>
		       <i class="ion-pizza"></i>
		      </li>
		    <?php endif;?>
		</ul>
		
		<ul id="tab">
		
		<!--MENU-->
	    <li class="active" id="menu_left_content" >
	        <div class="row">
			 <div class="col-md-4 col-xs-4 border category-list">
				<div class="theiaStickySidebar">
				 <?php 
				 $this->renderPartial('/front/menu-category',array(
				  'merchant_id'=>$merchant_id,
				  'menu'=>$menu,
				  'show_image_category'=>getOption($merchant_id, 'merchant_show_category_image')
				 ));
				 ?>
				</div>
			 </div> <!--col-->
			 <div class="col-md-8 col-xs-8 border" id="menu-list-wrapper">
			 
			 <?php if($enabled_food_search_menu==1):?>
			 <form method="GET" class="frm-search-food">			   
			 
			 <?php 
			 if($is_preview==true){
				 if(isset($_GET['preview'])){
				 	echo CHtml::hiddenField('preview','true');
				 }
				 if(isset($_GET['token'])){
				 	echo CHtml::hiddenField('token',$_GET['token']);
				 }
			 }
			 ?>
			 
			 <div class="search-food-wrap">			
			 			   
			  <div class="typeahead__container">
              <div class="typeahead__field">
              <div class="typeahead__query">   
			   <?php echo CHtml::textField('sname',
			   isset($_GET['sname'])?$_GET['sname']:''
			   ,array(
			     'placeholder'=>t("Search"),
			     'class'=>"form-control search_foodname required"
			   ))?>
			   </div>
			   </div>
			   </div>
			   			   
			 </div>
			 <?php if (isset($_GET['sname'])):?> 
			     <a href="<?php echo Yii::app()->createUrl('store/menu-'.$data['restaurant_slug'])?>">
			     [<?php echo t("Clear")?>]
			     </a>
			     <div class="clear"></div>
			   <?php endif;?>
			 </form>
			 <?php endif;?>
			 
			 <?php 
			 $admin_activated_menu=getOptionA('admin_activated_menu');			 
			 $admin_menu_allowed_merchant=getOptionA('admin_menu_allowed_merchant');
			 if ($admin_menu_allowed_merchant==2){			 	 
			 	 $temp_activated_menu=getOption($merchant_id,'merchant_activated_menu');			 	 
			 	 if(!empty($temp_activated_menu)){
			 	 	 $admin_activated_menu=$temp_activated_menu;
			 	 }
			 }			 
			 
			 $merchant_tax=getOption($merchant_id,'merchant_tax');
			 if($merchant_tax>0){
			    $merchant_tax=$merchant_tax/100;
			 }
						 
			 switch ($admin_activated_menu)
			 {
			 	case 1:
			 		$this->renderPartial('/front/menu-merchant-2',array(
					  'merchant_id'=>$merchant_id,
					  'menu'=>$menu,
					  'disabled_addcart'=>$disabled_addcart
					));
			 		break;
			 		
			 	case 2:			 		
			 		$this->renderPartial('/front/menu-merchant-3',array(
					  'merchant_id'=>$merchant_id,
					  'menu'=>$menu,
					  'disabled_addcart'=>$disabled_addcart
					));
			 		break;
			 			
			 	default:	
				 	$this->renderPartial('/front/menu-merchant-1',array(
					  'merchant_id'=>$merchant_id,
					  'menu'=>$menu,
					  'disabled_addcart'=>$disabled_addcart,
					  'tc'=>$tc,
					  'merchant_apply_tax'=>getOption($merchant_id,'merchant_apply_tax'),
					  'merchant_tax'=>$merchant_tax>0?$merchant_tax:0,
					));
			    break;
			 }			 
			 ?>			
			 </div> <!--col-->
			</div> <!--row-->
	    </li>
	    <!--END MENU-->
	    
	    
	    <!--OPENING HOURS-->
	    <?php if ($theme_hours_tab==""):?>
	    <li>	       	     
	    <?php
	    $this->renderPartial('/front/merchant-hours',array(
	      'merchant_id'=>$merchant_id
	    )); ?>           
	    </li>
	    <?php endif;?>
	    <!--END OPENING HOURS-->
	    
	    <!--MERCHANT REVIEW-->
	    <?php if ($theme_reviews_tab==""):?>
	    <li class="review-tab-content">	       	     
	    <?php $this->renderPartial('/front/merchant-review',array(
	      'merchant_id'=>$merchant_id,
	      'website_review_type'=>$website_review_type
	    )); ?>           
	    </li>
	    <?php endif;?>
	    <!--END MERCHANT REVIEW-->
	    
	    <!--MERCHANT MAP-->
	    <?php if ($theme_map_tab==""):?>
	    <li>	        	
	    <?php $this->renderPartial('/front/merchant-map'); ?>        
	    </li>
	    <?php endif;?>
	    <!--END MERCHANT MAP-->
	    
	    <!--BOOK A TABLE-->
	    <?php if ($booking_enabled):?>
	    <li>
	    <?php $this->renderPartial('/front/merchant-book-table',array(
	      'merchant_id'=>$merchant_id
	    )); ?>        
	    </li>
	    <?php endif;?>
	    <!--END BOOK A TABLE-->
	    
	    <!--PHOTOS-->
	    <?php if ($photo_enabled):?>
	    <li>
	    <?php 
	    $gallery=Yii::app()->functions->getOption("merchant_gallery",$merchant_id);
        $gallery=!empty($gallery)?json_decode($gallery):false;
	    $this->renderPartial('/front/merchant-photos',array(
	      'merchant_id'=>$merchant_id,
	      'gallery'=>$gallery
	    )); ?>        
	    </li>
	    <?php endif;?>
	    <!--END PHOTOS-->
	    
	    <!--INFORMATION-->
	    <?php if ($theme_info_tab==""):?>
	    <li>
	        <div class="box-grey rounded " style="margin-top:0;">
	          <?php echo getOption($merchant_id,'merchant_information')?>	          
	        </div>
	    </li>
	    <?php endif;?>
	    <!--END INFORMATION-->
	    
	    <!--PROMOS-->
	    <?php if ( $promo['enabled']==2 && $theme_promo_tab==""):?>
	    <li>
	    <?php	    
	     $this->renderPartial('/front/merchant-promo',array(
	      'merchant_id'=>$merchant_id,
	      'promo'=>$promo
	    )); ?>        
	    </li>
	    <?php endif;?>
	    <!--END PROMOS-->
	    
	    
	   </ul>
	   </div>
     
     </div> <!-- menu-left-content-->
     
     <?php if (getOptionA('disabled_website_ordering')!="yes"):?>
     <div id="menu-right-content" class="col-md-4 border menu-right-content <?php echo $disabled_addcart=="yes"?"hide":''?>" >
     
     <div class="theiaStickySidebar">
      <div class="box-grey rounded  relative">
              
        <!--DELIVERY INFO-->
        <?php if ($remove_delivery_info==false):?>
        <div class="star-float"></div>
        <div class="inner center">
         <button type="button" class="close modal-close-btn" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button> 
                   
            <?php if ($data['service']==3):?>
            <p class="bold"><?php echo t("Distance Information")?></p>
            <?php else :?>
	        <p class="bold"><?php echo t("Delivery Information")?></p>
	        <?php endif;?>
	        
	        <p>
	        <?php 
	        if(!$search_by_location){		    
	        	if(!empty($distance_pretty)){
		           echo t("Distance")." $distance_pretty";
	        	} else echo  t("Distance").": ".t("not available");
	        }
	        ?>
	        </p>
	        
	        <p class="delivery-fee-wrap">
	        <?php echo t("Delivery Est")?>: <?php echo FunctionsV3::getDeliveryEstimation($merchant_id)?></p>
	        
	        <p class="delivery-fee-wrap">
	        <?php 
	        if(!$search_by_location){
		        if($merchant_delivery_distance>0){
		        	echo t("Delivery Distance Covered").": ".$merchant_delivery_distance." $unit_pretty";
		        } else echo  t("Delivery Distance Covered").": ".t("not available");
	        }
	        ?>
	        </p>
	        
	        <p class="delivery-fee-wrap">
	        <?php 
	        if ($delivery_fee>0){
	             echo t("Delivery Fee").": ".FunctionsV3::prettyPrice($delivery_fee);
	        } else echo  t("Delivery Fee").": ".t("Free Delivery");
	        ?>
	        </p>
	        
	        <?php if($min_fees>0):?>
	         <p>	    
	         <p><?php echo tt("Minimum Order : [fee]",array(
	          '[fee]'=>FunctionsV3::prettyPrice($min_fees)
	         ))?></p>    
	        </p>
	        <?php endif;?>
	        
	        	        
	        <?php if($search_by_location):?>
	        <a href="javascript:;" class="top10 green-color change-location block text-center">
	        [<?php echo t("Change Location here")?>]
	        </a>
	        <?php else:?>
	        <a href="javascript:;" class="top10 green-color change-address block text-center">
	        [<?php echo t("Change Your Address here")?>]
	        </a>
	        <?php endif;?>
	        
	        
        </div>
        <!--END DELIVERY INFO-->
        <?php else :?>
        
        <?php endif;?>
        
        <!--CART-->
        <div class="inner line-top relative">
        
           <i class="order-icon your-order-icon"></i>
           
           <p class="bold center"><?php echo t("Your Order")?></p>
           
           <div class="item-order-wrap"></div>
           
           <!--VOUCHER STARTS HERE-->
           <?php //Widgets::applyVoucher($merchant_id);?>
           <!--VOUCHER STARTS HERE-->
           
           <!--MAX AND MIN ORDR-->
           <?php if ($minimum_order>0):?>
           <div class="delivery-min">
              <p class="small center"><?php echo Yii::t("default","Subtotal must exceed")?> 
              <?php echo displayPrice(baseCurrency(),prettyFormat($minimum_order,$merchant_id))?>
           </div>
           <?php endif;?>
           
           <?php if ($merchant_minimum_order_pickup>0):?>
           <div class="pickup-min">
              <p class="small center"><?php echo Yii::t("default","Subtotal must exceed")?> 
              <?php echo displayPrice(baseCurrency(),prettyFormat($merchant_minimum_order_pickup,$merchant_id))?>
           </div>
           <?php endif;?>
                      
           <?php if($minimum_order_dinein>0):?>
           <div class="dinein-min">
              <p class="small center"><?php echo Yii::t("default","Subtotal must exceed")?> 
              <?php echo FunctionsV3::prettyPrice($minimum_order_dinein)?>
           </div>
           <?php endif;?>
              
	        <a href="javascript:;" class="clear-cart">[<?php echo t("Clear Order")?>]</a>
           
        </div> <!--inner-->
        <!--END CART-->
                
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
             <div class="col-md-2">
                <?php echo CHtml::checkBox('opt_contact_delivery',$opt_contact_delivery==1?true:false,array(
                 'class'=>"icheck",                 
                ))?>
             </div>
             <div class="col-md-10 text_left">
              <p class="bold"><?php echo t("Opt in for no contact delivery")?></p>
              <?php echo t("Our delivery executive will leave the order at your door/gate (not applicable for offline payment like COD)")?>
             </div>
           </div>
         </div> <!--box_green-->   
        </div>
        <?php endif;?>
        <!--CONTACT LESS DELIVERY-->
        
        <!--DELIVERY OPTIONS-->
        <div class="inner line-top relative delivery-option center">
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
            <?php $detect = new Mobile_Detect;?>           
            <?php if ( $detect->isMobile() ) :?>
             <?php                           
             echo CHtml::dropDownList('delivery_time',$now_time,
             (array)FunctionsV3::timeList()
             ,array(
              'class'=>"grey-fields"
             ))
             ?>
            <?php else :?>                       
	          <?php
	          $website_use_time_picker = getOptionA('website_use_time_picker');
	          $delivery_time_list = FunctionsV3::getTimeList($merchant_id,$now);	          
	          if($website_use_time_picker==3){
	          	  echo CHtml::dropDownList('delivery_time','', (array)$delivery_time_list ,array(
	          	    'class'=>'grey-fields time_list'
	          	  ));
	          } else {
	          	  $now_time=date("h:i A");
		          echo CHtml::textField('delivery_time',$now_time,
		          array('class'=>"timepick grey-fields",'placeholder'=>Yii::t("default","Delivery Time")));
	          }
	          ?>
	       <?php endif;?>  	          	  

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
        
      </div> <!-- box-grey-->
      </div> <!--end theiaStickySidebar-->
     
     </div> <!--menu-right-content--> 
     <?php endif;?>
  
  </div> <!--row-->
</div> <!--container-->
</div> <!--section-menu-->