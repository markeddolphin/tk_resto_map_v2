<?php
class ScriptManagerCompress
{
	public static function RegisterAllJSFile($config=array())
	{		
		$baseUrl = Yii::app()->baseUrl; 
        $cs = Yii::app()->getClientScript();
      
        $cs->registerScriptFile($baseUrl."/assets/vendor/compress/combine-vendor.js?v=1.0",CClientScript::POS_END);            
        $js_lang=Yii::app()->functions->jsLanguageAdmin();
        $js_lang_validator=Yii::app()->functions->jsLanguageValidator();
        
        $cs->registerScript(
		  'js_lang',
		  'var js_lang = '.json_encode($js_lang).'
		  ',
		  CClientScript::POS_HEAD
		);
								
		$cs->registerScript(
		  'jsLanguageValidator',
		  'var jsLanguageValidator = '.json_encode($js_lang_validator).'
		  ',
		  CClientScript::POS_HEAD
		);

		/*$cs->registerScript(
		  'ajax_url',
		  "var ajax_url ='".Yii::app()->request->baseUrl."/admin/ajax' ",
		  CClientScript::POS_HEAD
		);*/
		
		$cs->registerScript(
		  'ajax_url',
		  "var ajax_url ='".Yii::app()->request->baseUrl."/ajax' ",
		  CClientScript::POS_HEAD
		);
				
		$cs->registerScript(
		  'front_ajax',
		  "var front_ajax ='".Yii::app()->request->baseUrl."/ajax' ",
		  CClientScript::POS_HEAD
		);
					
		$cs->registerScript(
		  'admin_url',
		  "var admin_url ='".Yii::app()->request->baseUrl."/admin' ",
		  CClientScript::POS_HEAD
		);
		
		$cs->registerScript(
		  'sites_url',
		  "var sites_url ='".Yii::app()->request->baseUrl."' ",
		  CClientScript::POS_HEAD
		);
		
		$cs->registerScript(
		  'home_url',
		  "var home_url ='".Yii::app()->createUrl('/store')."' ",
		  CClientScript::POS_HEAD
		);
		
		$cs->registerScript(
		  'upload_url',
		  "var upload_url ='".Yii::app()->request->baseUrl."/upload' ",
		  CClientScript::POS_HEAD
		);
		
		
		$cs->registerScript(
		  'map_marker',
		  "var map_marker ='".FunctionsV3::getMapMarker()."' ",
		  CClientScript::POS_HEAD
		);		
		
		/*$cs->registerScriptFile("//google-maps-utility-library-v3.googlecode.com/svn/tags/markerclusterer/1.0/src/markerclusterer.js"
		,CClientScript::POS_END); */		
		
		$cs->registerScriptFile($baseUrl."/assets/vendor/markercluster.js"
		,CClientScript::POS_END); 
						
		if(!empty($config['google_key'])){
			$cs->registerScriptFile("//maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=".$config['google_key']
			,CClientScript::POS_END); 
		} else {
			$cs->registerScriptFile("//maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"
		    ,CClientScript::POS_END); 
		}
		
			
		if($config['theme_time_pick']==2){
			$cs->registerScriptFile($baseUrl."/assets/vendor/timepicker.co/jquery.timepicker.js"
			,CClientScript::POS_END);
		}
		
		/*LEAFLET*/				
		if($config['map_provider']=="mapbox"){
			$cs->registerScriptFile($baseUrl."/assets/vendor/leaflet/leaflet.js"
			,CClientScript::POS_END); 					
		}	
		/*LEAFLET*/
		
		$cs->registerScriptFile($baseUrl."/assets/vendor/typehead/bootstrap3-typeahead.min.js"
		,CClientScript::POS_END); 
		
		$cs->registerScriptFile($baseUrl."/assets/vendor/SimpleAjaxUploader.min.js"
		,CClientScript::POS_END);
		
		$cs->registerScriptFile($baseUrl."/assets/js/uploader.js"
		,CClientScript::POS_END); 
		
		$cs->registerScriptFile($baseUrl."/assets/js/store.js?ver=3"
		,CClientScript::POS_END); 
		
		$cs->registerScriptFile($baseUrl."/assets/js/store-v3.js?ver=3"
		,CClientScript::POS_END); 
		
		$cs->registerScriptFile($baseUrl."/assets/js/k_mapbox.js?ver=1.0"
		,CClientScript::POS_END); 
	}
	
	public static function registerAllCSSFiles($config=array())
	{
		$baseUrl = Yii::app()->baseUrl; 
		$cs = Yii::app()->getClientScript();
		
		$cs->registerCssFile("//fonts.googleapis.com/css?family=Open+Sans|Podkova|Rosario|Abel|PT+Sans|Source+Sans+Pro:400,600,300|Roboto|Montserrat:400,700|Lato:400,300,100italic,100,300italic,400italic,700,700italic,900,900italic|Raleway:300,400,600,800");
		
		if($config['theme_time_pick']==2){
		  $cs->registerCssFile($baseUrl."/assets/vendor/timepicker.co/jquery.timepicker.min.css");
		}
		
		/*LEAFLET*/
		if($config['map_provider']=="mapbox"){
			$cs->registerCssFile($baseUrl."/assets/vendor/leaflet/leaflet.css");			
		}
		/*LEAFLET*/
	}	
	
	public static function registerGlobalVariables()
	{				
		echo CHtml::hiddenField('fb_app_id',Yii::app()->functions->getOptionAdmin('fb_app_id'));
		echo CHtml::hiddenField('admin_country_set',Yii::app()->functions->getOptionAdmin('admin_country_set'));
		echo CHtml::hiddenField('google_auto_address',Yii::app()->functions->getOptionAdmin('google_auto_address'));
		echo CHtml::hiddenField('google_default_country',getOptionA('google_default_country'));
		echo CHtml::hiddenField('disabled_share_location',getOptionA('disabled_share_location'));
		
		echo CHtml::hiddenField('theme_time_pick',Yii::app()->functions->getOptionAdmin('theme_time_pick'));
		
		$website_date_picker_format=getOptionA('website_date_picker_format');
		if (!empty($website_date_picker_format)){
	        echo CHtml::hiddenField('website_date_picker_format',$website_date_picker_format);
        }
        $website_time_picker_format=yii::app()->functions->getOptionAdmin('website_time_picker_format');
        if ( !empty($website_time_picker_format)){
	        echo CHtml::hiddenField('website_time_picker_format',$website_time_picker_format);
        }
        echo CHtml::hiddenField('disabled_cart_sticky',getOptionA('disabled_cart_sticky'));
		echo "\n";
	}
	
} /*END CLASS*/