<?php
$enabled=Yii::app()->functions->getOptionAdmin('admin_bankdeposit_enabled');
$deposit_instructions=Yii::app()->functions->getOptionAdmin('admin_deposit_instructions');
if (empty($deposit_instructions)){
	$deposit_instructions=EmailTPL::bankDepositTPL();
}
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','adminBankDeposit')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Offline Bank Deposit")?>?</label>
  <?php 
  echo CHtml::checkBox('admin_bankdeposit_enabled',
  $enabled=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>