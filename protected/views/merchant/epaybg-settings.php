<?php
$merchant_id=Yii::app()->functions->getMerchantID();
$enabled_paypal=Yii::app()->functions->getOption('merchant_enabled_epaybg',$merchant_id);
$paypal_mode=Yii::app()->functions->getOption('merchant_mode_epaybg',$merchant_id);
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','saveMerchantEpaybgSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_enabled_epaybg',
  $enabled_paypal=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('merchant_mode_epaybg',
  $paypal_mode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?> 
  <?php 
  echo CHtml::radioButton('merchant_mode_epaybg',
  $paypal_mode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo t("Live")?> 
</div>

<h3><?php echo Yii::t("default","Sandbox")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","MIN")?></label>
  <?php 
  echo CHtml::textField('merchant_sandbox_epaybg_min',
  Yii::app()->functions->getOption('merchant_sandbox_epaybg_min',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Secret")?></label>
  <?php 
  echo CHtml::textField('merchant_sandbox_epaybg_secret',
  Yii::app()->functions->getOption('merchant_sandbox_epaybg_secret',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Payment Request")?></label>
  <?php   
  echo CHtml::dropDownList('merchant_sandbox_epaybg_request',
  Yii::app()->functions->getOption('merchant_sandbox_epaybg_request',$merchant_id)
  ,Yii::app()->functions->epayBgPaymentRequestType());
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Language")?></label>
  <?php   
  echo CHtml::dropDownList('merchant_sandbox_epaybg_lang',
  Yii::app()->functions->getOption('merchant_sandbox_epaybg_lang',$merchant_id)
  ,Yii::app()->functions->epayBgPaymentLanguahe());
  ?>
</div>



<h3><?php echo Yii::t("default","Live")?></h3>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","MIN")?></label>
  <?php 
  echo CHtml::textField('merchant_live_epaybg_min',
  Yii::app()->functions->getOption('merchant_live_epaybg_min',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Secret")?></label>
  <?php 
  echo CHtml::textField('merchant_live_epaybg_secret',
  Yii::app()->functions->getOption('merchant_live_epaybg_secret',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Payment Request")?></label>
  <?php   
  echo CHtml::dropDownList('merchant_live_epaybg_request',
  Yii::app()->functions->getOption('merchant_live_epaybg_request',$merchant_id)
  ,Yii::app()->functions->epayBgPaymentRequestType());
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Language")?></label>
  <?php   
  echo CHtml::dropDownList('merchant_live_epaybg_lang',
  Yii::app()->functions->getOption('merchant_live_epaybg_lang',$merchant_id)
  ,Yii::app()->functions->epayBgPaymentLanguahe());
  ?>
</div>

<h3><?php echo Yii::t("default","URL for receiving notifications")?></h3>
<p class="uk-text-danger"><?php echo websiteUrl()?>/store/epaybg/mode/receiver/</p>
<p><?php echo t("Set this link as Notification for WEB payments in your Epaybg account")?></p>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>