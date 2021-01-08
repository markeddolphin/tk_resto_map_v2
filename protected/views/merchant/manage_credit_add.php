
<div class="uk-width-1">
<a href="<?php echo Yii::app()->createUrl('/merchant/manage_credit_cards',array('do'=>"add"))?>" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->createUrl('/merchant/manage_credit_cards')?>" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<div class="spacer"></div>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">

<?php 
echo CHtml::hiddenField('action','AddUpdateMerchantCC');
if(isset($data['mt_id'])){
   echo CHtml::hiddenField('id',isset($data['mt_id'])?$data['mt_id']:"");
}
?>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Card name")?></label>
  <?php
  echo CHtml::textField('card_name',
  isset($data['card_name'])?$data['card_name']:''
  ,array(
   'class'=>'uk-form-width-large',
   'data-validation'=>"required"  
  ));
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Credit Card Number")?></label>
  <?php  
  $decryp_card = isset($data['credit_card_number'])?$data['credit_card_number']:'';
  if(isset($data['encrypted_card'])){
	try {
		$decryp_card = CreditCardWrapper::decryptCard($data['encrypted_card']);
	} catch (Exception $e) {
		$decryp_card = Yii::t("default","Caught exception: [error]",array(
		  '[error]'=>$e->getMessage()
		));
	}
  }
  
  echo CHtml::textField('credit_card_number',
  $decryp_card
  ,array(
   'class'=>'uk-form-width-large numeric_only',
   'data-validation'=>"required" ,
   'maxlength'=>16
  ));
  ?>
</div>

<div class="uk-form-row">                  
  <label class="uk-form-label"><?php echo t("Exp. month")?></label>
      <?php echo CHtml::dropDownList('expiration_month',
      isset($data['expiration_month'])?$data['expiration_month']:''
      ,
      Yii::app()->functions->ccExpirationMonth()
      ,array(
       'class'=>'uk-form-width-large',   
       'data-validation'=>"required"  
      ))?>
</div>       


<div class="uk-form-row">                  
  <label class="uk-form-label"><?php echo t("Exp. year")?></label>
      <?php echo CHtml::dropDownList('expiration_yr',
      isset($data['expiration_yr'])?$data['expiration_yr']:''
      ,
      Yii::app()->functions->ccExpirationYear()
      ,array(
       'class'=>'uk-form-width-large',   
       'data-validation'=>"required"  
      ))?>
</div>    

 <div class="uk-form-row">      
 <label class="uk-form-label"><?php echo t("CVV")?></label>            
      <?php echo CHtml::textField('cvv',
      isset($data['cvv'])?$data['cvv']:''
      ,array(
       'class'=>'uk-form-width-large numeric_only',     
       'data-validation'=>"required",
       'maxlength'=>4
      ))?>
   </div>             
   
   <div class="uk-form-row">                     
   <label class="uk-form-label"><?php echo t("Billing Address")?></label>            
      <?php echo CHtml::textField('billing_address',
      isset($data['billing_address'])?$data['billing_address']:''
      ,array(
       'class'=>'uk-form-width-large',          
       'data-validation'=>"required"  
      ))?>
   </div>            

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>