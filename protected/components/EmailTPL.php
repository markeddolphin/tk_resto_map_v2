<?php
class EmailTPL extends CApplicationComponent
{
	
	public static function forgotPass($data='',$token='')
	{
      $website_title=Yii ::app()->functions->getOptionAdmin('website_title');
      $url=Yii::app()->getBaseUrl(true)."/store/forgotPassword/?token=".$token;      
	  return <<<HTML
	  <p>Hi $data[first_name]</p>
	  <br/>
	  <p>Click on the link below to change your password.</p>
	  <p><a href="$url">$url</a></p>
	  <p>Thank you.</p>
	  <p>- $website_title</p>
HTML;
	}
	
	public static function merchantActivationCode($data='')
	{
	$website_url=Yii::app()->getBaseUrl(true)."/merchant";
    $website_title=Yii::app()->functions->getOptionAdmin('website_title');
    
    $email_tpl_activation=Yii::app()->functions->getOptionAdmin('email_tpl_activation');    
    if (!empty($email_tpl_activation)){
    	$email_tpl_activation=Yii::app()->functions->smarty("restaurant_name",$data['restaurant_name'],$email_tpl_activation); 
    	$email_tpl_activation=Yii::app()->functions->smarty("activation_key",$data['activation_key'],$email_tpl_activation); 
    	$email_tpl_activation=Yii::app()->functions->smarty("website_title",$website_title,$email_tpl_activation); 
    	$email_tpl_activation=Yii::app()->functions->smarty("website_url",$website_url,$email_tpl_activation); 
    	return $email_tpl_activation;
    }
    
	return <<<HTML
	<p>hi $data[restaurant_name]<br/></p>
	<p>Thank you for joining us!</p>
	<p>Your activation code is $data[activation_key]</p>
	
	<p>Click <a href="$website_url">here</a> to login or visit $website_url</p>
	
	<p>Thank you.</p>
	<p>- $website_title</p>
HTML;
	}
	
	public static function merchantActivationCodePlain()
	{		
	return <<<HTML
	<p>hi {restaurant_name}<br/></p>
	<p>Thank you for joining us!</p>
	<p>Your activation code is {activation_key}</p>
	
	<p>Click <a href="{website_url}">here</a> to login or visit {website_url}</p>
	
	<p>Thank you.</p>
	<p>- {website_title}</p>
HTML;
	}	
	
	public static function merchantForgotPass($data='',$code='')
	{
	  $website_title=Yii::app()->functions->getOptionAdmin('website_title');
	  
	  $email_tpl_forgot=Yii::app()->functions->getOptionAdmin('email_tpl_forgot');	
	  if (!empty($email_tpl_forgot)){
	  	  $email_tpl_forgot=Yii::app()->functions->smarty("restaurant_name",$data['restaurant_name'],$email_tpl_forgot); 
	  	  $email_tpl_forgot=Yii::app()->functions->smarty("verification_code",$code,$email_tpl_forgot); 
	  	  $email_tpl_forgot=Yii::app()->functions->smarty("website_title",$website_title,$email_tpl_forgot); 	  	  
	  	  return $email_tpl_forgot;	  	  
	  }
			  
	  return <<<HTML
	  <p>hi $data[restaurant_name]<br/></p>
	  <p>Your verification code is $code</p>
	  <p>Thank you.</p>
	<p>- $website_title</p>
HTML;
	}
	
	public static function merchantForgotPassPlain()
	{	 
	  return <<<HTML
	  <p>hi {restaurant_name}<br/></p>
	  <p>Your verification code is {verification_code}</p>
	  <p>Thank you.</p>
	<p>- {website_title}</p>
HTML;
	}	
	
	public static function salesReceipt($data='',$item_details='')
	{				
		$tr=""; $sms_details='';
		if (is_array($data) && count($data)>=1){
			foreach ($data as $val) {				
				$tr.="<tr>";
				$tr.="<td>".$val['label']."</td>";
				$tr.="<td>".stripslashes($val['value'])."</td>";
				$tr.="</tr>";
			}
		}
		
		$mid=isset($item_details['total']['mid'])?$item_details['total']['mid']:'';
		//dump($mid);
		
		//dump($item_details);
		
		$tr.="<tr>";
		$tr.="<td colspan=\"2\">&nbsp;</td>";
		$tr.="</tr>";
		if (isset($item_details['item'])){
			if (is_array($item_details['item']) && count($item_details['item'])>=1){
				foreach ($item_details['item'] as $item) {
					//dump($item);
					$notes='';
					$item_total=$item['qty']*$item['discounted_price'];
					if (!empty($item['order_notes'])){
					    $notes="<p>".$item['order_notes']."</p>";
					}
					$cookref='';
					if (!empty($item['cooking_ref'])){
					    $cookref="<p>".qTranslate($item['cooking_ref'],'cooking_name',$item['cooking_name_trans'])."</p>";
					}
					$size='';
					if (!empty($item['size_words'])){
						$size_words=qTranslate($item['size_words'],'size_name',$item['size_name_trans']);
					    $size="<p>".$size_words."</p>";
					}
					
					$ingredients='';
					if (isset($item['ingredients'])){
						if (is_array($item['ingredients']) && count($item['ingredients'])>=1){
							$ingredients.="<p>".t("Ingredients")."</p>";
							$ingredients.="<p>";
							foreach ($item['ingredients'] as $ingredients_val) {
								if($details_ingredients=FunctionsV3::getIngredientsByName($ingredients_val,$mid)){
									$_ingredients['ingredients_name_trans']=json_decode($details_ingredients['ingredients_name_trans'],true);			          		    
									$ingredients.="- ".qTranslate($ingredients_val,'ingredients_name',$_ingredients)."<br/>";
									
								} else $ingredients.="- $ingredients_val<br/>";								
							}
							$ingredients.="</p>";
						}
					}
					
					if (!empty($item['category_id'])){
						$tr.="<tr>";
						$tr.="<td colspan=\"2\"><b>".
						qTranslate($item['category_name'],'category_name',$item['category_name_trans'])."</td>";
						$tr.="</b></tr>";
					}
					
					$item_name=qTranslate($item['item_name'],'item_name',$item['item_name_trans']);
					
					$tr.="<tr>";
				    $tr.="<td>".$item['qty']." ".$item_name.$size.$notes.$cookref.$ingredients."</td>";
				    $tr.="<td>".FunctionsV3::prettyPrice($item_total)."</td>";
				    $tr.="</tr>";
				    
				    //$sms_details.=$item['qty']." x ".$item_name." ".standardPrettyFormat($item_total);
				    $sms_details.=$item['qty']." x ".$item_name.$size.$notes.$cookref.$ingredients." ".standardPrettyFormat($item_total);
				    $sms_details.=FunctionsV3::smsSeparator();
				    				    				 				   
				    /*if (isset($item['sub_item'])){
				    	if (is_array($item['sub_item']) && count($item['sub_item'])>=1){
					    	foreach ($item['sub_item'] as $itemsub) {						    		
					    		$subitem_total=$itemsub['addon_qty']*$itemsub['addon_price'];				    		
					    		$tr.="<tr>";					            
					            $tr.="<td style=\"text-indent:10px;\">".$itemsub['addon_qty']."x".FunctionsV3::prettyPrice($itemsub['addon_price'])." ".$itemsub['addon_name']."</td>";
					            $tr.="<td>".FunctionsV3::prettyPrice($subitem_total)."</td>";
					            $tr.="</tr>";
					            
					            $sms_details.=$itemsub['addon_qty']." x ".standardPrettyFormat($itemsub['addon_price']);
					            $sms_details.=" ".$itemsub['addon_name'];
					            $sms_details.=FunctionsV3::smsSeparator();
					    	}
				    	}
				    }*/
				    
				    if (isset($item['new_sub_item'])){
				    	if (is_array($item['new_sub_item']) && count($item['new_sub_item'])>=1){
				    		foreach ($item['new_sub_item'] as $subcategory_name=> $subcategory_item) {
				    			//dump($subcategory_item);
				    			
				    			if(!isset($subcategory_item[0])){
				    				$subcategory_item[0]['subcategory_name_trans']='';
				    			}
				    			
				    			$subcategory_name_trans=qTranslate($subcategory_name,'subcategory_name',
				    			$subcategory_item[0]['subcategory_name_trans']);
				    			
				    			$tr.="<tr>";
				    			$tr.="<td colspan=\"2\">".$subcategory_name_trans."</td>";
				    			
				    			$tr.="</tr>";
				    			$sms_details.=$subcategory_name_trans;
				    			$sms_details.=FunctionsV3::smsSeparator();
				    			foreach ($subcategory_item as $itemsub) {
				    				
				    				$addon_name=qTranslate($itemsub['addon_name'],'sub_item_name',
				    				$itemsub['sub_item_name_trans']);
				    				
				    				$subitem_total=$itemsub['addon_qty']*$itemsub['addon_price'];				    		
						    		$tr.="<tr>";
						            $tr.="<td style=\"text-indent:10px;\">".$itemsub['addon_qty']."x".FunctionsV3::prettyPrice($itemsub['addon_price'])." ".$addon_name."</td>";
						            $tr.="<td>".FunctionsV3::prettyPrice($subitem_total)."</td>";
						            $tr.="</tr>";
						            
						            $sms_details.=$itemsub['addon_qty']." x ".standardPrettyFormat($itemsub['addon_price']);
					                $sms_details.=" ".$addon_name;
					                $sms_details.=FunctionsV3::smsSeparator();
				    			}
				    		}
				    	}
				    }
				    
				}
			}
		}
		$tr.="<tr>";
		$tr.="<td colspan=\"2\">&nbsp;</td>";
		$tr.="</tr>";
		
		//dump($item_details);
		
		Yii::app()->functions->additional_details=$sms_details;
		
		if (isset($item_details['total'])){
			
			if(!isset($item_details['total']['less_voucher'])){
				$item_details['total']['less_voucher']='';
			}
			if(!isset($item_details['total']['pts_redeem_amt'])){
				$item_details['total']['pts_redeem_amt']='';
			}
			if(!isset($item_details['total']['tips'])){
				$item_details['total']['tips']='';
			}
			
			if ($item_details['total']['less_voucher']>0.001){
				$tr.="<tr>";
				$tr.="<td>".Yii::t("default","Less Voucher")." " .$item_details['total']['voucher_type'] . ":</td>";
				$tr.="<td>(".FunctionsV3::prettyPrice($item_details['total']['less_voucher']).")</td>";
				$tr.="</tr>";
			}
			
			if ($item_details['total']['pts_redeem_amt']>0.001){
				$tr.="<tr>";
				$tr.="<td>".Yii::t("default","Points discount").":</td>";
				$tr.="<td>(".FunctionsV3::prettyPrice($item_details['total']['pts_redeem_amt']).")</td>";
				$tr.="</tr>";
			}
						
			if(!isset($item_details['total']['discounted_amount'])){
				$item_details['total']['discounted_amount']=0;
			}
			
			if($item_details['total']['calculation_method']==1){
				if($item_details['total']['discounted_amount']>0.001){
					$tr.="<tr>";
				    $tr.="<td>".Yii::t("default","Discount")." " . $item_details['total']['merchant_discount_amount']  ."% :</td>";
				    $tr.="<td>".FunctionsV3::prettyPrice($item_details['total']['discounted_amount'])."</td>";
				    $tr.="</tr>";
				}
			}
						
			$tr.="<tr>";
			$tr.="<td>".Yii::t("default","Sub Total").":</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($item_details['total']['subtotal'])."</td>";
			$tr.="</tr>";
			
			if (!empty($item_details['total']['delivery_charges'])):
			$tr.="<tr>";
			$tr.="<td>".Yii::t("default","Delivery Fee").":</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($item_details['total']['delivery_charges'])."</td>";
			$tr.="</tr>";
			endif;
			
					
			if (!empty($item_details['total']['merchant_packaging_charge'])):
			if ($item_details['total']['merchant_packaging_charge']>0.0001){
			$tr.="<tr>";
			$tr.="<td>".Yii::t("default","Packaging").":</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($item_details['total']['merchant_packaging_charge'])."</td>";
			$tr.="</tr>";
			}
			endif;
			
			if(isset($item_details['total']['tax_amt'])){
				$tr.="<tr>";
				$tr.="<td>".Yii::t("default","Tax")." ".$item_details['total']['tax_amt']."%</td>";
				$tr.="<td>".FunctionsV3::prettyPrice($item_details['total']['taxable_total'])."</td>";
				$tr.="</tr>";
			}
			
			if (!isset($item_details['total']['card_fee'])){
				$item_details['total']['card_fee']='';
			}
			
			if ($item_details['total']['card_fee']>0):
			$tr.="<tr>";
			$tr.="<td>".Yii::t("default","Card Fee").":</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($item_details['total']['card_fee'])."</td>";
			$tr.="</tr>";
			endif;
			
			if ($item_details['total']['tips']>0.001){
				$tr.="<tr>";
				$tr.="<td>".Yii::t("default","Tips")." " .$item_details['total']['tips_percent'] . ":</td>";
				$tr.="<td>".FunctionsV3::prettyPrice($item_details['total']['tips'])."</td>";
				$tr.="</tr>";
			}
			
			if($item_details['total']['calculation_method']==2){
				if($item_details['total']['less_voucher_orig']>0.01){
					$tr.="<tr>";
					$tr.="<td>".Yii::t("default","Voucher"). ":</td>";
					$tr.="<td>(".FunctionsV3::prettyPrice($item_details['total']['less_voucher_orig']).")</td>";
					$tr.="</tr>";
				}
				
				if($item_details['total']['pts_redeem_amt_orig']>0.01){
					$tr.="<tr>";
					$tr.="<td>".Yii::t("default","Points Discount"). ":</td>";
					$tr.="<td>(".FunctionsV3::prettyPrice($item_details['total']['pts_redeem_amt_orig']).")</td>";
					$tr.="</tr>";
				}
				
				if($item_details['total']['discounted_amount']>0.001){
					$tr.="<tr>";
				    $tr.="<td>".Yii::t("default","Discount")." " . $item_details['total']['merchant_discount_amount']  ."% :</td>";
				    $tr.="<td>(".FunctionsV3::prettyPrice($item_details['total']['discounted_amount']).")</td>";
				    $tr.="</tr>";
				}
			}
			
			$tr.="<tr>";
			$tr.="<td>".Yii::t("default","Total").":</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($item_details['total']['total'])."</td>";
			$tr.="</tr>";
		}
		ob_start();
		?>
		<!--<div style="display: block;max-height: 70px;max-width: 200px;">-->
		<?php //echo Widgets::receiptLogo();?>
		<!--</div>-->
		<table border="0">
		<?php echo $tr;?>		
		</table>
		<?php	
		$receipt = ob_get_contents();
        ob_end_clean();
        return $receipt;
	}
	
	public static function receiptTPL()
	{
		return <<<HTML
<p>Dear {customer-name},</p>
<br/><br/>
<p> Thank you for shopping at Karendera. We hope you enjoy your new purchase! Your order number is {receipt-number}. We have included your order receipt and delivery details below:	</p>
<br/>
 {receipt}	
	
<br/><br/>
<p> Kind Regards</p>
HTML;
	}
	
	public static function bookingApproved()
	{
		return <<<HTML
<p>Dear {customer-name},</p>
<br/><br/>
<p> Thank you. Your booking has been approved.</p>
<p>{booking-information}</p>
<br/>
	
<br/><br/>
<p> Kind Regards</p>
HTML;
	}	
	
	public static function bookingDenied()
	{
		return <<<HTML
<p>Dear {customer-name},</p>
<br/><br/>
<p> We regret to inform you that your table booking has been denied.</p>
<p>{booking-information}</p>
<br/>
	
<br/><br/>
<p> Kind Regards</p>
HTML;
	}		
	
	public static function bookingTPL()
	{
		return <<<HTML
<p>Dear admin,</p>
<br/>
<p> New table booking has been receive.</p>
<p>{booking-information}</p>
<br/>
	
<br/><br/>
<p> Kind Regards</p>
HTML;
	}			
	

	public static function bankDepositTPL()
	{
		return <<<HTML
<p><strong>Deposit Instructions</strong></p>
<br/>
<p>
Please deposit {amount} to :
</p>

<p>
Bank : Your bank name<br/>
Account Number : Your bank account number<br/>
Account Name : Your bank account name<br/>
</p>

<p>When deposit is completed {verify-payment-link}</p>

<br/><br/>
<p> Kind Regards</p>
HTML;
	}	
	
	public static function bankDepositedReceive()
	{
		return <<<HTML
<p>hi Admin,</p>
<br/><br/>
<p>There is new submitted offline bank deposited. you can check this via admin panel</p>
<br/>
	
<br/><br/>
HTML;
	}			
	
	public static function adminForgotPassword($newpass='')
	{	 
	  return <<<HTML
	  <p>hi <br/></p>
	  <p>Your password has been reset to : $newpass</p>
	  <p>Thank you.</p>	
HTML;
	}	
	
	public static function merchantChangeStatus()
	{	 
	  return <<<HTML
	  <p>hi {owner_name}<br/></p>
	  <p>your merchant {restaurant_name} has change status to {status}</p>
	  <br/>
	  <p>{website_title}</p>
	  <p>Thank you.</p>	
HTML;
	}		
	
	public static function receiptMerchantTPL()
	{
		return <<<HTML
<p>hi admin,</p>
<br/>
<p>There is a new order with the reference number {receipt-number} from customer {customer-name}</p>
<br/>
 {receipt}	
	
<br/><br/>
<p><a href="{confirmation-link}">Click here</a> to accept the order<br/>
or simply visit this link {confirmation-link}
</p>
<br/>
<p> Kind Regards</p>
HTML;
	}	
	
	public static function payoutRequest()
	{
		return <<<HTML
<p>Hi {merchant-name},</p>
<br/>
<p>We're just letting you know that we got your request to pay out {payout-amount} via {payment-method} to {account}</p>
<br/> 
	
<p>
You can cancel this request any time before {cancel-date} here:<br/>
{cancel-link}
</p>

<p>
We will complete this request on the {process-date} (or the next business day), but it can take up to 7 days to appear in your account. A second confirmation email will be sent at this time.
</p>

<br/>
<p> Kind Regards</p>
HTML;
	}
	
	public static function payoutProcess()
	{
return <<<HTML
<p>Hi {merchant-name},</p>
<br/>
<p>We just processed your request for {payout-amount} via {payment-method}.</p>
<p>Your payment was sent to {acoount}</p>
<br/> 

<p>Happy Spending!</p>

<br/>
<p> Kind Regards</p>
HTML;
	}
	
	public static function faxNotification()
	{
return <<<HTML
<p>Hi admin,</p>
<br/>
<p>You have a new fax payment from <b>{merchant-name}</b> with the total amount of {amount}</p>
<p>Payment method : {payment-method}</p>
<p>Package Name : {package-name}</p>
<br/> 

<br/>
<p> Kind Regards</p>
HTML;
	}	
	
	public static function bankDepositedReceiveMerchant()
	{
		return <<<HTML
<p>hi merchant,</p>
<br/><br/>
<p>There is new submitted offline bank deposited. you can check this via merchant panel</p>
<br/>
	
<br/><br/>
HTML;
	}			
	
	public static function signupEmailVerification()
	{
		return <<<HTML
<p>hi {firstname},</p>
<br/><br/>
<p>Your verification code is : {code}</p>
<br/>
	
<br/><br/>
<p> Kind Regards</p>
HTML;
	}			

	public static function salesReceiptTax($data='',$item_details='')
	{			
				
		$tr=""; $sms_details='';
		if (is_array($data) && count($data)>=1){
			foreach ($data as $val) {				
				$tr.="<tr>";
				$tr.="<td>".$val['label']."</td>";
				$tr.="<td>".stripslashes($val['value'])."</td>";
				$tr.="</tr>";
			}
		}
		
		$mid=isset($item_details['total']['mid'])?$item_details['total']['mid']:'';
		
		$tr.="<tr>";
		$tr.="<td colspan=\"2\">&nbsp;</td>";
		$tr.="</tr>";
		if (isset($item_details['item'])){
			if (is_array($item_details['item']) && count($item_details['item'])>=1){
				foreach ($item_details['item'] as $item) {
					//dump($item);
					$notes='';
					$item_total=$item['qty']*$item['discounted_price'];
					if (!empty($item['order_notes'])){
					    $notes="<p>".$item['order_notes']."</p>";
					}
					$cookref='';
					if (!empty($item['cooking_ref'])){						
					    $cookref="<p>".qTranslate($item['cooking_ref'],'cooking_name',$item['cooking_name_trans'])."</p>";
					}
					$size='';					
					if (!empty($item['size_words'])){
						$size_words=qTranslate($item['size_words'],'size_name',$item['size_name_trans']);
					    $size="<p>".$size_words."</p>";
					}
					
					$ingredients='';
					if (isset($item['ingredients'])){
						if (is_array($item['ingredients']) && count($item['ingredients'])>=1){
							$ingredients.="<p>".t("Ingredients")."</p>";
							$ingredients.="<p>";
							foreach ($item['ingredients'] as $ingredients_val) {
								$ingredients.="- $ingredients_val<br/>";
							}
							$ingredients.="</p>";
						}
					}
					
					if (!empty($item['category_id'])){
						$tr.="<tr>";
						$tr.="<td colspan=\"2\"><b>".
						qTranslate($item['category_name'],'category_name',$item['category_name_trans'])."</td>";
						$tr.="</b></tr>";
					}
					
					$item_name=qTranslate($item['item_name'],'item_name',$item['item_name_trans']);
					
					$tr.="<tr>";
				    $tr.="<td>".$item['qty']." ".$item_name.$size.$notes.$cookref.$ingredients."</td>";
				    $tr.="<td>".FunctionsV3::prettyPrice($item_total)."</td>";
				    $tr.="</tr>";
				    
				    
				    /*if (isset($item['sub_item'])){
				    	if (is_array($item['sub_item']) && count($item['sub_item'])>=1){
					    	foreach ($item['sub_item'] as $itemsub) {				    							    		
					    		$subitem_total=$itemsub['addon_qty']*$itemsub['addon_price'];				    		
					    		$tr.="<tr>";					            
					            $tr.="<td style=\"text-indent:10px;\">".$itemsub['addon_qty']."x".FunctionsV3::prettyPrice($itemsub['addon_price'])." ".$itemsub['addon_name']."</td>";
					            $tr.="<td>".FunctionsV3::prettyPrice($subitem_total)."</td>";
					            $tr.="</tr>";
					    	}
				    	}
				    }*/
				    
				     if (isset($item['new_sub_item'])){
				    	if (is_array($item['new_sub_item']) && count($item['new_sub_item'])>=1){
				    		foreach ($item['new_sub_item'] as $subcategory_name=> $subcategory_item) {
				    			
				    			if(!isset($subcategory_item[0])){
				    				$subcategory_item[0]['subcategory_name_trans']='';
				    			}
				    			
				    			$tr.="<tr>";
				    			$tr.="<td colspan=\"2\">".qTranslate($subcategory_name,'subcategory_name',
				    			$subcategory_item[0]['subcategory_name_trans'])."</td>";
				    			
				    			$tr.="</tr>";
				    							    							    		
				    			foreach ($subcategory_item as $itemsub) {
				    				
				    				$addon_name=qTranslate($itemsub['addon_name'],'sub_item_name',
				    				$itemsub['sub_item_name_trans']);
				    				
				    				$subitem_total=$itemsub['addon_qty']*$itemsub['addon_price'];				    		
						    		$tr.="<tr>";
						            $tr.="<td style=\"text-indent:10px;\">".$itemsub['addon_qty']."x".FunctionsV3::prettyPrice($itemsub['addon_price'])." ".$addon_name."</td>";
						            $tr.="<td>".FunctionsV3::prettyPrice($subitem_total)."</td>";
						            $tr.="</tr>";
						            						            
				    			}
				    		}
				    	}
				    }
				    
				}
			}
		}
		$tr.="<tr>";
		$tr.="<td colspan=\"2\">&nbsp;</td>";
		$tr.="</tr>";		
		$data_total=$item_details['total'];
		
		if ($data_total['delivery_charges']>0){
			$tr.="<tr>";
			$tr.="<td>".Yii::t("default","Delivery Fee").":</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($data_total['delivery_charges'])."</td>";
			$tr.="</tr>";
		}

		if(isset($data_total['card_fee'])){
		if ($data_total['card_fee']>0){
			$tr.="<tr>";
			$tr.="<td>".Yii::t("default","Card Fee").":</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($data_total['card_fee'])."</td>";
			$tr.="</tr>";
		}
		}

		if(isset($data_total['merchant_packaging_charge'])){
		if ($data_total['merchant_packaging_charge']>0){
			$tr.="<tr>";
			$tr.="<td>".Yii::t("default","Packaging").":</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($data_total['merchant_packaging_charge'])."</td>";
			$tr.="</tr>";
		}
		}
		
		if(isset($data_total['cart_tip_percentage'])){
		if ($data_total['cart_tip_percentage']>0){
			$tr.="<tr>";
			$tr.="<td>". t("Tip")." ".$data_total['cart_tip_percentage']."%:</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($data_total['tips'])."</td>";
			$tr.="</tr>";
		}
		}
		
		if(isset($data_total['discounted_amount'])){
		if ($data_total['discounted_amount']>0){
		 	$tr.="<tr>";
			$tr.="<td>".t("Discount")." ".$data_total['merchant_discount_amount']."%:</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($data_total['discounted_amount'])."</td>";
			$tr.="</tr>";
		}
		}
		
		if(isset($data_total['pts_redeem_amt'])){
		if ($data_total['pts_redeem_amt']>0){
			$tr.="<tr>";
			$tr.="<td>".t("Points Discount").":</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($data_total['pts_redeem_amt'])."</td>";
			$tr.="</tr>";
		}
		}
		
		if(isset($data_total['less_voucher'])){
		if ($data_total['less_voucher']>0){
			$tr.="<tr>";
			$tr.="<td>".t("Less Voucher")." ".":</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($data_total['less_voucher'])."</td>";
			$tr.="</tr>";
		}
		}
		
		if(isset($data_total['subtotal'])){
		if ($data_total['subtotal']>0){
			$tr.="<tr>";
			$tr.="<td>".t("Sub Total").":</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($data_total['subtotal'])."</td>";
			$tr.="</tr>";
		}
		}
		
		if(isset($data_total['taxable_total'])){
		if ($data_total['taxable_total']>0){
			$tr.="<tr>";
			$tr.="<td>".t("Tax")." ".$data_total['tax_amt']."%:</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($data_total['taxable_total'])."</td>";
			$tr.="</tr>";
		}
		}
		
		if(isset($data_total['total'])){
		if ($data_total['total']>0){
			$tr.="<tr>";
			$tr.="<td>".t("Total").":</td>";
			$tr.="<td>".FunctionsV3::prettyPrice($data_total['total'])."</td>";
			$tr.="</tr>";
		}
		}
		
		$website_enabled_rcpt=getOptionA("website_enabled_rcpt");
		ob_start();
		?>
		<?php if ($website_enabled_rcpt==2):?>
		<!--<div style="display: block;max-height: 70px;max-width: 200px;">-->
		<?php //echo Widgets::receiptLogo();?>
		<!--</div>		-->
		<?php endif;?>
		<table border="0">
		<?php echo $tr;?>		
		</table>
		<?php	
		$receipt = ob_get_contents();
        ob_end_clean();
        return $receipt;
	}	
		
} /*END CLASS*/