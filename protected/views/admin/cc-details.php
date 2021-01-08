
<?php if (is_array($data) && count($data)>=1):?>
<div style="padding:20px;">
<table class="uk-table">
  <tr>
    <td><?php echo t("Card name")?> :</td>
    <td><?php echo $data['card_name']?></td>
  </tr>
  <tr>
    <td><?php echo t("Credit Card Number")?> :</td>
    <td><?php echo Yii::app()->functions->maskCardnumber($data['credit_card_number'])?></td>
  </tr>
  <tr>
    <td><?php echo t("Exp. month")?> :</td>
    <td><?php echo $data['expiration_month']?></td>
  </tr>
  <tr>
    <td><?php echo t("Exp. year")?> :</td>
    <td><?php echo $data['expiration_yr']?></td>
  </tr>
  <tr>
    <td><?php echo t("CVV")?> :</td>
    <td><?php echo $data['cvv']?></td>
  </tr>
  <tr>
    <td><?php echo t("Billing Address")?> :</td>
    <td><?php echo $data['billing_address']?></td>
  </tr>
</table>
</div>
<?php else :?>
<p><?php echo t("No recods found")?></p>
<?php endif;?>
