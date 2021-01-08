<div style="padding:20px 25px;">
<form class="uk-form uk-form-horizontal admin-settings-page forms" id="forms-email" >
<?php echo CHtml::hiddenField('action','sendTestEmail')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Email Address")?></label>  
  <?php 
  echo CHtml::textField('email',
  '',
  array(
    'class'=>"uk-form-width-large"    
  ))
  ?> 
</div>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Send")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {	
$.validate({ 	
	language : jsLanguageValidator,
    form : '#forms-email',    
    onError : function() {      
    },
    onSuccess : function() {     
      form_submit('forms-email');
      return false;
    }  
});
});
</script>