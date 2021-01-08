
<h3><?php echo t("Notification Settings")?></h3>

<form id="newforms" class="uk-form uk-form-horizontal" method="POST" onsubmit="return false;">
<?php 
echo CHtml::hiddenField('action','notiSettings');
FunctionsV3::addCsrfToken(false);
?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("MERCHANT_NEW_SIGNUP")." ".t("EMAIL")?></label>
  <?php 
  echo CHtml::textField('noti_new_signup_email',
  getOptionA('noti_new_signup_email')
  ,array(
    'class'=>"uk-form-width-large",
    'placeholder'=>t("Email address")
  ))
  ?>
  <br/>
  <span class="uk-text-muted uk-text-small">
  <?php echo t("Email address to notify if there is new merchant signup")?>.
  <?php echo t("multiple email address must be separated by comma")?>
  </span>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("MERCHANT_NEW_SIGNUP")." ".t("SMS")?></label>
  <?php 
  echo CHtml::textField('noti_new_signup_sms',
  getOptionA('noti_new_signup_sms')
  ,array(
    'class'=>"uk-form-width-large",
    'placeholder'=>t("Mobile number")
  ))
  ?>
  <br/>
  <span class="uk-text-muted uk-text-small">
  <?php echo t("mobile number to notify if there is new merchant signup")?>.
  <?php echo t("multiple mobile must be separated by comma")?>
  </span>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("RECEIPT_SEND_TO_ADMIN")." ".t("EMAIL")?></label>
  <?php 
  echo CHtml::textField('noti_receipt_email',
  getOptionA('noti_receipt_email')
  ,array(
    'class'=>"uk-form-width-large",
    'placeholder'=>t("Email address")
  ))
  ?><br/>
  <span class="uk-text-muted uk-text-small">
  <?php echo t("Email address to notify if there is new order")?>.
  <?php echo t("multiple email address must be separated by comma")?>
  </span>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("RECEIPT_SEND_TO_ADMIN")." ".t("SMS")?></label>
  <?php 
  echo CHtml::textField('noti_receipt_sms',
  getOptionA('noti_receipt_sms')
  ,array(
    'class'=>"uk-form-width-large",
    'placeholder'=>t("Mobile number")
  ))
  ?><br/>
  <span class="uk-text-muted uk-text-small">
  <?php echo t("mobile number to notify if there is new order")?>.
  <?php echo t("multiple mobile must be separated by comma")?>
  </span>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("BOOKED_NOTIFY_ADMIN")." ".t("EMAIL")?></label>
  <?php 
  echo CHtml::textField('noti_booked_admin_email',
  getOptionA('noti_booked_admin_email')
  ,array(
    'class'=>"uk-form-width-large",
    'placeholder'=>t("Email address")
  ))
  ?>
  <br/>
  <span class="uk-text-muted uk-text-small">
  <?php echo t("Email address to notify if there is new booking")?>.
  <?php echo t("multiple email address must be separated by comma")?>
  </span>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("ORDER_IDLE_TO_ADMIN")." ".t("EMAIL")?></label>
  <?php 
  echo CHtml::textField('order_idle_admin_email',
  getOptionA('order_idle_admin_email')
  ,array(
    'class'=>"uk-form-width-large",
    'placeholder'=>t("Email address")
  ))
  ?>
  <br/>
  <span class="uk-text-muted uk-text-small">
  <?php echo t("Email address to notify if the order is not accepted for a period of time")?>.
  <?php echo t("multiple email address must be separated by comma")?>
  </span>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("ORDER_REQUEST_CANCEL_TO_ADMIN")." ".t("EMAIL")?></label>
  <?php 
  echo CHtml::textField('order_cancel_admin_email',
  getOptionA('order_cancel_admin_email')
  ,array(
    'class'=>"uk-form-width-large",
    'placeholder'=>t("Email address")
  ))
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("ORDER_REQUEST_CANCEL_TO_ADMIN")." ".t("SMS")?></label>
  <?php 
  echo CHtml::textField('order_cancel_admin_sms',
  getOptionA('order_cancel_admin_sms')
  ,array(
    'class'=>"uk-form-width-large",
    'placeholder'=>t("Mobile number")
  ))
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Order Idle minutes")?></label>
  <?php 
  echo CHtml::textField('order_idle_admin_minutes',
  getOptionA('order_idle_admin_minutes')
  ,array(
    'class'=>"numeric_only",
    'placeholder'=>t("Default is 5mins")
  ))
  ?>  
  <span><?php echo t("Minutes")?></span>
  <br/>
  <span class="uk-text-muted uk-text-small" style="padding-left:200px;">
  <?php echo t("Number of minutes to send notification to admin when the order is idle")?>
  </span>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled New Order Notification")?></label>
  <?php 
  echo CHtml::checkbox('admin_disabled_order_notification',
  getOptionA('admin_disabled_order_notification')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled New Order Notification Sounds")?></label>
  <?php 
  echo CHtml::checkbox('admin_disabled_order_notification_sounds',
  getOptionA('admin_disabled_order_notification_sounds')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Days Before Expiration")?></label>
  <?php 
  echo CHtml::textField('merchant_near_expiration_day',
  getOptionA('merchant_near_expiration_day'),array(
    'class'=>"numeric_only"
  ));  
  ?><br/>
  <span class="uk-text-muted uk-text-small" style="padding-left:200px;">
  <?php echo t("no. of days to inform merchant that there membership is about to expire");?>
  </span>
</div>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>