<?php
//if (!isset($_SESSION)) { session_start(); }

class AjaxController extends CController
{
	public $layout='_store';	
	public $code=2;
	public $msg;
	public $details;
	public $data;
	
	public function __construct()
	{	
		$this->data=$_POST;	
		if (isset($_GET['post_type'])){
			if ($_GET['post_type']=="get"){
				$this->data=$_GET;	
			}
		}
		
		if (isset($_REQUEST['tbl'])){
		   $this->data=$_REQUEST;	
		} 							
				
	}
	
	public function beforeAction($action)
	{
		/*ADD SECURITY VALIDATION TO ALL REQUEST*/		
		$validate_request_session = Yii::app()->params->validate_request_session;
        $validate_request_csrf = Yii::app()->params->validate_request_csrf;	
        
		if($validate_request_session){
			$session_id=session_id();		
			if(!isset($this->data['yii_session_token'])){
				$this->data['yii_session_token']='';
			}
			if($this->data['yii_session_token']!=$session_id){			
				$this->msg = Yii::t("default","Session token not valid");
				$this->jsonResponse();
				Yii::app()->end();
			}		
		}
				
		if($validate_request_csrf){
			if(!isset($this->data[Yii::app()->request->csrfTokenName])){
				$this->data[Yii::app()->request->csrfTokenName]='';
			}
			if ( $this->data[Yii::app()->request->csrfTokenName] != Yii::app()->getRequest()->getCsrfToken()){
				$this->msg = Yii::t("default","Request token not valid");
				$this->jsonResponse();
				Yii::app()->end();
			}
		}
		
		/*ADD SECURITY VALIDATION TO ALL REQUEST*/	
		return true;
	}
	
	public function init()
	{
		// set website timezone
		$website_timezone=Yii::app()->functions->getOptionAdmin("website_timezone");		 
		if (!empty($website_timezone)){		 	
		 	Yii::app()->timeZone=$website_timezone;
		}		 				 
		FunctionsV3::handleLanguage();
		//echo Yii::app()->language;
	}
	
	public function actionIndex()
	{				
		if (isset($_REQUEST['tbl'])){
		   $data=$_REQUEST;	
		} else $data=$_POST;
				
		if (isset($data['debug'])){
			dump($data);
		}				
		
		$lang = Yii::app()->language;
		
		$post_action_name = isset($data['action'])?$data['action']:'';
		if(empty($post_action_name)){
			Yii::app()->end();
		}
		if(!FunctionsV3::validateFrontAction($post_action_name)){
			$this->msg = Yii::t($lang,"action [name] is not defined",array(
			 '[name]'=>$post_action_name
			));
			$this->jsonResponse();
		}
		
		$class=new AjaxAdmin;
	    $class->data=$data;	 	    
	    if (method_exists($class,$data['action'])){
	    	 $action_name=$data['action'];	    	 
	    	 $class->$action_name();
	         echo $class->output();
	    } else {
	    	 $class=new Ajax;
	    	 $class->data=$data;	    	 
	    	 $action_name=$data['action'];	    	 
	    	 $class->$action_name();
	         echo $class->output();
	    }
	    yii::app()->end();		
	}
	
	private function jsonResponse()
	{
		$resp=array('code'=>$this->code,'msg'=>$this->msg,'details'=>$this->details);
		echo CJSON::encode($resp);
		Yii::app()->end();
	}
	
	private function otableNodata()
	{
		if (isset($_GET['sEcho'])){
			$feed_data['sEcho']=$_GET['sEcho'];
		} else $feed_data['sEcho']=1;	   
		     
        $feed_data['iTotalRecords']=0;
        $feed_data['iTotalDisplayRecords']=0;
        $feed_data['aaData']=array();		
        echo json_encode($feed_data);
    	die();
	}

	private function otableOutput($feed_data='')
	{
	  echo json_encode($feed_data);
	  die();
    }    
    
    public function actionLoadAllRestoMap()
    {
    	$data=array();
    	$stmt=$_SESSION['kmrs_search_stmt'];
    	if (!empty($stmt)){
    		$pos=strpos($stmt,'LIMIT');    		
    		$stmt=substr($stmt,0,$pos-1);       		
    		$DbExt=new DbExt();
    		$DbExt->qry("SET SQL_BIG_SELECTS=1");
    		if ( $res=$DbExt->rst($stmt)){
    			foreach ($res as $val) {    
    				if (!empty($val['latitude']) && !empty($val['lontitude'])){
	    				$data[]=array(
	    				  'restaurant_name'=>stripslashes($val['restaurant_name']),
	    				  'restaurant_slug'=>$val['restaurant_slug'],
	    				  'merchant_address'=>$val['merchant_address'],
	    				  'latitude'=>$val['latitude'],
	    				  'lontitude'=>$val['lontitude'],
	    				  'logo'=>FunctionsV3::getMerchantLogo($val['merchant_id']),
	    				  'link'=>Yii::app()->createUrl('/menu-'.$val['restaurant_slug'])
	    				);
    				}
    			}    			
    			$this->code=1;
    			$this->msg="OK";
    			$this->details=$data;
    		} else $this->msg=t("no records");
    	} else $this->msg=t("invalid statement query");
    	$this->jsonResponse();
    }
    
    public function actionloadAllMerchantMap()
    {    	
    	$datas=array();
    	if ( $data=Yii::app()->functions->getAllMerchant(true)){
    		foreach ($data['list'] as $val) {
    			if (!empty($val['latitude']) && !empty($val['lontitude'])){
    				$datas[]=array(
    				  'restaurant_name'=>stripslashes($val['restaurant_name']),
    				  'restaurant_slug'=>$val['restaurant_slug'],
    				  'merchant_address'=>stripslashes($val['merchant_address']),
    				  'latitude'=>$val['latitude'],
    				  'lontitude'=>$val['lontitude'],
    				  'logo'=>FunctionsV3::getMerchantLogo($val['merchant_id']),
    				  'link'=>Yii::app()->createUrl('menu-'.$val['restaurant_slug'])
    				);
				}
    		}
    		$this->code=1;
			$this->msg="OK";
			$this->details=$datas;
    	} else $this->msg=t("no records");
    	$this->jsonResponse();
    }
    
    public function actionClientCCList()
    {
    	if(  !Yii::app()->functions->isClientLogin() ){
    	   $this->msg=t("ERROR: Your session has expired.");
    	   $this->jsonResponse(); 		   
 	    }
     	    
    	$DbExt=new DbExt;
    	$stmt="SELECT * FROM
		{{client_cc}}		
		WHERE
		client_id ='".Yii::app()->functions->getClientId()."'	
		ORDER BY cc_id DESC
		";						
		if ($res=$DbExt->rst($stmt)){		
		   foreach ($res as $val) {	
		   	    $edit_url=Yii::app()->createUrl('/store/profile/?tab=4&do=add&id='.$val['cc_id']);
				$action="<div class=\"options\">
	    		<a href=\"$edit_url\" ><i class=\"ion-ios-compose-outline\"></i></a>
	    		<a href=\"javascript:;\" data-table=\"client_cc\" data-whereid=\"cc_id\" class=\"delete_client_cc\" data-id=\"$val[cc_id]\" ><i class=\"ion-ios-trash\"></i></a>
	    		</div>";		   	   
		   	   $feed_data['aaData'][]=array(
		   	      $val['card_name'].$action,
		   	      Yii::app()->functions->maskCardnumber($val['credit_card_number']),
		   	      $val['expiration_month']."-".$val['expiration_yr']
		   	   );			       
		   }
		   $this->otableOutput($feed_data);
		}
		$this->otableNodata();			
    }
    
    public function actionUpdateClientCC()
    {
    	
    	$p = new CHtmlPurifier();
    	
    	if (Yii::app()->functions->isClientLogin()){
    	$client_id=Yii::app()->functions->getClientId();    	    	
	    	$params=array(
	    	  'client_id'=>$client_id,
	    	  'card_name'=> $p->purify($this->data['card_name']) ,
	    	  'credit_card_number'=>FunctionsV3::maskCardnumber($p->purify($this->data['credit_card_number'])),
	    	  'expiration_month'=> $p->purify($this->data['expiration_month']),
	    	  'expiration_yr'=> $p->purify($this->data['expiration_yr']),
	    	  'billing_address'=> $p->purify($this->data['billing_address']),
	    	  'cvv'=> $p->purify($this->data['cvv']),
	    	  'date_created'=>FunctionsV3::dateNow(),
	    	  'ip_address'=>$_SERVER['REMOTE_ADDR'],	    	  
	    	  'encrypted_card'=> CreditCardWrapper::encryptCard($p->purify($this->data['credit_card_number'])),
	    	);	    	
	    		    	
	    	$DbExt=new DbExt;
	    	if (isset($this->data['cc_id'])){
	    		unset($params['date_created']);
	    		$params['date_modified']=FunctionsV3::dateNow();	    		
	    		
	    		$stmt="SELECT * FROM
	    		{{client_cc}}
	    		WHERE
	    		client_id=".FunctionsV3::q($client_id)."
	    		AND
	    		cc_id<>".FunctionsV3::q($this->data['cc_id'])."
	    		AND credit_card_number=".FunctionsV3::q($this->data['credit_card_number'])."
	    		
	    		LIMIT 0,1
	    		";	    		
	    		if ($DbExt->rst($stmt)){
	    			$this->msg=t("Credit card number already exist in you credit card list");
	    			$this->jsonResponse();
	    			return ;
	    		}
	    			    		
	    		if ( $DbExt->updateData("{{client_cc}}",$params,'cc_id',$this->data['cc_id'])){
	    			$this->code=1;
	    			$this->msg=t("Card successfully updated.");
	    		} else $this->msg=t("Error cannot saved information");
	    	} else {
	    		if (!Yii::app()->functions->getCCbyCard($this->data['credit_card_number'],$client_id) ){
		    		if ( $DbExt->insertData("{{client_cc}}",$params)){
		    			$cc_id=Yii::app()->db->getLastInsertID();	    			
		    			$redirect=Yii::app()->createUrl('/store/profile/?tab=4&do=add&id='.$cc_id);
		    			
		    			$this->code=1;
		    			$this->msg=t("Card successfully added");
		    			$this->details=array('redirect'=>$redirect);
		    		} else $this->msg=t("Error cannot saved information");
	    		} else $this->msg=t("Credit card number already exist in you credit card list");
	    	}
    	} else $this->msg=t("ERROR: Your session has expired.");
    	$this->jsonResponse();
    }
    
    public function actionsaveAvatar()
    {    	
    	$DbExt=new DbExt;
    	if (!empty($this->data['filename'])){
    		$params=array(
    		  'avatar'=>$this->data['filename'],
    		  'date_modified'=>FunctionsV3::dateNow(),
    		  'ip_address'=>$_SERVER['REMOTE_ADDR']
    		);
    		$client_id=Yii::app()->functions->getClientId();    		
    		if (is_numeric($client_id)){
    			
    			$filename_delete='';
    			if ( $old_data=Yii::app()->functions->getClientInfo($client_id)){
    				if ( $old_data['avatar']!=$params['avatar']){
    					$filename_delete=$old_data['avatar'];
    				}
    			}
    			
    			$DbExt->updateData("{{client}}",$params,'client_id',$client_id);
    			$this->msg=t("You have succesfully change your profile picture");
    			$this->code=1;
    			
    			if($filename_delete){
    				FunctionsV3::deleteUploadedFile($filename_delete);
    			}
    			
    		} else $this->msg=t("ERROR: Your session has expired.");
    	} else $this->msg=t("Filename is empty");
    	$this->jsonResponse();
    }
    
    public function actionViewReceipt()
    {
    	FunctionsV3::isUserLogin();
    	$client_id = Yii::app()->functions->getClientId();
    	$order_id = isset($this->data['order_id'])?$this->data['order_id']:0;    	
    	
    	$map_provider = Yii::app()->functions->getOptionAdmin('map_provider');
		$google_key=getOptionA('google_geo_api_key');
		$website_use_time_picker = Yii::app()->functions->getOptionAdmin('website_use_time_picker');
		$theme_time_pick = Yii::app()->functions->getOptionAdmin('theme_time_pick');
		
		$config = array(
		  'map_provider'=>$map_provider,
		  'google_key'=>$google_key,
		  'website_use_time_picker'=>$website_use_time_picker,
		  'theme_time_pick'=>$theme_time_pick
		);
			
    	/** Register all scripts here*/
    	ScriptManager::registerAllCSSFiles($config);
		$this->render('/store/receipt-front',array(		  
		  'data'=>FunctionsV3::getReceiptByID($order_id,$client_id)
		));
    }
    
    public function actionResendEmailCode()
    {
    	$client_id=isset($this->data['client_id'])?$this->data['client_id']:'';
    	if( $res=Yii::app()->functions->getClientInfo( $client_id )){	
    		FunctionsV3::sendEmailVerificationCode($res['email_address'],$res['email_verification_code'],$res);
    		$this->code=1;
    		$this->msg=t("We have sent verification code to your email address");
    	} else $this->msg=t("Sorry but we cannot find your information.");
    	$this->jsonResponse();
    }
    
    public function actionCityList()
    {    	
    	$post = $_POST; $status = true;
    	$state_id = isset($post['state_id'])?(integer)$post['state_id']:0;
    	$data=FunctionsV3::getCityList($state_id);    	
    	if(!$data){
    		$status=false;
    	}
    	$data = Yii::app()->request->stripSlashes($data);
    	header('Content-Type: application/json');
		echo json_encode(array(
		    "status" => $status,
		    "error"  => null,
		    "data"   => array(
		        "item" => $data,	        
		    )
		));
    	Yii::app()->end();
    }
    
    public function actionAreaList()
    {    
    	$data = array(); $post = $_POST; $status = true; $and='';
    	
    	$id  = isset($post['city_id'])?(integer)$post['city_id']:0;
    	$q  = isset($post['q'])?(integer)$post['q']:'';
    	
    	if($id>0){
    		$and.= " AND city_id =".q($id)." ";
    	}
    	if(!empty($q)){
    		$and.= " AND name LIKE =".q("$q%")." ";
    	}
    	
    	$stmt = "
    	 SELECT area_id,name,city_id FROM
    	 {{location_area}}		 
		 WHERE 1	
		 $and
		 ORDER BY name ASC
		 LIMIT 0,10
		";		       	
    	if($resp = Yii::app()->db->createCommand($stmt)->queryAll()){    	   
    	   $status = true;
    	   $data = Yii::app()->request->stripSlashes($resp);
    	}
    	header('Content-Type: application/json');
		echo json_encode(array(
		    "status" => $status,
		    "error"  => null,
		    "data"   => array(
		        "item" => $data,	        
		    )
		));
    	Yii::app()->end();
    }
    
    public function actionSetLocationSearch()
    {    	    	    	
    	Cookie::setCookie('kr_location_search',json_encode($this->data));    	
    	$this->code=1; $this->msg="OK";
    	$this->details=Yii::app()->createUrl('store/searcharea',array(
    	 'location'=>true
    	));    	
    	$this->jsonResponse();
    }
    
    public function actionCheckLocationData()
    {    	
    	if ( $this->data['delivery_type']=="delivery"){
    		if ( !FunctionsV3::getSearchByLocationData()){
    			$this->code=1;
    			$this->msg=t("No delivery fee selected");
    		} else $this->msg="OK";
    	} else $this->msg="OK";
    	$this->jsonResponse();
    }
    
    public function actionShowLocationFee()
    {
    	$this->data=$_GET;    	
    	$this->renderPartial('/front/location-fee',array(
    	  'data'=>FunctionsV3::GetViewLocationRateByMerchant($this->data['merchant_id'])
    	));
    }
    
    public function actionSetLocationFee()
    {    	
    	if ( $data=FunctionsV3::GetFeeByRateIDView($this->data['rate_id'])){    		    		    		
    		//dump($data);
    		$params=array(    		   
    		  'location_action'=>"SetLocationSearch",
    		  'city_id'=>$data['city_id'],
    		  'city_name'=>$data['city_name'],
    		  'area_id'=>$data['area_id'],
    		  'location_city'=>$data['city_name'],
    		  'location_area'=>$data['area_name'],
    		  'state_id'=>$data['state_id'],
    		  'state_name'=>$data['state_name'],
    		  'postal_code'=>$data['postal_code']
    		);    		
    		//dump($params);
    		Cookie::setCookie('kr_location_search',json_encode($params));    	
    		$this->code=1;
    	    $this->msg="OK";    	        	
    	} else $this->msg=t("Failed getting fee details");
    	$this->jsonResponse();
    }
    
    public function actionLoadState()
    {
    	$country_id = isset($this->data['country_id'])?(integer)$this->data['country_id']:0;
    	if ( $data=FunctionsV3::locationStateList($country_id)){
    	   $data = Yii::app()->request->stripSlashes($data);
		   $html="<option value=\"\">".t("Select state")."</option>";		   
		   foreach ($data as $key=>$val) {		   	  
		   	  $html.="<option value=\"".$val['state_id']."\">".$val['name']."</option>";
		   }		   
		   $this->code=1;
		   $this->msg="OK";
		   $this->details=$html;
		} else $this->msg= t("No results");
		$this->jsonResponse();
    }
    
    public function actionLoadCityList()
	{
		if ( $data=FunctionsV3::ListCityList($this->data['state_id'])){
		   $html='';
		   foreach ($data as $key=>$val) {		   	  
		   	  $html.="<option value=\"".$key."\">".$val."</option>";
		   }		   
		   $this->code=1;
		   $this->msg="OK";
		   $this->details=$html;
		} else $this->msg= t("No results");
		$this->jsonResponse();
	}    
	
	public function actionLoadArea()
	{		
		if ( $data=FunctionsV3::AreaList($this->data['city_id'])){
			$html='';
		    foreach ($data as $key=>$val) {		   	  
		   	   $html.="<option value=\"".$key."\">".$val."</option>";
		    }		   
		    $this->code=1;
		    $this->msg="OK";
		    $this->details=$html;
		} else $this->msg= t("No results");
		$this->jsonResponse();
	}	
	
	public function actionLoadPostCodeByArea()
	{		
		$DbExt=new DbExt;
		$stmt="SELECT 
		a.area_id,
		a.city_id,
		b.city_id as city_ids,
		b.postal_code
		FROM {{location_area}} a
		left join {{location_cities}} b
        on
        a.city_id=b.city_id	   
        WHERE
        a.area_id=".FunctionsV3::q($this->data['area_id'])."
		";
		if($res=$DbExt->rst($stmt)){
			$this->code=1;
			$this->msg="OK";
			$this->details=$res[0]['postal_code'];
		} else $this->msg=t("No results");
		$this->jsonResponse();
	}
	
    public function actionStateList()
    {    	
    	$data=array();$status = false;
    	$country_id = (integer) FunctionsV3::getLocationDefaultCountry();
    	$stmt="
   	    SELECT state_id,name,country_id
   	     FROM
   	    {{location_states}}
   	    WHERE
   	    country_id=".q($country_id)."
   	    ORDER BY name ASC   	   
   	   ";    	
    	if($resp = Yii::app()->db->createCommand($stmt)->queryAll()){
    		$data = Yii::app()->request->stripSlashes($resp);
    	}    		    
    	header('Content-Type: application/json');
		echo json_encode(array(
		    "status" => $status,
		    "error"  => null,
		    "data"   => array(
		        "item" => $data,	        
		    )
		));
    	Yii::app()->end();	
    }	
    
    public function actionPostalCodeList()
    {
    	$data=array(); $state_ids='';  $status = false;
    	$country_id=FunctionsV3::getLocationDefaultCountry();
    	if ( $res=FunctionsV3::locationStateList($country_id)){
    		foreach ($res as $val) {
    			$state_ids.="'$val[state_id]',";
    		}
    		$state_ids=substr($state_ids,0,-1);       		
    		$stmt="
    		SELECT rate_id ,postal_code as name
    		FROM
    		{{view_location_rate}}
    		WHERE
    		state_id IN ($state_ids)
    		GROUP BY postal_code
    		";    		
    		if($resp = Yii::app()->db->createCommand($stmt)->queryAll()){   
    			$data = Yii::app()->request->stripSlashes($resp);		
    			$status = true;	
    		}
    	}    	    	
    	header('Content-Type: application/json');
		echo json_encode(array(
		    "status" => $status,
		    "error"  => null,
		    "data"   => array(
		        "item" => $data,	        
		    )
		));
    	Yii::app()->end();			
    }
    
    public function actionAgeRestriction()
    {
    	
    	$this->renderPartial('/front/age-restriction',array(
    	  'restriction_content'=>getOptionA('age_restriction_content'),
    	  'restriction_exit_link'=>getOptionA('age_restriction_exit_link'),
    	));
    }
    
    public function actioncancelOrder()
    {    	
    	$client_id = Yii::app()->functions->getClientId();
    	if(!is_numeric($client_id)){
    		$this->msg = t("ERROR: Your session has expired.");
    		$this->jsonResponse();
    	}
    	$order_token= isset($this->data['id'])?$this->data['id']:'';
    	if(!empty($order_token)){
    		if ( $res = FunctionsV3::getOrderByToken($order_token)){        			
    			if($res['request_cancel']==1){
    				$this->msg = t("Request cancellation for this order is already sent to merchant");
    				$this->jsonResponse();
    			}    			
    			$order_id = $res['order_id'];
    			if($res['client_id']== $client_id){	   	    
    				
    				$params = array(
    				  'request_cancel'=>1,
    				  'date_modified'=>FunctionsV3::dateNow(),
    				  'ip_address'=>$_SERVER['REMOTE_ADDR']
    				);
    				$db = new DbExt();
    				if ( $db->updateData("{{order}}",$params,'order_id',$order_id)){    						
		    			FunctionsV3::notifyCancelOrder($res);
		    			$this->code = 1;
		    			$this->msg = t("Your request has been sent to merchant");
		    			$this->details=array(
		    			  'id'=>"order_id_$order_token",
		    			  'new_div'=>'<div class="pending_for_review label label-success">'.t('Pending for review').'</div>'
		    			);
    				} else $this->msg = t("ERROR: cannot update records.");
	    			
    			}else $this->msg = t("Sorry but this order does not belong to you");
    		} else $this->msg = t("Order id not found");
    	} else $this->msg=t("Order id is missing");
    	$this->jsonResponse();
    }
        
    public function actionUploadProfile()
	{
		require_once('SimpleUploader.php');
		
		if (!Yii::app()->functions->isClientLogin()){
			$this->msg = t("Session has expired");
			$this->jsonResponse();
		}
		
		/*create htaccess file*/		    
		$path_to_upload=Yii::getPathOfAlias('webroot')."/upload/";	    		    	
	    if(!file_exists($path_to_upload)) {	
           if (!@mkdir($path_to_upload,0777)){
           	    $this->msg=Yii::t("default","Cannot create upload folder. Please create the upload folder manually on your rood directory with 777 permission.");
           	    return ;
           }		    
	    }
		    
		$htaccess = FunctionsV3::htaccessForUpload();
		$htfile=$path_to_upload.'.htaccess';		    
	    if (!file_exists($htfile)){
	    	$myfile = fopen($htfile, "w") or die("Unable to open file!".$htfile);    
            fwrite($myfile, $htaccess);        
            fclose($myfile);
	    }
		
		$field_name = isset($this->data['field'])?$this->data['field']:'';		
		
		$path_to_upload=Yii::getPathOfAlias('webroot')."/upload";
        $valid_extensions = FunctionsV3::validImageExtension();
        if(!file_exists($path_to_upload)) {	
           if (!@mkdir($path_to_upload,0777)){           	               	
           	    $this->msg=AddonMobileApp::t("Error has occured cannot create upload directory");
                $this->jsonResponse();
           }		    
	    }
	    
	    $Upload = new FileUpload('uploadfile');
        $ext = $Upload->getExtension(); 
        $time=time();
        $filename = $Upload->getFileNameWithoutExt();       
        $new_filename =  "$time-$filename.$ext";        
        $Upload->newFileName = $new_filename;
        $Upload->sizeLimit = FunctionsV3::imageLimitSize();
        $result = $Upload->handleUpload($path_to_upload, $valid_extensions);          
        if (!$result) {                    	
            $this->msg=$Upload->getErrorMsg();            
        } else {         	
        	$image_url = Yii::app()->getBaseUrl(true)."/upload/".$new_filename;        	
        	$preview_html='';
        	        	
        	$preview_html.= '<img src="'.$image_url.'" class="img-circle">';
        	
        	$this->code=1;
        	$this->msg=t("upload done");        	        
			$this->details=array(
			 'new_filename'=>$new_filename,
			 'url'=>$image_url,
			 'preview_html'=>$preview_html
			);
        }	   
		$this->jsonResponse();
	}	    
	
	public function actiondelAddressBookLocation()
	{		
		$client_id = Yii::app()->functions->getClientId();
		if($client_id>0){
			$id = isset($this->data['id'])?$this->data['id']:'';
			if($id>0){
				$stmt="
				DELETE FROM
				{{address_book_location}}
				WHERE
				id=".FunctionsV3::q($id)."
				";
				$db = new DbExt();
				$db->qry($stmt);
				$this->code = 1;
				$this->msg ="OK";
			} else $this->msg = t("Invalid id");
		} else $this->msg = t("Session has expired");
		$this->jsonResponse();
	}
	
	public function actionloadTimeList()
	{		
		$now=date('Y-m-d');
		$merchant_id = isset($this->data['merchant_id'])?$this->data['merchant_id']:'';
		
		if($merchant_id<=0){
			$this->msg = t("Merchant id is required");
			$this->jsonResponse();
		}
		
		$date_selected = isset($this->data['date_selected'])?$this->data['date_selected']:$now;		
		$delivery_time_list = FunctionsV3::getTimeList($merchant_id,$date_selected);
		
		$new_time_list = '';
		
		if(is_array($delivery_time_list) && count($delivery_time_list)>=1){
			foreach ($delivery_time_list as $key=>$val) {				
				$new_time_list.="<option value=\"$key\">$val</option>";
			}
		}
				
		$this->code = 1;
		$this->msg = "OK";
		$this->details = $new_time_list;
		
		$this->jsonResponse();
	}
	
	public function actionloadMenu()
	{
		$merchant_id = isset($this->data['merchant_id'])?$this->data['merchant_id']:'';
		if($this->data['merchant_id']>0){			
			$day_selected = date("l",strtotime($this->data['date_selected']));			
			$menu=Yii::app()->functions->getMerchantMenu($merchant_id , '' , $day_selected ); 			
			if(is_array($menu) && count($menu)>=1){
				$this->code = 1;
				$this->msg = "OK";
												
				$enabled_food_search_menu = getOptionA('enabled_food_search_menu');
				
				/*CHECK DISABLED ORDERING FROM ADMIN AND MERCHANT SETTINGS*/			
				$disabled_addcart = getOption($merchant_id,'merchant_disabled_ordering');
				if(empty($disabled_addcart)){
					$merchant_master_disabled_ordering= getOption($merchant_id,'merchant_master_disabled_ordering');
					if($merchant_master_disabled_ordering==1){
						$disabled_addcart="yes";
					}
				}
				
				$tpl = $this->renderPartial('/front/menu-ajax',array(
				   'merchant_id'=>$merchant_id,
				   'menu'=>$menu,
		    	  'enabled_food_search_menu'=>$enabled_food_search_menu,
		    	  'is_preview'=>false,
		    	  'disabled_addcart'=>$disabled_addcart,
		    	  'tc'=>getOptionA('theme_menu_colapse'),
		    	),true);
		    	
		    	$this->details = $tpl;
		    	
		    	/*CLEAR CART*/
		    	unset($_SESSION['kr_item']);
				
			} else $this->msg = t("This restaurant has not published their menu yet.");
		} else $this->msg = t("Invalid merchant id");
		$this->jsonResponse();
	}
	
	public function actionmapbox_geocode()
	{
		$address = isset($this->data['address'])?$this->data['address']:'';
		if(!empty($address)){
			if ($res = Yii::app()->functions->geodecodeAddress($address)){
				$this->code = 1;
				$this->msg = "OK";
				$res['lng']=$res['long'];				
				$this->details = $res;
			} else $this->msg = t("Failed geocoding");
		} else $this->msg = t("Address is required");
		$this->jsonResponse();
	}
	
	public function actiongeocode()
	{		
		$lat = isset($this->data['lat'])?$this->data['lat']:'';
		$lng = isset($this->data['lng'])?$this->data['lng']:'';
		if (!empty($lat) && !empty($lng)){			
			if ($address = FunctionsV3::latToAdress($lat,$lng)){
				$this->code = 1;
				$this->msg = "OK";
				$this->details = $address['formatted_address'];
			} else $this->msg = t("Failed cannot locate coordinates");
		} else $this->msg = t("Latitude or longtitude is empty");
		$this->jsonResponse();
	}
    
	public function actionloadAddressByLocation()
	{
		if (Yii::app()->functions->isClientLogin()){
		   $client_id = Yii::app()->functions->getClientId(); 		
		   $id = isset($this->data['id'])?$this->data['id']:'';
		   if($id>0){
		   	  if ($res = FunctionsV3::getAddressByLocation($id)){
		   	  	  $this->code = 1;
		   	  	  $this->msg = "ok";
		   	  	  $this->details = $res;
		   	  } else $this->msg = t("record not found");
		   } else $this->msg = t("invalid address book id");
		} else $this->msg=t("ERROR: Your session has expired.");
		$this->jsonResponse();
	}   
	
	public function actionUploadDeposit()
	{
		require_once('SimpleUploader.php');
				
		/*create htaccess file*/		    
		$path_to_upload=Yii::getPathOfAlias('webroot')."/upload/";	    		    	
	    if(!file_exists($path_to_upload)) {	
           if (!@mkdir($path_to_upload,0777)){
           	    $this->msg=Yii::t("default","Cannot create upload folder. Please create the upload folder manually on your rood directory with 777 permission.");
           	    return ;
           }		    
	    }
		    
		$htaccess = FunctionsV3::htaccessForUpload();
		$htfile=$path_to_upload.'.htaccess';		    
	    if (!file_exists($htfile)){
	    	$myfile = fopen($htfile, "w") or die("Unable to open file!".$htfile);    
            fwrite($myfile, $htaccess);        
            fclose($myfile);
	    }
		
		$field_name = isset($this->data['field'])?$this->data['field']:'';		
		
		$path_to_upload=Yii::getPathOfAlias('webroot')."/upload";
        $valid_extensions = FunctionsV3::validImageExtension();
        if(!file_exists($path_to_upload)) {	
           if (!@mkdir($path_to_upload,0777)){           	               	
           	    $this->msg=AddonMobileApp::t("Error has occured cannot create upload directory");
                $this->jsonResponse();
           }		    
	    }
	    
	    $Upload = new FileUpload('uploadfile');
        $ext = $Upload->getExtension(); 
        $time=time();
        $filename = $Upload->getFileNameWithoutExt();       
        $new_filename =  "$time-$filename.$ext";        
        $Upload->newFileName = $new_filename;
        $Upload->sizeLimit = FunctionsV3::imageLimitSize();
        $result = $Upload->handleUpload($path_to_upload, $valid_extensions);          
        if (!$result) {                    	
            $this->msg=$Upload->getErrorMsg();            
        } else {         	
        	$image_url = Yii::app()->getBaseUrl(true)."/upload/".$new_filename;        	
        	$preview_html='';
        	        	
        	$preview_html.= '<img src="'.$image_url.'" >';
        	
        	$this->code=1;
        	$this->msg=t("upload done");        	        
			$this->details=array(
			 'new_filename'=>$new_filename,
			 'url'=>$image_url,
			 'preview_html'=>$preview_html
			);
        }	   
		$this->jsonResponse();
	}
	
	public function actionaddToFavorite()
	{		
		if(  Yii::app()->functions->isClientLogin() ){
			$client_id = Yii::app()->functions->getClientId();
			$id = isset($this->data['id'])?$this->data['id']:'';
			if($id>0 && $client_id>0){
				$res = FunctionsV3::addToFavorites($client_id,$id);
				$this->code = 1;
				$this->msg="OK";
				$this->details = array(
				  'added'=>$res==true?1:2,
				  'id'=>$id
				);
			} else $this->msg=t("Invalid id");
		} else $this->msg = t("You need to login to add this restaurant to your favorites");
		$this->jsonResponse();
	}
	
	public function actionloadFavorites()
	{
		$this->msg="";
		if(  Yii::app()->functions->isClientLogin() ){
			$client_id = Yii::app()->functions->getClientId();
			if($client_id>0){
				if ($res = FunctionsV3::getCustomerFavorites($client_id)){
					$this->code = 1;
					$this->msg = "OK";
					$this->details = array(
					  'data'=>$res
					);
				}
			}
		}
		$this->jsonResponse();
	}
	
	public function actionremoveFavorite()
	{
		$this->msg =t("Failed");
		if(  Yii::app()->functions->isClientLogin() ){
			$id = isset($this->data['id'])?$this->data['id']:'';
			$client_id = Yii::app()->functions->getClientId();
			if($id>0 && $client_id>0){
				FunctionsV3::removeFavorites($client_id,$id);
				$this->code = 1;
				$this->msg="OK";
				$this->details=$id;
			}
		}
		$this->jsonResponse();
	}
	
	public function actiongetRemainingReview()
	{		
		$mtid = isset($this->data['mtid'])?$this->data['mtid']:'';		
		if(  Yii::app()->functions->isClientLogin() && $mtid>0 ){
			$client_id = Yii::app()->functions->getClientId();
			
			if($res = FunctionsK::getRemainingReview($client_id,$mtid)){				
				$this->code = 1;
				$this->msg = "ok" ;
				
				if($res>1){
					$message =  Yii::t("default","You've got [count] reviews left for this Merchant. Please rate",array(
					  '[count]'=>$res
					));
				} else {
					$message =  Yii::t("default","You've got [count] review left for this Merchant. Please rate",array(
					  '[count]'=>$res
					));
				}
				
				$count_mgs = $message;
				
				$html='<div class="review_notification_wrap rounded">'.$count_mgs.'</div>';
				
				$this->details = array(
				  'remaining'=>$res,
				  'count_msg'=>$html
				);
			} else {
				$website_review_type = getOptionA('website_review_type');
				if($website_review_type==1 && $client_id>0){
					$this->code = 1;
					$this->msg = '';
					$this->details = array(
					  'remaining'=>0,
					  'count_msg'=>''
					);
				}  else $this->msg = t("no remaining review");				
			}
			
		} else $this->msg = t("not login");
		$this->jsonResponse();
	}
	
    /*START CODE FOR MOVING FROM AJAXADMIN.PHP*/
	
    public function actionsubscribeNewsletter()
    {
    	$db=new DbExt();
    	$validator=new Validator;
		$req=array(
		   'subscriber_email'=>t("Email is required")
		);
		$req_email=array(
		  'subscriber_email'=>t("Email address seems invalid")
		);
		$validator->required($req,$this->data);
		$validator->email($req_email,$this->data);
		
		if ( Yii::app()->functions->getSubsriberEmail($this->data['subscriber_email']) ){
			$validator->msg[]=t("Sorry your Email address is already exist in our records.");
		}
				
		if ( $validator->validate()){				
			$params=array(
			  'email_address'=>$this->data['subscriber_email'],
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR']
			);				
			if ( $db->insertData("{{newsletter}}",$params)){
				$this->code=1;
			    $this->msg=t("Thank you for subscribing to our mailing list!");
			} else $this->msg=t("Sorry there is error while we saving your information.");			
		} else $this->msg=$validator->getErrorAsHTML();
		$this->jsonResponse();
    }	

   public function actionloadItemCart()
    {	    		    	
    	// loadcart
    	//dump($this->data);
    	//dump($_SESSION['kr_item']);
    	
    	if (isset($this->data['merchant_id'])){	    		
    		$current_merchant_id=$this->data['merchant_id'];	    
    		if (isset($_SESSION['kr_item'])) {		
	    		if (is_array($_SESSION['kr_item']) && count($_SESSION['kr_item'])>=1){
	    			foreach ($_SESSION['kr_item'] as $key=>$temp_item) {	    				
	    				if ( $temp_item['merchant_id']!=$current_merchant_id){
	    					unset($_SESSION['kr_item'][$key]);
	    				}	    				    				
	    			}
	    		}	    	
    		}
    	}	    
    		    
    	/*tip amount*/
    	if ( !isset($this->data['current_page'])){
    		$this->data['current_page']='';
    	}
    	
    	$mtid=isset($this->data['merchant_id'])?$this->data['merchant_id']:'';
    		    
    	if ( $this->data['current_page']!="menu"){		    	
	    	$tip_enabled = getOption($mtid,'merchant_enabled_tip');
	    	$default_tip = getOption($mtid,'merchant_tip_default');
	    	if ($tip_enabled==2){	    		
	    	    $this->data['tip_enabled']=$tip_enabled;    	
	    	    $this->data['tip_percent']=$default_tip;
	    	    	    	    
	    	    if (isset($this->data['cart_tip_percentage'])){	    	    		    	    	
	    	    	if (strlen($this->data['cart_tip_percentage'])>=1){
	    	    		$this->data['tip_percent'] = $this->data['cart_tip_percentage'];
	    	    	}	    	    
	    	    }
	    	}
    	}
    		    	    
    	/*dump($this->data);
    	dump($_SESSION['kr_item']);*/
    	
    	Yii::app()->functions->displayOrderHTML($this->data, isset($_SESSION['kr_item'])?$_SESSION['kr_item']:'' );
    	$this->code=Yii::app()->functions->code;
    	$this->msg=Yii::app()->functions->msg;
    	$this->details=Yii::app()->functions->details;	    	
    	//dump(Yii::app()->functions->details['raw']);
    	//dump($this->details['html']);	  
    	
    	/*SET ORDER SUB TOTAL*/
    	unset($_SESSION['kmrs_subtotal']);
    	$raw = Yii::app()->functions->details['raw'];
    	if(isset($raw['total'])){
    		if(isset($raw['total']['subtotal'])){
    		   $_SESSION['kmrs_subtotal']=$raw['total']['subtotal'];
    		}	    	
    	}	    	    		    	
    		    	
    	/*ITEM TAXABLE CART*/	    	 
    	if(!empty($mtid)){
	    	$apply_tax=getOption($mtid,'merchant_apply_tax');
	    	$tax=FunctionsV3::getMerchantTax($mtid);
	    	if ( $apply_tax==1 && $tax>0){		    		
	    		/*$new_data=ItemTaxable::compute( $this->details['raw'] , $tax, $mtid);
	    		$this->details['raw']=$new_data;	*/
	    		$this->details['html']=Yii::app()->controller->renderPartial('/front/cart-with-tax',array(
	    		   'data'=>$this->details['raw'],
	    		   'tax'=>$tax,
	    		   'receipt'=>false,
	    		   'merchant_id'=>$mtid
	    		),true);
	    	}	    
    	}		   
    	$this->jsonResponse();
    }	    

    public function actiondelete_addressbook()
    {
    	if(  !Yii::app()->functions->isClientLogin() ){
    	   $this->msg=t("ERROR: Your session has expired.");
    	   $this->jsonResponse(); 		   
 	    }
 	    
    	$id = isset($this->data['id'])?$this->data['id']:'';
    	if($id>0){
    		$client_id = Yii::app()->functions->getClientId();
    		$stmt="DELETE FROM {{address_book}}
    		WHERE id=".FunctionsV3::q($id)."
    		AND client_id = ".FunctionsV3::q($client_id)."
    		";
    		$db=new DbExt();
    		$db->qry($stmt);
    		$this->code = 1;
    		$this->msg = "OK";
    		$this->details='';
    	} else $this->msg =t("invalid id");
    	$this->jsonResponse();
    }
    
    public function actiondeleteClientCC()
    {
    	if(  !Yii::app()->functions->isClientLogin() ){
    	   $this->msg=t("ERROR: Your session has expired.");
    	   $this->jsonResponse(); 		   
 	    }
 	    
 	    $id = isset($this->data['id'])?$this->data['id']:'';
 	    if($id>0){
 	    	$client_id = Yii::app()->functions->getClientId();
 	    	$stmt="DELETE FROM
 	    	{{client_cc}}
 	    	WHERE
 	    	cc_id=".FunctionsV3::q($id)."
 	    	AND
 	    	client_id = ".FunctionsV3::q($client_id)."
 	    	";
 	    	$db=new DbExt();
 	    	$db->qry($stmt);
 	    	unset($db);
 	    	$this->code=1;
 	    	$this->msg="OK";
 	    } else $this->msg = t("invalid id");
 	    $this->jsonResponse();
    }
    
    public function actionrequestCancelBooking()
    {
    	$pattern = 'booking_id,restaurant_name,number_guest,date_booking,time,booking_name,email,mobile,instruction,status,merchant_remarks,sitename,siteurl';
    	$pattern = explode(",",$pattern);
    	    	
    	$lang = Yii::app()->language;
    	$client_id = Yii::app()->functions->getClientId();
    	if(!is_numeric($client_id)){
    		$this->msg = t("ERROR: Your session has expired.");
    		$this->jsonResponse();
    	}
    	$booking_id = isset($this->data['id'])?(integer)$this->data['id']:'';
    	if ($res = FunctionsV3::getBookingByIDWithDetails($booking_id)){    		
    		
    		if($res['request_cancel']>=1){
    			$this->msg = t("You have already request to cancel this booking");
    		    $this->jsonResponse();
    		}
    		
    		$res['sitename'] = getOptionA('website_title');
    		$res['siteurl'] = websiteUrl();
    		$res = Yii::app()->request->stripSlashes($res);
    		
    		$merchant_id = $res['merchant_id'];
    		$merchant_email = getOption($merchant_id,'merchant_notify_email');
    		$sender = getOptionA('global_admin_sender_email');
    		$email_provider  = getOptionA('email_provider');
    		    	
    		/*SEND EMAIL TO MERCHANT*/
    		if(!empty($merchant_email)){
	    		$email = getOptionA('booking_request_cancel_email');    		
	    		$subject = getOptionA('booking_request_cancel_tpl_subject_'.$lang);
	    		$content = getOptionA('booking_request_cancel_tpl_content_'.$lang);
	    		foreach ($pattern as $val) {    			
	    			$content = FunctionsV3::smarty($val, isset($res[$val])?$res[$val]:'' ,$content);
	    			$subject = FunctionsV3::smarty($val, isset($res[$val])?$res[$val]:'' ,$subject);
	    		}
	    		$merchant_email = explode(",",$merchant_email);
	    		if(is_array($merchant_email) && count($merchant_email)>=1){
	    			foreach ($merchant_email as $_mail) {
	    				$params = array(
	    				  'email_address'=>$_mail,
	    				  'sender'=>$sender,
	    				  'subject'=>$subject,
	    				  'content'=>$content,
	    				  'date_created'=>FunctionsV3::dateNow(),
	    				  'ip_address'=>$_SERVER['REMOTE_ADDR'],
	    				  'email_provider'=>$email_provider,	    				  
	    				);
	    				Yii::app()->db->createCommand()->insert("{{email_logs}}",$params);
	    			}
	    		}	    			    			    	
    		}
    		
    		/*SMS*/	    		
    		$balance=Yii::app()->functions->getMerchantSMSCredit($merchant_id);	
    		$phone = getOption($merchant_id,'merchant_cancel_order_phone');	    		
    		if(!empty($phone) && $balance>0){	    			
    		    $sms_content = getOptionA('booking_request_cancel_sms_content_'.$lang);
    		    foreach ($pattern as $val) {    			
    			   $sms_content = FunctionsV3::smarty($val, isset($res[$val])?$res[$val]:'' ,$sms_content);	    			   
    		    }
    		    $params = array(
    		      'merchant_id'=>$merchant_id,
    		      'contact_phone'=>$phone,
    		      'sms_message'=>$sms_content,
    		      'date_created'=>FunctionsV3::dateNow(),
    		      'ip_address'=>$_SERVER['REMOTE_ADDR']
    		    );
    		    Yii::app()->db->createCommand()->insert("{{sms_broadcast_details}}",$params);    		    
    		}    		
    		
    		/*PUSH*/	    		
    		if(Yii::app()->db->schema->getTable("{{mobile_device_merchant}}")){
    			
    			$push_enabled=getOptionA('booking_request_cancel_sms');
    			$push_title=getOptionA('booking_request_cancel_push_title_'.$lang);
    			$push_message=getOptionA('booking_request_cancel_push_content_'.$lang);
    			
    			$resp = Yii::app()->db->createCommand()
		          ->select()
		          ->from('{{mobile_device_merchant}}')   
		          ->where("merchant_id=:merchant_id AND enabled_push=:enabled_push AND status=:status",array(
		             ':merchant_id'=>$merchant_id,			             
		             ':enabled_push'=>1,
		             ':status'=>'active'
		          )) 
		          ->limit(1)
		          ->queryAll();	
		        if($resp && $push_enabled==1){
		        	
		        	foreach ($pattern as $val) {    			
    			       $push_title = FunctionsV3::smarty($val, isset($res[$val])?$res[$val]:'' ,$push_title);
    			       $push_message = FunctionsV3::smarty($val, isset($res[$val])?$res[$val]:'' ,$push_message);
    		        }
		        	
		        	foreach ($merchant_resp as $merchant_device_id) {
		        		$params_merchant = array(
		        		  'merchant_id'=>(integer)$merchant_id,
		        		  'user_type'=>$merchant_device_id['user_type'],
		        		  'merchant_user_id'=>(integer)$merchant_device_id['merchant_user_id'],
		        		  'device_platform'=>$merchant_device_id['device_platform'],
		        		  'device_id'=>$merchant_device_id['device_id'],
		        		  'push_title'=>$push_title,
		        		  'push_message'=>$push_message,
		        		  'date_created'=>FunctionsV3::dateNow(),
		        		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		        		  'booking_id'=>(integer)$booking_id
		        		);
		        		Yii::app()->db->createCommand()->insert("{{mobile_merchant_pushlogs}}",$params_merchant);
		        	}
		        }
    		}
    		
	
    		Yii::app()->db->createCommand()->update("{{bookingtable}}",
    		 array(
    		   'request_cancel'=>1,
    		   'status'=>'request_cancel_booking'
    		 )
    		,
      	     'booking_id=:booking_id',
      	     array(
      	       ':booking_id'=>$booking_id
      	     )
      	   );
    		
    		$this->code = 1;
    		$this->msg = t("Your request has been sent to merchant");
    		$this->details = array(
    		  'booking_id'=>$booking_id
    		);
    		
    	} else $this->msg = t("Booking id not found");
    	$this->jsonResponse();
    }
    
    public function actionverify_change_password_code()
    {
    	$token = isset($this->data['token'])?trim($this->data['token']):'';
    	$sms_code = isset($this->data['sms_code'])?trim($this->data['sms_code']):'';
    	if($res = Yii::app()->functions->getLostPassToken($token)){    		
    		if($res['mobile_verification_code']==$sms_code){    			
    			$new_password = isset($this->data['new_password'])?$this->data['new_password']:'';
    			$confirm_new_password = isset($this->data['confirm_new_password'])?$this->data['confirm_new_password']:'';
    			if(!empty($new_password) && $new_password==$confirm_new_password){
    				
    			    Yii::app()->db->createCommand()->update("{{client}}",
		    		 array(
		    		   'password'=>md5($new_password),
		    		   'ip_address'=>$_SERVER['REMOTE_ADDR'],
		    		   'date_modified'=>FunctionsV3::dateNow()
		    		 )
		    		,
		      	     'client_id=:client_id',
		      	     array(
		      	       ':client_id'=>$res['client_id']
		      	     )
		      	   );   			
    			   $this->code = 1; $this->msg = t("You have successfully change your password");
    			   $this->details = array(
    			    'redirect'=>Yii::app()->createUrl("store/changepassword_successful",array(
    			     'token'=>$token
    			    ))
    			   );
    				
    			} else $this->msg = t("Password is not valid");
    		} else $this->msg = t("Invalid verification code");
    	} else $this->msg = t("Invalid token");
    	$this->jsonResponse();
    }
    
    
    public function actionRestaurantName()
    {    	    	
    	$name = isset($_POST['q'])?$_POST['q']:'';
    	$data=array();$status = false;    	
    	$stmt="
   	    SELECT restaurant_name as name
   	    FROM {{merchant}}
   	    WHERE restaurant_name LIKE ".q("$name%")."
   	    ORDER BY restaurant_name ASC 
   	    LIMIT 0,10
   	   ";    	    	
    	if($resp = Yii::app()->db->createCommand($stmt)->queryAll()){    		
    		$data = Yii::app()->request->stripSlashes($resp);
    	}    		    
    	header('Content-Type: application/json');
		echo json_encode(array(
		    "status" => $status,
		    "error"  => null,
		    "data"   => array(
		        "item" => $data,	        
		    )
		));
    	Yii::app()->end();	
    }	    
    
    public function actionAutoStreetName()
    {
    	$name = isset($_POST['q'])?$_POST['q']:'';
    	$data=array();$status = false;    	
    	$stmt="
   	    SELECT street as name
   	    FROM {{merchant}}
   	    WHERE street LIKE ".q("$name%")."
   	    AND
		status ='active'
		AND
		is_ready='2'
		GROUP BY street		
		ORDER BY street ASC 
   	    LIMIT 0,10
   	   ";    	    	
    	if($resp = Yii::app()->db->createCommand($stmt)->queryAll()){    		
    		$data = Yii::app()->request->stripSlashes($resp);
    	}    		    
    	header('Content-Type: application/json');
		echo json_encode(array(
		    "status" => $status,
		    "error"  => null,
		    "data"   => array(
		        "item" => $data,	        
		    )
		));
    	Yii::app()->end();	
    }
    
    public function actionAutoCuisine()
    {
    	$name = isset($_POST['q'])?$_POST['q']:'';
    	$data=array();$status = false;    	
    	$stmt="
   	    SELECT cuisine_name as name
   	    FROM {{cuisine}}
   	    WHERE cuisine_name LIKE ".FunctionsV3::q("$name%")."
   	    AND status ='publish'
   	    ORDER BY cuisine_name ASC   	    
   	    LIMIT 0,10
   	   ";    	    	
    	if($resp = Yii::app()->db->createCommand($stmt)->queryAll()){    		
    		$data = Yii::app()->request->stripSlashes($resp);
    	}    		    
    	header('Content-Type: application/json');
		echo json_encode(array(
		    "status" => $status,
		    "error"  => null,
		    "data"   => array(
		        "item" => $data,	        
		    )
		));
    	Yii::app()->end();	
    }
    
    public function actionAutoFoodName()
    {
    	$name = isset($_POST['q'])?$_POST['q']:'';
    	$data=array();$status = false;    	
    	$stmt="
   	    SELECT item_name as name
   	    FROM {{item}}
   	    WHERE item_name LIKE ".FunctionsV3::q("$name%")."
   	    AND status ='publish'
   	    ORDER BY item_name ASC   	    
   	    LIMIT 0,10
   	   ";    	    	
    	//dump($stmt);
    	if($resp = Yii::app()->db->createCommand($stmt)->queryAll()){    		
    		$data = Yii::app()->request->stripSlashes($resp);
    	}    		    
    	header('Content-Type: application/json');
		echo json_encode(array(
		    "status" => $status,
		    "error"  => null,
		    "data"   => array(
		        "item" => $data,	        
		    )
		));
    	Yii::app()->end();	
    }    
    
    public function actionautoFoodItem()
    {    	
    	$name = isset($_POST['q'])?$_POST['q']:'';
    	$merchant_id = isset($_POST['auto_merchant_id'])?(integer)$_POST['auto_merchant_id']:'';
    	$data=array();$status = false;    	
    	$stmt="
   	    SELECT item_name as name
   	    FROM {{item}}
   	    WHERE item_name LIKE ".FunctionsV3::q("$name%")."
   	    AND merchant_id=".q($merchant_id)."
   	    AND status ='publish'
   	    ORDER BY item_name ASC   	    
   	    LIMIT 0,10
   	   ";    	    	    	
    	if($resp = Yii::app()->db->createCommand($stmt)->queryAll()){    		
    		$data = Yii::app()->request->stripSlashes($resp);
    	}    		    
    	header('Content-Type: application/json');
		echo json_encode(array(
		    "status" => $status,
		    "error"  => null,
		    "data"   => array(
		        "item" => $data,	        
		    )
		));
    	Yii::app()->end();	
    }        

} /*end class*/    