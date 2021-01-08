<?php 
$merchant_id=Yii::app()->functions->getMerchantID();
$sms_enabled_alert=Yii::app()->functions->getOption("sms_enabled_alert",$merchant_id);
$sms_notify_number=Yii::app()->functions->getOption("sms_notify_number",$merchant_id);
$sms_alert_message=Yii::app()->functions->getOption("sms_alert_message",$merchant_id);
$sms_alert_customer=Yii::app()->functions->getOption("sms_alert_customer",$merchant_id);
$merchant_info=Yii::app()->functions->getMerchantInfo();
?>
<?php echo CHtml::hiddenField('country_code',$merchant_info[0]->country_code)?>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','SMSAlertSettings')?>


<!--<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled SMS alert")?>?</label>
  <?php 
  /*echo CHtml::checkBox('sms_enabled_alert',
  $sms_enabled_alert==1?true:false
  ,array('value'=>1,'class'=>"icheck"))*/
  ?>
</div>-->

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Notify Mobile Number")?></label>
  <?php 
  echo CHtml::textField('sms_notify_number',$sms_notify_number,array(
    'class'=>"uk-form-width-large "
  ));
  //mobile_inputs
  ?>
</div>




<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>