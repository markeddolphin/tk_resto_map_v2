<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Restaurant Signup"),
   'sub_text'=>t("step 3 of 4")
));

/*PROGRESS ORDER BAR*/
$this->renderPartial('/front/progress-merchantsignup',array(
   'step'=>3,
   'show_bar'=>true
));

?>

<div class="sections section-grey2 section-orangeform section-merchant-payment">

 <div class="container">

    <div class="row top30">    
       <div class="inner">       
          <h1><?php echo t("Choose Payment option")?></h1>
          <div class="box-grey rounded">	  
          
          <?php if ($merchant):?>
          <?php 
               $merchant_id=$merchant['merchant_id']; 
               if ($renew==TRUE){
  	               $merchant['package_price']=1;
               }               
          ?>
	          <?php if ($merchant['package_price']>=1):?>
	          
	             <form class="uk-form uk-form-horizontal forms" id="forms" onsubmit="return false;">
				 <?php echo CHtml::hiddenField('action','merchantPayment')?>
				 <?php echo CHtml::hiddenField('currentController','store')?>
				 <?php echo CHtml::hiddenField('token',$_GET['token'])?>  
				 
				     <?php if ($renew==TRUE):?>
				        <?php echo CHtml::hiddenField("renew",1);?>
                        <?php echo CHtml::hiddenField("package_id",$package_id);?>
                         <?php if (is_numeric($package_id)):?>
                         
                            <?php 
							 $this->renderPartial('/front/payment-list',array(
							   'merchant_id'=>$merchant_id,
							   'payment_list'=>FunctionsV3::getAdminPaymentList(),						   
							 ));
							 ?>  
                         
                         <?php else :?>
                            <p class="text-warning"><?php echo t("No Selecetd Membership package. Please go back.")?></p>
                         <?php endif;?>
				     <?php else :?>
				         <?php 
						 $this->renderPartial('/front/payment-list',array(
						   'merchant_id'=>$merchant_id,
						   'payment_list'=>FunctionsV3::getAdminPaymentList(),						   
						 ));
						 ?>
				     <?php endif;?>
				 
				 <div class="top10">
				   <input type="submit" value="<?php echo t("Next")?>" class="green-button medium inline">
				 </div>    
				     
				 </form>
				 
				 <!--CREDIT CART-->
			     <?php 
			     $this->renderPartial('/front/credit-cart-merchant',array(
				   'merchant_id'=>$merchant_id	   
				 ));
				 ?>     
			     <!--END CREDIT CART-->
				 
	          <?php else :?> 
	             <p class="text-success">
	             <?php echo t("You have selected a package which is free of charge. You can now proceed to next steps.")?>
	             </p>
	             
	             <div class="top10">
				   <input type="submit"
						 data-token="<?php echo $_GET['token']?>" 
						 value="<?php echo t("Next")?>" class="next_step_free_payment green-button medium inline">
				 </div>    
	             
	          <?php endif;?>
             
          <?php else :?>
            <p class="text-danger"><?php echo t("Sorry but we cannot find what you are looking for.")?></p>
          <?php endif;?>
             
          
          </div> <!--box-grey-->
       </div> <!--inner-->    
    </div> <!--row-->
 
 </div> <!--container-->

</div> <!--sections-->