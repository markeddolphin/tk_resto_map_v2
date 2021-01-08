<?php
$merchant_id=Yii::app()->functions->getMerchantID();
$enabled=Yii::app()->functions->getOption('merchant_paysera_enabled',$merchant_id);
$paymode=Yii::app()->functions->getOption('merchant_paysera_mode',$merchant_id);
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','merchantPayseraSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled paysera")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_paysera_enabled',
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
  echo CHtml::radioButton('merchant_paysera_mode',
  $paymode==1?true:false
  ,array(
    'value'=>"1",
    'class'=>"icheck"
  ))
  ?>
  <?php echo Yii::t("default","Sandbox")?>
  <?php 
  echo CHtml::radioButton('merchant_paysera_mode',
  $paymode==2?true:false
  ,array(
    'value'=>"2",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo Yii::t("default","live")?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","project id")?></label>
  <?php 
  echo CHtml::textField('merchant_paysera_project_id',
  Yii::app()->functions->getOption('merchant_paysera_project_id',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","sign password")?></label>
  <?php 
  echo CHtml::textField('merchant_paysera_password',
  Yii::app()->functions->getOption('merchant_paysera_password',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Country")?></label>
  <?php 
  echo CHtml::textField('merchant_paysera_country',
  Yii::app()->functions->getOption('merchant_paysera_country',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large",
    'placeholder'=>t("eg. LT")
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Language")?></label>
  <?php 
  echo CHtml::textField('merchant_paysera_lang',
  Yii::app()->functions->getOption('merchant_paysera_lang',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large",
    'placeholder'=>t("default is ENG for english")
  ))
  ?>
</div>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>