<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','cardPaymentSettings')?>
  
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Card Payment")?>?</label>
  <?php 
  echo CHtml::checkBox('admin_enabled_card',
  Yii::app()->functions->getOptionAdmin('admin_enabled_card')=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ));      
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Encryption Key")?>?</label>
  <?php 
  echo CHtml::textField('offline_cc_encryption_key',
  getOptionA('offline_cc_encryption_key'),array(
   'class'=>'uk-form-width-large'
  ));
  ?> 
</div>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>