
<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','AdminIpaySettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("Ipay")?>?</label>
  <?php 
  echo CHtml::checkBox('admin_ipay_enabled',
  getOptionA('admin_ipay_enabled')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant Key")?></label>
  <?php 
  echo CHtml::textField('admin_ipay_merchant_key',
  Yii::app()->functions->getOptionAdmin('admin_ipay_merchant_key')
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