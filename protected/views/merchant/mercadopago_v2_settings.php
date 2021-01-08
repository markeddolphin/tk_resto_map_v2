<?php
$enabled=getOption($mtid,'merchant_mercadopago_v2_enabled');
$paymode=getOption($mtid,'merchant_mercadopago_v2_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','merchant_mercadopago_v2_settings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled mercadopago V2")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_mercadopago_v2_enabled',
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
  echo CHtml::radioButton('merchant_mercadopago_v2_mode',
  $paymode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo Yii::t("default","Sandbox")?>
  <?php 
  echo CHtml::radioButton('merchant_mercadopago_v2_mode',
  $paymode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo Yii::t("default","live")?> 
</div>

<h3><?php echo Yii::t("default","Sandbox")?></h3>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Client ID")?></label>
  <?php 
  echo CHtml::textField('merchant_mercadopago_v2_client_id',
  getOption($mtid,'merchant_mercadopago_v2_client_id')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Client Secret")?></label>
  <?php 
  echo CHtml::textField('merchant_mercadopago_v2_client_secret',
  getOption($mtid,'merchant_mercadopago_v2_client_secret')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Card fee")?></label>
  <?php 
  echo CHtml::textField('merchant_mercadopago_v2_card_fee',
  getOption($mtid,'merchant_mercadopago_v2_card_fee')
  ,array(
    'class'=>"numeric_only"
  ))
  ?>
</div>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>