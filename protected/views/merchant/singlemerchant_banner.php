<h2><?php echo t("Home banner")?></h2>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','singleMerchantBanner')?>



<div class="custom-control custom-checkbox">  
 <?php 
 echo CHtml::checkBox('singleapp_enabled_banner',
 getOption($merchant_id,'singleapp_enabled_banner')==1?true:false
 ,array(
   'value'=>1,
   'class'=>"xform-control"
 ));
 ?>
  <label class="custom-control-label">
    <?php echo t("Enabled Banner")?>
  </label>
</div>

<div class="spacer"></div>

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
  $gallery_photo = getOption($merchant_id,'singleapp_banner');  
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


<div class="form-group">
  <label ><?php echo t("Scroll Interval")?></label>
  <?php 
    echo CHtml::textField('singleapp_homebanner_interval',
    getOption($merchant_id,'singleapp_homebanner_interval')
    ,array(
      'class'=>"form-control numeric_only",
      'placeholder'=>t("Default is 3000 miliseconds")
    ));
    ?>    	    
</div>

<div class="custom-control custom-checkbox">  
 <?php 
 echo CHtml::checkBox('singleapp_homebanner_auto_scroll',
 getOption($merchant_id,'singleapp_homebanner_auto_scroll')==1?true:false
 ,array(
   'value'=>1,   
 ));
 ?>
  <label class="custom-control-label">
    <?php echo t("Auto Scroll")?>
  </label>
</div>
  
<div class="spacer"></div>

<button type="submit" class="btn btn-success">
<?php echo t("Save settings")?>
</button>

<?php echo CHtml::endForm(); ?>