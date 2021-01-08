<?php
$merchant_id=Yii::app()->functions->getMerchantID();
$enabled=Yii::app()->functions->getOption('merchant_sisow_enabled',$merchant_id);
$paymode=Yii::app()->functions->getOption('merchant_sisow_mode',$merchant_id);
?>
<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','merchantSisowSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Sisow")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_sisow_enabled',
  $enabled=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('merchant_sisow_mode',
  $paymode=="Sandbox"?true:false
  ,array(
    'value'=>"Sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo Yii::t("default","Sandbox")?>
  <?php 
  echo CHtml::radioButton('merchant_sisow_mode',
  $paymode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo Yii::t("default","live")?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Merchant ID")?></label>
  <?php 
  echo CHtml::textField('merchant_sanbox_sisow_secret_key',
  Yii::app()->functions->getOption('merchant_sanbox_sisow_secret_key',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Merchant Key")?></label>
  <?php 
  echo CHtml::textField('merchant_sandbox_sisow_pub_key',
  Yii::app()->functions->getOption('merchant_sandbox_sisow_pub_key',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Shop ID")?></label>
  <?php 
  echo CHtml::textField('merchant_sandbox_sisow_shopid',
  Yii::app()->functions->getOption('merchant_sandbox_sisow_shopid',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>



<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>