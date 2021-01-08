<?php
$enabled=Yii::app()->functions->getOptionAdmin('admin_stripe_enabled');
$paymode=Yii::app()->functions->getOptionAdmin('admin_stripe_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','adminStripeSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Stripe")?></label>
  <?php 
  echo CHtml::checkBox('admin_stripe_enabled',
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
  echo CHtml::radioButton('admin_stripe_mode',
  $paymode=="Sandbox"?true:false
  ,array(
    'value'=>"Sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo Yii::t("default","Sandbox")?>
  <?php 
  echo CHtml::radioButton('admin_stripe_mode',
  $paymode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo Yii::t("default","live")?> 
</div>



<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Card fee")?></label>
  <?php 
  echo CHtml::textField('admin_stripe_card_fee',
  Yii::app()->functions->getOptionAdmin('admin_stripe_card_fee')
  ,array(
    'class'=>"numeric_only"
  ))
  ?>
</div>

<h3><?php echo Yii::t("default","Sandbox")?></h3>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Test Secret key")?></label>
  <?php 
  echo CHtml::textField('admin_sanbox_stripe_secret_key',
  Yii::app()->functions->getOptionAdmin('admin_sanbox_stripe_secret_key')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Test Publishable Key")?></label>
  <?php 
  echo CHtml::textField('admin_sandbox_stripe_pub_key',
  Yii::app()->functions->getOptionAdmin('admin_sandbox_stripe_pub_key')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Webhooks Signing secret")?></label>
  <?php 
  echo CHtml::textField('admin_sandbox_stripe_webhooks',
  Yii::app()->functions->getOptionAdmin('admin_sandbox_stripe_webhooks')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<h3><?php echo Yii::t("default","Live")?></h3>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Live Secret key")?></label>
  <?php 
  echo CHtml::textField('admin_live_stripe_secret_key',
  Yii::app()->functions->getOptionAdmin('admin_live_stripe_secret_key')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Live Publishable Key")?></label>
  <?php 
  echo CHtml::textField('admin_live_stripe_pub_key',
  Yii::app()->functions->getOptionAdmin('admin_live_stripe_pub_key')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Webhooks Signing secret")?></label>
  <?php 
  echo CHtml::textField('admin_live_stripe_webhooks',
  Yii::app()->functions->getOptionAdmin('admin_live_stripe_webhooks')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<h3><?php echo t("Webhooks endpoint")?></h3>
<p>
<?php echo Yii::t("default","Your webhook endpoint URL for stripe is [url] and add the event <b>checkout.session.completed</b>",array(
 '[url]'=>"<b>".websiteUrl()."/stripe/webhooks</b>"
))?>
</p>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>