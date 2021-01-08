<?php
$paymode=Yii::app()->functions->getOptionAdmin('admin_ip8_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','adminIpay88Settings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("Ipay88")?>?</label>
  <?php 
  echo CHtml::checkBox('admin_ip8_enabled',
  getOptionA('admin_ip8_enabled')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('admin_ip8_mode',
  $paymode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo Yii::t("default","Sandbox")?>
  <?php 
  echo CHtml::radioButton('admin_ip8_mode',
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
  echo CHtml::dropDownList('admin_ip8_language',getOptionA('admin_ip8_language'),array(
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
  echo CHtml::textField('admin_ip8_web_merchantcode',
  Yii::app()->functions->getOptionAdmin('admin_ip8_web_merchantcode')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant Key")?></label>
  <?php 
  echo CHtml::textField('admin_ip8_web_merchantkey',
  Yii::app()->functions->getOptionAdmin('admin_ip8_web_merchantkey')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<h3><?php echo Yii::t("default","Mobile Settings")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant Code")?></label>
  <?php 
  echo CHtml::textField('admin_ip8_merchantcode',
  Yii::app()->functions->getOptionAdmin('admin_ip8_merchantcode')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant Key")?></label>
  <?php 
  echo CHtml::textField('admin_ip8_merchantkey',
  Yii::app()->functions->getOptionAdmin('admin_ip8_merchantkey')
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