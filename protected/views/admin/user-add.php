
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/userlist/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/userlist" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<?php 
if (isset($_GET['id'])){
	if (!$data=Yii::app()->functions->getAdminUserInfo($_GET['id'])){
		echo "<div class=\"uk-alert uk-alert-danger\">".
		Yii::t("default","Sorry but we cannot find what your are looking for.")."</div>";
		return ;
	}
}
?>                                   

<div class="spacer"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','addAdminUser')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>
<?php if (!isset($_GET['id'])):?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/admin/userlist/Do/Add")?>
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
  isset($data['last_name'])?$p->purify($data['last_name']):""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Email address")?></label>  
<?php echo CHtml::textField("email_address",$p->purify($data['email_address']),array(
 'class'=>"uk-form-width-large",
 'data-validation'=>"required"
))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","User Name")?></label>
  <?php 
  echo CHtml::textField('username',
  isset($data['username'])?$p->purify($data['username']):""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","New Password")?></label>
  <?php 
  echo CHtml::passwordField('password',
  '',array(
     'class'=>"uk-form-width-large",
     'autocomplete'=>"new-password"     
   ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Confirm Password")?></label>
  <?php 
  echo CHtml::passwordField('cpassword',
  '',array('class'=>"uk-form-width-large"))
  ?>
</div>

<h4><?php echo t("User Access")?></h4>
<?php $menu=Yii::app()->functions->adminMenu();
$user_access='';
if (isset($data['user_access'])){
	$user_access=!empty($data['user_access'])?json_decode($data['user_access'],true):'';
}
?>

<a href="javascript:;" class="admin-select-access"><?php echo t("Select All")?></a>
|
<a href="javascript:;" class="admin-unselect-access"><?php echo t("Unselect All")?></a>

<ul class="admin-access-list">
 <li>
  <?php  
 echo CHtml::checkBox('user_access[]',
 in_array('autologin',(array)$user_access)?true:false
 ,array(
   'value'=>'autologin',
   'class'=>"icheck admin-acess"
 )); 
 echo t("Merchant Auto login")?>

 </li>
<?php foreach ($menu['items'] as $val):?>
 <li>
 <?php 
 if ( $val['tag']=="logout"){
 	continue;
 }
 echo CHtml::checkBox('user_access[]',
 in_array($val['tag'],(array)$user_access)?true:false
 ,array(
   'value'=>$val['tag'],
   'class'=>"icheck admin-acess"
 )); 
 echo $val['label']?>
 
 <?php 
 if(!isset($val['items'])){
 	$val['items']='';
 }
 ?>
 <?php if (is_array($val['items']) && count($val['items'])>=1 ):?>
 <ul>
 <?php foreach ($val['items'] as $sub_val):?>
     <li>
     <?php 
	 echo CHtml::checkBox('user_access[]',
	 in_array($sub_val['tag'],(array)$user_access)?true:false
	 ,array(
	   'value'=>$sub_val['tag'],
	   'class'=>"icheck admin-acess"
	 )); 
	 echo $sub_val['label']?>
     </li>
 <?php endforeach;?>
 </ul>
 <?php endif;?>
 
 </li>
<?php endforeach;?>
</ul>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>