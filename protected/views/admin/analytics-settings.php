
<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','analyticsSetting')?>




<p class="uk-text-muted">
<?php 
echo t("You can add your google analytics code here or any snippet code.")
?>
</p>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Code")?></label>
  <?php 
  echo CHtml::textArea('admin_header_codes',
  Yii::app()->functions->getOptionAdmin('admin_header_codes'),
  array('class'=>'uk-form-width-large'))
  ?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>