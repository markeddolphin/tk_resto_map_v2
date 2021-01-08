<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/user/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/user" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<div class="spacer"></div>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','addMerchantUser')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>
<?php if (!isset($_GET['id'])):?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/merchant/user/Do/Add")?>
<?php endif;?>

<?php 
$user_access='';
if (isset($_GET['id'])){
	if (!$data=Yii::app()->functions->getMerchantUserInfo($_GET['id'])){
		echo "<div class=\"uk-alert uk-alert-danger\">".
		Yii::t("default","Sorry but we cannot find what your are looking for.")."</div>";
		return ;
	} else $user_access=json_decode($data['user_access'],true);
}
?>                                 

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
  <label class="uk-form-label">Status</label>
  <?php echo CHtml::dropDownList('status',
  isset($data['status'])?$data['status']:"",
  (array)Yii::app()->functions->UserStatus(),          
  array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","User Access")?></label> 
</div>
<a href="javascript:;" class="select_all"><?php echo Yii::t("default","Select All")?></a>
<a href="javascript:;" class="unselect_all"><?php echo Yii::t("default","UnSelect All")?></a>
<ul class="user-access-list">
  <li>
  <?php     
  echo CHtml::checkBox('access[]',
  in_array('can_published_merchant',(array)$user_access)?true:false
  ,array(
    'value'=>'can_published_merchant',
    'class'=>"icheck access"
  ));
  echo "&nbsp;&nbsp;". t("Published Merchant");
  ?>
  </li>
  <?php     
  foreach ($menu['items'] as $val) {    	 
  	 if(!isset($val['tag'])){
  	 	$val['tag']='';
  	 }
  	 ?>
  	 <?php if ( $val['tag']!="logout" && count($val)>1):?>  	 
  	 <li>  	 
  	 <?php echo CHtml::checkBox('access[]',
  	 in_array($val['tag'],(array)$user_access)?true:false
  	 ,array('value'=>$val['tag'],'class'=>"access icheck"))?>&nbsp;&nbsp;<?php echo $val['label']?>
  	 <?php if(isset($val['items'])):?>
  	   <?php if (is_array($val['items']) && count($val['items'])>=1):?>
  	   <ul>
  	    <?php foreach ($val['items'] as $vals):?>
  	    <li>
  	      <?php echo CHtml::checkBox('access[]',
  	      in_array($vals['tag'],(array)$user_access)?true:false
  	      ,array('value'=>$vals['tag'],'class'=>"access icheck"))?>&nbsp;&nbsp;<?php echo $vals['label']?>
  	    </li>
  	    <?php endforeach;?>
  	   </ul>
  	   <?php endif;?>
  	   <?php endif;?>
  	 </li>
  	 <?php endif;?>  	 
  	 <?php
  }
  ?>
  </ul>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>