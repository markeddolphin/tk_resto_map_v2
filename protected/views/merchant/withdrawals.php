<?php 
$mtid=Yii::app()->functions->getMerchantID();
$wd_paypal_minimum=yii::app()->functions->getOptionAdmin('wd_paypal_minimum');
$wd_bank_minimum=yii::app()->functions->getOptionAdmin('wd_bank_minimum');

$query_date = date('c');					
$end_date=date('t F Y', strtotime($query_date));
$counrty_list=require_once 'CountryCode.php';

$default_country=Yii::app()->functions->getOptionAdmin("merchant_default_country");
$default_payout['paypal']=Yii::app()->functions->getOption("merchant_payout_account",$mtid);

$request_status=array(
  'pending','processing','approved'
);

$wd_enabled_paypal=yii::app()->functions->getOptionAdmin('wd_enabled_paypal');
$wd_bank_deposit=yii::app()->functions->getOptionAdmin('wd_bank_deposit');

$wd_bank_fields=yii::app()->functions->getOptionAdmin('wd_bank_fields');

$bank_info='';
$merchant_payout_bank_account=yii::app()->functions->getOption('merchant_payout_bank_account',$mtid);
if (!empty($merchant_payout_bank_account)){
	$bank_info=json_decode($merchant_payout_bank_account,true);
}
?>
<div class="withdrawals">

<div class="uk-panel uk-panel-box">

<div class="box-line-bottom">
  <h3 class="left"><?php echo t("Pending withdrawals")?></h3>
  <a href="<?php echo Yii::app()->createUrl('/merchant/withdrawalshistory')?>" class="right"><?php echo t("View withdrawal history")?></a>
  <div class="clear"></div>
</div>

<?php if ( $peding_request=Yii::app()->functions->getMerchantWithdrawal($mtid,$request_status)):?>
<table id="" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>            
            <th><?php echo Yii::t('default',"Request Date")?></th>
            <th><?php echo Yii::t('default',"Amount")?></th>
            <th><?php echo Yii::t('default',"Payment Type")?></th>
            <th><?php echo Yii::t('default',"Payment Method")?></th>
            <th><?php echo Yii::t('default',"Account")?></th>
            <th><?php echo Yii::t('default',"Date to process")?></th>
        </tr>
   <?php foreach ($peding_request as $val):?>  
        <?php 
        $account=$val['account'];
        if ( $val['payment_method']=="bank"){
        	$account=$val['bank_account_number'];
        }
        ?>      
        <tr>            
            <td><?php echo FormatDateTime($val['date_created'],false)?></td>
            <td><?php echo displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['amount']))?></td>
            <td><?php echo $val['payment_type']?></td>
            <td><?php echo $val['payment_method']?></td>
            <td><?php echo $account?></td>
            <td><?php echo FormatDateTime($val['date_to_process'],false)?></td>
        </tr>
   <?php endforeach;?>     
    </thead>
    <tbody> 
    </tbody>
</table>
<?php else :?>
 <p><?php echo t("You currently have no withdrawals pending or queued for processing.")?></p>
<?php endif;?>
 
 <a class="uk-button uk-button-primary togle-withdrawal" href="javascript:;" class="make-withdrawal"><?php echo t("Make a withdrawal")?></a>

</div> <!--box-->

<div class="withdrawal-info"  >
<div class="box-line-bottom">
  <h3 class="left"><?php echo t("New withdrawal")?></h3>
  <p class="right"><?php echo t("You can withdraw up to")?>: <span class="merchant_total_balance"></span></p>
  <div class="clear"></div>
</div>

<div style="height:20px;"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action',"requestPayout")?>
<?php echo CHtml::hiddenField('payment_type')?>
<?php echo CHtml::hiddenField('payment_method')?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/merchant/withdrawalstep2")?>
<?php echo CHtml::hiddenField('minimum_amount')?>

<h2>1. Select a payment type</h2>
<ul class="withdrawals-ul">
   <li class="child-li li-click" data-id="single" data-type="payment-type">   
     <div class="box-grey payment">
       <h4><?php echo t("Single Payment")?></h4>
       <div class="inner">
       <span style="padding-right:10px;"><?php echo Yii::app()->functions->adminCurrencySymbol();?></span>
       <?php echo CHtml::textField('amount','',array('class'=>"numeric_only"))?>
       <p class="uk-text-muted">Minimum values apply</p>
       </div>
     </div> <!--box-->   
   </li>
   
   <li class="child-li li-click" data-id="all-earnings" data-type="payment-type">  
     <div class="box-grey payment">
       <h4><?php echo t("Single Payment")?></h4>
       <div class="inner">
       <?php echo t("All Earnings")?>
       <p class="uk-text-muted" style="margin-top:25px;"><?php echo t("As of")?> <?php echo $end_date;?> 23:59:59 </p>
       </div>
     </div> <!--box-->    
   </li>   
</ul>
<div class="clear"></div>

<h2>2. Select a payment method</h2>

<ul class="withdrawals-ul">

  <?php if ($wd_enabled_paypal==2):?>
  <li class="child-li li-click2" data-id="paypal" data-type="payment-method" data-minimum="<?php echo $wd_paypal_minimum?>">  
     <div class="box-grey">
       <h4><?php echo t("Paypal")?></h4>
       <div class="inner">
       <p><?php echo t("Minimum")?> <?php echo displayPrice(baseCurrency(),standardPrettyFormat($wd_paypal_minimum))?></p>
       </div>
     </div> <!--box-->   
             
          
     
     <div class="paypal-account-wrap">
     <?php echo CHtml::textField('account',$default_payout['paypal'],array(
     'class'=>"uk-width-1-1",'placeholder'=>t("Email address")
     ))?>
     <div style="height:5px;"></div>
     <?php echo CHtml::textField('account_confirm',$default_payout['paypal'],array(
     'class'=>"uk-width-1-1",'placeholder'=>t("Confirm Email address")
     ))?>
     
     <div style="height:5px;"></div>
     <?php echo CHtml::checkBox('default_account_paypal',
     count($default_payout)>=1?true:false
     ,array('class'=>"icheck",'value'=>2))?>
     <?php echo t("Make this my new default withdrawal account")?>
     </div>     
     
   </li>
   <?php endif;?>
   
   <?php if ( $wd_bank_deposit==2):?>
   <li class="child-li li-click2" data-id="bank" data-type="payment-method" data-minimum="<?php echo $wd_bank_minimum?>">  
     <div class="box-grey">
       <h4><?php echo t("Bank")?></h4>
       <div class="inner">
       <p><?php echo t("Minimum")?> <?php echo displayPrice(baseCurrency(),standardPrettyFormat($wd_bank_minimum))?></p>
       </div>
     </div> <!--box-->  
                    
   </li>
   <?php endif;?>
</ul>
<div class="clear"></div>


<div class="bank-info-wrap">

<?php if ( $wd_bank_fields=="au"):?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","BSB")?><span class="required">*</span></label>
  <?php echo CHtml::textField('swift_code',
  isset($bank_info['swift_code'])?$bank_info['swift_code']:''
  ,array(  
  'class'=>'uk-form-width-large',
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Account Number")?><span class="required">*</span></label>
  <?php echo CHtml::textField('bank_account_number',
  isset($bank_info['bank_account_number'])?$bank_info['bank_account_number']:''
  ,array(  
  'class'=>'uk-form-width-large',
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Account Name")?><span class="required">*</span></label>
  <?php echo CHtml::textField('account_name',
  isset($bank_info['account_name'])?$bank_info['account_name']:''
  ,array(  
  'class'=>'uk-form-width-large',
  ))?>
</div>

<div style="height:5px;"></div>
 <?php echo CHtml::checkBox('default_account_bank',
 is_array($bank_info)?true:false
 ,array('class'=>"icheck",'value'=>2))?>
 <?php echo t("Make this my new default withdrawal account")?>
 </div>     
 


<?php else :?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Bank Account Holder's Name")?><span class="required">*</span></label>
  <?php echo CHtml::textField('account_name',
  isset($bank_info['account_name'])?$bank_info['account_name']:""
  ,array(  
  'class'=>'uk-form-width-large',
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Bank Account Number/IBAN")?><span class="required">*</span></label>
  <?php echo CHtml::textField('bank_account_number',
  isset($bank_info['bank_account_number'])?$bank_info['bank_account_number']:""
  ,array(  
  'class'=>'uk-form-width-large',
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","SWIFT Code")?><span class="required">*</span></label>
  <?php echo CHtml::textField('swift_code',
  isset($bank_info['swift_code'])?$bank_info['swift_code']:""
  ,array(  
  'class'=>'uk-form-width-large',
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Bank Name in Full")?><span class="required">*</span></label>
  <?php echo CHtml::textField('bank_name',
  isset($bank_info['bank_name'])?$bank_info['bank_name']:""
  ,array(  
  'class'=>'uk-form-width-large',
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Bank Branch City")?></label>
  <?php echo CHtml::textField('bank_branch',
  isset($bank_info['bank_branch'])?$bank_info['bank_branch']:""
  ,array(  
  'class'=>'uk-form-width-large',
  ))?>
</div>

<?php 
if (isset($bank_info['bank_country'])){
	if (!empty($bank_info['bank_country'])){
	    $default_country=$bank_info['bank_country'];
	}
}
?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Bank Branch Country")?><span class="required">*</span></label>
  <?php 
  echo CHtml::dropDownList('bank_country',$default_country,$counrty_list,array(
    'class'=>'uk-form-width-large'
  ));
  ?>
</div>



<div style="height:5px;"></div>
 <?php echo CHtml::checkBox('default_account_bank',false,array('class'=>"icheck",'value'=>2))?>
 <?php echo t("Make this my new default withdrawal account")?>
 </div>     
 

<?php endif;?>




<div style="height:20px;"></div>

<div class="uk-form-row" style="margin-left:30px;">
<input type="submit" value="<?php echo Yii::t("default","Submit Request")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</div> <!--bank-info-wrap-->

</form>


</div> <!--withdrawal-info-->

</div> <!--withdrawals-->