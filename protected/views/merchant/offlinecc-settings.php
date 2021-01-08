
<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','saveOfflineSettings')?>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Offline Credit Card Payment")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_disabled_ccr',
  Yii::app()->functions->getOption("merchant_disabled_ccr",$merchant_id)=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>