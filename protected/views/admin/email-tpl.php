<form class="uk-form uk-form-horizontal admin-settings-page forms" id="forms">
<?php 
echo CHtml::hiddenField('action','emailTplSettings');
?>

<?php 
$email_tpl_activation=Yii::app()->functions->getOptionAdmin('email_tpl_activation');
if (empty($email_tpl_activation)){	
	$email_tpl_activation=EmailTPL::merchantActivationCodePlain();
}

$email_tpl_forgot=Yii::app()->functions->getOptionAdmin('email_tpl_forgot');
if (empty($email_tpl_forgot)){		
	$email_tpl_forgot=EmailTPL::merchantForgotPassPlain();
}
?>


<div class="uk-form-row">
  <h3><?php echo t("customer welcome email template")?></h3>
  
<div class="uk-form-row">
<?php echo CHtml::textField('email_tpl_customer_subject',
getOptionA('email_tpl_customer_subject'),array(
  'class'=>"uk-form-width-large",
  "placeholder"=>t("Email Subject")
))?>
</div>
  
  <?php  
  echo CHtml::textArea('email_tpl_customer_reg',
  getOptionA('email_tpl_customer_reg'),
  array(
    'class'=>"big-textarea"    
  ))
  ?> 
</div>

<p style="margin:0;"><?php echo t("Available Tags")?>:</p>
<ul>
 <li><?php echo t("{website_name}")?></li>
 <li><?php echo t("{client_name}")?></li>
 <li><?php echo t("{email_address}")?></li> 
</ul>

<hr/>

<div class="uk-form-row">
  <h3><?php echo t("merchant activation email template")?></h3>
  <?php 
  echo CHtml::textArea('email_tpl_activation',
  $email_tpl_activation,
  array(
    'class'=>"big-textarea"    
  ))
  ?> 
</div>

<p style="margin:0;"><?php echo t("Available Tags")?>:</p>
<ul>
 <li><?php echo t("{restaurant_name}")?></li>
 <li><?php echo t("{activation_key}")?></li>
 <li><?php echo t("{website_title}")?></li>
 <li><?php echo t("{website_url}")?></li>
</ul>

<hr/>

<div class="uk-form-row">
  <h3><?php echo t("merchant forgot password email template")?></h3>
  <?php 
  echo CHtml::textArea('email_tpl_forgot',
  $email_tpl_forgot,
  array(
    'class'=>"big-textarea"    
  ))
  ?> 
</div>

<p style="margin:0;"><?php echo t("Available Tags")?>:</p>
<ul>
 <li><?php echo t("{restaurant_name}")?></li>
 <li><?php echo t("{website_title}")?></li>
 <li><?php echo t("{verification_code}")?></li>
</ul>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>