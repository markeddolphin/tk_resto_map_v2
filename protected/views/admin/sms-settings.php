<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','smsSettings')?>

<h3><?php echo t("Merchant SMS Settings")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Disabled SMS on merchant")?>?</label>
  <?php 
  echo CHtml::checkBox('mechant_sms_enabled',
  Yii::app()->functions->getOptionAdmin('mechant_sms_enabled')=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use admin SMS credits to send SMS")?>?</label>
  <?php 
  echo CHtml::checkBox('mechant_sms_purchase_disabled',
  Yii::app()->functions->getOptionAdmin('mechant_sms_purchase_disabled')=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>

<div style="height:10px;"></div>

<h3><?php echo t("SMS Gateway to use when sending SMS")?></h3>

<ul data-uk-tab="{connect:'#tab-content'}" class="uk-tab uk-active">
<li class="<?php echo $provider_selected=="twilio"?"uk-active":"";?>"><a href="#"><?php echo t("Twilio")?></a></li>
<li class="<?php echo $provider_selected=="nexmo"?"uk-active":"";?>" ><a href="#"><?php echo t("Nexmo")?></a></li>
<li class="<?php echo $provider_selected=="clickatell"?"uk-active":"";?>" ><a href="#"><?php echo t("Clickatell")?></a></li>
<li class="<?php echo $provider_selected=="bhashsms"?"uk-active":"";?>" ><a href="#"><?php echo t("BHASHSMS")?></a></li>
<li class="<?php echo $provider_selected=="smsglobal"?"uk-active":"";?>" ><a href="#"><?php echo t("SMSGlobal")?></a></li>
<li class="<?php echo $provider_selected=="swift"?"uk-active":"";?>" ><a href="#"><?php echo t("Swift SMS gateway")?></a></li>
<li class="<?php echo $provider_selected=="solutionsinfini"?"uk-active":"";?>" ><a href="#"><?php echo t("Solutionsinfini")?></a></li>
<li class="<?php echo $provider_selected=="plivo"?"uk-active":"";?>" ><a href="#"><?php echo t("plivo")?></a></li>
<li class="<?php echo $provider_selected=="msg91"?"uk-active":"";?>" ><a href="#"><?php echo t("msg91")?></a></li>
<li class="<?php echo $provider_selected=="spothit"?"uk-active":"";?>" ><a href="#"><?php echo t("Spot-hit")?></a></li>
</ul>

<ul class="uk-switcher uk-margin " id="tab-content">
<li class="uk-active">
<!--TWILIO-->

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")?></label>
  <?php
  echo CHtml::radioButton('sms_provider',
$provider_selected=="twilio"?true:false
,array(
'class'=>"icheck",
'value'=>"twilio"
));
  ?>
  </label>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender ID")?></label>
  <?php 
  echo CHtml::textField('sms_sender_id',
  Yii::app()->functions->getOptionAdmin('sms_sender_id')
  ,array(
    'class'=>"uk-form-width-large",
    //'data-validation'=>"required"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Account SID")?></label>
  <?php 
  echo CHtml::textField('sms_account_id',
  Yii::app()->functions->getOptionAdmin('sms_account_id')
  ,array(
    'class'=>"uk-form-width-large",
    //'data-validation'=>"required"
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","AUTH Token")?></label>
  <?php 
  echo CHtml::textField('sms_token',
  Yii::app()->functions->getOptionAdmin('sms_token')
  ,array(
    'class'=>"uk-form-width-large",
    //'data-validation'=>"required"
  ))
  ?>
</div>

<br/>
<?php echo t("get your account on")?> <a target="_blank"a href="https://www.twilio.com/">https://www.twilio.com/</a>


</li>

<li >
<!--NEXMO-->

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")?></label>
  <?php
echo CHtml::radioButton('sms_provider',
$provider_selected=="nexmo"?true:false
,array(
'class'=>"icheck",
'value'=>'nexmo'
));
  ?>
  </label>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender")?></label>
  <?php 
  echo CHtml::textField('nexmo_sender_id',
  Yii::app()->functions->getOptionAdmin('nexmo_sender_id')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Key")?></label>
  <?php 
  echo CHtml::textField('nexmo_key',
  Yii::app()->functions->getOptionAdmin('nexmo_key')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Secret")?></label>
  <?php 
  echo CHtml::textField('nexmo_secret',
  Yii::app()->functions->getOptionAdmin('nexmo_secret')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use CURL")?>?</label>
  <?php 
  echo CHtml::checkBox('nexmo_use_curl',
  Yii::app()->functions->getOptionAdmin('nexmo_use_curl')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use Unicode")?>?</label>
  <?php 
  echo CHtml::checkBox('nexmo_use_unicode',
  Yii::app()->functions->getOptionAdmin('nexmo_use_unicode')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>

<br/>
<?php echo t("get your account on")?> <a target="_blank"a href="https://www.nexmo.com/">https://www.nexmo.com/</a>



</li>

<li >
<!--Clickatell-->

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")?></label>
  <?php
  echo CHtml::radioButton('sms_provider',
$provider_selected=="clickatell"?true:false
,array(
'class'=>"icheck",
'value'=>'clickatell'
));
  ?>
  </label>
</div>

<!--<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","User")?></label>
  <?php 
  echo CHtml::textField('clickatel_user',
  Yii::app()->functions->getOptionAdmin('clickatel_user')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Password")?></label>
  <?php 
  echo CHtml::textField('clickatel_password',
  Yii::app()->functions->getOptionAdmin('clickatel_password')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender")?></label>
  <?php 
  echo CHtml::textField('clickatel_sender',
  Yii::app()->functions->getOptionAdmin('clickatel_sender')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>
-->
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","API Key")?></label>
  <?php 
  echo CHtml::textField('clickatel_api_key',
  Yii::app()->functions->getOptionAdmin('clickatel_api_key')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use CURL")?>?</label>
  <?php 
  echo CHtml::checkBox('clickatel_use_curl',
  Yii::app()->functions->getOptionAdmin('clickatel_use_curl')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use Unicode")?>?</label>
  <?php 
  echo CHtml::checkBox('clickatel_use_unicode',
  Yii::app()->functions->getOptionAdmin('clickatel_use_unicode')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>

<br/>
<?php echo t("get your account on")?> <a target="_blank"a href="https://www.clickatell.com/">https://www.clickatell.com/</a>


</li>

<li >
<!--BHASHSMS-->

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")?></label>
  <?php
  echo CHtml::radioButton('sms_provider',
$provider_selected=="bhashsms"?true:false
,array(
'class'=>"icheck",
'value'=>'bhashsms'
));
  ?>
  </label>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","User")?></label>
  <?php 
  echo CHtml::textField('bhashsms_user',
  Yii::app()->functions->getOptionAdmin('bhashsms_user')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Password")?></label>
  <?php 
  echo CHtml::textField('bhashsms_pass',
  Yii::app()->functions->getOptionAdmin('bhashsms_pass')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender ID")?></label>
  <?php 
  echo CHtml::textField('bhashsms_senderid',
  Yii::app()->functions->getOptionAdmin('bhashsms_senderid')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","SMS Type")?></label>
  <?php 
  echo CHtml::dropDownList('bhashsms_smstype',Yii::app()->functions->getOptionAdmin('bhashsms_smstype'),array(
    'normal'=>t("normal"),
    'flash'=>t("flash"),
    'unicode'=>t("unicode"),
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Priority")?></label>
  <?php 
  echo CHtml::dropDownList('bhashsms_priority',Yii::app()->functions->getOptionAdmin('bhashsms_priority'),array(
    'ndnd'=>t("ndnd"),
    'dnd'=>t("dnd")    
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use CURL")?>?</label>
  <?php 
  echo CHtml::checkBox('bhashsms_use_curl',
  Yii::app()->functions->getOptionAdmin('bhashsms_use_curl')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>


</li>

<li >
<!--SMSGlobal-->

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")?></label>
  <?php
  echo CHtml::radioButton('sms_provider',
$provider_selected=="smsglobal"?true:false
,array(
'class'=>"icheck",
'value'=>'smsglobal'
));
  ?>
  </label>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender ID")?></label>
  <?php 
  echo CHtml::textField('smsglobal_senderid',
  Yii::app()->functions->getOptionAdmin('smsglobal_senderid')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","API username")?></label>
  <?php 
  echo CHtml::textField('smsglobal_username',
  Yii::app()->functions->getOptionAdmin('smsglobal_username')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","API password")?></label>
  <?php 
  echo CHtml::textField('smsglobal_password',
  Yii::app()->functions->getOptionAdmin('smsglobal_password')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<br/>
<?php echo t("get your account on")?> <a target="_blank"a href="https://www.smsglobal.com/">https://www.smsglobal.com/</a>



</li>

<li >
<!--Swift SMS gateway-->

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")?></label>
  <?php
  echo CHtml::radioButton('sms_provider',
$provider_selected=="swift"?true:false
,array(
'class'=>"icheck",
'value'=>'swift'
));
  ?>
  </label>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Account Key")?></label>
  <?php 
  echo CHtml::textField('swift_accountkey',
  Yii::app()->functions->getOptionAdmin('swift_accountkey')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use CURL")?>?</label>
  <?php 
  echo CHtml::checkBox('swift_usecurl',
  Yii::app()->functions->getOptionAdmin('swift_usecurl')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>

<br/>
<?php echo t("get your account on")?> <a target="_blank"a href="http://smsgateway.ca">http://smsgateway.ca</a>


</li>

<li >
<!--Solutionsinfini-->

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")?></label>
  <?php
echo CHtml::radioButton('sms_provider',
$provider_selected=="solutionsinfini"?true:false
,array(
'class'=>"icheck",
'value'=>'solutionsinfini'
));
  ?>
  </label>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","API Key")?></label>
  <?php 
  echo CHtml::textField('solutionsinfini_apikey',
  Yii::app()->functions->getOptionAdmin('solutionsinfini_apikey')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender ID")?></label>
  <?php 
  echo CHtml::textField('solutionsinfini_sender',
  Yii::app()->functions->getOptionAdmin('solutionsinfini_sender')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use CURL")?>?</label>
  <?php 
  echo CHtml::checkBox('solutionsinfini_usecurl',
  Yii::app()->functions->getOptionAdmin('solutionsinfini_usecurl')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use Unicode")?>?</label>
  <?php 
  echo CHtml::checkBox('solutionsinfini_useunicode',
  Yii::app()->functions->getOptionAdmin('solutionsinfini_useunicode')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>

<br/>
<?php echo t("get your account on")?> <a target="_blank"a href="http://solutionsinfini.com/">http://solutionsinfini.com/</a>



</li>

<li >
<!--plivo-->
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")?></label>
  <?php
  echo CHtml::radioButton('sms_provider',
$provider_selected=="plivo"?true:false
,array(
'class'=>"icheck",
'value'=>'plivo'
));
  ?>
  </label>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Auth ID")?></label>
  <?php 
  echo CHtml::textField('plivo_auth_id',
  Yii::app()->functions->getOptionAdmin('plivo_auth_id')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Auth Token")?></label>
  <?php 
  echo CHtml::textField('plivo_auth_token',
  Yii::app()->functions->getOptionAdmin('plivo_auth_token')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender Number")?></label>
  <?php 
  echo CHtml::textField('plivo_sender_number',
  Yii::app()->functions->getOptionAdmin('plivo_sender_number')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>


</li>

<li >
<!--msg91-->
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")?></label>
  <?php
  echo CHtml::radioButton('sms_provider',
$provider_selected=="msg91"?true:false
,array(
'class'=>"icheck",
'value'=>'msg91'
));
  ?>
  </label>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Auth Key")?></label>
  <?php 
  echo CHtml::textField('msg91_authkey',
  Yii::app()->functions->getOptionAdmin('msg91_authkey')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender ID")?></label>
  <?php 
  echo CHtml::textField('msg91_senderid',
  Yii::app()->functions->getOptionAdmin('msg91_senderid')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Route")?></label>
  <?php 
  echo CHtml::dropDownList('msg91_route',
  getOptionA('msg91_route'),array(
    'default'=>"Default",
    1=>t("Promotional"),
    4=>t("Transactional SMS"),
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Send unicode message")?></label>
  <?php 
  echo CHtml::checkBox('msg91_unicode',
  getOptionA('msg91_unicode')==1?true:false
  ,array(
    'value'=>1
  ))
  ?>
</div>

<p>
<?php echo t("Your delivery report ulr is")?>:
<b><?php echo websiteUrl()."/msg91DeliveryReport"?></b>
 </p>


</li>

<li >
<!--Spot-hit-->
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")?></label>
  <?php
  echo CHtml::radioButton('sms_provider',
$provider_selected=="spothit"?true:false
,array(
'class'=>"icheck",
'value'=>'spothit'
));
  ?>
  </label>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","API Key")?></label>
  <?php 
  echo CHtml::textField('spothit_apikey',
  Yii::app()->functions->getOptionAdmin('spothit_apikey')
  ,array(
    'class'=>"uk-form-width-large"    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","SMS Type")?></label>
  <?php 
  echo CHtml::dropDownList('spothit_sms_type',Yii::app()->functions->getOptionAdmin('spothit_sms_type'),array(
    'premium'=>t("premium"),
    'lowcost'=>t("lowcost"),    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Sender")?></label>
  <?php 
  echo CHtml::textField('spothit_sender',
  Yii::app()->functions->getOptionAdmin('spothit_sender')
  ,array(
    'class'=>"uk-form-width-large",
    'placeholder'=>t("Optional (only Premium SMS)")
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","truncated")?></label>
  <?php 
  echo CHtml::checkBox('spothit_truncated',
  Yii::app()->functions->getOptionAdmin('spothit_truncated')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?>
  <span><?php echo t("Optional if you want to truncates the message to 160 characters")?></span>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Use CURL")?>?</label>
  <?php 
  echo CHtml::checkBox('spothit_use_curl',
  Yii::app()->functions->getOptionAdmin('spothit_use_curl')==1?true:false
  ,array(
    'value'=>1,
    'class'=>"icheck"
  ))
  ?> 
</div>


</li>


</ul>


<div class="uk-form-row" style="margin-top:20px;">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
<a href="javascript:;" class="uk-button test-sms"><?php echo t("Test SMS")?></a>
</div>


</form>