<?php 
$mode = getOption($mtid,'merchant_wirecard_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','WireCardSettingsMerchant')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("WireCard")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_wirecard_enabled',
  getOption($mtid,'merchant_wirecard_enabled')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('merchant_wirecard_mode',
  $mode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?> 
  <?php 
  echo CHtml::radioButton('merchant_wirecard_mode',
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
  <label class="uk-form-label"><?php echo t("Card Fee 1")?></label>
  <?php 
  echo CHtml::textField('merchant_wirecard_fee_1',
  getOption($mtid,'merchant_wirecard_fee_1')
  ,array(
    'class'=>"uk-form-width-small numeric_only",
    'maxlength'=>14,    
  ))
  ?>&nbsp;%
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Card Fee 2")?></label>
  <?php 
  echo CHtml::textField('merchant_wirecard_fee_2',
  getOption($mtid,'merchant_wirecard_fee_2')
  ,array(
    'class'=>"uk-form-width-small numeric_only",
    'maxlength'=>14,    
  ))
  ?>&nbsp;<?php echo adminCurrencySymbol()?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Display Text")?></label>
  <?php 
  echo CHtml::textField('merchant_wirecard_display_text',
  getOption($mtid,'merchant_wirecard_display_text')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Language")?></label>
  <?php 
  echo CHtml::textField('merchant_wirecard_lang',
  getOption($mtid,'merchant_wirecard_lang')
  ,array(
    'class'=>"uk-form-width-small",
    'maxlength'=>2,
    'placeholder'=>t("Default is en")
  ))
  ?>
</div>


<h2><?php echo t("Sandbox")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Customer ID")?></label>
  <?php 
  echo CHtml::textField('merchant_wirecard_customer_id',
  getOption($mtid,'merchant_wirecard_customer_id')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Shop Id")?></label>
  <?php 
  echo CHtml::textField('merchant_wiredcard_shopid',
  getOption($mtid,'merchant_wiredcard_shopid')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Secret")?></label>
  <?php 
  echo CHtml::textField('merchant_wirecard_secret',
  getOption($mtid,'merchant_wirecard_secret')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<h2><?php echo t("Production")?></h2>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Customer ID")?></label>
  <?php 
  echo CHtml::textField('merchant_wirecard_customer_id_live',
  getOption($mtid,'merchant_wirecard_customer_id_live')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Shop Id")?></label>
  <?php 
  echo CHtml::textField('merchant_wiredcard_shopid_live',
  getOption($mtid,'merchant_wiredcard_shopid_live')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Secret")?></label>
  <?php 
  echo CHtml::textField('merchant_wirecard_secret_live',
  getOption($mtid,'merchant_wirecard_secret_live')
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