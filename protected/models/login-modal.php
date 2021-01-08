
<?php echo CHtml::hiddenField('mobile_country_code',Yii::app()->functions->getAdminCountrySet(true));?>

<div class="login-modal-wrap">
 <div class="modal-header">
   <h3 class="left"><?php echo Yii::t("default","Login & Signup")?></h3>
   <a class="right fc-close" href="javascript:;"><i class="fa fa-times"></i></a>
   <div class="clear"></div>
 </div>
 <div class="modal-body">
   
  <div class="section1">
   <!--<a href="javascript:;" class="fb-link-login rounded2">
     <div class="label"><i class="fa fa-facebook"></i></div>
     <span>Log in with facebook</span>
     <div class="clear"></div>
   </a>  -->
   
   <?php 
   $fb_flag=Yii::app()->functions->getOptionAdmin('fb_flag');
   $fb_app_id=getOptionA('fb_app_id');   
   ?>
   
   <?php if ( $fb_flag=="" && !empty($fb_app_id) ):?>
   <div class="sigin-fb-wrap">
   <fb:login-button scope="public_profile,email" onlogin="checkLoginState();"><?php echo Yii::t('default',"Sign in with Facebook")?></fb:login-button>
   </div> <!--sigin-fb-wrap-->
   
  <?php if ( yii::app()->functions->getOptionAdmin('google_login_enabled')==2):?>
   <a class="google-login" href="<?php echo websiteUrl()."/store/GoogleLogin"; ?>">
   <i class="fa fa-google-plus"></i> <?php echo t("Sign in with Google")?>
   </a>
   <?php endif;?>
   
   <p class="uk-text-muted"><?php echo Yii::t("default","Or use your email address")?></p>
   <?php endif;?>     
   
   <div class="login-btn-wrap">
     <a href="javascript:;" class="login-link uk-button"><?php echo Yii::t("default","Login")?></a>
     <a href="javascript:;" class="signup-link uk-button" style="margin-right:0px;"><?php echo Yii::t("default","Sign up")?></a>
   </div>
 </div> <!-- section1--> 
  
 <div class="section2"> 
      <form id="frm-modal-login" class="frm-modal-login uk-panel uk-panel-box uk-form" method="POST" onsubmit="return false;">
         <?php echo CHtml::hiddenField('action','clientLoginModal')?>
         <?php echo CHtml::hiddenField('do-action', isset($_GET['do-action'])?$_GET['do-action']:'' )?>
         <?php echo CHtml::hiddenField('rating', isset($_GET['rating'])?$_GET['rating']:'' )?>
         <h3><?php echo Yii::t("default","Log in to your account")?></h3>
         <div class="uk-form-row">
           <?php echo CHtml::textField('username','',
            array('class'=>'uk-width-1-1','placeholder'=>Yii::t("default","Email"),
           'data-validation'=>"required"))?>
         </div>
         <div class="uk-form-row">
         <?php echo CHtml::passwordField('password','',
         array('class'=>'uk-width-1-1','placeholder'=>Yii::t("default","Password"),'data-validation'=>"required"))?>
         </div>
         
         <?php if ( getOptionA('captcha_customer_login')==2):?>
         <div class="recaptcha" id="RecaptchaField1"></div>
         <?php endif;?>
         
         <div class="uk-form-row">
         <input type="submit" value="<?php echo Yii::t("default","Login")?>" class="uk-button uk-width-1-1 uk-button-success">
         </div>
      </form>      
            
      <a href="javascript:;" class="back-link left"><i class="fa fa-angle-left"></i> <?php echo Yii::t("default","Back")?></a>      
      <a href="javascript:;" class="forgot-pass-link right"><?php echo Yii::t("default","Forgot Password")?>?</a>      
      <div class="clear"></div>
      
 </div> <!--section2-->
 
 <div class="section-forgotpass">
    <form id="frm-modal-forgotpass" class="frm-modal-forgotpass uk-panel uk-panel-box uk-form" method="POST" onsubmit="return false;">
    <?php echo CHtml::hiddenField('action','forgotPassword')?>
     <?php echo CHtml::hiddenField('do-action',$_GET['do-action'])?>     
     <h3><?php echo Yii::t("default","Forgot Password")?></h3>
         
    <div class="uk-form-row">
       <?php echo CHtml::textField('username-email','',
        array('class'=>'uk-width-1-1','placeholder'=>Yii::t("default","Email address"),
       'data-validation'=>"email"))?>
     </div>
         
    <div class="uk-form-row">
      <input type="submit" value="<?php echo Yii::t("default","Retrieve Password")?>" class="uk-button uk-width-1-1 uk-button-success">
    </div>     
     
    </form>
    
    <a href="javascript:;" class="back-link left"><i class="fa fa-angle-left"></i> <?php echo Yii::t("default","Back")?></a>      
    <div style="height:10px;"></div>
        
 </div> <!--section-forgotpass-->
  
 <div class="section3"> 
  <form id="form-signup" class="form-signup uk-panel uk-panel-box uk-form" method="POST" onsubmit="return false;">
    <?php echo CHtml::hiddenField('action','clientRegistrationModal')?>
    <?php 
    $verification=Yii::app()->functions->getOptionAdmin("website_enabled_mobile_verification");	    
    if ( $verification=="yes"){
        echo CHtml::hiddenField('verification',$verification);
    }
    ?>
    
    <?php //echo CHtml::hiddenField('redirect',Yii::app()->request->baseUrl."/store/paymentOption")?>    
     <h3><?php echo Yii::t("default","Sign up")?></h3>
     <div class="uk-form-row">
      <?php echo CHtml::textField('first_name','',array(
       'class'=>'uk-width-1-1',
       'placeholder'=>Yii::t("default","First Name"),
       'data-validation'=>"required"
      ))?>
     </div>
     <div class="uk-form-row">
      <?php echo CHtml::textField('last_name','',array(
       'class'=>'uk-width-1-1',
       'placeholder'=>Yii::t("default","Last Name"),
       'data-validation'=>"required"
      ))?>
     </div>
     <div class="uk-form-row">
      <?php echo CHtml::textField('contact_phone','',array(
       'class'=>'uk-width-1-1 mobile_inputs',
       'placeholder'=>yii::t("default","Mobile"),
       'data-validation'=>"required"
      ))?>
     </div>
     <div class="uk-form-row">
      <?php echo CHtml::textField('email_address','',array(
       'class'=>'uk-width-1-1',
       'placeholder'=>yii::t("default","Email address"),
       'data-validation'=>"email"
      ))?>
     </div>
     
     <?php 
     $FunctionsK=new FunctionsK();
     $FunctionsK->clientRegistrationCustomFields();
     ?>
               
     <div class="uk-form-row">
      <?php echo CHtml::passwordField('password','',array(
       'class'=>'uk-width-1-1',
       'placeholder'=>Yii::t("default","Password"),
       'data-validation'=>"required"
      ))?>
     </div>
          
     <div class="uk-form-row">
      <?php echo CHtml::passwordField('cpassword','',array(
       'class'=>'uk-width-1-1',
       'placeholder'=>Yii::t("default","Confirm Password"),
       'data-validation'=>"required"       
      ))?>      
     </div>
     
     <?php if (getOptionA('captcha_customer_signup')==2):?>
     <div class="recaptcha" id="RecaptchaField2"></div>
     <?php endif;?> 
     
     <p class="uk-text-muted" style="text-align: left;">
        <?php echo Yii::t("default","By creating an account, you agree to receive sms from vendor.")?>
     </p>
     
     
  <?php if ( Yii::app()->functions->getOptionAdmin('website_terms_customer')=="yes"):?>
  <?php 
  $terms_link=Yii::app()->functions->getOptionAdmin('website_terms_customer_url');
  $terms_link=Yii::app()->functions->prettyLink($terms_link);
  ?>
  <div class="uk-form-row">
  <label class="uk-form-label"></label>
  <?php 
  echo CHtml::checkBox('terms_n_condition',false,array(
   'value'=>2,
   'class'=>"",
   'data-validation'=>"required"
  ));
  echo " ". t("I Agree To")." <a href=\"$terms_link\" target=\"_blank\">".t("The Terms & Conditions")."</a>";
  ?>  
  </div>  
  <?php endif;?>
       
     
     <div class="uk-form-row">
     <input type="submit" value="<?php echo Yii::t("default","Create Account") ?>" class="uk-button uk-width-1-1 uk-button-primary">
     </div>
  </form>
  <a href="javascript:;" class="back-link"><i class="fa fa-angle-left"></i> <?php echo Yii::t("default","Back")?></a>
 </div> <!--section3-->
   
 </div> <!--modal-body-->
</div> <!--end login-modal-wrap-->

<script type="text/javascript">
$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm-modal-login',    
    onError : function() {      
    },
    onSuccess : function() {     
      form_submit('frm-modal-login');
      return false;
    }  
});

$.validate({ 	
	language : jsLanguageValidator,
    form : '#form-signup',    
    onError : function() {      
    },
    onSuccess : function() {           
      form_submit('form-signup');
      return false;
    }  
});

$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm-modal-forgotpass',    
    onError : function() {      
    },
    onSuccess : function() {           
      form_submit('frm-modal-forgotpass');
      return false;
    }  
});

jQuery(document).ready(function() {	
	if ( $(".mobile_inputs").exists()){
		if ( $("#mobile_country_code").exists()){
			$(".mobile_inputs").intlTelInput({      
		        autoPlaceholder: false,
		        defaultCountry: $("#mobile_country_code").val(),    
		        autoHideDialCode:true,    
		        nationalMode:false,
		        autoFormat:false,
		        utilsScript: sites_url+"/assets/vendor/intel/lib/libphonenumber/build/utils.js"
		     });
		} else {
			 $(".mobile_inputs").intlTelInput({      
		        autoPlaceholder: false,		        
		        autoHideDialCode:true,    
		        nationalMode:false,
		        autoFormat:false,
		        utilsScript: sites_url+"/assets/vendor/intel/lib/libphonenumber/build/utils.js"
		     });
		}
	}
});	

jQuery(document).ready(function() {	
	
	if ( !$(".recaptcha").exists()){
		return;
	}
	if (typeof captcha_site_key === "undefined" || captcha_site_key==null ) {   
		return;
	}	
	if ( $("#RecaptchaField1").exists() ){
		dump('RecaptchaField1');
        recaptcha1=grecaptcha.render('RecaptchaField1', {'sitekey' : captcha_site_key});    
	}
	if ( $("#RecaptchaField2").exists() ){
        recaptcha2=grecaptcha.render('RecaptchaField2', {'sitekey' : captcha_site_key});    
	}
	
});	
<?php if ( $fb_flag=="" && !empty($fb_app_id)):?>
window.fbAsyncInit();
<?php endif;?>
</script>