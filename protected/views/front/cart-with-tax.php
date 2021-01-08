<?php $sms_details='';?>
<?php if (is_array($data) && count($data)>=1):?>
<div class="new-cart-parent">

<?php 
$total_food=0;
foreach ($data['item'] as $row => $item):      
     $price=$item['normal_price'];
     if ( $item['discount']>0){
     	$price=$item['discounted_price'];
     }
     $total_food+=$item['qty']*$price;
     $data['item'][$row]['price']=$price;
     
     $sms_details.=$item['qty']." x ".qTranslate($item['item_name'],'item_name',$item['item_name_trans'])." ".standardPrettyFormat($price);
     $sms_details.=FunctionsV3::smsSeparator();      
     //dump($item);     
?>
<!--ITEM-->

<p><b><?php echo qTranslate($item['category_name'],'category_name',$item['category_name_trans'])?></b></p>
<div class="row item-rows-cart" id="new-cart">
  <div class="col-xs-1" ><?php echo $item['qty']?></div>
  <div class="col-xs-5" >      
      <?php echo qTranslate($item['item_name'],'item_name',$item['item_name_trans'])?>
      
      <?php if (isset($item['size_words'])):?>
      <?php if (!empty($item['size_words'])):?>
         (<?php echo qTranslate($item['size_words'],'size_name',$item['size_name_trans']);?>)
      <?php endif;?>
      <?php endif;?>
      
      <?php if ( $item['discount']>0):?>
        <p>
        <span class="normal-price"><?php echo FunctionsV3::prettyPrice($item['normal_price'])?></span>
        <span class="sale-price"><?php echo FunctionsV3::prettyPrice($item['discounted_price'])?></span>
        </p> 
      <?php else :?>
        <p><?php echo FunctionsV3::prettyPrice($price)?></p>
      <?php endif;?>
      
      <?php if(!empty($item['cooking_ref'])):?>
      <p><?php echo qTranslate($item['cooking_ref'],'cooking_name',$item['cooking_name_trans'])?></p>
      <?php endif;?>
      
      <?php if(isset($item['ingredients'])):?>
      <?php if(is_array($item['ingredients']) && count($item['ingredients'])>=1):?>
         <p class="small ingredients-label"><?php echo t("Ingredients")?></p>
         <?php foreach ($item['ingredients'] as $val_ingredients):?>
            <p><?php 
            if($details_ingredients=FunctionsV3::getIngredientsByName($val_ingredients,$merchant_id)){
            	$_ingredients['ingredients_name_trans']=json_decode($details_ingredients['ingredients_name_trans'],true);
            	echo qTranslate($val_ingredients,'ingredients_name',$_ingredients);  
            } else echo $val_ingredients;            
            ?></p>
         <?php endforeach;?>
      <?php endif;?>
      <?php endif;?>
      
      
     <?php if(isset($item['order_notes'])):?>
	     <?php if(!empty($item['order_notes'])):?>
	     <p class="small"><?php echo $item['order_notes']?></p>
	     <?php endif;?>
     <?php endif;?>
     
      
  </div>
  
  <div class="col-xs-3 text-center">
    <?php if ( $receipt!=TRUE):?>
    <a href="javascript:;" class="edit_item" data-row="<?php echo $row?>" rel="<?php echo $item['item_id']?>">
      <i class="ion-compose"></i>
    </a>
    <a href="javascript:;" class="delete_item" data-row="<?php echo $row?>" rel="<?php echo $item['item_id']?>" >
      <i class="ion-trash-a"></i>
    </a>
    <?php else :?>
    &nbsp;
    <?php endif;?>
  </div>
  
  <div class="col-xs-3 text-right" ><?php echo FunctionsV3::prettyPrice($item['qty']*$price)?></div>
</div> <!--row-->

	<!--SUB ITEM-->
	<?php 
	if(!isset($item['new_sub_item'])){
		$item['new_sub_item']='';		
	}
	if(!isset($sub_item[0])){
		$sub_item[0]['subcategory_name_trans']='';
	}
	?>
	<?php if (is_array($item['new_sub_item']) && count($item['new_sub_item'])>=1):?>
	   <?php foreach ($item['new_sub_item'] as $sub_name => $sub_item):?>	   
	     <p class="text-success"><b>
	     <?php echo $sub_name_trans=qTranslate($sub_name,'subcategory_name',$sub_item[0]['subcategory_name_trans']);?>
	     </b></p>
	     <?php $sms_details.=$sub_name_trans.FunctionsV3::smsSeparator();?>
	     <?php foreach ($sub_item as $sub_item2):?>
	        <div class="row" id="new-cart">
	           <div class="col-xs-9">
	             <?php 
	             $addon_name=qTranslate($sub_item2['addon_name'],'sub_item_name',$sub_item2['sub_item_name_trans']);
	             echo $sub_item2['addon_qty']."x ". 
	             FunctionsV3::prettyPrice($sub_item2['addon_price']) ." ". $addon_name;
	             
	             $sms_details.=$sub_item2['addon_qty']." x ".$addon_name;
	             $sms_details.=" ".standardPrettyFormat($sub_item2['addon_price']*$sub_item2['addon_qty']);
	             $sms_details.=FunctionsV3::smsSeparator();
	             ?>
	           </div>
	           <div class="col-xs-3 text-right">
	             <?php echo FunctionsV3::prettyPrice( $sub_item2['addon_price']*$sub_item2['addon_qty'] );?>
	             <?php $total_food+=$sub_item2['addon_price']*$sub_item2['addon_qty']; ?>
	             <?php 
	             
	             ?>
	           </div>
	        </div>
	     <?php endforeach;;?>
	   <?php endforeach;;?>
	<?php endif;?>

<hr/>	
	
<?php endforeach;?>


<!--TOTAL-->

<?php 
$grand_total=0;
$data_total=$data['total'];

//dump("total food :".$total_food); 

$total_plus_charges=$total_food;
if ($data_total['delivery_charges']>0){
	$total_plus_charges+=$data_total['delivery_charges'];
}
if ($data_total['merchant_packaging_charge']>0){
	$total_plus_charges+=$data_total['merchant_packaging_charge'];
}
if (isset($data_total['card_fee'])){
	if ($data_total['card_fee']>0){
		$total_plus_charges+=$data_total['card_fee'];
	}
}

if ( $data_total['cart_tip_percentage']>0.001){
	$data_total['tips']=$total_food*($data_total['cart_tip_percentage']/100);
	$total_plus_charges+=+$data_total['tips'];
}

if (isset($data_total['less_voucher'])){
	if ($data_total['less_voucher']>0){
		if (empty($data_total['voucher_type'])){			
		   $total_plus_charges+=-$data_total['less_voucher'];
		} else {
		   $data_total['less_voucher']=$total_food*($data_total['voucher_value']/100);	
		   $total_plus_charges+=-$data_total['less_voucher'];
		}
	}
}

if (isset($data_total['pts_redeem_amt'])){
	if ($data_total['pts_redeem_amt']>0){
		$total_plus_charges+=-$data_total['pts_redeem_amt'];
	}
}

//dump("total_plus_charge :".$total_plus_charges);

$grand_total+=$total_plus_charges;

if ( $data_total['discounted_amount']>0.001){	
	$data_total['discounted_amount']=$total_food*($data_total['merchant_discount_amount']/100);
	$grand_total+=-$data_total['discounted_amount'];
}

/*dump("grand total :".$grand_total);
dump("tax :".$tax);*/

$data_total['total']=$grand_total;
$data_total['subtotal']=$grand_total/($tax+1);
$data_total['taxable_total']=$data_total['total']-$data_total['subtotal'];

//dump("tax :".$data_total['taxable_total']);
?>

<?php //dump($data_total)?>

<?php if ($data_total['delivery_charges']>0):?>
<div class="row" id="new-cart">
  <div class="col-xs-9 txt-indent" ><?php echo t("Delivery Fee")?></div>
  <div class="col-xs-3 text-right">
    <?php echo FunctionsV3::prettyPrice($data_total['delivery_charges'])?>
  </div>
</div> <!--row-->
<?php endif;?>


<?php 
if (isset($data_total['card_fee'])):
if ($data_total['card_fee']>0):
?>
<div class="row" id="new-cart">
  <div class="col-xs-9 txt-indent" ><?php echo t("Card Fee")?></div>
  <div class="col-xs-3 text-right">
    <?php echo FunctionsV3::prettyPrice($data_total['card_fee'])?>
  </div>
</div> <!--row-->
<?php 
endif;
endif;
?>

<?php if ($data_total['merchant_packaging_charge']>0):?>
<div class="row" id="new-cart">
  <div class="col-xs-9 txt-indent" ><?php echo t("Packaging")?></div>
  <div class="col-xs-3 text-right">
    <?php echo FunctionsV3::prettyPrice($data_total['merchant_packaging_charge'])?>
  </div>
</div> <!--row-->
<?php endif;?>

<?php if (isset($data_total['cart_tip_percentage'])):?>
<?php if ($data_total['cart_tip_percentage']>0):?>
<div class="row" id="new-cart">
  <div class="col-xs-9 txt-indent" ><?php echo t("Tip")." ".$data_total['cart_tip_percentage']?>%</div>
  <div class="col-xs-3 text-right">
    <?php echo FunctionsV3::prettyPrice($data_total['tips'])?>
  </div>
</div> <!--row-->
<?php endif;?>
<?php endif;?>

<?php if ($data_total['discounted_amount']>0):?>
<div class="row" id="new-cart">
  <div class="col-xs-9 txt-indent" ><?php echo t("Discount")." ".$data_total['merchant_discount_amount']?>%</div>
  <div class="col-xs-3 text-right">
    (<?php echo FunctionsV3::prettyPrice($data_total['discounted_amount'])?>)
  </div>
</div> <!--row-->
<?php endif;?>

<?php if (isset($data_total['pts_redeem_amt'])):?>
<?php if ($data_total['pts_redeem_amt']>0):?>
<div class="row" id="new-cart">
  <div class="col-xs-9 txt-indent" ><?php echo t("Points Discount")?></div>
  <div class="col-xs-3 text-right">
    (<?php echo FunctionsV3::prettyPrice($data_total['pts_redeem_amt'])?>)
  </div>
</div> <!--row-->
<?php endif;?>
<?php endif;?>

<?php if ($data_total['less_voucher']>0):?>
<div class="row" id="new-cart">
  <div class="col-xs-9 txt-indent" >
    <?php 
      echo t("Less Voucher")." ";
      if (!empty($data_total['voucher_type'])){
      	  echo $data_total['voucher_type'];
      }
    ?>
  </div>
  <div class="col-xs-3 text-right">
    (<?php echo FunctionsV3::prettyPrice($data_total['less_voucher'])?>)
  </div>
</div> <!--row-->
<?php endif;?>


<?php if ($data_total['subtotal']>0):?>
<div class="row" id="new-cart">
  <div class="col-xs-9 txt-indent" ><?php echo t("Sub Total")?></div>
  <div class="col-xs-3 text-right">
    <?php echo FunctionsV3::prettyPrice($data_total['subtotal'])?>
  </div>
</div> <!--row-->
<?php endif;?>

<?php if ($data_total['taxable_total']>0):?>
<div class="row" id="new-cart">
  <div class="col-xs-9 txt-indent" ><?php echo t("Tax")." ".$data_total['tax_amt']?>%</div>
  <div class="col-xs-3 text-right">
    <?php echo FunctionsV3::prettyPrice($data_total['taxable_total'])?>
  </div>
</div> <!--row-->
<?php endif;?>

<?php if ($data_total['total']>0):?>
<div class="row" id="new-cart" style="border-bottom:0;">
  <div class="col-xs-9 txt-indent" ><b><?php echo t("Total")?></b></div>
  <div class="col-xs-3 text-right">
    <b><?php echo FunctionsV3::prettyPrice($data_total['total'])?></b>
  </div>
</div> <!--row-->
<?php endif;?>

<?php 
if ( $receipt!=TRUE){
	echo CHtml::hiddenField('subtotal_order' , normalPrettyPrice($data_total['total']) );
	echo CHtml::hiddenField('subtotal_order2', normalPrettyPrice($data_total['total']));
	echo CHtml::hiddenField('subtotal_extra_charge', 0);
}
?>

</div> <!--new-cart-parent-->
<?php endif;?>

<?php 
/*POINTS PROGRAM*/
if (FunctionsV3::hasModuleAddon("pointsprogram")){			
    echo PointsProgram::cartTotalEarnPoints(isset($data['item'])?$data['item']:'',$receipt);
}
		       
$data['total']=$data_total;
$_SESSION['cart_item_tax']=$data;
Yii::app()->functions->details['raw']=$data;
Yii::app()->functions->additional_details=$sms_details;
?>
