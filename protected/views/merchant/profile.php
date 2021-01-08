
<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','updateMerchantUserProfile')?>
<?php echo CHtml::hiddenField('merchant_user_id',$data['merchant_user_id'])?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","First Name")?></label>
  <?php echo CHtml::textField('first_name',
  isset($data['first_name'])?stripslashes($data['first_name']):""
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Last Name")?></label>
  <?php echo CHtml::textField('last_name',
  isset($data['last_name'])?stripslashes($data['last_name']):""
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Email address")?></label>
  <?php echo CHtml::textField('contact_email',
  isset($data['last_name'])?stripslashes($data['contact_email']):""
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Username")?></label>
  <?php echo CHtml::textField('username',
  isset($data['username'])?stripslashes($data['username']):""
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Password")?></label>
  <?php echo CHtml::passwordField('password',
  ''
  ,array(
  'class'=>'uk-form-width-large',
  //'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>