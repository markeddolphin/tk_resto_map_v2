
<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','MerchantPiPaySettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("Pi Pay")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_pipay_enabled',
  getOption($merchant_id,'merchant_pipay_enabled')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>


<?php $paymode=getOption($merchant_id,'merchant_pipay_mode')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('merchant_pipay_mode',
  $paymode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo Yii::t("default","Sandbox")?>
  <?php 
  echo CHtml::radioButton('merchant_pipay_mode',
  $paymode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo Yii::t("default","live")?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant ID")?></label>
  <?php 
  echo CHtml::textField('merchant_pipay_merchant_id',
  getOption($merchant_id,'merchant_pipay_merchant_id')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Device ID")?></label>
  <?php 
  echo CHtml::textField('merchant_pipay_device_id',
  getOption($merchant_id,'merchant_pipay_device_id')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Store ID")?></label>
  <?php 
  echo CHtml::textField('merchant_pipay_store_id',
  getOption($merchant_id,'merchant_pipay_store_id')
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