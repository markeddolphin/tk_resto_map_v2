
<div class="page-right-sidebar payment-option-page">
  <div class="main">  
  <?php if ( $res=Yii::app()->functions->getWithdrawalInformationByToken($_GET['id'])):?>
  
  <!--<form id="forms" class=forms uk-form" method="POST" onsubmit="return false;">        -->
  <form class="uk-form uk-form-horizontal forms" id="forms" onsubmit="return false;">
    <?php echo CHtml::hiddenField('action','cancelWithdrawal')?>
    <?php echo CHtml::hiddenField('id',$_GET['id'])?>
    
    <?php if ($res['status']=="approved" || $res['status']=="pending"):?>
    
    <h3><?php echo t("Cancel Withdrawal")?></h3>
    
	 
	<div class="uk-form-row">
	<label class="uk-form-label"><?php echo Yii::t("default","Amount")?>:</label>
	<?php echo displayPrice(adminCurrencySymbol(),normalPrettyPrice($res['amount']))?>
	</div>
	
	<div class="uk-form-row">
	<label class="uk-form-label"><?php echo Yii::t("default","Payment Type")?>:</label>
	<?php echo $res['payment_type']?>
	</div>
	
	<div class="uk-form-row">
	<label class="uk-form-label"><?php echo Yii::t("default","Payment Method")?>:</label>
	<?php echo $res['payment_method']?>
	</div>
	
	<?php if ($res['payment_method']=="paypal"):?>
		<div class="uk-form-row">
		<label class="uk-form-label"><?php echo Yii::t("default","Account")?>:</label>
		<?php echo $res['account']?>
		</div>
	<?php else :?>
	    <div class="uk-form-row">
		<label class="uk-form-label"><?php echo Yii::t("default","Account")?>:</label>
		<?php echo $res['bank_account_number']?>
		</div>
	<?php endif;?>
    
    <p><?php echo t("To cancel your withdrawal request please click below")?></p>
    
    <div class="uk-form-row">   
    <input type="submit" value="<?php echo Yii::t("default","Cancel Withdrawal")?>" class="uk-button uk-button-success uk-width-1-5">
    </div>
    
    <?php else :?>
      <p class="uk-text-danger"><?php echo t("Sorry but you cannot cancel this withdrawal anymore")?></p>
      <p><?php echo t("Withdrawal Status:")?> <span class="uk-text-warning"><?php echo $res['status']?></span></p>
    <?php endif;?>
    
  </form>
    
  <?php else :?>
  <p class="uk-text-danger"><?php echo t("Sorry but we cannot find what you are looking for.")?></p>
  <?php endif;?>
  </div>
</div>