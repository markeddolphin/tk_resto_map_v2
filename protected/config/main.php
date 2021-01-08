<?php
$patern="cuisine|signup|signin|merchantsignup|contact|searcharea";
$patern.="|menu|checkout|paymentoption|receipt|logout|paypalinit|paypalverify";
$patern.="|OrderHistory|Profile|howItWork|forgotPassword|PageSetlanguage|stripeInit";
$patern.="|MercadoInit|RenewSuccesful|Browse|PaylineInit|Paylineverify|sisowinit";
$patern.="|PayuInit|BankDepositverify|AutoResto|AutoStreetName|AutoCategory|PayseraInit";
$patern.="|AutoFoodName|Confirmorder|Paymentbcy|Bcyinit|EpayBg|EpyInit";
$patern.="|GuestCheckout|MerchantSignupSelection|MerchantSignupinfo|CancelWithdrawal";
$patern.="|ATZinit|DepositVerify|Verification|Map|GoogleLogin|AddressBook";
$patern.="|AutoZipcode|AutoPostAddress|Item|Ty|EmailVerification|MyPoints|BtrInit|setlanguage";
$patern.="|monerisinit|confirmorder|rzrinit|rzrverify|acceptorder|declineorder|cart|restaurant";
$patern.="|voguepaynotify|voguepaysuccess|voguepayfailed|voginit|vognotify|voginit|vogsuccess";
$patern.="|ipayinit|ipay_success_url|ipay_ipn_url|admin_ipay_ipn_url|admin_ipay_success_url";
$patern.="|pipayinit|pipayconfirm";
$patern.="|sofortinit|sofort_success|sofort_notify|sofort_update_order";
$patern.="|success_jampie|cancel_jampie|jampieinit";
$patern.="|winginit|wing_notify";
$patern.="|paymill_transaction";
$patern.="|strip_idealinit|strip_ideal|stripe_ideal_webhook|ideal_mobile|ideal_receipt";
$patern.="|ipay_africainit|ipay_africa_buy|ipay_africa_pay";
$patern.="|dixipayinit|dixipay_process";
$patern.="|mollieprocess_mobile";
$patern.="|wirecardinit|wirecard_confirm|wirecard_process";
$patern.="|payulataminit|payulatam_pay|add_review";
$patern.="|pskinit|pskinit_success|paystack_success|paystackinit|paystackwebhook";
$patern.="|driver_signup|driver_signup_ty";
$patern.="|viva_verify|viva_failed|vivainit";
$patern.="|stripe_success|stripe_cancel|stripe_webhook";
$patern.="|paypal_init_reg|paypal_success|paypal_v2init";
$patern.="|mercadopago_success|mercadopago_failure|mercadopagoinit|changepassword_sms";

$patern=strtolower($patern);

return array(
	'name'=>'Karinderia Multiple Restaurant',
	
	'defaultController'=>'store',
		
	'import'=>array(
		'application.models.*',
		'application.models.admin.*',
		'application.components.*',
		'application.vendor.*',
		'application.modules.pointsprogram.components.*',
		'application.modules.mobileapp.components.*',
		'application.modules.merchantapp.components.*',
		'application.modules.driver.components.*',
	),
	
	'language'=>'en',
	
	'params'=>array(	   
	   'validate_request_session'=>true,
	   'validate_request_csrf'=>true,
	),
			
	'modules'=>array(		
		'exportmanager'=>array(
		  'require_login'=>true
		),
		'mobileapp'=>array(
		  'require_login'=>true
		),
		'pointsprogram'=>array(
		  'require_login'=>true
		),
		'merchantapp'=>array(
		  'require_login'=>true
		),
		'driver'=>array(
		  'require_login'=>true
		),		
		'printer'=>array('require_login'=>true),
		'singlemerchant'=>array(),
		'mobileappv2'=>array(),
		'inventory'=>array(),
		'merchantappv2'=>array(),
	),
	
	'components'=>array(		   
	    'request'=>array(
	        'class' => 'application.components.HttpRequest',
            'enableCsrfValidation'=>true,
            'noCsrfValidationRoutes'=>array(	            
	            'ajaxadmin/uploadFile',
	            'ajaxmerchant/uploadFile',
	            'ajaxmerchant/MultipleUploadFile',
	            'ajax/UploadProfile',
	            'ajax/UploadDeposit',
	            'mobileapp/ajax/upload',
	            'singlemerchant/api',	            
	            'driver/ajax/uploadprofilephoto',
	            'driver/ajax/uploadCertificate',
	            'exportmanager/ajax/importMerchant',
	            'mobileapp/api',	
	            'merchantapp/api',
	            'driver/api',
	            'store/voginit',
                'store/vognotify',
                'store/vogsuccess',
                'store/voguepaynotify',
                'store/voguepaysuccess',
                'store/voguepayfailed',
                'store/merchantsignup',
                'stripe/webhooks',
                'merchant/vogsuccess',
                'admin/merchantAddBulk',
                'mobileapp/voguepay',
                'mobileapp/braintree',
                'store/epaybg',
                'store/mollie_webhook',
                'singlemerchant/ajax/upload',
                'singlemerchant/voguepay',
                'singlemerchant/braintree',                
				'mobileappv2/api', 
				'mobileappv2/voguepay',
				'mobileappv2/braintree',
				'mobileappv2/ajax/uploadFile',
				'inventory/upload',
				'singlemerchant/payu',
				'mobileappv2/payu',
				'merchantappv2/api'
	        ),
        ),
        'session' => array(
	        'timeout' => 86400,
	    ),
	    'urlManager'=>array(
	        'class' => 'UrlManager', 
		    'urlFormat'=>'path',		    
		    'showScriptName'=>false,		    
		    'rules'=>array(		
		        '' => 'store/index',		        
		        '<action:('.$patern.')>' => 'store/<action>',
		        'menu/<slug:[\w\-]+>'=>'store/menu',
		        'cuisine/<slug:[\w\-]+>'=>'store/cuisine',
		        'page/<slug:[\w\-]+>'=>'store/page',
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
		        '<controller:\w+>'=>'<controller>/index',		         
		        '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
		        '<controller:\w+>/<action:\w+>'=>'<controller>/<action>'		        
		    )		    
		),
				
		'db'=>array(	        
		    'class'            => 'CDbConnection' ,
			'connectionString' => 'mysql:host=localhost;dbname=kmrs',
			'emulatePrepare'   => true,
			'username'         => 'root',
			'password'         => '',
			'charset'          => 'utf8',
			'tablePrefix'      => 'mt_',
	    ),
		
	    'functions'=> array(
	       'class'=>'Functions'	       
	    ),
	    'validator'=>array(
	       'class'=>'Validator'
	    ),
	    'widgets'=> array(
	       'class'=>'Widgets'
	    ),
	    	    
	    'Smtpmail'=>array(
	        'class'=>'application.extension.smtpmail.PHPMailer',
	        'Host'=>"YOUR HOST",
            'Username'=>'YOUR USERNAME',
            'Password'=>'YOUR PASSWORD',
            'Mailer'=>'smtp',
            'Port'=>587, // change this port according to your mail server
            'SMTPAuth'=>true,   
            'ContentType'=>'UTF-8',
            //'SMTPSecure'=>'ssl'// tls
	    ), 
	    
	    'GoogleApis' => array(
	         'class' => 'application.extension.GoogleApis.GoogleApis',
	         'clientId' => '', 
	         'clientSecret' => '',
	         'redirectUri' => '',
	         'developerKey' => '',
	    ),
	),
);

function statusList()
{
	return array(
	 'publish'=>Yii::t("default",'Publish'),
	 'pending'=>Yii::t("default",'Pending for review'),
	 'draft'=>Yii::t("default",'Draft')
	);
}

function clientStatus()
{
	return array(
	  'pending'=>Yii::t("default",'pending for approval'),
	 'active'=>Yii::t("default",'active'),	 
	 'suspended'=>Yii::t("default",'suspended'),
	 'blocked'=>Yii::t("default",'blocked'),
	 'expired'=>Yii::t("default",'expired')
	);
}

function paymentStatus()
{
	return array(
	 'pending'=>Yii::t("default",'pending'),
	 'paid'=>Yii::t("default",'paid'),
	 'draft'=>Yii::t("default",'Draft')
	);
}

function dump($data=''){
    echo '<pre>';print_r($data);echo '</pre>';
}