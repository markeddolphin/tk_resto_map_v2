<?php
$this->renderPartial('/front/banner-receipt',array(
  'h1'=>t("Restaurant Signup"),
  'sub_text'=>t("signup process completed")
));
?>

<div class="sections section-grey2 section-orangeform ">

 <div class="container">          
     <div class="inner">        
        
           <div class="text-center bottom10">
		   <i class="ion-ios-checkmark-outline i-big-extra green-text"></i>
		   </div>
	   
           <h1><?php echo t("Congratulation. Your membership has been renew.")?></h1>
           <div class="box-grey rounded">	  
	           <p class="text-center top15">           	           
	           <a href="<?php echo Yii::app()->createUrl('/merchant')?>">
	           <?php echo t("Click here to go back to merchant portal")?>
	           </a>
	           </p>
           </div> <!--box-->        
     </div> <!--inner-->
     
 </div> <!--container--> 
 
</div>