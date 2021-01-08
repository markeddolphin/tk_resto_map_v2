<?php 
$mode = getOption($mtid,'merchant_ipay_africa_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','MerchantIpayAfricaSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("Ipay Africa")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_ipay_africa_enabled',
  getOption($mtid,'merchant_ipay_africa_enabled')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('merchant_ipay_africa_mode',
  $mode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?> 
  <?php 
  echo CHtml::radioButton('merchant_ipay_africa_mode',
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
  echo CHtml::textField('merchant_ipay_africa_vendor_id',
  getOption($mtid,'merchant_ipay_africa_vendor_id')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Hash Key")?></label>
  <?php 
  echo CHtml::textField('merchant_ipay_africa_hashkey',
  getOption($mtid,'merchant_ipay_africa_hashkey')
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