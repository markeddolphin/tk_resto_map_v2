<?php $merchant_id=Yii::app()->functions->getMerchantID();?>
<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','receiptSettings')?>


<h2 class="uk-h2"><?php echo t("Email template sents to customer")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender")?></label>
  <?php echo CHtml::textField('receipt_sender',
  Yii::app()->functions->getOption("receipt_sender",$merchant_id)
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"email"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Subject")?></label>
  <?php echo CHtml::textField('receipt_subject',
  Yii::app()->functions->getOption("receipt_subject",$merchant_id)
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>

<?php 
$tpl=Yii::app()->functions->getOption("receipt_content",$merchant_id);
if (empty($tpl)){
	$tpl=EmailTPL::receiptTPL();
}
?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Email Content")?></label>
  <?php echo CHtml::textArea('receipt_content',
  $tpl
  ,array(
  'class'=>'uk-form-width-large big-textarea',
  'data-validation'=>"required",
  //"style"=>"height:250px;"
  ))?>
</div>

<h4 style="margin:0;"><?php echo Yii::t("default","Available Tags")?></h4>
<p class="uk-text-muted"><?php echo Yii::t("default","{customer-name} customer name")?><br/>
<?php echo Yii::t("default","{receipt-number} receipt/Refference number")?><br/>
<?php echo Yii::t("default","{receipt} Printed Receipt")?>
</p>

<hr></hr>

<?php 
/*$tpl2=Yii::app()->functions->getOption("merchant_receipt_content",$merchant_id);
if (empty($tpl2)){	
	$tpl2=EmailTPL::receiptMerchantTPL();
}*/
$tpl2=Yii::app()->functions->getMerchantReceiptTemplate($merchant_id);
?>

<h2 class="uk-h2"><?php echo t("Email template sents to merchant")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Subject")?></label>
  <?php echo CHtml::textField('merchant_receipt_subject',
  Yii::app()->functions->getOption("merchant_receipt_subject",$merchant_id)
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Email Content")?></label>
  <?php echo CHtml::textArea('merchant_receipt_content',
  $tpl2
  ,array(
  'class'=>'uk-form-width-large big-textarea',
  'data-validation'=>"required",  
  ))?>
</div>

<h4 style="margin:0;"><?php echo Yii::t("default","Available Tags")?></h4>
<p class="uk-text-muted"><?php echo Yii::t("default","{customer-name} customer name")?><br/>
<?php echo Yii::t("default","{receipt-number} receipt/Refference number")?><br/>
<?php echo Yii::t("default","{receipt} Printed Receipt")?><br/>
<?php echo t("{confirmation-link}")?>
</p>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>