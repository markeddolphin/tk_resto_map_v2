<?php 
if(empty($website_review_type)){
	$website_review_type=1;
}
?>

<div class="review_notification">	
</div>

<div class="text-right">
<?php if($website_review_type==1):?>
<a href="javascript:;" class="write-review-new green-button inline bottom10 upper">
<?php echo t("write a review")?>
</a>
<?php else :?>
<a href="<?php echo Yii::app()->createUrl('/store/profile',array('tab'=>3))?>" class="green-button inline bottom10 upper">
<?php echo t("write a review")?>
</a>
<?php endif;?>
</div>


<div class="review-input-wrap bottom10 row">
<form class="forms-review" id="forms-review" onsubmit="return false;">
<?php echo CHtml::hiddenField('action','addReviews')?>
<?php echo CHtml::hiddenField('currentController','store')?>
<?php echo CHtml::hiddenField('merchant-id',$merchant_id)?>
<?php echo CHtml::hiddenField('initial_review_rating','')?>	        
   <div class="col-md-12 border">
     <div>
       <div class="raty-stars" data-score="5"></div>   
     </div>
     <div>
     <?php echo CHtml::textArea('review_content','',array(
        'required'=>true,
        'class'=>"grey-inputs"
     ))?>
     </div>
     <div class="top10">
        <button class="orange-button inline" type="submit"><?php echo t("PUBLISH REVIEW")?></button>
     </div>
   </div> <!--col-->	        
</form>
</div> <!--review-input-wrap-->

<div class="box-grey rounded merchant-review-wrap" style="margin-top:0;"></div>