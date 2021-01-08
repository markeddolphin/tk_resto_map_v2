

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','languageSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Language on front end")?></label>
  <?php 
  echo CHtml::checkBox('show_language',
  Yii::app()->functions->getOptionAdmin('show_language')
  ,array(
   'class'=>"icheck"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled Language bar on Admin/Merchant")?></label>
  <?php 
  echo CHtml::checkBox('show_language_backend',
  Yii::app()->functions->getOptionAdmin('show_language_backend')
  ,array(
   'class'=>"icheck"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Multiple Field Translation")?></label>
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
<p class="uk-text-muted">
<?php echo Yii::t("default","Select language that will be added in language bar")?>
</p>

<table class="uk-table uk-table-striped" style="width:50%;">
  <thead>
    <tr>
     <th><?php echo t("Language")?></th>
     <th><?php echo t("Enabled")?></th>
     <th><?php echo t("RTL")?></th>
    </tr>
  </thead>
  
  <?php foreach ($langauge_list as $key=>$val):?>
  <tbody>
   <tr>    
    <td><?php echo " ".$val;?></td>
    
    <td>
	<?php echo CHtml::checkBox('set_lang_id[]',
	in_array($key,(array)$set_lang_id)?true:false
	,array('class'=>"icheck",'value'=>$key))?>
    </td>
        
    <td>
    <?php echo CHtml::checkBox('lang_rtl[]',
	in_array($key,(array)$lang_rtl)?true:false
	,array('class'=>"icheck",'value'=>$key))?>
    </td>
    
   </tr>
  </tbody>
  <?php endforeach;?>
</table>


<?php endif;?>
  
  
<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>  

</form>