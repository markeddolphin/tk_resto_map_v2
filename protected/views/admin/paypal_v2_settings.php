<?php
$enabled=Yii::app()->functions->getOptionAdmin('admin_paypal_v2_enabled');
$paymode=Yii::app()->functions->getOptionAdmin('admin_paypal_v2_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','adminPaypalV2Settings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Paypal V2")?></label>
  <?php 
  echo CHtml::checkBox('admin_paypal_v2_enabled',
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
  echo CHtml::radioButton('admin_paypal_v2_mode',
  $paymode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo Yii::t("default","Sandbox")?>
  <?php 
  echo CHtml::radioButton('admin_paypal_v2_mode',
  $paymode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo Yii::t("default","live")?> 
</div>



<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Card fee")?></label>
  <?php 
  echo CHtml::textField('admin_paypal_v2_card_fee',
  Yii::app()->functions->getOptionAdmin('admin_paypal_v2_card_fee')
  ,array(
    'class'=>"numeric_only"
  ))
  ?>
</div>

<h3><?php echo Yii::t("default","Credentials")?></h3>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Client ID")?></label>
  <?php 
  echo CHtml::textField('admin_paypal_v2_client_id',
  Yii::app()->functions->getOptionAdmin('admin_paypal_v2_client_id')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Secret")?></label>
  <?php 
  echo CHtml::textField('admin_paypal_v2_secret',
  Yii::app()->functions->getOptionAdmin('admin_paypal_v2_secret')
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