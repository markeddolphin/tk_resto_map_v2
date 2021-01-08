<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','FaxMerchantSettings')?>

<?php $merchant_id=Yii::app()->functions->getMerchantID();?>
<h3><?php echo t("Fax Settings")?></h3>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo t("Enabled")?>:</label>  
<?php 
echo CHtml::checkbox('fax_merchant_enabled',
yii::app()->functions->getOption('fax_merchant_enabled',$merchant_id)==2?true:false
,array(
 'class'=>"icheck",
 'value'=>2
))
?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo t("Recipient Name")?>:</label>  
<?php 
echo CHtml::textField('fax_merchant_recipient',yii::app()->functions->getOption('fax_merchant_recipient',$merchant_id),array(
 'class'=>"uk-form-width-large",
 'data-validation'=>"required"
))
?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo t("Fax Number")?>:</label>  
<?php 
echo CHtml::textField('fax_merchant_number',yii::app()->functions->getOption('fax_merchant_number',$merchant_id),array(
 'class'=>"uk-form-width-large",
 'data-validation'=>"required"
))
?>
</div>
<p class="uk-text-muted uk-text-small"><?php echo t("Fax number that will receive the fax")?></p>


<hr></hr>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>


</form>