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
	   
           <h1><?php echo t("Congratulation your merchant is now ready.")?></h1>
           <div class="box-grey rounded">	  
	           <p class="text-center top15">           
	           <?php echo t("login to your account")?> 
	           <a href="<?php echo Yii::app()->createUrl('/merchant')?>"><?php echo t("click here")?></a>
	           </p>
           </div> <!--box-->        
     </div> <!--inner-->
     <?php else :?>
	  <?php 
	  $this->renderPartial('/front/404-page');
	  ?>
	 <?php endif;?>
 </div> <!--container--> 
 
</div>