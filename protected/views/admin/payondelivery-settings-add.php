
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/payondelivery/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/payondelivery" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/payondelivery/Do/Sort" class="uk-button"><i class="fa fa-sort-alpha-asc"></i> <?php echo Yii::t("default","Sort")?></a>
</div>


<?php 
if (isset($_GET['id'])){	
	if (!$data=Yii::app()->functions->getPaymentProvider($_GET['id'])){
		echo "<div class=\"uk-alert uk-alert-danger\">".
		Yii::t("default","Sorry but we cannot find what your are looking for.")."</div>";
		return ;
	}
}
?>                                   
<div class="spacer"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','addPayonDeliver')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>
<?php if (!isset($_GET['id'])):?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/admin/payondelivery/Do/Add")?>
<?php endif;?>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Payment Name")?></label>
  <?php 
  echo CHtml::textField('card_name',
  isset($data['payment_name'])?$data['payment_name']:""
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>


<!--<div class="uk-form-row"> 
 <label class="uk-form-label"><?php echo Yii::t('default',"Payment Logo")?></label>
  <div style="display:inline-table;margin-left:1px;" class="button uk-button" id="photo"><?php echo Yii::t('default',"Browse")?></div>	  
  <DIV  style="display:none;" class="photo_chart_status" >
	<div id="percent_bar" class="photo_percent_bar"></div>
	<div id="progress_bar" class="photo_progress_bar">
	  <div id="status_bar" class="photo_status_bar"></div>
	</div>
  </DIV>		  
</div>

<?php $website_logo=isset($data['payment_logo'])?$data['payment_logo']:"";?>
<?php if (!empty($website_logo)):?>
<div class="uk-form-row"> 
<?php else :?>
<div class="input_block preview">
<?php endif;?>
<label><?php echo Yii::t('default',"Preview")?></label>
<div class="image_preview">
 <?php if (!empty($website_logo)):?>
 <input type="hidden" name="photo" value="<?php echo $website_logo;?>">
 <img class="uk-thumbnail uk-thumbnail-small" src="<?php echo Yii::app()->request->baseUrl."/upload/".$website_logo;?>?>" alt="" title="">
 <p><a href="javascript:rm_preview();"><?php echo Yii::t("default","Remove image")?></a></p>
 <?php endif;?>
</div>
</div>-->

<div class="uk-form-row"> 
  <label class="uk-form-label"><?php echo t("Payment Logo")?></label>
  <a href="javascript:;" id="file_upload" 
   class="button uk-button" data-progress="file_upload_progress" data-preview="file_upload_preview" data-field="photo">
	<?php echo t("Browse")?>
  </a>
</div>
<div class="file_upload_progress"></div>

<div class="file_upload_preview">
 <?php 
 $logo = isset($data['payment_logo'])?$data['payment_logo']:"";
 if(!empty($logo)){
	echo '<img src="'.FunctionsV3::getImage($logo).'" class="uk-thumbnail" id="logo-small"  />';
	echo CHtml::hiddenField('photo',$logo);
	echo '<br/>';
	echo '<a href="javascript:;" class="sau_remove_file" data-preview="file_upload_preview" >'.t("Remove image").'</a>';
 }
 ?>
</div>

<div style="height:25px;"></div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Status")?></label>
  <?php 
  echo CHtml::dropDownList('status',isset($data['payment_name'])?$data['payment_name']:"",
  statusList(),array(
    'class'=>"uk-form-width-large"
  ));
  ?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>