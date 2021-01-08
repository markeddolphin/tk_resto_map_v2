<?php
class Functions extends CApplicationComponent
{	
	public $data;
	public $sms_msg;
	
	public $code=2;
	public $msg;
	public $details;
	public $db_ext;
	public $has_session=false;
	
	public $search_result_total=0;
	
	public $additional_details;
	
	public function __construct()
	{
		$this->db_ext=new DbExt; 		
	}
	
	public function isAdminLogin()
	{						
		$is_login=FALSE;				
		/*if (!empty($_COOKIE['kr_user'])){
			$user=json_decode($_COOKIE['kr_user']);									
			if (is_numeric($user[0]->admin_id)){
				$is_login=TRUE;
			}
		}*/				
		if ($this->validateAdminSession()){
			return true;
		}			
		return false;
	}
	
	public function getAdminInfo()
	{
		/*if (!empty($_COOKIE['kr_user'])){
			$user=json_decode($_COOKIE['kr_user']);									
			if (is_numeric($user[0]->admin_id)){
				return $user[0];
			}
		}*/		
		if (!empty($_SESSION['kr_user'])){
			$user=json_decode($_SESSION['kr_user']);									
			if (is_numeric($user[0]->admin_id)){
				return $user[0];
			}
		}
		return false;
	}
	
	public function getAdminId()
	{
		if (!empty($_SESSION['kr_user'])){
			$user=json_decode($_SESSION['kr_user']);									
			if (is_numeric($user[0]->admin_id)){
				return $user[0]->admin_id;
			}
		}
		return false;
	}	
	
	public function isMerchantLogin()
	{						
		$is_login=FALSE;						
		if (!empty($_SESSION['kr_merchant_user'])){
			$user=json_decode($_SESSION['kr_merchant_user']);									
			if (is_numeric($user[0]->merchant_id)){
				$is_login=TRUE;
			}
		}
		if ($is_login){
			return true;
		}
		return false;
	}
	
	public function getMerchantID()
	{
		if (!empty($_SESSION['kr_merchant_user'])){
			$user=json_decode($_SESSION['kr_merchant_user']);			
			if (is_array($user) && count($user)>=1){
				return $user[0]->merchant_id;
			}
		}
		return false;
	}		
	
	public function getMerchantUserName()
	{
		if (!empty($_SESSION['kr_merchant_user'])){
			$user=json_decode($_SESSION['kr_merchant_user'],true);			
			//dump($user);
			if (is_array($user) && count($user)>=1){
				//return ucwords($user[0]->contact_name);
				if (isset($user[0]['first_name'])){
					return $user[0]['first_name'];
				} else return $user[0]['contact_name'];
			}
		}
		return false;
	}
	
	public function getMerchantInfo()
	{
		if (!empty($_SESSION['kr_merchant_user'])){
			$user=json_decode($_SESSION['kr_merchant_user']);			
			if (is_array($user) && count($user)>=1){
				return $user;
			}
		}
		return false;
	}
	
	public function CountryList()
	{
		$cuntry_list=require 'CountryCode.php';  
		return $cuntry_list;
	}
	
	public function Cuisine($list=true)
	{
		$lists[]='Please select';
		$lists=array();
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		      {{cuisine}}
		      ORDER BY sequence ASC
		";
		if ( $res=$DbExt->rst($stmt)){
			if ($list){
				foreach ($res as $val) {	
					
					$cuisine_json['cuisine_name_trans']=!empty($val['cuisine_name_trans'])?
	    		    json_decode($val['cuisine_name_trans'],true):'';
									
					//$lists[$val['cuisine_id']]=ucwords($val['cuisine_name']);
					$lists[$val['cuisine_id']]=qTranslate($val['cuisine_name'],'cuisine_name',$cuisine_json);
				}
				return $lists;
			}
			return $res;
		}
		return false;
	}
	
	public function GetCuisine($id='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{cuisine}}
		WHERE
		cuisine_id=".FunctionsV3::q($id)."
		LIMIT 0,1
		";
		if ($res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;	
	}
	
	public function GetCuisineByName($name='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{cuisine}}
		WHERE
		cuisine_name LIKE ".FunctionsV3::q("%$name%")."
		LIMIT 0,1
		";		
		//cuisine_name LIKE '%$name%'		
		if ($res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;	
	}	
	
	public function Services()
	{
		return array(
		  1=>Yii::t("default","Delivery & Pickup"),
		  2=>Yii::t("default","Delivery Only"),
		  3=>yii::t("default","Pickup Only"),
		  4=>t("Delivery / Pickup / Dinein"),
		  5=>t("Delivery & Dinein"),
		  6=>t("Pickup & Dinein"),
		  7=>t("Dinein Only")
		);
	}
	
	public function DeliveryOptions($merchant_id='')
	{		
		if ( $res=$this->getMerchant($merchant_id)){
			switch ($res['service']) {
				case 2:
					return array(
			           'delivery'=>Yii::t("default","Delivery"),
			        );
					break;
				case 3:
					return array(
			            'pickup'=>Yii::t("default","Pickup")          
			        );
					break;
					
				case 4:	
				   return array(
			           'delivery'=>Yii::t("default","Delivery"),
			           'pickup'=>Yii::t("default","Pickup"),
			           'dinein'=>t("Dinein")
			        );
				   break;
				   
				case 5:	
				   return array(
			           'delivery'=>Yii::t("default","Delivery"),			           
			           'dinein'=>t("Dinein")
			        );
				   break;
				   
				case 6:	
				   return array(
			           'pickup'=>Yii::t("default","Pickup"),
			           'dinein'=>t("Dinein")
			        );
				   break;   
				  
				case 7:	
				   return array(			           
			           'dinein'=>t("Dinein")
			        );
				   break;       
				      
				default:
					return array(
			           'delivery'=>Yii::t("default","Delivery"),
			           'pickup'=>Yii::t("default","Pickup") 
			        );
					break;
			}
		} else {
			return array(
			  'delivery'=>Yii::t("default","Delivery"),
			  'pickup'=>Yii::t("default","Pickup") 
			);
		}
	}
	
	public function isMerchantExist($contact_email='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{merchant}}
		WHERE
		contact_email=".FunctionsV3::q($contact_email)."
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res;
		}
		return false;
	}
	
	public function getMerchant($merchant_id='')
	{		
		$stmt="SELECT a.*,
		(
		select title
		from
		{{packages}}
		where
		package_id=a.package_id
		) as package_name
		 FROM
		{{merchant}} a
		WHERE
		merchant_id=".FunctionsV3::q($merchant_id)."
		LIMIT 0,1
		";		
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
			return $res;
		}
		return false;
	}	
		
	public function getMerchantBySlug($slug_id='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{merchant}}
		WHERE
		restaurant_slug=".q($slug_id)."
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}	
	
	public function getMerchantByToken($token='')
	{
		if (empty($token)){
			return false;
		}	
		$DbExt=new DbExt;
		$stmt="SELECT a.*,
		(
		select title from
		{{packages}}
		where
		package_id = a.package_id
		) as package_name
		
		FROM
		{{merchant}} a
		WHERE
		activation_token=".FunctionsV3::q($token)."
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}		
		
	
	public function createSlug($merchant_name='')
	{
		//$slug_id=str_replace(" ","-",$merchant_name);	
		//$slug_id=$this->seo_friendly_url($merchant_name);
		//restaurant_name LIKE '%".addslashes($merchant_name)."%'		
		$merchant_name=str_replace("'",'',$merchant_name);		
		$DbExt=new DbExt;
		$stmt="SELECT count(*) as total FROM
		{{merchant}}
		WHERE
		restaurant_name LIKE ".FunctionsV3::q("%$merchant_name%")."
		LIMIT 0,1
		";						
		if ( $res=$DbExt->rst($stmt)){			
			if ($res[0]['total']==0){
				return $this->seo_friendly_url($merchant_name);
			} else {
				return $this->createSlug($merchant_name.$res[0]['total']);
			}		
		}
		return $this->seo_friendly_url($merchant_name);
	}
		
    public function jsLanguageAdmin()
    {
    	
    	$link="<a href=\"".Yii::app()->request->baseUrl."/merchant/MerchantStatus/"."\">".Yii::t("default","click here to renew membership")."</a>";
    	return array(
    	  "deleteWarning"=>Yii::t("default","You are about to permanently delete the selected items.\n'Cancel' to stop, 'OK' to delete.?"),
    	  "checkRowDelete"=>Yii::t("default","Please check on of the row to delete."),
    	  "removeFeatureImage"=>Yii::t("default","Remove image"),
    	  "removeFiles"=>Yii::t("default","Remove Files"),
    	  "lastTotalSales"=>Yii::t("default","Last 30 days Total Sales"),
    	  "lastItemSales"=>Yii::t("default","Last 30 days Total Sales By Item"),
    	  "NewOrderStatsMsg"=>Yii::t("default","New Order has been placed."),
    	  
    	  'Hour'=>Yii::t("default","Hour"),
    	  'Minute'=>Yii::t("default","Minute"),
    	  'processing'=>Yii::t("default","processing."),
    	  'merchantStats'=>Yii::t("default","Your merchant membership is expired. Please renew your membership.").$link,
    	  "Status"=>Yii::t("default","Status"),
    	  
    	  "tablet_1"=>Yii::t("default","No data available in table"),
    	  "tablet_2"=>Yii::t("default","Showing _START_ to _END_ of _TOTAL_ entries"),
    	  "tablet_3"=>Yii::t("default","Showing 0 to 0 of 0 entries"),
    	  "tablet_4"=>Yii::t("default","(filtered from _MAX_ total entries)"),
    	  "tablet_5"=>Yii::t("default","Show _MENU_ entries"),
    	  "tablet_6"=>Yii::t("default","Loading..."),
    	  "tablet_7"=>Yii::t("default","Processing..."),
    	  "tablet_8"=>Yii::t("default","Search:"),
    	  "tablet_9"=>Yii::t("default","No matching records found"),
    	  "tablet_10"=>Yii::t("default","First"),
    	  "tablet_11"=>Yii::t("default","Last"),
    	  "tablet_12"=>Yii::t("default","Next"),
    	  "tablet_13"=>Yii::t("default","Previous"),
    	  "tablet_14"=>Yii::t("default",": activate to sort column ascending"),
    	  "tablet_15"=>Yii::t("default",": activate to sort column descending"),
    	      	  
    	  "trans_1"=>Yii::t("default","Please rate the restaurant before submitting your review!"),
    	  "trans_2"=>Yii::t("default","Sorry but you can select only"),
    	  "trans_3"=>Yii::t("default","addon"),
    	  "trans_4"=>Yii::t("default","Are you sure?"),
    	  "trans_5"=>Yii::t("default","Sorry but Minimum order is"),
    	  "trans_6"=>Yii::t("default","Please select payment method"),
    	  "trans_7"=>Yii::t("default","Mobile number is required"),
    	  "trans_8"=>Yii::t("default","Please select your credit card"),
    	  "trans_9"=>Yii::t("default","Map not available"),
    	  "trans_10"=>Yii::t("default","Are you sure you want to delete this review? This action cannot be undone."),
    	  "trans_11"=>Yii::t("default","Add your recent order to cart?"),
    	  "trans_12"=>Yii::t("default","Got a total of _TOTAL_ Merchant to show (_START_ to _END_)"),
    	  "trans_13"=>Yii::t("default","Got a total of _TOTAL_ Records to show (_START_ to _END_)"),
    	  "trans_14"=>Yii::t("default","ERROR:"),
    	  "trans_15"=>Yii::t("default","Sorry but this merchant delivers only with in "),
    	  "trans_16"=>Yii::t("default","miles"),
    	  "trans_17"=>Yii::t("default","Notice: Your merchant will not show on search result. Click on Publish your merchant."),
    	  "trans_18"=>Yii::t("default","Continue?"),
    	  "trans_19"=>Yii::t("default","You are about to send SMS to"),
    	  "trans_20"=>Yii::t("default","customer"),
    	  "trans_21"=>Yii::t("default","Browse"),
    	  "trans_22"=>Yii::t("default","Invalid Voucher code"),
    	  "trans_23"=>Yii::t("default","Remove Voucher"),
    	  "trans_24"=>Yii::t("default","Use Voucher"),
    	  "trans_25"=>Yii::t("default","Please enter your origin"),
    	  "trans_26"=>Yii::t("default","Error: Something went wrong"),
    	  "trans_27"=>Yii::t("default","No results found"),
    	  "trans_28"=>Yii::t("default","Geocoder failed due to:"),
    	  "trans_29"=>Yii::t("default","Please select price"),
    	  "trans_30"=>Yii::t("default","Sorry this merchant is closed."),
    	  'Prev'=>Yii::t("default","Prev"),
    	  'Next'=>Yii::t("default","Next"),
    	  'Today'=>Yii::t("default","Today"),
    	  'January'=>Yii::t("default","January"),
    	  'February'=>Yii::t("default","February"),
    	  'March'=>Yii::t("default","March"),
    	  'April'=>Yii::t("default","April"),
    	  'May'=>Yii::t("default","May"),
    	  'June'=>Yii::t("default","June"),
    	  'July'=>Yii::t("default","July"),
    	  'August'=>Yii::t("default","August"),
    	  'September'=>Yii::t("default","September"),
    	  'October'=>Yii::t("default","October"),
    	  'November'=>Yii::t("default","November"),
    	  'December'=>Yii::t("default","December"),
    	  'Jan'=>Yii::t("default","Jan"),
    	  'Feb'=>Yii::t("default","Feb"),
    	  'Mar'=>Yii::t("default","Mar"),
    	  'Apr'=>Yii::t("default","Apr"),
    	  'May'=>Yii::t("default","May"),
    	  'Jun'=>Yii::t("default","Jun"),
    	  'Jul'=>Yii::t("default","Jul"),
    	  'Aug'=>Yii::t("default","Aug"),
    	  'Sep'=>Yii::t("default","Sep"),
    	  'Oct'=>Yii::t("default","Oct"),
    	  'Nov'=>Yii::t("default","Nov"),
    	  'Dec'=>Yii::t("default","Dec"),
    	  'Sun'=>Yii::t("default","Sun"),
    	  'Mon'=>Yii::t("default","Mon"),
    	  'Tue'=>Yii::t("default","Tue"),
    	  'Wed'=>Yii::t("default","Wed"),
    	  'Thu'=>Yii::t("default","Thu"),
    	  'Fri'=>Yii::t("default","Fri"),
    	  'Sat'=>Yii::t("default","Sat"), 	  
    	  'Su'=>Yii::t("default","Su"),
    	  'Mo'=>Yii::t("default","Mo"),
    	  'Tu'=>Yii::t("default","Tu"),
    	  'We'=>Yii::t("default","We"),
    	  'Th'=>Yii::t("default","Th"),
    	  'Fr'=>Yii::t("default","Fr"),
    	  'Sa'=>Yii::t("default","Sa"),
    	  'Hour'=>Yii::t("default","Hour"),
    	  'Minute'=>Yii::t("default","Minute"),
    	  'AM'=>Yii::t("default","AM"),
    	  'PM'=>Yii::t("default","PM"),
    	  "trans_31"=>Yii::t("default","Sorry but Maximum order is"),
    	  "trans_32"=>Yii::t("default","Select Some Options"),
    	  "trans_33"=>Yii::t("default","No results match"),
    	  "trans_34"=>Yii::t("default","New Booking Table"),
    	  "trans_35"=>t("Restaurant name"),
    	  "trans_36"=>t("Address"),
    	  "trans_37"=>t("Order Now"),
    	  "trans_38"=>t("Pickup Time"),
    	  "trans_39"=>t("Delivery Time"),
    	  "trans_40"=>t("Please select payment provider"),
    	  "trans_41"=>t("Pickup Time is required"),
    	  "trans_42"=>t("Pickup Date is required"),
    	  "trans_43"=>t("Delivery Date is required"),
    	  "trans_44"=>t("Delivery Time is required"),
    	  "trans_45"=>t("Tip"),
    	  "trans_46"=>t("You must select price for left and right flavor"),
    	  'trans_47'=>t("You must select at least one addon"),
    	  'trans_48'=>t("Please drag the marker to select your address"),
    	  'trans_49'=>t("You can drag the map marker"),
    	  'trans_50'=>t("Is this address correct"),
    	  'trans_51'=>t("Sorry but this item is not available"),
    	  'trans_52'=>t("Please validate Captcha"),
    	  'trans_53'=>t("SMS code is required"),
    	      	  
    	  'find_restaurant_by_name'=>t("Find restaurant by name"),
    	  'find_restaurant_by_streetname'=>t("Find by street name"),
    	  'find_restaurant_by_cuisine'=>t("Find restaurant by cuisine"),
    	  'find_restaurant_by_food'=>t("Find restaurant by food"),
    	  'read_more'=>t("Read more"),
    	  'close'=>t("Close"),
    	  'close_fullscreen'=>t("Close fullscreen"),
    	  'view_fullscreen'=>t("View in fullscreen"),
    	  'not_authorize'=>t("You are not authorize with this app"),
    	  'not_login_fb'=>t("Sorry but you are not login with facebook"),
    	  'login_succesful'=>t("Login Successful"),
    	  'you_cannot_edit_order'=>t("You cannot edit this order since you have redeem points"),
    	  'dinein_time_is_required'=>t("Dinein time is required"),
    	  'dinein_time'=>t("Dine in time"),
    	  'invalid_file_ext'=>t("Invalid File extension"),
    	  'invalid_file_size'=>t("Invalid File size"),
    	  'upload_failed'=>t("Upload failed"),
    	  'search_placeholder'=>t("Street Address,City,State"),
    	  'geolocation_failed'=>t("Geolocation failed"),
    	  'browser_not_supported'=>t("Your browser does not support geolocation"),
    	  'select_from_map'=>t("select your location from map"),
    	  'enter_full_name'=>t("please enter name on card eg. [first name][space][last name]"),
    	  'not_rated_yet'=>t("Not rated yet!"),
    	);
    }   	
    
    public function jsLanguageValidator()
    {
    	$js_lang=array(
		  'requiredFields'=>Yii::t("default","You have not answered all required fields"),
		  'groupCheckedTooFewStart'=>Yii::t("default","Please choose at least"),
		  'badEmail'=>Yii::t("default","You have not given a correct e-mail address"),
		);
		return $js_lang;
    }
    
    public function getCategory($cat_id='')
    {
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{category}}
			WHERE
			cat_id=".FunctionsV3::q($cat_id)."
			ORDER BY cat_id DESC			
		";		
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
    }		
    
    public function getCategory2($cat_id='')
    {
    	$mid=$this->getMerchantID();
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{category}}
			WHERE
			cat_id=".FunctionsV3::q($cat_id)."
			AND
			merchant_id=".FunctionsV3::q($mid)."
			ORDER BY cat_id DESC			
		";		
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
    }		    
    
    public function getCategoryList($merchant_id='')
	{
		$data_feed=array();
		$stmt="
		SELECT * FROM
		{{category}}
		WHERE 
		merchant_id= ".FunctionsV3::q($merchant_id)."
		ORDER BY sequence ASC
		";					
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 				
		if (is_array($rows) && count($rows)>=1){
			if ($this->data=="list"){
				foreach ($rows as $val) {									   
				   $data_feed[$val['cat_id']]=$val['category_name'];
				}
				return $data_feed;
			} else return $rows;
		}
		return FALSE;
	}    
    
    public function getSize($id='')
    {
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{size}}
			WHERE
			size_id=".FunctionsV3::q($id)."
			LIMIT 0,1			
		";		
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
    }		
    
    public function getSizeList($merchant_id)
    {    	
    	$data_feed[]='';
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{size}}
			WHERE
			merchant_id=".FunctionsV3::q($merchant_id)."
			ORDER BY sequence ASC			
		";			    
		if ( $res=$DbExt->rst($stmt)){			
			if ($this->data=="list"){
				foreach ($res as $val) {									   
				   $data_feed[$val['size_id']]=$val['size_name'];
				}
				return $data_feed;
			} else return $res;
		}
		return false;
    }		
    
    public function getSizeListAll()
    {    	
    	$data_feed[]='';
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{size}}		
			ORDER BY sequence ASC			
		";			    
		if ( $res=$DbExt->rst($stmt)){			
			if ($this->data=="list"){
				foreach ($res as $val) {									   
				   $data_feed[$val['size_id']]=$val['size_name'];
				}
				return $data_feed;
			} else return $res;
		}
		return false;
    }		    
    
    public function getCookingRef($id='')
    {
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{cooking_ref}}
			WHERE
			cook_id=".FunctionsV3::q($id)."
			LIMIT 0,1
		";		
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
    }		    
    
    public function getCookingRefList($merchant_id='')
    {
    	$data_feed=array();
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{cooking_ref}}
			WHERE
			merchant_id=".FunctionsV3::q($merchant_id)."
			ORDER BY sequence ASC			
		";		
		if ( $res=$DbExt->rst($stmt)){			
			if ($this->data=="list"){
				foreach ($res as $val) {									   
				   $data_feed[$val['cook_id']]=$val['cooking_name'];
				}
				return $data_feed;
			} else return $res;
		}
		return false;
    }		    
    
    public function getCookingRefAll()
    {
    	$data_feed=array();
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{cooking_ref}}			
			ORDER BY sequence ASC			
		";		
		if ( $res=$DbExt->rst($stmt)){			
			if ($this->data=="list"){
				foreach ($res as $val) {									   
				   $data_feed[$val['cook_id']]=$val['cooking_name'];
				}
				return $data_feed;
			} else return $res;
		}
		return false;
    }		        
    
    public function getAddonCategory($subcat_id='')
    {
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{subcategory}}
			WHERE
			subcat_id=".FunctionsV3::q($subcat_id)."
			ORDER BY subcat_id DESC			
		";		
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
    }
    public function getAddonCategory2($subcat_id='')
    {
    	$mid=$this->getMerchantID();
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{subcategory}}
			WHERE
			subcat_id=".FunctionsV3::q($subcat_id)."
			AND
			merchant_id='$mid'
			ORDER BY subcat_id DESC			
		";		    
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
    }
    
    public function getSubcategory()
	{
		$data_feed=array();
		$stmt="
		SELECT * FROM
		{{subcategory}}
		WHERE
		merchant_id=".FunctionsV3::q(Yii::app()->functions->getMerchantID())."
		ORDER BY sequence ASC
		";		
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 				
		if (is_array($rows) && count($rows)>=1){
			if ($this->data=="list"){
				foreach ($rows as $val) {									   
				   $data_feed[$val['subcat_id']]=$val['subcategory_name'];
				}
				return $data_feed;
			} else return $rows;
		}
		return FALSE;
	}
		
    public function getSubcategory2($merchant_id='')
	{
		if (isset($_SESSION['kr_merchant_id'])){			
			$merchant_id=$_SESSION['kr_merchant_id'];
		}		
		$data_feed=array();
		$stmt="
		SELECT * FROM
		{{subcategory}}
		WHERE
		merchant_id=".FunctionsV3::q($merchant_id)."
		AND status in ('publish','published')
		ORDER BY sequence ASC
		";		
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 				
		if (is_array($rows) && count($rows)>=1){
			if ($this->data=="list"){
				foreach ($rows as $val) {									   
				   $data_feed[$val['subcat_id']]=$val['subcategory_name'];
				}
				return $data_feed;
			} else return $rows;
		}
		return FALSE;
	}
		
    public function getAddonItem($sub_item_id='',$sortby='sub_item_id')
    {
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{subcategory_item}}
			WHERE
			sub_item_id=".FunctionsV3::q($sub_item_id)."
			AND status in ('publish','published')
			ORDER BY $sortby DESC			
		";			    
		if ( $res=$DbExt->rst($stmt)){			
			return $res[0];
		}
		return false;
    }
    public function getAddonItem2($sub_item_id='',$sortby='sub_item_id')
    {
    	$mid=$this->getMerchantID();
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{subcategory_item}}
			WHERE
			sub_item_id=".FunctionsV3::q($sub_item_id)."
			AND
			merchant_id=".FunctionsV3::q($mid)."
			ORDER BY $sortby DESC			
		";			    
		if ( $res=$DbExt->rst($stmt)){			
			return $res[0];
		}
		return false;
    }    
    
    public function getAddonItemListByMerchant($merchant_id='')
    {
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{subcategory_item}}
			WHERE
			merchant_id=".FunctionsV3::q($merchant_id)."
			ORDER BY sequence ASC
		";			    
		if ( $res=$DbExt->rst($stmt)){			
			return $res;
		}
		return false;
    }
    
    public function getAddonItemList($category='')
    {
    	$data_feed=array();
    	//$category='%"'.$category.'"%';
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{subcategory_item}}
			WHERE
			category like ".FunctionsV3::q('%"'.$category.'"%')."
			ORDER BY sequence ASC
		";			 
	    //dump($stmt);	     
		if ( $res=$DbExt->rst($stmt)){			
			foreach ($res as $val) {				
				$data_feed[$val['sub_item_id']]=$val['sub_item_name'];
			}
			return $data_feed;
		}
		return false;
    }    
    
    public function getAddOnList($merchant_id='')
    {
    	$datafeed=array();
    	$DbExt=new DbExt;
    	$stmt="SELECT * FROM
    	{{subcategory}}
    	WHERE
    	merchant_id=".FunctionsV3::q($merchant_id)."
    	ORDER BY sequence ASC
    	";
    	if ( $res=$DbExt->rst($stmt)){
    		foreach ($res as $val) {
    			$datafeed[]=array(
    			  'addon_id'=>$val['subcat_id'],
    			  'addon_item_name'=>$val['subcategory_name'],
    			  'item'=>$this->getAddonItemList($val['subcat_id'])
    			);
    		}
    		return $datafeed;
    	}
    	return false;
    }
    
    public function getAddOnLists($merchant_id='')
	{
		$data_feed=array();
		$stmt="
		SELECT * FROM
		{{subcategory}}
		WHERE 
		merchant_id=".FunctionsV3::q($merchant_id)."
		ORDER BY sequence ASC
		";				
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 						
		if (is_array($rows) && count($rows)>=1){
			if ($this->data=="list"){
				foreach ($rows as $val) {									   
				   //$data_feed[$val['cat_id']]=$val['category_name'];
				   $data_feed[$val['subcat_id']]=$val['subcategory_name'];
				}
				return $data_feed;
			} else return $rows;
		}
		return FALSE;
	}        
    	
	public function merchantMenu()
	{
		$user_access = (array)FunctionsV3::getMerchantUserAccess();
		
		$payment_list=array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"payment-gateway"),'tag'=>'payment-gateway','label'=>'<i class="fa fa-usd"></i>'.Yii::t("default",'Payment Gateway'),
                
                   'itemOptions'=>array('class'=>''), 'items'=>array(
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"cod"),'tag'=>'cod', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Cash On delivery"),                    
                   'url'=>array('merchant/codsettings')),                
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"ocr"),'tag'=>'ocr', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Offline Credit Card Payment"),                    
                   'url'=>array('merchant/offlineccsettings')),                                   
                   
                   /*array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"pyp"),'tag'=>'pyp', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Paypal"),                    
                   'url'=>array('merchant/paypalSettings')),                */
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"paypal_v2"),'tag'=>'paypal_v2', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Paypal V2"),                    
                   'url'=>array('merchant/paypal_v2')),                
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"stp"),'tag'=>'stp', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Stripe"),                    
                   'url'=>array('merchant/stripeSettings')),                
                   
                   /*array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"mcd"),'tag'=>'mcd', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Mercadopago"), 
                   'url'=>array('merchant/mercadopagoSettings')),*/
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"mercadopago"),'tag'=>'mercadopago', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","mercadopago V2"), 
                   'url'=>array('merchant/mercadopago')),
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"ide"),'tag'=>'ide', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Sisow"), 
                   'url'=>array('merchant/sisowsettings')),
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"payu"),'tag'=>'payu', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","PayUMoney"), 
                   'url'=>array('merchant/payumoneysettings')),
                   
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"pys"),'tag'=>'pys', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","paysera"), 
                   'url'=>array('merchant/payserasettings')),
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"pyr"),'tag'=>'pyr', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Pay On Delivery"), 
                   'url'=>array('merchant/payondelivery')),
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"bcy"),'tag'=>'bcy', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Barclaycard"), 
                   'url'=>array('merchant/barclay')),                   
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"epy"),'tag'=>'epy', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","EpayBg"), 
                   'url'=>array('merchant/epagbg')),                                       
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"atz"),'tag'=>'atz', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Authorize.net"), 
                   'url'=>array('merchant/authorize')),                                       
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"obd"),'tag'=>'obd', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Offline Bank Deposit"), 
                   'url'=>array('merchant/obd')),                                       
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"obdreceive"),'tag'=>'obdreceive', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Receive Bank Deposit"), 
                   'url'=>array('merchant/obdreceive')),                                                            
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"btr"),'tag'=>'btr', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Braintree"), 
                   'url'=>array('merchant/braintreesettings')),                                                            
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"rzr"),'tag'=>'rzr', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Razorpay"), 
                   'url'=>array('merchant/razor')),                                                            
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"vog"),'tag'=>'vog', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","voguepay"), 
                   'url'=>array('merchant/voguepay')),                                                                                                 
                                      
                ));              
                              
              $fax_menu=array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"fax"),'tag'=>'fax','label'=>'<i class="fa fa-fax"></i>'.Yii::t("default",'Fax'),      
                        
                   'itemOptions'=>array('class'=>''), 'items'=>array(
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"faxstats"),'tag'=>'faxstats', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Stats"),                    
                   'url'=>array('merchant/faxstats')),                
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"faxsettings"),'tag'=>'faxsettings', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Settings"),                    
                   'url'=>array('merchant/faxsettings')),                
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"faxpurchase"),'tag'=>'faxpurchase', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Purchase Fax Credit"),                    
                   'url'=>array('merchant/faxpurchase')),                                 
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"faxpurchasetrans"),'tag'=>'faxpurchasetrans', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Purchase Credit Transactions"),                    
                   'url'=>array('merchant/faxpurchasetrans')),                                 

              )); 
              
              if ( Yii::app()->functions->getOptionAdmin("fax_enabled")!=2){
              	  $fax_menu=$fax_menu=array('visible'=>false,'tag'=>'fax','label'=>'<i class="fa fa-fax"></i>'.Yii::t("default",'Fax') ); 
              }
                            
						
        /*$mtype=$this->getMerchantMembershipType();        
        if ( $mtype==2){        	
        	$payment_list='';        	
        	$payment_list=array('visible'=>true,'tag'=>'payment-gateway','label'=>'<i class="fa fa-usd"></i>'.Yii::t("default",'Payment Gateway'),                
                 'itemOptions'=>array('class'=>''), 'items'=>array(
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"pyr"),'tag'=>'pyr', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Pay On Delivery"),                    
                   'url'=>array('merchant/payondelivery')),        
              ));        
        }*/	
        $merchant_type=FunctionsV3::getMerchantTypeBySession();        
        if ($merchant_type==2){
        	$payment_list=array();        	
        	$payment_list=array('visible'=>true,
        	'tag'=>'payment-gateway','label'=>'<i class="fa fa-usd"></i>'.Yii::t("default",'Payment Gateway'),                
             'itemOptions'=>array('class'=>''), 'items'=>array(
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"pyr"),
                       'tag'=>'pyr', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Pay On Delivery"),
                       'url'=>array('merchant/payondelivery')),        
              ));        
        }	
        
        $minfo=$this->getMerchantInfo();         
        $togle_com=false;
        
        if($minfo){
	        if ($minfo[0]->is_commission==2){
	        	$togle_com=true;
	        }	
        }
        
        $togle_com_widrawals=false;        
        if ($merchant_type==2){
        	$togle_com_widrawals=true;
        } 
        
         
		return array(  
		    'activeCssClass'=>'active', 
		    'encodeLabel'=>false,
		    'items'=>array(
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"DashBoard"),'tag'=>"DashBoard",'label'=>'<i class="fa fa-home"></i>'.Yii::t("default","Dashboard"),
                'url'=>array('/merchant/DashBoard')),
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"Merchant"),'tag'=>"Merchant",'label'=>'<i class="fa fa-cutlery"></i>'.Yii::t("default","Merchant Info"),
                'url'=>array('/merchant/Merchant')),
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"Settings"),'tag'=>"Settings",'label'=>'<i class="fa fa-cog"></i>'.Yii::t("default","Settings"),
                'url'=>array('/merchant/Settings')),
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"tablebooking"),'tag'=>"tablebooking",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Table Booking"),
                'url'=>array('/merchant/tablebooking')),
                                                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"allorder"),'tag'=>"allorder",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","All order"),
                'url'=>array('/merchant/allorder')),
                                                                               
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"orderStatus"),'tag'=>"orderStatus",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Order Status"),
                'url'=>array('/merchant/orderStatus')),
                                                                               
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"CategoryList"),'tag'=>"CategoryList",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Food Category"),
                'url'=>array('/merchant/CategoryList')),
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"category_sked"),'tag'=>"category_sked",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Food Category Scheduler"),
                'url'=>array('/merchant/category_sked')),
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"Size"),'tag'=>"Size",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Size"),
                'url'=>array('/merchant/Size')),
                                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"AddOnCategory"),'tag'=>"AddOnCategory",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","AddOn Category"),
                'url'=>array('/merchant/AddOnCategory')),
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"AddOnItem"),'tag'=>"AddOnItem",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","AddOn Item"),
                'url'=>array('/merchant/AddOnItem')),
                                
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"ingredients"),'tag'=>"ingredients",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Ingredients"),
                'url'=>array('/merchant/ingredients')),
                                
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"CookingRef"),'tag'=>"CookingRef",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Cooking Reference"),
                'url'=>array('/merchant/CookingRef')),
                                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"FoodItem"),'tag'=>"FoodItem",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Food Item"),
                'url'=>array('/merchant/FoodItem')),             
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"invoice"),'tag'=>"invoice",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Invoice"),
                'url'=>array('/merchant/invoice')),
                
                /*POINTS PROGRAM*/
                /*array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"points_settings"),'tag'=>"points_settings",'label'=>'<i class="fa fa-list-alt"></i>'.t("Loyalty Points Settings"),
                'url'=>array('/merchant/points_settings')),*/
                
                /*PRINTER ADDON*/
                /*array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"printer_merchant_panel"),'tag'=>"printer_merchant_panel",'label'=>'<i class="fa fa-list-alt"></i>'.t("Printer Addon"),
                'url'=>array('/printer/merchant_panel')),*/
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"shippingrate"),'tag'=>"shippingrate",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Delivery Charges Rates"),
                'url'=>array('/merchant/shippingrate')),
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"mintable"),'tag'=>"mintable",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Minimum Order Table"),
                'url'=>array('/merchant/mintable')),
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"offers"),'tag'=>"offers",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Offers"),
                'url'=>array('/merchant/offers')),
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"gallerysettings"),'tag'=>"gallerysettings",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Gallery Settings"),
                'url'=>array('/merchant/gallerysettings')),
                                
				array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"banner_settings"),'tag'=>"banner_settings",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Banner Settings"),
				'url'=>array('/merchant/banner_settings')),
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"manage_credit_cards"),'tag'=>"manage_credit_cards",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Manage Credit Card"),
                'url'=>array('/merchant/manage_credit_cards')),
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"voucher"),'tag'=>"voucher",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Voucher"),
                'url'=>array('/merchant/voucher')),
                
                
                /*addon */
                 array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"addon"),'tag'=>'addon','label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default",'Addon'),
                   'itemOptions'=>array('class'=>''), 'items'=>array(
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"points_settings"),'tag'=>'points_settings', 'label'=>'<i class="fa fa-paypal"></i>'.t("Loyalty Points Settings"), 
                   'url'=>array('/merchant/points_settings')),                
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"printer_merchant_panel"),'tag'=>'printer_merchant_panel', 'label'=>'<i class="fa fa-paypal"></i>'.t("Printer Addon"), 
                   'url'=>array('/printer/merchant_panel?lang='.Yii::app()->language)),                
                   
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"singleapp"),'tag'=>'singleapp', 'label'=>'<i class="fa fa-paypal"></i>'.t("Single Merchant"),
                   'url'=>array('/merchant/singlemerchant/banner')), 
                                                                           
                )),   
                                
                // commission                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"commission"),'tag'=>'commission','label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default",'Commission'),
                   'itemOptions'=>array('class'=>''), 'items'=>array(
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"statement"),'tag'=>'statement', 'label'=>'<i class="fa fa-paypal"></i>'.t("Statement"), 
                   'url'=>array('merchant/statement')),                
                   
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"earnings"),'tag'=>'earnings', 'label'=>'<i class="fa fa-paypal"></i>'.t("Earnings"),
                   'url'=>array('merchant/earnings')),     
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"withdrawals"),'tag'=>'withdrawals', 'label'=>'<i class="fa fa-paypal"></i>'.t("Withdrawals"),
                   'url'=>array('merchant/withdrawals')),     
                                      
                )),   
                      
                $payment_list,
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"sms-gateway"),'tag'=>'sms-gateway','label'=>'<i class="fa fa-mobile"></i>'.Yii::t("default",'SMS'),
                   'itemOptions'=>array('class'=>''), 'items'=>array(
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"smsSettings"),'tag'=>'smsSettings', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","SMS Alert Settings"), 
                   'url'=>array('merchant/smsSettings')),                
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"smsBroadcast"),'tag'=>'smsBroadcast', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","SMS BroadCast"), 
                   'url'=>array('merchant/smsBroadcast')),     
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"purchaseSMS"),'tag'=>'purchaseSMS', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Purchase SMS Credit"), 
                   'url'=>array('merchant/purchasesms')),                           
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"purchasesmstransaction"),'tag'=>'purchasesmstransaction', 'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Purchase Credit Transactions"), 
                   'url'=>array('merchant/purchasesmstransaction')),                           
                )),
                                
                $fax_menu,
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"reports"),'tag'=>'reports','label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default",'Reports'),
                   'itemOptions'=>array('class'=>''), 'items'=>array(
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"salesReport"),'tag'=>'salesReport','label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Sales Report"), 
                   'url'=>array('merchant/salesReport')),
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"salesSummaryReport"),'tag'=>'salesSummaryReport','label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Sales Summary Report"), 
                   'url'=>array('merchant/salesSummaryReport')),                
                   
                   array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"bookingreport"),'tag'=>'bookingreport','label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Booking Summary Report"), 
                   'url'=>array('merchant/bookingreport')),                
                )),

                /*array('tag'=>"home",'label'=>'<i class="fa fa-cog"></i>'.Yii::t("default","Receipt Settings"),
                'url'=>array('/merchant/ReceiptSettings')),*/
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"review"),'tag'=>"review",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Customer reviews"),
                'url'=>array('/merchant/review')),               
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"SocialSettings"),'tag'=>"SocialSettings",'label'=>'<i class="fa fa-facebook-square"></i>'.Yii::t("default","Social Settings"),
                'url'=>array('/merchant/SocialSettings')),               
                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"AlertSettings"),'tag'=>"AlertSettings",'label'=>'<i class="fa fa-bell"></i>'.Yii::t("default","Alert Notification"),
                'url'=>array('/merchant/AlertSettings')),               
                                
                array('visible'=>FunctionsV3::hasMerchantAccessToMenu($user_access,"user"),'tag'=>"user",'label'=>'<i class="fa fa-users"></i>'.Yii::t("default","User"),
                'url'=>array('/merchant/user')),
                                
                array('tag'=>"logout",'label'=>'<i class="fa fa-sign-out"></i>'.Yii::t("default","Logout"),
                'url'=>array('/merchant/login/logout/true')),
            )
		);
	}	    
	
	public function hasMerchantAccess($tag='')
	{
		/*LIST OF ACCEPTED CONTROLLER NAME IN MERCHANT*/
		$accepted_tag=array(
			'MerchantStatus',
			'purchaseSMScredit',
			'paypalInit',
			'stripeInit',
			'mercadopagoInit',
			'sisowinit',
			'payuinit',
			'obdinit',
			'pysinit',
			'creditCardInit',
			'smsReceipt',
			'Setlanguage',
			'pay',
			'paymentconfirm',
			'faxreceipt',
			'profile',
			'print'
		);
		if (in_array($tag,$accepted_tag)){
			return true;
		}	
		
		/*HIDE TABLE BOOKING IF DISABLED */
		if ( $tag=="tablebook"){
			$tbl_booking=getOption( self::getMerchantID() ,'merchant_master_table_boooking');
			if($tbl_booking==1){
				return false;
			}		
		}
		
		/*HIDE MINIMUM TABLE RATES IF SEARCH TYPE IS BY LOCATION*/
		if ( $tag=="mintable"){
			if (FunctionsV3::isSearchByLocation()){
				return false;
			}
		}	
				
		if ($tag=="invoice"){
			if ( $merchant_type=FunctionsV3::getMerchantMembershipType( self::getMerchantID() )){
				if($merchant_type==1){
					return false;
				}			
			}
		}		
		
		/*special category*/
		if($tag=="CategoryList"){
		   $merchant_category_disabled=getOptionA('merchant_category_disabled');
		   if($merchant_category_disabled==1){
		   	  return false;
		   }		
		}			
		
		/*$tag_paymentgateway=array(
		  'paypal','stripe','mercadopago','ide','payu','pys','ccr','bcy','epy','pyr',
		  'atz','obd','pyp','stp','mcd','ocr','btr','rzr','mri'
		);				
				
		if ( $tag=="obdreceive"){
			$tag='obd';
		}	
		
		if (in_array($tag,$tag_paymentgateway)){			
			$list_payment=$this->getMerchantListOfPaymentGateway();
			if (!in_array($tag,(array)$list_payment)){
				return false;
			}
		}*/
		
		/*CHECK IF ENABLED IN PAYMENT GATEWAY SETTINGS*/
		if ( $tag=="obdreceive"){
			$tag='obd';
		}	
		
		$payment_list=FunctionsV3::PaymentOptionList();		
		if (array_key_exists($tag,(array)$payment_list)){
			$list_payment_enabled=$this->getMerchantListOfPaymentGateway();		
			if ( !in_array($tag,(array)$list_payment_enabled)){
				 return false;
			} else {
				$master_key="merchant_switch_master_$tag";
				$master_key_val=getOption(self::getMerchantID(),$master_key);
				if($master_key_val==1){
					return false;
				}			
			}
		}
		
		if ($tag=='sms-gateway'){
			$mechant_sms_enabled=Yii::app()->functions->getOptionAdmin('mechant_sms_enabled');
			if ($mechant_sms_enabled=="yes"){
			   return false;	
			}
		}	
		
		if ( $tag=="purchaseSMS"){
			$mechant_sms_purchase_disabled=Yii::app()->functions->getOptionAdmin('mechant_sms_purchase_disabled');
			if ( $mechant_sms_purchase_disabled=="yes"){
				return false;
			}
		}	
		
		/*check if table booking is enabled by admin*/
		if ( $tag=="tablebooking"){			
			if (getOptionA('merchant_tbl_book_disabled')==2){				
				return false;
			}
		}
		
		switch ($tag) {
			case "tablebooking":
			    $tag="tablebook";	
				break;
			case "paypalSettings":
			    $tag="paypal";	
			    break; 
			case "stripeSettings":    
			    $tag="stripe";	
			    break; 
			case "mercadopagoSettings":    
			    $tag="mercadopago";	
			    break; 
			case "sisowsettings":    
			    $tag="ide";	
			    break;     
			case "payumoneysettings":    
			    $tag="payu";	
			    break;         
			case "payserasettings":    
			    $tag="pys";	
			    break;             
			case "payondelivery":    
			    //$tag="ccr";	
			    $tag="pyr";
			    break;                 
			default:
				break;
		}
		
		$info=$this->getMerchantInfo();		
		if ( is_array($info) && count($info)>=1){
			$info=(array)$info[0];			
			if (isset($info['merchant_user_id'])){
				$access=json_decode($info['user_access']);		
				$tag=strtolower($tag);
				$access = array_map('strtolower', $access);				
				if (in_array($tag,(array)$access)){
					return true;
				}			
			} else return true;							    
		}
		return false;		
	}

	
	public function adminMenu()
	{
		return array(  
		    'activeCssClass'=>'active', 
		    'encodeLabel'=>false,
		    'items'=>array(
                array('visible'=>$this->AA('dashboard'),
                'tag'=>"dashboard",'label'=>'<i class="fa fa-home"></i>'.Yii::t("default","Dashboard"),
                'url'=>array('/admin/dashboard')),                              
                                                
                array('visible'=>$this->AA('merchant'),
                'tag'=>"merchant",'label'=>'<i class="fa fa-cutlery"></i>'.Yii::t("default","Merchant List"),
                'url'=>array('/admin/merchant')),
                
                
                array('visible'=>$this->AA('sponsoredMerchantList'),
                'tag'=>"sponsoredMerchantList",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Sponsored Listing"),                
                'url'=>array('/admin/sponsoredMerchantList')),
                
                array('visible'=>$this->AA('packages'),
                'tag'=>"packages",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Packages"),
                'url'=>array('/admin/packages')),                
                                
                array('visible'=>$this->AA('Cuisine'),
                'tag'=>"Cuisine",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Cuisine"),
                'url'=>array('/admin/Cuisine')),
                               
                array('visible'=>$this->AA('dishes'),
                'tag'=>"dishes",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Dishes"),
                'url'=>array('/admin/dishes')),
                
                /*array('visible'=>$this->AA('category'),
                'tag'=>"category",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Category"),
                'url'=>array('/admin/category')),*/
                                               
                array('visible'=>$this->AA('OrderStatus'),
                'tag'=>"OrderStatus",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Order Status"),
                'url'=>array('/admin/OrderStatus')),
                
                array('visible'=>$this->AA('tags'),
                'tag'=>"tags",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Tags"),
                'url'=>array('/admin/tags')),
                
                array('visible'=>$this->AA('incomingorders'),'tag'=>"incomingorders",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","All Orders"),
                'url'=>array('/admin/incomingorders')),           
                
                array('visible'=>$this->AA('cancel_order'),'tag'=>"cancel_order",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","New Cancel Orders"),
                'url'=>array('/admin/cancel_order')),           
                
                
                array('visible'=>$this->AA('settings'),
                'tag'=>"settings",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Settings"),
                'url'=>array('/admin/settings')),       
                
                array('visible'=>$this->AA('themesettings'),
                'tag'=>"themesettings",'label'=>'<i class="fa fa-list-alt"></i>'.t("Theme settings"),
                'url'=>array('/admin/themesettings')),                     
                
                /*array('visible'=>$this->AA('zipcode'),
                'tag'=>"zipcode",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Post codes"),
                'url'=>array('/admin/zipcode')),*/
                
                array('visible'=>$this->AA('managelocation'),
                'tag'=>"managelocation",'label'=>'<i class="fa fa-list-alt"></i>'.t("Manage location"),
                'url'=>array('/admin/managelocation')),
                
                                
                array('visible'=>$this->AA('commisionsettings'),
                'tag'=>"commisionsettings",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Commission Settings"),
                'url'=>array('/admin/commisionsettings')),                                   
                
                array('visible'=>$this->AA('voucher'),
                'tag'=>"voucher",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Voucher"),
                'url'=>array('/admin/voucher')),                                   
                
                array('visible'=>$this->AA('invoice'),
                'tag'=>"invoice",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Invoice"),
                'url'=>array('/admin/invoice')),                                                   
                
                array('visible'=>$this->AA('merchantcommission'),
                'tag'=>"merchantcommission",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Merchant Commission"),
                'url'=>array('/admin/merchantcommission')),                                                   
                                
                array('visible'=>$this->AA('withdrawal'),'tag'=>'withdrawal',
                   'label'=>'<i class="fa fa-university"></i>'.Yii::t("default",'Withdrawal'),
                   'itemOptions'=>array('class'=>''), 
                   'items'=>array(        
                     array('visible'=>$this->AA('incomingwithdrawal'),'tag'=>'incomingwithdrawal',
                     'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Withdrawal List"), 
                     'url'=>array('admin/incomingwithdrawal')),                                
                     
                      array('visible'=>$this->AA('withdrawalsettings'),'tag'=>'withdrawalsettings',
                      'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Settings"), 
                     'url'=>array('admin/withdrawalsettings')),                                   
                 )),          
                                
                array('visible'=>$this->AA('emailsettings'),'tag'=>"emailsettings",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Email Settings"),
                'url'=>array('/admin/emailsettings')),           
               
                array('visible'=>$this->AA('emailtpl'),
                'tag'=>"emailtpl",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Notification Template"),
                'url'=>array('/admin/emailtpl')),           
                
                array('visible'=>$this->AA('notisettings'),
                'tag'=>"notisettings",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Notification Settings"),
                'url'=>array('/admin/notisettings')),            
                                
                array('visible'=>$this->AA('emailogs'),'tag'=>"emailogs",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Email Logs"),
                'url'=>array('/admin/emailogs')),           
                
                array('visible'=>$this->AA('cronjobs'),'tag'=>"cronjobs",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Cron Jobs"),
                'url'=>array('/admin/cronjobs')),           
                               
                /*array('visible'=>$this->AA('ordertemplate'),
                'tag'=>"ordertemplate",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Order Email Template"),
                'url'=>array('/admin/ordertemplate')),*/
                
                
                array('visible'=>$this->AA('customPage'),
                'tag'=>"customPage",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Custom Page"),
                'url'=>array('/admin/customPage')),                
                               
                array('visible'=>$this->AA('Ratings'),
                'tag'=>"Ratings",'label'=>'<i class="fa fa-star-o"></i>'.Yii::t("default","Ratings"),
                'url'=>array('/admin/Ratings')),                
                
                array('visible'=>$this->AA('ContactSettings'),'tag'=>"ContactSettings",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Contact Settings"),
                'url'=>array('/admin/ContactSettings')),                
                
                array('visible'=>$this->AA('SocialSettings'),'tag'=>"SocialSettings",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Social Settings"),
                'url'=>array('/admin/SocialSettings')),                
                                
                array('visible'=>$this->AA('ManageCurrency'),'tag'=>"ManageCurrency",
                'label'=>'<i class="fa fa-usd"></i>'.Yii::t("default","Manage Currency"),
                'url'=>array('/admin/ManageCurrency')),                
                
                array('visible'=>$this->AA('ManageLanguage'),'tag'=>"ManageLanguage",
                'label'=>'<i class="fa fa-flag-o"></i>'.Yii::t("default","Manage Language"),
                'url'=>array('/admin/ManageLanguage')),

                array('visible'=>$this->AA('Seo'),
                'tag'=>"Seo",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","SEO"),
                'url'=>array('/admin/Seo')),   
                
                
                /**add ons */
                array('visible'=>$this->AA('addons'),'tag'=>'addons',
                'label'=>'<i class="fa fa-plus-circle"></i>'.Yii::t("default",'Add-ons'),
                   'itemOptions'=>array('class'=>''), 'items'=>array(                   
                   
                   array('visible'=>$this->AA('addonexport'),
                   'tag'=>'addonexport','label'=>'<i class="fa"></i>'.Yii::t("default","Export/Import"), 
                   'url'=>Yii::app()->getBaseUrl(true)."/exportmanager?lang=".Yii::app()->language),                
                   
                   /*array('visible'=>$this->AA('mobileapp'),
                   'tag'=>'mobileapp','label'=>'<i class="fa"></i>'.Yii::t("default","MobileApp"), 
                   'url'=>Yii::app()->getBaseUrl(true)."/mobileapp"),*/
                   
                   array('visible'=>$this->AA('mobileapp'),
                   'tag'=>'mobileapp','label'=>'<i class="fa"></i>'.Yii::t("default","MobileApp"), 
                   'url'=> Yii::app()->createUrl('/mobileapp?lang='.Yii::app()->language) ),                
                   
                   array('visible'=>$this->AA('pointsprogram'),
                   'tag'=>'pointsprogram','label'=>'<i class="fa"></i>'.Yii::t("default","Loyalty Points Program"), 
                   'url'=>Yii::app()->getBaseUrl(true)."/pointsprogram?lang=".Yii::app()->language),                
                                      
                   array('visible'=>$this->AA('merchantapp'),
                   'tag'=>'merchantapp','label'=>'<i class="fa"></i>'.Yii::t("default","MerchantApp"), 
                   'url'=>Yii::app()->getBaseUrl(true)."/merchantapp?lang=".Yii::app()->language),                
                   
                   array('visible'=>$this->AA('printermodule'),
                   'tag'=>'printermodule','label'=>'<i class="fa"></i>'.Yii::t("default","Printer"), 
                   'url'=>Yii::app()->getBaseUrl(true)."/printer?lang=".Yii::app()->language),                
                   
                   array('visible'=>$this->AA('singlemerchant'),
                   'tag'=>'singlemerchant','label'=>'<i class="fa"></i>'.Yii::t("default","Single Merchant"), 
                   'url'=>Yii::app()->getBaseUrl(true)."/singlemerchant?lang=".Yii::app()->language),                
                   
                   array('visible'=>$this->AA('mobileappv2'),
                   'tag'=>'mobileappv2','label'=>'<i class="fa"></i>'.Yii::t("default","MobileApp v2"), 
                   'url'=>Yii::app()->getBaseUrl(true)."/mobileappv2?lang=".Yii::app()->language),                
              
                 )),  
                /**add ons */     
                                            
                array('visible'=>$this->AA('analytics'),
                'tag'=>"analytics",'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Custom Code & Analytics"),
                'url'=>array('/admin/analytics')),                               
                
                array('visible'=>$this->AA('customerlist'),'tag'=>"customerlist",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Customer List"),
                'url'=>array('/admin/customerlist')),                               
                
                array('visible'=>$this->AA('subscriberlist'),'tag'=>"subscriberlist",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Subscriber List"),
                'url'=>array('admin/subscriberlist')),                               
                
                array('visible'=>$this->AA('reviews'),'tag'=>"reviews",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Reviews"),
                'url'=>array('admin/reviews')),                               
                
                array('visible'=>$this->AA('bankdeposit'),'tag'=>"bankdeposit",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Receive Bank Deposit"),
                'url'=>array('/admin/bankdeposit')),                               
                                
                array('visible'=>$this->AA('paymentgatewaysettings'),'tag'=>"paymentgatewaysettings",
                'label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default","Payment Gateway Settings"),
                'url'=>array('/admin/paymentgatewaysettings')),                               
                
                array('visible'=>$this->AA('paymentgateway'),'tag'=>'paymentgateway',
                'label'=>'<i class="fa fa-usd"></i>'.Yii::t("default",'Payment Gateway'),
                   'itemOptions'=>array('class'=>''), 'items'=>array(                   
                   
/*                   array('visible'=>$this->AA('paypalSettings'),
                   'tag'=>'paypalSettings','label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Paypal"), 
                   'url'=>array('admin/paypalSettings')),                */
                   
                   array('visible'=>$this->AA('paypal_v2'),
                   'tag'=>'paypal_v2','label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Paypal V2"), 
                   'url'=>array('admin/paypal_v2')),                
                   
                   array('visible'=>$this->AA('cardpaymentsettings'),  
                   'tag'=>'cardpaymentsettings','label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Offline Credit Card Payment"), 
                   'url'=>array('admin/cardpaymentsettings')),                                   
                   
                   array('visible'=>$this->AA('stripeSettings'),
                   'tag'=>'stripeSettings','label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Stripe"), 
                   'url'=>array('admin/stripeSettings')),                
                   
                   /*array('visible'=>$this->AA('mercadopagoSettings'),'tag'=>'mercadopagoSettings',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Mercadopago"), 
                   'url'=>array('admin/mercadopagoSettings')),                                   */
                   
                   array('visible'=>$this->AA('mercadopago'),'tag'=>'mercadopago',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","mercadopago V2"), 
                   'url'=>array('admin/mercadopago')),                                   
                   
                   array('visible'=>$this->AA('sisowsettings'),
                   'tag'=>'sisowsettings','label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Sisow"), 
                   'url'=>array('admin/sisowsettings')),                                   
                   
                   array('visible'=>$this->AA('payumonenysettings'),'tag'=>'payumonenysettings',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","PayUMoney"), 
                   'url'=>array('admin/payumonenysettings')),                                   
                   
                   array('visible'=>$this->AA('obdsettings'),'tag'=>'obdsettings',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Offline Bank Deposit"), 
                   'url'=>array('admin/obdsettings')),        
                   
                   array('visible'=>$this->AA('payserasettings'),'tag'=>'payserasettings',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Paysera"), 
                   'url'=>array('admin/payserasettings')),           
                   
                   array('visible'=>$this->AA('payondelivery'),'tag'=>'payondelivery',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Pay On Delivery settings"), 
                   'url'=>array('admin/payondelivery')),    
                                            
                   array('visible'=>$this->AA('barclay'),
                   'tag'=>'barclay','label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Barclaycard"), 
                   'url'=>array('admin/barclay')),                             
                   
                   array('visible'=>$this->AA('epaybg'),
                   'tag'=>'epaybg','label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","EpayBg"), 
                   'url'=>array('admin/epaybg')),                                                
                   
                   array('visible'=>$this->AA('authorize'),'tag'=>'authorize',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Authorize.net"), 
                   'url'=>array('admin/authorize')),                             
                   
                   array('visible'=>$this->AA('braintree'),'tag'=>'braintree',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Braintree"), 
                   'url'=>array('admin/braintree')),                             
                   
                   array('visible'=>$this->AA('razor'),'tag'=>'razor',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Razorpay"), 
                   'url'=>array('admin/razor')),                             
                   
                   array('visible'=>$this->AA('voguepay'),'tag'=>'voguepay',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","voguepay"), 
                   'url'=>array('admin/voguepay')),  
                    
                 )),                               
                 
                 array('visible'=>$this->AA('sms'),
                 'tag'=>'sms','label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default",'SMS'),
                   'itemOptions'=>array('class'=>''), 'items'=>array(
                   
                   array('visible'=>$this->AA('smsSettings'),'tag'=>'smsSettings',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","SMS Settings"), 
                   'url'=>array('admin/smsSettings')),                
                   
                   array('visible'=>$this->AA('smsPackage'),'tag'=>'smsPackage',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","SMS Package"), 
                   'url'=>array('admin/smsPackage')),                                                                         
                   array('visible'=>$this->AA('smstransaction'),'tag'=>'smstransaction',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","SMS Transaction"), 
                   'url'=>array('admin/smstransaction')),    
                               
                   array('visible'=>$this->AA('smslogs'),
                   'tag'=>'smslogs','label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","SMS Logs"), 
                   'url'=>array('admin/smslogs')),                
                 )),                        
                 
                 array('visible'=>$this->AA('fax'),
                 'tag'=>'fax','label'=>'<i class="fa fa-fax"></i>'.Yii::t("default",'Fax service'),
                   'itemOptions'=>array('class'=>''), 'items'=>array(        
                   
                   array('visible'=>$this->AA('faxtransaction'),'tag'=>'faxtransaction',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Fax Payment Transaction"), 
                   'url'=>array('admin/faxtransaction')),                                
                   
                   array('visible'=>$this->AA('faxpackage'),'tag'=>'faxpackage',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Fax Package"), 
                   'url'=>array('admin/faxpackage')),                                
                   
                   array('visible'=>$this->AA('faxlogs'),
                   'tag'=>'faxlogs','label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Fax Logs"), 
                   'url'=>array('admin/faxlogs')),     
                                              
                   array('visible'=>$this->AA('faxsettings'),
                   'tag'=>'faxsettings','label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Settings"), 
                   'url'=>array('admin/faxsettings')),                                   
                 )),          
                       
                
                array('visible'=>$this->AA('reports'),
                'tag'=>'reports','label'=>'<i class="fa fa-list-alt"></i>'.Yii::t("default",'Reports'),
                   'itemOptions'=>array('class'=>''), 'items'=>array(
                   
                   array('visible'=>$this->AA('rptMerchantReg'),'tag'=>'rptMerchantReg',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Merchant Registration"), 
                   'url'=>array('admin/rptMerchantReg')), 
                   
                   array('visible'=>$this->AA('rptMerchantPayment'),'tag'=>'rptMerchantPayment',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Merchant Payment"), 
                   'url'=>array('admin/rptMerchantPayment')), 
                   
                   array('visible'=>$this->AA('rptMerchanteSales'),'tag'=>'rptMerchanteSales',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Merchant Sales Report"), 
                   'url'=>array('admin/rptMerchanteSales')), 
                   
                   array('visible'=>$this->AA('rptmerchantsalesummary'),'tag'=>'rptmerchantsalesummary',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Merchant Sales Summary Report"), 
                   'url'=>array('admin/rptmerchantsalesummary')), 
                   
                   array('visible'=>$this->AA('rptbookingsummary'),'tag'=>'rptbookingsummary',
                   'label'=>'<i class="fa fa-paypal"></i>'.Yii::t("default","Booking Summary Report"), 
                   'url'=>array('admin/rptbookingsummary')), 
                 )),
                 
                 array('visible'=>$this->AA('userList'),
                 'tag'=>"userList",'label'=>'<i class="fa fa-users"></i>'.Yii::t("default","User List"),
                'url'=>array('/admin/userlist')),                
                                
                array('tag'=>"logout",'label'=>'<i class="fa fa-sign-out"></i>'.Yii::t("default","Logout"),
                'url'=>array('/admin/login/logout/true')),
            )
		);
	}
	
	public function topMenu()
	{
		$hide=true;
		if ( $this->isClientLogin()){
			$hide=false;
		}
		
		$merchant_disabled_registration=$this->getOptionAdmin('merchant_disabled_registration');		
		$enabled_reg=$merchant_disabled_registration=="yes"?false:true;
		
		$enabled_commission=Yii::app()->functions->getOptionAdmin('admin_commission_enabled');		
		$signup_link="/store/merchantsignup";
		if ($enabled_commission=="yes"){
		   $signup_link="/store/merchantsignupselection";	
		}
		
		$website_disabled_login_popup=Yii::app()->functions->getOptionAdmin('website_disabled_login_popup');
		$link_sigup='javascript:;';
		$link_sigup_class='top_signup';
		if ( $website_disabled_login_popup=="yes"){
			$link_sigup=array('/store/signup');
			$link_sigup_class='';
		}	
		
		$view_map=true;
		if ( getOptionA('view_map_disabled')==2){
			$view_map=false;
		}	
				
		return array(  
		    'activeCssClass'=>'active', 		    
		    'encodeLabel'=>false,		    
		    'items'=>array(
                array('visible'=>$hide,'tag'=>"signup",'label'=>'<i class="fa fa-user"></i>'.Yii::t("default","Login & Signup"),
                'url'=>$link_sigup,'itemOptions'=>array('class'=>$link_sigup_class)),            
                array('visible'=>$enabled_reg,'tag'=>"home",'label'=>'<i class="fa fa-cutlery"></i>'.Yii::t("default","Restaurant Signup"),
                'url'=>array($signup_link)),
                                
                array('tag'=>"home",'label'=>'<i class="fa fa-search"></i>'.Yii::t("default","Browse Restaurant"),
                'url'=>array('/store/browse')),                                
                
                array('visible'=>$view_map, 'tag'=>"home",'label'=>'<i class="fa fa-map-marker"></i>'.Yii::t("default","View Restaurant by map"),
                'url'=>array('/store/map')),                                
             )   
          );
	}
	
    public function topLeftMenu()
	{
		$top_menu[]=array('tag'=>"signup",'label'=>'<i class="fa fa-home"></i>'.Yii::t("default","Home"),
                'url'=>array('/store/home'));

        $top_menu[]=array('tag'=>"home",'label'=>'<i class="fa fa-envelope-o"></i> '.Yii::t("default","Contact"),
                'url'=>array('/store/contact'));
                             
		if ($data=Yii::app()->functions->customPagePosition()){
			foreach ($data as $val) {
				if ($val['is_custom_link']==2){
					if (!preg_match("/http/i", $val['content'])) {
						$val['content']="http://".$val['content'];
					} 
					if ( $val['open_new_tab']==2){
						$top_menu[]=array('tag'=>"home",'label'=>'<i class="'.$val['icons'].'"></i> '.
						Yii::t("default",$val['page_name']),
		                'url'=>$val['content'],
		                'linkOptions'=>array('target'=>"_blank")
		                );
					} else {
						$top_menu[]=array('tag'=>"home",'label'=>'<i class="'.$val['icons'].'"></i> '.
					    Yii::t("default",$val['page_name']),
	                   'url'=>$val['content']);
					}		
				} else {		
					if ( $val['open_new_tab']==2){
						$top_menu[]=array('tag'=>"home",'label'=>'<i class="'.$val['icons'].'"></i>'.
					    Yii::t("default",$val['page_name']),
	                   'url'=>array('/store/page/'.$val['slug_name']),
	                   'linkOptions'=>array('target'=>"_blank"));
					} else {
						$top_menu[]=array('tag'=>"home",'label'=>'<i class="'.$val['icons'].'"></i>'.
						Yii::t("default",$val['page_name']),
		                'url'=>array('/store/page/'.$val['slug_name']));
					}
				}
			}
		}	
		return array(  		    
		    'id'=>"top-menu",
		    'activeCssClass'=>'active', 
		    'encodeLabel'=>false,
		    'items'=>$top_menu                      
          );
	}
	
	public function bottomMenu($position='bottom')
	{
		
	   $menu=array();
       if ($data=Yii::app()->functions->customPagePosition($position)){
			foreach ($data as $val) {
				if ($val['is_custom_link']==2){
					if (!preg_match("/http/i", $val['content'])) {
						$val['content']="http://".$val['content'];
					} 
					if ( $val['open_new_tab']==2){
						$menu[]=array('tag'=>"home",'label'=>'<i class="'.$val['icons'].'"></i> '.
						Yii::t("default",$val['page_name']),
		                'url'=>$val['content'],
		                'linkOptions'=>array('target'=>"_blank")
		                );
					} else {
						$menu[]=array('tag'=>"home",'label'=>'<i class="'.$val['icons'].'"></i> '.
					    Yii::t("default",$val['page_name']),
	                   'url'=>$val['content']);
					}		
				} else {								
					if ( $val['open_new_tab']==2){						
						$menu[]=array('tag'=>"home",'label'=>'<i class="'.$val['icons'].'"></i> '.
						Yii::t("default",$val['page_name']),
		                'url'=>array('/store/page/'.$val['slug_name']),
		                'linkOptions'=>array('target'=>"_blank")
		                );
					} else {												
						$menu[]=array('tag'=>"home",'label'=>'<i class="'.$val['icons'].'"></i> '.
					    Yii::t("default",$val['page_name']),
	                   'url'=>array('/store/page/'.$val['slug_name']));
					}		
				}	
			}
		}
                  		
		return array(  		    
		    'id'=>"bottom-menu",
		    'activeCssClass'=>'active', 
		    'encodeLabel'=>false,
		    'items'=>$menu
          );
	}
	
	public function navMenu()
	{
		return array(  		    
		    'id'=>"nav-menu",
		    'activeCssClass'=>'active', 
		    'encodeLabel'=>false,
		    'items'=>array(
                array('tag'=>"signup",'label'=>Yii::t("default","Home"),
                'url'=>array('/store')),
                
                array('tag'=>"home",'label'=>Yii::t("default","How it works"),
                'url'=>array('/store/about')),
                
                array('tag'=>"home",'label'=>Yii::t("default","Contact"),
                'url'=>array('/store/contact')),
                
             )   
          );
	}
	
	public function socialMenu()
	{
		$social_flag=yii::app()->functions->getOptionAdmin('social_flag');
		$admin_fb_page=yii::app()->functions->getOptionAdmin('admin_fb_page');
		$admin_twitter_page=yii::app()->functions->getOptionAdmin('admin_twitter_page');
		$admin_google_page=yii::app()->functions->getOptionAdmin('admin_google_page');
				
		if ( $social_flag==1){
			return array(  		    		    
		    'activeCssClass'=>'active', 
		    'encodeLabel'=>false
		    );
		}
		
		$fb=true;
		$twiter=true;
		$google=true;
		if (empty($admin_fb_page)){
			$fb=false;
		} else {
			if (!preg_match("/http/i",$admin_fb_page )) {
				$admin_fb_page="http://$admin_fb_page";
			}
		}
	
		if (empty($admin_twitter_page)){
			$twiter=false;
		} else {
			if (!preg_match("/http/i",$admin_twitter_page )) {
				$admin_twitter_page="http://$admin_twitter_page";
			}
		}
			
		if (empty($admin_google_page)){
			$google=false;
		} else {
			if (!preg_match("/http/i",$admin_google_page )) {
				$admin_google_page="http://$admin_google_page";
			}
		}	
						
		return array(  		    		    
		    'activeCssClass'=>'active', 
		    'encodeLabel'=>false,
		    'items'=>array(
                array('visible'=>$fb, 'tag'=>"signup",'label'=>'<i class="fa fa-facebook"></i>&nbsp;',
                'url'=>$admin_fb_page,'linkOptions'=>array('target'=>"_blank")),
                                
                array('visible'=>$twiter,'tag'=>"signup",'label'=>'<i class="fa fa-twitter"></i>&nbsp;',
                'url'=>$admin_twitter_page,'linkOptions'=>array('target'=>"_blank")),
                
                array('visible'=>$google,'tag'=>"signup",'label'=>'<i class="fa fa-google-plus"></i>&nbsp;',
                'url'=>$admin_google_page,'linkOptions'=>array('target'=>"_blank")),
                
             )   
         );
	}
		
	public function getCurrencyCode()
	{								
		return $this->adminCurrencySymbol();
		// currency code define on admin
		/*$DbExt=new DbExt;
		if (is_numeric($merchant_id)){
			$currency_symbol=$this->getOption('merchant_currency',$merchant_id);
			if ( !empty($currency_symbol)){
				$stmt="SELECT * FROM 
				{{currency}}
				WHERE
				currency_code='$currency_symbol'
				LIMIT 0,1
				";				
				if ( $res=$DbExt->rst($stmt)){
					return $res[0]['currency_symbol'];
				}			
			}
		}	*/
		//return "$";
	}
			
	public function getCurrencyDetails($currency_code='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{currency}}
		WHERE
		currency_code=".FunctionsV3::q($currency_code)."
		LIMIT 0,1
		";
		if ($res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;	
	}
	
	public function getCurrencyDetailsByID($id='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{currency}}
		WHERE
		id=".FunctionsV3::q($id)."
		LIMIT 0,1
		";
		if ($res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;	
	}
		
	public function multiOptions()
	{
		return array(
		  'one'=>Yii::t("default","Can Select Only One"),
		  'multiple'=>Yii::t("default","Can Select Multiple"),
		  'custom'=>Yii::t("default","Custom")
		);
	}
	
    public function limitText($text='',$limit=100)
    {
    	if ( !empty($text)){
    		return substr($text,0,$limit)."...";
    	}    
    	return ;    	
    }
    
    public function getFoodItem($item_id='')
    {
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{item}}
			WHERE
			item_id=".FunctionsV3::q($item_id)."
			LIMIT 0,1
		";	
	    //dump($stmt);	
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
    }		       
    
    public function getFoodItem2($item_id='')
    {
    	$merchant_id=$this->getMerchantID();
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{item}}
			WHERE
			item_id=".FunctionsV3::q($item_id)."
			AND
			merchant_id =".FunctionsV3::q($merchant_id)."
			LIMIT 0,1
		";		
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
    }		           
    
    public function getFoodItemList($merchant_id='')
	{
		$data_feed=array();
		$stmt="
		SELECT * FROM
		{{item}}
		WHERE 
		merchant_id= ".FunctionsV3::q($merchant_id)."
		ORDER BY sequence ASC
		";				
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 				
		if (is_array($rows) && count($rows)>=1){
			if ($this->data=="list"){
				foreach ($rows as $val) {									   
				   $data_feed[$val['item_id']]=$val['item_name'];
				}
				return $data_feed;
			} else return $rows;
		}
		return FALSE;
	}    
	
    public function getFoodItemLists($merchant_id='')
	{
		$where='';
		if (is_numeric($merchant_id)){
			$where=" WHERE merchant_id=".$this->q($merchant_id)."";
		}			
		
		$data_feed=array();
		$stmt="
		SELECT * FROM
		{{item}}	
		$where	
		ORDER BY sequence ASC
		";				
				
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 				
		if (is_array($rows) && count($rows)>=1){
			if ($this->data=="list"){
				foreach ($rows as $val) {									   
				   $data_feed[$val['item_id']]=$val['item_name'];
				}
				return $data_feed;
			} else return $rows;
		}
		return FALSE;
	}    	
    
    public function updateOption($option_name='',$option_value='',$merchant_id='')
	{
		$and='';
		if ( !empty($merchant_id)){
			$and=" AND merchant_id=".FunctionsV3::q($merchant_id)." ";
		}
		$stmt="SELECT * FROM
		{{option}}
		WHERE
		option_name=".FunctionsV3::q($option_name)."
		$and
		";
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 		
		
		$params=array(
		'option_name'=> addslashes($option_name),
		'option_value'=> addslashes($option_value)
		);
		if ( !empty($merchant_id)){
			$params['merchant_id']=$merchant_id;
		}
		$command = Yii::app()->db->createCommand();
				
		if (is_array($rows) && count($rows)>=1){
			/*$res = $command->update('{{option}}' , $params , 
				                     'option_name=:option_name' , array(':option_name'=> addslashes($option_name) ));*/
			$res = $command->update('{{option}}' , $params , 
				                     'option_name=:option_name and merchant_id=:merchant_id' ,
				                     array(
				                      ':option_name'=> addslashes($option_name),
				                      ':merchant_id'=>$merchant_id
				                      )
				                     );
		    if ($res){
		    	return TRUE;
		    } 
		} else {			
			if ($command->insert('{{option}}',$params)){
				return TRUE;
			}
		}
		return FALSE;
	}
	
	public function getOption($option_name='',$merchant_id='')
	{
		$and='';
		if ( !empty($merchant_id)){
			$and=" AND merchant_id=".FunctionsV3::q($merchant_id)." ";
		}
		$stmt="SELECT * FROM
		{{option}}
		WHERE
		option_name= ".FunctionsV3::q($option_name)."
		$and
		LIMIT 0,1
		";
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 		
		if (is_array($rows) && count($rows)>=1){
			return stripslashes($rows[0]['option_value']);
		}
		return '';
	}
	
	public function updateOptionAdmin($option_name='',$option_value='')
	{
		$stmt="SELECT * FROM
		{{option}}
		WHERE
		option_name=".FunctionsV3::q($option_name)."
		AND
		merchant_id='0'
		";
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 		
		
		$params=array(
		'option_name'=> addslashes($option_name),
		'option_value'=> addslashes($option_value)
		);
		$command = Yii::app()->db->createCommand();
		
		if (is_array($rows) && count($rows)>=1){
			$option_id=$rows[0]['id'];			
			/*$res = $command->update('{{option}}' , $params , 
				                     'option_name=:option_name' , array(':option_name'=> addslashes($option_name) ));*/
			
			$res = $command->update('{{option}}' , $params , 
				                     'id=:id' , array(':id'=> addslashes($option_id) ));
				                     
		    if ($res){
		    	return TRUE;
		    } 
		} else {			
			if ($command->insert('{{option}}',$params)){
				return TRUE;
			}
		}
		return FALSE;
	}
	
	public function getOptionAdmin($option_name='')
	{
		$stmt="SELECT * FROM
		{{option}}
		WHERE
		option_name=".FunctionsV3::q($option_name)."
		AND
		merchant_id='0'
		LIMIT 0,1
		";
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 		
		if (is_array($rows) && count($rows)>=1){
			return stripslashes($rows[0]['option_value']);
		}
		return '';
	}	
	
	public function getDays()
	{
		return array(
		  'monday'=>Yii::t("default",'monday'),
		  'tuesday'=>Yii::t("default",'tuesday'),
		  'wednesday'=>Yii::t("default",'wednesday'),
		  'thursday'=>Yii::t("default",'thursday'),
		  'friday'=>Yii::t("default",'friday'),
		  'saturday'=>Yii::t("default",'saturday'),
		  'sunday'=>Yii::t("default",'sunday')
		);
	}
	
	public function decimalPlacesList()
    {
    	$numbers=array();
    	for ($x=0; $x<=10; $x++) {            
    		$numbers[$x]=$x;
    	} 
    	return $numbers;
    }
    
    public function defaultDecimal()
    {
    	return 2;
    }
    
	public function currencyList()
    {
        $data_feed=array();
		$stmt="
		SELECT * FROM
		{{currency}}					
		ORDER BY currency_code ASC
		";		
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll();
		if (is_array($rows) && count($rows)>=1){			
			$data_feed[]="";
			foreach ($rows as $val) {									   
			   $data_feed[$val['currency_code']]=$val['currency_code'];
			}
			return $data_feed;			
		}
		return FALSE;
    }
    
    public function defaultCurrency()
    {
    	return 'USD';
    }
    
	public function getCityList()
	{		
		$lists='';
		$DbExt=new DbExt;
		$stmt="SELECT city,country_code,state FROM
		      {{merchant}}
		      GROUP BY city ASC
		";
		if ( $res=$DbExt->rst($stmt)){			
			return $res;
		}
		return false;
	}   

	public function searchByArea($city='',$state='')
	{
				
		if (!isset($_GET['iDisplayStart'])){
			$Start_page=0;
		} else $Start_page=$_GET['iDisplayStart'];
		
		if (!isset($_GET['iDisplayLength'])){			
			$per_page=10;
		} else $per_page=$_GET['iDisplayLength'];
		
		if (isset($_GET['debug'])){
		   dump("START_>".$Start_page);
		   dump("PERPAGE_>".$per_page);
		}
		
		$and='';
		$filter_delivery='';$filter_delivery_arr=array();
		if (isset($_GET['filter_delivery_type'])){			
			$filter_delivery_type=!empty($_GET['filter_delivery_type'])?explode(",",$_GET['filter_delivery_type']):false;
			if (is_array($filter_delivery_type) && count($filter_delivery_type)>=1){
				foreach ($filter_delivery_type as $val) {
					if (!empty($val)){
						//$filter_delivery.="'$val',";
						$filter_delivery.=FunctionsV3::q($val).",";
						$filter_delivery_arr[]=$val;
					}
				}
				if (in_array(1,(array)$filter_delivery_arr)){
					$filter_delivery='';
				}
				if (!empty($filter_delivery)){
				   $filter_delivery=substr($filter_delivery,0,-1);
				   $and=" AND service IN ($filter_delivery) ";
				}
			}
		}	
		
		$filter_cuisine='';
		if (isset($_GET['filter_cuisine'])){
			$filter_cuisines=!empty($_GET['filter_cuisine'])?explode(",",$_GET['filter_cuisine']):false;
			if (is_array($filter_cuisines) && count($filter_cuisines)>=1){
				$x=1;
				foreach ($filter_cuisines as $val) {				
					if (!empty($val)){
						if ( $x==1){
							$filter_cuisine.=" LIKE '%\"$val\"%'";
						} else $filter_cuisine.=" OR cuisine LIKE '%\"$val\"%'";
						$x++;
					}
				}				
				if (!empty($filter_cuisine)){
				   $and.=" AND (cuisine $filter_cuisine) ";
				}
			}
		}
		
		$filter_promo='';
		if (isset($_GET['filter_promo'])){
			$filter_promo=!empty($_GET['filter_promo'])?explode(",",$_GET['filter_promo']):false;
			if (is_array($filter_promo) && count($filter_promo)>=1){				
				foreach ($filter_promo as $val) {				
					if (!empty($val)){						
						if ( $val=="free-delivery"){
						    //$and.=" AND free_delivery ='2' ";
						    $and.=" AND coalesce(delivery_charges, '') = '' ";
						}
					}
				}								
			}
		}
		
		$filter_minimum='';
		if (isset($_GET['filter_minimum'])){
			if (is_numeric($_GET['filter_minimum'])){
				$and.=" AND CAST(minimum_order as SIGNED) <=".FunctionsV3::q($_GET['filter_minimum'])." ";
			}		
		}	
		
		$filter_name='';
		if (isset($_GET['filter_name'])){
			if ( !empty($_GET['filter_name'])){
				$and.=" AND restaurant_name LIKE ".FunctionsV3::q($_GET['filter_name']."%")."  ";
			}		
		}
					
	
		$sort_by="restaurant_name ASC";
		if (isset($_GET['sort_filter'])){
			if (!empty($_GET['sort_filter'])){
				if ( $_GET['sort_filter']!="distance" ){
					if ( $_GET['sort_filter']=="minimum_order"){
						$sort_by="CAST(".$_GET['sort_filter']." AS SIGNED ) ASC";
					} elseif ( $_GET['sort_filter']=="ratings" ){
						$sort_by="CAST(".$_GET['sort_filter']." AS SIGNED ) DESC";
					} else $sort_by="".$_GET['sort_filter']." ASC";				
			    } 
			}		
		}
		
		$DbExt=new DbExt;
				
		$this->updateMerchantSponsored();
		$this->updateMerchantExpired();
		
		$and.="AND status='active' ";
		$and.="AND is_ready='2' ";
		
		$and0='';
		$sort_by0=" ORDER BY is_sponsored DESC";		
		
		$sort_combine="$sort_by0,$sort_by";
		if (isset($_GET['sort_filter'])){
			if (!empty($_GET['sort_filter'])){
				$sort_combine="ORDER BY ".$sort_by;
			}		
		}	
					
		$home_search_unit_type=Yii::app()->functions->getOptionAdmin('home_search_unit_type');
		$home_search_radius=Yii::app()->functions->getOptionAdmin('home_search_radius');
				
		if (empty($home_search_unit_type)){
			$home_search_unit_type='mi';
		}	
		if (!is_numeric($home_search_radius)){
			$home_search_radius=10;
		}			
				
		$count_query=false;
		
		if (isset($_GET['restaurant-name'])){
			$stmt="SELECT * FROM
			       {{view_merchant}}
			       WHERE
			       restaurant_name LIKE ".FunctionsV3::q("%".$_GET['restaurant-name']."%")."
			       $and0
				   $and
				   $sort_combine
				   LIMIT $Start_page,$per_page
			";
			
			$stmt2="SELECT a.*,count(*) as total_records FROM
			       {{view_merchant}} a
			       WHERE
			       restaurant_name LIKE ".FunctionsV3::q("%".$_GET['restaurant-name']."%")."
			       $and0
				   $and				   
				   LIMIT 0,1
			";
		} elseif (isset($_GET['street-name']))	{
			$stmt="SELECT * FROM
			       {{view_merchant}}
			       WHERE
			       street LIKE ".FunctionsV3::q("%".$_GET['restaurant-name']."%")."
			       $and0
				   $and
				   $sort_combine
				   LIMIT $Start_page,$per_page
			";
						
			$stmt2="SELECT count(*) as total_records, a.* FROM
			       {{view_merchant}} a
			       WHERE
			       street LIKE ".FunctionsV3::q("%".$_GET['street-name']."%")."
			       $and0
				   $and				   
				   LIMIT 0,1
			";
			
		} elseif (isset($_GET['category'])){		
			$cuisine_id='';
			if ( $cat_res=$this->GetCuisineByName($_GET['category'])){				
				$cuisine_id='"'.$cat_res['cuisine_id'].'"';
			} else $cuisine_id="-1";		
						
			$stmt="SELECT * FROM
			       {{view_merchant}}
			       WHERE
			       cuisine LIKE ".FunctionsV3::q("%$cuisine_id%")."
			       $and0
				   $and
				   $sort_combine
				   LIMIT $Start_page,$per_page
			";			
			$stmt2="SELECT 
			       count(*) as total_records,
			       a.* FROM
			       {{view_merchant}} a
			       WHERE
			       cuisine LIKE ".FunctionsV3::q("%$cuisine_id%")."
			       $and0
				   $and				   
				   LIMIT 0,1
			";			
			
		} elseif (isset($_GET['foodname'])){		
						
			$foodname_str='';
			if (isset($_GET['foodname'])){
				if (!empty($_GET['foodname'])){
					//$foodname_str= "%".$_GET['foodname']."%" ;
					$foodname_str= FunctionsV3::q("%".$_GET['foodname']."%");
				} else $foodname_str='-1';			
			} else $foodname_str='-1';			
			
			       $stmt="SELECT a.* FROM
			       {{view_merchant}} a
			       WHERE
			       merchant_id = (
			         select merchant_id
			         from
			         {{item}}
			         where
			         item_name like ".$this->q($foodname_str)."
			         and
			         merchant_id=a.merchant_id
			         limit 0,1
			       )
			       $and0
				   $and
				   $sort_combine
				   LIMIT $Start_page,$per_page
			       ";	
			       
			       $stmt2="SELECT 
			       count(*) as total_records,
			       a.* FROM
			       {{view_merchant}} a
			       WHERE
			       merchant_id = (
			         select merchant_id
			         from
			         {{item}}
			         where
			         item_name like ".$this->q($foodname_str)."
			         and
			         merchant_id=a.merchant_id
			         limit 0,1
			       )
			       $and0
				   $and				   
				   LIMIT 0,1
			       ";	
						
		} elseif (isset($_GET['stype'])){
			
			 /*this is the search if admin set the search to postcode*/
			 switch ($_GET['stype'])
			 {
			 	case "1":	
				 	$zipcode=!empty($_GET['zipcode'])?explode(" ",$_GET['zipcode']):false;			 	
				 	if($zipcode==false){
				 		$zipcode[0]='-1';
				 	}			 
				 	$stmt="SELECT SQL_CALC_FOUND_ROWS a.*
				     FROM
				     {{view_merchant}} a 
				     WHERE 
				     post_code LIKE ".q($zipcode[0])."
				     $and0
					 $and
					 $sort_combine
					 LIMIT $Start_page,$per_page
				    ";
			 	break;
			 	
			 	case "2":
			 		 $city=isset($_GET['city'])?$_GET['city']:'';
			 		 $area=isset($_GET['area'])?$_GET['area']:'';
			 		 $stmt="SELECT SQL_CALC_FOUND_ROWS a.*
				     FROM
				     {{view_merchant}} a 
				     WHERE 
				     city LIKE ".q($city."%")."
				     AND 
				     state LIKE ".q($area."%")."
				     $and0
					 $and
					 $sort_combine
					 LIMIT $Start_page,$per_page
				    ";
			 		break;
			 		
			 	case "3":		
			 	   $address=isset($_GET['address'])?explode(",",$_GET['address']):false;			 	   
			 	   if ($address==false){
			 	   	   $address[0]='-1';
			 	   	   $address[1]='-1';
			 	   	   $address[2]='-1';
			 	   	   $address[3]='-1';
			 	   }
			 	   $stmt="SELECT SQL_CALC_FOUND_ROWS *
				     FROM
				     {{view_merchant}} 
				     WHERE
				     street LIKE ".q($address[0]."%")."
				     AND state LIKE ".q($address[1]."%")."
				     AND city LIKE ".q($address[2]."%")."
				     AND post_code  LIKE ".q($address[3]."%")."
				     $and0
					 $and
					 $sort_combine
					 LIMIT $Start_page,$per_page
				    ";
			 	   break;
			 }			     
		     $stmt2="SELECT FOUND_ROWS()";
			 $count_query=true;
		} else {			
			if ($lat_res=$this->geodecodeAddress($_GET['s'])){			
				$lat=$lat_res['lat'];
				$long=$lat_res['long'];
				//HAVING distance < 25 		
				
				$distance_exp=3959;
				if ($home_search_unit_type=="km"){
					$distance_exp=6371;
				}		
				
				if (empty($lat)){
					$lat=0;
				}		
				if (empty($long)){
					$long=0;
				}					
				$stmt="
				SELECT SQL_CALC_FOUND_ROWS a.*, ( $distance_exp * acos( cos( radians($lat) ) * cos( radians( latitude ) ) 
				* cos( radians( lontitude ) - radians($long) ) 
				+ sin( radians($lat) ) * sin( radians( latitude ) ) ) ) 
				AS distance								
				
				FROM {{view_merchant}} a 
				HAVING distance < $home_search_radius
				$and0
				$and
				$sort_combine
				LIMIT $Start_page,$per_page
				";
								
				/*$stmt2="
				SELECT a.*, ( $distance_exp * acos( cos( radians($lat) ) * cos( radians( latitude ) ) 
				* cos( radians( lontitude ) - radians($long) ) 
				+ sin( radians($lat) ) * sin( radians( latitude ) ) ) ) 
				AS distance								
				
				FROM {{view_merchant}} a 
				HAVING distance < $home_search_radius
				$and0
				$and
				$sort_combine				
				";*/
				$stmt2="
				SELECT FOUND_ROWS()
				";
				$count_query=true;			
				
			} else {
				$stmt="SELECT a.*				
				 FROM
				{{view_merchant}} a
				WHERE
				city like ".FunctionsV3::q("%$city%")."
				$and0
				$and
				$sort_combine
				LIMIT $Start_page,$per_page
				";
				
				$stmt2="SELECT 
				count(*) as total_records,
				a.*				
				 FROM
				{{view_merchant}} a
				WHERE
				city like ".FunctionsV3::q("%$city%")."
				$and0
				$and
				$sort_combine
				LIMIT 0,1
				";
			}	
		}
				
		/*$home_search_mode=Yii::app()->functions->getOptionAdmin('home_search_mode');
		if ( $home_search_mode=="postcode"){			
			$postcode=substr($city,0,2);
			
			$stmt="SELECT a.*				
			 FROM
			{{view_merchant}} a
			WHERE
			post_code like '$postcode%'									
			$and
			$sort_combine
		    ";								
			$stmt2="
			SELECT FOUND_ROWS()
			";
			$count_query=true;			
			
		}*/		
			
		/*if (preg_match("/city like/i", $stmt)) {
			if ( !$res=$DbExt->rst($stmt)){
			    $stmt="SELECT a.*				
				 FROM
				{{view_merchant}} a
				WHERE
				post_code like '%$city%'						
				$and0
				$and
				$sort_combine
				";
			    
			    $stmt2="SELECT count(*) as total_records,a.*				
				 FROM
				{{view_merchant}} a
				WHERE
				post_code like '%$city%'						
				$and0
				$and				
				";
			}
		}*/
		
		if (isset($_GET['debug'])){
			dump($this->data);
			dump($stmt);
			dump($stmt2);
		}			
			
		/*$this->search_result_total=0;	
		if ( $res_total=$DbExt->rst($stmt2)){	
			if (isset($_GET['debug'])){
				dump("RESP TOTAL");
				dump($res_total);
			}
			if ( $count_query==true){
				$this->search_result_total=count($res_total);
			} else $this->search_result_total=$res_total[0]['total_records'];			
			if (isset($_GET['debug'])){
				echo "total->".$this->search_result_total;
			}		
		}*/
		
		$DbExt->qry("SET SQL_BIG_SELECTS=1");
		
		if ( $res=$DbExt->rst($stmt)){			
			if (isset($_GET['debug'])){
			    dump($res);
			}
						
			$this->search_result_total=0;	
			if ( $res_total=$DbExt->rst($stmt2)){	
				if (isset($_GET['debug'])){
					dump("RESP TOTAL");
					dump($res_total);
				}
				if ( $count_query==true){
					$this->search_result_total=$res_total[0]['FOUND_ROWS()'];
				} else $this->search_result_total=$res_total[0]['total_records'];			
				if (isset($_GET['debug'])){
					echo "total->".$this->search_result_total;
				}		
			}
			
			return $res;
		}
		return false;
	}
	
	public function geodecodeAddress($address='')
	{
		try {			
			$resp = MapsWrapper::geoCodeAdress($address);			
			return $resp;
		} catch (Exception $e) {
	        return false;
	    }				
	}
	
	public function updateMerchantSponsored()
	{
		$DbExt=new DbExt;
		$today = date('Y-m-d');
		$stmt="UPDATE
		{{merchant}}
		SET is_sponsored='1'
		WHERE
		is_sponsored='2'
		AND
		sponsored_expiration <'$today'
		";
		$DbExt->qry($stmt);		
	}	
	
	public function updateMerchantExpired()
	{
		$DbExt=new DbExt;
		$today = date('Y-m-d');
		$stmt="UPDATE
		{{merchant}}
		SET status='expired'
		WHERE
		status='active'
		AND
		membership_expired <'$today'
		AND
		is_commission='1'
		";				
		$DbExt->qry($stmt);		
	}
	
	public function getRatings($merchant_id='')
	{		
		$stmt="SELECT SUM(rating) as ratings ,COUNT(*) AS count
		FROM
		{{review}}
		WHERE
		merchant_id= ".FunctionsV3::q($merchant_id)."
		AND
		status in ('publish','published')
		 ";		
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			if ( $res[0]['ratings']>=1){
				$ret=array(
				  'ratings'=>number_format($res[0]['ratings']/$res[0]['count'],1),
				  'votes'=>$res[0]['count']
				);
			} else {
				$ret=array(
			     'ratings'=>0,
			      'votes'=>0
			   );
			}
		} else {
			$ret=array(
			  'ratings'=>0,
			  'votes'=>0
			);
		}		
		return $ret;
	}
	
	public function getRatingsMeaning($rating='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{rating_meaning}}
		WHERE
		rating_start<=".FunctionsV3::q($rating)." AND rating_end>=".FunctionsV3::q($rating)."
		ORDER BY rating_start ASC		
		";		
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
	public function getRatingInfo($id='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{rating_meaning}}
		WHERE
		id= ".FunctionsV3::q($id)."
		LIMIT 0,1
		";		
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
	public function isClientRatingExist($merchant_id='',$client_id='')
	{
	    $DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{rating}}
		WHERE		
		merchant_id= ".FunctionsV3::q($merchant_id)."
		AND
		client_id= ".FunctionsV3::q($client_id)."
		LIMIT 0,1
		";		
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
	public function removeRatings($merchant_id='',$client_id='')
	{
		$DbExt=new DbExt;
		$stmt="DELETE FROM
		{{rating}}
		WHERE
		merchant_id=".FunctionsV3::q($merchant_id)."
		AND
		client_id=".FunctionsV3::q($client_id)."
		";
		if ( $DbExt->qry($stmt)){
			return true;
		}
		return false;
	}

	public function sizePriceToArray($json_data='',$discount='')
	{		
		$this->data='list';
		$data=array();
		$size=$this->getSizeListAll();				
		$json_data=!empty($json_data)?json_decode($json_data,true):false;		
		if(is_array($json_data) && count($json_data)>=1){
			foreach ($json_data as $size_id=>$price) {				
				if (array_key_exists($size_id,(array)$size)){					
					
					$size_info=$this->getSize($size_id);		
					
					if(isset($size[$size_id])){
					   $size_name = $size[$size_id];
					} else $size_name='';

									
					$formatted_price = FunctionsV3::prettyPrice($price);
	    			
	    			$discount_price = 0;
	    			if($discount>=0.001){
	    				$discount_price = $price-$discount;
	    			}
					
					$data[]=array(
					  'price'=>$price,
					  'size'=>$size[$size_id],
					  'size_id'=>$size_id,
					  'size_trans'=>!empty($size_info['size_name_trans'])?json_decode($size_info['size_name_trans'],true):'',
					  'formatted_price'=>$formatted_price,
					  'discount_price'=>$discount_price,
					  'formatted_discount_price'=>FunctionsV3::prettyPrice($discount_price)
					);
				} else {
					$data[]=array(
					  'price'=>$price,
					);
				}
			}
			return $data;
		}
		return false;
	}
	
	public function cookingRefToArray($json_data='')
	{
		$data=array();
		$json_data=!empty($json_data)?json_decode($json_data,true):false;		
		$cooking_ref=$this->getCookingRefAll();		
		//if ( $json_data!=false){
		if(is_array($json_data) && count((array)$json_data)>=1){
			foreach ($json_data as $cooking_id) {
				if (array_key_exists($cooking_id,(array)$cooking_ref)){
					$data[$cooking_id]=$cooking_ref[$cooking_id];
				}
			}
			return $data;
		}
		return false;
	}
	
	public function addOnItemToArray($json_data='',
	$multi_option='',$multi_option_val,$two_flavors_position='',$require_addon='',$merhant_id='')
	{		
								
		$data=array();
		$data_sub=array();
		$json_data=!empty($json_data)?json_decode($json_data):false;		
		$this->data="list";
		$sub_category=$this->getSubcategory2($merhant_id);		
		//dump($sub_category);
				
		if ( $json_data!=FALSE ){
			foreach ($json_data as $sub_id=>$val) {								
				if (array_key_exists($sub_id,(array)$sub_category)){
					foreach ($val as $subitem_id) {						
						if ($subitem_details=$this->getAddonItem($subitem_id,'sequence')){	
														
							$data_sub[]=array(
							  'sub_item_id'=>$subitem_details['sub_item_id'],
							  'sub_item_name'=>$subitem_details['sub_item_name'],
							  'item_description'=>$subitem_details['item_description'],
							  'price'=>$subitem_details['price'],
							  'photo'=>$subitem_details['photo'],
							  'sub_item_name_trans'=>!empty($subitem_details['sub_item_name_trans'])?json_decode($subitem_details['sub_item_name_trans'],true):'',
							  'item_description_trans'=>!empty($subitem_details['item_description_trans'])?json_decode($subitem_details['item_description_trans'],true):'',
							  'pretty_price'=>FunctionsV3::prettyPrice($subitem_details['price'])
							);
						}						
					}
					
					$multi_options='';					
					if (array_key_exists($sub_id,(array)$multi_option)){						
						$multi_options=$multi_option[$sub_id];
					}
					
					$multi_option_vals='';
					if (array_key_exists($sub_id,(array)$multi_option_val)){						
						$multi_option_vals=$multi_option_val[$sub_id];
					}
					
					$two_flavor='';
					if (array_key_exists($sub_id,(array)$two_flavors_position)){						
						$two_flavor=$two_flavors_position[$sub_id];
					}
										
					$require_addons='';
					if (array_key_exists($sub_id,(array)$require_addon)){												
						$require_addons=$require_addon[$sub_id];
					}
												
					$data[]=array(
					  'subcat_id'=>$sub_id,
					  'subcat_name'=>$sub_category[$sub_id],
					  'subcat_name_trans'=>$this->getSubcategoryTranslation($sub_id),
					  'multi_option'=>$multi_options,
					  'multi_option_val'=>$multi_option_vals,
					  'two_flavor_position'=>$two_flavor,
					  'require_addons'=>$require_addons,
					  'sub_item'=>$data_sub					  
					);
					//$data_sub='';
					unset($data_sub);
				}			
			}
			//dump($data);
			return $data;
		}
		return false;
	}
	
	public function multiOptionToArray($json_data='')
	{
		$data=array();
		$json_data=!empty($json_data)?(array)json_decode($json_data):false;		
		if (is_array($json_data) && count($json_data)>=1){
			foreach ($json_data as $key=>$val) {				
				$data[$key]=$val[0];
			}
			return $data;
		}
		return false;
	}
	
	public function getItemByCategory($category_id='',$addon=false,$merchant_id='')
	{
		$DbExt=new DbExt;
		$data=array();
		//$category='%"'.$category_id.'"%';
					
		$and="";
		if (!empty($merchant_id)){
			$and=" AND merchant_id =".FunctionsV3::q($merchant_id)." ";
		}
		
        $food_option_not_available=getOption($merchant_id,'food_option_not_available');		
		if (!empty($food_option_not_available)){
			if ($food_option_not_available==1){
				$and.=" AND not_available!='2'";
			}
		}		
		
		//category like '$category'
		
		$stmt="SELECT * FROM
		{{item}}
		WHERE
		category like ".FunctionsV3::q('%"'.$category_id.'"%')."
		AND
		status IN ('publish','published')
		$and
		ORDER BY sequence ASC
		";		
		//dump($stmt);
		/*inventory*/
		if (FunctionsV3::hasModuleAddon('inventory')){
			if(FunctionsV3::checkIfTableExist('view_item_stocks_status')){	
			   Yii::app()->setImport(array(			
			       'application.modules.inventory.components.*',
		       ));		
		       
		       $and_inv ='';
		   	   if (!empty($merchant_id)){
				 $and_inv=" AND merchant_id =".FunctionsV3::q($merchant_id)." ";
			   }
				  
			   if(InventoryWrapper::hideItemOutStocks($merchant_id)){			   	  
			   	   	  
			   	  $stmt="SELECT a.* FROM
					{{item}} a
					WHERE
					category like ".FunctionsV3::q('%"'.$category_id.'"%')."
					AND
					status IN ('publish','published')		
					$and_inv	
					AND item_id IN (
					  select item_id from {{view_item_stocks_status}}
					  where available ='1'
					  and track_stock='1'
					  and stock_status not in ('Out of stocks')		
					  and item_id = a.item_id				  
					)		
					ORDER BY sequence ASC
					";		
			   } else {
			   	   if($food_option_not_available==1):
			   	   $stmt="SELECT a.* FROM
					{{item}} a
					WHERE
					category like ".FunctionsV3::q('%"'.$category_id.'"%')."
					AND
					status IN ('publish','published')			
					$and_inv
					AND item_id IN (
					   select item_id from {{item_relationship_size}}
					   where available ='1'					
					   and item_id = a.item_id		   
					)		
					ORDER BY sequence ASC
					";		
			   	   endif;
			   }
			}
		}
		/*end inventory*/
		
		//dump($stmt);
		
		if ( $res=$DbExt->rst($stmt)){			
			foreach ($res as $val) {				
												
				$multi_option=$this->multiOptionToArray($val['multi_option']);
				$multi_option_val=$this->multiOptionToArray($val['multi_option_value']);
				
				$price=$this->sizePriceToArray($val['price'],$val['discount']);
				$cooking_ref=$this->cookingRefToArray($val['cooking_ref']);
				$addon_item=$this->addOnItemToArray($val['addon_item'],$multi_option,$multi_option_val);
				
				$single_item=1;
				$single_details=array();
				if (!is_array($addon_item) && count( (array) $addon_item)<=1){					
					if ( count( (array) $price)<=1){
					   $single_item=2;					   
					   //$single_details['price']=$price[0]['price']-$val['discount'];
					   $single_details['price']=$price[0]['price'];
					   $single_details['size']=$price[0]['size'];
					   $single_details['size_id']=$price[0]['size_id'];
					}
				}
								
				if (is_array($cooking_ref) && count($cooking_ref)>=1){
					$single_item=1;
				}				
				if (strlen($val['ingredients'])>4){
					$single_item=1;
				}
								
				if ($addon==TRUE){
					$data[]=array(
					  'item_id'=>$val['item_id'],
					  'item_name'=>$val['item_name'],
					  'item_description'=>$val['item_description'],
					  'discount'=>$val['discount'],
					  'photo'=>$val['photo'],
					  'prices'=>$price,
					  'spicydish'=>$val['spicydish'],
					  'dish'=>$val['dish'],
                      'item_name_trans'=>!empty($val['item_name_trans'])?json_decode($val['item_name_trans'],true):'',
                      'item_description_trans'=>!empty($val['item_description_trans'])?json_decode($val['item_description_trans'],true):'',
					  'single_item'=>$single_item,
					  'single_details'=>$single_details,
					  'not_available'=>$val['not_available']
					  /*'cooking_ref'=>$cooking_ref,				  
					  'addon_item'=>$addon_item*/
					);				
				} else {
					$data[]=array(
				      'item_id'=>$val['item_id'],
				      'item_name'=>$val['item_name'],
				      'item_description'=>$val['item_description'],
				      'discount'=>$val['discount'],
				      'photo'=>$val['photo'],
				      'spicydish'=>$val['spicydish'],
				      'dish'=>$val['dish'],
				      'item_name_trans'=>!empty($val['item_name_trans'])?json_decode($val['item_name_trans'],true):'',
				      'item_description_trans'=>!empty($val['item_description_trans'])?json_decode($val['item_description_trans'],true):'',
				      'prices'=>$price,				
				      'single_item'=>$single_item,
				      'single_details'=>$single_details,
				      'not_available'=>$val['not_available']
				    );				
				}
			}
			return $data;
		}
		return false;
	}
	
	public function getItemById($item_id='',$addon=true)
	{				
		$data=array();		
		$stmt="SELECT * FROM
		{{item}}
		WHERE
		item_id = ".FunctionsV3::q($item_id)."
		LIMIT 0,1		
		";				
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			foreach ($res as $val) {				
												
				$multi_option=$this->multiOptionToArray($val['multi_option']);
				$multi_option_val=$this->multiOptionToArray($val['multi_option_value']);
				$two_flavors_position=$this->multiOptionToArray($val['two_flavors_position']);
				
				$require_addon=$this->multiOptionToArray($val['require_addon']);							
																			
				$price=$this->sizePriceToArray($val['price'],$val['discount']);						
				$cooking_ref=$this->cookingRefToArray($val['cooking_ref']);
				
                $addon_item=$this->addOnItemToArray(
                  $val['addon_item'],
                  $multi_option,$multi_option_val,
                  $two_flavors_position,
                  $require_addon,
                  $val['merchant_id']
                );
				$ingredients=$this->ingredientsToArray($val['ingredients']);
				
				$cooking_ref2=$this->cookingRefToArray2($val['cooking_ref']);				
				
				if ($addon==TRUE){
					$data[]=array(
					  'merchant_id'=>$val['merchant_id'],
					  'item_id'=>$val['item_id'],
					  'item_name'=>$val['item_name'],
					  'item_description'=>$val['item_description'],
					  'item_name_trans'=>!empty($val['item_name_trans'])?json_decode($val['item_name_trans'],true):'',
					  'item_description_trans'=>!empty($val['item_description_trans'])?json_decode($val['item_description_trans'],true):'',
					  'discount'=>$val['discount'],
					  'photo'=>$val['photo'],
					  'prices'=>$price,
					  'cooking_ref'=>$cooking_ref,				  
					  'cooking_ref_trans'=>isset($cooking_ref2['cooking_name_trans'])?$cooking_ref2['cooking_name_trans']:'',
					  'addon_item'=>$addon_item,
					  'ingredients'=>$ingredients,
					  'spicydish'=>$val['spicydish'],
					  'dish'=>$val['dish'],
					  'two_flavors'=>$val['two_flavors'],
					  'gallery_photo'=>$val['gallery_photo'],
					  'not_available'=>$val['not_available']
					);	
				} else {
					$data[]=array(
					  'merchant_id'=>$val['merchant_id'],
				      'item_id'=>$val['item_id'],
				      'item_name'=>$val['item_name'],
				      'item_description'=>$val['item_description'],
				      'item_name_trans'=>!empty($val['item_name_trans'])?json_decode($val['item_name_trans'],true):'',
					  'item_description_trans'=>!empty($val['item_description_trans'])?json_decode($val['item_description_trans'],true):'',
				      'discount'=>$val['discount'],
				      'photo'=>$val['photo'],
				      'prices'=>$price,				  
				      'ingredients'=>$ingredients,
				      'spicydish'=>$val['spicydish'],
				      'dish'=>$val['dish'],
				      'two_flavors'=>$val['two_flavors'],
				      'gallery_photo'=>$val['gallery_photo'],
				      'not_available'=>$val['not_available']      
				    );				
				}
			}			
			return $data;
		}
		return false;
	}	
		
	public function getMerchantMenu($merchant_id='',$food_name='', $todays_day='')
	{		
		$data=array();
		$this->data='list';
		if ( $res=$this->getCategoryList2($merchant_id , $food_name , $todays_day )){						
			foreach ($res as $cat_i=>$cat_name) {				
				$data[]=array(
				  'category_id'=>$cat_i,
				  'category_name'=>$cat_name['category_name'],
				  'category_description'=>$cat_name['category_description'],
				  'category_name_trans'=>!empty($cat_name['category_name_trans'])?json_decode($cat_name['category_name_trans'],true):'',
				  'photo'=>$cat_name['photo'],
				  'category_description_trans'=>!empty($cat_name['category_description_trans'])?json_decode($cat_name['category_description_trans'],true):'',
				  'dish'=>$cat_name['dish'],
				  'item'=>$this->getItemByCategory($cat_i,false,$merchant_id)
				);
			}
			return $data;
		}
		return false;
	}
	
	public function unPrettyPrice($price='')
	{
		if ( !empty($price)){
			//return number_format($price,2,".","");
			return str_replace(",","",$price);
		}
		return false;
	}
	
	/*prettyFormat is control on admin area not merchant*/
	
	public function prettyFormat($price='',$merchant_id='')
	{
		/*$decimal=yii::app()->functions->getOption('merchant_decimal',$merchant_id);
		$decimal_separators=yii::app()->functions->getOption('merchant_use_separators',$merchant_id);*/		
		
		$decimal=Yii::app()->functions->getOptionAdmin('admin_decimal_place');
		$decimal_separators=Yii::app()->functions->getOptionAdmin('admin_use_separators');		
		
		$thousand_separator=Yii::app()->functions->getOptionAdmin('admin_thousand_separator');
        $decimal_separator=Yii::app()->functions->getOptionAdmin('admin_decimal_separator');
        
        if (empty($thousand_separator)){
        	$thousand_separator=',';
        }
        if (empty($decimal_separator)){
        	$decimal_separator='.';
        }
		
		$thou_separator='';
		if (!empty($price)){
			if ($decimal==""){
				$decimal=2;
			}
			if ( $decimal_separators=="yes"){
				//$thou_separator=",";
				$thou_separator=$thousand_separator;
			}		
			//return number_format((float)$price,$decimal,".",$thou_separator);
			return number_format((float)$price,$decimal,$decimal_separator,$thou_separator);
		}	
		if ($decimal==""){
			$decimal=2;
		}	
		//return number_format(0,$decimal,".",$thou_separator);	
		$thou_separator=$thousand_separator;
		return number_format(0,$decimal,$decimal_separator,$thou_separator);	
	}
	
	public function explodeData($data='')
	{
		if (preg_match("/|/i", $data)) {
			$ret=explode("|",$data);
			if (is_array($ret) && count($ret)>=1){
				return $ret;
			}
		}
		return false;
	}
	
	public function displayOrderHTML($data='',$cart_item='',$receipt=false,$new_order_id='')
	{
		$item_array=array();
		$this->code=2;
		$htm='';	
    	$subtotal=0;
    	$subtotal_non=0;
    	$mid=isset($data['merchant_id'])?$data['merchant_id']:'';    	
    	    	
    	if (empty($mid)){
    		$this->msg=Yii::t("default","Merchant ID is empty");
    		return ;
    	}	
    	    	        	
    	Yii::app()->functions->data="list";
    	$food_item=Yii::app()->functions->getFoodItemLists($mid);
    	$subcat_list=Yii::app()->functions->getAddOnLists($mid);	   
    	
    	$merchant_packaging_wise = getOption($mid,'merchant_packaging_wise');
    	$wise_packaging_fee = 0;
    	$merchant_packaging_charge = 0;
    	    	    	    		       
    	if (isset($cart_item)){
    		if (is_array($cart_item) && count($cart_item)>=1){
    			$x=0;
    			foreach ($cart_item as $key=>$val) {	    
    				
    				//dump($val);				
    				
    				$val['notes']=isset($val['notes'])?$val['notes']:"";
    				
    				$size_words=''; $_size_id=0;
    				$t=!empty($val['price'])?explode("|",$val['price']):'';    				
    				if (is_array($t) && count($t)>=1){
    					$val['price']=$t[0];
    					if (isset($t[1])){
    					    $size_words=$t[1];
    					    $_size_id=isset($t[2])?(integer)$t[2]:0;  // inventory new code
    					} else $size_words='';					
    				}
    				
    				$price=cleanNumber(unPrettyPrice($val['price']));
    				if (!empty($val['discount'])){
    					$val['discount']=unPrettyPrice($val['discount']);
    					$price=$price-$val['discount'];
    				}    			
    				
    				$qty=$val['qty'];  /** fixed addon qty */    				
    				$total_price=$val['qty']*(float)$price;
    				    		
    				/** check if item is taxable*/
    				//dump($val);
    				$food_taxable=true;    				    				
    				if (isset($val['non_taxable'])){
    					if ( $val['non_taxable']==2){
    						$food_taxable=false;
    					}	
    				}    		
    				    				    			    				
    				$subtotal=$subtotal+$total_price; 
    				
    				if ($food_taxable==false){
    					//dump($total_price);
    					$subtotal_non=$subtotal_non+$total_price;    					
    				}  			
    				
    				/*$size_words='';
    				if ( $price_size=explodeData($val['price'])){	    					
    					if (isset($price_size[1])){
    					    $size_words=$price_size[1];
    					}
    				}	*/    				    				
    				
    				/** Translation */
    				$food_infos=array(); $food_info = array();
    				$size_info_trans='';
    				$cooking_ref_trans='';
    				if ($this->getOptionAdmin("enabled_multiple_translation")==2){
	    				$food_info=$this->getFoodItem($val['item_id']);
	    				$food_infos['item_name_trans']=!empty($food_info['item_name_trans'])?json_decode($food_info['item_name_trans'],true):'';		
	    					    				
	    				if (!empty($size_words)){
	    				    $size_info_trans=$this->getSizeTranslation($size_words,$mid);
	    				}	    		

	    				if(!empty($val['cooking_ref'])){	    					
	    					$cooking_ref_trans=$this->getCookingTranslation($val['cooking_ref'],$mid);
	    				}	
    				} else $food_info=$this->getFoodItem($val['item_id']);
    				
    				
    				/*GET PACKAGING CHARGE*/
    				if($merchant_packaging_wise==1){ 
    				   if(isset($food_info['packaging_fee'])){
	    				   if($food_info['packaging_fee']>0){
	    				   	   if ($food_info['packaging_incremental']==1){
	    				   	   	  $wise_packaging_fee+= $food_info['packaging_fee']*$qty;
	    				   	   } else {	    				   	   	
	    				   	   	   $wise_packaging_fee+= $food_info['packaging_fee'];
	    				   	   }
	    				   }
    				   }
    				}
    				
    				/*dump($food_item);
    				dump($val['item_id']);*/    				
    				
    				$htm.='<div class="item-order-list item-row">';
    				    				  
    				  $val['category_id']=isset($val['category_id'])?$val['category_id']:'';
    				  
    				  if ($res_category=$this->getCategory($val['category_id'])){     				  	  
    				  	  $category_name_trans['category_name_trans']=!empty($res_category['category_name_trans'])?
    				  	  json_decode($res_category['category_name_trans'],true):false; 
    				  	      			    				  	  
    				  	  $htm.="<p style=\"margin:0;\"><b>".qTranslate($res_category['category_name'],
    				  	  'category_name',$category_name_trans)."</b></p>";
    				  } 
    				  
    				  //dump($htm);
    				      				     				 
			          $htm.='<div class="a">'.$val['qty'].'</div>';
			          $htm.='<div class="b">'.qTranslate(
			          isset($food_item[$val['item_id']])?$food_item[$val['item_id']]:''
			          ,'item_name',$food_infos);
			          if (!empty($size_words)){
			          	 $htm.="(".qTranslate($size_words,'size_name',$size_info_trans).")";
			          }
			          
			          //dump($val);
			          
			          // array value
			          $item_array[$key]=array(
			            'item_id'=>$val['item_id'],
			            'item_name'=>isset($food_item[$val['item_id']])?$food_item[$val['item_id']]:'',
			            'size_words'=>$size_words,
			            'size_id'=>$_size_id, // inventory
			            'qty'=>$val['qty'],
			            //'normal_price'=>prettyFormat($val['price']),
			            'normal_price'=>$val['price'],
			            'discounted_price'=>$price,
			            'discount'=>$val['discount'],
			            'order_notes'=>isset($val['notes'])?$val['notes']:'',
			            'cooking_ref'=>isset($val['cooking_ref'])?$val['cooking_ref']:'',
			            'ingredients'=>isset($val['ingredients'])?$val['ingredients']:'',
			            'non_taxable'=>isset($val['non_taxable'])?$val['non_taxable']:1,
			            'category_id'=>isset($val['category_id'])?$val['category_id']:'',
			            'category_name'=>isset($res_category['category_name_trans'])?$res_category['category_name']:'',
			            'category_name_trans'=>isset($category_name_trans)?$category_name_trans:'',			            
			            'item_name_trans'=>isset($food_infos)?$food_infos:'',
			            'size_name_trans'=>isset($size_info_trans)?$size_info_trans:'',
			            'cooking_name_trans'=>isset($cooking_ref_trans)?$cooking_ref_trans:'',			            			            			            
			          );			          			         
			          
			          $htm.=Widgets::displaySpicyIconByID($val['item_id']);
			          
			          if (!empty($val['discount'])){
			          	  $htm.="<p class=\"uk-text-small\">".
			          	  "<span class=\"normal-price\">".displayPrice(baseCurrency(),prettyFormat($val['price']))." </span>".
			          	  "<span class=\"sale-price\">".displayPrice(baseCurrency(),prettyFormat($price))."</span>"
			          	  ."</p>";		
			          	  	          	  
			          } else {
			          	
			          	$htm.="<p class=\"uk-text-small\">".			          	  
			          	  "<span class=\"base-price\">".displayPrice(baseCurrency(),prettyFormat($val['price']))."</span>"
			          	  ."</p>";			          	  
			          }
			          
			          if (!empty($val['cooking_ref'])){
			              $htm.="<p class=\"small\">".
			              qTranslate($val['cooking_ref'],'cooking_name',$cooking_ref_trans)."</p>";
			          }	    			
			          if (!empty($val['notes'])){
			              $htm.="<p class=\"small text-info\">".$val['notes']."</p>";
			          }				          
			          			          
			          /*ingredients*/  			          
			          if (isset($val['ingredients'])){
			          	if (!empty($val['ingredients'])){
			          		if (is_array($val['ingredients']) && count($val['ingredients'])>=1){
			          			$htm.="<p class=\"small ingredients-label\">".t("Ingredients").":</p>";
			          		    foreach ($val['ingredients'] as $val_ingred) {		
			          		    				          		       
			          		       if($details_ingredients=FunctionsV3::getIngredientsByName($val_ingred,$mid)){
			          		       	  $_ingredients['ingredients_name_trans']=json_decode($details_ingredients['ingredients_name_trans'],true);			          		       	  
			          		       	  $htm.="<p class=\"small\">".
			          		       	  qTranslate($val_ingred,'ingredients_name',$_ingredients)
			          		       	  ."</p>";
			          		       } else $htm.="<p class=\"small\">".$val_ingred."</p>";
			          		    }	
			          		}
			          	}	
			          }
			          
			          $htm.='</div>';
			          
			          $htm.='<div class="manage">';
			            $htm.='<div class="c">';
			            
			             if ( $receipt==false):
			             $htm.='<a href="javascript:;" class="edit_item" data-row="'.$key.'" rel="'.$val['item_id'].'" >
			                        <i class="ion-compose"></i>
			                     </a>';
			              $htm.='<a href="javascript:;" class="delete_item" data-row="'.$key.'" rel="'.$val['item_id'].'" >
			                       <i class="ion-trash-a"></i>
			                    </a>';
			             endif;
			             
			             
			            $htm.='</div>';
			             $htm.='<div class="d">'.displayPrice(baseCurrency(),prettyFormat($total_price,$mid)).'</div>';
			          $htm.='</div>';
			          $htm.='<div class="clear"></div>';
			          
			          /*SUB ITEM*/
			          //dump($val);			          
			          //$item_array[$key]['sub_item']=$val['sub_item'];
			          $val['sub_item']=isset($val['sub_item'])?$val['sub_item']:'';
			          //dump($val['sub_item']);
			          //$val['sub_item']='';
			          
			          if (is_array($val['sub_item']) && count($val['sub_item'])>=1){
			          	 foreach ($val['sub_item'] as $cat_id=>$val_sub) {				          	 	
			          	 	
			          	 	if (array_key_exists($cat_id,(array)$subcat_list)){		
			          	 		
			          	 		//** Translation */
			          	 		$subcategory_trans=array();
			          	 		if ($this->getOptionAdmin("enabled_multiple_translation")==2){
			          	 			if($subcategory_tran=$this->getAddonCategory($cat_id)){
			          	 				$subcategory_trans['subcategory_name_trans']=!empty($subcategory_tran['subcategory_name_trans'])?json_decode($subcategory_tran['subcategory_name_trans'],true):'';
			          	 			}			          	 					          	 			
			          	 		}
			          	 	
			          	 		$htm.='<div class="a"></div>';
						        $htm.='<div class="b uk-text-success">'.
						        qTranslate($subcat_list[$cat_id],'subcategory_name',
						        $subcategory_trans).'</div>';  
						        $htm.='<div class="clear"></div>';
			          	 	}
			          	 				          	 				          	 				          	 	
			          	 	$addon_qty=1;				          	 	
			          	 	foreach ($val_sub as $addon_row=> $val_subs) {
			          	 					          	 		
			          	 		if (isset( $val['addon_qty'][$cat_id] )){				          	 		    
			          	 		    $addon_qty=$val['addon_qty'][$cat_id][$addon_row];
			          	 	    } else {
			          	 	    	 $addon_qty=$qty; /** fixed addon qty */
			          	 	    	 /*if ( in_array($val['item_id'],(array)$added_item)){
			          	 	    	    $addon_qty=0;
			          	 	    	 }*/
			          	 	    }			          	 	
			          	 	    			          	 		
			          	 		$val_subs=explodeData($val_subs);
			          	 		
			          	 		//dump($val_subs);
			          	 		$addon_raw_price=prettyFormat($val_subs[1]);
			          	 		$addon_item_price= unPrettyPrice($val_subs[1]);
			          	 		$addon_item_price=(integer)$addon_qty*$addon_item_price;
			          	 		
			          	 		/** two flavor */
			          	 		if (!isset($val['two_flavors'])){
			          	 			$val['two_flavors']='';
			          	 		}
			          	 		if ( $val['two_flavors']==2){
			          	 			if ( $val_subs[3]==""){
			          	 			   $subtotal+=$addon_item_price; 	
			          	 			   if ($food_taxable==false){
			          	 			   	   $subtotal_non+=$addon_item_price;
			          	 			   }
			          	 			}
			          	 		} else {		
			          	 			 /** check if item is taxable*/
			          	 			 $subtotal+=$addon_item_price;
			          	 			 if ($food_taxable==false){
			          	 			 	 $subtotal_non+=$addon_item_price;
			          	 			 }
			          	 		}
			          	 		
			          	 		$item_array[$key]['sub_item'][]=array(
			          	 		  'addon_name'=>$val_subs[2],
			          	 		  'addon_category'=>$subcat_list[$cat_id],
			          	 		  'addon_qty'=>$addon_qty,
			          	 		  //'addon_price'=>$addon_item_price
			          	 		  'addon_price'=>unPrettyPrice($val_subs[1]),
			          	 		  'sub_item_id'=>unPrettyPrice($val_subs[0]), // inventory new code,
			          	 		  'subcat_id'=>$cat_id // inventory new code,
			          	 		);
			          	 					          	 		
			          	 		/**translation */
			          	 		$addon_name_trans='';
			          	 		if ($this->getOptionAdmin("enabled_multiple_translation")==2){
			          	 			$addon_name_trans=$this->getAddonTranslation($val_subs[2],$mid);
			          	 		}
			          	 					          	 			          	 					          	 		
			          	 		$htm.='<div class="a">'.$addon_qty.'x</div>';
$htm.='<div class="b uk-text-muted">'."$addon_raw_price ".qTranslate($val_subs[2],
'sub_item_name',$addon_name_trans).'</div>';
						          $htm.='<div class="manage">';							             
						             if ( $addon_item_price!=0){
						             $htm.='<div class="d">'.displayPrice(baseCurrency(),prettyFormat($addon_item_price)).'</div>';
						             } else $htm.='<div class="d">-</div>';
						          $htm.='</div>';
						        $htm.='<div class="clear"></div>';
						        
						        /*changes for driver app*/
			          	 		$item_array[$key]['new_sub_item'][$subcat_list[$cat_id]][]=array(
			          	 		  'addon_name'=>$val_subs[2],
			          	 		  'addon_category'=>$subcat_list[$cat_id],
			          	 		  'addon_qty'=>$addon_qty,			          	 		  
			          	 		  'addon_price'=>unPrettyPrice($val_subs[1]),
			          	 		  'subcategory_name_trans'=>isset($subcategory_trans)?$subcategory_trans:'',
			          	 		  'sub_item_name_trans'=>isset($addon_name_trans)?$addon_name_trans:'',
			          	 		);
			          	 	}
			          	 }
			          }	    			
			          
			          $htm.='</div>';
			          				          
			          $x++;
			          $added_item[]=$val['item_id'];  /** fixed addon qty */
			          
    			} /*END LOOP FOOD ITEM*/	  
    			
    			    			
    			$taxable_subtotal=0;	    			
    			$tax_amt=0;
    			
    			$tax=Yii::app()->functions->getOption('merchant_tax',$mid);
    			    			
    			/*FIXED TAX BASED ON DATA WHEN VIEWING RECEIPT*/
    			if($receipt){    				    				    			
    				if(isset($data['tax_set'])){
    				   if($data['tax_set']>0.0001){    				   	
    				       $tax = 	$data['tax_set']*100;
    				   } else $tax = $data['tax_set'];
    				}
    			}
    			    			
    			/*if transaction is pickup*/
    			/*if ($data['delivery_type']=="pickup"){
    				$tax=0;
    			}*/
    			
    			$tax_amt=$tax;
    			$delivery_charges=Yii::app()->functions->getOption('merchant_delivery_charges',$mid);
    			$default_fee = $delivery_charges;
    			
    			/*dump($delivery_charges);
    			dump($_SESSION['shipping_fee']);
    			dump($subtotal);*/
    			
    			//shipping rates
    			if (isset($_SESSION['shipping_fee'])){
    				if (is_numeric($_SESSION['shipping_fee'])){
    					if($_SESSION['shipping_fee']>0.0001){
    					   $delivery_charges=$_SESSION['shipping_fee'];
    					}
    				}
    			}

    			
    			if (!isset($data['delivery_charge'])){
	    			if(FunctionsV3::isClassDeliveryTableExist()){   			
	    				if(isset($_SESSION['distance_info'])){
	    			        if(is_array($_SESSION['distance_info']) && count($_SESSION['distance_info'])>=1){
	    			           $delivery_charges = DeliveryTableRate::getDeliveryFee(
	    			           $mid,$subtotal,$default_fee,
	    			           $_SESSION['distance_info']['distance'],$_SESSION['distance_info']['unit']);
	    			        }
	    				}
	    			}
    			}
    			    		
    			
    			if (isset($data['delivery_charge'])){
    				$delivery_charges=$data['delivery_charge'];
    			}    		    	
    			
    			/*dump("final fee=>".$delivery_charges);
    			die();*/
    		    
    			/*EXTRA DELIVERY COST*/
    			if (!$receipt){    				    			
	    			if ($data['delivery_type']=="delivery"){	    				
	    				$delivery_time = isset($_SESSION['kr_delivery_options']['delivery_time'])?$_SESSION['kr_delivery_options']['delivery_time']:'';  
	    				
	    				$delivery_date = isset($_SESSION['kr_delivery_options']['delivery_date'])?$_SESSION['kr_delivery_options']['delivery_date']:'';  
	    					    				
		    			if (FunctionsV3::enabledExtraCharges()){ 
		    				$delivery_charges = FunctionsV3::extraDeliveryFee(
		    				$mid,$delivery_charges,
		    				$delivery_time,
		    				$delivery_date );
		    			}
	    			}    	
    			}		
    			/*EXTRA DELIVERY COST*/
    			
    			//end shipping rates
    			
    			
    			/*PACKAGING*/

    			if($merchant_packaging_wise==1){     				
    				$merchant_packaging_charge = $wise_packaging_fee;
    			} else {
	    			$merchant_packaging_charge=Yii::app()->functions->getOption('merchant_packaging_charge',$mid); 
			        $merchant_packaging_increment = getOption($mid,'merchant_packaging_increment');
			        if($merchant_packaging_increment==2){
			        	if (!isset($data['packaging'])){
			        		$total_cart_item=0;		        		
			        		foreach ($cart_item as $cart_item_x) {		        			
			        			$total_cart_item+=$cart_item_x['qty'];
			        		}		                
			                $merchant_packaging_charge=$total_cart_item*$merchant_packaging_charge;
			        	}
			        }			
    			}
    			
    			//fixed packaging charge
    			if (isset($data['packaging'])){
    				$merchant_packaging_charge=$data['packaging'];
    			}    		
    			
                if (!empty($delivery_charges)){
                	$delivery_charges=unPrettyPrice($delivery_charges);
                } else $delivery_charges=0;        
                
                /*if transaction is pickup*/
                //dump($data);
    			if ($data['delivery_type']=="pickup" || $data['delivery_type']=="dinein"){
    				$delivery_charges=0;
    			}           
    			
    			if ( $data['delivery_type']=="dinein"){    				
    				$merchant_packaging_charge=0;
    			}
    			    			
                /*VOUCHER*/                                                
    			$has_voucher=false;
    			$less_voucher=0; 
    			$voucher_type="";   	

    			$has_voucher_orig=false;
    			$less_voucher_orig=0; 
    			
    			$pts_redeem_amt_orig=0;
    			$pts_redeem_amt_found=false;
    			$pts_redeem_amt=0;
    			
    			$order_infos=array();
    			   			
		         if (isset($_SESSION['voucher_code'])){		         	
		         	if (is_array($_SESSION['voucher_code'])){		         		
		         		$has_voucher=true;
		         		//dump($_SESSION['voucher_code']);
		         		$_SESSION['voucher_code']['amount']=unPrettyPrice($_SESSION['voucher_code']['amount']);		         		
		         		if ( $_SESSION['voucher_code']['voucher_type']=="fixed amount" ){
		         			$less_voucher=$_SESSION['voucher_code']['amount'];
		         		} else {
		         			$less_voucher=$subtotal*($_SESSION['voucher_code']['amount']/100);
		         			$voucher_type=normalPrettyPrice($_SESSION['voucher_code']['amount'])."%";
		         		}		     
		         		$_SESSION['less_voucher']=$less_voucher;
		         	}		         
		        }    		  
		        
		        /*APPMERCHANT CHANGES*/		        
		        if(!empty($data['voucher_name'])){
		        	$has_voucher=true;		        	
		        	/*$_SESSION['voucher_code']['voucher_type'] = $data['voucher_type'];
		        	$_SESSION['voucher_code']['amount'] = $data['voucher_amount'];*/
		        	
		        	if ( $data['voucher_type']=="fixed amount" ){
		        		$less_voucher = unPrettyPrice($data['voucher_amount']);
		        	} else {
		        		$less_voucher=$subtotal*($data['voucher_amount']/100);
		        		$voucher_type=normalPrettyPrice($data['voucher_amount'])."%";
		        	}
		        }
		              
		        if ( $receipt==TRUE){		        	
		        	$order_ids=isset($data['order_id'])?$data['order_id']:'';
		        	if (isset($_GET['id'])){
		        		$order_ids=$_GET['id'];
		        	}		        		        	
		        	$order_infos=$this->getOrderInfo($order_ids);
		        	//dump($order_infos);
		        	if (!empty($order_infos['voucher_code'])){
		        		$has_voucher=true;
		        		if ($order_infos['voucher_type']=="fixed amount"){
		        			$less_voucher=$order_infos['voucher_amount'];
		        		} else {
		        			$voucher_type=normalPrettyPrice((integer)$order_infos['voucher_amount'])."%";
		        			$less_voucher=$subtotal*($order_infos['voucher_amount']/100);
		        		}		        	
		        	} else $has_voucher=false;	        
		        }    		
		        
		        
		        /*GET CALCULATION METHOD*/		        
		        $calculation_method='';
		        if($receipt==TRUE){		       	
				  $calculation_method = $order_infos['calculation_method'];
		        } else {
		       	  $calculation_method = FunctionsV3::getReceiptCalculationMethod();
		        }		        
		        
		        /*VOUCHER*/
		        if ( $receipt==TRUE){		        	
		        	//if ($order_infos['calculation_method']==2){
		        	if($calculation_method==2){
		        		$has_voucher_orig = $has_voucher;
			        	$less_voucher_orig = $less_voucher;
				        $has_voucher=false; 
				        $less_voucher=0; 
		        	}
		        } else {
			        //if(FunctionsV3::isReceiptCalculationMethodTwo()){
			        if($calculation_method==2){
			        	$has_voucher_orig = $has_voucher;
			        	$less_voucher_orig = $less_voucher;
				        $has_voucher=false; 
				        $less_voucher=0; 
			        }
		        }

		        if ($less_voucher==TRUE){
		        	$subtotal=$subtotal-$less_voucher;
		        	
		        	/** check if item is taxable*/
		        	if ($food_taxable==false){
		        	    $subtotal_non=$subtotal_non-$less_voucher;
		        	}
		        }    	
		        		        
		        /*PROMO STARTS HERE*/
		        $show_discount=false;
		        $discounted_amount=0;
		        $merchant_discount_amount=0;
		        
		        $discounted_amount_orig=0;
		        $has_discount = false;
		        
		        
		        if(isset($_SESSION['promo_discount'])){
		           unset($_SESSION['promo_discount']);
		        }
		        
		        if ($receipt==TRUE){
		        	$_GET['id']=isset($_GET['id'])?$_GET['id']:$new_order_id;
		        	if ( $promo_res=$this->getOrderDiscount($_GET['id'])){		        		
		        		if ( $promo_res['discounted_amount']>=0.0001){
		        			
		        			//if ($order_infos['calculation_method']==2){
		        			if($calculation_method==2){
		        				$merchant_discount_amount=number_format($promo_res['discount_percentage'],0);
				        		$discounted_amount=$promo_res['discounted_amount'];
				        		$has_discount=true;
		        			} else {
				        		$show_discount=true;
				        		$merchant_discount_amount=number_format($promo_res['discount_percentage'],0);
				        		$discounted_amount=$promo_res['discounted_amount'];
				        		$subtotal=$subtotal-$discounted_amount;
				        		
				        		/** check if item is taxable*/
				        		if ($food_taxable==false){
				        		    //$subtotal_non=$subtotal_non-$discounted_amount;
				        		}
		        			}
		        		}
		        	}		        	
		        } else {
			        //if ( $promo_res=Yii::app()->functions->getMerchantOffersActive($mid)){
			        if ( $promo_res=FunctionsV3::getMerchantOffersActive($mid,$data['delivery_type'])){
			        	//dump($promo_res);
			        	$offer_continue=true;
			        	
			        	if(isset($promo_res['applicable_to'])){
			        	  if (!empty($promo_res['applicable_to'])){
			        	  	  if ($promo_res['applicable_to']!="all"){
			        	  	     $applicable_to = json_decode($promo_res['applicable_to'],true);			        	  	     
			        	  	     if(!in_array($data['delivery_type'],(array)$applicable_to)){
			        	  	     	$offer_continue=false;
			        	  	     }
			        	  	  }
			        	  }	
			        	}
			        			        	
			        	$merchant_spend_amount=$promo_res['offer_price'];
			        	$merchant_discount_amount=number_format($promo_res['offer_percentage'],0);
			        	
			        	if($offer_continue){
				        	if ( $subtotal>=$merchant_spend_amount){
				        				
				        	   //if(FunctionsV3::isReceiptCalculationMethodTwo()){
				        	   if($calculation_method==2){
				        	   	  $merchant_discount_amount1=$merchant_discount_amount/100;
					        	  $discounted_amount=$subtotal*$merchant_discount_amount1;
					        	  $has_discount=true;
				        	   } else {		        	   
					        	   $show_discount=true;
					        	   $merchant_discount_amount1=$merchant_discount_amount/100;
					        	   $discounted_amount=$subtotal*$merchant_discount_amount1;		        	
					        	   $subtotal=$subtotal-$discounted_amount;
					        	   
					        	   $_SESSION['promo_discount']=1;
					        	   
					        	   /** check if item is taxable*/
					        	   if ($food_taxable==false){
					        		   //$subtotal_non=$subtotal_non-$discounted_amount;
					        	   }
				        	   }
				        	   
				        	} 
			        	}
			        	
			        }    	
		        }	
		        
		        
		        /*dump($discounted_amount);
		        dump($merchant_discount_amount);*/
		        
		        /**above sub total free delivery*/		
		        $free_delivery=false;        
		        if ( $data['delivery_type']=="delivery"){
		        	
		        	if(!isset($_GET['backend'])){
			        	$free_delivery_above_price=Yii::app()->functions->getOption("free_delivery_above_price",$mid);
			        	if (!empty($free_delivery_above_price)){
				        	if ($subtotal>=$free_delivery_above_price){
				        	   	$delivery_charges=0;
				        	   	$free_delivery=true;
				        	}
			        	}
		        	}		        	
		        }
		        /**above sub total free delivery*/
		        
		        /** packaging incremental*/
		        /*$merchant_packaging_increment = getOption($mid,'merchant_packaging_increment');
		        if($merchant_packaging_increment==2){
		        	if (!isset($data['packaging'])){
		        		$total_cart_item=0;		        		
		        		foreach ($cart_item as $cart_item_x) {		        			
		        			$total_cart_item+=$cart_item_x['qty'];
		        		}		                
		                $merchant_packaging_charge=$total_cart_item*$merchant_packaging_charge;
		        	}
		        }*/
		        
		        /*POINTS PROGRAM*/
		        if (FunctionsV3::hasModuleAddon("pointsprogram")){		
		        	      	
				    if (isset($_SESSION['pts_redeem_amt']) && $_SESSION['pts_redeem_amt']>0.01){
				    	$pts_redeem_amt=unPrettyPrice($_SESSION['pts_redeem_amt']);				    					    	
				    	if($calculation_method==2){
				    	   $subtotal=unPrettyPrice($subtotal);
				    	   $pts_redeem_amt_found=true;
				    	} else $subtotal=unPrettyPrice($subtotal)-$pts_redeem_amt;
				    } elseif ( isset($data['points_amount'])){
				    	
				    	$pts_redeem_amt=unPrettyPrice($data['points_amount']);  	
				    	if($calculation_method==2){
				    	   $subtotal=unPrettyPrice($subtotal);
				    	   $pts_redeem_amt_found=true;
				    	} else $subtotal=unPrettyPrice($subtotal)-$pts_redeem_amt;
				    	
				    } else {
					    if ($receipt==TRUE){			    	
					    	if (isset($data['points_discount']) && $data['points_discount']>0){
					    	   $pts_redeem_amt=unPrettyPrice($data['points_discount']);
					    	   //if ($order_infos['calculation_method']==2){
					    	   if($calculation_method==2){
					    	   	  $subtotal=unPrettyPrice($subtotal);
					    	   	  $pts_redeem_amt_found=true;
					    	   } else $subtotal=unPrettyPrice($subtotal)-$pts_redeem_amt;					    	   
					        }	    	
					    }    	
				    }
		        }
		        		        
    		    if ( !empty($tax)){
    				$tax=$tax/100;
    				
    				/** check if item is taxable*/    
    				$temp_delivery_charges=$delivery_charges;   	    				
    				if (Yii::app()->functions->getOption("merchant_tax_charges",$mid)==2){
    					$temp_delivery_charges=0;    					
    				}  
    				  				
    				if ($receipt==true){    					
    					if (isset($data['donot_apply_tax_delivery'])){
    						if ($data['donot_apply_tax_delivery']==2){
    						   	$temp_delivery_charges=0;    						   	
    						} else $temp_delivery_charges=$delivery_charges;
    					}    					
    				}

    				if (!isset($subtotal_non)){
    					$subtotal_non=0;
    				}  		   
    				
    				
    				//$htm.="=>$subtotal - $subtotal_non <br/>";
    				
    				/*FIXED TAX ISSUE*/
    				if ($subtotal_non>=0.0001){    					
    					$temp_subtotal=$subtotal-$subtotal_non;          					
    					if($temp_subtotal>0){
    					   $taxable_subtotal=($temp_subtotal+$temp_delivery_charges+$merchant_packaging_charge)*$tax;
    					} else {    				
    					   if($merchant_packaging_charge>0 && $merchant_packaging_charge>0){
    					      $taxable_subtotal=($temp_delivery_charges+$merchant_packaging_charge)*$tax;    					   
    					   }
    					}
    				} else {    					
    					$taxable_subtotal=( (float)$subtotal+ (float)$temp_delivery_charges + (float)$merchant_packaging_charge)*$tax;    
    				}
    				
    				
    				/*dump($temp_delivery_charges."=>".$merchant_packaging_charge);
    				dump($subtotal);
    				dump($subtotal_non);
    				dump("taxable_subtotal=>".$taxable_subtotal);*/
    			}	    	
    			
    			
    			//$htm.="TAXABLE=>$taxable_subtotal <br/>";
    			
    				            			
		        $total=(float)$subtotal + (float)$taxable_subtotal + (float)$delivery_charges + (float)$merchant_packaging_charge;
		        
		        /*VOUCHER*/
    			    			
		        $htm.='<div class="summary-wrap">';
		        
		         if ( $has_voucher==TRUE){		 

		         	 if ( $show_discount==true):		         	 			        
			           $htm.=FunctionsV3::receiptRowTotal( t("Discount")." $merchant_discount_amount%" ,
			           displayPrice(baseCurrency(),prettyFormat($discounted_amount,$mid)));
			         endif;
			         
			         /*$htm.=FunctionsV3::receiptRowTotal("Sub Total",
			         displayPrice(baseCurrency(),prettyFormat($subtotal+$less_voucher,$mid)),'','cart_subtotal'
			         );*/
		          				         
			         if ($receipt==TRUE){
			         	$voucher_code=" - ".$order_infos['voucher_code']."";
			         } else $voucher_code='';
			         			         
			         $htm.=FunctionsV3::receiptRowTotal( Yii::t("default","Less Voucher")." ".$voucher_type ,
			         "(".displayPrice(baseCurrency(),prettyFormat($less_voucher,$mid).")" )
			         );	         
			         
			         if (FunctionsV3::hasModuleAddon("pointsprogram")){ 			         	 
			         	 $pts_redeem_amt_orig = $pts_redeem_amt;
			         	 if($receipt==TRUE){				         	
				         	if($calculation_method==2){
				         		$pts_redeem_amt=0;
				         	}
				         } else {				         	
				         	if($calculation_method==2){
				         		$pts_redeem_amt=0;
				         	}
				         }
				         if($pts_redeem_amt>0){
					         $htm.=FunctionsV3::receiptRowTotal('Points Discount',
					         "(".PointsProgram::price($pts_redeem_amt).")" );	
				         } 	
			         }
			         			         
			         $htm.=FunctionsV3::receiptRowTotal("Sub Total",
			         displayPrice(baseCurrency(),prettyFormat($subtotal,$mid))
			         );
			         		        
		         } else {			         
		         	
			         if ( $show_discount==true):			         
			         $htm.=FunctionsV3::receiptRowTotal( t("Discount")." $merchant_discount_amount%",
			           "(".displayPrice(baseCurrency(),prettyFormat($discounted_amount,$mid)).")"
			          );
			         endif;
		         			         
			         /*POINTS PROGRAM*/			
			         if (FunctionsV3::hasModuleAddon("pointsprogram")){      
				         /*$pts_redeem_amt=0;
				         if (isset($_SESSION['pts_redeem_amt']) && $_SESSION['pts_redeem_amt']>0.01){			         
				         	$pts_redeem_amt=$_SESSION['pts_redeem_amt'];
				         } else {
				         	if ($receipt==TRUE){
				         		if (isset($data['points_discount']) && $data['points_discount']>0.01){
				         		   	$pts_redeem_amt=$data['points_discount'];
				         		}
				         	}
				         }*/			  
				         
				         $pts_redeem_amt_orig = $pts_redeem_amt;
				         
				         if($receipt==TRUE){
				         	//if ($order_infos['calculation_method']==2){
				         	if($calculation_method==2){
				         		$pts_redeem_amt=0;
				         	}
				         } else {
				         	//if(FunctionsV3::isReceiptCalculationMethodTwo()){
				         	if($calculation_method==2){
				         		$pts_redeem_amt=0;
				         	}
				         }
				                
				         if($pts_redeem_amt>0){
					         $htm.=FunctionsV3::receiptRowTotal('Points Discount',
					         "(".PointsProgram::price($pts_redeem_amt).")" );	
				         } 	
			         }
			         			         
			        $htm.=FunctionsV3::receiptRowTotal('Sub Total',
			        displayPrice(baseCurrency(),prettyFormat($subtotal,$mid)),'','cart_subtotal'
			        );
			        
		         }   		
		         
		         		         
		         if (!empty($delivery_charges)){
		            $htm.=FunctionsV3::receiptRowTotal('Delivery Fee',
		            displayPrice(baseCurrency(),prettyFormat($delivery_charges,$mid)));
		         }		         
		         
		         if ( $free_delivery==true){			         
		             $htm.=FunctionsV3::receiptRowTotal("Delivery Fee", t("Free") );
		         }
		         
		         if (!empty($merchant_packaging_charge)){		
		           if($merchant_packaging_charge>=0.001){         
			           $htm.=FunctionsV3::receiptRowTotal("Packaging",
			             displayPrice(baseCurrency(),prettyFormat($merchant_packaging_charge,$mid))
			           );
		           }
		         }
		         			         			         
		         if ( !empty($tax)){				 
		         	 if($taxable_subtotal>0.0001){    
				         $htm.=FunctionsV3::receiptRowTotal( t("Tax")." $tax_amt%" ,
				         displayPrice(baseCurrency(),prettyFormat($taxable_subtotal,$mid))
				         );
		         	 }
		         }

		         /*TIPS*/
		         if (isset($data['cart_tip_percentage'])){
		         	if(strlen($data['cart_tip_percentage'])>=0.0001){
			         	$data['tip_enabled']=2;
			         	$data['tip_percent']=$data['cart_tip_percentage'];
		         	}
		         }
		         		         
		         if (isset($data['tip_enabled'])){		         	
		         	if ($data['tip_enabled']==2 && $data['tip_percent']>=0.0001){
		         		$data['cart_tip_value'] =  $subtotal * $data['tip_percent'];
		         		$data['cart_tip_percentage']=$data['tip_percent']*100;
		         		
		         		$t_cart_tip_percentage = explode(".",$data['cart_tip_percentage']);		         				         
		         		$tip_decimal_places = 0;
		         		if(count($t_cart_tip_percentage)>=2){
		         			$tip_decimal_places = 2;
		         		}
		         		
		         		$htm.=FunctionsV3::receiptRowTotal( t("Tips")." ".number_format($data['cart_tip_percentage'],$tip_decimal_places)."%" ,
			            displayPrice(baseCurrency(),prettyFormat($data['cart_tip_value'],$mid))
			            );			       
			            
			            			            
			            $data['cart_tip_percentage']  = number_format($data['cart_tip_percentage'],$tip_decimal_places,'.','');			            
			                 
			            $total+=$data['cart_tip_value'];		         		
		         	}
		         }
		        		         
		         if (isset($data['card_fee'])){
		         	if ( $data['card_fee'] >=0.0001) {
			            $htm.=FunctionsV3::receiptRowTotal("Card Fee",
			            displayPrice(baseCurrency(),prettyFormat($data['card_fee'],$mid))
			            );			            
			            $total+=$data['card_fee'];
		         	}
		         }    		
		         
		       
		       /*VOUCHER*/
		       if($has_voucher_orig){
		         $htm.=FunctionsV3::receiptRowTotal(t("Voucher"), "(".FunctionsV3::prettyPrice($less_voucher_orig).")" );  
		         $total=unPrettyPrice($total)-unPrettyPrice($less_voucher_orig);
		       }
		       
		       
		       /*POINTS PROGRAM*/
		       if (FunctionsV3::hasModuleAddon("pointsprogram")){			       	  
		           if($pts_redeem_amt_found){
		           	  $htm.=FunctionsV3::receiptRowTotal(t("Points Discount"), "(".FunctionsV3::prettyPrice($pts_redeem_amt_orig).")" );  
		           	  $total=unPrettyPrice($total)-unPrettyPrice($pts_redeem_amt_orig);
		           	  //$pts_redeem_amt=$pts_redeem_amt_orig;
		           }
		       }
		       
		       /*DISCOUNT*/
		       if($has_discount){
		       	  $htm.=FunctionsV3::receiptRowTotal( t("Discount")." $merchant_discount_amount%",
			           "(".displayPrice(baseCurrency(),prettyFormat($discounted_amount,$mid)).")"
			          );
		          $total=unPrettyPrice($total)-unPrettyPrice($discounted_amount);
		       }
		         		       		       
		       $htm.="<div class=\"row cart_total_wrap bold\">";
	    	   $htm.="<div class=\"col-md-6 col-xs-6  text-right\">".t("Total")."</div>";
	    	   $htm.="<div class=\"col-md-6 col-xs-6  text-right cart_total\">".
	    	   displayPrice(baseCurrency(),prettyFormat($total,$mid))."</div>";
	    	   $htm.="</div>";

		       /*POINTS PROGRAM*/
		       if (FunctionsV3::hasModuleAddon("pointsprogram")){			       	 		       
		          $htm.=PointsProgram::cartTotalEarnPoints($cart_item,$receipt,$subtotal,$mid);
		       }
		       
		       if(!isset($pts_redeem_amt)){
		       	  $pts_redeem_amt=0;
		       }
		       $htm.=CHtml::hiddenField("subtotal_order",unPrettyPrice($subtotal+$less_voucher+$pts_redeem_amt));
		       
		       $htm.=CHtml::hiddenField("subtotal_order2",unPrettyPrice($subtotal));
		       $htm.=CHtml::hiddenField("subtotal_extra_charge",
		       unPrettyPrice( (float)$delivery_charges + (float)$merchant_packaging_charge + (float)$taxable_subtotal));
		       
		       
		       if(!isset($data['cart_tip_percentage'])){
		       	   $data['cart_tip_percentage']='';
		       }
		       
		       $tips_decimal_places = getOptionA('admin_decimal_place');		       
		       if(empty($tips_decimal_places)){		       	
		       	  $tips_decimal_places=2;
		       }		       
		       
		       //dump($data['cart_tip_percentage']);
		       // array value
		       
		       if(!isset($data['cart_tip_percentage'])){
		       	   $data['cart_tip_percentage']='';
		       }
		       
		       
		       $item_array_total=array(
		         'subtotal'=>$subtotal,
		         'taxable_total'=>$taxable_subtotal,
		         'delivery_charges'=>$delivery_charges,
		         'total'=>$total,		         
		         'tax'=>$tax,
		         'tax_amt'=>$tax_amt,
		         'curr'=>baseCurrency(),
		         'mid'=>$mid,
		         'discounted_amount'=>$discounted_amount,
		         'merchant_discount_amount'=>$merchant_discount_amount,
		         'merchant_packaging_charge'=>$merchant_packaging_charge,
		         'less_voucher'=>$less_voucher,
		         'voucher_type'=>$voucher_type,
		         /*'tips'=>isset($data['cart_tip_value'])?$data['cart_tip_value']:'',
		         'tips_percent'=>$data['cart_tip_percentage']>0.1?number_format($data['cart_tip_percentage'],0)."%":'',
		         'cart_tip_percentage'=>$data['cart_tip_percentage']>0.1?number_format($data['cart_tip_percentage']):'',*/
		         'tips'=>$data['cart_tip_percentage']>0.1?number_format($data['cart_tip_value'],$tips_decimal_places):'',		         
		         'tips_percent'=>$data['cart_tip_percentage']>0.1?$data['cart_tip_percentage']."%":'',		         
		         'cart_tip_percentage'=>$data['cart_tip_percentage']>0.1?($data['cart_tip_percentage']):'',
		         'pts_redeem_amt'=>isset($pts_redeem_amt)?$pts_redeem_amt:'',
		         'voucher_value'=>isset($_SESSION['voucher_code']['amount'])?$_SESSION['voucher_code']['amount']:'',
		         'voucher_types'=>isset($_SESSION['voucher_code']['voucher_type'])?$_SESSION['voucher_code']['voucher_type']:'',
		         
		         'calculation_method'=>$calculation_method,
		         'pts_redeem_amt_orig'=>$pts_redeem_amt_orig,
		         'less_voucher_orig'=>$less_voucher_orig,		         
		       );		
		       		       
		       
		       //dump($item_array_total);
		       
		       if ($receipt){		       	  
		       	  $item_array_total['voucher_value']=isset($data['voucher_amount'])?$data['voucher_amount']:0;
		       	  $item_array_total['voucher_types']=isset($data['voucher_type'])?$data['voucher_type']:'';
		       }
		       
		       if (isset($data['card_fee'])){
		       	   if ( $data['card_fee'] >0) {
		       	   	   $item_array_total['card_fee']=$data['card_fee'];
		       	   }
		       }
		       
		       //dump($data);
		       /*header('Content-Type: text/html; charset=utf-8');
		       echo $htm;*/
    			  			
    			$this->code=1;
    			$this->msg="OK";
    			$this->details=array(
    			  'item-count'=>$x,
    			  'html'=>$htm,
    			  'raw'=>array('item'=>$item_array,'total'=>$item_array_total)
    			);
    		} else $this->msg=Yii::t("default","No Item added yet!");
	    } else $this->msg=Yii::t("default","No Item added yet!");
	}
	
	public function isClientExist($email_address='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{client}}
		WHERE
		email_address= ".FunctionsV3::q($email_address)."
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
	public function clientAutoLogin($user='',$pass='',$md5_pass='')
    {    	
    	$DbExt=new DbExt;
    	if (!empty($md5_pass)){
    		$stmt="SELECT * FROM
	    	{{client}}
	    	WHERE
	    	email_address=".Yii::app()->db->quoteValue($user)."
	    	AND
	    	password=".Yii::app()->db->quoteValue($md5_pass)."
	    	AND
	    	status IN ('active')
	    	LIMIT 0,1
	    	";
    	} else {       	
	    	$stmt="SELECT * FROM
	    	{{client}}
	    	WHERE
	    	email_address=".Yii::app()->db->quoteValue($user)."
	    	AND
	    	password=".Yii::app()->db->quoteValue(md5($pass))."
	    	AND
	    	status IN ('active')
	    	LIMIT 0,1
	    	";
    	}    	
    	//dump($stmt);
    	if ( $res=$DbExt->rst($stmt)) {	    		
    		//dump($res);
    		unset($res[0]['password']);
    		$client_id=$res[0]['client_id'];
    		$update=array('last_login'=>FunctionsV3::dateNow(),'ip_address'=>$_SERVER['REMOTE_ADDR']);
    		$DbExt->updateData("{{client}}",$update,'client_id',$client_id);
    		$_SESSION['kr_client']=$res[0];
    		return true;
    	}	    
    	return false;
    }	
    
    public function isClientLogin()
    {
    	if (isset($_SESSION['kr_client'])){
    		if (array_key_exists('client_id',$_SESSION['kr_client'])){    			
    			if (is_numeric($_SESSION['kr_client']['client_id'])){
    				return true;
    			}
    		}    	
    	}
    	return false;
    }
    
    public function getClientId()
    {
    	if (isset($_SESSION['kr_client'])){
    		if (array_key_exists('client_id',$_SESSION['kr_client'])){    			
    			if (is_numeric($_SESSION['kr_client']['client_id'])){
    				return $_SESSION['kr_client']['client_id'];
    			}
    		}    	
    	}
    	return false;
    }
    
    public function getClientName()
    {
    	if (isset($_SESSION['kr_client'])){
    		if (array_key_exists('client_id',$_SESSION['kr_client'])){    			
    			if (is_numeric($_SESSION['kr_client']['client_id'])){    				
    				return $_SESSION['kr_client']['first_name'];
    			}
    		}    	
    	}
    	return false;
    }
    
    public function ccExpirationMonth()
    {
    	$data=array();
    	for ($i = 1; $i <= 12; $i++) {    		    		
    		$temp=$i;
    		if (strlen($i)==1){
    			$temp="0".$i;
    		}    		
    		$data[$temp]=$temp;
    	}
    	return $data;
    }
    
    public function ccExpirationYear()
    {
    	$data=array();
    	$yr_now=date("Y");
    	for ($i = 0; $i <= 12; $i++) {    		    		    		
    		$yr=$yr_now+$i;
    		$data[$yr]=$yr;
    	}
    	return $data;
    }
    
    public function maskCardnumber($cardnumber='')
    {
    	if ( !empty($cardnumber)){
    		return substr($cardnumber,0,4)."XXXXXXXX".substr($cardnumber,-4,4);
    	}
    	return '';
    }
    
	public function getCreditCardInfo($cc_id)
	{
		$stmt="
		SELECT * FROM
		{{client_cc}}
		WHERE
		cc_id= ".FunctionsV3::q($cc_id)."
		LIMIT 0,1
		";		
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 		
		if (is_array($rows) && count($rows)>=1){
			return $rows[0];
		}
		return FALSE;
	}	        
	
	public function getOrder($order_id='')
	{
		$stmt="
		SELECT a.*,
		(
		select concat(first_name,' ',last_name) as full_name
		from
		{{client}}
		where
		client_id=a.client_id
		) as full_name,
		
		(
		select email_address
		from
		{{client}}
		where
		client_id=a.client_id
		) as email_address,
		
		(
		select restaurant_name 	
		from
		{{merchant}}
		where
		merchant_id=a.merchant_id 	
		) as merchant_name,
		
		(
		select restaurant_slug 	
		from
		{{merchant}}
		where
		merchant_id=a.merchant_id 	
		) as restaurant_slug,
		
		(
		select concat(street,' ',city,' ',state,' ',zipcode )
		from
		{{client}}
		where
		client_id=a.client_id
		) as full_address,
		
		(
		select location_name
		from
		{{client}}
		where
		client_id=a.client_id
		) as location_name,
		
		(
		select contact_phone
		from
		{{client}}
		where
		client_id=a.client_id
		) as contact_phone,
		
		(
		select credit_card_number
		from
		{{client_cc}}
		where
		cc_id=a.cc_id 
		) as credit_card_number		
		
		 FROM
		{{order}} a
		WHERE
		order_id= ".FunctionsV3::q($order_id)."
		LIMIT 0,1
		";		
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 		
		if (is_array($rows) && count($rows)>=1){
			return $rows[0];
		}
		return FALSE;
	}	        	
	
	public function getOrder2($order_id='')
	{
		if (isset($_GET['backend'])){
			$and='';
		} else {
			$and="AND client_id=".FunctionsV3::q($this->getClientId())." ";
		}
		$stmt="
		SELECT a.*,
		(
		select concat(first_name,' ',last_name) as full_name
		from
		{{client}}
		where
		client_id=a.client_id
		limit 0,1
		) as full_name,
		
		(
		select email_address
		from
		{{client}}
		where
		client_id=a.client_id
		limit 0,1
		) as email_address,
		
		(
		select custom_field1
		from
		{{client}}
		where
		client_id=a.client_id
		limit 0,1
		) as custom_field1,
		
		(
		select custom_field2
		from
		{{client}}
		where
		client_id=a.client_id
		limit 0,1
		) as custom_field2,
		
		(
		select restaurant_name 	
		from
		{{merchant}}
		where
		merchant_id=a.merchant_id 	
		limit 0,1
		) as merchant_name,
				
		(
		select restaurant_slug 	
		from
		{{merchant}}
		where
		merchant_id=a.merchant_id 
		limit 0,1	
		) as restaurant_slug,
		
		(
		select concat(street,' ',city,' ',state,' ',zipcode )
		from
		{{client}}
		where
		client_id=a.client_id
		limit 0,1
		) as full_address,
		
		(
		select location_name
		from
		{{order_delivery_address}}
		where
		order_id=a.order_id
		limit 0,1
		) as location_name,
		
		(
		select contact_phone
		from
		{{client}}
		where
		client_id=a.client_id
		limit 0,1
		) as contact_phone,
		
		(
		select credit_card_number
		from
		{{client_cc}}
		where
		cc_id=a.cc_id 
		limit 0,1
		) as credit_card_number,

		(
		select payment_reference
		from
		{{payment_order}}
		where
		order_id=a.order_id
		order by id desc
		limit 0,1
		) as payment_reference,
		
		(
		select restaurant_phone 	
		from
		{{merchant}}
		where
		merchant_id=a.merchant_id 
		limit 0,1	
		) as merchant_contact_phone	,
		
		(
		select abn 	 	
		from
		{{merchant}}
		where
		merchant_id=a.merchant_id 
		limit 0,1	
		) as abn,
				
		(
		select concat(street,' ',area_name,' ',city,' ',state,' ',zipcode )
		from
		{{order_delivery_address}}
		where
		order_id=a.order_id
		limit 0,1
		) as client_full_address,
		
		(
		select location_name
		from
		{{order_delivery_address}}
		where
		order_id=a.order_id
		limit 0,1
		) as location_name1,
		
		(
		select contact_phone
		from
		{{order_delivery_address}}
		where
		order_id=a.order_id
		limit 0,1
		) as contact_phone1,
		
		(
		select concat(street,' ',city,' ',state,' ',post_code ) 	
		from
		{{merchant}}
		where
		merchant_id=a.merchant_id 
		limit 0,1	
		) as merchant_address,
		
		(
		select concat(street,' ',area_name,' #',location_name,' ',city,', ',state,' ',zipcode )
		from
		{{order_delivery_address}}
		where
		order_id=a.order_id
		limit 0,1
		) as client_delivery_address
		
		 FROM
		{{order}} a
		WHERE
		order_id= ".FunctionsV3::q($order_id)."
		$and
		LIMIT 0,1
		";				
		
		//dump($stmt);
		
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 				
		if (is_array($rows) && count($rows)>=1){
			return $rows[0];
		}
		return FALSE;
	}	        	
	
	public function getOrderInfo($order_id='')
	{
		$stmt="SELECT * FROM
		{{order}}
		WHERE
		order_id= ".FunctionsV3::q($order_id)."
		LIMIT 0,1
		";		
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 		
		if (is_array($rows) && count($rows)>=1){
			return $rows[0];
		}
		return FALSE;
	}
	
	public function updateClient($data='')
	{
		if ( $this->isClientLogin() ){		    		   
		    $params=array(
		      'street'=>isset($data['street'])?$data['street']:'',
		      'city'=>isset($data['city'])?$data['city']:'',
		      'state'=>isset($data['state'])?$data['state']:'',
		      'zipcode'=>isset($data['zipcode'])?$data['zipcode']:'',
		      'country_code'=>isset($data['country_code'])?$data['country_code']:'',
		      'location_name'=>isset($data['location_name'])?$data['location_name']:'',
		      'contact_phone'=>isset($data['contact_phone'])?$data['contact_phone']:''		      
		    );		    
		    $DbExt=new DbExt;
		    if ( $DbExt->updateData("{{client}}",$params,'client_id',$this->getClientId()) ){
		    	return true;
		    }
		}
		return false;
	}
	
	public function getClientInfo($client_id='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{client}}
		WHERE
		client_id= ".FunctionsV3::q($client_id)."
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
	public function formatOrderNumber($order_id='')
	{
		//return str_pad($order_id,10,"0");
		return $order_id;
	}
	
	public function getCCbyCard($card_number='',$client_id='')
	{		
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{client_cc}}
		WHERE
		client_id= ".FunctionsV3::q($client_id)."
		AND
		credit_card_number= ".FunctionsV3::q($card_number)."
		LIMIT 0,1
		";		
		if ( $res=$DbExt->rst($stmt)){			
			return $res[0];
		}
		return false;
	}
	
	public function Curl($uri="",$post="")
	{		
		 $error_no='';
		 $ch = curl_init($uri);
		 curl_setopt($ch, CURLOPT_POST, 1);		 
		 curl_setopt($ch, CURLOPT_POSTFIELDS, $post);		 
		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		 curl_setopt($ch, CURLOPT_HEADER, 0);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 $resutl=curl_exec ($ch);		
		 		 			 				 
		 if ($error_no==0) {
		 	 return $resutl;
		 } else return false;			 
		 curl_close ($ch);		 				 		 		 		 		 		
	}	
		
	public function getDistance($from='',$to='',$country_code='',$debug=false)
	{			
		$country_list=require "CountryCode.php";
		//$country_code=yii::app()->functions->getOption('country_code');		
		$country_name='';							
		if (array_key_exists((string)$country_code,(array)$country_list)){
			$country_name=$country_list[$country_code];
		} 
		if (!preg_match("/$country_name/i", $from)) {		
			$from.=" ".$country_name;
		}
		if (!preg_match("/$country_name/i", $to)) {		
			$to.=" ".$country_name;
		}		
		if ($debug){
		   dump($from);
		   dump($to);
		}
		

		$protocol = isset($_SERVER["https"]) ? 'https' : 'http';
		
		if ($protocol=="http"){			
		$url="http://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($from)."&destinations=".urlencode($to)."&language=en-EN&sensor=false&units=imperial";			
		} else {
		$url="https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($from)."&destinations=".urlencode($to)."&language=en-EN&sensor=false&units=imperial";		
		}
		
		/*check if has provide api key*/
		$key=Yii::app()->functions->getOptionAdmin('google_geo_api_key');		
		if ( !empty($key)){
			$url="https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($from)."&destinations=".urlencode($to)."&language=en-EN&sensor=false&units=imperial&key=".urlencode($key);
		}	
							
		$data = @file_get_contents($url);		
		if (empty($data)){
			$data=$this->Curl($url);
		}
	    $data = json_decode($data);              
	    
	    if ($debug){
		   dump($data);	   
		}
	    
	    if (is_object($data)){
	    	if ($data->status=="OK"){    		    		
	    		if ($data->rows[0]->elements[0]->status=="OK" ) {    			
	    			return $data;
	    		}    	    		
	    	}
	    }
	    return FALSE;
	}	
	
	public function arraySortByColumn(&$array,$column,$dir = 'asc') {
		
	    foreach($array as $a) $sortcol[$a[$column]][] = $a;
	    ksort($sortcol);
	    foreach($sortcol as $col) {
	        foreach($col as $row) $newarr[] = $row;
	    }	    
	    if($dir=='desc') $array = array_reverse($newarr);
	    else $array = $newarr;
    }
    
    public function getReviews($client_id='',$merchant_id='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{review}}
		WHERE
		client_id= ".FunctionsV3::q($client_id)."
		AND
		merchant_id= ".FunctionsV3::q($merchant_id)."
		AND
		status ='publish'
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
    public function getReviewsList($merchant_id='')
	{
		//select concat(first_name ,' ',last_name)
		$DbExt=new DbExt;
		$stmt="SELECT a.*,
		(
		select first_name
		from 
		{{client}}
		where
		client_id=a.client_id
		) as client_name
		FROM
		{{review}} a
		WHERE		
		merchant_id= ".FunctionsV3::q($merchant_id)."
		AND
		status ='publish'
		ORDER BY id DESC
		LIMIT 0,20
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res;
		}
		return false;
	}	
	
    public function getReviewsById($id='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT a.*,
		(
		select restaurant_name 
		from
		{{merchant}}
		where
		merchant_id=a.merchant_id
		) as merchant_name
		FROM
		{{review}} a
		WHERE
		id= ".FunctionsV3::q($id)."
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}	
	
    public function getReviewsById2($id='',$merchant_id='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{review}}
		WHERE
		id= ".FunctionsV3::q($id)."
		AND
		merchant_id= ".FunctionsV3::q($merchant_id)."
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}		
	
	public function updateRatings($merchant_id='',$ratings='',$client_id='')
	{
		$DbExt=new DbExt;
		
		$params=array(
	      'merchant_id'=>$merchant_id,
	      'ratings'=>$ratings,
	      'client_id'=>$client_id,
	      'date_created'=>FunctionsV3::dateNow(),
	      'ip_address'=>$_SERVER['REMOTE_ADDR']
	    );	    	    
	    	    	    
	    if ( !$res=$this->isClientRatingExist($merchant_id,$client_id) ){	    
	    	$DbExt->insertData("{{rating}}",$params);	    	
	    	return true;
	    } else {	    	    	
	    	$rating_id=$res['id'];	    	    	
	    	$update=array(
	    	  'ratings'=>$ratings,
	    	   'date_created'=>FunctionsV3::dateNow(),
	           'ip_address'=>$_SERVER['REMOTE_ADDR']
	        );
	    	if ( $DbExt->updateData("{{rating}}",$update,'id',$rating_id) ){	    		
	    		return true;
	    	} 	    	    
	    }	  
	    return false;  	
	}
	
    public function getPaypalConnection($merchant_id='')
    {
    	 $paypal_mode=yii::app()->functions->getOption('paypal_mode',$merchant_id);    	 
		 $paypal_con=array();		 
		 if ($paypal_mode=="sandbox"){
		  	  $paypal_con['mode']="sandbox";
		  	  $paypal_con['sandbox']['paypal_nvp']='https://api-3t.sandbox.paypal.com/nvp';
		  	  $paypal_con['sandbox']['paypal_web']='https://www.sandbox.paypal.com/cgi-bin/webscr';
		  	  $paypal_con['sandbox']['user']=yii::app()->functions->getOption('sanbox_paypal_user',$merchant_id);
		  	  $paypal_con['sandbox']['psw']=yii::app()->functions->getOption('sanbox_paypal_pass',$merchant_id);
		  	  $paypal_con['sandbox']['signature']=yii::app()->functions->getOption('sanbox_paypal_signature',$merchant_id);
		  	  $paypal_con['sandbox']['version']='61.0';
		  	  $paypal_con['sandbox']['action']='Sale';
		  } else {
		  	  $paypal_con['mode']="live";
		  	  $paypal_con['live']['paypal_nvp']='https://api-3t.paypal.com/nvp';
		  	  $paypal_con['live']['paypal_web']='https://www.paypal.com/cgi-bin/webscr';
		  	  $paypal_con['live']['user']=yii::app()->functions->getOption('live_paypal_user',$merchant_id);
		  	  $paypal_con['live']['psw']=yii::app()->functions->getOption('live_paypal_pass',$merchant_id);
		  	  $paypal_con['live']['signature']=yii::app()->functions->getOption('live_paypal_signature',$merchant_id);
		  	  $paypal_con['live']['version']='61.0';
		  	  $paypal_con['live']['action']='Sale';
		  }
		  return $paypal_con;
    }	

    public function getPaypalConnectionAdmin()
    {
    	 $paypal_mode=yii::app()->functions->getOptionAdmin('admin_paypal_mode');    	     	 
		 $paypal_con=array();		 
		 if ($paypal_mode=="sandbox"){
		  	  $paypal_con['mode']="sandbox";
		  	  $paypal_con['sandbox']['paypal_nvp']='https://api-3t.sandbox.paypal.com/nvp';
		  	  $paypal_con['sandbox']['paypal_web']='https://www.sandbox.paypal.com/cgi-bin/webscr';
		  	  $paypal_con['sandbox']['user']=yii::app()->functions->getOptionAdmin('admin_sanbox_paypal_user');
		  	  $paypal_con['sandbox']['psw']=yii::app()->functions->getOptionAdmin('admin_sanbox_paypal_pass');
		  	  $paypal_con['sandbox']['signature']=yii::app()->functions->getOptionAdmin('admin_sanbox_paypal_signature');
		  	  $paypal_con['sandbox']['version']='61.0';
		  	  $paypal_con['sandbox']['action']='Sale';
		  } else {
		  	  $paypal_con['mode']="live";
		  	  $paypal_con['live']['paypal_nvp']='https://api-3t.paypal.com/nvp';
		  	  $paypal_con['live']['paypal_web']='https://www.paypal.com/cgi-bin/webscr';
		  	  $paypal_con['live']['user']=yii::app()->functions->getOptionAdmin('admin_live_paypal_user');
		  	  $paypal_con['live']['psw']=yii::app()->functions->getOptionAdmin('admin_live_paypal_pass');
		  	  $paypal_con['live']['signature']=yii::app()->functions->getOptionAdmin('admin_live_paypal_signature');
		  	  $paypal_con['live']['version']='61.0';
		  	  $paypal_con['live']['action']='Sale';
		  }
		  return $paypal_con;
    }	        
	
	public function paypalSavedToken($params='')
    {    	    	
		$command = Yii::app()->db->createCommand();
		if ($command->insert('{{paypal_checkout}}',$params)){
		   return TRUE;
		} 
		return FALSE;
    }
    
    public function getOrderByPayPalToken($token='')
    {
    	$DbExt=new DbExt;
    	$stmt="
    	SELECT a.*,
    	(
    	select merchant_id
    	from
    	{{order}}
    	where
    	order_id=a.order_id
    	) as merchant_id
    	FROM
    	{{paypal_checkout}} a
    	WHERE
    	token= ".FunctionsV3::q($token)."
    	LIMIT 0,1
    	";
    	if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
    }
    
    public function getPaypalOrderPayment($order_id='')
    {
    	$DbExt=new DbExt;
    	$stmt="
    	SELECT * FROM
    	{{paypal_payment}}
    	WHERE
    	order_id= ".FunctionsV3::q($order_id)."
    	LIMIT 0,1
    	";
    	if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
    }
    
    public function prettyDate($date='',$full=false)
    {
    	if ($date=="0000-00-00"){
    		return ;
    	}    
    	if ($date=="0000-00-00 00:00:00"){
    		return ;
    	}
    	if ( !empty($date)){
    		if  ($full==TRUE){
    	         return date('M d,Y G:i:s',strtotime($date));
    		} else return date('M d,Y',strtotime($date));
    	}
    	return false;
    }
    
    public function clientHistyOrder($client_id='')
    {
    	$DbExt=new DbExt;
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
    	{{order}} a
    	WHERE 
    	client_id= ".FunctionsV3::q($client_id)."
    	AND status NOT IN ('".initialStatus()."')
    	ORDER BY order_id DESC
    	LIMIT 0,10
    	";
    	if ( $res=$DbExt->rst($stmt)){
			return $res;
		}
		return false;
    }
    
    public function clientHistyOrderDetails($order_id='')
    {
    	$DbExt=new DbExt;
    	$stmt="
    	SELECT * FROM
    	{{order_details}}
    	WHERE
    	order_id=".FunctionsV3::q($order_id)."
    	ORDER BY id ASC    	
    	";
    	if ( $res=$DbExt->rst($stmt)){
			return $res;
		}
		return false;
    }    
    
    public function orderStatusList($aslist=true)
    {
    	$mid=$this->getMerchantID();
    	$list=array();
    	if ($aslist){
    	    $list[]=Yii::t("default","Please select");    	
    	}
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM 
    	  {{order_status}} 
    	  WHERE
    	  merchant_id IN ('0','$mid')
    	  ORDER BY stats_id";	    	
    	if ($res=$db_ext->rst($stmt)){
    		foreach ($res as $val) {    			
    			//$list[$val['stats_id']]=ucwords($val['description']);
    			$list[$val['description']]=t($val['description']);
    		}    		
    		return $list;
    	}
    	return false;    
    }    
    
    public function getOrderStatus($stats_id='')
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM 
    	  {{order_status}} 
    	  WHERE
    	  stats_id= ".FunctionsV3::q($stats_id)." ";	    	
    	if ($res=$db_ext->rst($stmt)){
    		return $res[0];
    	}
    	return false;    
    }
    
    public function verifyOrderIdByOwner($order_id='',$merchant_id='')
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM
    	{{order}}
    	WHERE
    	order_id=".FunctionsV3::q($order_id)."
    	AND
    	merchant_id= ".FunctionsV3::q($merchant_id)."
    	";
    	if ($res=$db_ext->rst($stmt)){
    		return $res[0];
    	}
    	return false;
    }
    
    public function formatAsChart($data='')
    {
    	$chart_data='';
    	if (is_array($data) && count($data)>=1){
	    	foreach ($data as $key => $val) {
	    		$key=addslashes($key);
	    		$chart_data.="[\"$key\",$val],";
	    	}
	    	$chart_data=substr($chart_data,0,-1);
	    	return "[[$chart_data]]";
    	} 
    	return "[[0]]";
    }    
    
    public function newOrderList($viewed='')
    {
    	$merchant_id=Yii::app()->functions->getMerchantID();	   
    	$and='';
    	/*if (is_numeric($viewed)){
    		$and.=" AND viewed='0'";
    	}*/
    	$db_ext=new DbExt;    	
    	$stmt="
    	      SELECT * FROM
    	      {{order}}
    	      WHERE    	          	      
    	      date_created like '".date('Y-m-d')."%'
    	      AND
    	      merchant_id = ".FunctionsV3::q($merchant_id)."
    	      AND
    	      viewed='1'
    	      AND status NOT IN ('".initialStatus()."')
    	      AND request_cancel='2'
    	      ORDER BY date_created DESC
    	      LIMIT 0,50
    	";    	
    	//dump($stmt);
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res;
    	}
    	return false;
    }       
    
    public function getPackagesById($package_id='')
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM
    	{{packages}}
    	WHERE
    	package_id= ".FunctionsV3::q($package_id)."
    	LIMIT 0,1
    	";
    	if ( $res=$db_ext->rst($stmt)){
    		return $res[0];
    	}
    	return false;    
    }
    
    public function standardPrettyFormat($price='')
    {        
        $decimal=Yii::app()->functions->getOptionAdmin('admin_decimal_place');
		$decimal_separators=Yii::app()->functions->getOptionAdmin('admin_use_separators');		
		$thou_separator='';
		if (!empty($price)){
			if ($decimal==""){
				$decimal=2;
			}
			if ( $decimal_separators=="yes"){
				$thou_separator=",";
			}		
			return number_format((float)$price,$decimal,".",$thou_separator);
		}	
		if ($decimal==""){
			$decimal=2;
		}	
		return number_format(0,$decimal,".",$thou_separator);	
        
    }
        
    public function normalPrettyPrice($price='')
    {
    	if (is_numeric($price)){
		    return number_format($price,2,'.','');
	    }
	    return false;        
    }
    
    public function normalPrettyPrice2($price='')
    {
    	if (is_numeric($price)){
		    return number_format($price,0,'.','');
	    }
	    return false;        
    }
    
    public function limitDescription($text='',$limit=300)
    {
    	if ( !empty($text)){
    		return substr($text,0,$limit)."...";
    	}
    	return false;   
    }
    
    public function getPackagesList($price=false)
	{
		$and='';
		if ($price){
			$and=" AND price >0 ";
		}	
		$data_feed=array();
		$stmt="
		SELECT * FROM
		{{packages}}		
		WHERE
		status='publish'
		$and
		ORDER BY sequence ASC
		";						
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 				
		if (is_array($rows) && count($rows)>=1){
			if ($this->data=="list"){
				foreach ($rows as $val) {									   
				   $data_feed[$val['package_id']]=ucwords($val['title']);
				}
				return $data_feed;
			} else return $rows;
		}
		return FALSE;
	}        	
	
	public function randomColor()
    {
	   $color[1]='#55A2E8';
	   $color[2]='#42C9BA';
	   $color[3]='#E57567';
	   $color[4]='#FEB034';
	   $color[5]='#00D9A3';
	   $rnd_number=rand(1, 5);
	   return $color[$rnd_number];
    }
    
    public function adminCurrencyCode()
    {
    	$curr_code=$this->getOptionAdmin("admin_currency_set");
    	if (empty($curr_code)){
    		return "USD";
    	}        	
    	return $curr_code;
    }
    
    public function adminCurrencySymbol()
    {
    	$curr_code=$this->getOptionAdmin("admin_currency_set");
    	if (empty($curr_code)){
    		$curr_code="USD";
    	}    
    	$db_ext=new DbExt;
    	$stmt="SELECT  * FROM
    	{{currency}}
    	WHERE
    	currency_code= ".FunctionsV3::q($curr_code)."
    	LIMIT 0,1
    	";
    	if ( $res=$db_ext->rst($stmt)){
    		return $res[0]['currency_symbol'];
    	} 
    	return "$";
    }
    
    public function adminSetCounryCode()
    {
    	$country_code=$this->getOptionAdmin("admin_country_set");
    	if (empty($country_code)){
    		return "PH";
    	}        	
    	return $country_code;    	
    }
        
    public function generateRandomKey($range=10) 
    {
	    $chars = "0123456789";	
	    srand((double)microtime()*1000000);	
	    $i = 0;	
	    $pass = '' ;	
	    while ($i <= $range) {
	        $num = rand() % $range;	
	        $tmp = substr($chars, $num, 1);	
	        $pass = $pass . $tmp;	
	        $i++;	
	    }
	    return $pass;
    }
    
    public function validateUsername($username='',$merchant_id='')
    {
    	$db_ext=new DbExt;
    	if (is_numeric($merchant_id)){
    		$stmt="SELECT * FROM
	    	{{merchant}}
	    	WHERE 
	    	username=".FunctionsV3::q($username)."
	    	AND
	    	merchant_id <>'$merchant_id' 	
	    	LIMIT 0,1
	    	";
    	} else {    
	    	$stmt="SELECT * FROM
	    	{{merchant}}
	    	WHERE 
	    	username=".FunctionsV3::q($username)."
	    	LIMIT 0,1
	    	";
    	}    	
    	//dump($stmt);
    	if ( $res=$db_ext->rst($stmt)){    		
    		return $res;
    	} 
    	return false;    
    }
    
    public function insertMerchantCC($params='')
    {
    	$db_ext=new DbExt;
    	if ($db_ext->insertData("{{merchant_cc}}",$params)){
    		return Yii::app()->db->getLastInsertID();
    	}
    	return false;   
    }  
    
    public function getMerchantPaymentByID($id='')
    {
    	$DbExt=new DbExt;
    	$stmt="SELECT * FROM
    	{{package_trans}}
    	WHERE
    	id= ".FunctionsV3::q($id)."
    	LIMIT 0,1
    	";
    	if ($res=$DbExt->rst($stmt)){
    		return $res[0];
    	}
    	return false;    
    }
    
    public function getMerchantPaymentTransaction($merchant_id='')
    {
    	$DbExt=new DbExt;
    	$stmt="SELECT a.*,
    	(
    	select title
    	from
    	{{packages}}
    	where
    	package_id=a.package_id
    	) as package_name
    	FROM
    	{{package_trans}} a
    	WHERE
    	merchant_id= ".FunctionsV3::q($merchant_id)."
    	ORDER BY id DESC 	
    	";
    	if ($res=$DbExt->rst($stmt)){
    		return $res;
    	}
    	return false;
    }
    
    public function merchantList($as_list=true,$with_select=false)
    {
    	$data=array();
    	$DbExt=new DbExt;
    	$stmt="SELECT * FROM
    	{{merchant}}
    	WHERE status in ('active')
    	ORDER BY restaurant_name ASC
    	";
    	if ( $with_select){
    		$data[]=t("Please select");
    	}
    	if ($res=$DbExt->rst($stmt)){    		
    		if ( $as_list==TRUE){
    			foreach ($res as $val) {    				
    			    $data[$val['merchant_id']]=ucwords(stripslashes($val['restaurant_name']));
    			}
    			return $data;
    		} else return $res;    	
    	}
    	return false;
    }

    public function ExpirationType()
    {
    	return array(
    	 'days'=>t("Days"),
    	 'year'=>t("Year")
    	);
    }
    
    public function ListlimitedPost()
    {
    	return array(
    	  2=>t("Unlimited"),
    	  1=>t("Limited")
    	);
    }
    
    public function validateMerchantCanPost($merchant_id='')
    {    	
    	$DbExt=new DbExt;
    	$stmt="SELECT a.merchant_id,
    	a.package_id,
    	a.is_commission,
    	b.unlimited_post,
    	b.post_limit,
    	(
    	select count(*)
    	from
    	{{item}}
    	where
    	merchant_id=a.merchant_id
    	) as total_post
    	FROM
    	{{merchant}} a
    	left join {{packages}} b
        On
        a.package_id=b.package_id
    	WHERE
    	a.merchant_id= ".FunctionsV3::q($merchant_id)."
    	LIMIT 0,1
    	";
    	if ($res=$DbExt->rst($stmt)){
    		$data=$res[0];    		    		    		
    		
    		if ( $data['is_commission']==2){
    			return true;
    		}
    		
    		if ( $data['unlimited_post']==1){
    			if ( $data['total_post']>=$data['post_limit']){    				
    				return false;
    			}    		
    		}    	
    	}
    	return true;    
    }
    
    public function sendEmail($to='',$from='',$subject='',$body='' , $record_id='')
    {    			 
    	$from1=Yii::app()->functions->getOptionAdmin('global_admin_sender_email');
    	if (!empty($from1)){
    		$from=$from1;
    	}    	
    	
    	$email_dsiabled_auto_break=getOptionA('email_dsiabled_auto_break');
		if($email_dsiabled_auto_break!=1){
		   $body=nl2br($body);
		}
    				
    	$send_status=false; $send_msg='';
    	   	    	
    	$email_provider=Yii::app()->functions->getOptionAdmin('email_provider');
    	//dump($email_provider);
    	switch ($email_provider) {
    		case "smtp":
    			$smtp_host=Yii::app()->functions->getOptionAdmin('smtp_host');
	    		$smtp_port=Yii::app()->functions->getOptionAdmin('smtp_port');
	    		$smtp_username=Yii::app()->functions->getOptionAdmin('smtp_username');
	    		$smtp_password=Yii::app()->functions->getOptionAdmin('smtp_password');
	    		$smtp_secure=Yii::app()->functions->getOptionAdmin('smtp_secure');	   
	    			    		    		    	
	    		$mail=Yii::app()->Smtpmail;
	    		
	    		Yii::app()->Smtpmail->Host=$smtp_host;
	    		Yii::app()->Smtpmail->Username=$smtp_username;
	    		Yii::app()->Smtpmail->Password=$smtp_password;
	    		Yii::app()->Smtpmail->Port=$smtp_port;
	    		Yii::app()->Smtpmail->SMTPSecure=$smtp_secure;
	    		Yii::app()->Smtpmail->CharSet="utf-8";
	    		
			    $mail->SetFrom($from, '');
			    $mail->Subject = $subject;
			    $mail->MsgHTML($body);
			    $mail->AddAddress($to, "");
			    if(!$mail->Send()) {			        
			        $mail->ClearAddresses();	
			        $send_msg=t("error occurred")." ".$mail->ErrorInfo;
			    } else {			        
			        $mail->ClearAddresses();
			        $send_status=true;
			        $send_msg="sent";
			    }    		    		
    			break;
    	
    		case "mandrill":
    			
    			$api_key=Yii::app()->functions->getOptionAdmin('mandrill_api_key');    		
	    		try {
	    			 require_once 'mandrillapp/Mandrill.php';
	    			 $mandrill = new Mandrill($api_key);
	    			 $message = array(
				        'html' => $body,
				        'text' => '',
				        'subject' => $subject,
				        'from_email' => $from,
				        //'from_name' => 'Example Name',
				        'to' => array(
				            array(
				                'email' => $to,
				                //'name' => 'Recipient Name',
				                'type' => 'to'
				            )
				        )
	                );                
	                $async = false;
				    $ip_pool = '';
				    $send_at = '';
				    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);				    
				    if (is_array($result) && count($result)>=1){
				    	if ($result[0]['status']=="sent"){
				    		$send_status=true; $send_msg="sent";
				    	}				    	
				    } 
	    		} catch(Mandrill_Error $e) {	    			
	    			$send_msg=t("error occurred")." ".$e->getMessage();
	    		}		    		
    			 break;
    			 
    		case "sendgrid":
    			    		    
    		     require_once('sendgrid/sendgrid-php.php');
    		     
		         $request_body=(object)array(
		           'personalizations'=>array(
		             (object) array(
		                'to'=>array(
		                  (object)array(
		                    'email'=>$to
		                  )
		                ),
		                'subject'=>$subject
		              )
		           ),
		          'from'=>(object)array(
		             'email'=>$from
		           ),
		           'content'=>array(
		              (object)array(
		                'type'=>"text/html",
		                'value'=>$body
		              )
		           )
		         );
		         		         			         	       
		         $apiKey = trim(getOptionA('sendgrid_api_key'));
		         $sg = new \SendGrid($apiKey);
		         $response = $sg->client->mail()->send()->post($request_body);
		         $respcode = $response->statusCode();
		         
		         if ($respcode==202){
		         	$send_status=true; $send_msg="sent";
		         }  else {
		         	$sendgrid_body=json_decode($response->body(),true);
		            if(is_array($sendgrid_body) && count($sendgrid_body)>=1){
		                $send_msg = $sendgrid_body['errors'][0]['message'];
		            } else $send_msg = t("error occurred");
		         }	    		   		     
    		     break;
    		     
    		case "mailjet":     

    		    require_once 'mailjet/Mailjet.php';
			    $apikey = trim(getOptionA('mailjet_api_key'));
			    $secret_key = trim(getOptionA('mailjet_secret_key'));
			    $mj = new Mailjet($apikey, $secret_key);
			    
			    $params = array(
			        "method"  => "POST",
			        "from"    => $from,
			        "to"      => $to,
			        "subject" => $subject,
			        "html"    => $body
			    );

			    $mj_resp = $mj->sendEmail($params);
			    
			    if ($mj->_response_code == 200){
			   	   $send_status=true; $send_msg="sent";
			    } else $send_msg = t("error occurred")." ".$mj->_response_code;
			    		    
    		   break;
    		   
    		case "elasticemail":
    		   $api_elastic = 'https://api.elasticemail.com/v2/email/send';
    		   $elastic_params = array(
    		      'from' => $from,
			      //'fromName' => '',
			      'apikey' => trim(getOptionA('elastic_email_apikey')),
			      'subject' => $subject,
			      'to' => $to,
			      'bodyHtml' => $body ,
			      //'bodyText' => 'Text Body',
			      'isTransactional' => false
			   );
			    
			   $options = array(
                  'http' => array(
                          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                          'method'  => 'POST',
                          'content' => http_build_query($elastic_params)
                   )
               );

               $context  = stream_context_create($options);
               $elastic_resp = file_get_contents($api_elastic, false, $context);
               if ($elastic_resp === FALSE) {
               	   $send_msg = t("error occurred");
               } else {
               	   $elastic_results=json_decode($elastic_resp,true);               	   
               	   if(is_array($elastic_results) && count($elastic_results)>=1){
	               	   if ($elastic_results['success']==1){
	               	   	   $send_status=true; $send_msg="sent";
	               	   } else $send_msg = $elastic_results['error'];
               	   } else $send_msg = t("error occurred");
               }               
    		   break;
    		
    		default:    			
    			$headers  = "From: $from\r\n";		
		        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
		        
$message =<<<EOF
$body
EOF;
                $headers  = "From: $from\r\n";                                
                $headers .= "Content-type: text/html; charset=UTF-8\r\n";

                if (!empty($to)) {
                	//if (@mail($to, '=?utf-8?B?'.base64_encode($subject).'?=', $message, $headers)){		
					if (@mail($to, $subject, $message, $headers)){
						$send_status=true; $send_msg="sent";
					} else $send_msg = t("error occurred");
				} else $send_msg = t("to is missing"); 
    			break;
    	}
    	     	        	
    	$params_logs=array(
    	   'email_address'=>$to,
    	   'sender'=>$from,
    	   'subject'=>$subject,
    	   'content'=>$body,
    	   'status'=>$send_msg,
    	   'date_created'=>FunctionsV3::dateNow(),
    	   'ip_address'=>$_SERVER['REMOTE_ADDR'],
    	   'module_type'=>"core",
    	   'email_provider'=>$email_provider
    	);    	
    	$DbExt=new DbExt;
    	
    	if ($record_id>0){
    		$params_logs=array(
    		   'status'=>$send_msg,
    		   'email_provider'=>$email_provider,
    		   'ip_address'=>$_SERVER['REMOTE_ADDR'],
    		);
    		$DbExt->updateData("{{email_logs}}",$params_logs,'id',$record_id);
    	} else $DbExt->insertData("{{email_logs}}",$params_logs);    
    	unset($DbExt);
    	
    	return $send_status;   	
    }    		      
	
    public function adminCountry()
    {
    	$admin_country_set=Yii::app()->functions->getOptionAdmin('admin_country_set');
    	$country_list=require Yii::getPathOfAlias('webroot')."/protected/components/CountryCode.php";
		$country='';
		if (array_key_exists($admin_country_set,(array)$country_list)){
			$country=$country_list[$admin_country_set];
		} else $country=$admin_country_set;
		return $country;
    }
    
	public function accountExistSocial($email='',$social='fb')
    {    	
		/*$stmt="
		SELECT * FROM
		{{client}}
		WHERE
		email_address='".addslashes($email)."'
		AND
		social_strategy ='".addslashes($social)."'
		LIMIT 0,1
		";*/	
		$stmt="
		SELECT * FROM
		{{client}}
		WHERE
		email_address=".FunctionsV3::q($email)."
		LIMIT 0,1
		";		
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll();	
		if (is_array($rows) && count($rows)>=1){	
			return $rows;
		} else return FALSE;	
    }    
    
    public function paypalSetCancelOrder($token='')
    {    	
    	$DbExt=new DbExt;
    	$stmt="UPDATE
    	{{order}}
    	SET 
    	status='cancelled'
    	WHERE
    	order_id=(select order_id from {{paypal_checkout}} where token=".FunctionsV3::q($token)." )
    	";
    	$DbExt->qry($stmt);
    }
    
    public function getLostPassToken($token='')
    {
    	if(!empty($token)){	    	
	    	$stmt="SELECT * FROM
	    	{{client}}
	    	WHERE
	    	lost_password_token= ".FunctionsV3::q($token)."
	    	LIMIT 0,1
	    	";	    	
	    	if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
	    		return $res;
	    	}
    	}
    	return false;  
    }
    
    public function getAdminUserInfo($admin_id='')
    {
    	$DbExt=new DbExt;
    	$stmt="SELECT * FROM
    	{{admin_user}}
    	WHERE
    	admin_id= ".FunctionsV3::q($admin_id)."
    	LIMIT 0,1
    	";
    	if ($res=$DbExt->rst($stmt)){
    		return $res[0];
    	}
    	return false;  
    }    
    
    public function getCustomPage($id='')
    {
    	$DbExt=new DbExt;
    	$stmt="SELECT * FROM
    	{{custom_page}}
    	WHERE
    	id= ".FunctionsV3::q($id)."
    	LIMIT 0,1
    	";
    	if ($res=$DbExt->rst($stmt)){
    		return $res[0];
    	}
    	return false;  
    }        
    
    public function getCustomPageBySlug($slug='')
    {
    	$DbExt=new DbExt;
    	$stmt="SELECT * FROM
    	{{custom_page}}
    	WHERE
    	slug_name= ".FunctionsV3::q($slug)."
    	LIMIT 0,1
    	";
    	if ($res=$DbExt->rst($stmt)){
    		return $res[0];
    	}
    	return false;  
    }            
    
    public function getCustomPageList()
    {
    	$DbExt=new DbExt;
    	$stmt="SELECT * FROM
    	{{custom_page}}    	
    	WHERE
    	status IN ('publish')
    	ORDER BY sequence ASC
    	";
    	if ($res=$DbExt->rst($stmt)){
    		return $res;
    	}
    	return false;  
    }            
    
    public function customPageCreateSlug($page_name='')
    {
    	/*$slug_name=str_replace(" ","-",$page_name);
    	$slug_name=strtolower($slug_name);*/
    	
    	$slug_name=$this->seo_friendly_url($page_name);    	
    	
    	$DbExt=new DbExt;
    	$stmt="SELECT count(*) as total
    	FROM
    	{{custom_page}}
    	WHERE
    	slug_name= ".FunctionsV3::q($slug_name)."
    	";    	
    	if ($res=$DbExt->rst($stmt)){
    		if ($res[0]['total']>=1){
    			return $slug_name.$res[0]['total'];
    		} else  return $slug_name;        	
    	} else return $slug_name;    
    }
    
    public function customPagePosition($position='top')
    {
    	$DbExt=new DbExt;
    	$stmt="SELECT * FROM
    	{{custom_page}}    	
    	WHERE
    	status IN ('publish')
    	AND
    	assign_to= ".FunctionsV3::q($position)."
    	ORDER BY sequence ASC
    	";
    	if ($res=$DbExt->rst($stmt)){
    		return $res;
    	}
    	return false;  
    }
    
    public function generateCode($length = 8) {
	   $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	   $ret = '';
	   for($i = 0; $i < $length; ++$i) {
	     $random = str_shuffle($chars);
	     $ret .= $random[0];
	   }
	   return $ret;
    }
    
    public function mobileMenu()
    {
    	$menu_html='';
    	$arg1=$this->topLeftMenu();    
    	if (isset($arg1['items'])){
	    	if (is_array($arg1['items']) && count($arg1['items'])>=1){
	    		foreach ($arg1['items'] as $val) {	    				    			
	    			if (is_array($val['url']) && count($val['url'])>=1)	{
	    			    $url=Yii::app()->request->baseUrl.$val['url'][0];
	    			} else $url=Yii::app()->request->baseUrl.$val['url'];
	    			$menu_html.="<li><a href=\"$url\">".$val['label']."</a></li>";
	    		}
	    	}    
    	}
    	
    	if ( Yii::app()->functions->isClientLogin()){
$menu_html.="<li class=\"uk-parent\">";
$menu_html.="<a href=\"#\"><i class=\"uk-icon-user\"></i> ".ucwords(Yii::app()->functions->getClientName())."</a>";
$menu_html.="<ul class=\"uk-nav-sub\">";
$menu_html.="<li><a href=\"".Yii::app()->request->baseUrl."/store/Profile\"\"><i class=\"uk-icon-user\"></i> ".Yii::t("default","Profile")."</a></li>";
$menu_html.="<li><a href=\"".Yii::app()->request->baseUrl."/store/orderHistory\"\"><i class=\"fa fa-file-text-o\"></i> ".Yii::t("default","Order History")."</a></li>";    		

if (Yii::app()->functions->getOptionAdmin('disabled_cc_management')==""):
$menu_html.="<li><a href=\"".Yii::app()->request->baseUrl."/store/Cards\"\"><i class=\"uk-icon-gear\"></i> ".Yii::t("default","Credit Cards")."</a></li>";    	
endif;

/*POINTS PROGRAM*/
//$menu_html.=PointsProgram::frontMenu(false);


$menu_html.="<li><a href=\"".Yii::app()->request->baseUrl."/store/logout\"\"><i class=\"uk-icon-sign-out\"></i> ".Yii::t("default","Logout")."</a></li>";    		    		    		    		    	
$menu_html.="</ul>";
$menu_html.="</li>";
    	}
    	
    	$arg1=$this->topMenu();    
    	if (isset($arg1['items'])){
	    	if (is_array($arg1['items']) && count($arg1['items'])>=1){
	    		foreach ($arg1['items'] as $val) {	    				    			
	    			$class='';	    			
	    			if (is_array($val['url']) && count($val['url'])>=1){
	    				$url=Yii::app()->request->baseUrl.$val['url'][0];
	    			} else {
	    				$class=isset($val['itemOptions']['class'])?$val['itemOptions']['class']:'';
	    				$url=$val['url'];
	    			}	    
	    			if (isset($val['visible'])){
	    				if ($val['visible']){
	    					$menu_html.="<li class=\"$class\"><a href=\"$url\">".$val['label']."</a></li>";
	    				} 	    		
	    			} else {
	    				$menu_html.="<li class=\"$class\"><a href=\"$url\">".$val['label']."</a></li>";
	    			}	    			
	    		}
	    	}    
    	}
    	$arg1=$this->bottomMenu();    
    	if (isset($arg1['items'])){
	    	if (is_array($arg1['items']) && count($arg1['items'])>=1){
	    		foreach ($arg1['items'] as $val) {	    			
	    			if (is_array($val['url']) && count($val['url'])>=1)	{
	    				$url=Yii::app()->request->baseUrl.$val['url'][0];
	    			} else $url=Yii::app()->request->baseUrl.$val['url'];		
	    			$menu_html.="<li><a href=\"$url\">".$val['label']."</a></li>";
	    		}
	    	}    
    	}
    	return $menu_html;
    }
    
    public function isTableExist($table_name='')
    {
    	$db_ext=new DbExt;
    	$stmt="SHOW TABLE STATUS LIKE '{{{$table_name}}}'";	
    	if ($res=$db_ext->rst($stmt)){
    		return true;
    	}
    	return false;    
    }            
    
    public function checkTableStructure($table_name='')
    {
    	$db_ext=new DbExt;
    	$stmt=" SHOW COLUMNS FROM {{{$table_name}}}";	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res;
    	}
    	return false;    
    }      
    
    public function getSourceTranslation($lang_id='')
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM
    	{{languages}}
    	WHERE
    	lang_id= ".FunctionsV3::q($lang_id)."
    	LIMIT 0,1
    	";    	
    	if ($res=$db_ext->rst($stmt)){
    		$translated_text=!empty($res[0]['source_text'])?(array)json_decode($res[0]['source_text']):array();
    	    return $translated_text;
    	}
    	return false;
    }       
    
    public function getSourceTranslationFile($lang_id='')
    {
    	$db_ext=new DbExt;
    	
    	$path_to_upload=Yii::getPathOfAlias('webroot')."/upload";    	
    	$stmt="SELECT * FROM
    	{{languages}}
    	WHERE
    	lang_id= ".FunctionsV3::q($lang_id)."
    	LIMIT 0,1
    	";    	
    	if ($res=$db_ext->rst($stmt)){    		
    		$filename=$res[0]['source_text'];    		
    		if (file_exists($path_to_upload."/$filename")){
    			require_once $path_to_upload."/$filename";
    		    return $lang;
    		}
    	}    	
    	return false;    	
    }
    
    public function languageInfo($lang_id='')
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM {{languages}} 
    	  WHERE lang_id= ".FunctionsV3::q($lang_id)."
    	  LIMIT 0,1
    	  ";	
    	if ($res=$db_ext->rst($stmt)){
    		return $res[0];
    	}
    	return false;    
    }

    public function availableLanguage($as_list=true)
    {
    	if ($as_list){
    		$lang_list['-9999']=Yii::t("default","Default english");
    		//$lang_list='';
    	}        	
    	$db_ext=new DbExt;
    	$stmt="SELECT lang_id,country_code,language_code
    	 FROM {{languages}} 
    	 WHERE
    	 status in ('publish','published')
    	 ";	
    	if ($res=$db_ext->rst($stmt)){    		
    		foreach ($res as $val) {    			
    			$lang_list[$val['lang_id']]=$val['country_code']." ".$val['language_code'];
    		}    		
    	}
    	return $lang_list;    
    }   
    
    public function getFlagByCode($country_code='')
    {    	
    	$country_code_ups=$country_code;
    	$country_code_list=require 'CountryCode.php';    	
    	$country_code=strtolower($country_code);    
    	$path_flag=Yii::getPathOfAlias('webroot')."/assets/images";
    	$base_url=Yii::app()->request->baseUrl."/assets/images";
    	if (!empty($country_code)){    		
    		$file=$country_code.".png";    		    		    		    		
    		if (array_key_exists($country_code_ups,(array)$country_code_list)){
    			$alt=$country_code_list[$country_code_ups];
    		} else $alt=$country_code_ups;
    		if (file_exists($path_flag."/flags/$file")){    			
    			return  "<img class=\"flags\" src=\"$base_url/flags/$file\" alt=\"$alt\" title=\"$alt\" />";
    		} else return  "<img class=\"flags\" src=\"$base_url/flags/us.png\" alt=\"$alt\" title=\"$alt\" />";
    	}
    	return false;    
    }
    
    public function getAssignLanguage()
    {
    	$lang='';
    	$db_ext=new DbExt;
    	$stmt="SELECT lang_id,country_code,language_code
    	 FROM {{languages}} 
    	 WHERE
    	 status in ('publish','published')
    	 AND
    	 is_assign='1'
    	 ";	    	
    	 if ($res=$db_ext->rst($stmt)){    	 	
    	 	 foreach ($res as $val) {
    	 	 	$lang[$val['lang_id']]=$val['country_code'];
    	 	 }    	 	 
    	 	 return $lang;
    	 }    
    	 return false;
    }       
    
    public function inArray($val='',$source='')
    {
    	if (is_array($source) && count($source)>=1){
    		if (array_key_exists($val,$source)){
    			return $source[$val];
    		}    	
    	}
    	return '';    
    }    
    
    public function getAdminLanguage()
    {
    	$id=$this->getAdminId();
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM
    	{{admin_user}}
    	WHERE
    	admin_id= ".FunctionsV3::q($id)."
    	LIMIT 0,1
    	";
    	if ($res=$db_ext->rst($stmt)){
    		return $res[0]['user_lang'];
    	} 
    	return false;
    }    
    
    public function getMerchantLanguage()
    {
    	$id=$this->getMerchantID();
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM
    	{{merchant}}
    	WHERE
    	merchant_id= ".FunctionsV3::q($id)."
    	LIMIT 0,1
    	";
    	if ($res=$db_ext->rst($stmt)){
    		return $res[0]['user_lang'];
    	} 
    	return false;
    }        
        
    public function getLanguageList()
    {    	
    	$set_lang_id=Yii::app()->functions->getOptionAdmin('set_lang_id');
		if ( !empty($set_lang_id)){
			$set_lang_id=json_decode($set_lang_id);
		}		
		$and="";
		$lang_ids='';
		if (is_array($set_lang_id) && count($set_lang_id)>=1){
			foreach ($set_lang_id as $lang_id) {				
				if (is_numeric($lang_id)){
					//$lang_ids.="'$lang_id',";
					$lang_ids.= FunctionsV3::q($lang_id).",";
				}				
			}
			$lang_ids=substr($lang_ids,0,-1);
		} else $lang_ids="''";
    	if (!empty($lang_ids)){
    		$and=" AND lang_id IN ($lang_ids) ";
    	}    
		
    	$db_ext=new DbExt;
    	$stmt="SELECT lang_id,country_code,language_code
    	 FROM {{languages}} 
    	 WHERE
    	 status in ('publish','published')
    	 $and
    	 ";	       	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res;   		
    	}
    	return false;
    }       
    
    public function getCustomPages()
    {    	
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM
    	{{custom_page}}
    	WHERE
    	status='publish'
    	 ";	
    	$list='';
    	if ($res=$db_ext->rst($stmt)){    		
    		foreach ($res as $val) {
    			$list[]=$val['page_name'];
    		}
    		return $list;
    	}
    	return false;
    }       
    
    public function deliveryChargesType()
    {
    	return array(
    	  ""=>Yii::t("default","Fixed Amount"),
    	  "1"=>Yii::t("defaul","Percentage")
    	);
    }
    
    public function setSEO($title='',$meta='',$keywords='')
    {
    	if (!empty($title)){
    	   Yii::app()->clientScript->registerMetaTag($title, 'title');     	   
    	   //Yii::app()->clientScript->registerMetaTag($title, 'og:title');     	   
    	}    	
    	if ($meta){
    	   Yii::app()->clientScript->registerMetaTag($meta, 'description'); 
    	   Yii::app()->clientScript->registerMetaTag($meta, 'og:description'); 
    	}
    	if ($keywords){
    	   Yii::app()->clientScript->registerMetaTag($keywords, 'keywords'); 
    	}
    }
        
    public function smarty($search='',$value='',$subject='')
    {	
	   return str_replace("{".$search."}",$value,$subject);
    }
     
    public function paymentCode($type='',$is_reverse=false)
    {    	
    	$code= array(
    	  'paypal'=>"pyp",
    	  'creditcard'=>"ocr",
    	  'stripe'=>"stp",
    	  'mercadopago'=>"mcd",
    	  "payline"=>"pyl",
    	  'sisow'=>"ide",
    	  'payumoney'=>"payu",
    	  'paysera'=>'pys',
    	  'bankdeposit'=>'obd',
    	  'payondeliver'=>"pyr",
    	  'barclay'=>"bcy",
    	  "epaybg"=>"epy",
    	  "authorize"=>"atz"
    	);
    	if ($is_reverse){
    		$code=array_flip($code);
    	}        	
    	if (array_key_exists($type,$code)){
    		return $code[$type];
    	}
    	return '';
    }   
    
    public function getSMSPackagesById($package_id='')
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM
    	{{sms_package}}
    	WHERE
    	sms_package_id= ".FunctionsV3::q($package_id)."
    	LIMIT 0,1
    	";
    	if ( $res=$db_ext->rst($stmt)){
    		return $res[0];
    	}
    	return false;    
    }    
    
    public function updateAdminLanguage($user_id='',$lang_id='')
    {
    	$db_ext=new DbExt;
    	$params=array(
    	  'user_lang'=>$lang_id,
    	  'date_modified'=>FunctionsV3::dateNow()
    	);
    	$db_ext->updateData("{{admin_user}}",$params,'admin_id',$user_id);
    }
    
    public function updateMerchantLanguage($user_id='',$lang_id='')
    {
    	$db_ext=new DbExt;
    	$params=array(
    	  'user_lang'=>$lang_id,
    	  'date_modified'=>FunctionsV3::dateNow()
    	);
    	$db_ext->updateData("{{merchant}}",$params,'merchant_id',$user_id);
    }    
    
    public function hasSMSCredits()
    {    	
    	$stats=Yii::app()->functions->getOptionAdmin('mechant_sms_purchase_disabled'); 
    	if ($stats=="yes"){
    		return true;
    	}
    	
    	$merchant_id=Yii::app()->functions->getMerchantID();
    	
    	$db_ext=new DbExt;
    	$stmt="
    	SELECT * FROM
    	{{sms_package_trans}}
    	WHERE
    	merchant_id= ".FunctionsV3::q($merchant_id)."
    	AND
    	status in ('paid')
    	LIMIT 0,1
    	";
    	if ( $res=$db_ext->rst($stmt) ){
    		return $res;
    	}    
    	return false;
    }
    
    public function getSMSPackage()
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM
    	{{sms_package}}
    	WHERE
    	status in ('publish')
    	ORDER BY 
    	sequence ASC
    	";
    	if ( $res=$db_ext->rst($stmt)){
    		return $res;
    	}
    	return false;    
    }
    
    public function adminPaymentList()
    {
    	$enabled_stripe=Yii::app()->functions->getOptionAdmin('admin_stripe_enabled');
    	$admin_enabled_paypal=Yii::app()->functions->getOptionAdmin('admin_enabled_paypal');    	
    	$admin_enabled_card=Yii::app()->functions->getOptionAdmin('admin_enabled_card'); 
    	$admin_mercado_enabled=Yii::app()->functions->getOptionAdmin('admin_mercado_enabled'); 
    	$merchant_payline_enabled=Yii::app()->functions->getOptionAdmin('admin_payline_enabled'); 
    	$admin_sisow_enabled=Yii::app()->functions->getOptionAdmin('admin_sisow_enabled');     	
    	$admin_payu_enabled=Yii::app()->functions->getOptionAdmin('admin_payu_enabled');     	    	
    	
    	$admin_bankdeposit_enabled=Yii::app()->functions->getOptionAdmin('admin_bankdeposit_enabled');
    	$admin_paysera_enabled=Yii::app()->functions->getOptionAdmin('admin_paysera_enabled');
    	
    	$admin_enabled_barclay=Yii::app()->functions->getOptionAdmin('admin_enabled_barclay');    	
    	$admin_enabled_epaybg=Yii::app()->functions->getOptionAdmin('admin_enabled_epaybg');
    	
    	$admin_enabled_autho=Yii::app()->functions->getOptionAdmin('admin_enabled_autho');
    	?>
    	<h4><?php echo Yii::t("default","Choose Payment option")?></h4>
    	<div class="uk-panel uk-panel-box">
    	
    	<?php if ( $admin_enabled_paypal==""):?>
    	 <div class="uk-form-row">
         <?php echo CHtml::radioButton('payment_opt',false,
         array('class'=>"icheck payment_option",'value'=>"pyp"))?> <?php echo Yii::t("default","Paypal")?>
         </div>   
         <?php endif;?>
         
         <?php if ( $admin_enabled_card==""):?>
         <div class="uk-form-row">
         <?php echo CHtml::radioButton('payment_opt',false,
         array('class'=>"icheck payment_opt payment_option",'value'=>"ccr"))?> <?php echo Yii::t("default","Offline Credit Card")?>
         </div>     
         <?php endif;?>
         
         <?php if ( $enabled_stripe=="yes"):?>
         <div class="uk-form-row">
         <?php echo CHtml::radioButton('payment_opt',false,
         array('class'=>"icheck payment_option",'value'=>"stp"))?> <?php echo Yii::t("default","Stripe")?>
         </div>     
         <?php endif;?>
         
         <?php if ( $admin_mercado_enabled=="yes"):?>
         <div class="uk-form-row">
         <?php echo CHtml::radioButton('payment_opt',false,
         array('class'=>"icheck payment_option",'value'=>"mcd"))?> <?php echo Yii::t("default","Mercadopago")?>
         </div>     
         <?php endif;?>
                  
         
         <?php if ( $admin_sisow_enabled=="yes"):?>
         <div class="uk-form-row">
         <?php echo CHtml::radioButton('payment_opt',false,
         array('class'=>"icheck payment_option",'value'=>"ide"))?> <?php echo Yii::t("default","Sisow")?>
         </div>     
         <?php endif;?>         
         
         <?php if ( $admin_payu_enabled=="yes"):?>
         <div class="uk-form-row">
         <?php echo CHtml::radioButton('payment_opt',false,
         array('class'=>"icheck payment_option",'value'=>"payu"))?> <?php echo Yii::t("default","PayUMoney")?>
         </div>     
         <?php endif;?>         
         
         <?php if ( $admin_bankdeposit_enabled=="yes"):?>
         <div class="uk-form-row">
         <?php echo CHtml::radioButton('payment_opt',false,
         array('class'=>"icheck payment_option",'value'=>"obd"))?> <?php echo Yii::t("default","Bank Deposit")?>
         </div>     
         <?php endif;?>     
         
         <?php if ( $admin_paysera_enabled=="yes"):?>
         <div class="uk-form-row">
         <?php echo CHtml::radioButton('payment_opt',false,
         array('class'=>"icheck payment_option",'value'=>"pys"))?> <?php echo Yii::t("default","Paysera")?>
         </div>     
         <?php endif;?>             
         
         <?php if ( $admin_enabled_barclay=="yes"):?>
         <div class="uk-form-row">
         <?php echo CHtml::radioButton('payment_opt',false,
         array('class'=>"icheck payment_option",'value'=>"bcy"))?> <?php echo Yii::t("default","Barclay")?>
         </div>     
         <?php endif;?>             
         
         <?php if ( $admin_enabled_epaybg=="yes"):?>
         <div class="uk-form-row">
         <?php echo CHtml::radioButton('payment_opt',false,
         array('class'=>"icheck payment_option",'value'=>"epy"))?> <?php echo Yii::t("default","EpayBg")?>
         </div>     
         <?php endif;?>             
         
         <?php if ( $admin_enabled_autho=="yes"):?>
         <div class="uk-form-row">
         <?php echo CHtml::radioButton('payment_opt',false,
         array('class'=>"icheck payment_option",'value'=>"atz"))?> <?php echo Yii::t("default","Authorize.net")?>
         </div>     
         <?php endif;?>             
         
    	</div> <!--uk-panel-->
    	<?php
    }
    
    public function getPackageSMSTrans($package_id='')
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT a.*,
    	(
    	select title
    	from
    	{{sms_package}}
    	where
    	sms_package_id = a.sms_package_id 	
    	) as title
    	 FROM
    	{{sms_package_trans}} a
    	WHERE
    	id= ".FunctionsV3::q($package_id)."
    	LIMIT 0,1    	
    	";
    	if ( $res=$db_ext->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;    
    }    
    
    public function getPackageSMSTransByMerchant($package_id='',$merchant_id='')
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT a.*,
    	(
    	select title
    	from
    	{{sms_package}}
    	where
    	sms_package_id = a.sms_package_id 	
    	) as title
    	 FROM
    	{{sms_package_trans}} a
    	WHERE
    	id= ".FunctionsV3::q($package_id)."
    	AND
    	merchant_id=".Yii::app()->db->quoteValue($merchant_id)."
    	LIMIT 0,1    	
    	";    	
    	if ( $res=$db_ext->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;    
    }        
    
    public function getAllCustomerCount()
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT COUNT(*) as total
    	FROM
    	{{client}}
    	WHERE
    	contact_phone!=''
    	";
    	if ( $res=$db_ext->rst($stmt)){
    		return $res[0]['total'];
    	}
    	return 0;
    } 

    public function getAllClientsByMerchant($merchant_id='')
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT a.client_id, COUNT(*) as total
    	FROM
    	{{client}} a
    	WHERE
    	client_id  IN ( select client_id from {{order}} where client_id=a.client_id and merchant_id=".FunctionsV3::q($merchant_id)." )
    	AND
    	contact_phone!=''
    	";    	
    	if ( $res=$db_ext->rst($stmt)){
    		return $res[0]['total'];
    	}
    	return 0;
    }   
    
    public function getMerchantSMSCredit($merchant_id='')
    {
    	
        $stats=Yii::app()->functions->getOptionAdmin('mechant_sms_purchase_disabled');    	
    	if ($stats=="yes"){
    		return 1;
    	}
    	
    	$db_ext=new DbExt;
    	$stmt="
    	SELECT SUM(sms_limit) as total_credits,
    	(
    	  select count(*) as total_send
    	   from
    	  {{sms_broadcast_details}}
    	   where
    	   merchant_id=".Yii::app()->db->quoteValue($merchant_id)."    	
    	   and
    	   status in ('process')
    	) as total_send
    	
    	FROM {{sms_package_trans}}
    	WHERE
    	merchant_id=".Yii::app()->db->quoteValue($merchant_id)."
    	AND
    	status in ('paid')
    	";
    	//dump($stmt);
    	if ($res=$db_ext->rst($stmt)){
    		return $res[0]['total_credits']-$res[0]['total_send'];
    	}
    	return 0;
    }
    
    public function SMSsendStatus()
    {
    	return array(
    	   1=>Yii::t("default","Send to All Customer"),
    	   2=>Yii::t("default","Send to Customer Who already buy your products"),
    	   3=>Yii::t("default","Send to specific mobile numbers")
    	);
    }
    
    public function mercadoGetPayment($payment_ref='')
    {
    	if(empty($payment_ref)){
    		return false;
    	}
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM
    	{{sms_package_trans}}
    	WHERE
    	payment_reference=".Yii::app()->db->quoteValue($payment_ref)."
    	";
    	if ($res=$db_ext->rst($stmt)){
    		return $res;
    	}
    	return false;
    }
    
    public function validateSellLimit($merchant_id='')
    {    	    	
    	$m1=date('Y-m-01 00:00:00');
    	$m2=date('Y-m-t H:i:s');
    	$expiration=1;
    	
    	if ($merchant_info=$this->getMerchant($merchant_id)){  	    		
    		if ( $merchant_info['is_commission']==2){
    			return true;
    		}
    	    $membership_purchase_date=$merchant_info['membership_purchase_date'];    	    
    	    $membership_purchase_date1=date("Ymd",strtotime($membership_purchase_date));
    	    /*dump($membership_purchase_date); 
    	    dump($membership_purchase_date1);*/
    	    $m3=date("Ymd");
    	    //dump($m3);
    	    if ($membership_purchase_date1>=$m3 ){
    	    	//echo "change start date";
    	    	$m1=$membership_purchase_date;
    	    }
    	}    	    	
    	$stmt="
    	SELECT a.merchant_id,a.package_id,
    	(
    	select sell_limit
    	from
    	{{packages}}
    	where
    	package_id=a.package_id
    	) as sell_limit,
    	
    	(
    	select count(*) as total
    	from
    	{{order}}
    	where
    	merchant_id=a.merchant_id
    	AND
    	date_created between '$m1' and '$m2'
    	) as total_sell
    	
    	FROM
    	{{merchant}} a
    	WHERE
    	merchant_id=".Yii::app()->db->quoteValue($merchant_id)."
    	LIMIT 0,1
    	";    	    	
    	if($res = Yii::app()->db->createCommand($stmt)->queryRow()){       		
    		if ($res['sell_limit']>=1){
    			if ($res['total_sell']>=$res['sell_limit']){       				
    				return false;			
    			}
    		}
    	}
    	return true;
    }
    
    public function upgradeMembership($merchant_id='',$package_id='')
    {
    	$membership_expired='';
    	$package_price='';
    	if ( $package=$this->getPackagesById($package_id)){    		    		
    		$package_price=$package['price'];
    		if ($package['promo_price']>0){
    			$package_price=$package['promo_price'];
    		}    	
    		$expiration=$package['expiration'];
            $membership_expired = date('Y-m-d', strtotime ("+$expiration days"));            
    	}
    	
    	//dump("expire on : ".$membership_expired);
    	
    	if ($info=$this->getMerchant($merchant_id)){
    		$t1=date('Ymd');    		
    		$membership_expired_1=$info['membership_expired'];    		
    		if ($membership_expired_1!="0000-00-00"){    			
    			$t2=date("Ymd",strtotime($membership_expired_1));    			
    			if ($t2>$t1){      				
    		        $membership_expired = date('Y-m-d', strtotime ("$membership_expired_1 +$expiration days"));  
    			}  
    		}	  	    		
    	}
    	
    	//dump("expire on : ".$membership_expired);
    	
    	return array(
    	 'membership_expired'=>$membership_expired,
    	 'package_price'=>$package_price
    	);
    }
    
    public function membershipStatusClass($status="")
    {
    	if ($status=="expired"){
    		return "uk-badge uk-badge-danger";
    	}
    	return "uk-badge";    	
    }
    
    public function SMSnotificationMerchant($merchant_id='',$order_info='',$data='')
    {
        $db_ext=new DbExt;
    	$sms_enabled_alert=Yii::app()->functions->getOption("sms_enabled_alert",$merchant_id);
        $sms_notify_number=Yii::app()->functions->getOption("sms_notify_number",$merchant_id);
        $sms_alert_message=Yii::app()->functions->getOption("sms_alert_message",$merchant_id);
        
        $sms_alert_message=$this->smarty("customer-name",$order_info['full_name'],$sms_alert_message);        
                        
        $item_order='';        
        if (is_array($data) && count($data)>=1){
        	
        	$in_msg=t("OrderNo:").$order_info['order_id']." ";
            $in_msg.=t("ClientName:").$order_info['full_name']." "; 
        	
        	foreach ($data['item'] as $val) {        		        		
        		if (!empty($val['size_words'])){
        			$item_order.="(".$val['qty']."x)".$val['item_name']. " (".$val['size_words'].")" ." ".$val['order_notes'].",";
        		} else $item_order.="(".$val['qty']."x)".$val['item_name']." ".$val['order_notes'].",";        		
        		if (isset($val['sub_item'])){
	        		if (is_array($val['sub_item']) && count($val['sub_item'])>=1){        			
	        			foreach ($val['sub_item'] as $sub_val) {        			
	        				$item_order.=$sub_val['addon_category'].":";
	        				$item_order.="(".$sub_val['addon_qty']."x)".$sub_val['addon_name'];
	        				$item_order.=", ";
	        			}
	        		}
        		}
        	}          	
        	$item_order=substr($item_order,0,-1);        	
            $sms_alert_message=$this->smarty("receipt",$in_msg.$item_order,$sms_alert_message);                                
        }                         

        
        $sms_alert_message=$this->smarty("orderno",$order_info['order_id'],$sms_alert_message);
        $sms_alert_message=$this->smarty("customername",$order_info['full_name'],$sms_alert_message);
        //$sms_alert_message=$this->smarty("customermobile",$order_info['merchant_contact_phone'],$sms_alert_message);
        if ( $order_info['trans_type']=="pickup"){
			$sms_alert_message=$this->smarty("customermobile",$order_info['contact_phone'],$sms_alert_message);
		} else {
			$sms_alert_message=$this->smarty("customermobile",$order_info['contact_phone1'],$sms_alert_message);
		}	    
		
        $sms_alert_message=$this->smarty("customeraddress",$order_info['client_full_address'],$sms_alert_message);
        $sms_alert_message=$this->smarty("amount",displayPrice(baseCurrency(),normalPrettyPrice($order_info['total_w_tax'])),
        $sms_alert_message);
        $sms_alert_message=$this->smarty("website-ddress",websiteUrl(),$sms_alert_message);
		$sms_alert_message=$this->smarty("website-address",websiteUrl(),$sms_alert_message);
		
		$sms_alert_message=$this->smarty("payment-type",$order_info['payment_type'],$sms_alert_message);
		$sms_alert_message=$this->smarty("transaction-type",$order_info['trans_type'],$sms_alert_message);
		$sms_alert_message=$this->smarty("delivery-instruction",$order_info['delivery_instruction'],$sms_alert_message);
		$sms_alert_message=$this->smarty("delivery-date",
		  $this->translateDate($this->FormatDateTime($order_info['delivery_date'],false)),
		 $sms_alert_message);		
		$sms_alert_message=$this->smarty("delivery-time",$order_info['delivery_time'],$sms_alert_message);	
		
		$sms_alert_message=$this->smarty("order-change",
		displayPrice(adminCurrencySymbol(),$this->standardPrettyFormat($order_info['order_change']))
		,$sms_alert_message);			
									
		$sms_alert_message=$this->smarty("delivery-fee",
		displayPrice(adminCurrencySymbol(),$this->standardPrettyFormat($order_info['delivery_charge']))
		,$sms_alert_message);			

		//dump($order_info);
		//dump($sms_alert_message);		
			
        //send sms to customer        
        $client_sms=Yii::app()->functions->getOption("sms_alert_customer",$merchant_id);
        $client_contact_phone=$order_info['contact_phone'];
        if (!empty($client_sms) && !empty($client_contact_phone)){        	
        	$client_sms=$this->smarty("customer-name",$order_info['full_name'],$client_sms);
        	$client_sms=$this->smarty("orderno",$order_info['order_id'],$client_sms);
        	$client_sms=$this->smarty("merchantname",$order_info['merchant_name'],$client_sms);
        	$client_sms=$this->smarty("merchantphone",$order_info['merchant_contact_phone'],$client_sms);
        	$client_sms=$this->smarty("website-ddress",websiteUrl(),$client_sms);   
			$client_sms=$this->smarty("website-address",websiteUrl(),$client_sms); 
			
			$client_sms=$this->smarty("payment-type",$order_info['payment_type'],$client_sms);
			$client_sms=$this->smarty("transaction-type",$order_info['trans_type'],$client_sms);
			$client_sms=$this->smarty("delivery-instruction",$order_info['delivery_instruction'],$client_sms);
			$client_sms=$this->smarty("delivery-date",
			  $this->translateDate($this->FormatDateTime($order_info['delivery_date'],false)),
			 $client_sms);		
			$client_sms=$this->smarty("delivery-time",$order_info['delivery_time'],$client_sms);	
			$client_sms=$this->smarty("order-change",
			displayPrice(adminCurrencySymbol(),$this->standardPrettyFormat($order_info['order_change']))
			,$client_sms);			
			$client_sms=$this->smarty("merchant-address",$order_info['merchant_address'],$client_sms);
						
			$client_sms=$this->smarty("delivery-fee",
		    displayPrice(adminCurrencySymbol(),$this->standardPrettyFormat($order_info['delivery_charge']))
		    ,$client_sms);			
		    
        }                  
                       
        $sms_provider=Yii::app()->functions->getOptionAdmin('sms_provider');    	    	    	    	
        $sms_provider=strtolower($sms_provider);
                
        /*send SMS to client */
        if ( $sms_enabled_alert==1 && !empty($client_sms) && !empty($client_contact_phone) ){
        	$balance=$this->getMerchantSMSCredit($merchant_id);	
        	if (is_numeric($balance) && $balance>=1){
        		$resp2=$this->sendSMS($client_contact_phone,$client_sms);
        		$params=array(
	        	  'merchant_id'=>$merchant_id,
	        	  'broadcast_id'=>"999999999",
	        	  'client_id'=>$order_info['client_id'],
	        	  'client_name'=>$order_info['full_name'],
	        	  'contact_phone'=>$client_contact_phone,
	        	  'sms_message'=>$client_sms,
	        	  'status'=>$resp2['msg'],
	        	  'gateway_response'=>$resp2['raw'],
	        	  'date_created'=>FunctionsV3::dateNow(),
	        	  'date_executed'=>FunctionsV3::dateNow(),
	        	  'ip_address'=>$_SERVER['REMOTE_ADDR'],
	        	  'gateway'=>$sms_provider
	        	);	  		        	  
	        	$db_ext->insertData("{{sms_broadcast_details}}",$params);	        	
        	}
        }        
        /*end send sms to client*/
                                  
        if ( $sms_enabled_alert==1 && !empty($sms_notify_number) && !empty($sms_alert_message)) {        	
        	$sms_notify_number_s=explode(",",$sms_notify_number);        	        	
        	if (is_array($sms_notify_number_s) && count($sms_notify_number_s)>=1){
        		$x_counter=1;
        		foreach ($sms_notify_number_s as $sms_notify_number) {        			        		
			        $balance=$this->getMerchantSMSCredit($merchant_id);	        
			        if (is_numeric($balance) && $balance>=1){	        	
			        	//dump($sms_notify_number);
			        	$resp=$this->sendSMS($sms_notify_number,$sms_alert_message);
			        	$params=array(
			        	  'merchant_id'=>$merchant_id,
			        	  'broadcast_id'=>"999999999",
			        	  'client_id'=>$order_info['client_id'],
			        	  'client_name'=>$order_info['full_name'],
			        	  'contact_phone'=>$sms_notify_number,
			        	  'sms_message'=>$sms_alert_message,
			        	  'status'=>$resp['msg'],
			        	  'gateway_response'=>$resp['raw'],
			        	  'date_created'=>FunctionsV3::dateNow(),
			        	  'date_executed'=>FunctionsV3::dateNow(),
			        	  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			        	  'gateway'=>$sms_provider
			        	);	    	        	
			        	$db_ext->insertData("{{sms_broadcast_details}}",$params);	        	
			        	
			        	/*if ( $x_counter==1):
			        	if (!empty($client_sms) && !empty($client_contact_phone)){ 
			        		$resp2=$this->sendSMS($client_contact_phone,$client_sms);
			        		$params=array(
				        	  'merchant_id'=>$merchant_id,
				        	  'broadcast_id'=>"999999999",
				        	  'client_id'=>$order_info['client_id'],
				        	  'client_name'=>$order_info['full_name'],
				        	  'contact_phone'=>$client_contact_phone,
				        	  'sms_message'=>$client_sms,
				        	  'status'=>$resp['msg'],
				        	  'gateway_response'=>$resp['raw'],
				        	  'date_created'=>FunctionsV3::dateNow(),
				        	  'date_executed'=>FunctionsV3::dateNow(),
				        	  'ip_address'=>$_SERVER['REMOTE_ADDR'],
				        	  'gateway'=>$sms_provider
				        	);	  		        	  
				        	$db_ext->insertData("{{sms_broadcast_details}}",$params);	        	
			        	} 
			        	endif;*/
			        }
			        $x_counter++;
        		}
        	}	        
        }
    }
    
    public function sendSMS($to='',$message='' , $record_id='')
    {
    	$to=trim($to);
    	$message=trim($message);
    	
    	$msg='';$raw='';
    	$sms_provider=Yii::app()->functions->getOptionAdmin('sms_provider');    	
    	    	    	
    	//if ($sms_provider=="nexmo"){    
    	$sms_provider=strtolower($sms_provider);
    	
    	//dump($sms_provider);
    	        
    	switch ($sms_provider) {
    		
    		case "smsglobal":
    			$abs_smsglobal=new SMSGlobal;
				$abs_smsglobal->_debug=false;
				$abs_smsglobal->_smsuser= getOptionA('smsglobal_username');
				$abs_smsglobal->_smspass= getOptionA('smsglobal_password');
				//$abs_smsglobal->_sms_url="http://www.smsglobal.com/http-api.php";
				$abs_smsglobal->_sms_url="https://api.smsglobal.com/http-api.php";
				$abs_smsglobal->_smssender= getOptionA('smsglobal_senderid');
				if ($resp=$abs_smsglobal->sendSMS_HTTPOST($to,$message)){				
					$msg="process";
				} else $msg=$abs_smsglobal->get_error();			
    			break;
    			
    		case "clickatell":    				    		
	    		try {    			
	    			$api_key=getOptionA('clickatel_api_key');
	    			$use_curl=getOptionA('clickatel_use_curl');
	    			$use_unicode=getOptionA('clickatel_use_unicode');
	    			$raw= ClickatellWrapper::sendSMS($api_key,$to,$message,$use_curl,$use_unicode);
	    			$msg="process";
	    		} catch (Exception $e){
	    			$msg=$e->getMessage();
	    			$raw=$e->getMessage();
	    		}       		 			    	
	    		    		    	
    			break;
    			
    		case "nexmo":
	    		$nexmo_sender_id=Yii::app()->functions->getOptionAdmin('nexmo_sender_id');
	    		$nexmo_key=Yii::app()->functions->getOptionAdmin('nexmo_key');
	    		$nexmo_secret=Yii::app()->functions->getOptionAdmin('nexmo_secret');    		
	    		$nexmo_use_curl=Yii::app()->functions->getOptionAdmin('nexmo_use_curl');  
	    		    		
	    		$Nexmo=new Nexmo;
	    		$Nexmo->key=$nexmo_key;
	    		$Nexmo->secret=$nexmo_secret;
	    		$Nexmo->sender=$nexmo_sender_id;    		
	    		$Nexmo->to=$to;
	    		$Nexmo->message=$message;
	    		$Nexmo->is_curl=$nexmo_use_curl;	    		
	    		$nexmo_use_unicode=Yii::app()->functions->getOptionAdmin('nexmo_use_unicode');
	    		if ( $nexmo_use_unicode==1){
	    			$Nexmo->unicode=true;
	    		}	    		
	    		
	    		try {    			
	    			$raw=$Nexmo->sendSMS();
	    			$msg="process";
	    		} catch (Exception $e){
	    			$msg=$e->getMessage();
	    		}       		 			    	
    	
    		    break;
    		
    		case "private":    			 
    			 $privatesms_username=Yii::app()->functions->getOptionAdmin('privatesms_username');
    			 $privatesms_password=Yii::app()->functions->getOptionAdmin('privatesms_password');
    			 $privatesms_sender=Yii::app()->functions->getOptionAdmin('privatesms_sender');
    			     			 
                 $obj = new Sender("103.16.101.52", "80",$privatesms_username,$privatesms_password,$privatesms_sender,
                 $message, $to, "0", "1");
                 $resp=$obj->Submit();                                     
                                                                    
                   if (preg_match("/1701/i", $resp)) {
			        	$raw=$resp;
			        	$msg="process";
			        } else {
			        	$errors['1702']="Invalid URL Error, This means that one of the parameters was not
			provided or left blank";
			        	$errors['1703']="Invalid value in username or password field";
			        	$errors['1704']='Invalid value in "type" field';
			        	$errors['1705']="Invalid Message";
			        	$errors['1706']="Invalid Destination";
			        	$errors['1707']="Invalid Source (Sender)";
			        	$errors['1708']='Invalid value for "dlr" field';
			        	$errors['1709']="User validation failed";
			        	$errors['1710']="Internal Error";
			        	$errors['1025']="Insufficient Credit";
			        	$resp_temp=explode("|",$resp);	
			        	if (is_array($resp_temp) && count($resp_temp)>=1){
			        		$code_error=$resp_temp[0];
			        	} else $code_error=$resp;			        	
			        	if (array_key_exists($code_error,$errors)){
			        		$msg=$errors[$code_error];
			        	} else $msg="Undefined response from api.";	
			        					        	
			        }        
			                         
    			 break; 
    			 
    		case "bhashsms":	 
    		    $bhashsms_user=Yii::app()->functions->getOptionAdmin('bhashsms_user');
    		    $bhashsms_pass=Yii::app()->functions->getOptionAdmin('bhashsms_pass');
    		    $bhashsms_senderid=Yii::app()->functions->getOptionAdmin('bhashsms_senderid');
    		    $bhashsms_smstype=Yii::app()->functions->getOptionAdmin('bhashsms_smstype');
    		    $bhashsms_priority=Yii::app()->functions->getOptionAdmin('bhashsms_priority');
    		    $bhashsms_use_curl=Yii::app()->functions->getOptionAdmin('bhashsms_use_curl');
    		        		        		    
    		    $Bhashsms=new Bhashsms;
    		    $Bhashsms->user=$bhashsms_user;
    		    $Bhashsms->password=$bhashsms_pass;
    		    $Bhashsms->sender=$bhashsms_senderid;
    		    $Bhashsms->sms_type=$bhashsms_smstype;
    		    $Bhashsms->priority=$bhashsms_priority;    		    
    		    $Bhashsms->to=$to;
	    		$Bhashsms->message=$message;	    		
	    		$Bhashsms->is_curl=$bhashsms_use_curl==1?true:false;
    		    
	    		try {    			
	    			$raw=$Bhashsms->sendSMS();
	    			$msg="process";
	    		} catch (Exception $e){
	    			$msg=$e->getMessage();
	    		}       
    		    
    		    break;
    		    
    		    
    		case "swift":    
    		    $SwiftSMS=new SwiftSMS();
    		    $SwiftSMS->message=$message;
    		    $SwiftSMS->to=$to;
    		    $SwiftSMS->account_key=getOptionA('swift_accountkey');
    		    $SwiftSMS->is_curl=getOptionA('swift_usecurl')==2?true:false;
    		    try {    			
	    			$raw=$SwiftSMS->sendSMS();
	    			$msg="process";
	    		} catch (Exception $e){
	    			$msg=$e->getMessage();
	    		}     
    		    break;
    		
    		case "solutionsinfini":
    			$SMSinf=new SolutionsinfiniSMS;
    			$SMSinf->message=$message;
    			$SMSinf->to=$to;
    			$SMSinf->api_key=getOptionA('solutionsinfini_apikey');
    			$SMSinf->sender=getOptionA('solutionsinfini_sender');
    			$SMSinf->is_curl=getOptionA('solutionsinfini_usecurl')==2?true:false;
    			$SMSinf->unicode=getOptionA('solutionsinfini_useunicode')==2?true:false;
    			try {    			
	    			$raw=$SMSinf->sendSMS();
	    			$msg="process";
	    		} catch (Exception $e){
	    			$msg=$e->getMessage();
	    		}     
    		    break;
    		    
    		    
    		case "plivo":   
    		    		    
    		   $Plivo=new Plivo;
    		   $Plivo->auth_id=getOptionA('plivo_auth_id');
    		   $Plivo->auth_token=getOptionA('plivo_auth_token');
    		   $Plivo->sender=getOptionA('plivo_sender_number');
    		   $Plivo->message=$message;
    		   $Plivo->to=$to;
    		   try {    		   	 
    		   	  $raw=$Plivo->sendSMS();    		   	  
    		   	  $msg="process";
    		   } catch (Exception $e){
    		   	  $msg=$e->getMessage();
    		   }    		   
    		
    		   break;
    		   
    		case "msg91":    	    		   
    		  /* $msg_resp=Msg91::sendSMS(
    		      getOptionA('msg91_authkey'),
    		      $to,
    		      getOptionA('msg91_senderid'),
    		      $message,
    		      getOptionA('msg91_unicode'),
    		      !empty(getOptionA('msg91_route'))?getOptionA('msg91_route'):'default'
    		   );*/
    		   $msg91_route=getOptionA('msg91_route');
    		   if(empty($msg91_route)){
    		   	  $msg91_route='default';
    		   }
    		   $msg_resp=Msg91::sendSMS(
    		      getOptionA('msg91_authkey'),
    		      $to,
    		      getOptionA('msg91_senderid'),
    		      $message,
    		      getOptionA('msg91_unicode'),
    		      $msg91_route
    		   );
    		   if($msg_resp){
    		   	  $msg="process";
    		   	  $raw=$msg_resp;
    		   } else $msg=Msg91::$msg;
    		   break;
    			    
    		case "twilio":
    				    	
    			require_once "TwilioWrapper.php";	
    			$sms_sender_id=getOptionA('sms_sender_id');
				$sms_account_id=getOptionA('sms_account_id');
				$sms_token=getOptionA('sms_token');
				try {					
					TwilioWrapper::setCredentials($sms_account_id,$sms_token);
					$resp = TwilioWrapper::sendSMS(array(
					  'To'=>$to,
					  'From'=>$sms_sender_id,
					  'Body'=>$message
					));
					$msg="process";
					$raw = $resp;
				} catch (Exception $e){
    				$msg  = $e->getMessage();    				
    			}		    	
			    break;		
			    
    		case "spothit":
    			$spothit_apikey=getOptionA('spothit_apikey');
    			$spothit_sms_type=getOptionA('spothit_sms_type');
    			$spothit_sender=getOptionA('spothit_sender');
    			$spothit_truncated=getOptionA('spothit_truncated');
    			$spothit_use_curl=getOptionA('spothit_use_curl');
    			if ($spot_resp=SpotHit::sendSMS($to,$message, $spothit_apikey,$spothit_sms_type,$spothit_sender,
    			    $spothit_truncated,$spothit_use_curl)){
    				$raw=$spot_resp;				
					$msg="process";
    			} else $msg=SpotHit::$msg;
    			break;		
    			
    		case "libonet":	
    		   $Libonet=new Libonet(
    		     getOptionA('libonet_username'),
    		     getOptionA('libonet_password'),
    		     getOptionA('libonet_sender'),
    		     getOptionA('libonet_use_curl'),
    		     getOptionA('libonet_use_unicode')
    		   );
    		   if ($libonet_res=Libonet::sendSMS($to,$message)){
    		   	   $raw=$libonet_res;				
				   $msg="process";
    		   } else $msg=Libonet::$msg;
    		   break;		
    		   
    		case "hubtel":   
    		
    		   $Hubtel=new Hubtel(getOptionA("hubtel_username"),getOptionA("hubtel_password"));
    		   if ( $hubtel_res = $Hubtel->sendSMS( getOptionA('hubtel_sender'),$to,$message , 
    		       getOptionA('hubtel_flashmessage') )){   
    		   	   $raw=$hubtel_res;				
				   $msg="process";				   
    		   } else $msg = $Hubtel->getMessage();
    				
    		   break;
    		   
    		   
    		case "infobip":
    			$info_params = array(
    			  'details'=>array(
    			      'to'=>str_replace("+",'',$to),
    			      'message'=>$message
    			  ),
    			  'credetials'=>array(
    			     'username'=>getOptionA('infobip_username'),
    			     'password'=>getOptionA('infobip_password'),
    			     'senderid'=>getOptionA('infobip_senderid'),
    			     'use_curl'=>getOptionA('infobip_use_curl'),
    			     //'use_unicode'=>getOptionA('infobip_use_unicode'),
    			     'use_unicode'=>''
    			  )    			 
    			);
    			
    			try {
    				$raw = InfobipWrapper::sendSMS($info_params);
    				$msg ="process";
    			} catch (Exception $e){
    				$msg  = $e->getMessage();
    			}
    			break;
    			    			    		       	
		    default:
		       $msg="No sms gateway selected";
			   break;		
			    	
    	}
		
    	/*logs*/
    	$params_logs=array(
    	  'contact_phone'=>$to,
    	  'sms_message'=>$message,
    	  'status'=>$msg,
    	  'gateway_response'=>$raw,
    	  'gateway'=>$sms_provider,
    	  'date_created'=>FunctionsV3::dateNow(),
    	  'date_executed'=>FunctionsV3::dateNow(),
    	  'ip_address'=>$_SERVER['REMOTE_ADDR']
    	);
    	//dump($params_logs); die();
    	    	
    	$db=new DbExt;
    	if($record_id>0){
    		$params_logs=array(
    		  'status'=>$msg,
    	      'gateway_response'=>$raw,
    	      'date_executed'=>FunctionsV3::dateNow(),
    	      'gateway'=>$sms_provider,
    	      'ip_address'=>$_SERVER['REMOTE_ADDR']
    		);
    		$db->updateData("{{sms_broadcast_details}}",$params_logs,'id', $record_id);
    	} else $db->insertData("{{sms_broadcast_details}}",$params_logs);    	
    	unset($db);    	
    	
		return array(
		  'msg'=>$msg,
		  'raw'=>$raw,
		  'sms_provider'=>$sms_provider
		);
    }
    
    public function UserStatus()
    {
    	return array(
    	  'active'=>Yii::t("default",'active'),	 
		  'pending'=>Yii::t("default",'pending for approval'),		 
		  'suspended'=>Yii::t("default",'suspended'),
		  'blocked'=>Yii::t("default",'blocked')		 
		);
    }
    
    public function getMerchantUserInfo($merchant_user_id='')
    {
    	$mid=$this->getMerchantID();
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM
    	{{merchant_user}}
    	WHERE
    	merchant_user_id=".Yii::app()->db->quoteValue($merchant_user_id)."
    	AND
    	merchant_id='$mid'
    	LIMIT 0,1
    	";    	
    	if ($res=$db_ext->rst($stmt)){
    		return $res[0];
    	}
    	return false;
    }
        
    public function validateMerchantUSername($username='')
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM
    	{{merchant_user}}
    	WHERE
    	username=".Yii::app()->db->quoteValue($username)."
    	LIMIT 0,1
    	";
    	if ($res=$db_ext->rst($stmt)){
    		return $res[0];
    	}
    	return false;
    }
    
    public function voucherType()
    {
    	return array(
    	  'fixed amount'=>Yii::t("default","fixed amount"),
    	  'percentage'=>Yii::t("default","percentage")
    	);
    }
    
    public function voucherDetailsByIdWithClient($voucher_id='')
    {
    	$mtid=Yii::app()->functions->getMerchantID();
    	$db_ext=new DbExt;    	
    	$stmt="SELECT a.*,b.merchant_id,
               (
				select concat(first_name,' ',last_name)
				from
				{{client}}
				where
				client_id=a.client_id
			   ) as fullname			   			   
			   
    	       FROM
    	       {{voucher_list}} a
    	       
    	       left join {{voucher}} b
               ON 
               a.voucher_id=b.voucher_id
    	       
    	       WHERE
    	       a.voucher_id= ".FunctionsV3::q($voucher_id)."
    	       AND
    	       b.merchant_id= ".FunctionsV3::q($mtid)."
    	       ORDER BY voucher_code ASC
    	";    	    	
    	$_SESSION['export_stmt']=$stmt;
    	if ($res=$db_ext->rst($stmt)){
    		return $res;
    	}
    	return false;
    }            
    
    public function getVoucherCodeById($voucher_id='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="SELECT * FROM
    	       {{voucher}}
    	       WHERE
    	       voucher_id=".FunctionsV3::q($voucher_id)."
    	       LIMIT 0,1
    	";    	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;
    } 
        
    public function getVoucherCode($voucher_code='',$merchant_id='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="SELECT a.*,
    	       b.merchant_id,
    	       b.voucher_type, b.amount
    	       FROM
    	       {{voucher_list}} a
    	       left join {{voucher}} b
               ON 
               a.voucher_id=b.voucher_id
               
    	       WHERE
    	       a.voucher_code= ".FunctionsV3::q($voucher_code)."
    	       AND
    	       b.merchant_id= ".FunctionsV3::q($merchant_id)."
    	       AND
    	       b.status IN ('publish')
    	       LIMIT 0,1
    	";    	    	    	
    	if ($res=$db_ext->rst($stmt)){
    		return $res[0];
    	}
    	return false;
    } 
    
    public function updateVoucher($voucher_code='',$client_id='',$order_id='')
    {
    	$params=array(
    	  'voucher_code'=>$voucher_code,
    	  'status'=>"used",
    	  'client_id'=>$client_id,
    	  'date_used'=>FunctionsV3::dateNow(),
    	  'order_id'=>$order_id
    	);
    	$db_ext=new DbExt;    	
    	$db_ext->updateData("{{voucher_list}}",$params,'voucher_code',$voucher_code);
    }    
    
    public function travelMmode()
    {
    	return array(
    	  'DRIVING'=>Yii::t("default","Driving"),
    	  'WALKING'=>Yii::t("default","Walking"),
    	  'BICYCLING'=>Yii::t("default","Bicycling"),
    	  'TRANSIT'=>Yii::t("default","Transit")
    	);
    }
    
    public function getFeaturedMerchant()
    {
    	$page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;    	 
    	$page=$page-1;
    	$limit=FunctionsV3::getPerPage();
    	$start=$page*$limit;
    	
    	$db_ext=new DbExt;    	
    	$db_ext->qry("SET SQL_BIG_SELECTS=1");
    	
    	$stmt=" 
    	SELECT SQL_CALC_FOUND_ROWS a.*,
    	concat(street,' ',city,' ',state,' ',post_code) as merchant_address  
    	FROM
    	{{view_merchant}} a
    	WHERE is_featured='2'
    	AND is_ready ='2'
    	AND status in ('active')
    	ORDER BY sort_featured ASC
    	LIMIT $start,$limit    	
    	";    	      	
    	if ($res=$db_ext->rst($stmt)){
    		$stmt_rows="SELECT FOUND_ROWS()";
			$total_found=0;
			if ($rows=$db_ext->rst($stmt_rows)){
				$total_found=$rows[0]['FOUND_ROWS()'];
			}    					
    		return array(
    		  'total'=>$total_found,
    		  'list'=>$res
    		);
    	}
    	return false;
    }     
    
    public function getAllMerchant($is_all=false)
    {
    	$page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;    	 
    	$page=$page-1;
    	if ($is_all){
    		$limit=1500;
    	} else $limit=FunctionsV3::getPerPage();
    	
    	$start=$page*$limit;
    	
    	$db_ext=new DbExt;    	
    	$db_ext->qry("SET SQL_BIG_SELECTS=1");
    	
    	$sortby='';
    	$sort_options=getOptionA('browse_page_sort');
    	
    	switch ($sort_options) {
    		case 1:
    			$sortby='ORDER BY RAND()';
    			break;
    			
    	    case 2:
    			$sortby='ORDER BY restaurant_name ASC';
    			break;
    			
    		case 3:
    			$tbl='view_merchant';
    			$sortby='ORDER BY ratings DESC';
    			break;	
    			
    		default:
    			$sortby='ORDER BY membership_expired DESC';
    			break;
    	}
    	
    	$stmt="SELECT SQL_CALC_FOUND_ROWS a.*,
    	concat(street,' ',city,' ',state,' ',post_code) as merchant_address  
    	 FROM
    	{{view_merchant}} a    	
    	WHERE is_ready ='2'
    	AND status in ('active')
    	$sortby
    	LIMIT $start,$limit    	
    	";     	    	
    	
    	if ($res=$db_ext->rst($stmt)){
    		$stmt_rows="SELECT FOUND_ROWS()";
			$total_found=0;
			if ($rows=$db_ext->rst($stmt_rows)){
				$total_found=$rows[0]['FOUND_ROWS()'];
			}    		
    		return array(
    		  'total'=>$total_found,
    		  'list'=>$res
    		);
    	}
    	return false;
    }         
    
    public function getAllMerchantNewest()
    {    	
    	$date_now=date('Y-m-d 23:00:59');
	    $start_date=date('Y-m-d 00:00:00',strtotime($date_now . "-30 days"));
	    //$start_date=date('Y-m-d 00:00:00',strtotime($date_now . "-1000 days"));
    	    	
    	$db_ext=new DbExt;    
    	$db_ext->qry("SET SQL_BIG_SELECTS=1");
    	
    	$page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;    	 
    	$page=$page-1;
    	$limit=FunctionsV3::getPerPage();
    	$start=$page*$limit;
    		    	
    	$stmt="SELECT SQL_CALC_FOUND_ROWS a.*,
    	concat(street,' ',city,' ',state,' ',post_code) as merchant_address  	
    	 FROM
    	{{view_merchant}} a    	
    	WHERE is_ready ='2'
    	AND status in ('active')
    	AND date_created BETWEEN '$start_date' AND '$date_now'
    	ORDER BY membership_expired DESC
    	LIMIT $start,$limit    	
    	";    	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		$stmt_rows="SELECT FOUND_ROWS()";
			$total_found=0;
			if ($rows=$db_ext->rst($stmt_rows)){
				$total_found=$rows[0]['FOUND_ROWS()'];
			}    		
    		return array(
    		  'total'=>$total_found,
    		  'list'=>$res
    		);
    	}
    	return false;
    }             
    
    public function getAdminCountrySet($code=false)
    {    	
    	$admin_country_set=Yii::app()->functions->getOptionAdmin('admin_country_set');
    	if ( $code==true){
    		return $admin_country_set;
    	}
		return $this->countryCodeToFull($admin_country_set);    	
    }
    
    public function countryCodeToFull($code='')
    {    	
    	$country_list=$this->CountryList();
    	if (array_key_exists($code,(array)$country_list)){
    		return $country_list[$code];
    	}
    	return '';
    }
    
    public function getSMSTransaction($id='')
    {
    	$db_ext=new DbExt;    	
		$stmt="SELECT a.*,
		(
		select restaurant_name
		from
		{{merchant}} 
		where
		merchant_id=a.merchant_id
		) merchant_name,
		
		(
		select title
		from
		{{sms_package}}
		where
		sms_package_id=a.sms_package_id
		) sms_package_name
		
		 FROM
		{{sms_package_trans}} a
		WHERE
		id= ".FunctionsV3::q($id)."
		LIMIT 0,1
		";	    	
    	if ($res=$db_ext->rst($stmt)){
    		return $res[0];
    	}
    	return false;
    }
    
    public function distanceOption()
    {
    	return array(
    	   'mi'=>Yii::t("default","Miles"),
    	   'km'=>Yii::t("default","Kilometers")
    	);
    }    
    
    public function validateMerchantUser($username='',$merchant_id='')
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM
    	{{merchant_user}}
    	WHERE
    	username=".Yii::app()->db->quoteValue($username)."
    	AND
    	merchant_id <>'$merchant_id'
    	LIMIT 0,1
    	";    	
    	if ($res=$db_ext->rst($stmt)){
    		return $res[0];
    	}
    	return false;
    }    
    
    public function validateMerchantEmail($email='',$merchant_id='')
    {
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM
    	{{merchant}}
    	WHERE
    	contact_email=".Yii::app()->db->quoteValue($email)."
    	AND
    	merchant_id <>'$merchant_id'
    	LIMIT 0,1
    	";    	
    	if ($res=$db_ext->rst($stmt)){
    		return $res[0];
    	}
    	return false;
    }        
    
    public function getLastIncrement($table_name='')
    {
    	$stmt="show table status like ".FunctionsV3::q($table_name)." ";    	   
    	$db_ext=new DbExt;
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res[0]['Auto_increment'];
    	}    	    	
    	return false;
    }
    
    public function getSMSPackagesList($price=false)
	{
		$and='';
		if ($price){
			$and=" AND price >0 ";
		}	
		$data_feed='';
		$stmt="
		SELECT * FROM
		{{sms_package}}		
		WHERE
		status='publish'
		$and
		ORDER BY sequence ASC
		";						
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 				
		if (is_array($rows) && count($rows)>=1){
			if ($this->data=="list"){
				foreach ($rows as $val) {									   
				   $data_feed[$val['sms_package_id']]=ucwords($val['title']);
				}
				return $data_feed;
			} else return $rows;
		}
		return FALSE;
	}        	    
	
	public function timezoneList()
	{		
		$version=phpversion();				
		if ($version<=5.2){
			return Widgets::timezoneList();
		}		
		$list['']=Yii::t("default",'Please Select');
		$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
		if (is_array($tzlist) && count($tzlist)>=1){
			foreach ($tzlist as $val) {
				$list[$val]=$val;
			}
		}			
		return $list;		
	}
	
	public function isMerchantOpen($mtid='')
	{
	   //http://stackoverflow.com/questions/14904864/determine-if-business-is-open-closed-based-on-business-hours
	   //http://php.net/manual/en/class.datetime.php
	   
	   if (!empty($mtid)){
	   	   $timezone=Yii::app()->functions->getOption("merchant_timezone",$mtid);	   	   
	   	   if (!empty($timezone)){	   	   
	   	       date_default_timezone_set($timezone);	   	   
	   	   }	   	   
	   	   
	   	   $times=$this->getBusinnesHours($mtid);
	   	   if (empty($times)){
	   	   	  return true;
	   	   	  
	   	   }
	   	   /*dump(Yii::app()->timeZone);
	   	   echo FunctionsV3::dateNow();*/
	   	   //dump($times);
							   	   
			$now = strtotime(date("h:ia"));
			$open = $this->isOpen($now, $times);
	
			if ($open == 0) {
				//echo "is close";
			    return false;
			} else {
			    //echo "Is open. Will close in ".ceil($open/60)." minutes";
			    return true;
			}	   	   
	   }
	   return false;	   	
	} 
		
	function compileHours($times, $timestamp) {
	    //$times = $times[strtolower(date('D',$timestamp))];
	    $times = isset($times[strtolower(date('D',$timestamp))])?$times[strtolower(date('D',$timestamp))]:'';	
	    if(!strpos($times, '-')) return array();
	    $hours = explode(",", $times);
	    $hours = array_map('explode', array_pad(array(),count($hours),'-'), $hours);
	    $hours = array_map('array_map', array_pad(array(),count($hours),'strtotime'), $hours, array_pad(array(),count($hours),array_pad(array(),2,$timestamp)));
	    end($hours);
	    if ($hours[key($hours)][0] > $hours[key($hours)][1]) $hours[key($hours)][1] = strtotime('+1 day', $hours[key($hours)][1]);
	    return $hours;
	}
	
	function isOpen($now, $times) {
	    $open = 0;
	    $hours = array_merge($this->compileHours($times, strtotime('yesterday',$now)),$this->compileHours($times, $now)); 		
	    foreach ($hours as $h) {
	        if ($now >= $h[0] and $now < $h[1]) {
	            $open = $h[1] - $now;
	            return $open;
	        } 
	    }
	    return $open;
	}	

	public function getBusinnesHours($merchant_id='')
	{
		$stores_open_day=Yii::app()->functions->getOption("stores_open_day",$merchant_id);
		$stores_open_day=Yii::app()->functions->getOption("stores_open_day",$merchant_id);
		$stores_open_starts=Yii::app()->functions->getOption("stores_open_starts",$merchant_id);
		$stores_open_ends=Yii::app()->functions->getOption("stores_open_ends",$merchant_id);
		$stores_open_custom_text=Yii::app()->functions->getOption("stores_open_custom_text",$merchant_id);
		
		$stores_open_day=!empty($stores_open_day)?(array)json_decode($stores_open_day):false;
		$stores_open_starts=!empty($stores_open_starts)?(array)json_decode($stores_open_starts):false;
		$stores_open_ends=!empty($stores_open_ends)?(array)json_decode($stores_open_ends):false;
				
		
		$stores_open_pm_start=Yii::app()->functions->getOption("stores_open_pm_start",$merchant_id);
		$stores_open_pm_start=!empty($stores_open_pm_start)?(array)json_decode($stores_open_pm_start):false;
		
		$stores_open_pm_ends=Yii::app()->functions->getOption("stores_open_pm_ends",$merchant_id);
		$stores_open_pm_ends=!empty($stores_open_pm_ends)?(array)json_decode($stores_open_pm_ends):false;		
		
		$business_hours=array();
		if (is_array($stores_open_day) && count($stores_open_day)>=1){
		   foreach ($stores_open_day as $days) {		   	  
		   	  $days1=substr($days,0,3);
		   	  $start=''; $end='';
		   	  if (array_key_exists($days,$stores_open_starts)){		   	 
		   	  	 if (!empty($stores_open_starts[$days])){
		   	  	     $start=date("h:i A",strtotime($stores_open_starts[$days]));
		   	  	 }
		   	  }
		   	  if (array_key_exists($days,(array)$stores_open_ends)){		 
		   	  	 if (!empty($stores_open_ends[$days]))  	  {
		   	  	     $end=date("h:i A",strtotime($stores_open_ends[$days]));
		   	  	 }
		   	  }
		   	  
		   	  $pm_starts=''; $pm_ends='';
		   	  if (array_key_exists($days,(array)$stores_open_pm_start)){		   	  
		   	  	if (!empty($stores_open_pm_start[$days])){
		   	  	    $pm_starts=date("h:i A",strtotime($stores_open_pm_start[$days]));
		   	  	}
		   	  }
		   	  if (array_key_exists($days,(array)$stores_open_pm_ends)){		   	  
		   	  	 if (!empty($stores_open_pm_ends[$days])){
		   	  	    $pm_ends=date("h:i A",strtotime($stores_open_pm_ends[$days]));
		   	  	 }
		   	  }
		   	  			   	  	   	 
		   	  if (!empty($start) && !empty($end)){		   	  	
		   	  	  $business_hours[$days1]="$start - $end";
		   	  }
		   	  if (!empty($pm_starts) && !empty($pm_ends)){
		   	  	  $business_hours[$days1].=",$pm_starts - $pm_ends";
		   	  }
		   }
		}							
				
		if (is_array($business_hours) && count($business_hours)>=1){
			return $business_hours;
		} else return false;
	}
	
	public function getBooking($booking_id='')
    {
    	$mtid=$this->getMerchantID();
    	$stmt="
    	SELECT * FROM
    	{{bookingtable}}
    	WHERE
    	booking_id= ".FunctionsV3::q($booking_id)."
    	AND
    	merchant_id =".Yii::app()->db->quoteValue($mtid)."
    	LIMIT 0,1
    	";       	
    	$db_ext=new DbExt;
    	//dump($stmt);
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res[0];
    	}    	    	
    	return false;
    }
    
    public function dateTranslation()
    {
    	return array(
    	  'January'=>Yii::t("default","January"),
    	  'February'=>Yii::t("default","February"),
    	  'March'=>Yii::t("default","March"),
    	  'April'=>Yii::t("default","April"),
    	  'May'=>Yii::t("default","May"),
    	  'June'=>Yii::t("default","June"),
    	  'July'=>Yii::t("default","July"),
    	  'August'=>Yii::t("default","August"),
    	  'September'=>Yii::t("default","September"),
    	  'October'=>Yii::t("default","October"),
    	  'November'=>Yii::t("default","November"),
    	  'December'=>Yii::t("default","December"),
    	  'Jan'=>Yii::t("default","Jan"),
    	  'Feb'=>Yii::t("default","Feb"),
    	  'Mar'=>Yii::t("default","Mar"),
    	  'Apr'=>Yii::t("default","Apr"),
    	  'May'=>Yii::t("default","May"),
    	  'Jun'=>Yii::t("default","Jun"),
    	  'Jul'=>Yii::t("default","Jul"),
    	  'Aug'=>Yii::t("default","Aug"),
    	  'Sep'=>Yii::t("default","Sep"),
    	  'Oct'=>Yii::t("default","Oct"),
    	  'Nov'=>Yii::t("default","Nov"),
    	  'Dec'=>Yii::t("default","Dec"),
    	  'Sunday'=>t("Sunday"),
    	  'Monday'=>t("Monday"),
    	  'Tuesday'=>t("Tuesday"),
    	  'Wednesday'=>t("Wednesday"),
    	  'Thursday'=>t("Thursday"),
    	  'Friday'=>t("Friday"),
    	  'Saturday'=>t("Saturday"),
    	  'Sun'=>Yii::t("default","Sun"),
    	  'Mon'=>Yii::t("default","Mon"),
    	  'Tue'=>Yii::t("default","Tue"),
    	  'Wed'=>Yii::t("default","Wed"),
    	  'Thu'=>Yii::t("default","Thu"),
    	  'Fri'=>Yii::t("default","Fri"),
    	  'Sat'=>Yii::t("default","Sat"),
    	  'Su'=>Yii::t("default","Su"),
    	  'Mo'=>Yii::t("default","Mo"),
    	  'Tu'=>Yii::t("default","Tu"),
    	  'We'=>Yii::t("default","We"),
    	  'Th'=>Yii::t("default","Th"),
    	  'Fr'=>Yii::t("default","Fr"),
    	  'Sa'=>Yii::t("default","Sa"),
    	  
    	  'days'=>Yii::t("default","days"),
    	  'day'=>Yii::t("default","day"),    	  
    	  'week'=>Yii::t("default","week"),
    	  'weeks'=>Yii::t("default","weeks"),
    	  'months'=>Yii::t("default","months"),
    	  'month'=>Yii::t("default","month"),    	  
    	  'ago'=>Yii::t("default","ago"),
    	  'In'=>Yii::t("default","In"),
    	  'minute'=>Yii::t("default","minute"),
    	  'hour'=>Yii::t("default","hour"),
    	);
    }
    
    public function translateDate($date='')
    {    	    	
    	$translate=$this->dateTranslation();    	
    	foreach ($translate as $key=>$val) {    		
    		$date=str_replace($key,$val,$date);
    	}
    	return $date;
    }
    
    public function newTableBooking($viewed='')
    {
    	$merchant_id=Yii::app()->functions->getMerchantID();	   
    	$and='';    	
    	$db_ext=new DbExt;    	
    	$stmt="
    	      SELECT * FROM
    	      {{bookingtable}}
    	      WHERE    	          	      
    	      date_created like '".date('Y-m-d')."%'
    	      AND
    	      merchant_id = ".FunctionsV3::q($merchant_id)."
    	      AND
    	      viewed='1'
    	      ORDER BY date_created DESC
    	      LIMIT 0,50
    	";    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res;
    	}
    	return false;
    }           
    
    public function orderStatusList2($aslist=true)
    {
    	$mid=$this->getMerchantID();
    	$list=array();
    	if ($aslist){
    	    $list[]=Yii::t("default","Please select");    	
    	}
    	$db_ext=new DbExt;
    	$stmt="SELECT * FROM 
    	  {{order_status}}     	      	 
    	  ORDER BY stats_id";	    	
    	if ($res=$db_ext->rst($stmt)){
    		foreach ($res as $val) {    			    			
    			//$list[$val['description']]=ucwords($val['description']);
    			$list[$val['description']]=t($val['description']);
    		}
    		return $list;
    	}
    	return false;    
    }        
    
    public function offlineBankDeposit($merchant='',$data='')
    {    
    	    	
    	if (isset($_REQUEST['renew'])){ 
	    	$package_price=0;
	    	$membership_expired='';
	    	$membership_info=Yii::app()->functions->upgradeMembership($merchant['merchant_id'],$data['package_id']);
	    	$merchant_email=$merchant['contact_email'];
	    	$package_id=$data['package_id'];
	    	
	    	if (is_array($membership_info) && count($membership_info)>=1){
    		   $package_price=$membership_info['package_price'];
    		   $membership_expired=$membership_info['membership_expired'];
    	    }    	
	    	
    	} else {
    		$merchant_email=$merchant['contact_email'];
    		$package_id=$merchant['package_id'];
    		$package_price=$merchant['package_price'];
    		$membership_expired=$merchant['membership_expired'];
    	}

    	   	
    	$subject=Yii::app()->functions->getOptionAdmin('admin_deposit_subject');
    	$from=Yii::app()->functions->getOptionAdmin('admin_deposit_sender');
    	
    	if (empty($from)){
    	    $from='no-reply@'.$_SERVER['HTTP_HOST'];
    	}
    	if (empty($subject)){
    	    $subject=Yii::t("default","Bank Deposit instructions");
    	}
    	    	
    	$to=$merchant_email; 
    	    	
    	$link= FunctionsV3::getHostURL().Yii::app()->createUrl('store/bankdepositverify',array(
    	  'ref'=>$merchant['activation_token']
    	));
    	$links="<a href=\"$link\" target=\"_blank\" >".Yii::t("default","Click on this link")."</a>";
    	$tpl=Yii::app()->functions->getOptionAdmin('admin_deposit_instructions');
    	if (!empty($tpl)){    	   
    	   $tpl=$this->smarty('amount',
    	   $this->adminCurrencySymbol().$this->standardPrettyFormat($package_price),$tpl);
    	   $tpl=$this->smarty('verify-payment-link',$links,$tpl);
    	   if ($this->sendEmail($to,$from,$subject,$tpl)){
    	   	   $params=array('payment_steps'=>3);
    	   	   $db_ext=new DbExt;
    	   	   $db_ext->updateData("{{merchant}}",$params,'merchant_id',$merchant['merchant_id']);
    	   	   
    	   	   $params2=array(
    	   	     'package_id'=>$package_id,
    	   	     'merchant_id'=>$merchant['merchant_id'],
    	   	     'price'=>$package_price,
    	   	     'payment_type'=>'obd',
    	   	     'membership_expired'=>$membership_expired,
    	   	     'date_created'=>FunctionsV3::dateNow(),
    	   	     'ip_address'=>$_SERVER['REMOTE_ADDR']
    	   	   );
    	   	   $db_ext->insertData("{{package_trans}}",$params2);
    	   	   return true;
    	   }	    	   	
    	}
    	return false;
    }
    
    public function getMerchantListOfPaymentGateway()
    {
    	$db_ext=new DbExt;
    	$paymentgateway=$this->getOptionAdmin('paymentgateway');
		if (!empty($paymentgateway)){
			$paymentgateway=json_decode($paymentgateway,true);
		} else {
			$stmt="SELECT * FROM
			{{option}}
			WHERE
			option_name='paymentgateway'
			";
			if ( $db_ext->rst($stmt)){
				$paymentgateway=array();
			} else {
			   $paymentgateway=array('pyp','stp','mcd','ide','payu','pys');
			}
		}
		return $paymentgateway;
    }

    public function currencyPosition()
    {
    	return array(
    	   'left'=>t("Left"),
    	   'right'=>t("Right"),
    	);
    }  
    
    public function displayPrice($currency='',$amount='')
    {    	
    	$spacer="";
    	$spacer_enabled=getOptionA("admin_add_space_between_price");
    	if($spacer_enabled==1){
    		$spacer=" ";
    	}
    	$pos=Yii::app()->functions->getOptionAdmin('admin_currency_position');    	
    	if ( $pos=="right"){
    		return $amount.$spacer.$currency;
    	} else {    		
    		return $currency.$spacer.$amount;
    	}
    }
    
    public function bookedAvailable($merchant_id='')
    {    	
    	//dump($_POST);
    	//$day_now=strtolower(date('l'));		
    	if (isset($_POST['date_booking'])){
    		$day_now=strtolower(date("l",strtotime($_POST['date_booking'])));
    		$datenow=date("Y-m-d",strtotime($_POST['date_booking']));
    	} else {
    		$datenow=date('Y-m-d');
    		$day_now=strtolower(date('l'));   
    	}    	
    	
    	$max_booking=0;    	
		
		$max_booked=Yii::app()->functions->getOption("max_booked",$merchant_id);		
		if (!empty($max_booked)){
			$max_booked=json_decode($max_booked,true);			
			if (isset($max_booked[$day_now])){
				$max_booking=$max_booked[$day_now];
			}				
		}
								
		$total_book_today=0;
		
		$db_ext=new DbExt;
		$stmt="
		SELECT COUNT(*) as total
		FROM {{bookingtable}}
		WHERE
		date_booking like ".FunctionsV3::q("$datenow%")."
		AND status in ('pending','approved')
		";
		
		if ( $res=$db_ext->rst($stmt)){			
			$total_book_today=$res[0]['total'];
		}	
							
		if ($max_booking>=1){
			if ($total_book_today>=$max_booking){				
				return false;
			}	
		}		
		return true;
    }
    
    
	public function CountryListMerchant()
	{
		  $country_list=$this->CountryList();
		  $merchant_default_country=Yii::app()->functions->getOptionAdmin('merchant_default_country');  
		  $merchant_specific_country=Yii::app()->functions->getOptionAdmin('merchant_specific_country');
		  if (!empty($merchant_specific_country)){
			$merchant_specific_country=json_decode($merchant_specific_country);
		  }    
		  if (is_array($merchant_specific_country) && count($merchant_specific_country)>=1){  	  
		  	 $country_list_tem=$country_list;
		  	  $country_list=array();  	
		  	  foreach ($country_list_tem as $c_key=>$c_value) {  	  	   	  	 
		  	  	 if ( in_array($c_key,$merchant_specific_country)){
		  	  	 	$country_list[$c_key]=$c_value;
		  	  	 }
		  	  }
		  }  
		  return $country_list;
	}
	
	public function getWebsiteName()
	{
		return $this->getOptionAdmin('website_title');
	}
	
	public function getMerchantHoliday($merchant_id='')
	{
		$merchant_holiday=Yii::app()->functions->getOption("merchant_holiday",$merchant_id);
		if (!empty($merchant_holiday)){
			$merchant_holiday=json_decode($merchant_holiday,true);
			if (is_array($merchant_holiday) && count($merchant_holiday)>=1){
			   return $merchant_holiday;	
			}			
		}		
		return false;
	}
	
	public function isAdminExist($contact_email='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{admin_user}}
		WHERE
		email_address= ".FunctionsV3::q($contact_email)."
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res;
		}
		return false;
	}	
	
	public function updateMerchantToken($merchant_id='')
	{
		$token=$this->generateRandomKey();
		$token=md5($token);
		$DbExt=new DbExt;
		$params=array('activation_token'=>$token);
		if ( $DbExt->updateData("{{merchant}}",$params,'merchant_id',$merchant_id)){
			return $token;
		}
		return false;
			
	}
	
	public function getPaymentProvider($id='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{payment_provider}}
		WHERE
		id= ".FunctionsV3::q($id)."
		LIMIT 0,1
		";		
		if ($res=$DbExt->rst($stmt)){			
			return $res[0];
		}
		return false;	
	}	
	
	public function getPaymentProviderList()
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{payment_provider}}
		ORDER BY sequence ASC	
		";		
		if ($res=$DbExt->rst($stmt)){			
			return $res;
		}
		return false;	
	}		
	
	public function getPaymentProviderListActive()
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{payment_provider}}
		WHERE
		status IN ('publish','published')
		ORDER BY sequence ASC	
		";		
		if ($res=$DbExt->rst($stmt)){			
			return $res;
		}
		return false;	
	}	

	public function getPaymentProviderMerchant($merchant_id='')
	{
		$provider=array();
		$payondeliver_list=Yii::app()->functions->getOption('payment_provider',$merchant_id);
        if (!empty($payondeliver_list)){
        	$payondeliver_list=json_decode($payondeliver_list);
        	if (is_array($payondeliver_list) && count($payondeliver_list)>=1){
        		foreach ($payondeliver_list as $val) {
        			if ( $res=$this->getPaymentProvider($val)){        			    
        			    $provider[]=array(
        			      'id'=>$res['id'],
        			      'payment_name'=>$res['payment_name'],        			      
        			      'payment_logo'=>$res['payment_logo'],
        			    );
        			}
        		}
        	}	   
        	
        	if (is_array($provider) && count($provider)>=1) {
        		return $provider;
        	}        		
        }	
        return false;        
	}
	
	public function getOffers($offers_id='')
    {
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{offers}}
			WHERE
			offers_id= ".FunctionsV3::q($offers_id)."
			LIMIT 0,1			
		";		
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
    }		
    
    public function getMerchantOffers($merchant_id='',$start='',$end='',$applicable_to='',$id='')
    {
    	$and='';
        if (!empty($id)){ 
      	   $and="AND offers_id !=".$this->q($id)."  ";
        }
        
        $DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{offers}}
			WHERE
			status in ('publish','published')
			AND
			".$this->q($start)." >= valid_from and ".$this->q($end)." <= valid_to
			AND merchant_id =".$this->q($merchant_id)."
			AND applicable_to =".$this->q($applicable_to)."
			$and
			LIMIT 0,1
		";	  	    
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
    }
    
    public function getMerchantOffersActive($merchant_id='')
    {
    	$date_now=date('Y-m-d');
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{offers}}
			WHERE
			status in ('publish','published')
			AND
			now() >= valid_from and now() <= valid_to
			AND merchant_id =".$this->q($merchant_id)."
			LIMIT 0,1
		";	    
		if ( $res=$DbExt->rst($stmt)){			
			return $res[0];
		}
		return false;
    }
    
	public function q($data='')
	{
		return Yii::app()->db->quoteValue($data);
	}	
	
	public function getOrderDiscount($order_id='')
	{
		$DbExt=new DbExt;
		$stmt="
		SELECT discounted_amount,discount_percentage FROM
		{{order}}
		WHERE
		order_id =".$this->q($order_id)."
		LIMIT 0,1
		";			    
		if ( $res=$DbExt->rst($stmt)){			
			return $res[0];
		}
		return false;
	}
	
	public function getMerchantReceiptTemplate($merchant_id='')
	{
		$tpl2=Yii::app()->functions->getOption("merchant_receipt_content",$merchant_id);
		if (empty($tpl2)){	
			$tpl2=EmailTPL::receiptMerchantTPL();
		}
		return $tpl2;
	}
    
	public function getMerchantActivationToken($merchant_id='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT activation_token FROM
		{{merchant}}
		WHERE
		merchant_id=".$this->q($merchant_id)."
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){			
			if ( empty($res[0]['activation_token'])){
				$token=$this->updateMerchantToken($merchant_id);
			} else $token=$res[0]['activation_token'];
			return $token;
		}
		return false;			
	}
	
   public function getSubsriberEmail($email_address='')
    {
    	$db_ext=new DbExt; 
    	$stmt="
    	SELECT * FROM
    	{{newsletter}}
    	WHERE
    	email_address=".$this->q($email_address)."
    	LIMIT 0,1
    	";    	
    	if ( $res=$db_ext->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;    	
    }
    
    public function getFeatureMerchant2()
    {
    	$db_ext=new DbExt; 
    	$stmt="SELECT a.*,  
    	    (
	    	select option_value
	    	from 
	    	{{option}}
	    	WHERE
	    	merchant_id=a.merchant_id
	    	and
	    	option_name='merchant_photo'
	    	limit 0,1
	    	) as merchant_logo
	    	    	
    	    FROM
			{{merchant}} a
			WHERE
			status in ('active')
			AND
			is_featured='2'
			AND
			is_ready='2'
			ORDER BY restaurant_name ASC
			LIMIT 0,20
		";    	
    	//is_sponsored='2'
    	if ( $res=$db_ext->rst($stmt)){    		
    		return $res;
    	}
    	return false;    	
    }
    
    public function barclayGetTransaction($orderid='')
    {
    	$db_ext=new DbExt; 
    	$stmt="SELECT * FROM
    	{{barclay_trans}}
    	WHERE
    	orderid=".$this->q($orderid)."
    	LIMIT 0,1
    	";    	
    	if ( $res=$db_ext->rst($stmt)){
    	    return $res[0];	
    	}	
    	return false;
    }
    
    public function barclayGetTransaction2($orderid='',$trans_type='')
    {
    	$db_ext=new DbExt; 
    	$stmt="SELECT * FROM
    	{{barclay_trans}}
    	WHERE
    	orderid=".$this->q($orderid)."
    	AND
    	transaction_type=".$this->q($trans_type)."
    	LIMIT 0,1
    	";
    	if ( $db_ext->rst($stmt)){
    	    return true;	
    	}	
    	return false;
    }    
    
    public function barclayGetTokenTransaction($token='')
    {
    	$db_ext=new DbExt; 
    	$stmt="SELECT * FROM
    	{{barclay_trans}}
    	WHERE
    	token=".$this->q($token)."    	
    	LIMIT 0,1
    	";
    	/*AND
    	transaction_type=".$this->q($trans_type)."*/
    	if ( $res=$db_ext->rst($stmt)){
    	    return $res[0];	
    	}	
    	return false;
    }        
    
    public function barclaySaveTransaction($orderid='',$token='',$trans='signup',$param1='',$param2='',$param3='')
    {
    	if ( !$this->barclayGetTransaction2($orderid,$trans)){    		
    		$db_ext=new DbExt; 
    		$params=array(
    		  'orderid'=>$orderid,
    		  'token'=>$token,
    		  'transaction_type'=>$trans,
    		  'date_created'=>FunctionsV3::dateNow(),
    		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
    		  'param1'=>isset($param1)?$param1:'',
    		  'param2'=>isset($param2)?$param2:'',
    		  'param3'=>isset($param3)?$param3:'',
    		);
    		$db_ext->insertData("{{barclay_trans}}",$params);
    	}
    }
    
    public function barclayTransactionByOrderId($orderid='')
    {
    	$db_ext=new DbExt; 
    	$stmt="SELECT * FROM
    	{{barclay_trans}}
    	WHERE
    	orderid=".$this->q($orderid)."    	    
    	LIMIT 0,1
    	";
    	if ( $res=$db_ext->rst($stmt)){
    	    return $res[0];	
    	}	
    	return false;
    }        
    
    public function epayBgUpdateTransaction($orderid='',$status='')
    {
    	$db_ext=new DbExt; 
    	/*dump($orderid);
    	dump($status);*/
    	if ( $info=$this->barclayTransactionByOrderId($orderid)){
    		//dump($info);    		    		
    		$res=Yii::app()->functions->getMerchantByToken($info['token']);    	    		    		
    		$package_id=$res['package_id'];
    		
    		switch ($info['transaction_type']) {
    			case "renew":
    			case "signup":	
    			
    			    if ($info['transaction_type']=="renew"){  
    			    	
    			    	$package_id=$info['param1'];    			    	 			    
    			    	if ($new_info=Yii::app()->functions->getPackagesById($package_id)){	      			    		
							$res['package_name']=$new_info['title'];
							$res['package_price']=$new_info['price'];
							if ($new_info['promo_price']>0){
								$res['package_price']=$new_info['promo_price'];
							}			
						}
																		
						$membership_info=Yii::app()->functions->upgradeMembership($res['merchant_id'],$package_id);																	
	    				$params=array(
				          'package_id'=>$package_id,	          
				          'merchant_id'=>$res['merchant_id'],
				          'price'=>$res['package_price'],
				          'payment_type'=>Yii::app()->functions->paymentCode('paysera'),
				          'membership_expired'=>$membership_info['membership_expired'],
				          'date_created'=>FunctionsV3::dateNow(),
				          'ip_address'=>$_SERVER['REMOTE_ADDR'],
				          'PAYPALFULLRESPONSE'=>json_encode($_POST),
				          'TRANSACTIONID'=>$orderid,
				          'TOKEN'=>$orderid
				        );		    			    	
    			    } else {	
	    				$params=array(
				           'package_id'=>$res['package_id'],	          
				           'merchant_id'=>$res['merchant_id'],
				           'price'=>$res['package_price'],
				           'payment_type'=>Yii::app()->functions->paymentCode('epaybg'),
				           'membership_expired'=>$res['membership_expired'],
				           'date_created'=>FunctionsV3::dateNow(),
				           'ip_address'=>$_SERVER['REMOTE_ADDR'],
				           'PAYPALFULLRESPONSE'=>json_encode($_POST),
				           'TRANSACTIONID'=>$orderid,
				           'TOKEN'=>$orderid
					     );			
    			    }
				     $params['status']=strtolower($status);
		    		 /*dump($params);*/
		    		 if (!$this->epayBGIsPaymentExist($orderid)){		    		 	
		    		     $db_ext->insertData("{{package_trans}}",$params);	    		 
		    		 }
    				break;
    		
    			case "order":	    			    			   
    			     $params_logs=array(
			          'order_id'=>$info['token'],
			          'payment_reference'=>$orderid,
			          'payment_type'=>Yii::app()->functions->paymentCode('epaybg'),
			          'raw_response'=>json_encode($_POST),
			          'date_created'=>FunctionsV3::dateNow(),
			          'ip_address'=>$_SERVER['REMOTE_ADDR']
			        );				        			       
			        if(!$this->epayBgValidatePaymentOrder($info['token'],$orderid)){			           
			           $db_ext->insertData("{{payment_order}}",$params_logs);			           
			           $params_update=array('status'=>$status);	        
                       $db_ext->updateData("{{order}}",$params_update,'order_id',$info['token']);  
			        }			       				        				        			        
    			    break;
    			    
    			    
    			case "sms_purchase":    
    			    $payment_reference=$info['orderid'];
    			    $stmt_update="
    			    UPDATE {{sms_package_trans}}
    			    SET status=".strtolower($this->q($status))."
    			    WHERE
    			    payment_reference=".$this->q($payment_reference)."
    			    AND
    			    sms_package_id=".$this->q($info['param1'])."
    			    ";    			    
    			    $db_ext->qry($stmt_update);
    			    break;
    			    
    			default:
    				//echo 'not found';
    				break;
    		}    		
    	}	    	
    }
    
    public function epayBGIsPaymentExist($orderid='',$type="admin")
    {
    	$db_ext=new DbExt; 
    	if ( $type=="admin"){
	    	$stmt="SELECT * FROM
	    	{{package_trans}}
	    	WHERE
	    	TRANSACTIONID=".$this->q($orderid)."
	    	LIMIT 0,1    	
	    	";
    	} else {
    	}	    	
    	if ( $res=$db_ext->rst($stmt)){
    		return true;
    	} 
    	return false;    		    
    }
    
    public function epayBgValidatePaymentOrder($orderid='',$payment_reference='')
    {
    	$db_ext=new DbExt; 
    	$stmt="SELECT * FROM
	    	{{payment_order}}
	    	WHERE
	    	order_id=".$this->q($orderid)."
	    	AND
	    	payment_reference=".$this->q($payment_reference)."
	    	LIMIT 0,1    	
	    ";
    	if ( $res=$db_ext->rst($stmt)){
    		return true;
    	} 
    	return false; 
    }
    
    public function epayBgPaymentRequestType()
    {
    	return array(
    	  'paylogin'=>"paylogin",
    	  'credit_paydirect'=>"credit_paydirect",
    	);
    }
    
    public function epayBgPaymentLanguahe()
    {
    	return array(
    	  'bg'=>"bg",
    	  'en'=>"en",
    	);
    }    
    	    
	function createLogs($response='',$filename=''){    
		$path_to_upload=Yii::getPathOfAlias('webroot')."/upload/logs";
	    if(!file_exists($path_to_upload)) {	
           if (!@mkdir($path_to_upload,0777)){           	    
           	    return ;
           }		    
	    }	   
	    
	    $php_input = @file_get_contents('php://input');
	    
	    $myFile=$path_to_upload;
	    $myFile.= "/$filename-". date("Y-m-d") . '.txt';            
	    $fh = @fopen($myFile, 'a');
	    $stringData='';
	    $stringData .= 'URL=>'.$_SERVER['REQUEST_URI'] . "\n";    
	    $stringData .= 'IP ADDRESS=>'.$_SERVER['REMOTE_ADDR'] . "\n";     
	    $stringData .= 'DATE =>'.date("Y-m-d g:h i") . "\n";     
	    $stringData .= 'POST VAR=>'. json_encode($_POST) . "\n";  
	    $stringData .= 'GET VAR=>'. json_encode($_GET) . "\n";  
	    $stringData .= 'PHP_INPUT VAR=>'. json_encode($php_input) . "\n";  
	    $stringData .= 'RESPONSE =>'. json_encode($response) . "\n";  
	    $stringData .=  "\n"; 
	    fwrite($fh, $stringData);                         
	    fclose($fh); 
	}
	
	public function getMerchantMembershipType()
	{
		if (!empty($_SESSION['kr_merchant_user'])){
			$user=json_decode($_SESSION['kr_merchant_user']);			
			if (is_array($user) && count($user)>=1){				
				return $user[0]->is_commission;
			}
		}
		return false;
	}
	
	public function isMerchantCommission($merchant_id='')
	{
		
		$stmt="
		SELECT * FROM
		{{merchant}}
		WHERE
		merchant_id=".$this->q($merchant_id)."
		LIMIT 0,1
		";		
		if ( $res=$this->db_ext->rst($stmt)){
			/*if ($res[0]['is_commission']==2){
				return true;				
			}*/				
			if ($res[0]['merchant_type']==2 || $res[0]['merchant_type']==3){
				return true;				
			}			
		}
		return false;
	}
	
	public function getMerchantCommission($merchant_id='')
	{				
		$stmt="
		SELECT * FROM
		{{merchant}}
		WHERE
		merchant_id=".$this->q($merchant_id)."
		LIMIT 0,1
		";		
		if ( $res=$this->db_ext->rst($stmt)){
			return $res[0]['percent_commision'];
		}
		return false;
	}
		
   public function merchantList2($as_list=true)
    {
    	$data=array();    	
    	$stmt="SELECT * FROM
    	{{merchant}}
    	WHERE status in ('active')
    	AND
    	is_commission='2'
    	ORDER BY restaurant_name ASC
    	";
    	$data[]=t("All Merchant");
    	if ($res=$this->db_ext->rst($stmt)){    		
    		if ( $as_list==TRUE){
    			foreach ($res as $val) {    				
    			    $data[$val['merchant_id']]=stripslashes($val['restaurant_name']);
    			}
    			return $data;
    		} else return $res;    	
    	}
    	return false;
    }	
    
    public function getTotalCommission()
    {
    	$and = ''; $balance = 0;
    	$status_based = array('paid'); $used_status=''; $and='';
		$status = getOptionA('total_commission_status');
		$include_all_offline_payment = getOptionA('admin_include_all_offline_payment'); 
		
		if($json=json_decode($status,true)){    			
			$status_based = $json;
		}    		
		foreach ($status_based as $val) {
			$used_status.= q($val).",";
		}
		$used_status = substr($used_status,0,-1);
    		
		$offline = "'cod','ocr','pyr','obd'";
		$and = " AND payment_type NOT IN ($offline) ";
		if($include_all_offline_payment==1){
			$and='';
		}
		
    	$range1  = date("Y-m-d");    	
    	$range2 = date('Y-m-d', strtotime($range1 . ' -30 day'));
		$and_today =" AND DATE_FORMAT(date_created,'%Y-%m-%d')  BETWEEN ".FunctionsV3::q($range1)." AND ".FunctionsV3::q($range1)."  ";
		$and_last =" AND DATE_FORMAT(date_created,'%Y-%m-%d')  BETWEEN ".FunctionsV3::q($range2)." AND ".FunctionsV3::q($range1)."  ";
		
    	$stmt="SELECT 
    	IFNULL(sum(total_commission),0) as total_commission,
    	
    	IFNULL((
    	  select sum(total_commission)
    	  from {{order}}
    	  where status IN ($used_status)    
    	  $and $and_today	  
    	),0) as total_today,
    	
    	IFNULL((
    	  select sum(total_commission)
    	  from {{order}}
    	  where status IN ($used_status)    
    	  $and $and_last	  
    	),0) as total_last
    	
    	FROM
    	{{order}}
    	WHERE status IN ($used_status)
    	$and
    	";      	    	
    	if($res = Yii::app()->db->createCommand($stmt)->queryRow()){    		
    		return $res;
    	}
    	return $balance;
    }
    
   public function getTotalCommissionToday()
    {
    	$total_commission_status=Yii::app()->functions->getOptionAdmin('total_commission_status');
		if (!empty($total_commission_status)){
			$total_commission_status=json_decode($total_commission_status);
		} else {
			$total_commission_status=array('paid');
		}    	
    	$status='';
    	if (is_array($total_commission_status) && count($total_commission_status)>=1){
    		foreach ($total_commission_status as $val) {    			
    			$status.="'$val',";
    		}
    		$status=substr($status,0,-1);
    	} else $status="'paid'";
    	    	        
    	$start_date=date("Y-m-d");
    	$end_date=date("Y-m-d");
    	$and=" AND date_created BETWEEN  ".FunctionsV3::q("$start_date 00:00:00")." AND 
	    		        ".FunctionsV3::q("$end_date 23:59:00")."
	    		 ";	    		
    	    	
    	
    	
    	if ( Yii::app()->functions->getOptionAdmin('admin_exclude_cod_balance')==2){
    		$and.=" AND payment_type NOT IN ('cod','pyr','ccr')";
    	}
    	
    	$stmt="SELECT sum(total_commission) as total_commission
    	FROM
    	{{order}}
    	WHERE status IN ($status)    	
    	$and
    	";      	 
    	if ( $res=$this->db_ext->rst($stmt)){    		
    		if ( $res[0]['total_commission']==""){
    			return 0;
    		} 
    		return $res[0]['total_commission'];    			
    	}	
    	return false;    	
    }    
    

   public function getTotalCommissionLast()
    {
    	$total_commission_status=Yii::app()->functions->getOptionAdmin('total_commission_status');
		if (!empty($total_commission_status)){
			$total_commission_status=json_decode($total_commission_status);
		} else {
			$total_commission_status=array('paid');
		}    	
    	$status='';
    	if (is_array($total_commission_status) && count($total_commission_status)>=1){
    		foreach ($total_commission_status as $val) {    			
    			//$status.="'$val',";
    			$status.= FunctionsV3::q($val).",";
    		}
    		$status=substr($status,0,-1);
    	} else $status="'paid'";
    	    	        
    	$end_date=date("Y-m-d");
    	$start_date=date('Y-m-d', strtotime ('-30 days'));				
    	$and=" AND date_created BETWEEN  ".FunctionsV3::q("$start_date 00:00:00")." AND 
	    		        ".FunctionsV3::q("$end_date 23:59:00")."
	    		 ";	    		
    	    	
    	if ( Yii::app()->functions->getOptionAdmin('admin_exclude_cod_balance')==2){
    		$and.=" AND payment_type NOT IN ('cod','pyr','ccr')";
    	}
    	
    	$stmt="SELECT sum(total_commission) as total_commission
    	FROM
    	{{order}}
    	WHERE status IN ($status)    	
    	$and
    	";    	    	
    	if ( $res=$this->db_ext->rst($stmt)){    		
    		if ( $res[0]['total_commission']==""){
    			return 0;
    		} 
    		return $res[0]['total_commission'];    			
    	}	
    	return false;    	
    }        
	    
	public function seo_friendly_url($string){
	    $string = str_replace(array('[\', \']'), '', $string);
	    $string = preg_replace('/\[.*\]/U', '', $string);
	    $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
	    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
	    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
	    $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
	    return strtolower(trim($string, '-'));
	}   


    public function getMerchantBalance($merchant_id='')
    {
    	$merchant_id = (integer)$merchant_id;
    	if($merchant_id>0){
    		$status_based = array('paid'); $used_status=''; $and='';
    		$status = getOptionA('total_commission_status');
    		if($json=json_decode($status,true)){    			
    			$status_based = $json;
    		}    		
    		foreach ($status_based as $val) {
    			$used_status.= q($val).",";
    		}
    		$used_status = substr($used_status,0,-1);
    		
    		$offline = "'cod','ocr','pyr','obd'";
    		$include_all_offline_payment = getOptionA('admin_include_all_offline_payment');    		
    		
    		$and = " AND payment_type NOT IN ($offline) ";
    		    		
    		$stmt="SELECT
	    	IFNULL(sum(a.merchant_earnings),'0') as merchant_earnings,
	    	
	    	IFNULL((
	    	select sum(amount) from
	    	{{withdrawal}}
	    	where
	    	merchant_id=a.merchant_id
	    	and status IN ('pending','paid','processing','approved')
	    	),0) as total_payout,
	    	
	    	IFNULL((
	    	  select sum(merchant_earnings)
	    	  from {{order}}
	    	  WHERE merchant_id=".q($merchant_id)."	    
	    	  and status IN ($used_status)	  
	    	  and payment_type IN ($offline)
	    	),0) as offline_balance
	    	
	    	FROM
	    	{{order}} a
	    	WHERE merchant_id=".q($merchant_id)."	    
	    	and status IN ($used_status)	 
	    	$and   		    	
	    	";    		    		
    		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){       			
    			$merchant_earnings = (float)$res['merchant_earnings'];
    			$total_payout = (float)$res['total_payout'];
    			$offline_balance = (float)$res['offline_balance'];
    			if($include_all_offline_payment==1){
    				$balance = $merchant_earnings+$offline_balance - ($total_payout);
    			} else $balance = $merchant_earnings - ($total_payout+$offline_balance);    			
    			return $balance;
    		}    		   		
    	}
    	return 0;
    }	
    
   public function getMerchantBalanceThisMonth($merchant_id='')
   {
       	$and=''; 
       	$merchant_id = (integer)$merchant_id;
       	
       	$status_based = array('paid'); $used_status=''; $and='';
       	$offline = "'cod','ocr','pyr','obd'";
		$status = getOptionA('total_commission_status');
		$include_all_offline_payment = getOptionA('admin_include_all_offline_payment');    		
		
		if($json=json_decode($status,true)){    			
			$status_based = $json;
		}    		
		foreach ($status_based as $val) {
			$used_status.= q($val).",";
		}
		$used_status = substr($used_status,0,-1);
       	
		if($include_all_offline_payment!=1){
			$and.= " AND payment_type NOT IN ($offline) ";
		}
		
		$query_date = date("Y-m-d");
		$range1=date('Y-m-01', strtotime($query_date));
		$range2=date('Y-m-t', strtotime($query_date));
		$and.=" AND DATE_FORMAT(date_created,'%Y-%m-%d')  BETWEEN ".FunctionsV3::q($range1)." AND ".FunctionsV3::q($range2)."  ";
		
    	$stmt="SELECT 
    	sum(total_commission) as total_commission,
    	sum(total_w_tax) as total_w_tax,
    	count(*) as total_order
    	FROM
    	{{order}}
    	WHERE merchant_id=".q($merchant_id)."
    	AND status IN ($used_status)
    	$and    	
    	";    	       	    	    	
    	if($res = Yii::app()->db->createCommand($stmt)->queryRow()){     		
    		return array(
    		  'balance'=>$res['total_w_tax']-$res['total_commission'],
    		  'count'=>$res['total_order']
    		);
    	}
    	return false;    	    	   
    }	
    
    public function getMerchantTotalSales($merchant_id='')
    {    	
   	   	$and=''; 
       	$merchant_id = (integer)$merchant_id;
       	
       	$status_based = array('paid'); $used_status=''; $and='';
       	$offline = "'cod','ocr','pyr','obd'";
		$status = getOptionA('total_commission_status');
		$include_all_offline_payment = getOptionA('admin_include_all_offline_payment');    		
		
		if($json=json_decode($status,true)){    			
			$status_based = $json;
		}    		
		foreach ($status_based as $val) {
			$used_status.= q($val).",";
		}
		$used_status = substr($used_status,0,-1);
       	
		if($include_all_offline_payment!=1){
			$and.= " AND payment_type NOT IN ($offline) ";
		}
		
		$stmt="SELECT 
    	sum(total_w_tax) as total_w_tax,
    	count(*) as total_order
    	FROM
    	{{order}}
    	WHERE status IN ($used_status)
    	AND merchant_id=".q($merchant_id)."    	
    	$and
    	";      		
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
			return array(
    		  'balance'=>$res['total_w_tax'],
    		  'count'=>$res['total_order']
    		);
		}
		return false;
    }	    
        
    public function getCommissionOrderStats()
    {
    	$total_commission_status=Yii::app()->functions->getOptionAdmin('total_commission_status');
		if (!empty($total_commission_status)){
			$total_commission_status=json_decode($total_commission_status);
		} else {
			$total_commission_status=array('paid');
		}    	
    	$status='';
    	if (is_array($total_commission_status) && count($total_commission_status)>=1){
    		foreach ($total_commission_status as $val) {    			
    			//$status.="'$val',";
    			$status.=FunctionsV3::q($val).",";
    		}
    		$status=substr($status,0,-1);
    	} else $status="'paid'";
    	
    	return $status;
    }
    
    public function getCommissionOrderStatsArray()
    {
    	$total_commission_status=Yii::app()->functions->getOptionAdmin('total_commission_status');
		if (!empty($total_commission_status)){
			$total_commission_status=json_decode($total_commission_status);
		} else {
			$total_commission_status=array('paid');
		}    	
    	/*$status='';
    	if (is_array($total_commission_status) && count($total_commission_status)>=1){
    		foreach ($total_commission_status as $val) {    			
    			$status.="'$val',";
    		}
    		$status=substr($status,0,-1);
    	} else $status="'paid'";*/
    	
    	return $total_commission_status;
    }
        
    public function getLastTwoMonths()
    {
    	$a=date("F Y"); 
    	$b=date("F Y",strtotime("-1 Months")); 
    	$c=date("F Y",strtotime("-2 Months")); 
    	return array(
    	  date("Y-m-d")=>$a,
    	  date("Y-m-d",strtotime("-1 Months"))=>$b,
    	  date("Y-m-d",strtotime("-2 Months"))=>$c
    	);
    }
    
    public function getIngredients($id='')
    {
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{ingredients}}
			WHERE
			ingredients_id= ".FunctionsV3::q($id)."
			LIMIT 0,1
		";		
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
    }		       
    
    public function getIngredientsList($merchant_id='')
    {
    	$data_feed=array();
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{ingredients}}
			WHERE
			merchant_id= ".FunctionsV3::q($merchant_id)."
			AND status IN ('publish')
			ORDER BY sequence ASC			
		";			    
		if ( $res=$DbExt->rst($stmt)){			
			if ($this->data=="list"){
				foreach ($res as $val) {									   
				   $data_feed[$val['ingredients_id']]=$val['ingredients_name'];
				}
				return $data_feed;
			} else return $res;
		}
		return false;
    }		         
	    
	public function ingredientsToArray($json_data='')
	{
		$data=array();
		$json_data=!empty($json_data)?json_decode($json_data,true):false;		
		$cooking_ref=$this->getIngredientsAll();		
		//if ( $json_data!=false){
		if(is_array($json_data) && count((array)$json_data)>=1){
			foreach ($json_data as $cooking_id) {
				if (array_key_exists($cooking_id,(array)$cooking_ref)){
					$data[$cooking_id]=$cooking_ref[$cooking_id];
				}
			}
			return $data;
		}
		return false;
	}
	    
    public function getIngredientsAll()
    {    	
    	$data_feed=array();
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{ingredients}}		
			WHERE status IN ('publish')	
			ORDER BY sequence ASC			
		";		
		if ( $res=$DbExt->rst($stmt)){			
			if ($this->data=="list"){
				foreach ($res as $val) {									   
				   $data_feed[$val['ingredients_id']]=$val['ingredients_name'];
				}
				return $data_feed;
			} else return $res;
		}
		return false;
    }		        

    public function payoutRequest($payment_method='',$data='')
    {
    	$token=md5($this->generateRandomKey());
    	$wd_days_process=Yii::app()->functions->getOptionAdmin("wd_days_process");
    	//if (empty($wd_days_process)){
    	if (!is_numeric($wd_days_process)){
    		$wd_days_process=5;
    	}
        $process_date=date("Y-m-d", strtotime (" +$wd_days_process days"));
    	switch ($payment_method) {
    		case "paypal":
    			
    			$params=array(
    			  'merchant_id'=>$this->getMerchantID(),
    			  'payment_type'=>$data['payment_type'],
    			  'payment_method'=>$data['payment_method'],
    			  'amount'=>$data['amount'],
    			  'currency_code'=>adminCurrencyCode(),
    			  'date_created'=>FunctionsV3::dateNow(),
    			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
    			  'account'=>$data['account'],
    			  'date_to_process'=>$process_date,
    			  'current_balance'=>$data['current_balance'],
    			  'balance'=>$data['current_balance']-$data['amount'],
    			  'date_to_process'=>$process_date,
    			  'withdrawal_token'=>$token
    			);
    			if ( $this->db_ext->insertData("{{withdrawal}}",$params)){
    				//return Yii::app()->db->getLastInsertID();
    				return array(
    				  'id'=>Yii::app()->db->getLastInsertID(),
    				  'token'=>$token
    				);
    			}
    			break;
    	
    		case "bank":    
    		    $wd_bank_fields=yii::app()->functions->getOptionAdmin('wd_bank_fields');	    		    
    		    $mtid=Yii::app()->functions->getMerchantID();			        
    			$params=array(
    			  'merchant_id'=>$this->getMerchantID(),
    			  'payment_type'=>$data['payment_type'],
    			  'payment_method'=>$data['payment_method'],
    			  'amount'=>$data['amount'],
    			  'currency_code'=>adminCurrencyCode(),
    			  'date_created'=>FunctionsV3::dateNow(),
    			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
    			  'account_name'=>$data['account_name'],
    			  'bank_account_number'=>$data['bank_account_number'],
    			  'swift_code'=>$data['swift_code'],
    			  'bank_name'=>isset($data['bank_name'])?$data['bank_name']:'',
    			  'bank_branch'=>isset($data['bank_branch'])?$data['bank_branch']:'',
    			  'bank_country'=>isset($data['bank_country'])?$data['bank_country']:'',
    			  'date_to_process'=>$process_date,
    			  'current_balance'=>$data['current_balance'],
    			  'balance'=>$data['current_balance']-$data['amount'],
    			  'date_to_process'=>$process_date,
    			  'withdrawal_token'=>$token
    			);    	    			
    			if (!empty($wd_bank_fields)){
    				$params['bank_type']=$wd_bank_fields;
    			}    		
    			    		    			
    			if (isset($data['default_account_bank'])){
	    			if ( $data['default_account_bank']==2){
	    				Yii::app()->functions->updateOption("merchant_payout_bank_account",
    	                json_encode($params),$this->getMerchantID());
	    			}
    			}
    				
    			if ( $this->db_ext->insertData("{{withdrawal}}",$params)){
    				//return Yii::app()->db->getLastInsertID();
    				return array(
    				  'id'=>Yii::app()->db->getLastInsertID(),
    				  'token'=>$token
    				);
    			}    			
    			break;
    			    			
    		default:
    			break;
    	}
    	
    	return true;
    }   
    
    public function getWithdrawalInformation($id='')
    {
    	$stmt="SELECT * FROM
    	{{withdrawal}}
    	WHERE
    	withdrawal_id=".$this->q($id)."
    	LIMIT 0,1
    	";
    	if ( $res=$this->db_ext->rst($stmt)){
    		return $res[0];
    	}
    	return false;    		
    }
    
    public function getWithdrawalInformationByToken($token='')
    {
    	$stmt="SELECT * FROM
    	{{withdrawal}}
    	WHERE
    	withdrawal_token=".$this->q($token)."
    	LIMIT 0,1
    	";
    	if ( $res=$this->db_ext->rst($stmt)){
    		return $res[0];
    	}
    	return false;    		
    }    
    
    public function getMerchantWithdrawal($merchant_id='',$status='')
    {    	
    	$and="";
    	$temp_status='';
    	if ( is_array($status) && count($status)>=1){
    		foreach ($status as $val) {
    			//$temp_status.="'$val',";
    			$temp_status.=FunctionsV3::q($val).",";
    		}    		
    		$temp_status=substr($temp_status,0,-1);
    		$and=" AND status IN ($temp_status) ";
    	}
    	$stmt="SELECT * FROM
    	{{withdrawal}}
    	WHERE
    	merchant_id=".$this->q($merchant_id)."
    	$and
    	ORDER BY withdrawal_id DESC    	
    	";    	
    	if ( $res=$this->db_ext->rst($stmt)){
    		return $res;
    	}
    	return false;    		
    }    


    public function getMerchantFailedWithdrawal($merchant_id='')
    {    	
    	$and="AND status NOT IN ('paid','pending','approved')";    	
    	$stmt="SELECT * FROM
    	{{withdrawal}}
    	WHERE
    	merchant_id=".$this->q($merchant_id)."
    	$and
    	ORDER BY withdrawal_id DESC    	
    	";    	
    	if ( $res=$this->db_ext->rst($stmt)){
    		return $res;
    	}
    	return false;    		
    }    
        
    public function withdrawalStatus()
    {
    	return array(
    	   'pending'=>t("pending"),
    	   'paid'=>t("paid"),
    	   'cancel'=>t("cancel"),
    	   'reversal'=>t("reversal"),
    	   'denied'=>t("denied"),
    	   'processing'=>t("processing")
    	);
    }

    public function displayDate($date_to_format='')
    {
    	if ( $date_to_format==""){
    		return '';
    	} else {
	    	$date=date('M d,Y G:i:s',strtotime($date_to_format));  
	        $date=Yii::app()->functions->translateDate($date);
	        return $date;
    	}
    }
    
    public function getPaypalConnectionWithdrawal()
    {
    	 $paypal_mode=yii::app()->functions->getOptionAdmin('wd_paypal_mode'); 
    	 $paypal_mode=strtolower($paypal_mode);
		 $paypal_con=array();		 
		 if ($paypal_mode=="sandbox"){
		  	  $paypal_con['mode']="sandbox";
		  	  $paypal_con['sandbox']['paypal_nvp']='https://api-3t.sandbox.paypal.com/nvp';
		  	  $paypal_con['sandbox']['paypal_web']='https://www.sandbox.paypal.com/cgi-bin/webscr';
		  	  $paypal_con['sandbox']['user']=yii::app()->functions->getOptionAdmin('wd_paypal_mode_user');
		  	  $paypal_con['sandbox']['psw']=yii::app()->functions->getOptionAdmin('wd_paypal_mode_pass');
		  	  $paypal_con['sandbox']['signature']=yii::app()->functions->getOptionAdmin('wd_paypal_mode_signature');
		  	  $paypal_con['sandbox']['version']='61.0';
		  	  $paypal_con['sandbox']['action']='Sale';
		  } else {
		  	  $paypal_con['mode']="live";
		  	  $paypal_con['live']['paypal_nvp']='https://api-3t.paypal.com/nvp';
		  	  $paypal_con['live']['paypal_web']='https://www.paypal.com/cgi-bin/webscr';
		  	  $paypal_con['live']['user']=yii::app()->functions->getOptionAdmin('wd_paypal_mode_user');
		  	  $paypal_con['live']['psw']=yii::app()->functions->getOptionAdmin('wd_paypal_mode_pass');
		  	  $paypal_con['live']['signature']=yii::app()->functions->getOptionAdmin('wd_paypal_mode_signature');
		  	  $paypal_con['live']['version']='61.0';
		  	  $paypal_con['live']['action']='Sale';
		  }
		  return $paypal_con;
    }	          
                
    function dateDifference($start, $end )
    {
        $uts['start']=strtotime( $start );
		$uts['end']=strtotime( $end );
		if( $uts['start']!==-1 && $uts['end']!==-1 )
		{
		if( $uts['end'] >= $uts['start'] )
		{
		$diff    =    $uts['end'] - $uts['start'];
		if( $days=intval((floor($diff/86400))) )
		    $diff = $diff % 86400;
		if( $hours=intval((floor($diff/3600))) )
		    $diff = $diff % 3600;
		if( $minutes=intval((floor($diff/60))) )
		    $diff = $diff % 60;
		$diff    =    intval( $diff );            
		return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
		}
		else
		{			
		return false;
		}
		}
		else
		{			
		return false;
		}
		return( false );
     }    
     
     public function validateMerchantUserFromMerchantUser($username='',$email='',$id='')
     {
     	$and="";    	        	    	
    	$msg='';
    	
     	$stmt1="SELECT * FROM
     	{{merchant_user}}
     	WHERE
     	username=".$this->q($username)."
     	$and
     	LIMIT 0,1
     	";     	
     	if ($res1=$this->db_ext->rst($stmt1)){
     		$msg=t("Username already exist");
     	}
     	
     	$stmt1="SELECT * FROM
     	{{merchant_user}}
     	WHERE
     	contact_email=".$this->q($email)."
     	$and
     	LIMIT 0,1
     	";     	
     	if ($res1=$this->db_ext->rst($stmt1)){
     		$msg=t("Email address already exist");
     	}
     	     	
     	if (empty($msg)){
     		return false;
     	}     	
     	return $msg;
     }  
     
    public function merchantList3($as_list=true,$with_select=false)
    {
    	$data=array();
    	$DbExt=new DbExt;
    	$stmt="SELECT * FROM
    	{{merchant}}    	
    	ORDER BY restaurant_name ASC
    	";
    	if ( $with_select){    		
    		$data[]=t("All Merchant");
    	}
    	if ($res=$DbExt->rst($stmt)){    		
    		if ( $as_list==TRUE){
    			foreach ($res as $val) {    				
    			    $data[$val['merchant_id']]=ucwords($val['restaurant_name']);
    			}
    			return $data;
    		} else return $res;    	
    	}
    	return false;
    }     
        
    public function getBankDepositInstruction()
    {
    	$sender=$this->getOptionAdmin("admin_deposit_sender");
    	$subject=$this->getOptionAdmin("admin_deposit_subject");
    	$content=$this->getOptionAdmin("admin_deposit_instructions");
    	return array(
    	  'sender'=>$sender,
    	  'subject'=>$subject,
    	  'content'=>$content
    	);
    }
    
    public function getMerchantFaxCredit($merchant_id='')
    {
    	
        $stats=Yii::app()->functions->getOptionAdmin('fax_user_admin_credit');    	
    	if ($stats=="2"){
    		return 1;
    	}
    	    	
    	$stmt="
    	SELECT SUM(fax_limit) as total_credits,
    	(
    	  select count(*) as total_send
    	   from
    	  {{fax_broadcast}}
    	   where
    	   merchant_id=".Yii::app()->db->quoteValue($merchant_id)."    	
    	   and    	   
    	   status in ('success')
    	) as total_send
    	
    	FROM {{fax_package_trans}}
    	WHERE
    	merchant_id=".Yii::app()->db->quoteValue($merchant_id)."
    	AND
    	status in ('paid')
    	";
    	if ($res=$this->db_ext->rst($stmt)){
    		return $res[0]['total_credits']-$res[0]['total_send'];
    	}
    	return 0;
    }    
    
    public function sendFax($merchant_id='',$order_id='')
    {    	    	
    	$enabled=$this->getOption('fax_merchant_enabled',$merchant_id);
    	if ( $enabled==2){
    		$params=array(
    		  'merchant_id'=>$merchant_id,
    		  'faxno'=>$this->getOption('fax_merchant_number',$merchant_id),
    		  'recipname'=>$this->getOption('fax_merchant_recipient',$merchant_id),
    		  'faxurl'=>websiteUrl()."/store/fax/?id=$order_id",  
    		  'date_created'=>FunctionsV3::dateNow(),
    		  'ip_address'=>$_SERVER['REMOTE_ADDR']
    		);
    		$this->db_ext->insertData("{{fax_broadcast}}",$params);
    	}
    }	
        
    public function getFaxJobId($jobid='')
    {
    	$stmt="SELECT * FROM
    	{{fax_broadcast}}
    	WHERE
    	jobid=".$this->q($jobid)."
    	LIMIT 0,1
    	";
    	if ($res=$this->db_ext->rst($stmt)){
    		return $res[0];
    	} 
    	return false;    		
    }
    
    public function validateAdminSession()
    {
    	$this->has_session=false;
    	if(isset($_SESSION['kr_user_session'])){
    		
    		$allowed=$this->getOptionAdmin('website_admin_mutiple_login');    		
    		if ( $allowed==""){    			
    			if (empty($_SESSION['kr_user_session'])){
    				return false;
    			}
    			return true;
    		}
    		
    		$admin_id=$this->getAdminId();
    		$kr_user_session=$_SESSION['kr_user_session'];
    		$stmt="SELECT session_token
    		FROM {{admin_user}}
    		WHERE
    		admin_id=".$this->q($admin_id)."
    		LIMIT 0,1
    		";    		
    		if ($res=$this->db_ext->rst($stmt)){    			
    			if ( $kr_user_session==$res[0]['session_token']){
    				return true;
    			}
    			$this->has_session=true;
    		}
    	}
    	return false;
    }
    
    public function validateMerchantSession()
    {
    	$this->has_session=false;
    	if(isset($_SESSION['kr_merchant_user_session'])){
    		
    		$merchant_id=$this->getMerchantID();
    		$session=$_SESSION['kr_merchant_user_session'];
    		
    		$allowed=$this->getOptionAdmin('website_merchant_mutiple_login');      			
    		if ( $allowed==""){
    			if (empty($_SESSION['kr_merchant_user_session'])){
    				return false;
    			}
    			return true;
    		}
    		    		    		    		
    		if ( $_SESSION['kr_merchant_user_type']=="admin"){
    			$stmt="SELECT session_token
	    		FROM {{merchant}}
	    		WHERE
	    		merchant_id=".$this->q($merchant_id)."
	    		LIMIT 0,1
	    		";    	
    		} else {
    			$merchant_user_id='';
    		    $user_info=json_decode($_SESSION['kr_merchant_user'],true);
    		    if (is_array($user_info) && count($user_info)>=1){
    			    $merchant_user_id=$user_info[0]['merchant_user_id'];
    		    }
    		
	    		$stmt="SELECT session_token
	    		FROM {{merchant_user}}
	    		WHERE
	    		merchant_user_id=".$this->q($merchant_user_id)."
	    		LIMIT 0,1
	    		";    	
    		}    		
    		if ($res=$this->db_ext->rst($stmt)){    			    			
    			if ( $session==$res[0]['session_token']){
    				return true;
    			}
    			$this->has_session=true;
    		}
    	}
    	return false;
    }    
    
    public function getShippingRates($mtid='')
    {
    	$stmt="SELECT * FROM
    	{{shipping_rate}}
    	WHERE
    	merchant_id=".Yii::app()->functions->q($mtid)."
    	ORDER BY id ASC
    	";
    	if ( $res=$this->db_ext->rst($stmt)){
    		return $res;
    	}
    	return false;
    }     
    
    public function isMerchantOpenTimes($merchant_id='',$full_booking_day='',$booking_time='')
    {
	   $business_hours=Yii::app()->functions->getBusinnesHours($merchant_id);
	   //dump($business_hours);	   
	   /*dump($merchant_id);
	   dump($full_booking_day);
	   dump($booking_time);*/
		if (is_array($business_hours) && count($business_hours)>=1){
			if (!array_key_exists($full_booking_day,$business_hours)){
				return false;
			} else {
				if (!empty($booking_time)){
					if (array_key_exists($full_booking_day,$business_hours)){						
						$selected_date=$business_hours[$full_booking_day];										
						//dump($selected_date);
						$temp_selected=explode(",",$selected_date);	
						//dump($temp_selected);	
												
						if(is_array($temp_selected) && count($temp_selected)>=1){							
							if ( empty($temp_selected[0])){
							    if (!empty($temp_selected[1])){
							    	$temp_selected[0]=$temp_selected[1];
							    }
							}
						}
						
						$selected_date=explode("-",$temp_selected[0]);
						//dump($selected_date);
						$t1=trim($selected_date[0]);
						$t2=trim($selected_date[1]);
																		
						if ( !Yii::app()->functions->checkBetweenTime($booking_time,$t1,$t2)){	
							if (isset($selected_date[1])){												
								if(!isset($temp_selected[1])){
									$temp_selected[1]='';
								}
								$selected_date=explode("-",$temp_selected[1]);
								if(!isset($selected_date[1])){
									$selected_date[1]='';
								}
								$t1=trim($selected_date[0]);
						        $t2=trim($selected_date[1]);						        
						        if ( Yii::app()->functions->checkBetweenTime($booking_time,$t1,$t2)){
						        	return true;
						        } 
							}										
							return false;
						}
					}
				}
			}
		}							
		return true;							
    }
    
    public function checkBetweenTime($current_time='',$sunrise='',$sunset='')
    {    	
    	/*refference http://stackoverflow.com/questions/15911312/how-to-check-if-time-is-between-two-times-in-php*/    	
      	/*$current_time = "09:55 AM";
        $sunrise = "09:00 AM";
        $sunset = "07:30 PM";*/    
      	/*dump($current_time);   
      	dump($sunrise);
      	dump($sunset);*/
		$date1 = DateTime::createFromFormat('H:i a', $current_time);		
		$date2 = DateTime::createFromFormat('H:i a', $sunrise);		
		$date3 = DateTime::createFromFormat('H:i a', $sunset);		
		if ($date1 >= $date2 && $date1 <= $date3) {			
		    return true;
		} 
		return false;
    }  

    public function prettyLink($link='')
    {
    	if (!preg_match("/http/i", $link)) {
		   $link="http://".$link;
        } 
        return $link;
    }
    
	public function getMerchantCommissionDetails($merchant_id='')
	{
		
		$stmt="
		SELECT * FROM
		{{merchant}}
		WHERE
		merchant_id=".$this->q($merchant_id)."
		LIMIT 0,1
		";		
		if ( $res=$this->db_ext->rst($stmt)){
			return array(
			  'is_commission'=>$res[0]['is_commission'],
			  'commision_type'=>$res[0]['commision_type'],
			  'percent_commision'=>$res[0]['percent_commision']
			);
		}
		return false;
	}        	

	public function FormatDateTime($date='',$time=true)
	{
		if ($date=="0000-00-00"){
    		return ;
    	}    
    	if ($date=="0000-00-00 00:00:00"){
    		return ;
    	}
    	if ( !empty($date)){    		
    		$date_f=Yii::app()->functions->getOptionAdmin("website_date_format");
    		$time_f=Yii::app()->functions->getOptionAdmin("website_time_format");       		
    		if (!empty($date_f)){
    			if ( $time==TRUE){
    			    $date_ouput = date("$date_f $time_f",strtotime($date));	
    			} else $date_ouput = date("$date_f",strtotime($date));	    			
    			return $this->translateDate($date_ouput);
    		} else {
    			if ( $time==TRUE){
    		        $date_ouput= date('M d,Y G:i:s',strtotime($date));	
    			} else $date_ouput= date('M d,Y',strtotime($date));	
    		    return $this->translateDate($date_ouput);
    		}
    	}
    	return false;
	}
	
	public function timeFormat($time='',$is_display=false)
	{
		if(empty($time)){
			return false;
		}
		
		$time_format=Yii::app()->functions->getOptionAdmin("website_time_picker_format");
		//dump($time_format);	
		switch ($time_format){
			case "12":
				if ( $is_display==true){
					return date("g:i A", strtotime($time));
				} else return date("G:i", strtotime($time));
				break;
			default:
				if ( $is_display==true){
					return date("G:i", strtotime($time));
				} else return date("G:i", strtotime($time));
				break;	
		}
		return $time;
	}
	
	public function sendVerificationCode($mobile='',$code='')
	{		
		$msg=t("Your verification code is")." ".$code;;		
		if ( $res = $this->sendSMS($mobile,$msg)){			
			$params=array(
			  'contact_phone'=>$mobile,
			  'sms_message'=>$msg,
			  'status'=>isset($res['msg'])?$res['msg']:'',
			  'gateway_response'=>isset($res['raw'])?$res['raw']:'',
			  'gateway'=>$res['sms_provider'],
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR']
			);
			$DbExt=new DbExt;
			$DbExt->insertData("{{sms_broadcast_details}}",$params);
			return true;
		}
		return false;
	}
	
    public function getCategoryList2($merchant_id='', $food_name='', $todays_day='')
	{		
		
		$todays_day = !empty($todays_day)?strtolower($todays_day):'';
		
		$data_feed=array();
		$and=''; $category_ids='';
		
		$enabled_category_sked = getOption($merchant_id,'enabled_category_sked');    	
		
		if (!empty($food_name)){			
			$stmt_sub="
			SELECT category FROM
			{{item}}
			WHERE
			item_name LIKE ".FunctionsV3::q("%$food_name%")."
			AND merchant_id=".FunctionsV3::q($merchant_id)."			
			";			
			$DbExt=new DbExt;
			if($res=$DbExt->rst($stmt_sub)){
			   foreach ($res as $val) {			   	   
			   	   $t=json_decode($val['category']);			   	   
			   	   if(is_array($t) && count($t)>=1){
			   	   	  foreach ($t as $tval) {			   	   	  	
			   	   	  	$category_ids.="'$tval',";
			   	   	  }
			   	   }
			   }			   
			}
		}
		
		if (!empty($category_ids)){
			$category_ids=substr($category_ids,0,-1);			
			$and=" AND cat_id IN ($category_ids) ";
		}
		
		if($enabled_category_sked==1){
    		$and .= " AND $todays_day='1' ";
    	}    
		
		$stmt="
		SELECT * FROM
		{{category}}
		WHERE 
		merchant_id= ".FunctionsV3::q($merchant_id)."
		AND status in ('publish','published')
		$and
		ORDER BY sequence ASC
		";		
		//dump($stmt);
				
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 				
		if (is_array($rows) && count($rows)>=1){
			if ($this->data=="list"){
				foreach ($rows as $val) {									   
				   $data_feed[$val['cat_id']]=array(
				     'category_name'=>$val['category_name'],
				     'category_description'=>$val['category_description'],
				     'dish'=>$val['dish'],
				     'category_name_trans'=>$val['category_name_trans'],
				     'category_description_trans'=>$val['category_description_trans'],
				     'photo'=>$val['photo'],
				   );
				}
				return $data_feed;
			} else return $rows;
		}
		return FALSE;
	}  

	/** NEW CODE ADDED FOR VERSION 2.1.1*/
	
	public function AA($tag='')
	{		
		if ( $access=$this->AAccess()){			
			$tag=strtolower($tag);
            $access = array_map('strtolower', $access);            
			if (in_array($tag,(array)$access)){
				return true;
			}
		}
		return false;
	}
	
	public function AAccess()
	{
		$info=$this->getAdminInfo();		
		if (is_object($info)){
			$access=!empty($info->user_access)?json_decode($info->user_access):false;
			if ($access!=false){
				//return $access;
				return array_map('strtolower', $access);
			}
		}
		return false;
	}
	
	public function AAmenuList()
	{
		$menu_list=array();
		$menu=$this->adminMenu();		
		foreach ($menu['items'] as $val) {
			$menu_list[] = strtolower($val['tag']);
			if (isset($val['items'])){
				if (is_array($val['items']) && count($val['items'])>=1){
					foreach ($val['items'] as $sub_val) {
						$menu_list[]= strtolower($sub_val['tag']);
					}
				}
			}
		}		
		return $menu_list;
	}
	
	public function GetDish($id='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{dishes}}
		WHERE
		dish_id= ".FunctionsV3::q($id)."
		LIMIT 0,1
		";
		if ($res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;	
	}	
	
	public function GetDishList()
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{dishes}}
		WHERE
		status IN ('publish','published')
		ORDER BY dish_name ASC		
		";
		if ($res=$DbExt->rst($stmt)){
			return $res;
		}
		return false;	
	}		
	
    public function getVoucherCodeByIdNew($voucher_id='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="SELECT a.*,
    	        (
    	        select count(*)
    	        from
    	        {{order}}
    	        where
    	        voucher_code=a.voucher_name
    	        ) as found
    	        FROM
    	       {{voucher_new}} a
    	       WHERE
    	       voucher_id= ".FunctionsV3::q($voucher_id)."
    	       LIMIT 0,1
    	";    	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;
    } 	
    
    public function getVoucherCodeNew($voucher_code='',$merchant_id='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="
    	SELECT a.*,
    	(
    	select count(*) from
    	{{order}}
    	where
    	voucher_code=".$this->q($voucher_code)."
    	and
    	client_id=".FunctionsV3::q($this->getClientId())."  	
    	LIMIT 0,1
    	) as found,
    	
    	(
    	select count(*) from
    	{{order}}
    	where
    	voucher_code=".$this->q($voucher_code)."    	
    	LIMIT 0,1
    	) as number_used    
    	
    	FROM
    	{{voucher_new}} a
    	WHERE
    	voucher_name=".$this->q($voucher_code)."
    	AND
    	merchant_id=".$this->q($merchant_id)."
    	AND status IN ('publish','published')
    	LIMIT 0,1
    	";    	    	
    	if ($res=$db_ext->rst($stmt)){    		    		
    		return $res[0];
    	}
    	return false;
    } 
    
    public function getVoucherCodeAdmin($voucher_code='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="
    	SELECT a.*,
    	(
    	select count(*) from
    	{{order}}
    	where
    	voucher_code=".$this->q($voucher_code)."
    	and
    	client_id=".$this->q($this->getClientId())."  	
    	LIMIT 0,1
    	) as found,
    	
    	(
    	select count(*) from
    	{{order}}
    	where
    	voucher_code=".$this->q($voucher_code)."    	
    	LIMIT 0,1
    	) as number_used    	
    	
    	FROM
    	{{voucher_new}} a
    	WHERE
    	voucher_name=".$this->q($voucher_code)."
    	AND
    	voucher_owner='admin'
    	AND status IN ('publish','published')
    	LIMIT 0,1
    	";    	     	
    	if ($res=$db_ext->rst($stmt)){    		    		
    		return $res[0];
    	}
    	return false;
    }     
    
    public function getAddressBookByID($id='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="SELECT * FROM
    	       {{address_book}}
    	       WHERE
    	       id= ".$this->q($id)."
    	       LIMIT 0,1
    	";    	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;
    } 	    
    
    public function hasAddressDefault($client_id='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="SELECT 
    	       concat(street,' ',city,' ',state,' ',zipcode) as address,
    	       id,location_name,country_code
    	       FROM
    	       {{address_book}}
    	       WHERE
    	       client_id= ".$this->q($client_id)."
    	       AND
    	       as_default ='2'
    	       LIMIT 0,1
    	";    	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;
    }

    public function showAddressBook()   
    {
    	if ( Yii::app()->functions->isClientLogin()){
    		$client_id=Yii::app()->functions->getClientId();
    		if ( $res=$this->hasAddressDefault($client_id)){    			
    			return $res;
    		}
    	}
    	return false;
    }
    
    public function getAddressBookByClient($client_id='')
    {
    	if($client_id>0){
	    	$db_ext=new DbExt;    	
	    	$stmt="SELECT  
	    	       concat(street,' ',city,' ',state,' ',zipcode) as address,
	    	       id,location_name,country_code,latitude,longitude
	    	       FROM
	    	       {{address_book}}
	    	       WHERE
	    	       client_id =".$this->q($client_id)."
	    	       ORDER BY street ASC    	       
	    	";    	    	
	    	if ($res=$db_ext->rst($stmt)){    		
	    		return $res;
	    	}
    	}
    	return false;
    } 	        
    
    public function addressBook($client_id='')
    {
    	$list=array();
    	if ( $res=$this->getAddressBookByClient($client_id)){
    		foreach ($res as $val) {    			
    			$list[$val['id']]=$val['address']." ".$this->countryCodeToFull($val['country_code']);
    		}
    	}
    	return $list;
    }
    
    public function getLanguageField()
    {
    	$lang_list=array();
    	$db_ext=new DbExt;
    	$stmt="SELECT lang_id,country_code,language_code
    	 FROM {{languages}} 
    	 WHERE
    	 status in ('publish','published')
    	 ";	
    	if ($res=$db_ext->rst($stmt)){    		
    		foreach ($res as $val) {    			
    			$lang_list[$val['lang_id']]=$val['language_code'];
    		}    		
    	}
    	return $lang_list;    
    }       
    
    public function multipleField()
    {
    	if ( Yii::app()->functions->getOptionAdmin('enabled_multiple_translation')==2){
    		return true;
    	}
    	return false;
    }
    
	public function cookingRefToArray2($json_data='')
	{
		$data=array();
		$json_data=!empty($json_data)?json_decode($json_data):false;				
		if ( $json_data!=false){
			foreach ($json_data as $cooking_id) {				
				$info=$this->getCookingRef($cooking_id);
				$data['cooking_name_trans']=!empty($info['cooking_name_trans'])?json_decode($info['cooking_name_trans'],true):'';
			}
			return $data;
		}
		return false;
	}    
	
	public function getSubcategoryTranslation($subcat_id='')
	{
		$db_ext=new DbExt;
    	$stmt="SELECT subcategory_name_trans,subcategory_description_trans
    	 FROM {{subcategory}} 
    	 WHERE
    	 subcat_id =".$this->q($subcat_id)."    	 
    	 LIMIT 0,1
    	 ";	
    	if ($res=$db_ext->rst($stmt)){    		
    		$res=$res[0];    		
    		if (!empty($res['subcategory_name_trans'])){
    			return json_decode($res['subcategory_name_trans'],true);
    		}	    		
    	}
    	return '';
	}
	
    public function getSizeTranslation($size_name='',$mt_id='')
    {
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{size}}
			WHERE
			size_name=".$this->q($size_name)."
			AND 
			merchant_id=".$this->q($mt_id)."
			LIMIT 0,1			
		";			    
		if ( $res=$DbExt->rst($stmt)){
			$res=$res[0];
			$t['size_name_trans']=!empty($res['size_name_trans'])?json_decode($res['size_name_trans'],true):'';
			return $t;
		}
		return false;
    }			
    
    public function getCookingTranslation($name='',$mt_id='')
    {
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{cooking_ref}}
			WHERE
			cooking_name=".$this->q($name)."
			AND 
			merchant_id=".$this->q($mt_id)."
			LIMIT 0,1			
		";			    
		if ( $res=$DbExt->rst($stmt)){
			$res=$res[0];
			$t['cooking_name_trans']=!empty($res['cooking_name_trans'])?json_decode($res['cooking_name_trans'],true):'';			
			return $t;
		}
		return false;
    }
    
    public function getAddonTranslation($name='',$mt_id='')
    {    	
	    $stmt="SELECT * FROM
			{{subcategory_item}}
			WHERE
			sub_item_name=".$this->q($name)."
			AND 
			merchant_id=".$this->q($mt_id)."
			LIMIT 0,1			
		";			      
		if ( $res=$this->db_ext->rst($stmt)){
			$res=$res[0];			
			$t['sub_item_name_trans']=!empty($res['sub_item_name_trans'])?json_decode($res['sub_item_name_trans'],true):'';			
			return $t;
		}
		return false;
    }
    
    public function geoCoding($lat='',$lng='')
    {    	    	    	
    	$protocol = isset($_SERVER["https"]) ? 'https' : 'http';
		if ($protocol=="http"){
			$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=true";
		} else $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=true";
		
		$google_geo_api_key=getOptionA('google_geo_api_key');
		if (!empty($google_geo_api_key)){
			$url=$url."&key=".urlencode($google_geo_api_key);
		}
		
    	$data = @file_get_contents($url);
    	if (!empty($data)){
    	    $result = json_decode($data,true);    	    	   
    	    //dump($result);
    	    if (!isset($result['results'])){
    	    	return false;
    	    }
    	    if (is_array($result['results']) && count($result['results'])>=2){
    	        $location = array();
    	         foreach ($result['results'][0]['address_components'] as $component) {
	               switch ($component['types']) {
				      case in_array('street_number', $component['types']):
				        $location['street_number'] = $component['long_name'];
				        break;
				      case in_array('route', $component['types']):
				        $location['street'] = $component['long_name'];
				        break;
				      case in_array('neighborhood', $component['types']):
				        $location['street2'] = $component['long_name'];
				        break;  
				      case in_array('sublocality', $component['types']):
				        $location['sublocality'] = $component['long_name'];
				        break;
				      case in_array('locality', $component['types']):
				        $location['locality'] = $component['long_name'];
				        break;
				      case in_array('administrative_area_level_2', $component['types']):
				        $location['admin_2'] = $component['long_name'];
				        break;
				      case in_array('administrative_area_level_1', $component['types']):
				        $location['admin_1'] = $component['long_name'];
				        break;
				      case in_array('postal_code', $component['types']):
				        $location['postal_code'] = $component['long_name'];
				        break;
				      case in_array('country', $component['types']):
				        $location['country'] = $component['long_name'];
				        $location['country_code'] = $component['short_name'];
				        break;
				   }
    	         } 	    	             	         
    	         return $location;
    	    }
    	} 
    	return false;
    }
        
	/** END CODE ADDED FOR VERSION 2.1.1*/
	
	
	public static function checkIfTableExist($table_name='')
	{
		$DbExt=new DbExt;		
		$prefix=Yii::app()->db->tablePrefix;
		$table = $prefix.$table_name;
		$stmt="SHOW TABLES LIKE ".self::q($table)." ";		
    	if ($res=$DbExt->rst($stmt)){    		
    		return $res;
    	}
    	return false;    
	}
	
	public function getClientFullName()
    {
    	if (isset($_SESSION['kr_client'])){
    		if (array_key_exists('client_id',$_SESSION['kr_client'])){    			
    			if (is_numeric($_SESSION['kr_client']['client_id'])){    				
    				return $_SESSION['kr_client']['first_name']." ".$_SESSION['kr_client']['last_name'];
    			}
    		}    	
    	}
    	return false;
    }
		       
}/* END CLASS*/


/**********************************************************************
FUNCTIONS
**********************************************************************/
function getOption($mtid='',$key='')
{
	return Yii::app()->functions->getOption($key,$mtid);  
}

function getOptionA($key='')
{
	return Yii::app()->functions->getOptionAdmin($key);  
}

function FormatDateTime($date='',$time=true)
{
	return Yii::app()->functions->FormatDateTime($date,$time);
}

function timeFormat($time='',$is_display=false)
{
	return Yii::app()->functions->timeFormat($time,$is_display);
}

function cleanNumber($string='')
{
	return preg_replace("/[^0-9^.]/","",$string);
}

function explodeData($data)
{
	return Yii::app()->functions->explodeData($data);
}

function prettyFormat($price='',$merchant_id='')
{	
	return Yii::app()->functions->prettyFormat($price,$merchant_id);
}

function standardPrettyFormat($price='')
{
    return Yii::app()->functions->standardPrettyFormat($price);
}

function normalPrettyPrice($price='')
{
	return Yii::app()->functions->normalPrettyPrice($price);
}

function unPrettyPrice($price)
{
	return Yii::app()->functions->unPrettyPrice($price);	
}

function arrayKeyExists($needle, $haystack)
{
    $result = array_key_exists($needle, $haystack);
    if ($result) return $result;
    foreach ($haystack as $v) {
        if (is_array($v)) {
            $result = arrayKeyExists($needle, $v);
        }
        if ($result) return $result;
    }
    return $result;
}

function getSelectedItemArray($key='',$array='')
{		
	if (is_array($array) && count($array)>=1){
		foreach ($array as $keys=>$val) {
			if ( $key == $keys){
				return $val;
			}
		}
	}
	return false;
}

function getCurrencyCode()
{
	return Yii::app()->functions->getCurrencyCode();
}

function baseCurrency()
{
	return Yii::app()->functions->getCurrencyCode();
}


function baseUrl()
{
	return Yii::app()->request->baseUrl;;
}

function getMerchantID()
{
	Yii::app()->functions->getMerchantID();
}

function isIsset($data='')
{
	if ( isset($data)){
		return $data;
	}
	return '';
}

function getDistance($from='',$to='',$debug=false)
{
	return Yii::app()->functions->getDistance($from,$to,$debug);
}

function prettyDate($date='',$full=false)
{
	return Yii::app()->functions->prettyDate($date,$full);
}

function getDeliveryDistance($from_address='',$merchant_address='',$country_code='')
{	
	$miles=0;
	$miles_raw=0;
	if($distance=getDistance($from_address,$merchant_address,$country_code,true)){	    				
        $miles=$distance->rows[0]->elements[0]->distance->text;
		$miles_raw=str_replace(array(" ","mi"),"",$miles); 		
		$km=$distance->rows[0]->elements[0]->distance->value;
		$kms=($km * 0.621371 / 1000);
	}	    		
	return $miles_raw;		    					    					    		
}

function getDeliveryDistance2($from_address='',$merchant_address='',$country_code='')
{	
	$miles=0;
	$miles_raw=0;
	$kms=0;
	if($distance=getDistance($from_address,$merchant_address,$country_code,true)){	    						
        $miles=$distance->rows[0]->elements[0]->distance->text;
		$miles_raw=str_replace(array(" ","mi"),"",$miles); 		
		$km=$distance->rows[0]->elements[0]->distance->value;
		//$kms=($km * 0.621371 / 1000);
		$kms=miles2kms( unPrettyPrice($miles_raw));
		$kms=standardPrettyFormat($kms);
	}	    		
	
	return array(
	   'mi'=>$miles_raw,
	   'km'=>$kms
	);		    					    					    		
}

function miles2kms($miles) {
	$ratio = 1.609344;
	$kms = $miles * $ratio;
	return $kms;
} 

function ft2kms($ft='') {
	$ratio = 0.0003048;
	$ft = $ft * $ratio;
	return $ft;
} 

function adminCurrencySymbol()
{
	return Yii::app()->functions->adminCurrencySymbol();
}

function adminCurrencyCode()
{
	return Yii::app()->functions->adminCurrencyCode();
}

function sendEmail($to='',$from='',$subject='',$body='' , $record_id='')
{
	return Yii::app()->functions->sendEmail($to,$from,$subject,$body,$record_id);
}

function generateCouponCode($length = 8) {
  $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $ret = '';
  for($i = 0; $i < $length; ++$i) {
    $random = str_shuffle($chars);
    $ret .= $random[0];
  }
  return $ret;
}

function customNumberFormat($n, $precision = 3) {
    if ($n < 1000000) {
        // Anything less than a million
        $n_format = number_format($n);
    } else if ($n < 1000000000) {
        // Anything less than a billion
        $n_format = number_format($n / 1000000, $precision) . 'M';
    } else {
        // At least a billion
        $n_format = number_format($n / 1000000000, $precision) . 'B';
    }

    return $n_format;
}

function send($url,$api,$amount,$redirect){ 
    $ch = curl_init(); 
    curl_setopt($ch,CURLOPT_URL,$url); 
     
    curl_setopt($ch,CURLOPT_POSTFIELDS,"api=$api&amount=$amount&redirect=$redirect"); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 
    $res = curl_exec($ch); 
    curl_close($ch); 
    return $res; 
} 
 
function get($url,$api,$trans_id,$id_get){ 
    $ch = curl_init(); 
    curl_setopt($ch,CURLOPT_URL,$url);  curl_setopt($ch,CURLOPT_POSTFIELDS,"api=$api&id_get=$id_get&trans_id=$trans_id"); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 
    $res = curl_exec($ch); 
    curl_close($ch); 
    return $res; 
} 

function bookingStatus()
{
	return array(
	  'pending'=>Yii::t("default",'pending'),
	  'approved'=>Yii::t('default','approved'),
	  'denied'=>Yii::t('default','denied'),
	  'request_cancel_booking'=>Yii::t('default','request cancel booking'),
	  'cancel_booking_approved'=>Yii::t('default','cancel booking approved'),
	);
}

function t($message='')
{
	return Yii::t("default",$message);
}


function tt($words='', $params=array())
{
	return Yii::t("default",$words,$params);
}

function uploadURL()
{
	return Yii::app()->request->baseUrl."/upload";
}

function assetsURL()
{
	return Yii::app()->request->baseUrl."/assets";
}

function ccController()
{
	return "/".Yii::app()->controller->id;
}

function displayPrice($currency='',$amount='')
{
	return Yii::app()->functions->displayPrice($currency,$amount);
}

function getWebsiteName()
{
	return Yii::app()->functions->getWebsiteName();
}

function smarty($search='',$value='',$subject='')
{	
   return Yii::app()->functions->smarty($search,$value,$subject);
}

function getAdminGlobalSender()
{
	$from=Yii::app()->functions->getOptionAdmin("global_admin_sender_email");	    				
	if (empty($from)){
		$from='no-reply@'.$_SERVER['HTTP_HOST'];
	}
	return $from;
}

function initialStatus()
{
	return 'initial_order';
}

function websiteUrl()
{
	return Yii::app()->getBaseUrl(true);
}

function membershipType($is_commission='')
{
	if ($is_commission==2){
		return t("Commission");
	} else return t("Membership");
}

function withdrawalStatus()
{
	return Yii::app()->functions->withdrawalStatus();
}

function displayDate($date='')
{
	return Yii::app()->functions->displayDate($date);
}

function createUrl($url='')
{
	return Yii::app()->createUrl($url);
}

function qTranslate($text='',$key='',$data='',$cookie_lang_id='kr_lang_id')
{		
	$p = new CHtmlPurifier();
	if (Yii::app()->functions->getOptionAdmin("enabled_multiple_translation")!=2){
		return stripslashes($text);
	}
	$key=$key."_trans";				
	$id=Yii::app()->language;	
	//dump($text);dump($key); dump($data);
	if(!empty($id)){
		if (is_array($data) && count($data)>=1){
			if (isset($data[$key])){					
				if (array_key_exists($id,(array)$data[$key])){
					if (!empty($data[$key][$id])){							
					    //return stripslashes($data[$key][$id]);					    
					    return $p->purify(stripslashes($data[$key][$id]));
					}
				}
			}
		}
	}			
	return $p->purify(stripslashes($text));
	//return stripslashes($text);
}

function okToDecode()
{
	$version=phpversion();		
	if ( $version>5.3){
		return true;
	}
	return false;
}

function geoCoding($lat='',$lng='')
{
	return Yii::app()->functions->geoCoding($lat,$lng);
}

function q($data='')
{
	return Yii::app()->db->quoteValue($data);
}	

function getNextClientID()
{
	$DbExt=new DbExt; 
	$stmt="
	SHOW TABLE STATUS WHERE name='{{client}}'
	";		
	if ($res=$DbExt->rst($stmt)){
		return $res[0]['Auto_increment'].Yii::app()->functions->generateRandomKey(3);
	}
	return false;
}

function clearString($text='')
{
	if(!empty($text)){
	   return stripslashes($text);
	}
	return ;
}