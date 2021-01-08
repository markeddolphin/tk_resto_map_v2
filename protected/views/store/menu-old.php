<?php
/*POINTS PROGRAM*/
/*unset($_SESSION['pts_redeem_amt']);
unset($_SESSION['pts_redeem_points']);*/


unset($_SESSION['guest_client_id']);

$now=date('Y-m-d');
$now_time='';//date("h:i A");

$marker=Yii::app()->functions->getOptionAdmin('map_marker');
if (!empty($marker)){
   echo CHtml::hiddenField('map_marker',$marker);
}

echo CHtml::hiddenField('website_disbaled_auto_cart',
Yii::app()->functions->getOptionAdmin('website_disbaled_auto_cart'));

$hide_foodprice=Yii::app()->functions->getOptionAdmin('website_hide_foodprice');
echo CHtml::hiddenField('hide_foodprice',$hide_foodprice);

echo CHtml::hiddenField('accept_booking_sameday',getOption($re_info['merchant_id']
,'accept_booking_sameday'));

echo CHtml::hiddenField('customer_ask_address',getOptionA('customer_ask_address'));
?>

<?php $style_bg='';?>
<?php if (isset($merchant_header)):?>
  <?php if (!empty($merchant_header)):?>
  <div class="new-merchant-header"></div>
  <?php echo CHtml::hiddenField('merchant_header_new',$merchant_header)?>  
  <?php $style_bg='has-banner';?>
  <?php endif;?>
<?php endif;?>

<div class="new-menu-wrapper menu-wrapper page-right-sidebar <?php echo $style_bg;?>">
  <div class="main">    
  <div class="inner">
  
  <?php if (is_array($re_info) && count($re_info)>=1):?> 
  <?php 
  $cuisine_list=Yii::app()->functions->Cuisine(true);	    		    	
  $country_list=Yii::app()->functions->CountryList();
  
  $resto_cuisine='';  
  $merchant_id=$re_info['merchant_id'];  
  
  echo CHtml::hiddenField('merchant_required_delivery_time',
  Yii::app()->functions->getOption("merchant_required_delivery_time",$merchant_id));
  
  echo CHtml::hiddenField('merchant_id',$merchant_id);
  echo CHtml::hiddenField('is_client_login',Yii::app()->functions->isClientLogin());
  
  $_SESSION['kr_merchant_id']=$merchant_id;
  $_SESSION['kr_merchant_slug']=$data['merchant'];

  $merchant_photo=Yii::app()->functions->getOption("merchant_photo",$re_info['merchant_id']);  
  $cuisine=!empty($re_info['cuisine'])?(array)json_decode($re_info['cuisine']):false;  
  if($cuisine!=false){
	foreach ($cuisine as $valc) {	    						
		if ( array_key_exists($valc,(array)$cuisine_list)){
			$resto_cuisine.=$cuisine_list[$valc].", ";
		}				
	}
	$resto_cuisine=!empty($resto_cuisine)?substr($resto_cuisine,0,-2):'';
  }	    		     
  if (array_key_exists($re_info['country_code'],(array)$country_list)){  	
     $country_name=$country_list[$re_info['country_code']];
  } else $country_name=$re_info['country_code'];
  
  $minimum_order=Yii::app()->functions->getOption("merchant_minimum_order",$re_info['merchant_id']);
  $delivery_fee=Yii::app()->functions->getOption("merchant_delivery_charges",$re_info['merchant_id']);
  
  $merchant_map_latitude=Yii::app()->functions->getOption("merchant_latitude",$re_info['merchant_id']);
  $merchant_map_longtitude=Yii::app()->functions->getOption("merchant_longtitude",$re_info['merchant_id']);
      
  echo CHtml::hiddenField('merchant_map_latitude',$merchant_map_latitude);
  echo CHtml::hiddenField('merchant_map_longtitude',$merchant_map_longtitude);
  echo CHtml::hiddenField('map_title',isIsset($re_info['restaurant_name']));
  echo CHtml::hiddenField('web_session_id',session_id());
  
  $ratings=Yii::app()->functions->getRatings($re_info['merchant_id']);   
  $rating_meanings='';
  if ( $ratings['ratings'] >=1){
	$rating_meaning=Yii::app()->functions->getRatingsMeaning($ratings['ratings']);
	$rating_meanings=ucwords($rating_meaning['meaning']);
  }	    		  
  
  
  $initial_rating='';
  $client_id=Yii::app()->functions->getClientId();  
  if ( $your_ratings=Yii::app()->functions->isClientRatingExist($merchant_id,$client_id) ){  	  	
  	$initial_rating=$your_ratings['ratings'];
  }    
  echo CHtml::hiddenField('initial_rating',$initial_rating);
  
  $has_reviews=false;
  if ($reviews=Yii::app()->functions->getReviews($client_id,$merchant_id)){
  	  $has_reviews=true;
  }   
  
  $merchant_address=$re_info['street']." ".$re_info['city'] ." ". $re_info['post_code'];  
  $from_address=$_SESSION['kr_search_address'];     
  
  /*dump($from_address);
  dump($merchant_address);
  dump($re_info['country_code']);*/
  
  $miles=getDeliveryDistance2($from_address,$merchant_address,$re_info['country_code']);  
  //dump($miles);  
  $mt_delivery_miles=Yii::app()->functions->getOption("merchant_delivery_miles",$merchant_id);   
  
  $merchant_distance_type=Yii::app()->functions->getOption("merchant_distance_type",$merchant_id);
  //dump($merchant_distance_type);
        
  $use_distance=0;
  $unit_distance=Yii::t("default","miles");  
  $use_distance1='';
  $ft=false;
  $unit="miles";
  if (is_array($miles) && count($miles)>=1){  	   
  	  if ( $merchant_distance_type=="km"){
  	  	  $use_distance=str_replace(",","",$miles['km']);
  	  	  $use_distance1=$miles['km'];
  	  	  $unit_distance=Yii::t("default","km");
  	  	  $unit="km";
  	  } else {  	  	
  	  	  $use_distance=str_replace(",","",$miles['mi']); 
  	  	  $use_distance1=$miles['mi'];
  	  	  $unit_distance=Yii::t("default","miles");
  	  }     
  	  if (preg_match("/ft/i",$miles['mi'])) {
  	  	  $use_distance1=str_replace("ft",'',$miles['mi']);          
          $ft=true;
          $unit="ft";
  	  }
  } 
  
  //dump("=>".$use_distance1);
  
  $is_ok_delivered=1;
  if (is_numeric($mt_delivery_miles)){
	  if ( $mt_delivery_miles>=$use_distance){
	  	 $is_ok_delivered=1;
	  } else $is_ok_delivered=2;
	  if ($ft==TRUE){
	  	   $is_ok_delivered=1;
	  }
  }
  
  echo CHtml::hiddenField('is_ok_delivered',$is_ok_delivered);
  echo CHtml::hiddenField('merchant_delivery_miles',$mt_delivery_miles);
  echo CHtml::hiddenField('from_address',$from_address);
  echo CHtml::hiddenField('unit_distance',$unit_distance);
  
  //$mt_delivery_charges_type=Yii::app()->functions->getOption("merchant_delivery_charges_type",$re_info['merchant_id']);    
  $mt_delivery_estimation=Yii::app()->functions->getOption("merchant_delivery_estimation",$merchant_id);
  $merchant_extenal=Yii::app()->functions->getOption("merchant_extenal",$merchant_id);
  if  (!empty($merchant_extenal)){
  	   if (!preg_match("/http/i", $merchant_extenal)) {
  	   	   $merchant_extenal="http://".$merchant_extenal;
  	   }
  }  
  $is_merchant_open = Yii::app()->functions->isMerchantOpen($merchant_id);  
  $is_merchant_open1 = $is_merchant_open;
  $merchant_preorder= Yii::app()->functions->getOption("merchant_preorder",$merchant_id);  
  $disbabled_table_booking=Yii::app()->functions->getOption("merchant_table_booking",$merchant_id);  
  
  /*check if admin has disabled the booking module*/
  if (getOptionA('merchant_tbl_book_disabled')==2){
  	  $disbabled_table_booking=2;
  }
  
  $gallery_disabled=Yii::app()->functions->getOption("gallery_disabled",$merchant_id);  
  
  if ( $merchant_preorder==1){
  	  $is_merchant_open=true;
  }
  echo CHtml::hiddenField('is_merchant_open',$is_merchant_open==true?1:2);
  $close_msg=Yii::app()->functions->getOption("merchant_close_msg",$merchant_id);
  if (empty($close_msg)){
  	 $close_msg=Yii::t("default","This restaurant is closed now. Please check the opening times.");
  }
  echo CHtml::hiddenField('merchant_close_msg',ucwords($close_msg));
  
  $merchant_close_store=Yii::app()->functions->getOption('merchant_close_store',$merchant_id);
  echo CHtml::hiddenField('merchant_close_store',$merchant_close_store);
  
  /*check if admin has set default social shared text*/
  $default_share_text=getOptionA('default_share_text');
  if(empty($default_share_text)){
  	$default_share_text=t("Come and order at")." ".$re_info['restaurant_name'];
  } else {
  	$default_share_text=smarty('merchant-name',$re_info['restaurant_name'],$default_share_text);
  }
  
  
  Yii::app()->clientScript->registerMetaTag(Yii::app()->getBaseUrl(true)."/upload/$merchant_photo", 'og:image'); 
  Yii::app()->clientScript->registerMetaTag(Yii::app()->getBaseUrl(true).'/store/menu/merchant/'.$_GET['merchant'],'og:url');  
  Yii::app()->clientScript->registerMetaTag(Yii::t("default",$default_share_text),'og:title');     
  Yii::app()->clientScript->registerMetaTag($re_info['city'],'Description');  
      
  $shipping_enabled=Yii::app()->functions->getOption("shipping_enabled",$merchant_id);  
  
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
  ?>  
  <div class="restaurant-wrap">
    <div class="uk-grid">
    
      <div class="uk-width-1-4" style="width:110px;">
       <?php if ( !empty($merchant_photo)):?>
       <img src="<?php echo baseUrl()."/upload/$merchant_photo"?>" alt="" title="" class="uk-thumbnail uk-thumbnail-mini">
       <?php else :?>
       <?php //echo Yii::t("default","no image")?>
       <img src="<?php echo baseUrl()."/assets/images/thumbnail-medium.png"?>" alt="" title="" class="uk-thumbnail uk-thumbnail-mini">
       <?php endif;?>       
      </div> <!--END uk-width-1-4-->
      
      <div class="uk-width-1-3">
        <h5 style="position:relative;">
         <?php echo stripslashes($re_info['restaurant_name'])?>         
        </h5>
        <a href="javascript:;" class="change-address" ><i class="fa fa-map-marker"></i> <?php echo Yii::t("default","Change Your Address here")?></a>
        <p class="uk-text-muted"><?php echo $resto_address= $re_info['street']." ".$re_info['city']." ".$re_info['state'] ." ".$re_info['post_code']?></p>
        <p class="uk-text-bold"><?php echo $country_name?></p>
        <p class="uk-text-bold"><?php echo Yii::t("default","Cuisine")?> - <?php echo wordwrap($resto_cuisine,50,"<br />\n")?></p>
        <p><span class="uk-text-bold"><?php echo Yii::t("default","Distance")?>:</span> 
        <?php 
          //$unit=$unit_distance;
          if ($ft==TRUE){
          	 echo $use_distance1." ft";
          	 $unit="ft";
          } else echo $use_distance1." ".$unit_distance;          
         ?>
        </p>
        <p><span class="uk-text-bold"><?php echo Yii::t("default","Delivery Est")?>:</span> <?php echo $mt_delivery_estimation?></p>
        <?php if (is_numeric($mt_delivery_miles)):?>
        <p class="delivery-fee-wrap"><span class="uk-text-bold"><?php echo Yii::t("default","Delivery Distance Covered")?>:</span> <?php echo $mt_delivery_miles." ".$unit_distance?></p>
        <?php endif;?>
                
        
        <?php 
        //delivery rates table        
        $_SESSION['shipping_fee']='';
        if ( $shipping_enabled==2){
        	$FunctionsK=new FunctionsK();        	
        	$delivery_fee=$FunctionsK->getDeliveryChargesByDistance(
        	$merchant_id,
        	$use_distance1,
        	$unit,
        	$delivery_fee);        	
        	
        	/*dump($use_distance1);
        	dump($unit);
        	dump($delivery_fee);*/
        	$_SESSION['shipping_fee']=$delivery_fee;
        }                        
        ?>
        
        <?php if ($delivery_fee>=1):?>
        <p class="delivery-fee-wrap"><span class="uk-text-bold"><?php echo Yii::t("default","Delivery Fee")?>:</span> 
          <?php echo displayPrice(getCurrencyCode(),prettyFormat($delivery_fee))?>
        </p>        
        <?php else :?>
        <p class="delivery-fee-wrap"><span class="uk-text-bold"><?php echo Yii::t("default","Delivery Fee")?>:
        </span> <span class="uk-text-success"><?php echo t("Free Delivery")?></span></p>        
        <?php endif;?>
        
        <?php if ( !empty($merchant_extenal)):?>
        <p><span class="uk-text-bold"><?php echo Yii::t("default","Website")?>:</span> <span class="uk-text-success"><a href="<?php echo $merchant_extenal?>" target="_blank"><?php echo $merchant_extenal;?></a></span></p>        
        <?php endif;?>
        
        <?php echo Yii::app()->widgets->getOperationalHours($re_info['merchant_id'])?>
        
        <?php if (Yii::app()->functions->getOption("merchant_show_time",$merchant_id)=="yes"):?>
        <p>
        <span class="uk-text-bold"><?php echo Yii::t("default","Merchant Current Date/Time")?>:</span>         
        <?php //echo Yii::app()->functions->translateDate(date("F d l h:i:s a"));?>        
        <?php echo Yii::app()->functions->translateDate(date('F d l')."@".timeFormat(date('c'),true));?>
        </p>                
        <?php endif;?>
        
                
      </div> <!--END uk-width-1-4-->
      
      <div class="uk-width-1-2">
        
        <div class="uk-grid">
        <div class="uk-width-1-2">
         <p class="uk-text-bold"><?php echo Yii::t("default","Minimum")?></p>
         <p>
         <?php 
          if (is_numeric($minimum_order)):
              echo displayPrice(getCurrencyCode(),prettyFormat($minimum_order));
          endif;
         ?>
        </p>
        </div>
        <div class="uk-width-1-2" >
             <div class="rate-wrap">
		     <h6 class="rounded2" data-uk-tooltip="{pos:'bottom-right'}" title="<?php echo $rating_meanings?>">
		     <?php echo number_format($ratings['ratings'],1);?>
		    </h6>
		     <span class="votes_counter"><?php echo $ratings['votes']?> <?php echo Yii::t("default","Votes")?></span>
		    </div>
        </div>
       </div> <!--uk-grid-->
       
       <?php Widgets::shareWidget($re_info['restaurant_name']);?>
       <?php echo Widgets::offers($merchant_id);?>
       
      </div> <!--END uk-width-1-2-->
      
    </div>
  </div> <!--restaurant-wrap-->
  
  <div class="rating-wrapper">
    <div class="uk-grid">
      <div class="uk-width-1-2" style="width:220px;padding-top:10px;">
        <a href="javascript:;" class="write-review btn-flat-grey rounded2 <?php echo $has_reviews==true?"active":"";?> ">
         <?php echo Yii::t("default","write a review")?> <i class="fa fa-angle-right"></i>
        </a>
      </div>
      <div class="uk-width-1-2">
      <div class="bar-rating-wrap">
          <?php $class='hide';?>
          <?php if (is_numeric($initial_rating) && Yii::app()->functions->isClientLogin() ):?>          
          <?php $class='hide';?>
          <?php endif;?>
      
          <p><?php echo Yii::t("default","Your Rating")?>
          <span class="rating_handle <?php echo $class?>"> <a href="javascript:;" class="remove-rating"><?php echo Yii::t("default","remove my ratings")?></a> </span>          
          </p>
          
          <select id="bar-rating" name="rating">
                <option value=""></option>
                <option value="1.0" class="level1">1.0</option>
                <option value="1.5">1.5</option>
                <option value="2.0">2.0</option>
                <option value="2.5">2.5</option>
                <option value="3.0">3.0</option>
                <option value="3.5">3.5</option> 
                <option value="4.0">4.0</option> 
                <option value="4.5">4.5</option> 
                <option value="5.0">5.0</option> 	                
            </select>
      </div>      
      </div>      
    </div>
        
    <div class="review-content-wrap">
      <form class="forms" id="forms" onsubmit="return false;" style="position:relative;">
	  <?php echo CHtml::hiddenField('action','addReviews')?>
	  <?php echo CHtml::hiddenField('currentController','store')?>
	  <?php echo CHtml::hiddenField('merchant-id',$merchant_id)?>
	  <?php echo CHtml::hiddenField('initial_review_rating','')?>
	  <?php echo CHtml::textArea('review_content','',array(
	  'data-validation'=>"required"
	  ))?>
	  <input type="submit" class="uk-button uk-button-danger right" value="<?php echo Yii::t("default","PUBLISH REVIEW")?>">
	  <div class="clear"></div>
	  </form>
    </div> <!--review-content-wrap-->    
    
  </div><!-- END rating-wrapper-->
  
<div class="grid" id="menu-wrap">
     
  <div class="grid-1 left left-menu-content">
       
  <!--TAB STARTS HERE-->
  <ul data-uk-tab="{connect:'#tab-content'}" class="uk-tab uk-active">
	<li class="<?php echo !isset($_GET['tab'])?"uk-active":'';?>"  >
	   <a href="#"><?php echo Yii::t("default","Menu")?></a>
    </li>
	<li class="">
	  <a href="#"><?php echo Yii::t("default","Reviews")?></a>
	</li>
	<li class="">
	  <a href="#" class="map-li"><?php echo Yii::t("default","Map")?></a>
    </li>
	<?php if ( $disbabled_table_booking==""):?>
	<li class="<?php echo isset($_GET['tab'])?"uk-active":'';?>">
	  <a href="#"><?php echo Yii::t("default","Book a Table")?></a>
	</li>
	<?php endif;?>
	
	<?php if ( $gallery_disabled==""):?>
	<li class=""><a href="#"><?php echo Yii::t("default","Photos")?></a></li>
	<?php endif;?>
	
	<li><a href="#"><?php echo t("Information")?></a></li>
	
 </ul>
  <!--TAB ENDS HERE-->
  
  <?php 
  $menu=Yii::app()->functions->getMerchantMenu($merchant_id);        
  $merchant_activated_menu=Yii::app()->functions->getOptionAdmin("admin_activated_menu");  
  //dump($menu);
  ?>  
  <!--TAB CONTENT STARTS HERE-->
  
  <ul class="uk-switcher uk-margin" id="tab-content">
  
      <li class="uk-active">        
         <?php if (is_array($menu) && count($menu)>=1):?>    
         <div class="mytable separate-category-menu">
           <div class="col col-category">
           <div class="scroll-parent">           
              <ul class="side-bar-category scroll-child"> 
                <?php foreach ($menu as $val):?>
                <li>
	            <a href="javascript:;" data-id="cat-<?php echo $val['category_id']?>" class="goto-category">
	            <?php echo qTranslate($val['category_name'],'category_name',$val)?>
	            </a>
	            </li>
                <?php endforeach;?>
              </ul>
            </div> <!-- scroll-parent--> 
           </div> <!--end col-category-->
           
           <div class="col col-menu">
           
           <!--DISPLAY MENU STARTS HERE-->           
		    <?php if ( $merchant_activated_menu =="" || $merchant_activated_menu=="default"):?>    
		    <div class="menu">    
		    <?php foreach ($menu as $val):?>
		     <div class="inner">
		      <a class="menu-category cat-<?php echo $val['category_id']?>" href="javascript:;">
		        <?php echo qTranslate($val['category_name'],'category_name',$val)?>
		        <?php //echo Widgets::displayCatSpicyIconByID($val['category_id'])?>
		        <?php echo Widgets::displaySpicyIconNew($val['dish'],"dish-category")?>
		        <i class="fa fa-chevron-up"></i>
		      </a>
		      <?php if (is_array($val['item']) && count($val['item'])>=1):?>     
		      <ul class="menu-ul">
		      <?php $x=0;?>
		       <?php foreach ($val['item'] as $val_item): //dump($val_item);?>
		       
		       <?php 
			    $atts='';
			    if ( $val_item['single_item']==2){
			  	  $atts.='data-price="'.$val_item['single_details']['price'].'"';
			  	  $atts.=" ";
			  	  $atts.='data-size="'.$val_item['single_details']['size'].'"';
			    }
			   ?>       
		       <li class="<?php if ( $x%2){echo "even";} echo count($val['item'])==$x+1?"last":"";  ?>">
		         <a href="javascript:;" rel="<?php echo $val_item['item_id']?>" 
			     class="menu-item <?php echo $val_item['not_available']==2?"item_not_available":''?>"
		           data-single="<?php echo $val_item['single_item']?>" 
		           <?php echo $atts;?>
		         >
		          <div class="left">
		             <?php echo qTranslate($val_item['item_name'],'item_name',$val_item)?>
		             <?php //echo Widgets::displaySpicyIcon($val_item['spicydish'],'',$merchant_id);?>             
		             <?php echo Widgets::displaySpicyIconNew($val_item['dish']);?>             
		          </div>
		          <div class="right food-price-wrap">
		             <?php if (is_array($val_item['prices']) && count($val_item['prices'])>=1):?>
		              <?php if (!empty($val_item['discount'])):?>
		              <div class="normal-price left">
		               <?php 
		               echo displayPrice(getCurrencyCode(),prettyFormat($val_item['prices'][0]['price']))?>
		               </div>
		              <div class="sale-price left">
		              <?php 
		              echo displayPrice(getCurrencyCode(),prettyFormat($val_item['prices'][0]['price']-$val_item['discount']));
		              ?></div>
		              <?php else :?>
		              <?php echo displayPrice(getCurrencyCode(),prettyFormat($val_item['prices'][0]['price']))?>
		              <?php endif;?>
		             <?php endif;?>
		          </div>
		          <div class="clear"></div>
		         </a>
		       </li>
		       <?php $x++;?>
		       <?php endforeach;?>
		       <div class="clear"></div>
		      </ul>		      
		      <?php endif;?>
		      </div> <!--inner-->
		    <?php endforeach;?>     
		    </div> <!--menu-->
		    
		    <?php else :?>
		      <?php Widgets::displayMenu($menu,$merchant_activated_menu,$merchant_id)?>
		    <?php endif;?>		    		    
           <!--DISPLAY MENU ENDS HERE-->
                                 
             <a class="back-top-menu" href="javascript:;"><i class="fa fa-arrow-up"></i> <?php echo t("Top")?></a>
             
           </div> <!--end col-menu-->
           
         </div> <!--end separate-category-menu-->
         
         <?php else :?>
	     <p class="uk-text-muted"><?php echo Yii::t("default","This restaurant has not published their menu yet.")?></p>
	     <?php endif;?>         
      </li>
      
      <li>  
	   <div class="reviews-wrap"></div>       
      </li>
      
      <li>
        <div id="google_map_wrap"></div>
        <?php if ( !empty($resto_address)):?>
        <?php echo CHtml::hiddenField("resto_address",$resto_address." ".$country_name)?>
	      <div class="direction_wrap">         
	      
	        <div class="uk-grid uk-form">
	          <div class="uk-width-medium-1-2">
	           <?php echo CHtml::textField('origin','',array('uk-form-width-large'))?>
	          </div>          
	          <div class="uk-width-medium-1-2">   
	            <?php echo CHtml::dropDownList('travel_mode','',
	            Yii::app()->functions->travelMmode()
	             ,array('uk-width-1-1'))?>
	            <input type="button"class="get_direction_btn uk-button uk-button-primary" value="<?php echo Yii::t("default","Get directions")?>">
	          </div>
	        </div> <!--uk-grid-->
	        
	        <div class="direction_output" id="direction_output"></div>
	      
	      </div> <!--direction_wrap-->
        <?php endif;?>
      </li>
      
      <?php if ( $disbabled_table_booking==""):?>
      <li><?php Widgets::bookTable($merchant_id);?></li>
      <?php endif;?>
      
      <?php if ( $gallery_disabled==""):?>
      <li><?php Widgets::merchantGallery($merchant_id);?></li>
      <?php endif;?>
      
      <li>
      <?php echo Yii::app()->functions->getOption("merchant_information",$merchant_id)?>
      </li>
      
  </ul>
  <!--TAB CONTENT ENDS HERE-->
  
  
  </div>  <!--END GRID-1-->
  
  <?php   
  $disabled_website_ordering=Yii::app()->functions->getOptionAdmin('disabled_website_ordering');
  $merchant_disabled_ordering=Yii::app()->functions->getOption('merchant_disabled_ordering',$merchant_id);
  if ( $merchant_disabled_ordering=="yes"){
  	   $disabled_website_ordering="yes";
  }
  ?>  
   
  <?php if ($disabled_website_ordering==""):?>
  <div class="grid-2 left right-menu-content">
  
  <div class="scroll-parent2">  
    <div class="order-list-wrap scroll-child2">
      <h5><?php echo Yii::t("default","Your Order")?></h5>
      
      <a href="javascript:;" class="clear-cart"><?php echo Yii::t("default","Clear Order")?></a>
         
      <div class="item-order-wrap"></div> <!--END item-order-wrap-->           
      
      <!--VOUCHER STARTS HERE-->
      <?php Widgets::applyVoucher($merchant_id);?>
      <!--VOUCHER STARTS HERE-->
      
      <?php $minimum_order=Yii::app()->functions->getOption('merchant_minimum_order',$merchant_id);?>
      <?php if (!empty($minimum_order)):?>
      <?php 
            echo CHtml::hiddenField('minimum_order',unPrettyPrice($minimum_order));
            echo CHtml::hiddenField('minimum_order_pretty',
             displayPrice(baseCurrency(),prettyFormat($minimum_order))
            );
       ?>
      <p class="uk-text-muted"><?php echo Yii::t("default","Subtotal must exceed")?> 
         <?php echo displayPrice(baseCurrency(),prettyFormat($minimum_order,$merchant_id))?>
      </p>      
      <?php endif;?>
      
      <?php $merchant_maximum_order=Yii::app()->functions->getOption("merchant_maximum_order",$merchant_id);?>
      <?php if (is_numeric($merchant_maximum_order)):?>
      <?php 
      echo CHtml::hiddenField('merchant_maximum_order',unPrettyPrice($merchant_maximum_order));
      echo CHtml::hiddenField('merchant_maximum_order_pretty',baseCurrency().prettyFormat($merchant_maximum_order));
      ?>
      <p class="uk-text-muted"><?php echo Yii::t("default","Maximum Order is")?> 
         <?php echo displayPrice(baseCurrency(),prettyFormat($merchant_maximum_order,$merchant_id))?>
      </p>      
      <?php endif;?>
      
      <div class="delivery_options uk-form" style="margin-top:10px;">
       <h5><?php echo Yii::t("default","Delivery Options")?></h5>
       <?php echo CHtml::dropDownList('delivery_type',$now,(array)Yii::app()->functions->DeliveryOptions($merchant_id))?>
       
       <?php echo CHtml::hiddenField('delivery_date',$now)?>
       <?php echo CHtml::textField('delivery_date1',
       FormatDateTime($now,false),array('class'=>"j_date",'data-id'=>'delivery_date'))?>
       
       
       <div class="delivery_asap_wrap"> 
       <?php echo CHtml::textField('delivery_time',$now_time,
       array('class'=>"timepick",'placeholder'=>Yii::t("default","Delivery Time")))?>
       
       <span class="delivery-asap">
       <span class="uk-text-small uk-text-muted"><?php echo Yii::t("default","Delivery ASAP?")?></span>
       <?php echo CHtml::checkBox('delivery_asap',false,array('class'=>"icheck"))?>
       </span>
       
       </div><!-- delivery_asap_wrap-->
       
      </div>      
      
      <?php 
      $merchant_close_msg_holiday=Yii::app()->functions->getOption("merchant_close_msg_holiday",$merchant_id);
      $is_holiday=false;
      if ( $m_holiday=Yii::app()->functions->getMerchantHoliday($merchant_id)){      	         	  
      	   if (in_array($now,(array)$m_holiday)){
      	   	  $is_holiday=true;
      	   }
      }
      ?>
        
      <?php if ( $is_holiday ):?>
         <p class="uk-alert uk-alert-warning">
           <?php 
           if (!empty($merchant_close_msg_holiday)){
           	  echo $th=t($merchant_close_msg_holiday);
           } else echo $th=t("Sorry merchant is closed");
           echo CHtml::hiddenField('is_holiday',$th,array('class'=>'is_holiday'));
           ?>           
         </p>
      <?php else :?>
          
      <?php if (yii::app()->functions->validateSellLimit($merchant_id) ):?>
         <?php if ( $is_merchant_open1):?>         
         <a href="javascript:;" class="uk-button checkout"><?php echo Yii::t("default","Checkout")?></a>
         <?php else :?>
            <?php if ($merchant_preorder==1):?>
            <a href="javascript:;" class="uk-button checkout"><?php echo Yii::t("default","Pre-Order")?></a>
            <?php else :?>
            <p class="uk-alert uk-alert-warning"><?php echo Yii::t("default","Sorry merchant is closed.")?></p>
            <p><?php echo Yii::app()->functions->translateDate(date('F d l')."@".timeFormat(date('c'),true));?></p>
            <?php endif;?>
         <?php endif;?>
      <?php else :?>
      <?php $msg=Yii::t("default","This merchant is not currently accepting orders.");?>
      <p class="uk-text-danger"><?php echo $msg;?></p>      
      <?php endif;?>   
         
      <?php endif;?>   
      
      <div class="spacer"></div>      
      
   </div> <!--order-list-wrap-->
   </div>
  </div> <!--END GRID-2-->
  <?php endif;?>  
  
  <div class="clear"></div>
  
  </div> <!--END GRID menu-wrap-->
  
  <?php else :?>
  <p><?php echo Yii::t("default","Sorry but we cannot find what you are looking for.")?></p>
  <?php endif;?>
  
  </div>
  </div>
</div> <!--END  menu-wrapper--> 