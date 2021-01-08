<?php
$merchant_id=Yii::app()->functions->getMerchantID();
$enabled_paypal=Yii::app()->functions->getOption('merchant_enabled_barclay',$merchant_id);
$paypal_mode=Yii::app()->functions->getOption('merchant_mode_barclay',$merchant_id);
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','saveMerchantBarclaySettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_enabled_barclay',
  $enabled_paypal=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('merchant_mode_barclay',
  $paypal_mode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?> 
  <?php 
  echo CHtml::radioButton('merchant_mode_barclay',
  $paypal_mode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo t("Live")?> 
</div>

<h3><?php echo Yii::t("default","Sandbox")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","PSPID")?></label>
  <?php 
  echo CHtml::textField('merchant_sandbox_barclay_pspid',
  Yii::app()->functions->getOption('merchant_sandbox_barclay_pspid',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Password")?></label>
  <?php 
  echo CHtml::textField('merchant_sandbox_barclay_password',
  Yii::app()->functions->getOption('merchant_sandbox_barclay_password',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>



<h3><?php echo Yii::t("default","Live")?></h3>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","PSPID")?></label>
  <?php 
  echo CHtml::textField('merchant_live_barclay_pspid',
  Yii::app()->functions->getOption('merchant_live_barclay_pspid',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Password")?></label>
  <?php 
  echo CHtml::textField('merchant_live_barclay_password',
  Yii::app()->functions->getOption('merchant_live_barclay_password',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<h3><?php echo Yii::t("default","Parameters")?></h3>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Currency")?></label>
  <?php 
  echo CHtml::textField('merchant_bcy_currency',
  Yii::app()->functions->getOption('merchant_bcy_currency',$merchant_id)
  ,array(
    'class'=>"uk-form-width-small"
  ))
  ?>
  <span class="uk-text-muted"><?php echo t("Example GBP")?></span>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Language")?></label>
  <?php 
  echo CHtml::textField('merchant_bcy_language',
  Yii::app()->functions->getOption('merchant_bcy_language',$merchant_id)
  ,array(
    'class'=>"uk-form-width-small"
  ))
  ?>
  <span class="uk-text-muted"><?php echo t("Example en_GB")?></span>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Font Type")?></label>
  <?php 
  echo CHtml::textField('merchant_bcy_font',
  Yii::app()->functions->getOption('merchant_bcy_font',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Logo URL Or Filename")?></label>
  <?php 
  echo CHtml::textField('merchant_bcy_logo',
  Yii::app()->functions->getOption('merchant_bcy_logo',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","PMLISTTYPE")?></label>
  <?php 
  echo CHtml::textField('merchant_bcy_listype',
  Yii::app()->functions->getOption('merchant_bcy_listype',$merchant_id)
  ,array(
    'class'=>"uk-form-width-small"
  ))
  ?>
  <span class="uk-text-muted"><?php echo t("value = 1")?></span>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","BG Color")?></label>
  <?php 
  echo CHtml::textField('merchant_bcy_bgcolor',
  Yii::app()->functions->getOption('merchant_bcy_bgcolor',$merchant_id)
  ,array(
    'class'=>"uk-form-width-small"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Button Color")?></label>
  <?php 
  echo CHtml::textField('merchant_bcy_buttoncolor',
  Yii::app()->functions->getOption('merchant_bcy_buttoncolor',$merchant_id)
  ,array(
    'class'=>"uk-form-width-small"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Button Text Color")?></label>
  <?php 
  echo CHtml::textField('merchant_bcy_buttontextcolor',
  Yii::app()->functions->getOption('merchant_bcy_buttontextcolor',$merchant_id)
  ,array(
    'class'=>"uk-form-width-small"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Table BG Color")?></label>
  <?php 
  echo CHtml::textField('merchant_bcy_table_bgcolor',
  Yii::app()->functions->getOption('merchant_bcy_table_bgcolor',$merchant_id)
  ,array(
    'class'=>"uk-form-width-small"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Table Text Color")?></label>
  <?php 
  echo CHtml::textField('merchant_bcy_table_textcolor',
  Yii::app()->functions->getOption('merchant_bcy_table_textcolor',$merchant_id)
  ,array(
    'class'=>"uk-form-width-small"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Title")?></label>
  <?php 
  echo CHtml::textField('merchant_bcy_title',
  Yii::app()->functions->getOption('merchant_bcy_title',$merchant_id)
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Text Color")?></label>
  <?php 
  echo CHtml::textField('merchant_bcy_textcolor',
  Yii::app()->functions->getOption('merchant_bcy_textcolor',$merchant_id)
  ,array(
    'class'=>"uk-form-width-small"
  ))
  ?> 
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>