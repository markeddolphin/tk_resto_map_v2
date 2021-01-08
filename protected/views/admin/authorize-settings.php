<?php
$enabled=Yii::app()->functions->getOptionAdmin('admin_enabled_autho');
$mode=Yii::app()->functions->getOptionAdmin('admin_mode_autho');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','saveAdminAuthorizeSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled")?>?</label>
  <?php 
  echo CHtml::checkBox('admin_enabled_autho',
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
  echo CHtml::radioButton('admin_mode_autho',
  $mode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?> 
  <?php 
  echo CHtml::radioButton('admin_mode_autho',
  $mode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo t("Live")?> 
</div>

<h3><?php echo Yii::t("default","Credentials")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","API Login ID")?></label>
  <?php 
  echo CHtml::textField('admin_autho_api_id',
  Yii::app()->functions->getOptionAdmin('admin_autho_api_id')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Transaction Key")?></label>
  <?php 
  echo CHtml::textField('admin_autho_key',
  Yii::app()->functions->getOptionAdmin('admin_autho_key')
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