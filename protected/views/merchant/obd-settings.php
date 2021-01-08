<?php
$merchant_id=Yii::app()->functions->getMerchantID();
$enabled=Yii::app()->functions->getOption('merchant_bankdeposit_enabled',$merchant_id);
$deposit_instructions=Yii::app()->functions->getOption('merchant_deposit_instructions',$merchant_id);
if (empty($deposit_instructions)){
	$deposit_instructions=EmailTPL::bankDepositTPL();
}
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','merchantBankDeposit')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Offline Bank Deposit")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_bankdeposit_enabled',
  $enabled=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row" style="display:none;">
  <label class="uk-form-label"><?php echo Yii::t("default","Email Sender")?></label>
  <?php   
  echo CHtml::textField('merchant_deposit_sender',
  Yii::app()->functions->getOption('merchant_deposit_sender',$merchant_id),array(
  'class'=>'uk-form-width-large'
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Subject")?></label>
  <?php   
  echo CHtml::textField('merchant_deposit_subject',
  Yii::app()->functions->getOption('merchant_deposit_subject',$merchant_id),array(
  'class'=>'uk-form-width-large','placeholder'=>t("Bank Deposit instructions")
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Bank Deposit instructions")?></label>
  <?php 
  echo CHtml::textArea('merchant_deposit_instructions',$deposit_instructions,array(
    'class'=>'big-textarea'
  ));
  ?>
</div>

<p><?php echo Yii::t("default",'Available Tags')?>:</p>
<p class="uk-text-muted">{amount}<br/>
{verify-payment-link}</p>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>