<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>

<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/admin.css" rel="stylesheet" />

<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" rel="stylesheet" />

<link rel="shortcut icon" href="<?php echo  Yii::app()->request->baseUrl; ?>/favicon.ico" />

<!--START Google FOnts-->
<link href='//fonts.googleapis.com/css?family=Open+Sans|Podkova|Rosario|Abel|PT+Sans|Source+Sans+Pro:400,600,300|Roboto' rel='stylesheet' type='text/css'>
<!--END Google FOnts-->

<!--FONT AWESOME-->
<!--<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">-->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<!--END FONT AWESOME-->

<!--UIKIT-->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/vendor/uikit/css/uikit.almost-flat.min.css" rel="stylesheet" />
<!--<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/vendor/uikit/css/uikit.gradient.min.css" rel="stylesheet" />-->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/vendor/uikit/css/addons/uikit.addons.min.css" rel="stylesheet" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/vendor/uikit/css/addons/uikit.gradient.addons.min.css" rel="stylesheet" />
<!--UIKIT-->

<!--COLOR PICK-->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/vendor/colorpick/css/colpick.css" rel="stylesheet" />
<!--COLOR PICK-->

<!--ICHECK-->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/vendor/iCheck/skins/all.css" rel="stylesheet" />
<!--ICHECK-->

<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/vendor/chosen/chosen.css" rel="stylesheet" />

<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/vendor/fancybox/source/jquery.fancybox.css" rel="stylesheet" />

<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/vendor/jQuery-TE_v.1.4.0/jquery-te-1.4.0.css" rel="stylesheet">

<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/vendor/rupee/rupyaINR.css" rel="stylesheet" />

</head>
<body id="admin">

<?php  $admin_info=(array)Yii::app()->functions->getAdminInfo(); ?>

<?php if ($this->needs_db_update):?>
<div style="background:#f3989b;padding:5px;color:#fff;text-align:center;">
<?php echo t("Your database needs update")?> 
<a href="<?php echo Yii::app()->createUrl('/update')?>" target="_blank"><?php echo t("click here")?></a> 
<?php echo t("to update your database")?>
</div>
<?php endif;?>


<div class="header_wrap">
  <div class="left">
   <h1><?php echo Yii::t("default","ADMIN")?></h1>
  </div>
      
  <div class="right">  
      
  <div class="left" style="width:150px;">
  <a target="_blank" class="uk-button" href="<?php echo Yii::app()->createUrl('/')?>/"><i class="fa fa-cutlery"></i> <?php echo t("View Website")?></a>
  </div>
  
	<div data-uk-dropdown="{mode:'click'}" class="uk-button-dropdown">
	<button class="uk-button"><i class="fa fa-user"></i> <?php echo $admin_info['username'] ?> <i class="uk-icon-caret-down"></i></button>
	<div class="uk-dropdown" >
	<ul class="uk-nav uk-nav-dropdown">
	    <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/profile"><i class="fa fa-user"></i> <?php echo Yii::t("default","Profile")?></a></li>	    
	    <li><a href="<?php echo Yii::app()->request->baseUrl."/admin/login/logout/true"?>"><i class="fa fa-sign-out"></i> <?php echo Yii::t("default","Logout")?> </a></li>	    
	</ul>
	</div>
	</div>
    
  </div> <!--END RIGHT-->
  
   
  <div class="right">
  <h3 class="uk-button uk-button-danger"><?php echo t("Commission last 30 days")?>: 
  <span class="commission_total_1 commission_loader"></span>
  </h3>
  </div> <!--end right-->  
  
  <div class="right">
  <h3 class="uk-button uk-button-success"><?php echo t("Commission today")?>: 
  <span class="commission_total_2 commission_loader"></span>
  </h3>
  </div> <!--end right-->
  
  
  <div class="right">
  <h3 class="uk-button uk-button-primary"><?php echo t("Total Commission")?>: 
  <span class="commission_total_3 commission_loader"></span>
  </h3>
  </div> <!--end right-->
  
  
  <div class="clear"></div>
</div> <!--END header_wrap-->

<div class="main_wrapper">
  <div class="left_panel left">
      <div class="menu">        
        <?php $this->widget('zii.widgets.CMenu', Yii::app()->functions->adminMenu());?>
      </div>
  </div>
  <div class="left main_content">
     <div class="inner">
       <div class="breadcrumbs">
        <div class="inner">
          <h2 class="uk-h2"><?php echo $this->crumbsTitle;?></h2>
          
          <div class="language-wrapper">
          
           <div data-uk-dropdown="{mode:'click'}" class="uk-button-dropdown">
             <button class="uk-button uk-button-primary"><i class="fa fa-bell"></i> 
              <div class="uk-badge system_notification"></div>
             </button>
             <div class="uk-dropdown" >
                <ul class="uk-nav uk-nav-dropdown system_notification_list">                 
                </ul>
             </div>
           </div>
          
          
          <?php Widgets::languageBar("admin",true);?>
          </div>
          
        </div>
       </div> <!--breadcrumbs-->
       
       <div class="content_wrap">
         <?php echo $content;?>
       </div>
       
     </div> <!--INNER-->
  </div>
  <div class="clear"></div>
</div> <!--END main_wrapper-->

<?php echo CHtml::hiddenField("currentController","admin")?>
<?php echo CHtml::hiddenField("wd_payout_alert",yii::app()->functions->getOptionAdmin('wd_payout_notification'))?>

<?php 
$website_date_picker_format=yii::app()->functions->getOptionAdmin('website_date_picker_format');
if (!empty($website_date_picker_format)){
	echo CHtml::hiddenField('website_date_picker_format',$website_date_picker_format);
}
$website_time_picker_format=yii::app()->functions->getOptionAdmin('website_time_picker_format');
if ( !empty($website_time_picker_format)){
	echo CHtml::hiddenField('website_time_picker_format',$website_time_picker_format);
}
?>

<!--*****************************************
NOTIFICATION PLAYER STARTS HERE
*****************************************-->
<div style="display:none;">
	<div id="jquery_jplayer_1"></div>
	<div id="jp_container_1">
	  <a href="#" class="jp-play">Play</a>
	  <a href="#" class="jp-pause">Pause</a>
	</div>
</div>
<!--*****************************************
NOTIFICATION PLAYER END HERE
*****************************************-->

<!--PRELOADER-->
<div class="main-preloader">
   <div class="inner">
   <div class="ploader"></div>
   </div>
</div> 
<!--PRELOADER-->

<audio id="my_notification">  
  <source src="<?php echo Yii::app()->request->baseUrl."/assets/sound/notify.ogg";?>" type="audio/ogg">
  <source src="<?php echo Yii::app()->request->baseUrl."/assets/sound/notify.mp3";?>" type="audio/mpeg">  
</audio>


<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/vendor/jquery-1.10.2.min.js" type="text/javascript"></script>  
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/vendor/jquery.printelement.js" type="text/javascript"></script>  

<?php $js_lang=Yii::app()->functions->jsLanguageAdmin(); ?>
<?php $js_lang_validator=Yii::app()->functions->jsLanguageValidator();?>
<script type="text/javascript">
var js_lang=<?php echo json_encode($js_lang)?>;
var jsLanguageValidator=<?php echo json_encode($js_lang_validator)?>;
</script>

<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/vendor/DataTables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/vendor/DataTables/fnReloadAjax.js" type="text/javascript"></script>


<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/vendor/JQV/form-validator/jquery.form-validator.min.js" type="text/javascript"></script>

<script src="//code.jquery.com/ui/1.10.3/jquery-ui.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/vendor/jquery.ui.timepicker-0.0.8.js" type="text/javascript"></script>


<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/vendor/SimpleAjaxUploader.min.js" type="text/javascript"></script>


<!--UIKIT-->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/vendor/uikit/js/uikit.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/vendor/uikit/js/addons/notify.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/vendor/uikit/js/addons/sticky.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/vendor/uikit/js/addons/sortable.min.js"></script>
<!--UIKIT-->

<!--ICHECK-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/assets/vendor/iCheck/icheck.js"></script>
<!--ICHECK-->

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/assets/vendor/chosen/chosen.jquery.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/vendor/fancybox/source/jquery.fancybox.js"></script>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/assets/vendor/jQuery-TE_v.1.4.0/jquery-te-1.4.0.min.js"></script>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/admin.js?ver=1" type="text/javascript"></script>  

</body>

</html>