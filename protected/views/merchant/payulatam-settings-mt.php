<?php 
$mode = getOption($mtid,'merchant_payulatam_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','payulatamMerchantSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("PayU Latam")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_payulatam_enabled',
  getOption($mtid,'merchant_payulatam_enabled')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('merchant_payulatam_mode',
  $mode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?> 
  <?php 
  echo CHtml::radioButton('merchant_payulatam_mode',
  $mode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo t("Live")?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Language")?></label>
  <?php echo CHtml::dropDownList('merchant_payulatam_lang',
  getOption($mtid,'merchant_payulatam_lang')
  ,(array)Payulatam::language())?>
</div>  


<h2><?php echo t("Sandbox")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("API Key")?></label>
  <?php 
  echo CHtml::textField('merchant_payulatam_apikey',
  getOption($mtid,'merchant_payulatam_apikey')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("API Login")?></label>
  <?php 
  echo CHtml::textField('merchant_payulatam_apilogin',
  getOption($mtid,'merchant_payulatam_apilogin')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant ID")?></label>
  <?php 
  echo CHtml::textField('merchant_payulatam_mtid',
  getOption($mtid,'merchant_payulatam_mtid')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Account ID")?></label>
  <?php 
  echo CHtml::textField('merchant_payulatam_account_id',
  getOption($mtid,'merchant_payulatam_account_id')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<h2><?php echo t("Production")?></h2>



<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("API Key")?></label>
  <?php 
  echo CHtml::textField('merchant_payulatam_apikey_live',
  getOption($mtid,'merchant_payulatam_apikey_live')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("API Login")?></label>
  <?php 
  echo CHtml::textField('merchant_payulatam_apilogin_live',
  getOption($mtid,'merchant_payulatam_apilogin_live')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant ID")?></label>
  <?php 
  echo CHtml::textField('merchant_payulatam_mtid_live',
  getOption($mtid,'merchant_payulatam_mtid_live')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Account ID")?></label>
  <?php 
  echo CHtml::textField('merchant_payulatam_account_id_live',
  getOption($mtid,'merchant_payulatam_account_id_live')
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