<?php
class Ajax extends AjaxAdmin 
{
	
	public function commissionSettings()
	{		
				
		Yii::app()->functions->updateOptionAdmin("admin_commission_enabled",
    	isset($this->data['admin_commission_enabled'])?$this->data['admin_commission_enabled']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_disabled_membership",
    	isset($this->data['admin_disabled_membership'])?$this->data['admin_disabled_membership']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_commision_percent",
    	isset($this->data['admin_commision_percent'])?$this->data['admin_commision_percent']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_vat_no",
    	isset($this->data['admin_vat_no'])?$this->data['admin_vat_no']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_vat_percent",
    	isset($this->data['admin_vat_percent'])?$this->data['admin_vat_percent']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("total_commission_status",
    	isset($this->data['total_commission_status'])?json_encode($this->data['total_commission_status']):'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_commision_ontop",
    	isset($this->data['admin_commision_ontop'])?$this->data['admin_commision_ontop']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_commision_type",
    	isset($this->data['admin_commision_type'])?$this->data['admin_commision_type']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_include_merchant_cod",
    	isset($this->data['admin_include_merchant_cod'])?$this->data['admin_include_merchant_cod']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_exclude_cod_balance",
    	isset($this->data['admin_exclude_cod_balance'])?$this->data['admin_exclude_cod_balance']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_disabled_membership_signup",
    	isset($this->data['admin_disabled_membership_signup'])?$this->data['admin_disabled_membership_signup']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_bank_account_name",
    	isset($this->data['admin_bank_account_name'])?$this->data['admin_bank_account_name']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_bank_account_number",
    	isset($this->data['admin_bank_account_number'])?$this->data['admin_bank_account_number']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_bank_custom_tpl",
    	isset($this->data['admin_bank_custom_tpl'])?$this->data['admin_bank_custom_tpl']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_bank_deposited_timeframe",
    	isset($this->data['admin_bank_deposited_timeframe'])?$this->data['admin_bank_deposited_timeframe']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_include_all_offline_payment",
    	isset($this->data['admin_include_all_offline_payment'])?$this->data['admin_include_all_offline_payment']:'');
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
	
	public function merchantCommission()
	{		 		    
		    $where_params='';
		    $and_params='';
		    
	    	$and='';  
	    	$and_date='';	    	
	    	if (isset($this->data['start_date']) && isset($this->data['end_date']))	{
	    		if (!empty($this->data['start_date']) && !empty($this->data['end_date'])){
		    		$and=" AND a.date_created BETWEEN  ".FunctionsV3::q($this->data['start_date']." 00:00:00")." AND 
		    		        ".FunctionsV3::q($this->data['end_date']." 23:59:00")."
		    		 ";	    		    		
		    		$and_date=" AND date_created BETWEEN  ".FunctionsV3::q($this->data['start_date']." 00:00:00")." AND 
		    		        ".FunctionsV3::q($this->data['end_date']." 23:59:00")."
		    		 ";
		    		
		    		$and_params.="a.date_created|".FunctionsV3::q($this->data['start_date']." 00:00:00");
		    		$and_params.=",".FunctionsV3::q($this->data['end_date']." 23:59:00");
	    		}
	    	}
	    	
	    	if ( $this->data['query']=="last15"){
		    	$start_date=date("Y-m-d", strtotime ('-15 days'));
				$end_date=date("Y-m-d");
				
				$and =" AND a.date_created BETWEEN  ".FunctionsV3::q($start_date." 00:00:00")." AND 
		    		        ".FunctionsV3::q($end_date." 23:59:00")."
		    		 ";	    		
				$and_date =" AND date_created BETWEEN  '".$start_date." 00:00:00' AND 
		    		        '".$end_date." 23:59:00'
		    		 ";	    		
				
				$and_params.="a.date_created|".$start_date." 00:00:00";
				$and_params.=",".$end_date." 23:59:00";
				
	    	} elseif ( $this->data['query']=="last30"){
	    		
	    		$start_date=date("Y-m-d", strtotime ('-30 days'));
				$end_date=date("Y-m-d");
				
				$and =" AND a.date_created BETWEEN  '".$start_date." 00:00:00' AND 
		    		        '".$end_date." 23:59:00'
		    		 ";	    		
				$and_date =" AND date_created BETWEEN  '".$start_date." 00:00:00' AND 
		    		        '".$end_date." 23:59:00'
		    		 ";	    		
				
				$and_params.="a.date_created|".$start_date." 00:00:00";
				$and_params.=",".$end_date." 23:59:00";
				
	    	} elseif ( $this->data['query']=="month"){
	    		
	    		$query_date = $this->data['query_date'];			
				$start_date=date('Y-m-01', strtotime($query_date));
				$end_date=date('Y-m-t', strtotime($query_date));
				
				$and =" AND a.date_created BETWEEN  '".$start_date." 00:00:00' AND 
		    		        '".$end_date." 23:59:00'
		    		 ";	    		
				$and_date =" AND date_created BETWEEN  '".$start_date." 00:00:00' AND 
		    		        '".$end_date." 23:59:00'
		    		 ";	    		
				
				$and_params.="a.date_created|".$start_date." 00:00:00";
				$and_params.=",".$end_date." 23:59:00";
	    	}
	    	
	    	$order_status_id='';
	    	$or='';
	    	$order_status_raw='';
	    	if (isset($this->data['stats_id'])){
		    	if (is_array($this->data['stats_id']) && count($this->data['stats_id'])>=1){
		    		foreach ($this->data['stats_id'] as $stats_id) {		    			
		    			$order_status_id.= FunctionsV3::q($stats_id)."," ;
		    			$order_status_raw.="$stats_id,";
		    		}
		    		if ( !empty($order_status_id)){
		    			$order_status_id=substr($order_status_id,0,-1);
		    		}		    	
		    	}	    
	    	}
	    	
	    	if ( !empty($order_status_id)){	    		
	    		$where= " WHERE a.status IN ($order_status_id)";	    		
	    		$and_date.=" AND status IN ($order_status_id)";
	    		
	    		$where_params.="a.status_in|$order_status_raw";
	    	} else {
	    		$where= " WHERE a.status NOT IN ('".initialStatus()."')";
	    		$and_date.="AND status NOT IN ('".initialStatus()."')";
	    		
	    		$where_params.="a.status_not_in|$order_status_raw";
	    	}
	    		    	
	    	 	    	
	    	
	    	if ( $this->data['merchant_id']>=1){
	    		$and.=" AND a.merchant_id=".FunctionsV3::q($this->data['merchant_id'])." ";
	    	}
	    	
	    	if (isset($this->data['payment_type'])){
	    		if ( $this->data['payment_type']==2){ // cash
	    			$and_date.=" AND payment_type IN ('cod','pyr','ccr','ocr') ";
	    			$and.=" AND payment_type IN ('cod','pyr','ccr','ocr') ";
	    			
	    			
	    			$and_params.="&payment_type_in=payment_type|cod,pyr,ccr,ocr";
	    			
	    		} else if ($this->data['payment_type']==3) { // card
	    			$and_date.=" AND payment_type NOT IN ('cod','pyr','ccr','ocr') ";
	    			$and.=" AND payment_type NOT IN ('cod','pyr','ccr','ocr') ";
	    			
	    			$and_params.="&payment_type_not_in=cod,pyr,ccr,ocr";
	    		}	    	
	    	}	
	    	
	    	$DbExt=new DbExt;    	
	    	
	    	$stmt="SELECT a.*,b.is_commission,
	    	(
	    	select restaurant_name 
	    	from
	    	{{merchant}}
	    	where merchant_id = a.merchant_id 
	    	) as merchant_name,
	    	
	    	(
	    	select sum(total_w_tax) 
	    	from
	    	{{order}}
	    	where merchant_id = a.merchant_id 	  
	    	$and_date  		 
	    	) as total_order,
	    	
	    	(
	    	select sum(total_commission)
	    	from
	    	{{order}}
	    	where merchant_id = a.merchant_id 	 
	    	$and_date   		 
	    	) as total_commission
	    	
	    	FROM
	    	{{order}} a	  	    
	    	left join {{merchant}} b
			On
			a.merchant_id=b.merchant_id
	    	  	  
	    	$where
	    	$and	    	
	    	AND b.is_commission='2'
	    	
	    	GROUP BY merchant_id
	    	ORDER BY order_id DESC
	    	LIMIT 0,2000
	    	";
	    	
	    	//dump($stmt);
	    	
	    	$_SESSION['kr_export_stmt']=$stmt;	    	
	    			    
	    	if ( $res=$DbExt->rst($stmt)){	 
	    		
	    		$total_commission=0;
	    		foreach ($res as $val) {	    		
	    			$link=websiteUrl()."/admin/merchantcommissiondetails";	    			
	    			
	    			/*$link.="?mtid=".$val['merchant_id'];	 
	    			$link.="&where=".$where;	 
	    			$link.="&and=".$and;	    			
	    			dump($link);*/
	    			
	    			$link.="?mtid=".$val['merchant_id'];
	    			$link.="&where=$where_params";
	    			$link.="&and=$and_params";
	    				    			
                    $total_commission+=$val['total_commission'];
$action="<a href=\"$link\" >".Yii::t("default","Details")."</a>";
	    			$date=prettyDate($val['date_created'],true);
	    			$date=Yii::app()->functions->translateDate($date);
	    				    				    				    			
	    			$feed_data['aaData'][]=array(
	    			  $val['merchant_id'],
	    			  stripslashes($val['merchant_name']),
	    			  displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['total_order'])),
	    			  displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['total_commission'])),
	    			  $action	    			  
	    		    );
	    		}	    		
	    		
	    		$feed_data['total_commission']=displayPrice(adminCurrencySymbol(),normalPrettyPrice($total_commission));
	    		$this->otableOutput($feed_data);
	    	}	   
	    	$this->otableNodata();					
	}
	
	public function merchantCommissionDetails()
	{
		$DbExt=new DbExt;		
		$where='';		
		$and=" AND merchant_id=".FunctionsV3::q($this->data['mtid'])." ";

		if(isset($this->data['where'])){
		   if(!empty($this->data['where'])){
		   	   $data_where = explode("|",$this->data['where']);		   	   
		   	   if(is_array($data_where) && count($data_where)>=1){
		   	   	
		   	   	  switch ($data_where[0]) {
		   	   	  	case "a.status_in":
		   	   	  		$data_where_query = explode(",",$data_where[1]);
		   	   	  		if(is_array($data_where_query) && count($data_where_query)>=1){
		   	   	  		   $status_in='';
		   	   	  		   foreach ($data_where_query as $val) {
		   	   	  		   		if(!empty($val)){
		   	   	  		   			$status_in.= FunctionsV3::q($val).",";
		   	   	  		   		}		   	   	  		   
		   	   	  		   	}	
		   	   	  		   	$status_in = substr($status_in,0,-1);
		   	   	  		   	$and.=" AND a.status IN ($status_in)";
		   	   	  		}		   	   	  
		   	   	  		break;
		   	   	  
		   	   	  	default:
		   	   	  		break;
		   	   	  }
		   	   	  
		   	   }		   
		   }		
		}
		
		if(isset($this->data['and'])){
			$data_and = explode("|",$this->data['and']);
			if(is_array($data_and) && count($data_and)>=1){				
				$data_and_date = explode(",",$data_and[1]);
				if(is_array($data_and_date) && count($data_and_date)>=1){					
					$data_and_between = FunctionsV3::q($data_and_date[0])." AND ".FunctionsV3::q($data_and_date[1]);
				}
				switch ($data_and[0]) {
					case "a.date_created":
						$and.=" AND a.date_created BETWEEN $data_and_between";
						break;
				
					default:
						break;
				}
			}		
		}
		
		if (isset($this->data['payment_type_in'])){
			if(!empty($this->data['payment_type_in'])){
			    $payment_type_in = explode("|",$this->data['payment_type_in']);
			    if(is_array($payment_type_in) && count($payment_type_in)>=1){
			    	$payment_type_list='';		    	
			    	$payment_type_in_list = explode(",", isset($payment_type_in[1])?$payment_type_in[1]:'' );
			    	if(is_array($payment_type_in_list) && count($payment_type_in_list)>=1){
			    		foreach ($payment_type_in_list as $payment_type_in_list_val) {		    			
			    			$payment_type_list.=FunctionsV3::q($payment_type_in_list_val).",";
			    		}		    		
			    		$payment_type_list = substr($payment_type_list,0,-1);		    		
			    		$and.=" AND payment_type IN ($payment_type_list)";
			    	}		    
			    }
			}
		}	
		
		if (isset($this->data['payment_type_not_in'])){
			if(!empty($this->data['payment_type_not_in'])){
				$payment_type_list='';
				$payment_type_not_in = explode(",",$this->data['payment_type_not_in']);
				if(is_array($payment_type_not_in) && count($payment_type_not_in)>=1){
					foreach ($payment_type_not_in as $val_in) {
						$payment_type_list.=FunctionsV3::q($val_in).",";					
					}
					
					$payment_type_list = substr($payment_type_list,0,-1);
					$and.=" AND payment_type NOT IN ($payment_type_list)";
				}		
			}
		}
		
				
    	if (isset($this->data['start_date']) && isset($this->data['end_date']))	{
    		if (!empty($this->data['start_date']) && !empty($this->data['end_date'])){
    		$and.=" AND date_created BETWEEN  ".FunctionsV3::q($this->data['start_date']." 00:00:00")." AND 
    		        ".FunctionsV3::q($this->data['end_date']." 23:59:00")."
    		 ";
    		}
    	}
		
		$stmt="
		SELECT a.*,		
		(
    	select restaurant_name 
    	from
    	{{merchant}}
    	where merchant_id = a.merchant_id 
    	) as merchant_name    	
		FROM		
		{{order}} a
		WHERE 1
		$where
		$and
		ORDER BY order_id DESC
		";
		
		if (isset($_GET['debug'])){
			dump($this->data);	    	
            dump($stmt);
	    }	    	
		$total_order=0;	
	    $total_commission=0;    	
	    
	    $_SESSION['kr_export_stmt']=$stmt;	    
	    	
		if ($res=$DbExt->rst($stmt)){
			foreach ($res as $val) {
				
				if(!isset($val['total_order'])){
					$val['total_order']=0;
				}		
				$total_order=$total_order+$val['total_order'];
	    		$total_commission=$total_commission+$val['total_commission'];
	    			
				/*$date=prettyDate($val['date_created'],true);
	    	    $date=Yii::app()->functions->translateDate($date);	    	    */
				$date=FormatDateTime($val['date_created']);
	    	    
	    	    $feed_data['total_commission']=displayPrice(adminCurrencySymbol(),
	    	    normalPrettyPrice($total_commission));
	    	    $feed_data['merchant_name']=ucwords($val['merchant_name']);
	    	    
	    	    if ( $val['commision_ontop']==1){
	    	    	$total_w_tax="<a  class=\"view-receipt\" data-id=\"$val[order_id]\" href=\"javascript:;\">".displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['sub_total']))."</a>";
	    	    } else {
	    	    	$total_w_tax="<a  class=\"view-receipt\" data-id=\"$val[order_id]\" href=\"javascript:;\">".displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['total_w_tax']))."</a>";
	    	    }	    	    
	    	    
	    	    $feed_data['aaData'][]=array(
					$val['order_id'],
					t($val['payment_type']),
					$total_w_tax,
					normalPrettyPrice($val['percent_commision']),
					displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['total_commission'])),
					$date
				);
			}	
			$this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}
	
	public function getPackageInformation()
	{				
		if (isset($this->data['package_id'])){
			if ( $this->data['package_id']==0 ){
				$this->code=3;
				return false;
			}
			if ( $res=Yii::app()->functions->getSMSPackagesById($this->data['package_id'])){
				$this->code=1;
				$this->msg=t("Successful");
				$this->details=$res['sms_limit'];
			} else $this->msg=t("Cannot find information");
		} else $this->msg=t("Missing parameters");
	}
	
	public function getCommissionTotal()
	{						
		$resp = Yii::app()->functions->getTotalCommission();		
		
		$commission=array(
		  'total_com'=>FunctionsV3::prettyPrice($resp['total_commission']),
		  'total_today'=>FunctionsV3::prettyPrice($resp['total_today']),
		  'total_last'=>FunctionsV3::prettyPrice($resp['total_last'])
		);
		$this->code=1;
		$this->msg="Ok";
		$this->details=$commission;
	}
	
	public function merchantSignUp2()
	{		
									
        /** check if admin has enabled the google captcha*/    	    	
    	if ( getOptionA('captcha_merchant_signup')==2){
    		try {	    			
				$recaptcha_token = isset($this->data['recaptcha_v3'])?$this->data['recaptcha_v3']:'';	    			
				GoogleCaptchaV3::validateToken($recaptcha_token);
			} catch (Exception $e) {
				 $this->msg = $e->getMessage();
				 return ;
			}	
    	} 
	    	
		$status=Yii::app()->functions->getOptionAdmin('merchant_sigup_status');
		if(empty($status)){
			$status='pending';
		}	
		$token=md5($this->data['restaurant_name'].date('c'));
		
		$percent=Yii::app()->functions->getOptionAdmin('admin_commision_percent');
		
		$p = new CHtmlPurifier();
				
	    $params=array(
	      'restaurant_name'=>$p->purify($this->data['restaurant_name']),
	      'restaurant_phone'=>$p->purify($this->data['restaurant_phone']),
	      'contact_name'=>$p->purify($this->data['contact_name']),
	      'contact_phone'=>$p->purify($this->data['contact_phone']),
	      'contact_email'=>$p->purify($this->data['contact_email']),
	      'street'=>$p->purify($this->data['street']),
	      'city'=>$p->purify($this->data['city']),
	      'post_code'=>$this->data['post_code'],
	      'cuisine'=>json_encode($this->data['cuisine']),
	      'username'=>$this->data['username'],
	      'password'=>md5($this->data['password']),
	      'status'=>$status,
	      'date_created'=>FunctionsV3::dateNow(),
	      'ip_address'=>$_SERVER['REMOTE_ADDR'],
	      'activation_token'=>$token,
	      'activation_key'=>Yii::app()->functions->generateRandomKey(5),
	      'restaurant_slug'=>Yii::app()->functions->createSlug($this->data['restaurant_name']),	      
	      'payment_steps'=>3,	      
	      'country_code'=>$this->data['country_code'],
	      'state'=>$this->data['state'],
	      'is_commission'=>2,
	      'percent_commision'=>is_numeric($percent)?$percent:0,	      
	      'abn'=>isset($this->data['abn'])?$this->data['abn']:'',
	      'merchant_type'=>isset($this->data['merchant_type'])?$this->data['merchant_type']:'',
	      'service'=>isset($this->data['service'])?$this->data['service']:1,
	      'delivery_distance_covered'=>isset($this->data['delivery_distance_covered'])?(float)$this->data['delivery_distance_covered']:0,
		  'distance_unit'=>isset($this->data['distance_unit'])?$p->purify($this->data['distance_unit']):'mi',
	    );			
	    
	    if (isset($this->data['invoice_terms'])){
	    	if (is_numeric($this->data['invoice_terms'])){
	    		$params['invoice_terms']=$this->data['invoice_terms'];
	    	}	    
	    }
	    	    	    
	    $commision_type=getOptionA('admin_commision_type');
		if(!empty($commision_type)){
			$params['commision_type']=$commision_type;
		}		
	    
	    if ( !Yii::app()->functions->validateUsername($this->data['username']) ){
	    	
	    	if ($respck=Yii::app()->functions->validateMerchantUserFromMerchantUser($params['username'],
	    	    $params['contact_email'])){
	    		$this->msg=$respck;
	    		return ;		    		
	    	}		    
	    		    		    		    	
		    if ($this->insertData("{{merchant}}",$params)){
		    	$mtid=Yii::app()->db->getLastInsertID();
		    			    	
                Yii::app()->functions->updateOption("merchant_delivery_miles",
		        $params['delivery_distance_covered']
		        ,$mtid);
		        
		        Yii::app()->functions->updateOption("merchant_distance_type",
		        $params['distance_unit']
		        ,$mtid);			
			        
		    	//AUTO ADD SIZE
			    FunctionsV3::autoAddSize($mtid);
			    
			    /*ADD CUISINE*/
                try {
                	$cuisine = isset($this->data['cuisine'])?$this->data['cuisine']:array();
		    		FunctionsV3::insertCuisine($mtid,(array)$cuisine);
		    	} catch (Exception $e) {
		    		//$e->getMessage()
		    	}   
			    	
		    	$this->code=1;
		    	$this->msg=Yii::t("default","Successful");
		    	$this->details=$token;
		    	
		    	/*SEND WELCOME EMAIL*/
		    	FunctionsV3::sendWelcomeEmailMerchant($params,true);
		    	
		    	/*SEND NOTIFICATION TO ADMIN*/
		    	FunctionsV3::NotiNewMerchantSignup($params,'commission');
	            			    				    				    	
		    } else $this->msg=Yii::t("default","Sorry but we cannot add your information. Please try again later");
	    } else $this->msg=Yii::t("default","Sorry but your username is alread been taken.");
	}
	
	public function getMerchantBalance()
	{
		$balance = 0;
		$merchant_id = Yii::app()->functions->getMerchantID();
		$balance = Yii::app()->functions->getMerchantBalance($merchant_id);
		
		$this->details=FunctionsV3::prettyPrice($balance);
		$this->code=1;
		$this->msg="ok";
	}
	
	public function merchantStatement()
	{
		if (isset($_GET['debug'])){
		   dump($this->data);
		}
		$mtid=Yii::app()->functions->getMerchantID();
		
		$orderstats=Yii::app()->functions->getCommissionOrderStats();		
		
		if (isset($_GET['debug'])){
		   dump($orderstats);
		   dump($admin_commision_ontop);
		}
		
		$and=''; $trans_type='';
		if ( $this->data['query']=="month"){
			$query_date = $this->data['query_date'];			
			$start_date=date('Y-m-01', strtotime($query_date));
			$end_date=date('Y-m-t', strtotime($query_date));
			$and =" AND date_created BETWEEN  ".FunctionsV3::q($start_date." 00:00:00")." AND 
	    		        ".FunctionsV3::q($end_date." 23:59:00")."
	    		 ";	    		
		} elseif ( $this->data['query']=="period"){
			
			$start_date=$this->data['start_date'];
			$end_date=$this->data['end_date'];
			
			$and =" AND date_created BETWEEN  ".FunctionsV3::q($start_date." 00:00:00")." AND 
	    		        ".FunctionsV3::q($end_date." 23:59:00")."
	    		 ";	    		
		} elseif ( $this->data['query']=="last15"){
			
			$start_date=date("Y-m-d", strtotime ('-15 days'));
			$end_date=date("Y-m-d");
			
			$and =" AND date_created BETWEEN  ".FunctionsV3::q($start_date." 00:00:00")." AND 
	    		        ".FunctionsV3::q($end_date." 23:59:00")."
	    		 ";	    		
		} elseif ( $this->data['query']=="last30"){
			
			$start_date=date("Y-m-d", strtotime ('-30 days'));
			$end_date=date("Y-m-d");
			
			$and =" AND date_created BETWEEN  ".FunctionsV3::q($start_date." 00:00:00")." AND 
	    		        ".FunctionsV3::q($end_date." 23:59:00")."
	    		 ";	    		
		}	
		
		if (isset($this->data['payment_type'])){
			if ( $this->data['payment_type']==2){ //cash
				$trans_type="AND payment_type IN ('cod','pyr','ccr','ocr')";				
			} elseif ( $this->data['payment_type']==3){ //card			
				$trans_type="AND payment_type NOT IN ('cod','pyr','ccr','ocr')";	
			}		
		}		
		
	    $stmt="SELECT * FROM
	    {{order}}
	    WHERE
	    merchant_id=".Yii::app()->functions->q($mtid)."
	    AND status in ($orderstats)
	    $and	    
	    $trans_type
	    ORDER BY order_id DESC
	    ";
	    if (isset($_GET['debug'])){
	        dump($stmt);
	    }
	    
	    $_SESSION['kr_export_stmt']=$stmt;
	    
	    $total_amount=0;
	    $total_payable=0;
	    if ( $res=$this->rst($stmt)){
	    	foreach ($res as $val) {	    			    		
	    		//$date=prettyDate($val['date_created']);
	    		/*$date=date('M d,Y G:i:s',strtotime($val['date_created']));
			    $date=Yii::app()->functions->translateDate($date);*/					
	    		$date=FormatDateTime($val['date_created']);
			    
			    $total=$val['total_w_tax'];
			    if ( $val['commision_ontop']==1){
			    	$total=$val['sub_total'];
			    }
			    
			    $total_commission=$val['total_commission'];
			    $amount=$total-$total_commission;
			    
			    $amount=$val['merchant_earnings'];
			    
			    $link="<a href=\"javascript:;\" class=\"view-receipt\"  data-id=\"$val[order_id]\"  >".
			    displayPrice(adminCurrencySymbol(),normalPrettyPrice($total))."</a>";
			    			    
	    		$feed_data['aaData'][]=array(
	    		    $val['order_id'],
	    		    strtoupper($val['payment_type']),
	    		    $link,	    		    
	    		    normalPrettyPrice($val['percent_commision']),
	    		    displayPrice(adminCurrencySymbol(),normalPrettyPrice($total_commission)),
	    		    displayPrice(adminCurrencySymbol(),normalPrettyPrice($amount)),
	    		    $date
	    		);	    		
	    			    		
	    		$total_amount+=$amount;
	    		$total_payable+=$total_commission;
	    	}
	    	
	    	$feed_data['total_amount']=displayPrice(adminCurrencySymbol(),normalPrettyPrice($total_amount));
	    	$feed_data['total_payable']=displayPrice(adminCurrencySymbol(),normalPrettyPrice($total_payable));
	    	$this->otableOutput($feed_data);
	    } 
	    $this->otableNodata();
	}
	
	public function removeNotice()
	{
		$mtid=Yii::app()->functions->getMerchantID();		
		Yii::app()->functions->updateOption("merchant_read_notice","1",$mtid);
		$this->code=1;
	}
	
	public function ingredientsList()
	{		
		$aColumns = array('ingredients_id','ingredients_name','status');
		$sWhere=''; $sOrder=''; $sLimit='';
		$functionk=new FunctionsK();
		$t=$functionk->ajaxDataTables($aColumns);
		if (is_array($t) && count($t)>=1){
			$sWhere=$t['sWhere'];
			$sOrder=$t['sOrder'];
			$sLimit=$t['sLimit'];
			$sWhere = str_replace("WHERE","AND",$sWhere);
		}			

	    $slug=$this->data['slug'];
        $stmt="
		SELECT SQL_CALC_FOUND_ROWS * FROM
		{{ingredients}}
		WHERE			
		merchant_id=". FunctionsV3::q(Yii::app()->functions->getMerchantID()) ."
		$sWhere
        $sOrder
        $sLimit
		";        
		$connection=Yii::app()->db;
	    $rows=$connection->createCommand($stmt)->queryAll();     	    
	    if (is_array($rows) && count($rows)>=1){
	    	
	    	$iTotalRecords=0;
			$stmt2="SELECT FOUND_ROWS()";
			if ( $res2=$this->rst($stmt2)){
				$iTotalRecords=$res2[0]['FOUND_ROWS()'];
			}			
			$feed_data['sEcho']=intval($_GET['sEcho']);
			$feed_data['iTotalRecords']=$iTotalRecords;
			$feed_data['iTotalDisplayRecords']=$iTotalRecords;

	    	foreach ($rows as $val) {    	     	    		
	    		$chk="<input type=\"checkbox\" name=\"row[]\" value=\"$val[ingredients_id]\" class=\"chk_child\" >";   		
	    		$option="<div class=\"options\">
	    		<a href=\"$slug/id/$val[ingredients_id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[ingredients_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";	    		
	    		$date=FormatDateTime($val['date_created']);
	    		
	    		$feed_data['aaData'][]=array(
	    		  $chk,$val['ingredients_name'].$option,	    		  
	    		  "$date<br/><span class=\"tag ".$val['status']."\">".t($val['status'])."</span>"
	    		);
	    	}
	    	$this->otableOutput($feed_data);
	    }     	    
	    $this->otableNodata();	
	}
	
	public function AddIngredients()
	{
	  $params=array(
		  'ingredients_name'=>$this->data['ingredients_name'],
		  'status'=>addslashes($this->data['status']),
		  'date_created'=>FunctionsV3::dateNow(),
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'merchant_id'=>Yii::app()->functions->getMerchantID()
		);								

		if (isset($this->data['ingredients_name_trans'])){
			if (okToDecode()){
				$params['ingredients_name_trans']=json_encode($this->data['ingredients_name_trans'],
				JSON_UNESCAPED_UNICODE);
			} else $params['ingredients_name_trans']=json_encode($this->data['ingredients_name_trans']);
		}
		
		$params = FunctionsV3::purifyData($params);
			
		$command = Yii::app()->db->createCommand();
		if (isset($this->data['id']) && is_numeric($this->data['id'])){				
			unset($params['date_created']);
			$params['date_modified']=FunctionsV3::dateNow();				
			$res = $command->update('{{ingredients}}' , $params , 
			'ingredients_id=:ingredients_id' , array(':ingredients_id'=>addslashes($this->data['id'])));
			if ($res){
				$this->code=1;
                $this->msg=Yii::t("default",'ingredients updated');  
			} else $this->msg=Yii::t("default","ERROR: cannot update");
		} else {				
			if ($res=$command->insert('{{ingredients}}',$params)){
				$this->details=Yii::app()->db->getLastInsertID();	
                $this->code=1;
                $this->msg=Yii::t("default",'ingredients added'); 
            } else $this->msg=Yii::t("default",'ERROR. cannot insert data.');
		}	    		  
	}
	
	public function withdrawalSettings()
	{
		/*Yii::app()->functions->updateOptionAdmin("wd_minimum_amount",
	    isset($this->data['wd_minimum_amount'])?$this->data['wd_minimum_amount']:'');*/
		
		Yii::app()->functions->updateOptionAdmin("wd_paypal_minimum",
	    isset($this->data['wd_paypal_minimum'])?$this->data['wd_paypal_minimum']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_bank_minimum",
	    isset($this->data['wd_bank_minimum'])?$this->data['wd_bank_minimum']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_days_process",
	    isset($this->data['wd_days_process'])?$this->data['wd_days_process']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_paypal",
	    isset($this->data['wd_paypal'])?$this->data['wd_paypal']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_paypal_mode",
	    isset($this->data['wd_paypal_mode'])?$this->data['wd_paypal_mode']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_paypal_mode_user",
	    isset($this->data['wd_paypal_mode_user'])?$this->data['wd_paypal_mode_user']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_paypal_mode_pass",
	    isset($this->data['wd_paypal_mode_pass'])?$this->data['wd_paypal_mode_pass']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_paypal_mode_signature",
	    isset($this->data['wd_paypal_mode_signature'])?$this->data['wd_paypal_mode_signature']:'');
	    
	    /*Yii::app()->functions->updateOptionAdmin("wd_paypal_client_id",
	    isset($this->data['wd_paypal_client_id'])?$this->data['wd_paypal_client_id']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_paypal_client_secret",
	    isset($this->data['wd_paypal_client_secret'])?$this->data['wd_paypal_client_secret']:'');*/
	    
	    Yii::app()->functions->updateOptionAdmin("wd_bank_deposit",
	    isset($this->data['wd_bank_deposit'])?$this->data['wd_bank_deposit']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_template_payout",
	    isset($this->data['wd_template_payout'])?$this->data['wd_template_payout']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_template_process",
	    isset($this->data['wd_template_process'])?$this->data['wd_template_process']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_enabled_paypal",
	    isset($this->data['wd_enabled_paypal'])?$this->data['wd_enabled_paypal']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_payout_disabled",
	    isset($this->data['wd_payout_disabled'])?$this->data['wd_payout_disabled']:'');
	    	    
	    Yii::app()->functions->updateOptionAdmin("wd_payout_notification",
	    isset($this->data['wd_payout_notification'])?$this->data['wd_payout_notification']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_template_payout_subject",
	    isset($this->data['wd_template_payout_subject'])?$this->data['wd_template_payout_subject']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_template_process_subject",
	    isset($this->data['wd_template_process_subject'])?$this->data['wd_template_process_subject']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("wd_bank_fields",
	    isset($this->data['wd_bank_fields'])?$this->data['wd_bank_fields']:'');
	    
	    $this->code=1;
	    $this->msg=t("Successful");
	}
	
	public function requestPayout()
	{
		
		$mtid=Yii::app()->functions->getMerchantID();
		
 		$wd_paypal_minimum=yii::app()->functions->getOptionAdmin('wd_paypal_minimum');
        $wd_bank_minimum=yii::app()->functions->getOptionAdmin('wd_bank_minimum');
        
        $wd_paypal_minimum=standardPrettyFormat($wd_paypal_minimum);
        $wd_bank_minimum=standardPrettyFormat($wd_bank_minimum);
        
        $current_balance=Yii::app()->functions->getMerchantBalance($mtid);
        $this->data['current_balance']=$current_balance;

		$validator=new Validator;
		
		$req=array(
		  'payment_type'=>t("Payment type is required"),
		  'payment_method'=>t("Payment Method is required"),
		);								
		
		if ( $this->data['payment_method']=="paypal"){
			$req2=array(
			 'account'=>t("Email account not valid"),	 
			 'account_confirm'=>t("Confirm email account not valid"),
			);
			$validator->email($req2,$this->data);

			$req['amount']=t("Amount is required");

			if ( $this->data['account']!=$this->data['account_confirm']){
				$validator->msg[]=t("Confirm email does not match");
			}
											    		   
		} elseif ( $this->data['payment_method']=="bank" ){
			
		}				
		
		if ( $this->data['payment_type']=="single"){
			if ( $this->data['minimum_amount']>$this->data['amount']){
			   $validator->msg[]=t("Sorry but minimum amount is")." ".displayPrice(baseCurrency(),$this->data['minimum_amount']);
			}				
			if ( $current_balance<$this->data['amount']){		    	
		       $validator->msg[]=t("Amount is greater than your balance");
		    }
		} elseif ( $this->data['payment_type']=="all-earnings"){						
			$this->data['amount']=$current_balance;
			if ( $this->data['minimum_amount']>$this->data['amount']){
			   $validator->msg[]=t("Sorry but minimum amount is")." ".displayPrice(baseCurrency(),$this->data['minimum_amount']);
			}				
			if ( $this->data['minimum_amount']>$current_balance){
			   $validator->msg[]=t("Sorry but minimum amount is")." ".displayPrice(baseCurrency(),$this->data['minimum_amount']);
			}		
		} else {
			
		}
												
		$validator->required($req,$this->data);
											
		if ( $validator->validate()){
			if ( $this->data['payment_type']=="single"){															
				/*if  ( $wd_paypal_minimum>$this->data['amount']){
					$this->msg=t("Sorry but minimum amount is")." ".displayPrice(baseCurrency(),$wd_paypal_minimum);
				} else {*/
					$resp=Yii::app()->functions->payoutRequest($this->data['payment_method'],$this->data);
					if ($resp){
						$this->details=$resp['id'];
						$this->code=1;
						$this->msg=t("Successful");
																	
					} else $this->msg=t("ERROR: Something went wrong");
				//}			
			} else {
				//echo 'all earning';
				$resp=Yii::app()->functions->payoutRequest($this->data['payment_method'],$this->data);
				if ($resp){
					$this->details=$resp['id'];
					$this->code=1;
					$this->msg=t("Successful");											
			   } else $this->msg=t("ERROR: Something went wrong");
			}
		} else $this->msg=$validator->getErrorAsHTML();
				
		if ( $this->code==1){
			
			/*update all orders paid status to locked*/		
		    FunctionsK::updateAllPaidOrders($mtid);
			
			if ( isset($this->data['default_account_paypal'])){
			   if ( $this->data['default_account_paypal']==2){
			   	    Yii::app()->functions->updateOption("merchant_payout_account",
    	            isset($this->data['account'])?$this->data['account']:'',$mtid);
			   }
            }			
            
            if ( isset($this->data['default_account_bank'])){
            	if ( $this->data['default_account_bank']==2){
            		$bank_info=array(
            		  'swift_code'=>isset($this->data['swift_code'])?$this->data['swift_code']:'',
            		  'bank_account_number'=>isset($this->data['bank_account_number'])?$this->data['bank_account_number']:'' ,
            		  'account_name'=>isset($this->data['account_name'])?$this->data['account_name']:'',
            		  'bank_account_number'=>isset($this->data['bank_account_number'])?$this->data['bank_account_number']:'',
            		  'swift_code'=>isset($this->data['swift_code'])?$this->data['swift_code']:'',
            		  'bank_name'=>isset($this->data['bank_name'])?$this->data['bank_name']:'',
            		  'bank_branch'=>isset($this->data['bank_branch'])?$this->data['bank_branch']:''
            		);
            		Yii::app()->functions->updateOption("merchant_payout_bank_account",
            		json_encode($bank_info),$mtid);
            	}            
            }		
            
            // send email
            $merchant_email='';
			$tpl='';
			
			$wd_days_process=Yii::app()->functions->getOptionAdmin("wd_days_process");		
			if (empty($wd_days_process)){
    		    $wd_days_process=5;
    	    }
			$cancel_date=$wd_days_process-2;
	        $cancel_date=date("F d Y", strtotime (" +$cancel_date days"));
	        $process_date=date("F d Y", strtotime (" +$wd_days_process days"));
	        
			if ( $merchant_info=Yii::app()->functions->getMerchant($mtid)){			
				$merchant_email=$merchant_info['contact_email'];
				$cancel_link=websiteUrl()."/store/cancelwithdrawal/id/".$resp['token'];
				$tpl=yii::app()->functions->getOptionAdmin('wd_template_payout');
			    $tpl=smarty("merchant-name",$merchant_info['restaurant_name'],$tpl);
			    $tpl=smarty("payout-amount",standardPrettyFormat($this->data['amount']),$tpl);
			    $tpl=smarty("payment-method",$this->data['payment_method'],$tpl);
			    $tpl=smarty("account",$this->data['account'],$tpl);
			    $tpl=smarty("cancel-date",$cancel_date,$tpl);
			    $tpl=smarty("cancel-link",$cancel_link,$tpl);
			    $tpl=smarty("process-date",$process_date,$tpl);
			}		
										
			if (!empty($tpl)){
				$wd_template_payout_subject=yii::app()->functions->getOptionAdmin('wd_template_payout_subject');
                if (empty($wd_template_payout_subject)){
	                $wd_template_payout_subject=t("Your Request for Withdrawal was Received");
                }                
				sendEmail($merchant_email,'',$wd_template_payout_subject,$tpl);
			}            
		} 		
	}	

	public function incomingWithdrawals()
	{		
		$show_action=true;
		$and="WHERE status IN ('pending')";		
		if (isset($this->data['w-list'])){
			switch ($this->data['w-list']) {
				case "cancel":
					$and="WHERE status IN ('cancel')";
					$show_action=false;
					break;
				case "reversal":
					$and="WHERE status IN ('reversal')";
					$show_action=false;
					break;
				case "paid":					
					$and="WHERE status IN ('paid')";
					$show_action=true;
					break;
				case "denied":					
					$and="WHERE status IN ('denied')";
					$show_action=false;
					break;
				case "failed":	
				    $and="WHERE status NOT IN ('paid','pending','denied','approved','reversal')";
				    $show_action=false;
				    break;
				case "approved":    
				    $and="WHERE status IN ('approved')";
					$show_action=false;
					break;
				case "all":    
				    $and="";
				    $show_action=false;
				    break;
				default:
					break;
			}
		}
		
		if (isset($this->data['start_date']) && isset($this->data['end_date'])){
			if (!empty($this->data['start_date']) && !empty($this->data['end_date'])){
				if (!empty($and)){				
					$and.=" AND date_created BETWEEN  ".FunctionsV3::q($this->data['start_date']." 00:00:00")." AND 
	    		        ".FunctionsV3::q($this->data['end_date']." 23:59:00")."
	    		    ";
				} else {
					$and.=" WHERE date_created BETWEEN  ".FunctionsV3::q($this->data['start_date']." 00:00:00")." AND 
	    		        ".FunctionsV3::q($this->data['end_date']." 23:59:00")."
	    		    ";
				}
			}
		}	
		
		if (isset($this->data['merchant_id'])){
			if (!empty($this->data['merchant_id'])){
				if (!empty($and)){
					$and.=" AND merchant_id= ".FunctionsV3::q($this->data['merchant_id'])." ";
				} else {
					$and=" WHERE merchant_id=".FunctionsV3::q($this->data['merchant_id'])." ";
				}
			}
		}	
		
		$slug=$this->data['slug'];
        $stmt="
		SELECT a.*,
		(
		select restaurant_name 
		from
		{{merchant}}
		where
		merchant_id=a.merchant_id
		) as merchant_name
		 FROM
		{{withdrawal}} a		
		$and
		ORDER BY withdrawal_id DESC
		";
        
        if (isset($_GET['debug'])){
           dump($this->data);
           dump($stmt);
        }
        
        $_SESSION['kr_export_stmt']=$stmt;
        
		$connection=Yii::app()->db;
	    $rows=$connection->createCommand($stmt)->queryAll();     	    
	    if (is_array($rows) && count($rows)>=1){
	    	foreach ($rows as $val) {    	 
	    		$method=t("Paypal to")." ".$val['account'];
	    		if ( $val['payment_method']=="bank"){
	    			$method=t("Bank to")." ".$val['bank_account_number'];
	    		}	    	
	    		
	    		if ( $this->data['w-list']=="paid"){
	    		$action="<a href=\"javascript:;\" class=\"payout_action\" data-id=\"$val[withdrawal_id]\" data-status=\"reversal\">".t("Apply reversal")."</a><br/>";		    		
	    		} else {
	    		$action="<a href=\"javascript:;\" class=\"payout_action\" data-id=\"$val[withdrawal_id]\" data-status=\"approved\">".t("approved")."</a><br/>";
	    		$action.="<a href=\"javascript:;\" class=\"payout_action\" data-id=\"$val[withdrawal_id]\" data-status=\"denied\">".t("denied")."</a>";
	    		}
	    		
	    		/*$chk="<input type=\"checkbox\" name=\"row[]\" value=\"$val[cook_id]\" class=\"chk_child\" >";   		
	    		$option="<div class=\"options\">
	    		<a href=\"$slug/id/$val[cook_id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[cook_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";*/
	    		/*$date=date('M d,Y G:i:s',strtotime($val['date_created']));  
	    		$date=Yii::app()->functions->translateDate($date);*/
	    		$date=FormatDateTime($val['date_created']);
	    		
	    		/*$date_created=displayDate($val['date_created']);
	    		$date_to_process=displayDate($val['date_to_process']);*/
	    		$date_created=FormatDateTime($val['date_created'],false);
	    		$date_to_process=FormatDateTime($val['date_to_process'],false);
	    		
	    		$bank_info='';
	    		if ( $val['payment_method']=="bank"){
	    			$bank_info="<br/><a href=\"javascript:;\" data-id=\"$val[withdrawal_id]\" class=\"view-bank-info\">".t("View bank info")."</a>";
	    		}	    	
	    		
	    		$feed_data['aaData'][]=array(
	    		  $val['withdrawal_id'],
	    		  $val['merchant_name'],
	    		  $method.$bank_info,
	    		  displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['amount'])),
	    		  displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['current_balance'])),
	    		  "<span class=\"uk-badge withdrawal-status\">".t($val['status'])."</span>",
	    		  $date_created,
	    		  $date_to_process,	    		  
	    		  $show_action==true?$action:''
	    		);
	    	}
	    	$this->otableOutput($feed_data);
	    }     	    
	    $this->otableNodata();	
	}
	
	public function payoutChangeStatus()
	{	    
	    $validator=new Validator;
	    $req=array(
	     'withdrawal_id'=>t("withdrawal id is required"),
	     'status'=>t("Status is required"),
	    );
	    $validator->required($req,$this->data);
	    if ( $validator->validate()){
	    	$params=array(
	    	  'status'=>$this->data['status'],
	    	  'viewed'=>2
	    	);	    	
	    	$DbExt=new DbExt;
	    	if ( $DbExt->updateData("{{withdrawal}}",$params,'withdrawal_id',$this->data['withdrawal_id'])){
	    		$this->code=1;
	    		$this->msg=t("Successful");
	    	} else $this->msg=t("Failed cannot update records");	    
	    } else $this->msg=$validator->getErrorAsHTML();
	}
	
	public function wdPayoutNotification()
	{	
		$DbExt=new DbExt;
		$stmt="SELECT count(*) as total
		 FROM
		{{withdrawal}}
		WHERE
		status ='pending'
		AND
		viewed='1'
		";
		if ( $res=$DbExt->rst($stmt)){			
			if ( $res[0]['total']>=1){
				$this->code=1;
				$msg=t("There are")." (".$res[0]['total'].") ".t("withdrawals waiting for your approval");
				$this->msg=$msg."<br/><a class=\"white-link\" href=\"".websiteUrl()."/admin/incomingwithdrawal\">".t("Click here to view")."</a>";
				$this->details=$res[0]['total'];
			} else $this->msg="no results";
		} else $this->msg="no results";
	}
	
	public function cancelWithdrawal()
	{
		if ( $res=Yii::app()->functions->getWithdrawalInformationByToken($this->data['id'])){			
			if ($res['status']=="cancel"){				
				$this->msg=t("This withdrawal request has been already cancel");
				return ;
			}		
		}
		
		$DbExt=new DbExt;
		if (isset($this->data['id'])){
			$params=array(
			  'status'=>'cancel',
			  'date_process'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR']
			);
			if ($DbExt->updateData("{{withdrawal}}",$params,'withdrawal_token',$this->data['id'])){
				$this->code=1;
				$this->msg=t("Your withdrawal has been cancel");
			} else $this->msg=t("Error cannot cancel withdrawal please contact site admin");
		} else $this->msg=t("Missing id");
	}
	
	public function rptMerchantSalesSummaryReport()
	{
						
		$aColumns = array(		  
		  'restaurant_name','total_sales','total_commission','total_earnings'
		);		
		$sWhere=''; $sOrder=''; $sLimit='';		
		$functionk=new FunctionsK();
		$t=$functionk->ajaxDataTables($aColumns);
		if (is_array($t) && count($t)>=1){
			$sWhere=$t['sWhere'];
			$sOrder=$t['sOrder'];
			$sLimit=$t['sLimit'];
		}	
		
		unset($_SESSION['rpt_date_range']);
	
		$and='';  
    	if (isset($this->data['start_date']) && isset($this->data['end_date']))	{
    		if (!empty($this->data['start_date']) && !empty($this->data['end_date'])){
    		   $and=" AND date_created BETWEEN  ".FunctionsV3::q($this->data['start_date']." 00:00:00")." AND 
    		        ".FunctionsV3::q($this->data['end_date']." 23:59:00")."
    		   ";
    		   $_SESSION['rpt_date_range']=array(
    		     'start_date'=>$this->data['start_date'],
    		     'end_date'=>$this->data['end_date']
    		   );
    		}
    	}
    	
    	$order_status_id='';
	    $or='';
    	if (isset($this->data['stats_id'])){
	    	if (is_array($this->data['stats_id']) && count($this->data['stats_id'])>=1){
	    		foreach ($this->data['stats_id'] as $stats_id) {	    			
	    			$order_status_id.= FunctionsV3::q($stats_id).",";
	    		}
	    		if ( !empty($order_status_id)){
	    			$order_status_id=substr($order_status_id,0,-1);
	    		}		    	
	    	}	    
    	}
    	
    	if ( !empty($order_status_id)){	    		
    		$and.= " AND status IN ($order_status_id)";
    	}	    	 
    	
    	$where='';
    	if (isset($this->data['merchant_id'])){
    		if (!empty($this->data['merchant_id'])){
    			$where=" WHERE merchant_id=".Yii::app()->functions->q($this->data['merchant_id'])." ";
    		}    	
    	}	
    	
    	if(!isset($this->data['slug'])){
    		$this->data['slug']='';
    	}
		$slug=$this->data['slug'];
        $stmt="
		SELECT SQL_CALC_FOUND_ROWS a.restaurant_name,
		(
		select sum(total_w_tax)as total
		from 
		{{order}}
		where
		merchant_id=a.merchant_id
		$and
		) as total_sales,
		
		(
		select sum(total_commission)
		from
		{{order}}
		where
		merchant_id=a.merchant_id
		$and
		) as total_commission,
		
		(
		select sum(merchant_earnings)
		from
		{{order}}
		where
		merchant_id=a.merchant_id
		$and
		) as total_earnings
		
		
		FROM
		{{merchant}} a
		$where		
		$sOrder
		$sLimit
		";        		                
        $pos = strpos($stmt,"LIMIT");	    	
	    $_SESSION['kr_export_stmt'] = substr($stmt,0,$pos);	
        
		$connection=Yii::app()->db;
	    $rows=$connection->createCommand($stmt)->queryAll();     	    
	    if (is_array($rows) && count($rows)>=1){
	    	
	    	$iTotalRecords=0;
			$stmt2="SELECT FOUND_ROWS()";
			if ( $res2=$this->rst($stmt2)){				
				$iTotalRecords=$res2[0]['FOUND_ROWS()'];
			}						
			$feed_data['sEcho']=intval($_GET['sEcho']);
			$feed_data['iTotalRecords']=$iTotalRecords;
			$feed_data['iTotalDisplayRecords']=$iTotalRecords;
			
	    	foreach ($rows as $val) {    	     	    			    			    		
	    		$feed_data['aaData'][]=array(	    		  
	    		   stripslashes($val['restaurant_name']),
	    		   displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['total_sales']+0)),
	    		   displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['total_commission']+0)),
	    		   displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['total_earnings']+0)),	    		   
	    		);
	    	}
	    	$this->otableOutput($feed_data);
	    }     	    
	    $this->otableNodata();	
	}
	
	public function testEmail()
	{
		require_once 'test-email.php';
		die();
	}
	
	public function sendTestEmail()
	{		
		if (isset($this->data['email'])){
			$content="This is a test email";
			if ( Yii::app()->functions->sendEmail($this->data['email'],'',$content,$content)){
				$this->code=1;
				$this->msg=t("Successful");
			} else $this->msg=t("Sending of email has failed");		
		}  else $this->msg=t("Email is required");
	}
	
	public function FaxSettings()
	{	
		Yii::app()->functions->updateOptionAdmin("fax_company",
    	isset($this->data['fax_company'])?$this->data['fax_company']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("fax_username",
    	isset($this->data['fax_username'])?$this->data['fax_username']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("fax_password",
    	isset($this->data['fax_password'])?$this->data['fax_password']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("fax_enabled",
    	isset($this->data['fax_enabled'])?$this->data['fax_enabled']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("fax_user_admin_credit",
    	isset($this->data['fax_user_admin_credit'])?$this->data['fax_user_admin_credit']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("fax_email_notification",
    	isset($this->data['fax_email_notification'])?$this->data['fax_email_notification']:'');
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
		
	public function FaxpackagesList()
	{
	    $slug=$this->data['slug'];
		$stmt="SELECT * FROM
		{{fax_package}}			
		ORDER BY fax_package_id DESC
		";
		if ( $res=$this->rst($stmt)){
			foreach ($res as $val) {	
				/*$date=date('M d,Y G:i:s',strtotime($val['date_created']));  				
				$date=Yii::app()->functions->translateDate($date);*/
				$date=FormatDateTime($val['date_created']);
				$action="<div class=\"options\">
	    		<a href=\"$slug/id/$val[fax_package_id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[fax_package_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";
				$val['title']=ucwords($val['title']);
				$feed_data['aaData'][]=array(
				  $val['fax_package_id'],
				  $val['title'].$action,
				  Yii::app()->functions->limitDescription($val['description']),
				  Yii::app()->functions->standardPrettyFormat($val['price']),
				  Yii::app()->functions->standardPrettyFormat($val['promo_price']),
				  $val['fax_limit'],
				  $date."<div>".Yii::t("default",$val['status'])."</div>"					  
				);
			}
			$this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}
	
	public function FaxPackageAdd()
	{		   
       $params=array(
         'title'=>$this->data['title'],
         'description'=>$this->data['description'],
         'price'=>$this->data['price'],
         'promo_price'=>is_numeric($this->data['promo_price'])?$this->data['promo_price']:0,
         'fax_limit'=>$this->data['fax_limit'],
         'status'=>$this->data['status'],
         'date_created'=>FunctionsV3::dateNow(),
         'ip_address'=>$_SERVER['REMOTE_ADDR']
       );	       
       if (empty($this->data['id'])){	
	    	if ( $this->insertData("{{fax_package}}",$params)){
	    		$this->details=Yii::app()->db->getLastInsertID();
	    		$this->code=1;
	    		$this->msg=Yii::t("default","Successful");		    		
	    	}
	    } else {		    	
	    	unset($params['date_created']);
			$params['date_modified']=FunctionsV3::dateNow();			
			$res = $this->updateData('{{fax_package}}' , $params ,'fax_package_id',$this->data['id']);
			if ($res){
				$this->code=1;
                $this->msg=Yii::t("default",'Package updated.');  
			} else $this->msg=Yii::t("default","ERROR: cannot update");
	    }			
	}	
	
	public function FaxMerchantSettings()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("fax_merchant_recipient",
    	isset($this->data['fax_merchant_recipient'])?$this->data['fax_merchant_recipient']:''
    	,$merchant_id);
    	
	    Yii::app()->functions->updateOption("fax_merchant_number",
    	isset($this->data['fax_merchant_number'])?$this->data['fax_merchant_number']:''
    	,$merchant_id);
    	
    	Yii::app()->functions->updateOption("fax_merchant_enabled",
    	isset($this->data['fax_merchant_enabled'])?$this->data['fax_merchant_enabled']:''
    	,$merchant_id);
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function initpaymentprovider()
	{
		$params="?method=".$this->data['payment_opt'];
		$params.="&purchase=".$this->data['purchase'];		
		$params.="&return_url=".$this->data['return_url'];
				
		$FunctionsK=new FunctionsK;		
		$merchantinfo=Yii::app()->functions->getMerchantInfo();
		
			
		switch ($this->data['purchase']) {
			case "fax_package":	
					
			    $resp=$FunctionsK->getFaxPackagesById( isset($this->data['fax_package_id'])?$this->data['fax_package_id']:'' );			    
			    if (!$resp){
			    	$this->msg=t("Package information not found");
			    	return ;
			    }		
			    
			    if ($resp['promo_price']>=1){
					$package_price=$resp['promo_price'];
			    } else $package_price=$resp['price'];
			   
			    $credit=$resp['fax_limit'];
			    
			    if ($this->data['payment_opt']=="pyp" || $this->data['payment_opt']=="stp"){
					$params.="&package_id=".$this->data['fax_package_id'];				
					//if ( $resp=$FunctionsK->getFaxPackagesById($this->data['fax_package_id'])){					
					if ($resp){
						$params2='';
						if ($resp['promo_price']>=1){
							$params2.="&price=".$resp['promo_price'];
						} else $params2.="&price=".$resp['price'];
							
						$params2.="&description=".urlencode($resp['title']);		
						$params.="&raw=".base64_encode($params2);
												
						$this->code=1;
						$this->msg=t("Please wait while we redirect you");
						$this->details=websiteUrl()."/merchant/pay/$params";
					} else $this->msg=t("Package information not found");
					
			    } elseif ( $this->data['payment_opt']=="obd"){
			    	
			    	//$merchantinfo=Yii::app()->functions->getMerchantInfo();			    	
			    	if ( is_array($merchantinfo) && count($merchantinfo)>=1){
			    		$merchant_email=$merchantinfo[0]->contact_email;
			    		$ref="FAX_".Yii::app()->functions->generateRandomKey(8);
			 			    		
			    		$params_insert=array(
			    		 'merchant_id'=>Yii::app()->functions->getMerchantID(),
			    		 'fax_package_id'=>$this->data['fax_package_id'],
			    		 'payment_type'=>Yii::app()->functions->paymentCode("bankdeposit"),
			    		 'package_price'=>$package_price,
			    		 'fax_limit'=>$credit,
			    		 'payment_reference'=>$ref,
			    		 'date_created'=>FunctionsV3::dateNow(),
			    		 'ip_address'=>$_SERVER['REMOTE_ADDR']
			    		);		
			    		$bank_info=Yii::app()->functions->getBankDepositInstruction();			    		
			    		$tpl=$bank_info['content'];
			    		$tpl=smarty('amount',displayPrice(baseCurrency(),standardPrettyFormat($package_price)),$tpl);
			    		$tpl=smarty('verify-payment-link',
			    		websiteUrl()."/merchant/faxbankdepositverification/?ref=$ref",$tpl);
			    		
			    		if (sendEmail($merchant_email,$bank_info['sender'],$bank_info['subject'],$tpl)){
			    			if ( $this->insertData("{{fax_package_trans}}",$params_insert)){
			    				$this->details=websiteUrl()."/merchant/faxreceipt/id/".Yii::app()->db->getLastInsertID();
			    				$this->code=1;
$this->msg=t("We have sent bank information instruction to your email")." :$merchant_email";


                                //$FunctionsK=new FunctionsK();
                                $FunctionsK->faxSendNotification((array)$merchantinfo[0],
                                                   $this->data['fax_package_id'],
                                                   Yii::app()->functions->paymentCode("bankdeposit"),
                                                   $package_price);
                                
			    			} else $this->msg=t("Error cannot saved information");
			    		} else $this->msg=t("Failed cannot send bank payment instructions");
			    	} else $this->msg=t("Something went wrong merchant information is empty");
			    } else {
			    	if ($package_price==0){
			    		// Free package
			    		$params_insert=array(
			    		 'merchant_id'=>Yii::app()->functions->getMerchantID(),
			    		 'fax_package_id'=>$this->data['fax_package_id'],
			    		 'payment_type'=>'Free',
			    		 'package_price'=>$package_price,
			    		 'fax_limit'=>$credit,
			    		 'payment_reference'=>'',
			    		 'date_created'=>FunctionsV3::dateNow(),
			    		 'ip_address'=>$_SERVER['REMOTE_ADDR'],
			    		 'status'=>"paid"
			    		);					    		
			    		if ( $this->insertData("{{fax_package_trans}}",$params_insert)){
			    			$this->details=websiteUrl()."/merchant/faxreceipt/id/".Yii::app()->db->getLastInsertID();
			    			$this->code=1;
                            $this->msg=t("Successful");                            
                            $FunctionsK->faxSendNotification((array)$merchantinfo[0],
                                                   $this->data['fax_package_id'],
                                                   "Free",
                                                   $package_price);
			    		} else $this->msg=t("Error cannot saved information");
			    	} else {
			    		//$this->msg=t("No payment options has been selected");
			    		$this->code=1; $this->msg=t("Please wait");
			    		$this->details=Yii::app()->createUrl('merchant/fax-'.$this->data['payment_opt'].'-init',array(
			    		  'fax_id'=>$this->data['fax_package_id']
			    		));
			    	}			    
			    }
				break;
		
			default:
				$this->msg=t("No found instructions");
				break;
		}
	}
	
	public function paymentPaypalVerification()
	{		
		$raw=base64_decode(isset($this->data['raw'])?$this->data['raw']:'');
		parse_str($raw,$raw_decode);				
						
		$price='';
		$description='';
		if (is_array($raw_decode) && count($raw_decode)>=1){
			$price=isset($raw_decode['price'])?$raw_decode['price']:'';
			$description=isset($raw_decode['description'])?$raw_decode['description']:'';
		}
		
		$paypal_con=Yii::app()->functions->getPaypalConnectionAdmin();   			
        $paypal=new Paypal($paypal_con);
		
		if ($res_paypal=$paypal->getExpressDetail()){	            	
							
			$paypal->params['PAYERID']=$res_paypal['PAYERID'];
            $paypal->params['AMT']=$res_paypal['AMT'];
            $paypal->params['TOKEN']=$res_paypal['TOKEN'];
            $paypal->params['CURRENCYCODE']=$res_paypal['CURRENCYCODE'];	            	           
		            
            if ($res=$paypal->expressCheckout()){ 
            	            
        	    /*now insert transaction logs*/
				if ( $this->data['purchase']=="fax_package"){
					$payment_code=Yii::app()->functions->paymentCode("paypal");
					
					$FunctionsK=new FunctionsK;
					$info=$FunctionsK->getFaxPackagesById($this->data['package_id']);
										
	                $params=array(
    			      'merchant_id'=>Yii::app()->functions->getMerchantID(),
	    			  'fax_package_id'=>$this->data['package_id'],
	    			  'payment_type'=>$payment_code,
	    			  'package_price'=>$price,
	    			  'fax_limit'=>$info['fax_limit'],
	    			  'date_created'=>FunctionsV3::dateNow(),
	    			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
	    			  'payment_gateway_response'=>json_encode($res),
	    			  'status'=>"paid"
	    			);	 
	    				    				    		
	    			if ( $this->insertData("{{fax_package_trans}}",$params)){
					   $this->details=websiteUrl()."/merchant/faxreceipt/id/".Yii::app()->db->getLastInsertID();
					   $this->code=1;
					   $this->msg=Yii::t("default","Successful");	

					   
					   $merchantinfo=Yii::app()->functions->getMerchantInfo();			    	
					   $FunctionsK=new FunctionsK();
                       $FunctionsK->faxSendNotification((array)$merchantinfo[0],
                                           $this->data['package_id'],
                                           $payment_code,
                                           $price);
                        
					   							
				    } else $this->msg=Yii::t("default","ERROR: Cannot insert record.");	
				    
				} else $this->msg=t("Uknown transaction");
					            	
            } else $this->msg=$paypal->getError();	
		} else $this->msg=$paypal->getError();	           	
	}
	
	public function faxTransactionList()
	{
	    $slug=$this->data['slug'];
		$stmt="SELECT a.*,
		(
		select restaurant_name
		from
		{{merchant}} 
		where
		merchant_id=a.merchant_id
		limit 0,1
		) merchant_name,
		
		(
		select title
		from
		{{fax_package}}
		where
		fax_package_id=a.fax_package_id
		limit 0,1
		) fax_package_name
		
		 FROM
		{{fax_package_trans}} a
		ORDER BY id DESC
		";
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    
				$action="<div class=\"options\">
	    		<a href=\"$slug/Do/Add/?id=$val[id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";		   	
				
				$class='';
			   /*$date=Yii::app()->functions->prettyDate($val['date_created']);
			   $date=Yii::app()->functions->translateDate($date);   */
			   $date=FormatDateTime($val['date_created']);
			   
		   	   $feed_data['aaData'][]=array(
		   	      $val['id'],
		   	      ucwords($val['merchant_name']).$action,
		   	      ucwords($val['fax_package_name']),
		   	      standardPrettyFormat($val['package_price']),
		   	      $val['fax_limit'],
		   	      strtoupper($val['payment_type']),
		   	      $date."<br/><div class=\"uk-badge $class\">".strtoupper(Yii::t("default",$val['status']))."</div>"
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}	
	
	public function updateFaxTransaction()
	{						
		$f=new FunctionsK;
		if (empty($this->data['id'])){
			if ( $res=$f->getFaxPackagesById($this->data['fax_package_id'])){
				if ( $res['promo_price']>=1){
					$package_price=$res['promo_price'];
				} else $package_price=$res['price'];
			}
			$params=array(
			  'merchant_id'=>$this->data['merchant_id'],
			  'fax_package_id'=>$this->data['fax_package_id'],
			  'package_price'=>$package_price,
			  'fax_limit'=>$this->data['fax_limit'],
			  'status'=>$this->data['status'],
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			  'payment_type'=>"manual"
			);				
			if ( $this->insertData("{{fax_package_trans}}",$params)){
				$this->details=Yii::app()->db->getLastInsertID();					
				$this->code=1;
				$this->msg=t("Successful");
			} else $this->msg=t("ERROR. cannot insert data.");
		} else {		
			$params=array( 
			  'fax_limit'=>$this->data['fax_limit'],
			  'status'=>$this->data['status'],
			  'ip_address'=>$_SERVER['REMOTE_ADDR']
			);
			if ( $this->updateData("{{fax_package_trans}}",$params,'id',$this->data['id']) ){
				$this->code=1;
				$this->msg=Yii::t("default","Successful");
			} else $this->msg=Yii::t("default","ERROR: cannot update");		
		}
	}		
	
	public function FaxbankDepositVerification()
	{
		$FunctionsK=new FunctionsK();
		
		if (isset($this->data['photo'])){
				$req=array('ref'=>t("reference number is required"));
			} else {
		        $req=array(
		          'branch_code'=>t("branch code is required"),
		          'date_of_deposit'=>t("date of deposit is required"),
		          'time_of_deposit'=>t("time of deposit is required"),
		          'amount'=>t("amount is required"),
		        );
			}
			$Validator=new Validator;			
			$Validator->required($req,$this->data);
						
			if ($Validator->validate()){
				$DbExt=new DbExt;
				 if ($res=$FunctionsK->getFaxTransactionByRef($this->data['ref'])){			 	
				 	
					$params=array(				
					  'merchant_id'=>Yii::app()->functions->getMerchantID(),
					  'branch_code'=>$this->data['branch_code'],
					  'date_of_deposit'=>$this->data['date_of_deposit'],
					  'time_of_deposit'=>$this->data['time_of_deposit'],
					  'amount'=>$this->data['amount'],
					  'scanphoto'=>isset($this->data['photo'])?$this->data['photo']:'',
					  'date_created'=>FunctionsV3::dateNow(),
					  'ip_address'=>$_SERVER['REMOTE_ADDR'],
					  'transaction_type'=>"fax_purchase"
					);									
					if ($DbExt->insertData("{{bank_deposit}}",$params)){
						$this->code=1;
						$this->msg=Yii::t("default","Thank you. Your information has been receive please wait 1 or 2 days to verify your payment.");
						
						/*send email to admin owner*/
						$from='no-reply@'.$_SERVER['HTTP_HOST'];
	    	            $subject=Yii::t("default","New Bank Deposit");
	    	            $to=Yii::app()->functions->getOptionAdmin('website_contact_email');
	    	            $tpl=EmailTPL::bankDepositedReceive();
	    	            if (!empty($to)){
	    	                Yii::app()->functions->sendEmail($to,$from,$subject,$tpl);
	    	            }
						
					} else $this->msg=Yii::t("default","Something went wrong during processing your request. Please try again later.");
				 } else $this->msg=Yii::t("default","Reference number not found");
			} else $this->msg=$Validator->getErrorAsHTML();
	}
	
	public function faxTransactionLogs()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
		$slug=$this->data['slug'];
		$stmt="
		SELECT * FROM
		{{fax_broadcast}}
		WHERE
		merchant_id=".Yii::app()->functions->q($merchant_id)."
		ORDER BY id DESC
		";
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    				
			   /*$date=Yii::app()->functions->prettyDate($val['date_created']);
			   $date=Yii::app()->functions->translateDate($date);   */
			   $date=FormatDateTime($val['date_created']);
		   	   $feed_data['aaData'][]=array(
		   	      $val['id'],
		   	      $val['jobid'],
		   	      $val['faxurl'],
		   	      $val['status'],
		   	      $val['api_raw_response'],
		   	      $date
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}
	
	public function updateMerchantUserProfile()
	{		
		$params=array(
		  'first_name'=>$this->data['first_name'],
		  'last_name'=>$this->data['last_name'],
		  'contact_email'=>$this->data['contact_email'],
		  'username'=>$this->data['username'],
		  'date_modified'=>FunctionsV3::dateNow()
		);
		if (!empty($this->data['password'])){
			$params['password']=md5($this->data['password']);
		}
		
		if ($this->updateData("{{merchant_user}}",$params,'merchant_user_id',$this->data['merchant_user_id'])){
			$this->code=1;
			$this->msg=t("Profile successfully updated");
		} else $this->msg=t("ERROR: cannot update");
	}
	
	public function faxPurchaseTransaction()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
		$slug=$this->data['slug'];
		$stmt="
		SELECT a.*,
		(
		select title
		from
		{{fax_package}}
		where
		fax_package_id=a.fax_package_id
		) as package_name
		 FROM
		{{fax_package_trans}} a
		WHERE
		merchant_id=".Yii::app()->functions->q($merchant_id)."
		ORDER BY id DESC
		";
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    				
			   /*$date=Yii::app()->functions->prettyDate($val['date_created']);
			   $date=Yii::app()->functions->translateDate($date);   */
			   $date=FormatDateTime($val['date_created']);
		   	   $feed_data['aaData'][]=array(
		   	      $val['id'],
		   	      strtoupper($val['payment_type']),
		   	      ucfirst($val['package_name']),
		   	      displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['package_price'])),
		   	      $val['fax_limit'],
		   	      $val['status'],
		   	      $date
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}
	
	public function smsPurchaseTransaction()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
		$slug=$this->data['slug'];
		$stmt="
		SELECT a.*,
		(
		select title
		from
		{{sms_package}}
		where
		sms_package_id=a.sms_package_id
		) as package_name
		 FROM
		{{sms_package_trans}} a
		WHERE
		merchant_id=".Yii::app()->functions->q($merchant_id)."
		ORDER BY id DESC
		";
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    				
			   /*$date=Yii::app()->functions->prettyDate($val['date_created']);
			   $date=Yii::app()->functions->translateDate($date);   */
			   $date=FormatDateTime($val['date_created']);
		   	   $feed_data['aaData'][]=array(
		   	      $val['id'],
		   	      //strtoupper($val['payment_type']),
		   	      FunctionsV3::prettyPaymentType('sms_package_trans',$val['payment_type'],$val['id']),
		   	      ucfirst($val['package_name']),
		   	      displayPrice(adminCurrencySymbol(),normalPrettyPrice($val['package_price'])),
		   	      $val['sms_limit'],
		   	      $val['status'],
		   	      $date
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}


	public function viewBankInfo()
	{
		require_once 'bank-info.php';
		die();
	}
	
	public function shipppingRates()
	{		
		$mtid=Yii::app()->functions->getMerchantID();	
		Yii::app()->functions->updateOption("shipping_enabled",
    	isset($this->data['shipping_enabled'])?$this->data['shipping_enabled']:'',$mtid);
    	
    	Yii::app()->functions->updateOption("free_delivery_above_price",
    	isset($this->data['free_delivery_above_price'])?$this->data['free_delivery_above_price']:'',$mtid);
    	
    	if (is_array($this->data['distance_from']) && count($this->data['distance_from'])>=1){    		
    		$x=0;
    		$stmt="
    		DELETE FROM
    		{{shipping_rate}}    		
    		WHERE
    		merchant_id=".Yii::app()->functions->q($mtid)."
    		";
    		$this->qry($stmt);
    		foreach ($this->data['distance_from'] as $val) {    	    			
    			$params=array(
    			  'merchant_id'=>$mtid,
    			  'distance_from'=>is_numeric($val)?$val:0,
    			  'distance_to'=>is_numeric($this->data['distance_to'][$x])?$this->data['distance_to'][$x]:0,
    			  'shipping_units'=>$this->data['shipping_units'][$x],
    			  'distance_price'=>is_numeric($this->data['distance_price'][$x])?$this->data['distance_price'][$x]:0,    			  
    			);    	    			
    			$this->insertData("{{shipping_rate}}",$params);
    			$x++;
    		}
    	}	
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
	
	public function bookingSummaryReport()
	{
		
		$aColumns = array('total_approved','total_denied','total_pending');
		
		$sWhere=''; $sOrder=''; $sLimit='';
		
		$functionk=new FunctionsK();
		$t=$functionk->ajaxDataTables($aColumns);
		if (is_array($t) && count($t)>=1){
			$sWhere=$t['sWhere'];
			$sOrder=$t['sOrder'];
			$sLimit=$t['sLimit'];
		}			

		$merchant_id=Yii::app()->functions->getMerchantID();
		
		$and='';  
    	if (isset($this->data['start_date']) && isset($this->data['end_date']))	{
    		if (!empty($this->data['start_date']) && !empty($this->data['end_date'])){
    		  $and=" AND date_created BETWEEN  ".FunctionsV3::q($this->data['start_date']." 00:00:00")." AND 
    		        ".FunctionsV3::q($this->data['end_date']." 23:59:00")."
    		  ";
    		    		  
              $_SESSION['rpt_date_range']=array(
    		     'start_date'=>$this->data['start_date'],
    		     'end_date'=>$this->data['end_date']
    		   );
    		  
    		}
    	}
		
		$slug=isset($this->data['slug'])?$this->data['slug']:'';
		$stmt="
		SELECT SQL_CALC_FOUND_ROWS sum(a.number_guest) as total_approved,
		(
		select sum(number_guest)
		from {{bookingtable}}
		where
		merchant_id=".Yii::app()->functions->q($merchant_id)."
		and
		status='denied'
		) as total_denied,
		
		(
		select sum(number_guest)
		from {{bookingtable}}
		where
		merchant_id=".Yii::app()->functions->q($merchant_id)."
		and
		status='pending'
		) as total_pending
		
		FROM
		{{bookingtable}} a
		WHERE
		merchant_id=".Yii::app()->functions->q($merchant_id)."
		AND status='approved'
		$and		
        $sOrder
        $sLimit
		";				
		$pos = strpos($stmt,"LIMIT");	    	
        $_SESSION['kr_export_stmt'] = substr($stmt,0,$pos);	
		
		if ($res=$this->rst($stmt)){	

			$iTotalRecords=0;
			$stmt2="SELECT FOUND_ROWS()";
			if ( $res2=$this->rst($stmt2)){
				$iTotalRecords=$res2[0]['FOUND_ROWS()'];
			}	
						
			$feed_data['sEcho']=intval($_GET['sEcho']);
			$feed_data['iTotalRecords']=$iTotalRecords;
			$feed_data['iTotalDisplayRecords']=$iTotalRecords;
		
		   foreach ($res as $val) {				   	    			   	    							   
		   	   $feed_data['aaData'][]=array(
		   	      $val['total_approved']+0,
		   	      $val['total_denied']+0,
		   	      $val['total_pending']+0
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}
	
	public function merchanBbookingSummaryReport()
	{				
		$aColumns = array(
		  'merchant_name','total_approved','total_denied','total_pending'
		);		
		$sWhere=''; $sOrder=''; $sLimit='';		
		$functionk=new FunctionsK();
		$t=$functionk->ajaxDataTables($aColumns);
		if (is_array($t) && count($t)>=1){
			$sWhere=$t['sWhere'];
			$sOrder=$t['sOrder'];
			$sLimit=$t['sLimit'];
		}	
		
		unset($_SESSION['rpt_date_range']);
		$and='';  
    	if (isset($this->data['start_date']) && isset($this->data['end_date']))	{
    		if (!empty($this->data['start_date']) && !empty($this->data['end_date'])){
    		   $and=" AND date_created BETWEEN  ".FunctionsV3::q($this->data['start_date']." 00:00:00")." AND 
    		        ".FunctionsV3::q($this->data['end_date']." 23:59:00")."
    		   ";    		   
               $_SESSION['rpt_date_range']=array(
    		     'start_date'=>$this->data['start_date'],
    		     'end_date'=>$this->data['end_date']
    		   );
    		}
    	}
    	
    	$where='';
    	if (isset($this->data['merchant_id'])){
    		if (!empty($this->data['merchant_id'])){
    			$where=" WHERE merchant_id=".Yii::app()->functions->q($this->data['merchant_id'])." ";
    		}    	
    	}	
    	
    	$stmt="
    	SELECT SQL_CALC_FOUND_ROWS a.merchant_id,a.restaurant_name as merchant_name,
    	
    	(
    	select sum(number_guest)
    	from
    	{{bookingtable}}
    	where
    	merchant_id=a.merchant_id  
    	and status='approved'  	
    	$and
    	) as total_approved,
    	
    	
    	(
    	select sum(number_guest)
    	from
    	{{bookingtable}}
    	where
    	merchant_id=a.merchant_id  
    	and status='denied'  	
    	$and
    	) as total_denied,
    	
    	
    	(
    	select sum(number_guest)
    	from
    	{{bookingtable}}
    	where
    	merchant_id=a.merchant_id    	
    	and status='pending'
    	$and
    	) as total_pending
    	
    	
    	FROM
    	{{merchant}} a
    	$where
    	GROUP BY merchant_id    	
		$sOrder
		$sLimit
    	";		
		$pos = strpos($stmt,"LIMIT");	    	
	    $_SESSION['kr_export_stmt'] = substr($stmt,0,$pos);			
		
		if ($res=$this->rst($stmt)){		   	
			
			$iTotalRecords=0;
			$stmt2="SELECT FOUND_ROWS()";
			if ( $res2=$this->rst($stmt2)){				
				$iTotalRecords=$res2[0]['FOUND_ROWS()'];
			}	
						
			$feed_data['sEcho']=intval($_GET['sEcho']);
			$feed_data['iTotalRecords']=$iTotalRecords;
			$feed_data['iTotalDisplayRecords']=$iTotalRecords;
							   
		   foreach ($res as $val) {				   	    			   	    							   
		   	   $feed_data['aaData'][]=array(
		   	      ucwords(stripslashes($val['merchant_name'])),
		   	      $val['total_approved']+0,
		   	      $val['total_denied']+0,
		   	      $val['total_pending']+0
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}
	
	public function adminFaxTransactionLogs()
	{		
		$slug=$this->data['slug'];
		$stmt="
		SELECT a.*,
		(
		select restaurant_name
		from
		{{merchant}}
		where
		merchant_id=a.merchant_id
		) as merchant_name
		 FROM
		{{fax_broadcast}} a		
		ORDER BY id DESC
		";
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    				
			   /*$date=Yii::app()->functions->prettyDate($val['date_created']);
			   $date=Yii::app()->functions->translateDate($date);   */
			   $date=FormatDateTime($val['date_created']);
		   	   $feed_data['aaData'][]=array(
		   	      $val['id'],
		   	      stripslashes($val['merchant_name']),
		   	      $val['jobid'],
		   	      $val['faxurl'],
		   	      $val['status'],
		   	      $val['api_raw_response'],
		   	      $date
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}	
	
	public function merchantList()
	{
				       		
		
		$slug=$this->data['slug'];
		
		$aColumns = array(
		  'merchant_id','restaurant_name','street','city','country_code','contact_phone',
		  'package_id','activation_token','is_commission','status'
		);
		
		$sWhere=''; $sOrder=''; $sLimit='';
		
		$sTable = "{{merchant}}";
		
		$functionk=new FunctionsK();
		$t=$functionk->ajaxDataTables($aColumns);
		if (is_array($t) && count($t)>=1){
			$sWhere=$t['sWhere'];
			$sOrder=$t['sOrder'];
			$sLimit=$t['sLimit'];
		}	
		$stmt = "
			SELECT SQL_CALC_FOUND_ROWS 
			a.*,
			(
			select title
			from
			{{packages}}
			where
			package_id = a.package_id
			limit 0,1
			) as package_name
			
			FROM $sTable a
			$sWhere
			$sOrder
			$sLimit
		";	
		if ( $res=$this->rst($stmt)){		
			
			$iTotalRecords=0;
			$stmt2="SELECT FOUND_ROWS()";
			if ( $res2=$this->rst($stmt2)){				
				$iTotalRecords=$res2[0]['FOUND_ROWS()'];
			}	
						
			$feed_data['sEcho']=intval($_GET['sEcho']);
			$feed_data['iTotalRecords']=$iTotalRecords;
			$feed_data['iTotalDisplayRecords']=$iTotalRecords;
			
			foreach ($res as $val) {	
				    $class='';
					/*$date=date('M d,Y G:i:s',strtotime($val['date_created']));
					$date=Yii::app()->functions->translateDate($date);*/
					$date=FormatDateTime($val['date_created']);
					
					$action="<div class=\"options\">
    	    		<a href=\"$slug/id/$val[merchant_id]\" >".Yii::t("default","Edit")."</a>
    	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[merchant_id]\" >".Yii::t("default","Delete")."</a>
    	    		</div>";
					
					$val['package_name']=isset($val['package_name'])?$val['package_name']:'';
					
					if ($val['status']=="expired"){
					   $class='uk-badge-danger';
					} elseif ( $val['status']=="pending"){
						$class='';
					} elseif ($val['status']=="active"){
						$class='uk-badge-success';
					}				
					$membershipdate=FormatDateTime($val['membership_expired'],false);
					$membershipdate=Yii::app()->functions->translateDate($membershipdate);					
					
					$url_login=baseUrl()."/merchant/autologin/id/".$val['merchant_id']."/token/".$val['password'];
					$link_login='<br/><br/>
					<a target="_blank" href="'.$url_login.'"><div class="uk-badge">'.t("AutoLogin").'</div></a>
					';
					
					$aa_access=Yii::app()->functions->AAccess();
					if (!in_array('autologin',(array)$aa_access)){
						$link_login='';						
					}					
					
					if (getOptionA('home_search_mode')!="postcode"){
						$feed_data['aaData'][]=array(
						  $val['merchant_id'],stripslashes($val['restaurant_name']).$action,
						  $val['street'],
						  $val['city'],
						  $val['country_code'],
						  $val['restaurant_phone']." / ".$val['contact_phone'],
						  $val['package_name']."<br/>".$membershipdate,
						  $val['activation_key'],
						  //membershipType($val['is_commission']),
						  FunctionsV3::DisplayMembershipType($val['merchant_type'], $val['invoice_terms']),
						  //$date."<br/><div class=\"uk-badge $class\">".strtoupper(Yii::t("default",$val['status']))."</div>".$link_login
						  "$date<br/><span class=\"tag ".$val['status']."\">".t($val['status'])."</span>$link_login"
						  
						);
					} else {
						$feed_data['aaData'][]=array(
						  $val['merchant_id'],stripslashes($val['restaurant_name']).$action,
						  $val['street'],						  
						  $val['post_code'],
						  $val['restaurant_phone']." / ".$val['contact_phone'],
						  $val['package_name']."<br/>".$membershipdate,
						  $val['activation_key'],
						  //membershipType($val['is_commission']),
						  FunctionsV3::DisplayMembershipType($val['merchant_type'] , $val['invoice_terms']),
						  //$date."<br/><div class=\"uk-badge $class\">".strtoupper(Yii::t("default",$val['status']))."</div>".$link_login
						  "$date<br/><span class=\"tag ".$val['status']."\">".t($val['status'])."</span>$link_login"
					);
					}
		
				}										
						
			$this->otableOutput($feed_data);	
		}
	    $this->otableNodata();
	}
	
	public function showSMS()
	{
		require_once "show-sms.php";
		die();
	}
	
	public function sendUpdateOrderSMS()
	{				
		$_GET['backend']=true;
		$order=Yii::app()->functions->getOrder2($this->data['order_id']);		
				
		if (isset($this->data['sms_order_change_msg'])){
			if (is_array($order) && count($order)>=1){
				$sms_msg=$this->data['sms_order_change_msg'];				
				$to=$order['contact_phone'];		
				
				$mtid=Yii::app()->functions->getMerchantID();				
				$available_credit=Yii::app()->functions->getMerchantSMSCredit($mtid);	 				
				if ( $available_credit>=1){						
					if ($resp= Yii::app()->functions->sendSMS($to,$sms_msg)){							
						if ( $resp['msg']=='process'){
							$this->code=1;
						    $this->msg=t("SMS sent");
						    $params=array(
						      'merchant_id'=>$mtid,
						      'broadcast_id'=>'999999999',
						      'client_id'=>$order['client_id'],
						      'client_name'=>$order['full_name'],
						      'contact_phone'=>$to,
						      'sms_message'=>$sms_msg,
						      'status'=>'process',
						      'gateway_response'=>$resp['raw'],
						      'date_created'=>FunctionsV3::dateNow(),
						      'date_executed'=>FunctionsV3::dateNow(),
						      'ip_address'=>$_SERVER['REMOTE_ADDR'],
						      'gateway'=>$resp['sms_provider']
						    );						    
						    $this->insertData("{{sms_broadcast_details}}",$params);
						} else $this->msg=$resp['msg'];
					} else $this->msg=$resp['msg'];
				} else $this->msg=t("No SMS Credit");
			} else $this->msg=t("Sory but we cannot find the order information");
		} else $this->msg=t("Message is required");
	}	
	
	public function saveAdminAuthorizeSettings()
	{

		Yii::app()->functions->updateOptionAdmin("admin_enabled_autho",
    	isset($this->data['admin_enabled_autho'])?$this->data['admin_enabled_autho']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_mode_autho",
    	isset($this->data['admin_mode_autho'])?$this->data['admin_mode_autho']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_autho_api_id",
    	isset($this->data['admin_autho_api_id'])?$this->data['admin_autho_api_id']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_autho_key",
    	isset($this->data['admin_autho_key'])?$this->data['admin_autho_key']:'');
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
	
	public function saveMerchantAuthorizeSettings()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("merchant_enabled_autho",
    	isset($this->data['merchant_enabled_autho'])?$this->data['merchant_enabled_autho']:'',$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_mode_autho",
    	isset($this->data['merchant_mode_autho'])?$this->data['merchant_mode_autho']:'',$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_autho_api_id",
    	isset($this->data['merchant_autho_api_id'])?$this->data['merchant_autho_api_id']:'',$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_autho_key",
    	isset($this->data['merchant_autho_key'])?$this->data['merchant_autho_key']:'',$merchant_id);
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
	
	public function merchantBankDeposit()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("merchant_bankdeposit_enabled",
    	isset($this->data['merchant_bankdeposit_enabled'])?$this->data['merchant_bankdeposit_enabled']:'',$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_deposit_sender",
    	isset($this->data['merchant_deposit_sender'])?$this->data['merchant_deposit_sender']:'',$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_deposit_subject",
    	isset($this->data['merchant_deposit_subject'])?$this->data['merchant_deposit_subject']:'',$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_deposit_instructions",
    	isset($this->data['merchant_deposit_instructions'])?$this->data['merchant_deposit_instructions']:'',$merchant_id);
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
	
	public function ItemBankDepositVerification()
	{		
		$order_id = isset($this->data['ref'])?(integer)$this->data['ref']:'';	

		try {				
			FunctionsV3::getBankDeposit($order_id);			
			$this->msg = t("There is already upload bank deposit for this transaction");
		    return ;
		} catch (Exception $e) {		   
			//
		}
			
		$stmt = "
		SELECT 
		a.order_id,
		a.client_id,
		a.merchant_id,
		b.restaurant_name,
		b.contact_email as merchant_email,
		b.contact_phone as merchant_phone,
		concat(c.first_name,' ',c.last_name) as customer_name
		
		FROM
		{{order}} a 
		left join {{merchant}} b
		on
		a.merchant_id = b.merchant_id
		
		left join {{client}} c
		on
		a.client_id = c.client_id
		LIMIT 0,1
		";		
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){			
			$params=array(				
			  'merchant_id'=>$res['merchant_id'],
			  'branch_code'=>$this->data['branch_code'],
			  'date_of_deposit'=>$this->data['date_of_deposit'],
			  'time_of_deposit'=>$this->data['time_of_deposit'],
			  'amount'=>(float)$this->data['amount'],
			  'scanphoto'=>isset($this->data['photo'])?$this->data['photo']:'',
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			  'transaction_type'=>"item_purchase",
			  'client_id'=>(integer)$res['client_id'],
			  'order_id'=>(integer)$order_id
			);			
			
			if ($this->insertData("{{bank_deposit}}",$params)){
				$this->code=1;
				$this->msg = t("Thank you. Your information has been receive please wait 1 or 2 days to verify your payment.");
				try {
					
					$resp = FunctionsV3::getNotificationTemplate('offline_new_bank_deposit',Yii::app()->language);					
					$email_content = $resp['email_content'];
					$email_subject = $resp['email_subject'];
					$sms_content = $resp['sms_content'];
					
					$data = array(
					  'merchant_name'=>$res['restaurant_name'],
					  'customer_name'=>$res['customer_name'],
					  'amount'=>normalPrettyPrice((float)$this->data['amount']),
					  'sitename'=>getOptionA('website_title'),
					  'siteurl'=>websiteUrl()
					);					
					$email_content = FunctionsV3::replaceTags($email_content,$data);
					$email_subject = FunctionsV3::replaceTags($email_subject,$data);
					$sms_content = FunctionsV3::replaceTags($sms_content,$data);
										
					
					if(!empty($res['merchant_email'])){
					    sendEmail($res['merchant_email'],'',$email_subject,$email_content);
					}
										
					if($resp['sms_enabled']==1 && !empty($res['merchant_phone'])){						
						Yii::app()->functions->sendSMS($res['merchant_phone'],$sms_content);
					}				
					
				} catch (Exception $e) {
					$this->msg = $e->getMessage();
				}
				
			} else $this->msg=t("Something went wrong during processing your request. Please try again later.");
		} else $this->msg=t("ERROR: Something went wrong");
	}
	
	public function BankDepositListMerchant()
	{
		$mtid=Yii::app()->functions->getMerchantID();
		$slug=$this->data['slug'];
		$stmt="SELECT a.*,
		(
		select restaurant_name from
		{{merchant}}
		where merchant_id=a.merchant_id
		) as merchant_name,
		
		(
		select concat(first_name,' ',last_name)
		from
		{{client}}
		where
		client_id=a.client_id
		) as client_name
		
		 FROM		 
		{{bank_deposit}} a
		
		WHERE
		merchant_id=".Yii::app()->functions->q($mtid)."
		AND
		transaction_type='item_purchase'
		ORDER BY id DESC
		";
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    
				$action="<div class=\"options\">
	    		<a href=\"$slug/Do/Add/?id=$val[cuisine_id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[cuisine_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";		   	   
				
			   /*$date=Yii::app()->functions->prettyDate($val['date_created']);
			   $date=Yii::app()->functions->translateDate($date);*/
			   $date=FormatDateTime($val['date_created']);
			   
			   if (!empty($val['scanphoto'])){
			      $img=Yii::app()->request->baseUrl."/upload/$val[scanphoto]";
			      $scanphoto="<a href=\"$img\" target=\"_blank\">";
	    		  $scanphoto.="<img class=\"uk-thumbnail uk-thumbnail-mini\" src=\"$img\" >";	
	    		  $scanphoto.="</a>";
			   } else $scanphoto='';
			   
		   	   $feed_data['aaData'][]=array(
		   	      $val['id'],
		   	      ucwords($val['client_name']),		   	      
		   	      $val['branch_code'],
		   	      FormatDateTime($val['date_of_deposit'],false),
		   	      $val['time_of_deposit'],
		   	      Yii::app()->functions->standardPrettyFormat($val['amount']),
		   	      $scanphoto,
		   	      $date
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}

	public function testSms()
	{
		require_once 'test-sms.php';
		die();
	}
	
	public function SendTestSMS()
	{		
		if (isset($this->data['mobile'])){
			$text="This is a sms test message";
			if ( $res=Yii::app()->functions->sendSMS($this->data['mobile'],$text) ){								
				if ( $res['msg']=="process"){
					$this->code=1;
					$this->msg=t("Successful");
					
					$params=array(
				      'merchant_id'=>'999999999',
				      'broadcast_id'=>'999999999',
				      'client_id'=>0,
				      'client_name'=>'',
				      'contact_phone'=>$this->data['mobile'],
				      'sms_message'=>$text,
				      'status'=>'process',
				      'gateway_response'=>$res['raw'],
				      'date_created'=>FunctionsV3::dateNow(),
				      'date_executed'=>FunctionsV3::dateNow(),
				      'ip_address'=>$_SERVER['REMOTE_ADDR'],
				      'gateway'=>$res['sms_provider']
				    );		
				    				    
				    /*$db= new DbExt;
				    $db->insertData("{{sms_broadcast_details}}",$params);
				    unset($db);*/
			
				} else $this->msg=$res['msg'];
			} else $this->msg=t("Failed");
		} else $this->msg=t("Mobile number is required");				
	}
	
	public function verifyMobileCode()
	{		
		if( $res=Yii::app()->functions->getClientInfo($this->data['client_id'])){
			if ( $this->data['code']==$res['mobile_verification_code']){
				$this->code=1;
				$this->msg=t("Successful");
				
				$params=array( 
				  'status'=>"active",
				  'mobile_verification_date'=>FunctionsV3::dateNow()
				);
				$this->updateData("{{client}}",$params,'client_id',$res['client_id']);
				
				Yii::app()->functions->clientAutoLogin($res['email_address'],$res['password'],$res['password']);
				
			} else $this->msg=t("Verification code is invalid");		
		} else $this->msg=t("Sorry but we cannot find your records");
	}
	
	public function resendMobileCode()
	{
		$date_now=date('Y-m-d g:i:s a');				
		if ( isset($_SESSION['resend_code'])){			
			$date_diff=Yii::app()->functions->dateDifference($_SESSION['resend_code'],$date_now);			
			if (is_array($date_diff) && count($date_diff)>=1){
				if ( $date_diff['minutes']<5){
					$remaining=5-$date_diff['minutes'];
					$this->msg=t("Please wait for a minute to receive your code");					
					$this->msg.=" (".$remaining ." "."minutes".")";
					return ;
				}			
			}		
		}	
				
		if ( isset($this->data['id'])){
			if( $res=Yii::app()->functions->getClientInfo($this->data['id'])){				
				$code=$res['mobile_verification_code'];
				$_SESSION['resend_code']=$date_now;				
				FunctionsV3::sendCustomerSMSVerification($res['contact_phone'],$code);
				$this->code=1;
				$this->msg=t("Your verification code has been sent to")." ".$res['contact_phone'];
			} else $this->msg=t("Sorry but we cannot find your records");
		} else $this->msg=t("Missing id");
	}
		
	public function getAllMerchantCoordinates()
	{		
		$admin_country_set=Yii::app()->functions->getOptionAdmin('admin_country_set');
		
		$this->qry("SET SQL_BIG_SELECTS=1");
		
		$stmt="
		SELECT merchant_id,
		restaurant_slug,restaurant_name,latitude,lontitude,
		concat(street,' ',city,' ',state,' ',post_code) as address
		FROM
		{{view_merchant}}
		WHERE
		status in ('active')
		AND latitude <>''
		AND lontitude <>''		
		ORDER BY latitude ASC
		LIMIT 0,3000
		";
		if ( $res=$this->rst($stmt)){
			$list='';
			$x=0;
			foreach ($res as $val) {					
				$photo=Yii::app()->functions->getOption("merchant_photo",$val['merchant_id']);
				if (empty($photo)){
					$photo='thumbnail-medium.png';
				}
				
				$logo='<a href="'.websiteUrl()."/store/menu/merchant/".$val['restaurant_slug'].'">';
                $logo.='<img title="" alt="" src="'.uploadURL()."/$photo".'" class="uk-thumbnail uk-thumbnail-mini">';
				$logo.='</a>';
				
							
				$list[]=array(
				  $val['restaurant_name'],
				  $val['latitude'],
				  $val['lontitude'],
				  $x,
				  $val['address'],
				  $val['restaurant_slug'],
				  $logo,
				);
				$x++;
			}			
			
			$lng='';
			$lng='';
			$country=Yii::app()->functions->getAdminCountrySet();			
			if( $lat_res=Yii::app()->functions->geodecodeAddress($country)){				
				$lat=$lat_res['lat'];
				$lng=$lat_res['long'];
			}		
			
			$this->code=1;
			$this->msg=array(
			  'lat'=>$lat,
			  'lng'=>$lng,
			);
			$this->details=$list;
		} else $this->msg=t("0 restaurant found");
	}
	
	public function findGeo()
	{
		$home_search_unit_type=Yii::app()->functions->getOptionAdmin('home_search_unit_type');
		$home_search_radius=Yii::app()->functions->getOptionAdmin('home_search_radius');
		
		if(!is_numeric($home_search_radius)){
			$home_search_radius=15;
		}	
				
		$distance_exp=3959;
		if ($home_search_unit_type=="km"){
			$distance_exp=6371;
		}		
		
		$lat=isset($this->data['lat'])?$this->data['lat']:0;
		$long=isset($this->data['lng'])?$this->data['lng']:0;
						
		if ($lat_res=Yii::app()->functions->geodecodeAddress($this->data['geo_address'])){			
			$lat=$lat_res['lat'];
			$long=$lat_res['long'];
		}		
						
		if (isset($this->data['geo_address'])){
			$stmt="
			SELECT 
			SQL_CALC_FOUND_ROWS a.*, ( $distance_exp * acos( cos( radians($lat) ) * cos( radians( latitude ) ) 
			* cos( radians( lontitude ) - radians($long) ) 
			+ sin( radians($lat) ) * sin( radians( latitude ) ) ) ) 
			AS distance								
			
			FROM {{view_merchant}} a 
			HAVING distance < $home_search_radius	
			AND status='active' AND is_ready='2' 		
			";		
			//dump($stmt);
			if ( $res=$this->rst($stmt)){
				$list='';
			    $x=0;
				foreach ($res as $val) {
					$address=$val['street']." ".$val['city']." ".$val['state']." ".$val['post_code'];
					
					
					$photo=Yii::app()->functions->getOption("merchant_photo",$val['merchant_id']);
				    if (empty($photo)){
					   $photo='thumbnail-medium.png';
				    }
				
				    $logo='<a href="'.websiteUrl()."/store/menu/merchant/".$val['restaurant_slug'].'">';
                    $logo.='<img title="" alt="" src="'.uploadURL()."/$photo".'" class="uk-thumbnail uk-thumbnail-mini">';
				    $logo.='</a>';
					
					$list[]=array(
					  $val['restaurant_name'],
					  $val['latitude'],
					  $val['lontitude'],
					  $x,
					  $address,
					  $val['restaurant_slug'],
					  $logo,
					);
				    $x++;
				}				
				$this->code=1;
			    $this->msg=array(
			      'lat'=>$lat,
			      'lng'=>$long
			    );
			    $this->details=$list;
			} else $this->msg=t("No results");
		} else $this->msg=t("Missing parameters");
	}
	
	public function dishList()
	{
				
		
		$slug=$this->data['slug'];
        $stmt="
		SELECT * FROM
		{{dishes}}
		WHERE
		status in ('published','publish')
		ORDER BY dish_id  DESC
		";
		$connection=Yii::app()->db;
	    $rows=$connection->createCommand($stmt)->queryAll();     	    
	    if (is_array($rows) && count($rows)>=1){
	    	foreach ($rows as $val) {    	     	    		
	    		$chk="<input type=\"checkbox\" name=\"row[]\" value=\"$val[dish_id]\" class=\"chk_child\" >";   		
	    		/*$option="<div class=\"options\">
	    		<a href=\"$slug/id/$val[dish_id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[dish_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";*/
	    		
	    		$slug=Yii::app()->createUrl('/admin/dishes',array(
	    		  'Do'=>"Add",
	    		  'id'=>$val['dish_id']
	    		));
	    		
	    		$option="<div class=\"options\">
	    		<a href=\"$slug\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[dish_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";
	    		
	    		$date=FormatDateTime($val['date_created']);
	    		
	    		$feed_data['aaData'][]=array(
	    		  $val['dish_id'],
	    		  $val['dish_name'].$option,
	    		  '<img class="uk-thumbnail uk-thumbnail-mini" src="'.uploadURL()."/".$val['photo'].'">',
	    		  //$date."<div>".$val['status']."</div>"
	    		  "$date<br/><span class=\"tag ".$val['status']."\">".t($val['status'])."</span>",
	    		);
	    	}
	    	$this->otableOutput($feed_data);
	    }     	    
	    $this->otableNodata();	
	}
	
	public function addDish()
	{		
		
	   $p = new CHtmlPurifier();
		
	   $Validator=new Validator;
		$req=array(
		  'dish_name'=>Yii::t("default","Dish name is required"),
		  'spicydish'=>t("Icon is required")
		);		
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			$params=array(
			  'dish_name'=> $p->purify($this->data['dish_name']) ,
			  'photo'=>$this->data['spicydish'],
			  'status'=>$p->purify($this->data['status']),
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR']
			);			
		   if (empty($this->data['id'])){	
		    	if ( $this->insertData("{{dishes}}",$params)){
		    		    $this->details=Yii::app()->db->getLastInsertID();
			    		$this->code=1;
			    		$this->msg=Yii::t("default","Successful");				    		
			    	}
			    } else {		    	
			    	unset($params['date_created']);
					$params['date_modified']=FunctionsV3::dateNow();
					
					$filename_to_delete='';
					if($old_data=Yii::app()->functions->GetDish($this->data['id'])){
						if($old_data['photo']!=$this->data['spicydish']){
						   $filename_to_delete=$old_data['photo'];			
						}			
					}
					
					$res = $this->updateData('{{dishes}}' , $params ,'dish_id',$this->data['id']);
					if ($res){
						$this->code=1;
		                $this->msg=Yii::t("default",'Dish updated');  
		                
		                /*DELETE IMAGE*/
		                if(!empty($filename_to_delete)){
		                  FunctionsV3::deleteUploadedFile($filename_to_delete);
		                }
					    
				} else $this->msg=Yii::t("default","ERROR: cannot update");
		    }	
		} else $this->msg=$Validator->getErrorAsHTML();	
	}
	
	public function addVoucherNew()
	{					
		$functionsk=new FunctionsK();		
		
		$merchant_id=Yii::app()->functions->getMerchantID();
		$params=array(
		  'voucher_name'=>$this->data['voucher_name'],
		  'voucher_type'=>$this->data['voucher_type'],
		  'amount'=>$this->data['amount'],
		  'expiration'=>$this->data['expiration'],
		  'status'=>$this->data['status'],
		  'date_created'=>FunctionsV3::dateNow(),
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'merchant_id'=>$merchant_id
		);
				
		if (isset($this->data['voucher_owner'])){
			unset($params['merchant_id']);
			$params['voucher_owner']=$this->data['voucher_owner'];
		}
		
		if (isset($this->data['joining_merchant'])){
			$params['joining_merchant']=json_encode($this->data['joining_merchant']);
		} else $params['joining_merchant']='';
		
		if (isset($this->data['used_once'])){
			$params['used_once']=$this->data['used_once'];
		} else 	$params['used_once']='0';
							
		if (!empty($this->data['id'])){
			
			if ( $functionsk->checkIFVoucherCodeExisting($this->data['voucher_name'],$this->data['id'])){
				$this->msg=t("Sorry but voucher name already exist!");
				return;
			}		
			
			$params['date_modified']=FunctionsV3::dateNow();
			unset($params['date_created']);
			if ( $this->updateData("{{voucher_new}}",$params,'voucher_id',$this->data['id'])){
				$this->code=1;
	    		$this->msg=t("Successful");
			} else $this->msg=t("Failed cannot update records");	    	
		} else {
			if ( $functionsk->checkIFVoucherCodeExists($this->data['voucher_name'])){
				$this->msg=t("Sorry but voucher name already exist!");
				return;
			}		
	        if ( $this->insertData('{{voucher_new}}',$params)){
	        	$this->details=Yii::app()->db->getLastInsertID();
	    		$this->code=1;
	    		$this->msg=Yii::t("default","Successful");		 
	        } else $this->msg=t("ERROR: Something went wrong");		
		}
	}
	
	public function VoucherListNew()
		{
			$slug=$this->data['slug'];
						
			$and='';
			if (isset($this->data['voucher_owner'])){
				$stmt="
				SELECT a.*,
				(
				select count(*) 
				from
				{{order}}
				where
				voucher_code=a.voucher_name			
				) as total_used
				FROM
				{{voucher_new}} a
				WHERE
				voucher_owner=".Yii::app()->db->quoteValue($this->data['voucher_owner'])."
				ORDER BY voucher_id DESC
				";	   		    		    	
			} else {
				$merchant_id=Yii::app()->functions->getMerchantID();		    
			    $stmt="
				SELECT a.*,
				(
				select count(*) 
				from
				{{order}}
				where
				voucher_code=a.voucher_name			
				) as total_used
				FROM
				{{voucher_new}} a
				WHERE
				merchant_id=".Yii::app()->db->quoteValue($merchant_id)."
				ORDER BY voucher_id DESC
				";	   		    		    	
			}	
						
			$connection=Yii::app()->db;
    	    $rows=$connection->createCommand($stmt)->queryAll();     	        	        	        	    
    	    //dump($rows);
    	    if (is_array($rows) && count($rows)>=1){
    	    	foreach ($rows as $val) {    	    	    		
    	    		$chk="<input type=\"checkbox\" name=\"row[]\" value=\"$val[voucher_id]\" class=\"chk_child\" >";   		
    	    		$action="<div class=\"options\">
    	    		<a href=\"$slug/id/$val[voucher_id]\" >".Yii::t("default","Edit")."</a>
    	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[voucher_id]\" >".Yii::t("default","Delete")."</a>
    	    		</div>";
    	    		
    	    		
    	    		if ( $val['total_used']>0){
    	    			$used='<a class="voucher-details" href="javascript:;" data-id="'.$val['voucher_name'].'">'.
    	    			$val['total_used'].'</a>';
    	    		} else $used='';  	    	 
    	    		
    	    		if ($val['voucher_type']=="percentage"){
    	    			$amt=normalPrettyPrice($val['amount']). " %";
    	    		} else $amt=normalPrettyPrice($val['amount']);    		
    	    		    	    		
    	    		$date=FormatDateTime($val['date_created']);
    	    		
    	    		$feed_data['aaData'][]=array(
    	    		  $val['voucher_id'],
    	    		  $val['voucher_name'].$action,    	    		  
    	    		  t($val['voucher_type']),
    	    		  $amt,    	    		
    	    		  FormatDateTime($val['expiration'],false),
    	    		  $used,
    	    		  "$date<br/><span class=\"tag ".$val['status']."\">".t($val['status'])."</span>"
    	    		  //$date."<div>".Yii::t("default",$val['status'])."</div>"
    	    		);
    	    	}
    	    	$this->otableOutput($feed_data);
    	    }     	    
    	    $this->otableNodata();	
     }	
     
     public function viewVoucherDetails()
     {
     	require_once "voucher-details.php";
     }
     
     public function addressBook()
     {     	 	    
     	FunctionsV3::isUserLogin();
     	     	
		$stmt="SELECT id,location_name,country_code,as_default,
		concat(street,' ',city,' ',state,' ',zipcode) as address		
		FROM
		{{address_book}}		
		WHERE
		client_id = ".FunctionsV3::q(Yii::app()->functions->getClientId())."
		ORDER BY id DESC
		";						
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    	
		   	    $slug=Yii::app()->createUrl("store/profile/",array(
		   	     'tab'=>2,
		   	     'do'=>"add",
		   	     'id'=>$val['id']
		   	    ));		   	    
				$action="<div class=\"options\">
	    		<a href=\"$slug\" ><i class=\"ion-ios-compose-outline\"></i></a>
	    		<a href=\"javascript:;\" class=\"delete_addressbook\" data-id=\"$val[id]\" ><i class=\"ion-ios-trash\"></i></a>
	    		</div>";		   	   
		   	   $feed_data['aaData'][]=array(
		   	      $val['address'].$action,
		   	      $val['location_name'],
		   	      $val['as_default']==2?'<i class="fa fa-check"></i>':'<i class="fa fa-times"></i>'
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();			
     }
     
     public function addAddressBook()
     {     	
     	
     	$params=array(
     	  'client_id'=>Yii::app()->functions->getClientId(),
     	  'street'=> ($this->data['street']) ,
     	  'city'=>($this->data['city']),
     	  'state'=>($this->data['state']),
     	  'zipcode'=>($this->data['zipcode']),
     	  'location_name'=>isset($this->data['location_name'])?($this->data['location_name']):'',
     	  'as_default'=>isset($this->data['as_default'])?$this->data['as_default']:1,
     	  'date_created'=>FunctionsV3::dateNow(),
     	  'ip_address'=>$_SERVER['REMOTE_ADDR'],
     	  'country_code'=>isset($this->data['country_code'])?$this->data['country_code']:'',
     	  'latitude'=>isset($this->data['latitude'])?$this->data['latitude']:'',
     	  'longitude'=>isset($this->data['longitude'])?$this->data['longitude']:'',
     	);     	
     	
     	if (!isset($this->data['as_default'])){
     		$this->data['as_default']='';
     	}
     	     	
     	if ( $this->data['as_default']==2){
     		$sql_up="UPDATE {{address_book}}
     		SET as_default='1' 	     		
     		WHERE
     		client_id= ".FunctionsV3::q(Yii::app()->functions->getClientId())."
     		";
     		$this->qry($sql_up);
     	}     
     	
     	$params = FunctionsV3::purifyData($params);
     	
     	if ( isset($this->data['id'])){
     		unset($params['date_created']);
     		$params['date_modified']=FunctionsV3::dateNow();
     		if ( $this->updateData("{{address_book}}",$params,'id',$this->data['id'])){
     			$this->code=1;
     			$this->msg=Yii::t("default","Successful");		 
     		} else $this->msg=t("ERROR: Something went wrong");	
     	} else {
     	    if ( $this->insertData('{{address_book}}',$params)){
	        	$id=Yii::app()->db->getLastInsertID();
	        	$this->details=Yii::app()->createUrl('store/profile',array(
	        	  'tab'=>2,
	        	  'do'=>'add',
	        	  'id'=>$id
	        	));
	    		$this->code=1;
	    		$this->msg=Yii::t("default","Successful");		 
	        } else $this->msg=t("ERROR: Something went wrong");		
     	}
     }
     
	public function adminCustomerReviews()
	{		
		$slug=$this->data['slug'];
		$stmt="SELECT a.*,
		(
		select concat(first_name,' ',last_name)
		from {{client}}
		where
		client_id=a.client_id
		) client_name,
		
		(
		select restaurant_name
		from
		{{merchant}}
		where
		merchant_id=a.merchant_id
		) as merchant_name
		
		 FROM
		{{review}} a			
		ORDER BY id DESC
		";						
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	  
		   	$class='';  			   	    
				$action="<div class=\"options\">
	    		<a href=\"$slug/Do/Add/?id=$val[id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";		   	  
				
				if ( $this->data['currentController']=="admin"){						
				} else {					
					if ( Yii::app()->functions->getOptionAdmin('merchant_can_edit_reviews')=="yes"){
						$action='';
					}
				}
				
			   /*$date=Yii::app()->functions->prettyDate($val['date_created']);	
			   $date=Yii::app()->functions->translateDate($date); */
			   $date=FormatDateTime($val['date_created']);
			   
		   	   $feed_data['aaData'][]=array(
		   	     $val['id'],
		   	      !empty($val['merchant_name'])?stripslashes($val['merchant_name']).$action:stripslashes($val['reply_from']).$action,
		   	      !empty($val['client_name'])?stripslashes($val['client_name']):'-',
		   	      $val['review'],
		   	      /*$val['order_id'],*/
		   	      $val['rating'],
		   	      //$date."<br/><div class=\"uk-badge $class\">".strtoupper(Yii::t("default",$val['status']))."</div>"
		   	      "$date<br/><span class=\"tag ".$val['status']."\">".t($val['status'])."</span>",
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();	
	}     
	
	public function AdminUpdateCustomerReviews()
	{
		$db_ext=new DbExt;			
		if (isset($this->data['id'])){
			$params=array(
			  'review'=>$this->data['review'],
			  'status'=>$this->data['status'],
			  'rating'=>$this->data['rating'],
			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			  'date_modified'=>FunctionsV3::dateNow()
			);
			if ($db_ext->updateData("{{review}}",$params,'id',$this->data['id'])){
				$this->code=1;
				$this->msg=Yii::t("default","Successful");
			} else $this->msg=Yii::t("default","ERROR: cannot update");
		} else $this->msg="";		
	}	
	
	public function clearCart()
	{
		unset($_SESSION['kr_item']);
		$this->code=1;
		$this->msg="OK";
	}
	
	public function UpdateItemAvailable()
	{		
		if (isset($this->data['item_id'])){
			$params=array('not_available'=>$this->data['checked']==1?2:1);
			$db_ext=new DbExt;
			if ( $db_ext->updateData("{{item}}",$params,'item_id',$this->data['item_id'])){
				$this->code=1;
				$this->msg=t("Successful");
			} else $this->msg=t("ERROR: cannot update records.");
		} else $this->msg=t("Missing parameters");
	}
	
	public function cardPaymentSettings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_enabled_card",
    	isset($this->data['admin_enabled_card'])?$this->data['admin_enabled_card']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("offline_cc_encryption_key",
    	isset($this->data['offline_cc_encryption_key'])?$this->data['offline_cc_encryption_key']:'');
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
	
	public function switchMerchantAccount()
	{		
		if (!isset($this->data['iagree'])){
			$this->msg=t("You must agree to switch your account to commission");
			return ;
		}			
		$params=array(
		  'is_commission'=>2,
		  'percent_commision'=>getOptionA('admin_commision_percent'),
		  'commision_type'=>getOptionA('admin_commision_type'),
		  'merchant_type'=>$this->data['merchant_type']
		);	
				
		$merchant_id=Yii::app()->functions->getMerchantID();		
		$db_ext=new DbExt;
		if ( $db_ext->updateData("{{merchant}}",$params,'merchant_id',$merchant_id)){
			$this->code=1; 
			$this->msg=t("You have successfully switch your account to commission");
			$this->msg.="<br/>";
			$this->msg.=t("Please not you might have to relogin again to see the balance");
			$this->details=websiteUrl()."/merchant/dashboard";
		} else $this->msg=t("ERROR: cannot update");
	}

	public function sendUpdateOrderEmail()
	{
		if (empty($this->data['email_order_change_msg'])){
			$this->msg=t("Email content is required");
			return false;
		}	
		if (empty($this->data['subject'])){
			$this->msg=t("Subject is required");
			return false;
		}	
		if ($res=Yii::app()->functions->getOrder($this->data['order_id'])){
			$client_email=$res['email_address'];			
			$content=$this->data['email_order_change_msg'];
			$subject=$this->data['subject'];
			if (Yii::app()->functions->sendEmail($client_email,'',$subject,$content)){
				$this->code=1;
				$this->msg=t("Email sent");
			} else $this->msg=t('ERROR: Cannot sent email.');
		} else $this->msg=t("Sory but we cannot find the order information");
	}
	
	public function viewOrderHistory()
	{		
		?>
		<div class="view-receipt-pop">
	      <h3><?php echo Yii::t("default",'History')?></h3>
	    
	      <?php if ( $resh=FunctionsK::orderHistory($this->data['id'])):?>                    
               <table class="uk-table uk-table-hover">
                 <thead>
                   <tr>
                    <th class="uk-text-muted"><?php echo t("Date/Time")?></th>
                    <th class="uk-text-muted"><?php echo t("Status")?></th>
                    <th class="uk-text-muted"><?php echo t("Remarks")?></th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php foreach ($resh as $valh):?>                   
                   <?php 
		           $remarks = $valh['remarks'];
		           if(!empty($valh['remarks2']) && !empty($valh['remarks_args']) ){
		           	   $remarks_args = json_decode($valh['remarks_args'],true);
		           	   if(is_array($remarks_args) && count($remarks_args)>=1){
		           	      $remarks = Yii::t("driver",$valh['remarks2'],$remarks_args);            	   
		           	   }
		           }
		           ?>                                      
                   <tr style="font-size:12px;">
                     <td><?php                       
                      echo FormatDateTime($valh['date_created'],true);
                      ?></td>
                     <td><?php echo t($valh['status'])?></td>
                     <td><?php echo $remarks?></td>
                   </tr>
                   <?php endforeach;?>
                 </tbody>
               </table> 
          <?php else :?>                
            <p class="uk-text-danger order-order-history show-history-<?php echo $val['order_id']?>">
              <?php echo t("No history found")?>
            </p>
          <?php endif;?>	 
	    	 
	    </div>
		<?php
		Yii::app()->end();
	}
	
	public function sendOrderSMSCode()
	{		
		$validator=new Validator;
		$req=array(
		  'session'=>t("Session is missing"),
		  'mobile'=>t("Mobile number is required"),
		  'mtid'=>t("Merchant id is missing")
		);
		
		if (empty($this->data['mtid'])){
			$this->msg=t("Merchant id is missing");
			return ;
		}	
		
		$waiting_time_define=getOption($this->data['mtid'],'order_sms_code_waiting');	
		if (!is_numeric($waiting_time_define)){
			$waiting_time_define=5;
		}			
		if (isset($_SESSION['request_order_sms'])){			
			$time_1=date('Y-m-d g:i:s a');			
			$time_2=$_SESSION['request_order_sms'];			
			if (!empty($time_2)){			
				$time_diff=Yii::app()->functions->dateDifference($time_2,$time_1);				
				if (is_array($time_diff) && count($time_diff)>=1){
					if ($time_diff['days']==0 && $time_diff['hours']==0){
						if ($time_diff['minutes']<$waiting_time_define){
							$waiting_time=$waiting_time_define-$time_diff['minutes'];
							$this->msg=t("Spam protection. you cannot request another order sms code in less than")." ".$waiting_time_define." ".t("Minutes");
							$this->msg.="<br/><br/>";
							$this->msg.=t("Please wait in")." ".$waiting_time." ".t("Minutes")."";
							$this->details=$time_diff;
							return ;
						}				
					} else {					
						$this->msg=t("Spam protection. you cannot request another order sms code in less than")." ".$waiting_time_define." ".t("Minutes");
						$waiting_time=$time_diff['hours']." ".t("hour")." ".t("and")." ".$time_diff['minutes']." ".t("Minutes");
						$this->msg.="<br/><br/>";
						$this->msg.=t("Please wait in")." ".$waiting_time;
						$this->details=$time_diff;
						return ;
					}		
				}			
			}
		}		
		
		$validator->required($req,$this->data);
		if ($validator->validate()){
						
			$client_id=Yii::app()->functions->getClientId();			
			if ( $client_info=Yii::app()->functions->getClientInfo($client_id)){
				$this->data['mobile']=$client_info['contact_phone'];
			}
											
			$sms_balance=Yii::app()->functions->getMerchantSMSCredit($this->data['mtid']);			
			if ( $sms_balance>=1){
				$code=FunctionsK::generateSMSOrderCode($this->data['mobile']);
				$sms_msg=t("Your order sms code is")." ".$code;
				if ( $resp=Yii::app()->functions->sendSMS($this->data['mobile'],$sms_msg)){				    
				    if ($resp['msg']=="process"){
				    	$this->code=1;
				    	$this->msg=t("Your order sms code has been sent to")." ".$this->data['mobile'];
				    	
				    	$this->data['mobile']=str_replace("+","",$this->data['mobile']);
				    	$params=array(
				    	  'mobile'=>trim($this->data['mobile']),
				    	  'code'=>$code,
				    	  'session'=>$this->data['session'],
				    	  'date_created'=>FunctionsV3::dateNow(),
				    	  'ip_address'=>$_SERVER['REMOTE_ADDR']
				    	);
				    	$this->insertData("{{order_sms}}",$params);
				    	$_SESSION['request_order_sms']=date('Y-m-d g:i:s a');
				    					    								    		    
                        $params=array(
			        	  'merchant_id'=>$this->data['mtid'],
			        	  'broadcast_id'=>"999999999",			        	  
			        	  'contact_phone'=>$this->data['mobile'],
			        	  'sms_message'=>$sms_msg,
			        	  'status'=>$resp['msg'],
			        	  'gateway_response'=>$resp['raw'],
			        	  'date_created'=>FunctionsV3::dateNow(),
			        	  'date_executed'=>FunctionsV3::dateNow(),
			        	  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			        	  'gateway'=>$resp['sms_provider']
			        	);	  		        	  
			        	$this->insertData("{{sms_broadcast_details}}",$params);	   
			        	
			        	FunctionsV3::fastRequest(FunctionsV3::getHostURL().Yii::app()->createUrl("cron/processsms"));
				    	
				    } else $this->msg=t("Sorry but we cannot sms code this time")." ".$resp['msg'];
				} else $this->msg=t("Sorry but we cannot sms code this time");
			} else $this->msg=t("Sorry but this merchant does not have enought sms credit to send sms");		
		} else $this->msg=$validator->getErrorAsHTML();	
	}
	
	public function ZipCodeList()
	{		
	    $slug=$this->data['slug'];
		$stmt="SELECT * FROM
		{{zipcode}}
		ORDER BY zipcode_id DESC
		";
		if ($res=$this->rst($stmt)){
		   foreach ($res as $val) {				   	    			   	    
				$action="<div class=\"options\">
	    		<a href=\"$slug/Do/Add/?id=$val[zipcode_id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[zipcode_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";		   	   
							   
			   $date=FormatDateTime($val['date_created']);
		   	   $feed_data['aaData'][]=array(
		   	      $val['zipcode_id'].$action,
		   	      $val['zipcode'],
		   	      Yii::app()->functions->countryCodeToFull($val['country_code']),
		   	      $val['stree_name'],
		   	      $val['city'],
		   	      $val['area'],
		   	      Yii::app()->functions->translateDate($date)."<br/><div class=\"uk-badge\">".$val['status']."</div>"
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();		
	}
	
	public function addZipCode()
	{
		$Validator=new Validator;
		$req=array(
		  'zipcode'=>Yii::t("default","post code is required"),
		  'country_code'=>t("country is required"),
		  'city'=>t("city is required"),
		  'area'=>t("area is required"),
		);				
		$Validator->required($req,$this->data);
		if ($Validator->validate()){
			$params=array(
			  'zipcode'=>$this->data['zipcode'],
			  'country_code'=>$this->data['country_code'],
			  'city'=>$this->data['city'],
			  'area'=>$this->data['area'],
			  'status'=>$this->data['status'],			  
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			  'stree_name'=>isset($this->data['stree_name'])?$this->data['stree_name']:''
			);
		   if (empty($this->data['id'])){	
		    	if ( $this->insertData("{{zipcode}}",$params)){
		    		    $this->details=Yii::app()->db->getLastInsertID();
			    		$this->code=1;
			    		$this->msg=Yii::t("default","Successful");				    		
			    	}
			    } else {		    	
			    	unset($params['date_created']);
					$params['date_modified']=FunctionsV3::dateNow();		
					$res = $this->updateData('{{zipcode}}' , $params ,'zipcode_id',$this->data['id']);
					if ($res){
						$this->code=1;
		                $this->msg=Yii::t("default",'zipcode updated');  
				} else $this->msg=Yii::t("default","ERROR: cannot update");
		    }	
		} else $this->msg=$Validator->getErrorAsHTML();		
	}
	
	public function getArea()
	{		
		if (isset($this->data['city'])){
		   $stmt="
		   SELECT DISTINCT area
		   FROM
		   {{zipcode}}
		   WHERE
		   city =".q($this->data['city'])."
		   ORDER BY area ASC
		   ";
		   if ( $res=$this->rst($stmt)){
		   	   $this->code=1;
		   	   $this->msg="OK";
		   	   $this->details=$res;
		   } else $this->msg=t("No results");
		} else $this->msg=t("missing city parameters");
	}
	
	public function verifyEmailCode()
	{
		$client_id=isset($this->data['client_id'])?$this->data['client_id']:'';
		if( $res=Yii::app()->functions->getClientInfo( $client_id )){	
			
		    if ($res['email_verification_code']==trim($this->data['code'])){
		    	$this->code=1;
		    	$this->msg=t("Successful");
		    	
		    	$params=array( 
				  'status'=>"active",
				  'last_login'=>FunctionsV3::dateNow()
				);
				$this->updateData("{{client}}",$params,'client_id',$res['client_id']);
				
				$verification=Yii::app()->functions->getOptionAdmin("website_enabled_mobile_verification");	
				$email_verification=getOptionA('theme_enabled_email_verification');								
				if ($verification=="yes" || $email_verification==2){
					/*sent welcome email*/	    			
	    			FunctionsV3::sendCustomerWelcomeEmail($res);
				}
				
				Yii::app()->functions->clientAutoLogin($res['email_address'],$res['password'],$res['password']);
				
		    } else $this->msg=t("Verification code is invalid");
		} else $this->msg=t("Sorry but we cannot find your information.");
    }
    
    public function adminBrainTreeSettings()
    {
    
    	Yii::app()->functions->updateOptionAdmin("admin_btr_enabled",
	    isset($this->data['admin_btr_enabled'])?$this->data['admin_btr_enabled']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_btr_mode",
	    isset($this->data['admin_btr_mode'])?$this->data['admin_btr_mode']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("sanbox_brain_mtid",
	    isset($this->data['sanbox_brain_mtid'])?$this->data['sanbox_brain_mtid']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("sanbox_brain_publickey",
	    isset($this->data['sanbox_brain_publickey'])?$this->data['sanbox_brain_publickey']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("sanbox_brain_privateckey",
	    isset($this->data['sanbox_brain_privateckey'])?$this->data['sanbox_brain_privateckey']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("live_brain_mtid",
	    isset($this->data['live_brain_mtid'])?$this->data['live_brain_mtid']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("live_brain_publickey",
	    isset($this->data['live_brain_publickey'])?$this->data['live_brain_publickey']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("live_brain_privateckey",
	    isset($this->data['live_brain_privateckey'])?$this->data['live_brain_privateckey']:'');
	    	
		$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
    		
    }
    
    public function merchantBrainTreeSettings()
    {
    	
		$merchant_id=Yii::app()->functions->getMerchantID();
			    	
		Yii::app()->functions->updateOption("merchant_btr_mode",
		isset($this->data['merchant_btr_mode'])?$this->data['merchant_btr_mode']:'',$merchant_id);
		
		Yii::app()->functions->updateOption("merchant_btr_enabled",
		isset($this->data['merchant_btr_enabled'])?$this->data['merchant_btr_enabled']:'',$merchant_id);
		
		Yii::app()->functions->updateOption("mt_sanbox_brain_mtid",
		isset($this->data['mt_sanbox_brain_mtid'])?$this->data['mt_sanbox_brain_mtid']:'',$merchant_id);
		
		Yii::app()->functions->updateOption("mt_sanbox_brain_publickey",
		isset($this->data['mt_sanbox_brain_publickey'])?$this->data['mt_sanbox_brain_publickey']:'',$merchant_id);
		
		Yii::app()->functions->updateOption("mt_sanbox_brain_privateckey",
		isset($this->data['mt_sanbox_brain_privateckey'])?$this->data['mt_sanbox_brain_privateckey']:'',$merchant_id);
		
		Yii::app()->functions->updateOption("mt_live_brain_mtid",
		isset($this->data['mt_live_brain_mtid'])?$this->data['mt_live_brain_mtid']:'',$merchant_id);
		
		Yii::app()->functions->updateOption("mt_live_brain_publickey",
		isset($this->data['mt_live_brain_publickey'])?$this->data['mt_live_brain_publickey']:'',$merchant_id);
		
		Yii::app()->functions->updateOption("mt_live_brain_privateckey",
		isset($this->data['mt_live_brain_privateckey'])?$this->data['mt_live_brain_privateckey']:'',$merchant_id);
				
		$this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
    }
			
    public function adminRazorSettings()
    {
    	Yii::app()->functions->updateOptionAdmin("admin_razor_key_id_sanbox",
	    isset($this->data['admin_razor_key_id_sanbox'])?$this->data['admin_razor_key_id_sanbox']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_razor_secret_key_sanbox",
	    isset($this->data['admin_razor_secret_key_sanbox'])?$this->data['admin_razor_secret_key_sanbox']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_razor_key_id_live",
	    isset($this->data['admin_razor_key_id_live'])?$this->data['admin_razor_key_id_live']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_razor_secret_key_live",
	    isset($this->data['admin_razor_secret_key_live'])?$this->data['admin_razor_secret_key_live']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_rzr_enabled",
	    isset($this->data['admin_rzr_enabled'])?$this->data['admin_rzr_enabled']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_rzr_mode",
	    isset($this->data['admin_rzr_mode'])?$this->data['admin_rzr_mode']:'');
	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
    }    
    
    public function merchantRazorSettings()
    {
    	    
	    $merchant_id=Yii::app()->functions->getMerchantID();
	    
	    Yii::app()->functions->updateOption("merchant_rzr_mode",
		isset($this->data['merchant_rzr_mode'])?$this->data['merchant_rzr_mode']:'',$merchant_id);
		
		Yii::app()->functions->updateOption("merchant_rzr_enabled",
		isset($this->data['merchant_rzr_enabled'])?$this->data['merchant_rzr_enabled']:'',$merchant_id);
		
		Yii::app()->functions->updateOption("merchant_razor_key_id_sanbox",
		isset($this->data['merchant_razor_key_id_sanbox'])?$this->data['merchant_razor_key_id_sanbox']:'',$merchant_id);
		
		Yii::app()->functions->updateOption("merchant_razor_secret_key_sanbox",
		isset($this->data['merchant_razor_secret_key_sanbox'])?$this->data['merchant_razor_secret_key_sanbox']:'',$merchant_id);
		
		Yii::app()->functions->updateOption("merchant_razor_key_id_live",
		isset($this->data['merchant_razor_key_id_live'])?$this->data['merchant_razor_key_id_live']:'',$merchant_id);
		
		Yii::app()->functions->updateOption("merchant_razor_secret_key_live",
		isset($this->data['merchant_razor_secret_key_live'])?$this->data['merchant_razor_secret_key_live']:'',$merchant_id);
	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
    }        
    
    public function Admincategorylist()
	{		
		$slug=$this->data['slug'];
		$stmt="
		SELECT * FROM
		{{category}}
		WHERE
		merchant_id='999999'
		ORDER BY cat_id DESC
		";
		$connection=Yii::app()->db;
	    $rows=$connection->createCommand($stmt)->queryAll();     	    
	    if (is_array($rows) && count($rows)>=1){
	    	foreach ($rows as $val) {    	 
	    		$chk="<input type=\"checkbox\" name=\"row[]\" value=\"$val[cat_id]\" class=\"chk_child\" >";   		
	    		$option="<div class=\"options\">
	    		<a href=\"$slug/id/$val[cat_id]\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[cat_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";
	    			    		
	    		$date=FormatDateTime($val['date_created']);
	    		
	    		if (!empty($val['photo'])){
	    			$img=Yii::app()->request->baseUrl."/upload/$val[photo]";
	    		    $photo="<img class=\"uk-thumbnail uk-thumbnail-mini\" src=\"$img\" >";	
	    		} else $photo='';
	    		
	    		$feed_data['aaData'][]=array(
	    		  $chk,stripslashes($val['category_name']).$option,
	    		  stripslashes($val['category_description']),
	    		  $photo,
	    		  Widgets::displaySpicyIconNew($val['dish']),
	    		  $date."<div>".Yii::t("default",$val['status'])."</div>"
	    		);
	    	}
	    	$this->otableOutput($feed_data);
	    }     	    
	    $this->otableNodata();
	}    
	
	public function adminAddCategory()
	{
		  $params=array(
			  'category_name'=>addslashes($this->data['category_name']),
			  'category_description'=>addslashes($this->data['category_description']),
			  'photo'=>isset($this->data['photo'])?addslashes($this->data['photo']):'',
			  'status'=>addslashes($this->data['status']),
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			  'merchant_id'=>'999999',			  
			  'spicydish_notes'=>isset($this->data['spicydish_notes'])?$this->data['spicydish_notes']:'',
			  'dish'=>isset($this->data['dish'])?json_encode($this->data['dish']):''			  
			);				
			
			if (isset($this->data['category_name_trans'])){				
				if (okToDecode()){
					$params['category_name_trans']=json_encode($this->data['category_name_trans'],
					JSON_UNESCAPED_UNICODE);
				} else $params['category_name_trans']=json_encode($this->data['category_name_trans']);				
			}
			if (isset($this->data['category_description_trans'])){
				if (okToDecode()){
					$params['category_description_trans']=json_encode($this->data['category_description_trans'],
					JSON_UNESCAPED_UNICODE);
				} else $params['category_description_trans']=json_encode($this->data['category_description_trans']);
			}
																					
			$command = Yii::app()->db->createCommand();
			if (isset($this->data['id']) && is_numeric($this->data['id'])){				
				unset($params['date_created']);
				$params['date_modified']=FunctionsV3::dateNow();
								
				ClassCategory::updateCategoryMerchant($this->data['id'],$params);				
						
				$res = $command->update('{{category}}' , $params , 
				'cat_id=:cat_id' , array(':cat_id'=> addslashes($this->data['id']) ));
				if ($res){
					$this->code=1;
	                $this->msg=Yii::t("default",'Category updated.');  
				} else $this->msg=Yii::t("default","ERROR: cannot update");
			} else {				
				if ($res=$command->insert('{{category}}',$params)){
					$this->details=Yii::app()->db->getLastInsertID();	   
					
					/*special category*/
					ClassCategory::autoAddCategoryToMerchant($this->details);
					             
	                $this->code=1;
	                $this->msg=Yii::t("default",'Category added.');  	                
	            } else $this->msg=Yii::t("default",'ERROR. cannot insert data.');
			}		
	}
	
	public function categorySettings()
	{
		Yii::app()->functions->updateOptionAdmin("merchant_category_disabled",
	    isset($this->data['merchant_category_disabled'])?$this->data['merchant_category_disabled']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("merchant_category_auto_add",
	    isset($this->data['merchant_category_auto_add'])?$this->data['merchant_category_auto_add']:'');
	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function showCCDetails()
	{
		$data=FunctionsV3::getMerchantCCdetails($this->data['id']);
		require_once('cc_details.php');		
		die();
	}
	
	public function getCartCount()
	{
		//$count=count($_SESSION['kr_item']);
		$count = 0;				
		if(is_array($_SESSION['kr_item']) && count( (array) $_SESSION['kr_item'])>=1){
		   foreach ($_SESSION['kr_item'] as $val) {		   	 
		   	 $count+= $val['qty'];
		   }
		}
		if($count>0){
			$this->code=1;
			$this->msg="OK";
			$this->details=$count;
		} else $this->msg="No item";
	}
	
	public function getAdminNewOrder()
	{
		$list='';
		if ( $res=FunctionsV3::getNewOrders('admin')){
			$this->code=1;
	    	$this->msg=count($res);	 
	    	$order_list='';
    		foreach ($res as $val) {	    			
    			$order_list.="<div class=\"new-order-link\">";
    			$order_list.="<a class=\"view-receipt\" data-id=\"$val[order_id]\" 
    			href=\"javascript:;\">".t("Click here to view")." ". t("Reference #") .":". $val['order_id'] . "</a>";	    			
    			$order_list.="<div>";
    		}	    		
    		$this->details=$order_list;
		} else $this->msg= t("No results");
	}
	
	public function updateOrderAdmin()
	{
	    //dump($this->data);
	    $DbExt=new DbExt;	   
	    $date_now=date('Y-m-d');
	    if (isset($this->data['order_id'])){
	    	$order_id=$this->data['order_id'];
	    	
	    	$params=array(
	    	    'status'=>$this->data['status'],
	    	    'date_modified'=>FunctionsV3::dateNow(),
	    	    'admin_viewed'=>1
	    	);	    	
	    	if ($DbExt->updateData('{{order}}',$params,'order_id',$order_id)){
	    		
	    		$this->code=1;
	    		$this->msg=Yii::t("default","Status saved.");
	    		
	    		/*Now we insert the order history*/	    		
				$params_history=array(
				  'order_id'=>$order_id,
				  'status'=>$this->data['status'],
				  'remarks'=>isset($this->data['remarks'])?$this->data['remarks']:'',
				  'date_created'=>FunctionsV3::dateNow(),
				  'ip_address'=>$_SERVER['REMOTE_ADDR']
				);	    			
					
				/*inventory*/	
				if($admin_info=Yii::app()->functions->getAdminInfo()){
					$new_fields=array('update_by_id'=>"update_by_id");
                    if ( FunctionsV3::checkTableFields('order_history',$new_fields)){	
						$params_history['update_by_id']= (integer) $admin_info->admin_id;
						$params_history['update_by_name']="$admin_info->first_name $admin_info->last_name";
                    }
				}				
				$DbExt->insertData("{{order_history}}",$params_history);
								
				/*UPDATE REVIEWS BASED ON STATUS*/
				if (method_exists('FunctionsV3','updateReviews')){
					FunctionsV3::updateReviews($order_id , $this->data['status']);
				}
				
				/*SEND NOTIFICATIONS TO CUSTOMER*/	    				
				FunctionsV3::notifyCustomerOrderStatusChange(
				  $order_id,
				  $this->data['status'],
				  isset($this->data['remarks'])?$this->data['remarks']:''
				);
		    	
				/*DRIVER APP ADD TASK*/
				if (FunctionsV3::hasModuleAddon("driver")){			    	
			    	Yii::app()->setImport(array(			
					  'application.modules.driver.components.*',
				    ));
				    Driver::addToTask($order_id);
				}
				
				/*UPDATE POINTS BASED ON ORDER STATUS*/
				if (FunctionsV3::hasModuleAddon("pointsprogram")){
					if (method_exists('PointsProgram','updateOrderBasedOnStatus')){
					   PointsProgram::updateOrderBasedOnStatus($this->data['status'],$order_id);
					}
					if (method_exists('PointsProgram','udapteReviews')){
					   PointsProgram::udapteReviews($order_id,$this->data['status']);
					}
				}
				
				/*INVENTORY ADDON*/				
				$resp = Yii::app()->db->createCommand()
		          ->select('merchant_id,order_id')
		          ->from('{{order}}')   
		          ->where("order_id=:order_id",array(
		             ':order_id'=>$order_id
		          )) 
		          ->limit(1)
		          ->queryRow();		
		        if($resp){
					if (FunctionsV3::inventoryEnabled($resp['merchant_id'])){
						try {
						  Yii::app()->setImport(array(			
							  'application.modules.inventory.components.*',
						   ));	
						   InventoryWrapper::insertInventorySale($order_id,$this->data['status']);	
						} catch (Exception $e) {										    
						  // echo $e->getMessage();		    					    	  
						}		    					    	
					}
		        }
	    		
	    	} else $this->msg=Yii::t("default","ERROR: cannot update order.");	    	
	    } else $this->msg=Yii::t("default","Missing parameters");	    
	}
	
	public function minTableRates()
	{
		
		$mtid=Yii::app()->functions->getMerchantID();	
		Yii::app()->functions->updateOption("min_tables_enabled",
    	isset($this->data['min_tables_enabled'])?$this->data['min_tables_enabled']:'',$mtid);
    	    	
    	if (is_array($this->data['distance_from']) && count($this->data['distance_from'])>=1){    		
    		$x=0;
    		$stmt="
    		DELETE FROM
    		{{minimum_table}}    		
    		WHERE
    		merchant_id=".Yii::app()->functions->q($mtid)."
    		";
    		$this->qry($stmt);
    		foreach ($this->data['distance_from'] as $val) {    			
    			$params=array(
    			  'merchant_id'=>$mtid,
    			  'distance_from'=>is_numeric($val)?$val:0,
    			  'distance_to'=>is_numeric($this->data['distance_to'][$x])?$this->data['distance_to'][$x]:0,
    			  'shipping_units'=>$this->data['shipping_units'][$x],    			  
    			  'min_order'=>is_numeric($this->data['min_order'][$x])?$this->data['min_order'][$x]:0, 
    			);    			
    			$this->insertData("{{minimum_table}}",$params);
    			$x++;
    		}
    	}	
    	
    	$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");
	}
	
	public function adminMonerisSettings()
	{
				
		Yii::app()->functions->updateOptionAdmin("admin_moneris_storeid",
    	isset($this->data['admin_moneris_storeid'])?trim($this->data['admin_moneris_storeid']):'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_moneris_token",
    	isset($this->data['admin_moneris_token'])?trim($this->data['admin_moneris_token']):'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_moneris_enabled",
    	isset($this->data['admin_moneris_enabled'])?trim($this->data['admin_moneris_enabled']):'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_moneris_mode",
    	isset($this->data['admin_moneris_mode'])?trim($this->data['admin_moneris_mode']):'');
    	
		$this->code=1;
    	$this->msg=Yii::t("default","Setting saved");	    
	}
	
	public function merchantMonerisSettings()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
			
        Yii::app()->functions->updateOption("merchant_moneris_enabled",
    	isset($this->data['merchant_moneris_enabled'])?$this->data['merchant_moneris_enabled']:''
    	,$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_moneris_mode",
    	isset($this->data['merchant_moneris_mode'])?$this->data['merchant_moneris_mode']:''
    	,$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_moneris_storeid",
    	isset($this->data['merchant_moneris_storeid'])?$this->data['merchant_moneris_storeid']:''
    	,$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_moneris_token",
    	isset($this->data['merchant_moneris_token'])?$this->data['merchant_moneris_token']:''
    	,$merchant_id);
    	
    	$this->code=1;
	    $this->msg=Yii::t("default","Setting saved");
	}
	
	public function InitPlaceOrder()
	{
		
		unset($_SESSION['checkout_resp_geocode']);
		
		if ( getOptionA('captcha_order')==2){
			try {	    			
				$recaptcha_token = isset($this->data['recaptcha_v3'])?$this->data['recaptcha_v3']:'';	    			
				GoogleCaptchaV3::validateToken($recaptcha_token);
			} catch (Exception $e) {
				 $this->msg = $e->getMessage();
				 return ;
			}
		} 

		
		$transaction_type  = isset($this->data['delivery_type'])?$this->data['delivery_type']:'';
		$accurate_address_lat = isset($this->data['map_accurate_address_lat'])?$this->data['map_accurate_address_lat']:0;
		$accurate_address_lng = isset($this->data['map_accurate_address_lng'])?$this->data['map_accurate_address_lng']:0;
				
		$street = isset($this->data['street'])?$this->data['street']:'';
		$city = isset($this->data['city'])?$this->data['city']:'';		
		$state = isset($this->data['state'])?$this->data['state']:'';
		$zipcode = isset($this->data['zipcode'])?$this->data['zipcode']:'';
		$country_code = isset($this->data['country_code'])?$this->data['country_code']:'';
		$country_name = Yii::app()->functions->countryCodeToFull($country_code);		
		$complete_address = "$street $city $state $zipcode $country_name";		
		
		$is_by_location = FunctionsV3::isSearchByLocation();		
		$address_book_id = isset($this->data['address_book_id'])?$this->data['address_book_id']:'';
		
		if($address_book_id>0){
			$client_id=Yii::app()->functions->getClientId();
			$stmt_book = "SELECT * FROM {{address_book}} WHERE
			client_id = ".q($client_id)."
			AND id = ".q($address_book_id)."
			";
			if($resp_book = Yii::app()->db->createCommand($stmt_book)->queryRow()){
				$accurate_address_lat = $resp_book['latitude'];
				$accurate_address_lng = $resp_book['longitude'];
				$_SESSION['checkout_resp_geocode'] = array(
				  'lat'=>$accurate_address_lat,
				  'long'=>$accurate_address_lng
				);
			}
		}	
				
		if ( $transaction_type=="delivery" && empty($accurate_address_lat) ){
			try {													
				$resp_geocode = MapsWrapper::geoCodeAdress($complete_address);				
				$accurate_address_lat = $resp_geocode['lat'];
				$accurate_address_lng = $resp_geocode['long'];		
				$_SESSION['checkout_resp_geocode'] = $resp_geocode;
			} catch (Exception $e) {
			    $this->msg = $e->getMessage();
			    return ;
			}
		}
					
		if ( $transaction_type=="delivery"){
			if(empty($accurate_address_lng) || empty($accurate_address_lng)){
				$this->msg=t("Please select location on tne map");
	    		return ;
			}	    		
		}	
	
		$mtid=$_SESSION['kr_merchant_id'];				
		
		/*check if merchant has enabled Order sms verification*/
		$order_verification = getOption($mtid,'order_verification');
		if($order_verification==2){
	    	if (isset($this->data['client_order_sms_code'])){
	    		if (!empty($this->data['client_order_sms_code'])){
	    			if (!FunctionsK::validateOrderSMSCode($this->data['contact_phone'],
	    			   $this->data['client_order_sms_code'],
	    			   $this->data['client_order_session'])){
	    				 $this->msg=t("Sorry but you have input invalid order sms code");
	    				 return ;
	    			}	    		
	    		}
	    	}	 
		}   

			    
	    if ( $this->data['delivery_type']=="delivery"){
	    	if ($is_by_location){	    		
	    			    		
	    		/*IF USE ADDRESS BOOK */	    		
	    		if(isset($this->data['address_book_id_location'])){
	    			
	    			$address_book_id_location = $this->data['address_book_id_location'];	    			
	    			if($res_book = FunctionsV3::getAddressByLocationFullDetails($address_book_id_location)){	    				
	    					    				
	    				$this->data['state_id']=$res_book['state_id'];
	    				$this->data['city_id']=$res_book['city_id'];
	    				$this->data['area_id']=$res_book['area_id'];
	    				
	    				$this->data['street'] = $res_book['street'];    				
	    				$this->data['city'] = $res_book['city_name'];
	    				$this->data['state'] = $res_book['state_name'];
	    				$this->data['area_name'] = $res_book['area_name'];
	    				$this->data['location_name'] = $res_book['location_name'];
	    				$this->data['zipcode'] = $res_book['postal_code'];
	    			}	    		
	    		} 	    			    		
	    		$params_check=array(
	    		   'state_id'=>$this->data['state_id'],
	    		   'city_id'=>$this->data['city_id'],
	    		   'area_id'=>$this->data['area_id'],
	    		   'location_city'=>isset($this->data['city'])?$this->data['city']:'',
	    		   'city_name'=>isset($this->data['city'])?$this->data['city']:'',
	    		   'location_area'=>isset($this->data['area_name'])?$this->data['area_name']:'',
	    		   'location_type'=>getOptionA('admin_zipcode_searchtype')
	    		);	   	    		
	    		if ( $fee=FunctionsV3::validateCanDeliverByLocation($mtid,$params_check)){
	    			$_SESSION['shipping_fee']=$fee['fee'];		
	    			Cookie::setCookie('kr_location_search',json_encode($params_check));  	    			
	    		} else {
	    			$this->msg=t("Sorry this merchant does not deliver to your location");
	    			return ;
	    		} 	    		  	
	    	} else {	    			
	    		/*CHECK DISTANCE*/ 		    	    		
	    		try {
	    			
	    			$order_subtotal = isset($_SESSION['kmrs_subtotal'])?$_SESSION['kmrs_subtotal']:0;	    		
	    			$lat = $accurate_address_lat;
	    			$lng = $accurate_address_lng;
	    			$resp = CheckoutWrapper::verifyLocation($mtid,$lat,$lng,$order_subtotal);	    			
	    			$_SESSION['shipping_fee']=$resp['delivery_fee'];
	    			$_SESSION['shipping_distance']=isset($resp['pretty_distance'])?$resp['pretty_distance']:'';	    			
	    			
	    		} catch (Exception $e) {
				    $this->msg = $e->getMessage();
				    return ;
				}
	    		    		
	    	}
	    }	    	    
	    	    
	    $params=array();
	    if (is_array($this->data) && count($this->data)>=1){
	    	foreach ($this->data as $key=>$val) {	    		
	    		$params[$key]=$val;
	    	}
	    	unset($params['action']);
	    }
	    
	    $params['merchant_id']=$mtid;
	    $params['payment_opt']=$this->data['payment_opt'];
	    
	    switch ($this->data['payment_opt']) {
	    	case "pyp":
	    		//if ( FunctionsV3::isMerchantCommission($mtid)){
	    		if (FunctionsV3::isMerchantPaymentToUseAdmin($mtid)){
	    			$card_fee = getOptionA('admin_paypal_fee');
	    		} else $card_fee = getOption($mtid,'merchant_paypal_fee');
	    		if ($card_fee>0){
	    			$params['card_fee']=$card_fee;
	    		}	    
	    		break;
	    
	    	case "paymill":	    		
	    		if ( $credentials=KPaymill::getCredentials($mtid)){ 	    			
	    			if($credentials['card_fee1']>0.001){
	    			  $credentials['card_fee2']=is_numeric($credentials['card_fee2'])?$credentials['card_fee2']:0;
	    			  $fee = $this->data['x_subtotal']*($credentials['card_fee1']/100)+$credentials['card_fee2'];
	    			  $params['card_fee']=$fee;
	    			}	    			    			
	    		}	    		
	    		break;
	    		
	    	case "strip_ideal":	
	    	   if ( $credentials=StripeIdeal::getCredentials($mtid)){	    	   	   
	    	   	   if(is_numeric($credentials['ideal_fee'])){
		    	   	   if($credentials['ideal_fee']>=0.0001){
		    	   	   	  $params['card_fee']=$credentials['ideal_fee'];
		    	   	   }	    	   
	    	   	   }
	    	   }
	    	   break;
	    		
	    	case "mol":
	    		if ($credentials=MollieClass::getCredentials($mtid)){
	    			if(is_numeric($credentials['card_fee'])){
	    				if($credentials['card_fee']>=0.0001){
	    					$params['card_fee']=$credentials['card_fee'];
	    				}
	    			}
	    		}
	    	   break;
	    		   
	    	case "wirecard":   
	    	   if ($credentials = WireCard::getCredentials($mtid)){	    	   	  
	    	   	  if(is_numeric($credentials['fee1'])){
	    	   	  	 if($credentials['fee1']>0.0001){	    	   	  	 	
	    	   	  	 	$credentials['fee2']=is_numeric($credentials['fee2'])?$credentials['fee2']:0;
	    			    $fee = $this->data['x_subtotal']*($credentials['fee1']/100)+$credentials['fee2'];
	    			    $params['card_fee']=$fee; 
	    	   	  	 }	    	   	  
	    	   	  }
	    	   }	    
	    	   break;	    
	    	
	    	case "stp":   
	    	    if($credentials = StripeWrapper::getCredentials($mtid)){
	    	       if(is_numeric($credentials['card_fee'])){
	    	       	  if($credentials['card_fee']>0.0001){
	    	       	  	  $params['card_fee']=$credentials['card_fee'];
	    	       	  }
	    	       }
	    	    }	    
	    	    break;       	    	        
	    	    
	    	 case "paypal_v2":   
	    	    if($credentials = PaypalWrapper::getCredentials($mtid)){
	    	       if(is_numeric($credentials['card_fee'])){
	    	       	  if($credentials['card_fee']>0.0001){
	    	       	  	  $params['card_fee']=$credentials['card_fee'];
	    	       	  }
	    	       }
	    	    }	    
	    	    break;       	    	           
	    	  
	    	  case "mercadopago":   
	    	    if($credentials = mercadopagoWrapper::getCredentials($mtid)){
	    	       if(is_numeric($credentials['card_fee'])){
	    	       	  if($credentials['card_fee']>0.0001){
	    	       	  	  $params['card_fee']=$credentials['card_fee'];
	    	       	  }
	    	       }
	    	    }	    
	    	    break;         
	    	    
	    	 case "mollie":   
	    	    if($credentials = MollieWrapper::getCredentials($mtid)){
	    	       if(is_numeric($credentials['card_fee'])){
	    	       	  if($credentials['card_fee']>0.0001){
	    	       	  	  $params['card_fee']=$credentials['card_fee'];
	    	       	  }
	    	       }
	    	    }	    
	    	    break;       	    	              
	    	    
	    	default:
	    		break;
	    }	    
	    
	    	    	    	   	    	   
	    $_SESSION['confirm_order_data']=$params;	    
	    $this->code=1; $this->msg=t("Please wait while we redirect you");
	    	    
	    $is_guest=false;
	    if (isset($this->data['is_guest_checkout'])){
	    	if ($this->data['is_guest_checkout']==2){
	    		$is_guest=true;
	    	}	    
	    } 	
	    if ($is_guest){
	    	$this->details=Yii::app()->createUrl('store/confirmorder',array(
	    	  'isguest'=>1
	    	)) ;
	    } else $this->details=Yii::app()->createUrl('store/confirmorder') ;		    
	}
	
	public function emailLogs()
	{
		$aColumns = array(
		  'id','email_address','sender','subject','content','email_provider','status','date_created'
		);
		$t=AjaxDataTables::AjaxData($aColumns);		
		if (isset($_GET['debug'])){
		    dump($t);
		}
		
		if (is_array($t) && count($t)>=1){
			$sWhere=$t['sWhere'];
			$sOrder=$t['sOrder'];
			$sLimit=$t['sLimit'];
		}	
		
		$and='';		
				
		$stmt="SELECT SQL_CALC_FOUND_ROWS a.*		
		FROM
		{{email_logs}} a
		WHERE 1
		$and		
		$sWhere
		$sOrder
		$sLimit
		";
		if (isset($_GET['debug'])){
		   dump($stmt);
		}
				
		$DbExt=new DbExt; 
		if ( $res=$DbExt->rst($stmt)){
			
			$iTotalRecords=0;						
			$stmtc="SELECT FOUND_ROWS() as total_records";
			if ( $resc=$DbExt->rst($stmtc)){									
				$iTotalRecords=$resc[0]['total_records'];
			}
			
			$feed_data['sEcho']=intval($_GET['sEcho']);
			$feed_data['iTotalRecords']=$iTotalRecords;
			$feed_data['iTotalDisplayRecords']=$iTotalRecords;										
			
			foreach ($res as $val) {
				$date_created=Yii::app()->functions->prettyDate($val['date_created'],true);
			    $date_created=Yii::app()->functions->translateDate($date_created);		
			    $action='';	
			    
			    $status="<span class=\"tag ".$val['status']."\">".t($val['status'])."</span>";
			    $link=Yii::app()->createUrl('admin/viewemail',array(
			      'id'=>$val['id']
			    ));
			    $action="<a target=\"_blank\" href=\"$link\">".t("View Content")."</a>";
			    			    
			    $feed_data['aaData'][]=array(			      
			      $val['id'],
			      $val['email_address'],
			      $val['sender'],
			      $val['subject'],
			      $action,
			      t($val['email_provider']),
			      $status,
			      $date_created,
			    );			    
			}
			if (isset($_GET['debug'])){
			   dump($feed_data);
			}
			$this->otableOutput($feed_data);	
		}
		$this->otableNodata();
	}
	
	public function CountryList()
	{
		$aColumns = array(
		  'country_id','shortcode','country_name','phonecode','country_id'
		);
		$t=AjaxDataTables::AjaxData($aColumns);		
		if (isset($_GET['debug'])){
		    dump($t);
		}
		
		if (is_array($t) && count($t)>=1){
			$sWhere=$t['sWhere'];
			$sOrder=$t['sOrder'];
			$sLimit=$t['sLimit'];
		}	
		
		$and='';		
				
		$stmt="SELECT SQL_CALC_FOUND_ROWS a.*		
		FROM
		{{location_countries}} a
		WHERE 1
		$and		
		$sWhere
		$sOrder
		$sLimit
		";
		if (isset($_GET['debug'])){
		   dump($stmt);
		}
				
		$DbExt=new DbExt; 
		if ( $res=$DbExt->rst($stmt)){
			
			$iTotalRecords=0;						
			$stmtc="SELECT FOUND_ROWS() as total_records";
			if ( $resc=$DbExt->rst($stmtc)){									
				$iTotalRecords=$resc[0]['total_records'];
			}
			
			$feed_data['sEcho']=intval($_GET['sEcho']);
			$feed_data['iTotalRecords']=$iTotalRecords;
			$feed_data['iTotalDisplayRecords']=$iTotalRecords;										
			
			foreach ($res as $val) {
				$link=Yii::app()->createUrl('admin/definelocation',array(
				  'countryid'=>$val['country_id']
				));
				$acion="<a href=\"$link\">".t("Define location")."</a>";			    
			    $feed_data['aaData'][]=array(			      
			      $val['country_id'],
			      $val['shortcode'],
			      $val['country_name'],
			      $val['phonecode'],
			      $acion
			    );			    
			}
			if (isset($_GET['debug'])){
			   dump($feed_data);
			}
			$this->otableOutput($feed_data);	
		}
		$this->otableNodata();
	}
	
	public function InvoiceList()
	{
				
		$aColumns = array(
		  'invoice_number','merchant_name','invoice_terms',
		  'invoice_total','date_from','status','pdf_filename'
		);
		
		$sWhere=''; $sOrder=''; $sLimit='';
		
		$sTable = "{{invoice}}";
		
		$functionk=new FunctionsK();
		$t=$functionk->ajaxDataTables($aColumns);
		if (is_array($t) && count($t)>=1){
			$sWhere=$t['sWhere'];
			$sOrder=$t['sOrder'];
			$sLimit=$t['sLimit'];
		}	
		$stmt = "
			SELECT SQL_CALC_FOUND_ROWS 
			a.*
			FROM $sTable a
			$sWhere
			$sOrder
			$sLimit
		";
		if (isset($_GET['debug'])){dump($stmt);}
		if ( $res=$this->rst($stmt)){		
			
			$iTotalRecords=0;
			$stmt2="SELECT FOUND_ROWS()";
			if ( $res2=$this->rst($stmt2)){
				//dump($res2);
				$iTotalRecords=$res2[0]['FOUND_ROWS()'];
			}	
						
			$feed_data['sEcho']=intval($_GET['sEcho']);
			$feed_data['iTotalRecords']=$iTotalRecords;
			$feed_data['iTotalDisplayRecords']=$iTotalRecords;
			
			$action='';
						
			foreach ($res as $val) {	
			   if (isset($_GET['debug'])){dump($val);}
			   
			   $action='';
			   
			   if(!empty($val['pdf_filename']) && $val['pdf_filename']!=".pdf" ){			
			   	  $link=uploadURL()."/invoice/".$val['pdf_filename'];
			      $action="<a href=\"$link\" target=\"_blank\" class=\"uk-button uk-button-primary\">".t("View")."</a>";
			   }		
			   
			   $action.='<br/><br/>';
			   $action.='<a href="javascript:;" class="uk-button invoice_view_history" data-id="'.$val['invoice_number'].'" >'.t("History").'</a>';
			   
			   $edit_actions='<div class="options">';
			   $edit_actions.='<a href="javascript:;" data-id="'.$val['invoice_number'].'" class="edit_invoice">'.t("Edit").'</a>';
			   $edit_actions.="&nbsp;";
			   $edit_actions.='<a href="javascript:;" class="row_del" rev="'.$val['invoice_number'].'">'.t("Delete").'</a>';
			   $edit_actions.='</div>';
			   
			   $date_created=FunctionsV3::prettyDate($val['date_created']);
			   $date_created.=" ".FunctionsV3::prettyTime($val['date_created']);
			   $feed_data['aaData'][]=array(			
			     $val['invoice_number'],
			     stripslashes($val['merchant_id']).$edit_actions,
			     stripslashes($val['merchant_name']),			     
			     FunctionsV3::prettyInvoiceTerms($val['invoice_terms']),			     
			     FunctionsV3::prettyDate($val['date_from'])." - ".FunctionsV3::prettyDate($val['date_to']),
			     FunctionsV3::prettyPrice($val['invoice_total']),
			     $date_created."<span class=\"tag $val[status]\">".t($val['status'])."</span>",
			     "<span class=\"tag $val[payment_status]\">".t($val['payment_status'])."</span>",
			     $action
			   );
					
			}										
						
			$this->otableOutput($feed_data);	
		}
	    $this->otableNodata();
	}	

	
	public function AllOrders()
	{

		if(!Yii::app()->functions->isAdminLogin() ){
			Yii::app()->end();
		}
		
		$aColumns = array(
		  'order_id','a.merchant_id','c.first_name',
		  'json_details','trans_type','payment_type',
		  'sub_total','taxable_total','total_w_tax','a.status','request_from','a.date_created'
		);
		
		$sWhere=''; $sOrder=''; $sLimit='';
				
		
		$functionk=new FunctionsK();
		$t=$functionk->ajaxDataTables($aColumns);
		if (is_array($t) && count($t)>=1){
			$sWhere=$t['sWhere'];
			$sOrder=$t['sOrder'];
			$sLimit=$t['sLimit'];
		}	
		
		if(!empty($sWhere)){
			$sWhere.=" AND a.status NOT IN ('initial_order')";
		} else $sWhere.=" WHERE a.status NOT IN ('initial_order')";
				
		$stmt = "
			SELECT SQL_CALC_FOUND_ROWS 
			a.*,
			b.restaurant_name,
			concat(c.first_name,' ',c.last_name) as client_name,
			
			(
	    	select concat(first_name,' ',last_name)
	    	from {{order_delivery_address}}
	    	where order_id = a.order_id
	    	limit 0,1
	    	) as customer_name
			
			FROM {{order}} a
			LEFT join {{merchant}} b
            ON
            a.merchant_id=b.merchant_id
            
            LEFT join {{client}} c
            ON
            a.client_id = c.client_id
			
			$sWhere
			$sOrder
			$sLimit
		";		
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			$res = Yii::app()->request->stripSlashes($res);
						
			$iTotalRecords=0;
			$stmt2="SELECT FOUND_ROWS()";
			if ( $res2=$this->rst($stmt2)){
				//dump($res2);
				$iTotalRecords=$res2[0]['FOUND_ROWS()'];
			}	
						
			$feed_data['sEcho']=intval($_GET['sEcho']);
			$feed_data['iTotalRecords']=$iTotalRecords;
			$feed_data['iTotalDisplayRecords']=$iTotalRecords;
			
			$action='';
						
			foreach ($res as $val) {	

				if(!empty($val['customer_name']) && strlen($val['customer_name'])>1 ){					
					$val['client_name'] = $val['customer_name'];
				}

			    $action='';
				$action.="<a data-id=\"".$val['order_id']."\" class=\"edit-order\" href=\"javascript:\">".Yii::t("default","Edit")."</a>";
				$action.="<br/><a data-id=\"".$val['order_id']."\" class=\"view-receipt\" href=\"javascript:\">".Yii::t("default","View")."</a>";
				
				$action.="<a data-id=\"".$val['order_id']."\" class=\"view-order-history\" href=\"javascript:\">".Yii::t("default","History")."</a>";		   
			   
			   $item=FunctionsV3::translateFoodItemByOrderId($val['order_id']);
			   
			   $new='';
                if ($val['admin_viewed']<=0){
    				$new=" <div class=\"uk-badge\">".Yii::t("default","NEW")."</div>";
    			}	    			    			
	    			
			   $date_created=FunctionsV3::prettyDate($val['date_created']);
			   $date_created.=" ".FunctionsV3::prettyTime($val['date_created']);
			   $feed_data['aaData'][]=array(			
			     $val['order_id'],
			     stripslashes($val['restaurant_name']).$new,
			     $val['client_name'],
			     $item,
			     t($val['trans_type']),
			     t($val['payment_type']),
			     FunctionsV3::prettyPrice($val['sub_total']),
			     FunctionsV3::prettyPrice($val['taxable_total']),
			     FunctionsV3::prettyPrice($val['total_w_tax']),
			     "<span class=\"tag ".$val['status']."\">".t($val['status'])."</span>"."<div>$action</div>",
			     t($val['request_from']),
			     $date_created
			   );
					
			}										
						
			$this->otableOutput($feed_data);	
		}
	    $this->otableNodata();
	}	
	
	public function MerchantInvoiceList()
	{
				
		$aColumns = array(
		  'invoice_number','merchant_name','invoice_terms',
		  'invoice_total','date_from','status','pdf_filename'
		);
		
		$sWhere=''; $sOrder=''; $sLimit='';
		
		$sTable = "{{invoice}}";
		
		$functionk=new FunctionsK();
		$t=$functionk->ajaxDataTables($aColumns);
		if (is_array($t) && count($t)>=1){
			$sWhere=$t['sWhere'];
			$sWhere=str_replace("WHERE",'AND',$sWhere);
			$sOrder=$t['sOrder'];
			$sLimit=$t['sLimit'];
		}	
		$stmt = "
			SELECT SQL_CALC_FOUND_ROWS 
			a.*
			FROM $sTable a			
			WHERE merchant_id  =".FunctionsV3::q($this->data['merchant_id'])."
			$sWhere
			$sOrder
			$sLimit
		";
		if (isset($_GET['debug'])){dump($stmt);}
		if ( $res=$this->rst($stmt)){		
			
			$iTotalRecords=0;
			$stmt2="SELECT FOUND_ROWS()";
			if ( $res2=$this->rst($stmt2)){
				//dump($res2);
				$iTotalRecords=$res2[0]['FOUND_ROWS()'];
			}	
						
			$feed_data['sEcho']=intval($_GET['sEcho']);
			$feed_data['iTotalRecords']=$iTotalRecords;
			$feed_data['iTotalDisplayRecords']=$iTotalRecords;
			
			$action='';
						
			foreach ($res as $val) {	
			   if (isset($_GET['debug'])){dump($val);}
			   
			   if(!empty($val['pdf_filename']) && $val['pdf_filename']!=".pdf" ){			
			   	  //$link=uploadURL()."/invoice/".$val['pdf_filename'];
			   	  $link=Yii::app()->createUrl('merchant/viewinvoice',array(
			   	    'token'=>$val['invoice_token']
			   	  ));
			      $action="<a href=\"$link\" target=\"_blank\">".t("View")."</a>";
			   }		
			   
			   $new='';
			   if ($val['viewed']==2 || $val['viewed']=""){
			   	  $new='<span class="tag new">'.t("NEW").'</span>';
			   }			
			   
			   $date_created=FunctionsV3::prettyDate($val['date_created']);
			   $date_created.=" ".FunctionsV3::prettyTime($val['date_created']);
			   $feed_data['aaData'][]=array(			
			     $val['invoice_number'],			     
			     stripslashes($val['merchant_name'])."<br/>".$new,			     
			     FunctionsV3::prettyInvoiceTerms($val['invoice_terms']),			     
			     FunctionsV3::prettyDate($val['date_from'])." - ".FunctionsV3::prettyDate($val['date_to']),
			     FunctionsV3::prettyPrice($val['invoice_total']),
			     $date_created."<br/><span class=\"tag $val[payment_status]\">".t($val['payment_status'])."</span>",
			     $action
			   );
					
			}										
						
			$this->otableOutput($feed_data);	
		}
	    $this->otableNodata();
	}	
	
	public function saveCODSettings()
	{		
		$merchant_id=Yii::app()->functions->getMerchantID();
    	
    	Yii::app()->functions->updateOption("merchant_disabled_cod",
    	isset($this->data['merchant_disabled_cod'])?$this->data['merchant_disabled_cod']:'',$merchant_id);
    	
    	Yii::app()->functions->updateOption("cod_change_required_merchant",
    	isset($this->data['cod_change_required_merchant'])?$this->data['cod_change_required_merchant']:'',$merchant_id);

    	$this->code=1;
	    $this->msg=Yii::t("default","Settings saved.");    
	}
	
	public function saveOfflineSettings()
	{		
		$merchant_id=Yii::app()->functions->getMerchantID();
		    
    	Yii::app()->functions->updateOption("merchant_disabled_ccr",
    	isset($this->data['merchant_disabled_ccr'])?$this->data['merchant_disabled_ccr']:'',$merchant_id);

    	$this->code=1;
	    $this->msg=Yii::t("default","Settings saved.");    
	}

	public function AdminVoguepaySettings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_vog_enabled",
	    isset($this->data['admin_vog_enabled'])?$this->data['admin_vog_enabled']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_vog_merchant_id",
	    isset($this->data['admin_vog_merchant_id'])?$this->data['admin_vog_merchant_id']:'');
	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function MerchantVoguepaySettings()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
    	
    	Yii::app()->functions->updateOption("merchant_vog_enabled",
    	isset($this->data['merchant_vog_enabled'])?$this->data['merchant_vog_enabled']:'',$merchant_id);
    	
    	Yii::app()->functions->updateOption("merchant_vog_merchant_id",
    	isset($this->data['merchant_vog_merchant_id'])?$this->data['merchant_vog_merchant_id']:'',$merchant_id);

    	$this->code=1;
	    $this->msg=Yii::t("default","Settings saved.");    
	}
	
	public function replyToReview()
	{		
		if($merchant_info=Yii::app()->functions->getMerchantInfo()){
			$params=array(
			  'parent_id'=>$this->data['parent_id'],
			  'review'=>stripslashes($this->data['review']),
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			  'reply_from'=>$merchant_info[0]->restaurant_name
			);			
			$DbExt=new DbExt;
			
			if (isset($this->data['record_id'])){
				unset($params['date_created']);
				$params['date_modified']=FunctionsV3::dateNow();
				if ( $DbExt->updateData("{{review}}",$params,'id',$this->data['record_id'])){
					$this->msg=t("Successful"); $this->code=1;
				} else $this->msg=t("Failed cannot update records");
			} else {
				$DbExt->insertData("{{review}}",$params);
				$this->msg=t("Successful"); $this->code=1;
		    }
						
			unset($DbExt);			
		} else $this->msg=t("ERROR: Your session has expired.");
	}
	
	public function ViewccDetailSms()
	{		 
		 if ( $res=FunctionsV3::getMerchantCCdetails($this->data['id'])){
		 	Yii::app()->controller->renderPartial('/admin/cc-details',array(
		    		   'data'=>$res,		    		   
		    ),false);
		 } else echo t("No recods found");
	     Yii::app()->end();	
	}
	
	public function AdminIpaySettings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_ipay_enabled",
	    isset($this->data['admin_ipay_enabled'])?$this->data['admin_ipay_enabled']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_ipay_merchant_key",
	    isset($this->data['admin_ipay_merchant_key'])?$this->data['admin_ipay_merchant_key']:'');
	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
		
	
	public function MerchantIpaySettings()
	{
		$mtid=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("merchant_ipay_enabled",
    	isset($this->data['merchant_ipay_enabled'])?$this->data['merchant_ipay_enabled']:'',$mtid);
    	            
    	Yii::app()->functions->updateOption("merchant_ipay_merchant_key",
    	isset($this->data['merchant_ipay_merchant_key'])?$this->data['merchant_ipay_merchant_key']:'',$mtid); 
		
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}

	public function AdminPiPaySettings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_pipay_enabled",
	    isset($this->data['admin_pipay_enabled'])?$this->data['admin_pipay_enabled']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_pipay_merchant_id",
	    isset($this->data['admin_pipay_merchant_id'])?$this->data['admin_pipay_merchant_id']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_pipay_device_id",
	    isset($this->data['admin_pipay_device_id'])?$this->data['admin_pipay_device_id']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_pipay_store_id",
	    isset($this->data['admin_pipay_store_id'])?$this->data['admin_pipay_store_id']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_pipay_mode",
	    isset($this->data['admin_pipay_mode'])?$this->data['admin_pipay_mode']:'');
	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function MerchantPiPaySettings()
	{
		$mtid=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("merchant_pipay_enabled",
    	isset($this->data['merchant_pipay_enabled'])?$this->data['merchant_pipay_enabled']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_pipay_merchant_id",
    	isset($this->data['merchant_pipay_merchant_id'])?$this->data['merchant_pipay_merchant_id']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_pipay_device_id",
    	isset($this->data['merchant_pipay_device_id'])?$this->data['merchant_pipay_device_id']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_pipay_store_id",
    	isset($this->data['merchant_pipay_store_id'])?$this->data['merchant_pipay_store_id']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_pipay_mode",
    	isset($this->data['merchant_pipay_mode'])?$this->data['merchant_pipay_mode']:'',$mtid); 
		
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");		
	}
	
	public function AdminHubtelPaymentSettings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_hubtel_enabled",
	    isset($this->data['admin_hubtel_enabled'])?$this->data['admin_hubtel_enabled']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_hubtel_client_id",
	    isset($this->data['admin_hubtel_client_id'])?$this->data['admin_hubtel_client_id']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_hubtel_client_secret",
	    isset($this->data['admin_hubtel_client_secret'])?$this->data['admin_hubtel_client_secret']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_hubtel_accountno",
	    isset($this->data['admin_hubtel_accountno'])?$this->data['admin_hubtel_accountno']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_hubtel_channel",
	    isset($this->data['admin_hubtel_channel'])?$this->data['admin_hubtel_channel']:'');
	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}

	public function MerchantHubtelPaymentSettings()
	{
		$mtid=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("merchant_hubtel_enabled",
    	isset($this->data['merchant_hubtel_enabled'])?$this->data['merchant_hubtel_enabled']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_hubtel_client_id",
    	isset($this->data['merchant_hubtel_client_id'])?$this->data['merchant_hubtel_client_id']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_hubtel_client_secret",
    	isset($this->data['merchant_hubtel_client_secret'])?$this->data['merchant_hubtel_client_secret']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_hubtel_accountno",
    	isset($this->data['merchant_hubtel_accountno'])?$this->data['merchant_hubtel_accountno']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_hubtel_channel",
    	isset($this->data['merchant_hubtel_channel'])?$this->data['merchant_hubtel_channel']:'',$mtid); 
		
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");		
	}
	
	public function SofortAdminSettings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_sofort_enabled",
	    isset($this->data['admin_sofort_enabled'])?$this->data['admin_sofort_enabled']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_sofort_config_key",
	    isset($this->data['admin_sofort_config_key'])?$this->data['admin_sofort_config_key']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_sofort_lang",
	    isset($this->data['admin_sofort_lang'])?$this->data['admin_sofort_lang']:'');
	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function SofortMerchantSettings()
	{

		$mtid=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("merchant_sofort_enabled",
    	isset($this->data['merchant_sofort_enabled'])?$this->data['merchant_sofort_enabled']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_sofort_config_key",
    	isset($this->data['merchant_sofort_config_key'])?$this->data['merchant_sofort_config_key']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_sofort_lang",
    	isset($this->data['merchant_sofort_lang'])?$this->data['merchant_sofort_lang']:'',$mtid); 
		
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");		
		
	}
	
	public function JampieSettings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_jampie_enabled",
	    isset($this->data['admin_jampie_enabled'])?$this->data['admin_jampie_enabled']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_jampie_email",
	    isset($this->data['admin_jampie_email'])?$this->data['admin_jampie_email']:'');
	    	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function MerchantJampieSettings()
	{

		$mtid=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("merchant_jampie_enabled",
    	isset($this->data['merchant_jampie_enabled'])?$this->data['merchant_jampie_enabled']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_jampie_email",
    	isset($this->data['merchant_jampie_email'])?$this->data['merchant_jampie_email']:'',$mtid); 
    	    	
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");		
		
	}
	
	public function MerchantPointsSettings()
	{
		$mtid=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("mt_disabled_pts",
    	isset($this->data['mt_disabled_pts'])?$this->data['mt_disabled_pts']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("mt_pts_earn_points_status",
    	isset($this->data['mt_pts_earn_points_status'])?json_encode($this->data['mt_pts_earn_points_status']):'',$mtid); 
    	
    	Yii::app()->functions->updateOption("mt_pts_earning_points",
    	isset($this->data['mt_pts_earning_points'])?$this->data['mt_pts_earning_points']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("mt_pts_earning_points_value",
    	isset($this->data['mt_pts_earning_points_value'])?$this->data['mt_pts_earning_points_value']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("mt_pts_disabled_redeem",
    	isset($this->data['mt_pts_disabled_redeem'])?$this->data['mt_pts_disabled_redeem']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("mt_pts_redeeming_point",
    	isset($this->data['mt_pts_redeeming_point'])?$this->data['mt_pts_redeeming_point']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("mt_pts_redeeming_point_value",
    	isset($this->data['mt_pts_redeeming_point_value'])?$this->data['mt_pts_redeeming_point_value']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("mt_points_apply_order_amt",
    	isset($this->data['mt_points_apply_order_amt'])?$this->data['mt_points_apply_order_amt']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("mt_points_minimum",
    	isset($this->data['mt_points_minimum'])?$this->data['mt_points_minimum']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("mt_points_max",
    	isset($this->data['mt_points_max'])?$this->data['mt_points_max']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("mt_points_based_earn",
    	isset($this->data['mt_points_based_earn'])?$this->data['mt_points_based_earn']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("mt_pts_earn_above_amount",
    	isset($this->data['mt_pts_earn_above_amount'])?$this->data['mt_pts_earn_above_amount']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("mt_pts_enabled_add_voucher",
    	isset($this->data['mt_pts_enabled_add_voucher'])?$this->data['mt_pts_enabled_add_voucher']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("mt_pts_enabled_offers_discount",
    	isset($this->data['mt_pts_enabled_offers_discount'])?$this->data['mt_pts_enabled_offers_discount']:'',$mtid); 
    	    	
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");		
	}
	
	public function WingSettings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_wing_enabled",
	    isset($this->data['admin_wing_enabled'])?$this->data['admin_wing_enabled']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wing_loginid",
	    isset($this->data['admin_wing_loginid'])?$this->data['admin_wing_loginid']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wing_mode",
	    isset($this->data['admin_wing_mode'])?$this->data['admin_wing_mode']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wing_password",
	    isset($this->data['admin_wing_password'])?$this->data['admin_wing_password']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wing_biller",
	    isset($this->data['admin_wing_biller'])?$this->data['admin_wing_biller']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wing_web_sandbox_url",
	    isset($this->data['admin_wing_web_sandbox_url'])?$this->data['admin_wing_web_sandbox_url']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wing_web_live_url",
	    isset($this->data['admin_wing_web_live_url'])?$this->data['admin_wing_web_live_url']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wing_mobile_sandbox_url",
	    isset($this->data['admin_wing_mobile_sandbox_url'])?$this->data['admin_wing_mobile_sandbox_url']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wing_mobile_live_url",
	    isset($this->data['admin_wing_mobile_live_url'])?$this->data['admin_wing_mobile_live_url']:'');
	    	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function MerchantWingSettings()
	{
		$mtid=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("merchant_wing_enabled",
    	isset($this->data['merchant_wing_enabled'])?$this->data['merchant_wing_enabled']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_wing_mode",
    	isset($this->data['merchant_wing_mode'])?$this->data['merchant_wing_mode']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_wing_loginid",
    	isset($this->data['merchant_wing_loginid'])?$this->data['merchant_wing_loginid']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_wing_password",
    	isset($this->data['merchant_wing_password'])?$this->data['merchant_wing_password']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_wing_biller",
    	isset($this->data['merchant_wing_biller'])?$this->data['merchant_wing_biller']:'',$mtid); 
    	
		$this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function PaymillSettings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_paymill_enabled",
	    isset($this->data['admin_paymill_enabled'])?$this->data['admin_paymill_enabled']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_paymill_mode",
	    isset($this->data['admin_paymill_mode'])?$this->data['admin_paymill_mode']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_paymill_test_private_key",
	    isset($this->data['admin_paymill_test_private_key'])?$this->data['admin_paymill_test_private_key']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_paymill_test_public_key",
	    isset($this->data['admin_paymill_test_public_key'])?$this->data['admin_paymill_test_public_key']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_paymill_live_private_key",
	    isset($this->data['admin_paymill_live_private_key'])?$this->data['admin_paymill_live_private_key']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_paymill_live_public_key",
	    isset($this->data['admin_paymill_live_public_key'])?$this->data['admin_paymill_live_public_key']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_paymill_card_fee1",
	    isset($this->data['admin_paymill_card_fee1'])?$this->data['admin_paymill_card_fee1']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_paymill_card_fee2",
	    isset($this->data['admin_paymill_card_fee2'])?$this->data['admin_paymill_card_fee2']:'');
	    	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function PaymillMerchantSettings()
	{
		$mtid=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("merchant_paymill_enabled",
    	isset($this->data['merchant_paymill_enabled'])?$this->data['merchant_paymill_enabled']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_paymill_mode",
    	isset($this->data['merchant_paymill_mode'])?$this->data['merchant_paymill_mode']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_paymill_test_private_key",
    	isset($this->data['merchant_paymill_test_private_key'])?$this->data['merchant_paymill_test_private_key']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_paymill_test_public_key",
    	isset($this->data['merchant_paymill_test_public_key'])?$this->data['merchant_paymill_test_public_key']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_paymill_live_private_key",
    	isset($this->data['merchant_paymill_live_private_key'])?$this->data['merchant_paymill_live_private_key']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_paymill_live_public_key",
    	isset($this->data['merchant_paymill_live_public_key'])?$this->data['merchant_paymill_live_public_key']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_paymill_card_fee1",
    	isset($this->data['merchant_paymill_card_fee1'])?$this->data['merchant_paymill_card_fee1']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_paymill_card_fee2",
    	isset($this->data['merchant_paymill_card_fee2'])?$this->data['merchant_paymill_card_fee2']:'',$mtid);     	
    	
		$this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function IpayAfricaSettings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_ipay_africa_enabled",
	    isset($this->data['admin_ipay_africa_enabled'])?$this->data['admin_ipay_africa_enabled']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_ipay_africa_mode",
	    isset($this->data['admin_ipay_africa_mode'])?$this->data['admin_ipay_africa_mode']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_ipay_africa_vendor_id",
	    isset($this->data['admin_ipay_africa_vendor_id'])?$this->data['admin_ipay_africa_vendor_id']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_ipay_africa_hashkey",
	    isset($this->data['admin_ipay_africa_hashkey'])?$this->data['admin_ipay_africa_hashkey']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("mpesa_content",
	    isset($this->data['mpesa_content'])?$this->data['mpesa_content']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("airtel_content",
	    isset($this->data['airtel_content'])?$this->data['airtel_content']:'');

	    Yii::app()->functions->updateOptionAdmin("ipay_africa_enabled_payment",
	    isset($this->data['ipay_africa_enabled_payment'])?json_encode($this->data['ipay_africa_enabled_payment']):'');	    
	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function MerchantIpayAfricaSettings()
	{
		$mtid=Yii::app()->functions->getMerchantID();
		
		Yii::app()->functions->updateOption("merchant_ipay_africa_enabled",
    	isset($this->data['merchant_ipay_africa_enabled'])?$this->data['merchant_ipay_africa_enabled']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_ipay_africa_mode",
    	isset($this->data['merchant_ipay_africa_mode'])?$this->data['merchant_ipay_africa_mode']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_ipay_africa_vendor_id",
    	isset($this->data['merchant_ipay_africa_vendor_id'])?$this->data['merchant_ipay_africa_vendor_id']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_ipay_africa_hashkey",
    	isset($this->data['merchant_ipay_africa_hashkey'])?$this->data['merchant_ipay_africa_hashkey']:'',$mtid);  
    	
		$this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function adminDixiPaySettings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_dixipay_enabled",
	    isset($this->data['admin_dixipay_enabled'])?$this->data['admin_dixipay_enabled']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_dixipay_mode",
	    isset($this->data['admin_dixipay_mode'])?$this->data['admin_dixipay_mode']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_dixipay_username",
	    isset($this->data['admin_dixipay_username'])?$this->data['admin_dixipay_username']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_dixipay_password",
	    isset($this->data['admin_dixipay_password'])?$this->data['admin_dixipay_password']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_dixipay_account_code",
	    isset($this->data['admin_dixipay_account_code'])?$this->data['admin_dixipay_account_code']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_dixipay_sandbox_url",
	    isset($this->data['admin_dixipay_sandbox_url'])?$this->data['admin_dixipay_sandbox_url']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_dixipay_production_url",
	    isset($this->data['admin_dixipay_production_url'])?$this->data['admin_dixipay_production_url']:'');
	    	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function merchantDixiPaySettings()
	{
		$mtid=Yii::app()->functions->getMerchantID();		
		
		Yii::app()->functions->updateOption("merchant_dixipay_mode",
    	isset($this->data['merchant_dixipay_mode'])?$this->data['merchant_dixipay_mode']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_dixipay_enabled",
    	isset($this->data['merchant_dixipay_enabled'])?$this->data['merchant_dixipay_enabled']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_dixipay_username",
    	isset($this->data['merchant_dixipay_username'])?$this->data['merchant_dixipay_username']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_dixipay_password",
    	isset($this->data['merchant_dixipay_password'])?$this->data['merchant_dixipay_password']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_dixipay_account_code",
    	isset($this->data['merchant_dixipay_account_code'])?$this->data['merchant_dixipay_account_code']:'',$mtid);  
    	
		$this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function WireCardSettings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_wirecard_enabled",
	    isset($this->data['admin_wirecard_enabled'])?$this->data['admin_wirecard_enabled']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wirecard_mode",
	    isset($this->data['admin_wirecard_mode'])?$this->data['admin_wirecard_mode']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wirecard_customer_id",
	    isset($this->data['admin_wirecard_customer_id'])?$this->data['admin_wirecard_customer_id']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wiredcard_shopid",
	    isset($this->data['admin_wiredcard_shopid'])?$this->data['admin_wiredcard_shopid']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wirecard_secret",
	    isset($this->data['admin_wirecard_secret'])?$this->data['admin_wirecard_secret']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wirecard_customer_id_live",
	    isset($this->data['admin_wirecard_customer_id_live'])?$this->data['admin_wirecard_customer_id_live']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wiredcard_shopid_live",
	    isset($this->data['admin_wiredcard_shopid_live'])?$this->data['admin_wiredcard_shopid_live']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wirecard_secret_live",
	    isset($this->data['admin_wirecard_secret_live'])?$this->data['admin_wirecard_secret_live']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wirecard_display_text",
	    isset($this->data['admin_wirecard_display_text'])?$this->data['admin_wirecard_display_text']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wirecard_lang",
	    isset($this->data['admin_wirecard_lang'])?$this->data['admin_wirecard_lang']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wirecard_fee_1",
	    isset($this->data['admin_wirecard_fee_1'])?$this->data['admin_wirecard_fee_1']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_wirecard_fee_2",
	    isset($this->data['admin_wirecard_fee_2'])?$this->data['admin_wirecard_fee_2']:'');
	    	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function WireCardSettingsMerchant()
	{
		$mtid=Yii::app()->functions->getMerchantID();		
		
		Yii::app()->functions->updateOption("merchant_wirecard_enabled",
    	isset($this->data['merchant_wirecard_enabled'])?$this->data['merchant_wirecard_enabled']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_wirecard_mode",
    	isset($this->data['merchant_wirecard_mode'])?$this->data['merchant_wirecard_mode']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_wirecard_customer_id",
    	isset($this->data['merchant_wirecard_customer_id'])?$this->data['merchant_wirecard_customer_id']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_wiredcard_shopid",
    	isset($this->data['merchant_wiredcard_shopid'])?$this->data['merchant_wiredcard_shopid']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_wirecard_secret",
    	isset($this->data['merchant_wirecard_secret'])?$this->data['merchant_wirecard_secret']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_wirecard_customer_id_live",
    	isset($this->data['merchant_wirecard_customer_id_live'])?$this->data['merchant_wirecard_customer_id_live']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_wiredcard_shopid_live",
    	isset($this->data['merchant_wiredcard_shopid_live'])?$this->data['merchant_wiredcard_shopid_live']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_wirecard_secret_live",
    	isset($this->data['merchant_wirecard_secret_live'])?$this->data['merchant_wirecard_secret_live']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_wirecard_display_text",
    	isset($this->data['merchant_wirecard_display_text'])?$this->data['merchant_wirecard_display_text']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_wirecard_lang",
    	isset($this->data['merchant_wirecard_lang'])?$this->data['merchant_wirecard_lang']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_wirecard_fee_1",
    	isset($this->data['merchant_wirecard_fee_1'])?$this->data['merchant_wirecard_fee_1']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_wirecard_fee_2",
    	isset($this->data['merchant_wirecard_fee_2'])?$this->data['merchant_wirecard_fee_2']:'',$mtid);  
    	
		$this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function payulatamSettings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_payulatam_enabled",
	    isset($this->data['admin_payulatam_enabled'])?$this->data['admin_payulatam_enabled']:'');
	    
		Yii::app()->functions->updateOptionAdmin("admin_payulatam_mode",
	    isset($this->data['admin_payulatam_mode'])?$this->data['admin_payulatam_mode']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_payulatam_apikey",
	    isset($this->data['admin_payulatam_apikey'])?$this->data['admin_payulatam_apikey']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_payulatam_apilogin",
	    isset($this->data['admin_payulatam_apilogin'])?$this->data['admin_payulatam_apilogin']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_payulatam_mtid",
	    isset($this->data['admin_payulatam_mtid'])?$this->data['admin_payulatam_mtid']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_payulatam_apikey_live",
	    isset($this->data['admin_payulatam_apikey_live'])?$this->data['admin_payulatam_apikey_live']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_payulatam_apilogin_live",
	    isset($this->data['admin_payulatam_apilogin_live'])?$this->data['admin_payulatam_apilogin_live']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_payulatam_mtid_live",
	    isset($this->data['admin_payulatam_mtid_live'])?$this->data['admin_payulatam_mtid_live']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_payulatam_account_id",
	    isset($this->data['admin_payulatam_account_id'])?$this->data['admin_payulatam_account_id']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_payulatam_account_id_live",
	    isset($this->data['admin_payulatam_account_id_live'])?$this->data['admin_payulatam_account_id_live']:'');
	    
	    Yii::app()->functions->updateOptionAdmin("admin_payulatam_lang",
	    isset($this->data['admin_payulatam_lang'])?$this->data['admin_payulatam_lang']:'');
	    	    
	    $this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function payulatamMerchantSettings()
	{
		
		$mtid=Yii::app()->functions->getMerchantID();		
		
		Yii::app()->functions->updateOption("merchant_payulatam_enabled",
    	isset($this->data['merchant_payulatam_enabled'])?$this->data['merchant_payulatam_enabled']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_payulatam_mode",
    	isset($this->data['merchant_payulatam_mode'])?$this->data['merchant_payulatam_mode']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_payulatam_apikey",
    	isset($this->data['merchant_payulatam_apikey'])?$this->data['merchant_payulatam_apikey']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_payulatam_apilogin",
    	isset($this->data['merchant_payulatam_apilogin'])?$this->data['merchant_payulatam_apilogin']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_payulatam_mtid",
    	isset($this->data['merchant_payulatam_mtid'])?$this->data['merchant_payulatam_mtid']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_payulatam_apikey_live",
    	isset($this->data['merchant_payulatam_apikey_live'])?$this->data['merchant_payulatam_apikey_live']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_payulatam_apilogin_live",
    	isset($this->data['merchant_payulatam_apilogin_live'])?$this->data['merchant_payulatam_apilogin_live']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_payulatam_mtid_live",
    	isset($this->data['merchant_payulatam_mtid_live'])?$this->data['merchant_payulatam_mtid_live']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_payulatam_account_id",
    	isset($this->data['merchant_payulatam_account_id'])?$this->data['merchant_payulatam_account_id']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_payulatam_account_id_live",
    	isset($this->data['merchant_payulatam_account_id_live'])?$this->data['merchant_payulatam_account_id_live']:'',$mtid); 
    	
    	Yii::app()->functions->updateOption("merchant_payulatam_lang",
    	isset($this->data['merchant_payulatam_lang'])?$this->data['merchant_payulatam_lang']:'',$mtid); 
		
    	$this->code=1;
		$this->msg=Yii::t("default","Settings saved.");
	}
	
	public function requestCancelOrderList()
	{
				
		
		$DbExt=new DbExt;
		
		$and = '';
		$current_panel = isset($this->data['current_panel'])?$this->data['current_panel']:'';
		if(!empty($current_panel)){
			switch ($current_panel) {
				case "admin":
					if(!Yii::app()->functions->isAdminLogin() ){
					   $this->otableNodata();
					}			
					break;
							
				default:
					if ( !Yii::app()->functions->isMerchantLogin()){
					   $this->otableNodata();
					}
					$merchant_id=Yii::app()->functions->getMerchantID();
					$and=" AND merchant_id =".FunctionsV3::q($merchant_id)." ";					
					break;
			}
		}	
		    	
    	
    	$stmt="SELECT a.*,
    	(
    	select concat(first_name,' ',last_name)
    	from
    	{{client}}
    	where
    	client_id=a.client_id
    	) as client_name,
    	
    	(
    	select concat(contact_phone)
    	from
    	{{client}}
    	where
    	client_id=a.client_id
    	) as contact_phone,
    	
    	(
    	select group_concat(item_name)
    	from
    	{{order_details}}
    	where
    	order_id=a.order_id
    	) as item
    	
    	FROM
    	{{order}} a
    	WHERE 1    	
    	$and
    	AND status NOT in ('".initialStatus()."')
    	AND request_cancel = '1'
    	ORDER BY date_created DESC	    	
    	";    	    	
    	if ( $res=$DbExt->rst($stmt)){    		
    		foreach ($res as $val) {	    			
    			$new='';
    			$action="<a data-id=\"".$val['order_id_token']."\" class=\"order_cancel_review\" href=\"javascript:\">".t("Review Order")."</a>";
    			
    			if ($val['request_cancel_viewed']==2){
    				$new=" <div class=\"uk-badge\">".Yii::t("default","NEW")."</div>";
    			}
    			    			
    			$date=FormatDateTime($val['date_created']);
    			
    			$item=FunctionsV3::translateFoodItemByOrderId(
    			  $val['order_id'],
    			  'kr_merchant_lang_id'
    			);
    			
    			$feed_data['aaData'][]=array(
    			  $val['order_id'],
    			  ucwords($val['client_name']).$new,
    			  $val['contact_phone'],
    			  $item,
    			  t($val['trans_type']),	    			  
    			  FunctionsV3::prettyPaymentType('payment_order',$val['payment_type'],$val['order_id'],$val['trans_type']),
    			  FunctionsV3::prettyPrice($val['sub_total']),
    			  FunctionsV3::prettyPrice($val['taxable_total']),
    			  FunctionsV3::prettyPrice($val['total_w_tax']),	  
    			  "<span class=\"tag ".$val['status']."\">".t($val['status'])."</span>",
    			  t($val['request_from']),
    			  $date,
    			  $action
    		    );
    		}
    		$this->otableOutput($feed_data);
    	}	   
    	$this->otableNodata();		
	}
	
	public function reviewCancelOrder()
	{
		$order_id = isset($this->data['order_id'])?$this->data['order_id']:'';		
		if(!empty($order_id)){
			if ($res = FunctionsV3::getOrderByToken($order_id)){
				$id = $res['order_id'];
				$params = array(
				  'request_cancel_viewed'=>1,
				  'date_modified'=>FunctionsV3::dateNow(),
				  'ip_address'=>$_SERVER['REMOTE_ADDR']
				);
				$db = new DbExt();
				$db->updateData("{{order}}",$params,'order_id',$id);
				Yii::app()->controller->renderPartial('/merchant/review_cancel_order',array(
			        'order_id'=>$this->data['order_id']  
			    ),false);
			    
				Yii::app()->end();
			} else $error = t("Order id not found");
		} else $error = t("Order id not found");

		Yii::app()->controller->renderPartial('/merchant/error',array(
	        'message'=>$error
	    ),false);
		Yii::app()->end();
				
	}
	
	public function addAddressBookLocation()
	{
		$client_id=Yii::app()->functions->getClientId();
		
		if($client_id<=0){
			$this->msg =  t("Session has expired");
			return ;
		}	
		
		$params = array(
		 'client_id'=>$client_id,
		 'street'=>$this->data['street'],
		 'state_id'=>$this->data['state_id'],
		 'city_id'=>$this->data['city_id'],
		 'area_id'=>$this->data['area_id'],
		 'location_name'=>isset($this->data['location_name'])?$this->data['location_name']:'',
		 'country_id'=>$this->data['country_id'],
		 'as_default'=>isset($this->data['as_default'])?$this->data['as_default']:0,
		 'date_created'=>FunctionsV3::dateNow(),
		 'ip_address'=>$_SERVER['REMOTE_ADDR']
		);		
		$db = new DbExt();
		if(isset($this->data['id'])){
			unset($params['date_created']);
			unset($params['client_id']);
			$params['date_modified']=FunctionsV3::dateNow();			
			if ($db->updateData("{{address_book_location}}",$params,'id',$this->data['id'])){
			    $this->code=1;
     			$this->msg=Yii::t("default","Successful");		 
     			
     			
     			if($params['as_default']==1){
     			   $db->qry("
     			    UPDATE {{address_book_location}}
     			    SET as_default=''
     			    WHERE
     			    client_id = ".FunctionsV3::q($client_id)."
     			    AND 
     			    id <> ".FunctionsV3::q($this->data['id'])."
     			   ");
     			}			
     			
     		} else $this->msg=t("ERROR: Something went wrong");	
		} else {						
			if ( $db->insertData('{{address_book_location}}',$params)){
	        	$id=Yii::app()->db->getLastInsertID();
	        	$this->details=Yii::app()->createUrl('store/profile',array(
	        	  'tab'=>2,
	        	  'do'=>'add',
	        	  'id'=>$id
	        	));
	    		$this->code=1;
	    		$this->msg=Yii::t("default","Successful");	
	    		
	    		if($params['as_default']==1){
     			   $db->qry("
     			    UPDATE {{address_book_location}}
     			    SET as_default=''
     			    WHERE
     			    client_id = ".FunctionsV3::q($client_id)."
     			    AND 
     			    id <> ".FunctionsV3::q($id)."
     			   ");
     			}			
	    		 
	        } else $this->msg=t("ERROR: Something went wrong");		
		}	
		unset($db);
	}
	
	public function addressBookLocation()
	{		
		$client_id = Yii::app()->functions->getClientId();
		$feed_data = array();
		if($client_id>0){
			$stmt="
			SELECT 
			a.*,
			b.name as state_name,
			c.name as city_name,
			c.postal_code ,
			d.name as area_name
			
		    FROM
			{{address_book_location}} a
			
			left join {{location_states}} b
			On
			a.state_id = b.state_id
			
			left join {{location_cities}} c
			On
			a.city_id = c.city_id
			
			left join {{location_area}} d
			On
			a.area_id = d.area_id
			
			WHERE
			client_id = ".FunctionsV3::q($client_id)."
			";							
			if ($res=$this->rst($stmt)){				
				foreach ($res as $val) {
					$slug=Yii::app()->createUrl("store/profile/",array(
			   	     'tab'=>2,
			   	     'do'=>"add",
			   	     'id'=>$val['id']
			   	    ));
			   	    
			   	    $address='';
			   	    $address.= $val['street']." ".$val['state_name']." ".$val['city_name']." ".$val['area_name'];			   	    
			   	    $address.=" ".$val['postal_code'];
			   	    
			   	    $action="<div class=\"options\">
		    		<a href=\"$slug\" ><i class=\"ion-ios-compose-outline\"></i></a>
		    		<a href=\"javascript:;\" class=\"del_addresslocation\" data-id=\"$val[id]\" ><i class=\"ion-ios-trash\"></i></a>
		    		</div>";		   	   
			   	   $feed_data['aaData'][]=array(
			   	      $address.$action,
			   	      $val['location_name'],
			   	      $val['as_default']==1?'<i class="fa fa-check"></i>':'<i class="fa fa-times"></i>'
			   	   );
				}
				$this->otableOutput($feed_data);
			} else $this->otableNodata();
		} else $this->otableNodata();		
	}
	
	public function addReviewToOrder()
	{
		$order_info= array();
		$client_id = Yii::app()->functions->getClientId();
		if($client_id<=0){
			$this->msg =  t("Session has expired");
			return ;
		}	
		
		if($this->data['initial_review_rating']<0 || empty($this->data['initial_review_rating'])){
		   $this->msg = t("Rating is required");
		   return ;	
		}	
				
		$order_token = isset($this->data['order_id_token'])?$this->data['order_id_token']:'';		
		if(!empty($order_token)){
			$order_info = FunctionsV3::getOrderInfoByToken($order_token);
		}		
		if(is_array($order_info) && count($order_info)>=1){
			$order_id = $order_info['order_id'];			
			$params = array(
			  'merchant_id'=>$order_info['merchant_id'],
			  'client_id'=>$client_id,
			  'review'=>$this->data['review_content'],
			  'rating'=>$this->data['initial_review_rating'],
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			  'order_id'=>$order_id,
			  'as_anonymous'=>isset($this->data['as_anonymous'])?$this->data['as_anonymous']:0
			);
			
			if(method_exists('FunctionsV3','getReviewBasedOnStatus')){
				$params['status']=FunctionsV3::getReviewBasedOnStatus($order_info['status']);
			}
									
			$db = new DbExt();			
			if(!FunctionsV3::getReviewByOrder($client_id,$order_id)){
								
				if ( $db->insertData("{{review}}",$params)){
					$review_id=Yii::app()->db->getLastInsertID();
					/*POINTS REVIEW*/
					if (FunctionsV3::hasModuleAddon("pointsprogram")){
						if (method_exists('PointsProgram','addReviewsPerOrder')){
							PointsProgram::addReviewsPerOrder($order_id,$client_id,$review_id,$order_info['merchant_id'],$order_info['status']);
						}			
					}		
					
					$this->code = 1;
					$this->msg = t("Your review has been published.");
				} else $this->msg = t("ERROR. cannot insert data.");
			} else $this->msg = t("You have already have add review to this order");
		} else $this->msg  = t("Order id not found");
	}
	
	public function saveCategorySked()
	{
		$db = new DbExt();
		
		$mtid = Yii::app()->functions->getMerchantID();
		if($mtid<=0){
		   $this->msg = t("ERROR: Your session has expired.");
		   return ;	
		}
		
		Yii::app()->functions->updateOption("enabled_category_sked",
    	isset($this->data['enabled_category_sked'])?$this->data['enabled_category_sked']:'',$mtid);     	
		
		$stmt="
		UPDATE {{category}}
		SET 
		monday='0',
		tuesday='0',
		wednesday='0',
		thursday='0',
		friday='0',
		saturday='0',
		sunday='0'
		
		WHERE
		merchant_id = ". FunctionsV3::q($mtid)."
		";
		$db->qry($stmt);
				
		if(isset($this->data['category'])){
		   foreach ($this->data['category'] as $days=>$val) {		   	    	
		   		if(is_array($val) && count($val)>=1){
		   			foreach ($val as $sub_key=>$sub_val) {		   				
		   				$stmt="
		   				UPDATE {{category}}
		   				SET $days='1'
		   				WHERE
		   				cat_id=".FunctionsV3::q($sub_key)."
		   				AND
		   				merchant_id = ". FunctionsV3::q($mtid)."
		   				";
		   				//dump($stmt);
		   				$db->qry($stmt);
		   			}
		   		}		   
		   	}	
		}		
		
		$this->code = 1;
		$this->msg = t("Setting saved");
	}
	
	public function AdminPaystackSettings()
	{		
		
		Yii::app()->functions->updateOptionAdmin("admin_paystack_enabled",
    	isset($this->data['admin_paystack_enabled'])?$this->data['admin_paystack_enabled']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_paystack_mode",
    	isset($this->data['admin_paystack_mode'])?$this->data['admin_paystack_mode']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_paystack_sandbox_secret_key",
    	isset($this->data['admin_paystack_sandbox_secret_key'])?$this->data['admin_paystack_sandbox_secret_key']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_paystack_production_secret_key",
    	isset($this->data['admin_paystack_production_secret_key'])?$this->data['admin_paystack_production_secret_key']:'');
    	
		$this->code = 1;
		$this->msg = t("Setting saved");
	}
	
	public function MerchantPaystackSettings()
	{
		$mtid = Yii::app()->functions->getMerchantID();
		if($mtid<=0){
		   $this->msg = t("ERROR: Your session has expired.");
		   return ;	
		}
		
		Yii::app()->functions->updateOption("merchant_paystack_enabled",
    	isset($this->data['merchant_paystack_enabled'])?$this->data['merchant_paystack_enabled']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_paystack_mode",
    	isset($this->data['merchant_paystack_mode'])?$this->data['merchant_paystack_mode']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_paystack_sandbox_secret_key",
    	isset($this->data['merchant_paystack_sandbox_secret_key'])?$this->data['merchant_paystack_sandbox_secret_key']:'',$mtid);     	
    	
    	Yii::app()->functions->updateOption("merchant_paystack_production_secret_key",
    	isset($this->data['merchant_paystack_production_secret_key'])?$this->data['merchant_paystack_production_secret_key']:'',$mtid);     	
    	
    	$this->code = 1;
		$this->msg = t("Setting saved");
	}
	
	public function driverSignup()
	{		
		if (FunctionsV3::hasModuleAddon('driver')){
			
			$Validator=new Validator;
	    	$req=array(
	    	  'first_name'=>t("First name is required"),
	    	  'last_name'=>t("Last name is required"),
	    	  'email'=>t("Email is required"),
	    	  'phone'=>t("Mobile number is required"),
	    	  'username'=>t("Username is required"),
	    	  'password'=>t("Password is required"),
	    	  'transport_type_id'=>t("Transport Type is required"),
	    	);
	    	
	    	if ( Driver::getDriverByUsername($this->data['username'])){			
				$Validator->msg[]=t("Username already exist");
			}			
			if ( Driver::getDriverByEmail($this->data['email'])){			
				$Validator->msg[]=t("Email already exist");
			}			
			
			if (isset($this->data['phone'])){
				if ( strlen($this->data['phone']<10)){
					$Validator->msg[]=t("Mobile number is required");
				}
			}
			
			if(isset($this->data['password'])){					
				if($this->data['password']!=$this->data['cpassword']){					
					$Validator->msg[]=t("Confirm password does not match");
				}			
			}		
		
	    	$Validator->email(array(
			  'email'=>"Invalid email address"
			),$this->data);
	    	
	    	$Validator->required($req,$this->data);
	    	if ( $Validator->validate()){
	    			    		
	    		$admin_id=Driver::getAdminID();
	    		$status=getOptionA('driver_signup_status');
	    		if(empty($status)){
	    			$status='pending';
	    		}
	    		
	    		$params=array(
	    		  'first_name'=>$this->data['first_name'],
	    		  'last_name'=>$this->data['last_name'],
	    		  'email'=>$this->data['email'],
	    		  'phone'=>$this->data['phone'],
	    		  'username'=>$this->data['username'],
	    		  'password'=>md5($this->data['password']),
	    		  'transport_type_id'=>$this->data['transport_type_id'],
	    		  'transport_description'=>isset($this->data['transport_description'])?$this->data['transport_description']:'',
	              'licence_plate'=>isset($this->data['licence_plate'])?$this->data['licence_plate']:'',
	              'color'=>isset($this->data['color'])?$this->data['color']:'',
	              'date_created'=>FunctionsV3::dateNow(),
	              'ip_address'=>$_SERVER['REMOTE_ADDR'],
	              'status'=>$status,
	              'user_type'=>"admin",
	              'user_id'=>isset($admin_id['admin_id'])?$admin_id['admin_id']:'',
	              'is_signup'=>1
	    		);
	    			    		
	    		$db=new DbExt;
	    		if ( $db->insertData("{{driver}}",$params)){
	    			
	    			$this->code=1;
	    			
	    			if ( $status=="active"){
    			      $this->msg=t("Signup successful");
    			    } else $this->msg=t("Your request has been receive please wait while we validate your application");
    			    
    			    $this->details = Yii::app()->createUrl('/store/driver_signup_ty',array(
	    			  'message'=>$this->msg
	    			));
	    			
    			    /*send email to admin*/
		    		$driver_enabled_signup=getOptionA('driver_enabled_signup');
		    		if($driver_enabled_signup==1){
		    			$admin_email=getOptionA('driver_send_admin_notification_email');
		    			if(!empty($admin_email)){
		    				$tpl=EmailTemplate::newDriverSignup();
		    				$tpl=Driver::smarty('full_name',$this->data['first_name']." ".
		    				$this->data['last_name']
		    				,$tpl);
		    				$tpl=Driver::smarty('email',$this->data['email'],$tpl);
		    				$tpl=Driver::smarty('phone',$this->data['phone'],$tpl);
		    				$tpl=Driver::smarty('username',$this->data['username'],$tpl);
		    				$tpl=Driver::smarty('transport_type_id',$this->data['transport_type_id'],$tpl);    				
		    				Yii::app()->functions->sendEmail(
		    				  $admin_email,'',t("New driver Signup"),$tpl
		    				);
		    			}
		    		}
		    		
		    		/*send welcome email*/
		    		$DRIVER_NEW_SIGNUP_EMAIL=getOptionA('DRIVER_NEW_SIGNUP_EMAIL');
		    		$DRIVER_NEW_SIGNUP_EMAIL_TPL=getOptionA('DRIVER_NEW_SIGNUP_EMAIL_TPL');    		
		    		if ( $DRIVER_NEW_SIGNUP_EMAIL==1 && !empty($DRIVER_NEW_SIGNUP_EMAIL_TPL) ){
		    			$tpl=$DRIVER_NEW_SIGNUP_EMAIL_TPL;
		    			$company_name=Yii ::app()->functions->getOptionAdmin('website_title');  
		    			$tpl=Driver::smarty('DriverName',$this->data['first_name'],$tpl);
		    			$tpl=Driver::smarty('CompanyName',$company_name,$tpl);
		    			Yii::app()->functions->sendEmail(
						  $this->data['email'],'',t("Thank you for signing up"),$tpl
						);
		    		}
    			    
	    		} else $this->msg = t("Something went wrong please try again later");
	    		
	    	} else $this->msg = $Validator->getErrorAsHTML();
			
		} else $this->msg = t("Failed. cannot find driver addon");
	}	
	
	public function adminPaypalV2Settings()
	{
		Yii::app()->functions->updateOptionAdmin("admin_paypal_v2_enabled",
    	isset($this->data['admin_paypal_v2_enabled'])?$this->data['admin_paypal_v2_enabled']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_paypal_v2_mode",
    	isset($this->data['admin_paypal_v2_mode'])?$this->data['admin_paypal_v2_mode']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_paypal_v2_card_fee",
    	isset($this->data['admin_paypal_v2_mode'])?$this->data['admin_paypal_v2_card_fee']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_paypal_v2_client_id",
    	isset($this->data['admin_paypal_v2_client_id'])?$this->data['admin_paypal_v2_client_id']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_paypal_v2_secret",
    	isset($this->data['admin_paypal_v2_secret'])?$this->data['admin_paypal_v2_secret']:'');
    	    	
		$this->code = 1;
		$this->msg = t("Setting saved");	
	}
	
	public function merchantPaypalV2Settings()
	{
		$mtid = Yii::app()->functions->getMerchantID();
		if($mtid<=0){
		   $this->msg = t("ERROR: Your session has expired.");
		   return ;	
		}
		
		Yii::app()->functions->updateOption("merchant_paypal_v2_enabled",
    	isset($this->data['merchant_paypal_v2_enabled'])?$this->data['merchant_paypal_v2_enabled']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_paypal_v2_mode",
    	isset($this->data['merchant_paypal_v2_mode'])?$this->data['merchant_paypal_v2_mode']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_paypal_v2_card_fee",
    	isset($this->data['merchant_paypal_v2_card_fee'])?$this->data['merchant_paypal_v2_card_fee']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_paypal_v2_client_id",
    	isset($this->data['merchant_paypal_v2_client_id'])?$this->data['merchant_paypal_v2_client_id']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_paypal_v2_secret",
    	isset($this->data['merchant_paypal_v2_secret'])?$this->data['merchant_paypal_v2_secret']:'',$mtid);  
    	
    	$this->code = 1;
		$this->msg = t("Setting saved");	
	}
			
	public function merchantCreditCardList()
	{
		$merchant_id = Yii::app()->functions->getMerchantID();
		if($merchant_id>=1){
			$db = new DbExt();
			$feed_data = array();
			$stmt="
			SELECT SQL_CALC_FOUND_ROWS a.*
			FROM
			{{merchant_cc}} a
			WHERE 
			merchant_id=".FunctionsV3::q($merchant_id)."
			ORDER BY mt_id DESC
			";			
			if ($res = $db->rst($stmt)){
				
				$iTotalRecords=0;
				$stmt2="SELECT FOUND_ROWS()";
				if ( $res2=$this->rst($stmt2)){				
					$iTotalRecords=$res2[0]['FOUND_ROWS()'];
				}	
							
				$feed_data['sEcho']=intval($_GET['sEcho']);
				$feed_data['iTotalRecords']=$iTotalRecords;
				$feed_data['iTotalDisplayRecords']=$iTotalRecords;
				
				foreach ($res as $val) {					
					$edit_link= Yii::app()->createUrl('/merchant/manage_credit_cards/',array(
					  'do'=>"add",
					  'ids'=>$val['mt_id']
					));
					$action="<div class=\"options\">
		    		<a href=\"$edit_link\" >".Yii::t("default","Edit")."</a>
		    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[mt_id]\" >".Yii::t("default","Delete")."</a>
		    		</div>";
					
					$feed_data['aaData'][]=array(
					  $val['mt_id'],
					  $val['card_name'].$action,
					  $val['credit_card_number'],
					  FunctionsV3::prettyDate($val['date_created']),					  
					);					
				}
				$this->otableOutput($feed_data);
			} else $this->otableNodata();			
		} else $this->otableNodata();
	}
	
	public function AddUpdateMerchantCC()
	{	
		$db = new DbExt();
		$id = isset($this->data['id'])?$this->data['id']:'';		
		$merchant_id = Yii::app()->functions->getMerchantID();
		
		if($merchant_id>=1){
			
			$p = new CHtmlPurifier();
						
			$params = array(
			  'merchant_id'=>$merchant_id,
			  'card_name'=>$p->purify($this->data['card_name']),
			  'credit_card_number'=>FunctionsV3::maskCardnumber($p->purify($this->data['credit_card_number'])),
			  'expiration_month'=>$this->data['expiration_month'],
			  'expiration_yr'=>$this->data['expiration_yr'],
			  'cvv'=>$this->data['cvv'],
			  'billing_address'=>$p->purify($this->data['billing_address']),
			  'date_created'=>FunctionsV3::dateNow()
			);
			
			try {
	    	   $params['encrypted_card']=CreditCardWrapper::encryptCard($p->purify($this->data['credit_card_number']));
	    	} catch (Exception $e) {
	    		$this->msg = Yii::t("default","Caught exception: [error]",array(
							    '[error]'=>$e->getMessage()
							  ));
	    		return ;
	    	}
				    	
	    	if($id>=1){
	    		unset($params['date_created']);
	    		$params['date_modified']=FunctionsV3::dateNow();
	    		if ($db->updateData("{{merchant_cc}}",$params,'mt_id',$id)){
	    			$this->code=1;
	    		    $this->msg=t("Successful");
	    		} else $this->msg=t("Failed cannot update records");	    	
	    	} else {
	    		$db->insertData("{{merchant_cc}}",$params);
	    		$id = Yii::app()->db->getLastInsertID();
	    		$this->details = array(
	    		  'redirect'=>Yii::app()->createUrl('/merchant/manage_credit_cards',
	    		  array('do'=>"add",'ids'=>$id))
	    		);
	    		$this->code = 1;
	    		$this->msg=t("Successful");
	    	}		
	    	
		} else $this->msg = t("ERROR: Your session has expired.");
	}
	
	public function admin_mercadopago_v2_settings()
	{
		Yii::app()->functions->updateOptionAdmin("mercadopago_v2_enabled",
    	isset($this->data['mercadopago_v2_enabled'])?$this->data['mercadopago_v2_enabled']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("mercadopago_v2_mode",
    	isset($this->data['mercadopago_v2_mode'])?$this->data['mercadopago_v2_mode']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_mercadopago_v2_client_id",
    	isset($this->data['admin_mercadopago_v2_client_id'])?$this->data['admin_mercadopago_v2_client_id']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_mercadopago_v2_client_secret",
    	isset($this->data['admin_mercadopago_v2_client_secret'])?$this->data['admin_mercadopago_v2_client_secret']:'');
    	
    	Yii::app()->functions->updateOptionAdmin("admin_mercadopago_v2_card_fee",
    	isset($this->data['admin_mercadopago_v2_card_fee'])?$this->data['admin_mercadopago_v2_card_fee']:'');
    	    	
		$this->code = 1;
		$this->msg = t("Setting saved");	
	}
	
	public function merchant_mercadopago_v2_settings()
	{		
		$mtid = Yii::app()->functions->getMerchantID();
		if($mtid<=0){
		   $this->msg = t("ERROR: Your session has expired.");
		   return ;	
		}
		
		Yii::app()->functions->updateOption("merchant_mercadopago_v2_enabled",
    	isset($this->data['merchant_mercadopago_v2_enabled'])?$this->data['merchant_mercadopago_v2_enabled']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_mercadopago_v2_mode",
    	isset($this->data['merchant_mercadopago_v2_mode'])?$this->data['merchant_mercadopago_v2_mode']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_mercadopago_v2_client_id",
    	isset($this->data['merchant_mercadopago_v2_client_id'])?$this->data['merchant_mercadopago_v2_client_id']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_mercadopago_v2_client_secret",
    	isset($this->data['merchant_mercadopago_v2_client_secret'])?$this->data['merchant_mercadopago_v2_client_secret']:'',$mtid);  
    	
    	Yii::app()->functions->updateOption("merchant_mercadopago_v2_card_fee",
    	isset($this->data['merchant_mercadopago_v2_card_fee'])?$this->data['merchant_mercadopago_v2_card_fee']:'',$mtid);  
    	
    	$this->code = 1;
		$this->msg = t("Setting saved");	
	}	
	
	public function BannerSettings()
	{
		$merchant_id=Yii::app()->functions->getMerchantID();
		if($merchant_id>=1){
			
			$old_data=getOption($merchant_id,'merchant_banner');
			if(!empty($old_data)){
				$old_data=json_decode($old_data,true);
			}		
			
			/*DELETE IMAGE*/
			if(is_array($old_data) && count($old_data)>=1 && !empty($this->data['photo'])){
				foreach ($old_data as $val) {
					if(!in_array($val,(array)$this->data['photo'])){				
		    	        FunctionsV3::deleteUploadedFile($val);
					}				
				}
			}			
			
			Yii::app()->functions->updateOption("merchant_banner",
	    	isset($this->data['photo'])?json_encode($this->data['photo']):''
	    	,$merchant_id);
	    	
	    	Yii::app()->functions->updateOption("banner_enabled",
	    	isset($this->data['banner_enabled'])?$this->data['banner_enabled']:''
	    	,$merchant_id);
			
			$this->code=1;
		    $this->msg=Yii::t("default","Setting saved");
		} else $this->msg = t("invalid merchant id");
	}	
	
	public function AllOrdersMerchant()
	{
		$merchant_id = (integer)Yii::app()->functions->getMerchantID();
		if($merchant_id>0){
			
			$aColumns = array(
			  'order_id','a.merchant_id','c.first_name',
			  'json_details','trans_type','payment_type',
			  'sub_total','taxable_total','total_w_tax','a.status','request_from','a.date_created'
			);			
			$sWhere=''; $sOrder=''; $sLimit='';
			$functionk=new FunctionsK();
			$t=$functionk->ajaxDataTables($aColumns);
			if (is_array($t) && count($t)>=1){
				$sWhere=$t['sWhere'];
				$sOrder=$t['sOrder'];
				$sLimit=$t['sLimit'];
			}	
			
			$where = " WHERE a.merchant_id= ".q($merchant_id)." ";
			
			if(!empty($sWhere)){
				$sWhere.=" AND a.status NOT IN ('initial_order')";
			} else $sWhere.=" AND a.status NOT IN ('initial_order')";
					
			$stmt = "
				SELECT SQL_CALC_FOUND_ROWS 
				a.*,
				b.restaurant_name,
				concat(c.first_name,' ',c.last_name) as client_name,
				
				concat(d.first_name,' ',d.last_name) as customer_name
				
				FROM {{order}} a
				LEFT join {{merchant}} b
	            ON
	            a.merchant_id=b.merchant_id
	            
	            LEFT join {{client}} c
	            ON
	            a.client_id = c.client_id
	            
	            LEFT join {{order_delivery_address}} d
	            ON
	            a.order_id = d.order_id
	            
				$where
				$sWhere			
				$sOrder
				$sLimit
			";									
			if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
				$res = Yii::app()->request->stripSlashes($res);
				$iTotalRecords=0;				
				if($res2 = Yii::app()->db->createCommand("SELECT FOUND_ROWS()")->queryRow()){
					$iTotalRecords=$res2['FOUND_ROWS()'];
				}	
							
				$feed_data['sEcho']=intval($_GET['sEcho']);
				$feed_data['iTotalRecords']=$iTotalRecords;
				$feed_data['iTotalDisplayRecords']=$iTotalRecords;
			
				foreach ($res as $val) {
					
					$action='';
					$action.="<a data-id=\"".$val['order_id']."\" class=\"edit-order\" href=\"javascript:\">".Yii::t("default","Edit")."</a>";
					$action.="<br/><a data-id=\"".$val['order_id']."\" class=\"view-receipt\" href=\"javascript:\">".Yii::t("default","View")."</a>";
					
					$action.="<a data-id=\"".$val['order_id']."\" class=\"view-order-history\" href=\"javascript:\">".Yii::t("default","History")."</a>";		   
				   
				   $item=FunctionsV3::translateFoodItemByOrderId($val['order_id']);
				   
				   $new='';
	                if ($val['admin_viewed']<=0){
	    				$new=" <div class=\"uk-badge\">".Yii::t("default","NEW")."</div>";
	    			}	    	

	    		   if(!empty($val['customer_name'])){
  	    		   	   $val['client_name']=$val['customer_name'];
	    		   }				
		    			
				   $date_created=FunctionsV3::prettyDate($val['date_created']);
				   $date_created.=" ".FunctionsV3::prettyTime($val['date_created']);
				   $feed_data['aaData'][]=array(			
				     $val['order_id'],
				     stripslashes($val['restaurant_name']).$new,
				     $val['client_name'],
				     $item,
				     t($val['trans_type']),
				     t($val['payment_type']),
				     FunctionsV3::prettyPrice($val['sub_total']),
				     FunctionsV3::prettyPrice($val['taxable_total']),
				     FunctionsV3::prettyPrice($val['total_w_tax']),
				     "<span class=\"tag ".$val['status']."\">".t($val['status'])."</span>"."<div>$action</div>",
				     t($val['request_from']),
				     $date_created
				   );
				}
				$this->otableOutput($feed_data);
			}
		}			
		$this->otableNodata();
	}

	public function TagsList()
	{								    
		$stmt="SELECT * FROM
		{{tags}}
		ORDER BY date_created DESC
		";
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
		   $res = Yii::app()->request->stripSlashes($res);
		   foreach ($res as $val) {				   	    			   	    			   	  
		   	    $link = Yii::app()->createUrl("admin/tags_add",array(
		   	     'tag_id'=>$val['tag_id']
		   	    ));
				$action="<div class=\"options\">
	    		<a href=\"$link\" >".Yii::t("default","Edit")."</a>
	    		<a href=\"javascript:;\" class=\"row_del\" rev=\"$val[tag_id]\" >".Yii::t("default","Delete")."</a>
	    		</div>";		   	   
			   				   				   
		   	   $feed_data['aaData'][]=array(
		   	      $val['tag_id'],
		   	      $val['tag_name'].$action,
		   	      $val['description'],			   	         	      	
		   	      $val['slug'],
		   	      FunctionsV3::prettyDate($val['date_created'])
		   	   );		
		   	   //dump($feed_data);	       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();
	}	
	
	public function addTag()
	{
		$tag_id = isset($this->data['tag_id'])?(integer)$this->data['tag_id']:0;
		$params = array(
		  'tag_name'=>isset($this->data['tag_name'])?$this->data['tag_name']:'',
		  'description'=>isset($this->data['description'])?$this->data['description']:'',
		  'date_created'=>FunctionsV3::dateNow(),
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		
		if (isset($this->data['tag_name_trans'])){
			if (okToDecode()){
				$params['tag_name_trans']=json_encode($this->data['tag_name_trans'],
				JSON_UNESCAPED_UNICODE);
			} else $params['tag_name_trans']=json_encode($this->data['tag_name_trans']);
		}
		
		if (isset($this->data['description_trans'])){
			if (okToDecode()){
				$params['description_trans']=json_encode($this->data['description_trans'],
				JSON_UNESCAPED_UNICODE);
			} else $params['description_trans']=json_encode($this->data['description_trans']);
		}
				
		if($tag_id>0){
			if(!empty($params['tag_name'])){
				$params['slug'] = FunctionsV3::createSlug('tags',$params['tag_name'],'tag_id',$tag_id);
			}			
			$up =Yii::app()->db->createCommand()->update("{{tags}}",$params,
	  	    'tag_id=:tag_id',
		  	    array(
		  	      ':tag_id'=>$tag_id
		  	    )
	  	    );
	  	    $this->code = 1;
			$this->msg = t("Successful");			
		} else {
			if(!empty($params['tag_name'])){
				$params['slug'] = FunctionsV3::createSlug('tags',$params['tag_name']);
			}	
			if(Yii::app()->db->createCommand()->insert("{{tags}}",$params)){
				$tag_id=Yii::app()->db->getLastInsertID();
				$this->code = 1;
				$this->msg = t("Successful");
				$this->details = array(
	    		  'redirect'=>Yii::app()->createUrl('/admin/tags')
	    		);
			} else $this->msg = t("ERROR. cannot insert data.");
	    }
		
	}
	
	public function singleMerchantBanner()
	{		
		if (  Yii::app()->functions->isMerchantLogin()){
		   $merchant_id = (integer)Yii::app()->functions->getMerchantID();
		   
		   Yii::app()->functions->updateOption("singleapp_banner",
		    isset($this->data['photo'])?json_encode($this->data['photo']):''
		    ,$merchant_id);
		    
		    Yii::app()->functions->updateOption("singleapp_enabled_banner",
	        isset($this->data['singleapp_enabled_banner'])?$this->data['singleapp_enabled_banner']:''
	        ,$merchant_id);
	        
	        Yii::app()->functions->updateOption("singleapp_homebanner_interval",
	        isset($this->data['singleapp_homebanner_interval'])?$this->data['singleapp_homebanner_interval']:''
	        ,$merchant_id);
	        
	        Yii::app()->functions->updateOption("singleapp_homebanner_auto_scroll",
	        isset($this->data['singleapp_homebanner_auto_scroll'])?$this->data['singleapp_homebanner_auto_scroll']:''
	        ,$merchant_id);
		    
		    $this->code = 1;		
	        $this->msg = t("Successful");	 
		} else $this->msg=t("ERROR: Your session has expired.");
	}
	
	public function singleMerchantAndroid()
	{
		if (  Yii::app()->functions->isMerchantLogin()){
		   $merchant_id = (integer)Yii::app()->functions->getMerchantID();
		   		    		    
		    Yii::app()->functions->updateOption("singleapp_enabled_pushpic",
	        isset($this->data['singleapp_enabled_pushpic'])?$this->data['singleapp_enabled_pushpic']:''
	        ,$merchant_id);
	        
	        Yii::app()->functions->updateOption("singleapp_push_icon",
	        isset($this->data['photo'])?$this->data['photo']:''
	        ,$merchant_id);
	        
	        Yii::app()->functions->updateOption("singleapp_push_picture",
	        isset($this->data['photo2'])?$this->data['photo2']:''
	        ,$merchant_id);
	        	        
		    $this->code = 1;		
	        $this->msg = t("Successful");	 
		} else $this->msg=t("ERROR: Your session has expired.");
	}
	
	public function singlemerchantPage()
	{
	   if ( !Yii::app()->functions->isMerchantLogin()){
			$this->msg=t("ERROR: Your session has expired.");
			return false;
	   }
	   
	   $merchant_id = (integer)Yii::app()->functions->getMerchantID();
	   
		$cols = array(
		  'page_id','title',
		  'content','sequence',
		  'date_created','page_id'
		);
		
		$sWhere=''; $sOrder=''; $sLimit='';
		$functionk=new FunctionsK();
		$t=$functionk->ajaxDataTables($cols);
		if (is_array($t) && count($t)>=1){
			$sWhere=$t['sWhere'];
			$sOrder=$t['sOrder'];
			$sLimit=$t['sLimit'];
		}	
							
				
		$stmt="SELECT SQL_CALC_FOUND_ROWS a.*
		FROM
		{{singleapp_pages}} a
		WHERE 
		merchant_id = ".FunctionsV3::q($merchant_id)."			
		$sOrder
		$sLimit
		";		
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			$res = Yii::app()->request->stripSlashes($res);
			$iTotalRecords=0;				
			if($res2 = Yii::app()->db->createCommand("SELECT FOUND_ROWS()")->queryRow()){
				$iTotalRecords=$res2['FOUND_ROWS()'];
			}	
						
			$feed_data['sEcho']=intval($_GET['sEcho']);
			$feed_data['iTotalRecords']=$iTotalRecords;
			$feed_data['iTotalDisplayRecords']=$iTotalRecords;
		
			foreach ($res as $val) {
				$date_created=Yii::app()->functions->prettyDate($val['date_created'],true);
			    $date_created=Yii::app()->functions->translateDate($date_created);					
			    			    			    			   
			    $page_id = $val['page_id'];
			    $link = Yii::app()->createUrl("/merchant/singlemerchant/settings_add?page_id=".$page_id);
			    
				$action ='<a href="'.$link.'" class="edit_page btn btn-info" data-page_id="'.$page_id.'" ><i class="fas fa-edit" aria-hidden="true"></i></a>';
				$action.='<a href="javascript:;" class="delete_page btn btn-danger row_del" rev="'.$page_id.'" ><i class="fas fa-trash" aria-hidden="true"></i></a>';
			    
			    $status = $val['status'];
			    
			    $val['content'] = stripslashes(strip_tags($val['content']));			   
			    $use_html='';
			    $content =  "<p class=\"concat-text\">".$val['content']."..."."</p>";
			    if ($val['use_html']==2){
			    	$use_html = '<i class="fa fa-check"></i>';			    	
			    }
			    
			    
				$feed_data['aaData'][]=array(
				  $val['page_id'],
				  stripslashes($val['title']),
				  $content,				  
				  $val['icon'],
				  $use_html,
				  $val['sequence'],  
				  $status.'<br/>'.$date_created,
				  $action
				);				
			}
			$this->otableOutput($feed_data);
		}
		$this->otableNodata();	
	}
	
	public function singleMerchantAddPage()
	{
		if ( !Yii::app()->functions->isMerchantLogin()){
			$this->msg=t("ERROR: Your session has expired.");
			return false;
	   }
	   
		$merchant_id = (integer)Yii::app()->functions->getMerchantID();
		
		$validator=new Validator();
		$req=array( 
		  'title'=>t("title is required"),
		  'content'=>t("content is required"),
		  'merchant_id'=>t("merchant id is required"),
		);
		$validator->required($req,$this->data);
		if ( $validator->validate()){
			$params=array(
			  'title'=>$this->data['title'],
			  'content'=>$this->data['content'],
			  'icon'=>isset($this->data['icon'])?$this->data['icon']:'',
			  'sequence'=>isset($this->data['sequence'])?$this->data['sequence']:0,
			  'status'=>$this->data['status'],
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			  'use_html'=>isset($this->data['use_html'])?$this->data['use_html']:1,
			  'merchant_id'=>$merchant_id
			);
			
			if ( Yii::app()->functions->multipleField()==2){				
				if ( $fields=FunctionsV3::getLanguageList(false)){
					foreach ($fields as $f_val){
						$data_title = "title_$f_val";
						$data_content = "content_$f_val";
						$params[$data_title] = isset($this->data[$data_title])?$this->data[$data_title]:'';
						$params[$data_content] = isset($this->data[$data_content])?$this->data[$data_content]:'';
					}
				}				
			}
			
					
			if(!is_numeric($params['use_html'])){
				unset($params['use_html']);
			}					
			if(!is_numeric($params['sequence'])){
				$params['sequence']=0;
			}			
			if(!is_numeric($params['merchant_id'])){
				$params['merchant_id']=0;
			}
			
			$page_id = isset($this->data['page_id'])?(integer)$this->data['page_id']:0;
			
			if($page_id>0){
				unset($params['date_created']);
				$params['date_modified']=FunctionsV3::dateNow();
				$up = Yii::app()->db->createCommand()->update("{{singleapp_pages}}",$params,
		  	    'page_id=:page_id',
			  	    array(
			  	      ':page_id'=>$page_id
			  	    )
		  	    );				
				if($up){
					$this->code = 1;
					$this->msg = t("successfully updated");
					$this->details='';
				} else $this->msg = t("Failed cannot saved records");
			} else {								
				if(Yii::app()->db->createCommand()->insert("{{singleapp_pages}}",$params)){	
					/*$this->details=Yii::app()->createUrl('merchant/singlemerchant/settings_pages',array(
					  'id'=>Yii::app()->db->getLastInsertID(),
					  'merchant_id'=>$merchant_id
					));*/
					$this->code = 1;
					$this->msg = t("Successful");
				} else $this->msg = t("Failed cannot saved records");
			}
			
		} else $this->msg= $validator->getErrorAsHTML();		
	}

	public function singleMerchantContact()
	{
		if ( !Yii::app()->functions->isMerchantLogin()){
			$this->msg=t("ERROR: Your session has expired.");
			return false;
	   }
	   
		$merchant_id = (integer)Yii::app()->functions->getMerchantID();
		
		$this->code = 1;		
	    $this->msg = t("Setting saved");
	    
        Yii::app()->functions->updateOption("singleapp_contact_email",
	    isset($this->data['singleapp_contact_email'])?$this->data['singleapp_contact_email']:''
	    ,$merchant_id);
	    
	    Yii::app()->functions->updateOption("singleapp_contact_tpl",
	    isset($this->data['singleapp_contact_tpl'])?$this->data['singleapp_contact_tpl']:''
	    ,$merchant_id);
	    
	    Yii::app()->functions->updateOption("singleapp_contact_subject",
	    isset($this->data['singleapp_contact_subject'])?$this->data['singleapp_contact_subject']:''
	    ,$merchant_id);
	    
	    Yii::app()->functions->updateOption("singleapp_contactus_fields",
	    isset($this->data['singleapp_contactus_fields'])?json_encode($this->data['singleapp_contactus_fields']):''
	    ,$merchant_id);
	    
	    Yii::app()->functions->updateOption("singleapp_contactus_enabled",
	    isset($this->data['singleapp_contactus_enabled'])?$this->data['singleapp_contactus_enabled']:''
	    ,$merchant_id);
	}
	
} /*END CLASS*/