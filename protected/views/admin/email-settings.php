<?php 
$email_provider=getOptionA('email_provider');
?>

<form class="uk-form uk-form-horizontal admin-settings-page forms" id="forms" style="padding:10px;">
<?php echo CHtml::hiddenField('action','emailSettings')?>

<ul data-uk-tab="{connect:'#tab-content'}" class="uk-tab uk-active">
<li class="<?php echo $email_provider=="phpmail"?"uk-active":'';?>"><a href="#"><?php echo t("PHP Mail")?></a></li>
<li class="<?php echo $email_provider=="smtp"?"uk-active":'';?>"><a href="#"><?php echo t("SMTP")?></a></li>
<li class="<?php echo $email_provider=="mandrill"?"uk-active":'';?>"><a href="#"><?php echo t("Mandrill API")?></a></li>
<li class="<?php echo $email_provider=="sendgrid"?"uk-active":'';?>"><a href="#"><?php echo t("SendGrid")?></a></li>
<li class="<?php echo $email_provider=="mailjet"?"uk-active":'';?>"><a href="#"><?php echo t("MailJet")?></a></li>
<li class="<?php echo $email_provider=="elasticemail"?"uk-active":'';?>"><a href="#"><?php echo t("Elastic Email")?></a></li>
</ul>     

<ul class="uk-switcher uk-margin email-settings" id="tab-content">

   <li class="<?php echo $email_provider=="phpmail"?"uk-active":'';?>">
	<?php 
   echo CHtml::radioButton('email_provider',
   $email_provider=="phpmail"?true:false
   ,array(
    'class'=>"icheck",
    'value'=>"phpmail"
   ));
   echo "<span>".t("php mail functions")."</span>";
   ?>
	</li>
	
	<li class="<?php echo $email_provider=="smtp"?"uk-active":'';?>">
	
	<div class="uk-form-row">
	<?php 
   echo CHtml::radioButton('email_provider',
   $email_provider=="smtp"?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'smtp'
   ));
   echo "<span>".t("SMTP")."</span>";
    ?>   
    </div>
    
    <div class="uk-form-row">
	  <label class="uk-form-label"><?php echo Yii::t("default","SMTP host")?></label>  
	  <?php 
	  echo CHtml::textField('smtp_host',
	  Yii::app()->functions->getOptionAdmin('smtp_host'),
	  array(
	    'class'=>"uk-form-width-large"    
	  ))
	  ?> 
	</div>
	
    <div class="uk-form-row">
	  <label class="uk-form-label"><?php echo Yii::t("default","SMTP port")?></label>  
	  <?php 
	  echo CHtml::textField('smtp_port',
	  Yii::app()->functions->getOptionAdmin('smtp_port'),
	  array(
	    'class'=>"uk-form-width-large"    
	  ))
	  ?> 
	</div>
 		
	<div class="uk-form-row">
	  <label class="uk-form-label"><?php echo Yii::t("default","Username")?></label>  
	  <?php 
	  echo CHtml::textField('smtp_username',
	  Yii::app()->functions->getOptionAdmin('smtp_username'),
	  array(
	    'class'=>"uk-form-width-large"    
	  ))
	  ?> 
	</div>
	
	<div class="uk-form-row">
	  <label class="uk-form-label"><?php echo Yii::t("default","Password")?></label>  
	  <?php 
	  echo CHtml::textField('smtp_password',
	  Yii::app()->functions->getOptionAdmin('smtp_password'),
	  array(
	    'class'=>"uk-form-width-large"    
	  ))
	  ?> 
	</div>
	
	<div class="uk-form-row">
	  <label class="uk-form-label"><?php echo Yii::t("default","SMTPSecure")?></label>  
	  <?php 
	  echo CHtml::dropDownList('smtp_secure',
	  getOptionA('smtp_secure')
	  ,array(
	    'tls'=>t("tls"),
	    'ssl'=>t("ssl"),
	  ));
	  ?> 
	</div>
	
	<p class="uk-text-danger uk-text-small"><?php echo t("Note: When using SMTP make sure the port number is open in your server")?>.<br/>
	<?php echo t("You can ask your hosting to open this for you")?>.
	</p>
   
	</li>
	
   <li class="<?php echo $email_provider=="mandrill"?"uk-active":'';?>">
   
   <div class="uk-form-row">
	<?php 
   echo CHtml::radioButton('email_provider',
   $email_provider=="mandrill"?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'mandrill'
   ));
   echo "<span>".t("Mandrill API")."</span>";
   ?>
   </div>
   
   <div class="uk-form-row">
	  <label class="uk-form-label"><?php echo Yii::t("default","API KEY")?></label>  
	  <?php 
	  echo CHtml::textField('mandrill_api_key',
	  Yii::app()->functions->getOptionAdmin('mandrill_api_key'),
	  array(
	    'class'=>"uk-form-width-large"    
	  ))
	  ?> 
	</div>
	
	<p>
	<?php echo t("Create your account")?> <a href="http://mandrill.com" target="_blank">http://mandrill.com</a>
	</p>
	
	</li>
	
   <li class="<?php echo $email_provider=="sendgrid"?"uk-active":'';?>">
   
   <div class="uk-form-row">
   <?php 
   echo CHtml::radioButton('email_provider',
   $email_provider=="sendgrid"?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'sendgrid'
   ));
   echo "<span>".t("SendGrid")."</span>";
   ?>
   </div>
   
   <div class="uk-form-row">
	  <label class="uk-form-label"><?php echo Yii::t("default","API KEY")?></label>  
	  <?php 
	  echo CHtml::textField('sendgrid_api_key',
	  getOptionA('sendgrid_api_key'),
	  array(
	    'class'=>"uk-form-width-large"    
	  ))
	  ?> 
	</div>
	
	<p>
	<?php echo t("Create your account")?> <a href="http://sendgrid.com/" target="_blank">http://sendgrid.com</a>
	</p>

   </li>
   
   <li class="<?php echo $email_provider=="mailjet"?"uk-active":'';?>">
   
   <div class="uk-form-row">
   <?php 
   echo CHtml::radioButton('email_provider',
   $email_provider=="mailjet"?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'mailjet'
   ));
   echo "<span>".t("MailJet")."</span>";
   ?>
   </div>
   
   <div class="uk-form-row">
	  <label class="uk-form-label"><?php echo Yii::t("default","API KEY")?></label>  
	  <?php 
	  echo CHtml::textField('mailjet_api_key',
	  getOptionA('mailjet_api_key'),
	  array(
	    'class'=>"uk-form-width-large"    
	  ))
	  ?> 
	</div>
	
	<div class="uk-form-row">
	  <label class="uk-form-label"><?php echo Yii::t("default","SECRET KEY")?></label>  
	  <?php 
	  echo CHtml::textField('mailjet_secret_key',
	  getOptionA('mailjet_secret_key'),
	  array(
	    'class'=>"uk-form-width-large"    
	  ))
	  ?> 
	</div>
	
	<p>
	<?php echo t("Create your account")?> <a href="http://mailjet.com/" target="_blank">http://mailjet.com</a>
	</p>
	
   </li>
   
   <li class="<?php echo $email_provider=="elasticemail"?"uk-active":'';?>">
   <div class="uk-form-row">
   <?php 
   echo CHtml::radioButton('email_provider',
   $email_provider=="elasticemail"?true:false
   ,array(
    'class'=>"icheck",
    'value'=>'elasticemail'
   ));
   echo "<span>".t("Elastic Email")."</span>";
   ?>
   </div>
   
   <div class="uk-form-row">
	  <label class="uk-form-label"><?php echo Yii::t("default","API KEY")?></label>  
	  <?php 
	  echo CHtml::textField('elastic_email_apikey',
	  getOptionA('elastic_email_apikey'),
	  array(
	    'class'=>"uk-form-width-large"    
	  ))
	  ?> 
	</div>
   
	<p>
	<?php echo t("Create your account")?> <a href="https://elasticemail.com/" target="_blank">https://elasticemail.com</a>
	</p>
	
   </li>
	
</ul>


<hr/>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Sender")?></label>  
  <?php 
  echo CHtml::textField('global_admin_sender_email',
  getOptionA('global_admin_sender_email'),
  array(
    'class'=>"uk-form-width-large"    
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled New Line")?></label>  
  <?php 
  echo CHtml::checkBox('email_dsiabled_auto_break',
  getOptionA('email_dsiabled_auto_break'),array(
    'value'=>1
  ));
  ?>   
  <p class="uk-text-muted">
  <?php echo t("This will remove the new line added in email content")?>.
  <br/>
  <?php echo t("tick this if your content is in html format");?>
  </p>
</div>

<div class="uk-form-row" style="margin-top:50px;padding-bottom:30px;">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
<a href="javascript:;" class="test-email uk-button"><?php echo t("Click here to send Test Email")?></a>
</div>


</form>