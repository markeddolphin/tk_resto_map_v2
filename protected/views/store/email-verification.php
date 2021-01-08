<?php
$this->renderPartial('/front/default-header',array(
   'h1'=>t("Email Verification"),
   'sub_text'=>t("Your registration is almost complete")
));?>

<?php 
if (isset($_GET['checkout'])){
	$this->renderPartial('/front/order-progress-bar',array(
	   'step'=>4,
	   'show_bar'=>true
	));
}
?>

<div class="sections section-grey2 section-mobile-verification section-orangeform">
 <div class="container">
   <div class="row top30">
     
     <div class="inner">
         <h1><?php echo t("We have sent verification code to your email address")?></h1>
	     <div class="box-grey rounded">	     	     	    
	     <form class="forms bottom20" id="forms" onsubmit="return false;">
	     <?php echo CHtml::hiddenField('action','verifyEmailCode')?>         
         <?php echo CHtml::hiddenField('client_id',$data['client_id']) ?>
         <?php echo CHtml::hiddenField('currentController','store')?>
         
         <?php if (isset($_GET['checkout'])):?>
         <?php echo CHtml::hiddenField('redirect', Yii::app()->createUrl('/store/paymentoption') )?>
         <?php endif;?>
                  
         <?php FunctionsV3::sectionHeader('Please enter you verification code');?>
               
         
         <?php 
		  echo CHtml::textField('code','',array(
		    'class'=>"grey-fields",
		    'data-validation'=>"required",
		    'maxlength'=>14
		  ));
		  ?>		 		  		  
		  <input type="submit" value="<?php echo t("Submit")?>" class="green-button inline">		  
		  
	     
	     </form>
	     
	     <p class="text-small text-center block">
	     <?php echo t("Did not receive your verification code")?>? 
	     <a href="javascript:;" class="resend-email-code"><?php echo t("Click here to resend")?></a>
	     </p>
	     
	     </div> <!--box-grey-->
     </div> <!--inner-->
   
   </div> <!--row-->
 </div> <!--container-->
</div> <!-- section-grey-->
