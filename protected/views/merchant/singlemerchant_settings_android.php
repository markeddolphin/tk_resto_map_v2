<h2><?php echo t("Android Settings")?></h2>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','singleMerchantAndroid')?>



<div class="custom-control custom-checkbox">  
 <?php 
 echo CHtml::checkBox('singleapp_enabled_pushpic',
 getOption($merchant_id,'singleapp_enabled_pushpic')==1?true:false
 ,array(
   'value'=>1,
   'class'=>"xform-control"
 ));
 ?>
  <label class="custom-control-label">
    <?php echo t("Enabled Push Picture")?>
  </label>
</div>

<div class="spacer"></div>

<!--Merchant Logo-->
<div class="uk-form-row"> 
  <label class="uk-form-label"><?php echo t("Push Icon")?></label>
  <a href="javascript:;" id="sau_merchant_upload_file" 
   class="button uk-button" data-progress="sau_merchant_progress" data-preview="image_preview" data-field="photo">
    <?php echo t("Browse")?>
  </a>
</div>
<div class="sau_merchant_progress"></div>

<div class="image_preview">
 <?php 
 $image=getOption($merchant_id,'singleapp_push_icon');
 $image_url = FunctionsV3::getImage($image); 
 if(!empty($image_url)){
 	echo '<img src="'.$image_url.'" class="uk-thumbnail" id="logo-small"  />';
 	echo CHtml::hiddenField('photo',$image);
 	echo '<br/>';
 	echo '<a href="javascript:;" class="sau_remove_file" data-preview="image_preview" >'.t("Remove image").'</a>';
 }
 ?>
</div>	

<div class="spacer"></div>

<!--END Merchant Logo-->

<!--HEADER BG-->

<div class="uk-form-row"> 
  <label class="uk-form-label"><?php echo t("Push Picture")?></label>
  <a href="javascript:;" id="single_uploadfile" 
   class="button uk-button" data-progress="single_uploadfile_progress" data-preview="single_uploadfile_preview" data-field="photo2">
    <?php echo t("Browse")?>
  </a>
</div>
<div class="single_uploadfile_progress"></div>

<div class="single_uploadfile_preview">
 <?php 
 $image=getOption($merchant_id,'singleapp_push_picture');
 $image_url = FunctionsV3::getImage($image); 
 if(!empty($image_url)){
 	echo '<img src="'.$image_url.'" class="uk-thumbnail" id="logo-small"  />';
 	echo CHtml::hiddenField('photo2',$image);
 	echo '<br/>';
 	echo '<a href="javascript:;" class="single_uploadfile_remove" data-preview="image_preview" >'.t("Remove image").'</a>';
 }
 ?>
</div>	

<div class="spacer"></div>

<!--END HEADER BG-->

  
<div class="spacer"></div>

<button type="submit" class="btn btn-success">
<?php echo t("Save settings")?>
</button>

<?php echo CHtml::endForm(); ?>