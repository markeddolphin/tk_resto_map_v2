
<div class="box-grey rounded merchant-opening-hours" style="margin-top:0;">

<div class="section-label bottom20">
    <a class="section-label-a">
      <span class="bold" style="background:#fff;">
      <?php echo t("Opening Hours")?></span>
      <b></b>
    </a>     
</div>  

<?php if ( $res=FunctionsV3::getMerchantOpeningHours($merchant_id)):?>
<?php foreach ($res as $val):?>
   <div class="row">
      <div class="col-md-3 col-xs-3 "><i class="green-color ion-ios-plus-empty"></i> 
      <?php echo t($val['day'])?></div>
      <div class="col-md-6 col-xs-6 "><?php echo $val['hours']?></div>
      <div class="col-md-3 col-xs-3"><?php echo $val['open_text']?></div>
   </div>
<?php endforeach;?>
<?php else :?>
<p class="text-danger"><?php echo t("Not available.")?></p>
<?php endif;?>

</div> <!--box-grey-->