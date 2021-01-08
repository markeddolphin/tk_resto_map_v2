
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/Cuisine/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/Cuisine" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<?php 
if (isset($_GET['id'])){
	if (!$data=Yii::app()->functions->GetCuisine($_GET['id'])){
		echo "<div class=\"uk-alert uk-alert-danger\">".
		Yii::t("default","Sorry but we cannot find what your are looking for.")."</div>";
		return ;
	}
}
?>                                   

<div class="spacer"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','addCuisine')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>
<?php if (!isset($_GET['id'])):?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/admin/Cuisine/Do/Add")?>
<?php endif;?>


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
   <label class="uk-form-label"><?php echo Yii::t("default","Cuisine Name")?></label>
  <?php echo CHtml::textField('cuisine_name',
  isset($data['cuisine_name'])?stripslashes($data['cuisine_name']):""
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>  
    </div>    
    
    <div style="height:10px;"></div>
  
   </li>
   
   <?php 
   $cuisine_name_trans=isset($data['cuisine_name_trans'])?json_decode($data['cuisine_name_trans'],true):'';   
   ?>
   
   <?php if (is_array($fields) && count($fields)>=1):?>
   <?php foreach ($fields as $key_f => $f_val): ?>
   <li>
   
   <div class="uk-form-row">
	   <label class="uk-form-label"><?php echo Yii::t("default","Cuisine Name")?></label>
	  <?php echo CHtml::textField("cuisine_name_trans[$key_f]",
	  array_key_exists($key_f,(array)$cuisine_name_trans)?$cuisine_name_trans[$key_f]:''
	  ,array(
	  'class'=>'uk-form-width-large',
	  ))?>  
   </div>    
   
   <div style="height:10px;"></div>
   
   </li>
   <?php endforeach;?>
   <?php endif;?>
</ul>

<?php else :?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Cuisine Name")?></label>
  <?php 
  echo CHtml::textField('cuisine_name',
  isset($data['cuisine_name'])?$data['cuisine_name']:""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>
<?php endif;?>



<div class="uk-form-row"> 
  <label class="uk-form-label"><?php echo t("Featured Image")?></label>
  <a href="javascript:;" id="sau_upload_file" 
   class="button uk-button" data-progress="sau_progress" data-preview="image_preview" data-field="featured_image">
    <?php echo t("Browse")?>
  </a>
</div>
<div class="sau_progress"></div>

<div class="image_preview">
 <?php  
 $image=isset($data['featured_image'])?$data['featured_image']:'';
 if(!empty($image)){
 	echo '<img src="'.FunctionsV3::getImage($image).'" class="uk-thumbnail" id="logo-small"  />';
 	echo CHtml::hiddenField('featured_image',$image);
 	echo '<br/>';
 	echo '<a href="javascript:;" class="sau_remove_file" data-preview="image_preview" >'.t("Remove image").'</a>';
 }
 ?>
</div>	

<div style="height:20px;"></div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>