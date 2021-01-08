<?php
$paymode=Yii::app()->functions->getOptionAdmin('admin_rzr_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','adminRazorSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("Razorpay")?>?</label>
  <?php 
  echo CHtml::checkBox('admin_rzr_enabled',
  getOptionA('admin_rzr_enabled')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('admin_rzr_mode',
  $paymode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo Yii::t("default","Sandbox")?>
  <?php 
  echo CHtml::radioButton('admin_rzr_mode',
  $paymode=="production"?true:false
  ,array(
    'value'=>"production",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo Yii::t("default","live")?> 
</div>

<h3><?php echo Yii::t("default","Sandbox")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Key ID")?></label>
  <?php 
  echo CHtml::textField('admin_razor_key_id_sanbox',
  Yii::app()->functions->getOptionAdmin('admin_razor_key_id_sanbox')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Key Secret")?></label>
  <?php 
  echo CHtml::textField('admin_razor_secret_key_sanbox',
  Yii::app()->functions->getOptionAdmin('admin_razor_secret_key_sanbox')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>



<h3><?php echo Yii::t("default","Live")?></h3>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Key ID")?></label>
  <?php 
  echo CHtml::textField('admin_razor_key_id_live',
  Yii::app()->functions->getOptionAdmin('admin_razor_key_id_live')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Key Secret")?></label>
  <?php 
  echo CHtml::textField('admin_razor_secret_key_live',
  Yii::app()->functions->getOptionAdmin('admin_razor_secret_key_live')
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