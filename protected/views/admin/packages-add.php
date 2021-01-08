<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/packagesAdd" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/packages" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<?php 
if (isset($_GET['id'])){
	if (!$data=Yii::app()->functions->getPackagesById($_GET['id'])){
		echo "<div class=\"uk-alert uk-alert-danger\">".
		Yii::t("default","Sorry but we cannot find what your are looking for.")."</div>";
		return ;
	}
}
?>                                   

<div class="spacer"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','packagesAdd')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>
<?php if (!isset($_GET['id'])):?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/admin/packagesAdd")?>
<?php endif;?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Title")?></label>
  <?php echo CHtml::textField('title',
  isset($data['title'])?$data['title']:""
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Description")?>
  </label>
  <?php echo CHtml::textArea('description',
  isset($data['description'])?$data['description']:""
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Price")?></label>
  <?php echo CHtml::textField('price',
  isset($data['price'])?standardPrettyFormat($data['price']):""
  ,array(
  'class'=>'uk-form-width-medium numeric_only',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Promo Price")?></label>
  <?php echo CHtml::textField('promo_price',
  isset($data['promo_price'])?standardPrettyFormat($data['promo_price']):""
  ,array(
  'class'=>'uk-form-width-medium numeric_only'  
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Type")?></label>
  <?php
  echo CHtml::dropDownList('expiration_type',
  isset($data['expiration_type'])?$data['expiration_type']:'',
  Yii::app()->functions->ExpirationType(),array(
  'data-validation'=>"required"
  ));
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Expiration (no. of days or Year)")?></label>
  <?php echo CHtml::textField('expiration',
  isset($data['expiration'])?$data['expiration']:""
  ,array(
  'class'=>'uk-form-width-medium numeric_only',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Usage")?></label>
  <?php
  echo CHtml::dropDownList('unlimited_post',
  isset($data['unlimited_post'])?$data['unlimited_post']:'',
  Yii::app()->functions->ListlimitedPost(),array(
  'data-validation'=>"required",
  'class'=>"unlimited_post"
  ));
  ?>
</div>

<div class="uk-form-row post_limit_wrap">
  <label class="uk-form-label"><?php echo Yii::t("default","Number of Food Item Can Add")?></label>
  <?php echo CHtml::textField('post_limit',
  isset($data['post_limit'])?$data['post_limit']:""
  ,array(
  'class'=>'uk-form-width-medium numeric_only',
  //'data-validation'=>"required"
  ))?>
</div>

<?php 
$limit_sell=isset($data['sell_limit'])?$data['sell_limit']:"";
$limit_sell=$limit_sell<=0?"":$limit_sell;
?>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Limit merchant by sell")?></label>
  <?php echo CHtml::textField('sell_limit',
  $limit_sell
  ,array(
  'class'=>'uk-form-width-medium numeric_only',
  //'data-validation'=>"required"
  ))?>
  <?php echo Yii::t("default","Per month")?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Status")?></label>
  <?php echo CHtml::dropDownList('status',
  isset($data['status'])?$data['status']:"",
  (array)statusList(),          
  array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>