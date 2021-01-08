
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/faxpackage/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/faxpackage" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/faxpackage/Do/Sort" class="uk-button"><i class="fa fa-sort-alpha-asc"></i> <?php echo Yii::t("default","Sort")?></a>
</div>


<?php 
$FunctionsK=new FunctionsK;
if (isset($_GET['id'])){
	if (!$data=$FunctionsK->getSMSPackagesById($_GET['id'])){
		echo "<div class=\"uk-alert uk-alert-danger\">".
		Yii::t("default","Sorry but we cannot find what your are looking for.")."</div>";
		return ;
	}
}
?>                                   

<div class="spacer"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','FaxPackageAdd')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>
<?php if (!isset($_GET['id'])):?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/admin/faxpackage/Do/Add")?>
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
  <label class="uk-form-label"><?php echo Yii::t("default","Fax Credit Limit")?></label>
  <?php echo CHtml::textField('fax_limit',
  isset($data['fax_limit'])?$data['fax_limit']:""
  ,array(
  'class'=>'uk-form-width-medium numeric_only',
  'data-validation'=>"required"
  ))?>
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