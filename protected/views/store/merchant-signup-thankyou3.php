<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Restaurant Signup"),
   'sub_text'=>t("signup process completed")
));

?>

<div class="sections section-grey2 section-orangeform ">

 <div class="container">     
     <?php if ($data):?>
     <div class="inner">        
        
           <div class="text-center bottom10">
		   <i class="ion-ios-checkmark-outline i-big-extra green-text"></i>
		   </div>
	   
           <h1><?php echo t("Congratulation for signing up.")?>!</h1>
           <div class="box-grey rounded">	  
	           <p class="text-center top15">           
	           <?php echo t("Please check your email for bank deposit instructions")?>
	           
	           <div class="top15">
	           <p class="text-center">           
	           <p><?php echo t("You will receive email once your merchant has been approved. Thank You.")?></p>
	           </div>
	           
	           <a href="<?php echo Yii::app()->createUrl('/store')?>" 
               class="top25 green-text block text-center"><i class="ion-ios-arrow-thin-left"></i> <?php echo t("back to homepage")?></a>
           </div> <!--box-->        
     </div> <!--inner-->
     <?php else :?>
	  <?php 
	  $this->renderPartial('/front/404-page');
	  ?>
	 <?php endif;?>
 </div> <!--container--> 
 
</div>