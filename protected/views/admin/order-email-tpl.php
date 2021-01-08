<form class="uk-form uk-form-horizontal admin-settings-page forms" id="forms">
<?php echo CHtml::hiddenField('action','OrderTemplate')?>

<div class="uk-form-row">
  <h3><?php echo t("Email template that will send to customer every time the merchant change the status")?></h3>
  


<div class="uk-form-row">
<label class="uk-form-label"><?php echo t("Auto send email once the merchant change the order status")?>?</label>
<?php echo CHtml::checkBox('order_tpl_enabled',
getOptionA('order_tpl_enabled')==2?true:false
,array(
  'class'=>"icheck",
  'value'=>2
))?>
</div>
  
  <?php 
  echo CHtml::textArea('email_tpl_activation',
  $email_tpl_activation,
  array(
    'class'=>"big-textarea"    
  ))
  ?> 
</div>

<p style="margin:0;"><?php echo t("Available Tags")?>:</p>
<ul>
 <li><?php echo t("{restaurant-name}")?></li>
 <li><?php echo t("{customer-name}")?></li>
 <li><?php echo t("{order-number}")?></li>
 <li><?php echo t("{order-status}")?></li>
</ul>

<hr/>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>