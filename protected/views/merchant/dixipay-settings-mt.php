<?php 
$mode = getOption($mtid,'merchant_dixipay_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','merchantDixiPaySettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("DIXIPAY")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_dixipay_enabled',
  getOption($mtid,'merchant_dixipay_enabled')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('merchant_dixipay_mode',
  $mode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?> 
  <?php 
  echo CHtml::radioButton('merchant_dixipay_mode',
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
  <label class="uk-form-label"><?php echo t("Username")?></label>
  <?php 
  echo CHtml::textField('merchant_dixipay_username',
  getOption($mtid,'merchant_dixipay_username')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Password")?></label>
  <?php 
  echo CHtml::textField('merchant_dixipay_password',
  getOption($mtid,'merchant_dixipay_password')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant Account Code")?></label>
  <?php 
  echo CHtml::textField('merchant_dixipay_account_code',
  getOption($mtid,'merchant_dixipay_account_code')
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