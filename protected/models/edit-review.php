
<div class="container view-food-item-wrap">

<?php if ( $res=Yii::app()->functions->getReviewsById($this->data['id']) ):?>
<form id="frm-modal-update-review" class="frm-modal-update-review" method="POST" onsubmit="return false;" >
<?php echo CHtml::hiddenField('action','updateReview');?>
<?php echo CHtml::hiddenField('id',$this->data['id']);?>
<?php echo CHtml::hiddenField('web_session_id',$this->data['web_session_id']);?>
 
<div class="section-label">
    <a class="section-label-a">
      <span class="bold">
      <?php echo t("Edit your review")?></span>
      <b></b>
    </a>     
</div>  
<div class="row">
<div class="col-md-12">
<?php  
 echo CHtml::textArea('review_content',$res['review'],array(
  'class'=>"grey-inputs"
 ));
 ?>
 </div>
</div> <!--row-->

<div class="row food-item-actions top10">
  <div class="col-md-6 "></div>
  <div class="col-md-3 ">
  <input type="submit" class="orange-button upper-text" value="<?php echo t("Submit")?>">
  </div>
  <div class="col-md-3">
  <a href="javascript:close_fb();" class="center upper-text green-button inline" >
  <?php echo t("Cancel")?>
  </a>
  </div>
</div>

</form>
<?php else :?>
 <p class="text-danger"><?php echo t("Error: review not found.")?></p>
<?php endif;?>
  
</div> <!--container-->
<script type="text/javascript">
$.validate({ 	
    form : '#frm-modal-update-review',    
    onError : function() {      
    },
    onSuccess : function() {     
      form_submit('frm-modal-update-review');
      return false;
    }  
})
</script>