<?php
$data='';
$ok=false;
$_GET['backend']=true;
if ( $data=Yii::app()->functions->getOrder2($_GET['id'])){		
	$merchant_id=$data['merchant_id'];
	$json_details=!empty($data['json_details'])?json_decode($data['json_details'],true):false;
	if ( $json_details !=false){
		Yii::app()->functions->displayOrderHTML(array(
		  'merchant_id'=>$data['merchant_id'],
		  'delivery_type'=>$data['trans_type'],
		  'delivery_charge'=>$data['delivery_charge'],
		  'packaging'=>$data['packaging'],
		  'cart_tip_value'=>$data['cart_tip_value'],
		  'cart_tip_percentage'=>$data['cart_tip_percentage']/100,
		  'card_fee'=>$data['card_fee'],
		  'tax'=>$data['tax'],
		  'points_discount'=>isset($data['points_discount'])?$data['points_discount']:'' /*POINTS PROGRAM*/,
		  'voucher_amount'=>$data['voucher_amount'],
		  'voucher_type'=>$data['voucher_type']
		  ),$json_details,true);
		if ( Yii::app()->functions->code==1){
			$ok=true;
		}
	}	
} else {
	echo die("Receipt not found");
}
$print='';

//dump($data);
if (!empty($data['client_delivery_address'])){		
 	$delivery_address=$data['client_delivery_address'];
 } else $delivery_address=$data['full_address'];		
?>

<div class="fax_page container">

  <div class="fax_header">
	  <div class="mytable">
	    <div class="mycol col-1 v_top">
	       <img src="<?php echo websiteUrl();?>/assets/images/fax_logo.jpg" class="logo">
	    </div> <!--col-->
	    <div class="mycol col-2 text-right">
	       <h3><?php echo stripslashes($data['merchant_name'])?></h3>
	       <p><?php echo t("Order")?> <span># <?php echo Yii::app()->functions->formatOrderNumber($data['order_id'])?></span></p>
	       <p><?php echo t("Placed on")?>: <?php echo date("M d, Y g:i A",strtotime($data['date_created']))?></p>
	       <p><?php echo t("Payment method")?>: <span class="bold">
	       <?php 
	       if ( $data['payment_type']=="cod"){
	       	   echo t("CASH");
	       } else echo t("PREPAID");
	       ?>
	       </span></p>
	    </div> <!--col-->
	  </div> <!--mytable-->	  	  
  </div><!-- fax_header-->
  
  <div class="fax_sub_header">
   To confirm orders or need help please call (213) 610-1014 or email : support@eatster.us
  </div>
  
  <div class="fax_customer_details">
  <div class="mytable">
     <div class="mycol col-1 v_center">
        <p><?php echo $data['full_name']?></p>
        
        <?php         
        if(!empty($data['contact_phone1'])){
        	$contact_phone=$data['contact_phone1'];
        } else $contact_phone=$data['contact_phone'];
        ?>
        <?php if(!empty($contact_phone)):?>
        <p><span><?php         
        $phone = str_replace("+",'',$contact_phone);        
        echo "(".substr($phone,0,3).")"." ".substr($phone,3,3)."-".substr($phone,6,strlen($phone));
        ?></span></p>
        <?php endif;?>
        
        <p><?php echo stripslashes(nl2br($delivery_address))?></p>
     </div> <!--col-->
     <div class="mycol col-2 v_center center">
       <h2><?php echo t($data['trans_type'])?></h2>
       <?php if ( !empty($data['delivery_time'])):?>
         <h3><?php echo date("M d, Y g:i A",strtotime($data['delivery_time']))?></h3>
       <?php else :?>
       <?php if ( !empty($data['delivery_asap'])):?>
        <h3><?php echo $data['delivery_asap']==1?t("ASAP"):""?></h3>
       <?php endif;?>       
       <?php endif;?>
     </div> <!--col-->
  </div> <!--mytable-->
  </div> <!--fax_customer_details-->
  
  <div class="fax_delivery_instruction">
     <p><span class=""><?php echo t("Instructions")?></span>: <?php echo stripslashes($data['delivery_instruction'])?></p>
  </div> <!--fax_delivery_instruction-->
  
  <?php if(isset($data['order_change'])):?>
  <?php if ($data['order_change']>0):?>
  <div class="fax_delivery_instruction">
     <p><span class=""><?php echo t("Change")?></span>: <?php echo FunctionsV3::prettyPrice($data['order_change'])?></p>
  </div> <!--fax_delivery_instruction-->
  <?php endif;?>
  <?php endif;?>
  
  <div class="fax_item">
    <div class="mytable">
       <div class="mycol col-1 v_center th"><?php echo t("Qty")?></div>
       <div class="mycol col-2 v_center th"><?php echo t("Item")?></div>
       <div class="mycol col-3 v_center th text-right"><?php echo t("Price")?></div>
    </div> 
  </div><!-- fax_item-->
  
  <div class="fax_item_details fax_item">
  <?php //dump(Yii::app()->functions->details['raw'])?>
  <?php if(is_array(Yii::app()->functions->details['raw']['item'])):?>
  <?php foreach (Yii::app()->functions->details['raw']['item'] as $val): //dump($val);?>
  <?php 
  $price = $val['normal_price'];
  if($val['discounted_price']>=1){
  	$price = $val['discounted_price'];
  }
  $price = $val['qty']*unPrettyPrice($price);
  $_price = FunctionsV3::prettyPrice($price);
  
  $size = '';
  if(isset($val['size_words'])){
	  if(!empty($val['size_words'])){
	  	 $size = $val['size_words'];
	  }
  }
  ?>  
  
  <ul>
    <li>

    <!--MAIN FOOD ITEM-->   
    <div class="mytable">
       <div class="mycol col-1 v_center "><?php echo $val['qty']?></div>
       <div class="mycol col-2 v_center item_name "><?php echo $val['item_name']?> <?php echo !empty($size)?"(".$size.")":''?></div>
       <div class="mycol col-3 v_center text-right"><?php echo $_price?></div>
    </div> 
    <!--MAIN FOOD ITEM-->
    
    <!--COOKING REF-->
    <?php if(!empty($val['cooking_ref'])):?>
    <div class="mytable ">
       <div class="mycol col-1 v_center "></div>
       <div class="mycol col-2 v_center "><span class="col_red"><?php echo t("Cooking Ref")?></span> : <?php echo $val['cooking_ref']?></div>
       <div class="mycol col-3 v_center "></div>       
    </div> 
    <?php endif;?>
    <!--COOKING REF-->
    
    <!--START ingredients-->
    <?php if(isset($val['ingredients'])):?>
    <?php if(is_array($val['ingredients']) && count($val['ingredients'])>=1):?>
      <!--<ul>
       <li>-->
      
      <?php 
      $ingredients_list='';
      foreach ($val['ingredients'] as $ingredients){
      	$ingredients_list.="$ingredients,";
      }
      $ingredients_list=substr($ingredients_list,0,-1);
      ?>
       <div class="mytable">
	       <div class="mycol col-1 v_center "></div>
	       <div class="mycol col-2 v_center"><span class="col_red"><?php echo t("Ingredients")?>:</span> <?php echo $ingredients_list?></div>
	       <div class="mycol col-3 v_center text-right"></div>	       
	  </div>  
	 <!-- <?php foreach ($val['ingredients'] as $ingredients):?>
	    <div class="mytable">
	       <div class="mycol col-1 v_center "></div>
	       <div class="mycol col-2 v_center "><?php echo $ingredients ?></div>
	       <div class="mycol col-3 v_center text-right"></div>
	     </div>  
	  <?php endforeach;?>-->
       <!--</li>
      </ul>-->
    <?php endif;?>
    <?php endif;?>
    <!--END ingredients-->
    
    
    <!--START ADDON --> 
    <?php //dump($val['new_sub_item']);?>   
    <?php if (isset($val['new_sub_item'])):?>
    <?php if (is_array($val['new_sub_item']) && count($val['new_sub_item'])>=1):?>
          
      <?php foreach ($val['new_sub_item'] as $category=> $subitem):?>
       
         <?php 
         $sub_item_counter=0;
         $first_sub_item['name'] = $subitem[0]['addon_name'];
         $first_sub_item['qty'] = $subitem[0]['addon_qty'];
         $first_sub_item['price'] = $subitem[0]['addon_price'];
         $first_sub_item['total'] = unPrettyPrice($subitem[0]['addon_price'])*$subitem[0]['addon_qty'];
         ?>              
         <?php if($sub_item_counter<=0):?>
         <div class="mytable">
	       <div class="mycol col-1 v_center "><?php //echo $first_sub_item['qty']?></div>
	       <div class="mycol col-2 v_center addon_category">
	         <span class="col_red"><?php echo $category ?></span> <?php echo $first_sub_item['name']?>
	       </div>
	       <div class="mycol col-3 v_center text-right">
	          <?php 
	          $addon_total = unPrettyPrice($first_sub_item['price'])*$first_sub_item['qty'];
	          echo FunctionsV3::prettyPrice($addon_total);?>
	       </div>	       
	     </div>  
         <?php else :?>
         <div class="mytable">
	       <div class="mycol col-1 v_center "></div>
	       <div class="mycol col-2 v_center col_red addon_category"><?php echo $category ?></div>
	       <div class="mycol col-3 v_center text-right"></div>
	     </div>  
	     <?php endif;?>
	     
	     <?php foreach ($subitem as $subitem_val): //dump($subitem_val);?>
	         <?php 
	         if($sub_item_counter<=0){
	         	$sub_item_counter++;
	         	continue;
	         }
	         ?>
		     <?php $addon_total = unPrettyPrice($subitem_val['addon_price'])*$subitem_val['addon_qty'];?>
		     <div class="mytable">
		       <div class="mycol col-1 v_center ">&nbsp;</div>
		       <div class="mycol col-2 v_center "><?php echo $subitem_val['addon_name'];?></div>
		       <div class="mycol col-3 v_center text-right"><?php echo FunctionsV3::prettyPrice($addon_total)?></div>		       
		     </div>  		     
	     <?php endforeach;?>
      <?php endforeach;?>
      
    <?php endif;?>
    <?php endif;?>
    <!--END ADDON-->
    
    <!--COOKING REF-->
    <?php if(!empty($val['order_notes'])):?>
    <div class="mytable">
       <div class="mycol col-1 v_center "></div>
       <div class="mycol col-2 v_center "><span class="col_red"><?php echo t("Instruction")?></span> : <?php echo $val['order_notes']?></div>
       <div class="mycol col-3 v_center text-right"></div>       
    </div> 
    <?php endif;?>
    <!--COOKING REF-->
    
    </li>
  </ul>
  
  <?php endforeach;?>
  <?php endif;?>
  </div> <!--fax_item_details-->
  
  <!--STAR TOTAL-->  
  <div class="fax_total">
  
  <div class="fax_total_col_2">


  
  <?php if(isset(Yii::app()->functions->details['raw']['total'])):?>
  <?php if (is_array(Yii::app()->functions->details['raw']['total']) && count(Yii::app()->functions->details['raw']['total'])>=1):?>
    <?php 
    $totalinfo = Yii::app()->functions->details['raw']['total'];
    //dump($totalinfo);   
    //dump($data);
    $total_discount = 0;
    $total_customer_pays = 0;
    ?>
    
    <?php if($data['calculation_method']==1):?>
    <?php if (isset($totalinfo['discounted_amount'])):?>
    <?php if ($totalinfo['discounted_amount']>0.001):?>
      <div class="mytable">
       <div class="mycol col-1 v_center "></div>
       <div class="mycol col-2 v_center text-right index_right"><?php echo t("Discount")." ".number_format($totalinfo['merchant_discount_amount'],0);?>%</div>
       <div class="mycol col-3 v_center text-right"><?php echo FunctionsV3::prettyPrice($totalinfo['discounted_amount'])?></div>
     </div> 
    <?php endif;?>
    <?php endif;?>
    <?php endif;?>
        
    <?php if($data['calculation_method']==1):?>
    <?php if (isset($totalinfo['pts_redeem_amt'])):?>
    <?php if ($totalinfo['pts_redeem_amt']>0.001):?>
      <div class="mytable">
       <div class="mycol col-1 v_center "></div>
       <div class="mycol col-2 v_center text-right index_right"><?php echo t("Points Discount");?></div>
       <div class="mycol col-3 v_center text-right">(<?php echo FunctionsV3::prettyPrice($totalinfo['pts_redeem_amt'])?>)</div>
     </div> 
    <?php endif;?>
    <?php endif;?>
    <?php endif;?>
    
    <?php if(isset($totalinfo['subtotal'])):?>
    <?php if ($totalinfo['subtotal']>0):?> 
    
      <?php if ($totalinfo['voucher_value']>0.001):?> 
         <div class="mytable">
	       <div class="mycol col-1 v_center "></div>
	       <div class="mycol col-2 v_center text-right index_right"><?php echo t("Subtotal")?></div>
	       <?php if($data['calculation_method']==1):?>
	       <div class="mycol col-3 v_center text-right"><?php echo FunctionsV3::prettyPrice($totalinfo['subtotal']+$totalinfo['voucher_value'])?></div>
	       <?php else :?>
	       <div class="mycol col-3 v_center text-right"><?php echo FunctionsV3::prettyPrice($totalinfo['subtotal'])?></div>
	       <?php endif;?>
	     </div> 
      <?php else:?>    
	     <div class="mytable">
	       <div class="mycol col-1 v_center "></div>
	       <div class="mycol col-2 v_center text-right index_right"><?php echo t("Subtotal")?></div>
	       <div class="mycol col-3 v_center text-right"><?php echo FunctionsV3::prettyPrice($totalinfo['subtotal'])?></div>
	     </div> 
	 <?php endif;?>
     
     <?php endif;?>
    <?php endif;?>
    
    <?php if($data['calculation_method']==1):?>
    <?php if(isset($totalinfo['voucher_value'])):?>
     <?php if ($totalinfo['voucher_value']>0.001):?> 
     <div class="mytable">
       <div class="mycol col-1 v_center "></div>
       <div class="mycol col-2 v_center text-right index_right"><?php echo t("Less Voucher")?></div>
       <div class="mycol col-3 v_center text-right">(<?php echo FunctionsV3::prettyPrice($totalinfo['voucher_value'])?>)</div>
     </div> 
     
     
     <div class="mytable">
       <div class="mycol col-1 v_center "></div>
       <div class="mycol col-2 v_center text-right index_right"><?php echo t("Sub Total (Less Voucher)")?></div>
       <div class="mycol col-3 v_center text-right"><?php echo FunctionsV3::prettyPrice($totalinfo['subtotal'])?></div>
     </div> 
     
     <?php endif;?>
    <?php endif;?>
    <?php endif;?>
    
    
     <?php if(isset($totalinfo['delivery_charges'])):?>
     <?php if ($totalinfo['delivery_charges']>0.001):?> 
     <div class="mytable">
       <div class="mycol col-1 v_center "></div>
       <div class="mycol col-2 v_center text-right index_right"><?php echo t("Delivery Fee")?></div>
       <div class="mycol col-3 v_center text-right"><?php echo FunctionsV3::prettyPrice($totalinfo['delivery_charges'])?></div>
     </div> 
     <?php endif;?>
    <?php endif;?>
     
     <?php if(isset($totalinfo['merchant_packaging_charge'])):?>
     <?php if ($totalinfo['merchant_packaging_charge']>0):?> 
     <div class="mytable">
       <div class="mycol col-1 v_center "></div>
       <div class="mycol col-2 v_center text-right index_right"><?php echo t("Packaging")?></div>
       <div class="mycol col-3 v_center text-right"><?php echo FunctionsV3::prettyPrice($totalinfo['merchant_packaging_charge'])?></div>
     </div> 
     <?php endif;?>
    <?php endif;?>
     
     <?php if(isset($totalinfo['taxable_total'])):?>
     <?php if ($totalinfo['taxable_total']>0):?> 
     <div class="mytable">
       <div class="mycol col-1 v_center "></div>
       <div class="mycol col-2 v_center text-right index_right"><?php echo t("Tax")." ".$totalinfo['tax_amt']?>%</div>
       <div class="mycol col-3 v_center text-right"><?php echo FunctionsV3::prettyPrice($totalinfo['taxable_total'])?></div>
     </div> 
     <?php endif;?>
    <?php endif;?>
     
     <?php if(isset($totalinfo['tips'])):?>
     <?php if ($totalinfo['tips']>0.001):?> 
     <div class="mytable">
       <div class="mycol col-1 v_center "></div>
       <div class="mycol col-2 v_center text-right index_right"><?php echo t("Tips")." ".$totalinfo['tips_percent']?></div>
       <div class="mycol col-3 v_center text-right"><?php echo FunctionsV3::prettyPrice($totalinfo['tips'])?></div>
     </div> 
     <?php endif;?>
    <?php endif;?>
    
    <?php if($data['calculation_method']==2):?>
    <?php if(isset($totalinfo['voucher_value'])):?>
     <?php if ($totalinfo['voucher_value']>0.001):?>      
     <?php $total_discount+=unPrettyPrice($totalinfo['voucher_value'])?>
     <div class="mytable" style="display:none;">
       <div class="mycol col-1 v_center "></div>
       <div class="mycol col-2 v_center text-right index_right"><?php echo t("Voucher")?></div>
       <div class="mycol col-3 v_center text-right">(<?php echo FunctionsV3::prettyPrice($totalinfo['voucher_value'])?>)</div>
     </div>      
     <?php endif;?>
    <?php endif;?>
    <?php endif;?>
    
     <?php if($data['calculation_method']==2):?>
    <?php if (isset($totalinfo['discounted_amount'])):?>
    <?php if ($totalinfo['discounted_amount']>0.001):?>
      <?php $total_discount+=unPrettyPrice($totalinfo['discounted_amount'])?>
      <div class="mytable" style="display:none;">
       <div class="mycol col-1 v_center "></div>
       <div class="mycol col-2 v_center text-right index_right"><?php echo t("Discount")." ".number_format($totalinfo['merchant_discount_amount'],0);?>%</div>
       <div class="mycol col-3 v_center text-right">(<?php echo FunctionsV3::prettyPrice($totalinfo['discounted_amount'])?>)</div>
     </div> 
    <?php endif;?>
    <?php endif;?>
    <?php endif;?>
        
    <?php if($data['calculation_method']==2):?>
    <?php if (isset($data['points_discount'])):?>
    <?php if ($data['points_discount']>0.001):?>
      <?php $total_discount+=unPrettyPrice($data['points_discount'])?>
      <div class="mytable" style="display:none;">
       <div class="mycol col-1 v_center "></div>
       <div class="mycol col-2 v_center text-right index_right"><?php echo t("Points Discount");?></div>
       <div class="mycol col-3 v_center text-right">(<?php echo FunctionsV3::prettyPrice($data['points_discount'])?>)</div>
     </div> 
    <?php endif;?>
    <?php endif;?>
    <?php endif;?>
    
    <div class="mytable">
     <div class="mycol col-1 v_center "></div>
     <div class="mycol col-2 v_top text-right index_right bold"><span><?php echo t("TOTAL")?></span></div>
     <div class="mycol col-3 v_top text-right bold">
       <?php 
       if($total_discount>=0.01){
       	  /*dump($total_discount);
       	  dump($totalinfo['total']);*/
       	  $total_customer_pays = unPrettyPrice($totalinfo['total'])+$total_discount;       	  
       	  echo FunctionsV3::prettyPrice($total_customer_pays);       
       } else echo FunctionsV3::prettyPrice($totalinfo['total']);?>       
       </div>
    </div>
    
  <?php endif;?>
  <?php endif;?>
      </div>
      
      <!--CUSTOMER PART PART-->
     <div class="fax_total_col_1">
     
        <?php if($data['calculation_method']==2):?>
        <?php if(isset($totalinfo['voucher_value'])):?>
        <?php if ($totalinfo['voucher_value']>0.001):?>      
        <div class="mytable two_col">
          <div class="mycol col-1"><?php echo t("Voucher")?></div>
          <div class="mycol col-2">(<?php echo FunctionsV3::prettyPrice($totalinfo['voucher_value'])?>)</div>
        </div>
        <?php endif;?>
        <?php endif;?>
        <?php endif;?>
        
        <?php if($data['calculation_method']==2):?>
        <?php if(isset($totalinfo['discounted_amount'])):?>
        <?php if ($totalinfo['discounted_amount']>0.001):?>      
        <div class="mytable two_col">
          <div class="mycol col-1"><?php echo t("Discount")." ".number_format($totalinfo['merchant_discount_amount'],0);?>%</div>
          <div class="mycol col-2">(<?php echo FunctionsV3::prettyPrice($totalinfo['discounted_amount'])?>)</div>
        </div>
        <?php endif;?>
        <?php endif;?>
        <?php endif;?>
        
        
        <?php if($data['calculation_method']==2):?>
        <?php if(isset($data['points_discount'])):?>
        <?php if ($data['points_discount']>0.001):?>      
        <div class="mytable two_col">
          <div class="mycol col-1"><?php echo t("Points Discount");?></div>
          <div class="mycol col-2">(<?php echo FunctionsV3::prettyPrice($data['points_discount'])?>)</div>
        </div>
        <?php endif;?>
        <?php endif;?>
        <?php endif;?>
        
        <div class="mytable two_col">
          <div class="mycol col-1 bold">Customer paid</div>
          <div class="mycol col-2 bold"><?php echo FunctionsV3::prettyPrice($totalinfo['total'])?></div>
        </div>
     
	    <div class="mytable signature_wrap">
	       <div class="mycol col-1 ">	       
	        <p class="bold underline"><?php echo t("Sign")?></p>
	        <p><?php echo t("I agree to pay the total amount.")?></p>	        	         
	       </div>	       
	     </div> 
	     
	  </div> <!--end fax_total_col_1-->
      
	  <div class="clear"></div>
  </div>
  <!--END TOTAL-->

</div> <!--fax_page-->
