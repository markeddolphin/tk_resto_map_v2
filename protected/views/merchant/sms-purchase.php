

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','initSelectPaymentProvider')?>
<?php echo CHtml::hiddenField('controller',"merchant")?>

<p class="uk-text-success"><?php echo Yii::t("default","To receive SMS notification and to send SMS to customer you need to purchase SMS Credits. Please find below our SMS package.")?></p>

<?php if ( $res=Yii::app()->functions->getSMSPackage() ):?>
<div class="uk-grid sms-package-wrap">
  <?php foreach ($res as $val):?>
   <div class="uk-width-1-3">     
     <div class="uk-panel uk-panel-box">
       <h3><?php echo $val['title']?></h3>
       <h4 class="uk-text-muted"><?php echo $val['description']?></h4>
       <?php if ( $val['promo_price']>=1):?>
         <p class="promo"><?php echo adminCurrencySymbol().standardPrettyFormat($val['price'])?></p>
         <p><?php echo adminCurrencySymbol().standardPrettyFormat($val['promo_price'])?></p>
       <?php else :?>
        <p><?php echo adminCurrencySymbol().standardPrettyFormat($val['price'])?></p>
       <?php endif;?>
       <p><?php echo Yii::t("default","SMS Credit")?>: <?php echo $val['sms_limit']?></p>
       
       <p class="uk-text-muted"><?php echo Yii::t("default","Take this package")?>
       <?php echo CHtml::radioButton('sms_package_id',false,array(
        'value'=>$val['sms_package_id'],
        'class'=>"icheck"
       ))?></p>
     </div>
   </div>  
   <?php endforeach;?>
</div>
<?php else :?>
<p class="uk-text-danger"><?php echo Yii::t("default","No SMS Package found. please come back again later.")?></p>
<?php endif;?>


<p class="uk-text-warning uk-text-small"><?php echo Yii::t("default","Notice: Paying using Offline Credit Card will not automaticaly credited your SMS. the SMS will be credited once your transactions is approved by administrator.")?></p>

<div style="margin-top:20px;margin-bottom:20px;">
<?php //echo Yii::app()->functions->adminPaymentList()?>
<?php 
	 $this->renderPartial('/front/payment-list',array(	   
	   'payment_list'=>FunctionsV3::getAdminPaymentList(),
	   'merchant_id'=>Yii::app()->functions->getMerchantID()
	 ));
	 ?>
</div>

<div class="uk-form-row">
<input type="submit" value="<?php echo Yii::t("default","Buy")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>