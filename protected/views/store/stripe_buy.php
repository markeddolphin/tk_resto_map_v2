<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));
?>

<div class="sections section-grey2 section-orangeform">
<div class="container">  
<div class="row top30">
<div class="inner">
<h1><?php echo t("Pay using Stripe Payment")?></h1>

<div class="box-grey rounded">	     
<table class="table">
 <tr>
 <tr>
   <td><?php echo t("Description")?></td>
   <td><?php echo $payment_description?></td> 
 </tr>
 <?php if($fee>0.001):?>
  <tr>
   <td><?php echo t("Card Fee")?></td>
   <td><?php echo FunctionsV3::prettyPrice($fee)?></td> 
 </tr>
 
 <tr>
  <td><?php echo t("Amount")?></td>
  <td><?php echo FunctionsV3::prettyPrice( ($amount/100)-$fee  )?></td>
 </tr> 
 
 <tr>
  <td><?php echo t("Total")?></td>
  <td><?php echo FunctionsV3::prettyPrice( ($amount) /100)?></td>
 </tr> 
 
 <?php else :?>
 
 <tr>
  <td><?php echo t("Total")?></td>
  <td><?php echo FunctionsV3::prettyPrice($amount/100)?></td>
 </tr> 
 
 
 <?php endif;?> 
 <tr>
  <td colspan="2">
   <button class="btn paynow_stripe"><?php echo t("Pay Now")?></button>  
  </td>
 </tr>
</table>

<div class="top25">
     <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
     <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
    </div>
    
</div>

</div>
</div>
</div>
</div>