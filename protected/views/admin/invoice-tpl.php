<?php 
$total_w_tax=0; $total_commission=0;
$style='style="border-bottom:1px solid #000;"';

$date_from=FunctionsV3::prettyDate($invoice_info['date_from']);
$date_to=FunctionsV3::prettyDate($invoice_info['date_to']);
?>

<p>
<b style="font-size:15px;"><?php echo stripslashes($invoice_info['merchant_name'])?><br/></b>
<?php echo stripslashes($invoice_info['merchant_address'])?><br/>
<?php echo stripslashes($invoice_info['merchant_address2'])?>
</p>

<p>
<?php echo t("Invoice Date")?>: <?php echo FunctionsV3::prettyDate($invoice_info['date_created'])?><br/>
<?php echo t("Period").": ".$terms[$invoice_info['invoice_terms']]?> <br/>
<?php echo t("Invoice number").": ".$invoice_info['invoice_number']?> 
</p>

<p>
<b><?php echo t("Invoice")?></b> 
<?php echo Yii::t("dafault","We hereby send you an invoice for our services in the period [from] - [to]",array(
  '[from]'=>$date_from,
  '[to]'=>$date_to,
))?></p>

<h3><?php echo Yii::t("default","Orders Covered from [from] to [to]",array(
 '[from]'=>$date_from,
 '[to]'=>$date_to,
))?></h3>

<?php if (is_array($data) && count($data)>=1):?>
<table cellspacing="2" cellpadding="2" border="0"  >
<thead>
 <tr>
  <th height="20px;" width="70" <?php echo $style;?> ><b><?php echo t("Order ID")?></b></th>
  <th height="20px;" width="150" <?php echo $style;?> ><b><?php echo t("Date")?></b></th>
  <th height="20px;" width="90" <?php echo $style;?> ><b><?php echo t("Amount")?></b></th>
  <th height="20px;" width="90" <?php echo $style;?> ><b><?php echo t("Commission")?></b></th>   
  <th height="20px;" width="90" <?php echo $style;?> ><b><?php echo t("Total")?></b></th>
 </tr>
</thead>
<tbody>
<?php foreach ($data as $val): ?>
<?php 
$total_w_tax+=$val['total_w_tax'];
$total_commission+=$val['total_commission'];
?>
  <tr>
    <td width="70" <?php echo $style;?>>  <span style="padding-left:20px;"><?php echo $val['order_id']?></span> </td>
    <td width="150" <?php echo $style;?>><?php echo FunctionsV3::prettyDate($val['date_created'])." ".FunctionsV3::prettyTime($val['date_created'])?></td>
    <td width="90" align="left" <?php echo $style;?>><?php echo FunctionsV3::prettyPrice($val['total_w_tax'])?></td>
    <td width="90" align="left" <?php echo $style;?>>         
      <?php 
      if ($val['commision_type']=="percentage"){
      	  echo number_format($val['percent_commision'],0,'','')."%";
      } else {
      	  echo number_format($val['percent_commision'],2,'.','');
      }
      ?>
    </td>
    <td width="90" align="left" <?php echo $style;?>><?php echo FunctionsV3::prettyPrice($val['total_commission'])?></td>    
  </tr>
<?php endforeach;?>
<tr>
  <td width="70" height="15px;" <?php echo $style;?> >
    <b><?php echo t("Total")?></b>
  </td>
  <td width="150"  height="15px;" <?php echo $style;?>>&nbsp;</td>
  <td width="90" height="15px;" align="left" <?php echo $style;?> ><b><?php echo FunctionsV3::prettyPrice($total_w_tax)?></b></td>
  <td width="90" height="15px;" align="left" <?php echo $style;?>></td>
  <td width="90" height="15px;" align="left" <?php echo $style;?>><b><?php echo FunctionsV3::prettyPrice($total_commission)?></b></td>
</tr>
</tbody>
</table>

<p>
<?php echo Yii::t("default","The amount should be deposited before [date] into our account",array(
  '[date]'=>FunctionsV3::prettyDate($bank_deposited_timeframe_date)
))?>.<br/>
<?php echo t("Payment information")?>:<br/>
<?php if (empty($bank_custom_tpl)):?>
<?php echo t("Account name").": <b>$bank_account_name</b>"?><br/>
<?php echo t("Account number").": <b>$bank_account_number</b>"?><br/>
<?php else :?>
<?php echo $bank_custom_tpl;?>
<?php endif;?>
</p>

<p><?php echo t("Sincerely")?>,</p>
<p>- <?php echo $site_title?></p>

<?php endif;?>