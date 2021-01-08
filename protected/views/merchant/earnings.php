<div class="earnings-wrap">

<div class="table">
  <ul>
  
  <li>
   <div class="rounded-box rounded">
     <p><?php echo t("Sales earnings this month")?>:</p>
     <h3><?php echo displayPrice(adminCurrencySymbol(),normalPrettyPrice($sale_month));?></h3>
     <P class="small"><?php echo t("From")?>:<?php echo $sale_month_count?> <?php echo t("orders")?></P>
   </div>
  </li>
  
  <?php if($merchant_type==2):?>
  <li>
   <div class="rounded-box rounded">
     <p><?php echo t("Your balance")?>:</p>
     <h3 class="merchant_total_balance"></h3>     
     <a href="<?php echo websiteUrl()."/merchant/withdrawals"?>"><?php echo t("Withdraw money")?></a>
   </div>
  </li>
  <?php endif;?>
  
  
  <li>
   <div class="rounded-box rounded">
     <p><?php echo t("Total value of your item sales")?>:</p>
     <h3><?php echo FunctionsV3::prettyPrice($sale_total);?></h3>     
     <P class="small"><?php echo t("From")?>:<?php echo $total_sale_count?> <?php echo t("orders")?></P>
   </div>
  </li>
  
  </ul>
  <div class="clear"></div>
</div> <!--table-->

</div> <!--earnings-wrap-->