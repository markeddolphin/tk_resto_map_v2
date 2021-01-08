<?php 
$FunctionsK=new FunctionsK();
?>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','initpaymentprovider')?>
<?php echo CHtml::hiddenField('controller',"merchant")?>
<?php echo CHtml::hiddenField('purchase',"fax_package")?>
<?php echo CHtml::hiddenField('return_url',websiteUrl()."/merchant/faxpurchase")?>

<?php if ( $res=$FunctionsK->getFaxPackage()):?>
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
       <p><?php echo Yii::t("default","Fax Credit")?>: <?php echo $val['fax_limit']?></p>
       
       <p class="uk-text-muted"><?php echo Yii::t("default","Take this package")?>
       <?php echo CHtml::radioButton('fax_package_id',false,array(
        'value'=>$val['fax_package_id'],
        'class'=>"icheck"
       ))?></p>
     </div>
   </div>  
   <?php endforeach;?>
</div>
<?php else :?>
<p class="uk-text-danger"><?php echo Yii::t("default","No Fax Service Package found. please come back again later")?>.</p>
<?php endif;?>



<div style="margin-top:20px;margin-bottom:20px;">
<?php //echo $FunctionsK->adminPaymentList();?>
<?php 
$this->renderPartial('/front/payment-list',array(	   
   'payment_list'=>FunctionsV3::getAdminPaymentList(),
   'merchant_id'=>''
 ));
?>
</div>

<div class="uk-form-row">
<input type="submit" value="<?php echo Yii::t("default","Buy")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>