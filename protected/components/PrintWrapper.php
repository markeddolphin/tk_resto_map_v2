<?php
class PrintWrapper
{
	public static function prepareReceipt($order_id='')
	{
		$_GET['backend']=true; $print = array();
		if ( $data=Yii::app()->functions->getOrder2($order_id)){
			$merchant_id=$data['merchant_id'];
			$json_details=!empty($data['json_details'])?json_decode($data['json_details'],true):false;				
			
			if ( $json_details !=false){
				Yii::app()->functions->displayOrderHTML(array(
			   'order_id'=>$order_id,
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
			  ),$json_details,true,$order_id);
			}
			
			$print[]=array( 'label'=> t("Customer Name"), 'value'=>$data['full_name'] );
			$print[]=array( 'label'=> t("Merchant Name"), 'value'=>$data['merchant_name']);
			if (isset($data['abn']) && !empty($data['abn'])){
				$print[]=array(
				 'label'=>Yii::t("default","ABN"),
				 'value'=>$data['abn']
				);
			}
			$print[]=array('label'=>Yii::t("default","Telephone"),'value'=>$data['merchant_contact_phone']);
			
			$merchant_info=Yii::app()->functions->getMerchant(isset($merchant_id)?$merchant_id:'');
			$full_merchant_address=$merchant_info['street']." ".$merchant_info['city']. " ".$merchant_info['state'].
			" ".$merchant_info['post_code'];
		
			$print[]=array('label'=>Yii::t("default","Address"),'value'=>$full_merchant_address);
			
			$print[]=array('label'=>Yii::t("default","TRN Type"),'value'=>t($data['trans_type']));
			
			$print[]=array(
			 'label'=>Yii::t("default","Payment Type"),
			 'value'=>FunctionsV3::prettyPaymentType('payment_order',$data['payment_type'],$order_id,$data['trans_type'])
			);	       
		   
			if ( $data['payment_provider_name']){
				$print[]=array('label'=>Yii::t("default","Card#"),'value'=>strtoupper($data['payment_provider_name']));
			}
			
			if ( $data['payment_type'] =="pyp"){
				$paypal_info=Yii::app()->functions->getPaypalOrderPayment($order_id);
				$print[]=array(
				   'label'=>Yii::t("default","Paypal Transaction ID"),
				   'value'=>isset($paypal_info['TRANSACTIONID'])?$paypal_info['TRANSACTIONID']:''
				);
			}
						
			$print[]=array(
			 'label'=>Yii::t("default","Reference #"),
			 'value'=>Yii::app()->functions->formatOrderNumber($data['order_id'])
			);
			
			if ( !empty($data['payment_reference'])){
				$print[]=array(
				  'label'=>Yii::t("default","Payment Ref"),
				  'value'=>isset($data['payment_reference'])?$data['payment_reference']:Yii::app()->functions->formatOrderNumber($data['order_id'])
				);
			}
			
			if ( $data['payment_type']=="ccr" || $data['payment_type']=="ocr"){
				$print[]=array(
				  'label'=>Yii::t("default","Card #"),
				  'value'=>Yii::app()->functions->maskCardnumber($data['credit_card_number'])
				);
			}
			
			$trn_date=date('M d,Y G:i:s',strtotime($data['date_created']));
			$print[]=array(
			  'label'=>Yii::t("default","TRN Date"),
			  'value'=>$trn_date
			);
						
			/*dump($data);
			dump($print);
			die();*/
			
			switch ($data['trans_type']) {
				case "delivery":	        		
					$print[]=array(
					 'label'=>Yii::t("default","Delivery Date"),
					 'value'=>Yii::app()->functions->translateDate($data['delivery_date'])
					);
					
					if(!empty($data['delivery_time'])){
					   $print[]=array(
						 'label'=>Yii::t("default","Delivery Time"),
						 'value'=>Yii::app()->functions->timeFormat($data['delivery_time'],true)
					   );
					}
					
					if(!empty($data['delivery_asap'])){
						$delivery_asap=$data['delivery_asap']==1?t("Yes"):'';
						$print[]=array(
						 'label'=>Yii::t("default","Deliver ASAP"),
						 'value'=>$delivery_asap
						);
					}
					
					if (!empty($data['client_full_address'])){		         	
					   $delivery_address=$data['client_full_address'];
					} else $delivery_address=$data['full_address'];		
										
					$print[]=array(
					  'label'=>Yii::t("default","Deliver to"),
					  'value'=>$delivery_address
					);
					
					$print[]=array(
					  'label'=>Yii::t("default","Delivery Instruction"),
					  'value'=>$data['delivery_instruction']
					);         
					
					$print[]=array(
					  'label'=>Yii::t("default","Location Name"),
					  'value'=>$data['location_name']
					);
			   
					$print[]=array(
					  'label'=>Yii::t("default","Contact Number"),
					  'value'=>$data['contact_phone']
					);
					
					if ($data['order_change']>=0.1){
						$print[]=array(
						  'label'=>Yii::t("default","Change"),
						  'value'=>normalPrettyPrice($data['order_change'])
						);
					}
				
					break;
				
				case "pickup":		
				case "dinein":		
				
					$label_date=t("Pickup Date");
					$label_time=t("Pickup Time");
					if ($data['trans_type']=="dinein"){
						$label_date=t("Dine in Date");
						$label_time=t("Dine in Time");
					}   
					
					if (isset($data['contact_phone1'])){
						if (!empty($data['contact_phone1'])){
							$data['contact_phone']=$data['contact_phone1'];
						}
					}
				
					$print[]=array(
					  'label'=>Yii::t("default","Contact Number"),
					  'value'=>$data['contact_phone']
					);
					
					$print[]=array(
					 'label'=>$label_date,
					 'value'=>Yii::app()->functions->translateDate($data['delivery_date'])
					);
					
					if(!empty($data['delivery_time'])){
					   $print[]=array(
						 'label'=>$label_time,
						 'value'=>Yii::app()->functions->timeFormat($data['delivery_time'],true)
					   );
					}
					
					if ($data['order_change']>=0.1){
						$print[]=array(
						  'label'=>Yii::t("default","Change"),
						  'value'=>normalPrettyPrice($data['order_change'])
						);
					}
					
					if ($data['trans_type']=="dinein"){
						$print[]=array(
						  'label'=>t("Number of guest"),
						  'value'=>$data['dinein_number_of_guest']
						);
						$print[]=array(
						  'label'=>t("Special instructions"),
						  'value'=>$data['dinein_special_instruction']
						);
					}
				
				   break;
			
				default:
					break;
			}
			
			return array(
			 'merchant_id'=>$merchant_id,
			 'print'=>$print,
			 'raw'=>Yii::app()->functions->details['raw'],
			 'data'=>$data,
			 'additional_details'=>Yii::app()->functions->additional_details
			);
			
		} else throw new Exception( t("order not found"));
	}
	
	
	public static function doPrint($order_id='', $panel='admin')
	{
		try {
			
			Yii::app()->setImport(array(			
			  'application.modules.printer.components.*',
		   ));
			
			$resp = self::prepareReceipt($order_id);
			//dump($resp);
						
			if (FunctionsV3::hasModuleAddon("printer")){
				switch ($panel) {
					case "admin":
						$html=getOptionA('printer_receipt_tpl');
						break;
				
					default:
						$html = getOption($resp['merchant_id'],'mt_printer_receipt_tpl');
						break;
				}				
				if(!empty($html)){										
					
					if($panel=="admin"):
					
						if($print_receipt = ReceiptClass::formatReceipt($html,$resp['print'],$resp['raw'],$resp['data'])){
							PrinterClass::printReceipt($order_id,$print_receipt,true);														
						} else throw new Exception( t("failed formating receipt") );
					
					else :
					
						if($print_receipt = ReceiptClass::formatReceipt($html,$resp['print'],$resp['raw'],$resp['data'])){
							PrinterClass::printReceiptMerchant($resp['merchant_id'],$order_id,$print_receipt,true);
						} else throw new Exception( t("failed formating receipt") );
					
					endif;
					
					FunctionsV3::fastRequest(FunctionsV3::getHostURL().Yii::app()->createUrl("printer/cron/processprint"));	
										
				} else throw new Exception( t("Template not found") );
			} else throw new Exception( t("Printer addon not available") );
			
		} catch (Exception $e) {
			throw new Exception( $e->getMessage() );
		}
	}
	
}
/*end class*/