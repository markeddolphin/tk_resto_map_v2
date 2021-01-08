
<div class="box-grey rounded" style="margin-top:0;">

<form class="krms-forms" id="frm-cc"  onsubmit="return false;">
<?php echo CHtml::hiddenField('action','updateClientCC')?>
<?php 
if (isset($data['cc_id'])){
	echo CHtml::hiddenField('cc_id', $data['cc_id']);
}
?>

<div class="row bottom10">
  <div class="col-md-6">
    <p class="text-small"><?php echo t("Card name")?></p>
    <?php 
	  echo CHtml::textField('card_name',isset($data['card_name'])?$data['card_name']:'',
	  array(
	    'class'=>'grey-fields full-width',
	    'data-validation'=>"required"
	  ));
	  ?>     
  </div>
  <div class="col-md-6">
    <p class="text-small"><?php echo t("Credit Card Number")?></p>
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
	isset($data['credit_card_number'])?$decryp_card:''
	,
	array(
	'class'=>'grey-fields full-width numeric_only',
	'data-validation'=>"required",
	'maxlength'=>16
	));
	?>
  </div>
</div> <!--row-->


<div class="row bottom10">
  <div class="col-md-6">
    <p class="text-small"><?php echo t("Exp. month")?></p>
    <?php echo CHtml::dropDownList('expiration_month',
      isset($data['expiration_month'])?$data['expiration_month']:''
     , 
      Yii::app()->functions->ccExpirationMonth()
      ,array(
       'class'=>'grey-fields full-width',
       'placeholder'=>Yii::t("default","Exp. month"),
       'data-validation'=>"required"  
      ))?>
  </div>
  <div class="col-md-6">
    <p class="text-small"><?php echo t("Exp. year")?></p>
   <?php echo CHtml::dropDownList('expiration_yr',
   isset($data['expiration_yr'])?$data['expiration_yr']:''
   ,
      Yii::app()->functions->ccExpirationYear()
      ,array(
       'class'=>'grey-fields full-width',
       'placeholder'=>Yii::t("default","Exp. year") ,
       'data-validation'=>"required"  
      ))?>
  </div>
</div> <!--row-->

<div class="row bottom10">
  <div class="col-md-6">
    <p class="text-small"><?php echo t("Billing Address")?></p>
    <?php 
	  echo CHtml::textField('billing_address',isset($data['billing_address'])?$data['billing_address']:'',
	  array(
	    'class'=>'grey-fields full-width',
	    'data-validation'=>"required"
	  ));
	  ?>     
  </div>
  <div class="col-md-6">
    <p class="text-small"><?php echo t("CVV")?></p>
	<?php 
	echo CHtml::textField('cvv',
	isset($data['cvv'])?$data['cvv']:''
	,
	array(
	'class'=>'grey-fields full-width',
	'data-validation'=>"required"
	));
	?>
  </div>
</div> <!--row-->


<div class="row top10">
  <div class="col-md-2">  
  <button type="submit" class="krms-forms-btn green-button medium inline"><?php echo t("Save")?></button>
  </div>
  <div class="col-md-5">
    <a class="green-text top10 block" href="<?php echo Yii::app()->createUrl('/store/profile/?tab=4')?>">
	<i class="ion-ios-arrow-thin-left"></i> <?php echo t("Back")?>
	</a>
  </div>
</div>

</form>
</div> <!--box-->