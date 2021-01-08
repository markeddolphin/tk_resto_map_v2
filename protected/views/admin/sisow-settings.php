<?php
$enabled=Yii::app()->functions->getOptionAdmin('admin_sisow_enabled');
$paymode=Yii::app()->functions->getOptionAdmin('admin_sisow_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','adminSisowSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Sisow")?>?</label>
  <?php 
  echo CHtml::checkBox('admin_sisow_enabled',
  $enabled=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('admin_sisow_mode',
  $paymode=="Sandbox"?true:false
  ,array(
    'value'=>"Sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo Yii::t("default","Sandbox")?>
  <?php 
  echo CHtml::radioButton('admin_sisow_mode',
  $paymode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo Yii::t("default","live")?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Merchant ID")?></label>
  <?php 
  echo CHtml::textField('admin_sanbox_sisow_secret_key',
  Yii::app()->functions->getOptionAdmin('admin_sanbox_sisow_secret_key')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Merchant Key")?></label>
  <?php 
  echo CHtml::textField('admin_sandbox_sisow_pub_key',
  Yii::app()->functions->getOptionAdmin('admin_sandbox_sisow_pub_key')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Shop ID")?></label>
  <?php 
  echo CHtml::textField('admin_sandbox_sisow_shopid',
  Yii::app()->functions->getOptionAdmin('admin_sandbox_sisow_shopid')
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