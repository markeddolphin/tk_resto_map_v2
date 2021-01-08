
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/category/do/add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/category" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/categorysettings" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Settings")?></a>

</div>

<br/>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','categorySettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled category on merchant panel")?></label>
  <?php 
  echo CHtml::checkBox('merchant_category_disabled',
  getOptionA('merchant_category_disabled')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Auto add category when merchant is added")?></label>
  <?php 
  echo CHtml::checkBox('merchant_category_auto_add',
  getOptionA('merchant_category_auto_add')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>