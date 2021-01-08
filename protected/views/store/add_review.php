<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Add Review"),
   'sub_text'=>''
));
?>

<div class="sections section-grey2 section-profile">
  <div class="container">
  
  <div class="row">
    <div class="col-md-8">
    
     <div class="box-grey rounded">
       <div class="bottom10"><?php echo FunctionsV3::sectionHeader( Yii::t("default","Add review to order #[order_id]",array(
         '[order_id]'=>$order_info['order_id']
       ))  );?></div>
       
       
       
<div class="bottom10 row">
<form class="forms-review" id="forms-review" onsubmit="return false;">
<?php echo CHtml::hiddenField('action','addReviewToOrder')?>
<?php echo CHtml::hiddenField('currentController','store')?>
<?php echo CHtml::hiddenField('merchant_id',$order_info['merchant_id'])?>
<?php echo CHtml::hiddenField('order_id_token',$order_info['order_id_token'])?>
<?php echo CHtml::hiddenField('initial_review_rating','')?>	        

   <div class="col-md-12 border review_wrap">
     <div>
       <div class="raty-stars" data-score="5"></div>   
     </div>
     <div>
     <?php echo CHtml::textArea('review_content','',array(
        'required'=>true,
        'class'=>"grey-inputs"
     ))?>
     
     <div class="row padtop15">
       <div class="col-md-6"><?php echo Yii::t("default","Review as [customer_name]",array(
         '[customer_name]'=>$order_info['customer_name']
       ))?></div>
       <div class="col-md-6 text-right">         
         <?php echo t("Anonymous")?>
         <?php echo CHtml::checkBox('as_anonymous',false,array(
          'value'=>1,
          'class'=>"icheck"
         ))?>
       </div>
     </div>
     
     </div>
     <div class="top10">
        <button class="orange-button inline" type="submit"><?php echo t("PUBLISH REVIEW")?></button>
     </div>
   </div> <!--col-->	      
     
</form>
</div> <!--review-input-wrap-->
       
       
     </div> <!--box-grey-->
    
    </div>
    <div class="col-md-4"></div>
  </div>
  
  </div> 
</div>  