<?php 
$data=$_GET;

$continue=true;
$msg="";
$paypal_con=Yii::app()->functions->getPaypalConnectionAdmin();   
$paypal=new Paypal($paypal_con);

if ($res_paypal=$paypal->getExpressDetail()){		
} else {
	 $continue=false;
	 $msg="Paypay Error: ".$paypal->getError();
}
?>
<div class="page merchant-payment-option">
  <div class="mainx">   
  <div class="innerx">
  <div class="spacer"></div>
  
  <?php if ( $continue==TRUE):?>
  <h3><?php echo Yii::t("default","Paypal Verification")?></h3>
  
  <form class="uk-form uk-form-horizontal forms" id="forms" onsubmit="return false;">
  <?php echo CHtml::hiddenField('action','PayPaypal')?>
  <?php echo CHtml::hiddenField('type',isset($_GET['type'])?$_GET['type']:'')?>
  <?php echo CHtml::hiddenField('package_id',isset($_GET['package_id'])?$_GET['package_id']:'')?>
  <?php echo CHtml::hiddenField('token',$_GET['token'])?>    

  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal Name")?></label>
  <span class="uk-text-bold"><?php echo $res_paypal['FIRSTNAME']." ".$res_paypal['LASTNAME']?></span>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal Email")?></label>
  <span class="uk-text-bold"><?php echo $res_paypal['EMAIL']?></span>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Selected Package")?></label>
  <span class="uk-text-bold"><?php echo ucwords($res_paypal['L_NAME1'])?></span>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Amount to pay")?></label>
  <span class="uk-text-bold"><?php echo $res_paypal['CURRENCYCODE']." ".$res_paypal['AMT']?></span>
  </div>

   
  <div class="uk-form-row">   
    <input type="submit" value="<?php echo Yii::t("default","Pay Now")?>" class="uk-button uk-button-success uk-width-1-3">
  </div>
  
  </form>
  
  <?php else :?>
  <p class="uk-text-danger"><?php echo $msg;?></p>
  <?php endif;?>
  
  </div>
  </div> <!--main-->
</div>  <!--page-->

<div class="spacer"></div>