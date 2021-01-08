
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/smsBroadcast/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/smsBroadcast" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>
<?php 
$merchant_id=Yii::app()->functions->getMerchantID();
$total_customer=Yii::app()->functions->getAllCustomerCount();
$total_customer_by_merchant=Yii::app()->functions->getAllClientsByMerchant($merchant_id);
//$available_credit=Yii::app()->functions->getMerchantSMSCredit($merchant_id);

/*dump($available_credit);
dump($total_customer);
dump($total_customer_by_merchant);*/
?>

<form class="uk-form uk-form-horizontal forms" id="frm-smsbroadcast">
<?php echo CHtml::hiddenField('action','SMSCreateBroadcast')?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/merchant/smsBroadcast/")?>
<?php
echo CHtml::hiddenField('total_customer',$total_customer);
echo CHtml::hiddenField('total_customer_by_merchant',$total_customer_by_merchant);
?>


<h2><?php echo Yii::t("default","Send SMS Offers to customer by sending Bulk SMS")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Send to All Customer")?></label>
  <?php 
  echo CHtml::radioButton('send_to',
  true  
  ,array('value'=>1,'class'=>"icheck send_to"))
  ?>
</div>
<p class="uk-text-muted">
<?php echo Yii::t("default","This options will send SMS to all available clients on the database");?>
</p>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Send to Customer Who already buy your products")?></label>
  <?php 
  echo CHtml::radioButton('send_to',
  false
  ,array('value'=>2,'class'=>"icheck send_to"))
  ?>
</div>
<p class="uk-text-muted"><?php echo Yii::t("default","This options will send SMS to all customer who already purchase your product.")?></p>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Send to specific mobile numbers")?></label>
  <?php 
  echo CHtml::radioButton('send_to',
  false
  ,array('value'=>3,'class'=>"icheck send_to"))
  ?>
</div>

<div class="uk-form-row custom_wrap_mobile" style="display:none;">
<p style="margin:0;" class="uk-text-muted"><?php echo Yii::t("default","This options will send SMS to specific mobile number")?></p>
<p  style="margin:0;" class="uk-text-muted"><?php echo Yii::t("default","Mobile number must be separated by comma")?></p>

  <label class="uk-form-label"><?php echo Yii::t("default","List of mobile number")?></label>
  <?php 
  echo CHtml::textArea('list_mobile_number','',array(
    'class'=>"uk-form-width-large",
    'style'=>"height:150px",
    //"data-validation"=>"required"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","SMS Message")?></label>
  <?php 
  echo CHtml::textArea('sms_alert_message','',array(
    'class'=>"uk-form-width-large",
    'style'=>"height:150px",
    "data-validation"=>"required"
  ))
  ?>
</div>
<p style="text-indent:200px;"><?php echo Yii::t("default","SMS")?> (<span id="maxlength">160</span> <?php echo Yii::t("default","characters left")?>)</p>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Send SMS")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>