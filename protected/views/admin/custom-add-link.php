
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customPage/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customPage/Do/AddCustom" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New Custom Link")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customPage" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customPage/Do/Assign" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Assign Page")?></a>
</div>

<?php 
if (isset($_GET['id'])){
	if (!$data=Yii::app()->functions->getCustomPage($_GET['id'])){
		echo "<div class=\"uk-alert uk-alert-danger\">".
		Yii::t("default","Sorry but we cannot find what your are looking for.")."</div>";
		return ;
	}
}
?>                                   

<div class="spacer"></div>

<?php
echo CHtml::beginForm('','post',array(
  'id'=>"forms",
  'class'=>"uk-form uk-form-horizontal forms",
  'onsubmit'=>"return false;"	  
)); 
echo CHtml::hiddenField('action','addCustomPageLink');
echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");
if (!isset($_GET['id'])){
	echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/admin/customPage/Do/AddCustom");
}
?>

<?php if ( Yii::app()->functions->multipleField()==2):?>
<ul data-uk-tab="{connect:'#tab-content'}" class="uk-tab uk-active">
    <li class="uk-active" ><a href="#"><?php echo t("default")?></a></li>    
    <?php if ( $fields=FunctionsV3::getLanguageList(false)):?>  
    <?php foreach ($fields as $f_val): ?>
    <li class="" ><a href="#"><?php echo $f_val;?></a></li>
    <?php endforeach;?>
    <?php endif;?>
</ul>

<ul class="uk-switcher" id="tab-content">
<li class="uk-active">

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Link Name")?></label>
  <?php 
  echo CHtml::textField('page_name',
  isset($data['page_name'])?$data['page_name']:""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

</li>

<?php foreach ($fields as $key_f => $f_val): ?>
<li>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Link Name")?></label>
  <?php 
  echo CHtml::textField("page_name_trans[$key_f]",
  isset($page_name_trans[$key_f])?$page_name_trans[$key_f]:''
  ,array('class'=>"uk-form-width-large"))
  ?>
</div>
</li>
<?php endforeach;?>

</ul>

<?php else :?>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Link Name")?></label>
  <?php 
  echo CHtml::textField('page_name',
  isset($data['page_name'])?$data['page_name']:""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<?php endif;?>

<div class="spacer"></div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Link")?></label>
  <?php 
  echo CHtml::textField('content',
  isset($data['content'])?$data['content']:""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Open in new window")?>?</label>
  <?php 
  echo CHtml::checkBox('open_new_tab',
  $data['open_new_tab']==2?true:false
  ,array('class'=>'icheck','value'=>2));
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

