

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','contactSettings')?>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t('default',"Contact Content")?></label>  
  <?php echo CHtml::textArea("contact_content",yii::app()->functions->getOptionAdmin('contact_content'),
array(
'id'=>'contact_content',
'class'=>"uk-form-width-large",
))?>
</div>

<h2><?php echo Yii::t('default',"Map")?></h2>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Display Google Map")?></label>  
<?php echo CHtml::checkBox('contact_map',yii::app()->functions->getOptionAdmin('contact_map'),array(
'class'=>"icheck"))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Latitude")?></label>  
<?php echo CHtml::textField("map_latitude",yii::app()->functions->getOptionAdmin('map_latitude'))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Longitude")?></label>  
<?php echo CHtml::textField("map_longitude",yii::app()->functions->getOptionAdmin('map_longitude'))?>
</div>

<?php 
$fields=yii::app()->functions->getOptionAdmin('contact_field');
if (!empty($fields)){
	$fields=json_decode($fields);
}
?>

<h2><?php echo Yii::t("default",'Contact Fields')?></h2>
<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Name")?></label>  
<?php echo CHtml::checkBox('contact_field[]',
 in_array('name',(array)$fields)?true:false,array('value'=>'name','class'=>"icheck"))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Email")?></label>  
<?php echo CHtml::checkBox('contact_field[]',
 in_array('email',(array)$fields)?true:false,array('value'=>'email','class'=>"icheck"))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Phone")?></label>  
<?php echo CHtml::checkBox('contact_field[]',
 in_array('phone',(array)$fields)?true:false,array('value'=>'phone','class'=>"icheck"))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Country")?></label>  
<?php echo CHtml::checkBox('contact_field[]',
 in_array('country',(array)$fields)?true:false,array('value'=>'country','class'=>"icheck"))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Message")?></label>  
<?php echo CHtml::checkBox('contact_field[]',
 in_array('message',(array)$fields)?true:false,array('value'=>'message','class'=>"icheck"))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Send To")?></label>  
<?php echo CHtml::textField("contact_email_receiver",yii::app()->functions->getOptionAdmin('contact_email_receiver'),
array(
'class'=>"uk-form-width-large",
'placeholder'=>Yii::t("default","Email address")
))?>
</div>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>