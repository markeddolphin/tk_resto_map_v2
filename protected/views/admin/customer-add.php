
<div class="uk-width-1">
<!--<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customPage/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>-->
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customerlist" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>

</div>

<?php 
if (isset($_GET['id'])){
	if (!$data=Yii::app()->functions->getClientInfo($_GET['id'])){
		echo "<div class=\"uk-alert uk-alert-danger\">".
		Yii::t("default","Sorry but we cannot find what your are looking for.")."</div>";
		return ;
	}
}
?>                                   

<div class="spacer"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','customerAdd')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>
<?php if (!isset($_GET['id'])):?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/admin/customerlist/Do/Add")?>
<?php endif;?>
<?php 
$p = new CHtmlPurifier();
FunctionsV3::addCsrfToken();
?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","First Name")?></label>
  <?php 
  echo CHtml::textField('first_name',
  isset($data['first_name'])?$p->purify($data['first_name']):""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Last Name")?></label>
  <?php 
  echo CHtml::textField('last_name',
  isset($data['first_name'])?$p->purify($data['last_name']):""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Email address")?></label>
  <?php 
  echo CHtml::textField('email_address',
  isset($data['email_address'])?$p->purify($data['email_address']):""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<?php 
$FunctionsK=new FunctionsK();
$FunctionsK->clientRegistrationCustomFields(true,$data,2);
?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Password")?></label>
  <?php 
  echo CHtml::passwordField('password',
  ''
  ,array('class'=>"uk-form-width-large"))
  ?>
</div>

<!--<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Street Address")?></label>
  <?php 
  echo CHtml::textField('street',
  isset($data['street'])?$p->purify($data['street']):""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","City")?></label>
  <?php 
  echo CHtml::textField('city',
  isset($data['city'])?$p->purify($data['city']):""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","State")?></label>
  <?php 
  echo CHtml::textField('state',
  isset($data['state'])?$p->purify($data['state']):""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","ZipCode")?></label>
  <?php 
  echo CHtml::textField('zipcode',
  isset($data['zipcode'])?$p->purify($data['zipcode']):""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>
-->
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Status")?></label>
  <?php 
  echo CHtml::dropDownList('status',$data['status'],
  clientStatus(),
  array('class'=>'uk-form-width-large')
  )
  ?>
</div>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>