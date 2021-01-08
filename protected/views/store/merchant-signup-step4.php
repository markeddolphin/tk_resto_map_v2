<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Restaurant Signup"),
   'sub_text'=>t("step 4 of 4")
));

/*PROGRESS ORDER BAR*/
$this->renderPartial('/front/progress-merchantsignup',array(
   'step'=>4,
   'show_bar'=>true
));

?>

<div class="sections section-grey2 section-orangeform ">

 <div class="container">
    <div class="row top30">    
       <div class="inner">       
           <?php if ($continue==TRUE):?>
	           <h1><?php echo t("Almost Done..")?></h1>
	           <div class="box-grey rounded">	  
	              <p><?php echo t("Your merchant registration is successfull. An email was sent to your email with activation code.")?></p>
	              
	             <form class="forms" id="forms" onsubmit="return false;">
				 <?php echo CHtml::hiddenField('action','activationMerchant')?> 
				 <?php echo CHtml::hiddenField('currentController','store')?>
				 <?php echo CHtml::hiddenField('token',$_GET['token'])?> 
				 
				 <?php FunctionsV3::sectionHeader('Enter Activation Code');?>
				 <div class="top10">				   
				  <?php echo CHtml::textField('activation_code',
					  ''
					  ,array(
					  'class'=>'grey-fields',
					  'data-validation'=>"required",
					  'maxlength'=>10,
					  'placeholder'=>t("Enter Activation Code")
				  ))?> 
				  <input type="submit" value="<?php echo t("Submit")?>" 
				  class="black-button inline medium">
				 </div>
				 
				 <div class="top15">
				 <p class="text-small"><?php echo t("Did not receive activation code? click")?> <a class="resend-activation-code"href="javascript:;"><?php echo t("here")?></a> <?php echo Yii::t("default","to resend again.")?></p>
				 </div>
				 
				 </form>	              
	              
	           </div> <!--box-->
           <?php else :?>
              <p><?php echo t("Sorry but we cannot find what you are looking for.")?></p>
           <?php endif;?>
       </div> <!--inner-->
    </div> <!--row-->   
 </div> <!--container-->
 
</div> <!--sections-->