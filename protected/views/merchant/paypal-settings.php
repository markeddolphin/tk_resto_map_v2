<?php
$merchant_id=Yii::app()->functions->getMerchantID();
$enabled_paypal=Yii::app()->functions->getOption('enabled_paypal',$merchant_id);
$paypal_mode=Yii::app()->functions->getOption('paypal_mode',$merchant_id);
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','savePaypalSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Paypal")?>?</label>
  <?php 
  echo CHtml::checkBox('enabled_paypal',
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
  echo CHtml::radioButton('paypal_mode',
  $paypal_mode=="sandbox"?true:false
  ,array(
    'value'=>Yii::t("default","sandbox"),
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?> 
  <?php 
  echo CHtml::radioButton('paypal_mode',
  $paypal_mode=="live"?true:false
  ,array(
    'value'=>Yii::t("default","live"),
    'class'=>"icheck"
  ))
  ?>	
  <?php echo t("Live")?> 
</div>


<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Card Fee")?></label>
<?php 
echo CHtml::textField('merchant_paypal_fee',
Yii::app()->functions->getOption('merchant_paypal_fee',$merchant_id)
,array(
'class'=>"uk-form-width-small numeric_only"
))
?>
</div>

<h3><?php echo Yii::t("default","Sandbox")?></h3>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal User")?></label>
  <?php 
  echo CHtml::textField('sanbox_paypal_user',
  Yii::app()->functions->getOption('sanbox_paypal_user',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal Password")?></label>
  <?php 
  echo CHtml::textField('sanbox_paypal_pass',
  Yii::app()->functions->getOption('sanbox_paypal_pass',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal Signature")?></label>
  <?php 
  echo CHtml::textField('sanbox_paypal_signature',
  Yii::app()->functions->getOption('sanbox_paypal_signature',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>



<h3><?php echo Yii::t("default","Live")?></h3>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal User")?></label>
  <?php 
  echo CHtml::textField('live_paypal_user',
  Yii::app()->functions->getOption('live_paypal_user',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal Password")?></label>
  <?php 
  echo CHtml::textField('live_paypal_pass',
  Yii::app()->functions->getOption('live_paypal_pass',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal Signature")?></label>
  <?php 
  echo CHtml::textField('live_paypal_signature',
  Yii::app()->functions->getOption('live_paypal_signature',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>


<!--<hr>
<h3><?php echo t("Mobile Paypal payment Settings")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Paypal")?>?</label>
  <?php 
  echo CHtml::checkBox('mt_paypal_mobile_enabled',
  getOption($merchant_id,'mt_paypal_mobile_enabled')=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('mt_paypal_mobile_mode',
  getOption($merchant_id,'mt_paypal_mobile_mode')=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?> 
  <?php 
  echo CHtml::radioButton('mt_paypal_mobile_mode',
  getOption($merchant_id,'mt_paypal_mobile_mode')=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo t("Live")?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Client ID")?></label>
  <?php 
  echo CHtml::textField('mt_paypal_mobile_clientid',
  getOption($merchant_id,'mt_paypal_mobile_clientid')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>
-->

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>