<?php 
$mode = getOptionA('admin_payulatam_mode');
?>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','payulatamSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")." ".t("PayU Latam")?>?</label>
  <?php 
  echo CHtml::checkBox('admin_payulatam_enabled',
  getOptionA('admin_payulatam_enabled')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php 
  echo CHtml::radioButton('admin_payulatam_mode',
  $mode=="sandbox"?true:false
  ,array(
    'value'=>"sandbox",
    'class'=>"icheck"
  ))
  ?>
  <?php echo t("Sandbox")?> 
  <?php 
  echo CHtml::radioButton('admin_payulatam_mode',
  $mode=="live"?true:false
  ,array(
    'value'=>"live",
    'class'=>"icheck"
  ))
  ?>	
  <?php echo t("Live")?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Language")?></label>
  <?php echo CHtml::dropDownList('admin_payulatam_lang',
  getOptionA('admin_payulatam_lang')
  ,(array)Payulatam::language())?>
</div>  

<h2><?php echo t("Sandbox")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("API Key")?></label>
  <?php 
  echo CHtml::textField('admin_payulatam_apikey',
  getOptionA('admin_payulatam_apikey')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("API Login")?></label>
  <?php 
  echo CHtml::textField('admin_payulatam_apilogin',
  getOptionA('admin_payulatam_apilogin')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant ID")?></label>
  <?php 
  echo CHtml::textField('admin_payulatam_mtid',
  getOptionA('admin_payulatam_mtid')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Account ID")?></label>
  <?php 
  echo CHtml::textField('admin_payulatam_account_id',
  getOptionA('admin_payulatam_account_id')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<h2><?php echo t("Production")?></h2>



<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("API Key")?></label>
  <?php 
  echo CHtml::textField('admin_payulatam_apikey_live',
  getOptionA('admin_payulatam_apikey_live')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("API Login")?></label>
  <?php 
  echo CHtml::textField('admin_payulatam_apilogin_live',
  getOptionA('admin_payulatam_apilogin_live')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Merchant ID")?></label>
  <?php 
  echo CHtml::textField('admin_payulatam_mtid_live',
  getOptionA('admin_payulatam_mtid_live')
  ,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Account ID")?></label>
  <?php 
  echo CHtml::textField('admin_payulatam_account_id_live',
  getOptionA('admin_payulatam_account_id_live')
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