<?php
$data=$_GET;
//dump($data);
$email_content=EmailTPL::merchantChangeStatus();
?>

<Div style="width:550px;padding:2px 20px;padding-bottom:20px;">

<form class="uk-form uk-form-horizontal frm-sent-sms" id="frm-sent-sms">
<?php echo CHtml::hiddenField('action','SendTestSMS')?>

<h2><?php echo t("Send Test SMS")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Mobile Number")?></label>
  <?php 
  echo CHtml::textField('mobile',
  ''  
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>
<p style="margin:0;" class="uk-text-small uk-text-muted"><?php echo t("Include mobile country code eg +1")?></p>
<p style="margin:0;" class="uk-text-small uk-text-muted"><?php echo t("note: before making a test make sure you saved your sms settings")?></p>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Submit")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>

</Div>
<script type="text/javascript">
jQuery(document).ready(function() {
	
$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm-sent-sms',    
    onError : function() {      
    },
    onSuccess : function() {     
      form_submit('frm-sent-sms');
      return false;
    }  
});
	
});
</script>