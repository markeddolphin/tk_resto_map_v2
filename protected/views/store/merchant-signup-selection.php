<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Restaurant Signup"),
   'sub_text'=>t("Please Choose A Package Below To Signup")
));
?>

<div class="sections section-grey2 section-orangeform">

<div class="container">

   <div class="row top30">
   <?php if ( $disabled_membership_signup!=1):?>
     <div class="col-md-6 borderx">       
       <?php if ( FunctionsK::hasMembershipPackage()):?>  
       <div class="inner">
         <h1 class="center"><?php echo t("Membership")?></h1>
         <div class="box-grey rounded">	     	     	               
           <p class="center">
              <?php echo t("You will be charged a monthly or yearly fee")?>
           </p>
	           <div class="center top25">
	           <a href="<?php echo Yii::app()->createUrl("/store/merchantsignup")?>"
                class="rounded3 green-button medium inline">
	             <?php echo t("click here")?>
	           </a>
	           </div>
         </div> <!--box-grey-->
       </div><!-- inner-->       
       <?php endif;?>
     
     </div> <!--col-->
     <?php endif;?>
     
     <div class="col-md-6 borderx">
     
      <div class="inner">
         <h1 class="center"><?php echo t("Commission")?></h1>
         <div class="box-grey rounded">	     	     	               
           <p class="center">           
           <?php 
           if ( $commision_type=="fixed"){
           	   echo displayPrice($currency,$percent)." ".t("commission per order");
           } else echo standardPrettyFormat($percent)."% ".t("commission per order")
           ?>                
           </p>
	           <div class="center top25">
	           <a href="<?php echo Yii::app()->createUrl("/store/merchantsignupinfo")?>" 
               class="rounded3 black-button medium inline">
	             <?php echo t("click here")?>
	           </a>
	           </div>
         </div> <!--box-grey-->
       </div><!-- inner-->
     
     </div> <!--col-->
   </div> <!--row-->

</div> <!--container-->

</div><!-- sections-->