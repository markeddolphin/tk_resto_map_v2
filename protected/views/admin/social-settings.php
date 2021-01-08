
<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','adminSocialSettings')?>


<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Default Share text")?></label>  
<?php 
echo CHtml::textArea('default_share_text',getOptionA('default_share_text'),array(
  'class'=>"uk-form-width-large"
))
?>
</div>
<p class="uk-text-muted" style="margin-left:200px;"><?php echo t("Available tags {merchant-name}")?></p>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Disabled Social Icon")?>?</label>  
<?php echo CHtml::checkBox('social_flag',yii::app()->functions->getOptionAdmin('social_flag'),
array('value'=>1,'class'=>"icheck"))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Disabled restaurant share")?>?</label>  
<?php echo CHtml::checkBox('admin_merchant_share',yii::app()->functions->getOptionAdmin('admin_merchant_share'),
array('value'=>1,'class'=>"icheck"))?>
</div>

<hr/>

<h2><?php echo Yii::t('default',"Facebook")?></h2>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Disabled Facebook Login")?>?</label>  
<?php echo CHtml::checkBox('fb_flag',yii::app()->functions->getOptionAdmin('fb_flag'),
array('value'=>1,'class'=>"icheck"))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"App ID")?></label>  
<?php echo CHtml::textField('fb_app_id',yii::app()->functions->getOptionAdmin('fb_app_id'),array(
'class'=>"uk-form-width-large"
))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"App Secret")?></label>  
<?php echo CHtml::textField('fb_app_secret',yii::app()->functions->getOptionAdmin('fb_app_secret') )?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Facebook Page URL")?></label>  
<?php echo CHtml::textField('admin_fb_page',yii::app()->functions->getOptionAdmin('admin_fb_page'),array(
'class'=>"uk-form-width-large"
))?>
</div>

<hr/>

<h2><?php echo Yii::t('default',"Twitter")?></h2>
<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Twitter Page URL")?></label>  
<?php echo CHtml::textField('admin_twitter_page',yii::app()->functions->getOptionAdmin('admin_twitter_page'),array(
'class'=>"uk-form-width-large"
))?>
</div>

<hr/>

<h2><?php echo Yii::t('default',"Google")?></h2>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Google Page URL")?></label>  
<?php echo CHtml::textField('admin_google_page',yii::app()->functions->getOptionAdmin('admin_google_page'),array(
'class'=>"uk-form-width-large"
))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Enabled Google Login")?>?</label>  
<?php echo CHtml::checkBox('google_login_enabled',yii::app()->functions->getOptionAdmin('google_login_enabled'),
array('value'=>2,'class'=>"icheck"))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Client ID")?></label>  
<?php echo CHtml::textField('google_client_id',
yii::app()->functions->getOptionAdmin('google_client_id'),array(
'class'=>"uk-form-width-large"
))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Client Secret")?></label>  
<?php echo CHtml::textField('google_client_secret',
yii::app()->functions->getOptionAdmin('google_client_secret'),array(
'class'=>"uk-form-width-large"
))?>
</div>

<?php 
$redirect_url=yii::app()->functions->getOptionAdmin('google_client_redirect_ulr');
if (empty($redirect_url)){
	$redirect_url=websiteUrl()."/store/GoogleLogin";
}
?>
<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Redirect Url")?></label>  
<?php echo CHtml::textField('google_client_redirect_ulr',
$redirect_url,array(
'class'=>"uk-form-width-large",
//'onclick'=>'this.blur();'
))?>
</div>
<p class="uk-text-muted uk-text-small">
<?php echo t("Redirect URL Must equal to")." ".websiteUrl()."/store/GoogleLogin"?><br>
<?php echo t("Set this url to your google developer settings")?>.
<a target="_blank" href="https://theonetechnologies.com/blog/post/how-to-get-google-app-client-id-and-client-secret">
<?php echo t('click here how to configure')?>
</a>
</p>

<hr/>

<h2><?php echo Yii::t('default',"Instagram")?></h2>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Instagram Page URL")?></label>  
<?php echo CHtml::textField('admin_intagram_page',getOptionA('admin_intagram_page'),array(
'class'=>"uk-form-width-large"
))?>
</div>

<hr/>


<h2><?php echo Yii::t('default',"Youtube Channel")?></h2>

<div class="uk-form-row">
<label class="uk-form-label"><?php echo Yii::t('default',"Youtube Channel URL")?></label>  
<?php echo CHtml::textField('admin_youtube_url',getOptionA('admin_youtube_url'),array(
'class'=>"uk-form-width-large"
))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>


</form>