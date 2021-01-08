<?php
//if (!isset($_SESSION)) { session_start(); }

class AjaxadminController extends CController
{
	
	public $code=2;
	public $msg;
	public $details;
	public $data;
	static $db;
	
	public function __construct()
	{
		$this->data=$_POST;	
		if(isset($_GET['method'])){
			if($_GET['method']=="get"){
			   $this->data=$_GET;
			}
		}								
		self::$db=new DbExt;
	}	
	
	public function beforeAction($action)
	{		
		$action_name= $action->id ;		
		$data = $_POST;					
		$post_action = isset($data['action'])?$data['action']:'';
		$allowed_action = array('login','adminForgotPass');		
					
	    if(!in_array($post_action,$allowed_action)){
			if ( !Yii::app()->functions->isAdminLogin()){
				 $this->msg=t("Error session has expired");
				 $this->jsonResponse();
				 Yii::app()->end();
			}
		}
				
		if (isset($_REQUEST['tbl'])){
		    $data=$_REQUEST;	
		} 
		if(isset($_GET['method'])){
			if($_GET['method']=="get"){
			   $data=$_GET;
			}
		}	
		
		/*ADD SECURITY VALIDATION TO ALL REQUEST*/
		$validate_request_session = Yii::app()->params->validate_request_session;
		$validate_request_csrf = Yii::app()->params->validate_request_csrf;				
		
		if($validate_request_session){
			$session_id=session_id();	
			if(!isset($data['yii_session_token'])){
				$data['yii_session_token']='';
			}
			if($data['yii_session_token']!=$session_id){			
				$this->msg = t("Session token not valid");
				$this->jsonResponse();
				Yii::app()->end();
			}	
		}
		
		if($validate_request_csrf){
			if(!isset($data[Yii::app()->request->csrfTokenName])){
				$data[Yii::app()->request->csrfTokenName]='';
			}				
			if ( $data[Yii::app()->request->csrfTokenName] != Yii::app()->getRequest()->getCsrfToken()){
				$this->msg = t("Request token not valid");
				$this->jsonResponse();
				Yii::app()->end();
			}
		}
		
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
	
	private function jsonResponse()
	{
		$resp=array('code'=>$this->code,'msg'=>$this->msg,'details'=>$this->details);
		echo CJSON::encode($resp);
		Yii::app()->end();
	}
	
	public function actionIndex()
	{		
		if (isset($_REQUEST['tbl'])){
		   $data=$_REQUEST;	
		} else $data=$_POST;
				
		if (isset($data['debug'])){
			dump($data);
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
	
	public function actionTemplate()
	{		
		$email_tags=''; $sms_tags =''; $tag_push='';
		if (isset($this->data['tag_email'])){
			$email_tags=explode(",",$this->data['tag_email']);			
		}
		if (isset($this->data['tag_sms'])){
			$sms_tags=explode(",",$this->data['tag_sms']);			
		}
		if (isset($this->data['tag_push'])){
			$tag_push=explode(",",$this->data['tag_push']);			
		}
		
		array_unshift($email_tags,t("Available Tags"));
		array_unshift($sms_tags,t("Available Tags"));
		$this->renderPartial('/admin/template',array(
		  'data'=>$this->data,
		  'key'=>$this->data['key'],
		  'tag_email'=>$email_tags,
		  'tag_sms'=>$sms_tags,
		  'lang_list'=>FunctionsV3::getLanguageList(),
		  'lang'=>Yii::app()->language,
		  'tag_push'=>$tag_push		  
		));
	}
	
	public function actionsaveTemplate()
	{
		
		$lang=$this->data['template_lang_selection'];		
		if ($lang=="0"){
			$this->msg=t("Invalid language");
		} else {
			$key=$this->data['key'];			
			/*EMAIL*/
			if(isset($this->data['email_subject'])){
				Yii::app()->functions->updateOptionAdmin($key."_tpl_subject_$lang",
				isset($this->data['email_subject'])?$this->data['email_subject']:'');
			}
			
			if(isset($this->data['email_content'])){
				Yii::app()->functions->updateOptionAdmin($key."_tpl_content_$lang",
				isset($this->data['email_content'])?$this->data['email_content']:'');
			}
			
			/*SMS*/		
			if (isset($this->data['sms_content'])){
				Yii::app()->functions->updateOptionAdmin($key."_sms_content_$lang",
				isset($this->data['sms_content'])?$this->data['sms_content']:'');
			}
			
			/*PUSH*/		
			if (isset($this->data['push_content'])){
				Yii::app()->functions->updateOptionAdmin($key."_push_content_$lang",
				isset($this->data['push_content'])?$this->data['push_content']:'');
			}
			if (isset($this->data['push_title'])){
				Yii::app()->functions->updateOptionAdmin($key."_push_title_$lang",
				isset($this->data['push_title'])?$this->data['push_title']:'');
			}
			
			$this->code=1;
			$this->msg=t("Setting saved");
		}
		$this->jsonResponse();
	}
	
	public function actionsaveTemplateSettings()
	{				
				
		$data=$this->data;
		$order_stats = FunctionsV3::orderStatusTPL(2);		
		$predefined=array(
		  'contact_us'."_email",
		  'contact_us'."_sms",
		  'customer_welcome_email'."_email",
		  'customer_welcome_email'."_sms",
		  'customer_forgot_password'."_email",
		  'customer_forgot_password'."_sms",
		  'customer_verification_code_email'."_email",
		  'customer_verification_code_email'."_sms",
		  'customer_verification_code_sms'."_email",
		  'customer_verification_code_sms'."_sms",
		  'merchant_verification_code'."_email",
		  'merchant_verification_code'."_sms",
		  'merchant_forgot_password'."_email",
		  'merchant_forgot_password'."_sms",
		  'admin_forgot_password'."_email",
		  'admin_forgot_password'."_sms",
		  'merchant_new_signup_email',
		  'merchant_new_signup_sms',
		  'receipt_template_email',
		  'receipt_template_sms',
		  'receipt_send_to_merchant_email',
		  'receipt_send_to_merchant_sms',
		  'receipt_send_to_merchant_push',
		  'receipt_send_to_admin_email',
		  'receipt_send_to_admin_sms',
		  'offline_bank_deposit_email',
		  'offline_bank_deposit_sms',
		  'offline_bank_deposit_signup_merchant_email',
		  'offline_bank_deposit_signup_merchant_sms',
		  'offline_bank_deposit_purchase_email',
		  'merchant_near_expiration_email',
		  'merchant_near_expiration_sms',
		  'merchant_change_status_email',
		  'merchant_change_status_sms',
		  'customer_booked_email',
		  'customer_booked_sms',
		  'booked_notify_admin_email',
		  'booked_notify_admin_sms',
		  'booked_notify_merchant_email',
		  'booked_notify_merchant_sms',
		  'booking_update_status_email',
		  'booking_update_status_sms',
		  'booking_update_status_push',
		  'merchant_welcome_signup_email',
		  'merchant_welcome_signup_sms',
		  'order_idle_to_merchant_email',
		  'order_idle_to_merchant_sms',
		  'order_idle_to_admin_email',
		  'order_idle_to_admin_sms',
		  'merchant_invoice_email',
		  'merchant_invoice_sms',
		  'booked_notify_merchant_push',
		  'order_request_cancel_to_merchant_email',
		  'order_request_cancel_to_merchant_sms',
		  'order_request_cancel_to_merchant_push',
		  'order_request_cancel_to_customer_email',
		  'order_request_cancel_to_customer_sms',
		  'order_request_cancel_to_customer_push',
		  
		  'order_request_cancel_to_admin_email',
		  'order_request_cancel_to_admin_sms',
		  'order_request_cancel_to_admin_push',

		  'booking_request_cancel_email',
		  'booking_request_cancel_sms',
		  'booking_request_cancel_push',
		  
		  'offline_new_bank_deposit_email',
		  'offline_new_bank_deposit_sms',		  
		  
		);
		
		$predefined=array_merge($predefined,(array)$order_stats);		
		
		foreach ($predefined as $key) {
			if (array_key_exists($key,$data)){				
				Yii::app()->functions->updateOptionAdmin($key,$data[$key]);
			} else {
				Yii::app()->functions->updateOptionAdmin($key,'');
			}
		}
		
		$this->code=1;
		$this->msg=t("Setting saved");
		$this->jsonResponse();
	}
	
	public function actionloadETemplateByLang()
	{
		$lang=$this->data['lang'];
		$subject = $this->data['key']."_tpl_subject_$lang";
		$content = $this->data['key']."_tpl_content_$lang";
		$sms = $this->data['key']."_sms_content_$lang";
		$push = $this->data['key']."_push_content_$lang";
		$push_title = $this->data['key']."_push_title_$lang";
		
		$subject=getOptionA($subject);
		$content=getOptionA($content);
		$sms=getOptionA($sms);
		$push=getOptionA($push);
		$push_title=getOptionA($push_title);
				
		$this->code=1; $this->msg="OK";
		$this->details=array(
		  'subject'=>$subject,
		  'content'=>$content,
		  'sms'=>$sms,
		  'push'=>$push,
		  'push_title'=>$push_title
		);		
		$this->jsonResponse();
	}
	
	public function actionnotiSettings()
	{				
    		
		Yii::app()->functions->updateOptionAdmin('noti_new_signup_email',
		isset($this->data['noti_new_signup_email'])?$this->data['noti_new_signup_email']:'' );
		
		Yii::app()->functions->updateOptionAdmin('noti_new_signup_sms',
		isset($this->data['noti_new_signup_sms'])?$this->data['noti_new_signup_sms']:'' );
		
		Yii::app()->functions->updateOptionAdmin('noti_receipt_email',
		isset($this->data['noti_receipt_email'])?$this->data['noti_receipt_email']:'' );
		
		Yii::app()->functions->updateOptionAdmin('noti_receipt_sms',
		isset($this->data['noti_receipt_sms'])?$this->data['noti_receipt_sms']:'' );
		
		Yii::app()->functions->updateOptionAdmin('admin_disabled_order_notification',
		isset($this->data['admin_disabled_order_notification'])?$this->data['admin_disabled_order_notification']:'' );
		
		Yii::app()->functions->updateOptionAdmin('admin_disabled_order_notification_sounds',
		isset($this->data['admin_disabled_order_notification_sounds'])?$this->data['admin_disabled_order_notification_sounds']:'' );
		
		Yii::app()->functions->updateOptionAdmin('merchant_near_expiration_day',
		isset($this->data['merchant_near_expiration_day'])?$this->data['merchant_near_expiration_day']:'' );
		
		Yii::app()->functions->updateOptionAdmin('noti_booked_admin_email',
		isset($this->data['noti_booked_admin_email'])?$this->data['noti_booked_admin_email']:'' );
		
		Yii::app()->functions->updateOptionAdmin('order_idle_admin_email',
		isset($this->data['order_idle_admin_email'])?$this->data['order_idle_admin_email']:'' );
		
		Yii::app()->functions->updateOptionAdmin('order_idle_admin_minutes',
		isset($this->data['order_idle_admin_minutes'])?$this->data['order_idle_admin_minutes']:'' );
		
		Yii::app()->functions->updateOptionAdmin('order_cancel_admin_email',
		isset($this->data['order_cancel_admin_email'])?$this->data['order_cancel_admin_email']:'' );
		
		Yii::app()->functions->updateOptionAdmin('order_cancel_admin_sms',
		isset($this->data['order_cancel_admin_sms'])?$this->data['order_cancel_admin_sms']:'' );
		
		$this->code=1;
		$this->msg=t("Setting saved");
		$this->jsonResponse();
	}
	
	public function actionloadCountryDetails()
	{		
		if ( $res=FunctionsV3::locationStateList($this->data['country_id'])){
			$html=Yii::app()->controller->renderPartial('/admin/manage-country-details',array(
			  'data'=>$res
			),true);
			$this->code=1; $this->msg="OK";
			$this->details=$html;
		} else $this->msg=t("Failed loading data");
		$this->jsonResponse();
	}
	
	public function actionaddCity()
	{		
		if ( $data=FunctionsV3::getStateByID($this->data['state_id'])){
			$this->render('admin/manage-loc-addcity',array(
			  'data'=>$data,
			  'state_id'=>$this->data['state_id'],
			  'data2'=>FunctionsV3::getCityByID( isset($this->data['id'])?$this->data['id']:'' )
			));
		} 
	}
	
	public function actionSaveCity()
	{
	
		$params=array(
		  'state_id'=>$this->data['state_id'],
		  'name'=>$this->data['city_name'],
		  'date_created'=>FunctionsV3::dateNow(),
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'postal_code'=>isset($this->data['postal_code'])?$this->data['postal_code']:''
		);
		$DbExt=new DbExt;
		
		if ( isset($this->data['id'])){
			unset($params['date_created']);
			$params['date_modified']=FunctionsV3::dateNow();
			if ( $DbExt->updateData("{{location_cities}}",$params,'city_id',$this->data['id'])){
				$this->msg=t("Successful");	
				$this->code=1;
			} else $this->msg=t("Failed cannot update records");
		} else {
			if ($DbExt->insertData("{{location_cities}}",$params)){
				$this->msg=t("Successful");	
				$this->code=1;
			} else $this->msg=t("ERROR. cannot insert data.");
		}
		$this->jsonResponse();
	}
	
	public function actionDeleteCity()
	{		
		$DbExt=new DbExt;		
		$stmt="SELECT * FROM
		{{location_rate}}
		WHERE
		city_id=".FunctionsV3::q($this->data['id'])."
		";		
		if ( $DbExt->rst($stmt)){
			$this->msg=t("You cannot delete this record it has reference to other tables");
			$this->jsonResponse();
		} else {			
			$DbExt->qry("DELETE FROM
			{{location_cities}}
			WHERE
			city_id=".FunctionsV3::q($this->data['id'])."
			");
			$this->msg=t("Successful");	
			$this->code=1;
			$this->jsonResponse();
		}
	}
	
	public function actionAddState()
	{		
		if ( $data=FunctionsV3::getCountryByID($this->data['country_id'])){
			$this->render('admin/manage-loc-addstate',array(
			  'data'=>$data,		
			  'data2'=>FunctionsV3::getStateByID( isset($this->data['state_id'])?$this->data['state_id']:'' )
			));
		} 
	}
	
	public function actionSaveState()
	{		
		$params=array(
		  'country_id'=>$this->data['country_id'],
		  'name'=>$this->data['name'],
		  'date_created'=>FunctionsV3::dateNow(),
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		$DbExt=new DbExt;		
		if ( isset($this->data['id'])){
			unset($params['date_created']);
			$params['date_modified']=FunctionsV3::dateNow();
			if ( $DbExt->updateData("{{location_states}}",$params,'state_id',$this->data['id'])){
				$this->msg=t("Successful");	
				$this->code=1;
			} else $this->msg=t("Failed cannot update records");
		} else {
			if ($DbExt->insertData("{{location_states}}",$params)){
				$this->msg=t("Successful");	
				$this->code=1;
			} else $this->msg=t("ERROR. cannot insert data.");
		}
		$this->jsonResponse();
	}
	
	public function actionDeleteState()
	{
		$DbExt=new DbExt;
		
		$stmt="SELECT * FROM
		{{location_rate}}
		WHERE
		state_id=".FunctionsV3::q($this->data['id'])."
		";		
		if ( $DbExt->rst($stmt)){
			$this->msg=t("You cannot delete this record it has reference to other tables");
			$this->jsonResponse();
		} else {			
			$DbExt->qry("DELETE FROM
			{{location_states}}
			WHERE
			state_id=".FunctionsV3::q($this->data['id'])."
			");
			$this->msg=t("Successful");	
			$this->code=1;
			$this->jsonResponse();
		}
	}
	
	public function actionAddArea()
	{
		if ( $data=FunctionsV3::getCityByID($this->data['city_id'])){
			$this->render('admin/manage-loc-addarea',array(
			  'data'=>$data,		
			  'data2'=>FunctionsV3::getAreaLocation( isset($this->data['area_id'])?$this->data['area_id']:'' )
			));
		} 
	}
	
	public function actionSaveArea()
	{
		$params=array(
		  'city_id'=>$this->data['city_id'],
		  'name'=>$this->data['name'],
		  'date_created'=>FunctionsV3::dateNow(),
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);		
		$DbExt=new DbExt;		
		if ( isset($this->data['id'])){
			unset($params['date_created']);
			$params['date_modified']=FunctionsV3::dateNow();
			if ( $DbExt->updateData("{{location_area}}",$params,'area_id',$this->data['id'])){
				$this->msg=t("Successful");	
				$this->code=1;
			} else $this->msg=t("Failed cannot update records");
		} else {
			if ($DbExt->insertData("{{location_area}}",$params)){
				$this->msg=t("Successful");	
				$this->code=1;
			} else $this->msg=t("ERROR. cannot insert data.");
		}
		$this->jsonResponse();
	}
	
	public function actionDeleteArea()
	{		
		$DbExt=new DbExt;
		
		$stmt="SELECT * FROM
		{{location_rate}}
		WHERE
		area_id=".FunctionsV3::q($this->data['id'])."
		";		
		if ( $DbExt->rst($stmt)){
			$this->msg=t("You cannot delete this record it has reference to other tables");
			$this->jsonResponse();
		} else {	
			$DbExt->qry("DELETE FROM
			{{location_area}}
			WHERE
			area_id=".FunctionsV3::q($this->data['id'])."
			");
			$this->msg=t("Successful");	
			$this->code=1;
			$this->jsonResponse();
		}
	}
	
	public function actionSortArea()
	{		
		if (isset($this->data['ids'])){
			$DbExt=new DbExt;
			$id=explode(",",$this->data['ids']);
			foreach ($id as $sequence=>$area_id) {
				if(!empty($area_id)){
				   $sequence=$sequence+1;				   
				   $DbExt->updateData("{{location_area}}",array(
				     'sequence'=>$sequence,
				     'date_modified'=>FunctionsV3::dateNow(),
				     'ip_address'=>$_SERVER['REMOTE_ADDR']
				   ),'area_id', $area_id);
				}
				$this->msg="OK";
				$this->code=1;
			}
		} else $this->msg=t("Missing ID");
		$this->jsonResponse();
	}
	
	public function actionSortState()
	{
		if (isset($this->data['ids'])){
			$DbExt=new DbExt;
			$id=explode(",",$this->data['ids']);
			foreach ($id as $sequence=>$id) {
				if(!empty($id)){
				   $sequence=$sequence+1;				   
				   $DbExt->updateData("{{location_states}}",array(
				     'sequence'=>$sequence,
				     'date_modified'=>FunctionsV3::dateNow(),
				     'ip_address'=>$_SERVER['REMOTE_ADDR']
				   ),'state_id', $id);
				}
				$this->msg="OK";
				$this->code=1;
			}
		} else $this->msg=t("Missing ID");
		$this->jsonResponse();
	}
	
	public function actionEditInvoice()
	{
		$this->data=$_GET;		
		if ($res=FunctionsV3::getInvoiceByID($this->data['id'])){
			$this->renderPartial('/admin/invoice-edit',array(
			  'data'=>$res
			));
		} else echo t("No results");
	}
	
	public function actionSaveInvoice()
	{
		$DbExt=new DbExt;		
		$DbExt->updateData("{{invoice}}",array(
		  'payment_status'=>$this->data['payment_status']
		),'invoice_number',$this->data['invoice_number']);
		$this->code=1;
		$this->msg=t("Successful");
		
		$params=array(
		  'invoice_number'=>$this->data['invoice_number'],
		  'payment_status'=>$this->data['payment_status'],
		  'remarks'=>isset($this->data['remarks'])?$this->data['remarks']:'',
		  'date_created'=>FunctionsV3::dateNow(),
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		$DbExt->insertData("{{invoice_history}}",$params);
		
		$this->jsonResponse();
	}
	
	public function actionInvoiceHistory()
	{
		$this->data=$_GET;		
		$res=FunctionsV3::getInvoiceHistory($this->data['id']);
		$this->renderPartial('/admin/invoice-history',array(
		   'invoice_number'=>$this->data['id'],
		   'data'=>$res
		));
	}
	
	public function actionuploadFile()
	{
		require_once('SimpleUploader.php');
		
		if ( !Yii::app()->functions->isAdminLogin()){
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
        	
        	if(!empty($field_name)){
        	  $preview_html .= CHtml::hiddenField( $field_name , $new_filename );
        	}
        	$preview_html.= '<img src="'.$image_url.'" class="uk-thumbnail" id="logo-small" >';
        	if(isset($this->data['preview'])){
	        	$preview_html.='<br/>';
	        	$preview_html.= '<a href="javascript:;" class="sau_remove_file" data-preview="'.$this->data['preview'].'" >'.t("Remove image").'</a>';
        	}
        	
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
	
	public function actionrequestOrderApproved()
	{		
		$order_id_token = isset($this->data['order_id'])?$this->data['order_id']:'';
		if(!empty($order_id_token)){
			if ($res = FunctionsV3::getOrderByToken($order_id_token)){
				$order_id = $res['order_id'];
				
				$cancel_status='cancelled';
				$website_review_approved_status = getOptionA('website_review_approved_status');
				if(empty($website_review_approved_status)){
					$cancel_status = $website_review_approved_status;
				}
				
				$params = array(
				  'request_cancel'=>2,
				  'status'=>$cancel_status,
				  'request_cancel_status'=>'approved',
				  'date_modified'=>FunctionsV3::dateNow(),
				  'ip_address'=>$_SERVER['REMOTE_ADDR']
				);
				
				$db = new DbExt();
				if ( $db->updateData("{{order}}",$params,'order_id',$order_id)){
					
					$params_history=array(
					  'order_id'=>$order_id,
					  'status'=>$cancel_status,
					  'remarks'=>'request to cancel order approved',
					  'date_created'=>FunctionsV3::dateNow(),
					  'ip_address'=>$_SERVER['REMOTE_ADDR']
					);	    				
					$db->insertData("{{order_history}}",$params_history);
										
	                /*UPDATE REVIEWS BASED ON STATUS*/
					if (method_exists('FunctionsV3','updateReviews')){
						FunctionsV3::updateReviews($order_id , $cancel_status);
					}
					
					FunctionsV3::notifyCustomerCancelOrder($res, $params['request_cancel_status'] );
					
					/*UPDATE POINTS BASED ON ORDER STATUS*/
    				if (FunctionsV3::hasModuleAddon("pointsprogram")){
    					if (method_exists('PointsProgram','updateOrderBasedOnStatus')){
						   PointsProgram::updateOrderBasedOnStatus($cancel_status,$order_id);
						}
						if (method_exists('PointsProgram','udapteReviews')){
						   PointsProgram::udapteReviews($order_id,$cancel_status);
						}  					    				
    				}
					
					$this->code =1;
					$this->msg = t("Successful");
					$this->details='';
				} else $this->msg = t("ERROR: cannot update order.");
				unset($db);
			} else t("Order id not found");
		} else $this->msg = t("Order id is required");
		$this->jsonResponse();
	}
		
	public function actionrequestOrderDecline()
	{
		$order_id_token = isset($this->data['order_id'])?$this->data['order_id']:'';
		if(!empty($order_id_token)){
			if ($res = FunctionsV3::getOrderByToken($order_id_token)){
				$order_id = $res['order_id'];
				
				$status = 'decline';				
				
				$params = array(
				  'request_cancel'=>2,				  
				  'request_cancel_status'=>$status,
				  'date_modified'=>FunctionsV3::dateNow(),
				  'ip_address'=>$_SERVER['REMOTE_ADDR']
				);				
				$db = new DbExt();
				if ( $db->updateData("{{order}}",$params,'order_id',$order_id)){
					
					$params_history=array(
					  'order_id'=>$order_id,
					  'status'=>$status,
					  'remarks'=>'request to cancel order has been denied',
					  'date_created'=>FunctionsV3::dateNow(),
					  'ip_address'=>$_SERVER['REMOTE_ADDR']
					);	    				
					$db->insertData("{{order_history}}",$params_history);
					
					FunctionsV3::notifyCustomerCancelOrder($res, t($params['request_cancel_status']) );
					
					$this->code =1;
					$this->msg = t("Successful");
					$this->details='';
				} else $this->msg = t("ERROR: cannot update order.");
				unset($db);
			} else t("Order id not found");
		} else $this->msg = t("Order id is required");
		$this->jsonResponse();
	}	
	
	public function actiongetNewCancelOrderAdmin()
	{
		if ($new_order_count = FunctionsV3::getNewCancelOrderAdmin()){
			$this->code = 1;
			$this->msg = Yii::t("default","You have [count] new cancel order request",array(
				 '[count]'=>$new_order_count
				));
			$this->details='';
		} else $this->msg = t("no results");
		$this->jsonResponse();
	}
	
	public function actionprinterThermalReceipt()
	{
		$data = $_POST;
		$order_id = isset($data['order_id'])?$data['order_id']:'';
		$panel = isset($data['panel'])?$data['panel']:'';
		if($order_id>0){
			try {
				PrintWrapper::doPrint($order_id, $panel);
				$this->code = 1;
				$this->msg = t("Print request has been sent");
			} catch (Exception $e) {
			    $this->msg = $e->getMessage();
			}
		} else $this->msg = t("order id not valid");
		$this->jsonResponse();
	}
	
	public function actiongetNotification()
	{
		$error  = array();
		
		try {
		   NotificationWrapper::checkRequiredFile();
		} catch (Exception $e) {
    		$error[] =  $e->getMessage();
    	}
								    	
    	try {
		   NotificationWrapper::checkCurrency();
		} catch (Exception $e) {
    		$error[] =  $e->getMessage();
    	}
		    	
    	try {
    		/*5.4*/
    	    NotificationWrapper::checkFields('client',array(
    	      'payment_customer_id'=>""
    	    ));    	        	    
    	    NotificationWrapper::checkFields('order',array(
    	      'distance'=>"",
    	      'cancel_reason'=>"",    	      
    	    ));    	       	    
    	    NotificationWrapper::checkFields('merchant',array(
    	      'distance_unit'=>"",
    	      'delivery_distance_covered'=>"",    	      
    	    ));    	        	   
    	    NotificationWrapper::checkFields('bookingtable',array(
    	      'request_cancel'=>""    	      
    	    ));  
    	    NotificationWrapper::checkFields('cuisine',array(
    	      'slug'=>""    	      
    	    ));  
    	    /*5.4*/
    	    
    	} catch (Exception $e) {
    		$error[] =  $e->getMessage();
    	}
    	
    	try {
    	   NotificationWrapper::checkCuisine();
    	} catch (Exception $e) {
    		$error[] =  $e->getMessage();
    	}
    	
    	try {
    	    NotificationWrapper::checkOpeningHours();
    	} catch (Exception $e) {
    		$error[] =  $e->getMessage();
    	}
    	
    	try {
    	    NotificationWrapper::checkDeliveryDistanceCovered();
    	} catch (Exception $e) {
    		$error[] =  $e->getMessage();
    	}
    	    	    	
    	if(is_array($error) && count($error)>=1){    		
    		$this->code = 1;    	
    		$this->msg = "ERROR";
    		$this->details = array(
    		  'count'=>count($error),
    		  'error'=>$error
    		);
    	} else $this->msg = t("No new notification");
		$this->jsonResponse();
	}
	
} /*end class*/