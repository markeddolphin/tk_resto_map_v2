<div class="uk-width-1">
<a href="<?php echo Yii::app()->createUrl('merchant/review')?>"
 class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>
<br/>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','replyToReview')?>
<?php echo CHtml::hiddenField('parent_id',$data['id']);?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->createUrl('merchant/review'))?>
<?php 
if (isset($_GET['record_id'])){
	echo CHtml::hiddenField('record_id',$_GET['record_id']);
}
?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Customer review")?></label>
  <p style="margin:0;" class="uk-text-muted"><?php echo nl2br($data['review'])?></p>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Comment")?></label>
  <?php
  echo CHtml::textArea('review',isset($data2['review'])?$data2['review']:"",array(
    'class'=>"uk-width-1-2",
    'data-validation'=>"required",
    "style"=>"height:250px;"
  ));
  ?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>