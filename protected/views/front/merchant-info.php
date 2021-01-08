<table class="tbl_merchant_info">

<tr>
 <td><?php echo t("Restaurant name")?>:</td>
 <td><?php echo $data['restaurant_name']?></td>
 </tr>
 
 <tr>
 <td><?php echo t("Restaurant phone")?>:</td>
 <td><?php echo $data['restaurant_phone']?></td>
 </tr>
 
 <tr>
 <td><?php echo t("Contact name")?>:</td>
 <td><?php echo $data['contact_name']?></td>
 </tr>
 
 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>
 
 <tr>
 <td><?php echo t("Address")?>:</td>
 <td><?php echo nl2br(clearString($data['merchant_address']))?></td>
 </tr>
 
 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>
 
 <tr>
 <td><?php echo t("Offered Services")?>:</td>
 <td><?php echo FunctionsV3::displayServicesList($data['service'])?></td>
 </tr>
 
 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>
 
 <tr>
 <td><?php echo t("Offered Payments")?>:</td>
 <td>
 <?php if(is_array($payment_list) && count($payment_list)>=1):?>
 <ul class="services-type">
 <?php foreach ($payment_list as $val):?>
  <li><?php echo $val;?></li>
 <?php endforeach;?>
 </ul>
 <?php endif;?>
 </td>
 </tr>
 
 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>
 
 <tr>
 <td><?php echo t("Website")?>:</td>
 <td>
    <?php if(!empty($website)):?>
    <?php $link = FunctionsV3::fixedLink($website);?>
      <a href="<?php echo $link?>" target="_blank"><?php echo $link;?></a>
    <?php endif;?>
 </td>
 </tr>
 
 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>
 
 <tr>
 <td><?php echo t("Info")?>:</td>
 <td><?php echo nl2br(clearString($information_content))?></td>
 </tr>
 
</table>