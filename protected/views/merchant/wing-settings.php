<?php 
$mode = getOption($mtid,'merchant_wing_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','MerchantWingSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("Wing Pay")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_wing_enabled',
  getOption($mtid,'merchant_wing_enabled')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('merchant_wing_mode',
  $mode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?> 
  <?php 
  echo CHtml::radioButton('merchant_wing_mode',
  $mode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo t("Live")?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Login ID")?></label>
  <?php 
  echo CHtml::textField('merchant_wing_loginid',
  getOption($mtid,'merchant_wing_loginid')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Password")?></label>
  <?php 
  echo CHtml::passwordField('merchant_wing_password',
  getOption($mtid,'merchant_wing_password')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Biller")?></label>
  <?php 
  echo CHtml::textField('merchant_wing_biller',
  getOption($mtid,'merchant_wing_biller')
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