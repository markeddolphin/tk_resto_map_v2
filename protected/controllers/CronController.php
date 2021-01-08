<?php
/**
 * CronController Controller
 *
 */
//if (!isset($_SESSION)) { session_start(); }

class CronController extends CController
{
	
	public function init()
	{		
		 $name=Yii::app()->functions->getOptionAdmin('website_title');
		 if (!empty($name)){		 	
		 	 Yii::app()->name = $name;
		 }	
		 		 
		 // set website timezone
		 $website_timezone=Yii::app()->functions->getOptionAdmin("website_timezone");		 		 
		 if (!empty($website_timezone)){		 	
		 	Yii::app()->timeZone=$website_timezone;
		 }		 				 
	}
	
	public function actionIndex()
	{
		echo "CONTROLLER INDEX";
	}
	
	public function actionProcessBroacast()
	{
	   define('LOCK_SUFFIX', '.lock');
		
		if(($pid = cronHelper::lock()) !== FALSE) {			
			if(isset($_GET['debug'])){echo 'cron running';}
			
			$this->ProcessBroacast();
			//sleep(1); // Cron job code for demonstration
	
			cronHelper::unlock();
	    } else {	    	
	    	if(isset($_GET['debug'])){echo "CRON LOCK";}
	    }
	}
	
	public function ProcessBroacast()
	{
		$cron=new CronFunctions;
		$db_ext=new DbExt;
		$stmt="SELECT * FROM
		{{sms_broadcast}}
		WHERE
		status IN ('pending')
		LIMIT 0,1
		";
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {
				if(isset($_GET['debug'])){dump($val);}
				if ( $val['send_to']==1 ){
					$cron->getAllCustomer($val);
				} elseif ( $val['send_to']==2){
					if(isset($_GET['debug'])){echo "Merchant customer";}
					$cron->getAllCustomerByMerchant($val);
				} else {
					if(isset($_GET['debug'])){echo "custom mobile";}
					$cron->customMobile($val);
				}
				$db_ext->updateData("{{sms_broadcast}}",
				  array('status'=>"process",'date_modified'=>FunctionsV3::dateNow()),
				  'broadcast_id',$val['broadcast_id']);
			}
		} else {
			if(isset($_GET['debug'])){
			   echo "<p>No records to process</p>";
			}
		}
	}	

	public function actionProcessSMS()
	{
	   define('LOCK_SUFFIX', '.locksms');
		
		if(($pid = cronHelper::lock()) !== FALSE) {			
			if(isset($_GET['debug'])){
			   echo 'cron running sms';
			}
			
			$this->ProcessSMS();
			sleep(1); // Cron job code for demonstration
	
			cronHelper::unlock();
	    } else {	    	
	    	if(isset($_GET['debug'])){
	    	   echo "CRON LOCK";
	    	}
	    }
	}
			
	public function actionProcessPayout()
	{		
		$db_ext=new DbExt;
		
		$paypal_client_id=yii::app()->functions->getOptionAdmin('wd_paypal_client_id');
		$paypal_client_secret=yii::app()->functions->getOptionAdmin('wd_paypal_client_secret');
		
		$paypal_config=Yii::app()->functions->getPaypalConnectionWithdrawal();
		//dump($paypal_config);
		$Paypal=new Paypal($paypal_config);
		$Paypal->debug=true;
		
		$website_title=yii::app()->functions->getOptionAdmin('website_title');
		
		$cron=new CronFunctions;		
		if ( $res=$cron->getPayoutToProcess()){
			if (is_array($res) && count($res)>=1){
				foreach ($res as $val) {
					$withdrawal_id=$val['withdrawal_id'];
					$api_raw_response='';
					$status_msg='';
					//dump($val);
					switch ($val['payment_method']){
						case "paypal":
							//dump("Process paypal");
							//if (!empty($paypal_client_id) && !empty($paypal_client_secret)){
							if (is_array($paypal_config) && count($paypal_config)>=1){
								if ( $val['account']!=""){
									
									$Paypal->params['RECEIVERTYPE']="EmailAddress";
									$Paypal->params['CURRENCYCODE']="USD";
									$Paypal->params['EMAILSUBJECT']="=You have a payment from ".$website_title;
									
									$Paypal->params['L_EMAIL0']=$val['account'];
									$Paypal->params['L_AMT0']=normalPrettyPrice($val['amount']);
									$Paypal->params['L_UNIQUEID0']=str_pad($val['withdrawal_id'],10,"0");																														
									if ( $pay_resp=$Paypal->payout()){
									    dump($pay_resp);
									    if ( $pay_resp['ACK']=="Success"){
									    	$status_msg='paid';		
									    	$api_raw_response=json_encode($pay_resp);
									    } else {
									    	$api_raw_response=json_encode($pay_resp);
									    	$status_msg=$pay_resp['L_LONGMESSAGE0'];
									    }
									} else $status_msg=$Paypal->getError();
								} else $status_msg=t("Paypal account is empty");
							} else $status_msg=t("Payout settings for paypal not yet set");
							break;
							
						case "bank":
							$status_msg='paid';
							break;	
					}
					
					echo "<h3>Update status</h3>";
					/*dump($api_raw_response);
					dump($status_msg);*/
					$params_update=array(
					  'date_process'=>FunctionsV3::dateNow(),
					  'api_raw_response'=>$api_raw_response,
					  'status'=>$status_msg
					);
					//dump($params_update);
					if ( $db_ext->updateData("{{withdrawal}}",$params_update,'withdrawal_id',$withdrawal_id)){
						//echo "<h2>Update ok</h2>";
					} //else echo "<h2>Update Failed</h2>";
					
					if ( $status_msg=="paid"){
						// send email
						$subject=yii::app()->functions->getOptionAdmin('wd_template_process_subject');
						if (empty($subject)){
	                        $subject=t("Your Request for Withdrawal has been Processed");
                        }
                        if ( $merchant_info=Yii::app()->functions->getMerchant($val['merchant_id'])){ 
                        	$merchant_email=$merchant_info['contact_email'];
                        	$tpl=yii::app()->functions->getOptionAdmin('wd_template_process');
                        	$tpl=smarty("merchant-name",$merchant_info['restaurant_name'],$tpl);
			                $tpl=smarty("payout-amount",standardPrettyFormat($val['amount']),$tpl);
			                $tpl=smarty("payment-method",$val['payment_method'],$tpl);
			                $tpl=smarty("acoount",$val['account'],$tpl);
                        	//dump($tpl);
                        	if(!empty($tpl)){
                        		sendEmail($merchant_email,'',$subject,$tpl);
                        	}
                        }	
					}
				}
			}
		} //else dump("No record to process");
	}
	
	public function actionFax()
	{
		$msg='';
		$send_fax_link='https://www.faxage.com/httpsfax.php';
		
		$db_ext=new DbExt;
		$stmt="SELECT * FROM
		{{fax_broadcast}}
		WHERE
		status='pending'
		AND faxno!=''		
		LIMIT 0,5
		";
		
		$fax_company=yii::app()->functions->getOptionAdmin("fax_company");
		$fax_username=yii::app()->functions->getOptionAdmin("fax_username");
		$fax_password=yii::app()->functions->getOptionAdmin("fax_password");
		
		/*dump("company: ".$fax_company);
		dump("username: ".$fax_username);
		dump("password: ".$fax_password);*/
		$notify_url=websiteUrl()."/cron/faxpostback/";
		
		if ( $res=$db_ext->rst($stmt)){			
			foreach ($res as $val) {
				//dump($val);				
				$jobid='';
				$record_id=$val['id'];
				$credit=Yii::app()->functions->getMerchantFaxCredit($val['merchant_id']);	    	
				//dump($credit);
				if ($credit>=1){
					$params="username=".$fax_username;
					$params.="&company=".$fax_company;
					$params.="&password=".$fax_password;
					$params.="&recipname=".$val['recipname'];
					$params.="&faxno=".$val['faxno'];
					$params.="&operation=sendfax";
					$params.="&faxurl=".$val['faxurl'];
					$params.="&url_notify=$notify_url";					
					//dump($params);
					if ( $response=Yii::app()->functions->Curl($send_fax_link,$params)){
						$msg=$response;
						if (preg_match("/JOBID/i", $response)) {
							$jobid=str_replace("JOBID:",'',$response);
							$jobid=trim($jobid);
						} else $jobid='';
					} else $msg="Invalid response";
				} else $msg=t("Zero credits");
				
				$params_update=array(
				 'status'=>"process",
				 'api_raw_response'=>$msg,
				 'date_process'=>FunctionsV3::dateNow(),
				 'jobid'=>$jobid
				);
				$db_ext->updateData("{{fax_broadcast}}",$params_update,'id',$record_id);
			} /*end foreach*/
		} //else $msg="NO records to process";
		
		//dump("Result: ".$msg);
	}
	
	public function actionFaxPostBack()
	{
		$data=$_REQUEST;
		dump($data);
		if ( $res=Yii::app()->functions->getFaxJobId($data['jobid'])){
			dump($res);
			$record_id=$res['id'];
			$params=array(
			 'status'=>$data['shortstatus'],
			 'api_raw_response'=>$data['longstatus'],
			 'date_postback'=>FunctionsV3::dateNow()
			);
			dump($params);
			$db_ext=new DbExt;
			$db_ext->updateData("{{fax_broadcast}}",$params,'jobid',$data['jobid']);
		}
	}
	
	public function actionSetMerchantExpired()
	{		
		Yii::app()->functions->updateMerchantExpired();
	}
	
	public function ProcessSMS()
	{
		$DbExt=new DbExt;
		$stmt="
		SELECT * FROM
		{{sms_broadcast_details}}
		WHERE
		status IN ('pending')
		ORDER BY id ASC
		LIMIT 0,10
		";		
		if ( $res=$DbExt->rst($stmt)){
			foreach ($res as $val) {
				if(isset($_GET['debug'])){
					dump($val);
				}
				Yii::app()->functions->sendSMS(trim($val['contact_phone']),$val['sms_message'],$val['id']);
			}
		} else {
			if(isset($_GET['debug'])){
				echo "no records";
			}
		}
		
	}
	
	public function actionProcessEmail()
	{
		$DbExt=new DbExt;
		$stmt="
		SELECT * FROM
		{{email_logs}}
		WHERE
		status IN ('pending')
		ORDER BY id ASC
		LIMIT 0,10
		";		
		if ( $res=$DbExt->rst($stmt)){
			foreach ($res as $val) {
				if(isset($_GET['debug'])){
					dump($val);					
				}
				
				$DbExt->updateData("{{email_logs}}",array(
				 'status'=>"processing"
				),'id',$val['id']);				
				
				sendEmail(
				  $val['email_address'],
				  $val['sender'],
				  $val['subject'],
				  $val['content'],
				  $val['id']
				);
			}
		} else {
			if(isset($_GET['debug'])){
				echo "no records";
			}
		}
	}
	
	public function actionMerchantExpired()
	{
		
		Yii::app()->functions->updateMerchantSponsored();
		Yii::app()->functions->updateMerchantExpired();
	}
	
	public function actionMerchantNearExpiration()
	{
		$lang=Yii::app()->language;
		$email_enabled=getOptionA("merchant_near_expiration_email");
		$sms_enabled=getOptionA("merchant_near_expiration_sms");
		$sender=getOptionA("global_admin_sender_email");
		
		if($email_enabled!=1 && $sms_enabled!=1){
			if(isset($_GET['debug'])){ echo "disabled"; }
			return ;
		}
		
		$days=getOptionA('merchant_near_expiration_day');
		if(empty($days)){
			$days=5;
		}
		$date=date("Y-m-d", strtotime("+$days day"));		
		$DbExt=new DbExt;
		$stmt="
		SELECT * FROM
		{{merchant}}
		WHERE
		membership_expired<".FunctionsV3::q($date)."
		AND status in ('active')
		AND is_commission ='1'
		";		
		if(isset($_GET['debug'])){
		   dump($stmt);
		}
		if ($res=$DbExt->rst($stmt)){
			if(isset($_GET['debug'])){
			  dump($res);
			}
			
			$tpl_orig=getOptionA("merchant_near_expiration_tpl_content_$lang");
			$subject_orig=getOptionA("merchant_near_expiration_tpl_subject_$lang");
		    $tpl_sms_orig=getOptionA("merchant_near_expiration_sms_content_$lang");
		    		    
			foreach ($res as $val) {
				
				$merchant_email=$val['contact_email'];
				
				$pattern=array(		    	   
		    	   'restaurant_name'=>'restaurant_name',	
		    	   'expiration_date'=>'membership_expired',
		    	   'sitename'=>getOptionA('website_title'),
		    	   'siteurl'=>websiteUrl(),	 		    	   
		    	);
		    	$tpl=FunctionsV3::replaceTemplateTags($tpl_orig,$pattern,$val); 
		    	$subject=FunctionsV3::replaceTemplateTags($subject_orig,$pattern,$val); 
		    	$tpl_sms=FunctionsV3::replaceTemplateTags($tpl_sms_orig,$pattern,$val); 		    	
				
		    	/*dump($subject);
		    	dump($tpl);*/
		    	//dump($tpl_sms);
		    	
				$params=array(
	    		  'email_address'=>$merchant_email,
	    		  'sender'=>$sender,
	    		  'subject'=>$subject,
	    		  'content'=>$tpl,
	    		  'date_created'=>FunctionsV3::dateNow(),
	    		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
	    		  'module_type'=>'core'
	    		);	    		
	    		$DbExt->insertData("{{email_logs}}",$params); 
	    		
	    		$params=array(
	    		  'contact_phone'=>$val['contact_phone'],
	    		  'sms_message'=>$tpl_sms,
	    		  'date_created'=>FunctionsV3::dateNow(),
	    		  'ip_address'=>$_SERVER['REMOTE_ADDR']    		 
	    		);	    		
	    		$DbExt->insertData("{{sms_broadcast_details}}",$params); 				
			}
		} else {
			if(isset($_GET['debug'])){ echo "no records"; }
		}
		unset($DbExt);
		FunctionsV3::runCronEmail();
		FunctionsV3::runCronSMS();
	}
	
	public function actionIdleOrder()
	{
		$DbExt=new DbExt;
		$datenow=date("Y-m-d");
		$stmt="
		SELECT 
		a.order_id,
		a.merchant_id,
		a.date_created,
		b.restaurant_name
		
		FROM {{order}} a
		LEFT JOIN {{merchant}} b
		ON 
		a.merchant_id = b.merchant_id
		
		WHERE
		a.date_created LIKE '$datenow%'
		AND
		a.status = 'pending'
		AND
		a.critical = '1'
		ORDER BY a.order_id ASC
		limit 0,10
		";
		if(isset($_GET['debug'])){
		   dump($stmt);
		}		
		$idle_minutes=getOptionA('order_idle_admin_minutes');
		if(!is_numeric($idle_minutes)){			
			$idle_minutes=5;
		}
		
		if ($res=$DbExt->rst($stmt)){
			foreach ($res as $val) {	
			   if(isset($_GET['debug'])){
			  	  dump($val);
			   }
			   $critical=false;	
			   $time_1=date('Y-m-d g:i:s a');			
		       $time_2=date("Y-m-d g:i:s a",strtotime($val['date_created']));						
			   $time_diff=FunctionsV3::dateDifference($time_2,$time_1);	
			   		   
			   if (is_array($time_diff) && count($time_diff)>=1){			   	   
			   	   if ($time_diff['minutes']>$idle_minutes){
			   	   	  $critical=true;
			   	   }
			   	   if ($time_diff['hours']>=1){
			   	   	  $critical=true;
			   	   }
			   	   if ($time_diff['days']>=1){
			   	   	  $critical=true;
			   	   }
			   }
			   
			   if($critical){
			   	  $val['time_diff']=$time_diff;			   	  
			   	  $DbExt->updateData("{{order}}",array('critical'=>2),'order_id',$val['order_id']);
			   	  self::notiIdleAdmin($val);			   	  
			   }
			   
			}			
		} 
	}
	
	public static function notiIdleAdmin($data='')
	{
		$lang=Yii::app()->language; 
		$sender=getOptionA('global_admin_sender_email');
		
		$enabled=getOptionA('order_idle_to_admin_email');
		$email=getOptionA('order_idle_admin_email');
		if($enabled==true && !empty($email)){
			$tpl=getOptionA("order_idle_to_admin_tpl_content_$lang");
			$subject = getOptionA("order_idle_to_admin_tpl_subject_$lang");
									
			$data['idle_time']=$data['time_diff']['hours'].":".$data['time_diff']['minutes'].":".$data['time_diff']['seconds'];
			
			$pattern=array(		    	   	   	   
			   'order_id'=>'order_id',
	    	   'restaurant_name'=>'restaurant_name',	    	   
	    	   'sitename'=>getOptionA('website_title'),
	    	   'siteurl'=>websiteUrl(),	 		    	   
	    	   'idle_time'=>'idle_time'
	    	  );
	    	$tpl=FunctionsV3::replaceTemplateTags($tpl,$pattern,$data);
	    	$subject=FunctionsV3::replaceTemplateTags($subject,$pattern,$data);
									
			$DbExt=new DbExt();
			  $params=array(
			   'email_address'=>$email,
			   'sender'=>$sender,
			   'subject'=>$subject,
			   'content'=>$tpl,
			   'date_created'=>FunctionsV3::dateNow(),
			   'ip_address'=>$_SERVER['REMOTE_ADDR'],
			   'module_type'=>'core'
			  );	    			  
			  $DbExt->insertData("{{email_logs}}",$params);    	  
			  FunctionsV3::runCronEmail();
			
		}
	}
	
}/* END CLASS*/