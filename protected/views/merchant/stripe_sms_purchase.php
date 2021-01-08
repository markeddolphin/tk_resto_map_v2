<table class="uk-table" style="width:50%;">
<tr>
<tr>
<td><?php echo t("Description")?></td>
<td><?php echo $payment_description?></td> 
</tr>
<?php if($card_fee>0.001):?>

<tr>
<td><?php echo t("Amount")?></td>
<td><?php echo FunctionsV3::prettyPrice( ($amount_to_pay/100)-$card_fee  )?></td>
</tr> 

<tr>
<td><?php echo t("Card fee")?></td>
<td><?php echo FunctionsV3::prettyPrice($card_fee)?></td> 
</tr>

<tr>
<td><?php echo t("Total")?></td>
<td><?php echo FunctionsV3::prettyPrice( ($amount_to_pay) /100)?></td>
</tr> 

<?php else :?>

<tr>
<td><?php echo t("Total")?></td>
<td><?php echo FunctionsV3::prettyPrice($amount_to_pay/100)?></td>
</tr> 


<?php endif;?> 
<tr>
<td colspan="2">
<button class="btn paynow_stripe"><?php echo t("Pay now")?></button>  
</td>
</tr>
</table>

<p>
<a href="<?php echo Yii::app()->request->baseUrl."/merchant/purchasesms"; ?>"><?php echo t("Go back")?></a>
</p>