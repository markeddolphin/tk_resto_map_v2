
<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','BannerSettings')?>


<div class="uk-form-row"> 
 <label class="uk-form-label"><?php echo Yii::t('default',"Enabled")?></label>
 <?php 
 echo CHtml::checkBox('banner_enabled',
 $banner_enabled==1?true:false
 ,array(
   'class'=>'icheck',
   'value'=>1
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
  if(!empty($banner)){
       $banner = json_decode($banner,true);
       if(is_array($banner) && count($banner)>=1){
           foreach ($banner as $banner_val) {
                $image_url = FunctionsV3::getImage($banner_val); 
                if(!empty($image_url)){
                    echo "<div class=\"col\">";
                    echo '<img src="'.$image_url.'" class="uk-thumbnail"  />';
                 echo CHtml::hiddenField('photo[]',$banner_val);                 
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