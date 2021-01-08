<?php
$merchant_id=Yii::app()->functions->getMerchantID();
$paymode=Yii::app()->functions->getOption('merchant_ip8_mode',$merchant_id);
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','merchantIpay88Settings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("Ipay88")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_ip8_enabled',
  Yii::app()->functions->getOption('merchant_ip8_enabled',$merchant_id)==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('merchant_ip8_mode',
  $paymode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo Yii::t("default","Sandbox")?>
  <?php 
  echo CHtml::radioButton('merchant_ip8_mode',
  $paymode=="production"?true:false
  ,array(
    'value'=>"production",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo Yii::t("default","live")?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Language")?></label>
  <?php 
  echo CHtml::dropDownList('merchant_ip8_language',getOptionA('merchant_ip8_language'),array(
    'ISO-8859-1'=>"English",
    'UTF-8'=>"UTF-8",
    'GB2312'=>"Chinese Simplified - GB2312",
    'GD18030'=>"Chinese Simplified - GD18030",
    'BIG5'=>"Chinese Traditional",
  ));
  ?>
</div>



<h3><?php echo Yii::t("default","Web Settings")?></h3>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant Code")?></label>
  <?php 
  echo CHtml::textField('merchant_ip8_web_merchantcode',
  Yii::app()->functions->getOption('merchant_ip8_web_merchantcode',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant Key")?></label>
  <?php 
  echo CHtml::textField('merchant_ip8_web_merchantkey',
  Yii::app()->functions->getOption('merchant_ip8_web_merchantkey',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<h3><?php echo Yii::t("default","Mobile Settings")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant Code")?></label>
  <?php 
  echo CHtml::textField('merchant_ip8_merchantcode',
  Yii::app()->functions->getOption('merchant_ip8_merchantcode',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant Key")?></label>
  <?php 
  echo CHtml::textField('merchant_ip8_merchantkey',
  Yii::app()->functions->getOption('merchant_ip8_merchantkey',$merchant_id)
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