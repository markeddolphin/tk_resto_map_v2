<?php
$merchant_id=Yii::app()->functions->getMerchantID();
/*$gallery=Yii::app()->functions->getOption("merchant_gallery",$merchant_id);
$gallery=!empty($gallery)?json_decode($gallery):false;*/

$gallery_disabled=Yii::app()->functions->getOption("gallery_disabled",$merchant_id);
?>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','gallerySettings')?>


<div class="uk-form-row"> 
 <label class="uk-form-label"><?php echo Yii::t('default',"Disabled Gallery")?>?</label>
 <?php 
 echo CHtml::checkBox('gallery_disabled',
 $gallery_disabled=="yes"?true:false
 ,array(
   'class'=>'icheck',
   'value'=>"yes"
 ));
 ?>
</div>


<!--GALLERY -->
<div class="uk-form-row"> 
  <label class="uk-form-label"><?php echo t("Gallery Image")?></label>
  <a href="javascript:;" id="multiple_upload" 
   class="button uk-button" data-progress="multiple_upload_progress" data-preview="image_multiple_preview" data-field="photo">
    <?php echo t("Browse")?>
  </a>
</div>
<div class="multiple_upload_progress"></div>

<div class="image_multiple_preview"> 
  <?php   
  $gallery_photo = getOption($merchant_id,'merchant_gallery');
  if(!empty($gallery_photo)){
  	 $gallery_photo = json_decode($gallery_photo,true);
  	 if(is_array($gallery_photo) && count($gallery_photo)>=1){
  	 	foreach ($gallery_photo as $gallery_photo_val) {
  	 		 $image_url = FunctionsV3::getImage($gallery_photo_val); 
  	 		 if(!empty($image_url)){
  	 		 	echo "<div class=\"col\">";
  	 		 	echo '<img src="'.$image_url.'" class="uk-thumbnail"  />';
			 	echo CHtml::hiddenField('photo[]',$gallery_photo_val);			 	
			 	echo '<a href="javascript:;" class="multiple_remove_image" data-preview="image_multiple_preview" >'.t("Remove image").'</a>';
			 	echo "</div>";
  	 		 }
  	 	}
  	 }
  }
  ?>
</div>	
<div class="clear"></div>
<!--GALLERY -->

<div class="spacer"></div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>