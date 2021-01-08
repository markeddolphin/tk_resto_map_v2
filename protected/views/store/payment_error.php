<div class="parallax-search" 
data-parallax="scroll" data-position="top" data-bleed="0" 
data-image-src="<?php echo assetsURL()."/images/b-4.jpg"?>">

<div class="search-wraps">
	<h1><?php echo t("oops something went wrong")?></h1>	
	<p></p>
</div> <!--search-wraps-->

</div> <!--parallax-container-->


<div class="sections">
   <div class="container center">
      <h3 class="text-danger"><?php echo isset($message)?$message:''?></h3>
            
      <?php if($trans_type=="buy"):?>
      <div class="top25">
		 <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
		 <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
      </div>
     <?php elseif ( $trans_type=="merchantreg"):?>
     <?php 
         if(!empty($package_id)){
         	$back_url = Yii::app()->createUrl('/store/merchantsignup',array(
	          'do'=>"step3",
	          'token'=>$ref_token,
	          'package_id'=>$package_id,
	          'renew'=>1
	        ));
         } else {
         	$back_url = Yii::app()->createUrl('/store/merchantsignup',array(
	          'do'=>"step3",
	          'token'=>$ref_token,          
	        ));
         }         
     ?>
       <div class="top25">
		 <a href="<?php echo $back_url;?>">
		 <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
       </div>
     <?php endif;?>

   </div>
</div>
