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
  
  <?php
  /*dump($amount_to_pay);
  dump($payment_description);
  dump($credentials);
  dump($order_id);*/
  ?>
  
  <h1><?php echo t("Ipay")?></h1>
  <div class="box-grey rounded">	     
  
  <?php if (isset($_GET['errormsg'])):?>
  <p class="text-danger"><?php echo $_GET['errormsg']?></p>
  <?php endif;?>
  
  <form method=POST action="https://community.ipaygh.com/gateway">
    <?php
    echo CHtml::hiddenField('merchant_key', trim($credentials['merchant_key']) );
    echo CHtml::hiddenField('invoice_id',$order_id);
    echo CHtml::hiddenField('total', trim($amount_to_pay) );
    echo CHtml::hiddenField('currency', Yii::app()->functions->adminCurrencyCode() );
    
    echo CHtml::hiddenField('ipn_url', websiteUrl()."/ipay_ipn_url");
    echo CHtml::hiddenField('success_url', websiteUrl()."/ipay_success_url/?invoice_id=".$order_id);
    echo CHtml::hiddenField('cancelled_url', websiteUrl()."/paymentoption");
    //echo CHtml::textField('deferred_url', websiteUrl()."/ipay_deferred_url");
    ?>
    
    <div class="row top10">
	  <div class="col-md-3"><?php echo t("Amount")?></div>
	  <div class="col-md-8">
	    <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
	  </div>
	</div>
    
	<div class="row top10">
	  <div class="col-md-6">
        <input type="submit" name="submit" value="<?php  echo t("Make Payment")?>" class="green-button medium inline block">
      </div>
    </div> <!--row-->
    
  </form>
  
  <div class="top25">
    <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
     <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
  </div>
   
  </div> <!--box-grey-->
  
  </div>
  </div>
</div>
</div>