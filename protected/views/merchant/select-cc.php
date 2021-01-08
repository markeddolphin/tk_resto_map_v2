<?php 
$merchant_id=Yii::app()->functions->getMerchantID();
?>
<form class="uk-form uk-form-horizontal forms uk-panel uk-panel-box" id="forms" onsubmit="return false;"> 
<?php echo CHtml::hiddenField('action','payCC')?>
<?php echo CHtml::hiddenField('type',isset($_GET['type'])?$_GET['type']:'' )?>
<?php echo CHtml::hiddenField('package_id',isset($_GET['package_id'])?$_GET['package_id']:'')?>

<h3><?php echo Yii::t("default","Credit Card information")?> <a href="javascript:;" class="cc-add uk-button"><?php echo Yii::t("default","Add new card")?></a></h3>


<p class="uk-text-muted"><?php echo Yii::t("default","select credit card below")?></p>
<ul class="uk-list uk-list-striped uk-list-cc">  
</ul>

<div class="spacer"></div>
<div class="uk-form-row">
<input type="submit" value="<?php echo Yii::t("default","Next")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>          

</form>

<div class="spacer"></div>

<div class="cc-add-wrap hidden">

<form id="frm-creditcard" class="frm-creditcard uk-panel uk-panel-box uk-form" method="POST" onsubmit="return false;">

   <p class="uk-text-bold"><?php echo Yii::t("default","New Card")?></p>
   <?php echo CHtml::hiddenField('action','addCreditCardMerchant')?>   
   <?php echo CHtml::hiddenField('merchant_id',$merchant_id)?>
   
   <div class="uk-form-row">                  
      <?php echo CHtml::textField('card_name','',array(
       'class'=>'uk-width-1-1',
       'placeholder'=>Yii::t("default","Card name"),
       'data-validation'=>"required"  
      ))?>
   </div>             
   
   <div class="uk-form-row">                  
      <?php echo CHtml::textField('credit_card_number','',array(
       'class'=>'uk-width-1-1 numeric_only',
       'placeholder'=>Yii::t("default","Credit Card Number"),
       'data-validation'=>"required",
       'maxlength'=>20
      ))?>
   </div>             
   
   <div class="uk-form-row">                  
      <?php echo CHtml::dropDownList('expiration_month','',
      Yii::app()->functions->ccExpirationMonth()
      ,array(
       'class'=>'uk-width-1-1',
       'placeholder'=>Yii::t("default","Exp. month"),
       'data-validation'=>"required"  
      ))?>
   </div>             
   
   <div class="uk-form-row">                  
      <?php echo CHtml::dropDownList('expiration_yr','',
      Yii::app()->functions->ccExpirationYear()
      ,array(
       'class'=>'uk-width-1-1',
       'placeholder'=>Yii::t("default","Exp. year") ,
       'data-validation'=>"required"  
      ))?>
   </div>             
   
   <div class="uk-form-row">                  
      <?php echo CHtml::textField('cvv','',array(
       'class'=>'uk-width-1-1',
       'placeholder'=>Yii::t("default","CVV"),
       'data-validation'=>"required",
       'maxlength'=>4
      ))?>
   </div>             
   
   <div class="uk-form-row">                  
      <?php echo CHtml::textField('billing_address','',array(
       'class'=>'uk-width-1-1',
       'placeholder'=>Yii::t("default","Billing Address") ,
       'data-validation'=>"required"  
      ))?>
   </div>             
   
   <div class="uk-form-row">   
      <input type="submit" value="<?php echo Yii::t("default","Add Credit Card")?>" class="uk-button uk-button-success uk-width-1-1">
   </div>
</form>
</div> 