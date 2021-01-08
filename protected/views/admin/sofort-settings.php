
<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','SofortAdminSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("Sofort Payments")?>?</label>
  <?php 
  echo CHtml::checkBox('admin_sofort_enabled',
  getOptionA('admin_sofort_enabled')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Config Key")?></label>
  <?php 
  echo CHtml::textField('admin_sofort_config_key',
  Yii::app()->functions->getOptionAdmin('admin_sofort_config_key')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Set Language Code")?></label>
  <?php 
  echo CHtml::textField('admin_sofort_lang',
  Yii::app()->functions->getOptionAdmin('admin_sofort_lang')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>



<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>