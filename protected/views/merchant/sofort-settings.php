
<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','SofortMerchantSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("Sofort Payments")?>?</label>
  <?php   
  echo CHtml::checkBox('merchant_sofort_enabled',
  getOption($mtid,'merchant_sofort_enabled')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Config Key")?></label>
  <?php 
  echo CHtml::textField('merchant_sofort_config_key',
  getOption($mtid,'merchant_sofort_config_key')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Set Language Code")?></label>
  <?php 
  echo CHtml::textField('merchant_sofort_lang',
  getOption($mtid,'merchant_sofort_lang')
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