<?php
//if (!isset($_SESSION)) { session_start(); }

class AjaxmerchantController extends CController
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
		if (isset($_REQUEST['tbl'])){		    
		    $this->data=$_REQUEST;	
		} 		
			
		self::$db=new DbExt;
	}	
	
	public function beforeAction($action)
	{
		$action_name= $action->id ;
		
		$data = $_POST;					
		$post_action = isset($data['action'])?$data['action']:'';
		$allowed_action = array('merchantLogin','merchantForgotPass','merchantChangePassword');		
		
		if(!in_array($post_action,$allowed_action)){
			if ( !Yii::app()->functions->isMerchantLogin()){
				 $this->msg=t("Error session has expired");
				 $this->jsonResponse();
				 Yii::app()->end();
			}
		}
		
		
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
	
	public function actionaddNewRates()
	{
		$country_id=getOptionA('location_default_country');		
		$mtid = Yii::app()->functions->getMerchantID();
		if ( $res=FunctionsV3::getMerchantInfo($mtid)){
			if ($resp=FunctionsV3::getDefaultCountrySignup($res['country_code'])){
				$country_id=$resp;
			}
		}		
				
		if (!empty($country_id)){
			$citys=''; $areas='';
			if ($data=FunctionsV3::GetLocationRateByID( isset($this->data['rate_id'])?$this->data['rate_id']:'')){
				$citys=FunctionsV3::ListCityList($data['state_id']);
				$areas=FunctionsV3::AreaList($data['city_id']);
			}		
			$this->renderPartial('/merchant/add-new-rates',array(
			  'default_country_id'=>$country_id,
			  'states'=>FunctionsV3::ListLocationState($country_id),
			  'data'=>$data,
			  'citys'=>$citys,
			  'areas'=>$areas
			));
		}
	}
	
	public function actionLoadStateList()
	{				
		if($data=FunctionsV3::ListLocationState($this->data['country_id'])){
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
	
	public function actionSaveRate()
	{		
		$mtid = Yii::app()->functions->getMerchantID();
		if(!empty($mtid)){
			$params=array(
			  'merchant_id'=>$mtid,
			  'country_id'=>$this->data['rate_country_id'],
			  'state_id'=>$this->data['rate_state_id'],
			  'city_id'=>$this->data['rate_city_id'],
			  'area_id'=>$this->data['rate_area_id'],
			  'fee'=>$this->data['fee'],
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR']
			);			
			$DbExt=new DbExt;
			if ( isset($this->data['rate_id'])){
				if ( $DbExt->updateData("{{location_rate}}",$params,'rate_id',$this->data['rate_id'])){
					$this->code=1; $this->msg=t("Successful");
				} else $this->msg=t("ERROR: cannot update records.");
			} else {
				$stmt_check="
				SELECT * FROM
				{{location_rate}}
				WHERE
				merchant_id=".FunctionsV3::q($mtid)."
				AND
				country_id=".FunctionsV3::q($this->data['rate_country_id'])."
				AND
				state_id=".FunctionsV3::q($this->data['rate_state_id'])."
				AND
				city_id=".FunctionsV3::q($this->data['rate_city_id'])."
				AND
				area_id=".FunctionsV3::q($this->data['rate_area_id'])."
				";
				
				if (!$DbExt->rst($stmt_check)){
					if ( $DbExt->insertData("{{location_rate}}",$params)){
						$this->code=1; $this->msg=t("Successful");
					} else $this->msg=t("ERROR. cannot insert data.");
				} else $this->msg=t("The rate you about to save is already exist");
			}
		} else $this->msg=t("Session Expired");
		$this->jsonResponse();
	}
	
	public function actionLoadTableRates()
	{
		$mtid = Yii::app()->functions->getMerchantID();
		if(!empty($mtid)){
			if ( $res = FunctionsV3::GetLocationRateByMerchantWithName($mtid)){
				$html='';
				foreach ($res as $val) {
					$id=$val['rate_id'];
					$action='<div class="options">';					
					$action.="<a href=\"javascript:;\" data-id=\"$id\" class=\"location_edit\" >".t("Edit")."</a>";
					$action.="<a href=\"javascript:;\" data-id=\"$id\" class=\"location_delete\" >".t("Delete")."</a>";
					$action.="</div>";
					
					$rate_id=$val['rate_id'];
					
					$html.="<tr data-rateid=\"$rate_id\">";
					$html.="<td>".$val['country_name'].$action."</td>";
					$html.="<td>".$val['state_name']."</td>";
					$html.="<td>".$val['city_name']."</td>";
					$html.="<td>".$val['area_name']."</td>";
					$html.="<td>".$val['postal_code']."</td>";
					$html.="<td>".FunctionsV3::prettyPrice($val['fee'])."</td>";
					$html.="</tr>";
				}
				$this->code=1; $this->msg="OK";
				$this->details=$html;
			} else $this->msg=t("No results");
		} else $this->msg=t("Session Expired");
		$this->jsonResponse();
	}
	
	public function actionDeleteLocationRates()
	{	
		$DbExt=new DbExt;
		$stmt="DELETE FROM
		{{location_rate}}
		WHERE
		rate_id=".FunctionsV3::q($this->data['rate_id'])."
		";
		$DbExt->qry($stmt);
		$this->code=1; $this->msg=t("Successful");
		$this->jsonResponse();
	}
	
	public function actionSortTableRates()
	{
		if (isset($this->data['ids'])){
			$DbExt=new DbExt;
			$id=explode(",",$this->data['ids']);
			foreach ($id as $sequence=>$rate_id) {
				if(!empty($rate_id)){
				   $sequence=$sequence+1;				   
				   $DbExt->updateData("{{location_rate}}",array(
				     'sequence'=>$sequence,
				     'date_modified'=>FunctionsV3::dateNow(),
				     'ip_address'=>$_SERVER['REMOTE_ADDR']
				   ),'rate_id', $rate_id);
				}
				$this->msg="OK";
				$this->code=1;
			}
		} else $this->msg=t("Missing ID");
		$this->jsonResponse();
	}
	
	public function actionLoadReviewComment()
	{		
		if ($res=FunctionsV3::reviewReplyList($this->data['parent_id'])){
			$html='';
			foreach ($res as $val) {
				$edit_link=Yii::app()->createUrl('merchant/reviewreply',array(
				  'id'=>$val['parent_id'],
				  'record_id'=>$val['id']
				));
				
				$html.='<div class="replies-list-'.$val['id'].'">';
				$html.='<p style="color:#f00">'.stripslashes($val['reply_from']). ' '. t("reply") . ':</p>';
				$html.='<p>'.stripslashes(nl2br(trim($val['review']))).'</p>';
				$html.='<a href="javascript:;" class="delete-reply" data-id="'.$val['id'].'" >'.t("Delete").'</a>';
				$html.=" | ";
				$html.='<a href="'.$edit_link.'" >'.t("Edit").'</a>';
				$html.='</div>';
			}
			$this->code=1; $this->msg="OK";
			$this->details=array(
			  'html'=>$html,
			  'parent_id'=>$this->data['parent_id']
			);
		} else $this->msg=t("No results");
		$this->jsonResponse();
	}
	
	public function actionDeleteReviewReply()
	{		
		if (isset($this->data['id'])){
			$DbExt=new DbExt;
			$DbExt->qry("
			DELETE FROM {{review}}
			WHERE
			id=".FunctionsV3::q($this->data['id'])."
			");
			$this->code=1; $this->msg="OK";
			$this->details=array(
			  'id'=>$this->data['id']
			);
			unset($DbExt);
		} else $this->msg=t("Missing id");
		$this->jsonResponse();
	}
	
    public function actionuploadFile()
	{
		require_once('SimpleUploader.php');
		
		if ( !Yii::app()->functions->isMerchantLogin()){	
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
	
	public function actionMultipleUploadFile()
	{
		require_once('SimpleUploader.php');
		
		if ( !Yii::app()->functions->isMerchantLogin()){	
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
        	  $preview_html .= CHtml::hiddenField( $field_name."[]" , $new_filename );
        	}
        	$preview_html.='<div class="col">';
        	$preview_html.= '<img src="'.$image_url.'" class="uk-thumbnail"  >';
        	if(isset($this->data['preview'])){	        	
	        	$preview_html.= '<a href="javascript:;" class="multiple_remove_image" data-preview="'.$this->data['preview'].'" >'.t("Remove image").'</a>';	        	
        	}
        	$preview_html.='</div>';
        	
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
				$params = array(
				  'request_cancel'=>2,				  
				  'request_cancel_status'=>'decline',
				  'date_modified'=>FunctionsV3::dateNow(),
				  'ip_address'=>$_SERVER['REMOTE_ADDR']
				);				
				$db = new DbExt();
				if ( $db->updateData("{{order}}",$params,'order_id',$order_id)){
					
					$params_history=array(
					  'order_id'=>$order_id,
					  'status'=>"decline",
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
	
	public function actiongetNewCancelOrder()
	{
		$mtid = Yii::app()->functions->getMerchantID();
		
		if ( !Yii::app()->functions->isMerchantLogin()){	
			$this->msg = t("Session has expired");
			$this->jsonResponse();
		}
		
		if($mtid>0){
			if ( $new_order_count = FunctionsV3::getNewCancelOrder($mtid)){
				$this->code = 1;
				$this->msg = Yii::t("default","You have [count] new cancel order request",array(
				 '[count]'=>$new_order_count
				));
				$this->details='';
			} else $this->msg = t("no results");
		} else $this->msg = t("Invalid merchant id");
		
		$this->jsonResponse();
	}
	
	public function actiongeocode()
	{		
		$address = isset($this->data['address'])?$this->data['address']:'';
		if(!empty($address)){
			if ( $res = Yii::app()->functions->geodecodeAddress($address)){
				$res['lng']=$res['long'];
				unset($res['long']);
				$this->code = 1;
				$this->msg ="OK";
				$this->details = $res;
			} else $this->msg = t("failed");
		} else $this->msg = t("address is required");
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
	
} /*end class*/