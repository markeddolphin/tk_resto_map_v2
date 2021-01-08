<?php
$data=$_GET;
//dump($data);
$email_content=EmailTPL::merchantChangeStatus();
?>

<Div style="width:550px;padding:2px 20px;">

<form class="uk-form uk-form-horizontal frm-sent-email" id="frm-sent-email">
<?php echo CHtml::hiddenField('action','sendEmailToMerchant')?>
<?php echo CHtml::hiddenField('id',isset($data['id'])?$data['id']:'')?>

<h2><?php echo t("Send email to merchant")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Subject")?></label>
  <?php 
  echo CHtml::textField('subject',
  t("Your merchant status")  
  ,array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>

<div class="uk-form-row"> 
  <?php 
  echo CHtml::textArea('email_content',
  $email_content,
  array(
    'class'=>"big-textarea"    
  ))
  ?> 
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Send Email")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

<p><?php echo t("Available Tags")?></p>
<ul>
 <li>{owner_name}</li>
 <li>{restaurant_name}</li> 
 <li>{website_title}</li>
 <li>{status}</li>
</ul>

</form>

</Div>
<script type="text/javascript">
jQuery(document).ready(function() {
	if ( $(".big-textarea").exists() ){
	   	$(".big-textarea").jqte();
	}
	

$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm-sent-email',    
    onError : function() {      
    },
    onSuccess : function() {     
      form_submit('frm-sent-email');
      return false;
    }  
});
	
});
</script>

