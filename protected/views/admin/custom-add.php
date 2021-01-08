<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customPage/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customPage/Do/AddCustom" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New Custom Link")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customPage" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customPage/Do/Assign" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Assign Page")?></a>
</div>


<div class="spacer"></div>

<?php
echo CHtml::beginForm('','post',array(
  'id'=>"forms",
  'class'=>"uk-form uk-form-horizontal forms",
  'onsubmit'=>"return false;"	  
)); 
echo CHtml::hiddenField('action','addCustomPage');
echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");
if (!isset($_GET['id'])){
	echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/admin/customPage/Do/Add");
}
$page_name_trans=isset($data['page_name_trans'])?json_decode($data['page_name_trans'],true):'';   
$content_trans=isset($data['content_trans'])?json_decode($data['content_trans'],true):'';
$seo_title_trans=isset($data['seo_title_trans'])?json_decode($data['seo_title_trans'],true):'';
$meta_description_trans=isset($data['meta_description_trans'])?json_decode($data['meta_description_trans'],true):'';
$meta_keywords_trans=isset($data['meta_keywords_trans'])?json_decode($data['meta_keywords_trans'],true):'';
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
  <label class="uk-form-label"><?php echo Yii::t("default","Page Name")?></label>
  <?php 
  echo CHtml::textField('page_name',
  isset($data['page_name'])?$data['page_name']:""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Icon")?></label>
  <?php 
  echo CHtml::textField('icons',
  isset($data['icons'])?$data['icons']:""
  ,array('class'=>"uk-form-width-large",'placeholder'=>"eg. fa fa-info-circle"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Content")?></label>
  <?php 
  echo CHtml::textArea('content',
  isset($data['content'])?$data['content']:""
  ,array('class'=>"uk-form-width-large ",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Open in new window")?>?</label>
  <?php 
  if(!isset($data['open_new_tab'])){
  	$data['open_new_tab']='';
  }
  echo CHtml::checkBox('open_new_tab',
  $data['open_new_tab']==2?true:false
  ,array('class'=>'icheck','value'=>2));
  ?>
</div>

<h2><?php echo t("SEO")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","SEO Title")?></label>
  <?php 
  echo CHtml::textField('seo_title',
  isset($data['seo_title'])?$data['seo_title']:""
  ,array('class'=>"uk-form-width-large"))
  ?>
</div>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Meta Description")?></label>
  <?php 
  echo CHtml::textField('meta_description',
  isset($data['meta_description'])?$data['meta_description']:""
  ,array('class'=>"uk-form-width-large"))
  ?>
</div>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Meta Keywords")?></label>
  <?php 
  echo CHtml::textField('meta_keywords',
  isset($data['meta_keywords'])?$data['meta_keywords']:""
  ,array('class'=>"uk-form-width-large"))
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


</li>

<?php foreach ($fields as $key_f => $f_val): ?>
<li>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Page Name")?></label>
  <?php 
  echo CHtml::textField("page_name_trans[$key_f]",
  isset($page_name_trans[$key_f])?$page_name_trans[$key_f]:''
  ,array('class'=>"uk-form-width-large"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Content")?></label>
  <?php 
  echo CHtml::textArea("content_trans[$key_f]",
  isset($content_trans[$key_f])?$content_trans[$key_f]:''
  ,array('class'=>"uk-form-width-large "))
  ?>
</div>

<h2><?php echo t("SEO")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","SEO Title")?></label>
  <?php 
  echo CHtml::textField("seo_title_trans[$key_f]",
  isset($seo_title_trans[$key_f])?$seo_title_trans[$key_f]:''
  ,array('class'=>"uk-form-width-large"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Meta Description")?></label>
  <?php 
  echo CHtml::textField("meta_description_trans[$key_f]",
  isset($meta_description_trans[$key_f])?$meta_description_trans[$key_f]:''
  ,array('class'=>"uk-form-width-large"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Meta Keywords")?></label>
  <?php 
  echo CHtml::textField("meta_keywords_trans[$key_f]",
  isset($meta_keywords_trans[$key_f])?$meta_keywords_trans[$key_f]:''
  ,array('class'=>"uk-form-width-large"))
  ?>
</div>

</li>
<?php endforeach;?>
</ul>

<?php else :?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Page Name")?></label>
  <?php 
  echo CHtml::textField('page_name',
  isset($data['page_name'])?$data['page_name']:""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Icon")?></label>
  <?php 
  echo CHtml::textField('icons',
  isset($data['icons'])?$data['icons']:""
  ,array('class'=>"uk-form-width-large",'placeholder'=>"eg. fa fa-info-circle"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Content")?></label>
  <?php 
  echo CHtml::textArea('content',
  isset($data['content'])?$data['content']:""
  ,array('class'=>"uk-form-width-large ",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Open in new window")?>?</label>
  <?php 
  if(!isset($data['open_new_tab'])){
  	 $data['open_new_tab']='';
  }
  echo CHtml::checkBox('open_new_tab',
  $data['open_new_tab']==2?true:false
  ,array('class'=>'icheck','value'=>2));
  ?>
</div>

<h2><?php echo t("SEO")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","SEO Title")?></label>
  <?php 
  echo CHtml::textField('seo_title',
  isset($data['seo_title'])?$data['seo_title']:""
  ,array('class'=>"uk-form-width-large"))
  ?>
</div>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Meta Description")?></label>
  <?php 
  echo CHtml::textField('meta_description',
  isset($data['meta_description'])?$data['meta_description']:""
  ,array('class'=>"uk-form-width-large"))
  ?>
</div>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Meta Keywords")?></label>
  <?php 
  echo CHtml::textField('meta_keywords',
  isset($data['meta_keywords'])?$data['meta_keywords']:""
  ,array('class'=>"uk-form-width-large"))
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


<?php endif;?>

<div class="spacer"></div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

<?php echo CHtml::endForm(); ?>