<?php
//if (!isset($_SESSION)) { session_start(); }

class StoreController extends CController
{
	public $layout='store_tpl';	
	public $crumbsTitle='';
	public $theme_compression='';
	public $is_rtl=false;
	
	public function beforeAction($action)
	{
		//$cs->registerCssFile($baseUrl.'/css/yourcss.css'); 		
		if( parent::beforeAction($action) ) {			
			
						
			$google_key=getOptionA('google_geo_api_key');
			$website_use_time_picker = Yii::app()->functions->getOptionAdmin('website_use_time_picker');
			$theme_time_pick = Yii::app()->functions->getOptionAdmin('theme_time_pick');
			
			$config = array(			  			  
			  'website_use_time_picker'=>$website_use_time_picker,
			  'theme_time_pick'=>$theme_time_pick
			);
			
			/** Register all scripts here*/
			if ($this->theme_compression==2){
				/*ScriptManagerCompress::RegisterAllJSFile($config);
			    ScriptManagerCompress::registerAllCSSFiles($config);
			   
				$compress_css = require_once 'assets/css/css.php';
			    $cs = Yii::app()->getClientScript();
			    Yii::app()->clientScript->registerCss('compress-css',$compress_css);*/
				ScriptManager::RegisterAllJSFile($config);
			    ScriptManager::registerAllCSSFiles($config);
			} else {
			    ScriptManager::RegisterAllJSFile($config);
			    ScriptManager::registerAllCSSFiles($config);
			}
			
			$action_name = $action->id ;
			$action_name=strtolower($action_name);

			$cs = Yii::app()->getClientScript();			
			$cs->registerScript(
			  'current_page',
			 "var current_page='$action_name';",
			  CClientScript::POS_HEAD
			);
			$cs->registerScript(
			  'card_fee',
			 "var card_fee='';",
			  CClientScript::POS_HEAD
			);
			
			$csrfTokenName = Yii::app()->request->csrfTokenName;
            $csrfToken = Yii::app()->request->csrfToken;            
            $cs->registerScript(
			  "csrf_token",
			  "var csrf_token='$csrfToken';",
			  CClientScript::POS_HEAD
			);
 					
	        /*ADD SECURITY VALIDATION*/
			$yii_session_token=session_id();		
			$cs->registerScript(
			  'yii_session_token',
			 "var yii_session_token='$yii_session_token';",
			  CClientScript::POS_HEAD
			);				
			$csrfTokenName = Yii::app()->request->csrfTokenName;
	        $csrfToken = Yii::app()->request->csrfToken;
			$cs->registerScript(
			  "$csrfTokenName",
			 "var $csrfTokenName='$csrfToken';",
			  CClientScript::POS_HEAD
			);
			
			$image_limit_size = FunctionsV3::imageLimitSize();
		
			$cs->registerScript(
			  'image_limit_size',
			 "var image_limit_size='$image_limit_size';",
			  CClientScript::POS_HEAD
			);	
						
			$act_menu=FunctionsV3::getTopMenuActivated();			
			if(in_array('driver_signup',(array)$act_menu)){
				Yii::app()->clientScript->registerCss('menu_css', '
				     #menu a{
				       font-size:13px;
				     }
				');
			}
			return true;
		}
		return false;
	}
	
	public function accessRules()
	{		
		
	}
	
    public function filters()
    {
    	$this->theme_compression = getOptionA('theme_compression');
		if ($this->theme_compression==2){
	        $filters = array(  
	            array(
	                'application.filters.HtmlCompressorFilter',
	            ),  
	        );
	        return $filters;
		}
    }	
		
	public function init()
	{		
		 /*CHECK IF KMRS IS ALREADY INSTALL*/		
		 if (!FunctionsV3::checkIfTableExist('option')){
		 	 $this->redirect(Yii::app()->request->baseUrl."/index.php/install");
		 }
		
		 $name=Yii::app()->functions->getOptionAdmin('website_title');
		 if (!empty($name)){		 	
		 	 Yii::app()->name = $name;
		 }
		 		 
		 // set website timezone
		 $website_timezone=Yii::app()->functions->getOptionAdmin("website_timezone");		 
		 if (!empty($website_timezone)){		 	
		 	Yii::app()->timeZone=$website_timezone;
		 }		 		 
		 
		 FunctionsV3::handleLanguage();
		 $cs = Yii::app()->getClientScript();
		 $lang=Yii::app()->language;		 
		 $cs->registerScript(
		   'lang',
		   "var lang='$lang';",
		   CClientScript::POS_HEAD
		 );
		 
		 $age_restriction=getOptionA('age_restriction');
		 $cs->registerScript(
		   'age_restriction',
		   "var age_restriction='$age_restriction';",
		   CClientScript::POS_HEAD
		 );
		 $restriction_exit_link=getOptionA('age_restriction_exit_link');
		 if (empty($restriction_exit_link)){
		 	$restriction_exit_link="http://google.com";
		 }
		 $cs->registerScript(
		   'restriction_exit_link',
		   "var restriction_exit_link='$restriction_exit_link';",
		   CClientScript::POS_HEAD
		 );
		 
		 /*ADD RTL CLASS*/
		 /*$theme_enabled_rtl = getOptionA('theme_enabled_rtl');
		 if($theme_enabled_rtl==2){
		 	$this->is_rtl=true;
		 }*/
		 if (FunctionsV3::isLangRTL($lang)){
		 	$this->is_rtl=true;
		 }			 		 
	}
	
	public function actionHome()
	{
		//Cookie::removeCookie('kr_location_search');
		unset($_SESSION['voucher_code']);
        unset($_SESSION['less_voucher']);
        unset($_SESSION['google_http_refferer']); 
        
		if (isset($_GET['token'])){
			if (!empty($_GET['token'])){
			    //Yii::app()->functions->paypalSetCancelOrder($_GET['token']);
			}
		}
							
		$seo_title=Yii::app()->functions->getOptionAdmin('seo_home');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_home_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_home_keywords');
					
		if (!empty($seo_title)){
		   $seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		   $this->pageTitle=$seo_title;
		   Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}

		$map = FunctionsV3::getMapProvider();
		if($map['provider']=="mapbox"){
			
			$search_address = isset($_SESSION['kr_search_address'])?$_SESSION['kr_search_address']:'';
			
			$baseUrl = Yii::app()->baseUrl; 
			$cs = Yii::app()->getClientScript();
			
			$cs->registerCssFile($baseUrl."/assets/vendor/leaflet/plugin/geocoder/mapbox-gl-geocoder.css");
			$cs->registerScriptFile($baseUrl."/assets/vendor/leaflet/plugin/geocoder/mapbox-gl-geocoder.min.js"
			,CClientScript::POS_END); 	
			
			if(!empty($search_address)){
				$cs->registerScript(
				  'mapbox_search_address',
				  "var mapbox_search_address ='".$search_address."'; ",
				  CClientScript::POS_HEAD
				);
			}
			
		}
		
		$this->render('index',array(
		   'home_search_mode'=>getOptionA('home_search_mode'),
		   'enabled_advance_search'=> getOptionA('enabled_advance_search'),
		   'theme_hide_how_works'=>getOptionA('theme_hide_how_works'),
		   'theme_hide_cuisine'=>getOptionA('theme_hide_cuisine'),
		   'disabled_featured_merchant'=>getOptionA('disabled_featured_merchant'),
		   'disabled_subscription'=>getOptionA('disabled_subscription'),
		   'theme_show_app'=>getOptionA('theme_show_app'),
		   'theme_app_android'=>FunctionsV3::prettyUrl(getOptionA('theme_app_android')),
		   'theme_app_ios'=>FunctionsV3::prettyUrl(getOptionA('theme_app_ios')),
		   'theme_app_windows'=>FunctionsV3::prettyUrl(getOptionA('theme_app_windows')),
		   'map_provider'=>$map
		));
	}
				  
	public function actionIndex()
	{								
		if(!FunctionsV3::isSearchByLocation()){
		    ScriptManager::includeMappLibrary();
		    $enabled_advance_search  = getOptionA('enabled_advance_search');
		    if($enabled_advance_search=="yes"){
		        ScriptManager::includeTypeHead();
		    }
		} else {
			ScriptManager::includeTypeHead();
		}	
		$this->actionHome();
	}	
	
	public function actionCuisine()
	{
		
		ScriptManager::includeMappLibrary(true);
		$search_by_location = FunctionsV3::isSearchByLocation();
				
		$slug=isset($_GET['slug'])?$_GET['slug']:'';	
		
		$cuisine_id=0;
		if($resp = FunctionsV3::getCuisineBySlug($slug)){			
			$cuisine_id = $resp['cuisine_id'];			
		}	
					
		if($search_by_location){
			if(isset($_GET['city_id']) || isset($_GET['postal_code']) ){
				$data = $_GET;
				Cookie::setCookie('kr_location_search',json_encode($data));	
				$this->redirect(Yii::app()->createUrl("store/cuisine?category=$cuisine_id")); 	
				Yii::app()->end();
			}
						
			if (!$location_data = FunctionsV3::getSearchByLocationData()){
				ScriptManager::includeTypeHead();
				$this->render('enter_location',array(				 
				 'form_action'=>"store/cuisine?category=$cuisine_id",
				 'search_type'=>getOptionA('admin_zipcode_searchtype')
				));
				Yii::app()->end();
			}		
		} else {
			
			if(isset($_GET['s'])){
				if(!isset($_SESSION['kr_search_address']) || !isset($_SESSION['client_location']['lat']) ){			
					$_SESSION['kr_search_address'] = FunctionsV3::purify($_GET['s']);
					try {
						$resp = MapsWrapper::geoCodeAdress($_SESSION['kr_search_address']);					
						$_SESSION['client_location']['lat'] = $resp['lat'];
						$_SESSION['client_location']['long'] = $resp['long'];
					} catch (Exception $e) {					
					}				
				}
			}
		
			if(!isset($_SESSION['kr_search_address']) || !isset($_SESSION['client_location']['lat']) ){				
				$provider = FunctionsV3::getMapProvider();
				$this->render('enter_address',array(
				 'map_provider'=>$provider,
				 'form_action'=>"cuisine/$slug"				 
				));
				Yii::app()->end();
			}
		}	
		
		/*update merchant if expired and sponsored*/
		Yii::app()->functions->updateMerchantSponsored();
		Yii::app()->functions->updateMerchantExpired();						
		 
		 if (!isset($_GET['filter_cuisine'])){
		 	$_GET['filter_cuisine']='';
		 }

		 
		$_GET['filter_cuisine']=$_GET['filter_cuisine'].",$cuisine_id";		
		 			 
	    $res=FunctionsV3::searchByMerchant(
		   'kr_search_category',
		   isset($_GET['st'])?$_GET['st']:'',
		   isset($_GET['page'])?$_GET['page']:0,
		   FunctionsV3::getPerPage(),
		   $_GET			  
		);		
		
		if(empty($slug)){
			$res = false;
		}	
		
		$this->render('merchant-list-cuisine',array(
		  'list'=>$res,
		  'category'=>isset($category)?$category:'',
		  'search_by_location'=>$search_by_location
		));
	}
	
	public function actionSignup()
	{
		$cs = Yii::app()->getClientScript();
		$baseUrl = Yii::app()->baseUrl; 
		$cs->registerScriptFile($baseUrl."/assets/js/fblogin.js?ver=1",CClientScript::POS_END); 
		    
		if (Yii::app()->functions->isClientLogin()){
			$this->redirect(Yii::app()->createUrl('/store')); 
			die();
		}
		
		$act_menu=FunctionsV3::getTopMenuActivated();
		if (!in_array('signup',(array)$act_menu)){
			$this->render('404-page',array('header'=>true));
			return ;
		}	
		
		$fb=1;
		$fb_app_id=getOptionA('fb_app_id');
		$fb_flag=getOptionA('fb_flag');
		
		if ( $fb_flag=="" && $fb_app_id<>""){
			$fb=2;
		}
		
		$this->render('signup',array(
		   'terms_customer'=>getOptionA('website_terms_customer'),
		   'terms_customer_url'=>Yii::app()->functions->prettyLink(getOptionA('website_terms_customer_url')),
		   'fb_flag'=>$fb,
		   'google_login_enabled'=>getOptionA('google_login_enabled'),
		   'captcha_customer_login'=>getOptionA('captcha_customer_login'),
		   'captcha_customer_signup'=>getOptionA('captcha_customer_signup'),
		   'customer_forgot_password_sms'=>getOptionA('customer_forgot_password_sms')
		));
	}
	
	public function actionSignin()
	{
		$this->render('index');
	}
	
	public function actionMerchantSignup()
	{		
		
		$act_menu=FunctionsV3::getTopMenuActivated();
		if (!in_array('resto_signup',(array)$act_menu)){
			$this->render('404-page',array('header'=>true));
			return ;
		}
		
		$seo_title=Yii::app()->functions->getOptionAdmin('seo_merchantsignup');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_merchantsignup_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_merchantsignup_keywords');
		
		if (!empty($seo_title)){
		   $seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		   $this->pageTitle=$seo_title;
		   Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
		
		if(isset($_GET['package_id'])){
			$_GET['id']=$_GET['package_id'];
		}	
		
		if (isset($_GET['Do'])){
			$_GET['do']=$_GET['Do'];
		}	
		
		//dump($_GET);
		
		if (isset($_GET['do'])){
			switch ($_GET['do']) {
				case 'step2':
					$this->render('merchant-signup-step2',array(
					  'data'=>Yii::app()->functions->getPackagesById($_GET['package_id']),
					  'limit_post'=>Yii::app()->functions->ListlimitedPost(),
					  'terms_merchant'=>getOptionA('website_terms_merchant'),
					  'terms_merchant_url'=>getOptionA('website_terms_merchant_url'),
					  'package_list'=>Yii::app()->functions->getPackagesList(),
					  'kapcha_enabled'=>getOptionA('captcha_merchant_signup')
					));		
					break;
				case "step3":
					 $renew=false;
					 $package_id=isset($_GET['package_id'])?$_GET['package_id']:'';  
					 if (isset($_GET['action'])){	 
						 $renew=true;
					 } 
					 if (isset($_GET['renew'])){	 
						 $renew=true;
					 } 
					 if(isset($_GET['internal-token'])){
					 	$_GET['token']=$_GET['internal-token'];
					 }
					 $this->render('merchant-signup-step3',array(
					    'merchant'=>Yii::app()->functions->getMerchantByToken($_GET['token']),
					    'package_id'=>$package_id,
					    'renew'=>$renew					    
					 ));		
					break;
				case "step3a":
					 $this->render('merchant-signup-step3a');		
					break;	
				case "step3b":					    
					if (isset($_GET['gateway'])){
						if ($_GET['gateway']=="mcd"){
							$this->render('mercado-init');
						} elseif ( $_GET['gateway']=="pyl" ){
							$this->render('payline-init2');
						} elseif ( $_GET['gateway']=="ide" ){
							$this->render('sow-init');
						} elseif ( $_GET['gateway']=="payu" ){							
							$this->render('pau-init');	
						} elseif ( $_GET['gateway']=="pys" ){							
							$this->render('paysera-init');	
						} elseif ( $_GET['gateway']=="rzr" ){							
							$this->render('rzr-init-merchant');		
						} else {
							$this->render($_GET['gateway'].'-init');	
						}
					} else $this->render('merchant-signup-step3b');
					break;		
					
				case "step4":									
				     $disabled_verification=Yii::app()->functions->getOptionAdmin('merchant_email_verification');
				     if ( $disabled_verification=="yes"){
				     	$this->render('merchant-signup-thankyou2',array(
				     	  'data'=>Yii::app()->functions->getMerchantByToken($_GET['token'])
				     	));		
				     } else {			
				     					     	
				     	 $continue=true;
						 if ($merchant=Yii::app()->functions->getMerchantByToken($_GET['token'])){	
							if ( $merchant['package_price']>=1){
								if ($merchant['payment_steps']!=3){
									$continue=false;
								}
							}
						 } else $continue=false;
						 						 						
				     	 /*check if payment was offline*/
				     	 $is_offline_paid=false;
			      	 	 if ( $package_info=FunctionsV3::getMerchantPaymentMembership($merchant['merchant_id'],
			      	 	 $merchant['package_id'])){						      	 	 	  	 	
			      	 		$offline_payment=FunctionsV3::getOfflinePaymentList();			      	 		
			      	 		if ( array_key_exists($package_info['payment_type'],(array)$offline_payment)){
			      	 			$is_offline_paid=true;
			      	 		}
			      	 	 }			

			      	 	 if ($is_offline_paid){
			      	 	 	$this->render('merchant-signup-thankyou2',array(
				     	       'data'=>$merchant
				     	    ));		
			      	 	 } else {				   			      	 	   		     						 
					     	 $this->render('merchant-signup-step4',array(
					            'continue'=>$continue
					         ));						
			      	 	 }	 
				     }
					break;	
					
				case "thankyou1":
					 $this->render('merchant-signup-thankyou1',array(
					   'data'=>Yii::app()->functions->getMerchantByToken($_GET['token'])
					 ));		
					break;		
				case "thankyou2":
					$this->render('merchant-signup-thankyou2',array(
					  'data'=>Yii::app()->functions->getMerchantByToken($_GET['token'])
					));		
					break;		
				case "thankyou3":
					$this->render('merchant-signup-thankyou3',array(
					  'data'=>Yii::app()->functions->getMerchantByToken($_GET['token'])
					));		
					break;			
				default:
					$this->render('merchant-signup',array(
					    'list'=>Yii::app()->functions->getPackagesList(),
		               'limited_post'=>Yii::app()->functions->ListlimitedPost()
					));		
					break;
			}
		} else {
			$disabled_membership_signup=getOptionA('admin_disabled_membership_signup');
			if($disabled_membership_signup==1){				
				$this->render('404-page',array('header'=>true));
			} else {
				$this->render('merchant-signup',array(
			      'list'=>Yii::app()->functions->getPackagesList(),
			      'limited_post'=>Yii::app()->functions->ListlimitedPost()
			    ));						
			}
		}
	}
	
	public function actionAbout()
	{
		$this->render('index');
	}
	
	public function actionContact()
	{
		
		ScriptManager::includeMappLibrary();
		
		$act_menu=FunctionsV3::getTopMenuActivated();
		if (!in_array('contact',(array)$act_menu)){
			$this->render('404-page',array('header'=>true));
			return ;
		}	
		
		$seo_title=Yii::app()->functions->getOptionAdmin('seo_contact');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_contact_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_contact_keywords');
		
		if (!empty($seo_title)){
			$seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		    $this->pageTitle=$seo_title;
		    Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
		
		$website_map_location=array(
		  'map_latitude'=>getOptionA('map_latitude'),
		  'map_longitude'=>getOptionA('map_longitude'),
		);
				
		$address=getOptionA('website_address');		
		
		if (empty($website_map_location['map_latitude'])){		
			if ($lat_res=Yii::app()->functions->geodecodeAddress($address)){				
				$website_map_location['map_latitude']=$lat_res['lat'];
				$website_map_location['map_longitude']=$lat_res['long'];
	    	} 
		}
						
		$cs = Yii::app()->getClientScript();
		$cs->registerScript(
		  'website_location',
		  'var website_location = '.json_encode($website_map_location).'
		  ',
		  CClientScript::POS_HEAD
		);
		
		$disabled_map = getOptionA('contact_map');						
		$cs->registerScript(
		  'contact_disabled_map',
		  "var contact_disabled_map ='".$disabled_map."'; ",
		  CClientScript::POS_HEAD
		);
		
			
		$this->render('contact',array(
		  'address'=>$address,
		  'website_title'=>getOptionA('website_title'),
		  'contact_phone'=>getOptionA('website_contact_phone'),
		  'contact_email'=>getOptionA('website_contact_email'),
		  'contact_content'=>getOptionA('contact_content'),
		  'country'=>Yii::app()->functions->adminCountry()		  
		));
	}
	
	public function actionSearchArea()
	{

		ScriptManager::includeMappLibrary();
		
		$res = '';
		
		unset($_SESSION['confirm_order_data']);
		unset($_SESSION['kr_delivery_options']);
		
		
		$seo_title=Yii::app()->functions->getOptionAdmin('seo_search');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_search_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_search_keywords');
		
		if (!empty($seo_title)){
			$seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		    $this->pageTitle=$seo_title;
		    Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
		
		if(isset($_GET)){
			$_GET = FunctionsV3::purifyData($_GET);		
		}
		
		$_SESSION['search_type']='';
		if (isset($_GET['s'])){
			$_SESSION['kr_search_address']=$_GET['s'];
			$_SESSION['search_type']='kr_search_address';
			Cookie::setCookie('kr_search_address',$_GET['s']);
		}
		
		if (isset($_GET['foodname'])){
			$_SESSION['kr_search_foodname']=$_GET['foodname'];
			$_SESSION['search_type']='kr_search_foodname';
		}
		
		if (isset($_GET['category'])){
			$_SESSION['kr_search_category']=$_GET['category'];
			$_SESSION['search_type']='kr_search_category';
		}
		
		if (isset($_GET['restaurant-name'])){
			$_SESSION['kr_search_restaurantname']=$_GET['restaurant-name'];
			$_SESSION['search_type']='kr_search_restaurantname';
		}
		
		if (isset($_GET['street-name'])){
			$_SESSION['kr_search_streetname']=$_GET['street-name'];
			$_SESSION['search_type']='kr_search_streetname';
		}
		
		if (isset($_GET['zipcode'])){
			$_SESSION['search_type']='kr_postcode';
			$_SESSION['kr_postcode']=isset($_GET['zipcode'])?$_GET['zipcode']:'';
		}
		
		if(isset($_GET['location'])){
			$_SESSION['search_type']='kr_search_location';
		}
		
		unset($_SESSION['kr_item']);
		unset($_SESSION['kr_merchant_id']);
		
		$filter_cuisine=isset($_GET['filter_cuisine'])?explode(",",$_GET['filter_cuisine']):false;
		$filter_delivery_type=isset($_GET['filter_delivery_type'])?$_GET['filter_delivery_type']:'';		
		$filter_minimum=isset($_GET['filter_minimum'])?$_GET['filter_minimum']:'';
		$sort_filter=isset($_GET['sort_filter'])?$_GET['sort_filter']:'';		
		$display_type=isset($_GET['display_type'])?$_GET['display_type']:'';
		$restaurant_name=isset($_GET['restaurant_name'])?$_GET['restaurant_name']:'';
						
		$current_page_get=$_GET;
		unset($current_page_get['page']);				
		$current_page_link=Yii::app()->createUrl('store/searcharea/',$current_page_get);
		$current_page_url='';
				
		
		/*update merchant if expired and sponsored*/
		Yii::app()->functions->updateMerchantSponsored();
		Yii::app()->functions->updateMerchantExpired();
		
		/*  switch between search type */						
		switch ($_SESSION['search_type']) {
			case "kr_search_address":
				if (isset($_GET['s'])){
					$res=FunctionsV3::searchByAddress(
					  isset($_GET['s'])?$_GET['s']:'' ,
					  isset($_GET['page'])?$_GET['page']:0,
					  FunctionsV3::getPerPage(),
					  $_GET			  
					);
				}		
				$current_page_url=Yii::app()->createUrl('store/searcharea/',array(
				  's'=>isset($_GET['s'])?$_GET['s']:''
				));										
				break;
						
			case "kr_search_restaurantname":				
				 $res=FunctionsV3::searchByMerchant(
				   $_SESSION['search_type'],
				   isset($_GET['st'])?$_GET['st']:'',
				   isset($_GET['page'])?$_GET['page']:0,
				   FunctionsV3::getPerPage(),
				   $_GET			  
				 );					
				 $current_page_url=Yii::app()->createUrl('store/searcharea/',array(
				  'st'=>isset($_GET['st'])?$_GET['st']:'',
				  'restaurant-name'=>isset($_GET['restaurant-name'])?$_GET['restaurant-name']:''
				));													 
				 break;
				 
			case "kr_search_streetname":	 
			      $res=FunctionsV3::searchByMerchant(
				   $_SESSION['search_type'],
				   isset($_GET['st'])?$_GET['st']:'',
				   isset($_GET['page'])?$_GET['page']:0,
				   FunctionsV3::getPerPage(),
				   $_GET			  
				 );			

				 $current_page_url=Yii::app()->createUrl('store/searcharea/',array(
				  'st'=>isset($_GET['st'])?$_GET['st']:'',
				  'street-name'=>isset($_GET['street-name'])?$_GET['street-name']:''
				));													 
							 
			     break;
			     
			case "kr_search_category":    
						
				 if ( $cat_res=Yii::app()->functions->GetCuisineByName( isset($_GET['category'])?$_GET['category']:'' )){
					$cuisine_id=$cat_res['cuisine_id'];
				 } else $cuisine_id="-1";
				 $filter_cuisine[]=$cuisine_id;				 
				 
				 if (!isset($_GET['filter_cuisine'])){
				 	$_GET['filter_cuisine']='';
				 }
				 
				 $_GET['filter_cuisine']=$_GET['filter_cuisine'].",$cuisine_id";
				 				
			     $res=FunctionsV3::searchByMerchant(
				   $_SESSION['search_type'],
				   isset($_GET['st'])?$_GET['st']:'',
				   isset($_GET['page'])?$_GET['page']:0,
				   FunctionsV3::getPerPage(),
				   $_GET			  
				 );			

				 $current_page_url=Yii::app()->createUrl('store/searcharea/',array(
				  'st'=>isset($_GET['st'])?$_GET['st']:'',
				  'category'=>isset($_GET['category'])?$_GET['category']:''
				));													 			 
			     break;
				
			case "kr_search_foodname":
				
				$res=FunctionsV3::searchByMerchant(
				   $_SESSION['search_type'],
				   isset($_GET['st'])?$_GET['st']:'',
				   isset($_GET['page'])?$_GET['page']:0,
				   FunctionsV3::getPerPage(),
				   $_GET			  
				 );			
				 $current_page_url=Yii::app()->createUrl('store/searcharea/',array(
				  'st'=>isset($_GET['st'])?$_GET['st']:'',
				  'foodname'=>isset($_GET['foodname'])?$_GET['foodname']:''
				));													 			 					 
			     break;
				
			case "kr_postcode":     			   
			    $res=FunctionsV3::searchByMerchant(
				   $_SESSION['search_type'],
				   isset($_GET['st'])?$_GET['st']:'',
				   isset($_GET['page'])?$_GET['page']:0,
				   FunctionsV3::getPerPage(),
				   $_GET			  
				 );			
				 $current_page_url=Yii::app()->createUrl('store/searcharea/',array(
				  'zipcode'=>isset($_GET['zipcode'])?$_GET['zipcode']:''
				));		
			    break;
			    
			case "kr_search_location":    
			    $res=FunctionsV3::searchByMerchant(
				   $_SESSION['search_type'],
				   '',
				   isset($_GET['page'])?$_GET['page']:0,
				   FunctionsV3::getPerPage(),
				   $_GET			  
				 );			
				 $current_page_url=Yii::app()->createUrl('store/searcharea/',array(
				  'location'=>true
				));		
			break;
			    
			default:
				break;
		}
										
		if (empty($display_type)){
			if ( !empty($_SESSION['krms_display_type']) ){				
				$display_type=$_SESSION['krms_display_type'];
			} else {		
				$display_type=getOptionA('theme_list_style');
				if (empty($display_type)){
				    $display_type='gridview';	
				}
			}
		}
		
		$_SESSION['krms_display_type']=$display_type;	
								
		if (is_array($res) && count($res)>=1){			
						
			$_SESSION['client_location']=$res['client'];						
			Cookie::setCookie('client_location', json_encode($res['client']) );
			
			$this->render('search-results',array(
			  'data'=>$res,
			  'filter_delivery_type'=>$filter_delivery_type,
			  'filter_cuisine'=>$filter_cuisine,
			  'filter_minimum'=>$filter_minimum,
			  'sort_filter'=>$sort_filter,
			  'display_type'=>$display_type,
			  'restaurant_name'=>$restaurant_name,
			  'current_page_link'=>$current_page_link,
			  'current_page_url'=>$current_page_url,
			  'fc'=>getOptionA('theme_filter_colapse'),
			  'enabled_search_map'=>getOptionA('enabled_search_map'),
			  'search_by_location'=>FunctionsV3::isSearchByLocation()
			));
			$_SESSION['kmrs_search_stmt']=$res['sql'];			
		} else {
			$has_filter=false;
			if (isset($_GET['filter_minimum'])){$has_filter=true;}		
			if (isset($_GET['filter_delivery_type'])){$has_filter=true;}		
			if (isset($_GET['filter_cuisine'])){$has_filter=true;}
			if ($has_filter){
				$this->render('search-results',array(
				  'data'=>$res,
				  'filter_delivery_type'=>$filter_delivery_type,
				  'filter_cuisine'=>$filter_cuisine,
				  'filter_minimum'=>$filter_minimum,
				  'sort_filter'=>$sort_filter,
				  'display_type'=>$display_type,
				  'restaurant_name'=>$restaurant_name,
				  'current_page_url'=>isset($current_page_url)?$current_page_url:'',
				  'fc'=>getOptionA('theme_filter_colapse'),
				  'enabled_search_map'=>getOptionA('enabled_search_map'),
			   ));
			} else $this->render('search-results-nodata');							
		}	
	}
	
	public function actionMenu()
	{		
		
		ScriptManager::includeMappLibrary(true);
		$enabled_food_search_menu = getOptionA('enabled_food_search_menu');
		if($enabled_food_search_menu==1){
			ScriptManager::includeTypeHead();
		}	
		
		
		unset($_SESSION['kr_receipt']);
		unset($_SESSION['confirm_order_data']);
				
		$data=$_GET; $page_slug='';		
		$current_merchant='';
		if (isset($_SESSION['kr_merchant_id'])){
			$current_merchant=$_SESSION['kr_merchant_id'];
		}
					
		$page_slug = isset($data['slug'])?trim($data['slug']):'';			
		if(empty($page_slug)){
			$url=isset($_SERVER['REQUEST_URI'])?explode("/",$_SERVER['REQUEST_URI']):false;
			if(!is_array($url) && count($url)<=0){		
				 $this->render('404-page',array(
				   'header'=>true,
				  'msg'=>"Sorry but we cannot find what you are looking for"
				));			
				return ;
			}			
			$page_slug=$url[count($url)-1];
			$page_slug=str_replace('menu-','',$page_slug);			
			if(isset($_GET)){				
				$c=strpos($page_slug,'?');
				if(is_numeric($c)){
					$page_slug=substr($page_slug,0,$c);
				}
			}			
		}	
				
		$res=FunctionsV3::getMerchantBySlug($page_slug);
		
		if (is_array($res) && count($res)>=1){
			if ( $current_merchant !=$res['merchant_id']){							 
				 unset($_SESSION['kr_item']);
			}		
			
			//dump($res);
			
			if ( $res['status']=="active" && $res['is_ready']==2){
								
				/*SEO*/
				$seo_title=Yii::app()->functions->getOptionAdmin('seo_menu');
				$seo_meta=Yii::app()->functions->getOptionAdmin('seo_menu_meta');
				$seo_key=Yii::app()->functions->getOptionAdmin('seo_menu_keywords');
				
				if (!empty($seo_title)){
					$seo_title=smarty('website_title',getWebsiteName(),$seo_title);
					$seo_title=smarty('merchant_name',ucwords($res['restaurant_name']),$seo_title);		    
				    $this->pageTitle=$seo_title;
				    
				    $seo_meta=smarty('merchant_name',ucwords($res['restaurant_name']),$seo_meta);
				    $seo_key=smarty('merchant_name',ucwords($res['restaurant_name']),$seo_key);		    
				    
				    Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
				}
				/*END SEO*/
				
				unset($_SESSION['guest_client_id']);
				
				$merchant_id=$res['merchant_id'];				
				
				/*SET TIME*/
				$mt_timezone=Yii::app()->functions->getOption("merchant_timezone",$merchant_id);				
		    	if (!empty($mt_timezone)){       	 	
		    		Yii::app()->timeZone=$mt_timezone;
		    	}		   		
		    			    	
		    	$distance_type='';
		    	$distance='';
		    	$merchant_delivery_distance = isset($res['delivery_distance_covered'])?(float)$res['delivery_distance_covered']:0;
		    	$delivery_fee=0; 		    	
		    	$unit_pretty='';
		    	$min_fees=0;
		    	$distance_pretty='';
		    	$ratings = array(
	              'ratings'=>isset($res['ratings'])?(float)$res['ratings']:0,
	              'votes'=>isset($res['ratings_votes'])?(integer)$res['ratings_votes']:0,
	             );
	             $distance_error = '';
		    			    			    	
		    	/*double check if session has value else use cookie*/		    	
		    	FunctionsV3::cookieLocation();
		    	
		    	
		    	/*GET SEARCH LOCATION DATA*/	
		    	$location_data=FunctionsV3::getSearchByLocationData();
		    	$search_by_location=false;
		    	if (FunctionsV3::isSearchByLocation()){
		    		$search_by_location=TRUE;
		    	    $delivery_fee=FunctionsV3::getLocationDeliveryFee(
		    	      $merchant_id,
		    	      $res['delivery_charges'],
		    	      $location_data
		    	    );		  
		    	    $min_fees = isset($res['delivery_minimum_order'])?(float)$res['delivery_minimum_order']:0;
		    	} else {
			    	if (isset($_SESSION['client_location'])){
			    		 try {			    		 				  			    		 	
			    		 	$provider = FunctionsV3::getMapProvider(); 
                 	        MapsWrapper::init($provider);  
			             	$resp = CheckoutWrapper::getDeliveryDetails(array(
			             	  'merchant_id'=>$merchant_id,
			             	  'provider'=>$provider,
			             	  'merchant_id'=>$merchant_id,
			             	  'from_lat'=>isset($res['latitude'])?$res['latitude']:0,
			             	  'from_lng'=>isset($res['lontitude'])?$res['lontitude']:0,
			             	  'to_lat'=>$_SESSION['client_location']['lat'],
			             	  'to_lng'=>$_SESSION['client_location']['long'],
			             	  'delivery_charges'=>isset($res['delivery_charges'])?$res['delivery_charges']:0,
			             	  'unit'=>isset($res['distance_unit'])?$res['distance_unit']:'',
			             	  'delivery_distance_covered'=>isset($res['delivery_distance_covered'])?$res['delivery_distance_covered']:0,
			             	  'order_subtotal'=>0,
			             	  'minimum_order'=>isset($res['minimum_order'])?$res['minimum_order']:0
			             	));	  	 			             	
			             	$distance_error = isset($resp['distance_error'])?$resp['distance_error']:'';
			             	$unit_pretty = $resp['pretty_unit'];
			             	$distance = $resp['distance'];
			             	$distance_pretty = $resp['pretty_distance'];
			             	$delivery_fee = $resp['delivery_fee'];
			             	$min_fees = $resp['min_order'];
			             } catch (Exception $e) {
			             	$distance_pretty = $e->getMessage();			             	
			             	$delivery_fee = 0;
			             }	             		          
			    	}   
		    	}
		    	
		    	//dump($delivery_fee);
		    			    	
		    	/*SESSION REF*/
		    	$_SESSION['kr_merchant_id']=$merchant_id;
                $_SESSION['kr_merchant_slug']=$page_slug;
		    	$_SESSION['shipping_fee']=$delivery_fee;		
		    			    			    	
		    			    	
		    	/*CHECK IF BOOKING IS ENABLED*/
		    	$booking_enabled=true;		    		
		    	if (getOption($merchant_id,'merchant_table_booking')=="yes"){
		    		$booking_enabled=false;
		    	}			
		    	if ( getOptionA('merchant_tbl_book_disabled')){
		    		$booking_enabled=false;
		    	}
		    	
		    	/*CHECK IF MERCHANT HAS PROMO*/
		    	$promo['enabled']=1;
		    	//if($offer=FunctionsV3::getOffersByMerchant($merchant_id,2)){
		    	if($offer=FunctionsV3::getOffersByMerchantNew($merchant_id)){
		    	   $promo['offer']=$offer;
		    	   $promo['enabled']=2;
		    	}		    			
		    	if ( $voucher=FunctionsV3::merchantActiveVoucher($merchant_id)){		    
		    		$promo['voucher']=$voucher;
		    		$promo['enabled']=2;
		    	}
		    	$free_delivery_above_price=getOption($merchant_id,'free_delivery_above_price');
		    	if ($free_delivery_above_price>0){
		    	    $promo['free_delivery']=$free_delivery_above_price;
		    		$promo['enabled']=2;
		    	}
		    	
		    	$photo_enabled=getOption($merchant_id,'gallery_disabled')=="yes"?false:true;
		    	if ( getOptionA('theme_photos_tab')==2){
		    		$photo_enabled=false;
		    	}

		    	$minimum_order_dinein=getOption($merchant_id,'merchant_minimum_order_dinein');
		    	$maximum_order_dinein=getOption($merchant_id,'merchant_maximum_order_dinein');
		    	
	            
				$tbl_booking=getOption( $merchant_id ,'merchant_master_table_boooking');
				if($tbl_booking==1){
					$booking_enabled=false;
				}
				
				$food_viewing_private=getOption($merchant_id,'food_viewing_private');				

				/*CHECK DISABLED ORDERING FROM ADMIN AND MERCHANT SETTINGS*/			
				$disabled_addcart = getOption($merchant_id,'merchant_disabled_ordering');
				if(empty($disabled_addcart)){
					$merchant_master_disabled_ordering= getOption($merchant_id,'merchant_master_disabled_ordering');
					if($merchant_master_disabled_ordering==1){
						$disabled_addcart="yes";
					}
				}
				
				$website_use_date_picker = getOptionA('website_use_date_picker');
				$enabled_category_sked = getOption($merchant_id,'enabled_category_sked');
				$website_review_type=getOptionA('website_review_type');				
				
				$merchant_opt_contact_delivery = getOption($merchant_id,'merchant_opt_contact_delivery');
				
				FunctionsV3::registerScript(array(
				 "var dinein_minimum='$minimum_order_dinein';",
				 "var dinein_max='$maximum_order_dinein';",
				 "var website_use_date_picker='$website_use_date_picker';",
				 "var enabled_category_sked='$enabled_category_sked';",
				 "var website_review_type='$website_review_type';",
				 "var search_by_location='$search_by_location';",
				 "var distance_error='$distance_error';",
				 "var merchant_opt_contact_delivery='$merchant_opt_contact_delivery';",
				));		
				
				/*inventory*/
                if($inv_enabled = FunctionsV3::inventoryEnabled($merchant_id)){
                    Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl."/protected/modules/inventory/assets/css/front.css");                
                    Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl."/protected/modules/inventory/assets/js/inventory.js"
                    ,CClientScript::POS_END);                
                    InventoryWrapper::registerScript(array(
                      "var inv_ajax='".CJavaScript::quote(Yii::app()->request->baseUrl."/inventory/Ajaxfront")."';",
                      "var inv_loader='".CJavaScript::quote( t("loading") )."...';",
                    ),'inventory_script');
                } 

												
				$this->render('menu' ,array(
				   'data'=>$res,
				   'merchant_id'=>$merchant_id,				   
				   'distance'=>$distance,
				   'distance_pretty'=>$distance_pretty,
				   'merchant_delivery_distance'=>(float)$merchant_delivery_distance,
				   'unit_pretty'=>$unit_pretty,
				   'delivery_fee'=>(float)$delivery_fee,
				   'min_fees'=>(float)$min_fees,
				   'ratings'=>$ratings,
				   'disabled_addcart'=>$disabled_addcart,
				   'merchant_website'=>getOption($merchant_id,'merchant_extenal'),
				   'photo_enabled'=>$photo_enabled,
				   'booking_enabled'=>$booking_enabled,
				   'promo'=>$promo,
				   'tc'=>getOptionA('theme_menu_colapse'),
				   'theme_promo_tab'=>getOptionA('theme_promo_tab'),
				   'theme_hours_tab'=>getOptionA('theme_hours_tab'),
				   'theme_reviews_tab'=>getOptionA('theme_reviews_tab'),
				   'theme_map_tab'=>getOptionA('theme_map_tab'),
				   'theme_info_tab'=>getOptionA('theme_info_tab'),
				   'theme_photos_tab'=>getOptionA('theme_photos_tab'),
				   'enabled_food_search_menu'=>$enabled_food_search_menu,
				   'location_data'=>$location_data,
				   'search_by_location'=>$search_by_location,
				   'social_facebook_page'=>getOption($merchant_id,'facebook_page'),
				   'social_twitter_page'=>getOption($merchant_id,'twitter_page'),
				   'social_google_page'=>getOption($merchant_id,'google_page'),
				   'minimum_order_dinein'=>$minimum_order_dinein,
				   'maximum_order_dinein'=>$maximum_order_dinein,
				   'food_viewing_private'=>$food_viewing_private,
				   'website_review_type'=>$website_review_type,
				   'website_use_date_picker'=>$website_use_date_picker,
				   'merchant_opt_contact_delivery'=>$merchant_opt_contact_delivery
				));	
								
			}  else  $this->render('error',array(
		       'message'=>t("Sorry but this merchant is no longer available")
		    ));
			
		} else $this->render('error',array(
		  'message'=>t("merchant is not available")
		));
	}
	
	public function actionCheckout()
	{
		
		if(!isset($_SESSION['kr_item'])){
			$this->redirect(Yii::app()->createUrl('/store/index'));
			Yii::app()->end();
		}
		
		if ( Yii::app()->functions->isClientLogin()){	       			
 	       $this->redirect(Yii::app()->createUrl('/store/paymentoption'));
 	       die();
        }
        
        $cs = Yii::app()->getClientScript();
		$baseUrl = Yii::app()->baseUrl; 
		$cs->registerScriptFile($baseUrl."/assets/js/fblogin.js?ver=1",CClientScript::POS_END); 
		    
		if (Yii::app()->functions->isClientLogin()){
			$this->redirect(Yii::app()->createUrl('/store')); 
			die();
		}
		
		$_SESSION['google_http_refferer']=websiteUrl()."/paymentoption";		
		
		$seo_title=Yii::app()->functions->getOptionAdmin('seo_checkout');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_checkout_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_checkout_keywords');
		
		$current_merchant='';
		if (isset($_SESSION['kr_merchant_id'])){
			$current_merchant=$_SESSION['kr_merchant_id'];
		}
											               		
		if (!empty($seo_title)){
		   $seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		   if ( $info=Yii::app()->functions->getMerchant($current_merchant)){        	
		   	   $seo_title=smarty('merchant_name',ucwords($info['restaurant_name']),$seo_title);
           }		   
		   $this->pageTitle=$seo_title;
		   Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
		
		$fb=1;
		$fb_app_id=getOptionA('fb_app_id');
		$fb_flag=getOptionA('fb_flag');
		
		if ( $fb_flag=="" && $fb_app_id<>""){
			$fb=2;
		}
		
		$this->render('checkout',array(
		   'terms_customer'=>getOptionA('website_terms_customer'),
		   'terms_customer_url'=>Yii::app()->functions->prettyLink(getOptionA('website_terms_customer_url')),
		   'disabled_guest_checkout'=>getOptionA('website_disabled_guest_checkout'),
		   'enabled_mobile_verification'=>getOptionA('website_enabled_mobile_verification'),
		   'fb_flag'=>$fb,
		   'google_login_enabled'=>getOptionA('google_login_enabled'),
		   'captcha_customer_login'=>getOptionA('captcha_customer_login'),
		   'captcha_customer_signup'=>getOptionA('captcha_customer_signup'),
		   'step'=>3,
		   'customer_forgot_password_sms'=>getOptionA('customer_forgot_password_sms')
		));
	}
	
	public function actionPaymentOption()
	{	
		
		ScriptManager::includeMappLibrary();
		
		unset($_SESSION['confirm_order_data']);
		
		/*POINTS PROGRAM*/
		if (FunctionsV3::hasModuleAddon("pointsprogram")){
		   PointsProgram::includeFrontEndFiles();	   
		} 
		
 	    $seo_title=Yii::app()->functions->getOptionAdmin('seo_checkout');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_checkout_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_checkout_keywords');
		
		$current_merchant='';
		if (isset($_SESSION['kr_merchant_id'])){
			$current_merchant=$_SESSION['kr_merchant_id'];
		}
		
		if (!empty($seo_title)){
		   $seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		   if ( $info=Yii::app()->functions->getMerchant($current_merchant)){        	
		   	   $seo_title=smarty('merchant_name',ucwords($info['restaurant_name']),$seo_title);
           }		   
		   $this->pageTitle=$seo_title;		   
		   Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
		
		$client_id = Yii::app()->functions->getClientId();
		$search_by_location = FunctionsV3::isSearchByLocation();				
		
		$has_addressbook=false;
		
		if($search_by_location){
			if (FunctionsV3::hasAddressBook($client_id)){
	         	$has_addressbook=true;
	        }
		}
		
				
		$s=$_SESSION;
		$transaction_type = isset($s['kr_delivery_options']['delivery_type'])?$s['kr_delivery_options']['delivery_type']:'';		
		$enabled_map_selection_delivery = getOptionA('enabled_map_selection_delivery');
		
		$cs = Yii::app()->getClientScript();			
		$cs->registerScript(
		  'enabled_map_selection_delivery',
		 "var enabled_map_selection_delivery='".CJavaScript::quote($enabled_map_selection_delivery)."';",
		  CClientScript::POS_HEAD
		);		
		$cs->registerScript(
		  'transaction_type',
		 "var transaction_type='$transaction_type';",
		  CClientScript::POS_HEAD
		);
		
		if($transaction_type=="delivery"){			
			$temporary_address = isset($_SESSION['kr_search_address'])?$_SESSION['kr_search_address']:getOptionA('admin_country_set');			
			if(empty($temporary_address)){
				$temporary_address=Yii::app()->functions->adminCountry();
			}		
									
			$lat = ''; $lng ='';
			if($res=Yii::app()->functions->geodecodeAddress($temporary_address)){
				$lat = $res['lat'];
				$lng = $res['long'];
			}			
						
			$cs = Yii::app()->getClientScript();
			$cs->registerScript(
			  'temporary_address_lat',
			 "var temporary_address_lat='$lat';",
			  CClientScript::POS_HEAD
			);
			$cs->registerScript(
			  'temporary_address_lng',
			 "var temporary_address_lng='$lng';",
			  CClientScript::POS_HEAD
			);			
		}
		
		$this->render('payment-option',array(		  
		  'address_book'=>Yii::app()->functions->showAddressBook(),
		  'search_by_location'=>$search_by_location,	
		  'client_id'=>$client_id,
		  'has_addressbook'=>$has_addressbook,
		  'enabled_map_selection_delivery'=>$enabled_map_selection_delivery
		));
	}
	
	public function actionReceipt()
	{		
		if (Yii::app()->functions->isClientLogin()){
			$order_id = isset($_GET['id'])?(integer)$_GET['id']:0;
			$client_id = (integer) Yii::app()->functions->getClientId();
			if ($data=FunctionsV3::getReceiptByID($order_id,$client_id)){
				$this->render('receipt',array(
				  'data'=>$data
				));
			} else $this->render('error',array(
			  'message'=>t("Sorry but we cannot find what you are looking for.")
			));
		} else $this->render('error',array(
			  'message'=>t("Sorry but we cannot find what you are looking for.")
			)); 
	}
	
	public function actionLogout()
	{
		unset($_SESSION['kr_client']);
		$http_referer=$_SERVER['HTTP_REFERER'];				
		if (preg_match("/receipt/i", $http_referer)) {
			$http_referer=websiteUrl()."/store";
		}		
		if (preg_match("/orderHistory/i", $http_referer)) {
			$http_referer=websiteUrl()."/store";
		}		
		if (preg_match("/Profile/i", $http_referer)) {
			$http_referer=websiteUrl()."/store";
		}		
		if (preg_match("/Cards/i", $http_referer)) {
			$http_referer=websiteUrl()."/store";
		}		
		if (preg_match("/PaymentOption/i", $http_referer)) {
			$http_referer=websiteUrl()."/store";
		}		
		if (preg_match("/verification/i", $http_referer)) {
			$http_referer=websiteUrl()."/store";
		}		
		if ( !empty($http_referer)){
			header("Location: ".$http_referer);
		} else header("Location: ". Yii::app()->createUrl('/store') );		
	}
	
	public function actionPaypalInit()
	{
		$this->render('paypal-init');
	}
	
	public function actionPaypalVerify()
	{
		$this->render('paypal-verify');
	}
	
	public function actionOrderHistory()
	{
		$this->render('order-history');
	}
	
	public function actionProfile()
	{
		if (Yii::app()->functions->isClientLogin()){		   
		   $this->render('profile',array(
		     'tabs'=>isset($_GET['tab'])?$_GET['tab']:'',
		     'disabled_cc'=>getOptionA('disabled_cc_management'),
		     'info'=>Yii::app()->functions->getClientInfo( Yii::app()->functions->getClientId()),
		     'avatar'=>FunctionsV3::getAvatar( Yii::app()->functions->getClientId() ),
		     'booking_disabled'=>getOptionA('merchant_tbl_book_disabled'),
		   ));
		} else $this->render('404-page',array(
		   'header'=>true
		));
	}
	

	public function actionhowItWork()
	{
		$this->render('dynamic-page');
	}
	
	public function actionforgotPassword()
	{
		if ($res=Yii::app()->functions->getLostPassToken($_GET['token']) ){
			$this->render('forgot-pass');
		} else $this->render('error',array('message'=>t("ERROR: Invalid token.")));
	}
	
	public function actionPage()
	{
		$page_slug = isset($_GET['slug'])?trim($_GET['slug']):'';
		if(empty($page_slug)){
			$url=isset($_SERVER['REQUEST_URI'])?explode("/",$_SERVER['REQUEST_URI']):false;
			if(is_array($url) && count($url)>=1){
				$page_slug=$url[count($url)-1];
				$page_slug=str_replace('page-','',$page_slug);
				if(isset($_GET)){				
					$c=strpos($page_slug,'?');
					if(is_numeric($c)){
						$page_slug=substr($page_slug,0,$c);
					}
				}				
			}
		}	
				
		if ($data=yii::app()->functions->getCustomPageBySlug($page_slug)){
			
			$data = Yii::app()->request->stripSlashes($data);
        	$lang = Yii::app()->language;
        	
        	$page_name_trans['page_name_trans'] =  isset($data['page_name_trans'])? json_decode($data['page_name_trans'],true) : '';	        	
        	$page_name = qTranslate($data['page_name'],'page_name',(array)$page_name_trans);	        	
        	
        	$content_trans['content_trans'] = isset($data['content_trans']) ? json_decode($data['content_trans'],true) : '';	        	
        	$content = qTranslate($data['content'],'content',(array)$content_trans);	        	
        	
        	$seo_title_trans['seo_title_trans'] = isset($data['seo_title_trans']) ?  json_decode($data['seo_title_trans'],true) :'';	        	
        	$seo_title = qTranslate($data['seo_title'],'seo_title',(array)$seo_title_trans);	        	
        	
        	$meta_description_trans['meta_description_trans'] = isset($data['meta_description_trans']) ?  json_decode($data['meta_description_trans'],true) :'';      	
        	$meta_description = qTranslate($data['meta_description'],'meta_description',(array)$meta_description_trans);	        	
        	
        	$meta_keywords_trans['meta_keywords_trans'] = isset($data['meta_keywords_trans']) ? json_decode($data['meta_keywords_trans'],true) :'';
        	$meta_keywords = qTranslate($data['meta_keywords'],'meta_keywords',(array)$meta_keywords_trans);	        	
        	
            /*SET SEO META*/
			if (!empty($data['seo_title'])){
			     $this->pageTitle=$seo_title;
			     Yii::app()->clientScript->registerMetaTag($seo_title, 'title'); 
			}
			if (!empty($data['meta_description'])){   
			     Yii::app()->clientScript->registerMetaTag($meta_description, 'description'); 
			}
			if (!empty($data['meta_keywords'])){   
			     Yii::app()->clientScript->registerMetaTag($meta_keywords, 'keywords'); 
			}
        					
        	$this->render('custom-page',array(
        	  'data'=>array(
        	    'page_name'=>$page_name,
        	    'content'=>$content,
        	  )
        	));
		} else $this->render('404-page',array(
		   'header'=>true,
		  'msg'=>"Sorry but we cannot find what you are looking for"
		));		
	}
	
	public function actionSetlanguage()
	{		
		$redirect='';
		$referrer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
		if(isset($_GET['lang'])){
			if (!empty($referrer)){
				$redirect=$referrer;
			} else $redirect=Yii::app()->createUrl('store/index',array(
			  'lang'=>$_GET['lang']
			));
		} else {
			if (!empty($referrer)){
				$redirect=$referrer;
			} else $redirect=Yii::app()->createUrl('store/index');
		}
				
		$this->redirect($redirect);
	}
		
	public function actionMercadoInit()
	{
		$this->render('mercado-merchant-init');
	}
	
	public function actionRenewSuccesful()
	{
		$this->render('merchant-renew-successful');
	}
	
	public function actionBrowse()
	{		
		ScriptManager::includeMappLibrary(true);
		
		if(isset($_GET['s'])){
			if(!isset($_SESSION['kr_search_address']) || !isset($_SESSION['client_location']['lat']) ){			
				$_SESSION['kr_search_address'] = FunctionsV3::purify($_GET['s']);
				try {
					$resp = MapsWrapper::geoCodeAdress($_SESSION['kr_search_address']);					
					$_SESSION['client_location']['lat'] = $resp['lat'];
					$_SESSION['client_location']['long'] = $resp['long'];
				} catch (Exception $e) {					
				}				
			}
		}
				
		
		if( $search_by_location = FunctionsV3::isSearchByLocation()){
			
			if(isset($_GET['city_id']) || isset($_GET['postal_code']) ){
				$data = $_GET;
				Cookie::setCookie('kr_location_search',json_encode($data));	
				$this->redirect(Yii::app()->createUrl('/store/browse')); 	
				Yii::app()->end();
			}
						
			if (!$location_data = FunctionsV3::getSearchByLocationData()){
				ScriptManager::includeTypeHead();
				$this->render('enter_location',array(				 
				 'form_action'=>"store/browse",
				 'search_type'=>getOptionA('admin_zipcode_searchtype')
				));
				Yii::app()->end();
			}		
		} else {				
			if(!isset($_SESSION['kr_search_address']) || !isset($_SESSION['client_location']['lat']) ){			
				$provider = FunctionsV3::getMapProvider();
				$this->render('enter_address',array(
				 'map_provider'=>$provider,
				 'form_action'=>"store/browse"
				));
				Yii::app()->end();
			}
		}
				
				
		unset($_SESSION['confirm_order_data']);
		
		$act_menu=FunctionsV3::getTopMenuActivated();
		if (!in_array('browse',(array)$act_menu)){
			$this->render('404-page',array('header'=>true));
			return ;
		}
		
        /*update merchant if expired and sponsored*/
		Yii::app()->functions->updateMerchantSponsored();
		Yii::app()->functions->updateMerchantExpired();
		
		if (!isset($_GET['tab'])){
			$_GET['tab']='';
		}
		switch ($_GET['tab']){			
			case 2:
			  $tabs=2;
		      $list=Yii::app()->functions->getAllMerchantNewest();		
		      break;
		      
		    case 3:
			  $tabs=3;
			  $list=Yii::app()->functions->getFeaturedMerchant();	      
		      break;  
		    
		    case "4":
		       break;  
		    	  
			default:
			  $tabs=1;
			  $list=Yii::app()->functions->getAllMerchant();		
			  break;
		}
	
    	$lat_res=array('lat'=>0,'lng'=>0);    	
    	
    	$cs = Yii::app()->getClientScript();
    	$cs->registerScript(
		  'country_coordinates',
		  'var country_coordinates = '.json_encode($lat_res).'
		  ',
		  CClientScript::POS_HEAD
		);
					
		$this->render('browse-resto',array(
		  'list'=>$list,
		  'tabs'=>$tabs,
		  'search_by_location'=>$search_by_location
		));
	}
	
	public function actionPaylineInit()
	{
		$this->render('payline-init');
	}
	
	public function actionPaylineverify()
	{		
		$this->render('payline-verify');
	}
	
	public function actionsisowinit()
	{
		$this->render('sow-init-merchant');
	}
	
	public function actionPayuInit()
	{		
		$this->render('payuinit-merchant');
	}
	
	public function actionBankDepositverify()
	{
		$this->render('bankdeposit-verify');
	}
	
	public function actionAutoResto()
	{		
		$datas=array();
		$str=isset($_POST['search'])?trim($_POST['search']):'';		
		
		$db_ext=new DbExt;
		$stmt="SELECT restaurant_name
		FROM
		{{view_merchant}}
		WHERE
		restaurant_name LIKE ".FunctionsV3::q("%$str%")."
		AND
		status ='active'
		AND
		is_ready='2'
		ORDER BY restaurant_name ASC
		LIMIT 0,20
		";
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {								
				$datas[]=array(				  				
				  'name'=>clearString($val['restaurant_name'])
				);
			}
			echo json_encode($datas);
		}
	}
	
	public function actionAutoStreetName()
	{
		$datas=array();
		$str=isset($_POST['search'])?trim($_POST['search']):'';		
		$db_ext=new DbExt;
		$stmt="SELECT street
		FROM
		{{view_merchant}}
		WHERE
		street LIKE ".FunctionsV3::q("%$str%")."
		AND
		status ='active'
		AND
		is_ready='2'
		GROUP BY street		
		ORDER BY restaurant_name ASC		
		LIMIT 0,20
		";		
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {								
				$datas[]=array(				  				
				  'name'=>$val['street']
				);
			}
			echo json_encode($datas);
		}
	}
	
	public function actionAutoCategory()
	{
		$datas=array();		
		$str=isset($_POST['search'])?trim($_POST['search']):'';
		$db_ext=new DbExt;
		$stmt="SELECT cuisine_name
		FROM
		{{cuisine}}
		WHERE
		cuisine_name LIKE ".FunctionsV3::q("%$str%")."
		ORDER BY cuisine_name ASC
		LIMIT 0,20
		";				
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {								
				$datas[]=array(				  				
				  'name'=>$val['cuisine_name']
				);
			}
			echo json_encode($datas);
		}
	}
	
	public function actionPayseraInit()
	{
		$this->render('merchant-paysera');
	}	
	
	public function actionAutoFoodName()
	{
		$datas=array();		
		$str=isset($_POST['search'])?trim($_POST['search']):'';
		$db_ext=new DbExt;
		$stmt="SELECT item_name
		FROM
		{{item}}
		WHERE
		item_name LIKE ".FunctionsV3::q("%$str%")."
		Group by item_name	
		ORDER BY item_name ASC
		LIMIT 0,16
		";					
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {								
				$datas[]=array(				  				
				  'name'=>$val['item_name']
				);
			}			
			echo json_encode($datas);
		}
	}
	
	public function actionApicheckout()
	{
		$data=$_GET;		
		if (isset($data['token'])){
			$ApiFunctions=new ApiFunctions;		
			if ( $res=$ApiFunctions->getCart($data['token'])){				
				$order='';
				$merchant_id=$res[0]['merchant_id'];
				foreach ($res as $val) {															
					$temp=json_decode($val['raw_order'],true);				
					$temp_1='';
					if(is_array($temp) && count($temp)>=1){						
						$temp_1['row']=$val['id'];
						$temp_1['row_api_id']=$val['id'];
						$temp_1['merchant_id']=$val['merchant_id'];
						$temp_1['currentController']="store";
						foreach ($temp as $key=>$value) {							
							$temp_1[$key]=$value;
						}						
						$order[]=$temp_1;
					}
				}							
				//unset($_SESSION);
				$_SESSION['api_token']=$data['token'];
				$_SESSION['currentController']="store";
				$_SESSION['kr_merchant_id']=$merchant_id;
				$_SESSION['kr_delivery_options']['delivery_type']=$data['delivery_type'];
				$_SESSION['kr_delivery_options']['delivery_date']=$data['delivery_date'];
				$_SESSION['kr_delivery_options']['delivery_time']=$data['delivery_time'];				
				$_SESSION['kr_item']=$order;								
				$redirect=Yii::app()->getBaseUrl(true)."/store/checkout";
				header("Location: ".$redirect);
				$this->render('error',array('message'=>t("Please wait while we redirect you")));
			} else $this->render('error',array('message'=>t("Token not found")));
		} else $this->render('error',array('message'=>t("Token is missing")));
	}
	
	public function actionPaymentbcy()
	{
		$db_ext=new DbExt;
		
		$data=$_GET;
		//dump($data);		
		if (isset($data['orderID'])){
			if ( $res=Yii::app()->functions->barclayGetTransaction($data['orderID'])){
				//dump($res);
				if ($data['do']=="accept") {
									
					switch ($res['transaction_type']) {
						case "order":							
							$order_id=$res['token'];							
							$order_info=Yii::app()->functions->getOrder($order_id);							
										
							$db_ext=new DbExt;
					        $params_logs=array(
					          'order_id'=>$order_id,
					          'payment_type'=>"bcy",
					          'raw_response'=>json_encode($data),
					          'date_created'=>FunctionsV3::dateNow(),
					          'ip_address'=>$_SERVER['REMOTE_ADDR'],
					          'payment_reference'=>$data['PAYID']
					        );		 
					        					        
					        $db_ext->insertData("{{payment_order}}",$params_logs);		      
			
					        $params_update=array('status'=>'paid');	        
				            $db_ext->updateData("{{order}}",$params_update,'order_id',$order_id);					          
					        header('Location: '.Yii::app()->request->baseUrl."/store/receipt/id/".$order_id);
			        		die();
							
							break;
							
						case "renew":
						case "signup":
							
							$my_token=$res['token'];
							$token_details=Yii::app()->functions->getMerchantByToken($res['token']);
							
							if ( $res['transaction_type']=="renew"){
																							
								$package_id=$token_details['package_id'];
							    if ($new_info=Yii::app()->functions->getPackagesById($package_id)){	   
										$token_details['package_name']=$new_info['title'];
										$token_details['package_price']=$new_info['price'];
										if ($new_info['promo_price']>0){
											$token_details['package_price']=$new_info['promo_price'];
										}			
								}
																
								$membership_info=Yii::app()->functions->upgradeMembership($token_details['merchant_id'],
								$package_id);
																					
			    				$params=array(
						          'package_id'=>$package_id,	          
						          'merchant_id'=>$token_details['merchant_id'],
						          'price'=>$token_details['package_price'],
						          'payment_type'=>Yii::app()->functions->paymentCode('barclay'),
						          'membership_expired'=>$membership_info['membership_expired'],
						          'date_created'=>FunctionsV3::dateNow(),
						          'ip_address'=>$_SERVER['REMOTE_ADDR'],
						          'PAYPALFULLRESPONSE'=>json_encode($data),
						          'TRANSACTIONID'=>$data['PAYID'],
						          'TOKEN'=>$data['PAYID']			           
						        );		
								
							} else {
								$params=array(
						           'package_id'=>$token_details['package_id'],	          
						           'merchant_id'=>$token_details['merchant_id'],
						           'price'=>$token_details['package_price'],
						           'payment_type'=>Yii::app()->functions->paymentCode('barclay'),
						           'membership_expired'=>$token_details['membership_expired'],
						           'date_created'=>FunctionsV3::dateNow(),
						           'ip_address'=>$_SERVER['REMOTE_ADDR'],
						           'PAYPALFULLRESPONSE'=>json_encode($data),
						           'TRANSACTIONID'=>$data['PAYID'],
						           'TOKEN'=>$data['PAYID']			           
							     );										     
							}
							
							if ($data['STATUS']==5 || $data['STATUS']==9 ){
						        $params['status']='paid';
						    }			        
						         					         
					         $db_ext->insertData("{{package_trans}}",$params);				        
			                 $db_ext->updateData("{{merchant}}",
											  array(
											    'payment_steps'=>3,
											    'membership_purchase_date'=>FunctionsV3::dateNow()
											  ),'merchant_id',$token_details['merchant_id']);
					
					         
							if ( $res['transaction_type']=="renew"){
                                header('Location: '.Yii::app()->request->baseUrl."/store/renewsuccesful");
                            } else {
                   header('Location: '.Yii::app()->request->baseUrl."/store/merchantsignup/Do/step4/token/$my_token"); 
                            }
                            die();
							break;
					
						default:
							break;
					}				
				} elseif ( $data['do']=="decline"){
					$this->render("error",array('message'=>t("Your payment has been decline")));
				} elseif ( $data['do']=="exception"){
					$this->render("error",array('message'=>t("Your Payment transactions is uncertain")));
				} elseif ( $data['do']=="cancel"){
					$this->render("error",array('message'=>t("Your transaction has been cancelled")));
				} else {
					$this->render("error",array('message'=>t("Unknow request")));
				}	
			} else $this->render("error",array('message'=>t("Cannot find order id")));
		} else $this->render("error",array('message'=>t("Something went wrong")));
	}
	
	public function actionBcyinit()
	{		
		$this->render("merchant-bcy");
	}
	
	public function actionEpayBg()
	{
		$db_ext=new DbExt;
		$data=$_GET;		
		$msg='';
		$error_receiver='';
				
		if ($data['mode']=="receiver"){
			
			$mode=Yii::app()->functions->getOptionAdmin('admin_mode_epaybg');				
			if ($mode=="sandbox"){					
				$min=Yii::app()->functions->getOptionAdmin('admin_sandbox_epaybg_min');
				$secret=Yii::app()->functions->getOptionAdmin('admin_sandbox_epaybg_secret');
			} else {					
				$min=Yii::app()->functions->getOptionAdmin('admin_live_epaybg_min');
				$secret=Yii::app()->functions->getOptionAdmin('admin_live_epaybg_secret');
			}
			/*dump($min);
			dump($secret);*/
			
			$EpayBg=new EpayBg;
			
			$ENCODED  = $data['encoded'];
            $CHECKSUM = $data['checksum'];                
            $hmac  = $EpayBg->hmac('sha1', $ENCODED, $secret);
                          
            /*dump("Check");
            dump($CHECKSUM);
            dump($hmac);*/
            
            //if ($hmac == $CHECKSUM) {                 	
            	$data_info = base64_decode($ENCODED);
                $lines_arr = split("\n", $data_info);
                $info_data = '';                    
                //dump($lines_arr);
                if (is_array($lines_arr) && count($lines_arr)>=1){                    	                    	
                	foreach ($lines_arr as $line) {
                		if (!empty($line)){
                		     $payment_info=explode(":",$line);	                    	                        	   
                    	     $invoice_number=str_replace("INVOICE=",'',$payment_info[0]);
                    	                                        	     
                    	    $status=str_replace("STATUS=",'',$payment_info[1]);
                    	    if (preg_match("/PAID/i", $payment_info[1])) {	                    	    	
                    	    	$info_data .= "INVOICE=$invoice_number:STATUS=OK\n";
                    	    	Yii::app()->functions->epayBgUpdateTransaction($invoice_number,$status);
                    	    } else {	                    	    	
                    	    	$info_data .= "INVOICE=$invoice_number:STATUS=ERR\n";
                    	    	Yii::app()->functions->epayBgUpdateTransaction($invoice_number,$status);
                    	    }                    		
                		}
                	}
                	echo $info_data;
                	Yii::app()->functions->createLogs($info_data,"epaybg");
                	die();
                } else $error_receiver="ERR=Not valid CHECKSUM\n";
            /*} else {
            	$error_receiver="ERR=Not valid CHECKSUM\n";
            }*/
            
            if (!empty($error_receiver)){
            	echo $error_receiver;
            	Yii::app()->functions->createLogs($error_receiver,"epaybg");
            } else {
            	Yii::app()->functions->createLogs("none response","epaybg");
            }		
			die();
			
		} elseif ( $data['mode']=="cancel" ){
			$msg=t("Transaction has been cancelled");
			
		} elseif (  $data['mode']=="accept"  ) {
								
			if ( $trans_info=Yii::app()->functions->barclayGetTokenTransaction($data['token'])){
				//dump($trans_info);
				switch ($data['mode']){
					case "accept":	
					     if ( $trans_info['transaction_type']=="order"){
					     	  $params_update=array(
					     	    'status'=>"pending",
					     	    'date_modified'=>FunctionsV3::dateNow()
					     	  );
					     	  $db_ext->updateData("{{order}}",$params_update,'order_id',$data['token']);
					     	  header('Location: '.websiteUrl()."/store/receipt/id/".$data['token']);
					     } else {
						    if ( $token_details=Yii::app()->functions->getMerchantByToken($data['token'])){	
								$db_ext->updateData("{{merchant}}",
								  array(
								    'payment_steps'=>3,
								    'membership_purchase_date'=>FunctionsV3::dateNow()
								  ),'merchant_id',$token_details['merchant_id']);
								
								header('Location: '.websiteUrl()."/store/merchantsignup/Do/thankyou2/token/".$data['token']); 
						    } else $msg=t("Token not found");	
					     }
						break;
						
					case "cancel":	
					    if ( $trans_info['transaction_type']=="order"){
					    	header('Location: '.websiteUrl()."/store/"); 
					    } else {
					        header('Location: '.websiteUrl()."/store/merchantsignup/Do/step3/token/".$data['token']); 
					    }
					    break;		
					
				}
			} else $msg=t("Transaction information not found");
		}
		
		if (!empty($msg)){
			$this->render('error',array('message'=>$msg));
		}
	}
	
	public function actionEpyInit()
	{
		$this->render('merchant-epyinit');
	}
	
	public function actionGuestCheckout()
	{
		ScriptManager::includeMappLibrary();
		unset($_SESSION['confirm_order_data']);
		
		/*POINTS PROGRAM*/
		if (FunctionsV3::hasModuleAddon("pointsprogram")){
		   PointsProgram::includeFrontEndFiles();	
		}    
		
		$seo_title=Yii::app()->functions->getOptionAdmin('seo_checkout');
		$seo_meta=Yii::app()->functions->getOptionAdmin('seo_checkout_meta');
		$seo_key=Yii::app()->functions->getOptionAdmin('seo_checkout_keywords');
		
		$current_merchant='';
		if (isset($_SESSION['kr_merchant_id'])){
			$current_merchant=$_SESSION['kr_merchant_id'];
		}
		
		if (!empty($seo_title)){
		   $seo_title=smarty('website_title',getWebsiteName(),$seo_title);
		   if ( $info=Yii::app()->functions->getMerchant($current_merchant)){        	
		   	   $seo_title=smarty('merchant_name',ucwords($info['restaurant_name']),$seo_title);
           }		   
		   $this->pageTitle=$seo_title;		   
		   Yii::app()->functions->setSEO($seo_title,$seo_meta,$seo_key);
		}
				
		$search_by_location = FunctionsV3::isSearchByLocation();	
		
		$enabled_map_selection_delivery = getOptionA('enabled_map_selection_delivery');
		
		$s=$_SESSION;
		$transaction_type = $s['kr_delivery_options']['delivery_type'];		
		
		$cs = Yii::app()->getClientScript();			
		$cs->registerScript(
		  'enabled_map_selection_delivery',
		 "var enabled_map_selection_delivery='$enabled_map_selection_delivery';",
		  CClientScript::POS_HEAD
		);		
		$cs->registerScript(
		  'transaction_type',
		 "var transaction_type='$transaction_type';",
		  CClientScript::POS_HEAD
		);
				
		
		if($transaction_type=="delivery"){			
				$temporary_address = isset($_SESSION['kr_search_address'])?$_SESSION['kr_search_address']:getOptionA('admin_country_set');			
				if(empty($temporary_address)){
					$temporary_address=Yii::app()->functions->adminCountry();
				}		
							
				$lat = ''; $lng ='';
				if($res=Yii::app()->functions->geodecodeAddress($temporary_address)){
					$lat = $res['lat'];
					$lng = $res['long'];
				}			
				$cs = Yii::app()->getClientScript();
				$cs->registerScript(
				  'temporary_address_lat',
				 "var temporary_address_lat='$lat';",
				  CClientScript::POS_HEAD
				);
				$cs->registerScript(
				  'temporary_address_lng',
				 "var temporary_address_lng='$lng';",
				  CClientScript::POS_HEAD
				);			
		}
		
		$this->render('payment-option',
		  array(
		     'is_guest_checkout'=>true,
		     'website_enabled_map_address'=>getOptionA('website_enabled_map_address'),
		     'address_book'=>Yii::app()->functions->showAddressBook(),
		     'step'=>4,
		     'guestcheckout'=>true,
		     'search_by_location'=>$search_by_location,
		     'enabled_map_selection_delivery'=>$enabled_map_selection_delivery,
		     'has_addressbook'=>false
		));
	}
	
	public function actionMerchantSignupSelection()
	{
		
		$act_menu=FunctionsV3::getTopMenuActivated();
		if (!in_array('resto_signup',(array)$act_menu)){
			$this->render('404-page',array('header'=>true));
			return ;
		}	

		if ( Yii::app()->functions->getOptionAdmin("merchant_disabled_registration")=="yes"){
			//$this->render('error',array('message'=>t("Sorry but merchant registration is disabled by admin")));
			$this->render('404-page',array('header'=>true));
		} else $this->render('merchant-signup-selection',array(
		  'percent'=>getOptionA('admin_commision_percent'),
		  'commision_type'=>getOptionA('admin_commision_type'),
		  'currency'=>adminCurrencySymbol(),
		  'disabled_membership_signup'=>getOptionA('admin_disabled_membership_signup')
		));		
	}
	
	public function actionMerchantSignupinfo()
	{
		$commission_type=FunctionsV3::MembershipType();
		unset($commission_type[1]);		
		$this->render('merchant-signup-info',array(
		  'terms_merchant'=>getOptionA('website_terms_merchant'),
		  'terms_merchant_url'=>getOptionA('website_terms_merchant_url'),
		  'kapcha_enabled'=>getOptionA('captcha_merchant_signup'),
		  'commission_type'=>$commission_type
		));
	}
	
	public function actionCancelWithdrawal()
	{
		$this->render('withdrawal-cancel');
	}
	
	public function actionFax()
	{
		$this->layout='_store';
		$this->render('fax');
	}
	
	public function actionATZinit()
	{
		$this->render('atz-merchant-init');
	}
	
	public function actionDepositVerify()
	{
		try {			
			$order_id = isset($_GET['ref'])?(integer)$_GET['ref']:'';				
			FunctionsV3::getBankDeposit($order_id);			
			$this->render('error',array('message'=> t("There is already upload bank deposit for this transaction") ));			
		} catch (Exception $e) {
		   $this->render('deposit-verify');
		}
	}
	
	public function actionVerification()
	{
		$continue=true;
		$msg='';
		$id=Yii::app()->functions->getClientId();
		if (!empty($id)){
			$continue=false;
			$msg=t("Sorry but we cannot find what you are looking for.");
		}
		if ( $continue==true){
			if( $res=Yii::app()->functions->getClientInfo($_GET['id'])){								
				if ( $res['status']=="active"){
					$continue=false;
					$msg=t("Your account is already verified");
				}
			} else {
				$continue=false;
				$msg=t("Sorry but we cannot find what you are looking for.");
			}
		}		
		
		if ( $continue==true){
		   $this->render('mobile-verification');
		} else $this->render('error',array('message'=>$msg));
	}

	public function actionMap()
	{
		if ( getOptionA('view_map_disabled')==2){
			$this->render('error',array(
			  'message'=>t("Sorry but we cannot find what you are looking for.")
			));
		} else {	
			$this->layout='_store';
			$this->render('map');
		}
	}
	
	public function missingAction($action)
	{
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
		ScriptManager::RegisterAllJSFile($config);
		ScriptManager::registerAllCSSFiles($config);
		$this->render('404-page',array(
		  'header'=>true
		));
	}
	
	public function actionGoogleLogin()
	{
		if (isset($_GET['error'])){
			$this->redirect(Yii::app()->createUrl('/store')); 
		}
			
		$plus = Yii::app()->GoogleApis->serviceFactory('Oauth2');
		$client = Yii::app()->GoogleApis->client;
		Try {
			 if(!isset(Yii::app()->session['auth_token']) 
			  || is_null(Yii::app()->session['auth_token']))
			    // You want to use a persistence layer like the DB for storing this along
			    // with the current user
			    Yii::app()->session['auth_token'] = $client->authenticate();
			  else
			    			  			  			    
			    if (isset($_SESSION['auth_token'])) {			    	 
				    $client->setAccessToken($_SESSION['auth_token']);
				}		    
				
				if (isset($_REQUEST['logout'])) {				   
				   unset($_SESSION['auth_token']);
				   $client->revokeToken();
				}
																								
			    if ( $token=$client->getAccessToken()){			    	
			    	$t=$plus->userinfo->get();			    			    	
			    	if (is_array($t) && count($t)>=1){
				        $func=new FunctionsK();
				        if ( $resp_t=$func->googleRegister($t) ){						
				        	
				        	//dump($resp_t); die();
				        	if (isset($resp_t['verification_type'])){				        		
				        		if (isset($_SESSION['google_http_refferer'])){
				        		   $redirect_url=Yii::app()->createUrl('store/emailverification',array(
				        		   'id'=>$resp_t['client_id'],
				        		   'checkout'=>true
				        		   ));
				        		} else {
				        		  $redirect_url=Yii::app()->createUrl('store/emailverification',array(
				        		    'id'=>$resp_t['client_id']
				        		  ));
				        		}
				        		$this->redirect($redirect_url);
				        	} else {				        		
					            Yii::app()->functions->clientAutoLogin($t['email'],
					            $resp_t['password'],$resp_t['password']);
					        	unset($_SESSION['auth_token']);
					            $client->revokeToken();		
					            if (isset($_SESSION['google_http_refferer'])){
					                $this->redirect($_SESSION['google_http_refferer']);   					            
					            } else $this->redirect(websiteUrl());					            
					        	die();					        	
				        	}
				        	
				        } else echo t("ERROR: Something went wrong");
			    	} else echo t("ERROR: Something went wrong");
			    }  else {
			    	$authUrl = $client->createAuthUrl();			    	
			    }			    
			    if(isset($authUrl)) {
				    print "<a class='login' href='$authUrl'>Connect Me!</a>";
				} else {
				   print "<a class='logout' href='?logout'>Logout</a>";
				}
		} catch(Exception $e) {
			Yii::app()->session['auth_token'] = null;
            throw $e;
		}
	}
		
	public function actionAddressBook()
	{
		 if ( Yii::app()->functions->isClientLogin()){
		 	if (isset($_GET['do'])){		
		 	   $data='';
		 	   if ( isset($_GET['id'])){
		 	   	    $data=Yii::app()->functions->getAddressBookByID($_GET['id']);
		 	   }		 
		       $this->render('address-book-add',array(
		         'data'=>$data
		       ));
		 	} else $this->render('address-book');
		 } else $this->render('error',array('message'=>t("Sorry but we cannot find what you are looking for.")));
	}
	
	public function actionAutoZipcode()
	{		
		$datas='';	
		$str=isset($_POST['search'])?trim($_POST['search']):'';
		$db_ext=new DbExt;
		$stmt="
		SELECT DISTINCT zipcode,area,city FROM
		{{zipcode}}
		WHERE
		zipcode LIKE ".FunctionsV3::q("$str%")."
		AND
		status IN ('publish','published')
		ORDER BY zipcode ASC
		LIMIT 0,16
		";		
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {								
				$full=$val['zipcode']." " .$val['area'] ." ".$val['city'];
				$datas[]=array(				  				
				  'name'=>$full
				);
			}
			echo json_encode($datas);
		}
	}
	
	public function actionAutoPostAddress()
	{
		$datas='';
		$str=isset($_POST['search'])?trim($_POST['search']):'';
		$db_ext=new DbExt;
		$stmt="
		SELECT * FROM
		{{zipcode}}
		WHERE
		stree_name LIKE ".FunctionsV3::q("$str%")."
		AND
		status IN ('publish','published')
		ORDER BY stree_name ASC
		LIMIT 0,16
		";				
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {								
				$full=$val['stree_name']."," .$val['area'] .",".$val['city'].",".$val['zipcode'];
				$datas[]=array(				  
				  'value'=>$full,
				  'title'=>$full,
				  'text'=>$full,
				);
			}			
			echo json_encode($datas);
		}
	}
		
	public function actionSMS()
	{
		$db_ext=new DbExt;
		$data=$_GET;		
		
		$resp='';
		$sms_to_sender='';
		$sms_customer='';
		$customer_number='';
		$sender=isset($data['msisdn'])?$data['msisdn']:'';
		$keys=array(0,1);
				
		if (isset($data['text'])){
			$text_split=explode(" ",$data['text']);			
			switch (strtolower($text_split[0])){
				case "order":
					$order_id=$text_split[1];
					//dump($order_id);	
									
					$stmt="SELECT a.order_id,
					a.client_id,
					a.trans_type,
					b.contact_phone
					 FROM
					{{order}} a					
					left join {{order_delivery_address}} b
                    ON
                    a.order_id=b.order_id
					WHERE
					a.order_id=".q($order_id)."
					LIMIT 0,1
					";					
					if ( $res=$db_ext->rst($stmt)){
						$res=$res[0];	
							
						if ( $res['trans_type']=="pickup"){
							$stmt3="
							select contact_phone 
							from
							{{client}}
							where
							client_id =".FunctionsV3::q($res['client_id'])."
							limit 0,1
							";
							if ($res3=$db_ext->rst($stmt3)){
								$res3=$res3[0];
								$customer_number=$res3['contact_phone'];
							}
						} else $customer_number=$res['contact_phone'];
						
						foreach ($text_split as $key=>$val) {
							if (!array_key_exists($key,$keys)){
								$sms_customer.=$val." ";
							}
						}						
					} else {
						$resp="Order ID not found or you have invalid sms syntax.";
						$sms_to_sender=$resp;						
					}								
					break;
					
				default:
					$resp="Undefined SMS keyword";
					break;
			}		
			
			
			$sms_customer=$data['text'];		
			
		/*	dump($customer_number);
			dump($sms_customer);
			die();	*/
			
			/*now we send the sms to either merchant or customer*/
			if (!empty($sms_customer) && !empty($customer_number)){
				/** send sms to customer*/				
				$send_resp=Yii::app()->functions->sendSMS($customer_number,$sms_customer);				
				$params_log=array(
				  'broadcast_id'=>'999999999',
				  'contact_phone'=>$customer_number,
				  'sms_message'=>$sms_customer,
				  'status'=>$send_resp['msg'],
				  'date_created'=>FunctionsV3::dateNow(),
				  'date_executed'=>FunctionsV3::dateNow(),
				  'ip_address'=>$_SERVER['REMOTE_ADDR'],
				  'gateway'=>$send_resp['sms_provider']
				);			
				$db_ext->insertData("{{sms_broadcast_details}}",$params_log);
				$resp="OK:SMS SEND";
			}		
			
			if (!empty($sms_to_sender) && !empty($sender)){
				/** send sms to sender or merchant*/							
				$send_resp=Yii::app()->functions->sendSMS($sender,$sms_to_sender);				
				$params_log=array(
				  'broadcast_id'=>'999999999',
				  'contact_phone'=>$sender,
				  'sms_message'=>$sms_to_sender,
				  'status'=>$send_resp['msg'],
				  'date_created'=>FunctionsV3::dateNow(),
				  'date_executed'=>FunctionsV3::dateNow(),
				  'ip_address'=>$_SERVER['REMOTE_ADDR'],
				  'gateway'=>$send_resp['sms_provider']
				);			
				$db_ext->insertData("{{sms_broadcast_details}}",$params_log);				
			}					
		} else $resp='missing text message';
		
		Yii::app()->functions->createLogs(array('msg'=>$resp),'sms-logs');
		echo "SMS:OK";
	}	
		
	public function actionItem()
	{
		$data=Yii::app()->functions->getItemById($_GET['item_id']);
		$merchant_id = $data[0]['merchant_id'];
		
		/*inventory*/
        if($inv_enabled = FunctionsV3::inventoryEnabled($merchant_id)){
            Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl."/protected/modules/inventory/assets/css/front.css");                
            Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl."/protected/modules/inventory/assets/js/inventory.js"
            ,CClientScript::POS_END);                
            InventoryWrapper::registerScript(array(
              "var inv_ajax='".CJavaScript::quote(Yii::app()->request->baseUrl."/inventory/Ajaxfront")."';",
              "var inv_loader='".CJavaScript::quote( t("loading") )."...';",
            ),'inventory_script');
        } 
		
		$this->layout='mobile_tpl';
		$this->render('item',array(
		   'title'=>"test title",
		   'data'=>$data,
		   'this_data'=>isset($_GET)?$_GET:'',
		   'inv_enabled'=>FunctionsV3::inventoryEnabled($merchant_id)
		));
	}
	
	public function actionTy()
	{
		$this->render('ty',array(
		  'verify'=>isset($_GET['verify'])?true:false
		));
	}	
	
	public function actionEmailVerification()
	{
		
		if ( Yii::app()->functions->isClientLogin()){
			$this->redirect(Yii::app()->request->baseUrl."/store/home");
		    Yii::app()->end();
		}
		
		$continue=true; $msg='';
		
		if(!isset($_GET['id'])){
			$_GET['id']='';
		}
		if( $res=Yii::app()->functions->getClientInfo($_GET['id'])){	
			if ( $res['status']=="active"){
				$continue=false;
				$msg=t("Your account is already verified");
			}
		} else {
			$continue=false;
			$msg=t("Sorry but we cannot find what you are looking for.");
		}
		
		if ($continue){
		   $this->render('email-verification',array(
		     'data'=>$res
		   ));
		} else $this->render('error',array('message'=>$msg));
	}
	
	public function actionMyPoints()
	{		
		/*POINTS PROGRAM*/
		PointsProgram::includeFrontEndFiles();

		$points_enabled=getOptionA('points_enabled');
			
		if ( $points_enabled==1){
			if ( Yii::app()->functions->isClientLogin()){			
				$points=PointsProgram::getTotalEarnPoints(
				   Yii::app()->functions->getClientId()
				);			
				
				$points_expirint=PointsProgram::getExpiringPoints(
				   Yii::app()->functions->getClientId()
				);
				
				$this->render('pts-mypoints',array(
				 'earn_points'=>$points,
				 'points_expirint'=>$points_expirint
				));
			} else $this->render('error',array(
			  'message'=>t("Sorry but you need to login first.")
			));		
		} else {
			$this->render('error',array(
			  'message'=>t("Sorry but we cannot find what you are looking for.")
			));		
		}
	}
	
	/*braintree*/
	public function actionBtrInit()
	{
		if (!Yii::app()->functions->isClientLogin()){
			$this->redirect(Yii::app()->createUrl('/store')); 
			Yii::app()->end();
		}
		$this->render('braintree-init',array(
		  'getdata'=>$_GET
		));
	}
	
	public function actionmsg91DeliveryReport()
	{
		$data=$_GET;		
		Yii::app()->functions->createLogs($_REQUEST,'msg91_');
		if(isset($data['data'])){
			$data=json_decode($data['data'],true);	
			//dump($data);
			$db= new DbExt;
			$stmt="
			SELECT * FROM
			{{sms_broadcast_details}}
			WHERE
			gateway_response=".FunctionsV3::q($data['requestId'])."
			LIMIT 0,1
			";
			if($res=$db->rst($stmt)){
				$res=$res[0];
				$id=$res['id'];				
				foreach ($data['numbers'] as $val) {					
					$params=array(
					  'status'=>$val['desc'],
					  'date_executed'=>FunctionsV3::dateNow(),
					  'ip_address'=>$_SERVER['REMOTE_ADDR']
					);					
					$db->updateData("{{sms_broadcast_details}}",$params,'id',$id);
				}
				echo "RESPONSE : SUCCESS";
				unset($db);
				return true;
			}
		}
		echo "RESPONSE : FAILED";
		return false;		
	}
		
	public function actionConfirmOrder()
	{		
				
		if (!isset($_GET['isguest'])){
			if ( !Yii::app()->functions->isClientLogin()){
				$this->redirect( Yii::app()->createUrl('/store/index'));
				Yii::app()->end();
			}
		}
		
		$data=isset($_SESSION['confirm_order_data'])?$_SESSION['confirm_order_data']:'';		
		
		if(isset($data['is_search_by_location'])){
		   unset($data['is_search_by_location']);
		}
		
		if (is_array($data) && count($data)>=1){
			$this->render('confirm-order-new',array(
			  'data'=>$data,
			  'merchant_info'=>Yii::app()->functions->getMerchant($data['merchant_id']),
			  's'=>$_SESSION,
			  'paymentlist'=>FunctionsV3::PaymentOptionList(),
			  'guestcheckout'=>isset($data['is_guest_checkout'])?true:false
			));
		} else $this->render('error',array(
		  'message'=>t("Something went wrong during processing your request. Please try again later.")
		));
	}
		
	public function actionrzrinit()
	{				
		$amount_to_pay=0; $error=''; $credentials='';
        $payment_description=Yii::t("default",'Payment to merchant')." ";
        $merchant_name='';

		if ( $data=Yii::app()->functions->getOrder($_GET['id'])){	
			$merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';	
			$payment_description.=isset($data['merchant_name'])?clearString($data['merchant_name']):'';	
									
			$amount_to_pay = normalPrettyPrice($data['total_w_tax']);
			
		    $this->render('rzr-init',array(
			   'data'=>$data,
			   'error'=>$error,
			   'amount_to_pay'=>$amount_to_pay,
			   'amount'=>str_replace(",",'',$amount_to_pay)*100,
			   'payment_description'=>$payment_description,
			   'credentials'=>FunctionsV3::razorPaymentCredentials($data['merchant_id'])
			));
		
		} else {
			$this->render('error',array(
			  'message'=>t("Sorry but we cannot find what your are looking for.")
			));
		}				
	}
	
	public function actionrzrverify()
	{
		$error='';  $data=$_POST;
		
		if (isset($data['hidden']) && isset($_GET['xid'])){
		if ( $data['hidden']==$_GET['xid']){			
			if (isset($data['razorpay_payment_id'])){
				if ( !empty($data['razorpay_payment_id'])){
					
					$db_ext=new DbExt;
			        $params_logs=array(
			          'order_id'=>$_GET['xid'],
			          'payment_type'=>"rzr",
			          'payment_reference'=>$data['razorpay_payment_id'],
			          'raw_response'=>$data['razorpay_payment_id'],
			          'date_created'=>FunctionsV3::dateNow(),
			          'ip_address'=>$_SERVER['REMOTE_ADDR']
			        );
			        $db_ext->insertData("{{payment_order}}",$params_logs);
			        
			        $params_update=array(
			         'status'=>'paid'
			        );	        
			        $db_ext->updateData("{{order}}",$params_update,'order_id',$_GET['xid']);
			        
			        /*POINTS PROGRAM*/ 
			        if (FunctionsV3::hasModuleAddon("pointsprogram")){
			           PointsProgram::updatePoints($_GET['xid']);
			        }
			        
			        /*Driver app*/
					if (FunctionsV3::hasModuleAddon("driver")){
					   Yii::app()->setImport(array(			
						  'application.modules.driver.components.*',
					   ));
					   Driver::addToTask($_GET['xid']);
					}
			        	        
			        $this->redirect( Yii::app()->createUrl('/store/receipt',array(
			          'id'=>$_GET['xid']
			        )) );
					Yii::app()->end();
					
				} else $error=t("Invalid razorpay response");
			} else $error=t("Invalid razorpay response");
		} else $error=t("Order id did not match");
		} else $error=t("Sorry but we cannot find what you are looking for.");
		
		$this->render('error',array(
			'message'=>$error
		));
	}		
	
	public function actionRzrValidate()
	{
		$error='';  $data=$_POST;
				
		if (isset($data['token']) && isset($data['razorpay_payment_id'])){
			if ( !empty($data['razorpay_payment_id'])){
				if($res=Yii::app()->functions->getMerchantByToken($data['token'])){		
					$db_ext=new DbExt;
					$params_logs=array(
			          'package_id'=>$res['package_id'],	          
			          'merchant_id'=>$res['merchant_id'],
			          'price'=>$res['package_price'],
			          'payment_type'=>'rzr',
			          'membership_expired'=>$res['membership_expired'],
			          'date_created'=>FunctionsV3::dateNow(),
			          'ip_address'=>$_SERVER['REMOTE_ADDR'],
			          'PAYPALFULLRESPONSE'=>$data['razorpay_payment_id'],
			          'status'=>"paid"
			        );			
					if (isset($_GET['renew'])){							
						$membership_info=Yii::app()->functions->upgradeMembership($res['merchant_id'],$_GET['package_id']);
	        	        $params_logs['membership_expired']=$membership_info['membership_expired'];	        	
	        	        $params_logs['price']=$membership_info['package_price'];
			        	$params_update=array(
						  'package_id'=>$res['package_id'],
						  'package_price'=>$membership_info['package_price'],
						  'membership_expired'=>$membership_info['membership_expired'],				  
						  'status'=>'active',
					 	 );					 	 
						 $db_ext->updateData("{{merchant}}",$params_update,'merchant_id',$res['merchant_id']);	 
					}
										
					$db_ext->insertData("{{package_trans}}",$params_logs);
					
					$db_ext->updateData("{{merchant}}",
					   array(
					     'payment_steps'=>3,
					     'membership_purchase_date'=>FunctionsV3::dateNow()
					),'merchant_id',$res['merchant_id']);	
					 
					if (isset($_GET['renew'])){            	
            	        header('Location: '. Yii::app()->createUrl('/store/renewsuccesful'));
		            } else {		            	
		            	/*SEND EMAIL*/
		            	FunctionsV3::sendMerchantActivation($res, $res['activation_key']);
		            	
		            	header('Location: '. Yii::app()->createUrl("store/merchantsignup",array(
		                  'Do'=>"step4",
		                  'token'=>$data['token']
		                )));	
		            } 								
				} else $error=t("Transaction token not found");
			} else $error=t("Invalid razorpay response");
		} else $error=t("Sorry but we cannot find what you are looking for.");		
		
		$this->render('error',array(
			'message'=>$error
		));
	}
	
	public function actionAcceptOrder()
	{
		$data=$_GET;		
		if (isset($data['id']) && isset($data['token'])){
			$db_ext=new DbExt;
			$stmt="SELECT a.*,
				(
				select activation_token
				from
				{{merchant}}
				where
				merchant_id=a.merchant_id
				) as activation_token
			 FROM
			{{order}} a
			WHERE
			order_id=".Yii::app()->functions->q($data['id'])."
			";
			if ($res=$db_ext->rst($stmt)){
				//dump($res); die();
				if ( $res[0]['date_modified']=="0000-00-00 00:00:00" || $res[0]['date_modified']=="" ){
				if ( $res[0]['activation_token']==$data['token']){
					$params=array(
					 'status'=>"accepted",
					 'date_modified'=>FunctionsV3::dateNow(),
					 'ip_address'=>$_SERVER['REMOTE_ADDR'],
					 'viewed'=>2,
					 'admin_viewed'=>1
					);				
					if ($res[0]['status']=="paid"){
						unset($params['status']);
					}	
					if ( $db_ext->updateData("{{order}}",$params,'order_id',$data['id'])){
						$msg=t("Order Status change to received, Thank you!");
												
						/** Mobile save logs for push notification */
						/*if (FunctionsV3::hasModuleAddon("mobileapp")){							
							$new_data['order_id']=$data['id'];
							$new_data['status']='accepted';
							
					    	Yii::app()->setImport(array(			
							  'application.modules.mobileapp.components.*',
						    ));
					    	AddonMobileApp::savedOrderPushNotification($new_data);	
						}*/						
						
                        /*SEND NOTIFICATIONS TO CUSTOMER*/	    		
                        $order_id=$data['id'];		
	    				FunctionsV3::notifyCustomerOrderStatusChange(
	    				  $order_id,
	    				  'accepted'	    				  
	    				);
				    	
	    				if (FunctionsV3::hasModuleAddon("driver")){
					    	/*Driver app*/
					    	Yii::app()->setImport(array(			
							  'application.modules.driver.components.*',
						    ));
						    Driver::addToTask($order_id);
	    				}
				    	
				    	/*Now we insert the order history*/	    		
	    				$params_history=array(
	    				  'order_id'=>$data['id'],
	    				  'status'=>'accepted',
	    				  'remarks'=>'',
	    				  'date_created'=>FunctionsV3::dateNow(),
	    				  'ip_address'=>$_SERVER['REMOTE_ADDR']
	    				);	    				
	    				$db_ext->insertData("{{order_history}}",$params_history);						
						
	    				
	    			   /*Driver app*/
					    if (FunctionsV3::hasModuleAddon("driver")){
						    Yii::app()->setImport(array(			
							  'application.modules.driver.components.*',
						    ));
						    Driver::addToTask($data['id']);
					    }
	    				
					} else $msg= t("Failed cannot update order");
				} else $msg= t("Token is invalid or not belong to the merchant");
				} else $msg=t("Order is already accepted");
			} else $msg= t("Order details not found");
		} else $msg= t("Missing parameters");
		$this->render('confirm-order',array('data'=>$msg));
	}
	
	public function actiondeclineorder()
	{
		$data=$_GET; $msg='';
		if (isset($data['id']) && isset($data['token'])){
			$db_ext=new DbExt;
			$stmt="SELECT a.*,
				(
				select activation_token
				from
				{{merchant}}
				where
				merchant_id=a.merchant_id
				) as activation_token
			 FROM
			{{order}} a
			WHERE
			order_id=".Yii::app()->functions->q($data['id'])."
			";
			if ($res=$db_ext->rst($stmt)){
				if ( $res[0]['date_modified']=="0000-00-00 00:00:00" || $res[0]['date_modified']=="" ){
				if ( $res[0]['activation_token']==$data['token']){
					$params=array(
					 'status'=>"decline",
					 'date_modified'=>FunctionsV3::dateNow(),
					 'ip_address'=>$_SERVER['REMOTE_ADDR'],
					 'viewed'=>2,
					 'admin_viewed'=>1
					);				
					if ($res[0]['status']=="paid"){
						unset($params['status']);
					}	
					if ( $db_ext->updateData("{{order}}",$params,'order_id',$data['id'])){
						$msg=t("Order Status change to decline");
						
						/** Mobile save logs for push notification */
						/*if (FunctionsV3::hasModuleAddon("mobileapp")){							
							$new_data['order_id']=$data['id'];
							$new_data['status']='accepted';
							
					    	Yii::app()->setImport(array(			
							  'application.modules.mobileapp.components.*',
						    ));
					    	AddonMobileApp::savedOrderPushNotification($new_data);	
						}*/
				    							
                        /*SEND NOTIFICATIONS TO CUSTOMER*/	    				
                        $order_id = $data['id'];
	    				FunctionsV3::notifyCustomerOrderStatusChange(
	    				  $order_id,
	    				  'decline'
	    				);
	    								    	
				    	/*Now we insert the order history*/	    		
	    				$params_history=array(
	    				  'order_id'=>$data['id'],
	    				  'status'=>'decline',
	    				  'remarks'=>'',
	    				  'date_created'=>FunctionsV3::dateNow(),
	    				  'ip_address'=>$_SERVER['REMOTE_ADDR']
	    				);	    				
	    				$db_ext->insertData("{{order_history}}",$params_history);						
							    				
	    			   /*Driver app*/
					    if (FunctionsV3::hasModuleAddon("driver")){
						    Yii::app()->setImport(array(			
							  'application.modules.driver.components.*',
						    ));
						    Driver::addToTask($data['id']);
					    }
	    				
					} else $msg= t("Failed cannot update order");
				} else $msg= t("Token is invalid or not belong to the merchant");
				} else $msg=t("Order is already decline");
			} else $msg= t("Order details not found");
		} else $msg= t("Missing parameters");
		$this->render('confirm-order',array('data'=>$msg));
	}
	
	public function actionCart()
	{
		$this->layout='mobile_tpl';
		
		$merchant_id=$_SESSION['kr_merchant_id'];
		if ( $data=FunctionsV3::getMerchantInfo($merchant_id)){
						
			    $minimum_order_dinein=getOption($merchant_id,'merchant_minimum_order_dinein');
		    	$maximum_order_dinein=getOption($merchant_id,'merchant_maximum_order_dinein');
		    	
	            $cs = Yii::app()->getClientScript();			
				$cs->registerScript(
				  'dinein_minimum',
				 "var dinein_minimum='$minimum_order_dinein';",
				  CClientScript::POS_HEAD
				);
				$cs->registerScript(
				  'dinein_max',
				 "var dinein_max='$maximum_order_dinein';",
				  CClientScript::POS_HEAD
				);
				echo CHtml::hiddenField('minimum_order_dinein',FunctionsV3::prettyPrice($minimum_order_dinein));
                echo CHtml::hiddenField('maximum_order_dinein',FunctionsV3::prettyPrice($maximum_order_dinein));
			
			    $distance_type='';
		    	$distance='';
		    	$merchant_delivery_distance='';
		    	$delivery_fee=0;
		    	$distance_type_raw='';
		    			    			    	
		    	/*double check if session has value else use cookie*/		    	
		    	FunctionsV3::cookieLocation();
					    		    	
		    	if (isset($_SESSION['client_location'])){
		    		
		    		/*get the distance from client address to merchant Address*/             
	                 $distance_type=FunctionsV3::getMerchantDistanceType($merchant_id); 
	                 $distance_type_orig=$distance_type;
	                 
		             $distance=FunctionsV3::getDistanceBetweenPlot(
		                $_SESSION['client_location']['lat'],
		                $_SESSION['client_location']['long'],
		                $data['latitude'],$data['lontitude'],$distance_type
		             );           
		             		            		 
		             $distance_type_raw = $distance_type=="M"?"miles":"kilometers";            		            
		             $distance_type=$distance_type=="M"?t("miles"):t("kilometers");
		             $distance_type_orig = $distance_type;
		             
		              if(!empty(FunctionsV3::$distance_type_result)){
		             	$distance_type_raw=FunctionsV3::$distance_type_result;
		             	$distance_type=t(FunctionsV3::$distance_type_result);
		             }
		             
		             $merchant_delivery_distance=getOption($merchant_id,'merchant_delivery_miles');             
		             		             
		             $delivery_fee=FunctionsV3::getMerchantDeliveryFee(
		                          $merchant_id,
		                          $data['delivery_charges'],
		                          $distance,
		                          $distance_type_raw);
		    		
		    	}			
		    			    		
		    	
		    	/*SESSION REF*/		    	
		    	$_SESSION['shipping_fee']=$delivery_fee;		
		    			    	
		    	/*CHECK IF BOOKING IS ENABLED*/
		    	$booking_enabled=true;		    		
		    	if (getOption($merchant_id,'merchant_table_booking')=="yes"){
		    		$booking_enabled=false;
		    	}			
		    	if ( getOptionA('merchant_tbl_book_disabled')){
		    		$booking_enabled=false;
		    	}
		    	
		    	/*CHECK IF MERCHANT HAS PROMO*/
		    	$promo['enabled']=1;
		    	if($offer=FunctionsV3::getOffersByMerchant($merchant_id,2)){		    	   
		    	   $promo['offer']=$offer;
		    	   $promo['enabled']=2;
		    	}		    			
		    	if ( $voucher=FunctionsV3::merchantActiveVoucher($merchant_id)){		    
		    		$promo['voucher']=$voucher;
		    		$promo['enabled']=2;
		    	}
		    	$free_delivery_above_price=getOption($merchant_id,'free_delivery_above_price');
		    	if ($free_delivery_above_price>0){
		    	    $promo['free_delivery']=$free_delivery_above_price;
		    		$promo['enabled']=2;
		    	}
		    	
		    	$photo_enabled=getOption($merchant_id,'gallery_disabled')=="yes"?false:true;
		    	if ( getOptionA('theme_photos_tab')==2){
		    		$photo_enabled=false;
		    	}
		    	
		    	$website_use_date_picker = getOptionA('website_use_date_picker');
				$enabled_category_sked = getOption($merchant_id,'enabled_category_sked');
				
				$merchant_info = array(
				  'merchant_id'=>$data['merchant_id']
				);							
				$merchant_opt_contact_delivery = getOption($merchant_id,'merchant_opt_contact_delivery');	
				
				FunctionsV3::registerScript(array(
				  "var website_use_date_picker='$website_use_date_picker';",
				  "var enabled_category_sked='$enabled_category_sked';",
				  "var merchant_information =".json_encode($merchant_info)."",
				  "var merchant_opt_contact_delivery='$merchant_opt_contact_delivery';",
				));
				
			
			/*inventory*/
            if($inv_enabled = FunctionsV3::inventoryEnabled($merchant_id)){
                Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl."/protected/modules/inventory/assets/css/front.css");                
                Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl."/protected/modules/inventory/assets/js/inventory.js"
                ,CClientScript::POS_END);                
                InventoryWrapper::registerScript(array(
                  "var inv_ajax='".CJavaScript::quote(Yii::app()->request->baseUrl."/inventory/Ajaxfront")."';",
                  "var inv_loader='".CJavaScript::quote( t("loading") )."...';",
                ),'inventory_script');
            } 		
			
			$this->render('mobile-cart',array(
			   'data'=>$data,
			   'merchant_id'=>$merchant_id,
			   'distance_type'=>$distance_type,
			   'distance_type_orig'=>$distance_type_orig,
			   'distance_type_raw'=>$distance_type_raw,
			   'distance'=>$distance,
			   'merchant_delivery_distance'=>$merchant_delivery_distance,
			   'delivery_fee'=>$delivery_fee,
			   'website_use_date_picker'=>$website_use_date_picker,
			   'merchant_opt_contact_delivery'=>$merchant_opt_contact_delivery
			));
		} else {
			$this->render('error',array(
			  'message'=>t("Sorry but we cannot find what you are looking for.")
			));
		}
	}
	
	public function actionAutoFoodItem()
	{
		$datas=array();		
		$str=isset($_POST['search'])?trim($_POST['search']):'';
		$db_ext=new DbExt;
		$stmt="SELECT item_name
		FROM
		{{item}}
		WHERE
		item_name LIKE ".FunctionsV3::q("%$str%")."
		AND merchant_id=".FunctionsV3::q($_POST['merchant_id'])."
		Group by item_name	
		ORDER BY item_name ASC
		LIMIT 0,16
		";		
		if ( $res=$db_ext->rst($stmt)){
			foreach ($res as $val) {								
				$datas[]=array(				  				
				  'name'=>$val['item_name']
				);
			}			
			echo json_encode($datas);
		}
	}
	
	public function actionRestaurant()
	{
		
	}
	
	public function actionvoguepaysuccess()
	{	
		$data=$_POST; $get=$_GET; $error='';
		
		//dump($data);die();
		
		if (isset($data['transaction_id'])){
		    $transaction_id=$data['transaction_id'];		    
		    
		    $is_demo=false;
		    $admin_vog_merchant_id=getOptionA('admin_vog_merchant_id');
		    if($admin_vog_merchant_id=="demo"){
		    	$is_demo=true;
		    }
		    
		    if ( $vog_res=voguepayClass::getTransaction($transaction_id,$is_demo)){		    	
		    	//dump($vog_res);	
		    	if($res=Yii::app()->functions->getMerchantByToken($get['token'])){		
					$db_ext=new DbExt;
					$params_logs=array(
			          'package_id'=>$res['package_id'],	          
			          'merchant_id'=>$res['merchant_id'],
			          'price'=>$res['package_price'],
			          'payment_type'=>'vog',
			          'membership_expired'=>$res['membership_expired'],
			          'date_created'=>FunctionsV3::dateNow(),
			          'ip_address'=>$_SERVER['REMOTE_ADDR'],
			          'TRANSACTIONID'=>$vog_res['transaction_id'],
			          'PAYPALFULLRESPONSE'=>json_encode($vog_res),	
			          'PAYMENTSTATUS'=>$vog_res['status'],			          
			          'TOKEN'=>$vog_res['merchant_ref']
			        );			
			        
			        if($vog_res['status']=="Approved"){
			          $params_logs['status']='paid';
			        } else {
			          $params_logs['status']=$vog_res['status'];
			        }
		    	    
			        //dump($params_logs);	die();
					if (isset($_GET['renew'])){							
						$membership_info=Yii::app()->functions->upgradeMembership($res['merchant_id'],$_GET['package_id']);
	        	        $params_logs['membership_expired']=$membership_info['membership_expired'];	        	
	        	        $params_logs['price']=$membership_info['package_price'];
			        	$params_update=array(
						  'package_id'=>$res['package_id'],
						  'package_price'=>$membership_info['package_price'],
						  'membership_expired'=>$membership_info['membership_expired'],				  
						  'status'=>'active',
					 	 );				
					 	 
					 	 if($vog_res['status']=="Failed" || $vog_res['status']=="Disputed" ){
					 	 	$params_update['status']='pending';
					 	 }					
					 	 	 	 
						 $db_ext->updateData("{{merchant}}",$params_update,'merchant_id',$res['merchant_id']);	 
					}
								
					if ( !Yii::app()->functions->epayBGIsPaymentExist($transaction_id) ){
					   $db_ext->insertData("{{package_trans}}",$params_logs);
					}
					
					if($vog_res['status']=="Failed" || $vog_res['status']=="Disputed" ){
						header('Location: '. Yii::app()->createUrl("store/merchantsignup",array(
		                  'Do'=>"step3",
		                  'token'=>$get['token'],
		                  'failed'=>1
		                )));	
						Yii::app()->end();
					}
					
					$db_ext->updateData("{{merchant}}",
					   array(
					     'payment_steps'=>3,
					     'membership_purchase_date'=>FunctionsV3::dateNow()
					),'merchant_id',$res['merchant_id']);	
					 
					if (isset($_GET['renew'])){            	
            	        header('Location: '. Yii::app()->createUrl('/store/renewsuccesful'));
		            } else {		            	
		            	/*SEND EMAIL*/
		            	FunctionsV3::sendMerchantActivation($res, $res['activation_key']);
		            	
		            	header('Location: '. Yii::app()->createUrl("store/merchantsignup",array(
		                  'Do'=>"step4",
		                  'token'=>$get['token']
		                )));	
		            } 								
				} else $error=t("Transaction token not found");
		    } else $error = t("Transaction failed");
		    
		} else $error = t("Missing transaction id");
		
		if(!empty($error)){
			$this->render('error',array(
				'message'=>$error
			));
		}
	}
	
	public function actionvoguepaynotify()
	{
		$data=$_POST; $get=$_GET; $error=''; $db_ext=new DbExt;
		
		if (isset($data['transaction_id'])){
		    $transaction_id=$data['transaction_id'];		    
		    
		    $is_demo=false;
		    $admin_vog_merchant_id=getOptionA('admin_vog_merchant_id');
		    if($admin_vog_merchant_id=="demo"){
		    	$is_demo=true;
		    }
		    
		    if ( $vog_res=voguepayClass::getTransaction($transaction_id,$is_demo)){		
		    	if($res=Yii::app()->functions->getMerchantByToken($get['token'])){		
					$db_ext=new DbExt;
					$params_logs=array(
			          'package_id'=>$res['package_id'],	          
			          'merchant_id'=>$res['merchant_id'],
			          'price'=>$res['package_price'],
			          'payment_type'=>'vog',
			          'membership_expired'=>$res['membership_expired'],
			          'date_created'=>FunctionsV3::dateNow(),
			          'ip_address'=>$_SERVER['REMOTE_ADDR'],
			          'TRANSACTIONID'=>$vog_res['transaction_id'],
			          'PAYPALFULLRESPONSE'=>json_encode($vog_res),	
			          'PAYMENTSTATUS'=>$vog_res['status'],			          
			          'TOKEN'=>$vog_res['merchant_ref']
			        );		
			        
			        if($vog_res['status']=="Approved"){
			          $params_logs['status']='paid';
			        } else {
			          $params_logs['status']=$vog_res['status'];
			        }
			        
			        if ( !Yii::app()->functions->epayBGIsPaymentExist($transaction_id) ){
			        	$db_ext->insertData("{{package_trans}}",$params_logs);			        	
			        }
			        	
		    	} else {
		    		// FAILED GETTING MERCHANT INFO
		    	}     	
		    }
		}      		
	}
	
	public function actionvoginit()
	{
				
		$amount_to_pay=0; $error=''; $credentials='';
        $payment_description=Yii::t("default",'Payment to merchant')." ";
        $merchant_name='';

		if ( $data=Yii::app()->functions->getOrder($_GET['id'])){	
			$merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';	
			$payment_description.=isset($data['merchant_name'])?clearString($data['merchant_name']):'';	
						
			$amount_to_pay = $data['total_w_tax']; $error='';
			
			$credentials  = FunctionsV3::GetVogueCredentials($merchant_id);			
						
			if( $credentials){
				if(isset($_GET['failed'])){	
					if(isset($_POST['transaction_id'])){
						$is_demo=false;				    
					    if($credentials['merchant_id']=="demo"){
					    	$is_demo=true;
					    }	    		    
						if ( $vog_res=voguepayClass::getTransaction($_POST['transaction_id'],$is_demo)){
							if(isset($vog_res['response_message'])){
								$error = Yii::t("default", "Payment failed reason : [reason]",array(
								  '[reason]'=>$vog_res['response_message']
								));
							} else $error = t("Payment Failed");
						}
					}
				}
			} else $error = t("invalid credentails");
						
		    $this->render('vog-merchant-init',array(
			   'data'=>$data,
			   'error'=>$error,
			   'amount_to_pay'=>$amount_to_pay,
			   'amount'=>str_replace(",",'',$amount_to_pay)*100,
			   'payment_description'=>$payment_description,
			   'credentials'=>$credentials
			));
		
		} else {
			$this->render('error',array(
			  'message'=>t("Sorry but we cannot find what your are looking for.")
			));
		}				
	}
	
	public function actionvognotify()
	{
		$data=$_POST; $DbExt=new DbExt;
		if (isset($data['transaction_id'])){
			$transaction_id=$data['transaction_id'];
			$order_id=isset($_GET['id'])?$_GET['id']:'';			
			if ($order_info=Yii::app()->functions->getOrderInfo($order_id)){
				$merchant_id=$order_info['merchant_id'];				
				if($credentials=FunctionsV3::GetVogueCredentials($merchant_id)){					
					$is_demo=false;				    
				    if($credentials['merchant_id']=="demo"){
				    	$is_demo=true;
				    }	    		    
			    	if ( $vog_res=voguepayClass::getTransaction($transaction_id,$is_demo)){
			    			    					    		
				        $params_logs=array(
				          'order_id'=>$order_id,
				          'payment_type'=>"vog",
				          'payment_reference'=>$transaction_id,
				          'raw_response'=>json_encode($vog_res),
				          'date_created'=>FunctionsV3::dateNow(),
				          'ip_address'=>$_SERVER['REMOTE_ADDR']
				        );					
				        if (!Yii::app()->functions->epayBgValidatePaymentOrder($order_id,$transaction_id)){
                           $DbExt->insertData("{{payment_order}}",$params_logs);
				        }
			    		
                        if(isset($vog_res['ERROR'])){
				        	$params_update=array(
		                      'status'=>$vog_res['ERROR'],
		                      'date_modified'=>FunctionsV3::dateNow(),
		                      'ip_address'=>$_SERVER['REMOTE_ADDR']
		                    );	
		                    $DbExt->updateData("{{order}}",$params_update,'order_id',$order_id);
				        	Yii::app()->end();
				        }
				        
				        switch (strtolower($vog_res['status'])) {
			    			case "failed":
			    			case "disputed":	
			    			case "pending":	
			    			case "cancelled":
			    				$params_update=array(
			                      'status'=>$vog_res['status'],
			                      'date_modified'=>FunctionsV3::dateNow(),
			                      'ip_address'=>$_SERVER['REMOTE_ADDR']
			                    );	
			                    $DbExt->updateData("{{order}}",$params_update,'order_id',$order_id);
			    				break;
			    		
			    			case "approved":	
			    			   $params_update=array(
			                      'status'=>'paid',
			                      'date_modified'=>FunctionsV3::dateNow(),
			                      'ip_address'=>$_SERVER['REMOTE_ADDR']
			                   );	
			                   $DbExt->updateData("{{order}}",$params_update,'order_id',$order_id);
			                   
			                   /*POINTS PROGRAM*/ 
						        if (FunctionsV3::hasModuleAddon("pointsprogram")){
						           PointsProgram::updatePoints($_GET['xid']);
						        }
						        
						        /*Driver app*/
								if (FunctionsV3::hasModuleAddon("driver")){
								   Yii::app()->setImport(array(			
									  'application.modules.driver.components.*',
								   ));
								   Driver::addToTask($_GET['xid']);
								}
								
			    			   break;
			    			   			    			
			    			default:
			    			   $params_update=array(
			                      'status'=>$vog_res['status'],
			                      'date_modified'=>FunctionsV3::dateNow(),
			                      'ip_address'=>$_SERVER['REMOTE_ADDR']
			                   );	
			                   $DbExt->updateData("{{order}}",$params_update,'order_id',$order_id);
			    			   break;
			    		}
			    		
			    		echo "OK";
                        
			    	} else {
			    		// failed get transaction
			    		$params_update=array(
	                      'status'=>'failed',
	                      'date_modified'=>FunctionsV3::dateNow(),
	                      'ip_address'=>$_SERVER['REMOTE_ADDR']
	                   );	
	                   $DbExt->updateData("{{order}}",$params_update,'order_id',$order_id);
			    	}
				}
			}
		} //else echo "Missing transaction id";
	}
	
	public function actionvogsuccess()
	{
		$error='';  $data=$_POST;				
		if(isset($data['transaction_id'])){
			$transaction_id=$data['transaction_id'];
			$order_id=isset($_GET['xid'])?$_GET['xid']:'';
			
			$DbExt=new DbExt;
			$stmt="
			SELECT merchant_id 
			FROM {{order}}
			WHERE
			order_id=".FunctionsV3::q($order_id)."
			LIMIT 0,1
			";
			if ($order_info=Yii::app()->functions->getOrderInfo($order_id)){			    
				$merchant_id=$order_info['merchant_id'];				
				if($order_info['status']=="paid"){
					// PAYMENT SUCCESSFUL					
					$this->redirect( Yii::app()->createUrl('/store/receipt',array(
		               'id'=>$order_id
		            )) );
				    Yii::app()->end();
				} else {
					// GET TRANSACTIN ID INFORMATION
					if($credentials=FunctionsV3::GetVogueCredentials($merchant_id)){											
						$is_demo=false;				    
					    if($credentials['merchant_id']=="demo"){
					    	$is_demo=true;
					    }	    	
					    					    
					    if ( $vog_res=voguepayClass::getTransaction($transaction_id,$is_demo)){	
					    	
					    	$params_logs=array(
					          'order_id'=>$order_id,
					          'payment_type'=>"vog",
					          'payment_reference'=>$transaction_id,
					          'raw_response'=>json_encode($vog_res),
					          'date_created'=>FunctionsV3::dateNow(),
					          'ip_address'=>$_SERVER['REMOTE_ADDR']
					        );								        
					        if (!Yii::app()->functions->epayBgValidatePaymentOrder($order_id,$transaction_id)){
					        	//echo 'insert';
	                           $DbExt->insertData("{{payment_order}}",$params_logs);
					        }
					        
	                        if(isset($vog_res['ERROR'])){
					        	$params_update=array(
			                      'status'=>$vog_res['ERROR'],
			                      'date_modified'=>FunctionsV3::dateNow(),
			                      'ip_address'=>$_SERVER['REMOTE_ADDR']
			                    );	
			                    $DbExt->updateData("{{order}}",$params_update,'order_id',$order_id);
			                    
			                    $this->redirect( Yii::app()->createUrl('/store/voginit',array(
					        	   'id'=>$order_id,
				                   'failed'=>1,
				                   'errormsg'=>$vog_res['status']
					        	)));			                    
					        	Yii::app()->end();
					        }
					        
					        switch (strtolower($vog_res['status'])) {
				    			case "failed":
				    			case "disputed":	
				    			case "pending":	
				    			case "cancelled":
				    				$params_update=array(
				                      'status'=>$vog_res['status'],
				                      'date_modified'=>FunctionsV3::dateNow(),
				                      'ip_address'=>$_SERVER['REMOTE_ADDR']
				                    );	
				                    $DbExt->updateData("{{order}}",$params_update,'order_id',$order_id);
				                    
				                    $this->redirect( Yii::app()->createUrl('/store/voginit',array(
						        	   'id'=>$order_id,
					                   'failed'=>1,
					                   'errormsg'=>$vog_res['status']
						        	)));
						        	Yii::app()->end();
				    				break;
				    		
				    			case "approved":	
				    			   $params_update=array(
				                      'status'=>'paid',
				                      'date_modified'=>FunctionsV3::dateNow(),
				                      'ip_address'=>$_SERVER['REMOTE_ADDR']
				                   );	
				                   $DbExt->updateData("{{order}}",$params_update,'order_id',$order_id);
				                   
				                   /*POINTS PROGRAM*/ 
							        if (FunctionsV3::hasModuleAddon("pointsprogram")){
							           PointsProgram::updatePoints($_GET['xid']);
							        }
							        
							        /*Driver app*/
									if (FunctionsV3::hasModuleAddon("driver")){
									   Yii::app()->setImport(array(			
										  'application.modules.driver.components.*',
									   ));
									   Driver::addToTask($_GET['xid']);
									}
									
									$this->redirect( Yii::app()->createUrl('/store/receipt',array(
							          'id'=>$order_id
							        )) );
							        Yii::app()->end();
				    			   break;
				    			   			    			
				    			default:
				    			   $params_update=array(
				                      'status'=>'undefined payment status',
				                      'date_modified'=>FunctionsV3::dateNow(),
				                      'ip_address'=>$_SERVER['REMOTE_ADDR']
				                   );	
				                   $DbExt->updateData("{{order}}",$params_update,'order_id',$order_id);
				                   
				                   $this->redirect( Yii::app()->createUrl('/store/voginit',array(
						        	   'id'=>$order_id,
					                   'failed'=>1,
					                   'errormsg'=>$vog_res['status']
						        	)));
						        	Yii::app()->end();
				    			   break;
				    		}
					    	
					    } else {
					    	// FAILED TRANSACTION 
					    	$error=t("Failed getting transaction information");
					    }					    
					} else {
						/// FAILED GETTING CREDENTIALS
						$error=t("Failed getting merchant credentials");
					}
				}
			} else {
				// FAILED GETTING ORDER INFORMATION
				$error=t("Failed getting order information");
			}				
		} else $error=t("Payment Failed");
		
		if(!empty($error)){
			$this->render('error',array(
				'message'=>$error
			));
		}
	}	
	
	public function actionadd_review()
	{
		$order_info = array();
		$order_token = isset($_GET['order_token'])?$_GET['order_token']:'';
		if(!empty($order_token)){
			$order_info = FunctionsV3::getOrderInfoByTokenWithCustomerName($order_token);
		}		
		
		if(is_array($order_info) && count($order_info)>=1){			
			$this->render('add_review',array(		  
			  'order_info'=>$order_info
			));
		} else {		
			 $this->render('error',array(
		       'message'=>t("Sorry but we cannot find what you are looking for")
		    ));
		}
	}
		
	public function actiondriver_signup()
	{
		$act_menu=FunctionsV3::getTopMenuActivated();
		if (!in_array('driver_signup',(array)$act_menu)){
			$this->render('404-page',array('header'=>true));
			return ;
		}
		$this->render('driver_signup');
	}
	
	public function actiondriver_signup_ty()
	{		
		$this->render('driver_signup_ty',array(
		  'message'=>$_GET['message']
		));
	}	
	
	/*STRIPE*/	
	public function actionstripeInit()
	{
		$amount_to_pay=0; $error=''; $credentials='';
        $payment_description='';
        $merchant_name=''; $error = '';
        
        if ( $data=Yii::app()->functions->getOrder($_GET['id'])){                	
        	$merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';	
	        $client_id = $data['client_id'];       
	        $order_id = $data['order_id']; 	
	        	        
        	if ($credentials = StripeWrapper::getCredentials($merchant_id)){        	    
	        	$merchant_name =isset($data['merchant_name'])?clearString($data['merchant_name']):'';
				$payment_description = Yii::t("default","Payment to merchant [merchant_name]. Order ID#[order]",array(
				  '[merchant_name]'=>$merchant_name,
				  '[order]'=>$_GET['id']
				));
				
				$description = Yii::t("default","Purchase Order ID# [order_id]",array(
				  '[order_id]'=>$_GET['id']
				));
				
				$amount_to_pay = Yii::app()->functions->normalPrettyPrice($data['total_w_tax']);
				$reference_id = $data['order_id_token'];
				$amount_to_pay = unPrettyPrice($amount_to_pay)*100;				
								
				try {
					
					$client_email='';
					if( $client_info=Yii::app()->functions->getClientInfo($client_id)){
						$client_email = $client_info['email_address'];
					}
					
					$trans_type='order';	
					
					$params = array(
					   'customer_email' => trim($client_email),					   
					   'payment_method_types'=>array('card'),
					   'client_reference_id'=>$trans_type."-".$reference_id,					   
					   'line_items'=>array(
					     array(
					       'name'=>$payment_description,
						     'description'=>$description,						     
						     'amount'=>$amount_to_pay,
						     'currency'=>FunctionsV3::getCurrencyCode(),
						     'quantity'=>1
					     )
					   ),					   
					   'success_url'=>websiteUrl()."/stripe_success?reference_id=".urlencode($reference_id)."&trans_type=$trans_type",
					   'cancel_url'=>websiteUrl()."/paymentoption",
					   //'locale'=>'es'
					);			
										
					$resp  =  StripeWrapper::createSession($credentials['secret_key'],$params);					
					$stripe_session=$resp['id'];
					$payment_intent=$resp['payment_intent'];
					
					/*LOGS THE PAYMENT INTENT*/
					$db=new DbExt();
					$db->updateData("{{order}}",array(
					  'payment_gateway_ref'=>$payment_intent
					),'order_id',$order_id);
					
					
					$cs = Yii::app()->getClientScript();
					$cs->registerScriptFile("https://js.stripe.com/v3/");
					
					$publish_key = $credentials['publish_key'];
					$publish_key = "Stripe('$publish_key')";
										
					$cs->registerScript(
					  'stripe',
					  'var stripe = '.$publish_key.';
					  ',
					  CClientScript::POS_HEAD
					);					
					$cs->registerScript(
					  'stripe_session',
					 "var stripe_session='$stripe_session';",
					  CClientScript::POS_HEAD
					);		
					
					$cs->registerScript(
					  'payment_reference_id',
					 "var payment_reference_id='$reference_id';",
					  CClientScript::POS_HEAD
					);		
					
					$this->render('stripe_buy',array(
					  'payment_description'=>$payment_description,
					  'fee'=>$credentials['card_fee'],
					  'amount'=>$amount_to_pay,					  
					));
					
				} catch (Exception $e) {
					$error = Yii::t("default","Caught exception: [error]",array(
					  '[error]'=>$e->getMessage()
					));
				}        	
        	} else $error=t("invalid payment credentials");        	        				
        } else $error = t("Sorry but we cannot find what your are looking for.");
        
        $back_url = Yii::app()->createUrl('/store/confirmorder');
        
        if(!empty($error)){
        	$error.='<p style="margin-top:20px;"><a href="'.$back_url.'" />'.t("back").'</a></p>';
	        $this->render('error',array(
			  'message'=>$error
			));
        }
	}
	
	public function actionstripe_success()
	{
		$db=new DbExt();
		$get = $_GET;$error = '';
		$back_url = Yii::app()->createUrl('/store/confirmorder');			
		$reference_id = isset($get['reference_id'])?$get['reference_id']:'';
		$trans_type = isset($get['trans_type'])?$get['trans_type']:'';		
		if(!empty($reference_id)){			
						
			switch ($trans_type) {
				case "order":
					if ($data = FunctionsV3::getOrderInfoByToken($reference_id)){
						$payment_gateway_ref=isset($data['payment_gateway_ref'])?$data['payment_gateway_ref']:'';				
						$merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';	
		        	    $client_id = $data['client_id'];
		        	    $order_id = $data['order_id'];
		        	    
		        	    $redirec_link=Yii::app()->createUrl('/store/receipt',array('id'=>$order_id));	
						             
		        	    if($credentials = StripeWrapper::getCredentials($merchant_id)){        	    	
		        	    	try {            	    		
		        	    		$resp = StripeWrapper::retrievePaymentIntent($credentials['secret_key'],$payment_gateway_ref);
		        	    		//dump($resp); die();
		        	    		if($data['status']=="paid"){
		        	    			header('Location: '.$redirec_link."&note=". t("already paid") );   
						            Yii::app()->end(); 		        	    			
		        	    		} else {		        	    			
		        	    		  FunctionsV3::updateOrderPayment($order_id,StripeWrapper::paymentCode(),
		        	    		  $payment_gateway_ref,$resp,$reference_id);
		        	    		  
					              FunctionsV3::callAddons($order_id);
					              					              
						          header('Location: '.$redirec_link);   
						          Yii::app()->end();						          						            
		        	    		}
		        	    	} catch (Exception $e) {
								$error = Yii::t("default","Caught exception: [error]",array(
								  '[error]'=>$e->getMessage()
								));
							}          	    	
		        	    } else $error = t("invalid payment credentials");				
					} else $error = t("Failed getting order information");			
					break;
					
				case "reg":				
				  
				  $payment_code = StripeWrapper::paymentCode();
				  $back_url = websiteUrl()."/merchantsignup?Do=step3b&token=$reference_id&gateway=$payment_code";	  
				  if($data=Yii::app()->functions->getMerchantByToken($reference_id)){
				  	 if ($credentials = StripeWrapper::getAdminCredentials()){
				  	 	try {
				  	 		
				  	 		$payment_gateway_ref=isset($data['payment_gateway_ref'])?$data['payment_gateway_ref']:'';				  	 						  	 	
				  	 		$resp = StripeWrapper::retrievePaymentIntent($credentials['secret_key'],$payment_gateway_ref);				  	 		
				  	 		
				  	 		$card_fee = 0;
				  	 		if($credentials['card_fee']>0.001){
				  	 			$card_fee=$credentials['card_fee'];
				  	 		}				  	 	
				  	 				
				  	 		$params_logs=array(
					          'package_id'=>$data['package_id'],	          
					          'merchant_id'=>$data['merchant_id'],
					          'price'=>$data['package_price']+$card_fee,
					          'payment_type'=>$payment_code,
					          'membership_expired'=>$data['membership_expired'],
					          'date_created'=>FunctionsV3::dateNow(),
					          'ip_address'=>$_SERVER['REMOTE_ADDR'],
					          'PAYPALFULLRESPONSE'=>json_encode($resp),
					          'TRANSACTIONID'=>$payment_gateway_ref,
					          'merchant_ref'=>$reference_id,
					          'status'=>"paid"
					        );					        
					        if (isset($_GET['renew'])){
					        	$package_id = isset($_GET['package_id'])?$_GET['package_id']:'';
					        	if($package_id>0){
					        	   $membership_info=Yii::app()->functions->upgradeMembership($data['merchant_id'],$package_id);
					        	   
					        	   $params_logs['membership_expired']=$membership_info['membership_expired'];
					        	   $params_logs['package_id']=$package_id;
					        	   $params_logs['price']=$membership_info['package_price']+$card_fee;
					        	   
					        	   $params_update=array(
								     'package_id'=>$package_id,
								     'package_price'=>$membership_info['package_price']+$card_fee,
								     'membership_expired'=>$membership_info['membership_expired'],				  
								     'status'=>'active'
							 	   );					 	 
								   $db->updateData("{{merchant}}",$params_update,'merchant_id',$data['merchant_id']);								   
					        	}
					        }
					        					        
					        $db->insertData("{{package_trans}}",$params_logs);
					        
					        $db->updateData("{{merchant}}",
						    array(
						     'payment_steps'=>3,
						     'membership_purchase_date'=>FunctionsV3::dateNow()
						    ),'merchant_id',$data['merchant_id']);
						    
						    if (isset($_GET['renew'])){
						    	header('Location: '. Yii::app()->createUrl('/store/renewsuccesful'));
						    } else {
							    FunctionsV3::sendWelcomeEmailMerchant($data);
	            	            FunctionsV3::sendMerchantActivation($data, $data['activation_key']);
	            	            
	            	            header('Location: '. Yii::app()->createUrl("store/merchantsignup",array(
				                  'Do'=>"step4",
				                  'token'=>$reference_id
				                )));	
						    }
				  	 		
				  	 	} catch (Exception $e) {
							$error = Yii::t("default","Caught exception: [error]",array(
							  '[error]'=>$e->getMessage()
							));
						}          	    	
				  	 } else t("invalid payment credentials");				  
				  } else $error = "Invalid reference id";
				  break;
				  
				case "sms":
					if($data = FunctionsV3::getSMSTransLog($reference_id)){
						$merchant_id = $data['merchant_id'];
						if ($merchant_info = FunctionsV3::getMerchantInfo($merchant_id)){
							$payment_gateway_ref = $merchant_info['payment_gateway_ref'];
							if($credentials = StripeWrapper::getAdminCredentials()){
								
								try {
									$resp = StripeWrapper::retrievePaymentIntent($credentials['secret_key'],$payment_gateway_ref);
									
									$payment_code = StripeWrapper::paymentCode();
									$amount_to_pay = $data['package_price'];
									
									$params=array(
									  'merchant_id'=>$merchant_id,
									  'sms_package_id'=>$data['sms_package_id'],
									  'payment_type'=>$payment_code,
									  'package_price'=>$amount_to_pay,
									  'sms_limit'=>isset($data['sms_limit'])?$data['sms_limit']:'',
									  'date_created'=>FunctionsV3::dateNow(),
									  'ip_address'=>$_SERVER['REMOTE_ADDR'],
									  'payment_gateway_response'=>json_encode($_GET),						  
									  'payment_reference'=>$payment_gateway_ref,
									  'status'=>'paid',
									  'merchant_ref'=>$reference_id
									 );			
									 $db->insertData("{{sms_package_trans}}",$params);
									 $id = Yii::app()->db->getLastInsertID();									 
									 $this->redirect(Yii::app()->createUrl('/merchant/smsReceipt',array(
									   'id'=>$id
									 ))); 
									 Yii::app()->end();
									
								} catch (Exception $e) {
									$error = Yii::t("default","Caught exception: [error]",array(
									  '[error]'=>$e->getMessage()
									));
								}          	    	
								
							} else t("invalid payment credentials");								
						} else $error = t("merchant information not found");
					} else $error = t("Invalid reference id");
					break;
			
				default:
					$error = Yii::t("default","invalid transaction type [trans_type]",array(
					  '[trans_type]'=>$trans_type
					));
					break;
			}			
		} else $error = t("Sorry but we cannot find what your are looking for.");
		
		if(!empty($error)){
        	$error.='<p style="margin-top:20px;"><a href="'.$back_url.'" />'.t("Go back").'</a></p>';
	        $this->render('error',array(
			  'message'=>$error
			));
        }
	}	
	/*END STRIPE*/
	
	public function actionpaypal_init_reg()
	{		
		$reference_id = isset($_POST['reference_id'])?$_POST['reference_id']:'';
		$cancel_url='';		
		if(!empty($reference_id)){
			
			$payment_code=PaypalWrapper::paymentCode();
			$trans_type='reg';
			$cancel_url = websiteUrl()."/merchantsignup?Do=step3b&reference_id=$reference_id&gateway=$payment_code";
            $success_url = websiteUrl()."/paypal_success?reference_id=".urlencode($reference_id)."&trans_type=$trans_type";

			if ($data = Yii::app()->functions->getMerchantByToken($reference_id)){
				if (isset($_POST['renew'])){
					$package_id=isset($_POST['package_id'])?$_POST['package_id']:'';					
					$extra_params="&renew=1&package_id=".$package_id;
					$cancel_url.=$extra_params;
	                $success_url.=$extra_params;
					if ($new_info=Yii::app()->functions->getPackagesById($package_id)){
						$data['package_price']=$new_info['price'];
						$data['package_name']=$new_info['title'];
	                    $data['package_id']=$package_id;
						if ( $new_info['promo_price']>0.001){
							$data['package_price']=$new_info['promo_price'];
						}
					}					
				}
				if ($credentials = PaypalWrapper::getAdminCredentials()){
					
					$merchant_id = $data['merchant_id'];			
					$payment_description= Yii::t("default","Membership Payment by merchant [restaurant_name]",array(					   
					   '[restaurant_name]'=>$data['restaurant_name']
					));
					$description = Yii::t("default","Package name [package_name]",array(
					  '[package_name]'=>isset($data['package_name'])?$data['package_name']:'',
					));
					
					$amount_to_pay = Yii::app()->functions->normalPrettyPrice($data['package_price']);		
					$card_fee = $credentials['card_fee'];
					if($credentials['card_fee']>0.001){
						$amount_to_pay = unPrettyPrice($amount_to_pay) + unPrettyPrice($credentials['card_fee']);
					}					
					$amount_to_pay = normalPrettyPrice($amount_to_pay);
					
					$currency_code = FunctionsV3::getCurrencyCode();
										
					try {
																		
						 $params = array(
				            'intent' => 'CAPTURE',
				            'application_context' => array(
				                'return_url' => $success_url,
				                'cancel_url' => $cancel_url,   
				                //'locale' => 'en-US',
				            ),
				            'purchase_units' => array(
				                0 => array(
				                    'reference_id' => $reference_id,
				                    'description' => $payment_description,   
				                    'amount' => array(
				                        'currency_code' => $currency_code,
				                        'value' => $amount_to_pay,
				                        'breakdown' => array(
				                            'item_total' => array(
				                                'currency_code' => $currency_code,
				                                'value' => $amount_to_pay
				                            )
				                        )
				                    ),
				                    'items' => array(
				                        0 => array(
				                            'name' => t("Membership fee"),
				                            'description' => $description,				                            
				                            'unit_amount' => array(
				                                'currency_code' => $currency_code,
				                                'value' => $amount_to_pay
				                            ),
				                            'quantity' => '1',				                            
				                        )
				                    )
				                )
				            )
				        );
        
						/*dump($params);				
						die();*/
						
						$resp = PaypalWrapper::createOrder(
							$credentials['client_id'],
							$credentials['secret_key'],
							$credentials['mode'],
							$params
						);
												
						$this->redirect($resp['approve']);
						Yii::app()->end();
						
					} catch (Exception $e) {
						$error = Yii::t("default","Caught exception: [error]",array(
						  '[error]'=>$e->getMessage()
						));
					}        	
					
				} else $error = t("invalid payment credentails");					
			} else $error = t("cannot find reference id");
		} else $error = t("invalid reference id");
		
		if(!empty($error)){
        	$error.='<p style="margin-top:20px;"><a href="'.$cancel_url.'" />'.t("go back").'</a></p>';
	        $this->render('error',array(
			  'message'=>$error
			));
        }
	}
	
	public function actionpaypal_success()
	{
		$get = $_GET; $back_url='';
		$error='';
		$payment_code = PaypalWrapper::paymentCode();
				
		$reference_id = isset($get['reference_id'])?$get['reference_id']:'';
		$trans_type = isset($get['trans_type'])?$get['trans_type']:'';
		$payer_id = isset($get['PayerID'])?$get['PayerID']:'';
		$payment_token = isset($get['token'])?$get['token']:'';
		
		$db=new DbExt();
		
		if(!empty($reference_id) && !empty($trans_type)){
			
			switch ($trans_type) {
				case "reg":					
				    $back_url = websiteUrl()."/merchantsignup?Do=step3b&token=$reference_id&gateway=$payment_code";	  
				    if($data=Yii::app()->functions->getMerchantByToken($reference_id)){
				    	if ($credentials = PaypalWrapper::getAdminCredentials()){
				    		
				    		try {
				    			
				    			$resp = PaypalWrapper::captureRequest(
						    			  $credentials['client_id'],
									      $credentials['secret_key'],
									      $credentials['mode'],
						    			  $payment_token
						    			);
					    		
					    		$card_fee = 0;
					  	 		if($credentials['card_fee']>0.001){
					  	 			$card_fee=$credentials['card_fee'];
					  	 		}	
					  	 		
					  	 		$params_logs=array(
						          'package_id'=>$data['package_id'],	          
						          'merchant_id'=>$data['merchant_id'],
						          'price'=>$data['package_price']+$card_fee,
						          'payment_type'=>$payment_code,
						          'membership_expired'=>$data['membership_expired'],
						          'date_created'=>FunctionsV3::dateNow(),
						          'ip_address'=>$_SERVER['REMOTE_ADDR'],
						          'PAYPALFULLRESPONSE'=>json_encode($resp),
						          'TRANSACTIONID'=>$resp['id'],
						          'merchant_ref'=>$reference_id,
						          'status'=>$resp['status']
						        );		
						        if (isset($_GET['renew'])){
						        	$package_id = isset($_GET['package_id'])?$_GET['package_id']:'';
						        	if($package_id>0){
						        		
						        		$back_url.="&renew=1&package_id=$package_id";
						        		
						        		$membership_info=Yii::app()->functions->upgradeMembership($data['merchant_id'],$package_id);
						        		
						        		$params_logs['membership_expired']=$membership_info['membership_expired'];
						        	    $params_logs['package_id']=$package_id;
						        	    $params_logs['price']=$membership_info['package_price']+$card_fee;
						        	   
						        	    $params_update=array(
									      'package_id'=>$package_id,
									      'package_price'=>$membership_info['package_price']+$card_fee,
									      'membership_expired'=>$membership_info['membership_expired'],				  
									      'status'=>'active'
								 	    );					 	 
									    $db->updateData("{{merchant}}",$params_update,'merchant_id',$data['merchant_id']);	
						        	}
						        }						        
						        $db->insertData("{{package_trans}}",$params_logs);
						        
						        $db->updateData("{{merchant}}",
							    array(
							     'payment_steps'=>3,
							     'membership_purchase_date'=>FunctionsV3::dateNow()
							    ),'merchant_id',$data['merchant_id']);
							    
							    if (isset($_GET['renew'])){
							    	header('Location: '. Yii::app()->createUrl('/store/renewsuccesful'));
							    } else {
								    FunctionsV3::sendWelcomeEmailMerchant($data);
		            	            FunctionsV3::sendMerchantActivation($data, $data['activation_key']);
		            	            
		            	            header('Location: '. Yii::app()->createUrl("store/merchantsignup",array(
					                  'Do'=>"step4",
					                  'token'=>$reference_id
					                )));	
							    }
				    			
				    		} catch (Exception $e) {
								$error = Yii::t("default","Caught exception: [error]",array(
								  '[error]'=>$e->getMessage()
								));
							}        	
				    		
				    	} else t("invalid payment credentials");
				    } else $error = "Invalid reference id";
					break;
			
				case "order":
					  $back_url = Yii::app()->createUrl('/store/paymentoption');
					  if ($data = FunctionsV3::getOrderInfoByToken($reference_id)){
					      $merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';	
		        	      $client_id = $data['client_id'];
		        	      $order_id = $data['order_id'];
		        	      
		        	      $redirec_link=Yii::app()->createUrl('/store/receipt',array('id'=>$order_id));	
		        	      if($credentials = PaypalWrapper::getCredentials($merchant_id)){
		        	      	 try {
		        	      	 	
		        	      	 	$resp = PaypalWrapper::captureRequest(
				    			  $credentials['client_id'],
							      $credentials['secret_key'],
							      $credentials['mode'],
				    			  $payment_token
				    			);
				    			
				    			if($data['status']=="paid"){
				    				header('Location: '.$redirec_link."&note=". t("already paid") );   
						            Yii::app()->end(); 
				    			} else {
				    				  FunctionsV3::updateOrderPayment($order_id,$payment_code,
		        	    		      $resp['id'],$resp,$reference_id);
		        	    		      
				    				  FunctionsV3::callAddons($order_id);
				    				  header('Location: '.$redirec_link);   
				    				  Yii::app()->end();
				    			}		        	      	 
		        	      	 	
		        	      	 } catch (Exception $e) {
								$error = Yii::t("default","Caught exception: [error]",array(
								  '[error]'=>$e->getMessage()
								));
							 }    
		        	      } else t("invalid payment credentials");
		        	      	
					  } else $error = t("Failed getting order information");			
				   break;
				   
				case "sms":  
				   $back_url = websiteUrl()."/merchant/purchasesms";
				   if($data = FunctionsV3::getSMSTransLog($reference_id)){
				   	  if ($credentials = PaypalWrapper::getAdminCredentials()){
				   	  	
				   	  	  try {
				   	  	  	
				   	  	  	   $resp = PaypalWrapper::captureRequest(
				    			  $credentials['client_id'],
							      $credentials['secret_key'],
							      $credentials['mode'],
				    			  $payment_token
				    			);
				    			
				    			$params=array(
								  'merchant_id'=>$data['merchant_id'],
								  'sms_package_id'=>$data['sms_package_id'],
								  'payment_type'=>$payment_code,
								  'package_price'=>$data['package_price'],
								  'sms_limit'=>isset($data['sms_limit'])?$data['sms_limit']:'',
								  'date_created'=>FunctionsV3::dateNow(),
								  'ip_address'=>$_SERVER['REMOTE_ADDR'],
								  'payment_gateway_response'=>json_encode($resp),						  
								  'payment_reference'=>$resp['id'],
								  'status'=>'paid',
								  'merchant_ref'=>$reference_id
								 );			
								 $db->insertData("{{sms_package_trans}}",$params);								 
								 $id = Yii::app()->db->getLastInsertID();			
								 						 
								 $this->redirect(Yii::app()->createUrl('/merchant/smsReceipt',array(
								   'id'=>$id
								 ))); 
								 Yii::app()->end();
				   	  	  	
				   	  	  } catch (Exception $e) {
							$error = Yii::t("default","Caught exception: [error]",array(
							  '[error]'=>$e->getMessage()
							));
						  }   
				   	  	
				   	  } else t("invalid payment credentials");
				   } else $error = t("Invalid reference id");
				   break;
				   
				default:
					$error = Yii::t("default","invalid transaction type [trans_type]",array(
					  '[trans_type]'=>$trans_type
					));
					break;
			}
		} else $error = t("Sorry but we cannot find what you are looking for");

		if(!empty($error)){
        	$error.='<p style="margin-top:20px;"><a href="'.$back_url.'" />'.t("Go back").'</a></p>';
	        $this->render('error',array(
			  'message'=>$error
			));
        }
	}
	
	public function actionpaypal_v2init()
	{
		require_once('buy.php');
		
		if(empty($error)){
			if ($credentials=PaypalWrapper::getCredentials($merchant_id)){
				
				$success_url = websiteUrl()."/paypal_success?reference_id=".urlencode($reference_id)."&trans_type=$trans_type";
				$cancel_url = websiteUrl()."/paymentoption";
				
				try {																	
					 $params = array(
			            'intent' => 'CAPTURE',
			            'application_context' => array(
			                'return_url' => $success_url,
			                'cancel_url' => $cancel_url,   				                
			            ),
			            'purchase_units' => array(
			                0 => array(
			                    'reference_id' => $reference_id,
			                    'description' => $payment_description,   
			                    'amount' => array(
			                        'currency_code' => $currency_code,
			                        'value' => $amount_to_pay,
			                        'breakdown' => array(
			                            'item_total' => array(
			                                'currency_code' => $currency_code,
			                                'value' => $amount_to_pay
			                            )
			                        )
			                    ),
			                    'items' => array(
			                        0 => array(
			                            'name' => t("Purchase"),
			                            'description' => $description,				                            
			                            'unit_amount' => array(
			                                'currency_code' => $currency_code,
			                                'value' => $amount_to_pay
			                            ),
			                            'quantity' => '1',				                            
			                        )
			                    )
			                )
			            )
			        );
    
			        /*dump($params);
			        die();*/
					
					$resp = PaypalWrapper::createOrder(
						$credentials['client_id'],
						$credentials['secret_key'],
						$credentials['mode'],
						$params
					);
											
					$this->redirect($resp['approve']);
					Yii::app()->end();
						
				} catch (Exception $e) {
					$error = Yii::t("default","Caught exception: [error]",array(
					  '[error]'=>$e->getMessage()
					));
				}        	
				
			} else $error = t("invalid merchant credentials");
		} 

		if(!empty($error)){
			$error.='<p style="margin-top:20px;"><a href="'.$back_url.'" />'.t("back").'</a></p>';
	        $this->render('error',array(
			  'message'=>$error
			));
		}	
	}
	
	public function actionmercadopagoinit()
	{
		require_once('buy.php');
		if(empty($error)){
			if ($credentials=mercadopagoWrapper::getCredentials($merchant_id)){				
				$success_url = websiteUrl()."/mercadopago_success?trans_type=$trans_type";
				$failure_url = websiteUrl()."/mercadopago_failure?trans_type=$trans_type";
				$cancel_url=$failure_url;
				
				try {					
					$params=array(
					  'title'=>$payment_description,
					  'quantity'=>1,
					  'currency_id'=>FunctionsV3::getCurrencyCode(),
					  'unit_price'=>$amount_to_pay,
					  'email'=>$data['email_address'],
					  'external_reference'=>$reference_id,
					  'success'=>$success_url,
					  'failure'=>$failure_url,
					  'pending'=>$cancel_url,
					);					
					$resp = mercadopagoWrapper::createPayment($credentials,$params);			
					$this->redirect($resp);
			        Yii::app()->end();
					
				} catch (Exception $e){
			       $error = $e->getMessage();
		        }		
			}
		}
		
		$back_url = Yii::app()->createUrl('/store/confirmorder');
		
		if(!empty($error)){
			$error.='<p style="margin-top:20px;"><a href="'.$back_url.'" />'.t("back").'</a></p>';
	        $this->render('error',array(
			  'message'=>$error
			));
		}	
	}	
	
	public function actionmercadopago_success()
	{
		$data = $_GET;
		$db = new DbExt();	    
	    $error='';
	    $back_url='';
	    
	    $trans_type = isset($data['trans_type'])?$data['trans_type']:'';	    
	    $reference_id = isset($data['external_reference'])?$data['external_reference']:'';	    
	    $merchant_order_id = isset($data['merchant_order_id'])?$data['merchant_order_id']:'';	    
	    
	    $payment_code = mercadopagoWrapper::paymentCode();
	    
	    if(empty($reference_id) && empty($merchant_order_id)){
	    	$error = t("Sorry but we cannot find what your are looking for.");
    		 $this->render('error',array(
			  'message'=>$error
			));
			Yii::app()->end();
	    }
	    	    
	    switch ($trans_type) {
	    	case "reg":	    		
	    		$back_url = websiteUrl()."/merchantsignup?Do=step3&token=$reference_id&gateway=$payment_code";	  
	    		if($data=Yii::app()->functions->getMerchantByToken($reference_id)){
	    			if ($credentials = mercadopagoWrapper::getAdminCredentials()){
	    				try {
	    					
	    					$resp = mercadopagoWrapper::getPaymentStatus($credentials,$reference_id);
	    						    					
	    					$card_fee = 0;
					  	 	if($credentials['card_fee']>0.001){
					  	 	   $card_fee=$credentials['card_fee'];
					  	 	}		
					  	 	
					  	 	$params_logs=array(
					          'package_id'=>$data['package_id'],	          
					          'merchant_id'=>$data['merchant_id'],
					          'price'=>$data['package_price']+$card_fee,
					          'payment_type'=>mercadopagoWrapper::paymentCode(),
					          'membership_expired'=>$data['membership_expired'],
					          'date_created'=>FunctionsV3::dateNow(),
					          'ip_address'=>$_SERVER['REMOTE_ADDR'],
					          'PAYPALFULLRESPONSE'=>json_encode($data),
					          'TRANSACTIONID'=>$merchant_order_id,
					          'merchant_ref'=>$reference_id,
					          'status'=>$resp
					        );		
					         if (isset($_GET['renew'])){
					        	$package_id = isset($_GET['package_id'])?$_GET['package_id']:'';
					        	if($package_id>0){
					        		$back_url.="&renew=1&package_id=$package_id";
					        		$membership_info=Yii::app()->functions->upgradeMembership($data['merchant_id'],$package_id);
					        		
					        		$params_logs['membership_expired']=$membership_info['membership_expired'];
						        	$params_logs['package_id']=$package_id;
						        	$params_logs['price']=$membership_info['package_price']+$card_fee;
					        		
						        	$params_update=array(
								      'package_id'=>$package_id,
								      'package_price'=>$membership_info['package_price']+$card_fee,
								      'membership_expired'=>$membership_info['membership_expired'],				  
								      'status'=>'active'
							 	    );					 	 
								    $db->updateData("{{merchant}}",$params_update,'merchant_id',$data['merchant_id']);	
					        	}
					        }
					        
					        $db->insertData("{{package_trans}}",$params_logs);
					        
					        $db->updateData("{{merchant}}",
						    array(
						     'payment_steps'=>3,
						     'membership_purchase_date'=>FunctionsV3::dateNow()
						    ),'merchant_id',$data['merchant_id']);
	    					
						     if (isset($_GET['renew'])){
						    	header('Location: '. Yii::app()->createUrl('/store/renewsuccesful'));
						     } else {
							    FunctionsV3::sendWelcomeEmailMerchant($data);
	            	            FunctionsV3::sendMerchantActivation($data, $data['activation_key']);
	            	            
	            	            header('Location: '. Yii::app()->createUrl("store/merchantsignup",array(
				                  'Do'=>"step4",
				                  'token'=>$reference_id
				                )));	
						    }
						    
	    				} catch (Exception $e) {
							$error = Yii::t("default","Caught exception: [error]",array(
							  '[error]'=>$e->getMessage()
							));
					    }  
	    			} else t("invalid payment credentials");
	    		} else $error = "Invalid reference id";
	    		break;
	    		
	    	case "order":	    	
	    	    $back_url = Yii::app()->createUrl('/store/confirmorder');
	    	    if( $order_info = FunctionsV3::getOrderInfoByToken($reference_id)){
	    	    	$merchant_id = $order_info['merchant_id'];					
		            $order_id = $order_info['order_id'];
		            $amount_to_pay = Yii::app()->functions->normalPrettyPrice($order_info['total_w_tax']);
		            
		            $redirec_link=Yii::app()->createUrl('/store/receipt',array('id'=>$order_id));	
		            
		            if($order_info['status']=="paid"){
	    				header('Location: '.$redirec_link."&note=already paid");   
						Yii::app()->end();
	    			}
	    			
	    			if($credentials=mercadopagoWrapper::getCredentials($merchant_id)){	    				
	    				try {
	    					$resp = mercadopagoWrapper::getPaymentStatus($credentials,$reference_id);
	    					
	    					FunctionsV3::updateOrderPayment($order_id,mercadopagoWrapper::paymentCode(),
		        	    	$merchant_order_id,$data,$reference_id);
		        	    	
		        	    	FunctionsV3::callAddons($order_id);
		        	    	header('Location: '.$redirec_link);   
						    Yii::app()->end();
	    					
	    				} catch (Exception $e) {
							$error = Yii::t("default","Caught exception: [error]",array(
							  '[error]'=>$e->getMessage()
							));
					    }  	    			
	    			} else $error = t("invalid merchant credentials");
	    				    			
	    	    } else $error = t("order id not found");
	    	break;
	    	
	    	case "sms":
	    		$back_url = websiteUrl()."/merchant/purchasesms";
	    		if($data = FunctionsV3::getSMSTransLog($reference_id)){
	    			if ($credentials = mercadopagoWrapper::getAdminCredentials()){
	    				
	    				try {
	    					
	    					$resp = mercadopagoWrapper::getPaymentStatus($credentials,$reference_id);
	    					$params=array(
							  'merchant_id'=>$data['merchant_id'],
							  'sms_package_id'=>$data['sms_package_id'],
							  'payment_type'=>mercadopagoWrapper::paymentCode(),
							  'package_price'=>$data['package_price'],
							  'sms_limit'=>isset($data['sms_limit'])?$data['sms_limit']:'',
							  'date_created'=>FunctionsV3::dateNow(),
							  'ip_address'=>$_SERVER['REMOTE_ADDR'],
							  'payment_gateway_response'=>json_encode($data),						  
							  'payment_reference'=>$merchant_order_id,
							  'status'=>'paid',
							  'merchant_ref'=>$reference_id
							 );			
							 $db->insertData("{{sms_package_trans}}",$params);
						     $id = Yii::app()->db->getLastInsertID();			
						     $this->redirect(Yii::app()->createUrl('/merchant/smsReceipt',array(
							   'id'=>$id
							 ))); 
							 Yii::app()->end();
	    					
	    				} catch (Exception $e) {
							$error = Yii::t("default","Caught exception: [error]",array(
							  '[error]'=>$e->getMessage()
							));
					    }  	  
	    				
	    			} else t("invalid payment credentials");
	    		} else $error = t("Invalid reference id");
	    	break;
	    
	    	default:
	    		$error = Yii::t("default","invalid transaction type [trans_type]",array(
					  '[trans_type]'=>$trans_type
					));
	    		break;
	    }
	
	     if(!empty($error)){
			$error.='<p style="margin-top:20px;"><a href="'.$back_url.'" />'.t("back").'</a></p>';
	        $this->render('error',array(
			  'message'=>$error
			));
		}
		
	}
		
	public function actionmercadopago_failure()
	{
		$data = $_GET;
		$db = new DbExt();	    
	    $error='';
	    $back_url='';
	    
	    $trans_type = isset($data['trans_type'])?$data['trans_type']:'';	    
	    $reference_id = isset($data['external_reference'])?$data['external_reference']:'';	    
	    $merchant_order_id = isset($data['merchant_order_id'])?$data['merchant_order_id']:'';	    
	    
	    $payment_code = mercadopagoWrapper::paymentCode();
	    
	    switch ($trans_type) {
	    	case "reg":
	    		$back_url = websiteUrl()."/merchantsignup?Do=step3&token=$reference_id&gateway=$payment_code";	  
	    		if(isset($data['renew'])){
	    			$back_url.="&renew=1";
	    			$back_url.="&package_id=".$data['package_id'];
	    		}	    		
	    		$this->redirect($back_url);
	    		break;
	    
	    	case "order":	
	    	   $back_url = Yii::app()->createUrl('/store/confirmorder');
	    	   $this->redirect($back_url);
	    	break;
	    	
	    	case "sms":
	    		$back_url = Yii::app()->createUrl('/merchant/purchasesms');
	    	    $this->redirect($back_url);
	    		break;
	    	
	    	default:
	    		$error = t("Sorry but we cannot find what your are looking for.");
	    		 $this->render('error',array(
				  'message'=>$error
				));
	    		break;
	    }
	}
	
	public function actionchangepassword_sms()
	{
		FunctionsV3::registerScript(array(
		 "var ajax_action= 'verify_change_password_code'"
		));		
		$token = isset($_GET['token'])?$_GET['token']:'';		
		if($res = Yii::app()->functions->getLostPassToken($token)){			
			$this->render('changepassword_sms',array(
			 'token'=>$token
			));
		}  else $this->render('error',array(
	       'message'=>t("Sorry but this merchant is no longer available")
	    ));
	}
	
	public function actionchangepassword_successful()
	{
		$token = isset($_GET['token'])?$_GET['token']:'';	
		if($res = Yii::app()->functions->getLostPassToken($token)){
			$this->render('changepassword_ty',array(			 
			));
		}  else $this->render('error',array(
	       'message'=>t("Sorry but this merchant is no longer available")
	    ));
	}
		
	/*START CUSTOM CODE*/
	
} /*END CLASS*/