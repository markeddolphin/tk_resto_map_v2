<?php 
$mtid=Yii::app()->functions->getMerchantID();
$request_status=array(
  'paid'
);
if (isset($_GET['do'])){
	if ( $_GET['do']=="failed"){		
		$res=Yii::app()->functions->getMerchantFailedWithdrawal($mtid);
	}
} else {
	$res=Yii::app()->functions->getMerchantWithdrawal($mtid,$request_status);
}
?>

<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/withdrawals/" class="uk-button"><i class="fa fa-long-arrow-left"></i> <?php echo Yii::t("default","Back")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/withdrawalshistory/" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Withdrawal History")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/withdrawalshistory/?do=failed" class="uk-button "><i class="fa fa-list"></i> <?php echo Yii::t("default","Failed withdrawal")?></a>

<?php if ($res):?>

<?php if (isset($_GET['do'])):?>
<h3><?php echo t("Failed withdrawal")?></h3>
<?php else :?>
<h3><?php echo t("Withdrawal History")?></h3>
<?php endif;?>

<table id="" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>                        
            <th><?php echo Yii::t('default',"Amount")?></th>            
            <th><?php echo Yii::t('default',"Payment Method")?></th>
            <th><?php echo Yii::t('default',"Date Processed")?></th>
            <th><?php echo Yii::t('default',"Status")?></th>
        </tr>
   <?php foreach ($res as $val):?>  
        <?php 
        $account=$val['account'];
        $method="Paypal to $account";
        if ( $val['payment_method']=="bank"){
        	$account=$val['bank_account_number'];
        	$method="Bank account to $account";
        }        
        ?>      
        <tr>                        
            <td><?php echo displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['amount']))?></td>            
            <td><?php echo $method?></td>            
            <td><?php echo prettyDate($val['date_process'])?></td>
            <td><?php echo $val['status']?></td>            
        </tr>
   <?php endforeach;?>     
    </thead>
    <tbody> 
    </tbody>
</table>
<?php else :?>
<?php if (isset($_GET['do'])):?>
<p><?php echo t("You currently have no withdrawals")?>.</p>
<?php else :?>
<p><?php echo t("You currently have no withdrawals paid status")?>.</p>
<?php endif;?>
<?php endif;?>

</div>