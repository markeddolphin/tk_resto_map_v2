<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/AddOnItem/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/AddOnItem" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/AddOnItem/Do/Sort" class="uk-button"><i class="fa fa-sort-alpha-asc"></i> <?php echo Yii::t("default","Sort")?></a>
</div>

<div class="spacer"></div>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','addOnItemNew')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>
<?php if (!isset($_GET['id'])):?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/merchant/AddOnItem/Do/Add")?>
<?php endif;?>

<?php 
if (isset($_GET['id'])){
	if (!$data=Yii::app()->functions->getAddonItem2($_GET['id'])){		
		echo "<div class=\"uk-alert uk-alert-danger\">".
		Yii::t("default","Sorry but we cannot find what your are looking for.")."</div>";
		return ;
	}	
}
Yii::app()->functions->data='list';
$subcat=Yii::app()->functions->getSubcategory();
$selected_cat=isset($data['category'])?json_decode($data['category']):false;

echo CHtml::hiddenField('merchant_tax',$merchant_tax);
?>                                 

<div class="uk-grid">

<div class="uk-width-1-2">

<?php if ( Yii::app()->functions->multipleField()==2):?>
<?php 
Widgets::multipleFields(array(
  'AddOn Item','Description'
),array(
  'sub_item_name','item_description'
),isset($data)?$data:'',array(true,false));
?>
<div class="spacer"></div>

<?php else :?>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","AddOn Item")?></label>
  <?php echo CHtml::textField('sub_item_name',
  isset($data['sub_item_name'])?$data['sub_item_name']:""
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Description")?></label>
  <?php echo CHtml::textField('item_description',
  isset($data['item_description'])?$data['item_description']:""
  ,array(
  'class'=>'uk-form-width-large'  
  ))?>
</div>
<?php endif;?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Status")?></label>
  <?php echo CHtml::dropDownList('status',
  isset($data['status'])?$data['status']:"",
  (array)statusList(),          
  array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
</div>

</div>

<div class="uk-width-1-2">

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","AddOn Category")?></label>  
  <div class="clear"></div>
  <?php if (is_array($subcat) && count($subcat)>=1):?>
  <ul class="uk-list uk-list-striped">
  <?php foreach ($subcat as $key=>$val):?>
    <li>
    <?php echo CHtml::checkBox('category[]',
    in_array($key,(array)$selected_cat)?true:false,array(
      'value'=>$key,     
       'data-validation'=>"checkbox_group",
	   'data-validation-qty'=>'min1'
    ))?>
    <?php echo $val;?>
    </li>
  <?php endforeach;?>
  </ul>
  <?php endif;?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Price")?></label>
  <?php
  echo CHtml::textField('price',isset($data['price'])?$data['price']:"",array(
   'class'=>"numeric_only addon_price"
  ))
  ?>  
</div>

<?php if ($merchant_apply_tax==1):?>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Total Price with tax")?></label>
  <span class="total_addon_with_tax">
  <?php 
  if(isset($data['price'])){
  	$total_price=$data['price']+($data['price']*$merchant_tax);
  	echo standardPrettyFormat($total_price);
  }
  ?>
  </span>
</div>
<?php endif;?>



<div class="uk-form-row"> 
  <label class="uk-form-label"><?php echo t("Featured Image")?></label>
  <a href="javascript:;" id="sau_merchant_upload_file" 
   class="button uk-button" data-progress="sau_merchant_progress" data-preview="image_preview" data-field="photo">
    <?php echo t("Browse")?>
  </a>
</div>
<div class="sau_merchant_progress"></div>

<div class="image_preview">
 <?php 
 $image=isset($data['photo'])?$data['photo']:'';
 $image_url = FunctionsV3::getImage($image); 
 if(!empty($image_url)){
 	echo '<img src="'.$image_url.'" class="uk-thumbnail" id="logo-small"  />';
 	echo CHtml::hiddenField('photo',$image);
 	echo '<br/>';
 	echo '<a href="javascript:;" class="sau_remove_file" data-preview="image_preview" >'.t("Remove image").'</a>';
 }
 ?>
</div>	

<div style="height:20px;"></div>


</div> <!--END uk-width-1-2-->

</div> <!--END UK-GRID-->

<div class="spacer"></div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>