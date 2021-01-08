<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','FaxSettings')?>

<h3><?php echo t("Fax Settings")?></h3>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo t("Enabled Fax Services from merchant")?>:</label>  
<?php echo CHtml::checkBox('fax_enabled',
yii::app()->functions->getOptionAdmin('fax_enabled')==2?true:false,
array(
 'class'=>"icheck",
 'value'=>2
))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo t("Use admin Fax credits to send Fax on merchant")?>:</label>  
<?php echo CHtml::checkBox('fax_user_admin_credit',
yii::app()->functions->getOptionAdmin('fax_user_admin_credit')==2?true:false,
array(
 'class'=>"icheck",
 'value'=>2
))?>
</div>

<h3><?php echo t("Faxage Account")?></h3>
<p class="uk-text-muted uk-text-small">
<?php echo t("Get your faxage.com account on")?>  http://www.faxage.com
</p>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo t("Company")?>:</label>  
<?php 
echo CHtml::textField('fax_company',yii::app()->functions->getOptionAdmin('fax_company'),array(
 'class'=>"uk-form-width-large"
))
?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo t("Username")?>:</label>  
<?php 
echo CHtml::textField('fax_username',yii::app()->functions->getOptionAdmin('fax_username'),array(
 'class'=>"uk-form-width-large"
))
?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo t("Password")?>:</label>  
<?php 
echo CHtml::passwordField('fax_password',yii::app()->functions->getOptionAdmin('fax_password'),array(
 'class'=>"uk-form-width-large"
))
?>
</div>

<h3><?php echo t("Notificaton")?></h3>


<div class="uk-form-row">
<label class="uk-form-label"><?php echo t("Email address")?>:</label>  
<?php 
echo CHtml::textField('fax_email_notification',yii::app()->functions->getOptionAdmin('fax_email_notification'),array(
 'class'=>"uk-form-width-large"
))
?>
</div>
<p class="uk-text-muted"><?php echo t("Email Address that will receive notification when there is new payment")?></p>

<hr></hr>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>


</form>