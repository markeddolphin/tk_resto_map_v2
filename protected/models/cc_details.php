

<?php if (is_array($data) && count($data)>=1):?>
<div  style="width:400px;padding:15px;padding-bottom:30px;">

<table style="withd:100%;">
 <tr>
   <td><?php echo t("Card name")?> </td>
   <td>: <?php echo $data['card_name']?></td>
 </tr>
 <tr>
   <td><?php echo t("Credit Card Number")?></td>
   <td>: <?php echo $data['credit_card_number']?></td>
 </tr>
 <tr>
   <td><?php echo t("Expiration")?></td>
   <td>: <?php echo $data['expiration_month']."/".$data['expiration_yr']?></td>
 </tr>
 <tr>
   <td><?php echo t("CVV")?> </td>
   <td>: <?php echo $data['cvv']?></td>
 </tr>
 <tr>
   <td><?php echo t("Billing Address")?></td>
   <td>: <?php echo $data['billing_address']?></td>
 </tr>
 
</table>

</div>
<?php else :?>
<p>Card information not available</p>
<?php endif;?>