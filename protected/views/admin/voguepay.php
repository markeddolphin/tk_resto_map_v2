
<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','AdminVoguepaySettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("voguepay")?>?</label>
  <?php 
  echo CHtml::checkBox('admin_vog_enabled',
  getOptionA('admin_vog_enabled')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>



<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant ID")?></label>
  <?php 
  echo CHtml::textField('admin_vog_merchant_id',
  Yii::app()->functions->getOptionAdmin('admin_vog_merchant_id')
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