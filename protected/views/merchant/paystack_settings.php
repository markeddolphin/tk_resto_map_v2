<?php
$enabled=getOption($mtid,'merchant_paystack_enabled');
$paymode=getOption($mtid,'merchant_paystack_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','MerchantPaystackSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_paystack_enabled',
  $enabled==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('merchant_paystack_mode',
  $paymode==1?true:false
  ,array(
    'value'=>"1",
    'class'=>"icheck"
  ))
  ?>
  <?php echo Yii::t("default","Sandbox")?>
  <?php 
  echo CHtml::radioButton('merchant_paystack_mode',
  $paymode==2?true:false
  ,array(
    'value'=>"2",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo Yii::t("default","live")?> 
</div>

<h3><?php echo t("Sandbox")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Secret Key")?></label>
  <?php 
  echo CHtml::textField('merchant_paystack_sandbox_secret_key',
  getOption($mtid,'merchant_paystack_sandbox_secret_key')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<h3><?php echo t("Production")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Secret Key")?></label>
  <?php 
  echo CHtml::textField('merchant_paystack_production_secret_key',
  getOption($mtid,'merchant_paystack_production_secret_key')
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