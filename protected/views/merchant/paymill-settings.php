<?php 
$mode = getOption($mtid,'merchant_paymill_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','PaymillMerchantSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("Paymill")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_paymill_enabled',
  getOption($mtid,'merchant_paymill_enabled')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('merchant_paymill_mode',
  $mode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?> 
  <?php 
  echo CHtml::radioButton('merchant_paymill_mode',
  $mode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo t("Live")?> 
</div>



<h2><?php echo t("Fee")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Card Fee")?> 1</label>
  <?php 
  echo CHtml::textField('merchant_paymill_card_fee1',
  getOption($mtid,'merchant_paymill_card_fee1')
  ,array(
    'class'=>"numeric_only"
  ))
  ?>&nbsp;%
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Card Fee")?> 2</label>
  <?php 
  echo CHtml::textField('merchant_paymill_card_fee2',
  getOption($mtid,'merchant_paymill_card_fee2')
  ,array(
    'class'=>"numeric_only"
  ))
  ?>&nbsp;<?php echo adminCurrencySymbol()?>
</div>


<h2><?php echo t("Sandbox")?></h2>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Private key")?></label>
  <?php 
  echo CHtml::textField('merchant_paymill_test_private_key',
  getOption($mtid,'merchant_paymill_test_private_key')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Public key")?></label>
  <?php 
  echo CHtml::textField('merchant_paymill_test_public_key',
  getOption($mtid,'merchant_paymill_test_public_key')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<h2><?php echo t("Production")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Private key")?></label>
  <?php 
  echo CHtml::textField('merchant_paymill_live_private_key',
  getOption($mtid,'merchant_paymill_live_private_key')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Public key")?></label>
  <?php 
  echo CHtml::textField('merchant_paymill_live_public_key',
  getOption($mtid,'merchant_paymill_live_public_key')
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