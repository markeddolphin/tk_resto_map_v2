<?php 
class FunctionsV3
{
	static $message;
	static $distance_type_result;
	
	public static function getPerPage()
	{
		return 4;		
	}
	
	public static function q($data)
	{
		return Yii::app()->db->quoteValue($data);
	}
	
    public static function prettyPrice($amount='')
	{		
		return displayPrice(getCurrencyCode(),prettyFormat( (float) $amount));		
	}			
	
	public static function getDesktopLogo()
	{
		$upload_path=self::uploadPath();
		$logo=getOptionA('website_logo');
		if (!empty($logo)){
			if (file_exists($upload_path."/$logo")){
				return uploadURL()."/$logo";
			}
		} 
		return assetsURL()."/images/logo-desktop.png";
	}
	
	public static function getMobileLogo()
	{
		$upload_path=self::uploadPath();
		$logo=getOptionA('mobilelogo');
		if (!empty($logo)){
			if (file_exists($upload_path."/$logo")){
				return uploadURL()."/$logo";
			}
		} 
		return assetsURL()."/images/logo-mobile.png";
	}	
	
	public static function getFooterAddress()
	{
		$theme_custom_footer=getOptionA('theme_custom_footer');
		if (!empty($theme_custom_footer)){
			echo $theme_custom_footer;
			return ;
		}
		
		$website_address=getOptionA('website_address');
		$website_contact_phone=getOptionA('website_contact_phone');
		$website_contact_email=getOptionA('website_contact_email');
		$htm="<p>";
		if (!empty($website_address)){
			$htm.=$website_address." ".yii::app()->functions->adminCountry().'<br/>';
		}
		if (!empty($website_contact_phone)){
			$htm.=t("Call Us")." $website_contact_phone <br/>";
		}
		if (!empty($website_contact_email)){
			$htm.="$website_contact_email";
		}
		$htm.="</p>";	
		echo $htm;
	}
	
    public static function getMenu($class="menu")
    {
    	$top_menu_activated=self::getTopMenuActivated();
    
    	$top_menu[]=array('tag'=>"signup",'label'=>''.Yii::t("default","Home"),
                'url'=> websiteUrl() );
                                              
        $top_menu[]=array('tag'=>"browse",
                'visible'=>in_array('browse',(array)$top_menu_activated)?true:false,
                'label'=>''.Yii::t("default","Browse Restaurant"),
                'url'=>array('/store/browse'));
                        
        $enabled_commission=getOptionA('admin_commission_enabled');		
		$signup_link="/store/merchantsignup";
		if ($enabled_commission=="yes"){
		   $signup_link="/store/merchantsignupselection";	
		}         
		
		$client_signup=in_array('signup',(array)$top_menu_activated)?true:false;
		if ($client_signup){
			$client_signup=Yii::app()->functions->isClientLogin()?false:true;
		}
								
		if ( getOptionA('merchant_disabled_registration')!="yes"){
		    $top_menu[]=array('tag'=>"resto_signup",
		        'visible'=>in_array('resto_signup',(array)$top_menu_activated)?true:false,
		        'label'=>''.Yii::t("default","Restaurant Signup"),
                'url'=>array($signup_link));
		}
		        
        $top_menu[]=array('tag'=>"contact",
                'visible'=>in_array('contact',(array)$top_menu_activated)?true:false,
                'label'=>''.Yii::t("default","Contact"),
                'url'=>array('/store/contact'));                             
                                       
       $top_menu[]=array('visible'=>$client_signup,
                'tag'=>"signup",'label'=>''.Yii::t("default","Login & Signup"),
                'url'=>array('/store/signup'));   
                
       if ( FunctionsV3::hasModuleAddon('driver')){
	       $top_menu[]=array('tag'=>"driver_signup",
	                'visible'=>in_array('driver_signup',(array)$top_menu_activated)?true:false,
	                'label'=>''.Yii::t("default","Driver Signup"),
	                'url'=>array('/store/driver_signup'));      
       }                                
                       
        if ( Yii::app()->functions->isClientLogin()){
        	$top_menu[]=array(
        	  'url'=>array('/store/profile'),
        	  'label'=>'<i class="ion-android-contact"></i> '.ucwords(Yii::app()->functions->getClientName()),
        	  'itemOptions'=>array('class'=>'green-button')      	  
        	);
        	
        	$top_menu[]=array(
        	  'url'=>array('/store/logout'),
        	  'label'=>t("Sign Out"),
        	  'itemOptions'=>array('class'=>'logout-menu orange-button')
        	);
        }
        
        /*LANGUAGE BAR TOP*/
        $language_selection=true;
        $theme_lang_pos=getOptionA('theme_lang_pos');
        $show_language=getOptionA('show_language');
        if($show_language==1){
        	$language_selection=false;
        }
        if ( $theme_lang_pos=="bottom" || $theme_lang_pos==""){
        	$language_selection=false;
        }
        
        if ($language_selection){
           $text_lang="<img src=\"".self::getLanguageFlag()."\">";
           $text_lang.=t(Yii::app()->language);           
           $top_menu[]=array('visible'=>$language_selection,
                'tag'=>"signup",'label'=>$text_lang,
                'itemOptions'=>array('class'=>"language-selection"),
                'url'=>"javascript:;");   
        } 
       /*LANGUAGE BAR TOP*/         
               
        return array(  		    
		    'id'=>$class,
		    'activeCssClass'=>'active', 
		    'encodeLabel'=>false,
		    'items'=>$top_menu                      
         );        
    }
    
    public static function displayServicesList($service='')
    {    
    	$htm='<ul class="services-type">';
    	switch ($service) {
    		case 1:
    			$htm.='<li>'.t("Delivery").' <i class="green-color ion-android-checkmark-circle"></i></li>';
    			$htm.='<li>'.t("Pickup").' <i class="green-color ion-android-checkmark-circle"></i></li>';
    			break;    			
    	
    		case 2:
    			$htm.='<li>'.t("Delivery").' <i class="green-color ion-android-checkmark-circle"></i></li>';    				
    			break; 
    		case 3:
    			$htm.='<li>'.t("Pickup").' <i class="green-color ion-android-checkmark-circle"></i></li>';
    			break; 
    			
    		case 4:
    			$htm.='<li>'.t("Delivery").' <i class="green-color ion-android-checkmark-circle"></i></li>';
    			$htm.='<li>'.t("Pickup").' <i class="green-color ion-android-checkmark-circle"></i></li>';
    			$htm.='<li>'.t("Dinein").' <i class="green-color ion-android-checkmark-circle"></i></li>';
    			break;    			
    			
    		case 5:
    			$htm.='<li>'.t("Delivery").' <i class="green-color ion-android-checkmark-circle"></i></li>';    			
    			$htm.='<li>'.t("Dinein").' <i class="green-color ion-android-checkmark-circle"></i></li>';
    			break;    			
    			
    	    case 6:    			
    			$htm.='<li>'.t("Pickup").' <i class="green-color ion-android-checkmark-circle"></i></li>';
    			$htm.='<li>'.t("Dinein").' <i class="green-color ion-android-checkmark-circle"></i></li>';
    			break;    			
    			
    		case 7:    			
    			$htm.='<li>'.t("Dinein").' <i class="green-color ion-android-checkmark-circle"></i></li>';
    			break;    							
    				
    		default:
    			break;
    	}
    	$htm.='</ul>';
    	return $htm;
    }
    
    public static function displayCuisine($cuisine='', $list='')
    {    
    	$p='';
    	if ( !empty($cuisine)){
    		//$list=Yii::app()->functions->Cuisine(true);
    		if (!is_array($list) && count((array)$list)<=1){
    		    $list=Yii::app()->functions->Cuisine(true);    		  
    		}
    		$cuisine=json_decode($cuisine,true);    		
    		if (is_array($cuisine) && count($cuisine)>=1){
    			foreach ($cuisine as $val) {    				
    				if (array_key_exists($val,(array)$list)){
    					
    					 if ( Yii::app()->functions->multipleField()==2){
    					 	
	    					$cuisine_id=$val;    				
	    					$cuisine_info=Yii::app()->functions->GetCuisine($cuisine_id);
	    					
	    					$cuisine_json['cuisine_name_trans']=!empty($cuisine_info['cuisine_name_trans'])?
	    					json_decode($cuisine_info['cuisine_name_trans'],true):'';
	    					
    					    $p.= qTranslate($list[$val],'cuisine_name',$cuisine_json)  .", ";
    					 } else {
    					
    					    $p.= $list[$val].", ";
    					 }
    				}
    			}
    			$p=substr($p,0,-2);
    		}
    	}
    	return $p;
    }
    
    public static function minimumDeliveryFee()   
    {
    	$minimum_list=array(
		  '5'=>"< ".displayPrice(baseCurrency(),5),
		  '10'=>"< ".displayPrice(baseCurrency(),10),
		  '15'=>"< ".displayPrice(baseCurrency(),15),
		  '20'=>"< ".displayPrice(baseCurrency(),20),
		);		
		return $minimum_list;
    }
    
    public static function getMerchantLogo($merchant_id='',$logo='')
    {    	
    	$upload_path=Yii::getPathOfAlias('webroot')."/upload";     
    	if (!empty($logo)){
    		$merchant_logo=$logo;
    	} else $merchant_logo=Yii::app()->functions->getOption('merchant_photo',$merchant_id);    	 	    	
    	if (!empty($merchant_logo)){
    		if (file_exists($upload_path."/".$merchant_logo)){
    		   $merchant_logo=uploadURL()."/$merchant_logo";    
    		} else $merchant_logo=assetsURL()."/images/default-image-merchant.png";
    	} else $merchant_logo=assetsURL()."/images/default-image-merchant.png";
    	return $merchant_logo;
    }
    
    public static function searchByAddress($address='',$page=0,$per_page=5,$getdata='')
    {    	
    	if (empty($address)){
    		return false;
    	}
    	
    	$p = new CHtmlPurifier();
    	$address = $p->purify($address);
    	    	    	
    	if ($page>0){
    	    $page=($page-1)*$per_page;
    	}
    	
    	$lat=0;
		$long=0;
		$and='';
		    	
    	try {
    		$lat_res = MapsWrapper::geoCodeAdress($address);    		
    		$lat=$lat_res['lat'];
			$long=$lat_res['long'];
    	} catch (Exception $e) {			
			$lat = 0; $long=0;		    					    	  
		}		    					    	
    	    	    	    
    	$home_search_unit_type=getOptionA('home_search_unit_type');
		$home_search_radius=getOptionA('home_search_radius');
				
		if (empty($home_search_unit_type)){
			$home_search_unit_type='mi';
		}	
		if (!is_numeric($home_search_radius)){
			$home_search_radius=10;
		}			
    	
		$distance_exp=3959;
		if ($home_search_unit_type=="km"){
			$distance_exp=6371;
		}		
		
		$sort_by =" ORDER BY open_status DESC, is_sponsored DESC, distance ASC";		
		$sort_combine=$sort_by;
		
		if (isset($getdata['sort_filter'])){
			if (!empty($getdata['sort_filter'])){
				$sort="ASC";
				if($getdata['sort_filter']=="ratings" || $getdata['sort_filter']=="open_status" ){
					$sort="DESC";
				}
				$sort_combine=" ORDER BY ".$getdata['sort_filter']." $sort";
			}
		}
		
		//dump($getdata); die();
		if (isset($getdata['filter_delivery_type'])){			
			switch ($getdata['filter_delivery_type']) {
				case 1:
					$and = "AND ( service='1' OR service ='2' OR service='3' OR service='4' OR service='5' OR service='6' )";
					break;			
				case 2:
					$and = "AND ( service ='2')";
					break;
				case 3:
					$and = "AND ( service ='3')";
					break;		
				case 4:					
					break;			
				case 5:
					$and = "AND ( service='1' OR service ='2' OR service ='4' OR service ='7' )";
					break;									
				case 6:
					$and = "AND ( service='3' OR service ='4' OR service ='6' OR service ='7' )";
					break;										
				case 7:
					$and = "AND ( service='7' )";
					break;											
				default:
					break;
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
							$filter_cuisine.=" LIKE ".FunctionsV3::q('%"'.$val.'"%')." ";
						} else $filter_cuisine.=" OR cuisine LIKE ".FunctionsV3::q('%"'.$val.'"%')." ";
						
						$x++;
					}
				}				
				if (!empty($filter_cuisine)){
				   $and.=" AND (cuisine $filter_cuisine) ";
				}
			}
		}
		
		$filter_minimum='';
		if (isset($_GET['filter_minimum'])){
			if (is_numeric($_GET['filter_minimum'])){				
				$and.=" AND CAST(minimum_order as SIGNED) <=".FunctionsV3::q($_GET['filter_minimum'])." ";
			}		
		}		
		
		if (isset($_GET['restaurant_name'])){
			if (!empty($_GET['restaurant_name'])){			    
			    $and.=" AND restaurant_name LIKE ".FunctionsV3::q("%".$_GET['restaurant_name']."%")."  ";
			}
		}
		
		$and.=" AND status='active' ";
		$and.=" AND is_ready ='2' ";	
		
		$time_now = date("H:i");
		$open_day = strtolower(date("l"));		
		    	
    	$stmt="
		SELECT SQL_CALC_FOUND_ROWS a.*, ( $distance_exp * acos( cos( radians($lat) ) * cos( radians( latitude ) ) 
		* cos( radians( lontitude ) - radians($long) ) 
		+ sin( radians($lat) ) * sin( radians( latitude ) ) ) ) 
		AS distance,
		concat(street,' ',city,' ',state,' ',post_code) as merchant_address,
		
		(
			select count(*) from
			{{opening_hours}}
			where
			merchant_id = a.merchant_id
			and
			day=".q($open_day)."
			and
			status = 'open'
			and 
			
			(
			CAST(".q($time_now)." AS TIME)
			BETWEEN CAST(start_time AS TIME) and CAST(end_time AS TIME)
			
			or
			
			CAST(".q($time_now)." AS TIME)
			BETWEEN CAST(start_time_pm AS TIME) and CAST(end_time_pm AS TIME)
			
			)
			
		) as open_status

		
		FROM {{view_merchant}} a 
		HAVING distance < delivery_distance_covered		
		$and
		$sort_combine
		LIMIT $page,$per_page
		"; 
    	    	    	    	    	        				
		Yii::app()->db->createCommand("SET SQL_BIG_SELECTS=1")->query();	
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){			
			$total_found=0;
			if($rows = Yii::app()->db->createCommand("SELECT FOUND_ROWS()")->queryRow()){
				$total_found=$rows['FOUND_ROWS()'];
			}
			return array(
			   'total'=>$total_found,
			   'client'=>array(
			     'lat'=>$lat,
			     'long'=>$long
			   ),
			   'list'=>$res,
			   'sql'=>$stmt
			);
		}
    	return false;
    }
    
    public static function  searchGetFilter($getdata='')
    {
    	$and='';    	
    	    					    	
		if (isset($getdata['filter_delivery_type'])){			
			switch ($getdata['filter_delivery_type']) {				
				case 1:
					$and = "AND ( service='1' OR service ='2' OR service='3' OR service='4' OR service='5' OR service='6' )";
					break;			
				case 2:
					$and = "AND ( service ='2')";
					break;
				case 3:
					$and = "AND ( service ='3')";
					break;		
				case 4:					
					break;			
				case 5:
					$and = "AND ( service='1' OR service ='2' OR service ='4' OR service ='7' )";
					break;									
				case 6:
					$and = "AND ( service='3' OR service ='4' OR service ='6' OR service ='7' )";
					break;										
				case 7:
					$and = "AND ( service='7' )";
					break;											
				default:
					break;
			}		
		}		

		$filter_cuisine='';
		if (isset($getdata['filter_cuisine'])){
			$filter_cuisines=!empty($getdata['filter_cuisine'])?explode(",",$getdata['filter_cuisine']):false;
			if (is_array($filter_cuisines) && count($filter_cuisines)>=1){
				$x=1;
				foreach ($filter_cuisines as $val) {				
					if (!empty($val)){						
						if ( $x==1){
							$filter_cuisine.=" LIKE ".FunctionsV3::q('%"'.$val.'"%')." ";
						} else $filter_cuisine.=" OR cuisine LIKE ".FunctionsV3::q('%"'.$val.'"%')." ";
						$x++;
					}
				}				
				if (!empty($filter_cuisine)){
				   $and.=" AND (cuisine $filter_cuisine) ";
				}
			}
		}
		
		$filter_minimum='';
		if (isset($getdata['filter_minimum'])){
			if (is_numeric($getdata['filter_minimum'])){				
				$and.=" AND CAST(minimum_order as SIGNED) <=".FunctionsV3::q($getdata['filter_minimum'])." ";
			}		
		}		
		
		if (isset($getdata['restaurant_name'])){
			if (!empty($getdata['restaurant_name'])){			    
			    $and.=" AND restaurant_name LIKE ".FunctionsV3::q("%".$getdata['restaurant_name']."%")." ";
			}
		}
		
		$and.=" AND status='active' ";
		$and.=" AND is_ready ='2' ";
		return $and;
    }
    
    public static function searchByMerchant($stype='',$address='',$page=0,$per_page=5,$getdata='')
    {        
    	
    	$p = new CHtmlPurifier();
    	$address  = $p->purify($address);
    	
        if ($page>0){
    	    $page=($page-1)*$per_page;
    	}
    	
        $lat=0;
		$long=0;
		if ( !empty($address)){
	    	if ($lat_res=Yii::app()->functions->geodecodeAddress($address)){	    		
		        $lat=$lat_res['lat'];
				$long=$lat_res['long'];
	    	} 
		}
    	
    	if (empty($lat)){
			$lat=0;
		}		
		if (empty($long)){
			$long=0;
		}					
		    	    	    	        
        $sort_by =" ORDER BY is_sponsored DESC, restaurant_name ASC";		
		$sort_combine=$sort_by;
				
		if (isset($getdata['sort_filter'])){
			if (!empty($getdata['sort_filter'])){
				$sort="ASC";
				if($getdata['sort_filter']=="ratings"){
					$sort="DESC";
				}
				$sort_combine=" ORDER BY ".$getdata['sort_filter']." $sort";
			}
		}
		
		$and=self::searchGetFilter($getdata);
		
		$stmt=''; $query='';
		
		$time_now = date("H:i");
		$open_day = strtolower(date("l"));
		
		$and_open_qry = ",
		(
		select count(*) from
		{{opening_hours}}
		where
		merchant_id = a.merchant_id
		and
		day=".q($open_day)."
		and
		status = 'open'
		and 
		
		(
		CAST(".q($time_now)." AS TIME)
		BETWEEN CAST(start_time AS TIME) and CAST(end_time AS TIME)
		
		or
		
		CAST(".q($time_now)." AS TIME)
		BETWEEN CAST(start_time_pm AS TIME) and CAST(end_time_pm AS TIME)
		
		)
		
		) as open_status
		";
				
		switch ($stype) {
			case "kr_search_restaurantname":
				$merchant_name= isset($getdata['restaurant-name'])?$getdata['restaurant-name']:'';				
				if (preg_match("/'/i", $merchant_name )) {
					$merchant_name=substr($merchant_name,0, strpos($merchant_name,"'"));					
					$query=" restaurant_name LIKE ".FunctionsV3::q("%$merchant_name%")." ";
				} else $query=" restaurant_name LIKE ".FunctionsV3::q("%$merchant_name%")." ";				
				break;
		
			case "kr_search_streetname":	
			   $stree_name= isset($getdata['street-name'])?$getdata['street-name']:'';			   
			   $query=" street LIKE ".FunctionsV3::q("%$stree_name%")." ";			   
			   break;
			   
			case "kr_search_category":   							   
			   $query =" 1";			   
			   break;
			   
			case "kr_search_foodname":   
			   $foodname_str='';
			   if (isset($getdata['foodname'])){
				  if (!empty($getdata['foodname'])){
					  $foodname_str="%".$getdata['foodname']."%";					  			 
				  } else $foodname_str='-1';			
			   } else $foodname_str='-1';		   			   
			   $stmt="SELECT SQL_CALC_FOUND_ROWS a.*,
			   concat(street,' ',city,' ',state,' ',post_code) as merchant_address
			   $and_open_qry
			   FROM
		       {{view_merchant}} a
		       WHERE
		       merchant_id = (
		         select merchant_id
		         from
		         {{item}}
		         where
		         item_name like ".self::q($foodname_str)."
		         and
		         merchant_id=a.merchant_id
		         limit 0,1
		       )
		       $and
		       $sort_combine
		       LIMIT $page,$per_page
		       ";	
			   break;
			   
			case "kr_postcode":   
			   $post_code='-1';
			   $zipcode=isset($getdata['zipcode'])?$getdata['zipcode']:'';
			   if(!empty($zipcode)){
			   	  $zipcode=explode(" ",$zipcode);
			   	  $post_code=$zipcode[0];
			   }
			   //$query=" post_code LIKE '%$post_code%' ";
			   $query=" post_code LIKE ".FunctionsV3::q("%$post_code%")." ";
			   break;
			   
			case "kr_search_location":   
			   $location_type=getOptionA('admin_zipcode_searchtype');			   
			   $location_data=Cookie::getCookie('kr_location_search');
			   if(!empty($location_data)){
			   	  $location_data=json_decode($location_data,true);
			   	  switch ($location_type) {
			   	  		case "2":
			   	  		case 2:	
			   	  		    $and_location ='';	
			   	  		    $state_id = isset($location_data['state_id'])?(integer)$location_data['state_id']:0;
			   	  		    $city_id = isset($location_data['city_id'])?(integer)$location_data['city_id']:0;
			   	  		    if($city_id>0){			   	  		    	
			   	  		      $and_location = "and city_id=".self::q($city_id)." ";
			   	  		  }		   	  		
			   	  			$query=" AND a.merchant_id IN (
					   	     select merchant_id 
					   	     from
					   	     {{location_rate}}
					   	     where
					   	     state_id=".self::q($state_id)."
					   	     $and_location					   	     
					   	    ) ";
			   	  			break;
			   	  	
			   	  		case "1":
			   	  		case 1;	
			   	  		  $and_location ='';	
			   	  		  $area_id = isset($location_data['area_id'])?(integer)$location_data['area_id']:0;
			   	  		  $city_id = isset($location_data['city_id'])?(integer)$location_data['city_id']:0;
			   	  		  if($area_id>0){
			   	  		      $and_location = "and  area_id=".self::q($area_id)." ";
			   	  		  }		   	  		
				   	  	  $query=" AND a.merchant_id IN (
					   	    select merchant_id 
					   	    from
					   	    {{location_rate}}
					   	    where
					   	    city_id=".self::q($city_id)."
					   	    $and_location
					   	   ) ";	 	
			   	  		   break;
			   	  		   
			   	  		case "3":
			   	  		case 3;	
			   	  		   $postal_code = isset($location_data['postal_code'])?(integer)$location_data['postal_code']:0;
			   	  		   $query=" AND a.merchant_id IN (
					   	    select merchant_id 
					   	    from
					   	    {{view_location_rate}}
					   	    where
					   	    postal_code=".self::q($postal_code)."
					   	   ) ";	 	
			   	  		   break;
			   	  		   
			   	  		default:
			   	  			break;
			   	  	}			   	  
			   }
			   $stmt="
			   SELECT SQL_CALC_FOUND_ROWS a.*,
	           concat(street,' ',city,' ',state,' ',post_code) as merchant_address	            	       
	           $and_open_qry
	            
	           FROM {{view_merchant}} a
			   WHERE 1			   
			   $query
	           $and
			   $sort_combine
			   LIMIT $page,$per_page
			   ";				   	  
			   break;
			   
			default:
				break;
		}        
                
		if (empty($stmt)){			
	        $stmt="
	        SELECT SQL_CALC_FOUND_ROWS a.*,
	        concat(street,' ',city,' ',state,' ',post_code) as merchant_address
	        $and_open_qry
	        FROM {{view_merchant}} a
	        WHERE
	        $query
	        $and
			$sort_combine
			LIMIT $page,$per_page
	        ";
		}        				
        $DbExt=new DbExt();
		$DbExt->qry("SET SQL_BIG_SELECTS=1");		
		if ($res=$DbExt->rst($stmt)){			
						
			$stmt_rows="SELECT FOUND_ROWS()";
			$total_found=0;
			if ($rows=$DbExt->rst($stmt_rows)){
				$total_found=$rows[0]['FOUND_ROWS()'];
			}
			return array(
			   'total'=>$total_found,
			   'client'=>array(
			     'lat'=>$lat,
			     'long'=>$long
			   ),
			   'list'=>$res,
			   'sql'=>$stmt
			);
		}
    	return false;
    }
    
    public static function merchantOpenTag($merchant_id='')
    {
    	$is_merchant_open = Yii::app()->functions->isMerchantOpen($merchant_id); 
	    $merchant_preorder= Yii::app()->functions->getOption("merchant_preorder",$merchant_id);
	    
	    $now=date('Y-m-d');
		$is_holiday=false;
	        if ( $m_holiday=Yii::app()->functions->getMerchantHoliday($merchant_id)){  
      	   if (in_array($now,(array)$m_holiday)){
      	   	  $is_merchant_open=false;
      	   }
        }
        
        if ( $is_merchant_open==true){
        	if ( getOption($merchant_id,'merchant_close_store')=="yes"){
        		$is_merchant_open=false;	
        		$merchant_preorder=false;			        		
        	}
        }
        
        if ($is_merchant_open){
        	$tag='<span class="label label-success">'.t("Open").'</span>';
        } else {
        	if ($merchant_preorder){
        		$tag='<span class="label label-info">'.t("Pre-Order").'</span>';
        	} else {
        		$tag='<span class="label label-danger">'.t("Closed").'</span>';
        	}
        }      
        return $tag;  
    }
    
    public static function getFreeDeliveryTag($merchant_id='')
    {
    	$fee=getOption($merchant_id,'free_delivery_above_price');
    	if ($fee>0){
    		return '<span class="label label-default">'. t("Free Delivery On Orders Over")." ". self::prettyPrice($fee).'</span>';
    	}
    	return '&nbsp;';
    }
    
    public static function getDeliveryEstimation($merchant_id='')
    {
    	$delivery_est=Yii::app()->functions->getOption("merchant_delivery_estimation",$merchant_id);
    	if (empty($delivery_est)){
    		return t("not available");
    	}
    	return $delivery_est;
    }
    
    public static function getOffersByMerchant($merchant_id='',$display_type=1)
    {
    	$offer='';
    	if ( $res=Yii::app()->functions->getMerchantOffersActive($merchant_id)){    		    		
    		if ($display_type==1){
    			$offer=number_format($res['offer_percentage'],0)."% ".t("Off");
    		} else {
    			$applicable_to_list = '';
    			if(isset($res['applicable_to'])){
    			   $applicable_to=json_decode($res['applicable_to'],true);	
    			   if(is_array($applicable_to) && count($applicable_to)>=1){
    			   	  foreach ($applicable_to as $applicable_to_val) {    			   	  	 
    			   	  	 $applicable_to_list.=t($applicable_to_val).",";
    			   	  }
    			   	  $applicable_to_list = substr($applicable_to_list,0,-1);
    			   }    			
    			}    		    	
    			if (!empty($applicable_to_list)){
    				$offer=number_format($res['offer_percentage'],0)."% ".t("off today on orders over");
	    			$offer.=" ".self::prettyPrice($res['offer_price']);
	    			$offer.=" ".t("when you checkout via")." ".$applicable_to_list;
    			} else {
	    			$offer=number_format($res['offer_percentage'],0)."% ".t("off today on orders over");
	    			$offer.=" ".self::prettyPrice($res['offer_price']);
    			}
    		}
    		return $offer;
    	}
    	return false;
    }   
    
    public static function getDistanceBetweenPlot($lat1, $lon1, $lat2, $lon2, $unit)
    {
    	  
    	  if(empty($lat2) && empty($lon2)){
    	  	  return false;
    	  }
    	  if(empty($lat1) && empty($lon1)){
    	  	  return false;
    	  }
    	  
    	  self::$distance_type_result='';
    	  
    	  $map = FunctionsV3::getMapProvider();
    	      	     	  
    	  if ($map['provider']=="mapbox"){
    	  	  KMapbox::setToken($map['token']);
    	  	  $origin="$lon1,$lat1";
    	  	  $destination="$lon2,$lat2";
    	  	  if ( $resp = KMapbox::matrix($origin , $destination , $unit ) ){  
    	  	  	 if(isset($_GET['debug'])){
    	  	  	 	dump($resp);
    	  	  	 }
    	  	  	 if(!empty($resp['unit'])){
    	  	  	    self::$distance_type_result = $resp['unit'];
    	  	  	 }
    	  	  	 return $resp['raw_value'];
    	  	  }    	  
    	  	  return false;
    	  } 
    	  
    	  $units_params='';
    	  
    	  switch ($unit) {
    	  	case "M":
    	  	case "Miles":
    	  		$units_params='imperial';
    	  		break;
    	  
    	  	default:
    	  		$units_params='metric';
    	  		break;
    	  }
    	  
    	  $method=getOptionA('google_distance_method');
    	  $use_curl=getOptionA('google_use_curl');
    	  
    	  $protocol = isset($_SERVER["https"]) ? 'https' : 'http';
    	  $key=Yii::app()->functions->getOptionAdmin('google_geo_api_key');
    	  
    	  //dump($method);
    	  
    	  if ($method=="driving" || $method=="transit"){
    	  	 $url="https://maps.googleapis.com/maps/api/distancematrix/json";
    	  	 $url.="?origins=".urlencode("$lat1,$lon1");
    	  	 $url.="&destinations=".urlencode("$lat2,$lon2");
    	  	 $url.="&mode=".urlencode($method);    	  
    	  	 $url.="&units=".urlencode($units_params);
    	  	 if(!empty($key)){
    	  	 	$url.="&key=".urlencode($key);
    	  	 }
    	  	 
    	  	 if(isset($_GET['debug'])){
    	  	    //dump($url);
    	  	 }
    	  	 
    	  	 if ($use_curl==2){
    	  	 	$data = Yii::app()->functions->Curl($url);
    	  	 } else $data = @file_get_contents($url);	
    	  	 $data = json_decode($data);  
    	  	     	  	 
    	  	 if (is_object($data)){
    	  	 	if ($data->status=="OK"){ 
    	  	 		if($data->rows[0]->elements[0]->status=="ZERO_RESULTS"){
    	  	 			return false;
    	  	 		}
    	  	 		if($data->rows[0]->elements[0]->status=="NOT_FOUND"){
    	  	 			return false;
    	  	 		}    	  	 		
    	  	 		    	  	 	
    	  	 		$distance=0;    	  	 		    	  	 		
    	  	 		$text  = $data->rows[0]->elements[0]->distance->text;
    	  	 		$value = $data->rows[0]->elements[0]->distance->value;       	  	 		
    	  	 		    	  	 			 	
    	  	 		if($unit=="M"){    	  	 			
						$distance= (integer) $value*0.000621371192;
					} else $distance= (integer) $value*0.001;
										
					$distance = number_format($distance,1,'.',''); 
					
					if($distance<=0){                						
						if($unit=="M"){
						    $unit='ft';
					    } else $unit='m';
					}
					
					self::$distance_type_result = $unit;		
					
					return $distance;			
    	  	 		    	  	 		
    	  	 	} elseif ( $data->status=="OVER_QUERY_LIMIT" ) {
    	  	 		return 0;
    	  	 	}    	  	 
    	  	 }
    	  	 return false;
    	  }
    	  
    	  if (empty($lat1)){ return false; }
    	  if (empty($lon1)){ return false; }
    	  
    	  $theta = $lon1 - $lon2;
		  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		  $dist = acos($dist);
		  $dist = rad2deg($dist);
		  $miles = $dist * 60 * 1.1515;
		  $unit = strtoupper($unit);
		
		  if ($unit == "K") {
		      return ($miles * 1.609344);
		  } else if ($unit == "N") {
		      return ($miles * 0.8684);
		  } else {
		      return $miles;
		  }
    }       
    
    public static function getMerchantDistanceType($merchant_id='')
    {
    	$distance_type=getOption($merchant_id,'merchant_distance_type');
    	$distance_type=strtolower($distance_type);        
        switch ($distance_type) {
        	case "mi":
        		$type="M";
        		break;        
        	case "km":	
        	    $type="K";
        	    break;
        	default:
        		$type="M";
        		break;
        }
        return $type;
    }
    
    public static function unit($unit='')
    {    	
    	$type='';
    	$unit=strtolower($unit);        
        switch ($unit) {
        	case "mi":
        		$type="M";
        		break;        
        	case "km":	
        	    $type="K";
        	    break;
        	default:
        		$type="M";
        		break;
        }
        return $type;
    }
    
    public static function getMerchantDeliveryFee($merchant_id='',$fee='',$distance='',$unit='')
    {
    	//$distance=!empty($distance)?number_format($distance,3):0;    	
    	$distance=is_numeric($distance)?number_format($distance,3):0; 
    	$shipping_enabled=getOption($merchant_id,'shipping_enabled');    	
    	$charge=$fee;
    	if ( $shipping_enabled==2){
    		$FunctionsK=new FunctionsK();
    		$charge=$FunctionsK->getDeliveryChargesByDistance(
    		  $merchant_id,
    		  $distance,
    		  $unit,
    		  $fee
    		);
    	}    	
    	
    	if ($unit=="ft" || $unit=="mm" || $unit=="mt"){
    		if ($fee>0){
    		    return $fee;
	    	}
	    	return false;
    	}
    	
    	if ($charge>0){
    		return $charge;
    	}
    	return false;
    }
    
    public static function clearSearchParams($field_to_clear='',$extra_params='')
    {    	
    	$request=$_GET;    
    	$new_request='';
    	if (is_array($request) && count($request)>=1){
    		foreach ($request as $key=>$val) {
    			if ($key!=$field_to_clear){
    				$new_request.="$key=$val&";
    			}    			
    		}
    	}
    	if (!empty($extra_params)){
    		$new_request.=$extra_params;
    	}
    	return Yii::app()->createUrl('/searcharea?'.$new_request);    	
    }
    
	public static function getMerchantBySlug($slug_id='')
	{
		
		/*$p = new CHtmlPurifier();
		$slug_id = $p->purify($slug_id);*/
		
		$DbExt=new DbExt;
		$DbExt->qry("SET SQL_BIG_SELECTS=1");
		$stmt="SELECT *,
		concat(street,' ',city,' ',state,' ',post_code) as merchant_address
		 FROM
		{{view_merchant}}
		WHERE
		restaurant_slug=".q($slug_id)."
		LIMIT 0,1
		";
		$DbExt->qry("SET SQL_BIG_SELECTS=1");
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}	    
	
	public static function getMerchantById($merchant_id='')
	{
		$DbExt=new DbExt;
		
		$DbExt->qry("SET SQL_BIG_SELECTS=1");
		
		$stmt="SELECT *,
		concat(street,' ',city,' ',state,' ',post_code) as merchant_address
		 FROM
		{{view_merchant}}
		WHERE
		merchant_id=".q($merchant_id)."
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}	    	
	
	public static function isMerchantcanCheckout($merchant_id='')
	{
		$msg=''; $code=2; $button=''; $holiday=2;
		$is_pre_order=2;
		
		$now=date('Y-m-d');
		if ( $m_holiday=Yii::app()->functions->getMerchantHoliday($merchant_id)){      	         	  
      	   if (in_array($now,(array)$m_holiday)){
      	   	  $holiday=1;
      	   	  $msg=getOption($merchant_id,'merchant_close_msg_holiday');   
      	   	  if (empty($msg)){
      	   	  	  $msg=t("Sorry merchant is closed");
      	   	  }
      	   }
        }
                
        $is_merchant_open = Yii::app()->functions->isMerchantOpen($merchant_id); 
	    $merchant_preorder= Yii::app()->functions->getOption("merchant_preorder",$merchant_id);
        
	    if ($is_merchant_open){
	    	$code=1; $button=t("Checkout");
	    	if ($holiday==1){
	    		$code=2;
	    	}
	    } else {
	    	if ($merchant_preorder==1){
	    		$code=1; $button=t("Pre-Order");
	    		$is_pre_order=1;
	    	} else {
	    		if ($holiday==2){
		    		$merchant_close_msg=getOption($merchant_id,'merchant_close_msg');
		    		if (empty($merchant_close_msg)){
		    			$msg=t("Sorry merchant is closed.");
		    		} else $msg=$merchant_close_msg;
	    		}
	    	}
	    }
	    
	    /*check if merchant is close via backend*/
	    if ( getOption($merchant_id,'merchant_close_store')=="yes"){
	    	$code=2; 
	    	$msg=getOption($merchant_id,'merchant_close_msg');
	    	if (empty($msg)){
	    	    $msg=t("This restaurant is closed now. Please check the opening times.");
	    	}
	    }
	    
	    if (!yii::app()->functions->validateSellLimit($merchant_id) ){
        	$msg=t("This merchant has reach the maximum sells per month");
        	$code=2;
        }
	    	    
	    return array(
	     'code'=>$code,
	     'msg'=>$msg,
	     'button'=>$button,
	     'holiday'=>$holiday,
	     'is_pre_order'=>$is_pre_order
	    );
	}
	
	public static function getItemFirstPrice($prices='',$discount='')
	{		
		//dump($prices); die();
		$size='';
		if (is_array($prices) && count($prices)>=1){
			$regular_price=$prices[0]['price'];			
			if(isset($prices[0]['size'])){
				if(!empty($prices[0]['size'])){
					$size=$prices[0]['size'];
				}
			}
			if ($discount>0){
				$regular_price=$regular_price-$discount;
			}
			if(!empty($size)){
				//return $size." ".self::prettyPrice($regular_price);
				if($discount>=1){
					$original_price = unPrettyPrice($regular_price) + unPrettyPrice($discount);
					return qTranslate($size,'size',$prices[0]) . "&nbsp;<span class=\"normal-price\">".self::prettyPrice($original_price)."</span>" ." <span class=\"sale-price\">".self::prettyPrice($regular_price)."</span>";
				} else return qTranslate($size,'size',$prices[0]) ." ".self::prettyPrice($regular_price);							
			} else {
				if($discount>=1){
					$original_price = unPrettyPrice($regular_price) + unPrettyPrice($discount);
					return "<span class=\"normal-price\">".self::prettyPrice($original_price)."</span>" ." <span class=\"sale-price\">".self::prettyPrice($regular_price)."</span>";
				} else return self::prettyPrice($regular_price);							
			}		
		}
		return '-';
	}
		
/*	public static function getItemFirstPrice($prices='',$discount='')
	{
		if (is_array($prices) && count($prices)>=1){
			$regular_price=$prices[0]['price'];
			if ($discount>0){
				$regular_price=$regular_price-$discount;
			}
			return self::prettyPrice($regular_price);
		}
		return '-';
	}*/
	
	public static function uploadPath()
	{
		return Yii::getPathOfAlias('webroot')."/upload";
	}
	
	public static function fixedLink($link='')
	{
		if  (!empty($link)){
	  	   if (!preg_match("/http/i", $link)) {
	  	   	   $link="http://".$link;
	  	   }
	  	   return $link;
       }  
       return false;
	}
	
	public static function getFoodDefaultImage($photo='',$small=true)
	{
		$path=self::uploadPath()."/$photo";		
		if (!file_exists($path) || empty($photo)){
			if ( $small){
			    $url_image=websiteUrl()."/assets/images/default-food-image.png";
			} else $url_image=websiteUrl()."/assets/images/default-food-image-large.png";
		} else $url_image=websiteUrl()."/upload/$photo";		
		return $url_image;
	}		
	
    public static function getMerchantOpeningHours($merchant_id='')
	{
        $stores_open_day=Yii::app()->functions->getOption("stores_open_day",$merchant_id);
		$stores_open_starts=Yii::app()->functions->getOption("stores_open_starts",$merchant_id);
		$stores_open_ends=Yii::app()->functions->getOption("stores_open_ends",$merchant_id);
		$stores_open_custom_text=Yii::app()->functions->getOption("stores_open_custom_text",$merchant_id);
		
		$stores_open_day=!empty($stores_open_day)?(array)json_decode($stores_open_day):false;
		$stores_open_starts=!empty($stores_open_starts)?(array)json_decode($stores_open_starts):false;
		$stores_open_ends=!empty($stores_open_ends)?(array)json_decode($stores_open_ends):false;
		$stores_open_custom_text=!empty($stores_open_custom_text)?(array)json_decode($stores_open_custom_text):false;
		
		
		$stores_open_pm_start=Yii::app()->functions->getOption("stores_open_pm_start",$merchant_id);
		$stores_open_pm_start=!empty($stores_open_pm_start)?(array)json_decode($stores_open_pm_start):false;
		
		$stores_open_pm_ends=Yii::app()->functions->getOption("stores_open_pm_ends",$merchant_id);
		$stores_open_pm_ends=!empty($stores_open_pm_ends)?(array)json_decode($stores_open_pm_ends):false;		
												
		$open_starts='';
		$open_ends='';
		$open_text='';
		$data=array();
				
		if (is_array($stores_open_day) && count($stores_open_day)>=1){
			foreach ($stores_open_day as $val_open) {	
				if (array_key_exists($val_open,(array)$stores_open_starts)){
					$open_starts=timeFormat($stores_open_starts[$val_open],true);
				}							
				if (array_key_exists($val_open,(array)$stores_open_ends)){
					$open_ends=timeFormat($stores_open_ends[$val_open],true);
				}							
				if (array_key_exists($val_open,(array)$stores_open_custom_text)){
					$open_text=$stores_open_custom_text[$val_open];
				}					
				
				$pm_starts=''; $pm_ends=''; $pm_opens='';
				if (array_key_exists($val_open,(array)$stores_open_pm_start)){
					$pm_starts=timeFormat($stores_open_pm_start[$val_open],true);
				}											
				if (array_key_exists($val_open,(array)$stores_open_pm_ends)){
					$pm_ends=timeFormat($stores_open_pm_ends[$val_open],true);
				}												
				
				$full_time='';
				if (!empty($open_starts) && !empty($open_ends)){					
					$full_time=$open_starts." - ".$open_ends."&nbsp;&nbsp;";
				}			
				if (!empty($pm_starts) && !empty($pm_ends)){
					if ( !empty($full_time)){
						$full_time.=" / ";
					}				
					$full_time.="$pm_starts - $pm_ends";
				}												
								
				$data[]=array(
				  'day'=>$val_open,
				  'hours'=>$full_time,
				  'open_text'=>$open_text
				);
				
				$open_starts='';
		        $open_ends='';
		        $open_text='';
			}
			return $data;
		}			
		return false;		
	}	
		
    public static function merchantActiveVoucher($merchant_id='')
    {    	
    	$mtid='"'.$merchant_id.'"';
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{voucher_new}}
			WHERE
			status in ('publish','published')
			AND
			now() <= expiration
			AND ( merchant_id =".self::q($merchant_id)." OR joining_merchant LIKE ".FunctionsV3::q($mtid)." )
			LIMIT 0,10			
		";	 	 
	    //dump($stmt);   
		if ( $res=$DbExt->rst($stmt)){			
			return $res;
		}
		return false;
    }	
    
    public static function sectionHeader($title='')
    {
    	?>
    	<div class="section-label">
	    <a class="section-label-a">
	      <span class="bold">
	      <?php echo t($title)?></span>
	      <b></b>
	    </a>     
	   </div>    
    	<?php
    }
    
    public static function PaymentOptionList()
    {
    	/*
    	Important: you can change the value but not the key
    	like cod ocr pyr etc 
    	*/
    	
    	return array(
    	  'cod'=>t("Cash On delivery"),
    	  'ocr'=>t("Offline Credit Card Payment"),
    	  'pyr'=>t("Pay On Delivery"),
    	  //'pyp'=>t("Paypal"),
    	  'paypal_v2'=>t("Paypal V2"),
    	  'stp'=>t("Stripe"),
    	  //'mcd'=>t("Mercapado"),
    	  'mercadopago'=>t("mercadopago V2"),
    	  'ide'=>t("Sisow"),
    	  'payu'=>t("Payumoney"),
    	  'pys'=>t("Paysera"),    	  
    	  'bcy'=>t("Barclay"),
    	  'epy'=>t("EpayBg"),
    	  'atz'=>t("Authorize.net"),
    	  'obd'=>t("Offline Bank Deposit"),
    	  'btr' =>t("Braintree"),
    	  'rzr'=>t("Razorpay"),
    	  'vog'=>t("voguepay"),    	  
    	);
    }
    
    public static function getOfflinePaymentList()
    {
    	/*
    	Important: you can change the value but not the key
    	like cod ocr pyr etc 
    	*/
    	
    	return array(
    	   'cod'=>t("Cash On delivery"),
    	   'ocr'=>t("Offline Credit Card Payment"),
    	   'obd'=>t("Offline Bank Deposit") ,
    	   'pyr'=>t("Pay On Delivery"),   	  
    	);
    }
    
    public static function getAdminPaymentList()
    {
    	$payment_list=self::PaymentOptionList();    	
    	
    	$payment_available = array();
    	if (getOptionA('admin_stripe_enabled')=="yes"){
    		$payment_available[]='stp';
    	}    
    	if (getOptionA('admin_enabled_paypal')==""){
    		$payment_available[]='pyp';
    	}    
    	if (getOptionA('admin_enabled_card')==""){
    		$payment_available[]='ocr';
    	}
    	if (getOptionA('admin_mercado_enabled')=="yes"){
    		$payment_available[]='mcd';
    	}
    	if (getOptionA('admin_sisow_enabled')=="yes"){
    		$payment_available[]='ide';
    	}
    	if (getOptionA('admin_payu_enabled')=="yes"){
    		$payment_available[]='payu';
    	}
    	if (getOptionA('admin_bankdeposit_enabled')=="yes"){
    		$payment_available[]='obd';
    	}
    	if (getOptionA('admin_paysera_enabled')=="yes"){
    		$payment_available[]='pys';
    	}
    	if (getOptionA('admin_enabled_barclay')=="yes"){
    		$payment_available[]='bcy';
    	}
    	if (getOptionA('admin_enabled_epaybg')=="yes"){
    		$payment_available[]='epy';
    	}
    	if (getOptionA('admin_enabled_autho')=="yes"){
    		$payment_available[]='atz';
    	}
    	if (getOptionA('admin_btr_enabled')==2){
    		$payment_available[]='btr';
    	}    	    	
    	if (getOptionA('admin_rzr_enabled')==2){
    		$payment_available[]='rzr';
    	}
    	if (getOptionA('admin_vog_enabled')==2){
    		$payment_available[]='vog';
    	}    	    	    	    	    	    	    	    	    	
    	if (getOptionA('admin_paypal_v2_enabled')==1){
    		$payment_available[]='paypal_v2';
    	}
    	if (getOptionA('admin_paygate_enabled')==1){
    		$payment_available[]='paygate';
    	}
    	if (getOptionA('admin_mol_enabled')==2){
    		$payment_available[]='mollie';
    	}
    	if (getOptionA('mercadopago_v2_enabled')==1){
    		$payment_available[]='mercadopago';
    	}
    	    	
    	$new_payment_list=array();
		if (is_array($payment_list) && count($payment_list)>=1){
			foreach ($payment_list as $key=>$val) {
				if(in_array($key,(array)$payment_available)){
				   $new_payment_list[$key]=$val;
				}
			}
		}		
		return $new_payment_list;
    }
    
    public static function getMerchantPaymentList($merchant_id='')
    {
    	$payment_list=self::PaymentOptionList();

    	$merchant_type=self::getMerchantMembershipType($merchant_id);
    	
        $is_commission=false;
		//if ( Yii::app()->functions->isMerchantCommission($merchant_id)){
		if ($merchant_type==2){
			//commission
			$is_commission=true;
			$payment_available=Yii::app()->functions->getMerchantListOfPaymentGateway();	
			//dump($payment_available);		
		} else {
			//membership			
			if ( getOption($merchant_id,'merchant_disabled_cod')==""){
				$payment_available[]='cod';
			}
			if ( getOption($merchant_id,'merchant_disabled_ccr')==""){
				$payment_available[]='ocr';
			}
			if ( getOption($merchant_id,'enabled_paypal')=="yes"){
				$payment_available[]='pyp';
			}
			if ( getOption($merchant_id,'stripe_enabled')=="yes"){
				$payment_available[]='stp';
			}
			if ( getOption($merchant_id,'merchant_mercado_enabled')=="yes"){
				$payment_available[]='mcd';
			}
			if ( getOption($merchant_id,'merchant_sisow_enabled')=="yes"){
				$payment_available[]='ide';
			}
			if ( getOption($merchant_id,'merchant_payu_enabled')=="yes"){
				$payment_available[]='payu';
			}
			if ( getOption($merchant_id,'merchant_paysera_enabled')=="yes"){
				$payment_available[]='pys';
			}
			if ( getOption($merchant_id,'merchant_payondeliver_enabled')=="yes"){
				$payment_available[]='pyr';
			}
			if ( getOption($merchant_id,'merchant_enabled_barclay')=="yes"){
				$payment_available[]='bcy';
			}
			if ( getOption($merchant_id,'merchant_enabled_epaybg')=="yes"){
				$payment_available[]='epy';
			}
			if ( getOption($merchant_id,'merchant_enabled_autho')=="yes"){
				$payment_available[]='atz';
			}
			if ( getOption($merchant_id,'merchant_bankdeposit_enabled')=="yes"){
				$payment_available[]='obd';
			}		
			/*if ( getOption($merchant_id,'merchant_mol_enabled')=="2"){
				$payment_available[]='mol';
			}*/
			if (getOption($merchant_id,'merchant_btr_enabled')==2){
	    		$payment_available[]='btr';
	    	}
	    	if (getOption($merchant_id,'merchant_moneris_enabled')==2){
	    		$payment_available[]=Moneris::getPaymentCode();
	    	}
	    	if (getOption($merchant_id,'admin_stripe_ideal_enabled')==1){
	    		$payment_available[]='strip_ideal';
	    	}
	    	if (getOption($merchant_id,'admin_ipay_africa_enabled')==1){
	    		$payment_available[]='ipay_africa';
	    	}
	    	if (getOption($merchant_id,'admin_dixipay_enabled')==1){
	    		$payment_available[]='dixipay';
	    	}	    	
	    	if (getOption($merchant_id,'admin_wirecard_enabled')==1){
	    		$payment_available[]='wirecard';
	    	}
			
			$admin_available_payment=Yii::app()->functions->getMerchantListOfPaymentGateway();
			if (is_array($admin_available_payment) && count($admin_available_payment)>=1 ){
				foreach ($payment_available as $key=>$val) {
					if ( !in_array($val, (array) $admin_available_payment)){
						unset($payment_available[$key]);
					}
				}
			} else $payment_available='';
		}		
				
		$new_payment_list='';
		if (is_array($payment_list) && count($payment_list)>=1){
			foreach ($payment_list as $key=>$val) {
				if(in_array($key,(array)$payment_available)){
				   $new_payment_list[$key]=$val;
				}
			}
		}
		
		/*Check Admin individual settings for cod, offline cc, payon delivery*/
		if ( getOption($merchant_id,'merchant_switch_master_cod')==2){
			//cod
			if (array_key_exists('cod',(array)$new_payment_list)){
				unset($new_payment_list['cod']);
			}
		}
		if ( getOption($merchant_id,'merchant_switch_master_ccr')==2){
			//ocr
			if (array_key_exists('ocr',(array)$new_payment_list)){
				unset($new_payment_list['ocr']);
			}
		}
		if ( getOption($merchant_id,'merchant_switch_master_pyr')==2){
			//pyr
			if (array_key_exists('pyr',(array)$new_payment_list)){
				unset($new_payment_list['pyr']);
			}
		}
		
		
		/*check if has payment on delivery = pyr */
		if (array_key_exists('pyr',(array)$new_payment_list)){
			if ($is_commission){
				//$provider_list=Yii::app()->functions->getPaymentProviderListActive();         	
				$provider_list=Yii::app()->functions->getPaymentProviderMerchant($merchant_id);
				$merchant_payondeliver_enabled=getOption($merchant_id,'merchant_payondeliver_enabled');
				if($merchant_payondeliver_enabled==""){
					$provider_list='';
				}
			} else {
				$provider_list=Yii::app()->functions->getPaymentProviderMerchant($merchant_id);
			}			
			if (!is_array($provider_list) && count($provider_list)<=1){				
				unset($new_payment_list['pyr']);
			} 
		}
		
		//dump($new_payment_list);

		return $new_payment_list;
    }
    
    public static function displayCashAvailable($merchant_id='',$echo=true, $services='')
    {
    	//dump($services);
    	$payment_list=self::PaymentOptionList();        
    	$payment_available=array();
    	    	
        $is_commission=false;
		if ( Yii::app()->functions->isMerchantCommission($merchant_id)){			
			$is_commission=true;
			$payment_available=Yii::app()->functions->getMerchantListOfPaymentGateway();			
		} else {			
			$pay_available=Yii::app()->functions->getMerchantListOfPaymentGateway();			
			if ( getOption($merchant_id,'merchant_disabled_cod')==""){
				if (in_array('cod',(array)$pay_available)){
				   $payment_available[]='cod';
				}
			}				
		}
		
		$new_payment_list=array();
		if (is_array($payment_list) && count($payment_list)>=1){
			foreach ($payment_list as $key=>$val) {
				if(in_array($key,(array)$payment_available)){
				   $new_payment_list[$key]=$val;
				}
			}
		}
		/*Check Admin individual settings for cod, offline cc, payon delivery*/
		if ( getOption($merchant_id,'merchant_switch_master_cod')==2){
			//cod
			if (array_key_exists('cod',(array)$new_payment_list)){
				unset($new_payment_list['cod']);
			}
		}
		
		/*check if has payment on delivery = pyr */
		if (array_key_exists('pyr',(array)$new_payment_list)){
			if ($is_commission){
				$provider_list=Yii::app()->functions->getPaymentProviderListActive();         	
			} else {
				$provider_list=Yii::app()->functions->getPaymentProviderMerchant($merchant_id);
			}			
			if (!is_array($provider_list) && count( (array) $provider_list)<=1){				
				unset($new_payment_list['pyr']);
			} 			
		}
		
		if (array_key_exists('ocr',(array)$new_payment_list)){
			$cc_offline_master=getOption($merchant_id,'merchant_switch_master_ccr');
			if ($cc_offline_master==2){
				unset($new_payment_list['ocr']);
			}
		}
				
		$payment_accepted='';
		if (array_key_exists('cod',(array)$new_payment_list)){			
			$payment_accepted="<p class=\"cod-text\">".t("Cash on delivery available")."</p>";
			if($services==3){
				$payment_accepted="<p class=\"cod-text\">".t("Cash on pickup available")."</p>";
			} elseif ( $services==6 ){
				$payment_accepted="<p class=\"cod-text\">".t("Cash on pickup available")."</p>";
			} elseif ( $services==7 ){
				$payment_accepted="<p class=\"cod-text\">".t("Pay in person available")."</p>";	
			} else {	
				
			}
		}
		if (array_key_exists('ocr',(array)$new_payment_list)){
			if(!empty($payment_accepted)){
				$payment_accepted.='<div style="height:5px;"></div>';
			}
			$payment_accepted.="<p class=\"cod-text\">".t("Credit Card available")."</p>";
		}
		
		if(!empty($payment_accepted)){
			if ($echo){	
				echo $payment_accepted;
			} else return true;
		}
				
		return false;
    }
    
    public static function cookieLocation()
    {
    	$check_cookie=false;
    	if (!isset($_SESSION['client_location'])){
    		$check_cookie=true;
    	} else {
    		if (empty($_SESSION['client_location'])){
    			$check_cookie=true;
    		}
    	}
    	if ($check_cookie){    		
    		$temp_location=Cookie::getCookie('client_location');    	    
    	    if (!empty($temp_location)){    	    	
    	    	$temp_location=json_decode($temp_location,true);    	    	
    	    	$_SESSION['client_location']=$temp_location;
    	    }
    	}
    }
    
    public static function getMerchantPaymentMembership($merchant_id='',$package_id='')
    {
    	$DbExt=new DbExt;
    	$stmt="SELECT * FROM
    	{{package_trans}}
    	WHERE
    	merchant_id=".self::q($merchant_id)."
    	AND
    	package_id =".self::q($package_id)."
    	ORDER BY id DESC
    	LIMIT 0,1
    	";    	
    	if ($res=$DbExt->rst($stmt)){
    		return $res[0];
    	}
    	return false;
    }
    
    public static function getAvatar($client_id='')
    {
    	if ( $res= Yii::app()->functions->getClientInfo($client_id) ){
    		$file=$res['avatar'];
    	} else $file='avatar.jpg';
    	
    	if (empty($file)){
    		$file='avatar.jpg';
    	}
    	    	    
    	$path=Yii::getPathOfAlias('webroot')."/upload/$file";
    	
    	if ( file_exists($path) ){       		 		    	
    		return uploadURL()."/$file";
    	} else return assetsURL()."/images/avatar.jpg";    	
    }
    
    public static function prettyUrl($url='')
    {
    	if (preg_match("/http/i", $url)) {
    		return $url;
    	}
    	return "http://".$url;
    }
    
    public static function customPageUrl($data='')
    {
    	if (is_array($data) && count($data)>=1){
    		if ( $data['is_custom_link']==1){    			    			
    			return Yii::app()->createUrl('/page/'.$data['slug_name']);
    		} else {
    			return self::prettyUrl($data['content']);
    		}
    	}
    	return "#";
    }
    
    public static function openAsNewTab($data='')
    {
    	if (is_array($data) && count($data)>=1){
    		if ( $data['open_new_tab']==2){
    			echo " target=\"_blank\" ";
    		}
    	}
    	return false;
    }
    
    public static function getSessionAddress()
    {
    	$kr_search_adrress='';
    	if (isset($_SESSION['kr_search_address'])){	
			$kr_search_adrress=$_SESSION['kr_search_address'];		
		} else {
			$kr_search_adrress=Cookie::getCookie('kr_search_address');
			if (!empty($kr_search_adrress)){
				$_SESSION['kr_search_address']=$kr_search_adrress;
			}
		}
		
		return $kr_search_adrress;
    }
    
    public static function receiptRowTotal($label='',$value='',$class1='',$class2='')
    {
    	$html='';
    	$html.="<div class=\"row\">";
    	$html.="<div class=\"col-md-6 col-xs-6 text-right $class1\">".t($label)."</div>";
    	$html.="<div class=\"col-md-6 col-xs-6 text-right $class2\">$value</div>";
    	$html.="</div>";
    	return $html;
    }
    
    public static function getTopMenuActivated()
    {
		$theme_top_menu=getOptionA('theme_top_menu');
		if(empty($theme_top_menu)){
			$theme_top_menu=array(
			  'browse','resto_signup','contact','signup','driver_signup'
			);
		} else $theme_top_menu=json_decode($theme_top_menu,true);
		
		return $theme_top_menu;
    }
    
    public static function getLanguage()
    {
    	$lang[-999]=t("English");
    	if ($list=Yii::app()->functions->getLanguageList()){
    		foreach ($list as $val) {
    			$lang[$val['lang_id']]=$val['language_code'];
    		}
    	}
    	return $lang;
    }
    
    public static function receiptTableRow($label='',$value='')
    {
    	?>
    	<tr>
         <td><?php echo t($label)?></td>
         <td class="text-right"><?php echo $value?></td>
        </tr>
    	<?php
    }
    
    public static function login($user='',$pass='')
    {
    	$version = phpversion(); $login_type='email';
    	
    	if ($version>=5.2){
    		if (!filter_var($user, FILTER_VALIDATE_EMAIL)) {
    			$login_type='mobile';
    		}
    	} else {
    		if ( !self::validateEmail($user)){
    			$login_type='mobile';
    		}
    	}
    	    	
    	if ($login_type=="email"){
    		$stmt="SELECT * FROM
	    	{{client}}
	    	WHERE
	    	email_address=".Yii::app()->db->quoteValue($user)."
	    	AND
	    	password=".Yii::app()->db->quoteValue(md5($pass))."
	    	AND
	    	status IN ('active','pending')
	    	LIMIT 0,1
	    	";
    	} else {
    		$stmt="SELECT * FROM
	    	{{client}}
	    	WHERE
	    	contact_phone=".Yii::app()->db->quoteValue($user)."
	    	AND
	    	password=".Yii::app()->db->quoteValue(md5($pass))."
	    	AND
	    	status IN ('active','pending')
	    	LIMIT 0,1
	    	";
    	}
    	
    	//dump($stmt);
    	 
		$DbExt=new DbExt;
		if ( $res=$DbExt->rst($stmt)){		
			return $res[0];
		}
		return false;
    }
    
    public static function sendEmailVerificationCode($to='',$code='',$info='')
    {    
    	if(empty($to)){
    		return false;
    	}

    	$lang=Yii::app()->language;    	
    	$enabled  = getOptionA("customer_verification_code_email_email");
    	if ( $enabled==1){
    		$subject = getOptionA("customer_verification_code_email_tpl_subject_$lang");
    		if(!empty($subject)){
    			$subject=self::smarty('firstname',isset($info['first_name'])?$info['first_name']:'',$subject );
    			$subject=self::smarty('lastname',isset($info['last_name'])?$info['last_name']:'',$subject );
    		}
    		
    		$tpl = getOptionA("customer_verification_code_email_tpl_content_$lang");
    		if(!empty($tpl)){    			
    			$tpl=self::smarty('firstname',isset($info['first_name'])?$info['first_name']:'',$tpl );
    			$tpl=self::smarty('lastname',isset($info['last_name'])?$info['last_name']:'',$tpl );
    			$tpl=self::smarty('code',$code,$tpl );
    			$tpl=self::smarty('sitename',getOptionA('website_title'),$tpl);
    			$tpl=self::smarty('siteurl',websiteUrl(),$tpl);
    		}
    		if(!empty($subject) && !empty($tpl)){
    			sendEmail($to,'',$subject,$tpl);
    			return true;
    		}
    	}
    	
    	return false;
    }
    
    public static function getMapMarker()
    {
    	$map_marker=getOptionA('map_marker');
    	$upload_path=self::uploadPath();
    	if (!empty($map_marker)){
	    	if (file_exists($upload_path."/$map_marker")){
	    		return uploadURL()."/$map_marker";
	    	}
    	}
    	return assetsURL()."/images/map_pointer_small.png";
    }
    
    public static function reCheckDelivery($merchant_id='',$data='')
    {
    	    
    	if($merchant_info=FunctionsV3::getMerchantById($merchant_id)){
    		$distance_type=FunctionsV3::getMerchantDistanceType($merchant_id); 
    		    		
    		$complete_address=$data['street']." ".$data['city']." ".$data['state']." ".$data['zipcode'];
    		if(isset($data['country'])){
    			$complete_address.=" ".$data['country'];
    		}    
    		    		
    		$lat=0;
			$lng=0;			
    		/*if address book was used*/
    		if ( isset($data['address_book_id'])){
	    		if ($address_book=Yii::app()->functions->getAddressBookByID($data['address_book_id'])){
	        		$complete_address=$address_book['street'];	    	
	    	        $complete_address.=" ".$address_book['city'];
	    	        $complete_address.=" ".$address_book['state'];
	    	        $complete_address.=" ".$address_book['zipcode'];
	        	}	    		        
	    	}
	    		    		    		    	
	    	/*if map address was used*/
    		if (isset($data['map_address_toogle'])){    			
    			if ($data['map_address_toogle']==2){
    				$lat=$data['map_address_lat'];
    				$lng=$data['map_address_lng'];
    			} else {
    				if ($lat_res=Yii::app()->functions->geodecodeAddress($complete_address)){
			           $lat=$lat_res['lat'];
					   $lng=$lat_res['long'];
		    	    }
    			}
    		} else {    			
    			if ($lat_res=Yii::app()->functions->geodecodeAddress($complete_address)){
		           $lat=$lat_res['lat'];
				   $lng=$lat_res['long'];
	    	    }
    		}
    		    		
    		$distance=FunctionsV3::getDistanceBetweenPlot(
				$lat,
				$lng,
				$merchant_info['latitude'],$merchant_info['lontitude'],$distance_type
			);  
			//dump($distance);
			$distance_type_raw = $distance_type=="M"?"miles":"kilometers";
			$distance_type = $distance_type=="M"?t("miles"):t("kilometers");
			$merchant_delivery_distance=getOption($merchant_id,'merchant_delivery_miles'); 
			
			if(!empty(FunctionsV3::$distance_type_result)){
             	$distance_type_raw=FunctionsV3::$distance_type_result;
            }
			
			if (is_numeric($merchant_delivery_distance)){
				if ( $distance>$merchant_delivery_distance){
					 if($distance_type_raw=="ft" || $distance_type_raw=="meter" || $distance_type_raw=="mt"){
					 	return true;
					 }
		             return false;
                } else {
                	$delivery_fee=self::getMerchantDeliveryFee(
								              $merchant_id,
								              $merchant_info['delivery_charges'],
								              $distance,
								              $distance_type_raw);                    
                    $_SESSION['shipping_fee']=$delivery_fee;
                    return true;								              
                }
			}
    	}
    	return true;
    }
    
    public static function verifyMerchantSlug($slug_name='',$merchant_id='')
    {
    	$DbExt=new DbExt;
    	if ( is_numeric($merchant_id)){
    		$stmt="
    		SELECT count(*) as total FROM
    		{{merchant}}
    		WHERE
    		restaurant_slug=".self::q($slug_name)."
    		AND 
    		merchant_id <> ".self::q($merchant_id)."
    		";
    	} else {
    	    $stmt="
    		SELECT count(*) as total FROM
    		{{merchant}}
    		WHERE
    		restaurant_slug=".self::q($slug_name)."
    		";
    	}
    	//dump($stmt);
    	if ($res=$DbExt->rst($stmt)){
    		if ($res[0]['total']>0){
    		    $slug_name=Yii::app()->functions->seo_friendly_url($slug_name.$res[0]['total']);
    		}
    	} 
    	return $slug_name;
    }
    
    public static function getCuisine()
    {
    	/*testing php7*/
    	
    	$lists='';
        $DbExt=new DbExt;
		$stmt="SELECT *
		      FROM
		      {{cuisine}}
		      ORDER BY sequence ASC
		";
		$data=array();
		//$data='';
    	if ( $res=$DbExt->rst($stmt)){
			foreach ($res as $val) {								
				$stmt2="
				SELECT count(*) AS total
				FROM
				{{merchant}}
				WHERE
				cuisine LIKE ".FunctionsV3::q('%"'.$val['cuisine_id'].'"%')."
				AND status='active'
				AND is_ready ='2'
				";				
				$count=$DbExt->rst($stmt2);
				$val['total']=$count[0]['total'];
				$data[]=$val;
			}
			return $data;
		}
		return false;
    }
    
    public static function translateFoodItemByOrderId($order_id='',$lang_key='kr_lang_id')
    {    	
    	$translated_item=''; 
    	$trans=array();
    	
    	$DbExt=new DbExt;
    	$stmt="
    	SELECT a.*,
    	b.item_name_trans
    	FROM
    	{{order_details}} a
    	
    	left join {{item}} b
    	ON
        a.item_id=b.item_id
    	
    	WHERE
    	order_id=".self::q($order_id)."
    	ORDER BY id ASC
    	";
    	if ($res=$DbExt->rst($stmt)){
    		foreach ($res as $val) {    			
    			$trans['item_name_trans']=!empty($val['item_name_trans'])?json_decode($val['item_name_trans'],true):'';    			    			
    			$translated_item.=qTranslate(
    			  $val['item_name'],'item_name',(array)$trans,
    			  $lang_key
    			).",";
    		}
    		$translated_item=!empty($translated_item)?substr($translated_item,0,-1):$translated_item;
    		return $translated_item;
    	}
    	return '';
    }
    
    public static function cleanString($string='')
    {
    	$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
    
	public static function hasModuleAddon($modulename='')
	{
		if (Yii::app()->hasModule($modulename)){
		   $path_to_upload=Yii::getPathOfAlias('webroot')."/protected/modules/$modulename";	
		   if(file_exists($path_to_upload)){
		   	
		   	   /*check if tables exist for modules*/		   	   
		   	   $check_table='';
		   	   if($modulename=="driver"){
		   	   	  $check_table = 'driver_task';
		   	   	  
		   	   } elseif ($modulename=="pointsprogram"){
		   	   	  $check_table = 'points_earn'; 
		   	   	  
		   	   } elseif ($modulename=="printer"){
		   	   	  $check_table = 'printer_print'; 
		   	   	  
		   	   } elseif ($modulename=="mobileapp"){
		   	   	  $check_table = 'mobile_registered';  
		   	   	  
		   	   } elseif ($modulename=="singlemerchant"){
		   	   	  $check_table = 'singleapp_cart'; 
		   	   }
		   	   if(!empty($check_table)){
		   	   	  if(!self::checkIfTableExist($check_table)){
		   	   	  	 return false;
		   	   	  }		   	  
		   	   }	   
		   	   return true;
		   }
		}
		return false;
	}    
	
	public static function timeList()
	{
		$website_time_picker_format=getOptionA('website_time_picker_format');		
		$options[""]=""; $min30=array('00','30');
	    foreach (range(0,23) as $fullhour) {
	    	
	    	if ( $website_time_picker_format=="12"){
	            $parthour = $fullhour > 12 ? $fullhour - 12 : $fullhour;
	    	} else $parthour = $fullhour > 12 ? $fullhour - 0 : $fullhour;	       
	    	
	        foreach($min30 as $int){
	            if($fullhour > 11){
	                //$options[$fullhour.".".$int]=$parthour.":".$int." PM";
	                if ( $website_time_picker_format=="12"){
	                   $options[$parthour.":".$int." PM"]=$parthour.":".$int." PM";
	                } else $options[$parthour.":".$int.""]=$parthour.":".$int."";
	            }else{
	                if($fullhour == 0){$parthour='12';}
	                //$options[$fullhour.".".$int]=$parthour.":".$int." AM" ;
	                if ( $website_time_picker_format=="12"){
	                   $options[$parthour.":".$int." AM"]=$parthour.":".$int." AM" ;
	                } else $options[$parthour.":".$int.""]=$parthour.":".$int."" ;
	            }	            	            
	        }
	    }
	    return $options;
	}
	
	public static function getMerchantCCdetails($id='')
	{
		$DbExt=new DbExt;
    	$stmt="
    	SELECT * FROM
    	{{merchant_cc}}
    	WHERE
    	mt_id=".self::q($id)."
    	";
    	if ($res=$DbExt->rst($stmt)){
    		return $res[0];
    	}
    	return false;
	}
	
	public static function getMerchantByMtidCC($id='', $merchant_id='')
	{
		$DbExt=new DbExt;
    	$stmt="
    	SELECT * FROM
    	{{merchant_cc}}
    	WHERE
    	mt_id=".self::q($id)."
    	AND merchant_id=".self::q($merchant_id)."
    	";
    	if ($res=$DbExt->rst($stmt)){
    		return $res[0];
    	}
    	return false;
	}	
	
	public static function addCsrfToken($refresh=true)
	{
		/*$refresh=false;
		$protected_path = Yii::getPathOfAlias('webroot')."/protected/runtime";
		if(!file_exists($protected_path)){
			mkdir($protected_path,0777);
		}
		
		$request = Yii::app()->getRequest();
		if($refresh){
           $request->getCookies()->remove($request->csrfTokenName);
		}
        echo CHtml::hiddenField($request->csrfTokenName, $request->getCsrfToken());*/
	}
	
	public static function saveFbAvatarPicture($id='')
	{					
		if( @ini_get('allow_url_fopen') ) {   			
			$fbid=$id;
			$fb_avatar_filename="avatar_".$id.".jpg";
			$context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));			
			$image = json_decode(file_get_contents("https://graph.facebook.com/$fbid/picture?type=large&redirect=false",false,$context),true);	    		
			if(isset($image['data']['url'])){
				$image = file_get_contents($image['data']['url']);
				@file_put_contents( FunctionsV3::uploadPath()."/$fb_avatar_filename",$image);
				return $fb_avatar_filename;
			} 
		} 
		return false;
	}
	
	public static function latToAdress($lat='' , $lng='')
	{

		$map = FunctionsV3::getMapProvider();
		
		if($map['provider']=="mapbox"){
			Yii::app()->setImport(array(			
			   'application.vendor.mapbox.*',
			));	
			require_once('mapbox/Mapbox.php');
			$mapbox = new Mapbox($map['token']);			
			$res = $mapbox->reverseGeocode($lng, $lat);
			$success = $res->success();
			$count = $res->getCount();
			
			if($success && $count>0){
			   	 $relevance=array();
			   	 $data = array();
			   	 
			   	 foreach ($res as $key => $val) {			   	 				   	 
			   	 	$data[$key]=$val;
			   	 	$relevance[$key]=$val['relevance'];
			   	 }			   	 
			   	 $value = max($relevance);			   	 
			   	 $key = array_search($value, $relevance);
			   	 			   	 			   	 			   	
			   	 if($key>=0){
			   	 	if(isset($data[$key]['place_name'])){		
			   	 		
			   	 		$temp_data = $data[$key];
			   	 		
			   	 		$out_address = ''; $out_city='';
			   	 		$out_state=''; $out_country=''; $out_country_code='';
			   	 		$out_zip='';
			   	 		
			   	 		if(isset($temp_data['properties'])){
			   	 		   if(isset($temp_data['properties']['address'])){
			   	 		   	   $out_address=$temp_data['properties']['address'];
			   	 		   }
			   	 		}
			   	 		
			   	 		if(isset($temp_data['context'])){
			   	 			foreach ($temp_data['context'] as $val_context) {			   	 				
			   	 				if (preg_match("/locality./i", $val_context['id'])) {
			   	 					if(empty($out_address)){
			   	 					   $out_address = $val_context['text'];
			   	 					}
			   	 				}
			   	 				if (preg_match("/district./i", $val_context['id'])) {
			   	 					if(empty($out_address)){
			   	 					   $out_address = $val_context['text'];
			   	 					}
			   	 				}
			   	 				if (preg_match("/locality./i", $val_context['id'])) {
			   	 					if(empty($out_address)){
			   	 					   $out_address = $val_context['text'];
			   	 					}
			   	 				}
			   	 				if (preg_match("/place./i", $val_context['id'])) {
			   	 					$out_city = $val_context['text'];
			   	 				}
			   	 				if (preg_match("/postcode./i", $val_context['id'])) {
			   	 					$out_zip = $val_context['text'];
			   	 				}
			   	 				if (preg_match("/region./i", $val_context['id'])) {
			   	 					$out_state = $val_context['text'];
			   	 				}
			   	 				if (preg_match("/country./i", $val_context['id'])) {
			   	 					$out_country = $val_context['text'];
			   	 					$out_country_code = isset($val_context['short_code'])?$val_context['short_code']:'';
			   	 				}
			   	 			}
			   	 		}			   	 	
			   	 			   	 		
			   	 		
			   	 		$out['address']=$out_address;
			   	 		$out['city']=$out_city;
			   	 		$out['state']=$out_state;
			   	 		$out['zip']=$out_zip;
			   	 		$out['country']=$out_country;
			   	 		$out['country_code']=$out_country_code;
			   	 		$out['formatted_address']=$temp_data['place_name'];
			   	 		
			   	 		return $out;
			   	 	}
			   	 }			   			   	 
			}		
			return false;
		}
		/*END MAPBOX*/
		
		$lat_lng="$lat,$lng";
		$protocol = isset($_SERVER["https"]) ? 'https' : 'http';
		if ($protocol=="http"){
			$api="http://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($lat_lng);
		} else $api="https://maps.googleapis.com/maps/api/geocode/json?latlng=".urlencode($lat_lng);
		
		/*check if has provide api key*/
		$key=Yii::app()->functions->getOptionAdmin('google_geo_api_key');		
		if ( !empty($key)){
			$api="https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($lat_lng)."&key=".urlencode($key);
		}	
						
		if (!$json=@file_get_contents($api)){
			$json=Yii::app()->functions->Curl($api,'');
		}
		
		if (isset($_GET['debug'])){
			dump($api);		
			dump($json);    
		}
		
		$address_out=array();
			
		if (!empty($json)){			
			$results = json_decode($json,true);		
			//dump($results);		
			$parts = array(
			  'address'=>array('street_number','route'),			  
			  //'city'=>array('locality'),
			  'city'=>array('locality','political','sublocality'),
			  'state'=>array('administrative_area_level_1'),
			  'zip'=>array('postal_code'),
			  'country'=>array('country'),
			  'country_code'=>array('country'),
			);		    
			if (!empty($results['results'][0]['address_components'])) {
			  $ac = $results['results'][0]['address_components'];
			  foreach($parts as $need=>$types) {
				foreach($ac as &$a) {		          					  
					  if (in_array($a['types'][0],$types)){
						  if (in_array($a['types'][0],$types)){
							  if($need=="address"){
								  if(isset($address_out[$need])) {
									 $address_out[$need] .= " ".$a['long_name'];
								  } else $address_out[$need]= $a['long_name'];
							  } elseif ($need=="country_code"){					  	
							  	  $address_out[$need] = $a['short_name'];
							  } else $address_out[$need] = $a['long_name'];			          	  	  
						  }
					  } elseif (empty($address_out[$need])) $address_out[$need] = '';	
				}
			  }
			  
			  if(!empty($results['results'][0]['formatted_address'])){
				 $address_out['formatted_address']=$results['results'][0]['formatted_address'];
			  }
			  
			  return $address_out;
			} 				
		}			
		return false;
	}
	
	public static function getMollieApiKey($admin=true , $merchant_id='')
	{
		$apikey=false;
		if($admin){
			$admin_mol_mode=getOptionA('admin_mol_mode');
			if ($admin_mol_mode=="sandbox"){
				$apikey=getOptionA('admin_mollie_apikey_sanbox');
			} else $apikey=getOptionA('admin_mollie_apikey_live');
		} else {
			$admin_mol_mode=Yii::app()->functions->getOption('merchant_mol_mode',$merchant_id);
			if ($admin_mol_mode=="sandbox"){
				$apikey=Yii::app()->functions->getOption('merchant_mollie_apikey_sanbox',$merchant_id);
			} else $apikey=Yii::app()->functions->getOption('merchant_mollie_apikey_live',$merchant_id);
		}
		return $apikey;
	}
	
	public static function dateNow()
	{
		return date('Y-m-d G:i:s');
	}
	
	public static function getPaymentOrderByOrderID($order_id='')
	{
		$DbExt=new DbExt;
    	$stmt="
    	SELECT * FROM
    	{{payment_order}}
    	WHERE
    	order_id=".self::q($order_id)."
    	ORDER BY id DESC 
    	LIMIT 0,1
    	";
    	if ($res=$DbExt->rst($stmt)){
    		return $res[0];
    	}
    	return false;
	}
	
    public static function getPackageTransByToken($token='')
	{
		$DbExt=new DbExt;
    	$stmt="
    	SELECT * FROM
    	{{package_trans}}
    	WHERE
    	TOKEN=".self::q($token)."
    	ORDER BY id DESC 
    	LIMIT 0,1
    	";
    	if ($res=$DbExt->rst($stmt)){
    		return $res[0];
    	}
    	return false;
	}	
	
    public static function getSMSTrans($package_id='',$mtid='')
	{
		$DbExt=new DbExt;
    	$stmt="
    	SELECT * FROM
    	{{sms_package_trans}}
    	WHERE
    	sms_package_id=".self::q($package_id)."    	
    	AND
    	merchant_id =".self::q($mtid)."
    	ORDER BY id DESC
    	LIMIT 0,1
    	";
    	if ($res=$DbExt->rst($stmt)){
    		return $res[0];
    	}
    	return false;
	}		
	
	public static function prettyPaymentType($transaction_type='', $payment_code='',$data='',$trn_type='')
	{		
		$payment_prefix=''; $db=new DbExt;
		
		switch ($trn_type) {
			case "dinein":
				if($payment_code=="cod"){
					$payment_code="pay_in_person";
				}
				break;
		
			case "pickup":
				if($payment_code=="cod"){
					$payment_code="cash_pickup";
				}
				break;
				 	
			default:
				break;
		}
		
		if ($payment_code=="mol"){
			/*dump($payment_code);
		    dump($transaction_type);
		    dump($data);*/
		    switch ($transaction_type) {
		    	case "payment_order":	
		    	    $stmt="SELECT raw_response FROM
		    	    {{{$transaction_type}}}
		    	    WHERE
		    	    order_id=".self::q($data)."
		    	    LIMIT 0,1
		    	    ";	    		    	    
		    	    if($res=$db->rst($stmt)){
		    	    	$res=$res[0];	
		    	    	$details=json_decode($res['raw_response'],true);
		    	    	if(is_array($details) && count($details)>=1){
		    	    		$payment_prefix="-".strtoupper(t($details['method']));
		    	    	}		    	    	
		    	    } 
		    		break;
		    
		    	case "package_trans":	
		    	    $stmt="
		    	    select id,payment_type,PAYPALFULLRESPONSE
			    	from
			    	{{package_trans}}
			    	where
			    	id=".self::q($data)."
			    	ORDER BY id DESC
			    	LIMIT 0,1
		    	    ";		    	    
		    	    if($res=$db->rst($stmt)){
		    	    	$res=$res[0];	
		    	    	$details=json_decode($res['PAYPALFULLRESPONSE'],true);
		    	    	if(is_array($details) && count($details)>=1){
		    	    		$payment_prefix="-".strtoupper(t($details['method']));
		    	    	}		    	    	
		    	    }
		    	    break;
		    	    
		    	case "sms_package_trans":    
		    	    $stmt="
		    	    select id,payment_gateway_response
			    	from
			    	{{sms_package_trans}}
			    	where
			    	id=".self::q($data)."
			    	ORDER BY id DESC
			    	LIMIT 0,1
		    	    ";		    	    
		    	    if($res=$db->rst($stmt)){
		    	    	$res=$res[0];	
		    	    	$details=json_decode($res['payment_gateway_response'],true);
		    	    	if(is_array($details) && count($details)>=1){
		    	    		$payment_prefix="-".strtoupper(t($details['method']));
		    	    	}		    	    	
		    	    }
		    	   break;
		    	
		    	default:
		    		break;
		    }
		    unset($db);
		    return strtoupper(t($payment_code)).$payment_prefix;
		    
		} else return strtoupper(t($payment_code));
	}
	
	public static function getIpay88Key($admin=true , $merchant_id='')
	{
		$credentials='';
		if($admin){
			$credentials['code']=getOptionA('admin_ip8_merchantcode');
			$credentials['key']=getOptionA('admin_ip8_merchantkey');
		} else {
			$credentials['code']=Yii::app()->functions->getOption('merchant_ip8_merchantcode',$merchant_id);
			$credentials['key']=Yii::app()->functions->getOption('merchant_ip8_merchantkey',$merchant_id);
		}
		return $credentials;
	}	
	
    public static function getSizeListBySizeName($merchant_id='', $size_name='')
    {    	
    	$data_feed[]='';
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{size}}
			WHERE
			merchant_id=".self::q($merchant_id)."
			AND
			size_name=".self::q($size_name)."
			ORDER BY sequence ASC			
		";			    
		if ( $res=$DbExt->rst($stmt)){						
			$res=$res[0];
			return $res;
		}
		return false;
    }			
    
    public static function getIpay88Credentials($merchant_id='')
    {
    	$cred='';
    	//if ( Yii::app()->functions->isMerchantCommission($merchant_id)){
    	if (FunctionsV3::isMerchantPaymentToUseAdmin($merchant_id)){
    		$cred['merchant_code']=getOptionA('admin_ip8_web_merchantcode');
    		$cred['merchant_key']=getOptionA('admin_ip8_web_merchantkey');
    		$cred['mode']=getOptionA('admin_ip8_mode');
    		$cred['language']=getOptionA('admin_ip8_language');
    	} else {
    		$cred['merchant_code']=getOption($merchant_id,'merchant_ip8_web_merchantcode');
    		$cred['merchant_key']=getOption($merchant_id,'merchant_ip8_web_merchantkey');
    		$cred['mode']=getOption($merchant_id,'merchant_ip8_mode');
    		$cred['language']=getOption($merchant_id,'merchant_ip8_language');
    	}
    	if(!empty($cred['merchant_code']) && !empty($cred['merchant_key']) ){
    		return $cred;
    	} else return false;    	
    }       
    
    public static function getNewOrders($type='')
    {
    	$db_ext=new DbExt;
    	
    	switch ($type) {
    		case "admin":
    			$and=" AND admin_viewed<=0 ";
    			break;
    	
    		case "merchantapp":	
    		   $and=" AND merchantapp_viewed<=0 ";
    		   break;
    		   
    		default:
    			$and=" AND viewed='1' ";
    			break;
    	}
    	
    	$stmt="
    	      SELECT * FROM
    	      {{order}}
    	      WHERE    	          	      
    	      date_created like '".date('Y-m-d')."%'    	      
    	      $and
    	      AND status NOT IN ('".initialStatus()."')
    	      ORDER BY date_created DESC
    	      LIMIT 0,50
    	";    	       	
    	if ($res=$db_ext->rst($stmt)){    
    		unset($db_ext);
    		return $res;
    	}
    	unset($db_ext);
    	return false;
    	
    }
    
    public static function getMinOrderTable($mtid='' )
    {
    	$db=new DbExt;
    	$stmt="SELECT * FROM
    	{{minimum_table}}
    	WHERE
    	merchant_id=".self::q($mtid)."    	
    	ORDER BY distance_to ASC
    	";	    	
    	if ( $res=$db->rst($stmt)){
    		return $res;
    	}
    	return false;
    }
    
    public static function getMinOrderByTableRates($mtid='',$distance='',$unit='', $min_fee=0)
    {    
    	$is_enabled=getOption($mtid,'min_tables_enabled');    	
    	if ( $is_enabled!=1){
    		return $min_fee;
    	}
    	
    	$db=new DbExt;
    	switch (strtolower($unit)){
    		case "miles":    
    		case "mi":	
    			$unit='mi';
    			break;
    		case "kilometers":		
    		case "km":		
    		    $unit='km';
    			break;
    		case "ft":	
    		    $unit='mi';
    		    $distance=1;
    			break;
    	}    	
    	    	
    	$min_fees=$min_fee;
    	
    	$stmt="SELECT * FROM
    	{{minimum_table}}
    	WHERE
    	merchant_id=".self::q($mtid)."
    	AND
    	shipping_units=".self::q($unit)."
    	ORDER BY distance_to ASC
    	";	          	
    	if ( $res=$db->rst($stmt)){    		
    		$found=false; $last_records='';
    		foreach ($res as $val) {    			
    			if ( $val['distance_from']<=$distance && $val['distance_to']>=$distance){
    				$min_fees=$val['min_order']; $found=true;    				
    			} else $last_records=$val;
    		}
    		if (!$found){     			
    			if ($distance>$last_records['distance_to']){
    				$min_fees =  $last_records['min_order'];
    			}
    		}
    	}     	
    	return $min_fees;    	
    }
    
    public static function getItemFirstPriceWithtax($prices='',$discount='', $apply_tax='', $tax='')
	{				
		$size='';
		if (is_array($prices) && count($prices)>=1){
			$regular_price=$prices[0]['price'];			
			if(isset($prices[0]['size'])){
				if(!empty($prices[0]['size'])){
					$size=$prices[0]['size'];
				}
			}
						
			if ($apply_tax==1 && $tax>0){				
			    $regular_price=$regular_price + ($regular_price*$tax);				
			} 
			
			if ($discount>0){
				$regular_price=$regular_price-$discount;
			}
			
			if(!empty($size)){
				return $size." ".self::prettyPrice($regular_price);
			} else return self::prettyPrice($regular_price);			
		}
		return '-';
	}    
	
	public static function getMerchantTax($mtid='')
	{
		$merchant_tax=getOption($mtid,'merchant_tax');
		if($merchant_tax>0){
		    $merchant_tax=$merchant_tax/100;
		    return $merchant_tax;
		}
		return false;
	}

	public static function setPrintSize($font_size='', $width='')
	{
		
		$default_margin=0; $font_size_orig=$font_size;
		$page_left='';
		
		if (!empty($font_size)){
			$font_size.="px";
		}
									
		if (stripos( $_SERVER['HTTP_USER_AGENT'], "chrome")>0){
		    $font_size=$font_size_orig-4;
		    $font_size.="pt";
		    
		    $default_margin="0.5cm";
		    $page_left='
		      @page :right {
				margin: 0.8cm;
			  }
		    ';
	    }
	    	
		Yii::app()->clientScript->registerCss('printer_header_css', '
			@page {
				size: auto;
				margin:'.$default_margin.';
			}
			'.$page_left.'
			html, body{
				background:#fff !important;
				font-family:Arial !important;
			}
			.view-receipt-pop,
			.receipt-wrap.order-list-wrap
			{
			  padding:0;
			}
			.view-receipt-pop h4{
				margin:0;
				padding-bottom: 5px;
				padding-top: 5px;
			}
			.order-list-wrap{
			   border:0;
			}
		');
		
		if (!empty($width)){
			Yii::app()->clientScript->registerCss('printer_css', '
			     .view-receipt-pop,
			     #thermal-receipt.view-receipt-pop,
			     .view-receipt-pop.currentController_merchant,
			     .view-receipt-pop.currentController_admin
			     {			   
			       width:'.$width.'px;   
			     }			
			     .view-receipt-pop .item-row .b{
			        width: calc(100% - 117px);			        
			     }     			     
			');
		}
		if (!empty($font_size)){
			Yii::app()->clientScript->registerCss('printer_css1', '
			     #thermal-receipt.view-receipt-pop,
			     .view-receipt-pop,
			     .view-receipt-pop h4,
			     .view-receipt-pop .label,
			     .view-receipt-pop .value,
			     .view-receipt-pop .a,
			     .view-receipt-pop .b,
			     .currentController_merchant .row .col-md-6, 
			     .currentController_admin .row .col-md-6,
			     .receipt-main-wrap
			     {
			      font-size:'.$font_size.';
			      color:#000;
			      font-family:Arial !important;
			     }			     
			     .view-receipt-pop h4{
			       font-weight:bold;
			     }			
			     .view-receipt-pop .item-row .b{
			        width: calc(100% - 117px);			        
			     }     
			');
		}	
	}
	
	public static function prettyDate($date='')
	{
		if (!empty($date)){
			$date_format=getOptionA('website_date_format');
			if (empty($date_format)){
				$date_format="M d,Y";
			}
			$date = date($date_format,strtotime($date));
			return Yii::app()->functions->translateDate($date);
		}
		return false;
	}
	
	public static function prettyTime($time='')
	{
		if (!empty($time)){
			$format=getOptionA('website_time_format');			
			if(empty($format)){
				$format="g:i:s a";
			}
			return date($format,strtotime($time));
		}
		return false;
	}
	
	public static function isMerchantCommission($mtid='')
	{
		if ( Yii::app()->functions->isMerchantCommission($mtid)){
			return true;
		}
		return false;
	}
	
	public static function isDecimal($price){	  
	   if ( strpos( $price, "." ) !== false ) {
            return true;
       }
       return false;
    }
    
    public static function getMerchantInfo($merchant_id='')
	{		
		$stmt="SELECT a.*,
		concat(street,' ',city,' ',state,' ',post_code,' ',country_code) as complete_address
		 FROM
		{{merchant}} a
		WHERE
		merchant_id=".self::q($merchant_id)."
		LIMIT 0,1
		";
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){		 	
			return $res;
		}
		return false;
	}	
	
	public static function razorPaymentCredentials($merchant_id='')
	{
		
		$credentials=array();
		
		//if ( Yii::app()->functions->isMerchantCommission($merchant_id)){			
		if (FunctionsV3::isMerchantPaymentToUseAdmin($merchant_id)){
			$enabled=getOptionA('admin_rzr_enabled');
			$mode=strtolower(getOptionA('admin_rzr_mode'));
			if ( $mode=="sandbox"){
				$credentials['key_id']=getOptionA('admin_razor_key_id_sanbox');
				$credentials['key_secret']=getOptionA('admin_razor_secret_key_sanbox');
			} else {
				$credentials['key_id']=getOptionA('admin_razor_key_id_live');
				$credentials['key_secret']=getOptionA('admin_razor_secret_key_live');
			}			
		} else {
			$enabled=getOption($merchant_id,'merchant_rzr_enabled');
			$mode=strtolower(getOption($merchant_id,'merchant_rzr_mode'));
			if ( $mode=="sandbox"){
				$credentials['key_id']=getOption($merchant_id,'merchant_razor_key_id_sanbox');
				$credentials['key_secret']=getOption($merchant_id,'merchant_razor_secret_key_sanbox');
			} else {
				$credentials['key_id']=getOption($merchant_id,'merchant_razor_key_id_live');
				$credentials['key_secret']=getOption($merchant_id,'merchant_razor_secret_key_live');
			}			
		}
		
		if ( $enabled==2){
			return $credentials;		
		} else return false;		
	}
	
	public static  function generateCode($length = 8) {
	   $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz';
	   $ret = '';
	   for($i = 0; $i < $length; ++$i) {
	     $random = str_shuffle($chars);
	     $ret .= $random[0];
	   }
	   return $ret;
    }
    
	public static function generateOrderToken()
	{
		$token=self::generateCode(10);
		$db=new DbExt;
		$stmt="
		SELECT order_id_token
		FROM {{order}}
		WHERE
		order_id_token=".self::q($token)."
		LIMIT 0,1
		";
		if ( $res=$db->rst($stmt)){
			$token=self::generateOrderToken();
		}
		return $token;
	}
	
	public static function getEmailogsByID($id='')
	{
		$DbExt=new DbExt;
		$stmt="SELECT * FROM
		{{email_logs}}
		WHERE
		id=".self::q($id)."
		LIMIT 0,1
		";
		if ( $res=$DbExt->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
	public static function getHostURL()
	{
		return "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'];
	}
	
	public static function getLanguageList($aslist=true)
	{
		if($aslist){
			$list[0]=t("Please select");
		} else $list=array();
		
    	$path=Yii::getPathOfAlias('webroot')."/protected/messages";    	
    	$res=scandir($path);
    	if(is_array($res) && count($res)>=1){
    		foreach ($res as $val) {       			
    			if($val=="."){    				
    			} elseif ($val==".."){  
    			} elseif ($val=="default"){  
    			} elseif ( strpos($val,".") ){  
    			//} elseif ($val=="en"){  
    			} else {
    				$list[$val]=$val;
    			}
    		}       		
    		return $list;
    	}
    	return false;		
	}
	
	public static function getEnabledLanguage()
	{
		$lang=getOptionA("set_lang_id");
		//dump($lang);
		if(!empty($lang)){
			$lang=json_decode($lang,true);
		}
		return $lang;
	}
		
	public static function handleLanguage()
	{
		$app = Yii::app();
     	$user = $app->user;
     	
		if (isset($_GET['lang'])){
			
			if(!empty($_GET['lang'])){
	     	 	$app->language = $_GET['lang'];
	     	 	$app->user->setState('lang', $_GET['lang']);
	     	    $cookie = new CHttpCookie('_lang', $_GET['lang']);
	            $cookie->expire = time() + (60*60*24*365); // (1 year)
	            Yii::app()->request->cookies['lang'] = $cookie;   
			}

        } elseif ( isset($_POST['lang']) ){     	 	

        	if(!empty($_POST['lang'])){
	        	$app->language = $_POST['lang'];
	     	 	$app->user->setState('lang', $_POST['lang']);
	     	    $cookie = new CHttpCookie('_lang', $_POST['lang']);
	            $cookie->expire = time() + (60*60*24*365); // (1 year)
	            Yii::app()->request->cookies['lang'] = $cookie;           
        	}
        	
     	} elseif ( $app->user->hasState('lang') ){     	 	
     	 	$app->language = $app->user->getState('lang');
     	} elseif ( isset(Yii::app()->request->cookies['lang']) ){     	 	
     	 	$app->language = Yii::app()->request->cookies['lang']->value;
     	}
	}		

	public static function smarty($search='',$value='',$subject='')
    {	
	   $n=str_replace("[".$search."]",$value,$subject);
	   $n=str_replace("{".$search."}",$value,$n);
	   return $n;
    } 		
    
    public static function sendCustomerWelcomeEmail($data='')
    {
    	$to = isset($data['email_address'])?$data['email_address']:'';
    	$lang=Yii::app()->language;    	
    	$enabled=getOptionA("customer_welcome_email_email");    	
    	if (!empty($to)){
	    	if ($enabled==1){
	    		$subject = getOptionA("customer_welcome_email_tpl_subject_$lang");
	    		
	    		$sitename = getOptionA('website_title');
	    		$site_url = websiteUrl();
	    		
	    		if(!empty($subject)){
	    			$subject=self::smarty('firstname',isset($data['first_name'])?$data['first_name']:'',$subject );
	    			$subject=self::smarty('lastname',isset($data['lastname'])?$data['lastname']:'',$subject );
	    			$subject=self::smarty('sitename',$sitename,$subject );
	    			$subject=self::smarty('siteurl',$site_url,$subject );
	    		}
	    		
	    		$tpl=getOptionA('customer_welcome_email_tpl_content_'.$lang);
	    		if (!empty($tpl)){	    			
	    			$tpl=self::smarty('firstname',isset($data['first_name'])?$data['first_name']:'',$tpl );
	    			$tpl=self::smarty('lastname',isset($data['last_name'])?$data['last_name']:'',$tpl );
	    			$tpl=self::smarty('sitename',$sitename,$tpl);
		    		$tpl=self::smarty('siteurl',$site_url,$tpl);
	    		}	    	
	    		if (!empty($subject) && !empty($tpl)){
	    			sendEmail($to,'',$subject,$tpl);
	    		}
	    	}
    	}
    }
    
    public static function sendCustomerSMSVerification($phone='', $code='')
    {    	
    	$lang=Yii::app()->language;   
    	$enabled = getOptionA("customer_verification_code_sms_sms");    
    	if ($enabled==1){
    		$tpl = getOptionA("customer_verification_code_sms_sms_content_$lang");
    		if(!empty($tpl)){
    			$tpl=self::smarty('code',$code,$tpl);
    			$tpl=self::smarty('sitename',getOptionA('website_title'),$tpl);
		        $tpl=self::smarty('siteurl',websiteUrl(),$tpl);
    		}    		
    		$res=Yii::app()->functions->sendSMS($phone,$tpl);    		
    	}
    }
    
    public static function sendMerchantActivation($data='',$code='')
    {
    	$disabled_verification = getOptionA("merchant_email_verification");
    	if($disabled_verification=="yes"){
    		return false;
    	} 
    	    	
    	$to=isset($data['contact_email'])?$data['contact_email']:'';
    	$lang=Yii::app()->language;       	
    	$enabled=getOptionA("merchant_verification_code_email");
    	if ($enabled){
	    	$subject = getOptionA("merchant_verification_code_tpl_subject_$lang");
	    	$tpl=getOptionA("merchant_verification_code_tpl_content_$lang");
	    	if(!empty($tpl)){
	    		$tpl=self::smarty('code',$code,$tpl);
	    		
	    		$tpl=self::smarty('restaurant_name',
	    		isset($data['restaurant_name'])?$data['restaurant_name']:"",$tpl);
	    		
	    		$tpl=self::smarty('login_url',
	    		self::getHostURL().Yii::app()->createUrl('/merchant')
	    		,$tpl);
	    		
	    		$tpl=self::smarty('sitename',getOptionA('website_title'),$tpl);
                $tpl=self::smarty('siteurl',websiteUrl(),$tpl); 
	    	}
	    	if (!empty($tpl) && !empty($subject)){
	    		sendEmail($to,'',$subject, $tpl );
	    	}
    	}
    }
    
    public static function sendWelcomeEmailMerchant($data='',$force_send=false)
    {
    	$lang=Yii::app()->language; $sender=getOptionA('global_admin_sender_email');
    	$enabled=getOptionA("merchant_welcome_signup_email");
    	
    	$disabled_verification = getOptionA("merchant_email_verification");
    	if($disabled_verification=="yes"){
    	} else {
    		if($force_send==false){
    		   return ;
    		}
    	}
    	
    	if($enabled==1){
    		  $tpl=getOptionA("merchant_welcome_signup_tpl_content_$lang");
    		  $subject=getOptionA("merchant_welcome_signup_tpl_subject_$lang");
    		
    		  $data['login_url'] = self::getHostURL().Yii::app()->createUrl('/merchant');
    		  $email=isset($data['contact_email'])?$data['contact_email']:'';
    		
    		  $pattern=array(		    	   	   	   
	    	   'restaurant_name'=>'restaurant_name',
	    	   'login_url'=>'login_url',
	    	   'sitename'=>getOptionA('website_title'),
	    	   'siteurl'=>websiteUrl(),	 		    	   
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
			  self::runCronEmail();
    	}    	
    }
    
    public static function sendBankInstructions($merchant='',$data='')
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
    	
    	$to=$merchant_email; 
    	$lang=Yii::app()->language;   
    	//$enabled = getOptionA("offline_bank_deposit_email");
    	$enabled = getOptionA("offline_bank_deposit_signup_merchant_email");
    	
    	if ($enabled && !empty($to)){
	    	/*$subject = getOptionA("offline_bank_deposit_tpl_subject_$lang");
	    	$tpl = getOptionA("offline_bank_deposit_tpl_content_$lang");	*/
	    		    	
	    	$subject = getOptionA("offline_bank_deposit_signup_merchant_tpl_subject_$lang");
	    	$tpl = getOptionA("offline_bank_deposit_signup_merchant_tpl_content_$lang");	    	
	    	
	    	if(!empty($subject)){
	    		$subject = self::smarty("restaurant_name", isset($merchant['restaurant_name'])?$merchant['restaurant_name']:''
	    		, $subject);
	    	}
	    	    	
	    	if(!empty($tpl)){
	    		$verify_link=self::getHostURL().Yii::app()->createUrl('store/bankdepositverify',array(
	    		  'ref'=>$merchant['activation_token']
	    		));
	    		$tpl = self::smarty("restaurant_name", isset($merchant['restaurant_name'])?$merchant['restaurant_name']:''
	    		, $tpl);
	    		$tpl = self::smarty("amount", self::prettyPrice($package_price) , $tpl);
	    		$tpl = self::smarty("verify_payment_link", $verify_link , $tpl);
	    		$tpl = self::smarty('sitename',getOptionA('website_title'),$tpl);
                $tpl = self::smarty('siteurl',websiteUrl(),$tpl);
	    	}
	    	if(!empty($tpl) && !empty($subject)){
	    		
	    		 sendEmail($to,'',$subject, $tpl );
	    		
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
        
    public static function orderStatusTPL($type=1)
    {
    	$data=array();
    	$DbExt=new DbExt();
    	$stmt="
    	SELECT * FROM
    	{{order_status}}
    	ORDER BY description ASC    	
    	";
    	if ( $res=$DbExt->rst($stmt)){
    		foreach ($res as $val) {    
    			if ($type==1){			
    				$description=str_replace(" ","_",$val['description']);
	    			//$data["order_status_".$val['description']]=array(
	    			$data["order_status_".$description]=array(
	    			   'email'=>true,
			           'sms'=>true,		  
			           'push'=>true,      
			           'email_tag'=>'order_id,order_status,restaurant_name,customer_name,remarks,sitename,siteurl', 
			           'sms_tag'=>'order_id,order_status,restaurant_name,customer_name,remarks,sitename,siteurl', 
			           'push_tag'=>'order_id,order_status,restaurant_name,customer_name,remarks,sitename,siteurl', 
	    			);
    			} else {
    				/*$data[] = "order_status_".$val['description']."_email";
    				$data[] = "order_status_".$val['description']."_sms";
    				$data[] = "order_status_".$val['description']."_push";*/    				    			
    				$description=str_replace(" ","_",$val['description']);
    				$data[] = "order_status_".$description."_email";
    				$data[] = "order_status_".$description."_sms";
    				$data[] = "order_status_".$description."_push";
    			}
    		}
    	}    	
    	return $data;
    }
    
    public static function NotiNewMerchantSignup($data='', $merchant_type='')
    {
    	/*EMAIL*/
    	$DbExt=new DbExt();
    	$merchant_type=t($merchant_type); 
    	$sender=getOptionA('global_admin_sender_email');
    	
    	$lang=Yii::app()->language; 
    	$enabled = getOptionA('merchant_new_signup_email');
    	$to = trim(getOptionA('noti_new_signup_email'));
    	if ($enabled==1 && !empty($to)){
    		$subject = getOptionA("merchant_new_signup_tpl_subject_$lang");
    		$tpl = getOptionA("merchant_new_signup_tpl_content_$lang");
    		$package_info = Yii::app()->functions->getPackagesById( isset($data['package_id'])?$data['package_id']:'' ); 
    		if (!empty($tpl)){    			    			    			
    			$tpl = self::smarty("restaurant_name",$data['restaurant_name'],$tpl);
    			$tpl = self::smarty("merchant_type",$merchant_type,$tpl);    			
    			$tpl = self::smarty("package_name",isset($package_info['title'])?$package_info['title']:'',$tpl);
                $tpl=self::smarty('sitename',getOptionA('website_title'),$tpl);
                $tpl=self::smarty('siteurl',websiteUrl(),$tpl);
    		}
    		if (!empty($subject)){    			    			    			
    			$subject = self::smarty("restaurant_name",$data['restaurant_name'],$subject);
    			$subject = self::smarty("merchant_type",$merchant_type,$subject);    			
    			$subject = self::smarty("package_name",isset($package_info['title'])?$package_info['title']:'',$subject);
                $subject=self::smarty('sitename',getOptionA('website_title'),$subject);
                $subject=self::smarty('siteurl',websiteUrl(),$subject);
    		}
    		if(!empty($tpl) && !empty($to)){
    			$to=explode(",",$to);    			
    			if(is_array($to) && count($to)>=1){
    				foreach ($to as $email) {    					
    					$params=array(
    					   'email_address'=>$email,
    					   'sender'=>$sender,
    					   'subject'=>$subject,
    					   'content'=>$tpl,
    					   'date_created'=>self::dateNow(),
    					   'ip_address'=>$_SERVER['REMOTE_ADDR'],
    					   'module_type'=>'core',    					   
    					);
    					$DbExt->insertData("{{email_logs}}",$params);
    				}
    			}
    		}
    	}    	
    	
    	/*SMS*/
    	$enabled = getOptionA('merchant_new_signup_sms');
    	$mobile = trim(getOptionA('noti_new_signup_sms'));
    	if ($enabled==1 && !empty($mobile)){
    		$tpl = getOptionA("merchant_new_signup_sms_content_$lang");
    		if(!empty($tpl)){
    			
    			$tpl = self::smarty("restaurant_name",$data['restaurant_name'],$tpl);
    			$tpl = self::smarty("merchant_type",$merchant_type,$tpl);    			
    			$tpl = self::smarty("package_name",isset($package_info['title'])?$package_info['title']:'',$tpl);
                $tpl=self::smarty('sitename',getOptionA('website_title'),$tpl);
                $tpl=self::smarty('siteurl',websiteUrl(),$tpl);
    			
    			$mobile = explode(",",$mobile);    			
    			if(is_array($mobile) && count($mobile)>=1){
    				foreach ($mobile as $phone) {    					
    					$params=array(
    					  'contact_phone'=>$phone,
    					  'sms_message'=>$tpl,
    					  'date_created'=>self::dateNow(),
    					  'ip_address'=>$_SERVER['REMOTE_ADDR']
    					);
    					$DbExt->insertData("{{sms_broadcast_details}}",$params);
    				}
    			}
    		}
    	}    	
    }
    
    public static function notifyCustomer($data='',$sms_data='',$order_details_html='', $to='')
    {    	    	
    	$DbExt=new DbExt();     	
    	$lang=Yii::app()->language;   
    	$sms_provider=Yii::app()->functions->getOptionAdmin('sms_provider'); 
    	$enabled = getOptionA("receipt_template_email");
    	$sender=getOptionA("global_admin_sender_email");
    	
    	/*SEND EMAIL TO CUSTOMER*/
    	if($enabled==1 && !empty($to)){
    		$subject = getOptionA("receipt_template_tpl_subject_$lang");
    		$tpl = getOptionA("receipt_template_tpl_content_$lang");
    		
    		$pattern=array(
	    	   'customer_name'=>'full_name',
	    	   'order_id'=>'order_id',
	    	   'restaurant_name'=>'merchant_name',
	    	   'total_amount'=>'total_w_tax',
	    	   'sitename'=>getOptionA('website_title'),
	    	   'siteurl'=>websiteUrl(),	    	   
	    	   'receipt'=>$order_details_html
	    	);
    		
    		if(!empty($subject)){
    			$subject=self::replaceTemplateTags($subject,$pattern,$data);
    		}
    		if(!empty($tpl)){  
    		    $tpl=self::replaceTemplateTags($tpl,$pattern,$data);
    		}    		
    		$params=array(
    		  'email_address'=>$to,
    		  'sender'=>$sender,
    		  'subject'=>$subject,
    		  'content'=>$tpl,
    		  'date_created'=>self::dateNow(),
    		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
    		  'module_type'=>'core'
    		);
    		$DbExt->insertData("{{email_logs}}",$params);    	
    	}
    	    	    	
    	/*SEND SMS*/
    	$enabled = getOptionA("receipt_template_sms");
    	$tpl=getOptionA("receipt_template_sms_content_$lang");
    	$to = isset($data['contact_phone'])?$data['contact_phone']:'';    	
    	if($enabled==1 && !empty($to) && !empty($tpl)){
    		$pattern=array(
	    	   'customer_name'=>'full_name',
	    	   'order_id'=>'order_id',
	    	   'restaurant_name'=>'merchant_name',
	    	   'total_amount'=>'total_w_tax',
	    	   'total_amount_order'=>Yii::app()->functions->normalPrettyPrice($data['total_w_tax']),
	    	   'order_details'=>$sms_data,
	    	   'sitename'=>getOptionA('website_title'),
	    	   'siteurl'=>websiteUrl(),
	    	   'order_change'=>'order_change',
	    	   'contact_phone1'=>'contact_phone1',
	    	   'delivery_instruction'=>'delivery_instruction',
	    	   'client_delivery_address'=>'client_delivery_address',
	    	);
	    	$tpl=self::replaceTemplateTags($tpl,$pattern,$data); 	    	
    		$params=array(
    		  'contact_phone'=>$to,
    		  'sms_message'=>$tpl,
    		  'date_created'=>self::dateNow(),
    		  'ip_address'=>$_SERVER['REMOTE_ADDR']    		 
    		);    		
    		$DbExt->insertData("{{sms_broadcast_details}}",$params); 
    	}
    	
    	unset($DbExt);
    }
    
    public static function replaceTemplateTags($tpl='',$pattern='', $data='')
    {
    	foreach ($pattern as $key=>$val) {
    		switch ($key) {
    			case "sitename":
    			case "siteurl":	
    			case "accept_link":	
    			case "decline_link":	
    			case "receipt":
    			case "order_details":	
    			case "remarks":
    			case "total_amount_order":
    				$tpl=self::smarty($key,$val,$tpl);
    				break;    		
    			case "total_w_tax":	
    			case "total_amount":
    			    $tpl=self::smarty($key,
    				isset($data[$val])?FunctionsV3::prettyPrice($data[$val]):'',$tpl);
    			    break; 
    			case "expiration_date":    
    			case "date_booking":
    			    $tpl=self::smarty($key,
    				isset($data[$val])? date("M d,Y",strtotime($data[$val]))  :'',$tpl);
    			   break; 
    			default:    				
    				$tpl=self::smarty($key,
    				isset($data[$val])?clearString($data[$val]):'',$tpl);
    				break;
    		}    		
    	}
    	return $tpl;
    }
    
    public static function notifyMerchant($data='',$sms_data='',$order_details_html='')
    {
    	$DbExt=new DbExt();     	
    	$lang=Yii::app()->language;   
    	$sender=getOptionA("global_admin_sender_email");
    	
    	/*SEND EMAIL TO MERCHANT*/
    	$enabled=getOptionA("receipt_send_to_merchant_email");
    	$enabled_alert_notification=getOptionA("enabled_alert_notification");    	
    	$merchant_email=getOption($data['merchant_id'],"merchant_notify_email");
    	if ($enabled==1 && $enabled_alert_notification!=1 && !empty($merchant_email) ){
    		$subject=getOptionA("receipt_send_to_merchant_tpl_subject_$lang");
    		
    		 $merchant_token=Yii::app()->functions->getMerchantActivationToken($data['merchant_id']);
             $accept_link = self::getHostURL().Yii::app()->createUrl('store/acceptorder',array(
		     'id'=>$data['order_id'],
		      'token'=>$merchant_token
		     ));    			
	        $decline_link = self::getHostURL().Yii::app()->createUrl('store/declineorder',array(
		     'id'=>$data['order_id'],
		      'token'=>$merchant_token
		    ));    		    
    		$pattern=array(
	    	   'customer_name'=>'full_name',
	    	   'order_id'=>'order_id',
	    	   'restaurant_name'=>'merchant_name',
	    	   'total_amount'=>'total_w_tax',
	    	   'sitename'=>getOptionA('website_title'),
	    	   'siteurl'=>websiteUrl(),
	    	   'accept_link'=>$accept_link,
	    	   'decline_link'=>$decline_link,
	    	   'receipt'=>$order_details_html
	    	);
	    		    	
    		if(!empty($subject)){    			
    			$subject=self::replaceTemplateTags($subject,$pattern,$data);
    		}
    		
    		$tpl = getOptionA("receipt_send_to_merchant_tpl_content_$lang");
    		if(!empty($tpl)){    			
    			$tpl=self::replaceTemplateTags($tpl,$pattern,$data);
    		}    	
    		    		    		
    		$merchant_email=explode(",",$merchant_email);
    		if(is_array($merchant_email) && count($merchant_email)>=1){
    		   foreach ($merchant_email as $email) {
    		   	  $params=array(
	    		   'email_address'=>$email,
	    		   'sender'=>$sender,
	    		   'subject'=>$subject,
	    		   'content'=>$tpl,
	    		   'date_created'=>self::dateNow(),
	    		   'ip_address'=>$_SERVER['REMOTE_ADDR'],
	    		   'module_type'=>'core'
	    		  );	 	    		  
	    		  $DbExt->insertData("{{email_logs}}",$params);	    		  
    		   }
    		}    		
    	}    	   
    	
    	/*SEND SMS*/
    	$enabled=getOptionA("receipt_send_to_merchant_sms");
    	$mobiles = getOption($data['merchant_id'],"sms_notify_number");
    	if ($enabled==1 && !empty($mobiles)){
    		    		
    		$data['taxable_total'] = FunctionsV3::prettyPrice($data['taxable_total']);
    		$data['cart_tip_value'] = FunctionsV3::prettyPrice($data['cart_tip_value']);    		
    		
    		$data['total_order_amount'] = normalPrettyPrice($data['total_w_tax']);    		
    		 
    		$pattern=array(
	    	   'customer_name'=>'full_name',
	    	   'order_id'=>'order_id',
	    	   'restaurant_name'=>'merchant_name',
	    	   'total_amount'=>'total_w_tax',
	    	   'order_details'=>$sms_data,
	    	   'sitename'=>getOptionA('website_title'),
	    	   'siteurl'=>websiteUrl(),
	    	   'customer_mobile'=>'contact_phone',
	    	   'customer_address'=>'client_delivery_address',
	    	   'payment_type'=>'payment_type',
	    	   'transaction_type'=>'trans_type',
	    	   'delivery_time'=>'delivery_time',
	    	   'delivery_instruction'=>'delivery_instruction',
	    	   'taxable_total'=>'taxable_total',
	    	   'cart_tip_value'=>'cart_tip_value',
	    	   'delivery_date'=>'delivery_date',
	    	   'total_order_amount'=>'total_order_amount',
	    	   'order_change'=>'order_change'
	    	);	    	
	    	$tpl=getOptionA("receipt_send_to_merchant_sms_content_$lang");
	    	$tpl=self::replaceTemplateTags($tpl,$pattern,$data);
	    	
	    	$balance=Yii::app()->functions->getMerchantSMSCredit($data['merchant_id']);	    		    	
	    	if (is_numeric($balance) && $balance>=1){	    		
	    		$mobiles=explode(",",$mobiles);
	    		if(is_array($mobiles) && count($mobiles)>=1){
	    			foreach ($mobiles as $mobile) {
		    			$params=array(
			    		  'contact_phone'=>$mobile,
			    		  'sms_message'=>$tpl,
			    		  'date_created'=>self::dateNow(),
			    		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			    		  'merchant_id'=>$data['merchant_id']
			    		);			    		
			    		$DbExt->insertData("{{sms_broadcast_details}}",$params); 
	    			}
	    		}
	    	} else {	    		
	    		$mobiles=explode(",",$mobiles);
	    		if(is_array($mobiles) && count($mobiles)>=1){
	    			foreach ($mobiles as $mobile) {
		    			$params=array(
			    		  'contact_phone'=>$mobile,
			    		  'sms_message'=>$tpl,
			    		  'date_created'=>self::dateNow(),
			    		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			    		  'merchant_id'=>$data['merchant_id'],
			    		  'status'=>"merchant has no sufficient balance has make this request"
			    		);			  			    		
			    		$DbExt->insertData("{{sms_broadcast_details}}",$params); 
	    			}
	    		}
	    	}
    	}
    	
    	
    	/*SEND PUSH TO MERCHANT*/    
    	if (FunctionsV3::hasModuleAddon("merchantapp")){    		
    		self::MerchantpushNewOrder($data['order_id']);
    		FunctionsV3::fastRequest(FunctionsV3::getHostURL().Yii::app()->createUrl("merchantapp/cron/processpush"));
    	}
    	    	
    	unset($DbExt);
    }
    
    public static function notifyAdmin($data='',$sms_data='',$order_details_html='')
    {
    	$DbExt=new DbExt();     	
    	$lang=Yii::app()->language;   
    	$sender=getOptionA("global_admin_sender_email");
    	
    	/*SEND EMAIL TO ADMIN*/
    	$enabled=getOptionA("receipt_send_to_admin_email");    
    	$emails=getOptionA("noti_receipt_email");    	
    	if ($enabled==1 && !empty($emails) ){
    		$subject=getOptionA("receipt_send_to_admin_tpl_subject_$lang");
    		
    		 $merchant_token=Yii::app()->functions->getMerchantActivationToken($data['merchant_id']);
             $accept_link = self::getHostURL().Yii::app()->createUrl('store/acceptorder',array(
		     'id'=>$data['order_id'],
		      'token'=>$merchant_token
		     ));    			
	        $decline_link = self::getHostURL().Yii::app()->createUrl('store/declineorder',array(
		     'id'=>$data['order_id'],
		      'token'=>$merchant_token
		    ));    		    
    		$pattern=array(
	    	   'customer_name'=>'full_name',
	    	   'order_id'=>'order_id',
	    	   'restaurant_name'=>'merchant_name',
	    	   'total_amount'=>'total_w_tax',
	    	   'sitename'=>getOptionA('website_title'),
	    	   'siteurl'=>websiteUrl(),
	    	   'accept_link'=>$accept_link,
	    	   'decline_link'=>$decline_link,
	    	   'receipt'=>$order_details_html,
	    	   'contact_phone'=>"contact_phone" 
	    	);
	    		    	
    		if(!empty($subject)){    			
    			$subject=self::replaceTemplateTags($subject,$pattern,$data);
    		}
    		
    		$tpl = getOptionA("receipt_send_to_admin_tpl_content_$lang");
    		if(!empty($tpl)){    			
    			$tpl=self::replaceTemplateTags($tpl,$pattern,$data);
    		}    	
    		    		
    		$emails=explode(",",$emails);
    		if(is_array($emails) && count($emails)>=1){
    		   foreach ($emails as $email) {
    		   	  $params=array(
	    		   'email_address'=>$email,
	    		   'sender'=>$sender,
	    		   'subject'=>$subject,
	    		   'content'=>$tpl,
	    		   'date_created'=>self::dateNow(),
	    		   'ip_address'=>$_SERVER['REMOTE_ADDR'],
	    		   'module_type'=>'core'
	    		  );	 	  
	    		  $DbExt->insertData("{{email_logs}}",$params);	    		  
    		   }
    		}    		
    	}    	   
    	
    	/*SEND SMS*/
    	$enabled=getOptionA("receipt_send_to_admin_sms");
    	$mobiles = getOptionA('noti_receipt_sms');
    	if ($enabled==1 && !empty($mobiles)){
    		$pattern=array(
	    	   'customer_name'=>'full_name',
	    	   'order_id'=>'order_id',
	    	   'restaurant_name'=>'merchant_name',
	    	   'total_amount'=>'total_w_tax',
	    	   'total_amount_order'=>Yii::app()->functions->normalPrettyPrice($data['total_w_tax']),
	    	   'order_details'=>$sms_data,
	    	   'sitename'=>getOptionA('website_title'),
	    	   'siteurl'=>websiteUrl(),	 
	    	   'contact_phone'=>"contact_phone" 
	    	);
	    	$tpl=getOptionA("receipt_send_to_admin_sms_content_$lang");
	    	$tpl=self::replaceTemplateTags($tpl,$pattern,$data);	    		    	
    		$mobiles=explode(",",$mobiles);
    		if(is_array($mobiles) && count($mobiles)>=1){
    			foreach ($mobiles as $mobile) {
	    			$params=array(
		    		  'contact_phone'=>$mobile,
		    		  'sms_message'=>$tpl,
		    		  'date_created'=>self::dateNow(),
		    		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		    		  'merchant_id'=>$data['merchant_id']
		    		);			    
		    		$DbExt->insertData("{{sms_broadcast_details}}",$params); 
    			}
    		}	    	
    	}
    	
    	unset($DbExt);
    }
    
    public static function notifyCustomerOrderStatusChange($order_id='',$status='', $remarks='')
    {    	    	    	
    	$_GET['backend']=true; $DbExt=new DbExt(); $lang=Yii::app()->language;   
    	if ($data=Yii::app()->functions->getOrder2($order_id)){       		    		
    		/*EMAIL*/
    		
    		$status=str_replace(" ","_",$status);
    		
    		$sender=getOptionA("global_admin_sender_email");
    		$enabled = getOptionA("order_status_".$status."_email");
    		$to=isset($data['email_address'])?trim($data['email_address']):'';
    		    	
    		if($enabled==1 && !empty($to)){    			
    			$subject = getOptionA("order_status_".$status."_tpl_subject_$lang");
    		    $tpl = getOptionA("order_status_".$status."_tpl_content_$lang");
    		    
    		    $pattern=array(
		    	   'customer_name'=>'full_name',
		    	   'order_id'=>'order_id',
		    	   'restaurant_name'=>'merchant_name',
		    	   'total_amount'=>'total_w_tax',
		    	   'order_status'=>'status',
		    	   'sitename'=>getOptionA('website_title'),
		    	   'siteurl'=>websiteUrl(),	 		  
		    	   'remarks'=>$remarks
		    	);
	    		
	    		if(!empty($subject)){
	    			$subject=self::replaceTemplateTags($subject,$pattern,$data);
	    		}
	    		if(!empty($tpl)){  
	    		    $tpl=self::replaceTemplateTags($tpl,$pattern,$data);
	    		}    		    		
	    		$params=array(
	    		  'email_address'=>$to,
	    		  'sender'=>$sender,
	    		  'subject'=>$subject,
	    		  'content'=>$tpl,
	    		  'date_created'=>self::dateNow(),
	    		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
	    		  'module_type'=>'core'
	    		);	   	
	    		if (!empty($subject) && !empty($tpl)){    		
		    		$DbExt->insertData("{{email_logs}}",$params);    		    
		    		self::runCronEmail();
	    		}
    		}
    		
    		/*SMS*/
    		$balance=Yii::app()->functions->getMerchantSMSCredit($data['merchant_id']);
    		if (is_numeric($balance) && $balance>=1){
	    		$enabled = getOptionA("order_status_".$status."_sms");
		    	$tpl=getOptionA("order_status_".$status."_sms_content_$lang");
		    	$to = isset($data['contact_phone1'])?$data['contact_phone1']:'';    	
		    	if(empty($to)){
		    		$to = isset($data['contact_phone'])?$data['contact_phone']:''; 
		    	}
		    	if($enabled==1 && !empty($to) && !empty($tpl)){
		    		$pattern=array(
			    	   'customer_name'=>'full_name',
			    	   'order_id'=>'order_id',
			    	   'restaurant_name'=>'merchant_name',
			    	   'total_amount'=>'total_w_tax',
			    	   'order_status'=>'status',
			    	   'sitename'=>getOptionA('website_title'),
			    	   'siteurl'=>websiteUrl(),	    	
			    	   'remarks'=>$remarks   
			    	);
			    	$tpl=self::replaceTemplateTags($tpl,$pattern,$data);  		    	
		    		$params=array(
		    		  'contact_phone'=>$to,
		    		  'sms_message'=>$tpl,
		    		  'date_created'=>self::dateNow(),
		    		  'ip_address'=>$_SERVER['REMOTE_ADDR']    		 
		    		);	    		
		    		$DbExt->insertData("{{sms_broadcast_details}}",$params); 
		    		self::runCronSMS();
		    	}
    		}
	    	
	    	/*PUSH*/
	    	if (FunctionsV3::hasModuleAddon("mobileapp")){
		    	$enabled = getOptionA("order_status_".$status."_push");	  
		    	$tpl=getOptionA("order_status_".$status."_push_content_$lang"); 
		    	$client_id=isset($data['client_id'])?$data['client_id']:'';
		    	$client_info=self::getDeviceClientId($client_id);				    		    
		    	if($enabled==1 && !empty($tpl) && is_array($client_info) && count($client_info)>=1){
		    		if ($client_info['enabled_push']==1){
			    		$pattern=array(
				    	   'customer_name'=>'full_name',
				    	   'order_id'=>'order_id',
				    	   'restaurant_name'=>'merchant_name',
				    	   'total_amount'=>'total_w_tax',
				    	   'order_status'=>'status',
				    	   'sitename'=>getOptionA('website_title'),
				    	   'siteurl'=>websiteUrl(),	    
				    	   'remarks'=>$remarks	   
				    	);
				    	$tpl=self::replaceTemplateTags($tpl,$pattern,$data); 
				    	
				    	$push_title=getOptionA("order_status_".$status."_push_title_$lang");
				    	$push_title=self::replaceTemplateTags($push_title,$pattern,$data); 
				    	
				    	$params=array(
				    	  'client_id'=>$client_info['client_id'],
				    	  'client_name'=>isset($client_info['client_name'])?$client_info['client_name']:'',
				    	  'device_platform'=>$client_info['device_platform'],
				    	  'device_id'=>$client_info['device_id'],
				    	  'push_title'=>$push_title,
				    	  'push_message'=>$tpl,
				    	  'push_type'=>"order",
				    	  'date_created'=>self::dateNow(),
				    	  'ip_address'=>$_SERVER['REMOTE_ADDR'],
				    	);			    	
				    	$DbExt->insertData("{{mobile_push_logs}}",$params); 
				    	FunctionsV3::fastRequest(
				    	FunctionsV3::getHostURL().Yii::app()->createUrl("mobileapp/cron/processpush"));
		    		}
		    	}
	    	}
	    	
	    	/*SINGLEMERCHANT PUSH*/
	    	if (FunctionsV3::hasModuleAddon("singlemerchant")){
	    		Yii::app()->setImport(array(			
				  'application.modules.singlemerchant.components.*',
				));
	    		if(method_exists('SingleAppClass','OrderTrigger')){	    				    			
	    			SingleAppClass::OrderTrigger($order_id,$status,$remarks);	    			
	    			$link=FunctionsV3::getHostURL().Yii::app()->createUrl("singlemerchant/cron/trigger_order");
	    			FunctionsV3::consumeUrl($link);
	    		}
	    	}  	
	    	
	    	/*MOBILE VERSION 2*/
	    	if (FunctionsV3::hasModuleAddon("mobileappv2")){
	    		Yii::app()->setImport(array(			
				  'application.modules.mobileappv2.components.*',
				));
	    		if(method_exists('mobileWrapper','OrderTrigger')){	    				    			
	    			mobileWrapper::OrderTrigger($order_id,$status,$remarks);
	    		}
	    	}
	    	
    	} 
    	unset($DbExt);
    }
    
    public static function getDeviceClientId($client_id='')
    {
    	$db_ext=new DbExt;    	
    	$stmt="SELECT a.*,
    	concat(b.first_name,' ',b.last_name) as client_name
    	 FROM
    	{{mobile_registered}} a 
    	
    	left join {{client}} b
        ON
        a.client_id=b.client_id
    	
    	WHERE    	
    	a.client_id=".self::q($client_id)."
    	AND
    	a.status='active'
    	LIMIT 0,1
    	";    	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;
    }
    
    public static function fastRequest($url)
	{		
        if (preg_match("/https/i", $url)) {
        	@shell_exec("curl $url");
        } else {        	
		    $parts=parse_url($url);	    
		    $fp = fsockopen($parts['host'],isset($parts['port'])?$parts['port']:80,$errno, $errstr, 30);
		    $out = "GET ".$parts['path']." HTTP/1.1\r\n";
		    $out.= "Host: ".$parts['host']."\r\n";
		    $out.= "Content-Length: 0"."\r\n";
		    $out.= "Connection: Close\r\n\r\n";	
		    fwrite($fp, $out);
		    fclose($fp);
        }	    
	}
    
    public static function WgetRequest($url, $post_array='', $check_ssl=true) 
	{
	  $cmd = "curl -X POST -H 'Content-Type: application/json'";
	  $cmd.= " -d '" . json_encode($post_array) . "' '" . $url . "'";
	
	  if (!$check_ssl){
	    $cmd.= "'  --insecure"; // this can speed things up, though it's not secure
	  }
	  $cmd .= " > /dev/null 2>&1 &"; //just dismiss the response
	
	  //dump($cmd);
	  exec($cmd, $output, $exit);
	  return $exit == 0;
   }     
   
   public static function smsSeparator()
   {
   	  return "\n";
   }
   
   public static function runCronEmail()
   {
   	   FunctionsV3::fastRequest(FunctionsV3::getHostURL().Yii::app()->createUrl("cron/processemail"));
   }
   
   public static function runCronSMS()
   {
   	   FunctionsV3::fastRequest(FunctionsV3::getHostURL().Yii::app()->createUrl("cron/processsms"));
   }
   
   public static function sendBankInstructionPurchase($mtid='', $order_id='', $total_amount='',$client_id='')
   {
   	   $enabled=''; $subject=''; $tpl=''; $client_info='';
   	   $verify_link = self::getHostURL().Yii::app()->createUrl('store/depositverify',array(
   	     'ref'=>$order_id
   	   ));
   	      	   
   	   if(!$client_info=Yii::app()->functions->getClientInfo($client_id)){
   	   	  return false;
   	   }
   	      	   
   	   $data['full_name']=$client_info['first_name']." ".$client_info['last_name'];
   	   $data['amount']=self::prettyPrice($total_amount);
   	   $data['verify_payment_link']=$verify_link;
   	   $data['order_id']=$order_id;
   	   
   	   $to=$client_info['email_address'];
   	   
   	   $lang=Yii::app()->language;      	   
   	   if (FunctionsV3::isMerchantPaymentToUseAdmin($mtid)){
   	   	   $enabled=getOptionA("offline_bank_deposit_purchase_email");
   	   	   if ($enabled){
   	   	   	   $subject=getOptionA("offline_bank_deposit_purchase_tpl_subject_$lang");
   	   	   	   $tpl=getOptionA("offline_bank_deposit_purchase_tpl_content_$lang");
   	   	   }
   	   } else {   	   	   
   	   	  $enabled=getOption($mtid,'merchant_bankdeposit_enabled');
   	   	  if($enabled=="yes"){
   	   	  	 $enabled=1;
   	   	  	 $subject=getOption($mtid,"merchant_deposit_subject");
   	   	  	 $tpl=getOption($mtid,"merchant_deposit_instructions");
   	   	  }
   	   }   	   
   	   $pattern=array(
		   'customer_name'=>'full_name',		   
		   'amount'=>'amount',
		   'order_id'=>'order_id',
		   'verify_payment_link'=>"verify_payment_link",
		   'verify-payment-link'=>"verify_payment_link",
		   'sitename'=>getOptionA('website_title'),
		   'siteurl'=>websiteUrl(),	    		   
		);
		$tpl=self::replaceTemplateTags($tpl,$pattern,$data); 
		$subject=self::replaceTemplateTags($subject,$pattern,$data);
   	   
   	   if($enabled==1){   	      
   	      $sender=getOptionA("global_admin_sender_email");
   	      $DbExt=new DbExt();
   	      $params=array(
		    'email_address'=>$to,
		    'sender'=>$sender,
		    'subject'=>$subject,
		    'content'=>$tpl,
		    'date_created'=>self::dateNow(),
		    'ip_address'=>$_SERVER['REMOTE_ADDR'],
		    'module_type'=>'core'
		  );	  		  
		  $DbExt->insertData("{{email_logs}}",$params);    		    
		  self::runCronEmail();
		  unset($DbExt);
   	   }
   }
   
   public static function getLanguageFlag($lang='')
   {
   	   $assets_folder=Yii::getPathOfAlias('webroot')."/assets";
   	   if (empty($lang)){
   	   	  $lang=Yii::app()->language.".png";
   	   } else $lang="$lang.png";
   	   
   	   $flag=$assets_folder."/images/flags/".$lang;   	   
   	   if (file_exists($flag)){
   	   	   $flag_link=assetsURL()."/images/flags/$lang";
   	   } else $flag_link=assetsURL()."/images/flags/us.png";
   	   return $flag_link;
   }

   public static function getIngredientsByName($name='', $mtid='')
   {
   	   $db_ext=new DbExt;    	
    	$stmt="
    	SELECT * FROM
    	{{ingredients}}
    	WHERE
    	ingredients_name =".self::q($name)."
    	AND
    	merchant_id=".self::q($mtid)."
    	LIMIT 0,1
    	";    	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;
   }
   
   public static function preConfiguredSize()
   {   	   
   	   $size_list =  array(
   	     'small','medium','large'
   	   );
   	   
   	   $pre_configure_size = getOptionA('pre_configure_size');
   	   if(!empty($pre_configure_size)){
   	   	   $pre_configure_size = explode(",",$pre_configure_size);
   	   	   if(is_array($pre_configure_size) && count($pre_configure_size)>=1){
   	   	   	  $size_list = $pre_configure_size;
   	   	   }   	   
   	   }
   	      	   
   	   return $size_list;
   }
   
   public static function autoAddSize($mtid='')
   {
   	    $db_ext=new DbExt;  
   	    $size=self::preConfiguredSize();
    	if(is_array($size) && count($size)>=1){
	    	foreach ($size as $size_name) {
	    		$params_size=array(
	    		   'merchant_id'=>$mtid,
	    		   'size_name'=>$size_name,
	    		   'status'=>"publish",
	    		   'date_created'=>FunctionsV3::dateNow(),
	    		   'ip_address'=>$_SERVER['REMOTE_ADDR'],
	    		   'size_name_trans'=>''
	    		);
	    		$db_ext->insertData("{{size}}",$params_size);
	    	}
    	}      
    	unset($db_ext);
   }
   
   public static function MerchantchangeStatus($mtid='' , $data='')
   {
   	   	   
   	   $lang=Yii::app()->language;   
   	   $email_enabled=getOptionA("merchant_change_status_email");
   	   $sms_enabled=getOptionA("merchant_change_status_sms");
   	   $sender=getOptionA("global_admin_sender_email");
   	   
   	   if($email_enabled!=1 && $sms_enabled!=1){		
		  return false;
	   }
	   
	   if (isset($data['old_status']) && isset($data['status']) ){
	   	   if (!empty($data['old_status'])){
			   if ( $data['old_status']==$data['status']){
			   	  return false;
			   }
	   	   }
	   }

	   $DbExt=new DbExt;
	   
	   $tpl=getOptionA("merchant_change_status_tpl_content_$lang");
	   $subject=getOptionA("merchant_change_status_tpl_subject_$lang");
	   $tpl_sms=getOptionA("merchant_change_status_sms_content_$lang");
	   
	   //$data['status']=t($data['status']);
	   
	   $pattern=array(		    	   
    	   'restaurant_name'=>'restaurant_name',	
    	   'status'=>'status',
    	   'sitename'=>getOptionA('website_title'),
    	   'siteurl'=>websiteUrl(),	 		    	   
    	);
    	$tpl=FunctionsV3::replaceTemplateTags($tpl,$pattern,$data); 
    	$subject=FunctionsV3::replaceTemplateTags($subject,$pattern,$data); 
    	$tpl_sms=FunctionsV3::replaceTemplateTags($tpl_sms,$pattern,$data); 	
    	
    	$merchant_email=isset($data['contact_email'])?$data['contact_email']:'';
    	$contact_phone=isset($data['contact_phone'])?$data['contact_phone']:'';

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
		  'contact_phone'=>$contact_phone,
		  'sms_message'=>$tpl_sms,
		  'date_created'=>FunctionsV3::dateNow(),
		  'ip_address'=>$_SERVER['REMOTE_ADDR']    		 
		);	    				
		$DbExt->insertData("{{sms_broadcast_details}}",$params); 	   	
		
		unset($DbExt);
		FunctionsV3::runCronEmail();
		FunctionsV3::runCronSMS();
   }
   
   public static function getMerchantServices($mtid='')
   {
   	   if ( $res=self::getMerchantInfo($mtid)){
   	   	   return $res['service'];
   	   }
   	   return false;
   }
   
   public static function notifyBooking($data='')
   {   	   
   	
   	   if(isset($data['date_booking'])){
   	      $data['date_booking']=FunctionsV3::prettyDate($data['date_booking']);
   	   }   	   
   	  
   	   $lang=Yii::app()->language; 
   	   $sender=getOptionA("global_admin_sender_email");
   	   $DbExt=new DbExt;
   	   
   	   /*NOTIFY CUSTOMER EMAIL*/
   	   $enabled=getOptionA("customer_booked_email");
   	   if($enabled==1){
   	   	  $tpl=getOptionA("customer_booked_tpl_content_$lang");
   	   	  $subject=getOptionA("customer_booked_tpl_subject_$lang");
   	   	  
   	   	  $email=isset($data['email'])?$data['email']:'';
   	   	  
   	   	  $pattern=array(		    
   	   	   'customer_name'=>"booking_name",
    	   'restaurant_name'=>'restaurant_name',	
    	   'number_guest'=>'number_guest',
    	   'date_booking'=>'date_booking',
    	   'time'=>"booking_time",
    	   'email'=>"email",
    	   'mobile'=>"mobile",
    	   'instruction'=>"booking_notes",
    	   'booking_id'=>"booking_id",
    	   'status'=>'status',
    	   'sitename'=>getOptionA('website_title'),
    	   'siteurl'=>websiteUrl(),	 		    	   
    	  );
    	  $tpl=FunctionsV3::replaceTemplateTags($tpl,$pattern,$data);
    	  $subject=FunctionsV3::replaceTemplateTags($subject,$pattern,$data);
   	   	  
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
   	   }
   	   
   	   /*NOTIFY ADMIN*/
   	   $enabled=getOptionA("booked_notify_admin_email");
   	   if($enabled==1){
   	   	  $tpl=getOptionA("booked_notify_admin_tpl_content_$lang");
   	   	  $subject=getOptionA("booked_notify_admin_tpl_subject_$lang");
   	   	  
   	   	  $email=getOptionA("noti_booked_admin_email");
   	   	  
   	   	  $pattern=array(		    
   	   	   'customer_name'=>"booking_name",
    	   'restaurant_name'=>'restaurant_name',	
    	   'number_guest'=>'number_guest',
    	   'date_booking'=>'date_booking',
    	   'time'=>"booking_time",
    	   'email'=>"email",
    	   'mobile'=>"mobile",
    	   'instruction'=>"booking_notes",
    	   'booking_id'=>"booking_id",
    	   'status'=>'status',
    	   'sitename'=>getOptionA('website_title'),
    	   'siteurl'=>websiteUrl(),	 		    	   
    	  );
    	  $tpl=FunctionsV3::replaceTemplateTags($tpl,$pattern,$data);
    	  $subject=FunctionsV3::replaceTemplateTags($subject,$pattern,$data);
    	  
    	  //dump($email);dump($subject);dump($tpl);
   	   	  
    	  $email=explode(",",$email);
    	  if(is_array($email) && count($email)>=1){
    	     foreach ($email as $email_val) {    	     	    	     
		    	  $params=array(
				   'email_address'=>$email_val,
				   'sender'=>$sender,
				   'subject'=>$subject,
				   'content'=>$tpl,
				   'date_created'=>FunctionsV3::dateNow(),
				   'ip_address'=>$_SERVER['REMOTE_ADDR'],
				   'module_type'=>'core'
				  );	    						  
				  $DbExt->insertData("{{email_logs}}",$params);    	  
    	     }
    	  }
   	   }
   	   
   	   
   	   /*NOTIFY MERCHANT*/
   	   $enabled=getOptionA("booked_notify_merchant_email");
   	   if($enabled==1){
   	   	  $tpl=getOptionA("booked_notify_merchant_tpl_content_$lang");
   	   	  $subject=getOptionA("booked_notify_merchant_tpl_subject_$lang");
   	   	  
   	   	  $email=getOption($data['merchant_id'],'merchant_booking_receiver');
   	   	  
   	   	  $pattern=array(		    
   	   	   'customer_name'=>"booking_name",
    	   'restaurant_name'=>'restaurant_name',	
    	   'number_guest'=>'number_guest',
    	   'date_booking'=>'date_booking',
    	   'time'=>"booking_time",
    	   'email'=>"email",
    	   'mobile'=>"mobile",
    	   'instruction'=>"booking_notes",
    	   'booking_id'=>"booking_id",
    	   'status'=>'status',
    	   'sitename'=>getOptionA('website_title'),
    	   'siteurl'=>websiteUrl(),	 		    	   
    	  );
    	  $tpl=FunctionsV3::replaceTemplateTags($tpl,$pattern,$data);
    	  $subject=FunctionsV3::replaceTemplateTags($subject,$pattern,$data);
    	  
    	  //dump($email);dump($subject);dump($tpl);
    	  
   	   	  $email=explode(",",$email);
    	  if(is_array($email) && count($email)>=1){
    	     foreach ($email as $email_val) {    	     	    	         	     	
		    	  $params=array(
				   'email_address'=>$email_val,
				   'sender'=>$sender,
				   'subject'=>$subject,
				   'content'=>$tpl,
				   'date_created'=>FunctionsV3::dateNow(),
				   'ip_address'=>$_SERVER['REMOTE_ADDR'],
				   'module_type'=>'core'
				  );	    						  
				  $DbExt->insertData("{{email_logs}}",$params);    	
    	     }
    	  }    	       
   	   }
   	   
   	   /*NOTIFY MERCHANT PUSH*/
   	   if (FunctionsV3::hasModuleAddon("merchantapp")){
	   	   $enabled=getOptionA("booked_notify_merchant_push");
	   	   if($enabled==1){
	   	   	  $push_title=getOptionA("booked_notify_merchant_push_title_$lang");
	   	   	  $push_content=getOptionA("booked_notify_merchant_push_content_$lang");
	   	   	   
	   	   	  $pattern=array(		    
	   	   	   'customer_name'=>"booking_name",
	    	   'restaurant_name'=>'restaurant_name',	
	    	   'number_guest'=>'number_guest',
	    	   'date_booking'=>'date_booking',
	    	   'time'=>"booking_time",
	    	   'email'=>"email",
	    	   'mobile'=>"mobile",
	    	   'instruction'=>"booking_notes",
	    	   'booking_id'=>"booking_id",
	    	   'status'=>'status',
	    	   'sitename'=>getOptionA('website_title'),
	    	   'siteurl'=>websiteUrl(),	 		    	   
	    	  );
	    	  $push_title=FunctionsV3::replaceTemplateTags($push_title,$pattern,$data);
	    	  $push_content=FunctionsV3::replaceTemplateTags($push_content,$pattern,$data);
	    	  
	    	  $stmt="SELECT * FROM
	    	  {{mobile_device_merchant}}
	    	  WHERE
	    	  merchant_id=".self::q($data['merchant_id'])."
	    	  AND
	    	  status = 'active'
	    	  AND 
	    	  enabled_push='1'
	    	  LIMIT 0,100
	    	  ";	    	 
	    	  if ( $res=$DbExt->rst($stmt)){
	    	  	foreach ($res as $val_merchant) {	    	  		
	    	  		$params_merchant_push=array(
	    	  		   'merchant_id'=>$val_merchant['merchant_id'],
	    	  		   'user_type'=>$val_merchant['user_type'],
	    	  		   'merchant_user_id'=>$val_merchant['merchant_user_id'],
	    	  		   'device_platform'=>$val_merchant['device_platform'],
	    	  		   'device_id'=>$val_merchant['device_id'],
	    	  		   'push_title'=>$push_title,
	    	  		   'push_message'=>$push_content,
	    	  		   'date_created'=>FunctionsV3::dateNow(),
	    	  		   'ip_address'=>$_SERVER['REMOTE_ADDR'],
	    	  		   'booking_id'=>$data['booking_id'],
	    	  		   'push_type'=>'booking'
	    	  		);	    	  		
	    	  		$DbExt->insertData("{{mobile_merchant_pushlogs}}",$params_merchant_push);    	
	    	  	}
	    	  }
	   	   }   	   
   	   }
   	   
   	   unset($DbExt);
   	   FunctionsV3::runCronEmail();
   }
   
   public static function updateBookingNotify($data='')
   {
   	   $lang=Yii::app()->language; 
   	   $sender=getOptionA("global_admin_sender_email");
   	   $DbExt=new DbExt;   
   	   
   	   if($booking_details = self::getBookingByIDWithDetails($data['booking_id'])){
   	   	  $data['restaurant_name']=$booking_details['restaurant_name'];
   	   }   	  
   	   
   	   $booking_status = isset($data['status'])?$data['status']:'';
   	   $data['status'] = t($data['status']);
   	   
   	   $enabled=getOptionA("booking_update_status_email");
   	   if($enabled==1){
   	   	  $tpl=getOptionA("booking_update_status_tpl_content_$lang");
   	   	  $subject=getOptionA("booking_update_status_tpl_subject_$lang");
   	   	  
   	   	  $email=isset($data['email'])?$data['email']:'';
   	   	  
   	   	  $pattern=array(		    
   	   	   'customer_name'=>"booking_name",
    	   'restaurant_name'=>'restaurant_name',	
    	   'number_guest'=>'number_guest',
    	   'date_booking'=>'date_booking',
    	   'time'=>"booking_time",
    	   'email'=>"email",
    	   'mobile'=>"mobile",
    	   'instruction'=>"booking_notes",
    	   'booking_id'=>"booking_id",
    	   'status'=>'status',
    	   'merchant_remarks'=>'remarks',
    	   'sitename'=>getOptionA('website_title'),
    	   'siteurl'=>websiteUrl(),	 		    	   
    	  );
    	  $tpl=FunctionsV3::replaceTemplateTags($tpl,$pattern,$data);
    	  $subject=FunctionsV3::replaceTemplateTags($subject,$pattern,$data);
   	   	  
    	  /*dump($email); dump($subject); dump($tpl);
    	  die();*/
    	  
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
   	   }
   	   
   	   
   	   /*PUSH*/   	   
   	   if (FunctionsV3::hasModuleAddon("mobileapp")){
   	   	   $client_id='';
   	   	   //if ($booking_details=self::getBookingByID($data['booking_id'])){ 
   	   	   if($booking_details){
   	   	   	   if ($booking_details['client_id']>0){
   	   	   	   	   $client_id=$booking_details['client_id'];   	   	   	   	   
   	   	   	   } 
   	   	   	      	   	   	   
   	   	   	   $enabled=getOptionA('booking_update_status_push');   	   	   	   
   	   	   	   $push_tpl=getOptionA("booking_update_status_push_title_$lang"); 
   	   	   	   $tpl=getOptionA("booking_update_status_push_content_$lang"); 
   	   	   	      	   	   	   
   	   	   	   if($client_id>0 && $enabled==1){
   	   	   	   	  if($client_info=self::getDeviceClientId($client_id)){   	   	   	   	  	  
   	   	   	   	  	  if ($client_info['enabled_push']!=1){		    			  
		    		  	    		    	   	  	
	   	   	   	   	  	  $pattern=array(		    
				   	   	   'customer_name'=>"booking_name",
				    	   'restaurant_name'=>'restaurant_name',	
				    	   'number_guest'=>'number_guest',
				    	   'date_booking'=>'date_booking',
				    	   'time'=>"booking_time",
				    	   'email'=>"email",
				    	   'mobile'=>"mobile",
				    	   'instruction'=>"booking_notes",
				    	   'booking_id'=>"booking_id",
				    	   'status'=>'status',
				    	   'merchant_remarks'=>'remarks',
				    	   'sitename'=>getOptionA('website_title'),
				    	   'siteurl'=>websiteUrl(),	 		    	   
				    	  );
				    	  $tpl=FunctionsV3::replaceTemplateTags($tpl,$pattern,$data);
				    	  $push_tpl=FunctionsV3::replaceTemplateTags($push_tpl,$pattern,$data);
	   	   	   	   	  	
	   	   	   	   	  	 //dump($push_tpl); dump($tpl);   
	   	   	   	   	  	 
	   	   	   	   	  	 $params=array(
				    	  'client_id'=>$client_info['client_id'],
				    	  'client_name'=>isset($client_info['client_name'])?$client_info['client_name']:'',
				    	  'device_platform'=>$client_info['device_platform'],
				    	  'device_id'=>$client_info['device_id'],
				    	  'push_title'=>$push_tpl,
				    	  'push_message'=>$tpl,
				    	  'push_type'=>"book",
				    	  'date_created'=>self::dateNow(),
				    	  'ip_address'=>$_SERVER['REMOTE_ADDR'],
				    	);					    	
				    	$DbExt->insertData("{{mobile_push_logs}}",$params); 
				    	FunctionsV3::fastRequest(
				    	FunctionsV3::getHostURL().Yii::app()->createUrl("mobileapp/cron/processpush")); 
			    	
   	   	   	   	  	  }
   	   	   	   	  }
   	   	   	   }
   	   	   }
   	   }
   	   
   	   
   	   /*SINGLEAPP MERCHANT*/   	   
   	   if (FunctionsV3::hasModuleAddon("singlemerchant")){
   	   	   Yii::app()->setImport(array(			
			  'application.modules.singlemerchant.components.*',
		   ));
		   if(method_exists('SingleAppClass','OrderTrigger')){	    				    			
			  SingleAppClass::OrderTrigger($data['booking_id'],$booking_status,$data['remarks'],'booking');
			  $link=FunctionsV3::getHostURL().Yii::app()->createUrl("singlemerchant/cron/trigger_order");
    		  FunctionsV3::consumeUrl($link);
		   }
   	   }
   	   
   	   /*MOBILE VERSION 2*/
		if (FunctionsV3::hasModuleAddon("mobileappv2")){
			Yii::app()->setImport(array(			
			  'application.modules.mobileappv2.components.*',
			));			
			if(method_exists('mobileWrapper','OrderTrigger')){	    				    			
				mobileWrapper::OrderTrigger($data['booking_id'],$booking_status,$data['remarks'],'booking');
			}
		}
   	   
   	   unset($DbExt);
   	   FunctionsV3::runCronEmail();
   }
   
   public static function getBooking($client_id='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{bookingtable}}
   	   WHERE
   	   client_id=".self::q($client_id)."
   	   ORDER BY booking_id DESC
   	   LIMIT 0,20
   	   ";
   	   if ($res=$DbExt->rst($stmt)){
   	   	  return $res;
   	   }
   	   return false;
   }
   
   public static function getBookingHistory($booking_id='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{bookingtable_history}}
   	   WHERE
   	   booking_id=".self::q($booking_id)."
   	   ORDER BY id DESC   	   
   	   ";
   	   if ($res=$DbExt->rst($stmt)){
   	   	  return $res;
   	   }
   	   return false;
   }   
   
   public static function validateEmail($email){
   	   $emailIsValid = FALSE;
   	   if (!empty($email)){
   	   	   $domain = ltrim(stristr($email, '@'), '@');
   	   	   $user   = stristr($email, '@', TRUE);
   	   	    if(!empty($user) && !empty($domain) && checkdnsrr($domain)){
   	   	    	$emailIsValid = TRUE;
   	   	    }
   	   }
   	    return $emailIsValid;
   }
   
   public static function locationCountry($country_id='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{location_countries}}
   	   WHERE
   	   country_id=".self::q($country_id)."
   	   LIMIT 0,1
   	   ";
   	   if ($res=$DbExt->rst($stmt)){
   	   	  unset($DbExt);
   	   	  return $res[0];
   	   }
   	   unset($DbExt);
   	   return false;
   }
   
   public static function locationCountryList()
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{location_countries}}
   	   ORDER BY country_name ASC   	   
   	   ";
   	   if ($res=$DbExt->rst($stmt)){
   	   	  unset($DbExt);
   	   	  return $res;
   	   }
   	   unset($DbExt);
   	   return false;
   }   
   
   public static function countryList()
   {
   	   if ($res=self::locationCountryList()){
   	   	   $data=array();
   	   	   foreach ($res as $val) {
   	   	   	   $data[$val['country_id']]=self::cleanString($val['country_name']);
   	   	   }
   	   	   return $data;
   	   }
   	   return false;
   }
   
   public static function getDefaultCountrySignup($country_code='')
   {   	   
   	   $stmt="
   	   SELECT country_id,shortcode
   	   FROM
   	   {{location_countries}}
   	   WHERE
   	   shortcode=".self::q($country_code)."
   	   ";
   	   $DbExt=new DbExt;
   	   if ($res=$DbExt->rst($stmt)){
   	   	  unset($DbExt);
   	   	  return $res[0]['country_id'];
   	   }
   	   unset($DbExt);
   	   return false;
   }
   
   public static function getCountryByShortCode($short_code='')
   {   	   
   	   $stmt="
   	   SELECT country_id,shortcode,country_name
   	   FROM
   	   {{location_countries}}
   	   WHERE
   	   shortcode=".self::q($short_code)."
   	   LIMIT 0,1
   	   ";
   	   $DbExt=new DbExt;
   	   if ($res=$DbExt->rst($stmt)){
   	   	  unset($DbExt);
   	   	  return $res[0];
   	   }
   	   unset($DbExt);
   	   return false;
   } 
   
   public static function locationStateList($country_id='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{location_states}}
   	   WHERE
   	   country_id=".self::q($country_id)."
   	   ORDER BY sequence ASC   	   
   	   ";   	   
   	   if ($res=$DbExt->rst($stmt)){
   	   	  unset($DbExt);
   	   	  return $res;
   	   }
   	   unset($DbExt);
   	   return false;
   }
   
   public static function ListLocationState($country_id='',$with_select=true)
   {
   	  if ( $res=self::locationStateList($country_id)){
   	  	   $data=array();
   	  	   if($with_select){
   	  	   	  $data['']=t("Select State");
   	  	   }
   	  	   foreach ($res as $val) {   	  	   	 
   	  	   	  $data[$val['state_id']]=stripslashes($val['name']);
   	  	   }   	  	   
   	  	   return $data;
   	  }
   	  return false;
   }
   
   public static function getCountryByID($country_id='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{location_countries}}
   	   WHERE
   	   country_id=".self::q($country_id)."
   	   LIMIT 0,1
   	   ";
   	   if ($res=$DbExt->rst($stmt)){
   	   	  unset($DbExt);
   	   	  return $res[0];
   	   }
   	   unset($DbExt);
   	   return false;
   }   
   
   public static function getStateByID($state_id='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{location_states}}
   	   WHERE
   	   state_id=".self::q($state_id)."
   	   LIMIT 0,1
   	   ";   	   
   	   if ($res=$DbExt->rst($stmt)){
   	   	  unset($DbExt);
   	   	  return $res[0];
   	   }
   	   unset($DbExt);
   	   return false;
   }
   
   public static function locationCityList($state_id='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{location_cities}}
   	   WHERE
   	   state_id=".self::q($state_id)."
   	   ORDER BY sequence ASC
   	   ";
   	   if ($res=$DbExt->rst($stmt)){
   	   	  unset($DbExt);
   	   	  return $res;
   	   }
   	   unset($DbExt);
   	   return false;
   }   
   
   public static function ListCityList($state_id='' , $with_select=true )
   {
   	   if ($res=self::locationCityList($state_id)){
   	   	   $data=array();
   	   	   if($with_select){
   	  	   	  $data['']=t("Select City");
   	  	   }
   	   	   foreach ($res as $val) {
   	   	   	   $data[$val['city_id']]=stripslashes($val['name']);
   	   	   }
   	   	   return $data;
   	   }
   	   return false;
   }
   
   public static function getCityByID($city_id='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{location_cities}}
   	   WHERE
   	   city_id=".self::q($city_id)."
   	   LIMIT 0,1
   	   ";   	   
   	   if ($res=$DbExt->rst($stmt)){
   	   	  unset($DbExt);
   	   	  return $res[0];
   	   }
   	   unset($DbExt);
   	   return false;
   } 
   
   public static function locationAreaList($city_id='')
   {
   	  $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{location_area}}
   	   WHERE
   	   city_id=".self::q($city_id)."
   	   ORDER BY sequence ASC
   	   ";
   	   if ($res=$DbExt->rst($stmt)){
   	   	  unset($DbExt);
   	   	  return $res;
   	   }
   	   unset($DbExt);
   	   return false;
   } 
   
   public static function AreaList($city_id='' , $with_select=true)
   {
   	   if ($res=self::locationAreaList($city_id)){
   	   	   $data=array();
   	   	   if($with_select){
   	  	   	  $data['']=t("Select Distric/Area/neighborhood");
   	  	   }
   	   	   foreach ($res as $val) {
   	   	   	   $data[$val['area_id']]=stripslashes($val['name']);
   	   	   }
   	   	   return $data;
   	   }
   	   return false;
   }
   
   public static function getAreaLocation($area_id='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{location_area}}
   	   WHERE
   	   area_id=".self::q($area_id)."
   	   LIMIT 0,1
   	   ";
   	   if ($res=$DbExt->rst($stmt)){
   	   	  unset($DbExt);
   	   	  return $res[0];
   	   }
   	   unset($DbExt);
   	   return false;
   }
   
   public static function getLocationStateIdByCity($city_id=0)
   {
   	   $stmt="
   	   SELECT state_id,name,postal_code FROM {{location_cities}}
   	   WHERE
   	   city_id=".q($city_id)."
   	   ";
   	   if($resp = Yii::app()->db->createCommand($stmt)->queryRow()){ 
   	   	  return $resp;
   	   }
   	   return false;
   }
   
   public static function getCountryByCode($code='US')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{location_countries}}
   	   WHERE
   	   shortcode=".self::q($code)."
   	   LIMIT 0,1
   	   ";
   	   if ($res=$DbExt->rst($stmt)){
   	   	  unset($DbExt);
   	   	  return $res[0];
   	   }
   	   unset($DbExt);
   	   return false;
   }
   
   public static function getLocationDefaultCountry()
   {
   	    $country_id=getOptionA('location_default_country'); $state_ids='';
   	    if(empty($country_id)){
   	    	if($res=self::getCountryByCode()){   	    	
   	    		$country_id=$res['country_id'];
   	    	}
   	    }
   	    return $country_id;
   }
      
   public static function getCityList($stated_id=0)
   {
   	    $and="";
   	    if($stated_id<=0) {
	   	    $country_id=getOptionA('location_default_country'); $state_ids='';
	   	    if(empty($country_id)){
	   	    	if($res=self::getCountryByCode()){   	    	
	   	    		$country_id=$res['country_id'];
	   	    	}
	   	    }
	   	    if(!empty($country_id)){
	   	    	if ($res=self::locationStateList($country_id)){
	   	    		foreach ($res as $val) {
	   	    			$state_ids.="'$val[state_id]',";
	   	    		}
	   	    		$state_ids=substr($state_ids,0,-1);
	   	    	}
	   	    }
	   	    	   	    
	   	    if(!empty($state_ids)){
	   	    	$and.=" AND state_id IN ($state_ids) ";
	   	    }
   	    } else {
   	    	$and.=" AND state_id =".q($stated_id)." ";
   	    }
   	    
   	    
   	    $DbExt=new DbExt; $data=array();
   	    $stmt="SELECT * FROM
   	    {{location_cities}}
   	    WHERE 1
   	    $and
   	    ORDER BY name ASC   	    
   	    LIMIT 0,10
   	    ";     	    
   	    if($res = Yii::app()->db->createCommand($stmt)->queryAll()){   
   	    	foreach ($res as $val) {
   	    		$data[]=array(
   	    		  'id'=>$val['city_id'],
   	    		  'name'=>$val['name']
   	    		);
   	    	}
   	    	return $data;
   	    }
   	    return false;
   }
   
   public static function isSearchByLocation()
   {
   	    $home_search_mode=getOptionA('home_search_mode');
   	    if($home_search_mode=="postcode"){
   	    	return true;
   	    }
   	    return false;
   }
   
   public static function GetLocationRateByMerchant($mtid='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{location_rate}}
   	   WHERE
   	   merchant_id=".self::q($mtid)."
   	   ORDER BY rate_id ASC
   	   ";
   	   if ( $res=$DbExt->rst($stmt)){
   	   	   return $res;
   	   }
   	   return false;
   }
   
   public static function GetViewLocationRateByMerchant($mtid='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{view_location_rate}}
   	   WHERE
   	   merchant_id=".self::q($mtid)."
   	   ORDER BY sequence ASC
   	   ";
   	   if ( $res=$DbExt->rst($stmt)){
   	   	   return $res;
   	   }
   	   return false;
   }   
   
   public static function GetFeeByRateIDView($rate_id='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{view_location_rate}}
   	   WHERE
   	   rate_id=".self::q($rate_id)."
   	   LIMIT 0,1
   	   ";
   	   if ( $res=$DbExt->rst($stmt)){
   	   	   return $res[0];
   	   }
   	   return false;
   }      
   
   public static function GetLocationRateByMerchantWithName($mtid='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT a.*,
   	    b.country_name,
   	    c.name as state_name,
   	    d.name as city_name,
   	    d.postal_code,
   	    e.name as area_name
   	    
   	    FROM
   	   {{location_rate}} a
   	   
   	   left join {{location_countries}} b
       on
       a.country_id=b.country_id	   
       
       left join {{location_states}} c
       on
       a.state_id = c.state_id
       
       left join {{location_cities}} d
       on
       a.city_id = d.city_id
       
       left join {{location_area}} e
       on
       a.area_id = e.area_id
		
   	   WHERE
   	   merchant_id=".self::q($mtid)."
   	   ORDER BY sequence ASC
   	   ";
   	   //dump($stmt);
   	   if ( $res=$DbExt->rst($stmt)){
   	   	   return $res;
   	   }
   	   return false;
   }
   
   public static function GetLocationRateByID($rate_id='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{location_rate}}
   	   WHERE
   	   rate_id=".self::q($rate_id)."
   	   LIMIT 0,1
   	   ";
   	   if ( $res=$DbExt->rst($stmt)){
   	   	   return $res[0];
   	   }
   	   return false;
   }   
   
   public static function getSearchByLocationData()
   {   	  
   	  $location_type=getOptionA('admin_zipcode_searchtype');	
	  $location_data=Cookie::getCookie('kr_location_search');
	  if(!empty($location_data)){
	  	 $location_data=json_decode($location_data,true);
	  	 if(is_array($location_data) && count($location_data)>=1){
	  	 	$location_data['location_type']=$location_type;
	  	 	return $location_data;
	  	 }
	  }
	  return false;
   }
   
   public static function validateCanDeliverByLocation($merchant_id='',$data='')
   {
   	   $and='';    	   
   	   if ( is_array($data) && count($data)>=1){
   	   	   switch ($data['location_type']) {   	   	     	   	   		 	   	   	   	
   	   	   	case 2:   	   	   		
   	   	   	    $and.=" AND state_id=".self::q($data['state_id'])." ";
   	   	   		$and.=" AND city_id=".self::q($data['city_id'])." ";
   	   	   		
   	   	   		if(isset($data['area_id'])){
   	   	   		   $and.=" AND area_id=".self::q($data['area_id'])." ";
   	   	   		}
   	   	   		break;
   	   	   		
   	   	   	case 3:
   	   	   	    $and.=" AND state_id=".self::q($data['state_id'])." ";
   	   	   		$and.=" AND city_id=".self::q($data['city_id'])." ";
   	   	   		$and.=" AND area_id=".self::q($data['area_id'])." ";
   	   	   		break;	
   	   	   
   	   	    case 3:
   	   	    	$and.=" AND postal_code=".self::q($data['postal_code'])." ";
   	   	   	    break;	
   	   	   		 	
   	   	   	default:
   	   	   		$and.=" AND city_id=".self::q($data['city_id'])." ";
   	   	   		$and.=" AND area_id=".self::q($data['area_id'])." ";
   	   	   		break;
   	   	   }
   	   	   
   	   	   $DbExt=new DbExt;
   	   	   $stmt="SELECT * FROM
   	   	   {{view_location_rate}}
   	   	   WHERE
   	   	   merchant_id=".self::q($merchant_id)."
   	   	   $and
   	   	   LIMIT 0,1
   	   	   ";   	   	   
   	   	   //dump($stmt);
   	   	   if ( $res=$DbExt->rst($stmt)){   	   	   	   
   	   	   	   return $res[0];
   	   	   }    	   	   
   	   }
   	   return false;
   }
   
   public static function getLocationDeliveryFee($merchant_id='',$default_fee='',$data='')
   {
   	   $fee=0; $and='';   	      	   
   	   if ( is_array($data) && count($data)>=1){
   	   	   switch ($data['location_type']) {
   	   	   	case 2:   	   	   		   	   	   	    
   	   	   	    $state_id = isset($data['state_id'])?(integer)$data['state_id']:0;
   	   	   	    $city_id = isset($data['city_id'])?(integer)$data['city_id']:0; 
   	   	   		
   	   	   	    $and.=" AND state_id=".self::q($state_id)." ";
   	   	   	    
   	   	   	    if($city_id>0){
   	   	   		$and.=" AND city_id=".self::q($city_id)." ";
   	   	   	    }
   	   	   		
   	   	   		$area_id = isset($data['area_id'])?(integer)$data['area_id']:0;
   	   	   		if($area_id>0){
   	   	   		   $and.=" AND area_id=".self::q($area_id)." ";
   	   	   		}
   	   	   		break;
   	   	   		
   	   	   	case 3:
   	   	   		$postal_code = isset($data['postal_code'])?(integer)$data['postal_code']:0;
   	   	   		$city_id = isset($data['city_id'])?(integer)$data['city_id']:0; 
   	   	   		$area_id = isset($data['area_id'])?(integer)$data['area_id']:0;
   	   	   		
   	   	   		if($postal_code>0){
   	   	   	      $and.=" AND postal_code=".self::q($postal_code)." ";   	   	   		   	   	   	    
   	   	   		}
   	   	   		if($city_id>0){
   	   	   		   $and.=" AND city_id=".self::q($city_id)." ";
   	   	   		}
   	   	   		if($area_id>0){
   	   	   		   $and.=" AND area_id=".self::q($area_id)." ";
   	   	   		}
   	   	   		break;	
   	   	   	 	
   	   	   	default:
   	   	   		$city_id = isset($data['city_id'])?(integer)$data['city_id']:0;
   	   	   		$area_id = isset($data['area_id'])?(integer)$data['area_id']:0;
   	   	   		$and.=" AND city_id=".self::q($city_id)." ";
   	   	   		if($area_id>0){
   	   	   		   $and.=" AND area_id=".self::q($area_id)." ";
   	   	   		}
   	   	   		break;
   	   	   }
   	   	   
   	   	   $DbExt=new DbExt;
   	   	   $stmt="SELECT * FROM
   	   	   {{view_location_rate}}
   	   	   WHERE
   	   	   merchant_id=".self::q($merchant_id)."
   	   	   $and
   	   	   ORDER BY rate_id ASC
   	   	   LIMIT 0,1
   	   	   ";    	   	   
   	   	   if ( $res=$DbExt->rst($stmt)){
   	   	   	   $res=$res[0];
   	   	   	   $fee=$res['fee'];
   	   	   } else $fee=$default_fee;
   	   } else $fee=$default_fee;
   	   return $fee;
   }
      
   public static function getFeatureMerchant()
    {
    	$sort_options=getOptionA('featured_merchant_sort');
    	$sortby=''; $tbl='merchant';
    	
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
    			$sortby='ORDER BY restaurant_name ASC';
    			break;
    	}
    	
    	$db_ext=new DbExt; 
    	$stmt="SELECT a.*    	    
    	    FROM
    	    {{{$tbl}}} a
			WHERE
			status in ('active')
			AND
			is_featured='2'
			AND
			is_ready='2'
			$sortby
			LIMIT 0,20
		";     	
    	//dump($stmt);
    	if ( $res=$db_ext->rst($stmt)){    		
    		return $res;
    	}
    	return false;    	
    }   
    
    public static function MembershipType()
    {
    	return array(
    	  1=>t("Membership"),
    	  2=>t("Commission"),
    	  3=>t("Commission+Invoice")
    	);
    }

	public static function DisplayMembershipType($merchant_type='', $terms='')
	{		
		$list=self::MembershipType(); $terms_list=self::InvoiceTerms();
		if (array_key_exists($merchant_type,$list)){
			if ( $merchant_type==3 ){
				if(isset($terms_list[$terms])){
					return $list[$merchant_type]." ".$terms_list[$terms];
				} else return $list[$merchant_type];
			} else return $list[$merchant_type];			
		}
		return '-';
	}    
    
    public static function InvoiceTerms()
    {
    	return array(
    	  1=>t("Daily"),
    	  7=>t("Weekly"),
    	  15=>t("Every 15 Days"),
    	  30=>t("Every 30 Days"),
    	);
    }
    
    public static function prettyInvoiceTerms($term_id='')
    {
    	$list=self::InvoiceTerms();
    	if (array_key_exists($term_id,$list)){
    		return $list[$term_id];
    	}
    	return false;
    }
    
	public static function getMerchantTypeBySession()
	{
		if (!empty($_SESSION['kr_merchant_user'])){
			$user=json_decode($_SESSION['kr_merchant_user'],true);			
			//dump($user);
			if (is_array($user) && count($user)>=1){				
				return $user[0]['merchant_type'];
			}
		}
		return false;
	}    
	
	public static function getMerchantMembershipType($merchant_id='')
	{		
		$DbExt=new DbExt; 
		$stmt="
		SELECT merchant_type FROM
		{{merchant}}
		WHERE
		merchant_id=".self::q($merchant_id)."
		LIMIT 0,1
		";		
		if ( $res=$DbExt->rst($stmt)){
			$res=$res[0];
			unset($DbExt);
			return $res['merchant_type'];
		}
		unset($DbExt);
		return false;
	}	
	
	public static function isMerchantPaymentToUseAdmin($merchant_id='')
	{
		if ($merchant_type=self::getMerchantMembershipType($merchant_id)){			
			if ($merchant_type==2){
				return true;
			}
		}
		return false;
	}
	
	public static function getCommissionStatusBased($formated_query=true)
	{
		$commission_status=getOptionA('total_commission_status');
		if(!empty($commission_status)){
			$commission_status=json_decode($commission_status,true);			
			if($formated_query){
				$_stats='';
				foreach ($commission_status as $stats) {
					$_stats.="'$stats',";
				}
				$_stats=substr($_stats,0,-1);
				return $_stats;
			} else return $commission_status;
		}
		return false;
	}
	
	public static function generateInvoiceToken()
	{
		$token=self::generateCode(6);
		$db=new DbExt;
		$stmt="
		SELECT invoice_token
		FROM {{invoice}}
		WHERE
		invoice_token=".self::q($token)."
		LIMIT 0,1
		";
		if ( $res=$db->rst($stmt)){
			$token=self::generateInvoiceToken();
		}
		return $token;
	}	
	
	public static function sendInvoiceNotification($merchant_id='', $data='', $pdf_filename='')
	{
		$lang=Yii::app()->language;
		$sender=getOptionA('global_admin_sender_email');
						
		$merchant_email= getOption($merchant_id,'merchant_invoice_email');
		$is_enabled=getOptionA("merchant_invoice_email");
		if ($is_enabled==true && !empty($merchant_email)){
			$tpl=getOptionA("merchant_invoice_tpl_content_$lang");
			$subject=getOptionA("merchant_invoice_tpl_subject_$lang");
			
			
			$data['invoice_terms']=self::prettyInvoiceTerms($data['invoice_terms']);
			$data['period']= Yii::t("default",'[from] to [to]',array(
			  '[from]'=>FunctionsV3::prettyDate($data['date_from']),
			  '[to]'=>FunctionsV3::prettyDate($data['date_to']),
			));
			
			$data['invoice_link']=websiteUrl()."/upload/invoice/".$pdf_filename;
			
			$pattern=array(
			  'restaurant_name'=>'merchant_name',
			  'invoice_number'=>'invoice_number',
			  'terms'=>'invoice_terms',
			  'period'=>'period',
			  'invoice_link'=>'invoice_link',
			  'sitename'=>getOptionA('website_title'),
    	      'siteurl'=>websiteUrl(),	 		    	
			);
			$tpl=FunctionsV3::replaceTemplateTags($tpl,$pattern,$data);
    	    $subject=FunctionsV3::replaceTemplateTags($subject,$pattern,$data);
			
    	    $DbExt=new DbExt();
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
			self::runCronEmail();
    	    
		}			
	}
	
    public static function merchantList($as_list=true,$with_select=false)
    {
    	$data=array();
    	$DbExt=new DbExt;
    	$stmt="SELECT * FROM
    	{{merchant}}
    	WHERE status in ('active')
    	ORDER BY restaurant_name ASC
    	";
    	if ( $with_select){
    		$data['']=t("All restaurant");
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
    
    public static function prettyPaymentTypeTrans($transaction_type='', $payment_code='')
    {    	
    	$payment_list=FunctionsV3::PaymentOptionList();
    	if (array_key_exists($payment_code,(array)$payment_list)){
    		switch ($transaction_type) {
    			case "dinein":
    				if ( $payment_code=="cod"){
    					return t("Pay in person");
    				} else return $payment_list[$payment_code];
    				break;
    		
    			case "pickup":
    				if($payment_code=="cod"){
    				   return t("Cash On Pickup");
    				} else return $payment_list[$payment_code];
    				break; 	
    				
    			default:
    				return $payment_list[$payment_code];
    				break;
    		}
    	}
    	return $payment_code;
    }
    
	public static function getInvoiceByToken($token='')
	{		
		$db=new DbExt;
		$stmt="
		SELECT 
		invoice_number,
		invoice_token,
		viewed,
		pdf_filename
		FROM {{invoice}}
		WHERE
		invoice_token=".self::q($token)."
		LIMIT 0,1
		";
		if ( $res=$db->rst($stmt)){
			unset($db);
			return $res[0];
		}
		unset($db);
		return false;
	}	    
	
	public static function getInvoiceByID($invoice_id='')
	{		
		$db=new DbExt;
		$stmt="
		SELECT 
		invoice_number,
		invoice_token,
		viewed,
		pdf_filename,
		status,
		payment_status
			
		FROM {{invoice}}
		WHERE
		invoice_number=".self::q($invoice_id)."
		LIMIT 0,1
		";
		if ( $res=$db->rst($stmt)){
			unset($db);
			return $res[0];
		}
		unset($db);
		return false;
	}	    	
	
	public static function getInvoiceHistory($invoice_id='')
	{		
		$db=new DbExt;
		$stmt="
		SELECT 
		a.*
		FROM {{invoice_history}} a
		WHERE
		invoice_number=".self::q($invoice_id)."
		ORDER BY id DESC	
		";
		if ( $res=$db->rst($stmt)){
			unset($db);
			return $res;
		}
		unset($db);
		return false;
	}	    	
	
	
	public static function invoicePaymentStatusList()
	{
		return array(
		  'unpaid'=>t("unpaid"),
		  'paid'=>t("paid"),
		  'pending'=>t("pending"),
		  'denied'=>t("denied"),		  
		);
	}
	
	public static function dateDifference($start ='', $end ='' )
	{
		return Yii::app()->functions->dateDifference($start,$end);
	}
	
	public static function getMerchantPaymentListNew($merchant_id='')
	{
		$payment_available=array(); 
		$payment_available_final=array();
		$payment_list = self::PaymentOptionList();
		$merchant_type=self::getMerchantMembershipType($merchant_id);		
	    //dump($merchant_type);
		switch ($merchant_type) {			
			case 2:						
			    /*COMMISSION*/	
				$payment_available=Yii::app()->functions->getMerchantListOfPaymentGateway();					
				//dump($payment_available);
				if(is_array($payment_available) && count($payment_available)>=1){
				   foreach ($payment_available as $payment_key) {				   	   
				   	   $master_key="merchant_switch_master_$payment_key";
		    		   $master_key_val=getOption($merchant_id,$master_key);
		    		   if($master_key_val!=1){
		    		   			    		   	
		    		   	  if(isset($payment_list[$payment_key])){
		    		   	     $payment_available_final[$payment_key]=$payment_list[$payment_key];
		    		   	  }		    		   	  
		    		   	  
		    		   	  if ($payment_key=="pyr"){
		    		   	  	  $provider_list=Yii::app()->functions->getPaymentProviderMerchant($merchant_id);
		    		   	  	  $merchant_payondeliver_enabled=getOption($merchant_id,'merchant_payondeliver_enabled');
		    		   	  	  if($merchant_payondeliver_enabled==""){
					             $provider_list=array(); 					             
				              }				              
				              if (!is_array($provider_list) && count((array)$provider_list)<=1){				
				                  unset($payment_available_final['pyr']);
			                  } 	              
		    		   	  }		    		   		    		   	  
		    		   	  
		    		   }			    					    		   
				   }
				}		
				break;
		
			default:
				/*MEMBERSHIP IS DEFAULT*/			
				if ( getOption($merchant_id,'merchant_disabled_cod')==""){
					$payment_available[]='cod';
				}
				if ( getOption($merchant_id,'merchant_disabled_ccr')==""){
					$payment_available[]='ocr';
				}
				if ( getOption($merchant_id,'enabled_paypal')=="yes"){
					$payment_available[]='pyp';
				}
				if ( getOption($merchant_id,'stripe_enabled')=="yes"){
					$payment_available[]='stp';
				}
				if ( getOption($merchant_id,'merchant_mercado_enabled')=="yes"){
					$payment_available[]='mcd';
				}
				if ( getOption($merchant_id,'merchant_sisow_enabled')=="yes"){
					$payment_available[]='ide';
				}
				if ( getOption($merchant_id,'merchant_payu_enabled')=="yes"){
					$payment_available[]='payu';
				}
				if ( getOption($merchant_id,'merchant_paysera_enabled')=="yes"){
					$payment_available[]='pys';
				}
				if ( getOption($merchant_id,'merchant_payondeliver_enabled')=="yes"){
					$payment_available[]='pyr';
				}
				if ( getOption($merchant_id,'merchant_enabled_barclay')=="yes"){
					$payment_available[]='bcy';
				}
				if ( getOption($merchant_id,'merchant_enabled_epaybg')=="yes"){
					$payment_available[]='epy';
				}
				if ( getOption($merchant_id,'merchant_enabled_autho')=="yes"){
					$payment_available[]='atz';
				}
				if ( getOption($merchant_id,'merchant_bankdeposit_enabled')=="yes"){
					$payment_available[]='obd';
				}		
				/*if ( getOption($merchant_id,'merchant_mol_enabled')=="2"){
					$payment_available[]='mol';
				}*/		
				if (getOption($merchant_id,'merchant_btr_enabled')==2){
		    		$payment_available[]='btr';
		    	}		    	
		    	if (getOption($merchant_id,'merchant_rzr_enabled')==2){
		    		$payment_available[]='rzr';
		    	}		    			    			    
		    	if (getOption($merchant_id,'merchant_vog_enabled')==2){
		    		$payment_available[]='vog';
		    	}
		    	if (getOption($merchant_id,'merchant_ipay_enabled')==2){
		    		$payment_available[]='ipay';
		    	}
		    	if (getOption($merchant_id,'merchant_pipay_enabled')==2){
		    		$payment_available[]='pipay';
		    	}
		    	if (getOption($merchant_id,'merchant_hubtel_enabled')==2){
		    		$payment_available[]='hubtel';
		    	}
		    	if (getOption($merchant_id,'merchant_sofort_enabled')==2){		    		
		    		$payment_available[]='sofort';
		    	}		    	
		    	if (getOption($merchant_id,'merchant_jampie_enabled')==2){		    		
		    		$payment_available[]='jampie';
		    	}
		    	if (getOption($merchant_id,'merchant_wing_enabled')==2){		    		
		    		$payment_available[]='wing';
		    	}
		    	if (getOption($merchant_id,'merchant_paymill_enabled')==2){		    		
		    		$payment_available[]='paymill';
		    	}
		    	if (getOption($merchant_id,'merchant_stripe_ideal_enabled')==1){		    		
		    		$payment_available[]='strip_ideal';
		    	}
		    	if (getOption($merchant_id,'merchant_ipay_africa_enabled')==1){		    		
		    		$payment_available[]='ipay_africa';
		    	}
		    	if (getOption($merchant_id,'merchant_dixipay_enabled')==1){		    		
		    		$payment_available[]='dixipay';
		    	}
		    	if (getOption($merchant_id,'merchant_wirecard_enabled')==1){		    		
		    		$payment_available[]='wirecard';
		    	}
		    	if (getOption($merchant_id,'merchant_payulatam_enabled')==1){		    		
		    		$payment_available[]='payulatam';
		    	}
		    	if (getOption($merchant_id,'merchant_paystack_enabled')==1){		    		
		    		$payment_available[]='paystack';
		    	}		    			    	
		    	if (getOption($merchant_id,'merchant_moneris_enabled')==2){
		    		$payment_available[]=Moneris::getPaymentCode();
		    	}
		    	if (getOption($merchant_id,'merchant_paypal_v2_enabled')==1){
		    		$payment_available[]=PaypalWrapper::paymentCode();
		    	}		    	
		    	if (getOption($merchant_id,'merchant_mercadopago_v2_enabled')==1){		    		
		    		$payment_available[]='mercadopago';
		    	}
		    			    	
		    	$enabled_payment=Yii::app()->functions->getMerchantListOfPaymentGateway();	    	
		    	//dump($enabled_payment);
		    	if(is_array($payment_available) && count($payment_available)>=1){
			    	foreach ($payment_available as $pm_val) {
			    		if(in_array($pm_val,(array)$enabled_payment)){
			    		   $master_key="merchant_switch_master_$pm_val";
			    		   $master_key_val=getOption($merchant_id,$master_key);
			    		   if($master_key_val!=1){
			    		   	
			    		   	  if(isset($payment_list[$pm_val])){
			    		   	     $payment_available_final[$pm_val]=$payment_list[$pm_val];
			    		   	  }
			    		   	  
			    		   	  if ($pm_val=="pyr"){
			    		   	  	 $provider_list=Yii::app()->functions->getPaymentProviderMerchant($merchant_id);
			    		   	  	 if (!is_array($provider_list) && count($provider_list)<=1){				
				                      unset($payment_available_final['pyr']);
			                     } 	              
			    		   	  }
			    		   	  
			    		   }			    					    		   
			    		}
			    	}
		    	}
				break;
		}
		
		//dump($payment_available_final);
		return $payment_available_final;		
	}
	
	public static function deleteUploadedFile($filename='', $folder='')
	{
		$path=self::uploadPath();
		if(!empty($folder)){
			$path.="/$folder";
		}	
		if(!empty($filename)){
			$file_to_delete = $path."/$filename";
			if(file_exists($file_to_delete)){
				@unlink($file_to_delete);
			}
		}
	}
	
	public static function getAddonItem($sub_item_id='')
    {
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{subcategory_item}}
			WHERE
			sub_item_id=".self::q($sub_item_id)."
			LIMIT 0,1
		";			    
		if ( $res=$DbExt->rst($stmt)){			
			return $res[0];
		}
		return false;
    }
    
    public static function GetVogueCredentials($merchant_id='')
    {
    	$enabled=false; $mtid='';
    	if (self::isMerchantPaymentToUseAdmin($merchant_id)){
    		// USER ADMIN SETTINGS
    		$enabled=getOptionA('admin_vog_enabled');
    		$mtid=getOptionA('admin_vog_merchant_id');
    	} else {
    		// USE MERCHANT SETTINGS    		
    		$enabled=getOption($merchant_id,'merchant_vog_enabled');
    		$mtid=getOption($merchant_id,'merchant_vog_merchant_id');
    	}    	
    	if($enabled==2){
    		return array(
    		   'enabled'=>$enabled,
    		   'merchant_id'=>$mtid
    		);
    	}
    	return false;
    }
    
    public static function GetVogueAdminCredentials()
    {
    	$enabled=false; $mtid='';
    	$enabled=getOptionA('admin_vog_enabled');
        $mtid=getOptionA('admin_vog_merchant_id');
        if($enabled==2 && !empty($mtid)){
    		return array(
    		   'enabled'=>$enabled,
    		   'merchant_id'=>$mtid
    		);
    	}
    	return false;
    }
    
    public static function lastID($table='')
	{
		$DbExt=new DbExt;
		$stmt="
		SHOW TABLE status WHERE name='{{{$table}}}'; 
		";		
		if ($res=$DbExt->rst($stmt)){
			return $res[0]['Auto_increment'];
		}
		return false;
	}
	
   public static function getBookingByID($booking_id='')
   {
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{bookingtable}}
   	   WHERE
   	   booking_id=".self::q($booking_id)."
   	   LIMIT 0,1
   	   ";
   	   if ($res=$DbExt->rst($stmt)){
   	   	  return $res[0];
   	   }
   	   return false;
   }	
   
   public static function reviewReplyList($parent_id='' , $status='')
   {
   	
   	   $and='';
   	   if(!empty($status)){
   	   	 $and=" AND status = ".FunctionsV3::q($status)." ";
   	   }   
   	
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{review}}
   	   WHERE
   	   parent_id=".self::q($parent_id)."
   	   $and
   	   ORDER BY id ASC
   	   LIMIT 0,10
   	   ";   	   
   	   if ($res=$DbExt->rst($stmt)){
   	   	  return $res;
   	   }
   	   return false;
   }      
   
   public static function getEnabledLanguageList()
   {
   	    $data[0]=t("Please select");
   	    $list=self::getEnabledLanguage();
   	    if(is_array($list) && count($list)>=1){
   	    	foreach ($list as $val) {
   	    		$data[$val]=$val;
   	    	}
   	    }   	    
   	    return $data;
   }   
   
    public static function MerchantpushNewOrder($order_id='')
    {
    	
    	$lang=Yii::app()->language;	
    	
    	$enabled = getOptionA('receipt_send_to_merchant_push');
    	if ($enabled!=1){
    		return ;
    	}
    	   
    	if ( $res=Yii::app()->functions->getOrder($order_id)){
    		
    		if ($res['status']=="initial_order"){
    			return ;
    		}
    		
    		$merchant_id=$res['merchant_id'];
    		$client_id=$res['client_id'];    	
    		
    		$db_ext=new DbExt;
    		$stmt="
    		SELECT * FROM
    		{{mobile_device_merchant}}
    		WHERE
    		merchant_id =".self::q($merchant_id)."
    		AND
    		enabled_push ='1'
    		AND
    		status ='active'
    		
    		ORDER BY id ASC
    		LIMIT 0,50
    		";
    		    		
    		if ( $device=$db_ext->rst($stmt)){
    			    			
    			$title=''; $content='';
    		    			
    			$title = getOptionA('receipt_send_to_merchant_push_title_'.$lang);
    			$content = getOptionA('receipt_send_to_merchant_push_content_'.$lang);
    			
    			$pattern=array(
    			   'order_id'=>'order_id',
		    	   'customer_name'=>'full_name',		    	   
		    	   'restaurant_name'=>'merchant_name',
		    	   'total_amount'=>'total_w_tax',
		    	   'sitename'=>getOptionA('website_title'),
		    	   'siteurl'=>websiteUrl(),	    	   		    	  
		    	);
		    	
		    	$title=FunctionsV3::replaceTemplateTags($title,$pattern,$res);
		    	$content=FunctionsV3::replaceTemplateTags($content,$pattern,$res);
	    	    			
    			foreach ($device as $val) {    				
    				$params=array(
    				  'merchant_id'=>$val['merchant_id'],
    				  'user_type'=>$val['user_type'],
    				  'merchant_user_id'=>$val['merchant_user_id'],
    				  'device_platform'=>$val['device_platform'],
    				  'device_id'=>$val['device_id'],
    				  'push_title'=>$title,
    				  'push_message'=>$content,
    				  'date_created'=>FunctionsV3::dateNow(),
    				  'ip_address'=>$_SERVER['REMOTE_ADDR'],
    				  'order_id'=>$order_id
    				);    				
    				$db_ext->insertData('{{mobile_merchant_pushlogs}}',$params);
    			}
    		} //else echo 'no records';
    	}    	
    }           
    
    public static function GetIpayCredentials($merchant_id='')
    {
    	$enabled=false; $mtid='';
    	if (self::isMerchantPaymentToUseAdmin($merchant_id)){
    		// USER ADMIN SETTINGS
    		$enabled=getOptionA('admin_ipay_enabled');
    		$mtid=getOptionA('admin_ipay_merchant_key');
    	} else {
    		// USE MERCHANT SETTINGS    		
    		$enabled=getOption($merchant_id,'merchant_ipay_enabled');
    		$mtid=getOption($merchant_id,'merchant_ipay_merchant_key');
    	}    	
    	if($enabled==2){
    		return array(
    		   'enabled'=>$enabled,
    		   'merchant_key'=>$mtid
    		);
    	}
    	return false;
    }
    
    public static function GetIpayCredentialsAdmin()
    {
    	// USER ADMIN SETTINGS
		$enabled=getOptionA('admin_ipay_enabled');
		$mtid=getOptionA('admin_ipay_merchant_key');
    		
    	if($enabled==2){
    		return array(
    		   'enabled'=>$enabled,
    		   'merchant_key'=>$mtid
    		);
    	}
    	return false;
    }
    
   public static function getOrderPaymentRef($payment_ref='')
   {
   	   	  
   	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT * FROM
   	   {{payment_order}}
   	   WHERE
   	   payment_reference =".self::q($payment_ref)."
   	   LIMIT 0,1
   	   ";   	   
   	   if ($res=$DbExt->rst($stmt)){
   	   	  return $res;
   	   }
   	   return false;
   }      
   
   public static function enabledExtraCharges()
   {
   	  return false;
   }
   
   public static function extraDeliveryFee($mtid='', $current_fee='', $delivery_time='' , $delivery_date='')
   {
   	   	   
   	   $debug = false;
   	   $fee =  $current_fee;
   	   
   	   if(empty($delivery_date)){
   	   	  $delivery_date=date("Y-m-d");
   	   }  
   	   
   	   if(empty($delivery_time)){
   	   	  $delivery_time = date("Gi");
   	   } else $delivery_time = date("Gi",strtotime($delivery_time));
   	   
   	   if ($debug){
   	   	  dump("mtid=> $mtid");
   	   	  dump("current_fee=> $current_fee");
   	   	  dump("delivery_time=> $delivery_time");
   	   	  dump("delivery_date=> $delivery_date");
   	   }
   	   
   	   $extra_charge_start_time=getOption($mtid,'extra_charge_start_time');
   	   $extra_charge_end_time='';
   	   if(!empty($extra_charge_start_time)){
   	   	  $extra_charge_start_time = json_decode($extra_charge_start_time,true);   	   	  
   	   	  $extra_charge_end_time = json_decode( getOption($mtid,'extra_charge_end_time') ,true );
   	   	  $extra_charge_fee = json_decode( getOption($mtid,'extra_charge_fee') ,true );
   	   }   
   	   
   	   if(is_array($extra_charge_start_time) && count($extra_charge_start_time)>=1){   	   	 
   	   	  foreach ($extra_charge_start_time as $key=>$start) {
   	   	  	  $start = date("Gi",strtotime($start));
   	   	  	  if(isset($extra_charge_end_time[$key])){
   	   	  	      $end  = date("Gi",strtotime($extra_charge_end_time[$key]));
   	   	  	  } else $end='';
   	   	  	  
   	   	  	  if(isset($extra_charge_fee[$key])){
   	   	  	     $charge = $extra_charge_fee[$key];
   	   	  	  } else $charge='';
   	   	  	  
   	   	  	  if (!empty($charge) && !empty($start) && !empty($end) ){  
   	   	  	  	 if ( $start<=$delivery_time && $end>=$delivery_time){   	   	   	   
	   	   	   	     $fee+=$charge;
	   	   	   	     if ($debug){
	   	   	   	        dump("TIME=> $start - $end = $charge");
	   	   	   	     }
	   	   	   	     break;
	   	   	     }
   	   	  	  }
   	   	  }
   	   }   
   	   
   	   if ($debug){
   	   	  dump("final fee: $fee");
   	   }
   	      	      	   
   	   return $fee;
   }
   
   public static function validateVoucherByAddress($voucher_code='', $street='', $city='', $state='')
   {
   	   if(empty($street)){
   	   	  return false;
   	   }   
   	   if(empty($city)){
   	   	  return false;
   	   }   
   	   if(!empty($voucher_code)){
	   	   $DbExt=new DbExt;
	   	   $stmt="SELECT  a.order_id, a.voucher_code
	   	   FROM
	   	   {{order}} a   	   
	   	   WHERE
	   	   a.voucher_code = ".self::q(trim($voucher_code))."
	   	   AND
	   	   a.order_id IN (
	   	     select order_id
	   	     from
	   	     {{order_delivery_address}}
	   	     where
	   	     street=".self::q(trim($street))."
	   	     and
	   	     city=".self::q(trim($city))."
	   	     and
	   	     state=".self::q(trim($state))." 
	   	   )
	   	   LIMIT 0,1
	   	   ";	   	   
	   	   if ($DbExt->rst($stmt)){
	   	   	   return true;
	   	   } 
   	   }
   	   return false;
   	   
   }
   
    public static function getOffersByMerchantNew($merchant_id='')
    {
    	$DbExt=new DbExt; 
    	$offer_list = array(); 
    	$offer = '';
    	
	    $stmt="SELECT * FROM
			{{offers}}
			WHERE
			status in ('publish','published')
			AND
			now() >= valid_from and now() <= valid_to
			AND merchant_id =".self::q($merchant_id)."
			ORDER BY valid_from ASC
		";	    
		if ( $res=$DbExt->rst($stmt)){
			foreach ($res as $val) {				
				$applicable_to_list = '';
				if(isset($val['applicable_to'])){
    			   $applicable_to=json_decode($val['applicable_to'],true);	
    			   if(is_array($applicable_to) && count($applicable_to)>=1){
    			   	  foreach ($applicable_to as $applicable_to_val) {    			   	  	 
    			   	  	 $applicable_to_list.=t($applicable_to_val).",";
    			   	  }
    			   	  $applicable_to_list = substr($applicable_to_list,0,-1);
    			   }    			
    			}    		 
    			if (!empty($applicable_to_list)){
    				$offer=number_format($val['offer_percentage'],0)."% ".t("Off over");
	    			$offer.=" ".self::prettyPrice($val['offer_price']);
	    			$offer.=" ".t("if")." ".$applicable_to_list;
    			} else {
	    			$offer=number_format($val['offer_percentage'],0)."% ".t("Off over");
	    			$offer.=" ".self::prettyPrice($val['offer_price']);
    			}
    			$offer_list[] =$offer;
			}
			return $offer_list;
		}
		return false;
    }   
    
    public static function getMerchantOffersActive($merchant_id='',$transaction_type='delivery')
    {    	
    	$trans_type='';
    	$trans_type = "$transaction_type";
    	
    	$date_now=date('Y-m-d');
    	$DbExt=new DbExt;
	    $stmt="SELECT * FROM
			{{offers}}
			WHERE
			status in ('publish','published')
			AND
			now() >= valid_from and now() <= valid_to
			AND merchant_id =".FunctionsV3::q($merchant_id)."
			AND applicable_to LIKE ".FunctionsV3::q("%$trans_type%")."
			ORDER BY valid_from ASC			
			LIMIT 0,1
		";	    
	    //dump($stmt);
		if ( $res=$DbExt->rst($stmt)){			
			return $res[0];
		}
		return false;
    }
    
     public static function GetJampieCredentials($merchant_id='')
    {
    	$enabled=false; $mtid='';
    	if (self::isMerchantPaymentToUseAdmin($merchant_id)){
    		// USER ADMIN SETTINGS
    		$enabled=getOptionA('admin_jampie_enabled');
    		$email=getOptionA('admin_jampie_email');
    	} else {
    		// USE MERCHANT SETTINGS    		
    		$enabled=getOption($merchant_id,'merchant_jampie_enabled');
    		$email=getOption($merchant_id,'merchant_jampie_email');
    	}    	
    	if($enabled==2){
    		return array(
    		   'enabled'=>$enabled,
    		   'business_email'=>$email
    		);
    	}
    	return false;
    }
    
    public static function isReceiptCalculationMethodTwo()
    {
    	$receipt_computation_method=getOptionA('receipt_computation_method');
    	if($receipt_computation_method==2){
    		return true;
    	}    
    	return false;
    }
    
    public static function getReceiptCalculationMethod()
    {
    	$receipt_computation_method=getOptionA('receipt_computation_method');
    	if(empty($receipt_computation_method)){
    		$receipt_computation_method=1;
    	}    
    	return $receipt_computation_method;
    }

    public static function getClientName($full_name = false)
    {    	
    	if (isset($_SESSION['kr_client'])){
    		if (array_key_exists('client_id',$_SESSION['kr_client'])){    			
    			if (is_numeric($_SESSION['kr_client']['client_id'])){    
    				if($full_name){
    					return $_SESSION['kr_client']['first_name']." ".$_SESSION['kr_client']['last_name'];
    				} else return $_SESSION['kr_client']['first_name'];    				
    			}
    		}    	
    	}
    	return false;
    }
    
    public static function getClientEmail()
    {    	
    	if (isset($_SESSION['kr_client'])){
    		if (array_key_exists('client_id',$_SESSION['kr_client'])){    			
    			if (is_numeric($_SESSION['kr_client']['client_id'])){    
    				if(isset($_SESSION['kr_client']['email_address'])){
    				   return $_SESSION['kr_client']['email_address']; 
    				}
    			}
    		}    	
    	}
    	return false;
    }
    
    public static function getClientPhoneNumber()
    {    	
    	if (isset($_SESSION['kr_client'])){
    		if (array_key_exists('client_id',$_SESSION['kr_client'])){    			
    			if (is_numeric($_SESSION['kr_client']['client_id'])){    
    				if(isset($_SESSION['kr_client']['contact_phone'])){
    				   return $_SESSION['kr_client']['contact_phone']; 
    				}
    			}
    		}    	
    	}
    	return false;
    }
    
	public static function getOrderByToken($order_id_token='')
	{
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
		{{client}}
		where
		client_id=a.client_id
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
		) as credit_card_number		
		
		 FROM
		{{order}} a
		WHERE
		order_id_token=".self::q($order_id_token)."
		LIMIT 0,1
		";		
		$connection=Yii::app()->db;
		$rows=$connection->createCommand($stmt)->queryAll(); 		
		if (is_array($rows) && count($rows)>=1){
			return $rows[0];
		}
		return FALSE;
	}	        	    
	
	public static function getCategoryInItem($category_id='')
	{		
		
		//$category_id= "'". '%"'.$category_id.'"%' ."'";		
		$category_id= FunctionsV3::q('%"'.$category_id.'"%');
		$DbExt=new DbExt;
		$stmt="
		SELECT category 
		FROM 
		{{item}}
		WHERE
		category LIKE ".($category_id)."
		LIMIT 0,1
		";				
		if ($res=$DbExt->rst($stmt)){		
			return $res;
		}		
		return false;
	}
	
	public static function getSizeInItem($size_id='',$mtid='')
	{		
		//$q= "'". '%"'.$size_id.'":%' ."'";
		$q = FunctionsV3::q('%"'.$size_id.'"%');	
		$DbExt=new DbExt;
		$stmt="
		SELECT item_id,price 
		FROM 
		{{item}}
		WHERE
		price LIKE ".($q)."
		AND
		merchant_id=".self::q($mtid)."
		LIMIT 0,1
		";				
		if ($res=$DbExt->rst($stmt)){		
			return $res;
		}		
		return false;
	}	
	
	public static function getDishInItem($dish_id='')
	{		
		//$q = "'". '%"'.$dish_id.'"%' ."'";
		$q= FunctionsV3::q('%"'.$dish_id.'"%');	
		$DbExt=new DbExt;
		$stmt="
		SELECT category 
		FROM 
		{{item}}
		WHERE
		dish LIKE ".($q)."
		LIMIT 0,1
		";				
		if ($res=$DbExt->rst($stmt)){				
			return $res;
		}		
		return false;
	}	
	
	public static function getMerchantByCuisine($cuisine_id='')
	{
		//$q = "'". '%"'.$cuisine_id.'"%' ."'";
		$q= FunctionsV3::q('%"'.$cuisine_id.'"%');	
		$DbExt=new DbExt;
		$stmt="
		SELECT cuisine 
		FROM 
		{{merchant}}
		WHERE
		cuisine LIKE ".($q)."
		LIMIT 0,1
		";		
		//dump($stmt);		
		if ($res=$DbExt->rst($stmt)){				
			return $res;
		}		
		return false;
	}
	
	public static function getDishIcon($dishs='')
	{
		$data = array();
		$dish = json_decode($dishs,true);
		if(is_array($dish) && count($dish)>=1){
		   foreach ($dish as $id) {
		   	  if($res=Yii::app()->functions->GetDish($id)){		   	  	 
		   	  	 $data[]=websiteUrl()."/upload/".$res['photo'];
		   	  }
		   }
		   return $data;
		}
		return false;		
	}
	
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
    
	public static function getMerchantPackageByID($package_id='')
	{
		$DbExt=new DbExt;
		$stmt="
		SELECT package_id
		FROM
		{{merchant}}
		WHERE
		package_id = ".self::q($package_id)."
		LIMIT 0,1
		";		
		if ($res=$DbExt->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;    
	}
	
	public static function getImage($image='', $show_default = false)
	{	
		$url='';			
		$path_to_upload=Yii::getPathOfAlias('webroot')."/upload/";				
		
		if (!empty($image)){			
			if (file_exists($path_to_upload."/$image")){														
				$url = Yii::app()->getBaseUrl(true)."/upload/$image";
			}
		} 
		
		if($show_default==TRUE){
		   if(empty($url)){
		   	  $url = Yii::app()->getBaseUrl(true)."/assets/images/default-image-merchant.png";
		   }
		}
				
		return $url;
	}	
	
	public static function cancelOrderCheckDays($date_created='')
	{
		$cancel_order_days_applied = getOptionA('cancel_order_days_applied');
		if($cancel_order_days_applied>0){			
			$date_now=date('Y-m-d g:i:s a');
			$date_created = date('Y-m-d g:i:s a',strtotime($date_created));
			$time_diff=Yii::app()->functions->dateDifference($date_created,$date_now);						
			if(is_array($time_diff) && count($time_diff)>=1){
				if($time_diff['days']>=$cancel_order_days_applied){
					return false;
				}
			}
		} else return false;
			
		return true;
	}
	
	public static function cancelOrderCheckStatus($order_status='',$order_locked='')
	{
		if($order_locked==2){
			return false;
		}	
		$status = getOptionA('cancel_order_status_accepted');
		if(!empty($status)){					
			$status = json_decode($status,true);			
			if(in_array($order_status,(array)$status)){
				return true;
			}
		}
		return false;	
	}
	
	public static function canCancelOrder($request_cancel='',$date_created='',
	$order_status='',$order_locked='', $request_cancel_status='pending')
	{
				
		if($request_cancel==1){
		   return true;	
		}
		
		if($request_cancel_status!='pending'){
			return false;
		}	
		
		$cancel_order_enabled = getOptionA('cancel_order_enabled');
		if($cancel_order_enabled==1){
			$check_0 = true;
		} else $check_0 = false;
		
		$check_1 = FunctionsV3::cancelOrderCheckDays($date_created);		
		$check_2 = FunctionsV3::cancelOrderCheckStatus($order_status);
		if($check_0==TRUE && $check_1==TRUE && $check_2==TRUE){
			return true;
		}
		return false;
	}
	
	public static function getOrderInfoByToken($order_id_token='')
	{
		if(empty($order_id_token)){
			return false;
		}
		$db = new DbExt();
		$stmt="SELECT * FROM
		{{order}}
		WHERE
		order_id_token = ".FunctionsV3::q($order_id_token)."
		LIMIT 0,1
		";
		if($res = $db->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
	public static function notifyCancelOrder($data=array())
	{
		if(!is_array($data) && count($data)<=0){
			return false;
		}
		
		$merchant_id = isset($data['merchant_id'])?$data['merchant_id']:'';
	
		$lang=Yii::app()->language;
				
		$tpl_email_enabled = getOptionA('order_request_cancel_to_merchant_email');
		$tpl_sms_enabled = getOptionA('order_request_cancel_to_merchant_sms');
						
		
		$pattern=array(		    	   	   	   
    	   'restaurant_name'=>'merchant_name',
    	   'customer_name'=>'full_name',
    	   'order_id'=>'order_id',
    	   'login_url'=>'login_url',	    	   
    	   'sitename'=>getOptionA('website_title'),
    	   'siteurl'=>websiteUrl(),		   
    	);
    	
    	$total_amount = FunctionsV3::prettyPrice($data['total_w_tax']);
    		
		/*EMAIL*/
		if($tpl_email_enabled==1){
			$email_subject = getOptionA("order_request_cancel_to_merchant_tpl_subject_$lang");
			$email_content = getOptionA("order_request_cancel_to_merchant_tpl_content_$lang");
				    	
	    	$email_content=FunctionsV3::replaceTemplateTags($email_content,$pattern,$data);
	    	$email_content = self::smarty('total_order_amount',$total_amount,$email_content);
	    	
	    	$email_subject=FunctionsV3::replaceTemplateTags($email_subject,$pattern,$data);
	    	$email_subject = self::smarty('total_order_amount',$total_amount,$email_subject);
	    	
	    	if(is_numeric($merchant_id)){
	    		$email = getOption($merchant_id,'merchant_cancel_order_email');
	    		$emails = explode(',',$email);
	    		if(is_array($emails) && count($emails)>=1){
	    			foreach ($emails as $email_val) {
	    				sendEmail($email_val,'',$email_subject,$email_content);
	    			}
	    		}
	    	}		
	    		    		    	
		}
		
		/*SMS*/
		if($tpl_sms_enabled==1){
			$sms_content = getOptionA("order_request_cancel_to_merchant_sms_content_$lang");
			$sms_content=FunctionsV3::replaceTemplateTags($sms_content,$pattern,$data);
	    	$sms_content = self::smarty('total_order_amount',$total_amount,$sms_content);		
	    	
	    	$phone = getOption($merchant_id,'merchant_cancel_order_phone');
	    	$phones = explode(",",$phone);
	    	if(is_array($phones) && count($phones)>=1){
	    		foreach ($phones as $phone_val) {	    			
	    			Yii::app()->functions->sendSMS($phone_val,$sms_content);
	    		}
	    	}
		}		
				
		/*PUSH*/
		if (FunctionsV3::hasModuleAddon('merchantapp')){
			$push_enabled = getOptionA('order_request_cancel_to_merchant_push');
			if($push_enabled==1){
				$push_tpl_title = getOptionA("order_request_cancel_to_merchant_push_title_$lang");
				$push_tpl = getOptionA("order_request_cancel_to_merchant_push_content_$lang");			
				
				$push_tpl_title=FunctionsV3::replaceTemplateTags($push_tpl_title,$pattern,$data);
	    	    $push_tpl_title = self::smarty('total_order_amount',$total_amount,$push_tpl_title);
	    	    
	    	    $push_tpl=FunctionsV3::replaceTemplateTags($push_tpl,$pattern,$data);
	    	    $push_tpl = self::smarty('total_order_amount',$total_amount,$push_tpl);
				
				$db=new DbExt();
				$stmt="
				SELECT * FROM
				{{mobile_device_merchant}}
				WHERE
				merchant_id=".self::q($merchant_id)."
				AND status='active'
				LIMIT 0,20				
				";	
				if($res=$db->rst($stmt)){
					foreach ($res as $val) {
						$params=array(
						  'merchant_id'=>$val['merchant_id'],
						  'user_type'=>$val['user_type'],
						  'merchant_user_id'=>$val['merchant_user_id'],
						  'device_platform'=>$val['device_platform'],
						  'device_id'=>$val['device_id'],
						  'push_title'=>$push_tpl_title,
						  'push_message'=>$push_tpl,
						  'date_created'=>FunctionsV3::dateNow(),
						  'ip_address'=>$_SERVER['REMOTE_ADDR'],
						  'order_id'=>isset($data['order_id'])?$data['order_id']:0
						);
						$db->insertData("{{mobile_merchant_pushlogs}}",$params);
					}
				}			
				unset($db);
				FunctionsV3::fastRequest(FunctionsV3::getHostURL().Yii::app()->createUrl("merchantapp/cron/processpush"));
			}
		}	

		
		/*SEND NOTIFICATION TO ADMIN EMAIL*/
    	$enabled = getOptionA('order_request_cancel_to_admin_email');
    	if($enabled==1){
    		$email = getOptionA('order_cancel_admin_email');
    		$tpl =  getOptionA("order_request_cancel_to_admin_tpl_content_$lang"); 
    		$subject =  getOptionA("order_request_cancel_to_admin_tpl_subject_$lang"); 
    		
    		$tpl = FunctionsV3::replaceTemplateTags($tpl,$pattern,$data);
    		    		
    		$tpl = self::smarty('total_order_amount',$total_amount,$tpl);
    		
    		$subject = FunctionsV3::replaceTemplateTags($subject,$pattern,$data);
    		$subject = self::smarty('total_order_amount',$total_amount,$subject);
    		
    		if(!empty($email)){
    			$emails = explode(",",$email);
    			if(is_array($emails) && count($emails)>=1){
    				foreach ($emails as $emal_val) {
    					sendEmail($emal_val,'',$subject,$tpl);
    				}
    			}
    		}    	
    	}	    
    	/*END SEND NOTIFICATION TO ADMIN EMAIL*/
    	
    	/*SEND NOTIFICATION TO ADMIN SMS*/
    	$enabled = getOptionA('order_request_cancel_to_admin_sms');
    	if($enabled==1){
    		$tpl_sms =  getOptionA("order_request_cancel_to_admin_sms_content_$lang");     		
    		$tpl_sms = FunctionsV3::replaceTemplateTags($tpl_sms,$pattern,$data);    		    		
    		$tpl_sms = self::smarty('total_order_amount',$total_amount,$tpl_sms);    		
    		$order_cancel_admin_sms_phone = getOptionA('order_cancel_admin_sms');    		
    		if(!empty($order_cancel_admin_sms_phone)){
    			$phones = explode(",",$order_cancel_admin_sms_phone);
    			if(is_array($phones) && count($phones)>=1){
    				foreach ($phones as $phone_val) {
    					Yii::app()->functions->sendSMS($phone_val,$tpl_sms);
    				}
    			}
    		}
    	}	
    	/*END SEND NOTIFICATION TO ADMIN SMS*/    	    	
		
	}
	
	public static function htaccessForUpload()
	{
		$content ='<Files *>'."\n";
		$content .='SetHandler None'."\n";
		$content .='</Files>'."\n";
		
		$content .='<Files *.php>'."\n";
		$content .='deny from all'."\n";
		$content .='</Files>'."\n";
		
		$content .='<Files *.html>'."\n";
		$content .='deny from all'."\n";
		$content .='</Files>'."\n";
		
		$content .='<Files *.js>'."\n";
		$content .='deny from all'."\n";
		$content .='</Files>'."\n";
		
		$content .='<Files *.cgi>'."\n";
		$content .='deny from all'."\n";
		$content .='</Files>'."\n";
		return $content;
	}
	
	public static function imageLimitSize()
	{
		return 10485760;
	}
	
	public static function validImageExtension()
	{
		return array('jpeg', 'png' ,'jpg' , 'gif' ); 
	}
	
	public static function notifyCustomerCancelOrder($data = array(), $cancel_status='' )
	{
		if(!is_array($data) && count($data)<=0){
			return false;
		}
		
		$db = new DbExt();
		
		$merchant_id = isset($data['merchant_id'])?$data['merchant_id']:'';	
		$lang=Yii::app()->language;
		
		$tpl_email_enabled = getOptionA('order_request_cancel_to_customer_email');
		$tpl_sms_enabled = getOptionA('order_request_cancel_to_customer_sms');
		$enabled_push = getOptionA('order_request_cancel_to_customer_push');
		
		$customer_email = isset($data['email_address'])?$data['email_address']:'';
		
		$data['request_status']=t($cancel_status);
		$data['total_order_amount']=FunctionsV3::prettyPrice($data['total_w_tax']);
		
		$pattern=array(		    	   	   	   
    	   'restaurant_name'=>'merchant_name',
    	   'customer_name'=>'full_name',
    	   'order_id'=>'order_id',
    	   'login_url'=>'login_url',	    	   
    	   'sitename'=>getOptionA('website_title'),
    	   'siteurl'=>websiteUrl(),	 	
    	   'request_status'=>'request_status',
    	   'total_order_amount'=>'total_order_amount'
    	);
		
    	/*EMAIL*/
		if($tpl_email_enabled==1  && !empty($customer_email)){
			$email_subject = getOptionA("order_request_cancel_to_customer_tpl_subject_$lang");
			$email_content = getOptionA("order_request_cancel_to_customer_tpl_content_$lang");
			
	    	$email_subject=FunctionsV3::replaceTemplateTags($email_subject,$pattern,$data);
	    	$email_content=FunctionsV3::replaceTemplateTags($email_content,$pattern,$data);
	    		    	
	    	sendEmail($customer_email,'',$email_subject,$email_content);			
		}		
		
		/*SMS*/
		$contact_phone = isset($data['contact_phone'])?$data['contact_phone']:'';		
		if ($tpl_sms_enabled==1 && !empty($contact_phone)){
			$sms_content = getOptionA("order_request_cancel_to_customer_sms_content_$lang");
			$sms_content=FunctionsV3::replaceTemplateTags($sms_content,$pattern,$data);			
			//Yii::app()->functions->sendSMS($contact_phone,$sms_content);
			$sms_provider=Yii::app()->functions->getOptionAdmin('sms_provider');    	    	    	    	
            $sms_provider=strtolower($sms_provider);
			$params_sms = array(
			  'contact_phone'=>$contact_phone,
			  'sms_message'=>$sms_content,
			  'gateway'=>$sms_provider,
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR']
			);			
			$db->insertData("{{sms_broadcast_details}}",$params_sms);
		}		
		
		/*PUSH*/
		if (FunctionsV3::hasModuleAddon('mobileapp')){
			//mobile_registered
			if ($enabled_push==1){
				$tpl_push = getOptionA("order_request_cancel_to_customer_push_content_$lang");
				$tpl_push_title = getOptionA("order_request_cancel_to_customer_push_title_$lang");
				$tpl_push_title=FunctionsV3::replaceTemplateTags($tpl_push_title,$pattern,$data);
				$tpl_push=FunctionsV3::replaceTemplateTags($tpl_push,$pattern,$data);				
				$client_id = isset($data['client_id'])?$data['client_id']:'';
				if($client_id>0){					
					if ($device_info = AddonMobileApp::getRegisteredDeviceByClientID($client_id)){						
						$params_push = array(
						  'client_id'=>$client_id,
						  'client_name'=>$device_info['client_name'],
						  'device_platform'=>$device_info['device_platform'],
						  'device_id'=>$device_info['device_id'],
						  'push_title'=>$tpl_push_title,
						  'push_message'=>$tpl_push,
						  'date_created'=>FunctionsV3::dateNow(),
						  'ip_address'=>$_SERVER['REMOTE_ADDR']
						);						
						$db->insertData("{{mobile_push_logs}}",$params_push);									
						FunctionsV3::fastRequest(FunctionsV3::getHostURL().Yii::app()->createUrl("mobileapp/cron/processpush"));	        
					}
				}
			}
		}
		
		/*MOBILE VERSION 2*/
		if (FunctionsV3::hasModuleAddon("mobileappv2")){
			Yii::app()->setImport(array(			
			  'application.modules.mobileappv2.components.*',
			));
			if(method_exists('mobileWrapper','OrderTrigger')){	    				    			
				mobileWrapper::OrderTrigger($data['order_id'],$cancel_status,'','order_request_cancel');
			}
		}
		
		/*SINGLEMERCHANT PUSH*/
    	if (FunctionsV3::hasModuleAddon("singlemerchant")){
    		Yii::app()->setImport(array(			
			  'application.modules.singlemerchant.components.*',
			));
    		if(method_exists('SingleAppClass','OrderTrigger')){	       			    			
    			SingleAppClass::OrderTrigger($data['order_id'],$cancel_status,'','order_request_cancel');
    			$link=FunctionsV3::getHostURL().Yii::app()->createUrl("singlemerchant/cron/trigger_order");
    			FunctionsV3::consumeUrl($link);
    		}
    	}  	

		
		unset($db);		
	}
	
	public static function getNewCancelOrder($merchant_id='')
	{
		$db = new DbExt();
		$stmt="
		SELECT COUNT(*) as total
		FROM {{order}}
		WHERE
		merchant_id = ".FunctionsV3::q($merchant_id)."
		AND request_cancel='1'
		AND request_cancel_viewed = '2'
		AND request_cancel_status='pending'
		LIMIT 0,50
		";
		if ($res = $db->rst($stmt)){
			$res = $res[0];
			if($res['total']>0){
				return $res['total'];
			}
		}
		return false;
	}
	
	
    public static function reCheckDeliveryNew($merchant_id='',$data='')
    {
    	    
    	$response['code']=2;
    	
    	if($merchant_info=FunctionsV3::getMerchantById($merchant_id)){
    		$distance_type=FunctionsV3::getMerchantDistanceType($merchant_id); 
    		    		
    		$complete_address=$data['street']." ".$data['city']." ".$data['state']." ".$data['zipcode'];
    		if(isset($data['country'])){
    			$complete_address.=" ".$data['country'];
    		}    
    		    		
    		$lat=0;
			$lng=0;			
    		/*if address book was used*/
    		if ( isset($data['address_book_id'])){
	    		if ($address_book=Yii::app()->functions->getAddressBookByID($data['address_book_id'])){
	        		$complete_address=$address_book['street'];	    	
	    	        $complete_address.=" ".$address_book['city'];
	    	        $complete_address.=" ".$address_book['state'];
	    	        $complete_address.=" ".$address_book['zipcode'];
	        	}	    		        
	    	}
	    		    		    		    	
	    	/*if map address was used*/
    		if (isset($data['map_address_toogle'])){    			
    			if ($data['map_address_toogle']==2){
    				$lat=$data['map_address_lat'];
    				$lng=$data['map_address_lng'];
    			} else {
    				if ($lat_res=Yii::app()->functions->geodecodeAddress($complete_address)){
			           $lat=$lat_res['lat'];
					   $lng=$lat_res['long'];
		    	    }
    			}
    		} else {    			
    			if ($lat_res=Yii::app()->functions->geodecodeAddress($complete_address)){
		           $lat=$lat_res['lat'];
				   $lng=$lat_res['long'];
	    	    }
    		}
    		    		
    		$distance=FunctionsV3::getDistanceBetweenPlot(
				$lat,
				$lng,
				$merchant_info['latitude'],$merchant_info['lontitude'],$distance_type
			);  
			//dump($distance);
			$distance_type_raw = $distance_type=="M"?"miles":"kilometers";
			$distance_type = $distance_type=="M"?t("miles"):t("kilometers");
			$merchant_delivery_distance=getOption($merchant_id,'merchant_delivery_miles'); 
			
			if(!empty(FunctionsV3::$distance_type_result)){
             	$distance_type_raw=FunctionsV3::$distance_type_result;
            }
            
            $response['distance']=$distance;
            $response['distance_type_raw']=$distance_type_raw;
                                   
			if (is_numeric($merchant_delivery_distance)){
				if ( $distance>$merchant_delivery_distance){
					 if($distance_type_raw=="ft" || $distance_type_raw=="meter" || $distance_type_raw=="mt"){
					 	$response['code']=1;
					 }		             
                } else {
                	$delivery_fee=self::getMerchantDeliveryFee(
								              $merchant_id,
								              $merchant_info['delivery_charges'],
								              $distance,
								              $distance_type_raw);                    
                    $_SESSION['shipping_fee']=$delivery_fee;
                    $response['code']=1;
                }
			}  else $response['code']=3;
    	}
    	return $response;
    }
	
    public static function getAddressByLocation($id='')
    {
    	$db = new DbExt();    
    	$stmt = "
    	SELECT * FROM
    	{{address_book_location}}
    	WHERE
    	id =".self::q($id)."
    	LIMIT 0,1
    	";
    	if ($res = $db->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;
    }
    
    public static function hasAddressBook($client_id='',$type="location")
    {
    	$db = new DbExt();
    	$stmt="
    	SELECT * FROM
    	{{address_book_location}}
    	WHERE
    	client_id = ".FunctionsV3::q($client_id)."    	
    	LIMIT 0,1
    	";    	
    	if($type=="address"){
    		$stmt="
	    	SELECT * FROM
	    	{{address_book}}
	    	WHERE
	    	client_id = ".FunctionsV3::q($client_id)."	    	
	    	LIMIT 0,1
	    	";    	
    	}   
    	if($res=$db->rst($stmt)){
    		return $res[0];
    	}
    	return false;
    }
    
    public static function getCountryIDByState($state_id='')
    {
    	$db = new DbExt();
    	$stmt="
    	SELECT country_id FROM
    	{{location_states}}
    	WHERE
    	state_id = ".FunctionsV3::q($state_id)."    	
    	LIMIT 0,1
    	";    	
    	if($res=$db->rst($stmt)){
    		return $res[0];
    	}
    	return false;
    }

    public static function getAddressBookList($client_id='')
    {
    	$db = new DbExt();
    	$stmt="
    	SELECT 
		a.*,
		b.name as state_name,
		c.name as city_name,
		c.postal_code ,
		d.name as area_name,
		concat(a.street,' ',b.name,' ', c.name,' ',d.name,' ',c.postal_code ) as complete_address
		
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
    	if($res = $db->rst($stmt)){
    		$data = array();
    		foreach ($res as $val) {
    			$data[$val['id']]=$val['complete_address'];
    		}
    		return $data;
    	}
    	return false;
    }
	
    public static function getAddressByLocationFullDetails($id='')
    {
    	$db = new DbExt();
    	$stmt="
    	SELECT 
		a.*,
		b.name as state_name,
		c.name as city_name,
		c.postal_code ,
		d.name as area_name,
		concat(a.street,' ',b.name,' ', c.name,' ',d.name,' ',c.postal_code ) as complete_address
		
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
		id = ".FunctionsV3::q($id)."
		LIMIT 0,1
		";		
    	if($res = $db->rst($stmt)){
    		return $res[0];
    	}
    	return false;
    }

    public static function canReviewOrder($order_status='')
    {   
    	$website_review_type = getOptionA('website_review_type');
    	if($website_review_type==2){
    		$review_baseon_status = getOptionA('review_baseon_status');    		
    		if(!empty($review_baseon_status)){
    		   $review_baseon_status = json_decode($review_baseon_status,true);
    		   if (is_array($review_baseon_status) && count($review_baseon_status)>=1){
    		   	  if (in_array($order_status,$review_baseon_status)){
    		   	  	  return true;
    		   	  }
    		   }
    		} else return true;
    	}
    	return false;
    }
    
    public static function getReviewByOrder($client_id='', $order_id='')
    {
    	$db = new DbExt();
    	$stmt= "
    	SELECT * FROM
    	{{review}}
    	WHERE
    	client_id =".FunctionsV3::q($client_id)."
    	AND
    	order_id = ".FunctionsV3::q($order_id)."
    	LIMIT 0,1
    	";
    	if($res = $db->rst($stmt)){
    		return $res[0];
    	}
    	return false;
    }
    
    public static function clientHistyOrder($client_id='')
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
    	limit 0,1
    	) as merchant_name,
    	
    	(
    	 select count(*) 
    	 from {{review}}
    	 where
    	 order_id = a.order_id
    	) as review_count
    	
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
    
    public static function getOpeningHours($merchant_id='')
	{
        $stores_open_day=Yii::app()->functions->getOption("stores_open_day",$merchant_id);
		$stores_open_starts=Yii::app()->functions->getOption("stores_open_starts",$merchant_id);
		$stores_open_ends=Yii::app()->functions->getOption("stores_open_ends",$merchant_id);
		$stores_open_custom_text=Yii::app()->functions->getOption("stores_open_custom_text",$merchant_id);
		
		$stores_open_day=!empty($stores_open_day)?(array)json_decode($stores_open_day):false;
		$stores_open_starts=!empty($stores_open_starts)?(array)json_decode($stores_open_starts):false;
		$stores_open_ends=!empty($stores_open_ends)?(array)json_decode($stores_open_ends):false;
		$stores_open_custom_text=!empty($stores_open_custom_text)?(array)json_decode($stores_open_custom_text):false;
		
		
		$stores_open_pm_start=Yii::app()->functions->getOption("stores_open_pm_start",$merchant_id);
		$stores_open_pm_start=!empty($stores_open_pm_start)?(array)json_decode($stores_open_pm_start):false;
		
		$stores_open_pm_ends=Yii::app()->functions->getOption("stores_open_pm_ends",$merchant_id);
		$stores_open_pm_ends=!empty($stores_open_pm_ends)?(array)json_decode($stores_open_pm_ends):false;		
												
		$open_starts='';
		$open_ends='';
		$open_text='';
		$data=array();
				
		if (is_array($stores_open_day) && count($stores_open_day)>=1){
			foreach ($stores_open_day as $val_open) {	
				if (array_key_exists($val_open,(array)$stores_open_starts)){
					$open_starts=timeFormat($stores_open_starts[$val_open],true);
				}							
				if (array_key_exists($val_open,(array)$stores_open_ends)){
					$open_ends=timeFormat($stores_open_ends[$val_open],true);
				}							
				if (array_key_exists($val_open,(array)$stores_open_custom_text)){
					$open_text=$stores_open_custom_text[$val_open];
				}					
				
				$pm_starts=''; $pm_ends=''; $pm_opens='';
				if (array_key_exists($val_open,(array)$stores_open_pm_start)){
					$pm_starts=timeFormat($stores_open_pm_start[$val_open],true);
				}											
				if (array_key_exists($val_open,(array)$stores_open_pm_ends)){
					$pm_ends=timeFormat($stores_open_pm_ends[$val_open],true);
				}												
				
				$full_time='';
				if (!empty($open_starts) && !empty($open_ends)){					
					$full_time=$open_starts."-".$open_ends;
				}			
				if (!empty($pm_starts) && !empty($pm_ends)){
					if ( !empty($full_time)){
						$full_time.="x";
					}				
					$full_time.="$pm_starts-$pm_ends";
				}												
								
				$data[$val_open]=array(
				  'day'=>$val_open,
				  'hours'=>$full_time				  
				);
				
				$open_starts='';
		        $open_ends='';
		        $open_text='';
			}
			return $data;
		}			
		return false;		
	}	

	
    public static function createTimeRange($start, $end, $interval = '30 mins', $format = '12') {
	    $startTime = strtotime($start); 
	    $endTime   = strtotime($end);
	    $returnTimeFormat = ($format == '12')?'g:i:s A':'G:i:s';
	
	    $current   = time(); 
	    $addTime   = strtotime('+'.$interval, $current); 
	    $diff      = $addTime - $current;
	
	    $times = array(); 	    
	    while ($startTime < $endTime) { 
	        $times[] = date($returnTimeFormat, $startTime); 
	        $startTime += $diff; 
	    } 
	    $times[] = date($returnTimeFormat, $startTime); 
	    return $times; 
	}    
    
    public static function getTimeList($merchant_id='', $delivery_date='')
    {
    	$times = array(); 
		$date_now = date("Y-m-d");
		$time_start = '7:30'; $time_end = '18:30'; $interval = '15 mins';		
		
		$mt_timezone=Yii::app()->functions->getOption("merchant_timezone",$merchant_id);
		if(!empty($mt_timezone)){
			Yii::app()->timeZone=$mt_timezone;
		}
    	
    	$delivery_day = isset($delivery_date)?date("l",strtotime($delivery_date)): date("l") ;
    	$delivery_day = strtolower($delivery_day);    	    	
    	if ( $res = FunctionsV3::getOpeningHours($merchant_id) ){    	    		
			if (array_key_exists($delivery_day,(array)$res)){					
				$time_based = $res[$delivery_day]['hours'];				
				if(!empty($time_based)){					
											
					$time_based_ex='';
					if (preg_match("/x/i", $time_based)) {							
						$time_based_sep = explode("x",$time_based);														
						$time_based_sep_1 = explode("-",$time_based_sep[0]);
						$time_based_sep_2 = explode("-",$time_based_sep[1]);
						$time_based_start = $time_based_sep_1[0];
						$time_based_end = $time_based_sep_2[1];
						
						$time_based_combined = "$time_based_start-$time_based_end";							
						$time_based_ex = explode("-",$time_based_combined);
					} else $time_based_ex = explode("-",$time_based);
											
					//dump($time_based_ex);
											
					if(is_array($time_based_ex) && count($time_based_ex)>=1){						
						$time_start = $time_based_ex[0];
						//$time_end = $time_based_ex[1];
						$time_end = date("G:i",strtotime($time_based_ex[1]));

						/*CHECK IF SAME DAY*/
						if ($delivery_date==$date_now){
							$hour_now = date("Gi");
							$hour_now_l = date("G:i");
							$time_start_1 = date("Gi",strtotime($time_start));								
							if($hour_now>$time_start_1){
								$time_start = $hour_now_l;
							}
						}
													
					}
				}
			} 
    	}    	
    	    	
    	    	
    	/*CONVERT TO WHOLE NUMBER*/
    	
    	$enabled_whole_number = false;
    	
    	if($enabled_whole_number){
	    	if(!empty($time_start)){
	    		$time_s = explode(":",$time_start);
	    		if(is_array($time_s) && count($time_s)>=1){
	    			if(isset($time_s[1])){       				
	    				$rounded_minutes = round($time_s[1],-1);			    				
	    				if($rounded_minutes>=60){
	    				} else {    				
	    					$time_start =$time_s[0].":$rounded_minutes";
	    				}    				
	    			}
	    		}
	    	}    
    	}
    	    	       
    	$time_interval = 15; 
    	
    	$website_time_picker_interval= getOptionA('website_time_picker_interval');
    	if($website_time_picker_interval>0){
    		$time_interval=$website_time_picker_interval;
    	}    
    	
    	/*GET MERCHANT TIME INTERVAL*/
    	$website_merchant_time_picker_interval = getOption($merchant_id,'website_merchant_time_picker_interval');
    	if($website_merchant_time_picker_interval>0){
    		$time_interval = $website_merchant_time_picker_interval;
    	}    
    	    	
    	$time_format = 12;
    	
    	$website_time_picker_format = getOptionA('website_time_picker_format');    	
    	if(!empty($website_time_picker_format)){
    		$time_format = $website_time_picker_format;
    	}    
    	    	    	
    	/*REMOVE CURRENT TIME*/
    	$time_start = date("G:i",strtotime($time_start." +$time_interval mins"));  

    	    	    	
    	$times = FunctionsV3::createTimeRange($time_start, $time_end , "$time_interval mins",$time_format);    	
    	if(is_array($times) && count($times)>=1){			
		} else {
			$times = FunctionsV3::createTimeRange("6:00", "23:59" , "$time_interval mins",$time_format);		
		}		
				
		/*fixed time list with end of time opening hours*/
		$time_end_integer = str_replace(":","",$time_end);
		$time_end_last = 0;			
		if(is_array($times) && count($times)>=1){
			$time_end_last = date("G:i",strtotime($times[ count($times)-1 ]));
			$time_end_last = str_replace(":","",$time_end_last);			
			if($time_end_last>$time_end_integer){
				$times[ count($times)-1 ]  = $time_end;
			}
		}
					
		$final_time = array();
		if(is_array($times) && count($times)>=1){
			foreach ($times as $val) {
				//$final_time[$val]=$val;
				$final_time[$val]=FunctionsV3::prettyTime($val);
			}
		}   
				
		return $final_time;
    }
    
    public static function getDateList($merchant_id='')
    {
    	$mt_timezone=Yii::app()->functions->getOption("merchant_timezone",$merchant_id);
		if(!empty($mt_timezone)){
			Yii::app()->timeZone=$mt_timezone;
		}
		
		$day=Yii::app()->functions->getOption("stores_open_day",$merchant_id);
		$day_open=!empty($day)?json_decode($day,true):false;
			
		if(is_array($day_open) && count($day_open)>=1){
			
			for ($i = 0; $i <= 30; $i++) {				
				$key=date("Y-m-d",strtotime("+$i day"));
				$key_day = strtolower(date("l",strtotime($key)));
				if(in_array($key_day,(array)$day_open)){
					$dates[$key] = FunctionsV3::prettyDate($key);
				}
			}
		} else {			
			for ($i = 0; $i <= 30; $i++) {				
				$key=date("Y-m-d",strtotime("+$i day"));
				$dates[$key] = FunctionsV3::prettyDate(date("D F d Y",strtotime("+$i day")));
			}
		}
		
		return $dates;
    }
    
    public static function categorySkeduler($merchant_id='', $days='')
    {
    	$and=''; $days=strtolower($days);  
    	
    	$enabled_category_sked = getOption($merchant_id,'enabled_category_sked');    	

    	if($enabled_category_sked==1){
    		$and = " AND $days='1' ";
    	}    
    	
    	$stmt="
		SELECT 
		cat_id,
		category_name,
		category_description,
		category_name_trans,
		photo,
		category_description_trans
		
		FROM
		{{category}}
		WHERE 
		merchant_id= ".FunctionsV3::q($merchant_id)."
		AND status in ('publish','published')
		$and
		ORDER BY sequence ASC
		";		
    	//dump($stmt);	    	
    	$db = new DbExt();
    	if($res = $db->rst($stmt)){
    		foreach ($res as $val) {    			
    			if(!empty($val['category_name_trans'])){
    				$category_name_trans = json_decode($val['category_name_trans'],true);    				
    				$val['category_name_trans']=$category_name_trans;
    			}
    			if(!empty($val['category_description_trans'])){
    				$category_description = json_decode($val['category_description_trans'],true);    				
    				$val['category_description_trans']=$category_description;
    			}
    			$val['category_id']=$val['cat_id'];
    			$val['item']=array();
    			$data[]=$val;
    		}
    		return $data;
    	}
    	return false;
    }
    
    public static function addClass($add=false, $class_name='')
    {
    	if($add){
    		return 'class="'.$class_name.'"';
    	}
    	return false;
    }
    
    public static function getNewCancelOrderAdmin()
	{
		$db = new DbExt();
		$stmt="
		SELECT COUNT(*) as total
		FROM {{order}}
		WHERE 1		
		AND request_cancel='1'
		AND request_cancel_viewed = '2'
		AND request_cancel_status='pending'
		LIMIT 0,50		
		";
		if ($res = $db->rst($stmt)){
			$res = $res[0];
			if($res['total']>0){
				return $res['total'];
			}
		}
		return false;
	}
    
	public static function getMapProvider()
	{
		$map_provider = getOptionA('map_provider');
		$token = ''; $map_api = '';
		$map_distance_results  = ''; $mode = "driving";
		
		if(empty($map_provider)){
			$map_provider='google.maps';
		}
		
		switch ($map_provider) {
			case "mapbox":
				$token = getOptionA('mapbox_access_token');				
				$map_api = $token;
				$mode = getOptionA('mapbox_method');
				break;

			case "google.maps":	
			    $token = getOptionA('google_geo_api_key');
			    $map_api = getOptionA('google_maps_api_key');
			    $mode = getOptionA('google_distance_method');
			default:
				break;
		}
		
		$map_distance_results = (integer) getOptionA('map_distance_results');
		if($map_distance_results<0){
			$map_distance_results=2;
		}
				
		return array(		  
		  'provider'=>$map_provider,
		  'token'=>$token,
		  'map_api'=>$map_api,
		  'map_distance_results'=>$map_distance_results,
		  'mode'=>$mode
		);
	}	
	
	public static function prettyMobile($mobile='')
	{
		if(!empty($mobile)){
			$format = "(".substr($mobile,0,3).")";
			$format.= substr($mobile,3,3)."-";
			$format.= substr($mobile,6,strlen($mobile));
			return $format;
		}
		return '';
	}
	
	public static function prettyCC($card='')
	{
		if(!empty($card)){
			if(strlen($card)>=16){
			$format = substr($card,0,4)." ";
			$format.= substr($card,4,4)." ";
			$format.= substr($card,8,4)." ";
			$format.= substr($card,12,4);
			return $format;
			} else return $card;
		}
		return $card;
	}
	
	public static function getBookingByIDWithDetails($booking_id='')
	{
	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT a.*,
   	   b.restaurant_name 
   	   FROM
   	   {{bookingtable}} a
   	   left join {{merchant}} b
	   ON 
	   a.merchant_id=b.merchant_id
   	   WHERE
   	   a.booking_id=".self::q($booking_id)."
   	   
   	   LIMIT 0,1
   	   ";
   	   if ($res=$DbExt->rst($stmt)){
   	   	  return $res[0];
   	   }
   	   return false;
	}
	
	public static function getPackageTransTransID($transaction_id='')
	{
	   $DbExt=new DbExt;
   	   $stmt="
   	   SELECT TRANSACTIONID FROM
   	   {{package_trans}}
   	   WHERE
   	   TRANSACTIONID =".FunctionsV3::q(trim($transaction_id))."
   	   ";
   	   if ($res=$DbExt->rst($stmt)){
   	   	  return $res[0];
   	   }
   	   return false;
	}
	
	public static function generateHours()
	{
		$list=array();
		for ($i = 0; $i <= 12; $i++) {
			$ii = str_pad($i,2,"0",STR_PAD_LEFT);
			$list[$i]=$ii;
		}
		return $list;
	}
	
	public static function generateMinutes()
	{
		$list=array();
		for ($i = 0; $i <= 60; $i++) {
			$ii = str_pad($i,2,"0",STR_PAD_LEFT);
			$list[$i]=$ii;
		}
		return $list;
	}
		
    public static function canCancelOrderNew($request_cancel='',$date_created='',
	$order_status='',$order_locked='', $request_cancel_status='pending', $cancel_order_enabled='')
	{
				
		if($request_cancel==1){
		   return true;	
		}
		
		if($request_cancel_status!='pending'){
			return false;
		}	
							
		if($cancel_order_enabled==1){
			$check_0 = true;
		} else $check_0 = false;
		
		$check_1 = FunctionsV3::cancelOrderCheckDayTime($date_created);		
		$check_2 = FunctionsV3::cancelOrderCheckStatus($order_status);
		if($check_0==TRUE && $check_1==TRUE && $check_2==TRUE){
			return true;
		}
		return false;
	}
	
	public static function cancelOrderCheckDayTime($date_created='')
	{
		$cancel_order_days_applied = getOptionA('cancel_order_days_applied');
		
		$cancel_order_hours = getOptionA('cancel_order_hours');
		if(!is_numeric($cancel_order_hours)){
			$cancel_order_hours=0;
		}
		
		$cancel_order_minutes = getOptionA('cancel_order_minutes');
		if(!is_numeric($cancel_order_minutes)){
			$cancel_order_minutes=0;
		}
		
		if($cancel_order_days_applied>0 || $cancel_order_hours>0 || $cancel_order_minutes>0){			
			$date_now=date('Y-m-d g:i:s a');
			$date_created = date('Y-m-d g:i:s a',strtotime($date_created));
			$time_diff=Yii::app()->functions->dateDifference($date_created,$date_now);						
			//dump($time_diff);
			if(is_array($time_diff) && count($time_diff)>=1){		
				
				if($cancel_order_days_applied>0){
					if($time_diff['days']>=$cancel_order_days_applied){						
						return false;
					}					
				}	
				
				$time_1 = $time_diff['hours'].$time_diff['minutes'];				
				$time_2 = $cancel_order_hours.$cancel_order_minutes;				
				
				if($time_diff['days']>=$cancel_order_days_applied){
					if($time_1>$time_2){						
						return false;
					}			
				}
				
			}
		} else return false;
			
		return true;
	}
	
	public static function getReviewBasedOnStatus($order_status='')
	{
		$status='pending';
		$publish_review_status = getOptionA('publish_review_status');
		if(!empty($publish_review_status)){
			$publish_review_status = json_decode($publish_review_status,true);			
			if(in_array($order_status,(array)$publish_review_status)){
				$status='publish';
			}
		}
		return $status;
	}
	
	public static function updateReviews($order_id='',$order_status='')
	{
		$website_review_type=getOptionA('website_review_type');
		if($website_review_type==2){
			$db=new DbExt();
			$stmt="SELECT id
			FROM {{review}}
			WHERE
			order_id=".self::q($order_id)."
			LIMIT 0,1
			";
			if($res=$db->rst($stmt)){
				$res=$res[0];				
				$params=array(
				  'status'=>self::getReviewBasedOnStatus($order_status),
				  'date_modified'=>self::dateNow(),
				  'ip_address'=>$_SERVER['REMOTE_ADDR']
				);				
				$db->updateData('{{review}}',$params,'id',$res['id']);
				unset($db);
			}
		}
	}
	
	public static function getDefaultAddressByLocation($client_id='')
	{
		if($client_id>0){
			$db=new DbExt();
			$stmt="
			SELECT * FROM
			{{address_book_location}}
			WHERE
			client_id=".self::q($client_id)."
			AND
			as_default='1'
			LIMIT 0,1
			";
			if($res=$db->rst($stmt)){
				return $res[0];
			}			
		}
		return false;
	}
	
	public static function addToFavorites($client_id='', $merchant_id='')
	{
		
		if( !self::checkIfTableExist('favorites')){
			return false;
		}	
		
		$db = new DbExt();
		$stmt="
		SELECT * FROM
		{{favorites}}
		WHERE
		fav_type='restaurant'
		AND
		client_id=".self::q($client_id)."
		AND
		merchant_id=".self::q($merchant_id)."
		LIMIT 0,1
		";
		
		$params=array(
		  'client_id'=>$client_id,
		  'merchant_id'=>$merchant_id,
		  'date_created'=>self::dateNow(),
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		
		if($res=$db->rst($stmt)){
			$res=$res[0];
			$db->qry("DELETE FROM
			{{favorites}}
			WHERE
			id=".self::q($res['id'])."
			");
			return false;
		} else {
			if($db->insertData("{{favorites}}",$params)){
				return true;
			}
		}		
		return false;
	}
	
	public static function getCustomerFavorites($client_id='')
	{
		if( !self::checkIfTableExist('favorites')){
			return false;
		}	
		
		$db = new DbExt();
		$stmt="
		SELECT merchant_id
		FROM
		{{favorites}}
		WHERE
		client_id=".self::q($client_id)."
		ORDER BY id ASC
		";
		if($res = $db->rst($stmt)){
			return $res;
		}
		return false;
	}
	
	public static function getCustomerFavoritesList($client_id='')
	{
		if( !self::checkIfTableExist('favorites')){
			return false;
		}	
				
		$db = new DbExt();
		$stmt="
		SELECT 
		a.id,
		a.merchant_id,
		a.date_created,
		b.restaurant_name,
		b.restaurant_slug
		FROM {{favorites}} a
		
		left join {{merchant}} b
        On
        a.merchant_id=b.merchant_id
		
		WHERE
		a.client_id=".self::q($client_id)."
		ORDER BY id DESC
		";		
		if($res = $db->rst($stmt)){			
			return $res;
		}
		return false;
	}
	
	public static function removeFavorites($client_id='', $id='')
	{
		$db = new DbExt();
		if($client_id>0 && $id>0){
			$db->qry("DELETE FROM 
			{{favorites}}
			WHERE
			client_id=".self::q($client_id)."
			AND
			id=".self::q($id)."
			");
			unset($db);
			return true;
		}
		return false;
	}
		
	public static function checkTableFields($table='',$new_field='')
	{
		$DbExt=new DbExt;
		$prefix=Yii::app()->db->tablePrefix;		
		$existing_field=array();
		if ( $res = self::checkTableStructure($table)){
			foreach ($res as $val) {								
				$existing_field[$val['Field']]=$val['Field'];
			}							
			foreach ($new_field as $key_new=>$val_new) {								
				if (in_array($key_new,(array)$existing_field)){	
					return true;
				} 
			}
		}			
		return false;
	}		
	
    public static function checkTableStructure($table_name='')
    {
    	$db_ext=new DbExt;
    	$stmt=" SHOW COLUMNS FROM {{{$table_name}}}";	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res;
    	}
    	return false;    
    }   
    
    public static function checkNewDb()
	{
		$new=0;
		$new_fields=array('dinein_table_number'=>"dinein_table_number");
		if ( !self::checkTableFields('order',$new_fields)){			
			$new++;
		}
		
		if( !self::checkIfTableExist('favorites')){
			$new++;
		}	
		
		/*4.9*/
		$new_fields=array('merchant_ref'=>"merchant_ref");
		if ( !self::checkTableFields('payment_order',$new_fields)){			
			$new++;
		}
		
		/*5.1*/
		$new_fields=array('payment_gateway_ref'=>"payment_gateway_ref");
		if ( !self::checkTableFields('order',$new_fields)){			
			$new++;
		}		
		$new_fields=array('payment_gateway_ref'=>"payment_gateway_ref");
		if ( !self::checkTableFields('merchant',$new_fields)){			
			$new++;
		}		
		if( !self::checkIfTableExist('stripe_logger')){
			$new++;
		}		
		
		if ($new>0){
			return true;
		} else return false;
	}   
	
	public static function isClassDeliveryTableExist()
	{
	     $class = Yii::getPathOfAlias('webroot')."/protected/components/DeliveryTableRate.php";	
	     if(file_exists($class)){
	     	return true;
	     }
	     return false;
	}
	
	public static function deleteClientCC($cc_id='', $client_id='')
	{
		if($cc_id>=1 && $client_id>=1){
			$stmt="DELETE FROM
			{{client_cc}}
			WHERE
			cc_id=".FunctionsV3::q($cc_id)."
			AND
			client_id=".FunctionsV3::q($client_id)."
			";
			$db=new DbExt();
			$db->qry($stmt);
			unset($db);
		}
	}
	
	public static function makeLinkClickable($text) {
	    $text = html_entity_decode($text);
        $text = " ".$text;
        $text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
                '<a href="\\1" target=_blank>\\1</a>', $text);
        $text = eregi_replace('(((f|ht){1}tps://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
                '<a href="\\1" target=_blank>\\1</a>', $text);
        $text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
        '\\1<a href="http://\\2" target=_blank>\\2</a>', $text);
        $text = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})',
        '<a href="mailto:\\1" target=_blank>\\1</a>', $text);
        return $text;
	}	
	
	public static function getAddressBookDefault($client_id='')
    {
    	$db=new DbExt;    	
    	$stmt="SELECT  *    	       
    	       FROM
    	       {{address_book}}
    	       WHERE
    	       client_id= ".FunctionsV3::q($client_id)."
    	       AND
    	       as_default ='2'
    	       LIMIT 0,1
    	";    	    	
    	if ($res=$db->rst($stmt)){    		
    		return $res[0];
    	}
    	return false;
    }
    
    public static function getOrderInfoByTokenWithMerchantName($order_id_token='')
	{
		if(empty($order_id_token)){
			return false;
		}
		
		$db = new DbExt();
		$stmt="SELECT a.*,
		b.restaurant_name
		FROM
		{{order}} a
		
		left join {{merchant}} b
        ON
        a.merchant_id=b.merchant_id
		
		WHERE
		order_id_token = ".FunctionsV3::q($order_id_token)."
		LIMIT 0,1
		";
		if($res = $db->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
	public static function callAddons($order_id='')
	{
		/*SEND FAX*/
         if($order_info=Yii::app()->functions->getOrderInfo($order_id)){
            Yii::app()->functions->sendFax($order_info['merchant_id'],$order_id);
         } 
         
         $client_id = isset($order_info['client_id'])?$order_info['client_id']:'';
         
		 /*POINTS PROGRAM*/ 
		 if (FunctionsV3::hasModuleAddon('pointsprogram')){
			if (getOptionA('points_enabled')==1){									
				if (method_exists("PointsProgram","updateOrderBasedOnStatus")){								
					PointsProgram::updateOrderBasedOnStatus('paid',$order_id);
				} else AddonMobileApp::updatePoints($order_id,$client_id);							
			}
		}
		
		  /*Driver app*/
		 if (FunctionsV3::hasModuleAddon("driver")){
			Yii::app()->setImport(array(			
			  'application.modules.driver.components.*',
			));
			Driver::addToTask($order_id);
		 }

		 /*INVENTORY ADDON*/				
		if (FunctionsV3::hasModuleAddon('inventory')){
			try {
			  Yii::app()->setImport(array(			
				  'application.modules.inventory.components.*',
			   ));	
			   InventoryWrapper::insertInventorySale($order_id,'paid');	
			} catch (Exception $e) {										    
			  // echo $e->getMessage();		    					    	  
			}		    					    	
		}	 
		 
	}
	
	public static function getCountryCode()
	{
		$code = Yii::app()->functions->getOptionAdmin('admin_country_set');
		if(!empty($code)){
			return $code;
		}
		return false;
	}
	
	public static function isAdminLogin()
	{		
         if(!Yii::app()->functions->isAdminLogin() ){
         	 Yii::app()->end();
		 }
	}
	
	public static function isMerchantLogin()
	{
		if ( !Yii::app()->functions->isMerchantLogin()){
			Yii::app()->end();
		}		
	}
	
	public static function isUserLogin()
	{	
        if(  !Yii::app()->functions->isClientLogin() ){  
        	Yii::app()->end();
 	    }
	}
	
	public static function frontEndActionList()
	{
		$list[]='subscribeNewsletter';
		$list[]='addAddressBook';
		$list[]='addReviewToOrder';
		$list[]='bankDepositVerification';
		$list[]='addCreditCard';
		$list[]='updateCreditCard';
		$list[]='clientLogin';
		$list[]='forgotPassword';
		$list[]='clientRegistration';
		$list[]='placeOrder';
		$list[]='contacUsSubmit';
		$list[]='ItemBankDepositVerification';
		$list[]='driverSignup';
		$list[]='verifyEmailCode';
		$list[]='changePassword';
		$list[]='addToCart';
		$list[]='addReviews';
		$list[]='merchantSignUp2';
		$list[]='merchantSignUp';
		$list[]='merchantPaymentPaypal';
		$list[]='activationMerchant';
		$list[]='verifyMobileCode';
		$list[]='InitPlaceOrder';
		$list[]='paymill_transaction';
		$list[]='paypalCheckoutPayment';
		$list[]='clientRegistrationModal';
		$list[]='cancelWithdrawal';
		$list[]='addAddressBookLocation';
		$list[]='addAddressBook';
		$list[]='addCreditCardMerchant';
		$list[]='updateClientCC';
		$list[]='bookATable';
		$list[]='updateClientProfile';
		$list[]='addressBook';
		$list[]='ClientCCList';
		$list[]='merchantResumeSignup';
		$list[]='searchArea';
		$list[]='sisowPaymentMerchant';
		$list[]='sisowPayment';
		$list[]='addressBookLocation';
		$list[]='viewFoodItem';
		$list[]='setDeliveryOptions';
		$list[]='removeRating';
		$list[]='loginModal';
		$list[]='editReview';
		$list[]='viewReceipt';
		$list[]='loadItemCart';
		$list[]='deleteItem';
		$list[]='loadCreditCardList';
		$list[]='loadCreditCardListMerchant';
		$list[]='addRating';
		$list[]='loadRatings';
		$list[]='loadTopMenu';
		$list[]='loadReviews';
		$list[]='deleteReview';
		$list[]='addToOrder';
		$list[]='merchantFreePayment';
		$list[]='FBRegister';
		$list[]='resendActivationCode';
		$list[]='applyVoucher';
		$list[]='removeVoucher';
		$list[]='geoReverse';
		$list[]='enterAddress';
		$list[]='resendMobileCode';
		$list[]='getAllMerchantCoordinates';
		$list[]='findGeo';
		$list[]='clearCart';
		$list[]='sendOrderSMSCode';
		$list[]='getCartCount';		
		$list[]='updateReview';
		$list[]='merchantPayment';
		$list[]='setAddress';
				
		return $list;
	}
	
	public static function validateFrontAction($action='')
	{
		$list = self::frontEndActionList();		
		if(in_array($action,$list)){
			return true;
		}
		return false;
	}
	
	public static function maskCardnumber($cardnumber='')
    {
    	return Yii::app()->functions->maskCardnumber($cardnumber);
    }
    
    public static function getCurrencyCode()
    {
    	$curr_code=getOptionA("admin_currency_set");
    	if (empty($curr_code)){
    		return "USD";
    	}        	
    	return $curr_code;
    }
    
    public static function getCurrencySymbol()
    {
    	return Yii::app()->functions->adminCurrencySymbol();
    }
    
    public static function updateOrderPayment(
    $order_id,$payment_code='',$payment_ref='',$payment_response='', $merchant_ref='')
    {
    	$db=new DbExt();
    	$params_logs = array(
			 'order_id'=>$order_id,
			 'payment_type'=>$payment_code,
			 'payment_reference'=>$payment_ref,
			 'raw_response'=>json_encode($payment_response),
			 'date_created'=>FunctionsV3::dateNow(),
		     'ip_address'=>$_SERVER['REMOTE_ADDR'],
		     'merchant_ref'=>$merchant_ref
	    );	
	    $db->insertData("{{payment_order}}",$params_logs);
	    
	    $params_update=array(
	       'status'=>'paid',
	       'date_modified'=>FunctionsV3::dateNow(),
	       'ip_address'=>$_SERVER['REMOTE_ADDR']
	    );	            
	    $db->updateData("{{order}}",$params_update,'order_id',$order_id);
	    
	    $db->insertData("{{order_history}}",array(
	       'order_id'=>$order_id,
	       'status'=>"paid",
	       'date_created'=>FunctionsV3::dateNow(),
	       'ip_address'=>$_SERVER['REMOTE_ADDR'],
	    ));
	    unset($db);	  
    }
    
    public static function getSMSTransLog($payment_ref='')
    {
    	$db=new DbExt();
    	$stmt="SELECT * FROM
    	{{sms_trans_logs}}
    	WHERE
    	payment_reference=".FunctionsV3::q($payment_ref)."
    	LIMIT 0,1
    	";
    	if($res = $db->rst($stmt)){
    		return $res[0];
    	}
    	return false;
    }
    
    public static function getMerchantMenuTag()
    {
    	$menu_list=array();
		$menu=Yii::app()->functions->merchantMenu();		
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
    
    public static function validateMerchantControllerAccess($action_name='')
    {
    	$action_name = strtolower($action_name);    	
    	$menu = self::getMerchantMenuTag();
    	    
    	$merchant_id=Yii::app()->functions->getMerchantID();
    	
    	switch ($action_name) {
    		case "tablebooking":
    			$tbl_booking=getOption( $merchant_id ,'merchant_master_table_boooking');
    			if($tbl_booking==1){
    				return false;
    			}
    			
    			if (getOptionA('merchant_tbl_book_disabled')==2){				
					return false;
				}
    			break;
    	
    	    case "mintable":	
    		    if (FunctionsV3::isSearchByLocation()){
					return false;
				}
    		    break;
    	
    		case "invoice":    
    		   if ( $merchant_type=FunctionsV3::getMerchantMembershipType($merchant_id)){    		   	    
					if($merchant_type==1){
						return false;
					}			
				}
    		   break;
    		
    		case "sms-gateway":    
    		   $mechant_sms_enabled=Yii::app()->functions->getOptionAdmin('mechant_sms_enabled');
			   if ($mechant_sms_enabled=="yes"){
				   return false;	
			   }
    		   break;
    		   
    		case "purchasesms":   
    		case "purchasesmstransaction":
    		   $mechant_sms_purchase_disabled=Yii::app()->functions->getOptionAdmin('mechant_sms_purchase_disabled');
			   if ( $mechant_sms_purchase_disabled=="yes"){
					return false;
			   }
    		   break;
    		   
    		case "paypalsettings":
    			$action_name="paypal";
    			break;
    			
    		case "stripesettings":
    			$action_name="stripe";
    			break;	
    			
    		case "mercadopagosettings":
    			$action_name="mercadopago";
    			break;		
    	
    		case "sisowsettings":
    			$action_name="ide";
    			break;					
    			
    		case "payumoneysettings":
    			$action_name="payu";
    			break;						
    			
    		case "payserasettings":
    			$action_name="pys";
    			break;							
    			
    		case "barclay":
    			$action_name="bcy";
    			break;								
    			
    		case "epagbg":
    			$action_name="epy";
    			break;									
    			
    	    case "authorize":
    			$action_name="atz";
    			break;											
    		   	
    		default:
    			break;
    	}
    	     	
    	if (in_array($action_name,(array)$menu)){
    		if ($info=Yii::app()->functions->getMerchantInfo()){
    			$info = (array)$info[0];     			
    			$user_access = json_decode( isset($info['user_access'])?$info['user_access']:'',true);        	
    			if(is_array($user_access) && count((array)$user_access)>=1){
    				$user_access = array_map('strtolower', $user_access);
    				if($info['is_commission']==2){
	    			    array_push($user_access,"statement","earnings","withdrawals");
	    			}
    				//dump($user_access);
    				if (!in_array($action_name,$user_access)){
    					return false;
    				} //else echo 'has acces';
    			} //else echo 'no user access';
    		} //else echo 'no info';
    	} //else echo 'not validate';
    	return true;
    }   
    
    public static function getMerchantUserAccess()
    {
    	if ($info=Yii::app()->functions->getMerchantInfo()){
    		$info = (array)$info[0];  
    		$user_access = json_decode( isset($info['user_access'])?$info['user_access']:'',true); 
    		if(is_array($user_access) && count((array)$user_access)>=1){
    			$user_access = array_map('strtolower', $user_access);
    			return $user_access;
    		}
    	}    
    	return false;
    }  
    
    public static function hasMerchantAccessToMenu($user_access=array(), $tag='')
    {
    	$tag = strtolower($tag);        
    	$merchant_id=Yii::app()->functions->getMerchantID();
    	
    	switch ($tag) {
    		case "tablebooking":
    			$tbl_booking=getOption( $merchant_id ,'merchant_master_table_boooking');
    			if($tbl_booking==1){
    				return false;
    			}
    			
    			if (getOptionA('merchant_tbl_book_disabled')==2){				
					return false;
				}
    			break;

    		case "mintable":	
    		    if (FunctionsV3::isSearchByLocation()){
					return false;
				}
    		    break;
    	
    		case "invoice":    
    		   if ( $merchant_type=FunctionsV3::getMerchantMembershipType($merchant_id)){
					if($merchant_type==1){
						return false;
					}			
				}
    		   break;
    		
    		case "sms-gateway":    
    		   $mechant_sms_enabled=Yii::app()->functions->getOptionAdmin('mechant_sms_enabled');
			   if ($mechant_sms_enabled=="yes"){
				   return false;	
			   }
    		   break;
    		   
    		case "purchasesms":   
    		case "purchasesmstransaction":
    		   $mechant_sms_purchase_disabled=Yii::app()->functions->getOptionAdmin('mechant_sms_purchase_disabled');
			   if ( $mechant_sms_purchase_disabled=="yes"){
					return false;
			   }
    		   break;
    		   
    		case "obdreceive":   
    		   $tag='obd';
    		  break;
    		  
    		/*case "pyp":
    		   $tag="paypal";
    		   break;*/
    		   
    		/*case "stp":
    		   $tag="stripe";
    		   break;   */
    		   
    		/*case "mcd":
    		   $tag="mercadopago";
    		   break;      */
    		   
    		   
    		case "commission":
    		case "statement":
    		case "earnings":
    		case "withdrawals":
    			$merchant_type =  self::getMerchantTypeBySession();
    			if($merchant_type==1){
    				return false;
    			}    	
    			break;
    		     
    		default:
    			break;
    	}
    	
    	/*if ( $tag=="obdreceive"){
			$tag='obd';
		}*/	
		
    	$payment_list=FunctionsV3::PaymentOptionList();		
		if (array_key_exists($tag,(array)$payment_list)){
			$list_payment_enabled=Yii::app()->functions->getMerchantListOfPaymentGateway();							
			if ( !in_array($tag,(array)$list_payment_enabled)){				 
				 return false;
			} else {
				$master_key="merchant_switch_master_$tag";
				$master_key_val=getOption($merchant_id,$master_key);
				if($master_key_val==1){
					return false;
				}			
			}
		}
    			
    	if(!empty($user_access[0])){    		
	    	if (in_array($tag,(array)$user_access)){
	    		return true;
	    	}
    	} else {       		
    		return true;
    	}    
    	
    	return false;
    }
    
    public static function getCreditCardInfo($client_id='', $id='')
	{
		$stmt="
		SELECT * FROM
		{{client_cc}}
		WHERE
		cc_id= ".FunctionsV3::q($id)."
		AND
		client_id=".self::q($client_id)."
		LIMIT 0,1
		";		
		$db=new DbExt();
		if($res = $db->rst($stmt)){
			return $res[0];
		}
		return false;
	}	           

	public static function isLangRTL($lang='')
	{		
		$lang_rtl = getOptionA('lang_rtl');
		if ( !empty($lang_rtl) && !empty($lang)){
			$lang_rtl=json_decode($lang_rtl);
			if(is_array($lang_rtl) && count((array)$lang_rtl)>=1){
				if(in_array(trim($lang),$lang_rtl)){
					return true;
				}
			}
		}
		return false;		
	}
	
	public static function updatePoints($order_id='', $order_status='')
	{
		if (FunctionsV3::hasModuleAddon('pointsprogram')){
			if (method_exists("PointsProgram","updateOrderBasedOnStatus")){
				PointsProgram::updateOrderBasedOnStatus($order_status,$order_id);
			}
		}
	}
	
    public static function purify($text='')
	{
		$p = new CHtmlPurifier();
		return $p->purify($text);
	}
	
	public static function purifyData($data=array())
	{
		if(is_array($data) && count($data)>=1){
			$p = new CHtmlPurifier(); $new_data=array();
			foreach ($data as $key=>$val) {
				$new_data[$key]=$p->purify($val);
			}
			return $new_data;
		}
		return false;
	}	
	
	public static function saveAddresBookByLocation($client_id='',$params=array())
	{
		$db=new DbExt();		
		
		if($params['country_id']<=0){
			if($resp=$db->rst("SELECT country_id FROM {{location_states}} 
			WHERE state_id=".self::q($params['state_id'])." LIMIT 0,1 ")){
			    $resp = $resp[0];
				$params['country_id']=$resp['country_id'];
			}
		}	
		$stmt="SELECT * FROM 
		{{address_book_location}}
		WHERE
		client_id=".self::q($client_id)."
		AND
		state_id=".self::q($params['state_id'])."
		AND
		city_id=".self::q($params['city_id'])."
		AND
		area_id=".self::q($params['area_id'])."
		";
		if(!$res=$db->rst($stmt)){
		   $db->insertData("{{address_book_location}}",$params);
		}
		return false;
	}	
	
	/*inventory*/
	public static function inventoryEnabled($merchant_id='')
	{		
		if(empty($merchant_id)){
			return false;
		}	
		if(!is_numeric($merchant_id)){
			return false;
		}	
		
		$inv_enabled = false; 
		if (FunctionsV3::hasModuleAddon('inventory')){
		    if(FunctionsV3::checkIfTableExist('view_item_stocks')){
		    	$inventory_live = getOption($merchant_id,'inventory_live');
		    	if($inventory_live==1){
		    		$inv_enabled = true;
			 		Yii::app()->setImport(array(			
				       'application.modules.inventory.components.*',
			        ));	
		    	}
		    }
		}
		return $inv_enabled;
	}
	
	public static function canCancel($date_created='', $days=0, $hours=0, $minutes=0)
	{
		if(!empty($date_created)){
		    $date_created = date("Y-m-d g:i:s a",strtotime($date_created));			
			$date_now=date('Y-m-d g:i:s a');			
			$time_diff=Yii::app()->functions->dateDifference($date_created,$date_now);			
			if(is_array($time_diff) && count($time_diff)>=1){				
				if($days>$time_diff['days']){					
					return true;
				} elseif ( $hours>$time_diff['hours'] ) {
					return true;					
				} elseif ( $hours>=$time_diff['hours']){					
					if($minutes<$time_diff['minutes']){						
						return false;
					} else return true;
				} elseif ( $minutes>=$time_diff['minutes'] ){					
					return true;					
				}
								
			} else return true;
		}
		return false;
	}
	
	public static function registerScript($script=array(), $script_name='reg_script')
	{
		$reg_script='';
		if(is_array($script) && count($script)>=1){		
			foreach ($script as $val) {
				$reg_script.="$val\n";
			}
			$cs = Yii::app()->getClientScript(); 
			$cs->registerScript(
			  $script_name,
			  "$reg_script",
			  CClientScript::POS_HEAD
			);		
		}
	}
	
	
	public static function registerJS($data=array())
	{
		$cs = Yii::app()->getClientScript();
		if(is_array($data) && count($data)>=1){
			foreach ($data as $link) {
				Yii::app()->clientScript->registerScriptFile($link,CClientScript::POS_END);
			}
		}		
	}
	
	public static function registerCSS($data=array())
	{		
		$cs = Yii::app()->getClientScript();		
		if(is_array($data) && count($data)>=1){
			foreach ($data as $link) {
				$cs->registerCssFile($link);
			}
		}		
	}	
	
	public static function createSlug($table='', $name='', $field='slug', $id='', $id_val='')
	{
		$slug = Yii::app()->functions->seo_friendly_url($name);
		$and='';
		$and.=" AND $field LIKE ".q("$slug%")." ";
		$id_val = (integer)$id_val;		
		if($id_val>0){
			$and.=" AND $id <>".q($id_val)." ";
		}
		
		$stmt="
		SELECT $field,count(*)as found FROM {{{$table}}}		
		WHERE 1
		$and
		group by slug
		LIMIT 0,1
		";		
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){		
			if($res['found']>0){				
				$name = $name.$res['found'];				
			   	return self::createSlug($table,$name,$field,$id,$id_val);
			}
		} 
		return $slug;
	}
	
	public static function getCuisineBySlug($slug='')
	{
		if(!empty($slug)){
			$stmt="SELECT cuisine_id
			FROM {{cuisine}}
			WHERE slug = ".q($slug)."
			LIMIT 0,1
			";
			if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
				return $res;
			}
		}
		return false;
	}
	
	public static function getNotificationTemplate($key='',$lang='',$type='email')
	{		
		$data = array();
		$resp = Yii::app()->db->createCommand()
	          ->select()
	          ->from('{{option}}')   
	          ->where(array('like', 'option_name', "$key%" ))	          	          
	          ->order('option_name ASC')
	          ->queryAll();	
	    if($resp){
	    	foreach ($resp as $val) {	    
	    		if($val['option_name']==$key."_email"){	    			
	    			$data['email_enabled']=$val['option_value'];
	    		} elseif ( $val['option_name']==$key."_sms"){
	    			$data['sms_enabled']=$val['option_value'];
	    		} elseif ( $val['option_name']==$key."_tpl_subject_$lang"){
	    			$data['email_subject']=$val['option_value'];
	    		} elseif ( $val['option_name']==$key."_tpl_content_$lang"){
	    			$data['email_content']=$val['option_value'];
	    		} elseif ( $val['option_name']==$key."_sms_content_$lang"){
	    			$data['sms_content']=$val['option_value'];
	    		}
	    	}
	    	if(is_array($data) && count($data)>=1){
	    		if($type=="email"){
		    		if($data['email_enabled']!=1){
		    			throw new Exception( tt("The template [tpl] is not enabled",array(
					      '[tpl]'=>$key
					    )));
		    		}	    		    		
		    		if(empty($data['email_subject'])){	
		    			throw new Exception( tt("The template [tpl] subject is empty",array(
					      '[tpl]'=>$key
					    )));
		    		}	    
		    		if(empty($data['email_content'])){
		    			throw new Exception( tt("The template [tpl] content is empty",array(
					      '[tpl]'=>$key
					    )));
		    		}	    
	    		} elseif ( $type=="sms"){
	    			if($data['sms_enabled']!=1){
		    			throw new Exception( tt("The template [tpl] is not enabled",array(
					      '[tpl]'=>$key
					    )));
		    		}	    		    		
		    		if(empty($data['sms_content'])){	
		    			throw new Exception( tt("The template [tpl] sms content is empty",array(
					      '[tpl]'=>$key
					    )));
		    		}	    		    		
	    		}	    	
	    		return $data;
	    	}
	    }
	    throw new Exception( tt("The template [tpl] does not exist",array(
	      '[tpl]'=>$key
	    )));
	}
	
	public static function replaceTags($tpl='', $data=array())
	{
		if(is_array($data) && count($data)>=1){
			foreach ($data as $key=>$val) {				
				$tpl = self::smarty($key,$val,$tpl);
			}
		}
		return $tpl;
	}
	
	public static function getCustomerByPhone($phone='')
	{		
		$resp = Yii::app()->db->createCommand()
	          ->select()
	          ->from('{{client}}')   
	          ->where(array('like', 'contact_phone', "%$phone%" ))		          
	          ->queryRow();	
	    if($resp){
	    	return $resp;
	    }
	    throw new Exception( t("We cannot find your phone number in our records") );
	}
	
	public static function updateCustomerProfile($client_id='', $params=array())
	{		
	  $resp =Yii::app()->db->createCommand()->update("{{client}}",$params,
      	    'client_id=:client_id',
      	    array(
      	      ':client_id'=>(integer)$client_id
      	    )
      	  );
	    if($resp){
	    	return true;
	    }
	    throw new Exception( t("Cannot update records") );
	}
	
	public static function getBankDeposit($order_id='')
	{		
	    $resp = Yii::app()->db->createCommand()
	          ->select()
	          ->from('{{bank_deposit}}')   
	          ->where("order_id=:order_id ",array(
	             ':order_id'=>$order_id
	          )) 
	          ->queryRow();	
	    if($resp){
	    	return $resp;
	    }
	    throw new Exception( t("We cannot find your phone number in our records") );
	}
	
	public static function insertCuisine($merchant_id='', $cuisine=array())
	{		
		$merchant_id = (integer)$merchant_id;
		
		Yii::app()->db->createCommand("DELETE FROM 
		{{cuisine_merchant}} WHERE merchant_id=".q($merchant_id)." ")->query();
		
		if(is_array($cuisine) && count($cuisine)>=1){
			foreach ($cuisine as $cuisine_id) {
				Yii::app()->db->createCommand()->insert("{{cuisine_merchant}}",array(
				  'merchant_id'=>(integer)$merchant_id,
				  'cuisine_id'=>(integer)$cuisine_id
				));
			}
		}
	}
	
	public static function getTagsByID($tag_id='')
	{		
	    $resp = Yii::app()->db->createCommand()
	          ->select()
	          ->from('{{tags}}')   
	          ->where("tag_id=:tag_id ",array(
	             ':tag_id'=>$tag_id
	          )) 
	          ->queryRow();	
	    if($resp){
	    	return $resp;
	    }
	    throw new Exception( t("We cannot find what your looking for") );
	}
	
	public static function getTags()
	{		
	    $resp = Yii::app()->db->createCommand()
	          ->select()
	          ->from('{{tags}}')   
	          ->order("tag_name asc")	  
	          ->limit(1000)     
	          ->queryAll();	
	    if($resp){
	    	return $resp;
	    }	    
	    return false;
	}
	
	public static function dropdownFormat($data=array(),$value='', $label='', $default_value=array())
	{				
		$list = array();
		if(is_array($default_value) && count($default_value)>=1){
			$list = $default_value;
		} else $list['']='';		
		if(is_array($data) && count($data)>=1){
			foreach ($data as $val) {
				if(isset($val[$value]) && isset($val[$label])){
			 	   $list[ $val[$value] ] = t($val[$label]);
				}
			}
		}
		$list = array_filter($list);
		return $list;
	}
	
	public static function insertTag($merchant_id=0, $tag_id = array())
	{
		$merchant_id = (integer)$merchant_id;		
		Yii::app()->db->createCommand("DELETE FROM 
		{{option}} WHERE merchant_id=".q($merchant_id)."  AND  option_name='tags' ")->query();
		
		if(is_array($tag_id) && count($tag_id)>=1 && $merchant_id>0){
		   foreach ($tag_id as $tagid) {
		      Yii::app()->db->createCommand()->insert("{{option}}",array(
				  'merchant_id'=>(integer)$merchant_id,
				  'option_name'=>'tags',
				  'option_value'=>(integer)$tagid
				));
		   }
		}
	}
	
    public static function getMerchant($merchant_id='')
	{		
		$stmt="
		SELECT a.*,
		(
		 select GROUP_CONCAT(option_value)
		 from {{option}}
		 where
		 merchant_id=a.merchant_id
		 and option_name='tags'
		) as tag_id
		FROM {{merchant}} a
		WHERE merchant_id=".q($merchant_id)."
		LIMIT 0,1
		";				
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){			
			return Yii::app()->request->stripSlashes($res);
		}
		return false;
	}		
	
	public static function getReceiptByID($order_id=0, $client_id=0)
	{
		$and='';
		$order_id = (integer)$order_id;
		$client_id = (integer)$client_id;
		if($client_id>0){
			$and=" AND a.client_id=".q($client_id)."  ";
		}	
		$stmt="
		SELECT a.*,
		concat(b.first_name,' ',b.last_name) as full_name,
		b.location_name,
		concat(b.street,' ',b.area_name,' ',b.city,' ',b.state,' ',b.zipcode) as full_address,
		b.contact_phone,
		b.contact_phone as customer_phone,
		b.opt_contact_delivery,
		b.contact_email as email_address,
		b.contact_email as customer_email,
		
		c.restaurant_name as merchant_name,
		c.restaurant_phone as merchant_contact_phone
		
		FROM {{order}} a
		left join {{order_delivery_address}} b
		on
		a.order_id = b.order_id
		
		left join {{merchant}} c
		on
		a.merchant_id = c.merchant_id
		
		WHERE
		a.order_id=".q($order_id)."
		$and
		LIMIT 0,1
		";			
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
			/*FIXED OLD DATA*/
			if(empty( trim($res['full_name']) )){				
				$stmt2 = "
				select 
				concat(first_name,' ',last_name) as full_name,
				contact_phone
				
				from {{client}}
				where client_id = ".q($res['client_id'])."
				";
				if($res2 = Yii::app()->db->createCommand($stmt2)->queryRow()){
					$res['full_name'] = $res2['full_name'];
					$res['contact_phone'] = $res2['contact_phone'];
				}
			}		
			return $res;
		}
		return false;
	}
	
	public static function getOrderInfoByTokenWithCustomerName($order_id_token='')
	{
		if(empty($order_id_token)){
			return false;
		}		
		
		$and='';
				
		$stmt="SELECT
		a.order_id,
		a.merchant_id,
		a.order_id_token,
		concat(b.first_name,' ',b.last_name) as customer_name,
		concat(c.first_name,' ',c.last_name) as client_name		
		
		FROM
		{{order}} a
		
		left join {{client}} b
		on
		a.client_id = b.client_id
		
		left join {{order_delivery_address}} c
		on
		a.order_id = c.order_id
		
		WHERE
		order_id_token = ".FunctionsV3::q($order_id_token)."
		LIMIT 0,1
		";		
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
			if(!empty($res['client_name'])){
				$res['customer_name']=$res['client_name'];
			}		
			return $res;
		}
		return false;
	}
	
	public static function consumeUrl($url='')
	{		
		$is_curl_working = true;
		$ch = curl_init();
	 	curl_setopt($ch, CURLOPT_URL, $url);
	 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	 	$result = curl_exec($ch);
	 	if (curl_errno($ch)) {		    
		    $is_curl_working = false;
		}
	 	curl_close($ch);
	 	
	 	if(!$is_curl_working){
	 		 $response = @file_get_contents($url);		 	 
		 	 if (isset($http_response_header)) {
		 	 	if (!in_array('HTTP/1.1 200 OK',(array)$http_response_header) && !in_array('HTTP/1.0 200 OK',(array)$http_response_header)) {
		 	 		//
		 	 	}
		 	 }
	 	}
	}
	
}/* end class*/


function iPay88_signature($source)
{
	return base64_encode(hex2bin(sha1($source)));
}

if (!function_exists('hex2bin'))
{
	function hex2bin($hexSource)
	{
		$bin = '';
		for ($i=0;$i<strlen($hexSource);$i=$i+2)
		{
		  $bin .= chr(hexdec(substr($hexSource,$i,2)));
		}
	  return $bin;
	}
}