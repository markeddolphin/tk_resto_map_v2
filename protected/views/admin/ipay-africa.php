<?php 
$mode = getOptionA('admin_ipay_africa_mode');
$enabled_payment = getOptionA('ipay_africa_enabled_payment');
$enabled_payment_selected = array();
if(!empty($enabled_payment)){
	$enabled_payment_selected = json_decode($enabled_payment,true);
}
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','IpayAfricaSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("Ipay Africa")?>?</label>
  <?php 
  echo CHtml::checkBox('admin_ipay_africa_enabled',
  getOptionA('admin_ipay_africa_enabled')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('admin_ipay_africa_mode',
  $mode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?> 
  <?php 
  echo CHtml::radioButton('admin_ipay_africa_mode',
  $mode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo t("Live")?> 
</div>


<h2><?php echo t("Credentials")?></h2>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Vendor ID")?></label>
  <?php 
  echo CHtml::textField('admin_ipay_africa_vendor_id',
  getOptionA('admin_ipay_africa_vendor_id')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Hash Key")?></label>
  <?php 
  echo CHtml::textField('admin_ipay_africa_hashkey',
  getOptionA('admin_ipay_africa_hashkey')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<h2><?php echo t("Mobile app settings")?></h2>

<ul data-uk-tab="{connect:'#tab-content'}" class="uk-tab uk-active">
<li class="uk-active"><a href="#"><?php echo t("MPESA")?></a></li>
<li class=""><a href="#"><?php echo t("AIRTEL")?></a></li>
<li class=""><a href="#"><?php echo t("VISA")?></a></li>
</ul>

<ul class="uk-switcher uk-margin " id="tab-content">
<li class="uk-active">
    <fieldset>        
    
     <div class="uk-form-row">
	  <label class="uk-form-label"><?php echo t("Enabled")?></label>
	  <?php 
	  echo CHtml::checkBox('ipay_africa_enabled_payment[]',
	  in_array('mpesa',(array)$enabled_payment_selected)?true:false
	  ,array(
	   'value'=>"mpesa",
       'class'=>"icheck"	    	   
	  ))
	  ?>
	</div>
    
   
    
    </fieldset>
    
</li>
<li class="uk-active">
    <fieldset>        
   
     <div class="uk-form-row">
	  <label class="uk-form-label"><?php echo t("Enabled")?></label>
	  <?php 
	  echo CHtml::checkBox('ipay_africa_enabled_payment[]',
	  in_array('airtel',(array)$enabled_payment_selected)?true:false
	  ,array(
	   'value'=>"airtel",
       'class'=>"icheck"	    	   
	  ))
	  ?>
	</div>
	
 
    
    </fieldset>
</li>

<li>
  <fieldset>

    <div class="uk-form-row">
	  <label class="uk-form-label"><?php echo t("Enabled")?></label>
	  <?php 
	  echo CHtml::checkBox('ipay_africa_enabled_payment[]',
	  in_array('visa',(array)$enabled_payment_selected)?true:false
	  ,array(
	   'value'=>"visa",
       'class'=>"icheck"	    	   
	  ))
	  ?>
	</div> 
  
  </fieldset>
</li>
</ul>    

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>