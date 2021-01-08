
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/zipcode/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/zipcode" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<div class="spacer"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','addZipCode')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>
<?php if (!isset($_GET['id'])):?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/admin/zipcode/Do/Add")?>
<?php endif;?>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","post code")?></label>
  <?php 
  echo CHtml::textField('zipcode',
  isset($data['zipcode'])?$data['zipcode']:""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Country")?></label>
  <?php 
  echo CHtml::dropDownList('country_code',
  isset($data['country_code'])?$data['country_code']:getOptionA('admin_country_set')
  ,(array)Yii::app()->functions->CountryList(),array(
   'class'=>"chosen"
  ));
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Street name")?></label>
  <?php 
  echo CHtml::textField('stree_name',
  isset($data['stree_name'])?$data['stree_name']:""
  ,array('class'=>"uk-form-width-large"))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Area")?></label>
  <?php 
  echo CHtml::textField('area',
  isset($data['area'])?$data['area']:""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","City")?></label>
  <?php 
  echo CHtml::textField('city',
  isset($data['city'])?$data['city']:""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Status")?></label>
<?php echo CHtml::dropDownList('status',
isset($data['status'])?$data['status']:"",
(array)statusList(),          
array(
'class'=>'uk-form-width-medium',
'data-validation'=>"required"
))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>