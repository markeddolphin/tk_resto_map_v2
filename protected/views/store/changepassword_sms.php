<?php
$this->renderPartial('/front/default-header',array(
   'h1'=>t("Change password"),   
));
?>

<div class="sections section-grey2 ">
  <div class="container">
   <div class="row">
     <div class="col-md-6 white_bg" style="margin-left:25%;padding:20px;">
     
	<?php
	echo CHtml::beginForm('','post',array(
	'id'=>"frm_ajax",
	'onsubmit'=>"return false;"	  
	)); 
	echo CHtml::hiddenField('token',$token);
	?> 	
	
	 <div class="form-group">
	    <label for="sms_code"><?php echo t("Enter verification code")?>:</label>	    
	    <?php 
	    echo CHtml::textField('sms_code','',array(
	      'class'=>"form-control",
	      'required'=>true,
	      'maxlength'=>6
	    ));
	    ?>
	  </div>
	  
	   <div class="form-group">
	    <label for="sms_code"><?php echo t("Your new password")?>:</label>	    
	    <?php 
	    echo CHtml::passwordField('new_password','',array(
	      'class'=>"form-control",
	      'required'=>true,
	      'autocomplete'=>"new_password",
	      'maxlength'=>50
	    ));
	    ?>
	  </div>
	  
	   <div class="form-group">
	    <label for="sms_code"><?php echo t("Confirm password")?>:</label>	    
	    <?php 
	    echo CHtml::passwordField('confirm_new_password','',array(
	      'class'=>"form-control",
	      'required'=>true,	    
	      'autocomplete'=>"confirm_new_password",
	      'maxlength'=>50
	    ));
	    ?>
	  </div>

	 <button type="submit" class="green-button medium"><?php echo t("Continue")?></button> 
     <?php echo CHtml::endForm(); ?>
     
     </div>
   </div>
  </div>
</div>  
 