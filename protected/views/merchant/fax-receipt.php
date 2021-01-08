<?php 
$FunctionsK=new FunctionsK;
$data=$_GET;
?>
<?php $merchant_id=Yii::app()->functions->getMerchantID();?>
<?php if ( $res=$FunctionsK->getPackageFaxTransByMerchant($data['id'],$merchant_id)):?>  
  <h3><?php echo Yii::t("default","Thank you.")?></h3>
  <p><?php echo Yii::t("default",'Thank you for purchasing Fax Services. Please find your receipt details below')?>:</p>
  
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Reference #")?>:</label>  
  <span class="uk-text-muted"><?php echo $res['id']?></span>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Payment Type")?>:</label>  
  <span class="uk-text-muted"><?php echo Yii::app()->functions->paymentCode($res['payment_type'],true)?></span>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Package Type")?>:</label>  
  <span class="uk-text-muted"><?php echo $res['title']?></span>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Price")?>:</label>  
  <span class="uk-text-muted"><?php echo standardPrettyFormat($res['package_price'])?></span>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Fax Credits")?>:</label>  
  <span class="uk-text-muted"><?php echo $res['fax_limit']?></span>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Date of purchase")?>:</label>  
  <span class="uk-text-muted"><?php echo prettyDate(date('c'),true)?></span>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Payment Status")?>:</label>  
  <span class="uk-text-muted"><?php echo $res['status']?></span>
  </div>
  
  <?php if ($res['status']=="pending"):?>  
  <p class="uk-text-danger"><?php echo Yii::t("default","Note: Your Fax Credits has not been credited. Your Fax Credits Will be credited once the admin approved your payment transaction")?>.</p>
  <?php endif;?>
  
  <div class="spacer"></div>
  <a href="<?php echo Yii::app()->request->baseUrl;?>/merchant/faxsettings">
  <?php echo t("Click here to go to Fax settings")?>
  </a>
  
<?php else :?>
<p><?php echo Yii::t("default","Sorry but we cannot find what you are looking for.")?></p>
<?php endif;?>