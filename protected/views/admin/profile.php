<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','adminProfile')?>
<?php 
FunctionsV3::addCsrfToken();
$p = new CHtmlPurifier();
?>

<h2><?php echo Yii::t('default',"Profile")?></h2>

<?php $admin_id=Yii::app()->functions->getAdminId(); ?>
<?php if ( $res=Yii::app()->functions->getAdminUserInfo($admin_id)): ?>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Username")?></label>  
<p class="uk-text-mutedt"><?php echo $p->purify($res['username'])?></p>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"First Name")?></label>  
<?php echo CHtml::textField("first_name", $p->purify($res['first_name']),array(
 'class'=>"uk-form-width-large",
 'data-validation'=>"required"
))?>
</div>


<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Last Name")?></label>  
<?php echo CHtml::textField("last_name", $p->purify($res['last_name']),array(
 'class'=>"uk-form-width-large",
 'data-validation'=>"required"
))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Email address")?></label>  
<?php echo CHtml::textField("email_address", $p->purify($res['email_address']),array(
 'class'=>"uk-form-width-large",
 'data-validation'=>"required"
))?>
</div>




<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"New Password")?></label>  
<?php echo CHtml::passwordField("password",'',array(
 'class'=>"uk-form-width-large",
 'autocomplete'=>"new-password"
))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Confirm New Password")?></label>  
<?php echo CHtml::passwordField("cpassword",'',array(
 'class'=>"uk-form-width-large",
 'autocomplete'=>"new-password"
))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

<?php else :?>
<p class="uk-text-danger"><?php echo Yii::t("default","Profile not available")?></p>
<?php endif;?>

</form>