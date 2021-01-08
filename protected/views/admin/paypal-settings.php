<?php
$enabled_paypal=Yii::app()->functions->getOptionAdmin('admin_enabled_paypal');
$paypal_mode=Yii::app()->functions->getOptionAdmin('admin_paypal_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','saveAdminPaypalSettings')?>

<!--<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Paypal?")?></label>
  <?php 
  echo CHtml::checkBox('admin_enabled_paypal',
  $enabled_paypal=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>-->
  
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Paypal")?>?</label>
  <?php 
  echo CHtml::checkBox('admin_enabled_paypal',
  Yii::app()->functions->getOptionAdmin('admin_enabled_paypal')=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('admin_paypal_mode',
  $paypal_mode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?> 
  <?php 
  echo CHtml::radioButton('admin_paypal_mode',
  $paypal_mode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo t("Live")?> 
</div>


<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Card Fee")?></label>
<?php 
echo CHtml::textField('admin_paypal_fee',
Yii::app()->functions->getOptionAdmin('admin_paypal_fee')
,array(
'class'=>"uk-form-width-small numeric_only"
))
?>
</div>


<h3><?php echo Yii::t("default","Sandbox")?></h3>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal User")?></label>
  <?php 
  echo CHtml::textField('admin_sanbox_paypal_user',
  Yii::app()->functions->getOptionAdmin('admin_sanbox_paypal_user')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal Password")?></label>
  <?php 
  echo CHtml::textField('admin_sanbox_paypal_pass',
  Yii::app()->functions->getOptionAdmin('admin_sanbox_paypal_pass')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal Signature")?></label>
  <?php 
  echo CHtml::textField('admin_sanbox_paypal_signature',
  Yii::app()->functions->getOptionAdmin('admin_sanbox_paypal_signature')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>



<h3><?php echo Yii::t("default","Live")?></h3>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal User")?></label>
  <?php 
  echo CHtml::textField('admin_live_paypal_user',
  Yii::app()->functions->getOptionAdmin('admin_live_paypal_user')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal Password")?></label>
  <?php 
  echo CHtml::textField('admin_live_paypal_pass',
  Yii::app()->functions->getOptionAdmin('admin_live_paypal_pass')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal Signature")?></label>
  <?php 
  echo CHtml::textField('admin_live_paypal_signature',
  Yii::app()->functions->getOptionAdmin('admin_live_paypal_signature')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>


<!--<hr>
<h3><?php echo t("Mobile Paypal payment Settings")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Paypal")?>?</label>
  <?php 
  echo CHtml::checkBox('adm_paypal_mobile_enabled',
  getOptionA('adm_paypal_mobile_enabled')=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('adm_paypal_mobile_mode',
  getOptionA('adm_paypal_mobile_mode')=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?>
  <?php 
  echo CHtml::radioButton('adm_paypal_mobile_mode',
  getOptionA('adm_paypal_mobile_mode')=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo t("Live")?> 
</div>
-->

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Client ID")?></label>
  <?php 
  echo CHtml::textField('adm_paypal_mobile_clientid',
  getOptionA('adm_paypal_mobile_clientid')
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