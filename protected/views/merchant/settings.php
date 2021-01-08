<?php
$merchant_id=Yii::app()->functions->getMerchantID();
$merchant_currency=Yii::app()->functions->getOption("merchant_currency",$merchant_id);
$merchant_decimal=Yii::app()->functions->getOption("merchant_decimal",$merchant_id);
$merchant_use_separators=Yii::app()->functions->getOption("merchant_use_separators",$merchant_id);
$merchant_minimum_order=Yii::app()->functions->getOption("merchant_minimum_order",$merchant_id);
$merchant_tax=Yii::app()->functions->getOption("merchant_tax",$merchant_id);
$merchant_delivery_charges=Yii::app()->functions->getOption("merchant_delivery_charges",$merchant_id);
$stores_open_day=Yii::app()->functions->getOption("stores_open_day",$merchant_id);
$stores_open_starts=Yii::app()->functions->getOption("stores_open_starts",$merchant_id);
$stores_open_ends=Yii::app()->functions->getOption("stores_open_ends",$merchant_id);
$stores_open_custom_text=Yii::app()->functions->getOption("stores_open_custom_text",$merchant_id);

$stores_open_day=!empty($stores_open_day)?(array)json_decode($stores_open_day):false;
$stores_open_starts=!empty($stores_open_starts)?(array)json_decode($stores_open_starts):false;
$stores_open_ends=!empty($stores_open_ends)?(array)json_decode($stores_open_ends):false;
$stores_open_custom_text=!empty($stores_open_custom_text)?(array)json_decode($stores_open_custom_text):false;

$merchant_photo=Yii::app()->functions->getOption("merchant_photo",$merchant_id);
$merchant_delivery_estimation=Yii::app()->functions->getOption("merchant_delivery_estimation",$merchant_id);
$merchant_delivery_charges_type=Yii::app()->functions->getOption("merchant_delivery_charges_type",$merchant_id);

$merchant_photo_bg=Yii::app()->functions->getOption("merchant_photo_bg",$merchant_id);

$merchant_extenal=Yii::app()->functions->getOption("merchant_extenal",$merchant_id);
$merchant_maximum_order=Yii::app()->functions->getOption("merchant_maximum_order",$merchant_id);

$merchant_switch_master_cod=Yii::app()->functions->getOption("merchant_switch_master_cod",$merchant_id);
$merchant_switch_master_ccr=Yii::app()->functions->getOption("merchant_switch_master_ccr",$merchant_id);

$merchant_minimum_order_pickup=Yii::app()->functions->getOption("merchant_minimum_order_pickup",$merchant_id);
$merchant_maximum_order_pickup=Yii::app()->functions->getOption("merchant_maximum_order_pickup",$merchant_id);

$stores_open_pm_start=Yii::app()->functions->getOption("stores_open_pm_start",$merchant_id);
$stores_open_pm_start=!empty($stores_open_pm_start)?(array)json_decode($stores_open_pm_start):false;

$stores_open_pm_ends=Yii::app()->functions->getOption("stores_open_pm_ends",$merchant_id);
$stores_open_pm_ends=!empty($stores_open_pm_ends)?(array)json_decode($stores_open_pm_ends):false;

$FunctionsK=new FunctionsK();
$tips_list=$FunctionsK->tipsList(true);

$merchant_info=(array)Yii::app()->functions->getMerchantInfo();
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','merchantSettings')?>
<?php FunctionsV3::addCsrfToken(false);?>


<!--Merchant Logo-->
<div class="uk-form-row"> 
  <label class="uk-form-label"><?php echo t("Merchant Logo")?></label>
  <a href="javascript:;" id="sau_merchant_upload_file" 
   class="button uk-button" data-progress="sau_merchant_progress" data-preview="image_preview" data-field="photo">
    <?php echo t("Browse")?>
  </a>
</div>
<div class="sau_merchant_progress"></div>

<div class="image_preview">
 <?php 
 $image=$merchant_photo;
 $image_url = FunctionsV3::getImage($image); 
 if(!empty($image_url)){
 	echo '<img src="'.$image_url.'" class="uk-thumbnail" id="logo-small"  />';
 	echo CHtml::hiddenField('photo',$image);
 	echo '<br/>';
 	echo '<a href="javascript:;" class="sau_remove_file" data-preview="image_preview" >'.t("Remove image").'</a>';
 }
 ?>
</div>	

<div class="spacer"></div>

<!--END Merchant Logo-->

<!--HEADER BG-->

<div class="uk-form-row"> 
  <label class="uk-form-label"><?php echo t("Merchant Header/Background")?></label>
  <a href="javascript:;" id="single_uploadfile" 
   class="button uk-button" data-progress="single_uploadfile_progress" data-preview="single_uploadfile_preview" data-field="photo2">
    <?php echo t("Browse")?>
  </a>
</div>
<div class="single_uploadfile_progress"></div>

<div class="single_uploadfile_preview">
 <?php 
 $image=$merchant_photo_bg;
 $image_url = FunctionsV3::getImage($image); 
 if(!empty($image_url)){
 	echo '<img src="'.$image_url.'" class="uk-thumbnail" id="logo-small"  />';
 	echo CHtml::hiddenField('photo2',$image);
 	echo '<br/>';
 	echo '<a href="javascript:;" class="single_uploadfile_remove" data-preview="image_preview" >'.t("Remove image").'</a>';
 }
 ?>
</div>	

<div class="spacer"></div>

<!--END HEADER BG-->

<hr/>

<!--<h3><?php echo Yii::t("default","Store Currency")?></h3>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Currency Code")?></label>
  <?php echo CHtml::dropDownList('merchant_currency',
  empty($merchant_currency)?"USD":$merchant_currency
  ,Yii::app()->functions->currencyList()
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>-->

<!--<h3><?php echo Yii::t("default","Price Format")?></h3>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Decimal Places")?></label>
  <?php echo CHtml::dropDownList('merchant_decimal',empty($merchant_decimal)?2:$merchant_decimal,Yii::app()->functions->decimalPlacesList()
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use 1000 Separators(,)?")?></label>
  <?php 
  echo CHtml::checkBox('merchant_use_separators',
  $merchant_use_separators=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?>
</div>-->
  <!--
<h3><?php echo Yii::t("default","Menu Options")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Default Menu")?></label>
  <?php 
  echo CHtml::radioButton('merchant_activated_menu',
  Yii::app()->functions->getOption("merchant_activated_menu",$merchant_id)=="default"?true:false
  ,array(
    'value'=>"default",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Activate Menu 1")?></label>
  <?php 
  echo CHtml::radioButton('merchant_activated_menu',
  Yii::app()->functions->getOption("merchant_activated_menu",$merchant_id)=="1"?true:false
  ,array(
    'value'=>"1",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Activate Menu 2")?></label>
  <?php 
  echo CHtml::radioButton('merchant_activated_menu',
  Yii::app()->functions->getOption("merchant_activated_menu",$merchant_id)=="2"?true:false
  ,array(
    'value'=>"2",
    'class'=>"icheck"
  ))
  ?> 
</div>
-->  
  
<?php if ( getOptionA('mechant_sms_enabled')==""):?>
<h2><?php echo t("Order Options")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Order SMS Verification")?></label>  
  <?php 
  echo CHtml::checkBox('order_verification',
   Yii::app()->functions->getOption('order_verification',$merchant_id)==2?true:false
   ,array(
   'class'=>"icheck",
   'value'=>2
  ))
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Customer can request sms code every")?></label>  
  <?php echo CHtml::textField('order_sms_code_waiting',getOption($merchant_id,'order_sms_code_waiting'),array(
  'class'=>"numeric_only"
  ))?>
  <span>(<?php echo t("Minutes")?>) <?php echo t("default is 5 minutes")?></span>
  
</div>

<hr/>
<?php endif;?>

  
<h2><?php echo t("Food Item Options")?></h2>

<h5><?php echo t("If item is not available do the following actions")?></h5>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Hide")?></label>  
  <?php 
  echo CHtml::radioButton('food_option_not_available',
   Yii::app()->functions->getOption('food_option_not_available',$merchant_id)==1?true:false
   ,array(
   'class'=>"icheck",
   'value'=>1
  ))
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled")?></label>  
  <?php 
  echo CHtml::radioButton('food_option_not_available',
   Yii::app()->functions->getOption('food_option_not_available',$merchant_id)==2?true:false
   ,array(
   'class'=>"icheck",
   'value'=>2
  ))
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled food gallery")?></label>  
  <?php 
  echo CHtml::checkBox('disabled_food_gallery',
   Yii::app()->functions->getOption('disabled_food_gallery',$merchant_id)==2?true:false
   ,array(
   'class'=>"icheck",
   'value'=>2
  ))
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Make menu private")?></label>  
  <?php 
  echo CHtml::checkBox('food_viewing_private',
   Yii::app()->functions->getOption('food_viewing_private',$merchant_id)==2?true:false
   ,array(
   'class'=>"icheck",
   'value'=>2
  ))
  ?>
  <p class="uk-text-muted">
  <?php echo t("you can use this when you don't want to show yet your menu in front end")?>.
  <br/>
  <?php echo t("to preview the menu in front end")?> 
  <a target="_blank" 
  href="<?php echo Yii::app()->request->baseUrl."/menu-".$merchant_info[0]->restaurant_slug;?>?preview=true&token=<?php echo md5($merchant_info[0]->password)?>">
  <?php echo t("click here")?></a>
  </p>
</div>


<hr/>

<h2><?php echo t("Two Flavor Options")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Choose option")?></label>
  <?php 
  echo CHtml::dropDownList('merchant_two_flavor_option',
  getOption($merchant_id,'merchant_two_flavor_option'),array(
    1=>t("Highest price"),
    2=>t("Sumup and divided by 2")
  ));
  ?> 
</div>


<hr/>

<!--MENU OPTIONS SETTINGS FOR MERCHANT-->
<?php if (getOptionA('admin_menu_allowed_merchant')==2):?>
<h2><?php echo t("Menu Options")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Default Menu")?></label>
  <?php 
  echo CHtml::radioButton('merchant_activated_menu',
  Yii::app()->functions->getOption("merchant_activated_menu",$merchant_id)=="3"?true:false
  ,array(
    'value'=>3,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Activate Menu 1")?></label>
  <?php 
  echo CHtml::radioButton('merchant_activated_menu',
  Yii::app()->functions->getOption("merchant_activated_menu",$merchant_id)=="1"?true:false
  ,array(
    'value'=>"1",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Activate Menu 2")?></label>
  <?php 
  echo CHtml::radioButton('merchant_activated_menu',
  Yii::app()->functions->getOption("merchant_activated_menu",$merchant_id)=="2"?true:false
  ,array(
    'value'=>"2",
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Show Category Image")?></label>
  <?php 
  echo CHtml::checkBox('merchant_show_category_image',
  getOption($merchant_id,'merchant_show_category_image')==1?true:false,array(
    'value'=>1,
    'class'=>"icheck",
  ));
  ?> 
</div>

<hr/>
<?php endif;?>
<!--MENU OPTIONS SETTINGS FOR MERCHANT-->

<h2><?php echo Yii::t("default","Receipt Options")?></h2>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Tax number")?></label>
  <?php 
  echo CHtml::textField('merchant_tax_number',
  getOption($merchant_id,'merchant_tax_number')
  ,array(
    'class'=>"uk-width-1-4",
    'maxlength'=>30
  ))
  ?>  
</div>

<hr/>

<h2><?php echo Yii::t("default","Printing Options")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Receipt Width")?></label>
  <?php 
  echo CHtml::textField('printing_receipt_width',
  getOption($merchant_id,'printing_receipt_width')
  ,array(
    'class'=>"numeric_only"
  ))
  ?>  
  <span><?php echo t("px")?></span>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Font size")?></label>
  <?php 
  echo CHtml::textField('printing_receipt_size',
  getOption($merchant_id,'printing_receipt_size')
  ,array(
    'class'=>"numeric_only"
  ))
  ?>  
  <span><?php echo t("px")?></span>
</div>

<hr>

<h2><?php echo Yii::t("default","Free Delivery Options")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Free delivery above Sub Total Order")?></label>
  <?php
  echo CHtml::textField('free_delivery_above_price',  
  getOption($merchant_id,'free_delivery_above_price')
  ,array('class'=>"numeric_only"));  
  ?>
  <span style="padding-left:8px;"><?php echo adminCurrencySymbol();?></span>
</div>

<hr>

<h2><?php echo Yii::t("default","Merchant Open/Close")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Close Store")?>?</label>  
  <?php 
  echo CHtml::checkBox('merchant_close_store',
   Yii::app()->functions->getOption('merchant_close_store',$merchant_id)=="yes"?true:false
   ,array(
   'class'=>"icheck",
   'value'=>"yes"
  ))
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Show Merchant Current Time")?>?</label>  
  <?php 
  echo CHtml::checkBox('merchant_show_time',
   Yii::app()->functions->getOption('merchant_show_time',$merchant_id)=="yes"?true:false
   ,array(
   'class'=>"icheck",
   'value'=>"yes"
  ))
  ?>  
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Ordering")?>?</label>  
  <?php 
  echo CHtml::checkBox('merchant_disabled_ordering',
   Yii::app()->functions->getOption('merchant_disabled_ordering',$merchant_id)=="yes"?true:false
   ,array(
   'class'=>"icheck",
   'value'=>"yes"
  ))
  ?>  
</div>
  

<h3><?php echo Yii::t("default","External Website")?></h3>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Website address")?></label>
<?php 
echo CHtml::textField('merchant_extenal',$merchant_extenal,array(
'class'=>"uk-width-1-2"
))
?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Voucher")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_enabled_voucher',
  Yii::app()->functions->getOption("merchant_enabled_voucher",$merchant_id)=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Make Delivery Time Required")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_required_delivery_time',
  Yii::app()->functions->getOption("merchant_required_delivery_time",$merchant_id)=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>



<h3><?php echo Yii::t("default","Minimum Order")?> <?php echo t("Delivery")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Minimum purchase amount.")?></label>
  <?php 
  echo CHtml::textField('merchant_minimum_order',$merchant_minimum_order,array(
    'class'=>"numeric_only"
  ))
  ?>
  <?php echo Yii::app()->functions->getCurrencyCode(Yii::app()->functions->getMerchantID());?>
</div>

<h3><?php echo Yii::t("default","Maximum Order")?> <?php echo t("Delivery")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Maximum purchase amount")?></label>
  <?php 
  echo CHtml::textField('merchant_maximum_order',
  $merchant_maximum_order
  ,array(
    'class'=>"numeric_only"
  ))
  ?>
  <?php echo Yii::app()->functions->getCurrencyCode(Yii::app()->functions->getMerchantID());?>
</div>

<h3><?php echo Yii::t("default","Minimum Order")?> <?php echo t("Pickup")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Minimum purchase amount.")?></label>
  <?php 
  echo CHtml::textField('merchant_minimum_order_pickup',$merchant_minimum_order_pickup,array(
    'class'=>"numeric_only"
  ))
  ?>
  <?php echo Yii::app()->functions->getCurrencyCode(Yii::app()->functions->getMerchantID());?>
</div>

<h3><?php echo Yii::t("default","Maximum Order")?> <?php echo t("Pickup")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Maximum purchase amount.")?></label>
  <?php 
  echo CHtml::textField('merchant_maximum_order_pickup',$merchant_maximum_order_pickup,array(
    'class'=>"numeric_only"
  ))
  ?>
  <?php echo Yii::app()->functions->getCurrencyCode(Yii::app()->functions->getMerchantID());?>
</div>

<h3><?php echo t("Dine in")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Minimum purchase amount.")?></label>
  <?php 
  echo CHtml::textField('merchant_minimum_order_dinein',
  getOption($merchant_id,'merchant_minimum_order_dinein')
  ,array(
    'class'=>"numeric_only"
  ))
  ?>
  <?php echo Yii::app()->functions->getCurrencyCode(Yii::app()->functions->getMerchantID());?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Maximum purchase amount.")?></label>
  <?php 
  echo CHtml::textField('merchant_maximum_order_dinein',
  getOption($merchant_id,'merchant_maximum_order_dinein')
  ,array(
    'class'=>"numeric_only"
  ))
  ?>
  <?php echo Yii::app()->functions->getCurrencyCode(Yii::app()->functions->getMerchantID());?>
</div>


<h3><?php echo Yii::t("default","Packaging Charge")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Packaging Wise")?></label>
  <?php 
  echo CHtml::checkBox('merchant_packaging_wise',
  Yii::app()->functions->getOption("merchant_packaging_wise",$merchant_id)==1?true:false
  ,array(
  'class'=>"icheck",
  'value'=>1
  ))
  ?>  
</div>

<p class="uk-text-muted"><?php echo t("When this is enabled packaging charge will be use is what you set in food item")?></p>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Packaging Charge")?></label>
  <?php 
  echo CHtml::textField('merchant_packaging_charge',
  Yii::app()->functions->getOption("merchant_packaging_charge",$merchant_id)
  ,array(
    'class'=>"numeric_only"
  ))
  ?>
  <?php echo Yii::app()->functions->getCurrencyCode(Yii::app()->functions->getMerchantID());?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Packaging Incremental")?></label>
  <?php 
  echo CHtml::checkBox('merchant_packaging_increment',
  Yii::app()->functions->getOption("merchant_packaging_increment",$merchant_id)==2?true:false
  ,array(
  'class'=>"icheck",
  'value'=>2
  ))
  ?>  
</div>

<h3><?php echo Yii::t("default","Tax & Delivery Charges")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Tax")?></label>
  <?php 
  echo CHtml::textField('merchant_tax',$merchant_tax,array(
    'class'=>"numeric_only"
  ))
  ?>%  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Apply Tax to Food Item")?></label>
  <?php 
  echo CHtml::checkBox('merchant_apply_tax',
  getOption($merchant_id,'merchant_apply_tax')==1?true:false
  ,array(
    'value'=>1
  ))
  ?>
  <p class="uk-text-muted"><?php echo t("This will use the Euro tax computation")?></p>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Delivery Charges")?></label>
  <?php   
  echo CHtml::textField('merchant_delivery_charges',$merchant_delivery_charges,array(
    'class'=>"numeric_only",    
  ));
  ?>
  <?php echo Yii::app()->functions->getCurrencyCode(Yii::app()->functions->getMerchantID());?>      
</div>


<?php if ( FunctionsV3::enabledExtraCharges()):?>
<hr>

<h3><?php echo t("Extra Delivery Charges")?></h3>

<?php 
$extra_charge_start_time = getOption($merchant_id,'extra_charge_start_time');
$extra_charge_end_time=''; $extra_charge_fee='';
if(!empty($extra_charge_start_time)){
   $extra_charge_start_time = json_decode($extra_charge_start_time,true);
   $extra_charge_end_time = json_decode(getOption($merchant_id,'extra_charge_end_time'),true);
   $extra_charge_fee = json_decode(getOption($merchant_id,'extra_charge_fee'),true);
}  
?>

<?php if(!is_array($extra_charge_start_time) && count($extra_charge_start_time)<=1):?>
<div class="time_applicable_row">
<div class="uk-form-row time_applicable_rows" >
  <label class="uk-form-label"><?php echo t("Time Applicable")?></label>
  <?php 
  echo CHtml::textField('extra_charge_start_time[]','',
  array(
    'style'=>'width:100px',
    'class'=>'timepick',
    'placeholder'=>t("Start")
  ));
  ?>
  <span><?php echo t("To")?></span>
  <?php 
  echo CHtml::textField('extra_charge_end_time[]','',
  array(
    'style'=>'width:100px',
    'class'=>'timepick',
    'placeholder'=>t("End")
  ));
  ?>    
  
  <?php 
  echo CHtml::textField('extra_charge_fee[]','',
  array(
    'style'=>'width:100px',
    'class'=>'numeric_only',
    'placeholder'=>t("Fee")
  ));
  ?> 

  <a href="javascript:;" class="extra_charge_add_new_row">
  <i class="fa fa-plus-square"></i>
  </a> 
  
</div>    
</div> <!--time_applicable_row-->
<?php endif;?>

<div class="more_charge_row">  
  <?php if(is_array($extra_charge_start_time) && count($extra_charge_start_time)>=1):?>
  
  <?php $x_counter=0; //dump($extra_charge_start_time); dump($extra_charge_fee);?>
  <?php foreach ($extra_charge_start_time as $keyx=>$valx): //dump($keyx);?>
  <div class="uk-form-row time_applicable_rows">
	  <label class="uk-form-label">Time Applicable</label>

	   <?php 
	  echo CHtml::textField("extra_charge_start_time[$x_counter]",$valx,
	  array(
	    'style'=>'width:100px',
	    'class'=>'timepick',
	    'placeholder'=>t("Start")
	  ));
	  ?>
  
	  <span>To</span>
	  
	   <?php
	  echo CHtml::textField("extra_charge_end_time[$x_counter]",
	  isset($extra_charge_end_time[$keyx])?$extra_charge_end_time[$keyx]:''
	  ,
	  array(
	    'style'=>'width:100px',
	    'class'=>'timepick',
	    'placeholder'=>t("End")
	  ));
	  ?>    
	  
	  <?php 
	  echo CHtml::textField('extra_charge_fee[]',
	  isset($extra_charge_fee[$keyx])?$extra_charge_fee[$keyx]:''
	  ,
	  array(
	    'style'=>'width:100px',
	    'class'=>'numeric_only',
	    'placeholder'=>t("Fee")
	  ));
	  ?> 
 	  
	  <a href="javascript:;" class="<?php echo $x_counter==0?"extra_charge_add_new_row":"remove_charge_row"?>">
	  <i class="fa <?php echo $x_counter==0?"fa-plus-square":"fa-minus-square"?>"></i>
	  </a> 
	  
	</div>
	<?php $x_counter++;?>
	<?php endforeach;?>
  
  <?php endif;?>
</div> <!--more_charge_row-->



<div class="uk-form-row">
<label class="uk-form-label"><?php echo t("Extra Charge Notification")?></label>
<?php
echo CHtml::textArea('extra_charge_notification',
getOption($merchant_id,'extra_charge_notification')
,array(
  'placeholder'=>t(""),
  'class'=>'uk-form-width-large'
  ));
?>
</div>


<hr>
<?php endif;?>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Do not apply tax to delivery charges")?></label>
<?php echo CHtml::checkBox('merchant_tax_charges',
Yii::app()->functions->getOption("merchant_tax_charges",$merchant_id)==2?true:false
,array(
'class'=>"icheck",
'value'=>2
));  
?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled Opt in for no contact delivery")?></label>
  <?php 
  echo CHtml::checkBox('merchant_opt_contact_delivery',
  Yii::app()->functions->getOption("merchant_opt_contact_delivery",$merchant_id)==1?true:false
  ,array(
  'class'=>"icheck",
  'value'=>1
  ))
  ?>  
</div>


<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Delivery Estimation")?></label>
<?php 
  echo CHtml::textField('merchant_delivery_estimation',$merchant_delivery_estimation,array(
  'placeholder'=>Yii::t("default","1 hour approx.")
  ));
  ?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Delivery Distance Covered")?></label>
<?php 
  echo CHtml::textField('merchant_delivery_miles',
  Yii::app()->functions->getOption("merchant_delivery_miles",$merchant_id)
  ,array(
  'placeholder'=>"",
  "class"=>"numeric_only"
  ));
  ?>
  <?php //echo Yii::t("default","Miles")?>
  <?php 
  echo CHtml::dropDownList('merchant_distance_type',
  Yii::app()->functions->getOption("merchant_distance_type",$merchant_id),Yii::app()->functions->distanceOption());
  ?>
</div>
<p class="uk-text-muted"><?php echo Yii::t("default","Leave the fields empty to not check the distance")?></p>



<h3><?php echo Yii::t("default","Tips")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_enabled_tip',
  Yii::app()->functions->getOption("merchant_enabled_tip",$merchant_id)==2?true:false
  ,array(
  'class'=>"icheck",
  'value'=>2
  ))
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Default Tip")?></label>
  <?php 
  echo CHtml::dropDownList('merchant_tip_default',
  Yii::app()->functions->getOption("merchant_tip_default",$merchant_id)
  ,(array)$tips_list,
  array(   
  ));
  ?>  
</div>


<?php $days=Yii::app()->functions->getDays();?>
<h3><?php echo Yii::t("default","Store Hours")?></h3>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Time Zone")?></label>
<?php 
echo CHtml::dropDownList('merchant_timezone',
Yii::app()->functions->getOption("merchant_timezone",$merchant_id)
,Yii::app()->functions->timezoneList())
?>
</div>


<div class="uk-form-row">
<label class="uk-form-label"><?php echo t("Time picker list interval")?></label>
<?php 
echo CHtml::telField('website_merchant_time_picker_interval',
getOption($merchant_id,'website_merchant_time_picker_interval') ,array(
 'class'=>"numeric_only",
 'placeholder'=>t("default is 15mins")
));
?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label">
  <?php echo Yii::t("default","Store days(s) Open:")?>  
  </label>  
  <div class="clear"></div>
  <p class="uk-text-muted"><?php echo Yii::t("default","If days has not been selected then merchant will be set to open")?></p>
  <ul class="uk-list uk-list-striped">
  <?php foreach ($days as $key=>$val):?>
  <li>
  <div class="uk-grid" >
  
    <div class="uk-width-1-6" style="width:5%;">
    <?php echo CHtml::checkBox('stores_open_day[]',
    in_array($key,(array)$stores_open_day)?true:false
    ,array('value'=>$key,'class'=>"icheck"))?>
    </div>
    
    <div class="uk-width-1-6" style="width:12%"><?php echo ucwords(Yii::app()->functions->translateDate($val));?></div>    
    <div class="uk-width-1-6" style="width:12%">
      <?php echo CHtml::textField("stores_open_starts[$key]",
      array_key_exists($key,(array)$stores_open_starts)?timeFormat($stores_open_starts[$key],true):""
      ,array('placeholder'=>Yii::t("default","Start"),'class'=>"timepick"));?>
    </div>
    
    <div class="uk-width-1-6" style="width:5%;"><?php echo Yii::t("default","To")?></div>
    <div class="uk-width-1-6" style="width:12%">
      <?php echo CHtml::textField("stores_open_ends[$key]",
      array_key_exists($key,(array)$stores_open_ends)?timeFormat($stores_open_ends[$key],true):""
      ,array('placeholder'=>Yii::t("default","End"),'class'=>"timepick"));?>
    </div>
    
    <div class="uk-width-1-6" style="width:5%;">
    /
    </div>
        
    <div class="uk-width-1-6" style="width:12%">
      <?php echo CHtml::textField("stores_open_pm_start[$key]",
      array_key_exists($key,(array)$stores_open_pm_start)?timeFormat($stores_open_pm_start[$key],true):""
      ,array('placeholder'=>Yii::t("default","Start"),'class'=>"timepick"));?>
    </div>
    <div class="uk-width-1-6" style="width:5%;"><?php echo Yii::t("default","To")?></div>
    <div class="uk-width-1-6" style="width:12%">
      <?php echo CHtml::textField("stores_open_pm_ends[$key]",
      array_key_exists($key,(array)$stores_open_pm_ends)?timeFormat($stores_open_pm_ends[$key],true):""
      ,array('placeholder'=>Yii::t("default","End"),'class'=>"timepick"));?>
    </div>
    
    <div class="uk-width-1-6" style="width:12%">
      <?php echo CHtml::textField("stores_open_custom_text[$key]",
      array_key_exists($key,(array)$stores_open_custom_text)?$stores_open_custom_text[$key]:""
      ,array('placeholder'=>Yii::t("default","Custom text")));?>
     </div>
   </div>    
  </li>
  <?php endforeach;?>
  </ul>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Accept Pre-orders")?></label>
<?php 
echo CHtml::checkBox('merchant_preorder',
Yii::app()->functions->getOption("merchant_preorder",$merchant_id)==1?true:false
,array(
'class'=>"icheck",
'value'=>1
));
?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Close Message")?></label>
<?php 
echo CHtml::textArea('merchant_close_msg',
Yii::app()->functions->getOption("merchant_close_msg",$merchant_id)
,array(
'class'=>"uk-form-width-large"
));
?>
</div>



<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Holidays")?>:</label>

<div class="holiday_list">


<?php if ( $m_holiday=Yii::app()->functions->getMerchantHoliday($merchant_id)):?>
<?php foreach ($m_holiday as $m_h):?>
<div class="holiday_row">
<?php echo CHtml::textField('merchant_holiday[]',$m_h,array(
  'class'=>"j_date_normal small_date"
));?>
<a href="javascript:;" class="remove_holiday"><i class="fa fa-minus-square"></i></a>
</div>
<?php endforeach;?>

<?php else:?>

<div class="holiday_row">
<?php echo CHtml::textField('merchant_holiday[]','',array(
  'class'=>"j_date_normal small_date"
));?>
</div>

<?php endif;?>

</div> <!--holiday_list-->

<a href="javascript:;" class="add_new_holiday"><i class="fa fa-plus-square"></i></a>

</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Holiday Close Message")?></label>
<?php 
echo CHtml::textArea('merchant_close_msg_holiday',
Yii::app()->functions->getOption("merchant_close_msg_holiday",$merchant_id)
,array(
'class'=>"uk-form-width-large"
));
?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>