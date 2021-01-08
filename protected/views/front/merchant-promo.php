

<div class="box-grey rounded merchant-promo" style="margin-top:0;">

<?php if(isset($promo['offer'])):?>
<?php if (is_array($promo['offer']) && count($promo['offer'])>=1 ):?>
<div class="section-label">
    <a class="section-label-a">
      <span class="bold" style="background:#fff;">
      <?php echo t("Offers")?></span>
      <b></b>
    </a>     
</div>  
<?php foreach ($promo['offer'] as $promov_val):?>
   <p><i class="green-color ion-ios-plus-empty"></i> <?php echo $promov_val?></p>
<?php endforeach;?>
<?php endif;?>
<?php endif;?>


<?php if (isset($promo['voucher'])):?>
<?php if (is_array($promo['voucher']) && count($promo['voucher'])>=1):?>
<div class="section-label top15">
    <a class="section-label-a">
      <span class="bold" style="background:#fff;">
      <?php echo t("Vouchers")?></span>
      <b></b>
    </a>     
</div>  
<?php foreach ($promo['voucher'] as $val): 
      if ( $val['voucher_type']=="fixed amount"){
      	  $amount=FunctionsV3::prettyPrice($val['amount']);
      } else $amount=number_format( ($val['amount']/100)*100 )." %";
?>
   <p><i class="green-color ion-ios-plus-empty"></i> <?php echo $val['voucher_name']." - ".$amount." ".t("Discount")?></p>
<?php endforeach;?>
<?php endif;?>
<?php endif;?>


<?php if (!empty($promo['free_delivery'])):?>
<div class="section-label">
    <a class="section-label-a">
      <span class="bold" style="background:#fff;">
      <?php echo t("Delivery")?></span>
      <b></b>
    </a>     
</div>  
<p><i class="green-color ion-ios-plus-empty"></i> <?php 
echo t("Free Delivery On Orders Over")." ". FunctionsV3::prettyPrice($promo['free_delivery'])?></p>
<?php endif;?>

</div>