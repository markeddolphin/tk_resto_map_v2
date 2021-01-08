<?php
$paymode=Yii::app()->functions->getOptionAdmin('admin_btr_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','adminBrainTreeSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Braintree")?>?</label>
  <?php 
  echo CHtml::checkBox('admin_btr_enabled',
  getOptionA('admin_btr_enabled')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('admin_btr_mode',
  $paymode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo Yii::t("default","Sandbox")?>
  <?php 
  echo CHtml::radioButton('admin_btr_mode',
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
  <label class="uk-form-label"><?php echo t("Merchant ID")?></label>
  <?php 
  echo CHtml::textField('sanbox_brain_mtid',
  Yii::app()->functions->getOptionAdmin('sanbox_brain_mtid')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Public Key")?></label>
  <?php 
  echo CHtml::textField('sanbox_brain_publickey',
  Yii::app()->functions->getOptionAdmin('sanbox_brain_publickey')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Private Key")?></label>
  <?php 
  echo CHtml::textField('sanbox_brain_privateckey',
  Yii::app()->functions->getOptionAdmin('sanbox_brain_privateckey')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>


<h3><?php echo Yii::t("default","Live")?></h3>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant ID")?></label>
  <?php 
  echo CHtml::textField('live_brain_mtid',
  Yii::app()->functions->getOptionAdmin('live_brain_mtid')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Public Key")?></label>
  <?php 
  echo CHtml::textField('live_brain_publickey',
  Yii::app()->functions->getOptionAdmin('live_brain_publickey')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Private Key")?></label>
  <?php 
  echo CHtml::textField('live_brain_privateckey',
  Yii::app()->functions->getOptionAdmin('live_brain_privateckey')
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