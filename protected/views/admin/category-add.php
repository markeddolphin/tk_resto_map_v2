<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/category/do/add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/category" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/categorysettings" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Settings")?></a>

</div>

<div class="spacer"></div>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','adminAddCategory')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>
<?php if (!isset($_GET['id'])):?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/admin/category/do/add")?>
<?php endif;?>

<?php 
if (isset($_GET['id'])){
	if (!$data=Yii::app()->functions->getCategory($_GET['id'])){
		echo "<div class=\"uk-alert uk-alert-danger\">".
		Yii::t("default","Sorry but we cannot find what your are looking for.")."</div>";
		return ;
	}	
}
?>                                 

<div class="uk-form-row">

<?php if ( Yii::app()->functions->multipleField()==2):?>

<ul data-uk-tab="{connect:'#tab-content'}" class="uk-tab uk-active">
    <li class="uk-active" ><a href="#"><?php echo t("English")?></a></li>
    <?php if ( $fields=Yii::app()->functions->getLanguageField()):?>  
    <?php foreach ($fields as $f_val): ?>
    <li class="" ><a href="#"><?php echo $f_val;?></a></li>
    <?php endforeach;?>
    <?php endif;?>
</ul>

<ul class="uk-switcher" id="tab-content">

  <li class="uk-active">      
  <div class="uk-form-row">
   <label class="uk-form-label"><?php echo Yii::t("default","Food Category Name")?></label>
  <?php echo CHtml::textField('category_name',
  isset($data['category_name'])?stripslashes($data['category_name']):""
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>  
    </div>    
    
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Description")?></label>
  <?php echo CHtml::textField('category_description',
  isset($data['category_description'])?stripslashes($data['category_description']):""
  ,array(
  'class'=>'uk-form-width-large',
  ))?>
  </div>   
   </li>
   
   <?php 
   $category_name_trans=isset($data['category_name_trans'])?json_decode($data['category_name_trans'],true):'';   
   $category_description_trans=isset($data['category_description_trans'])?json_decode($data['category_description_trans'],true):'';   
   ?>
   
   <?php if (is_array($fields) && count($fields)>=1):?>
   <?php foreach ($fields as $key_f => $f_val): ?>
   <li>
   
   <div class="uk-form-row">
	   <label class="uk-form-label"><?php echo Yii::t("default","Food Category Name")?></label>
	  <?php echo CHtml::textField("category_name_trans[$key_f]",
	  array_key_exists($key_f,(array)$category_name_trans)?$category_name_trans[$key_f]:''
	  ,array(
	  'class'=>'uk-form-width-large',
	  //'data-validation'=>"required"
	  ))?>  
   </div>    
   
   <div class="uk-form-row">
	  <label class="uk-form-label"><?php echo Yii::t("default","Description")?></label>
	  <?php echo CHtml::textField("category_description_trans[$key_f]",
	  array_key_exists($key_f,(array)$category_description_trans)?$category_description_trans[$key_f]:''
	  ,array(
	  'class'=>'uk-form-width-large',
	  ))?>
  </div>   
   
   </li>
   <?php endforeach;?>
   <?php endif;?>
</ul>

<?php else : // Normal field?>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t("default","Food Category Name")?></label>
  <?php echo CHtml::textField('category_name',
  isset($data['category_name'])?stripslashes($data['category_name']):""
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Description")?></label>
  <?php echo CHtml::textField('category_description',
  isset($data['category_description'])?stripslashes($data['category_description']):""
  ,array(
  'class'=>'uk-form-width-large',
  ))?>
</div>

<?php endif;?>

<div class="uk-form-row"> 
 <label class="uk-form-label"><?php echo Yii::t('default',"Featured Image")?></label>
  <div style="display:inline-table;margin-left:1px;" class="button uk-button" id="photo"><?php echo Yii::t('default',"Browse")?></div>	  
  <DIV  style="display:none;" class="photo_chart_status" >
	<div id="percent_bar" class="photo_percent_bar"></div>
	<div id="progress_bar" class="photo_progress_bar">
	  <div id="status_bar" class="photo_status_bar"></div>
	</div>
  </DIV>		  
</div>

<?php if (!empty($data['photo'])):?>
<div class="uk-form-row"> 
<?php else :?>
<div class="input_block preview">
<?php endif;?>
<label><?php echo Yii::t('default',"Preview")?></label>
<div class="image_preview">
 <?php if (!empty($data['photo'])):?>
 <input type="hidden" name="photo" value="<?php echo $data['photo'];?>">
 <img class="uk-thumbnail uk-thumbnail-small" src="<?php echo Yii::app()->request->baseUrl."/upload/".$data['photo'];?>?>" alt="" title="">
 <?php endif;?>
</div>
</div>


<?php $dish=Yii::app()->functions->GetDishList();?>
<?php $dish_selected=isset($data['dish'])?json_decode($data['dish'],true):'';?>
<?php if (is_array($dish) && count($dish)>=1):?>
  <div class="uk-form-row">
  <label class="uk-form-label uk-h3"><?php echo Yii::t("default","Dish")?></label>  
  <div class="clear"></div>
  <ul class="uk-list uk-list-striped">
  
  <?php foreach ($dish as $dish_val):?>
  <li>
  <?php echo CHtml::checkBox('dish[]',
  in_array($dish_val['dish_id'],(array)$dish_selected)?true:false
  ,array(
   'class'=>"icheck",
   'value'=>$dish_val['dish_id']
  ))?>	  
  <?php echo $dish_val['dish_name']?>
  <?php endforeach;?>
  
  </li>
  </ul>
</div>
<?php endif;?>

<!--<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Spicy Note")?></label>
  <?php 
  echo CHtml::textArea('spicydish_notes',$data['spicydish_notes'],array(
    'class'=>"big-textarea"
  ))
  ?>
</div>-->

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Status")?></label>
  <?php echo CHtml::dropDownList('status',
  isset($data['status'])?$data['status']:"",
  (array)statusList(),          
  array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>