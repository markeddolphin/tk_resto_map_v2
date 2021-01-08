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
       
          <h1><?php echo t("Pay with IPay88")?></h1>
          <div class="box-grey rounded">	
          <?php if(!empty($error)):?>
           <p><?php echo $error;?></p>
          <?php endif;?>
          
          <form method="post" name="ePayment" action="https://www.mobile88.com/ePayment/entry.asp">
                    
          <?php 
          dump($credentials);          
          //dump($data);
          echo CHtml::hiddenField('MerchantCode',$credentials['merchant_code']);
          echo CHtml::hiddenField('PaymentId','');
          echo CHtml::hiddenField('RefNo',$order_id);
          echo CHtml::hiddenField('Amount',$amount_to_pay);
          echo CHtml::hiddenField('Currency',$currency);
          echo CHtml::hiddenField('ProdDesc',$payment_description);
          
          echo CHtml::hiddenField('UserName',$data['full_name']);
          echo CHtml::hiddenField('UserEmail',$data['email_address']);
          echo CHtml::hiddenField('UserContact',$data['contact_phone']);
          
          echo CHtml::hiddenField('Signature',$signature);
          echo CHtml::hiddenField('ResponseURL',$response_url);
          echo CHtml::hiddenField('BackendURL',$backend_url);
          
          if (isset($credentials['language'])){
          	if(!empty($credentials['language'])){
          		echo CHtml::hiddenField('Lang',$credentials['language']);
          	}
          }
          ?>
          
          <div class="row top10">
			  <div class="col-md-3"><?php echo t("Amount")?></div>
			  <div class="col-md-8">
			    <?php echo CHtml::textField('Amount',
				  $amount_to_pay
				  ,array(
				  'class'=>'grey-fields full-width',
				  'disabled'=>true
				  ))?>
			  </div>
		  </div>
		  
		 <div class="row top10">
		  <div class="col-md-3"></div>
		  <div class="col-md-8">
		  <input type="submit" value="<?php echo Yii::t("default","Pay Now")?>" class="black-button inline medium">
		  </div>
		</div>	
		
		</form>				
          
       </div> <!--rounded-->
          
          
          
          <a href="<?php echo $back_url?>">
          <?php echo t("Click here to change payment option")?>
          </a>
          
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->