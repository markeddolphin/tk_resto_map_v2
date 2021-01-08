<?php
$this->renderPartial('/front/default-header',array(
   'h1'=>t("Forgot Password"),
  // 'sub_text'=>t("Your registration is almost complete")
));?>

<div class="sections section-grey2 section-orangeform">
 <div class="container">
   <div class="row top30">
   
   
    <div class="inner">
         <h1><?php echo t("Forgot Password")?></h1>
	     <div class="box-grey rounded">	     
	     
	     <form class="forms bottom20" id="forms" onsubmit="return false;">
	     <?php echo CHtml::hiddenField('action','changePassword')?>
         <?php echo CHtml::hiddenField('token',$_GET['token'])?>
         <?php echo CHtml::hiddenField('currentController','store')?>
         
          <div>
          <?php 
		  echo CHtml::passwordField('password',''  
		  ,array(
		   'class'=>"grey-fields full-width",
		  'data-validation'=>"required",
		  'placeholder'=>Yii::t("default","New Password")
		  ))
		  ?>
		  </div>
		  
		  <div class="top10">
		  <?php 
		  echo CHtml::passwordField('confirm_password',''  
		  ,array('class'=>"grey-fields full-width",
		   'data-validation'=>"required",
		  'placeholder'=>Yii::t("default","Confirm Password")
		  ))
		  ?>
		  </div>
		  
		  <div class="top10">
		  <input type="submit" value="<?php echo t("Submit")?>" class="green-button inline">
		  </div>
  
	     </form>
	    
	     
	     </div>	 <!--box-grey-->    
	</div> <!--inner-->     
   
   
   </div> <!--row-->
 </div> <!--container-->
</div> <!--sections-->