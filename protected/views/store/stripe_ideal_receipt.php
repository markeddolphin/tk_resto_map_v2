<?php 
?>

<div class="orange_header">
	<p class="center title">Receipt</p>
	
	<div class="container">
	<div class="row">
	
	   <div class="col-xs-6">
	     <img class="logo" src="<?php echo AddonMobileApp::getMerchantLogo($data['merchant_id'])?>" alt="" title="">
	   </div>
	   
	   <div class="col-xs-6 text-right">
	     <p>
	     <?php echo clearString($data['merchant_name'])?></br>
	     <?php echo FunctionsV3::prettyPrice($data['total_w_tax'])?>
	     </p>
	   </div>
	</div>
	</div>	
</div> <!-- orange_header-->

<div class="container text-center stripe_ideal_receipt">
    <h2><?php echo t("Thank You")?></h2>
    <p><?php echo Yii::t("default","Your order has been placed. Reference # [order_id]",array(
     '[order_id]'=>$data['order_id']
    ))?></p>
    
    <i class="ion-checkmark-circled"></i>
</div> <!--container-->