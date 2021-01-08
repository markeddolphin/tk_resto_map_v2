
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/ManageLanguage/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/ManageLanguage" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/ManageLanguage/Do/Settings" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Settings")?></a>
</div>

<?php 
$translated_text='';
$new_raw_msg='';

if (isset($_GET['id'])){
	if (!$data=Yii::app()->functions->languageInfo($_GET['id'])){
		echo "<div class=\"uk-alert uk-alert-danger\">".
		Yii::t("default","Sorry but we cannot find what your are looking for.")."</div>";
		return ;
	}	
}
$langauge_list=yii::app()->functions->availableLanguage();
$set_lang_id=Yii::app()->functions->getOptionAdmin('set_lang_id');
if ( !empty($set_lang_id)){
	$set_lang_id=json_decode($set_lang_id);
}
?>                                   

<div class="spacer"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','languageSettings')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Language on front end")?>?</label>
  <?php 
  echo CHtml::checkBox('show_language',
  Yii::app()->functions->getOptionAdmin('show_language')
  ,array(
   'class'=>"icheck"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Language bar on Admin/Merchant")?>?</label>
  <?php 
  echo CHtml::checkBox('show_language_backend',
  Yii::app()->functions->getOptionAdmin('show_language_backend')
  ,array(
   'class'=>"icheck"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Multiple Field Translation")?>?</label>
  <?php 
  echo CHtml::checkBox('enabled_multiple_translation',
  Yii::app()->functions->getOptionAdmin('enabled_multiple_translation')==2?true:false  
  ,array(
   'class'=>"icheck",
   'value'=>2
  ))
  ?>
</div>
<p class="uk-text-muted uk-text-small">
<?php echo t("this will add a field on food item and category for multiple language")?>
</p>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Set Language")?></label>
  <?php if (is_array($langauge_list) && count($langauge_list)>=1):?>    
</div>  
<p class="uk-text-muted"><?php echo Yii::t("default","Select language that will be added on front end.")?></p>

 <ul class="uk-list uk-list-striped">
  <?php foreach ($langauge_list as $key=>$val):?>
  <li>
  <?php echo CHtml::checkBox('set_lang_id[]',
  in_array($key,(array)$set_lang_id)?true:false
  ,array('class'=>"icheck",'value'=>$key))?>
  <?php echo ucwords($val);?></li>
  <?php endforeach;?>
  <?php endif;?>
  </ul>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Default Language on front end")?></label>
  <?php 
  echo CHtml::dropDownList('default_language',
  Yii::app()->functions->getOptionAdmin('default_language'),
  (array)Yii::app()->functions->availableLanguage()
  ,array(
   'class'=>"uk-form-width-large",'data-validation'=>""
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Default Language on Admin/Merchant")?></label>
  <?php 
  echo CHtml::dropDownList('default_language_backend',
  Yii::app()->functions->getOptionAdmin('default_language_backend'),
  (array)Yii::app()->functions->availableLanguage()
  ,array(
   'class'=>"uk-form-width-large",'data-validation'=>""
  ))
  ?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>