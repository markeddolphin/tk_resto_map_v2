<?php
//if (!isset($_SESSION)) { session_start(); }

class InstallController extends CController
{
	public $layout='install_tpl';	
	public $steps;
	
	public function init()
	{	
	}
	
	public function beforeAction($action)
	{
	
		$baseUrl = Yii::app()->baseUrl; 
        $cs = Yii::app()->getClientScript();
        
        /*JS FILE*/
        $cs->registerScriptFile($baseUrl."/assets/vendor/jquery-1.10.2.min.js",CClientScript::POS_END);                    
        $cs->registerScriptFile("//code.jquery.com/ui/1.10.3/jquery-ui.js"
		,CClientScript::POS_END); 
		
        $cs->registerScriptFile($baseUrl."/assets/vendor/bootstrap/js/bootstrap.min.js"
		,CClientScript::POS_END); 
		
		/*CSS FILE*/
		$cs->registerCssFile($baseUrl.'/assets/css/store.css?ver=1.0');		
		$cs->registerCssFile('//ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css');
		
	    $cs->registerCssFile("//fonts.googleapis.com/css?family=Open+Sans|Podkova|Rosario|Abel|PT+Sans|Source+Sans+Pro:400,600,300|Roboto|Montserrat:400,700|Lato:400,300,100italic,100,300italic,400italic,700,700italic,900,900italic|Raleway:300,400,600,800");				
	    $cs->registerCssFile("//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i");
						
		$cs->registerCssFile($baseUrl."/assets/vendor/font-awesome/css/font-awesome.min.css");			 
		$cs->registerCssFile($baseUrl."/assets/vendor/bootstrap/css/bootstrap.min.css");
		$cs->registerCssFile($baseUrl."/assets/css/install.css");			 

		if (FunctionsV3::checkIfTableExist('option')){
			$installation_done = Yii::app()->functions->getOptionAdmin("installation_done");			
			if($installation_done==1){			 
				return false;
			}
		}
		
		return true;
	}	
	
	public function actionIndex()
	{
		$this->steps=1;
		$this->render('step1');
	}
	
	public function actionstep2()
	{
		if($_SESSION['kr_install']==1){		   
			$this->steps=2;
		   $this->render('step2');
		} else $this->redirect(Yii::app()->createUrl('/index.php/install',array(
		 'error'=>"Session install not found"
		)));
	}
	
	public function actionstep3()
	{
		$this->steps=3;
		$country_list=require_once('CountryCode.php');
		$currency_list=InstallHelper::CurrencyList();
	    $currency_list=array_flip($currency_list);
		$this->render('step3',array(
		  'country_list'=>$country_list,
		  'currency_list'=>$currency_list
		));
	}
	
	public function actionfinish()
	{
	    $this->steps=4;	
		$data=$_POST; $code=2; $msg='';
		$DbExt=new DbExt;
			
		if (isset($data['username'])){
			if(!empty($data['username'])){
			   if($data['password']==$data['cpassword']){
					/*INSERT ADMIN USER*/
					$params=array(
					  'username'=>$data['username'],
					  'password'=>md5($data['password']),
					  'date_created'=>FunctionsV3::dateNow(),
					  'ip_address'=>$_SERVER['REMOTE_ADDR'],
					  'user_access'=>InstallHelper::UserAccessString(),
					  'first_name'=>isset($data['first_name'])?$data['first_name']:'',
					  'last_name'=>isset($data['last_name'])?$data['last_name']:'',
					  'email_address'=>isset($data['website_contact_email'])?$data['website_contact_email']:''
					);							
					$DbExt->qry("TRUNCATE TABLE {{admin_user}}");
					$DbExt->insertData("{{admin_user}}",$params);
					
					/*CURRENCY*/
					$DbExt->qry("TRUNCATE TABLE {{currency}}");
					$currency_list=InstallHelper::CurrencyList();	                
	                foreach ($currency_list as $val) {
	                	$curr=explode("-",$val);
	                	$params_currency=array(
	                	  'currency_code'=>$curr[0],
	                	  'currency_symbol'=>$curr[1],
	                	  'date_created'=>FunctionsV3::dateNow(),
	                	  'ip_address'=>$_SERVER['REMOTE_ADDR'],
	                	);	                	
					    $DbExt->insertData("{{currency}}",$params_currency);
	                }
	                
	                /*OPTIONS*/
	                $DbExt->qry("TRUNCATE TABLE {{option}}");
	                
	                $currency_set=explode("-",$data['admin_currency_set']);	                
	                if(is_array($currency_set) && count($currency_set)>=1){
	                	// DO NOTHING
	                } else $currency_set[0]="USD";
	                
	                Yii::app()->functions->updateOptionAdmin("website_title",
	    	        isset($data['website_title'])?$data['website_title']:'' );	    	
	    	        
	    	        Yii::app()->functions->updateOptionAdmin("website_address",
	    	        isset($data['website_address'])?$data['website_address']:'' );    	
	    	        
	    	        Yii::app()->functions->updateOptionAdmin("admin_country_set",
	    	        isset($data['admin_country_set'])?$data['admin_country_set']:'' );	
	    	        
	    	        Yii::app()->functions->updateOptionAdmin("website_contact_phone",
	    	        isset($data['website_contact_phone'])?$data['website_contact_phone']:'' );	
	    	        
	    	        Yii::app()->functions->updateOptionAdmin("website_contact_email",
	    	        isset($data['website_contact_email'])?$data['website_contact_email']:'' );	
	    	        
	    	        Yii::app()->functions->updateOptionAdmin("website_contact_email",$currency_set[0]);	
	    	        	    	     
	    	        
	    	        /*ORDER STATUS*/
	    	        $DbExt->qry("TRUNCATE TABLE {{order_status}}");	    	        
	    	        $status=InstallHelper::OrderStatusList();
	    	        foreach ($status as $val) {
	    	        	$val['date_created']=FunctionsV3::dateNow();
	    	        	$DbExt->insertData("{{order_status}}",$val);
	    	        }
	    	        
	    	        /*RATING*/   
	    	        $DbExt->qry("TRUNCATE TABLE {{rating_meaning}}");	    	        
	    	        $list=InstallHelper::RatingList();
	    	        foreach ($list as $val) {
	    	        	$val['date_created']=FunctionsV3::dateNow();
	    	        	$DbExt->insertData("{{rating_meaning}}",$val);
	    	        }
	    	        
	    	        /*CUISINE*/
	    	        $DbExt->qry("TRUNCATE TABLE {{cuisine}}");	    	        
	    	        $list=InstallHelper::CuisineList();
	    	        foreach ($list as $val) {
	    	        	$val['date_created']=FunctionsV3::dateNow();
	    	        	$val['cuisine_name_trans']='';
	    	        	$DbExt->insertData("{{cuisine}}",$val);
	    	        }
	    	        
	    	        /*COUNTRY LIST*/
	    	        $DbExt->qry("TRUNCATE TABLE {{location_countries}}");
	    	        $stmt_country=InstallHelper::CountryList();
	    	        $stmt="
	    	        INSERT INTO {{location_countries}} (`country_id`, `shortcode`, `country_name`, `phonecode`) 
	    	        $stmt_country
	    	        ";
	    	        $DbExt->qry($stmt);
	    	        	    	        
	    	        /*INSERT DEFAULT TEMPLATE*/
	    	        $options_template=InstallHelper::optionDefaultValue();
	    	        foreach ($options_template as $option_params) {
	    	        	$DbExt->insertData("{{option}}",$option_params);
	    	        }
	    	        
	    	        /*MARK THE INSTALLATION AS DONE*/
	    	        Yii::app()->functions->updateOptionAdmin("installation_done",1);	
	    	        	    	        
	    	        $code=1;
					
			   } else $msg="Confirm password does not match";
			} else $msg="Missing data";
		} else $msg="Missing data";
		
		
		$this->render('finish',array(
		  'code'=>$code,
		  'msg'=>$msg
		));
	}
	
} /*end class*/