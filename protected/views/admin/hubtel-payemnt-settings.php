
<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','AdminHubtelPaymentSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("Hubtel Payments")?>?</label>
  <?php 
  echo CHtml::checkBox('admin_hubtel_enabled',
  getOptionA('admin_hubtel_enabled')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Client ID")?></label>
  <?php 
  echo CHtml::textField('admin_hubtel_client_id',
  Yii::app()->functions->getOptionAdmin('admin_hubtel_client_id')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Client Secret")?></label>
  <?php 
  echo CHtml::textField('admin_hubtel_client_secret',
  Yii::app()->functions->getOptionAdmin('admin_hubtel_client_secret')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant Account No.")?></label>
  <?php 
  echo CHtml::textField('admin_hubtel_accountno',
  Yii::app()->functions->getOptionAdmin('admin_hubtel_accountno')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Channel")?></label>
  <?php 
  echo CHtml::dropDownList('admin_hubtel_channel',
  getOptionA('admin_hubtel_channel')
  ,HubtelPayments::channelList());
  ?>
</div>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>